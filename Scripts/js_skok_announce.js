 var ayax_action=0;
 
 
 	
function getWariantUmowySkok(val,typ_umowy,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/skok_wariant_vs_bp.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowySkok(obj_id,jsonObj);
		
				
			 ayax_action=0;
			}}).get({'tid': typ_umowy,'bid': val});
	}else{
		aktualizacjaWariantUmowySkok(obj_id,new Array());
	}
} 	

function aktualizacjaWariantUmowySkok(obj_id,items){	
    var len = items.length;
    obj=document.getElementById(obj_id);
    if (obj){  
    	obj.options.length=0;
    	obj.options[0] = new Option('', 0, false, false);
	 	for (var i = 0; i < len; i++){		    		
	  		obj.options[obj.options.length] = new Option(items[i].nazwa, items[i].ID, false, false);		    		
		}
    }else{    	
    }    
}

function skok_getSumaUbezp_do_wyk(case_id,val,obj_id,obj_id2){
	if (val > 0 ){
			//ob = document.getElementById(obj_id1);
			
			ayax_action=1;
			var url = 'ayax/skok_suma_swiadczenie.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaSumyUbezpSkokKwota(obj_id,obj_id2,jsonObj,'PLN');				
				
				ayax_action=0;
			}}).get({'zid': val,'case_id':case_id});
	}else{
				aktualizacjaSumyUbezpSkokKwota(obj_id,obj_id2,jsonObj,'PLN');				
	}
}

function skok_getSumaUbezp(val,obj_id,obj_id2){
	if (val > 0 ){
			//ob = document.getElementById(obj_id1);
			
			ayax_action=1;
			var url = 'ayax/skok_suma_vs_wariant.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
					aktualizacjaSumyUbezpSkok(obj_id,obj_id2,jsonObj);
			 	ayax_action=0;
			}}).get({'zid': val});
	}else{
		aktualizacjaSumyUbezpSkok(obj_id,obj_id2,{'value': 0});
	}
}

function aktualizacjaSumyUbezpSkok(obj_id,obj_id2,items){	
  
    obj=document.getElementById(obj_id);
    obj2=document.getElementById(obj_id2);
    if (obj){      
	 	obj.value = items.suma;		    		
    }
	 if (obj2){      
	 	//obj2.value = items.currency_id;		    		
	 	obj2.options.length=0;
    	//obj2.options[0] = new Option('', 0, false, false);	  	
	  	obj2.options[0] = new Option(items.currency_id,items.currency_id, false, false);		    				
    }
	    
}
function aktualizacjaSumyUbezpSkokKwota(obj_id,obj_id2,jsonObj,waluta){	
  
    obj=document.getElementById(obj_id);
    obj2=document.getElementById(obj_id2);
    if (obj){      
	 	obj.value = jsonObj.suma;		    		
    }
	 if (obj2){      
	 	obj2.value = jsonObj.rezerwa;		    		
    }
	    
}

function edycja_wykonawcy(id){
	window.open('AS_cases_details_expenses_position_details.php?expense_id=' + id + '&tryb=Skok','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=550,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);	
}



///
function getWariantUmowySkokKod(val,typ_umowy,obj_id,obj_id2){
	if (val > 0 ){
			
			ayax_action=1;
			var url = 'ayax/skok_wariant_vs_bp.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowySkokKod(obj_id,jsonObj.wariant);
				aktualizacjaWariantUmowySkokKodOpcje(obj_id2,new Array());
				aktualizacjaSeriiPolisySkok('policy_series',jsonObj.seria_polisy);
			//	aktualizacjaSumyUbezpieczeniaSkok('policy_series',jsonObj.seria_polisy);
			 ayax_action=0;
			}}).get({'tid': typ_umowy,'bid': val});
	}else{
		aktualizacjaWariantUmowySkokKod(obj_id,new Array());
		aktualizacjaWariantUmowySkokKodOpcje(obj_id2,new Array());
	}
} 	

		
function aktualizacjaSeriiPolisySkok(obj_id,items){
	obj=document.getElementById(obj_id);
	//alert(items);
  //  if (obj && obj.value=='' && items != ''){ 
	if (items == null)
		items = '';
    		obj.value=items;
   // }	
}


function getWariantUmowySkokKodOpcje(val,obj_id){
	if (val > 0 ){
			
			ayax_action=1;
			var url = 'ayax/skok_opcje_vs_wariant.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {										
				aktualizacjaWariantUmowySkokKodOpcje(obj_id,jsonObj.opcje);
			 ayax_action=0;
			}}).get({'wid': val});
	}else{
		aktualizacjaWariantUmowySkokKod(obj_id,new Array());
		aktualizacjaWariantUmowySkokKodOpcje(obj_id,new Array());
	}
} 	

function aktualizacjaWariantUmowySkokKodOpcje(obj_id,items){	
    var len = items.length;
  //  alert(obj_id+'_lista');
    obj=document.getElementById(obj_id+'_lista');
     if (obj){  
    	obj.innerHTML='';
  
    	//obj.options.length=0;
    	//obj.options[0] = new Option('', 0, false, false);
    	txt = '';
	 	for (var i = 0; i < len; i++){		    		
	 		txt += '<input style="background-color:#AAAAAA;" type="checkbox" value="'+items[i]['ID']+'"';
	 		txt += ((items[i]['ID_opcja'] > 0 ) ? " checked" : "") +'  ';
	 		txt += (items[i]['opcja_status']==1 ? 'name="'+obj_id+'[]_fake" checked disabled' : ' name="'+obj_id+'[]" ') + '>'+items[i]['nazwa']+'<br>';
	 		if (items[i]['opcja_status']==1){
							txt += '<input type="hidden" name="'+obj_id+'[]" value="'+items[i]['ID']+'">';
						}
	 		obj.innerHTML=txt;
	  		//	obj.options[obj.options.length] = new Option(items[i].nazwa, items[i].ID, false, false);		    		
		}
    }else{    	
    }
        
}

function aktualizacjaWariantUmowySkokKod(obj_id,items){	
    var len = items.length;
    obj=document.getElementById(obj_id);
    if (obj){  
    	obj.options.length=0;
    	obj.options[0] = new Option('', 0, false, false);
	 	for (var i = 0; i < len; i++){		    		
	  		obj.options[obj.options.length] = new Option(items[i].nazwa, items[i].ID, false, false);		    		
		}
    }else{    	
    }    
}