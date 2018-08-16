<?php namespace Longbang\Cms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateLongbangCmsConfigMultilanguage extends Migration
{
    public function up()
    {
        Schema::table('longbang_cms_config_multilanguage', function($table)
        {
            $table->increments('id')->nullable(false)->unsigned()->default(null)->change();
            $table->string('language_package_m')->change();
            $table->string('language_m')->change();
            $table->string('currency_m')->change();
            $table->string('set_language_c')->change();
            $table->string('set_currency_c')->change();
            $table->boolean('get_lang_mode')->nullable(false)->unsigned(false)->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('longbang_cms_config_multilanguage', function($table)
        {
            $table->increments('id')->nullable(false)->unsigned()->default(null)->change();
            $table->string('language_package_m', 64)->change();
            $table->string('language_m', 64)->change();
            $table->string('currency_m', 64)->change();
            $table->string('set_language_c', 64)->change();
            $table->string('set_currency_c', 64)->change();
            $table->smallInteger('get_lang_mode')->nullable(false)->unsigned(false)->default(0)->change();
        });
    }
}