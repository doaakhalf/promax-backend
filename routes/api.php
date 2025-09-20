<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Public routes
Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);

Route::get('/coaches', [\App\Http\Controllers\CoachController::class, 'index']);


// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/test', function (Request $request) {
        return response()->json([
            'message' => 'Login successful',
            'user' => $request->user(),
          
        ]);
    });
    // Profile completion routes
    Route::post('/complete-athlete-profile', [\App\Http\Controllers\Auth\AuthController::class, 'completeAthleteProfile']);
    Route::post('/complete-coach-profile', [\App\Http\Controllers\Auth\AuthController::class, 'completeCoachProfile']);
});
