<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Http\Requests\Admin\Auth\RegisterAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterAdminRequest $request)
    {
        $newAdmin = User::create($request->validated());

        return response()->json(['message' => __('messages.registered_successfully'), 'user' => $newAdmin], 201);
    }

    public function login(AdminLoginRequest $request)
    {

        if (! Auth::attempt($request->validated())) {
            return response()->json(['message' => __('messages.invalid_credentials')], 400);
        }
        $user = Auth::user();

        // Check if user is admin
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'message' => __('messages.logged_in_successfully'),
            'token' => $token,
            'user' => $user,
            
        ]);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => __('messages.logged_out_successfully')]);
    }

    public function changePassword(Request $request)
    {
         $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => __('messages.current_password_incorrect'),
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'message' => __('messages.password_changed_successfully'),
        ]); 

        
    }
}
