<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TelegramPostJob implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $imageUrl;

    public function __construct($message, $imageUrl = null)
    {
        $this->message  = $message;
        $this->imageUrl = $imageUrl;
    }

    public function handle(): void
    {
        $token  = config('app.telegram.bot_token');
        $chatId = config('app.telegram.chat_id');

        $baseUrl = "https://api.telegram.org/bot{$token}/";

        if ($this->imageUrl) {
            $response = Http::post($baseUrl.'sendPhoto', [
                'chat_id' => $chatId,
                'photo'   => $this->imageUrl,
                'caption'=> $this->message,
            ]);
        } else {
            $response = Http::post($baseUrl.'sendMessage', [
                'chat_id' => $chatId,
                'text'    => $this->message,
            ]);
        }

        Log::info('Telegram response', [
            'status' => $response->status(),
            'body'   => $response->body()
        ]);

        Log::info('Telegram Config Check', [
            'token' => config('app.telegram.bot_token'),
            'chat'  => config('app.telegram.chat_id'),
        ]);
    }
}

