<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class RoleUser
 *
 * @property int $user_id
 * @property int $role_id
 */
class RoleUser extends Pivot
{
    use HasFactory;

    protected $table = 'role_user';

    protected $primaryKey = ['user_id', 'role_id'];

    public $timestamps = false;
}
