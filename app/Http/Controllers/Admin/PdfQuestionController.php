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
            'pdf_file'    => 'required|file|mimes:pdf|max:20480', // 20MB max
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

            return redirect()->route('admin.pdf.show', $pdf->id)
                ->with('success', 'PDF আপলোড সফল হয়েছে! এখন টেক্সট এক্সট্রাক্ট করুন।');

        } catch (\Exception $e) {
            Log::error('PDF Upload Error: ' . $e->getMessage());
            return back()->with('error', 'PDF আপলোড ব্যর্থ হয়েছে: ' . $e->getMessage());
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
                throw new \Exception('PDF ফাইল পাওয়া যায়নি।');
            }

            // Extract text using smalot/pdfparser
            $parser      = new \Smalot\PdfParser\Parser();
            $parsedPdf   = $parser->parseFile($fullPath);
            $text        = $parsedPdf->getText();

            if (empty(trim($text))) {
                throw new \Exception('PDF থেকে টেক্সট বের করা যায়নি। PDF টি image-based হতে পারে।');
            }

            $pdf->update([
                'extracted_text' => $text,
                'status'         => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'টেক্সট সফলভাবে বের হয়েছে! ' . strlen($text) . ' অক্ষর পাওয়া গেছে।',
                'preview' => Str::limit($text, 300),
            ]);

        } catch (\Exception $e) {
            $pdf->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generate(Request $request, PdfUpload $pdf)
    {
        $request->validate([
            'mcq_count'   => 'required|integer|min:5|max:50',
            'short_count' => 'required|integer|min:0|max:20',
            'language'    => 'required|in:bangla,english',
        ]);

        try {
            if (empty($pdf->extracted_text)) {
                return response()->json(['success' => false, 'message' => 'আগে টেক্সট এক্সট্রাক্ট করুন।'], 400);
            }

            $pdf->update(['status' => 'processing']);

            $gemini   = new GeminiService();
            $questions = $gemini->generateQuestions(
                $pdf->extracted_text,
                $request->language,
                (int) $request->mcq_count,
                (int) $request->short_count
            );

            if (empty($questions)) {
                throw new \Exception('Gemini থেকে কোনো প্রশ্ন তৈরি হয়নি। আবার চেষ্টা করুন।');
            }

            $pdf->update([
                'generated_questions' => $questions,
                'questions_generated' => count($questions),
                'status'              => 'done',
            ]);

            return response()->json([
                'success'   => true,
                'message'   => count($questions) . 'টি প্রশ্ন তৈরি হয়েছে!',
                'count'     => count($questions),
                'redirect'  => route('admin.pdf.preview', $pdf->id),
            ]);

        } catch (\Exception $e) {
            $pdf->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            Log::error('Question Generation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function preview(PdfUpload $pdf)
    {
        $pdf->load('category');
        $questions = $pdf->generated_questions ?? [];
        return view('portal.pdf-questions.preview', compact('pdf', 'questions'));
    }

    public function saveQuestions(Request $request, PdfUpload $pdf)
    {
        $request->validate([
            'questions'   => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type'     => 'required|in:mcq,short',
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

        $pdf->update(['questions_saved' => $pdf->questions_saved + $saved]);

        return redirect()->route('admin.pdf.index')
            ->with('success', "{$saved}টি প্রশ্ন সফলভাবে Question Bank এ সেভ হয়েছে!");
    }

    public function destroy(PdfUpload $pdf)
    {
        Storage::disk('public')->delete($pdf->file_path);
        $pdf->delete();
        return back()->with('success', 'PDF মুছে ফেলা হয়েছে।');
    }
}
