<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('GEMINI_API_KEY'));
    }

    /**
     * Generate MCQ and Short Questions from extracted text
     */
    public function generateQuestions(string $text, string $language = 'bangla', int $mcqCount = 20, int $shortCount = 5): array
    {
        // Trim text to avoid token limits (approx 3000 chars per request)
        $chunks = $this->chunkText($text, 3000);
        $allQuestions = [];

        foreach ($chunks as $index => $chunk) {
            try {
                $questions = $this->callGemini($chunk, $language, $mcqCount, $shortCount);
                $allQuestions = array_merge($allQuestions, $questions);

                // Limit total questions
                if (count($allQuestions) >= ($mcqCount + $shortCount) * 2) {
                    break;
                }

                // Small delay to avoid rate limiting
                if ($index < count($chunks) - 1) {
                    sleep(1);
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Error: ' . $e->getMessage());
                continue;
            }
        }

        return $allQuestions;
    }

    private function callGemini(string $text, string $language, int $mcqCount, int $shortCount): array
    {
        $langInstruction = $language === 'bangla'
            ? 'সকল প্রশ্ন ও উত্তর বাংলায় লিখুন।'
            : 'Write all questions and answers in English.';

        $prompt = <<<PROMPT
নিচের টেক্সট থেকে প্রশ্ন তৈরি করুন।

{$langInstruction}

টেক্সট:
{$text}

নির্দেশনা:
১. {$mcqCount}টি MCQ (বহুনির্বাচনী) প্রশ্ন তৈরি করুন। প্রতিটিতে ৪টি অপশন (ক, খ, গ, ঘ) এবং সঠিক উত্তর নির্দেশ করুন।
২. {$shortCount}টি সংক্ষিপ্ত প্রশ্ন (short question) তৈরি করুন সঠিক উত্তরসহ।
৩. শুধুমাত্র নিচের JSON format এ উত্তর দিন। অন্য কোনো text লিখবেন না।

JSON Format:
{
  "questions": [
    {
      "type": "mcq",
      "question": "প্রশ্নের টেক্সট",
      "options": {
        "a": "প্রথম অপশন",
        "b": "দ্বিতীয় অপশন",
        "c": "তৃতীয় অপশন",
        "d": "চতুর্থ অপশন"
      },
      "correct_answer": "a",
      "explanation": "সংক্ষিপ্ত ব্যাখ্যা"
    },
    {
      "type": "short",
      "question": "সংক্ষিপ্ত প্রশ্ন",
      "correct_answer": "উত্তর",
      "explanation": ""
    }
  ]
}
PROMPT;

        $response = Http::timeout(60)->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature'     => 0.7,
                'maxOutputTokens' => 8192,
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gemini API error: ' . $response->status() . ' ' . $response->body());
        }

        $data = $response->json();
        $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        // Extract JSON from response
        return $this->parseJsonResponse($rawText);
    }

    private function parseJsonResponse(string $rawText): array
    {
        // Remove markdown code blocks if present
        $cleanText = preg_replace('/```json\s*/i', '', $rawText);
        $cleanText = preg_replace('/```\s*/i', '', $cleanText);
        $cleanText = trim($cleanText);

        // Find JSON block
        $start = strpos($cleanText, '{');
        $end   = strrpos($cleanText, '}');

        if ($start === false || $end === false) {
            Log::warning('Gemini: No valid JSON found in response', ['raw' => substr($rawText, 0, 500)]);
            return [];
        }

        $jsonStr  = substr($cleanText, $start, $end - $start + 1);
        $decoded  = json_decode($jsonStr, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Gemini: JSON parse error', ['error' => json_last_error_msg(), 'json' => substr($jsonStr, 0, 300)]);
            return [];
        }

        return $decoded['questions'] ?? [];
    }

    private function chunkText(string $text, int $chunkSize = 3000): array
    {
        $text   = trim(preg_replace('/\s+/', ' ', $text));
        $chunks = [];
        $length = strlen($text);

        for ($i = 0; $i < $length; $i += $chunkSize) {
            $chunks[] = substr($text, $i, $chunkSize);
        }

        return $chunks ?: [$text];
    }

    /**
     * Perform high-accuracy OCR on an image file using Gemini Vision
     */
    public function extractTextFromImage(string $imagePath, string $mimeType): string
    {
        if (!file_exists($imagePath)) {
            throw new \Exception('ইমেজ ফাইলটি পাওয়া যায়নি।');
        }

        $imageData = base64_encode(file_get_contents($imagePath));

        $prompt = "Please extract all text from this image exactly as written. "
                . "Support Bengali and English text. Output ONLY the clean extracted text. "
                . "Do not write any markdown code blocks, intro, or outro text.";

        $response = Http::timeout(60)->post($this->apiUrl . '?key=' . $this->apiKey, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1,
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gemini Vision OCR Error: ' . $response->status() . ' ' . $response->body());
        }

        $data = $response->json();
        return trim($data['candidates'][0]['content']['parts'][0]['text'] ?? '');
    }
}
