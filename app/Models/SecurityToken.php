<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SecurityToken
 *
 * @property string $token
 * @property Carbon $valid_until
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 * @property bool $is_valid
 * @property bool $is_deleted
 * @property int user_id
 */
class SecurityToken extends Model
{
    use HasFactory;

    protected $primaryKey = 'token';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'security_tokens';

    protected $fillable = [
        'token',
        'valid_until',
        'created_at',
        'deleted_at',
        'is_valid',
        'is_deleted',
        'user_id'
    ];

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_valid' => 'boolean',
            'is_deleted' => 'boolean'
        ];
    }

    protected $hidden = [
        'is_valid',
        'is_deleted',
        'deleted_at',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
