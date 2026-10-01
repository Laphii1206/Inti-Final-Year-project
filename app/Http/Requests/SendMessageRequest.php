<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'body'          => ['required', 'string', 'min:1', 'max:5000'],
            'is_internal'   => ['nullable', 'boolean'],
            'attachments'   => ['nullable', 'array', 'max:5'],
            'attachments.*' => [
                'file',
                'max:10240',
                'mimes:jpg,jpeg,png,gif,webp,pdf',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required'       => 'Message cannot be empty.',
            'body.max'            => 'Message may not exceed 5,000 characters.',
            'attachments.max'     => 'You may upload a maximum of 5 attachments per message.',
            'attachments.*.max'   => 'Each file may not exceed 10 MB.',
            'attachments.*.mimes' => 'Only JPG, PNG, GIF, WebP, and PDF files are allowed.',
        ];
    }
}
