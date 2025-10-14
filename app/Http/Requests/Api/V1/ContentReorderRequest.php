<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentReorderRequest extends FormRequest
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
            'prev_content_id' => ['bail', 'nullable', 'integer', Rule::exists('contents', 'id')],
            'reorder_content_ids' => ['bail', 'required', 'array', 'min:1'],
            'reorder_content_ids.*' => ['bail', 'integer', Rule::exists('contents', 'id')],
            'next_content_id' => ['bail', 'nullable', 'integer', Rule::exists('contents', 'id')],
        ];
    }
}
