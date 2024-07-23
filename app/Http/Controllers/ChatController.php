<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetChatRequest $request)
    {
        $data = $request -> validated();

        $isPrivate = 1;
        if($request->has('is_private')) {
            $isPrivate = (int) $data['is_private'];
        }

        $chats = Chat::where('is_private',$isPrivate)
            ->hasParticipant(auth()->user()->id)
            ->whereHas('messages')
            ->with('lastMessage.user','participants.user')
            ->latest('updated_at')
            ->get();

        return response()->json([
            'status' => "1",
            'chats' => $chats,
            'user' => auth()->user()->id
        ],200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChatRequest $request)
    {
        $data = $this -> prepareStoreData($request);
        if($data['user_id'] === $data['otherUserId']) {
            return response()->json([
                'status' => "0",
                'errors' => "Can not Create A Chat With Your Own",
            ],400);
        }

        $previousChat = $this -> getPreviousChat($data['otherUserId']);
        if($previousChat === null) {
            $chat = Chat::create($data['data']);
            $chat -> participants()->createMany([
                [
                    'user_id' => $data['user_id']
                ],
                [
                    'user_id' => $data['otherUserId']
                ]
            ]);

            $chat->load('lastMessage.user','participants.user');
            return response()->json([
                'status' => "1",
                'chat' => $chat,
            ],201);
        }

        return response()->json([
            'status' => "1",
            'chat' => $previousChat->load('lastMessage.user','participants.user'),
        ],200);

    }

    private function prepareStoreData(StoreChatRequest $request) {

        $data = $request -> validated();

        $otherUserId = (int) $data['user_id'];

        unset($data['user_id']);

        $data['created_by'] = auth() -> user() -> id;

        return [
            'otherUserId' => $otherUserId,
            'user_id' => auth() -> user() -> id,
            'data' => $data
        ];
    }

    private function getPreviousChat(int $otherUserId) {

        $userId = auth() -> user() -> id;

        return Chat::where('is_private',1)
            ->whereHas('participants',function($query) use ($userId) {
                $query -> where('user_id',$userId);
            })
            ->whereHas('participants',function($query) use ($otherUserId) {
                $query -> where('user_id',$otherUserId);
            })->first();
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $chat->load('lastMessage.user','participants.user');
        return response()->json([
            'status' => "1",
            'chat' => $chat,
        ],200);
    }
}
