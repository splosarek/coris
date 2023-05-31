 var ayax_action=0;
 

function getSumaUbezp(val,obj_id1,obj_id){
	if (val > 0 ){
			ob = document.getElementById(obj_id1);
			
			ayax_action=1;
			var url = 'ayax/cardif_suma_vs_swiadczenie.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaSumyUbezp(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'sid': val,'wid': ob.value});
	}else{
		aktualizacjaSumyUbezp(obj_id,{'value': 0});
	}
}


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


function getWariantUmowy(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/cardif_wariant_vs_typ.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				aktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'tid': val});
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



function aktualizacjaSumyUbezp(obj_id,items){	   
    obj=document.getElementById(obj_id);
    if (obj){      
	 	obj.value = items.suma;		    		
    }
	    
}



function edycja_wykonawcy(id,dec){
	window.open('AS_cases_details_expenses_position_details.php?expense_id=' + id + '&decision_id='+dec+'&tryb=cardif','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=480,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);	
}
