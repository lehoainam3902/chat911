<?php

// app/Http/Controllers/FacebookWebhookController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacebookWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $data = $request->json()->all();

        // Xử lý sự kiện từ Facebook ở đây
        if (isset($data['entry'][0]['messaging'][0]['message'])) {
            $message = $data['entry'][0]['messaging'][0]['message'];
            // Xử lý tin nhắn mới ở đây
        }
        // Trả về một response 200 OK để báo hiệu cho Facebook rằng bạn đã nhận được sự kiện thành công
        return response('Event Received', 200);
    }
}
