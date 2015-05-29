function Filemanager(opts){
	this.FILEUPLOAD_URL = CONF.fileupload_url;
	this.$container = opts.container;
	this.active_folder_id = 0;
	this.$files_container = null;
	this.onSelect = arr_get(opts, 'onSelect', null);
	this.init();
}

Filemanager.prototype.init = function(){

	var that = this;

	that.$container.html($('.tpl_filemanager').render({}));

	if(that.$container.data('has_inited')){
		console.log('this filemanager already inited');
		return;
	}

	that.$files_container =
		this.$container.find('.js-folder_contents');

	// when root folder will be created
	that.$container.on('click', '.js-create_root_folder', function(){
		var name = prompt('Ime nove mape');
		if(!name){
			return;
		}
		that.create_folder({
			name : {
				sl : name
			}
		});
	});

	// when folder is clicked
	that.$container
		.find('.js-folder_list')
		.on('click', 'a', function(){

			var folder_id = $(this).closest('li').data('id');
			that.select_folder(folder_id);
		});

	// select url on click on input
	$('#filemanager_modal_url')
		.on('click', '.modal-body input', function(){
			$(this).select();
		});

	that.$container.on('click', '.js-rename_folder',function(){
		var new_name = prompt('Izberite novo ime');
		if(!new_name){
			return;
		}
		that.update_folder_name(that.active_folder_id, {
				name : {
					sl : new_name
				}
			});
	});

	that.$files_container.on('click', 'a.js-file_option', function(){
		var option = $(this).data('option');
		var file = $(this).closest('.file-box').data();

		if(option == 'get_url'){

			var url =
				arr_get(file, 'public_url', 'undefined');

			$('#filemanager_modal_url .modal-body input')
				.val(url)
				.select();

			$('#filemanager_modal_url')
				.modal('show');

		}else if(option == 'open'){

			var url = arr_get(file, 'public_url', 'undefined');

			var win = window.open(url, '_blank');
  			win.focus();

		}else if(option == 'delete'){
			that.delete_file(file.id);
		}else if(option == 'select'){
			if(typeof that.onSelect == 'function'){
				that.onSelect.apply(undefined, [file]);
			}
		}

	});

	// init fileuploader
	that.$container.find('.js-fileupload').fileupload({
			url: that.FILEUPLOAD_URL,
			dataType: 'json',
			formData: {
				folder_id : 0
			},
			done: function (e, data) {
				console.log(data);
				if(arr_get(data, 'result.files', []).length){
					toastr.success('Datoteka je bila shranjena.');
				}else{
					toastr.error('Napaka pri shranjevanju');
				}
				that.refresh_files();
				$('#progress').hide();
			},
			start: function (e, data) {
				$('#progress').show();
			},
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progress .progress-bar').css(
					'width',
					progress + '%'
					);
			}
		})
		.prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');

	that.refresh_folders();

};

Filemanager.prototype.create_folder = function(opts){

	opts = _.extend({
		name : {
			sl : 'Test folder 1'
		},
		parent_id : 0
	}, opts);

	var that = this;

	var form_data = _.extend({
		'_method' : 'POST'
	}, opts);

	$.ajax({
		url: that.FILEUPLOAD_URL + '/folder',
		data : form_data,
		dataType : 'json',
		method : 'POST'
	})
	.success(function( data ) {
		console.log(data);
	});

};

Filemanager.prototype.refresh_folders = function(){

	var that = this;

	that.$container.find('.js-folder_list').html('');

	$.getJSON(that.FILEUPLOAD_URL + '/folder',
		{},
		function(data){
			that.render_folders({
				children : data.feed
			},
			that.$container.find('.js-folder_list'),
			true);
		});

};

Filemanager.prototype.render_folders = function(arr, $parent, roots){
	var that = this;
	_.each(arr.children, function(folder){
		$entry = $($('.tpl_filemanager_folder').render({
			id : folder.id,
			name : folder.id + ' ' + folder.name.sl,
			li_class : folder.id == that.active_folder_id ? 'active' : ''
		}));
		$entry.appendTo($parent);
		that.render_folders(folder, $entry.find('.folder-list'), false);
	});
};

Filemanager.prototype.select_folder = function(folder_id){

	var that = this;

	// deactivate all folders
	that.$container
		.find('.js-folder_list li')
		.removeClass('active');

	// activate (gui) current folder
	that.$container
		.find('.js-folder_list li[data-id="' + folder_id + '"]')
		.addClass('active');

	// set global active folder id
	that.active_folder_id = folder_id;

	// update folder id for file upload
	$('.js-fileupload')
		.data('blueimp-fileupload')
		.options
		.formData
		.folder_id = folder_id

	// refresh files (gui)
	that.refresh_files();

};


Filemanager.prototype.update_folder_name = function(folder_id, opts){

	opts = _.extend({
		name : {
			sl : 'Test folder 1'
		}
	}, opts);

	var that = this;

	var form_data = _.extend({
		'_method' : 'PATCH'
	}, opts);

	$.ajax({
		url: that.FILEUPLOAD_URL + '/folder/' + folder_id,
		data : form_data,
		dataType : 'json',
		method : 'POST'
	})
	.success(function( data ) {
		console.log(data);
		that.refresh_folders();
	});
};

Filemanager.prototype.delete_folder = function(){

};

