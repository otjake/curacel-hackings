<?php

namespace App\Actions\DocumentContext;

use Aws\Credentials\Credentials;
use Aws\Textract\TextractClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use setasign\Fpdf\Fpdf;

class ExtractTextFromFile
{
    use AsAction;

    private TextractClient $textractClient;

    /**
     * Create the event listener.
     */
    public function __construct()
    {

        // Get credentials from config
        $awsKey = env('TEXTTRACT_ACCESS_KEY_ID');
        $awsSecret = env('TEXTTRACT_SECRET_ACCESS_KEY');
        $awsRegion = env('TEXTTRACT_DEFAULT_REGION');

        if (empty($awsKey) || empty($awsSecret)) {
            throw new \RuntimeException('AWS credentials are not properly configured');
        }

        $this->textractClient = new TextractClient([
            'credentials' => [
                'key'    => $awsKey,
                'secret' => $awsSecret,
            ],
            'version' => 'latest',
            'region' => $awsRegion,
            // 'http' => [
            //     'verify' => false // Only if you're having SSL verification issues
            // ]
        ]);
    }

    /**
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws FilterException
     * @throws PdfTypeException
     */
    public function handle($filePath, $isPdf)
    {
        if ($isPdf) {
            Log::info('Extracting text from PDF');
            return $this->extractTextFromPdf($filePath);
        } else {
            Log::info('Extracting text from Image');
            return $this->extractTextFromImage($filePath);
        }
    }


    /**
     * @param $filePath
     * @return array
     * @throws PdfParserException
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfTypeException
     * @throws PdfReaderException
     */
    public function extractTextFromPdf($filePath): array
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempFile, Storage::disk('public')->get($filePath));

        // Extract pages from the PDF
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($tempFile);

        $extractOutput = [];
        // Process each page
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            Log::info('Extracting text from PDF page: ' . $pageNo);
            // Create a new PDF document for the single page
            $pagePdf = new Fpdi();
            $pagePdf->AddPage();
            $pagePdf->setSourceFile($tempFile);
            $templateId = $pagePdf->importPage($pageNo);
            $pagePdf->useTemplate($templateId);

            // Save the single page PDF to a temporary file
            $singlePageFile = tempnam(sys_get_temp_dir(), 'page') . 'pdf';
            $pagePdf->Output($singlePageFile, 'F');
            // Read the single page PDF content
            $pageContent = file_get_contents($singlePageFile);

            // Send the single page content to AWS Textract
            if (empty($pageContent)) {
                logger()->debug("extractTextFromPdf Empty page content");
                continue;
            }
            $extractResult[] = $this->extractUsingFileBytes($pageContent);

            $extractOutput = array_merge($extractOutput, $extractResult);

            // Clean up the single page file
            unlink($singlePageFile);
            Log::info('Extracted text from PDF page: ' . $pageNo);
        }
        // Clean up the downloaded PDF file
        unlink($tempFile);
        Log::info('Extracted text from PDF', [$extractOutput]);
        return $extractOutput;
    }

    /**
     * @param string $filePath
     * @return array
     */
    public function extractTextFromImage(string $filePath): array
    {
        $content = Storage::disk('public')->get($filePath);

        return $this->extractUsingFileBytes($content);
    }

    /**
     * @param mixed $attachment
     * @param string $content
     * @return void
     */
    public function handleExtractOverS3(mixed $attachment, string $content): void
    {
        $filepath = 'temp/for-aws-extract/' . $attachment->file_name . '.' . $attachment->format;

        Storage::put(
            $filepath,
            $content
        );
        $imageUrl = Storage::disk('s3')->url($filepath);

        $result = $this->textractClient->detectDocumentText([
            'Document' => [
                'S3Object' => [
                    'Bucket' => env('AWS_BUCKET'),
                    'Name' => $filepath,
                ],
            ]
        ]);
    }

    /**
     * @param $pageContent
     * @return array
     */
    public function extractUsingFileBytes($pageContent): array
    {
        $extractOutput = [];

        $result = $this->textractClient->detectDocumentText([
            'Document' => [
                'Bytes' => $pageContent  // Send raw bytes directly
            ],
        ]);
        // Extract text from the result
        if (!empty($result['Blocks'])) {
            foreach ($result['Blocks'] as $block) {
                if ($block['BlockType'] == 'WORD') {
                    $extractOutput[] = $block['Text'];
                }
            }
        }
        return $extractOutput;
    }
}
