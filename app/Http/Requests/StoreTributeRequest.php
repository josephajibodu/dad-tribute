<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'relation' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
            // Honeypot: real visitors never see or fill this field.
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please add your name so the family knows who lit this candle.',
            'website.prohibited' => 'Something went wrong. Please try again.',
        ];
    }
}
