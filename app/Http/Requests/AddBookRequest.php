<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddBookRequest extends FormRequest
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
            'title' => 'required|string',
            'author_id' => 'required|integer',
            'category_id' => 'required|integer',
            'release_date' => 'required|date_format:Y-m-d',
            'price_huf' => 'required|integer|min:0',
        ];
    }

    public function message(): array 
    {
        return [
            'title.required' => 'Title field param is required.',
            'title.string' => 'Title field must be type of string.',
            'author_id.required' => 'Author id field is required.',
            'author_id.integer' => 'Author id field must be integer.',
            'category_id.required' => 'Category id field is required.',
            'category_id.integer' => 'Category id field must be integer.',
            'release_date.required' => 'Release date field is required',
            'release_date.date_format' => 'The correct format of release date is Y-m-d',
            'price_huf.required' => 'Price field is required.',
            'price_huf.integer' => 'Price field must be integer.',
            'price_huf.min' => 'Price field must be at least 0.',
        ];
    }
}
