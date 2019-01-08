<?php

namespace Lonban\Llaravel\Classes;

class CodeClasses extends CommonClasses
{
    public $data = null;

    public function __construct()
    {}

    public static function utf8($arr=null)
    {
        if(isset($arr)){
            if(is_array($arr)){
                $data=null;
                foreach($arr as $k=>$v){
                    if(is_array($v)){
                        $data[$k] = self::utf8($v);
                    }else{
                        $data[$k] = $v;
                        $encode = mb_detect_encoding($v, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                        if($encode != 'UTF-8'){
                            $data[$k] = mb_convert_encoding($v, 'UTF-8', $encode);
                        }
                    }
                }
                return $data;
            }else{
                $encode = mb_detect_encoding($arr, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                if($encode != 'UTF-8'){
                    return mb_convert_encoding($arr, 'UTF-8', $encode);
                }
            }
        }
        return null;
    }
}