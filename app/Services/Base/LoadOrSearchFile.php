<?php

namespace App\Services\Base;

use App\Exceptions\Model\NotFoundException;
use App\Services\Images\GooglePhotoImageSearchService;

/**
 * Trait LoadOrSearchFile
 * @package App\Services\Base
 */
trait LoadOrSearchFile
{
    /**
     * @description If image not found load from web
     *
     * @param string $filepath
     * @param string $search
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function loadOrSearchBase64StorageFile(string $filepath, string $search = "sport logo")
    {
        try {
            $base64 = $this->getBase64StorageFile($filepath);
        } catch (NotFoundException $exception) {
            $googleSearchService = resolve(GooglePhotoImageSearchService::class);
            $link = $googleSearchService->getImageLink($search);
            $image = file_get_contents($link);

            return base64_encode($image);
        }

        return $base64;
    }
}
