$(function(){
    var artModule = {
        formNode: $('#J_formModule'),
        baseurl: '/admin/modules/',
        hosturl: 'http://'+window.location.host+'/',
        submitObj: {
            add: {url: 'addModule', type: 'POST'}, 
            edit: {url: 'editModule', type: 'PUT'}
        },
        init: function(){
            var me = this;
            me.bindHandler();
            //init test
            $('.nav-tabs li').eq(1).find('a').click();
            $('.module-list li').eq(0).find('.glyphicon-edit').click();
        },
        bindHandler: function(){
            var me = this;
            //add
            $('#tab-module .module-add').on('click',function(e){
                //clean
                me.formNode.find('form')[0].reset(); //重置form
                $('#module_type').change();
                me.formNode.find('.form-group').removeClass('has-error');
                $('#J_files').html('');
                $('#J_uploadDownload .download-con').remove();
                me.cleanEditorStatus();
                //show
                me.formNode.attr('data-action','add');
                $('#J_moduleContentModal .modal-title').text('添加模块');
            });
            //edit
            $('#tab-module .module-list').on('click', '.glyphicon-edit', function(e){
                var item = $(this);
                //data
                var id = $(this).parents('li').attr('data-id');
                var url = me.baseurl+'editModule';
                $.ajax({
                    url: url,
                    data: {id:id},
                    success: function(data){
                        if(data.status == 'success'){
                            me.cleanEditorStatus();
                            me.renderForm(data.data);
                            //show
                            me.formNode.attr('data-action','edit');
                            $('#J_moduleContentModal .modal-title').text('编辑模块');
                        }
                    }
                });
            });
            //del
            $('#tab-module .module-list').on('click', '.glyphicon-trash', function(e){
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
            $('#module_type').change(function(){
                var type = $(this).val();
                var cls = $('#J_formModule').attr('class');
                cls = cls.replace(/ type-\w*/g,'');
                cls += ' type-'+type
                $('#J_formModule').attr('class',cls);
            });
            $('#J_files').on('click', '.image-del', function(e){
                $('#module_image').val('');
                $(this).parents('.file-item').remove();
            });
            $('#J_uploadDownload').on('click', '.download-del', function(e){
                $('#module_download').val('');
                $('#J_uploadDownload .download-con').remove();
            });
            //submit
            $('#J_submit').on('click', function(e){
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
                            //close modal
                            $('#J_moduleContentModal').modal('hide');
                        }else if(data.status == 'verify'){
                            me.addErrorMsg(data.msg);
                            // alert('verify error');
                        }else{
                            alert('error,提交出错');
                        }
                        
                    }
                })
            });
            $('#J_articleForm').on('submit', function(e){
                me.sendSortData();
            });
            //upload - image
            var uploadButton = $('<button/>')
                .addClass('btn btn-primary')
                .prop('disabled', true)
                .text('Processing...')
                .on('click', function () {
                    var $this = $(this),
                        data = $this.data();
                    //loading
                    $this.parent().find('.file-pic').addClass('loading');
                    $this
                        .off('click')
                        .text('Abort')
                        .on('click', function () {
                            $this.remove();
                            data.abort();
                        });
                    data.submit().always(function () {
                        $this.remove();
                    });
                    return false;
                });
            //upload - image
            $('#upload_image').fileupload({
                url: me.baseurl+'uploadPic',
                dataType: 'json',
                autoUpload: false,
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                maxFileSize: 5000000, // 5 MB
                previewMaxWidth: 100,
                previewMaxHeight: 100,
                previewCrop: true,
                done: function(e, data){
                    var result = data.result;
                    if(result.status == 'success'){
                        var url = result.data.url;
                        $('#module_image').val(url);
                        //
                       $('#J_files').find('.file-item .file-pic')
                            .addClass('active')
                            .removeClass('loading')
                            .attr('href', me.hosturl+url);
                        $('#J_files').find('.file-item')
                            .append('<a href="javascript:void(0);" class="btn btn-xs btn-danger image-del">删除</a>')
                            .find('br').remove();
                    }else{
                        alert(result.msg);
                    }
                }
            }).on('fileuploadadd', function (e, data) {
                data.context = $('<div/>');
                $('#J_files').html(data.context);
                data.context.attr('class','file-item');
                $.each(data.files, function (index, file) {
                    var node = $('<p/>');
                            // .append($('<span/>').text(file.name));
                    if (!index) {
                        node
                            // .append('<br>')
                            .append(uploadButton.clone(true).data(data));
                    }
                    node.appendTo(data.context);
                });
            }).on('fileuploadprocessalways', function (e, data) {
                var index = data.index,
                    file = data.files[index],
                    node = $(data.context.children()[index]);
                if (file.preview) {
                    node
                        .prepend('<br>')
                        .prepend('<a class="file-pic" href="javascript:void(0);" target="_blank"></a>');
                        // .prepend('<a href="javascript":void(0);>'+file.preview+'</a>');
                    node.find('.file-pic').append(file.preview);
                }
                if (file.error) {
                    node
                        .append('<br>')
                        .append($('<span class="text-danger"/>').text(file.error));
                }
                if (index + 1 === data.files.length) {
                    data.context.find('button')
                        .text('Upload')
                        .prop('disabled', !!data.files.error);
                }
            });
            //upload-download files
            $('#upload_donwload').fileupload({
                url: me.baseurl+'uploadFile',
                dataType: 'json',
                autoUpload: true,
                acceptFileTypes: /(\.|\/)(zip|rar)$/i,
                maxFileSize: 50000000, // 50 MB
                start: function(e){
                    $('#J_uploadDownload').addClass('loading');
                },
                done: function(e, data){
                    var result = data.result;
                    $('#J_uploadDownload').removeClass('loading');
                    if(result.status == 'success'){
                        var str = JSON.stringify(result.data);
                        //
                        $('#module_download').val(str);
                        me.renderUploadDownload(result.data);
                    }else{
                        alert(result.msg);
                    }
                }
            });
            //sortable
            $("ul.module-sortable").sortable({
                group: 'module-listsort',
                handle: 'i.icon-move',
                nested: false,
                vertical: false,
                exclude: '.module-add'
            });
            $('ul.module-nodrop').sortable({
                group: 'module-listsort',
                drop: false,
                drag: false
            });
        },
        sendSortData: function(){
            var me = this;
            //data
            var data = [];
            var arr = $('.module-sortable li');
            var item, obj;
            for(var i=0, len=arr.length; i<len; i++){
                item = $(arr[i]);
                obj = {id: item.attr('data-id'), sort: i};
                data.push(obj);
            }
            //send-data
            var url = me.baseurl+'sortModule';
            $.ajax({
                url: url,
                type: 'POST',
                data: {data: data},
                success: function(data){
                    return;
                    if(data.status == 'success'){

                    }else{
                        alert(data.msg);
                    }
                }
            });
        },
        renderModuleItem: function(data){
            var me = this;
            var id = data.id;
            var node = $('#tab-module li[data-id='+id+']');
            if(node.length == 0){
                node = me.addModuleItem(data);
            }
            node.find('h4').text(data.title);
            //add
        },
        addModuleItem: function(data){
            var me = this;
            var node = $('<li data-id="'+data.id+'">'+
                            '<h4>'+data.title+'</h4>'+
                            '<i class="icon-move glyphicon glyphicon-move"></i>'+
                            '<div class="opt">'+
                              '<a class="glyphicon glyphicon-edit" title="编辑" data-toggle="modal" data-target="#J_moduleContentModal">edit</a>'+
                              '<a class="glyphicon glyphicon glyphicon-trash" title="删除">delete</a>'+
                            '</div>'+
                        '</li>');
            $('.module-sortable').append(node);
            return node;
        },
        renderForm: function(data){
            var me = this;
            var item, node;
            for(item in data){
                node = $('#module_'+item);
                node.val(data[item]);
            }
            $('#module_type').change();
            //image
            if(data.image){
                me.renderUploadImage(me.hosturl+data.image);
            }
            //download
            if(data.download){
                var obj = $.parseJSON(data.download);
                me.renderUploadDownload(obj);
            }
        },
        addErrorMsg: function(msgObj){
            var me = this;
            var prefix = '';
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
        },
        renderUploadImage: function(url){
            var me = this;
            var node = $('<div class="file-item">'+
                            '<a href="'+url+'" target="_blank" title="查看大图"><img src="'+url+'" ></a>'+
                            '<a href="javascript:void(0);" class="btn btn-xs btn-danger image-del">删除</a>'+
                        '</div>');
            $('#J_files').html(node);
        },
        renderUploadDownload: function(data){
            var me = this;
            var node = $('<div class="download-con">'+
                            '<a class="download-link" href="#">下载文件</a>'+
                            '<span>1.27MB（.rar）</span>'+
                            '<a href="javascript:void(0);" class="btn btn-xs btn-danger download-del">删除</a>'+
                        '</div>');
            var conNode = $('#J_uploadDownload');
            var linkNode = conNode.find('.download-con');
            if(linkNode.length == 0){
                conNode.append(node);
                linkNode = node;
            }
            var info = data.size+'MB'+'（.'+ data.ext +'）';
            linkNode.find('.download-link').attr('href', me.hosturl+data.url);
            linkNode.find('span').text(info);
        },
        cleanEditorStatus: function(){
            var previewBtn = $('#J_formModule .md-editor button[data-handler="bootstrap-markdown-cmdPreview"]');
            if(previewBtn.hasClass('active')){
                previewBtn.click();
            }
        }
    };
    //init
    artModule.init();
});