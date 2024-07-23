<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobSeeker;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_title',
        'at',
        'time_spent',
    ];

    public function job_seeker(){
        return $this->belongsTo(JobSeeker::class);
    }
}
