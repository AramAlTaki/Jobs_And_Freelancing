<?php

namespace App\Models\Filters;

use Lacodix\LaravelModelFilter\Filters\StringFilter;

class SpecializationFilter extends StringFilter
{
    protected string $field = 'specialization';
}
