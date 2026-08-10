<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        
        Route::get('/auth/user', function (Request $request) {
            $user = $request->user()->load('role');
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo ? url('uploads/avatars/' . $user->profile_photo) : null,
                'role' => $user->role->role_name ?? null,
            ]);
        });
    });
});
