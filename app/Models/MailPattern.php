<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MailPattern
 *
 * @property string $scope
 * @property string $subject
 * @property string $title
 * @property string $body
 * @property string $footer
 */
class MailPattern extends Model
{
    use HasFactory;
    protected $primaryKey = 'scope';
    public $incrementing = false;
    protected $guarded = [];
    protected $table = 'mail_patterns';
    protected $keyType = 'string';
    public $timestamps = false;
}
