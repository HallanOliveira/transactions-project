<?php

namespace Core\Enums;

enum PersonDocumentType: int
{
    case CPF  = 1;
    case CNPJ = 2;

    public static function values(): array
    {
       return array_column(self::cases(), 'value');
    }
}
