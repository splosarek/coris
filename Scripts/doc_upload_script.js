
licznik = 0;
function addToList(type,txt,link){

	
	var list = $('demo-list')
	
	ui = {};
	id_nazwa = 'attach-' + licznik++;
	ui.element = new Element('li', {'class': 'file', id: id_nazwa});
	
	var name_file = new Element('a', {'class': 'file-title', text: txt.substr(0,60), href: link , title: txt,target: '_blank'});
	
	if (txt.toLowerCase().search('.pdf') > 0 ){
		var file_edit = new Element('a', {  href: '#','rel': id_nazwa  , title: 'edycja pliku pdf',
		'styles': {	    
			'margin-left': '10px'
	    },'events': {
	        'click': function(){
            	edit_pdf(this.getProperty('rel'),type,link,txt);
        	}
	    }
		});
	
		var ico_edit = new Element('img',{'src': 'img/edit.gif'});
		file_edit.adopt(ico_edit);
	}else{
		var file_edit = new Element('span');
	}
	
	
	var file_delete = new Element('a', { href: '#','rel': id_nazwa , title: 'usun zalacznik',
		'styles': {	    
			'margin-left': '10px'
	    },'events': {
	        'click': function(){
	    	removeFromList(this.getProperty('rel'));
        	}
	    }
		});	
		var ico_delete = new Element('img',{'src': 'img/delete.gif', 'border': 0});
		file_delete.adopt(ico_delete);
	
	var checkbox_file = new Element('input', {type: 'checkbox','class': 'file-check', 'checked': true,'name': 'file_upload[]','value': id_nazwa});							
	var hidden_file2 = new Element('input', {type: 'hidden','name': 'file_upload_org_name['+id_nazwa+']','value': link});							
	var hidden_file3 = new Element('input', {type: 'hidden','name': 'file_upload_name['+id_nazwa+']','value': txt});							
	var hidden_file = new Element('input', {type: 'hidden','name': 'file_upload_type['+id_nazwa+']','value': type});							
	  ui.element.adopt(			 
			  checkbox_file,
			  hidden_file2,
			  hidden_file3,
			  hidden_file,
			  name_file,			  
			  file_edit,
			  file_delete
		    ).inject(list).highlight();
}
    

	 function edit_pdf(id_nazwa,type,link,txt){
		 	//alert('Jest: '+id_nazwa+', '+type+', '+link);
		 	$('interface').opacity = 20;
		 	myWindow = new MUI.Modal({'id':'mywin1',width:710,height:650,top:50,
		 		type: 'modal',
		 		loadMethod: 'xhr',
		 		contentURL: 'ayax/attach_edit.php?id_object='+id_nazwa+'&type='+type+'&fid='+link+'&name='+txt,
		 		title: 'Edycja pliku PDF',
		 		icon: 'img/edit.gif'
		 		 	
		 	});
		 	myWindow.center();
}
	 
	 
function removeFromList(objID){
	if (JQ('#'+objID)){
	//	$('#'+objID).innerHTML = '';
        JQ('#'+objID).remove();
      //  $("#demo-list").listview("refresh");

	}else{
		alert('Blad usuwania objID='+objID);
	}
}	 


function dodaj_zalacznik(){
    JQ('#fileupload').click();

}

/*data: {
 foo: 'bar'
 },*/

function OnProgress(event, position, total, percentComplete){
    //Progress bar
    alert("preogress");
    console.log(total);
    //$('#pb').width(percentComplete + '%') //update progressbar percent complete
    //$('#pt').html(percentComplete + '%'); //update status text
}
function beforeSubmit(){
    alert("BS ");
    console.log('ajax start');
}
function afterSuccess(data){
    alert("AS " +  data)
    console.log(data);
}

function progressHandlingFunction(e){
    if(e.lengthComputable){
        console.log( e.loaded + " " + e.total);
    }
}

function start_upload(file){

    var filedata = document.getElementById("fileupload");
    formdata = new FormData();
    var i = 0, len = filedata.files.length, file;
    var lista= [];

    for (i; i < len; i++) {
        file = filedata.files[i];
        formdata.append("files[]", file);
        kid = Math.floor(Math.random() * (1000000 + 1));
        lista.push(kid);
        JQ('#demo-list').append('<li id="file-'+kid+'">'+filedata.files[i].name +' <img src="img/loader.gif" width="18"></li>');
    }

    formdata.append("json",true);

    JQ.ajax({
        url: "script2.php",
        type: "POST",
        data: formdata,
        processData: false,
        contentType: false,
       dataType:"JSON",
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = JQ.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
            }
            return myXhr;
        },
        success: function(data, textStatus, jqXHR)
        {
            if(typeof data.error === 'undefined')
            {
                console.log('OK: ');
                reactionFU(data,lista);
            }else {
                console.log('ERRORS1: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            console.log('ERRORS2: ' + textStatus);
        }

    });
}


