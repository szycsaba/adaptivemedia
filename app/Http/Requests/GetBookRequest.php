<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetBookRequest extends FormRequest
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
            'id' => 'required|integer|min:1|exists:books,id',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Book id is required.',
            'id.integer' => 'Book id must be an integer.',
            'id.min' => 'Book id must be at least 1.',
            'id.exists' => 'Book id does not exist.',
        ];
    }
}
