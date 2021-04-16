<?php

declare(strict_types=1);

namespace App\Services\Base;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Trait UploadTrait
 *
 * @package App\Services\Base
 */
trait UploadFileTrait
{
    /**
     * Generate file name
     *
     * @param string $prefix
     * @param string $fileType
     * @param int $randomStrLength
     * @return string
     */
    protected function generateFileName(string $prefix, string $fileType, int $randomStrLength = 16): string
    {
        return $prefix . "_" . Str::random($randomStrLength) . '.' . $fileType;
    }

    /**
     * Store file in storage
     *
     * @param string $path
     * @param $image
     */
    protected function store(string $path, $image): void
    {
        \Storage::put($path, $image);
    }

    /**
     * @param string $encodedFile
     * @return array
     */
    public function decodeBase64(string $encodedFile): array
    {
        $image_parts = explode(";base64,", $encodedFile);
        $image_type_aux = explode("/", Arr::first($image_parts));
        $mime_type = str_replace('data:', '', Arr::first($image_parts));
        $image_type = Arr::last($image_type_aux);
        $image_base64 = base64_decode(Arr::last($image_parts));

        return compact('mime_type', 'image_type', 'image_base64');
    }
}
