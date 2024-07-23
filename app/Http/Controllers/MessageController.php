<?php

namespace App\Http\Controllers;

use App\Events\NewMessageSent;
use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\StoreMessagesRequest;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a list Of Messages Between Users (Default 15).
     */
    public function index(GetMessagesRequest $request)
    {
        $data = $request->validated();
        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id',$chatId)
        ->with('user')
        ->latest('created_at')
        ->simplePaginate(
            $pageSize,
            ['*'],
            'page',
            $currentPage
        );

        
        return response()->json([
            'status' => "1",
            'messages' => $messages->getCollection(),
        ],200);
    }

    /**
     * Store a newly Sent Message In Database (After Hitting Send).
     */
    public function store(StoreMessagesRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth() -> user() -> id;

        $message = ChatMessage::create($data);
        $message->load('user');

        //Send Notification Message To Others
        $this -> sendNotificationToOther($message);

        return response()->json([
            'status' => "1",
            'chat_message' => $message,
            'message' => "Message is Sent Successfully"
        ],200);
    }

    private function sendNotificationToOther(ChatMessage $chatMessage) {
        broadcast(new NewMessageSent($chatMessage))->toOthers();

        $user = auth() -> user();
        $userId = $user->id;

        $chat = Chat::where('id',$chatMessage->chat_id)->with(['participants' => function($query) use ($userId) {
            $query->where('user_id','!=',$userId);
        }])->first();

        if(count($chat->participants) > 0) {

            $otherUserId = $chat->participants[0]->user_id;
            $otherUser = User::where('id',$otherUserId)->first();
            $otherUser->sendNewMessageNotification([
                'messageData' => [
                    'senderName' => ucfirst($user->first_name) . ' ' . ucfirst($user->last_name),
                    'message' => $chatMessage -> message,
                    'chatId' => $chatMessage -> chat_id,
                ]
            ]);

        }
    }

    /**
     * Display All Users Except The Authenticated User.
     */
    public function show()
    {
        $users = User::where('id','!=',auth()->user()->id)->get();
        return response()->json([
            'status' => "1",
            'users' => $users,
        ],200);
    }
}
