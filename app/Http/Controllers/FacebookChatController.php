<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FacebookChatController extends Controller
{
    
    private $pageId = "172965619235980";
    private $pageAccessToken;
    private $apiVersion = 'v18.0';
    private $httpClient;

    
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->pageAccessToken = "EAAKyVwqS4MMBO2RBsJTTGbiqOyI0BifBg8vZAqU0FA8ZBJux903wVzVs60mbRw1ILTZA0h9xZB6o2uF3ItyFctteRG5DTiuUqXgLSgErHFL2zC64FlgcQGsfVaxk3yLpnU4OtNF1fLo4TJKckGzHKLCKF4y8MFW7ZBivV9D7ESaIMHKhiE0Ig2zUxTsZAoLECAYRtkSLTVssg0ZCJoEkDx1QyZBi ";
    }

    public function     getInbox()
    { 
        if (!Auth::check()) {
        return redirect('/login/facebook')->with('error', 'Bạn cần đăng nhập bằng Facebook để truy cập.');
     }
        $pageAccessToken = $this->pageAccessToken;
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
