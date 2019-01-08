<?php namespace Lonban\Ooctober\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLongbangOoctoberMultilanguage extends Migration
{
    public function up()
    {
        Schema::create('longbang_ooctober_multilanguage', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('language_package_m', 128)->nullable();
            $table->string('language_m', 128)->nullable();
            $table->string('currency_m', 128)->nullable();
            $table->string('set_language_c', 128)->nullable();
            $table->string('set_currency_c', 128)->nullable();
            $table->boolean('get_lang_mode')->nullable()->default(0);
            $table->boolean('is_enabled')->nullable()->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('longbang_ooctober_multilanguage');
    }
}
