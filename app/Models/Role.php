<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Role
 *
 * @property int $role_id
 * @property string $role_name
 */
class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $table = 'roles';

    protected $fillable = [
        'role_name',
    ];

    public $timestamps = false;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')->using(RoleUser::class);
    }
}
