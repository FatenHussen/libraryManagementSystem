<?php
namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'book_id' => 'required|exists:books,id',  // تأكد من وجود الكتاب
        ];
    }

    public function attributes()
    {
        return [
            'rating' => 'Rating',
            'review' => 'Review',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'The rating is required.',
            'rating.integer' => 'The rating must be an integer.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating may not be greater than 5.',
        ];
    }
}
