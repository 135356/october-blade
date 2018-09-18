;(function(){
    $.fn.extend({
        'vc2_language':function()
        {
            var A = {
                'obj':$(this),
                ajax_:function(obj,locale,currency)
                {
                    var selectLocale = obj.find('[selectLocale="1"]');
                    if(selectLocale.length>0){
                        selectLocale.html('。......。......。......。......').css({'opacity':'0.1','overflow':'hidden','height':'50px'});
                        selectLocale.animate({'margin-right':'100px','opacity':'1'},2600);
                    }

                    if(!locale || locale.constructor != String){
                        locale = vc_default.locale;
                    }
                    locale = locale.toLowerCase();/*小写*/
                    if(!currency || currency.constructor != String){
                        var div = '[locale='+locale+']';
                        div = obj.find(div);
                        if(div.length<1){
                            if(locale.substr(0,2) == 'zh'){
                                currency = 'HKD';
                            }else{
                                currency = vc_default.currency;
                            }
                        }else{
                            currency = div.attr('money');
                        }
                    }

                    locale = locale.substr(0,2);
                    locale = locale == 'zh'?'zh-cn':locale;
                    currency = currency.toUpperCase();/*大写*/

                    var url = window.location.href;
                    var end_j = url.indexOf('#') >= 0?window.location.hash:'';
                    function url_f(url){
                        var reg = new RegExp("(^|&|/?)(locale|currency|vc_locale_break)=([^&]*)");
                        url = url.replace(reg,'');
                        if(reg.test(url)){
                            url = url_f(url);
                        }
                        return url;
                    }
                    url = url_f(url);
                    if(url.indexOf('#') >= 0){
                        var top = url.substr(0,url.indexOf('#'));
                        if(url.indexOf('?') >= 0){
                            url = top+'&locale='+locale+'&currency='+currency+end_j;
                        }else{
                            url = top+'?locale='+locale+'&currency='+currency+end_j;
                        }
                    }else if(url.indexOf('?') >= 0){
                        url = url+'&locale='+locale+'&currency='+currency+end_j;
                    }else{
                        url = url+'?locale='+locale+'&currency='+currency+end_j;
                    }
                    location.href = url;
                }
            };

            if($(this).length>0){
                var obj = A.obj = $(this);
                var selectLocale = obj.find('[selectLocale]');
                if(selectLocale.length>0){
                    if($(this).find('.active').length>0){
                        var locale_obj = $(this).find('.active');
                        if(locale_obj.attr('locale_name')){
                            selectLocale.html(locale_obj.attr('locale_name')+'&nbsp;▼');
                        }else{
                            selectLocale.html(locale_obj.html()+'&nbsp;▼');
                        }
                    }
                }
                /*hide_是控制包裹语言的大盒子如<ul hide="hover">,如:鼠标经过显示这了盒子或隐藏*/
                var hide_ = obj.find('[hide]');
                if(hide_.length>0){
                    switch(hide_.attr('hide')){
                        case 'hover':obj.hover(function(){hide_.show();},function(){hide_.hide();});
                        break;
                        case 'click':obj.click(function(){hide_.toggle();});
                        break;
                        default:obj.hover(function(){hide_.show();},function(){hide_.hide();});
                    }
                }

                if(obj.find('select').length>0){
                    obj.find('select').change(function(){
                        A.ajax_(obj,$(this).find("option:selected").attr('locale'),$(this).find("option:selected").attr('money'));
                    });
                }else{
                    obj.find('[locale]').hover(function(){
                        $(this).addClass('button_ff0');
                        $(this).click(function(){
                            A.ajax_(obj,$(this).attr('locale'),$(this).attr('money'));
                        });
                    },function(){
                        $(this).removeClass('button_ff0');
                    });
                }
            }
            return this;
        }
    });

    var vc_default = {
        'locale':'en',
        'currency':'USD'
    };
})(jQuery);
