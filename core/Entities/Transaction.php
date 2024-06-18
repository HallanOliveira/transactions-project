<?php

namespace Core\Entities;

use Core\Exceptions\PersonTypeInvalidException;
use Core\Exceptions\DataNotFoundException;
use Core\Ports\PersonRepository;

class Transaction
{
    public function __construct(
        public readonly int    $person_origin_id,
        public readonly int    $person_destination_id,
        public readonly string $type,
        public readonly float  $amount,
    ) {
    }

    public static function create(
        int              $person_origin_id,
        string           $type,
        float            $amount,
        PersonRepository $personRepository,
        ?int             $person_destination_id = null
    ): Transaction {
        return new Transaction($person_origin_id, $person_destination_id, $type, $amount, $personRepository);
    }
}
