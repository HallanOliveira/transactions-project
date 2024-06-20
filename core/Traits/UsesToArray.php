<?php

namespace Core\Traits;

trait UsesToArray
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
