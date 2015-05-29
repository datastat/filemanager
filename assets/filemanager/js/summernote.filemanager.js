(function (factory) {
	/* global define */
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
		alert('summernote filemanager define.amd not supported');
	} else {
		// Browser globals: jQuery
		factory(window.jQuery, window.Filemanager);
	}
}(function ($, Filemanager) {
  // template, editor
  var tmpl = $.summernote.renderer.getTemplate();
  // var editor = $.summernote.eventHandler.getEditor();

  /**
   * @class plugin.hello 
   * 
   * Hello Plugin  
   */
   $.summernote.addPlugin({
		/** @property {String} name name of plugin */
		name: 'filemanager',
		/** 
		 * @property {Object} buttons 
		 * @property {Function} buttons.hello   function to make button
		 * @property {Function} buttons.helloDropdown   function to make button
		 * @property {Function} buttons.helloImage   function to make button
		 */
		buttons: { // buttons
			filemanager: function () {

				return tmpl.iconButton('fa fa-picture-o', {
					event : 'filemanager',
					title: 'filemanager',
					hide: true
				});
			}

		},

		/**
		 * @property {Object} events 
		 * @property {Function} events.hello  run function when button that has a 'hello' event name  fires click
		 * @property {Function} events.helloDropdown run function when button that has a 'helloDropdown' event name  fires click
		 * @property {Function} events.helloImage run function when button that has a 'helloImage' event name  fires click
		 */
		events: { // events
			filemanager: function (e, editor_api, layout) {

				$('#filemanager_modal_app')
					.find('.modal-body')
					.html();

				var $editor = layout.editable();
				var api = editor_api;

				new Filemanager({
					container : $('#filemanager_modal_app .modal-body'),
					onSelect : function(file){

						if(file.type != 'image'){
							alert('Izbereš lahko samo sliko');
						}else{
							setTimeout(function(){
								console.log(api);
								console.log($editor);
								var img = $('<img src="' + file.public_url + '">');
								api.insertNode($editor, img[0]);
							}, 100);
						}

						$('#filemanager_modal_app')
							.modal('hide');

					}
				});

				$('#filemanager_modal_app')
					.modal('show');
				
		  },
	  }
	});
}));