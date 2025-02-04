<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;

class Chat extends Model
{
    use HasFactory;

    protected $table = "chats";
    protected $guarded = ["id"];

    public function participants(){
        return $this->hasMany(ChatParticipant::class,'chat_id');
    }

    public function messages(){
        return $this->hasMany(ChatMessage::class,'chat_id');
    }

    public function lastMessage(){
        return $this->hasOne(ChatMessage::class,'chat_id')->latest('updated_at');
    }

    public function scopeHasParticipant($query,int $userId){
        return $query->whereHas('participants',function($q) use ($userId) {
            $q->where('user_id',$userId);
        });
    }
}
