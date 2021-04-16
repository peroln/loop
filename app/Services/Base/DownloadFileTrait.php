<?php

declare(strict_types=1);

namespace App\Services\Base;

use App\Exceptions\Model\NotFoundException;
use App\Models\Documents;

/**
 * Trait DownloadFileTrait
 *
 * @package App\Services\Base
 */
trait DownloadFileTrait
{
    /**
     * Set base model file storage folder
     *
     * @return string
     */
    abstract protected function baseFolder(): string;

    /**
     * Load files from storage
     *
     * @param string $file
     *
     * @return array
     * @throws NotFoundException
     */
    public function getFile(string $file): array
    {
        $path = "storage/app/" . $this->baseFolder() . '/' . $file;
        $filePath = base_path($path);

        if (!file_exists($filePath)) {
            throw new NotFoundException("File not found");
        }
        $content = file_get_contents($filePath);

        return ['Content-Type' => mime_type($filePath), 'Content' => $content];
    }

    public function getBase64File(Documents $file): ?string
    {
        $path = "storage/app/" . $file->path;
        $filePath = base_path($path);

        if (!file_exists($filePath)) {
            return null;
        }
        $fileBinary = fread(fopen($filePath, 'rb'), filesize($filePath));
        return 'data:' . $file->mime_type . ';base64,' . base64_encode($fileBinary);
    }
}
