function popup(link,name,width,height){
	
	window.open(link,name,'toolbar=no,scrollbars=yes,location=no,status=yes,menubar=no,resizable=yes,width='+width+',height='+height+',left=10,top=0')
	
}
function popUpWindow(name, URLStr, width, height) {
  var left = (screen.availWidth - width) / 2;
  var top = ((screen.availHeight - 0.2 * screen.availHeight) - height) / 2;
  open(URLStr, name, 'scrollbars=no,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+',top='+top+',screenX='+left+',screenY='+top+'');
}


function open_case(id,title_txt){
		//if (id > 0 ){
		    var url = "AS_cases_details.php?case_id=" + id;
			var childwin = window.open(url, title_txt, 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1350,height=880,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
			//childwin.opener = parent;
			childwin.focus();			
		//}
}


function open_case2(id,title_txt){	
		    var url = "AS_cases_details.php?case_id=" + id;
			var childwin = window.open(url, title_txt, 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1350,height=880,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
			//childwin.opener = parent;
			childwin.focus();			
	
}

function open_caseF(id,title_txt){	
		    var url = "../coris/AS_cases_details.php?case_id=" + id;
			var childwin = window.open(url, title_txt, 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=1350,height=880,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
			//childwin.opener = parent;
			childwin.focus();			
	
}

function CalcDate(due, inval, indate, outdate) {
  if (!inval) {
    return false;
  }
  if (!inval.match(/^\d\d\d\d-\d\d-\d\d$/i)) {
    alert("Niew³a¶ciwy format daty! Poprawny: yyyy-mm-dd");
    return false;
  }


  var arrDate = inval.split("-");
  var y = arrDate[0];
  var m = parseInt(arrDate[1], 10);
  var d = parseInt(arrDate[2], 10);

  var days = new makeArray(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  if (((y % 4 == 0) && (y % 100 != 0)) || (y % 400 == 0)) {
    days[2] = 29;
  } else {
    days[2] = 28;
  }
  var months = 12;
  var nd, nm, ny; // new date

  if (due == 1) {
    // 2 tydzieñ
    nd = d + 14;
  } else if (due == 2) {
    // 3 tygodnie
    nd = d + 21;
  } else if (due == 3) {
    nd = d + 30;
  } else if (due == 4) {
    nd = d + 60;
  } else if (due == 0) {
    outdate.value = '';
    return false;
  }

  nm = m;
  ny = y;

  if (nd > days[nm]) {
    nd = nd - days[nm];
    nm++;
  }
  if (nd > days[nm]) {
    nd = nd - days[nm];
    nm++;
  }
  if (nm > months) {
    nm = nm - months;
    ny++;
  }

  nd = (nd < 10) ? new String("0" + nd) : nd;
  nm = (nm < 10) ? new String("0" + nm) : nm;
  outdate.value = ny + "-" + nm + "-" + nd;

}
function makeArray() {
  for (i = 0; i < makeArray.arguments.length; i++) {
    this[i] = makeArray.arguments[i];
  }
}



function FormcheckboxSelect(d) {
				s = document.getElementById(d);
				
				if (s.checked) {					
					s.checked = false;
				} else {
					s.checked = true;
				}				
}


 function move(s,form1) {	 	
		e = window.event;
		var keyInfo = String.fromCharCode(e.keyCode);

		if (e['keyCode'] != 9 && e['keyCode'] != 16 && e['keyCode'] != 8) {
			for (var i = 0; i < form1.length; i++) {
				if (s.name == form1.elements[i].name) {
					if ((form1.elements[i].value.length == 2)) {
						form1.elements[i+1].focus();
						return false;
					}
				}
			}
		}
}

function move_formant(s,form1,e) {	 	
	// e = window.event;
	 //var keyInfo = String.fromCharCode(e.keyCode);
	if(window.event)
		var keyInfo  = window.event.keyCode; // IE
	else
		var keyInfo  = e.charCode;	
	 
	// if (e['keyCode'] != 9 && e['keyCode'] != 16 && e['keyCode'] != 8) {
	 if (keyInfo != 9 && keyInfo != 16 && keyInfo != 8) {
		 for (var i = 0; i < form1.length; i++) {
			 if (s.name == form1.elements[i].name) {
				 if ((form1.elements[i].value.length == 2)) {
					 form1.elements[i+1].focus();
					 return false;
				 }
			 }
		 }
	 }
 }
 
function remove_formant(s,form1,e) {
	//e = window.event;
	//var keyInfo = String.fromCharCode(e.keyCode);
//evt = e || window.event;
//	  var pressedKey = evt.charCode || evt.keyCode;
	
	if(window.event)
		var keyInfo  = window.event.keyCode; // IE
	else
		var keyInfo  = e.charCode;
	
	

	//if (e['keyCode'] == 8) {
	if (keyInfo == 8) {
		for (var i = 0; i < form1.length; i++) {
			if (s.name == form1.elements[i].name) {
				if ((form1.elements[i].value.length == 0)) {
					form1.elements[i-1].focus();
					var rng = form1.elements[i-1].createTextRange();
					rng.select();
					return false;
				}
			}
		}
	}
}

function isDate(y, m, d) {
    var dayobj = new Date(y, m-1, d);
    if ((dayobj.getMonth()+1!=m)||(dayobj.getDate()!=d)||(dayobj.getFullYear()!=y)) {
        return false;
    }
    return true;
}

function isPolisa(number) {
    var re = /^[Kk]\d{8}$/;
    return re.test(number);
}
function getCaseCurrency(case_id,currency,raport_info,form_table_id,form_rate){
		ayax_action=1;
		tryb=1;
		
		
		var url = 'ayax/case_currency.php';
		var jsonRequest = new Request.JSON({encoding: 'iso-8859-2',url: url, 
		onComplete: function(jsonObj) {
				var item_get = jsonObj.item;
				
				var table_id = item_get.table_id;
				var table_date = item_get.table_date;
				var table_no = item_get.table_no;
				var table_source = item_get.table_source;
				var rate = item_get.rate;
				var status = item_get.status;
				if (status)
					UpdateCaseCurrency(tryb,table_id,table_date,table_no,rate,table_source,raport_info,form_table_id,form_rate);
				else
					alert('B³±d kursu waluty')	; ayax_action=0;
		}}).get({'case_id': case_id, 'currency_id': document.getElementById(currency).value });
}

function getCaseCurrencyExp(case_id,currency,raport_info,form_table_id,form_rate){
	ayax_action=1;
	tryb=1;
	
	
	var url = 'ayax/case_currency.php';
	var jsonRequest = new Request.JSON({encoding: 'iso-8859-2',url: url, 
		onComplete: function(jsonObj) {
			var item_get = jsonObj.item;
			
			var table_id = item_get.table_id;
			var table_date = item_get.table_date;
			var table_no = item_get.table_no;
			var table_source = item_get.table_source;
			var rate = item_get.rate;
			var status = item_get.status;
			if (status)
				UpdateCaseCurrency(tryb,table_id,table_date,table_no,rate,table_source,raport_info,form_table_id,form_rate);
			else
				alert('B³±d kursu waluty')	; ayax_action=0;
		}}).get({'case_id': case_id, 'currency_id': document.getElementById(currency).value, 'table_id': document.getElementById(form_table_id).value });
}

function UpdateCaseCurrency(tryb,table_id,table_date,table_no,rate,table_source,raport_info,form_table_id,form_rate){
	
		  if (tryb==1)	{
		  		form_table_id = document.getElementById(form_table_id);							
				form_table_id.value=table_id;		  	
		  		form_raport = document.getElementById(raport_info);					  											
				form_raport.innerHTML= '<small>'+rate+' '+table_source+' z d. '+ table_date +'</small>';	
				if ($(form_rate)){
					$(form_rate).value=rate;				
		  		}
		  }						
}

function load_ayaxP(id,url,param){		 	//encoding: 'ISO-8859-2',
	 $(id).set('load', {evalResponse: true, evalScripts: true,method: 'post',encoding: 'utf-8',urlEncoded: true,data: param,
	 	onSuccess: function(response1Text, responseXML){ 		 		
	 			$(id).removeClass('ajax-loading');} 
	 	});
	 $(id).empty().addClass('ajax-loading');
   $(id).load(url);	
}

function load_ayax(id,url){		 	//encoding: 'ISO-8859-2',
		if (	!$(id) ){
			//alert ('ID error: '+id);
			return;
		}
	
		 $(id).set('load', {evalResponse: true, evalScripts: true,method: 'post',
		 	onSuccess: function(response1Text, responseXML){ 		 		
		 			$(id).removeClass('ajax-loading');} 
		 	});
		 $(id).empty().addClass('ajax-loading');
         $(id).load(url);	
}

function clear_div(id){
	$(id).empty();	
}

function load_ayax_HTML(id,url){		 	//encoding: 'ISO-8859-2',
	 $(id).set('load', {evalResponse: true, evalScripts: true,method: 'post',
		 	onComplete: function(responseTree, responseElements, responseHTML) { 
	 			$(id).removeClass('ajax-loading');
				$(id).set('html', responseHTML); 
	 		}
	 	}).load(url);
	 $(id).empty().addClass('ajax-loading');	
}

function save_new_note(form_id,url){    	//,encoding: 'ISO-8859-2'
		var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
				onComplete: function(responseTree, responseElements, responseHTML) { 
					$('historyframe').removeClass('ajax-loading')
					$('historyframe').set('html', responseHTML); 
				} 		
				}).post($(form_id));
		$('historyframe').empty().addClass('ajax-loading')
}

function save_new_note2(div_object,form_id,url){    	//,encoding: 'ISO-8859-2'
	var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
		onComplete: function(responseTree, responseElements, responseHTML) { 
			$(div_object).removeClass('ajax-loading')
			$(div_object).set('html', responseHTML); 
		} 		
	}).post($(form_id));
	$(div_object).empty().addClass('ajax-loading')
}

function save_new_sms(form_id,url){    	//,encoding: 'ISO-8859-2'
	var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
		onComplete: function(responseTree, responseElements, responseHTML) { 
			$('historyframe').removeClass('ajax-loading')
			$('historyframe').set('html', responseHTML); 
		} 		
	}).post($(form_id));
	$('historyframe').empty().addClass('ajax-loading')
}

