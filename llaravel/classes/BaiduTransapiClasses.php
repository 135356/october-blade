<?php
/***************************************************************************
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

/**
 * @file baidu_transapi.php
 * @author mouyantao(mouyantao@baidu.com)
 * @date 2015/06/23 14:32:18
 * @brief
 *
 **/
namespace Longbang\Llaravel\Classes;

use Longbang\Llaravel\Classes\CommonClasses;

class BaiduTransapiClasses extends CommonClasses
{
    public $baidu = [];

    public function __construct()
    {
        $this->baidu['CURL_TIMEOUT'] = 10;
        $this->baidu['URL'] = "http://api.fanyi.baidu.com/api/trans/vip/translate";
        $this->baidu['APP_ID'] = "20180830000200450";//替换为您的APPID
        $this->baidu['SEC_KEY'] = "RVy9vqc6mBA0Ckcve6yR";//替换为您的密钥
    }


//翻译入口
    public function translate($query, $from='auto', $to='zh')
    {
        //将apple从英文翻译成中文q=apple + from=en + to=zh + appid=2015063000000001 + salt=1435660288
        $args = array(
            'q' => $query,//请求翻译query
            'appid' => $this->baidu['APP_ID'],//APP ID
            'salt' => rand(10000,99999),//随机数
            'from' => $from,//翻译源语言(可设置为auto)
            'to' => $to,//译文语言(翻译成)
        );
        $args['sign'] = $this->buildSign($query, $this->baidu['APP_ID'], $args['salt'], $this->baidu['SEC_KEY']);
        $ret = $this->call($this->baidu['URL'], $args);
        $ret = json_decode($ret, true);
        return $ret;
    }

//加密
    public function buildSign($query, $appID, $salt, $secKey)
    {
        $str = $appID . $query . $salt . $secKey;
        $ret = md5($str);
        return $ret;
    }

//发起网络请求
    public function call($url, $args=null, $method="post", $testflag = 0, $timeout = null, $headers=array())
    {
        $timeout = isset($timeout)?$timeout:$this->baidu['CURL_TIMEOUT'];
        $ret = false;
        $i = 0;
        while($ret === false)
        {
            if($i > 1)
                break;
            if($i > 0)
            {
                sleep(1);
            }
            $ret = $this->callOnce($url, $args, $method, false, $timeout, $headers);
            $i++;
        }
        return $ret;
    }

    public function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = null, $headers=array())
    {
        $timeout = isset($timeout)?$timeout:$this->baidu['CURL_TIMEOUT'];
        $ch = curl_init();
        if($method == "post")
        {
            $data = $this->convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        else
        {
            $data = $this->convert($args);
            if($data)
            {
                if(stripos($url, "?") > 0)
                {
                    $url .= "&$data";
                }
                else
                {
                    $url .= "?$data";
                }
            }
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!empty($headers))
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if($withCookie)
        {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    public function convert(&$args)
    {
        $data = '';
        if (is_array($args))
        {
            foreach ($args as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k=>$v)
                    {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                }
                else
                {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }
}