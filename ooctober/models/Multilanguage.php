<?php namespace Longbang\Ooctober\Models;

use Model;

/**
 * Model
 */
class Multilanguage extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $guarded = [];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'longbang_ooctober_multilanguage';
}
