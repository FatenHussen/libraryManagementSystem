<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // فقط المشرف يمكنه إدارة CRUD للمستخدمين
    public function before(User $user, $ability)
    {
        return $user->role === 'admin';
    }

    // السماح للمسؤولين فقط بعرض جميع المستخدمين
    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    // السماح للمسؤولين فقط بعرض مستخدم معين
    public function view(User $user, User $model)
    {
        return $user->role === 'admin';
    }

    // السماح فقط للمسؤولين بإنشاء المستخدمين
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    // السماح فقط للمسؤولين بتحديث المستخدمين
    public function update(User $user, User $model)
    {
        return $user->role === 'admin';
    }

    // السماح فقط للمسؤولين بحذف المستخدمين
    public function delete(User $user, User $model)
    {
        return $user->role === 'admin';
    }
}