function save_new_sms2(div_object,form_id,url){    	//,encoding: 'ISO-8859-2'
	var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
		onComplete: function(responseTree, responseElements, responseHTML) { 
			$(div_object).removeClass('ajax-loading')
			$(div_object).set('html', responseHTML); 
		} 		
	}).post($(form_id));
	$(div_object).empty().addClass('ajax-loading')
}


function init_documents(case_id,param){
	      load_ayaxP('historyframe','ayax/case_interactions.php','case_id='+case_id+'&'+param);
}
	
function new_note(case_id){
	load_ayax('historyframe','ayax/case_interaction_note.php?case_id='+case_id+'&type=note');		 
}

function new_conversation(case_id,type){
	load_ayax('historyframe','ayax/case_interaction_note.php?case_id='+case_id+'&type='+type);		 
}

function new_sms(case_id){	
	load_ayax('historyframe','ayax/case_interaction_sms.php?case_id='+case_id);		 
}

function print_interaction(id){
	
	window.open('DOC_document_view.php?id='+id+'&direct=&tryb=print','','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=710');
}

function open_interaction(typ,direction,id,case_id,tryb){
	 
	 switch(typ){
	 	  	case 2: //note
	 	  		load_ayax('historyframe','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=note');
                break;
                
	 	  	case 3: //call
	 	  		if (direction == 1){
	 	  			load_ayax('historyframe','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=call_in');
	 	  		}else{
	 	  			load_ayax('historyframe','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=call_out');
	 	  		}	
                
                break;                
            case 4: //email
					if (direction == 1){
						window.open('DOC_document_view.php?id='+id+'&direct=out&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
					}else{
		                	window.open('DOC_document_view.php?id='+id+'&direct=in&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
					}
				  break;                   
            case 5:  //fax /            	
            	if (direction == 1){                    
            		window.open('DOC_document_view.php?id='+id+'&direct=out&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');            		
            	}else{
                	window.open('DOC_document_view.php?id='+id+'&direct=in&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
            	}
                break;
          	case 6: //SMS
          		
          		if (tryb == 'sorter_out' || tryb == 'export'){
          			if (direction == 1){
          				window.open('DOC_document_view.php?id='+id+'&direct=out&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');            		
          			}else{
          				window.open('DOC_document_view.php?id='+id+'&direct=in&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
          			}	
          		}else{
		 	  		if (direction == 1){
		 	  			load_ayax('historyframe','ayax/case_interaction_sms.php?id='+id+'&case_id='+case_id+'&type=call_in');
		 	  		}else{
		 	  			load_ayax('historyframe','ayax/case_interaction_sms.php?id='+id+'&case_id='+case_id+'&type=call_out');
		 	  		}
          		}
     }                             
}

function open_case_interaction(typ,direction,id,case_id,tryb,row_tr){
	
	
	//interactions_row
	$$(".interactions_row").removeClass('interactions_row_select');
	row_tr.addClass('interactions_row_select');
	
	
	switch(typ){
	case 2: //note
		load_ayax('case_document_view','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=note');
		break;
		
	case 3: //call
		if (direction == 1){
			load_ayax('case_document_view','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=call_in');
		}else{
			load_ayax('case_document_view','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=call_out');
		}	
		
		break;                
	case 4: //email
		if (direction == 1){
			load_ayax('case_document_view','ayax/doc_case_view.php?id='+id+'&direct=out&tryb='+tryb);
			//window.open('DOC_document_view.php?id='+id+'&direct=out&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
		}else{
			//window.open('DOC_document_view.php?id='+id+'&direct=in&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
			load_ayax('case_document_view','ayax/doc_case_view.php?id='+id+'&direct=in&tryb='+tryb);
		}
		break;                   
	case 5:  //fax /            	
		if (direction == 1){                    
			load_ayax('case_document_view','ayax/doc_case_view.php?id='+id+'&direct=out&tryb='+tryb);
			//window.open('DOC_document_view.php?id='+id+'&direct=out&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');            		
		}else{
			//window.open('DOC_document_view.php?id='+id+'&direct=in&tryb='+tryb,'','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');
			load_ayax('case_document_view','ayax/doc_case_view.php?id='+id+'&direct=in&tryb='+tryb);
		}
		break;
	case 6: //SMS
			if (direction == 1){
				load_ayax('case_document_view','ayax/case_interaction_sms.php?id='+id+'&case_id='+case_id+'&type=call_in');
				
			}else{
				load_ayax('case_document_view','ayax/case_interaction_sms.php?id='+id+'&case_id='+case_id+'&type=call_out');
				//load_ayax('case_document_view','ayax/case_interaction_note.php?id='+id+'&case_id='+case_id+'&type=note');
			}
	}	                             
}


function  unbind_document(doc_id,case_id){
	if (confirm('Czy chcesz usun±æ ten dokument ze sprawy?')){		
		load_ayax('document_case_binded','ayax/sorter_doc_binded_case.php?action=unbind&doc_id='+doc_id+'&case_id='+case_id);
	}
}

function doc_binded(doc_id){	
	load_ayax('document_case_binded','ayax/sorter_doc_binded_case.php?action=list&doc_id='+doc_id);
}

function save_ad_doc_to_case(case_id,doc_id){
	
	var_category_id = document.getElementById('category_id').value;		
	var_reclamation = document.getElementById('reclamation').checked;
	
	if (var_category_id == 0){
		alert('Proszê wybraæ kategoriê!');
		return;
    }
	load_ayax('case_add_form','ayax/sorter_doc_add_to_case.php?action=add&case_id='+case_id+'&doc_id='+documentID+'&category_id='+var_category_id+'&reclamation='+var_reclamation);
}

function roszczenie_sprawdz_rezerwe(case_id,id,val,obj_id,obj_id2,obj_rez,tryb_init){
	if (! $(obj_id) ) return false;
	case_getSuma_do_wyk(case_id,0,obj_id,obj_id2,obj_rez,id,tryb_init);
}

function roszczenie_sprawdz_rezerwe2(case_id,currency,val,obj_id,obj_id2,obj_rez){
	if (! $(obj_id) ) return false;
	//case_getSuma_do_wyk2(case_id,0,obj_id,obj_id2,obj_rez,waluta);
	if (case_id > 0 ){
		//ob = document.getElementById(obj_id1);
		
		//currency='';
	///	if (val2 > 0 ){
		//		currency =  $('currency_id['+val2+']').value;
		//}
		ayax_action=1;
		var url = 'ayax/case_suma_swiadczenie.php';
		var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
		onComplete: function(jsonObj) {																
			roszczenieAktualizacjaSumyUbezpKwota2(obj_id,obj_id2,jsonObj,'PLN',obj_rez,currency);		
			ayax_action=0;
		}}).get({'eid': val,'case_id':case_id,'cid': -1,'currency_id': currency});
	}
}

function roszczenieAktualizacjaSumyUbezpKwota2(obj_id,obj_id2,jsonObj,waluta,obj_rez,obj_currency_val){	
	  id=0;///
    obj=document.getElementById(obj_id);
    obj2=document.getElementById(obj_id2);
   
    
    
    obj_table_id = obj_rez.getNext().getNext();
    
    obj_pln_value = obj_table_id.getNext(); 
    
    
    rezerwa_tmp = 0.0
    lista1  = document.getElementsByName('roszczenie_pln_value');
    lista2  = document.getElementsByName('add_pln_value[]');
    
    
    for (i = 0;i<lista1.length;i++){
    		if (lista1[i] != obj_pln_value ){
    				rezerwa_tmp = (1.0 * rezerwa_tmp + 1.0 * lista1[i].value).toFixed(2);
    		}    			
    }
    rezerwa_tmp = (1.0*rezerwa_tmp).toFixed(2);
    for (i = 0;i<lista2.length;i++){
    	if (lista2[i] != obj_pln_value ){
    		rezerwa_tmp =  (1.0 * rezerwa_tmp + 1.0 * lista2[i].value).toFixed(2);
    	}    			
    }
    
    
    
    rezerwa_tmp = (1.0*rezerwa_tmp).toFixed(2);

    if (typeof  lista_pozycji != 'undefined' ){
    	for (i = 0;i<lista_pozycji.length;i++){	
				//rezerwa_tmp += 1.0 * $('roszczenie_pln_value['+lista_pozycji[i]+']').value;
    			 rezerwa_tmp = (1.0 * rezerwa_tmp +  1.0 * $('roszczenie_pln_value['+lista_pozycji[i]+']').value - 1.0*$('zgloszenie_rezerwa_org['+lista_pozycji[i]+']').value).toFixed(2);
    	}
    }
    rezerwa_tmp = (1.0*rezerwa_tmp).toFixed(2);
    
    if (obj){      
	 	obj.value = (1.0 * jsonObj.rezerwa_globalna+1.0*rezerwa_tmp).toFixed(2);		  	 	
    }
    
    //alert(rezerwa_tmp);
    dostepna_rezerwa = ( 1.0 * jsonObj.rezerwa_globalna - 1.0 * jsonObj.rezerwa_all ).toFixed(2); // to co zapisane w bazie  
    dostepna_rezerwa_tmp = ( 1.0 * jsonObj.rezerwa_globalna - 1.0 * jsonObj.rezerwa_all - 1.0 * rezerwa_tmp).toFixed(2); // z uwzglednieniem wirtualnych dodawanych aktualnie
    
	if (obj2){      		 
	 	obj2.value = dostepna_rezerwa;
		 /*if (dostepna_rezerwa < 0.0){
			  if (confirm('Przekroczona rezerwa globalna2!!. Czy chcesz przeszacowaæ?')){
				  	nowa_rezerwa_globalna = (1.0*jsonObj.rezerwa_globalna - 1.0*dostepna_rezerwa ).toFixed(2);
					nowa_rezerwa_globalna_zmiana = - 1.0*dostepna_rezerwa;
					if (confirm('Przekroczona rezerwa globalna!!. Czy chcesz przeszacowaæ? Nowa warto¶æ: '+nowa_rezerwa_globalna +', zmiana o: '+nowa_rezerwa_globalna_zmiana)){
						$('case_rezerwa_globalna_zmiana').value=1;
						$('rezerwa_globalna').value = nowa_rezerwa_globalna;
						$('przycisk_save').disabled='';
						$('przycisk_save').set('class','disabled');
	//					obj2.value = 0;
					}else{
						$('przycisk_save').disabled='yes';
						$('przycisk_save').set('class','');
					}
			  }
		 }*/
    }
	    
	if ( obj_rez ){
		if ( obj_currency_val == 'PLN' ){				
			kwota = 1.00 * obj_rez.value.replace(',','.');
		  //  $('roszczenie_table_id['+id+']').value = 0;
				obj_table_id.value = 0;
			obj_pln_value.value = kwota;
		}else if ( obj_currency_val == '' ){
			alert('waluta error');			
			return;
		}else{
			//kwota = (1.00 * obj_rez.value.replace(',','.')* (1.0*$('currency_rate_id').value)).toFixed(2);
			//$('roszczenie_table_id['+id+']').value = jsonObj.currency_table_id;
			obj_table_id.value = jsonObj.currency_table_id;
			kwota = (1.00 * obj_rez.value.replace(',','.')* (1.0*jsonObj.currency_rate)).toFixed(2);
			obj_pln_value.value = kwota; 
		}
		
		//kwota_po_zmianie = (dostepna_rezerwa - kwota+ 1.0 * jsonObj.rezerwa).toFixed(2);	
		kwota_po_zmianie = (dostepna_rezerwa_tmp- kwota+ 1.0 * jsonObj.rezerwa).toFixed(2);	
		//alert(kwota_po_zmianie);
		/*if ( kwota_po_zmianie < 0 ){
				nowa_rezerwa_globalna = (1.0*jsonObj.rezerwa_globalna - 1.0*kwota_po_zmianie ).toFixed(2);
				nowa_rezerwa_globalna_zmiana =  ((- 1.0*kwota_po_zmianie) -rezerwa_tmp).toFixed(2);
				if (confirm('Przekroczona rezerwa globalna!!. Czy chcesz przeszacowaæ? Nowa warto¶æ: '+nowa_rezerwa_globalna +', zmiana o: '+nowa_rezerwa_globalna_zmiana)){
					$('case_rezerwa_globalna_zmiana').value=1;
					$('rezerwa_globalna').value = nowa_rezerwa_globalna;
					$('przycisk_save').disabled='';
					$('przycisk_save').set('class','');
					
				}else{
					$('przycisk_save').disabled='yes';
					$('przycisk_save').setStyles({});
					$('przycisk_save').set('class','disabled');
				}
		}else{
			$('przycisk_save').disabled='';
			$('przycisk_save').set('class','');
		}
	
		*/
	}else{
		//alert('ERROR obj_Rez')
	} 
	 
}



function sprawdz_rezerwe(case_id,val,obj_id,obj_id2,obj_rez){
	if (! $(obj_id) ) return false;
	
	case_getSuma_do_wyk(case_id,val,obj_id,obj_id2,obj_rez,0,0);
}



function case_getSuma_do_wyk(case_id,val,obj_id,obj_id2,obj_rez,val2,tryb_init){
	if (! $(obj_id) ) return false;
	if (case_id > 0 ){
			//ob = document.getElementById(obj_id1);
			
			currency='';
			if (val2 > 0 ){
					currency =  $('currency_id['+val2+']').value;
			}
			ayax_action=1;
			var url = 'ayax/case_suma_swiadczenie.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				//if (val > 0) 
									
				if (val2 > 0){
						roszczenieAktualizacjaSumyUbezpKwota(obj_id,obj_id2,jsonObj,'PLN',obj_rez,val2,tryb_init);
				}else{
						aktualizacjaSumyUbezpKwota(obj_id,obj_id2,jsonObj,'PLN',obj_rez);
				}
				ayax_action=0;
			}}).get({'eid': val,'case_id':case_id,'cid': val2,'currency_id': currency});
	}else{
		if (val > 0) 
			aktualizacjaSumyUbezpKwota(obj_id,obj_id2,jsonObj,'PLN',obj_rez);				
		if (val2 > 0) 
			roszczenieAktualizacjaSumyUbezpKwota(obj_id,obj_id2,jsonObj,'PLN',obj_rez,val2);				
	}
}


