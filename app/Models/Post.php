<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\Company;
use App\Models\Filters\EnrollmentStatusFilter;
use App\Models\Filters\JobTitleFilter;
use App\Models\Filters\SpecializationFilter;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Lacodix\LaravelModelFilter\Traits\IsSearchable;
use Laravel\Scout\Searchable;
use LamaLama\Wishlist\Wishlistable;

class Post extends Model
{
    use HasFactory, Wishlistable, Searchable, HasFilters, IsSearchable;

    protected $fillable = [
        'company_id',
        'general_job_title',
        'company_name',
        'company_logo',
        'company_location',
        'specialization',
        'enrollment_status',
        'prefered_gender',
        'prefered_experience',
        'detailed_location',
        'requirements',
        'promises',
        'job_information',
        'application_deadline',
        'expected_salary',
        'is_taken',
    ];

    protected array $filters = [
        JobTitleFilter::class,
        SpecializationFilter::class,
        EnrollmentStatusFilter::class,
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query) {
        return $query->where('application_deadline','>=',Carbon::now())->where('is_taken',false);
    }

    public function searchable(): array
    {
        return [
            'general-job_title',
            'specialization',
            'enrollment_status'
        ];
    }

    public function toSearchableArray()
    {
        return [
            'specialization' => $this->specialization,
            'general_job_title' => $this->general_job_title,
            'company_name' => $this->company_name
        ];
    }

    public function applications()
    {
        return $this->morphMany(Application::class, 'applicationable');
    }
    
}
