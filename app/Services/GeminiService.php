<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiService
{
    protected $apiKey;
    protected $modelId;

    public function __construct()
    {
        $this->apiKey = env('GOOGLE_API_KEY');
        $this->modelId = env('GEMINI_MODEL_ID', 'gemini-pro');

        if (empty($this->apiKey)) {
            throw new \RuntimeException('Google API Key is not configured');
        }
    }

    public function analyzeText($text, $prompt)
    {
        try {
            $formattedPrompt = $this->formatPrompt($text, $prompt);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->modelId}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $formattedPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API request failed: ' . $response->body());
            }

            $data = $response->json();
            if (empty($data['candidates'][0]['content']['parts'][0]['text'])) {
                throw new \Exception('No text returned from Gemini');
            }

            return $data['candidates'][0]['content']['parts'][0]['text'];

        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            throw new \Exception('Failed to get response from Gemini API: ' . $e->getMessage());
        }
    }

    public function analyzeFile($filePath, $prompt)
    {
        try {
            if (!Storage::disk('public')->exists($filePath)) {
                throw new \Exception("File not found at path: " . $filePath);
            }

            $fileContent = Storage::disk('public')->get($filePath);
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $mimeType = $this->getMimeType($extension);
            $base64Content = base64_encode($fileContent);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->modelId}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $base64Content
                                ]
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API request failed: ' . $response->body());
            }

            $data = $response->json();
            if (empty($data['candidates'][0]['content']['parts'][0]['text'])) {
                throw new \Exception('No text returned from Gemini');
            }

            return $data['candidates'][0]['content']['parts'][0]['text'];

        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            throw new \Exception('Failed to analyze file with Gemini API: ' . $e->getMessage());
        }
    }

    public function analyzeMultipleFiles(array $filePaths, $prompt)
    {
        try {
            $parts = [
                ['text' => $prompt]
            ];

            foreach ($filePaths as $filePath) {
                if (!Storage::disk('public')->exists($filePath)) {
                    throw new \Exception("File not found at path: " . $filePath);
                }

                $fileContent = Storage::disk('public')->get($filePath);
                $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                $mimeType = $this->getMimeType($extension);
                $base64Content = base64_encode($fileContent);

                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data' => $base64Content
                    ]
                ];
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->modelId}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => $parts
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 1024,
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini API request failed: ' . $response->body());
            }

            $data = $response->json();
            if (empty($data['candidates'][0]['content']['parts'][0]['text'])) {
                throw new \Exception('No text returned from Gemini');
            }

            return $data['candidates'][0]['content']['parts'][0]['text'];

        } catch (\Exception $e) {
            Log::error('Gemini API Error: ' . $e->getMessage());
            throw new \Exception('Failed to analyze files with Gemini API: ' . $e->getMessage());
        }
    }

    private function formatPrompt($text, $prompt)
    {
        return <<<PROMPT
Context:
{$text}

Task:
{$prompt}

Please provide a detailed and accurate response based on the context above.
PROMPT;
    }

    private function getMimeType($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'txt' => 'text/plain',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}
