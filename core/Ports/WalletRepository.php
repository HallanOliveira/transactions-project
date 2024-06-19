<?php

namespace Core\Ports;

use Core\Entities\Wallet;

interface WalletRepository
{
    public function get(int $id): ?Wallet;
    public function save(Wallet $wallet): ?bool;
}
