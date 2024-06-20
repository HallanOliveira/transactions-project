<?php

namespace App\Repositories;

use App\Models\Transaction;
use Core\Entities\Transaction as EntityTransaction;
use Core\Ports\TransactionRepository as TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function get(string $transactionId): ?EntityTransaction
    {
        $transaction = Transaction::find($transactionId);
        if (empty($transaction)) {
            return null;
        }
        return new EntityTransaction(
            $transaction->id,
            $transaction->person_origin_id,
            $transaction->type,
            $transaction->amount,
            $transaction->person_destination_id,
            $transaction->created_at
        );
    }

    public function save(EntityTransaction $transaction): bool
    {
        return Transaction::query()->upsert([
            'id'                    => $transaction->getId(),
            'person_origin_id'      => $transaction->getPersonOriginId(),
            'type'                  => $transaction->getType(),
            'amount'                => $transaction->getAmount(),
            'person_destination_id' => $transaction->getPersonDestinationId(),
        ],'id');
    }
}
