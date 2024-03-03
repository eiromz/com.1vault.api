<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UploadCtrl extends DomainBaseCtrl
{
    public $base_url = 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1';

    public function __invoke(UploadRequest $request): JsonResponse
    {
        $request->validated();

        $path = Storage::disk('do')->putFile('docs', $request->file('file'), 'public');

        return jsonResponse(Response::HTTP_OK, [
            'link' => "{$this->base_url}/{$path}",
        ]);
    }
}
