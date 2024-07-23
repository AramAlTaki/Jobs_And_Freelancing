<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobSeeker;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'degree',
        'at',
        'specialized_in',
        'from',
    ];

    public function job_seeker(){
        return $this->belongsTo(JobSeeker::class);
    }

}
