<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PassageController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\User\IELTSController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Public)
|--------------------------------------------------------------------------
*/
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (ROLE: ADMIN ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // PASSAGES
        Route::get('/passages', [PassageController::class, 'index']);
        Route::post('/passages', [PassageController::class, 'store']);
        Route::get('/passages/{passage}', [PassageController::class, 'show']);
        Route::put('/passages/{passage}', [PassageController::class, 'update']);
        Route::delete('/passages/{passage}', [PassageController::class, 'destroy']);

        // QUESTIONS
        Route::get('/questions', [QuestionController::class, 'index']);
        Route::post('/questions', [QuestionController::class, 'store']);
        Route::get('/questions/{question}', [QuestionController::class, 'show']);
        Route::put('/questions/{question}', [QuestionController::class, 'update']);
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy']);

        // OPTIONS
        Route::get('/options', [OptionController::class, 'index']);
        Route::post('/options', [OptionController::class, 'store']);
        Route::get('/options/{option}', [OptionController::class, 'show']);
        Route::put('/options/{option}', [OptionController::class, 'update']);        
        Route::delete('/options/{option}', [OptionController::class, 'destroy']);
    });

/*
|--------------------------------------------------------------------------
| USER ROUTES (ROLE: USER ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

    Route::get('/exercises', [IELTSController::class, 'index']);
    Route::post('/exercises/submit-answer', [IELTSController::class, 'submitAnswer']);
});
