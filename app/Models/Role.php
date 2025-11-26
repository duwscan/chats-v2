<?php

namespace App\Models;

use App\Utilities\Acl;

/**
 * @property string $name
 */
class Role extends \Spatie\Permission\Models\Role
{
    public function isAdmin(): bool
    {
        return $this->name == Acl::ROLE_ADMIN;
    }
}
