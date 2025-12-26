<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

/**
 * image intervention v3
 */

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

trait ImageSaveTrait
{
    private function saveImage($file_destination, $image_attribute, $width = null, $height = null): string
    {
        $directory = storage_path('app/public/' . $file_destination);

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // SVG case
        if ($image_attribute->extension() === 'svg') {
            $file_name = time() . Str::random(10) . '.svg';
            $image_attribute->move($directory, $file_name);
            return $file_destination . '/' . $file_name;
        }

        $manager = new ImageManager(Driver::class);
        $image = $manager->read($image_attribute);

        if ($width && $height) {
            $image->scale($width, $height);
            $image->pad($width, $height, 'fff');
        }

        $encoded = $image->encode(new WebpEncoder);
        $file_name = time() . '-' . Str::random(10) . '.webp';
        $encoded->save($directory . '/' . $file_name);

        return $file_destination . '/' . $file_name;
    }


    private function updateImage($old_path, $file_destination, $image_new_attribute, $width = null, $height = null): string
    {
        $directory = storage_path('app/public/' . $file_destination);

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if ($image_new_attribute->extension() === 'svg') {
            $file_name = time() . Str::random(10) . '.svg';
            $image_new_attribute->move($directory, $file_name);

            if ($old_path) {
                File::delete(storage_path('app/public/' . $old_path));
            }

            return $file_destination . '/' . $file_name;
        }

        $manager = new ImageManager(Driver::class);
        $image = $manager->read($image_new_attribute);

        if ($width && $height) {
            $image->scale($width, $height);
            $image->pad($width, $height, 'fff');
        }

        $encoded = $image->encode(new WebpEncoder);
        $file_name = time() . '-' . Str::random(10) . '.webp';
        $encoded->save($directory . '/' . $file_name);

        if ($old_path) {
            File::delete(storage_path('app/public/' . $old_path));
        }

        return $file_destination . '/' . $file_name;
    }


    private function deleteImage($path)
    {
        if (!$path) return;

        File::delete(storage_path('app/public/' . $path));
    }
}
