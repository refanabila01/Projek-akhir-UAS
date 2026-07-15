<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SentimentAnalyzer
{
    /**
     * Perform lexicon-based sentiment analysis on text.
     * Returns sentiment category and percentage distribution.
     */
    public function analyze(string $text): array
    {
        if (empty(trim($text))) {
            return [
                'sentiment' => 'Neutral',
                'score' => 0.0,
                'positive_percent' => 20,
                'neutral_percent' => 60,
                'negative_percent' => 20,
                'matched_positive' => [],
                'matched_negative' => []
            ];
        }

        // 1. Fetch word list from DB
        $positiveWords = DB::table('positive_words')->pluck('word')->toArray();
        $negativeWords = DB::table('negative_words')->pluck('word')->toArray();

        // 2. Tokenize and clean text
        $cleanedText = strtolower($text);
        // Replace punctuation with spaces
        $cleanedText = preg_replace('/[^\w\s]/', ' ', $cleanedText);
        $words = preg_split('/\s+/', $cleanedText);
        $words = array_filter($words); // Remove empty items

        $positiveCount = 0;
        $negativeCount = 0;
        $matchedPositive = [];
        $matchedNegative = [];

        // 3. Count matches
        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveCount++;
                $matchedPositive[] = $word;
            }
            if (in_array($word, $negativeWords)) {
                $negativeCount++;
                $matchedNegative[] = $word;
            }
        }

        // 4. Calculate sentiment category and scores
        if ($positiveCount > $negativeCount) {
            $sentiment = 'Positive';
            $score = 1.0;
            // E.g. Positive: 60%, Neutral: 25%, Negative: 15%
            $posPct = 60;
            $neuPct = 25;
            $negPct = 15;
        } elseif ($negativeCount > $positiveCount) {
            $sentiment = 'Negative';
            $score = -1.0;
            // E.g. Positive: 15%, Neutral: 25%, Negative: 60%
            $posPct = 15;
            $neuPct = 25;
            $negPct = 60;
        } else {
            $sentiment = 'Neutral';
            $score = 0.0;
            $posPct = 20;
            $neuPct = 60;
            $negPct = 20;
        }

        return [
            'sentiment' => $sentiment,
            'score' => $score,
            'positive_percent' => $posPct,
            'neutral_percent' => $neuPct,
            'negative_percent' => $negPct,
            'matched_positive' => array_unique($matchedPositive),
            'matched_negative' => array_unique($matchedNegative)
        ];
    }
}
