<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    
    }

    // عرض جميع المستخدمين (للأدمن فقط)
    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();
            return UserResource::collection($users);
        } catch (Exception $e) {
            \Log::error('Error fetching users: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch users.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return response()->json(new UserResource($user), Response::HTTP_CREATED);
        } catch (Exception $e) {
            \Log::error('Error creating user: ' . $e->getMessage());  // Log the error for debugging
            return response()->json(['message' => 'Failed to create user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function update(UserRequest $request, $id)
{
    try {
        // Get the user by ID
        $user = $this->userService->getUserById($id);


        // Pass the validated data to the service to update the user
        $updatedUser = $this->userService->updateUser($user, $request->validated());

        // Return the updated user as a response
        return response()->json(new UserResource($updatedUser), Response::HTTP_OK);
    } catch (Exception $e) {
        // Log the error for debugging
        \Log::error('Error updating user: ' . $e->getMessage());

        // Return a JSON response with an error message
        return response()->json(['message' => 'Failed to update user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}



    // عرض مستخدم معين (للأدمن فقط)
    public function show($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            return new UserResource($user);
        } catch (Exception $e) {
            \Log::error('Error fetching user: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // حذف مستخدم (للأدمن فقط)
    public function destroy($id)
    {
        try {
            $user = $this->userService->getUserById($id);            
            // حذف المستخدم
            $this->userService->deleteUser($user);
            
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete user.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
