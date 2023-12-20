<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Webhook extends Controller
{
    public function webhookVerify(Request $request)
    {
        $verify_token = "3902";
        $token = $request->query('hub_verify_token');
        $mode = $request->query('hub_mode');
        $challenge = $request->query('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verify_token) {
                Log::info('WEBHOOK_VERIFIED');
                return response($challenge, 200);
            } else {
                return response('Forbidden', 403);
            }
        }
        return response('Invalid Request', 400);
    }
    
    // public function webhookHandler(Request $request){
    //     $body = $request->all();
    //     Log::info('Received webhook:', $body);
    //     if ($body['object'] === 'page') {
    //         return response('EVENT_RECEIVED', 200);
    //     } else {
    //         return response()->json(['error' => 'Not Found'], 404);
    //     }
    // }
} 
