<?php namespace Lonban\Llaravel;

use System\Classes\PluginBase;
use Lonban\Llaravel\Classes\VcPathClasses;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            VcPathClasses::parentNamespace_route('Components\VcComponents') => 'vc'
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'vcSrc' => 'vcSrc',
                'vc_string_filtering' => [$this, 'stringFiltering']
            ],
            'functions' => [
                'AAAAAAAAA' => function($string=null)
                {
                    return vcUrl($string);
                }
            ]
        ];
    }

    public function stringFiltering($text=null)
    {
        empty($text);
    }

    public function boot()
    {
        $this->loadViewsFrom(VcPathClasses::views_path(),VC_NAME);
    }
}
