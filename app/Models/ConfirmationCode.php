<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ConfirmationCode
 *
 * @property int $code_id
 * @property int $user_id
 * @property string $code_text
 * @property Carbon $created_at
 * @property Carbon $valid_until
 * @property Carbon $confirmed_at
 * @property bool|int $is_confirmed
 * @property bool|int $is_expired
 */
class ConfirmationCode extends Model
{
    use HasFactory;

    protected $primaryKey = 'code_id';

    protected $table = 'confirmation_codes';

    protected $fillable = [
        'code_text',
        'created_at',
        'valid_until',
        'confirmed_at',
        'is_confirmed',
        'is_expired',
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
            'is_confirmed' => 'boolean',
            'is_expired' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
