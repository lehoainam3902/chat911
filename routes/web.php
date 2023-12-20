<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FacebookWebhookController;
use App\Http\Controllers\Webhook;
use Laravel\Socialite\Facades\Socialite;

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
    echo env('APP_URL');
    return view('welcome');
})->name('home');

Route::get('/webhook', [Webhook::class, 'webhookVerify']);
Route::post('/webhook', [Webhook::class, 'webhookHandler']);
Route::middleware(['auth.facebook'])->group(function () {
    Route::post('/send-message', [FacebookChatController::class, 'sendMessage'])->name('send-message');
    Route::get('/get-inbox', [FacebookChatController::class, 'getInbox'])->name('get-inbox');
    Route::get('/mess-details/{conversationId}', [FacebookChatController::class, 'getConversationDetail'])->name('mess-details');
});
// Đăng nhập
Route::get ('auth/facebook', function(){
    return Socialite::driver('facebook') -> redirect();
});

Route::get ('auth/facebook/callback', function(){
    $user = Socialite::driver('facebook')->user();
    dd($user);
});
