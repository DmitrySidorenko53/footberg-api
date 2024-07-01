<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_education', 'education_id', 'user_id')->using(UserEducation::class);
    }
}
