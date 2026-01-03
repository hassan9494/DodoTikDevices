<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('isAdmin') ?? false;
    }

    public function rules(): array
    {
        $codeId = $this->route('subscription_code')?->id;

        return [
            'code' => ['required', 'string', 'max:255', 'unique:subscription_codes,code,' . $codeId],
            'duration_days' => ['required', 'integer', 'min:1', 'max:3650'],
            'max_uses' => ['required', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper(trim((string) $this->input('code'))),
        ]);
    }
}
