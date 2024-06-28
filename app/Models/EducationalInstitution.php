<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class EducationalInstitution
 *
 * @property int $id
 * @property string $title
 * @property string $degree
 */
class EducationalInstitution extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = 'educational_institutions';

    protected $fillable = [
        'title',
        'degree'
    ];
    public $timestamps = false;

    public function degree(): BelongsTo
    {
        return $this->belongsTo(EducationalDegree::class, 'degree');
    }
}
