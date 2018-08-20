<?php

namespace Longbang\Llaravel\Classes;

use Longbang\Llaravel\Classes\CommonClasses;
use Longbang\Llaravel\Classes\GeoIp2Classes;
use Longbang\Llaravel\Api\CCLanguages as CCSHOP;

class LanguageClasses extends CommonClasses
{
    public $data = array();

    public function __construct()
    {
        /*建立连接*/
        $this->data['CCSHOP'] = new CCSHOP();
        $this->data['divideCountryLanguageCurrency'] = $this->data['CCSHOP']->language_M();
    }

    protected function match($lang,$type='language')
    {
        if(is_array($lang)){
            $lang = reset($lang);
        }

        $data = strtolower($lang);
        switch ($data){
            case 'en':$data='en-us';
                break;
            case 'zh':$data='zh-cn';
                break;
        }
        $type = strtolower($type);
        switch ($type){
            case 'language':$type = 'country';$lang = array('language'=>$lang,'currency'=>null);
            break;
            case 'currency':$lang = array('language'=>null,'currency'=>$lang);
            break;
        }
        foreach($this->data['divideCountryLanguageCurrency'] as $k=>$v){
            if(isset($v[$type]{strlen($data)})){
                if(stripos($v[$type],$data) > -1){
                    $lang = $v;
                    break;
                }
            }else{
                if(stripos($data,$v[$type]) > -1){
                    $lang = $v;
                    break;
                }
            }
        }
        if(stripos('zh-cn,zh-hk,zh-tw',$lang['language']) > -1){
            $lang['language'] = 'zh-cn';
        }

        $this->data['match'] = $lang;
        return $this;
    }

    /*与具体的语言匹配,必须传一个具体的语言过来如'en-us'*/
    public function match_language($language)
    {
        return $this->data['match_language'] = $this->match($language);
    }

    /*与具体的货币匹配，必须传一个具体的货币过来如'usd'*/
    public function match_currency($currency)
    {
        return $this->data['match_currency'] = $this->match($currency,'currency');
    }

    /*与浏览器语言匹配,可传一个具体的语言过来'en-us'，没有参数则获取客户端浏览器第一语言*/
    public function match_browser($language=null)
    {
        $data = $language;
        if(empty($data)){
            $data = GeoIp2Classes::getBrowser()['code'];
        }
        return $this->data['match_currency'] = $this->match($data);
    }

    /*与ip匹配,可传一个具体的语言过来'en-us'，也可以传一个ip地址过来，没有参数则获取客户端ip*/
    public function match_ip($language=null)
    {
        $data = $language;
        if(empty($data)){
            $data = GeoIp2Classes::getGeoIp()['code'];
        }else if(isset($data{8})){
            if(filter_var($data, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                $data = GeoIp2Classes::getGeoIp($data)['code'];
            }
        }
        $data = strtolower($data);
        switch ($data){
            case 'jp':$data='ja';
            break;
            case 'ph':$data='fil';
            break;
            case 'my':$data='ms';
            break;
            case 'vn':$data='vi';
            break;
            case 'ng':$data='ig';
            break;
        }
        return $this->data['match_ip'] = $this->match($data);
    }

    public function setLanguage($data = [])
    {
        if(empty($this->data['match'])){
            if(!empty($data['language'])){
                $this->match_language($data['language']);
            }else if(!empty($data['currency'])){
                $this->match_currency($data['currency']);
            }else{
                $this->match_browser();
            }
        }
        $arr = [];
        $arr['language'] = empty($this->data['match']['language'])?null:strtolower($this->data['match']['language']);
        $arr['currency'] = empty($this->data['match']['currency'])?null:strtoupper($this->data['match']['currency']);
        $arr['state'] = $this->data['CCSHOP']->language_C($arr['language']);
        $this->data['CCSHOP']->currency_C($arr['currency']);
        $this->data['setLanguage'] = $arr;
        return $this;
    }

    public static function __callstatic($method,$arg)
    {
        self::newSelf();
        //dump($method);
        /*if(method_exists(self::$SELF,$method)){
            $arg[0] = empty($arg[0])?null:$arg[0];
            self::$SELF->data[$method] = self::$SELF->$method($arg[0]);
        }*/
        return self::$SELF;
    }

    public function __get($variable)
    {
        if(isset($this->data['setLanguage'])){
            if(isset($this->data['setLanguage'][$variable])){
                return $this->data['setLanguage'][$variable];
            }
        }else if(isset($this->data['match'])){
            if(isset($this->data['match'][$variable])){
                return $this->data['match'][$variable];
            }
        }else if(isset($this->data['divideCountryLanguageCurrency'])){
            if(isset($this->data['divideCountryLanguageCurrency'][$variable])){
                return $this->data['divideCountryLanguageCurrency'][$variable];
            }
        }

        return $this->data[$variable];
    }
}