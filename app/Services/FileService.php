<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Uploads a file to S3
     * @param $file Uploaded file
     * @param $path Path where the file will be stored
     * @return string URL of the uploaded file
     */
    public function uploadFile($file, $path)
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filenameWithUuid = $filename . '_' . Str::uuid() . '.' . $extension;
        $fullPath = $path . '/' . $filenameWithUuid;

        $contents = file_get_contents($file);
        $options = ['visibility' => 'public'];
        Storage::put($fullPath, $contents, $options);

        return Storage::url($fullPath);
    }

    /**
     * Deletes a file from S3.
     * @param $path Path of the file to delete
     * @return bool
     */
    public function deleteFile($path)
    {
        return Storage::disk('s3')->delete($path);
    }

    /**
     * Updates a file in S3 by deleting the old file and uploading a new one.
     * @param $file New file to upload
     * @param $path Path where the old file was stored
     * @param $public Whether the new file should be publicly accessible
     * @return string URL of the uploaded file
     */
    public function updateFile($file, $path, $public = false)
    {
        $this->deleteFile($path);
        return $this->uploadFile($file, $path, $public);
    }
}
