<?php

namespace App\Models\Filters;

use Lacodix\LaravelModelFilter\Filters\StringFilter;
use Lacodix\LaravelModelFilter\Enums\FilterMode;

class EnrollmentStatusFilter extends StringFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'enrollment_status';
}
