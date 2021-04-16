<?php

namespace App\Traits;

use App\Exceptions\ErrorMessages;
use App\Exceptions\Model\NotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

trait GetStorageFile
{
    /**
     * Get files from storage.app.public
     *
     * @param string $filepath
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFile(string $filepath): \Illuminate\Http\Response
    {
        $path = storage_path('app/public/' . $filepath);

        if (! File::exists($path)) {
            return response(ErrorMessages::IMAGE_NOT_EXIST, \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, \Illuminate\Http\Response::HTTP_OK);
        $response->header("Content-Type", $type);

        return $response;
    }

    /**
     * @param string $filepath
     *
     * @return string
     * @throws NotFoundException
     */
    public function getBase64StorageFile(string $filepath): string
    {
        $path = storage_path('app/public/' . $filepath);

        if (!File::exists($path) || is_null($filepath)) {
            throw new NotFoundException(ErrorMessages::IMAGE_NOT_EXIST, \Illuminate\Http\Response::HTTP_NOT_FOUND);
        }

        $file   = file_get_contents($path);
        $type   = File::mimeType($path);
        $base64 = 'data:' . $type . ';base64,' . base64_encode($file);

        return $base64;
    }

    /**
     * Generating base64 from uploaded file
     *
     * @param \Illuminate\Http\UploadedFile $uploadedFile
     *
     * @return string
     */
    public function generateBase64File(UploadedFile $uploadedFile): string
    {
        $file = file_get_contents($uploadedFile);
        $type = $uploadedFile->getClientMimeType();

        return 'data:' . $type . ';base64,' . base64_encode($file);
    }
}
