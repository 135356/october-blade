<?php namespace Longbang\Cms\Models;

use Model;

/**
 * Model
 */
class ConfigMultilanguage extends Model
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
    public $table = 'longbang_cms_config_multilanguage';
}
