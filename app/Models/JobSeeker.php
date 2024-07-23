<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Application;

class JobSeeker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_photo',
        'gender',
        'date_of_birth',
        'country',
        'city',
        'languages',
        'search_for',
        'additional_information',
        'cv',
    ];

    protected $casts = [
        'search_for' => 'array'
    ];

    public function experiences(){
        return $this->hasMany(Experience::class);
    }

    public function education(){
        return $this->hasMany(Education::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }
}
