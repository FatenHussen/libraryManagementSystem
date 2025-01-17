<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BorrowRecord extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'book_id',
        'user_id',
        'borrowed_at',
        'due_date',
        'returned_at',
         'status'
    ];

    // تحديد نوع الحقول إذا لزم الأمر
    protected $casts = [
        'borrowed_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
    ];

    // العلاقات
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

