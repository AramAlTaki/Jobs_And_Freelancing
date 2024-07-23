<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FreelancePost;
use App\Models\User;

class FreelancingOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_photo',
        'gender',
        'phone_number',
        'bio',
        'languages',
        'location'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function posts() {
        return $this->hasMany(FreelancePost::class);
    }
}
