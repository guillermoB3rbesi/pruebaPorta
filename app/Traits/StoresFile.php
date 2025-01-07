<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait StoresFile
{
    public function storeFile(string $destinationPath = '', string $fileName = '', UploadedFile $file): false|string
    {
        return Storage::disk('public')->putFileAs($destinationPath,$file,$fileName);
    }

    public function putFile(UploadedFile $file, string $destinationPath = '', string $fileName = ''):array
    {
        $initPath = '/public/' . $destinationPath;
        $name = $fileName != ''
                    ? $fileName
                    : pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $path = $this->storeFile(
                $initPath,
                $name,
                $file);
        return [
            'path'      => $path,
            'extension' => $file->getClientOriginalExtension(),
            'size'      => $file->getSize(),
        ];
    }
}
