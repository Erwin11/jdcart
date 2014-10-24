$(function(){
    var artModule = {
        formNode: $('#J_formModule'),
        baseurl: '/admin/modules/',
        submitObj: {
            add: {url: 'addModule', type: 'POST'}, 
            edit: {url: 'editModule', type: 'PUT'}
        },
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
                        me.formNode.removeClass('active');
                    }
                }, 0);
            });
            //add
            $('#tab-module .module-add').on('click',function(e){
                //clean
                me.formNode.find('form')[0].reset(); //重置form
                $('#module_type').change();
                me.formNode.find('.form-group').removeClass('has-error');
                //show
                me.formNode.attr('data-action','add');
                me.formNode.addClass('active');
            });
            //edit
            $('#tab-module .module-list .glyphicon-edit').on('click', function(e){
                //data
                var id = $(this).parents('li').attr('data-id');
                var url = me.baseurl+'editModule';
                $.ajax({
                    url: url,
                    data: {id:id},
                    success: function(data){
                        if(data.status == 'success'){
                            me.renderForm(data.data);
                            //show
                            me.formNode.attr('data-action','edit');
                            me.formNode.addClass('active');
                        }
                    }
                });
            });
            //del
            $('#tab-module .module-list .glyphicon-trash').on('click', function(e){
                //data
                var itemNode = $(this).parents('li');
                var id = itemNode.attr('data-id');
                var url = me.baseurl+'deleteModule';
                $.ajax({
                    url: url,
                    data: {id:id},
                    success: function(data){
                        if(data.status == 'success'){
                            itemNode.remove();
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            });




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
                //action
                var action = me.formNode.attr('data-action');
                var url = me.baseurl+me.submitObj[action].url;
                $.ajax({
                    url: url,
                    data: data,
                    type : me.submitObj[action].type,
                    success: function(data){
                        if(data.status == 'success'){
                            me.renderModuleItem(data.data);
                            me.formNode.removeClass('active');
                        }else if(data.status == 'verify'){
                            me.addErrorMsg(data.msg);
                            alert('verify error');
                        }else{
                            alert('error');
                        }
                    }
                })
            });
        },
        renderModuleItem: function(data){
            var me = this;
            var id = data.id;
            var node = $('#tab-module li[data-id='+id+']');
            node.find('h4').text(data.title);
        },
        renderForm: function(data){
            var me = this;
            var item, node;
            for(item in data){
                node = $('#module_'+item);
                node.val(data[item]);
            }
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