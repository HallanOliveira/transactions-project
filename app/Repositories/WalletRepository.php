<?php

namespace App\Repositories;

use App\Models\Wallet;
use Core\Entities\Wallet as EntityWallet;
use Core\Ports\WalletRepository as WalletRepositoryInterface;

class WalletRepository implements WalletRepositoryInterface
{
    public function get(int $id): ?EntityWallet
    {
        $wallet = Wallet::find($id);

        return empty($wallet)
            ? null
            : new Wallet(
                $wallet->id,
                $wallet->balance,
                $wallet->person_id,
                $wallet->created_at
            );
    }

    public function save(EntityWallet $wallet): ?bool
    {
        return (bool) Wallet::query()->upsert([
            'id'              => $wallet->getId(),
            'person_id'       => $wallet->getPersonId(),
            'balance'         => $wallet->getBalance(),
        ],'id');
    }
}
