<?php

namespace Core\Enums;

enum NotificationType: int
{
    case EMAIL         = 1;
    case EMAIL_AND_SMS = 2;
}
