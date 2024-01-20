<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\CandidateProfileController;


// Public Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/send-reset-password-email', [PasswordResetController::class, 'send_reset_password_email']);
Route::post('/reset-password/{token}', [PasswordResetController::class, 'reset']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/loggeduser', [UserController::class, 'logged_user']);
    Route::post('/changepassword', [UserController::class, 'change_password']);
    Route::post('/stu', [CandidateProfileController::class, 'profile_save']);
    Route::get('/stu', [CandidateProfileController::class, 'profile_list']);
    Route::get('/stu/{id}', [CandidateProfileController::class, 'particular_profile_list']);
    Route::put('/stu/{id}', [CandidateProfileController::class, 'update_profile_list']);
    Route::delete('/stu/{id}', [CandidateProfileController::class, 'delete_profile_list']);
});

