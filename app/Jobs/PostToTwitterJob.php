<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use CURLFile;

class PostToTwitterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $imagePath;

    public function __construct($message, $imagePath = null)
    {
        $this->message = $message;
        $this->imagePath = $imagePath;
    }

    public function handle()
    {
        $mediaId = null;

        // ✅ Step 1: If image path exists, upload image first
        if ($this->imagePath && file_exists($this->imagePath)) {
            $mediaId = $this->uploadMedia($this->imagePath);
        }

        // ✅ Step 2: Post tweet with or without media
        return $this->postTweet($this->message, $mediaId);
    }

    private function uploadMedia($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File does not exist: $filePath");
        }

        $url = 'https://upload.twitter.com/1.1/media/upload.json';

        $oauth = [
            'oauth_consumer_key' => env('TWITTER_API_KEY'),
            'oauth_token' => env('TWITTER_ACCESS_TOKEN'),
            'oauth_nonce' => bin2hex(random_bytes(16)),
            'oauth_timestamp' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_version' => '1.0'
        ];

        $baseParams = $oauth;
        $baseParams['media_category'] = 'tweet_image';
        ksort($baseParams);

        $baseString = 'POST&' . rawurlencode($url) . '&' .
            rawurlencode(http_build_query($baseParams, '', '&', PHP_QUERY_RFC3986));

        $signingKey = rawurlencode(env('TWITTER_API_SECRET_KEY')) . '&' . rawurlencode(env('TWITTER_ACCESS_TOKEN_SECRET'));
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $baseString, $signingKey, true));

        $authHeader = 'OAuth ' . collect($oauth)
            ->map(fn($v, $k) => $k . '="' . rawurlencode($v) . '"')
            ->implode(', ');

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: $authHeader"
            ],
            CURLOPT_POSTFIELDS => [
                'media' => new \CURLFile($filePath)
            ],
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("Media upload failed: " . $error);
        }

        $data = json_decode($response, true);
        if (!isset($data['media_id_string'])) {
            throw new \Exception("Media ID not returned: " . $response);
        }

        return $data['media_id_string'];
    }


    private function postTweet($text, $mediaId = null)
    {
        $url = 'https://api.x.com/2/tweets';
        $method = 'POST';

        $oauth = [
            'oauth_consumer_key'     => env('TWITTER_API_KEY'),
            'oauth_token'            => env('TWITTER_ACCESS_TOKEN'),
            'oauth_nonce'            => bin2hex(random_bytes(16)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_version'          => '1.0'
        ];

        $baseParams = $oauth;
        ksort($baseParams);

        $baseString = strtoupper($method) . "&" .
            rawurlencode($url) . "&" .
            rawurlencode(http_build_query($baseParams, '', '&', PHP_QUERY_RFC3986));

        $signingKey = rawurlencode(env('TWITTER_API_SECRET_KEY')) . '&' .
            rawurlencode(env('TWITTER_ACCESS_TOKEN_SECRET'));

        $oauth['oauth_signature'] = base64_encode(
            hash_hmac('sha1', $baseString, $signingKey, true)
        );

        $authHeader = 'OAuth ' . collect($oauth)
            ->map(fn($v, $k) => $k . '="' . rawurlencode($v) . '"')
            ->implode(', ');

        $payload = ['text' => $text];

        if ($mediaId) {
            $payload['media'] = ['media_ids' => [$mediaId]];
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: $authHeader",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("Tweet failed: " . $error);
        }

        return json_decode($response, true);
    }
}
