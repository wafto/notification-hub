<?php

namespace App\Enums;

enum Status: string
{
    case STARTED = 'started';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
}