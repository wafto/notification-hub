<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case STARTED = 'started';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
}