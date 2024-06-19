<?php

namespace Core\UseCases;

use Core\Entities\Transaction;
use Core\DTOs\TransactionDTO;
use Core\Exceptions\TransactionFailedException;
use Core\Ports\PersonRepository;
use Core\Ports\TransactionRepository;
use Core\Ports\UuidGeneratorProvider;
use Core\Ports\WalletRepository;
use Core\Ports\TransactionAuthorizerProvider;
use Core\Ports\DBTransactionProvider;

class TransferBetweenUsers
{
    public function __construct(
        private readonly PersonRepository              $personRepository,
        private readonly TransactionRepository         $transactionRepository,
        private readonly WalletRepository              $walletRepository,
        private readonly UuidGeneratorProvider         $uuidGeneratorProvider,
        private readonly TransactionAuthorizerProvider $authorizerTransactionProvider,
        private readonly DBTransactionProvider         $dbTransactionProvider
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
        $transactionEntity->transfer($originPerson, $destinationPerson);

        if (! $this->authorizerTransactionProvider->execute($transactionEntity)) {
            throw new TransactionFailedException('Transaction not authorized.');
        }
        $save   = [];
        $save[] = $this->walletRepository->save($originPerson->getWallet());
        $save[] = $this->walletRepository->save($destinationPerson->getWallet());
        $save[] = $this->transactionRepository->save($transactionEntity);
        if (in_array(false, $save, true)) {
            $this->dbTransactionProvider->rollback();
            throw new TransactionFailedException('Transaction failed.');
        }

        $this->dbTransactionProvider->commit();
        return $transactionDTO;
    }
}
