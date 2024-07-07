<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class EducationalDegree
 *
 * @property string $degree
 * @property string $description
 */
class EducationalDegree extends Model
{
    use HasFactory;

    protected $primaryKey = 'degree';

    protected $keyType = 'string';

    protected $table = 'educational_degrees';

    protected $fillable = [
        'degree',
        'description'
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function educationalInstitutions(): HasMany
    {
        return $this->hasMany(EducationalInstitution::class, 'degree_id', 'degree');
    }

    public function getInstitutionCount(): int
    {
        return $this->educationalInstitutions()->count();
    }
}
