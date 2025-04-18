<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PineconeService
{
    protected $baseUrl;
    protected $apiKey;
    protected $environment;
    protected $indexName;

    public function __construct()
    {
        $this->baseUrl = config('services.pinecone.base_url');
        $this->apiKey = config('services.pinecone.api_key');
        $this->environment = config('services.pinecone.environment');
        $this->indexName = config('services.pinecone.index_name');
    }

    /**
     * Upsert vectors to Pinecone
     *
     * @param string $namespace
     * @param array $vectors Array of vectors with metadata
     * @return array
     */
    public function upsertVectors(string $namespace, array $vectors): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/vectors/upsert", [
                'namespace' => $namespace,
                'vectors' => $vectors
            ]);

            if ($response->failed()) {
                Log::error('Pinecone upsert failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new \Exception('Failed to upsert vectors to Pinecone');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error upserting vectors to Pinecone: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Query vectors from Pinecone
     *
     * @param string $namespace
     * @param array $vector
     * @param int $topK
     * @param array $filter
     * @return array
     */
    public function queryVectors(string $namespace, array $vector, int $topK = 5, array $filter = []): array
    {
        try {
            $payload = [
                'namespace' => $namespace,
                'topK' => $topK,
                'includeMetadata' => true,
                'vector' => $vector
            ];

            if (!empty($filter)) {
                $payload['filter'] = $filter;
            }

            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/query", $payload);

            if ($response->failed()) {
                Log::error('Pinecone query failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new \Exception('Failed to query vectors from Pinecone');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error querying vectors from Pinecone: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete vectors from Pinecone
     *
     * @param string $namespace
     * @param array $ids
     * @return array
     */
    public function deleteVectors(string $namespace, array $ids): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/vectors/delete", [
                'namespace' => $namespace,
                'ids' => $ids
            ]);

            if ($response->failed()) {
                Log::error('Pinecone delete failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new \Exception('Failed to delete vectors from Pinecone');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error deleting vectors from Pinecone: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Describe index statistics
     *
     * @return array
     */
    public function describeIndexStats(): array
    {
        try {
            $response = Http::withHeaders([
                'Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/describe_index_stats");

            if ($response->failed()) {
                Log::error('Pinecone describe index stats failed', [
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                throw new \Exception('Failed to get index statistics from Pinecone');
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error getting index statistics from Pinecone: ' . $e->getMessage());
            throw $e;
        }
    }
}
