@extends(VC_NAME.'::languages.layouts.default')
@section('content')
    <link href="{{assets_src('css/select2.min.css')}}" rel="stylesheet">
    <script src="{{assets_src('js/select2.min.js')}}"></script>
    <style>
        .language_table{

        }
        .language_table input{
            width:100%;
        }
        .language_table input{
            background:none;
        }
        .language_table select{
            background:none;
        }
    </style>
    <table class="language_table" width="100%">
        <thead>
        <tr>
            <th width="3%">ID</th>
            <th width="5%">排序</th>
            <th width="20%">显示名称</th>
            <th width="8%">国家代码</th>
            <th width="9%">货币代码</th>
            <th width="9%">选择语言包</th>
            <th width="9%">启用</th>
            <th width="9%">默认语言</th>
            <th width="9%"></th>
            <th width="9%"></th>
        </tr>
        </thead>

        <tbody>
        @foreach($language_LCC as $v)
            <form action="{{url_route('languages/up_language/').$v['id']}}" method="post">
            <tr>
                <td>
                    <input type="text" value="{{$v['id']}}" name="id" readonly="true" />
                </td>
                <td>
                    <input type="text" value="{{$v['sort']}}" name="sort" />
                </td>
                <td>
                    <input type="text" value="{{$v['name']}}" name="name" />
                </td>
                <td style="position:relative;">
                    @if($v['style']['iso']['country'] != 1)
                        <input type="text" value="{{$v['country']}}" name="code_country" style="background-color: rgba(255, 160, 0, 0.8);" title="建议设置为：{{$v['style']['iso_suggest']['country']}}" />
                    @else
                        <input type="text" value="{{$v['country']}}" name="code_country" />
                    @endif
                    {{--<input type="text" value="{{$v['country']}}" name="code_country" />
                    <div style="position:absolute;top: 26px;width:220px;z-index:102;background:#ddd;">
                    </div>--}}
                </td>
                <td style="position:relative;">
                    @if($v['style']['currency'] != 1)
                        <p vc_show_children="1" style="position:absolute;width: 30%;height:15px;color:#fff;background-color: rgba(255, 0, 0, 0.7);">
                            <a style="position:absolute;z-index:2;min-width: 200px;display:none;background:#aff;color:#0000ff;" href="{{url(route_route('/jason/ccshop/currencies'))}}"><b>没有该货币，点击去添加，code建议为：{{$v['style']['iso_suggest']['currency']}}</b></a>
                        </p>
                    @elseif($v['style']['iso']['currency'] != 1)
                        <p style="position:absolute;width: 30%;height:15px;color:#fff;background-color: rgba(255, 160, 0, 0.7);" title="code建议为：{{$v['style']['iso_suggest']['currency']}}"></p>
                    @endif
                    <select name="code_currency">
                        <option value="{{$v['currency']}}">{{$v['currency']}}</option>
                        @foreach($language_currency as $vv)
                            <option value="{{$vv->code}}">{{$vv->code}}</option>
                        @endforeach
                    </select>
                </td>
                <td style="position:relative;">
                    @if($v['style']['language'] != 1)
                        <p vc_show_children="1" style="position:absolute;width: 30%;height:15px;color:#fff;background-color: rgba(255, 0, 0, 0.7);">
                            <a style="position:absolute;z-index:2;min-width: 200px;display:none;background:#aff;color:#0000ff;" href="{{url(route_route('/rainlab/translate/locales'))}}"><b>没有该语言包，点击去添加（需事先添加语言包文件）code建议为：{{$v['style']['iso_suggest']['language']}}</b></a>
                        </p>
                    @elseif($v['style']['iso']['language'] != 1)
                        <p style="position:absolute;width: 30%;height:15px;color:#fff;background-color: rgba(255, 160, 0, 0.7);" title="code建议为：{{$v['style']['iso_suggest']['language']}}"></p>
                    @endif
                    <select name="code_language">
                        <option value="{{$v['language']}}">{{$v['language']}}</option>
                        @foreach($language_package as $vv)
                        <option value="{{$vv->code}}">{{$vv->code}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="is_enabled" >
                        <option value="0">no</option>
                        <option value="1" @if($v['is_enabled'] == 1) selected @endif>yes</option>
                    </select>
                </td>
                <td>
                    <select name="is_default" >
                        <option value="0">no</option>
                        <option value="1" @if($v['is_default'] == 1) selected @endif>yes</option>
                    </select>
                </td>
                <td>
                    <input type="submit" value="保存" />
                </td>
                <td>
                    <input type="button" vc_jump="{{url('/').'/'.route_route('languages/delete_language/').$v['id']}}" value="删除" />
                </td>
            </tr>
            </form>
        @endforeach
        </tbody>
    </table>
    <div>
        <div id="select_create_html" style="display:none;position:fixed;top: 100px;width: 750px;border:solid 2px #888;background:#fff;">
            <form action="{{url_route('languages/create_language')}}" method="post">
                <h1>请选择你要新增的国家</h1>
                <select class="selectpicker show-tick form-control **select2**" name="create_language" style="width: 100%;">
                    @foreach($language_iso3166 as $vv)
                        @if($v['country'] == $vv[0])
                            <option value="{{implode(',',$vv)}}" selected>{{$vv[2]}}{{$vv[3]}}</option>
                        @else
                            <option value="{{implode(',',$vv)}}">{{$vv[2]}}{{$vv[3]}}</option>
                        @endif
                    @endforeach
                </select>
                <br />
                <br />
                排序：<input type="text" value="0" name="sort" />
                状态：
                <select name="is_enabled">
                    <option value="1">yes</option>
                    <option value="0">no</option>
                </select>
                是否设为默认语言：
                <select name="is_default">
                    <option value="0">no</option>
                    <option value="1">yes</option>
                </select>
                <p style="width: 180px;margin:0 auto;"><input type="submit" value="确认新增">&nbsp;&nbsp;<a id="quit_select_create">取消</a></p>
            </form>
        </div>

        <div>
            <a id="select_create_button">新增一条</a>
        </div>
    </div>
    <script>
        /*点击跳转*/
        $('[vc_jump]').click(function(){
            window.location.href = $(this).attr('vc_jump');
        });
        /*搜索添加功能*/
        var select_create_html = $('#select_create_html');
        select_create_html.find('.selectpicker').select2();
        $('#select_create_button').click(function(){
            select_create_html.show();
        });
        $('#quit_select_create').click(function(){
            select_create_html.hide(100);
        });
        /*没匹配到语言包和货币code在鼠标经过弹出提示*/
        $('[vc_show_children="1"]').hover(function(){
            $(this).children().show(100);
        },function(){
            $(this).children().hide();
        });
        //改变偶数行背景色
        $(".language_table tr:odd td").css('background-color', '#ffe8a3');
        $(".language_table tr:even td").css('background-color', '#fff');
    </script>
@endsection