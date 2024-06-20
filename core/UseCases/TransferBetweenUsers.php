<?php

namespace Core\UseCases;

use Core\DTOs\TransactionDTO;
use Core\Entities\Transaction;
use Core\Entities\Person;
use Core\Ports\PersonRepository;
use Core\Ports\TransactionRepository;
use Core\Ports\UuidGeneratorProvider;
use Core\Ports\WalletRepository;
use Core\Ports\TransactionAuthorizerProvider;
use Core\Ports\DBTransactionProvider;
use Core\Ports\TransferEventDispatcher;
use Core\Exceptions\DataNotFoundException;
use Core\Exceptions\TransactionFailedException;
use Core\Exceptions\TransactionUnauthorizedException;

class TransferBetweenUsers
{
    public function __construct(
        private readonly PersonRepository              $personRepository,
        private readonly TransactionRepository         $transactionRepository,
        private readonly WalletRepository              $walletRepository,
        private readonly UuidGeneratorProvider         $uuidGeneratorProvider,
        private readonly TransactionAuthorizerProvider $authorizerTransactionProvider,
        private readonly DBTransactionProvider         $dbTransactionProvider,
        private readonly TransferEventDispatcher       $transferEvent
    ) {
    }

    public function execute(TransactionDTO $transactionDTO): TransactionDTO
    {
        $this->dbTransactionProvider->beginTransaction();

        $transactionEntity = Transaction::create(
            $this->uuidGeneratorProvider,
            $transactionDTO->person_origin_id,
            $transactionDTO->type,
            $transactionDTO->amount,
            $transactionDTO->person_destination_id
        );

        $originPerson      = $this->personRepository->get($transactionDTO->person_origin_id);
        $destinationPerson = $this->personRepository->get($transactionDTO->person_destination_id);
        if (! $originPerson instanceof Person) {
            throw new DataNotFoundException('Payer not found.');
        }

        if (! $destinationPerson instanceof Person) {
            throw new DataNotFoundException('Payee not found.');
        }

        $transactionEntity->transfer($originPerson, $destinationPerson);

        if (! $this->authorizerTransactionProvider->execute($transactionEntity)) {
            $this->dbTransactionProvider->rollback();
            throw new TransactionUnauthorizedException();
        }

        $save   = [];
        $save[] = $this->walletRepository->save($originPerson->getWallet());
        $save[] = $this->walletRepository->save($destinationPerson->getWallet());
        $save[] = $this->transactionRepository->save($transactionEntity);
        if (in_array(false, $save, true)) {
            $this->dbTransactionProvider->rollback();
            throw new TransactionFailedException('Transaction failed.');
        }

        $outputDto = new TransactionDTO(
            id: $transactionEntity->getId(),
            person_origin_id: $transactionEntity->getPersonOriginId(),
            person_destination_id: $transactionEntity->getPersonDestinationId(),
            type: $transactionEntity->getType(),
            amount: $transactionEntity->getAmount()
        );

        $this->dbTransactionProvider->commit();

        $this->transferEvent->dispatch($outputDto);

        return $outputDto;
    }
}
