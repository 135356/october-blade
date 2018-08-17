<?php

namespace Longbang\Llaravel\Controllers;

use Longbang\Llaravel\Controllers\CommonController;
use Longbang\Llaravel\Classes\LanguageClasses;
use Longbang\Llaravel\Api\CCLanguages as CCSHOP;

class LanguageController extends CommonController
{
    public $language_m = null;//语言与货币对象
    private $CCSHOP = null;

    public function __construct()
    {
        $this->CCSHOP = new CCSHOP();
        $this->language_m = new LanguageClasses();
    }

    public static function setLanguage($match_lang=null,$type='language')
    {
        static::newSelf();
        self::$SELF->CCSHOP->isApi();
        switch ($type){
            case 'language':$match = self::$SELF->language_m->match_language($match_lang);
                break;
            case 'currency':$match = self::$SELF->language_m->match_currency($match_lang);
                break;
            case 'ip':$match = self::$SELF->language_m->match_ip($match_lang);/*用ip匹配到的语言*/
                break;
            case 'browser':$match = self::$SELF->language_m->match_browser($match_lang);/*用浏览器匹配到的语言*/
                break;
            default:$match = self::$SELF->language_m->match_language('en-us');/*默认语言*/
        }
        return $match->setLanguage();
    }

    /*获取语言管理里面的所有数据*/
    /*当多语言为关闭，或功能不建全时，不能调用isApi()而是调用默认原始的数据模型*/
    public static function getLanguageAll()
    {
        self::newSelf();
        return self::$SELF->language_m->divideCountryLanguageCurrency;
    }
}