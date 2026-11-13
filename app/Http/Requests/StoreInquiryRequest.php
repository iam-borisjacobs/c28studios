<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'company' => ['nullable', 'string', 'max:150'],
            'service_interest' => ['required', 'string', 'max:120'],
            'engagement_mode' => [
                'nullable',
                'string',
                Rule::in(array_keys(Inquiry::ENGAGEMENT_MODES)),
            ],
            'project_type' => ['nullable', 'string', 'max:80'],
            'budget_range' => ['nullable', 'string', 'max:80'],
            'timeline' => ['nullable', 'string', 'max:80'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'website_url' => ['nullable', 'string', 'max:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your name and role.',
            'email.required' => 'Please provide a valid work email address.',
            'service_interest.required' => 'Please select the primary C28 engineering service you need.',
            'message.required' => 'Please share a brief overview of your repository, infrastructure, or project scope.',
            'message.min' => 'Please provide at least 10 characters of context so our engineers can prepare for your review.',
            'website_url.max' => 'Automated spam submission rejected.',
        ];
    }
}

