<?php

namespace Longbang\Llaravel\Controllers\Languages;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Longbang\Llaravel\Classes\VcPathClasses;
use Longbang\Llaravel\Classes\CsvClasses;
use Longbang\Llaravel\Api\CCLanguages as CCSHOP;
use Longbang\Llaravel\Controllers\CommonController;

class IndexController extends CommonController
{
    //private $country = null;
    private $data = null;

    public function __construct()
    {
        /*建立连接*/
        $this->data['CCSHOP'] = new CCSHOP();
    }

    public function index()
    {
        $this->data['CCSHOP']->isApi();
        $language_package = $this->data['CCSHOP']->languagePackage_M();
        $language_LCC = $this->data['CCSHOP']->language_M();
        $language_currency = $this->data['CCSHOP']->currency_M();
        $iso3166 = new CsvClasses();
        $language_iso3166 = $iso3166->getAll(VcPathClasses::tempDB_path('geoIp/iso639_3166.csv'));
        if(empty($language_iso3166)){return 'iso标准库没引入';}

        foreach($language_LCC as $k=>$v){
            /*语言包，1为匹配到语言包，0为没找到语言包需要去Manage languages里添加，影响使用*/
            $language_LCC[$k]['style']['language'] = 0;
            foreach($language_package as $vv){
                if(strtolower($v['language']) == $vv['code']){
                    $language_LCC[$k]['style']['language'] = 1;
                    break;
                }
            }
            /*货币，1为匹配到货币，0为没找到货币需要去货币管理添加，影响使用*/
            $language_LCC[$k]['style']['currency'] = 0;
            foreach($language_currency as $vv){
                if(strtoupper($v['currency']) == $vv['code']){
                    $language_LCC[$k]['style']['currency'] = 1;
                    break;
                }
            }
            /*提示建议设置，1为符合建议的标准，0为不符合iso标准，不影响使用*/
            $language_LCC[$k]['style']['iso']['country'] = 0;
            $language_LCC[$k]['style']['iso']['language'] = 0;
            $language_LCC[$k]['style']['iso']['currency'] = 0;
            $language_LCC[$k]['style']['iso_suggest']['country'] = null;
            $language_LCC[$k]['style']['iso_suggest']['language'] = null;
            $language_LCC[$k]['style']['iso_suggest']['currency'] = null;
            if($v['country'] == 'zh-cn'){
                $v['country'] = 'zh';
            }
            foreach($language_iso3166 as $vv){
                if(stripos($v['name'],$vv[2]) > -1 || stripos($vv[1],$v['country']) > -1){
                    /*给出建议设置*/
                    $language_LCC[$k]['style']['iso_suggest']['country'] = $vv[1];
                    $language_LCC[$k]['style']['iso_suggest']['language'] = $vv[0];
                    $language_LCC[$k]['style']['iso_suggest']['currency'] = $vv[1].'D';

                    if(stripos($vv[0],$v['language']) > -1){
                        $language_LCC[$k]['style']['iso']['language'] = 1;
                    }
                    if(stripos($vv[1].'D',$v['currency']) > -1){
                        $language_LCC[$k]['style']['iso']['currency'] = 1;
                    }
                    /*进一步匹配*/
                    if(stripos($vv[1],$v['country']) > -1){
                        $language_LCC[$k]['style']['iso']['country'] = 1;
                        $language_LCC[$k]['style']['iso_suggest']['country'] = $vv[1];
                        $language_LCC[$k]['style']['iso_suggest']['language'] = $vv[0];
                        $language_LCC[$k]['style']['iso_suggest']['currency'] = $vv[1].'D';
                        /*在进一步匹配*/
                        if(stripos($v['language'],$vv[0]) > -1){
                            $language_LCC[$k]['style']['iso_suggest']['language'] = $vv[0];
                            break;
                        }
                    }
                }
            }
        }
        return view(vcViews('pages/languages/index'),compact('language_LCC','language_package','language_currency','language_iso3166'));
    }

    public function upLanguage()
    {
        $input = Input::all();
        $id = $input['id'];

        $rl = [
            'name' => 'required|between:1,20',
            'code_language' => 'required|size:2|alpha_num',
            'code_country' => 'required|size:2|alpha_num',
            'code_currency' => 'required|size:3|alpha_num'
        ];
        $mes = [
            'name.between'=>'显示名称必须是1到20个字符之间',
            'code_language.required'=>'语言包名称必填',
            'code_language.size'=>'语言必须2位字符',
            'code_language.alpha_num'=>'语言包名称不能包含"-"符号',
            'code_country.required'=>'国家必填',
            'code_country.size'=>'国家必须2位字符',
            'code_country.alpha_num'=>'国家不能包含"-"符号',
            'code_currency.required'=>'货币必填',
            'code_currency.size'=>'货币必须3位字符',
            'code_currency.alpha_num'=>'货币不能包含"-"符号',
        ];
        $v = Validator::make($input,$rl,$mes);
        if($v->passes()){
            $input['code'] = $input['code_language'].'-'.$input['code_country'].'~'.$input['code_currency'];
            /*foreach($input as $v){
                $v['code'] = $v['code_language'].'-'.$v['code_country'].'~'.$v['code_currency'];
                unset($v['code_language']);unset($v['code_country']);unset($v['code_currency']);
                Language::find($v['id'])->update($v);
            }*/
            //unset($input['id']);unset($input['code_language']);unset($input['code_country']);unset($input['code_currency']);
            if($this->data['CCSHOP']->language_M('obj')->find($id)->update($input)){
                $state = '修改成功';
            }else{
                $state = '修改失败';
            }
            return back()->with('errors',$state);
        }
        return back()->withErrors($v);
    }

    public function deleteLanguage($id)
    {
        if($this->data['CCSHOP']->language_M('obj')->find($id)->delete()){
            $state = '删除成功';
        }else{
            $state = '删除失败';
        }
        return back()->with('errors',$state);
    }

    public function createLanguage()
    {
        $input = Input::all();
        $data = $input['create_language'];
        if(preg_match("/[-|~]/",$data)){
            return back()->with('errors','添加失败，因为包含了-或~符号(可能iso标准文件出了问题)');
        }
        $data = explode(',', $data);
        $state = null;
        foreach($this->data['CCSHOP']->language_M() as $v){
            if($v['country'] == $data[1]){
                $state = '添加失败(选择添加的国家以存在)';
                break;
            }
        }
        $language_package = $this->data['CCSHOP']->languagePackage_M();
        $language_currency = $this->data['CCSHOP']->currency_M();
        $state_language1 = '(但是'.$data[0].'语言包没找到)';
        foreach($language_package as $v){
            if($data[0] == $v['code']){
                $state_language1 = null;
                break;
            }
        }
        $state_language2 = '(但是'.$data[1].'D'.'货币没找到)';
        foreach($language_currency as $v){
            if($data[1].'D' == $v['code']){
                $state_language2 = null;
                break;
            }
        }

        if(!isset($state)){
            //$up_data = array('sort'=>$input['sort'],'is_enabled'=>$input['is_enabled'],'is_default'=>$input['is_default']);
            $input['name'] = $data[2];
            $input['code'] = $data[0].'-'.$data[1].'~'.$data[1].'D';
            if($this->data['CCSHOP']->language_M('obj')->create($input)){
                $state = '添加成功'.$state_language1.$state_language2;
            }else{
                $state = '添加失败';
            }
        }
        return back()->with('errors',$state);
    }

}