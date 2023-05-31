 var ayax_action=0;
 
 
function load_allianz_form(block){
	
	$(block).load('ayax/allianz_register_form.php');

}


function getKoloLowieckie(val){
	
	if (val > 0 || val == 'new' ){
		ayax_action=1;
		var url = 'ayax/allianz_kolo_lowieckie.php';
		var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
		onComplete: function(jsonObj) {						
			aktualizacjaKoloLowieckie(jsonObj);				
			ayax_action=0;
		}}).get({'kid': val});
	}else{
		aktualizacjaKoloLowieckie(new Array());
	}	
}


function aktualizacjaKoloLowieckie(jsonObj){
	
		if (jsonObj.kolo_dane){
				
				$('kolo_nazwa').value = jsonObj.kolo_dane.nazwa;
				$('kolo_nazwa').readOnly=false;
				
				$('kolo_adres').value = jsonObj.kolo_dane.adres;
				$('kolo_adres').readOnly=false;
				$('kolo_kod').value = jsonObj.kolo_dane.kod;
				$('kolo_kod').readOnly=false;
				$('kolo_miejscowosc').value = jsonObj.kolo_dane.miejscowosc;
				$('kolo_miejscowosc').readOnly=false;
				$('kolo_zo').value = jsonObj.kolo_dane.ZO;
				$('kolo_zo').readOnly=false;
				$('kolo_konto').value = jsonObj.kolo_dane.konto_bankowe ;
				$('kolo_konto').readOnly=false;				
		}else{			
			$('kolo_nazwa').value = '';
			$('kolo_nazwa').readOnly=true;
			
			$('kolo_adres').value = '';
			$('kolo_adres').readOnly=true;
			$('kolo_kod').value = '';
				$('kolo_kod').readOnly=true;
			$('kolo_miejscowosc').value = '';
			$('kolo_miejscowosc').readOnly=true;
			$('kolo_zo').value = '';
			$('kolo_zo').readOnly=true;
			$('kolo_konto').value = '';
			$('kolo_konto').readOnly=true;
			
		}
			
		
		if (jsonObj.ubezpieczenie){
			$('kolo_nr_polisy').value = jsonObj.ubezpieczenie.nr_polisy;
			$('kolo_suma_ubezpieczenia').value = jsonObj.ubezpieczenie.suma_ubezpieczenia;
			$('franszyza_info').innerHTML = 'Franszyza '  + ( jsonObj.ubezpieczenie.franszyza_rodzaj ==1 ? 'Integralna' : 'Redukcyjna' ) + ' ' +jsonObj.ubezpieczenie.franszyza_kwota + ' PLN';
		}else{			
			$('kolo_nr_polisy').value = '';
			$('kolo_suma_ubezpieczenia').value = '';
			$('franszyza_info').innerHTML = '';			
		}
		
		if (jsonObj.szacujacy){
			var len = jsonObj.szacujacy.length;
			obj = $('szacujacy_id[]');
			 if (obj){
				 obj.options.length=0;
			    //obj.options[0] = new Option('', 0, false, false);
			    for (var i = 0; i < len; i++){		    		
			  		obj.options[obj.options.length] = new Option(jsonObj.szacujacy[i].nazwa + ', tel:' + jsonObj.szacujacy[i].tel, jsonObj.szacujacy[i].ID, false, false);		    		
				} 	
			    obj.options[obj.options.length] = new Option( '--  Nowy szacuj±cy -- ', 'new', false, false);
			 }			
		}else{
			obj = $('szacujacy_id');
			 if (obj){
				 obj.options.length=0;
				 obj.options[obj.options.length] = new Option( '--- wybierz kolo ---', '0', false, false);
			 }
			
		}
	return;
}

function getPowiaty(val){
	
	if (val > 0  ){
		ayax_action=1;
		var url = 'ayax/powiaty.php';
		var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
		onComplete: function(jsonObj) {						
			aktualizacjaPowiatow(jsonObj);				
			ayax_action=0;
		}}).get({'wid': val});
	}else{
		aktualizacjaPowiatow(false);
	}	
	
}

function aktualizacjaPowiatow(jsonObj){
	
	if (jsonObj){
		var len = jsonObj.length;
		obj = $('pow_id');
		 if (obj){
			 obj.options.length=0;
		    obj.options[0] = new Option('', 0, false, false);
		    for (var i = 0; i < len; i++){		    		
		  		obj.options[obj.options.length] = new Option(jsonObj[i].nazwa, jsonObj[i].ID, false, false);		    		
			} 			  
		 }
		 
		 obj = $('gmina_id');
		 if (obj){
			 obj.options.length=0;
			 obj.options[obj.options.length] = new Option( '-- wbierz powiat--', '0', false, false);
		 }	
	}else{
		obj = $('pow_id');
		 if (obj){
			 obj.options.length=0;
			 obj.options[obj.options.length] = new Option( '--- wybierz wojewodztwo ---', '0', false, false);
		 }
		
		 obj = $('gmina_id');
		 if (obj){
			 obj.options.length=0;
			 obj.options[obj.options.length] = new Option( '-- wbierz powiat--', '0', false, false);
		 }
		 
	}
}


