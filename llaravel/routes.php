<?php include_once(__DIR__.'/config.php');define('VC_NAME',\Longbang\Llaravel\Classes\VcPathClasses::obj()->DIR);

use Longbang\Llaravel\Controllers\LanguageController;

//实际对应如：http://aaa.a/Pv4QZMrjaswXxkCG/llaravel/aaa
Route::get(route_route('aaa'), 'Longbang\Llaravel\Controllers\Languages\IndexController@aaa');
Route::get('file', function(){});

/*多语言相关（后台视图功能）*/
Route::group(['prefix'=>route_route('languages'),'namespace'=>namespace_route('languages'),'middleware'=>['\Illuminate\Cookie\Middleware\EncryptCookies::class','Longbang\Llaravel\Middleware\BackendSignIn::class']],function(){
    Route::get('/','IndexController@index');
    /*修改语言*/
    Route::post('up_language/{id}','IndexController@upLanguage');
    /*添加语言*/
    Route::post('create_language','IndexController@createLanguage');
    /*删除语言*/
    Route::get('delete_language/{id}','IndexController@deleteLanguage');

    /*搜索国家*/
    //Route::get('get_country/{country}','IndexController@getCountry');
    /*将一个不包含英文名字的iso文件里面加入它们对应的英文名字*/
    //Route::get('aaaaaa','IndexController@aaaaaa');
});

/*多语言相关*/
Route::group(['prefix'=>'languages','namespace'=>namespace_route()],function(){
    /*获取语言管理里的全部数据*/
    Route::get('/getLanguageAll', 'LanguageController@getLanguageAll');

    /*测试语言切换功能*/
    Route::get('/setLanguage/{language}', function($get){
        $arr = array('match_lang'=>strtolower($get),'type'=>'language');
        $get = explode('_',$arr['match_lang']);
        if(isset($get[1])){
            $arr = array('match_lang'=>$get[0],'type'=>$get[1]);
        }
        dump(LanguageController::setLanguage($arr['match_lang'],$arr['type'],'obj')->setLanguage);
    });
    /*语言切换功能（功能同上）*/
    Route::get('/setLanguage', function(){
        $get = $_GET;
        if(isset($get['language'])){
            $data = LanguageController::setLanguage($get['language'],'language');
        }else if(isset($get['currency'])){
            $data = LanguageController::setLanguage($get['currency'],'currency');
        }else if(isset($get['ip'])){
            $get['ip'] = $get['ip']!=1?$get['ip']:'';
            $data = LanguageController::setLanguage($get['ip'],'ip');
        }else if(isset($get['browser'])){
            $get['browser'] = $get['browser']!=1?$get['browser']:'';
            $data = LanguageController::setLanguage($get['browser'],'browser');
        }else{
            $get['browser'] = isset($get['browser'])?$get['browser']:'';
            $data = LanguageController::setLanguage($get['browser'],'browser');
        }
        if(isset($get['test'])){
            dump($data->setLanguage);
        }else{
            return back();
        }
        //示例：如：http://aaa.a/languages/setLanguage?language=en-us&test=1
        //$get['language'];//输入语言代码进行匹配如：?&language=en-us 说明：语言管理里面code的"~"前面部分设置成什么这里就输入什么，如果语言管理里面对应的语言包如en没有问题,就会设置成这个语言en否则设置为默认语言，如果国家如us对应的货币没有问题且开启状态,就会设置成这个货币否则设置为默认货币，下面所有的扩展也都会走这个流程
        //$get['currency'];//输入货币代码进行匹配如：?&currency=usd 说明：语言管理里面code的"~"后面部分设置成什么这里就输入什么，如果该货币对应的国家语言包没问题，就会设置成这个国家的语言，如果货币管理对应的货币没问题且开启状态，就会设置成这个货币
        //$get['ip'];
        /*
         * 与用户端ip地址进行匹配如：?&ip=1 说明：这会根据用户当前ip所对应的国家，判断我们的语言数据里面是否有这个国家的语言，如果有就设置成这个语言
         * 输入ip如：?&ip=100.10.1.1 说明：这会根据100.10.1.1所对应的国家，判断我们的语言数据里面是否有这个国家的语言，如果有就设置成这个语言
         * ip的语言代码如：?&ip=en-us 说明：这与&language=en-us差不多但确不一样，因为ip获取的的国家代码是由geoip根据iso639返回的，这与我们的语言数据有一部分会存在区别，我们的国家语言代码更多的是考虑浏览器的语言而不仅仅是iso639
         * */
        //$get['browser'];
        /*
         * 与用户端浏览器第一语言进行匹配如：?&browser=1 说明：这会获取用户当前所使用的浏览器的第一语言，判断我们的语言数据里面是否有这个国家的语言，如果有就设置成这个语言
         * 输入浏览器的语言代码如：?&browser=en-us 说明：这与?&language=en-us暂时非常类似
         * */
        //$get['test'];//显示切换后的结果数据如：http://aaa.a/languages/setLanguage?language=en-us&test=1
    });
});

/*国家地区相关*/
Route::group(['prefix'=>'area','namespace'=>namespace_route()],function(){
    //检查ip
    Route::get('getIp', 'AreaController@getIp');
    /*获取国家、省、市、邮编*/
    Route::get('getGeo', 'AreaController@getGeo');
    /*获取国家、省、市、邮编用定位功能*/
    Route::get('getLocation', 'AreaController@getLocation');
});
