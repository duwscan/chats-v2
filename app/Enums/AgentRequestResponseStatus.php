<?php

namespace App\Enums;

enum AgentRequestResponseStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
}