Filemanager.prototype.refresh_files = function(){

	// list all the files in folder that.active_folder_id
	var that = this;

	if(!that.active_folder_id){
		that.$container.find('.js-select_folder').removeClass('hidden');
		that.$container.find('.js-files_container').addClass('hidden');
		return;
	}else{
		that.$container.find('.js-select_folder').addClass('hidden');
		that.$container.find('.js-files_container').removeClass('hidden');
	}

	that.$files_container.html('');

	// render children folders
	// $.getJSON(that.FILEUPLOAD_URL + '/folder/' + that.active_folder_id,
	// 	{},
	// 	function(data){
	// 		_.each(data.children, function(){

	// 		})
	// 	}
	// 	);

	$.getJSON(that.FILEUPLOAD_URL + '/folder/' + that.active_folder_id + '/files',
		{},
		function(data){
			if(!data.feed.length){
				that.$files_container.html('Ni datotek');
				return;
			}
			_.each(data.feed, function(file){

				$file_html = $($('.tpl_filemanager_file')
					.render({
						filename : arr_get(file, 'file_info.original_filename', 'undf'),
						date : moment(arr_get(file, 'updated_at'))
							.format('LLL'),
						enable_select_option : that.onSelect != null
					}));

				$file_html.data(file);
				that.$files_container.append($file_html);
			});

			that.$files_container
				.find('.dropdown-toggle')
				.dropdown();
			
		});

};

Filemanager.prototype.update_file = function(){

};

Filemanager.prototype.delete_file = function(file_id){
	var that = this;
	$.ajax({
		url: that.FILEUPLOAD_URL + '/' + file_id,
		data : {
			'_method' : 'DELETE'
		},
		dataType : 'json',
		method : 'POST'
	})
	.success(function( data ) {
		if(!data.status){
			toastr.error('Napaka pri brisanju - ' + data.msg);
		}else{
			toastr.success('Datoteka izbrisana');
		}
		that.refresh_files();
	});
};

function FilemanagerAttachable(opts){
	this.FILEUPLOAD_URL = CONF.fileupload_url;
	this.$container = opts.container;
	this.$file_list = null;

	this.fileable_id = this.$container.data('id');
	this.module = this.$container.data('module');
	this.model_exists = this.$container.data('model_exists');

	if(!this.model_exists){
		toastr.error('Model must exists. Not yet implemented. (filemanager.js)');
	}

	this.init();
}

FilemanagerAttachable.prototype.init = function(opts){
	var that = this;

	if(that.$container.data('has_inited')){
		console.log('this FilemanagerAttachable already inited');
		return;
	}

	that.$container.html(
		$('.tpl_filemanager_attachable')
			.render({})
	);

	that.$file_list = that.$container.find('.js-file_list');

	that.$container.on('click', '.js-open_filemanager', function(){
		// cleanup existing content
		$('#filemanager_modal_app')
			.find('.modal-body')
			.html();

		new Filemanager({
			container : $('#filemanager_modal_app .modal-body'),
			onSelect : function(file){
				that.attach_file(file.id);
				$('#filemanager_modal_app')
					.modal('hide');
			}
		});

		$('#filemanager_modal_app')
			.modal('show');

	});

	that.list_files();

};

FilemanagerAttachable.prototype.list_files = function(){
	var that = this;

	that.$file_list.html('');

	$.getJSON(that.FILEUPLOAD_URL + '/attached_files' +
			'?module=' + that.module +
			'&fileable_id=' + that.fileable_id,
		{},
		function(data){
			if(!arr_get(data, 'status')){
				toastr.error('Napaka pri pridobivanju datotek.');
				return;
			}
			if(!data.feed.length){
				that.$file_list.html('Ni povezanih datotek');
			}
			_.each(data.feed, function(file){
				var $file = $($('.tpl_filemanager_attachable_file')
					.render({
						name : arr_get(file, 'file_info.original_filename')
					}));
				$file.data(file);
				that.$file_list.append($file);
			});
		});

	that.$file_list.on('click', '.js-detach_file', function(){
		var file = $(this).closest('li').data();
		that.detach_file(file.id);
	});

};

FilemanagerAttachable.prototype.detach_file = function(file_id){
	var that = this;

	$.ajax({
		url: that.FILEUPLOAD_URL + '/detach_file' +
			'?module=' + that.module +
			'&fileable_id=' + that.fileable_id +
			'&file_id=' + file_id,
		data : {
			'_method' : 'DELETE'
		},
		dataType : 'json',
		method : 'POST'
	})
	.success(function( data ) {
		if(!data.status){
			toastr.error('Napaka pri razdruževanju - ' + data.msg);
		}else{
			toastr.success('Datoteka razdružena');
		}
		that.list_files();
	});
};

FilemanagerAttachable.prototype.attach_file = function(file_id){
	var that = this;

	$.ajax({
		url: that.FILEUPLOAD_URL + '/attach_file' +
			'?module=' + that.module +
			'&fileable_id=' + that.fileable_id +
			'&file_id=' + file_id,
		data : {
			'_method' : 'PATCH'
		},
		dataType : 'json',
		method : 'POST'
	})
	.success(function( data ) {
		if(!data.status){
			toastr.error('Napaka pri povezovanju - ' + data.msg);
		}else{
			toastr.success('Datoteka povezana');
		}
		that.list_files();
	});
};

$(function(){
	$('.filemanager-attached-files').each(function(){
		new FilemanagerAttachable({
			container : $(this)
		});
	});
});

