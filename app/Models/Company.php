<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_email',
        'company_website',
        'company_logo',
        'company_location',
        'company_industry',
        'company_size',
        'description',
        'founding_year',
        'social_media_links',
    ];

    protected $casts = [
        'social_media_links' => 'array'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }
}
