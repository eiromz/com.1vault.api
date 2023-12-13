<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Src\Customer\App\Http\Resources\ProfileResource;
use Symfony\Component\HttpFoundation\Response;

class UploadCtrl extends DomainBaseCtrl
{
    public $base_url="https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1";
    /**
     * TODO refactor this method using form request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|max:2048|mimetypes:application/pdf,image/jpg,image/jpeg,image/png'
        ]);

        $path = Storage::disk('do')->putFile('docs', $request->file('file'),'public');

        return jsonResponse(Response::HTTP_OK, [
            'link' => "{$this->base_url}/{$path}"
        ]);
    }
}
