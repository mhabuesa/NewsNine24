<?php

use Illuminate\Support\Str;

// Generate media asset URL
if (!function_exists('media')) {
    function media(?string $path): string
    {
        if (!$path) {
            return asset('images/default.png'); // fallback
        }

        // already full url?
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // if already contains storage/
        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        // default: storage path
        return asset('storage/' . $path);
    }
}
