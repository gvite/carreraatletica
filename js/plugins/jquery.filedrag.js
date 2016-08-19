/*
 *  filedrag v1.5 | (c) 2013-2014 by UNION
 */
(function($) {
    /*Attach this new method to jQuery*/
    $.widget("ns.setFiledrag", {
        /*This is the Plugin's name*/
        options: {
            folderUpload: 'uploads', /*Folder in server*/
            folderUploadShow: true,
            uploadMessage: 'Arrastra aqui tus archivos', /*Message for upload file field (Note: no valid when showFiles is true)*/
            limitMessage: 'No puedes subir mas archivos a la carpeta',
            invalidTypesMessage: 'Este tipo de archivos no esta permitido',
            fileSize: 50000000, /*Max File Size*/
            maxFilesUpload: -1, /*Max number of files permitted*/
            showFiles: true, /*Show files into file field*/
            routeTheFiles: '', /*Route the files who show into file field in case different folder upload route*/
            validFileType: 'doc docx xls xlsx ppt pptx pps ppsx pdf rar zip png jpg gif wav mp3 swf mpg mp4 ogg avi flv', /*Valid file types (Note: not append more types)*/
            nameInput: 'filedrag',
            menuFile: [{
                    file: '*',
                    menu: ['copy', 'cut', 'edit', 'rename', 'delete', 'download']
                }], /*Options we apply a file with secondary menu (Note: if array is empty don't display secondary menu)*/
            menuFileNames: {copy: 'copiar', cut: 'cortar', edit: 'editar', rename: 'renombrar', delete: 'eliminar', download: 'descargar'},
            menuRoot: ['nueva carpeta', 'list'], /*Options we apply a folder with secondary menu (Note: if array is empty don't display secondary menu)*/
            renameFileRND: false, /*Rename the file with a random name*/
            editImg: {width: 800, height: 900},
            onSuccess: '', /*Callback after file has been uploaded*/
            onDeleted: '', /*Callback after file has been deleted*/
            onClickFile: '', /*Callback action click hover element*/
            onClickFolder: '', /*Callback action click hover folder*/
            onInit: '',
            onEditFile: '',
            onCancelEditImg: '',
            dirControllerFiledrag: 'archivos/filedrag',
            dirControllerImagen: 'archivos/imagenes',
            dirJcrop: 'js/plugins/jquery.Jcrop.min.js',
            editable: true,
            style: false
        },
        numberFiles: 0,
        parent: '',
        $obj: null,
        route: '',
        root: [''],
        xhr: new XMLHttpRequest(),
        folderSource: '',
        fileSelected: '',
        actionSelected: '',
        files_uploaded: 0,
        copy_cut: false,
        url_actual: '',
        _create: function() {
            var object = this;
            if (object.options.routeTheFiles === '') {
                object.options.routeTheFiles = object.options.folderUpload;
            }
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: base_url + object.options.dirControllerFiledrag + '/check_folder_upload/' + object.options.folderUpload + '/' + hide + '.jsp',
                success: function(data) {
                    if (data.status === 'OK') {
                        var $element = object.element;
                        if (object.xhr && ($element.is('div'))) {
                            $element.html('<input type="file" name="' + object.options.nameInput + '" size="20" style="display:none">');
                            object.$obj = $element;
                            /*Create Elements*/
                            object.$obj.append('<div data-name="" class="filedrag icon"><ul></ul><div class="clear"></div></div><div class="filedrag_progress"></div>');
                            /*Option uploadMessage*/
                            if (object.options.uploadMessage !== '' && object.options.showFiles === false) {
                                object.$obj.find('.filedrag').append('<span class="upload_message">' + object.options.uploadMessage + '</span>');
                            }
                            if (object.options.showFiles) {
                                object._buildFolder('', false, object);
                            }
                            object.$obj.find('.filedrag').data('config', {maxFiles: object.options.maxFilesUpload, extFiles: object.options.validFileType, numFiles: 0});
                            if (object.options.menuFile.length > 0) {
                                object.$obj.append('<div class="filedrag_menu menu_files"></div>');
                            }
                            if (object.options.editable) {
                                /*Option menuRoot*/
                                if (object.options.menuRoot.length > 0) {
                                    object.$obj.append('<div class="filedrag_menu menu_root"></div>');
                                }
                                object.$obj.append('<div class="filedrag_menu menu_folder"></div>');
                            }
                            object.$obj.find('.filedrag_menu').hide();
                            object._buildMenu(object);
                            if (object.options.style) {
                                object.$obj.find('.filedrag').css(object.options.style);
                            }
                            var $form = object.$obj.closest('form');
                            if ($form.length > 0) {
                                $form.submit(function(event) {
                                    event.preventDefault();
                                });
                            }
                            if (object.options.editable) {
                                $('<button class="filedrag_attach_file" type="button"></button>').appendTo(object.$obj.find('.filedrag')).on('click', function() {
                                    var config = object.$obj.find('.filedrag').data('config');
                                    if (config.maxFiles == -1 || config.maxFiles == 1 || config.numFiles < config.maxFiles) {
                                        object.$obj.find('input').trigger('click');
                                    } else {
                                        alerts('warning', object.options.limitMessage);
                                    }
                                });

                                /*Max Files Upload*/
                                if (object.numberFiles !== 0)
                                    object.files_uploaded = object.numberFiles;
                                else
                                    object.files_uploaded = 0;
                                var input_fileselect = object.$obj.find('input').get(0);
                                input_fileselect.addEventListener("change", function(e) {
                                    object._fileSelectHandler(e, object);
                                }, false);
                                /*Event assignment the field filedrag*/
                                var field_filedrag = object.$obj.find('.filedrag').get(0);
                                field_filedrag.addEventListener("dragover", object._fileDragHover, false);
                                field_filedrag.addEventListener("dragleave", object._fileDragHover, false);
                                field_filedrag.addEventListener("drop", function(e) {
                                    object._fileSelectHandler(e, object);
                                }, false);
                            }
                            if (typeof object.options.onInit === 'function') {
                                object._trigger("onInit", object);
                            }

                        } else {
                            alerts('error', 'No se a iniciado correctamete FileDrag');
                        }
                    }
                }
            });
        },
        /*Create Folder*/
        _buildFolder: function(folder, create, object) {
            var hide = 0;
            var route = object.options.routeTheFiles;
            if (folder !== '') {
                route += '-' + folder;
            }
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            $.fn.getJSONFromCI({
                url: base_url + object.options.dirControllerFiledrag + '/build_structure/' + route + '/' + hide + '.jsp',
                onSuccess: function(data) {
                    /*Create element go to parent Folder*/
                    if (create)
                        object.$obj.find('.filedrag ul').append('<li class="folder" data-name="' + folder + '" data-source="parentFolder"><p class="name-wrap"><span class="icon_list"></span><span class="name">Atras</span></p></li>');
                    if (data.files !== false) {
                        $.each(data.files, function(index, value) {
                            var file = value.name.split(".");
                            if (value.type === 0) {
                                object.$obj.find('.filedrag ul').append('<li class="file ' + file[file.length - 1] + '" data-name="' + value.name + '"><p class="name-wrap"><span class="icon_list"></span><span class="name">' + value.name + '</span><span class="filedrag_size">' + value.size + '</span><span class="filedrag_modified">' + value.date_mod + '</span></p></li>');
                            } else {
                                object.$obj.find('.filedrag ul').append('<li class="folder" data-name="-' + value.name + '"><p class="name-wrap"><span class="icon_list"></span><span class="name">' + value.name + '</span><span class="filedrag_size">' + value.size + '</span><span class="filedrag_modified">' + value.date_mod + '</span></p></li>');
                            }
                        });
                    }
                    if (data.config !== false) {
                        object.$obj.find('.filedrag').data('config', data.config);
                    } else {
                        object.$obj.find('.filedrag').data('config', {maxFiles: object.options.maxFilesUpload, extFiles: object.options.validFileType, numFiles: data.numFiles});
                    }
                    object._clickFile(object);
                    object._clickFolder(object);

                },
                error: function() {
                    alerts('error', 'Imposible leer folder');
                    return false;
                }
            });
        },
        _clickFile: function(object) {
            object.$obj.find('.filedrag ul li.file').on('click', function(event) {
                event.preventDefault();
                var fileName = $(this).data('name');
                if (typeof object.options.onClickFile === 'function') {
                    object._trigger("onClickFile", object, fileName);
                }
            });
        },
        _clickFolder: function(object) {
            object.$obj.find('.filedrag ul li.folder').on('click', function(event) {
                event.preventDefault();
                object.$obj.find('.filedrag ul li').remove();
                if ($(this).attr('data-source') !== 'parentFolder') {
                    object.root.push(object.parent + $(this).data('name'));
                    object.parent = object.parent + $(this).data('name');
                } else {
                    object.parent = object.root.pop();
                    if (object.parent === object.$obj.find('.filedrag').data('name'))
                        object.parent = object.root.pop();
                }
                if (object.parent === undefined)
                    object.parent = '';
                if (object.parent === '') {
                    object._buildFolder(object.parent, false, object);
                } else {
                    object._buildFolder(object.parent, true, object);
                }
                object.$obj.find('.filedrag').data('name', object.parent);
                object._buildMenu(object);
                if (typeof object.options.onClickFolder === 'function') {
                    object._trigger("onClickFolder", object, $(this).data('name'));
                }
            });
        },
        _buildMenu: function(object) {
            object.$obj.css('position', 'relative');
            object.$obj.find('.filedrag').bind("contextmenu", function(mouseEvent) {
                object.$obj.find(".menu_root, .menu_files, .menu_folder").hide();
                object.$obj.find("li.selected-elemet").removeClass('selected-element');
                var offset = object.$obj.find('.filedrag').offset();
                var x, y;
                if (mouseEvent.pageX !== undefined && mouseEvent.pageY !== undefined) {
                    x = mouseEvent.pageX;
                    y = mouseEvent.pageY;
                } else {
                    x = mouseEvent.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                    y = mouseEvent.clientY + document.body.scrollTop + document.documentElement.scrollTop;
                }
                x -= offset.left;
                y -= offset.top;
                object.$obj.find('.menu_root').css({
                    top: y + 'px',
                    left: x + 'px'
                }).show();
                object._menuRoot($(this), object);
                return false;
            });
            object.$obj.find('.file').bind("contextmenu", function(mouseEvent) {
                object.$obj.find(".menu_root, .menu_files, .menu_folder").hide();
                object.$obj.find("li.selected-elemet").removeClass('selected-element');
                var offset = object.$obj.find('.filedrag').offset();
                var x, y;
                if (mouseEvent.pageX !== undefined && mouseEvent.pageY !== undefined) {
                    x = mouseEvent.pageX;
                    y = mouseEvent.pageY;
                } else {
                    x = mouseEvent.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                    y = mouseEvent.clientY + document.body.scrollTop + document.documentElement.scrollTop;
                }
                x -= offset.left;
                y -= offset.top;
                object.$obj.find('.menu_files').css({
                    top: y + 'px',
                    left: x + 'px'
                }).show();
                object._menuFiles($(this), object);
                return false;
            });
            object.$obj.find('.folder').bind("contextmenu", function(e) {
                object.$obj.find(".menu_root, .menu_files, .menu_folder").hide();
                object.$obj.find("li.selected-elemet").removeClass('selected-element');
                var offset = object.$obj.find('.filedrag').offset();
                object.$obj.find('.menu_folder').css({
                    top: (e.clientY - offset.top) + 'px',
                    left: (e.clientX - offset.left) + 'px'
                }).show();
                object._menuFolder($(this), object);
                return false;
            });
            object.$obj.find(".menu_root, .menu_files, .menu_folder").on('click', function() {
                $(this).hide();
                object.$obj.find("li.selected-elemet").removeClass('selected-element');
            });
            $(document).on('click', function() {
                object.$obj.find(".menu_root, .menu_files, .menu_folder").hide();
                object.$obj.find("li.selected-elemet").removeClass('selected-element');
            });
        },
        _menuFiles: function($element) {
            var object = this;
            if (object.options.menuFile.length > 0) {
                var html = '';
                var nombre = $element.data('name').split('.');
                var extencion = nombre[nombre.length - 1];
                $.each(object.options.menuFile, function(index, value) {
                    if (typeof value === 'object') {
                        var bEsta = false;
                        if ((typeof value.file === 'object' && (jQuery.inArray(extencion, value.file) !== -1) || (typeof value.file === 'string' && value.file === extencion) ||
                                (typeof value.file === 'string' && value.file === '*'))) {
                            bEsta = true;
                        }
                        if (bEsta) {
                            html = '';
                            for (var j = 0; j < value.menu.length; j++) {
                                if (value.menu[j] === 'copy' && object.options.editable) {
                                    html += '<span class="copy">' + object.options.menuFileNames.copy + '</span>';
                                } else if (value.menu[j] === 'cut' && object.options.editable) {
                                    html += '<span class="cut">' + object.options.menuFileNames.cut + '</span>';
                                } else if (value.menu[j] === 'edit' && object.options.editable) {
                                    html += '<span class="edit">' + object.options.menuFileNames.edit + '</span>';
                                } else if (value.menu[j] === 'rename' && object.options.editable) {
                                    html += '<span class="rename">' + object.options.menuFileNames.rename + '</span>';
                                } else if (value.menu[j] === 'delete' && object.options.editable) {
                                    html += '<span class="delete">' + object.options.menuFileNames.delete + '</span>';
                                } else if (value.menu[j] === 'download') {
                                    html += '<span class="download">' + object.options.menuFileNames.download + '</span>';
                                }
                            }
                            if ((typeof value.file === 'object' && jQuery.inArray(extencion, value.file) !== -1) || (typeof value.file === 'string' && value.file === extencion)) {
                                return false;
                            }
                        }
                    }
                });
                object.$obj.find('.menu_files').html(html);
            }
            object.$obj.find('.filedrag_menu .copy, .filedrag_menu .cut').on('click', function() {
                object._elementCopyCut($element, $(this).attr('class'), object);
            });
            object.$obj.find('.filedrag_menu .rename').on('click', function() {
                object._elementRename($element.data('name'), '', object);
            });
            object.$obj.find('.filedrag_menu .delete').on('click', function() {
                object._elementDelete($element, false, object);
            });
            object.$obj.find('.filedrag_menu .edit').on('click', function() {
                object._elementEdit($element, object);
            });
            object.$obj.find('.filedrag_menu .download').on('click', function() {
                object._elementDownload($element, object);
            });
            $element.addClass('selected-element');
        },
        _menuRoot: function($element) {
            var object = this;
            var html = '';
            if (object.copy_cut) {
                html += '<span class="paste">Pegar</span>';
            }
            $.each(object.options.menuRoot, function(index, value) {
                if (value === 'new') {
                    html += '<span class="new">Nueva Carpeta</span>';
                } else if (value === 'list') {
                    if (object.$obj.find('.filedrag').hasClass('list')) {
                        html += '<span class="icon">Ver Iconos</span>';
                    } else {
                        html += '<span class="list " >Ver Lista</span>';
                    }
                }
            });
            object.$obj.find('.menu_root').html(html);
            object.$obj.find('.filedrag_menu .new').on('click', function() {
                object._folderCreate('', object);
            });
            object.$obj.find('.filedrag_menu .list').on('click', function() {
                object._folderList(object);
            });
            object.$obj.find('.filedrag_menu .icon').on('click', function() {
                object._folderIcon(object);
            });
            object.$obj.find('.filedrag_menu .paste').on('click', function() {
                object._elementPaste($element, object);
            });
        },
        _menuFolder: function($element) {
            var object = this;
            var html = '';
            if (object.copy_cut) {
                html += '<span class="paste">Pegar</span>';
            }
            html += '<span class="rename_f">Renombrar</span>';
            html += '<span class="delete_f">Eliminar</span>';
            object.$obj.find('.menu_folder').html(html);
            object.$obj.find('.filedrag_menu .rename_f').on('click', function() {
                object._elementRename($element.data('name'), '', object);
            });
            object.$obj.find('.filedrag_menu .delete_f').on('click', function() {
                object._folderDelete($element, object);
            });
            object.$obj.find('.filedrag_menu .paste').on('click', function() {
                object._elementPaste($element, object);
            });
        },
        _elementCopyCut: function($element, action, object) {
            object.$obj.find('li').css('opacity', '1');
            object.folderSource = object.$obj.find('.filedrag').data('name');
            object.fileSelected = $element.data('name');
            object.actionSelected = action;
            if (object.actionSelected === 'cut')
                $element.css('opacity', '0.5');
            object.copy_cut = true;
        },
        _elementPaste: function($element, object) {
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            $.fn.getJSONFromCI({
                url: base_url + object.options.dirControllerFiledrag + '/copy_cut.jsp',
                data: {
                    'file': object.fileSelected,
                    'folderSource': object.folderSource,
                    'folderSelected': $element.data('name'),
                    'route': object.options.routeTheFiles,
                    'action': object.actionSelected,
                    'hide': hide
                },
                onMsgSuccess: function(data) {
                    if (object.actionSelected === 'cut') {
                        if (object.folderSource === object.$obj.find('.filedrag').data('name')) {
                            object.$obj.find('ul li').each(function(index) {
                                if ($(this).data('name') === data.file[0]) {
                                    $(this).remove();
                                }
                            });
                        }
                    }
                    if (object.folderSource !== object.$obj.find('.filedrag').data('name')) {
                        object.$obj.find('.filedrag ul').append('<li class="file ' + data.file[1] + '" data-name="' + data.file[0] + '"><p class="name-wrap"><span class="icon_list"></span><span class="name">' + data.file[0] + '</span><span class="filedrag_size">' + data.file[2] + '</span><span class="filedrag_modified">' + data.file[3] + '</span></p></li>');
                    }
                    object.copy_cut = false;
                },
                onMsgWarning: function(data) {
                    object.fileSelected = $elementRename(object.fileSelected, data.message, object.folderSource);
                    object._elementPaste($element, object);
                }
            });
        },
        _elementDelete: function($element, force, object) {
            if (!force) {
                var opc_renombrar = '<span>&iquest;Seguro que desea eliminar el archivo <b>' + $element.find('span.name').html() + '</b>?</span>';
                noty({
                    text: opc_renombrar,
                    buttons: [
                        {
                            addClass: 'btn btn-primary',
                            text: 'Eliminar',
                            onClick: function($noty) {
                                $noty.close();
                                object._deleteFile($element, force, object);
                            }
                        },
                        {
                            addClass: 'btn btn-danger',
                            text: 'Cancelar',
                            onClick: function($noty) {
                                $noty.close();
                            }
                        }
                    ],
                    timeout: false
                });
            } else {
                object._deleteFile($element, force, object);
            }
        },
        _deleteFile: function($element, force, object) {
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: base_url + object.options.dirControllerFiledrag + '/delete.jsp',
                data: {
                    'file': $element.data('name'),
                    'folderSource': object.$obj.find('.filedrag').data('name'),
                    'route': object.options.routeTheFiles,
                    'hide': hide
                },
                success: function(data) {
                    if (data.status === 'MSG' && data.type === 'success') {
                        $element.remove();
                        var config = object.$obj.find('.filedrag').data('config');
                        config.numFiles--;
                        object.$obj.find('.filedrag').data('config', config);
                        if (object.files_uploaded === 0) {
                            object.$obj.find('.upload_message').html(object.options.uploadMessage);
                        }
                        if (typeof object.options.onDeleted === 'function') {
                            object._trigger('onDeleted', object, data.file);
                        }
                        if (!force)
                            alerts('success', data.message);
                    } else if (!force) {
                        alerts(data.type, data.message);
                    }
                }
            });
        },
        _elementEdit: function($element, object) {
            var nombre = $element.find('span.name').html();
            var array_name = (nombre).split('.');
            if (array_name.length > 0) {
                switch (array_name[array_name.length - 1]) {
                    case 'png':
                        object.editImg(nombre);
                        break;
                    case 'jpg':
                        object.editImg(nombre);
                        break;
                    case 'jpeg':
                        object.editImg(nombre);
                        break;
                    default:
                        break;
                }
            }
        },
        editImg: function(file, options) {
            var object = this;
            if ($(".imagen-edit-filedrag").length === 0) {
                object.$obj.append('<div class="imagen-edit-filedrag"></div>');

            }
            load_script_callback([object.options.dirJcrop], function() {
                load_css('css/jquery.Jcrop.min.css');
                object._resizeImg(file, options.width, options.height, object);
            });
        },
        _resizeImg: function(file, width, height, object) {
            //Validamos el tamaño minimo
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: base_url + object.options.dirControllerImagen + '/valid/' + width + '/' + height + '/' + object.options.routeTheFiles + object.$obj.find('.filedrag').data('name') + '/' + file,
                success: function(data) {
                    if (data.status === 'OK') {
                        var folder = object.options.routeTheFiles.replace(/-/g, '/') + object.$obj.find('.filedrag').data('name').replace(/-/g, '/');
                        var aux = '';
                        aux += '<div id="content_preview">';
                        aux += '<h4>Preview</h4>';
                        aux += '<div class="content_preview_1" style="width:' + width + 'px; height:' + height + 'px; overflow: hidden;" >';
                        aux += '<img class="preview" src="' + folder + '/' + file + '" />';
                        aux += '</div></div>';
                        aux += '<div id="resize_image" data-owidth="' + width + '" data-oheight="' + height + '" data-iwidth="-1" data-iheight="-1"><h4>Recorta y ajusta tu imagen</h4>';
                        aux += '<img src="' + folder + '/' + file + '?' + Math.random() + '"';
                        aux += ' />';
                        aux += '<form  action="' + base_url + object.options.dirControllerImagen + '/resize_and_crop" method="post" >';
                        // DATOS PARA EL RECORTE
                        aux += '<input type="hidden" name="x" id="x" value="0" >';
                        aux += '<input type="hidden" name="y" id="y" value="0" >';
                        aux += '<input type="hidden" name="w" id="w" value="0" >';
                        aux += '<input type="hidden" name="h" id="h" value="0" >';
                        // DATOS PARA EL REDIMENCIONAR
                        aux += '<input type="hidden" name="rw" id="rw" value="' + width + '" >';
                        aux += '<input type="hidden" name="rh" id="rh" value="' + height + '" >';
                        // DATOS DE UBICACION Y NOMBRE
                        aux += '<input type="hidden" name="folder" id="folder" value="' + folder + '" >';
                        aux += '<input type="hidden" name="filename" id="filename" value="' + file + '" >';
                        aux += '<button type="reset" id="cancelar_resize" class="">Cancelar</button>';
                        aux += '<button type="submit" id="aceptar_resize" class="">Cortar</button>';
                        aux += '</form>';
                        aux += '</div>';
                        $('.imagen-edit-filedrag').html(aux);
                        $('.imagen-edit-filedrag').find("#resize_image").data("iwidth", data.w);
                        $('.imagen-edit-filedrag').find("#resize_image").data("iheight", data.h);
                        $('.imagen-edit-filedrag').find('#resize_image img').Jcrop({
                            aspectRatio: width / height,
                            minSize: [width, height],
                            bgColor: '#FFFFFF',
                            bgOpacity: .8,
                            onSelect: function(c) {
                                object._showPreview(c);
                                $('.imagen-edit-filedrag').find('#x').val(c.x);
                                $('.imagen-edit-filedrag').find('#y').val(c.y);
                                $('.imagen-edit-filedrag').find('#w').val(c.w);
                                $('.imagen-edit-filedrag').find('#h').val(c.h);
                            },
                            onChange: function(c) {
                                object._showPreview(c);
                                $('.imagen-edit-filedrag').find('#x').val(c.x);
                                $('.imagen-edit-filedrag').find('#y').val(c.y);
                                $('.imagen-edit-filedrag').find('#w').val(c.w);
                                $('.imagen-edit-filedrag').find('#h').val(c.h);
                            }
                        });
                        $('.imagen-edit-filedrag #resize_image form').on('submit', function(event) {
                            event.preventDefault();
                            $.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: $(this).attr('action'),
                                data: $(this).serialize(),
                                success: function(data) {
                                    if (data.status === 'OK') {
                                        object.$obj.find('.imagen-edit-filedrag').remove();
                                        var $element = object.$obj.find('.filedrag ul li.selected-element');
                                        if ($element.length === 1) {
                                            $element.data('name', data.image);
                                            $element.find('span.name').text(data.image);
                                        }
                                        if (typeof object.options.onEditFile === 'function') {
                                            object._trigger("onEditFile", object, data.image);
                                        }
                                    }
                                }
                            });
                        });
                        $('.imagen-edit-filedrag #resize_image form #cancelar_resize').on('click', function() {
                            object.$obj.find('.imagen-edit-filedrag').remove();
                            if (typeof object.options.onCancelEditImg === 'function') {
                                object._trigger("onCancelEditImg", object, file);
                            }
                        });
                    } else {
                        object.$obj.find('.filedrag ul li.file').each(function() {
                            if ($(this).data("name") === data.file) {
                                object._elementDelete($(this), true, object);
                                alerts(data.type, data.message);
                            }
                        });
                    }
                }
            });
        },
        _showPreview: function(coords) {
            var o_w = $('.imagen-edit-filedrag').find("#resize_image").data("owidth");
            var o_h = $('.imagen-edit-filedrag').find("#resize_image").data("oheight");
            var i_w = $('.imagen-edit-filedrag').find("#resize_image").data("iwidth");
            var i_h = $('.imagen-edit-filedrag').find("#resize_image").data("iheight");
            var rx = o_w / coords.w;
            var ry = o_h / coords.h;
            $('.imagen-edit-filedrag').find('#content_preview .preview').css({
                width: Math.round(rx * i_w) + 'px',
                height: Math.round(ry * i_h) + 'px',
                marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                marginTop: '-' + Math.round(ry * coords.y) + 'px'
            });
        },
        _elementRename: function(element, text, object) {
            var ext = element.split(".");
            if (ext[1] === undefined) {
                ext[1] = '';
            }
            var folder = object.$obj.find('.filedrag').data('name');
            var opc_renombrar = text + '<span>Nuevo nombre </span><input type="text" max-length="100" name="nuevo_nombre" id="nuevo_nombre"> .' + ext[1];
            noty({
                text: opc_renombrar,
                buttons: [
                    {
                        addClass: 'btn btn-primary',
                        text: 'Renombrar',
                        onClick: function($noty) {
                            $noty.close();
                            var newName = $('#nuevo_nombre').val();
                            var hide = 0;
                            if (object.options.folderUploadShow) {
                                hide = 1;
                            }
                            $.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: base_url + object.options.dirControllerFiledrag + '/rename.jsp',
                                data: {
                                    'newName': newName,
                                    'ext': ext[1],
                                    'file': element,
                                    'folderSource': folder,
                                    'route': object.options.routeTheFiles,
                                    'hide': hide
                                },
                                success: function(data) {
                                    if (data.status === 'OK') {
                                        object.$obj.find('ul li').each(function(index) {
                                            if ($(this).data('name') === element) {
                                                $(this).data('name', data.newName);
                                                $(this).find('span').html(data.newName);
                                            }
                                        });
                                        alerts('success', data.message);
                                    } else if (data.status === 'RENAME') {
                                        object._elementRename(data.file, data.message, object);
                                    } else {
                                        alerts('error', data.message);
                                    }
                                }
                            });
                        }
                    },
                    {
                        addClass: 'btn btn-danger',
                        text: 'Cancelar',
                        onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ],
                timeout: false
            });
        },
        _folderCreate: function(text, object) {
            var opc_renombrar = text + '<span>Nombre de carpeta</span><input type="text" max-length="100" name="nuevo_nombre" id="nuevo_nombre">';
            noty({
                text: opc_renombrar,
                buttons: [
                    {
                        addClass: 'btn btn-primary',
                        text: 'Crear',
                        onClick: function($noty) {
                            $noty.close();
                            var name = $('#nuevo_nombre').val();
                            var folder = object.$obj.find('.filedrag').data('name');
                            var json_options = '';
                            json_options = '{"maxFiles":-1,"extFiles":""}';
                            object._folderInsert(name, folder, object, true, json_options);
                        }
                    },
                    {
                        addClass: 'btn btn-danger',
                        text: 'Cancelar',
                        onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ],
                timeout: false
            });
        },
        _folderInsert: function(name, folder, object, type, options) {
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            if (options === undefined) {
                options = '';
            }
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: base_url + object.options.dirControllerFiledrag + '/create_folder.jsp',
                data: {
                    'name': name,
                    'folderSource': folder,
                    'route': object.options.routeTheFiles,
                    'hide': hide,
                    'options': options
                },
                success: function(data) {
                    if (data.status === 'OK') {
                        object.$obj.find('ul').append('<li class="folder" data-name="-' + data.name + '"><p class="name-wrap"><span class="icon_list"></span><span class="name">' + data.name + '</span></p></li>');
                        object._clickFolder(object);
                        if (type) {
                            alerts('success', data.message);
                        }
                    } else if (data.status === 'EXIST') {
                        object._folderCreate(data.message, object);
                    } else {
                        alerts('error', data.message);
                    }
                }
            });
        },
        _folderDelete: function($element, object) {
            var opc_renombrar = '<span>&iquest;Seguro que desea eliminar la carpeta <b>' + $element.find('span').html() + '</b>?</span>';
            noty({
                text: opc_renombrar,
                buttons: [
                    {
                        addClass: 'btn btn-primary',
                        text: 'Eliminar',
                        onClick: function($noty) {
                            $noty.close();
                            var hide = 0;
                            if (object.options.folderUploadShow) {
                                hide = 1;
                            }
                            $.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: base_url + object.options.dirControllerFiledrag + '/delete_folder.jsp',
                                data: {
                                    'folder': $element.data('name'),
                                    'folderSource': object.$obj.find('.filedrag').data('name'),
                                    'route': object.options.routeTheFiles,
                                    'hide': hide
                                },
                                success: function(data) {
                                    if (data.status === 'OK') {
                                        $element.remove();
                                        alerts('success', data.message);
                                    } else {
                                        alerts('error', data.message);
                                    }
                                }
                            });
                        }
                    },
                    {
                        addClass: 'btn btn-danger',
                        text: 'Cancelar',
                        onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ],
                timeout: false
            });
        },
        _elementDownload: function($element, object) {
            var folder = object.$obj.find(".filedrag").data('name');
            /*Start upload*/
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            var action = base_url + object.options.dirControllerFiledrag + '/download_file/' + object.options.folderUpload + folder + '/' + $element.data("name") + '/' + hide + '.jsp';
            window.open(action);
        },
        _folderList: function(object) {
            object.$obj.find('.filedrag').removeClass('icon').addClass('list');
        },
        _folderIcon: function(object) {
            object.$obj.find('.filedrag').removeClass('list').addClass('icon');
        },
        _fileSelectHandler: function(e, object) {
            object._fileDragHover(e);
            var files = e.target.files || e.dataTransfer.files;
            var f;
            var i = 0;
            /*Set folder name for controller*/
            var folder = object.$obj.find(".filedrag").data('name');
            /*Open number of files*/
            var config = object.$obj.find('.filedrag').data('config');
            if (config.maxFiles === -1 || config.maxFiles === 1) {
                for (i = 0, f; f = files[i]; i++) {
                    object._validateFile(f, folder, object);
                }
                /*Restricted number of files*/
            } else {
                for (i = 0, f; f = files[i]; i++) {
                    var continua = true;
                    if (config.maxFiles != -1 && config.numFiles >= config.maxFiles) {
                        continua = false;
                    }
                    if (continua) {
                        object._validateFile(f, folder, object);
                    } else {
                        alerts('warning', object.options.limitMessage);
                    }
                }
            }
        },
        _fileDragHover: function(e) {
            e.stopPropagation();
            e.preventDefault();
        },
        _uploadFiles: function(file, folder, newName, original, object) {
            if (object.options.maxFilesUpload === 1) {
                object._cleanFolder(folder, object);
            }
            var data = new FormData();
            $.each(object.$obj.find('input[type="file"]'), function(i, tag) {
                if ($(tag)[0].files.length > 0) {
                    $.each($(tag)[0].files, function(i, file) {
                        data.append(tag.name, file);
                    });
                } else if(file){
                    data.append(tag.name, file);
                }
            });
            var params = object.$obj.serializeArray();
            $.each(params, function(i, val) {
                data.append(val.name, val.value);
            });
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            var action = base_url + object.options.dirControllerFiledrag + '/upload/' + object.options.folderUpload + folder + '/' + newName + '/' + object.options.nameInput + '/' + hide + '.jsp';
            $.ajax({
                url: action,
                data: data,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'MSG') {
                        if (data.type === 'success') {
                            object.$obj.find('.upload_message').html('');
                            object.$obj.find('.filedrag ul').append('<li data-name="' + data.name + '" class="file ' + data.ext + ' selected-element"><p class="name-wrap"><span class="icon_list"></span><span class="name">' + data.name + '</span><span class="filedrag_size">' + data.size + '</span><span class="filedrag_modified">' + data.date + '</span></p></li>');
                            object._buildMenu(object);
                            var config = object.$obj.find('.filedrag').data('config');
                            config.numFiles++;
                            object.$obj.find('.filedrag').data('config', config);
                            if (typeof object.options.onSuccess === 'function') {
                                object._trigger('onSuccess', object, {'name': data.name, 'size': data.size, 'modified': data.date, 'folder': object.options.folderUpload, 'folderLocal': object.$obj.find('.filedrag').data('name'), 'original': original});
                            }
                            object._clickFile(object);
                        }
                        alerts(data.type, data.message);
                    }
                }
            });
        },
        _cleanFolder: function(folder, object) {
            var hide = 0;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            $.ajax({
                url: base_url + object.options.dirControllerFiledrag + '/clean_folder/' + object.options.folderUpload + folder + '/' + hide + '.jsp',
                type: 'POST',
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'OK') {
                        object.$obj.find('.filedrag ul').html('');
                    }
                }
            });
        },
        _validateFile: function(file, folder, object) {
            if (file.size <= object.options.fileSize) {
                /*Validate file type*/
                if (object._validTypes(file.name, object)) {
                    /*Validate file name*/
                    var original = file.name;
                    if (object.options.renameFileRND) {
                        var hide = 0;
                        if (object.options.folderUploadShow) {
                            hide = 1;
                        }
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: base_url + object.options.dirControllerFiledrag + '/random_file.jsp',
                            data: {
                                'file': file.name,
                                'folder': folder,
                                'folderUpload': object.options.folderUpload,
                                'hide': hide
                            },
                            success: function(data) {
                                if (data.status === 'OK') {
                                    original = data.original;
                                    object._uploadFiles(file, folder, data.name, original, object);
                                } else {
                                    alerts('error', data.message);
                                }
                            }

                        });
                    } else {
                        var hide = 0;
                        if (object.options.folderUploadShow) {
                            hide = 1;
                        }
                        $.fn.getJSONFromCI({
                            url: base_url + object.options.dirControllerFiledrag + '/validate_file/' + object.options.folderUpload + folder + '.jsp',
                            data: {
                                'file': file.name,
                                'hide': hide
                            },
                            onSuccess: function(data) {
                                object._uploadFiles(file, folder, data.name, original, object);
                            },
                            onMsgWarning: function(data) {
                                object._cicleValidate(file, folder, data, object);
                            }
                        });
                    }
                } else {
                    /*Message error of valid file type*/
                    var config = object.$obj.find('.filedrag').data('config');
                    var valid_files = config.extFiles.split(' ');
                    alerts('warning', '<p>Este archivo no es v&aacute;lido, s&oacute;lo &eacute;stas extenciones son v&aacute;lidas: </p><p>' + valid_files.join() + '</p>');
                    return false;
                }
            } else {
                /*Message error of max file size*/
                alerts('warning', 'El tama&ntilde;o del archivo no puede se mayor a ' + opt.maxFilesUpload + ' archivo(s)');
                return false;
            }
        },
        _validTypes: function(name, object) {
            var fileName = (name).split('.');
            var valid = false;
            var config = object.$obj.find('.filedrag').data('config');
            var valid_files = config.extFiles.split(' ');
            for (var j = 0; j < valid_files.length; j++) {
                if (valid_files[j].toUpperCase() === fileName[fileName.length - 1].toUpperCase()) {
                    valid = true;
                    j = valid_files.length;
                }
            }
            return valid;
        },
        _cicleValidate: function(file, folder, data, object) {
            var opc_renombrar = '<p>El archivo <b>' + data.name + '</b> ya existe, si desea almacenarlo proporcione un nuevo nombre</p><span>Nuevo nombre </span><input type="text" max-length="100" name="nuevo_nombre" id="nuevo_nombre"> <span>.' + data.file_ext + '</span>';
            noty({
                text: opc_renombrar,
                buttons: [
                    {
                        addClass: 'btn btn-primary',
                        text: 'Renombrar',
                        onClick: function($noty) {
                            $noty.close();
                            var newName = $('#nuevo_nombre').val() + '.' + data.file_ext;
                            var hide = 0;
                            if (object.options.folderUploadShow) {
                                hide = 1;
                            }
                            $.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: base_url + object.options.dirControllerFiledrag + '/validate_file/' + object.options.folderUpload + folder + '.jsp',
                                data: {
                                    'file': newName,
                                    'hide': hide
                                },
                                success: function(data) {
                                    if (data.status === 'OK') {
                                        object._uploadFiles(file, folder, newName, data.name, object);
                                    } else {
                                        object._cicleValidate(file, folder, data, object);
                                    }
                                }
                            });
                        }
                    },
                    {
                        addClass: 'btn btn-danger',
                        text: 'Cancelar',
                        onClick: function($noty) {
                            $noty.close();
                        }
                    }
                ],
                timeout: false
            });
        },
        deleteFile: function(file) {
            var object = this;
            object.$obj.find('.filedrag ul li.file').each(function() {
                if ($(this).data("name") === file) {
                    object._elementDelete($(this), true, object);
                }
            });
        },
        get_files_name: function() {
            var object = this;
            var array_names = '';
            object.$obj.find('.filedrag ul li.file').each(function() {
                array_names += $(this).data("name") + ',';
            });
            array_names = array_names.substring(0, array_names.length - 1);
            return array_names;
        },
        rebuild: function() {
            var object = this;
            object._create();
        },
        createFolder: function(name, folder, options) {
            var object = this;
            var json_options = '';
            if (options !== undefined) {
                json_options = '{"maxFiles":';
                if (options.maxFiles !== undefined) {
                    json_options += options.maxFiles;
                } else {
                    json_options += '0';
                }
                json_options += ',"extFiles":"';
                if (options.extFiles !== undefined) {
                    json_options += options.extFiles;
                }
                json_options += '"}';
            }
            object._folderInsert(name, folder, object, false, options);
        },
        existFolder: function(name, folder) {
            var hide = 0;
            var object = this;
            if (object.options.folderUploadShow) {
                hide = 1;
            }
            var data1 = {exist: false};
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: base_url + object.options.dirControllerFiledrag + '/exist_folder.jsp',
                data: {
                    'name': name,
                    'folderSource': folder,
                    'route': object.options.routeTheFiles,
                    'hide': hide
                },
                success: function(data) {
                    data1 = data;
                }
            });
            return data1.exist;
        }
    });
//pass jQuery to the function,
//So that we will able to use any valid Javascript variable name
//to replace "$" SIGN. But, we'll stick to $ (I like dollar sign: ) )
}(jQuery));