<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDataDefinitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'package_id' => ['required', Rule::exists('packages', 'id')],
            'database_id' => ['required', Rule::exists('databases', 'id')],
            'ddl_type' => ['required'],
            'question' => ['required'],
            'answer' => ['required'],
            'additions' => [],
        ];
    }
}
