<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>404</title>
</head>
<body>
<div style="width:80%;margin:0 auto;">
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
    @endif
</div>
<script>
    function closeWindow(){window.opener=null;window.open('','_self');window.close();}
    setTimeout("closeWindow()",5000);
</script>
</body>
</html>