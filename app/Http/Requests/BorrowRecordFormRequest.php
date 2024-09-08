<?php
namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Validation\Validator;

use Illuminate\Http\Exceptions\HttpResponseException;


class BorrowRecordFormRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // يجب أن يكون المستخدم مسجل الدخول
    }

    public function rules()
    {
        return [
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrowed_at',
            'returned_at' => 'nullable|date|after_or_equal:borrowed_at',
        ];
    }

    public function messages()
    {
        return [
            'book_id.required' => 'The book ID is required.',
            'book_id.exists' => 'The selected book does not exist.',
            'borrowed_at.required' => 'The borrowed at date is required.',
            'borrowed_at.date' => 'The borrowed at date must be a valid date.',
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date must be after or equal to the borrowed at date.',
            'returned_at.date' => 'The returned at date must be a valid date.',
            'returned_at.after_or_equal' => 'The returned at date must be after or equal to the borrowed at date.',
        ];
    }

    public function attributes()
    {
        return [
            'book_id' => 'book ID',
            'borrowed_at' => 'borrowed at date',
            'due_date' => 'due date',
            'returned_at' => 'returned at date',
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
     // تنفيذ عمليات مخصصة بعد نجاح التحقق
     protected function passedValidation()
     {
         // مثال 1: تسجيل رسالة في السجلات (Logs)
         Log::info('Validation passed for BorrowRecordFormRequest', [
             'user_id' => auth()->id(),
             'book_id' => $this->book_id,
             'borrowed_at' => $this->borrowed_at,
             'due_date' => $this->due_date,
         ]);
 
         // مثال 2: إرسال تنبيه إلى المسؤول
         // يمكنك استخدام الإشعارات المدمجة في Laravel لإرسال إشعارات بريد إلكتروني أو إشعارات أخرى
         Notification::send(auth()->user(), new \App\Notifications\BorrowRecordCreated($this->book_id));
     }
}
