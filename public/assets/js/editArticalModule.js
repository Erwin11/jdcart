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
                $('#J_formModule .form-group').removeClass('has-error');
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
                var url = '/admin/articles/addModule';
                $.post(url, data, function(data){
                    console.log(data);
                    if(data.status == 'success'){
                        alert('ok');
                    }else if(data.status == 'verify'){
                        me.addErrorMsg(data.msg);
                        alert('verify error');
                    }else{
                        alert('error');
                    }
                })
            });
        },
        renderForm: function(){
            var me = this;

        },
        addErrorMsg: function(msgObj){
            var me = this;
            var prefix = 'module_';
            var item, txt, node;
            for(item in msgObj){
                txt = msgObj[item][0];
                node = $('#'+prefix+item);
                me.addItemErrMsg(txt, node);
            }
        },
        addItemErrMsg: function(msg, node){
            var parent = node.parent();
            var errNode = parent.find('.control-label');
            if(errNode.length == 0){
                errNode = $('<span class="control-label error-lable"></span>');
                errNode.insertBefore(node);
            }
            errNode.text(msg);
            parent.addClass('has-error');
        }
    };
    //init
    artModule.init();
});