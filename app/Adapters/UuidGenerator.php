<?php

namespace App\Adapters;

use Core\Ports\UuidGeneratorProvider;
use Illuminate\Support\Str;

class UuidGenerator implements UuidGeneratorProvider
{
    public function generate(): string
    {
        return Str::uuid();
    }
}
