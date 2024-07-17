<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SupportedLocale
 *
 * @property string $locale
 */
class SupportedLocale extends Model
{
    use HasFactory;

    protected $primaryKey = 'locale';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'supported_locales';

    protected $fillable = [
        'locale'
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
