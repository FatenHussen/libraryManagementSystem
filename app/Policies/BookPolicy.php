<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === 'user' || $user->role === 'admin';

    }

    public function view(User $user, Book $book)
    {
        return $user->role === 'user' || $user->role === 'admin';
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Book $book)
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Book $book)
    {
        return $user->role === 'admin';
    }
}
