<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacebookChatController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
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

 
Route::get('/auth/redirect', [AuthController::class, 'redirect']) 
    -> name('auth.redirect');
 
Route::get('/auth/callback', [AuthController::class, 'callback'])
    ->name('auth.callback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
