<?php namespace Longbang\Cms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLongbangCmsConfigMultilanguage extends Migration
{
    public function up()
    {
        Schema::create('longbang_cms_config_multilanguage', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('language_package_m');
            $table->string('language_m');
            $table->string('currency_m');
            $table->string('set_language_c');
            $table->string('set_currency_c');
            $table->smallInteger('get_lang_mode')->default(0);
            $table->boolean('is_enabled')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('longbang_cms_config_multilanguage');
    }
}
