<?php
namespace App\Http\Requests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->role === 'admin'; // يسمح فقط للمديرين
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure this is included
            'published_at' => 'required|date',
            'available' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The book title is required.',
            'author.required' => 'The author name is required.',
            'author.min' => 'The author name must be at least 3 characters.',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'book title',
            'author' => 'author name',
            'description' => 'book description',
            'published_at' => 'publication date',
        ];
    }
}
    