<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|mimes:csv,txt|max:4096', // 4MB limit
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'A CSV file is required.',
            'file.file' => 'The uploaded file must be valid.',
            'file.mimes' => 'The file must be a CSV or TXT.',
            'file.max' => 'The file size must not exceed 4MB.',
        ];
    }
}
