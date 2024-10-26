<?php

namespace App\Http\Controllers;

use Ably\AblyRest;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class WebSocketController extends Controller
{
    /**
     * Send a message via WebSockets
     *
     * @OA\Post(
     *     path="/api/sendMessage",
     *     tags={"Messages"},
     *     summary="Send a message via WebSocket broadcast",
     *     description="Sends a message and broadcasts it to other clients via WebSocket",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="string", example="Hello, World!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Message sent",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Message sent!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function sendMessage(Request $request)
    {
        $data = [
            'message' => 'Hello, this is a message from Laravel!',
            // Add any additional data you want to send
        ];

        // broadcast(new MessageSent($data))->toOthers();
        $client = new AblyRest('6jY4lw.wSFrTw:_FCLjMx0u5670bHjTKtGwKEj42beEriZu1190F-OY38');
        $channel = $client->channel('chat');
        $channel->publish('MessageSent', 'Hello!');
        return response()->json(['status' => 'Message sent!']);
    }
}
