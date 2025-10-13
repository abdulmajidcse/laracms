<?php

namespace App\Http\Requests\Api\V1;

use App\ContentStatus;
use App\ContentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentRequest extends FormRequest
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
            'type' => ['required', Rule::enum(ContentType::class)],
            'title' => 'required|string|max:200',
            'payload' => 'required|array',
            'status' => ['required', Rule::enum(ContentStatus::class)],
        ];
    }
}
