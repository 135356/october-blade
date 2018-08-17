<?php

namespace Longbang\Llaravel\Classes;

use Longbang\Llaravel\Classes\CommonClasses;
use Longbang\Llaravel\Classes\VcPathClasses;
use GeoIp2\Database\Reader;

class GeoIp2Classes extends CommonClasses
{
    public static $data = null;

    public function __construct()
    {
    }

    //不同环境下获取真实的IP
    public static function getIp()
    {
        $data = self::$data;
        if(!empty($_GET['ip'])){
            return $_GET['ip'];
        }
        if(!empty($data['real_ip'])){
            return $data['real_ip'];
        }
        //判断服务器是否允许$_SERVER,不允许就使用getenv获取
        if(isset($_SERVER) && defined('HTTP_X_FORWARDED_FOR')){
            if(isset($_SERVER[HTTP_X_FORWARDED_FOR])){
                $data['real_ip'] = $_SERVER[HTTP_X_FORWARDED_FOR];
            }elseif(isset($_SERVER[HTTP_CLIENT_IP])) {
                $data['real_ip'] = $_SERVER[HTTP_CLIENT_IP];
            }else{
                $data['real_ip'] = $_SERVER[REMOTE_ADDR];
            }
        }else{
            if(getenv("HTTP_X_FORWARDED_FOR")){
                $data['real_ip'] = getenv( "HTTP_X_FORWARDED_FOR");
            }elseif(getenv("HTTP_CLIENT_IP")) {
                $data['real_ip'] = getenv("HTTP_CLIENT_IP");
            }else{
                $data['real_ip'] = getenv("REMOTE_ADDR");
            }
        }
        return self::$data['real_ip'] = preg_match ( '/[\d\.]{7,15}/', $data['real_ip'], $matches ) ? $matches [0] : '';
    }

    public static function getGeoIp($ip=null,$type='City')
    {
        if(empty($ip)){
            $ip = self::getIp();
            //$ip = '222.10.200.200';
        }
        $data = array('ip'=>null,'code'=>null,'country'=>null,'province'=>null,'city'=>null,'postcode'=>null,'state'=>null);
        if(empty($ip)||$ip == '127.0.0.1'){return $data;}
        $reader = new Reader(VcPathClasses::tempDB_path('geoIp/GeoLite2-'.$type.'.zip'));//Redis::set('Reader',$reader);
        $record = $reader->$type($ip);
        /*
         * $record->country->isoCode // 'US'
         * $record->country->name // 'United States'
         * $record->country->names['en'] // 'China'
         * $record->mostSpecificSubdivision->name // 'Guangdong'
         * $record->mostSpecificSubdivision->isoCode // 'GD'
         * $record->city->name // 'Guangzhou'
         * $record->postal->code // '邮编'
         * $record->location->latitude // '23.1167纬度'
         * $record->location->longitude // '113.25经度'
        */
        if(!empty($record->country->isoCode)){
            $data['ip'] = $ip;
            $data['code'] = $record->country->isoCode;
            $data['country'] = $record->country->name;
            $data['province'] = $record->mostSpecificSubdivision->name;
            $data['city'] = $record->city->name;
            $data['postcode'] = $record->postal->code;
            $data['state'] = 1;
        }
        return $data;
    }

    public static function getBrowser($languageList=null)
    {
        if (is_null($languageList)) {
            if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                return array();
            }
            $languageList = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        $languages = array();
        $languageRanges = explode(',', trim($languageList));
        foreach ($languageRanges as $languageRange) {
            if (preg_match('/(\*|[a-zA-Z0-9]{1,8}(?:-[a-zA-Z0-9]{1,8})*)(?:\s*;\s*q\s*=\s*(0(?:\.\d{0,3})|1(?:\.0{0,3})))?/', trim($languageRange), $match)) {
                if (!isset($match[2])) {
                    $match[2] = '1.0';
                } else {
                    $match[2] = (string) floatval($match[2]);
                }
                if (!isset($languages[$match[2]])) {
                    $languages[$match[2]] = strtolower($match[1]);
                }
            }
        }
        krsort($languages);

        $data = array('ip'=>null,'code'=>null,'name'=>null,'province'=>null,'city'=>null,'zip'=>null,'state'=>null);
        $languages = reset($languages);
        if(!empty($languages)){
            $data['code'] = $languages;
            $data['state'] = 1;
        }

        return $data;
    }

    public function __get($variable)
    {
        if(empty(self::$data[$variable])){
            return null;
        }else{
            return self::$data[$variable];
        }
    }
}