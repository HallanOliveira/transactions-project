<?php

namespace Core\Ports;

interface UuidGeneratorProvider
{
    public function generate(): string;
}
