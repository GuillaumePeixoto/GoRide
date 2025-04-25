<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    public static function getHashedFileName(UploadedFile $file): string
    {
        $fileContent = file_get_contents($file->getRealPath());
        $hash = hash('sha256', $fileContent);
        $extension = $file->guessExtension();

        return $hash . '.' . $extension;
    }
}

