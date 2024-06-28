<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $user_id
 * @property string $email
 * @property string $password
 * @property Carbon $register_at
 * @property Carbon $deleted_at
 * @property Carbon $last_login_at
 * @property bool $is_active
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'register_at',
        'deleted_at',
        'last_login_at',
        'is_active'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    public function codes(): HasMany
    {
        return $this->hasMany(ConfirmationCode::class, 'user_id', 'user_id');
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(SecurityToken::class, 'user_id', 'user_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->using(RoleUser::class);
    }

    public function getLastValidCode($type = 'confirm'): object|null
    {
        return $this->codes()
            ->where('is_expired', false)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function isActiveOrNotDeleted(): bool
    {
        return $this->is_active && empty($this->deleted_at);
    }
}
