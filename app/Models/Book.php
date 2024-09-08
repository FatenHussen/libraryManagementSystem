<?php

namespace App\Models;

use App\Models\Category;
use App\Models\BorrowRecord;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Book extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'title', 'author', 'description', 'published_at', 'category_id',  'available'
    ];

    // تحديد نوع الحقول إذا لزم الأمر
    protected $casts = [
        'published_at' => 'date',
    ];

     
  
      // علاقة واحدة لجلب أحدث سجل استعارة غير مرجع (أي الكتاب مستعار حاليًا)
      public function currentBorrowRecord()
      {
          return $this->hasOne(BorrowRecord::class)->whereNull('returned_at');
      }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function borrowRecords()
{
    return $this->hasMany(BorrowRecord::class);
}

public function markAsAvailable()
{
    $this->available = true;
    $this->save();
}

public function markAsUnavailable()
{
    $this->available = false;
    $this->save();
}

 // علاقة الكتاب بالتقييمات
 public function ratings()
 {
     return $this->hasMany(Rating::class);
 }
   // علاقة الكتاب بتصنيف واحد
   public function category()
   {
       return $this->belongsTo(Category::class);
   }
}

