<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentFilterRequest extends FormRequest
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
            'search' => 'nullable|string|max:200',
            'sort_by' => 'nullable|string|in:type,title,order,status',
            'sort_dir' => 'nullable|string|in:asc,desc',
            'type' => ['nullable', Rule::enum(ContentType::class)],
            'status' => ['nullable', Rule::enum(ContentStatus::class)],
        ];
    }
}
