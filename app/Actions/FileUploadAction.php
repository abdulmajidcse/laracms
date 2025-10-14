<?php

namespace App\Actions;

use App\Services\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class FileUploadAction
{
    public function __construct(private ApiResponse $apiResponse) {}

    public function handle(array $data): JsonResponse
    {
        $filePath = Storage::putFile('', $data['file']);
        $fileUrl = Storage::url($filePath);

        return $this->apiResponse->success(
            ['file_url' => $fileUrl],
            'File uploaded successfully.'
        );
    }
}
