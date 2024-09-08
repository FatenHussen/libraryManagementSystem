<?php
namespace App\Http\Resources;

use App\Models\BorrowRecord;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResource extends JsonResource
{
    public function toArray($request)
    {
        // إذا كان المستخدم هو الإداري
        if (Auth::user()->role == 'admin') {
            return [
                'id' => $this->id,
                'book' => new BookResource($this->book),
                'user' => new UserResource($this->user), // عرض تفاصيل المستخدم
                'status' => $this->status,
                'borrowed_at' => $this->borrowed_at,
                'due_date' => $this->due_date,
                'returned_at' => $this->returned_at,
            ];
        }
    
        // // إذا كان المستخدم العادي
        // return [
        //     'id' => $this->id,
        //     'book' => new BookResource($this->book), // عرض تفاصيل الكتاب فقط
        //     'borrowed_at' => $this->borrowed_at,
        //     'due_date' => $this->due_date,
        //     'returned_at' => $this->returned_at,
        // ];
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'user_id' => $this->user_id,
            'borrowed_at' => $this->borrowed_at->toDateString(),
            'due_date' => $this->due_date->toDateString(),
            'returned_at' => $this->returned_at ? $this->returned_at->toDateString() : null,
            'book' => new BookResource($this->book), // إذا كنت قد عرفت BookResource مسبقًا
            'status' => $this->status,
        ];

    }
    
}
