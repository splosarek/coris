/**
 * FancyUpload Showcase
 *
 * @license		MIT License
 * @author		Harald Kirschner <mail [at] digitarald [dot] de>
 * @copyright	Authors
 */

function initloader(){
window.addEvent('domready', function() {

	/**
	 * Uploader instance
	 */
	var up = new FancyUpload3.Attach('demo-list', '#demo-attach, #demo-attach-2', {
		path: 'Scripts/Swiff.Uploader.swf',
		url: 'script.php',
		fileSizeMax: 2 * 1024 * 1024,
		
		verbose: true,
		
		onSelectFail: function(files) {
			files.each(function(file) {
				new Element('li', {
					'class': 'file-invalid',
					events: {
						click: function() {
							this.destroy();
						}
					}
				}).adopt(
					new Element('span', {html: file.validationErrorMessage || file.validationError})
				).inject(this.list, 'bottom');
			}, this);	
		},
		onFileComplete: function(file) {
			
			
			if (file.response.error) {
				log.alert('Failed Upload', 'Uploading <em>' + this.fileList[0].name + '</em> failed, please try again. (Error: #' + this.fileList[0].response.code + ' ' + this.fileList[0].response.error + ')');
			} else {
				nowy_obrazek = JSON.decode(file.response.text, true).link;
				nowy_obrazek_nazwa = JSON.decode(file.response.text, true).name;
				
				var desc = new Element('textarea', {'class': 'file-desc','cols': '30','rows': '2','name': 'file_upload_txt[]'}).inject(file.ui.element, 'bottom');			
				var checkbox_file = new Element('input', {type: 'checkbox','class': 'file-check', 'checked': true,'name': 'file_upload[]','value': nowy_obrazek_nazwa}).inject(file.ui.element, 'top');							
				
				var desc = new Element('div', {'class': 'clear'}).inject(file.ui.element, 'bottom');								
				file.ui.element.highlight('#e6efc2');
			}			
		},				
		onFileError: function(file) {
			file.ui.cancel.set('html', 'Retry').removeEvents().addEvent('click', function() {
				file.requeue();
				return false;
			});
			
			new Element('span', {
				html: file.errorMessage,
				'class': 'file-error'
			}).inject(file.ui.cancel, 'after');
		},
		
		onFileRequeue: function(file) {
			file.ui.element.getElement('.file-error').destroy();
			
			file.ui.cancel.set('html', 'Cancel').removeEvents().addEvent('click', function() {
				file.remove();
				return false;
			});
			
			this.start();
		}
		
	});

});

}