function roszczenieAktualizacjaSumyUbezpKwota(obj_id,obj_id2,jsonObj,waluta,obj_rez,id,tryb_init){	
  
   var  obj=document.getElementById(obj_id);
   var  obj2=document.getElementById(obj_id2);
 //   if (obj){      
	// 	obj.value = jsonObj.rezerwa_globalna;		    		
    //}
   var  dostepna_rezerwa = ( 1.0 * jsonObj.rezerwa_globalna - 1.0 * jsonObj.rezerwa_all).toFixed(2);
  
   var rezerwa_tmp = 0.0;
   var rezerwa_tmp2 = 0.0;
   
    
    for (i = 0;i<lista_pozycji.length;i++){
    		if ( (1.0*lista_pozycji[i]) != id ){
    				if (tryb_init == 1){
    					//rezerwa_tmp2 += (1.0 * $('roszczenie_pln_value['+lista_pozycji[i]+']').value ).toFixed(2);
    				}else{
    					rezerwa_tmp2 += (1.0 * $('roszczenie_pln_value['+lista_pozycji[i]+']').value - 1.0*$('zgloszenie_rezerwa_org['+lista_pozycji[i]+']').value).toFixed(2);
    					
//    					alert('id:' +id+'/'+lista_pozycji[i]+',add rezerwa_tmp2: ' + rezerwa_tmp2 );
    				}
    		}    			
    }

    lista2  = document.getElementsByName('add_pln_value[]');
    for (i = 0;i<lista2.length;i++){
    		rezerwa_tmp += 1.0 * lista2[i].value;    		
    }
    //rezerwa_tmp2 = ( 1.0 * rezerwa_tmp2).toFixed(2);
       
    //zapamietac roznice 
    
    rezerwa_tmp = ( 1.0 * rezerwa_tmp + 1.0*rezerwa_tmp2).toFixed(2);
    
    if (tryb_init == 1){
    	
    }else{
    	//dostepna_rezerwa = (dostepna_rezerwa - rezerwa_tmp).toFixed(2);
    }
//alert( rezerwa_tmp);
  //  if (obj){      
//	 	obj.value = (1.0 * jsonObj.rezerwa_globalna + 1.0*rezerwa_tmp ).toFixed(2);		  	 	
 ///   }

    rezerwa_tmp = 1.0*rezerwa_tmp  ;
  //  alert("rezerwa_tmp: " + rezerwa_tmp);
//    dostepna_rezerwa = ( 1.0 * jsonObj.rezerwa_globalna - 1.0 * jsonObj.rezerwa_all ).toFixed(2); // to co zapisane w bazie  
    dostepna_rezerwa_tmp = ( 1.0 * jsonObj.rezerwa_globalna - 1.0 * jsonObj.rezerwa_all -  1.0 * rezerwa_tmp).toFixed(2); // z uwzglednieniem wirtualnych dodawanych aktualnie
    
    dostepna_rezerwa_tmp = 1.0 * dostepna_rezerwa_tmp;
	 if (obj2){      
		 
	 	obj2.value = dostepna_rezerwa;
		 if (dostepna_rezerwa < 0.0){
			 
			 /* if (confirm('Przekroczona rezerwa globalna2!!. Czy chcesz przeszacowaæ?')){
				  	
				  	nowa_rezerwa_globalna = (1.0*jsonObj.rezerwa_globalna - 1.0*dostepna_rezerwa  ).toFixed(2);
					nowa_rezerwa_globalna_zmiana = - 1.0*dostepna_rezerwa;
					if (confirm('Przekroczona rezerwa globalna!!. Czy chcesz przeszacowaæ? Nowa warto¶æ: '+nowa_rezerwa_globalna +', zmiana o: '+nowa_rezerwa_globalna_zmiana)){
						$('case_rezerwa_globalna_zmiana').value=1;
						$('rezerwa_globalna').value = nowa_rezerwa_globalna;
						$('przycisk_save').disabled='';
						$('przycisk_save').set('class','disabled');
					}else{
						$('przycisk_save').disabled='yes';
						$('przycisk_save').set('class','');
					}
			  }*/
		 }
    }
	    
	if ( obj_rez ){
		if ( $('currency_id['+id+']').value == 'PLN' ){				
			kwota = 1.00 * obj_rez.value.replace(',','.');
		    $('roszczenie_table_id['+id+']').value = 0;
		    $('roszczenie_pln_value['+id+']').value = kwota;
		    if (tryb_init == 1)
				$('zgloszenie_rezerwa_org['+id+']').value = kwota;
		}else if ( $('currency_id['+id+']').value == '' ){
			alert('waluta error');			
			return;
		}else{
			//kwota = (1.00 * obj_rez.value.replace(',','.')* (1.0*$('currency_rate_id').value)).toFixed(2);
			$('roszczenie_table_id['+id+']').value = jsonObj.currency_table_id;
			kwota = (1.00 * obj_rez.value.replace(',','.')* (1.0*jsonObj.currency_rate)).toFixed(2);
			$('roszczenie_pln_value['+id+']').value = kwota;
			if (tryb_init == 1){				
				$('zgloszenie_rezerwa_org['+id+']').value = kwota;
			}
		}
		
		//kwota_po_zmianie = (dostepna_rezerwa - kwota+ 1.0 * jsonObj.rezerwa).toFixed(2);
		kwota_po_zmianie = (dostepna_rezerwa- kwota+ 1.0 * jsonObj.rezerwa  ).toFixed(2);	
		
		//alert(kwota_po_zmianie);
		/*if ( kwota_po_zmianie < 0 ){
			//alert("kwota_po_zmianie: " + kwota_po_zmianie);
			//alert('rezerwa_tmp2: '+rezerwa_tmp2);
				nowa_rezerwa_globalna = (1.0*jsonObj.rezerwa_globalna - 1.0*kwota_po_zmianie + 1.0*rezerwa_tmp).toFixed(2);
				//nowa_rezerwa_globalna_zmiana = - 1.0*kwota_po_zmianie;
				//nowa_rezerwa_globalna_zmiana =  ((- (1.0*kwota_po_zmianie-1.0*rezerwa_tmp) ) ).toFixed(2);
				nowa_rezerwa_globalna_zmiana  = (nowa_rezerwa_globalna - 1.0*(obj.value.replace(',','.'))).toFixed(2);
				if (confirm('Przekroczona rezerwa globalna!!. Czy chcesz przeszacowaæ? Nowa warto¶æ: '+nowa_rezerwa_globalna +', zmiana o: '+nowa_rezerwa_globalna_zmiana)){
					$('case_rezerwa_globalna_zmiana').value=1;
					$('rezerwa_globalna').value = nowa_rezerwa_globalna;
					$('przycisk_save').disabled='';
					$('przycisk_save').set('class','');
					
				}else{
					$('przycisk_save').disabled='yes';
					$('przycisk_save').setStyles({});
					$('przycisk_save').set('class','disabled');
				}
		}else{
			$('przycisk_save').disabled='';
			$('przycisk_save').set('class','');
		}
	*/
		
	}else{
		//alert('ERROR obj_Rez')
	} 
	 
}
function aktualizacjaSumyUbezpKwota(obj_id,obj_id2,jsonObj,waluta,obj_rez){	
	
	obj=document.getElementById(obj_id);
	obj2=document.getElementById(obj_id2);
	if (obj){      
		obj.value = jsonObj.rezerwa_globalna;		    		
	}
	dostepna_rezerwa = ( 1.0 * jsonObj.rezerwa_globalna - 1.0 * jsonObj.rezerwa_all).toFixed(2);
	
	if (obj2){      
		
		obj2.value = dostepna_rezerwa;
		if (dostepna_rezerwa < 0.0){
			/*if (confirm('Przekroczona rezerwa globalna2!!. Czy chcesz przeszacowaæ?')){
				nowa_rezerwa_globalna = (1.0*jsonObj.rezerwa_globalna - 1.0*dostepna_rezerwa ).toFixed(2);
				nowa_rezerwa_globalna_zmiana = - 1.0*dostepna_rezerwa;
				if (confirm('Przekroczona rezerwa globalna!!. Czy chcesz przeszacowaæ? Nowa warto¶æ: '+nowa_rezerwa_globalna +', zmiana o: '+nowa_rezerwa_globalna_zmiana)){
					$('case_rezerwa_globalna_zmiana').value=1;
					$('case_rezerwa_globalna').value = nowa_rezerwa_globalna;
					$('przycisk_save').disabled='';
					$('przycisk_save').set('class','disabled');
				}else{
					$('przycisk_save').disabled='yes';
					$('przycisk_save').set('class','');
				}
			}*/
		}
	}
	
	if ( obj_rez ){
		if ( $('currency_klient').value == 'PLN' )				
			kwota = 1.00 * obj_rez.value.replace(',','.');
		else if ( $('currency_klient').value == '' ){
			alert('waluta error');
			$('client_amount').value='0,00';
			return;
		}else{
			kwota = (1.00 * obj_rez.value.replace(',','.')* (1.0*$('currency_rate_id').value)).toFixed(2);
		}
		kwota_po_zmianie = (dostepna_rezerwa - kwota+ 1.0 * jsonObj.rezerwa).toFixed(2);	
		//alert(kwota_po_zmianie);
		/*if ( kwota_po_zmianie < 0 ){
			nowa_rezerwa_globalna = (1.0*jsonObj.rezerwa_globalna - 1.0*kwota_po_zmianie ).toFixed(2);
			nowa_rezerwa_globalna_zmiana = - 1.0*kwota_po_zmianie;
			if (confirm('Przekroczona rezerwa globalna!!. Czy chcesz przeszacowaæ? Nowa warto¶æ: '+nowa_rezerwa_globalna +', zmiana o: '+nowa_rezerwa_globalna_zmiana)){
				$('case_rezerwa_globalna_zmiana').value=1;
				$('case_rezerwa_globalna').value = nowa_rezerwa_globalna;
				$('przycisk_save').disabled='';
				$('przycisk_save').set('class','przycisk_save_ok');
				
			}else{
				$('przycisk_save').disabled='yes';
				$('przycisk_save').setStyles({});
				$('przycisk_save').set('class','disabled');
			}
		}else{
			$('przycisk_save').disabled='';
			$('przycisk_save').set('class','przycisk_save_ok');
		}
		
		*/
	}else{
		//alert('ERROR obj_Rez')
	} 
	
}


function load_history_operating(case_id){
	load_ayax('opearating_history_frame','ayax/case_history_operating.php?case_id='+case_id)  ;
}

var myICD10Window;
function icd10_search(obj){


	//alert(1);


    myICD10Window = new MUI.Modal({'id':'mywin1',width:1010,height:650,top:50,
        closable: true,
		type: 'modal',
        loadMethod: 'xhr',
        contentURL: 'ayax/icd10_codes.php?code='+obj.value,
        title: 'ICD-10 code',

     /*   type: 'modal',*/
      /*  icon: 'img/edit.gif'
*/
    });
    myICD10Window.center();
}


function  checkCodeICD10(obj){

	var code = obj.value;
	if (code==""){
		$('icd10-desc').innerHTML="";
	}else{
          load_ayax('icd10-desc','ayax/icd10_check.php?code='+code);
	}

}