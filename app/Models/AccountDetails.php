<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class AccountDetails
 *
 * @property int $user_id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $work_place
 * @property string $position
 * @property string $specialization
 * @property Carbon $birth_date
 */
class AccountDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $table = 'account_details';

    protected $guarded = [];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
