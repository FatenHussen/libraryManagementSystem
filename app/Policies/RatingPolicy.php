<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Rating;
use Illuminate\Auth\Access\HandlesAuthorization;

class RatingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function view(User $user, Rating $rating)
    {
        return $user->isAdmin() || $user->id === $rating->user_id;
    }

    public function create(User $user)
    {
        return true; // Any authenticated user can create a rating
    }
// التأكد من أن المستخدم الحالي هو صاحب التقييم قبل السماح بالتحديث
public function update(User $user, Rating $rating)
{
    return $user->id === $rating->user_id;
}
   // التأكد من أن المستخدم الحالي هو صاحب التقييم قبل السماح بالحذف
   public function delete(User $user, Rating $rating)
   {
       return $user->id === $rating->user_id;
   }
}
