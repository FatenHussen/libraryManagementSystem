<?php
namespace App\Services;

use App\Models\Rating;

class RatingService
{
    // إضافة تقييم جديد
    public function createRating($data)
    {
         // التحقق مما إذا كان التقييم موجودًا مسبقًا
         $existingRating = Rating::where('user_id', $data['user_id'])
         ->where('book_id', $data['book_id'])
         ->first();

if ($existingRating) {
throw new \Exception('You have already rated this book.');
}
        return Rating::create($data);
    }

    // جلب جميع التقييمات لكتاب معين
    public function getBookRatings($bookId)
    {
        return Rating::where('book_id', $bookId)->get();
    }

    // تحديث تقييم
    public function updateRating($ratingId, $data)
    {
        $rating = Rating::findOrFail($ratingId);
        $rating->update($data);
        return $rating;
    }

    // حذف تقييم
    public function deleteRating($ratingId)
    {
        $rating = Rating::findOrFail($ratingId);
        $rating->delete();
        return true;
    }
}
