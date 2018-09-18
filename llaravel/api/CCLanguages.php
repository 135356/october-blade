<?php

namespace Longbang\Llaravel\Api;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Longbang\Llaravel\Classes\VcPathClasses;
use Longbang\Ooctober\Models\Multilanguage;
use Longbang\Llaravel\Classes\CommonClasses;

/*在改变ccshop功能扩展的时候只需要修改这一个文件以兼容*/
class CCLanguages extends CommonClasses
{
    public $data = [];

    //连接
    public function __construct()
    {
        $ConfigMultilanguage = new Multilanguage();
        $data = $ConfigMultilanguage->all()->toArray();
        if(empty($data)){
            $data[0] = ['language_package_m'=>'locale','language_m'=>'language','currency_m'=>'currency','set_language_c'=>'translator','set_currency_c'=>'currencies','get_lang_mode'=>'0','is_enabled'=>'1'];
            $ConfigMultilanguage->create($data[0]);
        }
        foreach($data as $v){
            if($v['is_enabled']){$this->data['config'] = $v;}
        }
        if(empty($this->data)){$this->data['is_enabled']='off';}else{$this->data['is_enabled']='on';}
    }

    public function isApi($type='404')
    {
        $error = null;
        if($this->data['is_enabled']=='off'){
            $error[] = '语言切换功能没有开启';
        }else{
            if($this->language_C('is') == 'error'){$error[] = '语言切换没引入';}
            if($this->currency_C('is') == 'error'){$error[] = '货币切换没引入';}
            if($this->language_M('is') == 'error'){$error[] = '语言管理数据没引入';}
            if($this->currency_M('is') == 'error'){$error[] = '货币管理数据没引入';}
            if($this->languagePackage_M('is') == 'error'){$error[] = '语言包数据没引入';}
        }

        if($error){
            switch($type){
                case '404':echo view(vcViews('pages.languages.404'))->withErrors($error);exit;
                case 'error':return $error;
            }
        }
    }

    //语言切换的控制器
    public function language_C($lang=null)
    {
        switch(strtolower($this->data['config']['set_language_c'])){
            case 'translator':
                if(class_exists('\RainLab\Translate\Classes\Translator')){
                    if($lang=='is'){return 'ok';}
                    $obj = \RainLab\Translate\Classes\Translator::instance();
                    return $obj->setLocale($lang);
                }
            break;
            default:return 'error';
        }
    }

    //货币切换的控制器
    public function currency_C($currency=null)
    {
        switch(strtolower($this->data['config']['set_currency_c'])){
            case 'currencies':
                if(class_exists('\Jason\Ccshop\Controllers\Currencies')){
                    if($currency=='is'){return 'ok';}
                    $obj = new \Jason\Ccshop\Controllers\Currencies();
                    return $obj->switchCurrency($currency);
                }
                break;
            default:return 'error';
        }
    }

