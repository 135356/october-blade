<?php

namespace Lonban\Llaravel\Controllers;

use Lonban\Llaravel\Classes\GeoIp2Classes;

class AreaController extends CommonController
{
    public function __construct()
    {
    }

    public function getIp()
    {
        return GeoIp2Classes::getIp();
    }

    /*通过ip获取国家、省、市、邮编*/
    public static function getGeo($ip=null,$type='City')
    {
        return GeoIp2Classes::getGeoIp($ip,$type);
    }

    /*通过浏览器获取国家、省、市、邮编*/
    public static function getLocation($languageList=null)
    {
        return GeoIp2Classes::getBrowser($languageList);
    }
}