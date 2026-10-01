<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'booking_id'  => ['nullable', 'exists:bookings,id'],
            'type'        => ['required', 'in:service_quality,overcharge,parts_issue,general,other'],
            'subject'     => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:3000'],
            'priority'    => ['nullable', 'in:low,medium,high,urgent'],
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
            'type.required'        => 'Please select an issue type.',
            'type.in'              => 'Please select a valid issue type.',
            'subject.required'     => 'Please provide a subject for your ticket.',
            'subject.min'          => 'Subject must be at least 5 characters.',
            'subject.max'          => 'Subject may not exceed 255 characters.',
            'description.required' => 'Please describe your issue in detail.',
            'description.min'      => 'Description must be at least 10 characters.',
            'description.max'      => 'Description may not exceed 3,000 characters.',
            'attachments.max'      => 'You may upload a maximum of 5 attachments.',
            'attachments.*.max'    => 'Each file may not exceed 10 MB.',
            'attachments.*.mimes'  => 'Only JPG, PNG, GIF, WebP, and PDF files are allowed.',
        ];
    }
}
