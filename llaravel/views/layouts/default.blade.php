<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Manage the blog posts | MECLOSET</title>
    <link rel="stylesheet" href="{{vcSrc('css/init.min.css')}}">
    <script src="{{vcSrc('js/jquery1.11.1.min.js')}}"></script>
</head>
<body>
<div style="width:1024px;margin:0 auto;">
    {{--导航--}}
    <div style="width:18%;float: left;">
        @include(vcViews('partials.common.nav'))
    </div>

    {{--右--}}
    <div style="width:80%;float: right;">
        @if(isset($errors) && count($errors)>0)
            <div id="error">
                @if(is_object($errors))
                    @foreach($errors->all() as $v)
                        <p style="color:#fff;font-size:24px;font-weight:800;background:#800;">{{$v}}</p>
                    @endforeach
                @else
                    <p style="color:#fff;font-size:24px;font-weight:800;background:#800;">{{$errors}}</p>
                @endif
            </div>
            <script>
                $('#error').delay(5000).hide(500);
            </script>
        @endif
        @yield('content')
        {{--@section('content')@show--}}
    </div>
</div>
</body>
</html>