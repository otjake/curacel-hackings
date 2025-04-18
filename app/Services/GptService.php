<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GptService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1';
    protected $model = 'gpt-4.1-nano';
    protected $pineconeService;

    public function __construct(PineconeService $pineconeService)
    {
        $this->apiKey = config('services.openai.api_key');
        $this->pineconeService = $pineconeService;
    }

    /**
     * Analyze document content and answer questions
     *
     * @param array $documents Array of document contents
     * @param string $prompt User's question or request
     * @return string GPT's response
     */
    public function analyzeDocuments(array $documents, string $prompt): string
    {
        try {
            // Cache key for this analysis
            $cacheKey = 'document_analysis:' . md5(implode('', $documents) . $prompt);

            // Check cache first
            if ($cachedResult = Cache::get($cacheKey)) {
                return $cachedResult;
            }

            // Get embedding for the prompt
            $promptEmbedding = $this->getEmbedding($prompt);

            // Query Pinecone for relevant document chunks
            $relevantChunks = $this->pineconeService->queryVectors(
                'documents',
                $promptEmbedding,
                5, // top 5 most relevant chunks
                ['document_id' => array_keys($documents)]
            );

            // Prepare context from relevant chunks
            $context = $this->prepareContext($relevantChunks['matches']);

            // Get GPT analysis
            $analysis = $this->getGptAnalysis($context, $prompt);

            // Cache the result
            Cache::put($cacheKey, $analysis, now()->addHours(24));

            return $analysis;
        } catch (\Exception $e) {
            Log::error('Error in GPT analysis: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get embedding for text using OpenAI's API
     */
    private function getEmbedding(string $text): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/embeddings", [
            'model' => 'text-embedding-ada-002',
            'input' => $text
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to get embedding from OpenAI');
        }

        return $response->json()['data'][0]['embedding'];
    }

    /**
     * Prepare context from relevant document chunks
     */
    private function prepareContext(array $chunks): string
    {
        $context = '';
        foreach ($chunks as $chunk) {
            $context .= "Document {$chunk['metadata']['document_id']}:\n";
            $context .= $chunk['metadata']['content'] . "\n\n";
        }
        return trim($context);
    }

    /**
     * Get GPT analysis for the context and prompt
     */
    public function getGptAnalysis(string $context, string $prompt): string
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant that analyzes document content and provides accurate answers based on the provided context. Focus on the specific information relevant to the question.'
            ],
            [
                'role' => 'user',
                'content' => "Context:\n{$context}\n\nQuestion/Request: {$prompt}"
            ]
        ];

        Log::info('Sending request to GPT API: ' . json_encode($messages));

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/chat/completions", [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 1000,
        ]);

        if ($response->failed()) {
            Log::error('Failed to get response from GPT API: ' . $response->body());
            throw new \Exception('Failed to get response from GPT API');
        }

        return $response->json()['choices'][0]['message']['content'];
    }
}
