(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)


//config
$.ajaxSetup({
    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});