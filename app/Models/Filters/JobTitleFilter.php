<?php

namespace App\Models\Filters;
use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\StringFilter;

class JobTitleFilter extends StringFilter
{
    public FilterMode $mode = FilterMode::EQUAL;
    
    protected string $field = 'general_job_title';
}
