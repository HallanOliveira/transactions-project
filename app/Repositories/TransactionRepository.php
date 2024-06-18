<?php

namespace Core\Repositories;

use App\Models\Transaction;
use Core\Entities\Transaction as EntityTransaction;
use Core\Ports\TransactionRepository as TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function get(int $id): ?EntityTransaction
    {
        $transaction = Transaction::find($id);
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
}
