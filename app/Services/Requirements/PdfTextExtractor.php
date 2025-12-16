<?php

namespace App\Services\Requirements;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class PdfTextExtractor
{
    private Parser $parser;

    public function __construct()
    {
        $this->parser = new Parser;
    }

    /**
     * Extract text content from a PDF file
     *
     * @throws \Exception
     */
    public function extractTextFromUploadedFile(UploadedFile $file): string
    {
        try {
            // Read the file content
            $content = $file->get();

            // Parse the PDF
            $pdf = $this->parser->parseContent($content);

            // Extract all text
            $text = $pdf->getText();

            // Clean up the text (remove extra whitespace, normalize line breaks)
            $text = $this->cleanText($text);

            Log::info('PDF text extraction successful', [
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'extracted_length' => strlen($text),
            ]);

            return $text;

        } catch (\Exception $e) {
            Log::error('PDF text extraction failed', [
                'original_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to extract text from PDF: '.$e->getMessage());
        }
    }

    /**
     * Extract text from a file path
     *
     * @throws \Exception
     */
    public function extractTextFromPath(string $filePath): string
    {
        try {
            $pdf = $this->parser->parseFile($filePath);
            $text = $pdf->getText();

            return $this->cleanText($text);

        } catch (\Exception $e) {
            Log::error('PDF text extraction failed from path', [
                'file_path' => $filePath,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to extract text from PDF: '.$e->getMessage());
        }
    }

    /**
     * Clean and normalize extracted text
     */
    private function cleanText(string $text): string
    {
        // Remove excessive whitespace and normalize line breaks
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\n\s*\n/', "\n\n", $text);

        // Trim whitespace from the beginning and end
        $text = trim($text);

        return $text;
    }

    /**
     * Validate if the file appears to be a valid PDF with extractable text
     */
    public function validatePdf(UploadedFile $file): bool
    {
        try {
            $content = $file->get();
            $pdf = $this->parser->parseContent($content);

            // Try to extract some text to ensure it's readable
            $text = $pdf->getText();

            // Consider it valid if we can extract some text (at least 10 characters)
            return strlen(trim($text)) >= 10;

        } catch (\Exception $e) {
            Log::warning('PDF validation failed', [
                'original_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
