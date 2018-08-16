<?php namespace Longbang\Blade;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            namespaces_route('Components\VcComponents') => VC_NAME
        ];
    }

    public function registerSettings()
    {
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'vc_route_route' => 'route_route',
                'vc_assets' => 'assets_src',
                'vc_string_filtering' => [$this, 'stringFiltering']
            ],
            'functions' => [
                'vc_assets_src' => function($str=null)
                {
                    return assets_src($str);
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
        $this->loadViewsFrom(views_path(),VC_NAME);
    }
}
