<?php
namespace App\Jobs;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FacebookPostDeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fbPostId;

    public function __construct($fbPostId)
    {
        $this->fbPostId = $fbPostId;
    }

    public function handle()
    {

        $pageToken = config('app.fb.page_token');
        $baseUrl   = config('app.fb.base_url');

        try {
            // Delete FB Post if exists
            if ($this->fbPostId){
                Log::info("Deleting FB Post: " . $this->fbPostId);
                Http::delete(
                    "{$baseUrl}/{$this->fbPostId}",
                    [
                        'access_token' => $pageToken,
                        ]
                    );
                }
        } catch (\Exception $e) {
            Log::error("Job Exception: " . $e->getMessage());
            throw $e;
        }
    }
}
