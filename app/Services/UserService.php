<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

  

    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);  // Ensure password is hashed
        return User::create($data);
    }
    

    public function updateUser(User $user, array $data)
    {
        // If a password is provided, hash it before saving
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // If no password is provided, remove it from the data array so it doesn't override the existing password
            unset($data['password']);
        }
    
        // Update the user with the new data
        $user->update($data);
    
        return $user;
    }
    

    public function deleteUser(User $user)
    {
        return $user->delete();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }
}
