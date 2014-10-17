$(function(){
    var artModule = {
        init: function(){
            var me = this;
            me.bindHandler();
            //init
            $('.nav-tabs li').eq(1).find('a').click();
        },
        bindHandler: function(){
            var me = this;
            //nav
            $('.nav-tabs li a').on('click', function(e){
                setTimeout(function(){
                    if($('.nav-tabs li').eq(1).hasClass('active')){
                        $('.control-group-tab').css('visibility', 'hidden');
                    }else{
                        $('.control-group-tab').css('visibility', 'visible');
                        $('#J_formModule').removeClass('active');
                    }
                }, 0);
            });
            //add
            $('#tab-module .module-add').on('click',function(e){
                //show
                $('#J_formModule form')[0].reset(); //重置form
                $('#J_moduleType').change();
                $('#J_formModule').addClass('active');
            });
            //edit
            
            //del
            
            //swith-type
            $('#J_moduleType').change(function(){
                var type = $(this).val();
                var cls = $('#J_formModule').attr('class');
                cls = cls.replace(/ type-\w*/g,'');
                cls += ' type-'+type
                $('#J_formModule').attr('class',cls);
            });

            //cancel
            $('#J_formModule .btn-default').on('click', function(e){
                //show
                $('#J_formModule').removeClass('active');
            });
            //submit
            $('#J_formModule .btn-success').on('click', function(e){
                //show
                e.preventDefault();
                var data = $('#J_formModule form').serialize();
                var url = '/admin/articles/addtModule';
                $.post(url, data, function(data){
                    console.log(data);
                })
            });
        },
        renderForm: function(){
            var me = this;

        }
    };
    //init
    artModule.init();
});