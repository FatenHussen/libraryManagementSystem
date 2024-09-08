<?php
namespace App\Models;

use HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'name',
        'email',
        'password',
         'role'
    ];

    // إخفاء الحقول الحساسة
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // تحديد نوع الحقول إذا لزم الأمر
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    

    // العلاقات
    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }

    /**
     * Get the identifier that will be stored in the JWT's subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // حساب متوسط التقييمات
    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    // public function getRoleAttribute()
    // {
    //     return $this->attributes['role'];
    // }
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
