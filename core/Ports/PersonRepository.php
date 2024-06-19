<?php

namespace Core\Ports;

use Core\Entities\Person;

interface PersonRepository
{
    public function get(int $id): ?Person;
    public function save(Person $person): ?bool;
}

