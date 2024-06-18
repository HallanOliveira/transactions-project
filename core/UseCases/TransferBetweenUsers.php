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
        $originPerson      = $this->personRepository->get($transactionDTO->person_origin_id);
        $destinationPerson = $this->personRepository->get($transactionDTO->person_destination_id);
        $originPerson->transfer($destinationPerson, $transactionDTO->amount);

        $transactionEntity = Transaction::create(
            $transactionDTO->person_origin_id,
            $transactionDTO->type,
            $transactionDTO->amount,
            $this->personRepository,
            $transactionDTO->person_destination_id
        );
        return $transactionDTO;

    }
}
