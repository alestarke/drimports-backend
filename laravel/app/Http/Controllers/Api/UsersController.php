<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::whereNull('deleted_at')->get();

        return response()->json(['success' => true, 'message' => 'Users fetched successfully.', 'data' => $users]);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return response()->json(['success' => true, 'message' => 'User fetched successfully.', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        try {
            $user->delete();

            return response()->json(['success' => true, 'message' => 'User deleted successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'error_code' => 'USER_DELETE_FAILED',
                'message' => 'Failed to delete user. Please try again later.'
            ], 500);
        }
    }

    public function store(RegisterUserRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::create($data);

            return response()->json(['success' => true, 'message' => 'User created successfully', 'data' => $user], 201);
        } catch (\Exception $e) {
            $logData = collect($data)->except(['password', 'password_confirmation']);

            Log::error('User registration failed: ' . $e->getMessage(), [
                'data' => $logData,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'error_code' => 'USER_REGISTRATION_FAILED',
                'message' => 'User registration failed. Please try again later.'
            ], 500);
        }
    }
    
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        try {
            $data = $request->validated();

            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user->update($data);

            return response()->json(['success' => true, 'message' => 'User updated successfully', 'data' => $user]);
        } catch (\Exception $e) {
            Log::error('User update failed: ' . $e->getMessage(), [
                'user_id' => $user->id, 
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true, 
                'error_code' => 'USER_UPDATE_FAILED',
                'message' => 'User update failed. Please try again later.'
            ], 500);
        }
    }
}
