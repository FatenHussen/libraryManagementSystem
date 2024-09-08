<?php
namespace App\Policies;

use App\Models\User;
use App\Models\BorrowRecord;

class BorrowRecordPolicy
{
    // المشرف لديه جميع الصلاحيات
    public function before(User $user, $ability)
    {
        return $user->is_admin ? true : null;
    }

    // المستخدمون المسجلون فقط يمكنهم استعارة الكتب
    public function create(User $user)
    {
        return $user->isRegistered();  // أو يمكنك التأكد من دور المستخدم المسجل
    }

    public function update(User $user, BorrowRecord $BorrowRecord)
    {
        return $user->role === 'user';
    }

    public function delete(User $user, BorrowRecord $BorrowRecord)
    {
        return $user->role === 'user';
    }
    // المستخدم يمكنه عرض سجلات الاستعارة الخاصة به
    public function view(User $user, BorrowRecord $borrowRecord)
    {
        return $user->id === $borrowRecord->user_id;
    }

    // المشرف يمكنه الحصول على جميع السجلات
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }
}
