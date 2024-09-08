<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BorrowRequest extends FormRequest
{
    public function authorize()
    {
        // return auth()->check(); // يجب أن يكون المستخدم مسجل الدخول
        return true;
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id', // التأكد من أن الكتاب موجود
            // 'borrowed_at' => 'required|date',
            // 'due_date' => 'required|date|after:borrowed_at', // يجب أن يكون تاريخ الإعادة بعد تاريخ الاستعارة
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'Please select a book to borrow.',
            'book_id.exists' => 'The selected book does not exist in the library.',
        ];
    }

    public function attributes()
    {
        return [
            'book_id' => 'book ID',
        ];
    }
}
