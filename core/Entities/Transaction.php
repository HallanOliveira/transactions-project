<?php

namespace Core\Entities;

use Core\Exceptions\PersonTypeInvalidException;
use Core\Ports\UuidGeneratorProvider;

class Transaction extends BaseEntity
{
    public function __construct(
        private string $id,
        private int    $person_origin_id,
        private ?int   $person_destination_id,
        private int    $type,
        private float  $amount,
    ) {
    }

    public static function create(
        UuidGeneratorProvider $uuidGeneratorProvider,
        int                   $person_origin_id,
        string                $type,
        float                 $amount,
        ?int                  $person_destination_id = null
    ): Transaction {
        $id = $uuidGeneratorProvider->generate();
        return new Transaction(
            $id,
            $person_origin_id,
            $person_destination_id,
            $type,
            $amount
        );
    }

    public function transfer(Person $originPerson, Person $destinationPerson): void
    {
        if (! $originPerson->canPerformTransactions()) {
            throw new PersonTypeInvalidException('Payer cannot perform transactions, because it is a dealer.');
        }
        $originPerson->getWallet()->withdraw($this->amount);
        $destinationPerson->getWallet()->deposit($this->amount);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPersonOriginId()
    {
        return $this->person_origin_id;
    }

    public function getPersonDestinationId()
    {
        return $this->person_destination_id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
