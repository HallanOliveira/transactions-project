<?php

namespace Core\Entities;

use Core\Exceptions\PersonTypeInvalidException;
use Core\Exceptions\DataNotFoundException;
use Core\Ports\PersonRepository;

class Transaction
{
    public function __construct(
        private readonly int              $person_origin_id,
        private readonly int              $person_destination_id,
        private readonly string           $type,
        private readonly float            $amount,
        private readonly PersonRepository $personRepository
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

    /**
     * @return bool|null
     * @throws DataNotFoundException
     * @throws PersonTypeInvalidException
     */
    public function transfer(): ?bool
    {
        $person = $this->personRepository->get($this->person_origin_id);
        if (empty($person)) {
            throw new DataNotFoundException('Payer not found');
        }
        if ($person->isDealerType()) {
            throw new PersonTypeInvalidException('Transfer not allowed for user type');
        }
        return true;
    }

}
