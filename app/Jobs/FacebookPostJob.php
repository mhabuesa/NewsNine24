<?php
namespace App\Jobs;

use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FacebookPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $news;
    protected $message;
    protected $imageUrl;

    public function __construct($news, $message, $imageUrl = null)
    {
        $this->news = $news;
        $this->message    = $message;
        $this->imageUrl = $imageUrl;
    }

    public function handle()
{
    // Refresh model to ensure we have the latest data from DB
    // $this->news->refresh();

    $pageId    = config('app.fb.page_id');
    $pageToken = config('app.fb.page_token');
    $baseUrl   = config('app.fb.base_url');

    try {
        $response = Http::post("{$baseUrl}/{$pageId}/feed", [
            'message'      => $this->message,
            'access_token' => $pageToken,
        ]);

        $response = Http::post("{$baseUrl}/{$pageId}/photos", [
            'url'          => $imageUrl, // public image URL
            'caption'      => $this->message, // post message
            'access_token' => $pageToken,
        ]);


        if ($response->successful()) {
            $fbPostId = $response->json('id');

            // Log e check korun ID asche kina
            Log::info("FB ID received: " . $fbPostId);

            // Update column name and value
            $this->news->fb_post_id = (string) $fbPostId; // Cast to string
            $this->news->save();

        } else {
            Log::error("FB API Error: " . $response->body());
            $this->fail(new \Exception('FB API Error: ' . $response->status()));
        }
    } catch (\Exception $e) {
        Log::error("Job Exception: " . $e->getMessage());
        throw $e;
    }
}
}
