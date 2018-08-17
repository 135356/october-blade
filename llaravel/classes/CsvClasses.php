<?php

namespace Longbang\Llaravel\Classes;

use Longbang\Llaravel\Classes\CodeClasses;
use Longbang\Llaravel\Classes\VcPathClasses;
use Longbang\Llaravel\Classes\CommonClasses;

class CsvClasses extends CommonClasses
{
    public $data = null;

    public function __construct()
    {}

    public function getAll()
    {
        $file = VcPathClasses::tempDB_path('geoIp/iso639_3166.csv');
        $h = fopen($file,'r');
        //$h2 = fopen(VcPathClasses::classes_path('geoIp/iso31662.csv'),'w');
        $arr=[];
        while ($data = fgetcsv($h)) { //每次读取CSV里面的一行内容
            //$arr[] = CodeClasses::utf8($data);
            $arr[] = $data;
        }
        fclose($h);
        return $this->data['getAll'] = $arr;
    }

    public function getMatch($match=null)
    {
        if(empty($this->data['getAll'])){
            $this->getAll();
        }
        $data = [];
        foreach($this->data['getAll'] as $k=>$v){
            if(isset($match{1})){
                $str = $v[1].$v[2];
                if(isset($match{2})){
                    if(stripos($str,$match) > -1){
                        $data[] = $v;
                    }
                }else{
                    if(stripos(substr($str,0,2),$match) > -1){
                        $data[] = $v;
                    }
                }
            }else{
                if(stripos($v[0]{0},$match) > -1){
                    $data[] = $v;
                }
            }
        }
        return $this->data['getMatch'] = $data;
    }

    public function __get($variable)
    {
        if(isset($this->data[$variable])){
            return $this->data[$variable];
        }

        return $this->data[$variable];
    }

    public function aaaaaa()
    {
        return null;
        $h1 = fopen(VcPathClasses::tempDB_path('geoIp/iso3166.csv'),'r');
        $h2 = fopen(VcPathClasses::tempDB_path('geoIp/iso639_3166Y.csv'),'r');
        $h3 = fopen(VcPathClasses::tempDB_path('geoIp/iso639_3166.csv'),'w');
        $arr3166=[];
        while ($data = fgetcsv($h1)) { //每次读取CSV里面的一行内容
            $arr3166[] = CodeClasses::utf8($data);
        }
        $iso639_3166Y=[];
        while ($data = fgetcsv($h2)) { //每次读取CSV里面的一行内容
            $iso639_3166Y[] = CodeClasses::utf8($data);
        }
        $i = 0;
        fwrite($h3, chr(0xEF).chr(0xBB).chr(0xBF));
        while($data = next($arr3166)){
            $i++;
            foreach($iso639_3166Y as $v){
                $v[3] = $v[2];/*要包含了语言与国家的名字*/
                $v[2] = $data[1];/*在把它们的英文名字加上*/
                if($data[0] == $v[1]){
                    fputcsv($h3,$v);
                }
            }
        }
        fclose($h1);
        fclose($h2);
        fclose($h3);
    }
}