function getGminy(val){
	
	if (val > 0  ){
		ayax_action=1;
		var url = 'ayax/gminy.php';
		var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
		onComplete: function(jsonObj) {						
			aktualizacjaGmin(jsonObj);				
			ayax_action=0;
		}}).get({'wid': $('woj_id').value,'pid': val});
	}else{
		aktualizacjaGmin(false);
	}	
	
}

function aktualizacjaGmin(jsonObj){
	
	if (jsonObj){
		var len = jsonObj.length;
		obj = $('gmina_id');
		 if (obj){
			 obj.options.length=0;
		    obj.options[0] = new Option('', 0, false, false);
		    for (var i = 0; i < len; i++){		    		
		  		obj.options[obj.options.length] = new Option(jsonObj[i].nazwa, jsonObj[i].ID, false, false);		    		
			} 			  
		 }			
	}else{				
		 obj = $('gmina_id');
		 if (obj){
			 obj.options.length=0;
			 obj.options[obj.options.length] = new Option( '-- wbierz powiat--', '0', false, false);
		 }		 
	}
}

function getSzacujacy(val){
	
	if (val > 0  ){
	/*	ayax_action=1;
		var url = 'ayax/allianz_szacujacy.php';
		var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
		onComplete: function(jsonObj) {						
			aktualizacjaSzacujacy(jsonObj);				
			ayax_action=0;
		}}).get({'id': val});
		*/
		//aktualizacjaSzacujacy(false);
	}else if (val=='new'){
		 $('szacujacy_nazwa').value = '';
		 $('szacujacy_nazwa').readOnly=false;
		 $('szacujacy_tel').value = '';
		 $('szacujacy_tel').readOnly=false;

	}else{
		aktualizacjaSzacujacy(false);
	}		
}

function aktualizacjaSzacujacy(jsonObj){
	
	

	if (jsonObj.ID){
		var len = jsonObj.length;
		
		 if ($('szacujacy_nazwa')){
			 $('szacujacy_nazwa').value = jsonObj.nazwa;
			 $('szacujacy_nazwa').readOnly=false;;
			 $('szacujacy_tel').value = jsonObj.telefon;
			 $('szacujacy_tel').readOnly=false;;
		 }			
	}else{				
		if ($('szacujacy_nazwa')){
			 $('szacujacy_nazwa').value = '';
			 $('szacujacy_nazwa').readOnly=true;
			 $('szacujacy_tel').value = '';
			 $('szacujacy_tel').readOnly=true;
		 } 
	}
}

function sprawdz_zwierzyne(){
	
	$('gatunek_zwierzyny');
	$('gatunek_zwierzyny_inne');
	
}
/*
function NHCgetSumaUbezp(val,obj_id1,obj_id){
	if (val > 0 ){
			ob = document.getElementById(obj_id1);
			
			ayax_action=1;
			var url = 'ayax/cardif_suma_vs_swiadczenie.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				NHCaktualizacjaSumyUbezp(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'sid': val,'wid': ob.value});
	}else{
		NHCaktualizacjaSumyUbezp(obj_id,{'value': 0});
	}
}


function NHCgetSwiadczenia(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/cardif_swiadczenie_vs_typ.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				NHCaktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'tid': val});
	}else{
		NHCaktualizacjaWariantUmowy(obj_id,new Array());
	}
}


function NHCgetMainCases(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/nhc_main_cases.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				NHCaktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'policy_type': val});
	}else{
		NHCaktualizacjaWariantUmowy(obj_id,new Array());
	}
}

function NHCgetSalesObject(val,obj_id){
	if (val > 0 ){
			ayax_action=1;
			var url = 'ayax/nhc_sales_object.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {						
				NHCaktualizacjaWariantUmowy(obj_id,jsonObj);
			 ayax_action=0;
			}}).get({'policy_type': val});
	}else{
		NHCaktualizacjaWariantUmowy(obj_id,new Array());
	}
}


function NHCaktualizacjaWariantUmowy(obj_id,items){	
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



function NHCaktualizacjaSumyUbezp(obj_id,items){	
    
    obj=document.getElementById(obj_id);
    if (obj){      
	 	obj.value = items.suma;		    		
    }
	    
}



function NHCedycja_wykonawcy(id){
	window.open('AS_cases_details_expenses_position_details.php?expense_id=' + id + '&tryb=cardif','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=400,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);	
}

*/