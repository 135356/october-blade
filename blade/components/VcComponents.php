<?php

namespace Longbang\Blade\Components;

use Cms\Classes\CodeBase;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\URL;
use Longbang\Blade\Controllers\AreaController;
use Longbang\Blade\Controllers\LanguageController;

class VcComponents extends ComponentBase
{
    public function onRun()
    {
        $this->page['url_full'] = URL::full();
        $this->page['url_current'] = URL::current();
        $this->page['url_previous'] = URL::previous();
    }

    public function componentDetails()
    {
    }

    public function __construct(CodeBase $cmsObject = null, $properties = [])
    {
        parent::__construct($cmsObject, $properties);
    }

    /*获取客户端地址*/
    public function getAddress()
    {
        //echo post('ip',null);
        return AreaController::getGeo('100.10.1.1');
    }

    /*获取语言管理里面的所有数据*/
    public function getLanguageAll()
    {
        return LanguageController::getLanguageAll();
    }

    /*与后台数据库里的语言货币进行匹配并设置*/
    public function onSetLanguage($match_lang=null,$type='language')
    {
        return LanguageController::setLanguage($match_lang,$type);
    }
}

