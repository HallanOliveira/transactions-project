<?php

namespace Core\UseCases;

use Core\Entities\Transaction;
use Core\DTOs\TransactionDTO;
use Core\Ports\PersonRepository;

class TransferBetweenUsers
{
    public function __construct(
        private readonly PersonRepository $personRepository
    ) {
    }

    public function execute(TransactionDTO $transactionDTO): TransactionDTO
    {
        $transactionEntity = Transaction::create(
            $transactionDTO->person_origin_id,
            $transactionDTO->type,
            $transactionDTO->amount,
            $this->personRepository,
            $transactionDTO->person_destination_id
        );
        $transactionEntity->transfer();
        return $transactionDTO;

    }
}