function reactionFU(file,lista){
    if (file.response.error) {
        log.alert('Failed Upload', 'Uploading <em>' + this.fileList[0].name + '</em> failed, please try again. (Error: #' + this.fileList[0].response.code + ' ' + this.fileList[0].response.error + ')');
    } else {

        for (i=0; i< file.response.length; i ++ ) {
            nowy_obrazek_link = file.response[i].link;
            nowy_obrazek_nazwa = file.response[i].name;

          // kid = Math.floor(Math.random() * (1000000 + 1));
            kid = lista[i];

            linia = '<input type="checkbox" class="file-checkbox" checked="true" name="file_upload[]" value="file-' + kid + '"  style="margin-right: 10px;">';
            linia += '<a class="file-title" href="DOC_get_content.php?id=' + nowy_obrazek_link + '&source=upload&action=view" title="' + nowy_obrazek_nazwa + '" target="_blank">' + nowy_obrazek_nazwa.substr(0, 45) + '</a>';

            linia += '<input type="hidden" name="file_upload_org_name[' + 'file-' + kid + ']" value="' + nowy_obrazek_link + '">';
            linia += '<input type="hidden" name="file_upload_name[' + 'file-' + kid + ']" value="' + nowy_obrazek_nazwa + '">';
            linia += '<input type="hidden" name="file_upload_type[' + 'file-' + kid + ']" value="upload">';


            if (nowy_obrazek_nazwa.toLowerCase().search('.pdf') > 0) {
                linia += '<a rel="file-' + kid+'" href="#" title="edycja pliku pdf" style="margin-right:10px;margin-left: 10px" onClick= "edit_pdf(\'file-'+ kid+'\', \'upload\', \''+ nowy_obrazek_link + '\',\'' + nowy_obrazek_nazwa +'\')"><img src="img/edit.gif" border="0"></a>';
            }


            linia += '<a href="#" rel="file-'+ kid+'" title="usun zalacznik" style="margin-right:10px;margin-left: 10px;" onClick="removeFromList(\'file-'+ kid+'\')"><img src="img/delete.gif" border="0"></a>';


            //JQ('#demo-list').append('<li id="file-'+kid+'">'+linia+'</li>');
            JQ('#file-'+kid).html(linia)

        }
    }
}
function initloader(){
    return ;
window.addEvent('domready', function() {

	/**
	 * Uploader instance
	 */
	up = new FancyUpload4.Attach('demo-list', '#demo-attach, #demo-attach-2', {
		path: 'Scripts/Swiff.Uploader.swf',
		url: 'script.php',
		fileSizeMax: 10 * 1024 * 1024,
		
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
		},onFileComplete: function(file) {					
			if (file.response.error) {
				log.alert('Failed Upload', 'Uploading <em>' + this.fileList[0].name + '</em> failed, please try again. (Error: #' + this.fileList[0].response.code + ' ' + this.fileList[0].response.error + ')');
			} else {
				nowy_obrazek_link = JSON.decode(file.response.text, true).link;
				nowy_obrazek_nazwa = JSON.decode(file.response.text, true).name;
								
				var name_file = new Element('a', {'class': 'file-title', text: nowy_obrazek_nazwa.substr(0,45), href: 'DOC_get_content.php?id='+nowy_obrazek_link+'&source=upload&action=view' , title: nowy_obrazek_nazwa,target: '_blank'}).inject(file.ui.element, 'top');
				var checkbox_file = new Element('input', {type: 'checkbox','class': 'file-check', 'checked': true,'name': 'file_upload[]','value': 'file-'+kid}).inject(file.ui.element, 'top');							
				var hidden_file = new Element('input', {type: 'hidden','name': 'file_upload_org_name['+'file-'+kid+']','value': nowy_obrazek_link}).inject(file.ui.element, 'top');							
				var hidden_file = new Element('input', {type: 'hidden','name': 'file_upload_name['+'file-'+kid+']','value': nowy_obrazek_nazwa}).inject(file.ui.element, 'top');							
				var hidden_file1 = new Element('input', {type: 'hidden','name': 'file_upload_type['+'file-'+kid+']','value': 'upload'}).inject(file.ui.element, 'top');	
				
				if (nowy_obrazek_nazwa.toLowerCase().search('.pdf') > 0 ){
					var file_edit = new Element('a', {'rel': 'file-'+kid ,  href: '#' , title: 'edycja pliku pdf',
					'styles': {	    
						'margin-left': '10px'
				    },'events': {
				        'click': function(){				    	
			            		edit_pdf(this.getProperty('rel'),'upload',nowy_obrazek_link,nowy_obrazek_nazwa);
			        	}
				    }
					});
						
					var ico_edit = new Element('img',{'src': 'img/edit.gif'});
					file_edit.adopt(ico_edit);
					file_edit.inject(file.ui.element, 'bottom');
					
				}else{
					var file_edit = new Element('span');
				}
				
				
				var file_delete = new Element('a', { href: '#' ,'rel': 'file-'+kid, title: 'usun zalacznik',
					'styles': {	    
						'margin-left': '10px'
				    },'events': {
				        'click': function(){
				    	removeFromList(this.getProperty('rel'));
			        	}
				    }
					});	
					var ico_delete = new Element('img',{'src': 'img/delete.gif', 'border': 0});
					file_delete.adopt(ico_delete);
					file_delete.inject(file.ui.element, 'bottom');
					
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

