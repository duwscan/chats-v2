<?php

namespace App\Enums;

enum AgentRequestType: string
{
    case ASSIGNMENT = 'assignment';
    case TRANSFER = 'transfer';
}
