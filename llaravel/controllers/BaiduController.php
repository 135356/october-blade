<?php

namespace Lonban\Llaravel\Controllers;

use Lonban\Llaravel\Classes\BaiduTransapiClasses;
use Lonban\Llaravel\Classes\CsvClasses;
use Lonban\Llaravel\Classes\VcPathClasses;

class BaiduController extends CommonController
{
    public function __construct()
    {
    }

    public function trans()
    {
        $baidu = new BaiduTransapiClasses();
        $csv = new CsvClasses();
        $csv_data = $csv->getAll('LongBang/geoIp/iso639_3166.csv');
        foreach($csv_data as $k=>$v){
            $baidu_data = $baidu->translate($v[2],'en',$v[0]);
            if(isset($baidu_data['trans_result'])){
                $csv_data[$k][4] = $baidu_data['trans_result'][0]['dst'];
            }else{
                $csv_data[$k][4] = $v[2].'!';
            }
        }
        echo $csv->putCsv('LongBang/geoIp/iso639_3166_2.csv',$csv_data);
    }
}