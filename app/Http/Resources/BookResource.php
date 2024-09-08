<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'status' => $this->status,
            'published_at' => $this->published_at ? $this->published_at->toDateString() : null,
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ] : null,  // Handling the category explicitly to avoid large objects
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'current_borrower' => $this->currentBorrowRecord ? [
                'user_id' => $this->currentBorrowRecord->user->id,
                'user_name' => $this->currentBorrowRecord->user->name,
                'borrowed_at' => $this->currentBorrowRecord->borrowed_at->toDateString(),
                'due_date' => $this->currentBorrowRecord->due_date->toDateString(),
            ] : null,
              // حساب متوسط التقييمات
              'average_rating' => $this->ratings()->avg('rating') ?: 0, // إذا لم تكن هناك تقييمات، يتم عرض 0
              
        ];
    }
}
