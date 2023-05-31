 var ayax_action=0;
 
 
 	
function getWariantUmowyEuropa(val,typ_umowy,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/europa_wariant_vs_bp.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowyEuropa(obj_id,jsonObj);
		
				
			 ayax_action=0;
			}}).get({'tid': typ_umowy,'bid': val});
	}else{
		aktualizacjaWariantUmowyEuropa(obj_id,new Array());
	}
} 	

function aktualizacjaWariantUmowyEuropa(obj_id,items){	
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

function europa_getSumaUbezp_do_wyk(case_id,val,obj_id,obj_id2){
	if (val > 0 ){
			//ob = document.getElementById(obj_id1);
			
			ayax_action=1;
			var url = 'ayax/europa_suma_swiadczenie.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaSumyUbezpEuropaKwota(obj_id,obj_id2,jsonObj,'PLN');				
				
				ayax_action=0;
			}}).get({'zid': val,'case_id':case_id});
	}else{
				aktualizacjaSumyUbezpEuropaKwota(obj_id,obj_id2,null,'PLN');				
	}
}

function europa_getSumaUbezp(val,obj_id,obj_id2){
	if (val > 0 ){
			//ob = document.getElementById(obj_id1);
			
			ayax_action=1;
			var url = 'ayax/europa_suma_vs_wariant.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
					aktualizacjaSumyUbezpEuropa(obj_id,obj_id2,jsonObj);
			 	ayax_action=0;
			}}).get({'zid': val});
	}else{
		aktualizacjaSumyUbezpEuropa(obj_id,obj_id2,{'value': 0});
	}
}

function aktualizacjaSumyUbezpEuropa(obj_id,obj_id2,items){	
  
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
function aktualizacjaSumyUbezpEuropaKwota(obj_id,obj_id2,jsonObj,waluta){	
  
    obj=document.getElementById(obj_id);
    obj2=document.getElementById(obj_id2);
    if (obj && jsonObj){      
	 	obj.value = jsonObj.suma;		    		
    }
	 if (obj2 && jsonObj){      
	 	obj2.value = jsonObj.rezerwa;		    		
    }
	    
}

function edycja_wykonawcy(id,dec){
	window.open('AS_cases_details_expenses_position_details.php?expense_id=' + id + '&decision_id='+dec+'&tryb=europa','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=550,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);	
}



///
function getWariantUmowyEuropaKod(val,typ_umowy,obj_id,obj_id2){
	if (val > 0 ){
			
			ayax_action=1;
			var url = 'ayax/europa_wariant_vs_bp.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowyEuropaKod(obj_id,jsonObj.wariant);
				aktualizacjaWariantUmowyEuropaKodOpcje(obj_id2,new Array());
				aktualizacjaSeriiPolisyEuropa('policy_series',jsonObj.seria_polisy);
			//	aktualizacjaSumyUbezpieczeniaEuropa('policy_series',jsonObj.seria_polisy);
			 ayax_action=0;
			}}).get({'tid': typ_umowy,'bid': val});
	}else{
		aktualizacjaWariantUmowyEuropaKod(obj_id,new Array());
		aktualizacjaWariantUmowyEuropaKodOpcje(obj_id2,new Array());
	}
} 	

		
function aktualizacjaSeriiPolisyEuropa(obj_id,items){
	obj=document.getElementById(obj_id);
	//alert(items);
    //if (obj && obj.value=='' && items != ''){ 
	if (items == null)
		items = '';
    		obj.value=items;
    //}	
}


function getWariantUmowyEuropaKodOpcje(val,obj_id){
	if (val > 0 ){
			
			ayax_action=1;
			var url = 'ayax/europa_opcje_vs_wariant.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {										
				aktualizacjaWariantUmowyEuropaKodOpcje(obj_id,jsonObj.opcje);
			 ayax_action=0;
			}}).get({'wid': val});
	}else{
		aktualizacjaWariantUmowyEuropaKod(obj_id,new Array());
		aktualizacjaWariantUmowyEuropaKodOpcje(obj_id,new Array());
	}
} 	

function aktualizacjaWariantUmowyEuropaKodOpcje(obj_id,items){	
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

function aktualizacjaWariantUmowyEuropaKod(obj_id,items){	
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
/*


function getSwiadczenia(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/cardif_swiadczenie_vs_typ.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'tid': val});
	}else{
		aktualizacjaWariantUmowy(obj_id,new Array());
	}
}


function getMainCases(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/nhc_main_cases.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'policy_type': val});
	}else{
		aktualizacjaWariantUmowy(obj_id,new Array());
	}
}

function getSalesObject(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/nhc_sales_object.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'policy_type': val});
	}else{
		aktualizacjaWariantUmowy(obj_id,new Array());
	}
}


function aktualizacjaWariantUmowy(obj_id,items){	
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








*/