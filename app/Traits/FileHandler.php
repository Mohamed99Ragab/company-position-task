<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileHandler
{

    public function uploadFile($file, $folder = '')
    {
        // Ensure the folder exists, if not, create it.
        if (!$file) {
            return null;
        }


        $filePath = $file->store($folder, 'uploads');

        return $filePath;
    }


    public function deleteFile($filePath)
    {
        if (Storage::disk('uploads')->exists($filePath)) {
            return Storage::disk('uploads')->delete($filePath);
        }

        return false;
    }


    public function replaceFile($newFile, $oldFilePath, $folder = 'uploads')
    {
        // Delete the old file
        $this->deleteFile($oldFilePath);

        // Upload the new file and return the new file path
        return $this->uploadFile($newFile, $folder);
    }
}
