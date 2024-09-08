<?php
namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && Auth::user()->role == true;
    }

    public function rules()

    {
        $userId = $this->route('user');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user,
            'password' => $this->isMethod('post') ? 'required|string|min:6|confirmed' : 'nullable|string|min:6|confirmed',
            'role' => 'nullable|in:admin,user',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The user name is required.',
            'email.required' => 'The email is required.',
            'email.unique' => 'This email is already taken.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'user name',
            'email' => 'email address',
            'password' => 'password',
        ];
    }
}

