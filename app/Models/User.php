<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $user_id
 * @property string $email
 * @property string $password
 * @property string $confirmation_code
 * @property Carbon $register_at
 * @property Carbon $deleted_at
 * @property Carbon $last_login_at
 * @property Carbon $confirmed_at
 * @property bool $is_confirmed
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
        'confirmation_code',
        'register_at',
        'is_confirmed',
        'confirmed_at',
        'register_at',
        'deleted_at',
        'last_login_at'
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_confirmed' => 'boolean'
        ];
    }
}
