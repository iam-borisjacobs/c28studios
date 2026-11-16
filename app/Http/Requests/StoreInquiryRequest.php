<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    /**
     * @var array<string, bool>
     */
    protected array $detectedInjectionFields = [];

    private const BLOCKED_PATTERNS = [
        '/<script\b/i',
        '/<\/script>/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/data\s*:\s*text\/html/i',
        '/\bon(error|load|click|mouseover|focus|submit)\s*=/i',
        '/<iframe\b/i',
        '/<object\b/i',
        '/<embed\b/i',
        '/<svg\b/i',
    ];

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $textFields = [
            'name',
            'email',
            'company',
            'service_interest',
            'engagement_mode',
            'project_type',
            'budget_range',
            'timeline',
            'message',
        ];

        $sanitized = [];
        foreach ($textFields as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $raw = (string) $this->input($field);

                foreach (self::BLOCKED_PATTERNS as $pattern) {
                    if (preg_match($pattern, $raw)) {
                        $this->detectedInjectionFields[$field] = true;
                        break;
                    }
                }

                // Remove null bytes and non-printable control characters
                $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $raw) ?? $raw;
                $sanitized[$field] = trim(strip_tags($cleaned));
            }
        }

        if (! empty($sanitized)) {
            $this->merge($sanitized);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $noInjectionPayload = function (string $attribute, mixed $value, Closure $fail): void {
            if (! empty($this->detectedInjectionFields[$attribute])) {
                $fail("The {$attribute} field contains disallowed script or markup characters.");
                return;
            }
            if (! is_string($value)) {
                return;
            }
            foreach (self::BLOCKED_PATTERNS as $pattern) {
                if (preg_match($pattern, $value)) {
                    $fail("The {$attribute} field contains disallowed script or markup characters.");
                    return;
                }
            }
        };

        return [
            'name' => ['required', 'string', 'min:2', 'max:120', $noInjectionPayload],
            'email' => ['required', 'email', 'max:190', $noInjectionPayload],
            'company' => ['nullable', 'string', 'max:150', $noInjectionPayload],
            'service_interest' => ['required', 'string', 'max:120', $noInjectionPayload],
            'engagement_mode' => [
                'nullable',
                'string',
                Rule::in(array_keys(Inquiry::ENGAGEMENT_MODES)),
            ],
            'project_type' => ['nullable', 'string', 'max:80', $noInjectionPayload],
            'budget_range' => ['nullable', 'string', 'max:80', $noInjectionPayload],
            'timeline' => ['nullable', 'string', 'max:80', $noInjectionPayload],
            'message' => ['required', 'string', 'min:10', 'max:5000', $noInjectionPayload],
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
