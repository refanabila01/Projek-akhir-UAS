<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\ExternalApiService;
use App\Services\SentimentAnalyzer;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $apiService;
    protected $sentimentAnalyzer;

    public function __construct(ExternalApiService $apiService, SentimentAnalyzer $sentimentAnalyzer)
    {
        $this->apiService = $apiService;
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    public function index(Request $request)
    {
        $category = $request->input('category', 'Economy');
        $force = $request->has('refresh') && $request->input('refresh') == 1;

        // Check if we have recent cached news for this category (updated within 1 hour)
        $latestNews = News::where('category', $category)->orderBy('updated_at', 'desc')->first();
        $isStale = !$latestNews || $latestNews->updated_at->lt(now()->subHour());

        if ($force || $isStale) {
            $articles = $this->apiService->fetchGlobalNews($category);
            
            if (!empty($articles)) {
                // Delete previous articles for this category to keep database clean
                News::where('category', $category)->delete();

                foreach ($articles as $art) {
                    $sentiment = $art['sentiment'] ?? null;
                    $score = $art['sentiment_score'] ?? null;

                    if ($sentiment === null) {
                        $analysis = $this->sentimentAnalyzer->analyze($art['title'] . ' ' . $art['content']);
                        $sentiment = $analysis['sentiment'];
                        $score = $analysis['score'];
                    }

                    News::create([
                        'title' => $art['title'],
                        'content' => $art['content'],
                        'source' => $art['source'],
                        'url' => $art['url'],
                        'image' => $art['image'],
                        'published_at' => $art['published_at'],
                        'sentiment' => $sentiment,
                        'sentiment_score' => $score,
                        'category' => $category,
                        'country_id' => null,
                    ]);
                }
            }
        }

        // Retrieve news for the current category to display
        $news = News::where('category', $category)->orderBy('published_at', 'desc')->get();
        
        return view('news.index', compact('news', 'category'));
    }
}
