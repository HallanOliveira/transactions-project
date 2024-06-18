<?php

namespace Core\Enums;

Enum TransactionTypes: int
{
    case TRANSFER = 1;
    case DEPOSIT  = 2;
    case WITHDRAW = 3;
}
