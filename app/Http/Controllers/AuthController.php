<?php
namespace App\Http\Controllers;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // تسجيل المستخدمين
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
            'message' => 'Registration successful'
        ], 201);    }

   // تسجيل دخول مستخدم
   public function login(Request $request)
   {
       $credentials = $request->only('email', 'password');

       try {
           if (! $token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'Invalid credentials'], 401);
           }
       } catch (JWTException $e) {
           return response()->json(['error' => 'Could not create token'], 500);
       }

       $user = Auth::user();

       return response()->json([
           'user' => [
               'name' => $user->name,
               'email' => $user->email,
           ],
           'token' => $token,
           'message' => 'Login successful'
       ]);
   }
    // تسجيل خروج المستخدم
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not log out'], 500);
        }
    }
}
