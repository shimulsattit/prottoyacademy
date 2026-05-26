<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Option;
use App\Models\PdfUpload;
use App\Models\Question;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfQuestionController extends Controller
{
    public function index()
    {
        $pdfs = PdfUpload::with('category')->latest()->paginate(15);
        return view('portal.pdf-questions.index', compact('pdfs'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->orWhereNotNull('parent_id')
            ->orderBy('name')->get();
        return view('portal.pdf-questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'pdf_file'    => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:20480', // 20MB max
        ]);

        try {
            $file         = $request->file('pdf_file');
            $originalName = $file->getClientOriginalName();
            $path         = $file->store('pdf-uploads', 'public');

            $pdf = PdfUpload::create([
                'admin_id'      => Auth::guard('admin')->id(),
                'category_id'   => $request->category_id,
                'title'         => $request->title,
                'file_path'     => $path,
                'original_name' => $originalName,
                'status'        => 'pending',
            ]);

            return redirect()->route('portal.pdf.show', $pdf->id)
                ->with('success', 'ফাইল আপলোড সফল হয়েছে! এখন টেক্সট এক্সট্রাক্ট করুন।');

        } catch (\Exception $e) {
            Log::error('File Upload Error: ' . $e->getMessage());
            return back()->with('error', 'ফাইল আপলোড ব্যর্থ হয়েছে: ' . $e->getMessage());
        }
    }

    public function show(PdfUpload $pdf)
    {
        $pdf->load('category');
        return view('portal.pdf-questions.show', compact('pdf'));
    }

    public function extractText(PdfUpload $pdf)
    {
        try {
            $pdf->update(['status' => 'processing']);

            $fullPath = Storage::disk('public')->path($pdf->file_path);

            if (!file_exists($fullPath)) {
                throw new \Exception('ফাইল পাওয়া যায়নি।');
            }

            $mimeType = Storage::disk('public')->mimeType($pdf->file_path);

            if (str_contains($mimeType, 'image/')) {
                // OCR Image via Gemini API
                $gemini = new GeminiService();
                $text   = $gemini->extractTextFromImage($fullPath, $mimeType);

                if (empty(trim($text))) {
                    throw new \Exception('ছবি থেকে কোনো লেখা উদ্ধার করা যায়নি। ছবি পরিবর্তন করুন।');
                }

                $pagesText = [
                    [
                        'page' => 1,
                        'text' => $text
                    ]
                ];

                $pdf->update([
                    'extracted_text' => json_encode($pagesText, JSON_UNESCAPED_UNICODE),
                    'status'         => 'pending',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'ছবি থেকে টেক্সট সফলভাবে বের করা হয়েছে!',
                    'pages'   => $pagesText,
                ]);
            } else {
                // Extract text page-by-page using smalot/pdfparser for PDFs
                $parser      = new \Smalot\PdfParser\Parser();
                $parsedPdf   = $parser->parseFile($fullPath);
                $pages       = $parsedPdf->getPages();
                
                $pagesText = [];
                foreach ($pages as $index => $page) {
                    $pagesText[] = [
                        'page' => $index + 1,
                        'text' => trim($page->getText())
                    ];
                }

                if (empty($pagesText)) {
                    throw new \Exception('PDF থেকে টেক্সট বের করা যায়নি। PDF টি image-based হতে পারে।');
                }

                $pdf->update([
                    'extracted_text' => json_encode($pagesText, JSON_UNESCAPED_UNICODE),
                    'status'         => 'pending',
                ]);

                $totalChars = collect($pagesText)->sum(function($p) { return strlen($p['text']); });

                return response()->json([
                    'success' => true,
                    'message' => 'টেক্সট সফলভাবে বের হয়েছে! ' . count($pagesText) . 'টি পেজ ও ' . number_format($totalChars) . ' অক্ষর পাওয়া গেছে।',
                    'pages'   => $pagesText,
                ]);
            }

        } catch (\Exception $e) {
            $pdf->update(['status' => 'failed', 'error_message' => Str::limit($e->getMessage(), 250)]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generate(Request $request, PdfUpload $pdf)
    {
        $request->validate([
            'page'        => 'required|integer|min:1',
            'mcq_count'   => 'required|integer|min:0|max:50',
            'short_count' => 'required|integer|min:0|max:50',
            'language'    => 'required|in:bangla,english',
        ]);

        try {
            if (empty($pdf->extracted_text)) {
                return response()->json(['success' => false, 'message' => 'আগে টেক্সট এক্সট্রাক্ট করুন।'], 400);
            }

            $pagesText = json_decode($pdf->extracted_text, true);
            if (!is_array($pagesText)) {
                // Fallback if old format
                $pagesText = [['page' => 1, 'text' => $pdf->extracted_text]];
            }

            $pageIndex = array_search($request->page, array_column($pagesText, 'page'));
            if ($pageIndex === false) {
                return response()->json(['success' => false, 'message' => 'পৃষ্ঠা নম্বর পাওয়া যায়নি।'], 400);
            }

            $pageText = $pagesText[$pageIndex]['text'];
            if (empty(trim($pageText))) {
                return response()->json(['success' => false, 'message' => 'এই পৃষ্ঠাটি সম্পূর্ণ খালি বা টেক্সটবিহীন।'], 400);
            }

            $pdf->update(['status' => 'processing']);

            $gemini   = new GeminiService();
            $questions = $gemini->generateQuestions(
                $pageText,
                $request->language,
                (int) $request->mcq_count,
                (int) $request->short_count
            );

            if (empty($questions)) {
                throw new \Exception('Gemini থেকে কোনো প্রশ্ন তৈরি হয়নি। আবার চেষ্টা করুন।');
            }

            // Append or replace questions for this specific page
            $generated = $pdf->generated_questions ?? [];
            if (!is_array($generated)) {
                $generated = [];
            }
            $generated[$request->page] = $questions;

            $pdf->update([
                'generated_questions' => $generated,
                'questions_generated' => collect($generated)->flatten(1)->count(),
                'status'              => 'done',
            ]);

            return response()->json([
                'success'   => true,
                'message'   => count($questions) . 'টি প্রশ্ন তৈরি হয়েছে!',
                'questions' => $questions,
            ]);

        } catch (\Exception $e) {
            $pdf->update(['status' => 'failed', 'error_message' => Str::limit($e->getMessage(), 250)]);
            Log::error('Question Generation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function preview(PdfUpload $pdf)
    {
        return redirect()->route('portal.pdf.show', $pdf->id);
    }

    public function saveQuestions(Request $request, PdfUpload $pdf)
    {
        $request->validate([
            'questions'   => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type'     => 'required|in:mcq,short',
            'page'                 => 'required|integer',
        ]);

        $adminId    = Auth::guard('admin')->id();
        $categoryId = $pdf->category_id;
        $saved      = 0;

        foreach ($request->questions as $q) {
            if (empty(trim($q['question']))) continue;

            try {
                $correctAnswerMap = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

                $question = Question::create([
                    'admin_id'      => $adminId,
                    'category_id'   => $categoryId,
                    'question_type' => $q['type'] === 'mcq' ? 'mcq' : 'written',
                    'question'      => $q['question'],
                    'slug'          => Str::slug(Str::limit($q['question'], 80)) . '-' . Str::random(6),
                    'correct_answer'=> $q['type'] === 'mcq'
                        ? ($correctAnswerMap[$q['correct_answer'] ?? 'a'] ?? 1)
                        : ($q['correct_answer'] ?? ''),
                    'content'       => $q['explanation'] ?? null,
                    'status'        => 1,
                ]);

                // Save MCQ options
                if ($q['type'] === 'mcq') {
                    Option::create([
                        'question_id'  => $question->id,
                        'option_one'   => $q['options']['a'] ?? null,
                        'option_two'   => $q['options']['b'] ?? null,
                        'option_three' => $q['options']['c'] ?? null,
                        'option_four'  => $q['options']['d'] ?? null,
                    ]);
                }

                $saved++;
            } catch (\Exception $e) {
                Log::error('Save Question Error: ' . $e->getMessage());
            }
        }

        // Clean up or clear generated questions for this specific page after successful save
        $generated = $pdf->generated_questions ?? [];
        if (isset($generated[$request->page])) {
            unset($generated[$request->page]);
        }

        $pdf->update([
            'generated_questions' => $generated,
            'questions_generated' => collect($generated)->flatten(1)->count(),
            'questions_saved'     => $pdf->questions_saved + $saved
        ]);

        return redirect()->route('portal.pdf.show', $pdf->id)
            ->with('success', "{$saved}টি প্রশ্ন সফলভাবে Question Bank এ সেভ হয়েছে!");
    }

    public function destroy(PdfUpload $pdf)
    {
        Storage::disk('public')->delete($pdf->file_path);
        $pdf->delete();
        return back()->with('success', 'মুছে ফেলা হয়েছে।');
    }
}
