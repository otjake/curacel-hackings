<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Actions\DocumentContext\ExtractTextFromFile;
use App\Services\GptService;
// use App\Services\GeminiService;
use App\Models\DocumentContext;
use App\Jobs\ProcessDocumentChunk;

class DocumentContextController extends Controller
{
    protected $gptService;
    // protected $geminiService;

    public function __construct(GptService $gptService)
    {
        $this->gptService = $gptService;
        // $this->geminiService = $geminiService;
    }

    // public function __construct(GptService $gptService, GeminiService $geminiService)
    // {
    //     $this->gptService = $gptService;
    //     $this->geminiService = $geminiService;
    // }

    public function upload(Request $request)
    {
        Log::info('Uploading documents');
        $validator = Validator::make($request->all(), [
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|mimes:pdf|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $uploadedFiles = [];
        foreach ($request->file('documents') as $file) {
            $path = $file->store('documents/' . Str::random(10), 'public');

            // Save document information to database
            $document = DocumentContext::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'file_extract' => null
            ]);

            $uploadedFiles[] = [
                'id' => $document->id,
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'content' => null,
            ];
        }

        return response()->json([
            'message' => 'Documents uploaded successfully',
            'files' => $uploadedFiles,
        ]);
    }

    public function extractContent(Request $request)
    {
        try {
            $documentId = $request->input('document_id');
            // $content = $request->input('content');

            if (empty($documentId)) {
                return response()->json([
                    'error' => 'Document ID and content are required'
                ], 400);
            }

            $document = DocumentContext::find($documentId);

            if (!$document) {
                return response()->json([
                    'error' => 'Document not found'
                ], 404);
            }

            // Dispatch job to process document chunks
            // ProcessDocumentChunk::dispatch($documentId, $content);

            $extractedContent = $this->extractContentFromFile($document->file_path);

            $document->file_extract = $extractedContent;
            $document->save();

            return response()->json([
                'message' => 'Document extracted successfully',
                'extractedContent' => $extractedContent
            ]);
        } catch (\Exception $e) {
            Log::error('Error extracting document content: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing the document'
            ], 500);
        }
    }

    private function array_flatten($array)
    {
        $result = [];
        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->array_flatten($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }

    private function extractContentFromFile($path)
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $isPdf = strtolower($extension) === 'pdf';

        try {
            // Use Storage facade to get the file content
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("File not found at path: " . $path);
            }

            $textExtractInfo = ExtractTextFromFile::run($path, $isPdf);
            $textArray = [];

            // Process the extracted text information
            foreach ($textExtractInfo as $item) {
                $itemToAdd = is_string($item) ? [$item] : $item;
                $textArray = array_merge($textArray, $itemToAdd);
            }

            // Flatten and merge the text
            $mergedText = implode(' ', $this->array_flatten($textArray));

            Log::info('Extracted text: ' . $mergedText);
            return $mergedText;
        } catch (\Exception $e) {
            Log::error('Error extracting content: ' . $e->getMessage());
            throw new \Exception("Failed to extract content from file: " . $e->getMessage());
        }
    }

    public function process(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'required|integer|exists:document_contexts,id',
            'prompt' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the documents and their extracted content
            $documents = DocumentContext::whereIn('id', $request->document_ids)
                ->whereNotNull('file_extract')
                ->get();

            if ($documents->isEmpty()) {
                return response()->json([
                    'message' => 'No documents found with extracted content',
                    'errors' => ['documents' => ['Please ensure all documents have been processed for content extraction']]
                ], 422);
            }

            // Step 1: Get individual summaries for each document
            $documentSummaries = [];
            foreach ($documents as $document) {
                if(!$document->file_extract) {
                    Log::warning('Document with name' . $document->file_name . ' has no extracted content');
                    return response()->json([
                        'message' => 'Document with name' . $document->file_name . ' has no extracted content',
                        'errors' => ['documents' => ['Please ensure all documents have been processed for content extraction']]
                    ], 422);
                }
                // try {
                //     $summary = $this->gptService->getGptAnalysis(
                //         $document->file_extract,
                //         "Please provide a summary of this document that is relevant to the following question: " . $request->prompt
                //     );
                // } catch (\Exception $e) {
                //     Log::warning('GPT failed, falling back to Gemini: ' . $e->getMessage());
                //     // $summary = $this->geminiService->analyzeText(
                //     //     $document->file_extract,
                //     //     "Please provide a summary of this document that is relevant to the following question: " . $request->prompt
                //     // );
                // }
                $documentSummaries[] = $document->file_name . " - " . $document->file_extract;
                Log::info('Document summary: ' . $document->file_name . " - " . $document->file_extract);
            }

            // Step 2: Analyze all summaries together to provide a final answer
            try {
                $finalAnalysis = $this->gptService->getGptAnalysis(
                    implode("\n\n", $documentSummaries),
                    "Based on the following document extracts, please provide a comprehensive answer to the following question: " . $request->prompt
                );
            } catch (\Exception $e) {
                Log::warning('GPT failed for final analysis, falling back to Gemini: ' . $e->getMessage());
                // $finalAnalysis = $this->geminiService->analyzeText(
                //     implode("\n\n", $documentSummaries),
                //     "Based on the following document summaries, please provide a comprehensive answer to: " . $request->prompt
                // );
            }

            return response()->json([
                'message' => 'Analysis completed successfully',
                'result' => $finalAnalysis,
                'document_summaries' => $documentSummaries
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing documents: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to process documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function index(Request $request)
    {
        try {
            $documents = $request->input('documents', []);
            $prompt = $request->input('prompt');

            if (empty($documents) || empty($prompt)) {
                return response()->json([
                    'error' => 'Documents and prompt are required'
                ], 400);
            }

            $analysis = $this->gptService->analyzeDocuments($documents, $prompt);

            return response()->json([
                'analysis' => $analysis
            ]);
        } catch (\Exception $e) {
            Log::error('Error analyzing documents: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while analyzing the documents'
            ], 500);
        }
    }
}
