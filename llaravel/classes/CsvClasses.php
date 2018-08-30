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

    //把csv文件按行拆分成数组返回
    public function getAll($file)
    {
        //$file = VcPathClasses::tempDB_path('geoIp/iso639_3166.csv');
        $h = fopen($file,'r');
        $arr=[];
        while ($data = fgetcsv($h)) { //每次读取CSV里面的一行内容
            //转utf8编码//$arr[] = CodeClasses::utf8($data);
            $arr[] = $data;
        }
        fclose($h);
        return $this->data['getAll'] = $arr;
    }

    //获取csv文件里面与$match值匹配的所有信息，$colspan为csv文件里面的具体某一行是否与$match值匹配
    public function getMatch($match='cn',$colspan='all')
    {
        if(empty($match)){return 0;}
        if(empty($this->data['getAll'])){
            $this->getAll();
        }
        $data = [];
        foreach($this->data['getAll'] as $k=>$v){
            if($colspan == 'all'){//所有行的数据合并成一行进行匹配
                $str = null;
                if(is_array($v)){
                    foreach($v as $vv){
                        $str .= $vv.',';
                    }
                }else{
                    $str = $v;
                }
                if(isset($match{strlen($str)})){
                    if(stripos($match,$str) > -1){
                        $data[] = $v;
                    }
                }else{
                    if(stripos($str,$match) > -1){
                        $data[] = $v;
                    }
                }
            }else{//某一行的数据是否匹配
                if(isset($match{strlen($v[$colspan])})){
                    if(stripos($match,$v[$colspan]) > -1){
                        $data[] = $v;
                    }
                }else{
                    if(stripos($v[$colspan],$match) > -1){
                        $data[] = $v;
                    }
                }
            }
        }
        return $this->data['getMatch'] = $data;
    }

    //保存为csv
    public function putCsv($file,$data)
    {
        if(!is_file($file)){
            touch($file);
        }
        chmod($file,0777);
        $h1 = fopen($file,'w');
        //转码
        $data = CodeClasses::utf8($data);
        fwrite($h1, chr(0xEF).chr(0xBB).chr(0xBF));
        //转csv并保存
        $is = null;
        if(is_array($data)){
            foreach($data as $v){
                $is .= fputcsv($h1,$v);
            }
        }else{
            $is .= fputcsv($h1,$data);
        }
        fclose($h1);
        return $is;
    }

    public function __get($variable)
    {
        if(isset($this->data[$variable])){
            return $this->data[$variable];
        }

        return $this->data[$variable];
    }

    //合并数据
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