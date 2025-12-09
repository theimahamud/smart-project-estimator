<?php

namespace App\Services\Requirements;

use App\DTO\RequirementsDTO;
use App\Enums\RequirementsQuality;

class RequirementsPreprocessor
{
    private const MAX_REQUIREMENTS_LENGTH = 10000; // Characters

    private const MIN_REQUIREMENTS_LENGTH = 50; // Characters

    public function process(RequirementsDTO $requirements): RequirementsDTO
    {
        $processedText = $this->cleanText($requirements->rawText);
        $processedText = $this->truncateIfNeeded($processedText);
        $processedText = $this->enhanceStructure($processedText);

        // Adjust quality based on processed content
        $quality = $this->assessQuality($processedText, $requirements->quality);

        return new RequirementsDTO(
            rawText: $processedText,
            quality: $quality
        );
    }

    private function cleanText(string $text): string
    {
        // Remove excessive whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        // Remove multiple newlines
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        // Trim whitespace
        $text = trim($text);

        // Remove common PDF artifacts
        $text = preg_replace('/\f|\r/', '', $text); // Form feed and carriage return
        $text = preg_replace('/\x{00A0}/u', ' ', $text); // Non-breaking spaces

        return $text;
    }

    private function truncateIfNeeded(string $text): string
    {
        if (strlen($text) <= self::MAX_REQUIREMENTS_LENGTH) {
            return $text;
        }

        // Truncate at sentence boundary if possible
        $truncated = substr($text, 0, self::MAX_REQUIREMENTS_LENGTH);
        $lastSentence = strrpos($truncated, '.');

        if ($lastSentence !== false && $lastSentence > self::MAX_REQUIREMENTS_LENGTH * 0.8) {
            return substr($truncated, 0, $lastSentence + 1);
        }

        // Truncate at word boundary
        $lastSpace = strrpos($truncated, ' ');
        if ($lastSpace !== false) {
            return substr($truncated, 0, $lastSpace).'...';
        }

        return $truncated.'...';
    }

    private function enhanceStructure(string $text): string
    {
        // Add bullet points if the text lacks structure
        if (! $this->hasStructure($text)) {
            $sentences = preg_split('/\.\s+/', $text);
            $structuredText = '';

            foreach ($sentences as $sentence) {
                $sentence = trim($sentence);
                if (! empty($sentence)) {
                    $structuredText .= '• '.ucfirst($sentence);
                    if (! str_ends_with($sentence, '.')) {
                        $structuredText .= '.';
                    }
                    $structuredText .= "\n";
                }
            }

            return trim($structuredText);
        }

        return $text;
    }

    private function hasStructure(string $text): bool
    {
        // Check for common structure indicators
        $indicators = ['•', '-', '*', '1.', '2.', '3.', 'Feature:', 'Requirement:'];

        foreach ($indicators as $indicator) {
            if (str_contains($text, $indicator)) {
                return true;
            }
        }

        return false;
    }

    private function assessQuality(string $text, RequirementsQuality $originalQuality): RequirementsQuality
    {
        $length = strlen($text);
        $wordCount = str_word_count($text);

        // Downgrade quality if text is too short
        if ($length < self::MIN_REQUIREMENTS_LENGTH || $wordCount < 10) {
            return RequirementsQuality::RoughIdea;
        }

        // Check for quality indicators
        $qualityIndicators = [
            'acceptance criteria',
            'user story',
            'business logic',
            'integration',
            'authentication',
            'authorization',
            'database',
            'api',
        ];

        $indicatorCount = 0;
        foreach ($qualityIndicators as $indicator) {
            if (str_contains(strtolower($text), $indicator)) {
                $indicatorCount++;
            }
        }

        // Upgrade quality if we find detailed requirements
        if ($indicatorCount >= 3 && $originalQuality === RequirementsQuality::RoughIdea) {
            return RequirementsQuality::Draft;
        }

        if ($indicatorCount >= 5 && $originalQuality !== RequirementsQuality::Final) {
            return RequirementsQuality::Final;
        }

        return $originalQuality;
    }

    public function extractKeywords(string $text): array
    {
        $text = strtolower($text);

        // Common project keywords to extract
        $keywords = [
            'authentication', 'authorization', 'login', 'register',
            'dashboard', 'admin', 'user management',
            'api', 'rest', 'graphql', 'websocket',
            'database', 'mysql', 'postgresql', 'mongodb',
            'payment', 'stripe', 'paypal', 'billing',
            'email', 'notification', 'sms',
            'file upload', 'image', 'document',
            'search', 'filter', 'pagination',
            'mobile', 'responsive', 'ios', 'android',
            'integration', 'third party', 'webhook',
        ];

        $foundKeywords = [];
        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                $foundKeywords[] = $keyword;
            }
        }

        return $foundKeywords;
    }
}
