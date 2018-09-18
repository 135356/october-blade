<?php

use Longbang\Llaravel\Classes\VcPathClasses;

define('VC_NAME',VcPathClasses::obj()->DIR);
/*命名:longbang\llaravel\Controllers{$str}*/
function vcNamespace($str=null)
{
    return VcPathClasses::namespace_route($str);
}
/*路由:Pv4QZMrjaswXxkCG/llaravel{$str}*/
function vcRoute($str=null)
{
    return VcPathClasses::route_route($str);
}
/*url连接:http://aaa.a/Pv4QZMrjaswXxkCG/llaravel{$str}*/
function vcUrl($str=null)
{
    return VcPathClasses::url_route().'/'.preg_replace("/^[\\\|\/]/",'',$str);
}
/*url连接(父级):http://aaa.a/Pv4QZMrjaswXxkCG{$str}*/
function vcParentUrl($str=null)
{
    return VcPathClasses::parentUrl_route().'/'.preg_replace("/^[\\\|\/]/",'',$str);
}
/*视图资源:http://aaa.a/plugins/longbang/llaravel/views/assets{$str}*/
function vcSrc($str=null,$path='assets')
{
    return VcPathClasses::views_src($path).'/'.preg_replace("/^[\\\|\/]/",'',$str);
}
/*视图空间:llaravel::{$str}*/
function vcViews($str)
{
    return VC_NAME.'::'.preg_replace('/[\\\|\/]/','.',$str);
}