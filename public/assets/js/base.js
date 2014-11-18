(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)


//config
$.ajaxSetup({
    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});



//mainObj

$(function(){

var base = {
  isInit: false,
  init: function(){
    var me = this;
    me.bindHandler();
    me.switchPage();
    setTimeout(me.switchPage, 500);
  },
  bindHandler: function(){
    var me = this;
    $(window).load(function(e){
      me.switchPage();
    });
  },
  switchPage: function(){
    var me = base;
    var mainW = $(document).width();
    var mainH = Math.max($(window).height(), $('body').height());
    var winH = $(window).height();
    //blog-show
    var blogShow = $('#J_blogShow');
    if(blogShow.length>0){
      var sh = blogShow.height();
      var top = blogShow.find('.left-bg').css('top');
      top = top.replace('px','');
      top = parseInt(top);
      blogShow.find('.left-bg').css('height',mainH-top);
      //sw
      var sw = blogShow.width();
      var offsetW = parseInt((mainW - sw)/2) + $('#sidebar').outerWidth();
      blogShow.find('.left-bg').css('width',offsetW);
      blogShow.find('.list-group').css('height', winH);
      //sidebar-sw
      if(me.isInit){
        return;
      }
      var sidebarW = $('#sidebar').width();
      $('#sidebar .list-group').css('width',sidebarW);
      var listTop = $('.list-group').offset().top;
      //sticky nav
      $('.list-group').stickyNavbar({
          type: 'vertical',
          selector : '.module-list a',
          verticalSelfPos: listTop
       });
    }
    me.isInit = true;
  }
}

//init
base.init();





});