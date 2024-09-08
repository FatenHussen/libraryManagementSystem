<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookFormRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // يجب أن يكون المستخدم مسجل الدخول
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id', // التأكد من أن الكتاب موجود
            'borrowed_at' => 'required|date', // تاريخ الاستعارة مطلوب ويجب أن يكون تاريخًا صالحًا
            'descreption.required'=>'nullable|string', //
            'due_date' => 'required|date|after:borrowed_at', // تاريخ الإعادة مطلوب ويجب أن يكون بعد تاريخ الاستعارة
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'Please select a valid book to borrow.',
            'book_id.exists' => 'The selected book does not exist in the system.',
            'borrowed_at.required' => 'The borrowing date is required.',
            'borrowed_at.date' => 'The borrowing date must be a valid date.',
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after' => 'The due date must be after the borrowing date.', // رسالة التحقق من أن تاريخ الإعادة بعد تاريخ الاستعارة
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

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'status' => 'error',
            'message' => 'There were errors with the provided data.',
            'errors' => $errors->messages()
        ], 422);

        throw new HttpResponseException($response);
    }
}
