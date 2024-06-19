<?php

namespace Core\Ports;

interface TransactionAuthorizerProvider
{
    public function execute(): bool;
}
