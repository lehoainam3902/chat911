<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Xử lý thông báo webhook ở đây
        // $request chứa dữ liệu từ webhook

        // Phản hồi 200 OK để xác nhận webhook
        return response('Webhook received', 200);
    }
}