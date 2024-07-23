<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JobSeeker;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        "job_seeker_id",
        "model_type",
        "model_id",
        "cv_url",
        "user_full_name",
        "job_title",
        "status"
    ];

    public function applications()
    {
        return $this->morphTo();
    }

    public function job_seeker() {
        return $this->belongsTo(JobSeeker::class);
    }

}
