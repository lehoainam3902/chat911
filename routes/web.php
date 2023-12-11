<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookChatController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('hello');
});
Route::middleware(['auth.facebook'])->group(function () {
    Route::post('/send-message', [FacebookChatController::class, 'sendMessage'])->name('send-message');
    Route::get('/get-inbox', [FacebookChatController::class, 'getInbox'])->name('get-inbox');
    Route::get('/mess-details/{conversationId}', [FacebookChatController::class, 'getConversationDetail'])->name('mess-details');
});

// Route cho đăng nhập bằng Facebook
Route::get('/login/facebook', [AuthController::class, 'redirectToProvider']);
Route::get('/login/facebook/callback', [AuthController::class, 'handleProviderCallback']);
