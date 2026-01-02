<?php

namespace App\Services;

class TwitterService
{
    public static function postTweet(string $text)
    {
        $url = 'https://api.x.com/2/tweets';
        $method = 'POST';

        $oauth = [
            'oauth_consumer_key'     => env('TWITTER_API_KEY'),
            'oauth_nonce'            => bin2hex(random_bytes(16)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp'        => time(),
            'oauth_token'            => env('TWITTER_ACCESS_TOKEN'),
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

        $authHeader = 'OAuth ';
        $values = [];
        foreach ($oauth as $key => $value) {
            $values[] = $key . '="' . rawurlencode($value) . '"';
        }
        $authHeader .= implode(', ', $values);

        $payload = json_encode(['text' => $text]);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: $authHeader",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception($error);
        }

        return json_decode($response, true);
    }
}
