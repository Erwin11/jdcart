(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)


//config
$.ajaxSetup({
    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});



//mainObj

$(function(){

var base = {
  init: function(){
    var me = this;
    me.bindHandler();
    me.switchPage();
  },
  bindHandler: function(){
    var me = this;
    $(window).load(function(e){
      me.switchPage();
    });
  },
  switchPage: function(){
    var mainW = $(document).width();
    var mainH = Math.max($(window).height(), $('body').height())
    //blog-show
    var blogShow = $('#J_blogShow');
    if(blogShow.length>0){
      var sh = blogShow.height();
      var top = blogShow.find('.left-bg').css('top');
      top = top.replace('px','');
      top = parseInt(top);
      blogShow.find('.left-bg').css('height',mainH-top);
      console.log(mainH,mainH-top, $(window).height());
      //sw
      var sw = blogShow.width();
      var offsetW = parseInt((mainW - sw)/2) + $('#sidebar').outerWidth();
      blogShow.find('.left-bg').css('width',offsetW);
      //sidebar-sw
      var sidebarW = $('#sidebar').width();
      $('#sidebar .list-group').css('width',sidebarW);
      //sticky nav
      $('.list-group').stickyNavbar({
          type: 'vertical',
          selector : '.module-list a',
          verticalSelfPos: $('.list-group').offset().top
       });
    }
  }
}

//init
base.init();





});