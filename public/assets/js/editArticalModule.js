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
            // $('.nav-tabs li').eq(1).find('a').click();
            // $('.module-list li').eq(1).find('.glyphicon-edit').click();
        },
        bindHandler: function(){
            var me = this;
            //add
            $('#tab-module .module-add').on('click',function(e){
                //clean
               me.cleanForm();
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
                //picData
                me.setPicData();
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
                });
            });
            $('#J_articleForm').on('submit', function(e){
                me.sendSortData();
            });
            //upload - image
            var uploadButton = $('<button/>')
                .addClass('btn btn-xs btn-uploadpic')
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
                // singleFileUploads: false,
            }).on('fileuploadadd', function (e, data) {
                data.context = $('<div/>').appendTo('#J_files');
                data.context.addClass('file-additems');
                $.each(data.files, function (index, file) {
                    var node = $('<div/>')
                                .addClass('file-item');
                    if (!index) {
                        node
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
                        .prepend('<a class="file-pic" href="javascript:void(0);" target="_blank"></a>');
                    node.find('.file-pic').append(file.preview);
                }
                //del btn
                node.append('<a href="javascript:void(0);" class="btn btn-xs btn-danger image-del">删除</a>');
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
            }).on('fileuploaddone', function (e, data) {
                var item = null;
                var result = data.result;
                $.each(result.files, function (index, file) {
                    item = $(data.context.children()[index]);
                    if(file.status == 'success'){
                        item.find('.file-pic')
                            .addClass('active')
                            .removeClass('loading')
                            .attr('href', me.hosturl+file.url);
                        delete file.status;
                        item.data('file', file);
                    }else{
                        var error = $('<span class="text-danger"/>').text(file.msg);
                        item.append(error);
                        item.find('.file-pic')
                            .removeClass('loading');
                    }
                });
            }).on('fileuploadfail', function (e, data) {
                var item = null;
                $.each(data.files, function (index) {
                    item = $(data.context.children()[index]);
                    var error = $('<span class="text-danger"/>').text('File upload failed.');
                    item.append(error);
                });
            });
            $('#J_uploadAllPic').on('click', function(e){
                $('#J_files .btn-uploadpic').click();
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
                var obj = null;
                //merge oldData
                try{
                    obj = $.parseJSON(data.image);
                }catch(e){
                    obj = [{url: data.image}];
                }
                me.renderUploadImage(obj);
            }else{
                me.cleanUploadImage();
            }
            //download
            if(data.download){
                var obj = $.parseJSON(data.download);
                me.renderUploadDownload(obj);
            }else{
                me.cleanUploadDownload();
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
        setPicData: function(){
            var me = this;
            var newData = [];
            var addPicArr = $('#J_files .file-item');
            var addItem = null;
            for(var i=0, len=addPicArr.length; i<len; i++){
                item = $(addPicArr[i]);
                if(item.data('file')){
                    newData.push(item.data('file'));
                }
            }
            //mergeData
            var oldData = [];
            // var oldStr = $('#module_image').val();
            // debugger;
            // if(oldStr){
            //     try{
            //        $.parseJSON(oldStr);
            //     }catch(e){
            //         oldData = [{url: oldStr}];
            //     }    
            // }
            var data = oldData.concat(newData);
            $('#module_image').val(JSON.stringify(data));
        },
        renderUploadImage: function(dataArr){
            var me = this;
            me.cleanUploadImage();
            var node = $('<div class="file-item">'+
                            '<a class="file-pic" href="#" target="_blank" title="查看大图"></a>'+
                            '<a href="javascript:void(0);" class="btn btn-xs btn-danger image-del">删除</a>'+
                        '</div>');
            var item, itemNode, url, itemSize = '';
            for(var i=0, len=dataArr.length; i<len; i++){
                item = dataArr[i];
                itemNode = node.clone();
                url = me.hosturl + item.url;
                //size
                if(item.width){
                    itemSize = item.width>item.heigth ? '100px auto' : 'auto 100px';
                    itemSize = 'background-size:'+itemSize;
                }
                //render
                var style = 'background-image:url('+url+');'+itemSize;
                itemNode.find('.file-pic')
                        .attr({
                            'style': style,
                            'href' : url,
                        });
                itemNode.data('file', item);
                //append
                $('#J_files').append(itemNode);
            }
        },
        cleanUploadImage: function(){
            $('#J_files').html('');
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
        cleanUploadDownload: function(){
            var me = this;
            $('#J_uploadDownload .download-con').remove();
        },
        cleanEditorStatus: function(){
            var previewBtn = $('#J_formModule .md-editor button[data-handler="bootstrap-markdown-cmdPreview"]');
            if(previewBtn.hasClass('active')){
                previewBtn.click();
            }
        },
        cleanForm: function(){
            var me = this;
            me.formNode.find('form')[0].reset(); //重置form
            $('#module_type').change();
            me.formNode.find('.form-group').removeClass('has-error');
            me.cleanUploadImage();
            me.cleanUploadDownload();
            me.cleanEditorStatus();
        }
    };
    //init
    artModule.init();
});