<?php

namespace Lonban\Llaravel\Components;

use Cms\Classes\CodeBase;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\URL;
use Lonban\Llaravel\Controllers\AreaController;
use Lonban\Llaravel\Controllers\LanguageController;

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

    /*获取客户端地址,返回如{ip:"100.10.1.1",code:"US",country:"United States",city:"Providence",province:"Rhode Island",postcode:"02905",state:1}*/
    public function getAddress()
    {
        return AreaController::getGeo(post('ip',null));
    }
    /* 获取语言管理里面的所有数据，返回所有的语言数据字段如id:2,name:"China/中文",language:"ZH-CN",country:"CN",currency:"CNY",is_default:0,is_enabled:1,sort:0 */
    public function getLanguageAll()
    {
        return LanguageController::getLanguageAll();
    }

    /*
     * 与后台数据库里的语言货币进行匹配并设置,返回设置后的如{language: "zh-cn", currency: "CNY", state: true}
     * <a data-request="onSetLanguage" role="button" data-request-data="language:'220.10.1.1',type:'ip'" data-request-redirect="/">type为ip的时候language可以是一个语言代码如us-en，或ip地址，“/”设置完后跳到首页</a>
     * */
    public function onSetLanguage()
    {
        return LanguageController::setLanguage(post('language',null),post('type','browser'))->setLanguage;
    }
}