    /*
     * LCC分别为，语言language、国家country、货币currency，同时也会储存一个临时经过计算后的文件在tempDB下，避免不必要的资源浪费
     * all 未经加工的模型内的所有数据
     * obj 直接返回数据模型
     * */
    public function getLanguageCountryCurrency($type='LCC')
    {
        //Storage::disk('local')->put('file.txt', 'Contents');
        //echo asset('storage/file.txt');
        //dd(Storage::disk('s3')->put('LongBang/test.txt', '文件内容','public'));
        //dd(Storage::disk('s3')->get('storage/file.txt'));
        //Storage::delete('file.txt');
        //Storage::disk('s3')->deleteDirectory('storage');


        $file = VcPathClasses::tempDB_path('php/divideCountryLanguageCurrency.php');
        /*如果文件与文件夹不存在就创建这个文件与文件夹*/
        if(!is_file($file)){
            //chmod($file,0777);use October\Rain\Database\Attach\File;use Illuminate\Support\Facades\Storage;
            if(!file_exists($file.'/php')){
                mkdir($file.'/php', 0777);
            }
            touch($file.'/php/divideCountryLanguageCurrency.php');
            chmod($file.'/php/divideCountryLanguageCurrency.php',0777);
            $file = VcPathClasses::tempDB_path('php/divideCountryLanguageCurrency.php');
        }
        switch($type){
            case 'LCC':
                include_once $file;
                if(!empty($data_file['divideCountryLanguageCurrency']))return $data_file['divideCountryLanguageCurrency'];
                if(empty($data_file['getAll']))$data_file['getAll'] = \Jason\Ccshop\Models\Language::orderBy('sort','ASC')->get()->toArray();
                break;
            case 'all':unlink($file);return \Jason\Ccshop\Models\Language::orderBy('sort','ASC')->get();
            case 'obj':unlink($file);return new \Jason\Ccshop\Models\Language();
        }

        $arr = array();
        foreach($data_file['getAll'] as $k=>$v){
            $arr[$k] = array('id'=>$v['id'],'name'=>$v['name'],'is_enabled'=>$v['is_enabled'],'is_default'=>$v['is_default'],'sort'=>$v['sort'],'language'=>null,'country'=>null,'currency'=>null);
            $data = explode('~',$v['code']);
            if(empty($data[0])||empty($data[1])){
                break;
            }
            $language = stripos($data[0],'-');
            if($language > -1){
                $arr[$k]['language'] = substr($data[0],0,$language);
                $arr[$k]['country'] = ltrim(substr($data[0],$language),'-');
                if(stripos($data[0],'zh') > -1){
                    $arr[$k]['language'] = 'ZH-CN';
                }
            }else{
                $arr[$k]['language'] = $data[0];
                $arr[$k]['country'] = $data[0];
            }
            $arr[$k]['currency'] = $data[1];
        }
        $data_file['divideCountryLanguageCurrency'] = $arr;
        $file_data = '<?php $data_file='.var_export($data_file,true).';';
        file_put_contents($file,$file_data);
        return $arr;
    }

    //语言数据
    public function language_M($type='LCC',$type2=null)
    {
        if($type2 == 'off'){
            $data_file['getAll'] = \Jason\Ccshop\Models\Language::orderBy('sort','ASC')->get()->toArray();
            foreach($data_file['getAll'] as $k=>$v){
                $arr[$k] = array('id'=>$v['id'],'name'=>$v['name'],'is_enabled'=>$v['is_enabled'],'is_default'=>$v['is_default'],'sort'=>$v['sort'],'language'=>null,'country'=>null,'currency'=>null);
                $data = explode('~',$v['code']);
                if(empty($data[0])||empty($data[1])){
                    break;
                }
                $language = stripos($data[0],'-');
                if($language > -1){
                    $arr[$k]['language'] = substr($data[0],0,$language);
                    $arr[$k]['country'] = ltrim(substr($data[0],$language),'-');
                    if(stripos($data[0],'zh') > -1){
                        $arr[$k]['language'] = 'ZH-CN';
                    }
                }else{
                    $arr[$k]['language'] = $data[0];
                    $arr[$k]['country'] = $data[0];
                }
                $arr[$k]['currency'] = $data[1];
            }
            return $arr;
        }

        switch(strtolower($this->data['config']['language_m'])){
            case 'language'://cc_languages
                if(class_exists('\Jason\Ccshop\Models\Language')){
                    if($type=='is'){return 'ok';}
                    return $this->getLanguageCountryCurrency($type);
                }
            default:return 'error';
        }
    }

    //货币数据
    public function currency_M($type='all')
    {
        switch(strtolower($this->data['config']['currency_m'])){
            case 'currency'://cc_currencies
                if(class_exists('\Jason\Ccshop\Models\Currency')){
                    if($type=='is'){return 'ok';}
                    $obj = new \Jason\Ccshop\Models\Currency();
                    switch($type){
                        case 'obj':return $obj;
                        case 'all':return $obj->orderBy('id','asc')->get();
                    }
                }
                break;
            default:return 'error';
        }
    }

    //语言包的数据
    public function languagePackage_M($type='all')
    {
        switch(strtolower($this->data['config']['language_package_m'])){
            case 'locale'://'rainlab_translate_locales';
                if(class_exists('\RainLab\Translate\Models\Locale')){
                    if($type=='is'){return 'ok';}
                    $obj = new \RainLab\Translate\Models\Locale();
                    switch($type){
                        case 'obj':return $obj;
                        case 'all':return $obj->orderBy('id','asc')->get();
                    }
                }
                break;
            default:return 'error';
        }
    }
}