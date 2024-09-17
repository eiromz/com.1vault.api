<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'max:2048', 'mimetypes:application/pdf,image/jpg,image/jpeg,image/png'],
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'File size too large. Allowed file size: 2mb',
        ];
    }
}
