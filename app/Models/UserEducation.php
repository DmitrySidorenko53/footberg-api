<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UserEducation
 *
 * @property int $user_id
 * @property int $education_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 */
class UserEducation extends Pivot
{
    use HasFactory;

    protected $table = 'user_education';

    protected $primaryKey = ['user_id', 'education_id'];
    protected $guarded = [];

    public $timestamps = false;
}
