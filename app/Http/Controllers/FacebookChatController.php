<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class FacebookChatController extends Controller
{
    
    private $pageId = "172965619235980";
    private $pageAccessToken;
    private $apiVersion = 'v18.0';
    private $httpClient;

    
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->pageAccessToken = "EAAKyVwqS4MMBO0DGpppQEUYeNwkII6HhePWed8g3QLjqZC97rsrLWX2ZBbh3djlpnp7Juq3YBO5ifE0qI7lr5ZBCHSH7Mw2dM6sLw8TbMZAXQXiH1SHcy7f329ZC0ZB8Br7kNSob66cO9TqeD3BAZAxIsv5GcdxiHer32iNbEwZA2P9xVUSBYtB8ZCL9RhauYTXv0WegYKRBZArlzpR9nbz1fmkZC4ZD";
    }

    public function getInbox()
    { 
    //     if (!Auth::check()) {
    //     return redirect('/login/facebook')->with('error', 'Bạn cần đăng nhập bằng Facebook để truy cập.');
    // }
        $pageAccessToken = env("FACEBOOK_ACCESS_TOKEN");
        $pageId = $this->pageId;
        $apiUrl = "https://graph.facebook.com/{$this->apiVersion}/{$pageId}/conversations";

        $response = $this->httpClient
            ->withToken($pageAccessToken)
            ->get($apiUrl, ["fields" => "id,participants{name},messages{from,to,sticker,message}"])
            ->json();

        return view("chat", ["conversations" => $response["data"]]);
    }

    public function getConversationDetail($conversationId)
    {
        $pageAccessToken = $this->pageAccessToken;
        $pageId = $this->pageId;

        $response = $this->httpClient
            ->withToken($pageAccessToken)
            ->get("https://graph.facebook.com/{$this->apiVersion}/{$conversationId}", [
                "fields" => "messages{from,message,sticker,created_time,attachments{image_data}},participants{id}",
            ])
            ->json();

        $messages = $response["messages"]["data"] ?? [];
        $participants = $response["participants"]["data"][0]["id"] ?? [];

        return response()->json([
            "participants" => $participants,
            "messages" => $messages,
            "pageId" => $pageId,
        ]);
    }
    public function sendMessage(Request $request){
        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->pageId}/messages?access_token={$this->pageAccessToken}";
        if ($request->has('imageUrl')){
            $response = Http::post($url, [
                'messaging_type' => 'RESPONSE',
                'recipient' => ['id' => $request->recipientId],
                'message' => [
                    'attachment' => [
                        'type' => 'image',
                        'payload' => [
                            'url' => $request->imageUrl,
                            'is_reusable' => true,
                        ],
                    ],
                ],
            ]);
        }else{
            $response = Http::post($url, [
                'messaging_type' => 'RESPONSE',
                'recipient' => ['id' => $request->recipientId],
                'message' => ['text' => $request->messageText],
            ]);
        }
        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Tin nhắn đã được gửi thành công!']);
            
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gửi tin nhắn thất bại.', 'status_code' => $response->status()]);
        }   

    }
}
