<?php
//ubezpieczon_plec


function module_update(){			
	global  $pageName;
	$result ='';

	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');
	
	
	$check_js = '';
	$message = '';
	

	 if (isset($change['ch_rezerwa_zgloszenie']) && $case_id > 0  ){		
   		$res=check_update($case_id,'settings_ustawienia');
		if ($res[0]){					
			$status = getValue('status')>0 ? getValue('status') : 0 ;
			$status_old = getValue('status_old')>0 ? getValue('status_old') : 0 ;
			$status2 = getValue('status2')>0 ? getValue('status2') : 0 ;
			
			$choroba = getValue('choroba')>0 ? getValue('choroba') : 0 ;
			$okolicznosci = getValue('okolicznosci') > 0 ? getValue('okolicznosci') : 0 ;
			$ryzyko_gl = getValue('ryzyko_gl') > 0 ? getValue('ryzyko_gl') : 0 ;							
			
			
			$pax_pesel = getValue('pax_pesel');							
			$pax_email = getValue('pax_email');							
			
			$ubezpieczon_konto = getValue('ubezpieczon_konto');										
			$ubezpieczon_bank_nazwa = getValue('ubezpieczon_bank_nazwa');										
			$ubezpieczon_pay_type = getValue('ubezpieczon_pay_type') > 0  ? getValue('ubezpieczon_pay_type') : 0 ;							
			//$ubezpieczon_plec = getValue('ubezpieczon_plec');							
			
			$var = " choroba='$choroba',okolicznosci='$okolicznosci',ryzyko_gl='$ryzyko_gl',ubezpieczon_bank_nazwa='$ubezpieczon_bank_nazwa',ubezpieczon_konto='$ubezpieczon_konto',ubezpieczon_pay_type='$ubezpieczon_pay_type'  " ;
			
			if ( $status==1 && $status_old==0){
				$var .= ", status='1',status_date=now(),status_user_id='".$_SESSION['user_id']."'";
			}
		
			if ( $status==0 && $status_old==1){
				$var .= ", status='0',status_date=now(),status_user_id='".$_SESSION['user_id']."'";
			}
			
			if ( $status2==1  ){ // powtÛrna wysy≥ka
				$var .= ", status2='1',status2_date=now(),status2_user_id='".$_SESSION['user_id']."'";
			}
			
			
			$policy = getValue('policy');							
														
			$biurop_id= getValue('biurop_id');

			$var .= " ,biurop_id='$biurop_id' ";					
			$var2 = " policy='$policy' ";			
			
			
			
			$paxname = getValue('paxname');			
			$paxsurname = getValue('paxsurname');						
			$paxsex = getValue('paxsex');						
			$paxDob = getValue('paxDob_y').'-'.getValue('paxDob_m').'-'.getValue('paxDob_d');
						
			$var2 .= " , paxname='$paxname',paxsurname='$paxsurname',paxsex='$paxsex',paxdob='$paxDob',pax_email='$pax_email',pax_pesel='$pax_pesel' ";
			
			
			$paxaddress = getValue('paxaddress');			
			$paxpost = getValue('paxpost_1').'-'.getValue('paxpost_2');			
			$paxcity= getValue('paxcity');
			$paxcountry= getValue('paxcountry');
			$paxphone= getValue('paxphone');
			
			

			

			
			$var3 = " paxaddress='$paxaddress',paxpost='$paxpost',paxcountry='$paxcountry',paxcity='$paxcity',paxphone='$paxphone' ";			
			
			
			

			
			
			$ubezpieczajacy = getValue('ubezpieczajacy');
			$ubezpieczajacy_instytucja = getValue('ubezpieczajacy_instytucja');
			$var .= " ,ubezpieczajacy='$ubezpieczajacy',ubezpieczajacy_instytucja='$ubezpieczajacy_instytucja' ";
			
			$ubezpieczaj_nazwisko= getValue('ubezpieczaj_nazwisko');
			$ubezpieczaj_imie= getValue('ubezpieczaj_imie');
			$ubezpieczaj_plec= getValue('ubezpieczaj_plec');					
			$ubezpieczaj_data_ur = getValue('ubezpieczaj_data_ur_y').'-'.getValue('ubezpieczaj_data_ur_m').'-'.getValue('ubezpieczaj_data_ur_d');	
			
			$ubezpieczaj_pesel= getValue('ubezpieczaj_pesel');
			$ubezpieczaj_ulica= getValue('ubezpieczaj_ulica');
			$ubezpieczaj_kod = getValue('ubezpieczaj_kod_1').'-'.getValue('ubezpieczaj_kod_2');		
			$ubezpieczaj_miasto= getValue('ubezpieczaj_miasto');
			$ubezpieczaj_panstwo= getValue('ubezpieczaj_panstwo');
			$ubezpieczaj_telefon= getValue('ubezpieczaj_telefon');
			$ubezpieczaj_email= getValue('ubezpieczaj_email');
				
			$ubezpieczaj_konto = getValue('ubezpieczaj_konto');										
			$ubezpieczaj_bank_nazwa = getValue('ubezpieczaj_bank_nazwa');										
			$ubezpieczaj_pay_type = getValue('ubezpieczaj_pay_type') > 0  ? getValue('ubezpieczaj_pay_type') : 0 ;	
								
			$var .= " ,ubezpieczaj_nazwisko='$ubezpieczaj_nazwisko',ubezpieczaj_imie='$ubezpieczaj_imie',ubezpieczaj_plec='$ubezpieczaj_plec',ubezpieczaj_data_ur='$ubezpieczaj_data_ur',ubezpieczaj_pesel='$ubezpieczaj_pesel',ubezpieczaj_ulica='$ubezpieczaj_ulica',ubezpieczaj_kod='$ubezpieczaj_kod',ubezpieczaj_miasto='$ubezpieczaj_miasto',ubezpieczaj_panstwo='$ubezpieczaj_panstwo',ubezpieczaj_telefon='$ubezpieczaj_telefon',ubezpieczaj_email='$ubezpieczaj_email',ubezpieczaj_pay_type='$ubezpieczaj_pay_type',ubezpieczaj_bank_nazwa='$ubezpieczaj_bank_nazwa',ubezpieczaj_konto='$ubezpieczaj_konto' ";
			
				
			$upowaz_nazwisko= getValue('upowaz_nazwisko');
			$upowaz_imie= getValue('upowaz_imie');
			$upowaz_plec= getValue('upowaz_plec');					
			$upowaz_data_ur = getValue('upowaz_data_ur_y').'-'.getValue('upowaz_data_ur_m').'-'.getValue('upowaz_data_ur_d');	
			
			$upowaz_pesel= getValue('upowaz_pesel');
			$upowaz_ulica= getValue('upowaz_ulica');
			$upowaz_kod = getValue('upowaz_kod_1').'-'.getValue('upowaz_kod_2');		
			$upowaz_miasto= getValue('upowaz_miasto');
			$upowaz_panstwo= getValue('upowaz_panstwo');
			$upowaz_telefon= getValue('upowaz_telefon');
			$upowaz_email= getValue('upowaz_email');
			
			$upowaz_konto = getValue('upowaz_konto');										
			$upowaz_bank_nazwa = getValue('upowaz_bank_nazwa');										
			$upowaz_pay_type = getValue('upowaz_pay_type') > 0  ? getValue('upowaz_pay_type') : 0 ;	
					
			$var .= " ,upowaz_nazwisko='$upowaz_nazwisko',upowaz_imie='$upowaz_imie',upowaz_plec='$upowaz_plec',upowaz_data_ur='$upowaz_data_ur',upowaz_pesel='$upowaz_pesel',upowaz_ulica='$upowaz_ulica',upowaz_kod='$upowaz_kod',upowaz_miasto='$upowaz_miasto',upowaz_panstwo='$upowaz_panstwo',upowaz_telefon='$upowaz_telefon',upowaz_email='$upowaz_email',upowaz_bank_nazwa='$upowaz_bank_nazwa',upowaz_konto='$upowaz_konto',upowaz_pay_type='$upowaz_pay_type'  ";
			
			
			
			$poszk_nazwisko= getValue('poszk_nazwisko');
			$poszk_imie= getValue('poszk_imie');
			$poszk_plec= getValue('poszk_plec');					
			$poszk_data_ur = getValue('poszk_data_ur_y').'-'.getValue('poszk_data_ur_m').'-'.getValue('poszk_data_ur_d');	
			
			$poszk_pesel= getValue('poszk_pesel');
			$poszk_ulica= getValue('poszk_ulica');
			$poszk_kod = getValue('poszk_kod_1').'-'.getValue('poszk_kod_2');		
			$poszk_miasto= getValue('poszk_miasto');
			$poszk_panstwo= getValue('poszk_panstwo');
			$poszk_telefon= getValue('poszk_telefon');
			$poszk_email= getValue('poszk_email');
					
			$poszk_konto = getValue('poszk_konto');										
			$poszk_bank_nazwa = getValue('poszk_bank_nazwa');										
			$poszk_pay_type = getValue('poszk_pay_type') > 0  ? getValue('poszk_pay_type') : 0 ;							

			
			$var .= " ,poszk_nazwisko='$poszk_nazwisko',poszk_imie='$poszk_imie',poszk_plec='$poszk_plec',poszk_data_ur='$poszk_data_ur',poszk_pesel='$poszk_pesel',poszk_ulica='$poszk_ulica',poszk_kod='$poszk_kod',poszk_miasto='$poszk_miasto',poszk_panstwo='$poszk_panstwo',poszk_telefon='$poszk_telefon',poszk_email='$poszk_email',poszk_bank_nazwa='$poszk_bank_nazwa',poszk_konto='$poszk_konto',poszk_pay_type='$poszk_pay_type'  ";
			
			
			
/////////////////////			
			
			$qt = "SELECt case_id FROM coris_assistance_cases_announce  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);			
			
			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO coris_assistance_cases_announce SET case_id='$case_id', $var ";
								
			}else{
				$query = "UPDATE coris_assistance_cases_announce SET $var  WHERE case_id='$case_id' LIMIT 1";				
			}						
			$query2 = "UPDATE coris_assistance_cases  SET $var2 WHERE case_id='$case_id' LIMIT 1 ";			
			$query3 = "UPDATE coris_assistance_cases_details   SET $var3 WHERE case_id='$case_id' LIMIT 1 ";
			
			
			$mysql_result = mysql_query($query);
			
			
			
			if ($mysql_result){
				//$message .= "Udpate OK, ".$query;
			}else{
				$message .= "<br>Update Error: ".$query."\n<br> ".mysql_error();				
			}		
			$mysql_result2 = mysql_query($query2);
			if ($mysql_result2){
				//$message .= "Udpate2 OK, ".$query2;
			}else{
				$message .= "<br>Update 2 Error: ".$query2."\n<br> ".mysql_error();				
			}		
			$mysql_result3 = mysql_query($query3);
			if ($mysql_result3){
				//$message .= "Udpate3 OK, ";
			}else{
				$message .= "<br>Update 3 Error: ".$query3."\n<br> ".mysql_error();				
			}		
			
		}else{//error update
			echo $res[1];
			
		}		

	}
	
	

	
	
	if (isset($change['ch_rezerwy_rezerwy']) && $case_id > 0  ){		
   		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){			   	
						
			
			$edit_form_action   = getValue('edit_form_action') ;							
									
								
			if ($edit_form_action == 'reservere_add'){
				$id_ryzyko = getValue('id_ryzyko');
				$rezerwa = str_replace(',','.',getValue('rezerwa'));
				$rezerwa_suma= str_replace(',','.',getValue('rezerwa_suma'));
				$rezerwacurrency_id = getValue('rezerwacurrency_id');
						
				$query  = "INSERT INTO coris_assistance_cases_reserve  SET case_id ='$case_id',ID_ryzyko ='$id_ryzyko',rezerwa ='$rezerwa',suma='$rezerwa_suma',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1";		
				$mysql_result = mysql_query($query);
				$poz=0;
				if ($mysql_result){
					//$message .= "Udpate OK";
					$poz = mysql_insert_id();
				}else{
					$message .= "Update Error: ".$query."\n<br> ".mysql_error();				
				}		
				
				if ($poz>0){// insert history					 							
					$query  = "INSERT INTO coris_assistance_cases_reserve_history  SET  ID_reserve  ='$poz',rezerwa ='$rezerwa',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now()";		
					$mysql_result = mysql_query($query);					
				}
				
			}		
			
			
			if ($edit_form_action=='risk_edit_save'){
				
				$id_ryzyko = getValue('rez_id_ryzyko');
				$rez_rezerwa_id = getValue('rez_rezerwa_id');
				
				$rez_suma = getValue('rez_suma');
				$rez_rezerwa = getValue('rez_rezerwa');
				
				
				$rez_suma = str_replace(',','.',trim($rez_suma));					
				$rez_rezerwa = str_replace(',','.',trim($rez_rezerwa));		
					$key=0;
				if ($rez_rezerwa_id>0){
								
					
					$queryu = "UPDATE coris_assistance_cases_reserve SET case_id ='$case_id',ID_ryzyko ='$id_ryzyko',rezerwa ='$rez_rezerwa',suma='$rez_suma', ID_user='".$_SESSION['user_id']."',date=now(),status=1  WHERE ID='$rez_rezerwa_id' LIMIT 1";
					$mysql_result = mysql_query($queryu);			
					if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();								
					$key = $rez_rezerwa_id;
				}else{
					
					$queryu = "INSERT INTO coris_assistance_cases_reserve SET case_id ='$case_id',ID_ryzyko ='$id_ryzyko',rezerwa ='$rez_rezerwa',currency_id='PLN',suma='$rez_suma', ID_user='".$_SESSION['user_id']."',date=now(),status=1 ";
					$mysql_result = mysql_query($queryu);				
					if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();								
					$key = mysql_insert_id();
				}
				
				$query  = "INSERT INTO coris_assistance_cases_reserve_history  SET  ID_reserve  ='$key',rezerwa ='$rez_rezerwa',currency_id ='PLN',suma='$rez_suma',ID_user='".$_SESSION['user_id']."',date=now()";		
				$mysql_result = mysql_query($query);
				if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();							
			}		
		}else{//error update
			echo $res[1];
			
		}		

	}	
/////
		if (isset($change['ch_rezerwy_wyplaty']) && $case_id > 0  ){		
   		$res=check_update($case_id,'rezerwy_wyplaty');
		if ($res[0]){			   	
						
			
			$edit_form_action   = getValue('edit_form_action') ;							
									
								
			if ($edit_form_action == 'pay_add'){
				$id_ryzyko = getValue('id_ryzyko');		
				$id_invoice = getValue('id_invoice');
				$id_opis = getValue('id_opis');
				
				
				if ($id_invoice>0){	
				$qi = "SELECT invoice_out_id ,invoice_out_no ,invoice_out_year, gross_amount,currency_id    FROM coris_finances_invoices_out   WHERE invoice_out_id = '$id_invoice'";					
				$mi = mysql_query($qi);
				$ri = mysql_fetch_array($mi);			
				/// 'A'.$row2['invoice_out_no'].'/'.$row2['invoice_out_year'].' - '.number_format($row2['gross_amount'],2,',',' ').' '.$row2['currency_id'];		
						
					
					$amount = $ri['gross_amount'];
					$currency_id = $ri['currency_id'];
						
					
					$query  = "INSERT INTO coris_assistance_cases_pay  SET case_id ='$case_id',ID_ryzyko ='$id_ryzyko',amount  ='$amount',currency_id ='$currency_id',
					ID_opis_rachunku = '$id_opis',ID_invoice_out ='$id_invoice',
					ID_user='".$_SESSION['user_id']."',date=now()";		
			
					
					
					$mysql_result = mysql_query($query);
					$poz=0;
					if ($mysql_result){
						//$message .= "Udpate OK";
						$poz = mysql_insert_id();
					}else{
						$message .= "Update Error: ".$query."\n<br> ".mysql_error();				
					}		
					
				
				}
			}							
		}else{//error update
			echo $res[1];
			
		}		

	}	
////
	
	echo $message;	
}


function module_main(){
	global $case_id, $row_case;
	;
	$result = '';
	
		$query = "SELECT ac.number, ac.year, ac.client_id, ac.event, ac.country_id, ac.type_id, ac.genre_id, ac.paxname, ac.paxsurname,ac.paxsex, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.costless,ac.only_info, ac.costless, ac.unhandled, ac.archive, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention, ac.attention2, acr.reclamation_text, ac.attention, ac.attention2,ac.holowanie,ac.wynajem_samochodu		
		FROM coris_assistance_cases ac LEFT JOIN coris_assistance_cases_reclamations acr ON ac.case_id = acr.case_id WHERE ac.case_id = '".$case_id."'";			
		$mysql_result = mysql_query($query);
		$row_case_settings = mysql_fetch_array($mysql_result);			

		
		$query2 = "SELECT * FROM coris_assistance_cases_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		
if ($row_case_settings['client_id'] == 7592){
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  zgloszenie($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
	

	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  rezerwy($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  dokumenty($row_case_settings);	
	$result .=  '</div>';			
$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  wyplaty($row_case_settings);	
	$result .=  '</div>';			
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 840px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw SIGNAL IDUNA</b></div>
	</div>
	';
	
}
	
	return $result;	
}


function dokumenty($row){		  
       $result='';	
	global $global_link,$change,$case_id;
	$result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Dokumenty</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['settings_status2'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierdº" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
	<!--			<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;">	
	-->
	</div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      
if (isset($change['settings_status2'])){
		$result .= '<script language="JavaScript1.2">
			<!--
			function checkboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

			function zaznacz_uwaga2(s) {
				
				at= document.getElementById(\'attention\');
				at2=  document.getElementById(\'attention2\');
				
				if (s==\'attention\') {
					at.checked = true;
					at2.checked = false;										
				} else {
					at2.checked = true;
					at.checked = false;
				}
			}

			function zaznacz_uwaga(s) {
				at= document.getElementById(\'attention\');
				at2=  document.getElementById(\'attention2\');
				
				if (s==\'attention\') {					
					if (!at.checked)
							at.checked = false;
					else		
						at.checked = true;
					at2.checked = false;										
				} else {
					if (!at2.checked)
							at2.checked = false;
					else		
						at2.checked = true;
					
					at.checked = false;
				}
			}
			//-->
			</script>
			';
	
	}else{
		
		
		$result .=  '<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#BBBBBB">
				<td width="4%" align="center"><b>Typ</b></td>	
				<td width="4%" align="center"><b>&nbsp;</b></td>
				<td width="37%" align="center"><b>Odbiorca</b></td>	
				<td width="40%" align="center"><b>Temat</b></td>		
				<td width="15%" align="center"><b>Data</b></td>		
		</tr>		
				';
		$q = "SELECT  ID_interactions,ID_dokument,coris_signal_dokumenty.nazwa   FROM coris_signal_dokumenty_interactions,coris_signal_dokumenty WHERE coris_signal_dokumenty_interactions.ID_case='$case_id' AND coris_signal_dokumenty.ID =  coris_signal_dokumenty_interactions.ID_dokument   ORDER BY coris_signal_dokumenty.nazwa  ";
		$mr = mysql_query($q);
		$dok_type = '';
		while ($r = mysql_fetch_array($mr)){
		
			$query = "SELECT coris_assistance_cases_interactions.* FROM coris_assistance_cases_interactions WHERE interaction_id  = '".$r['ID_interactions']."' ";
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);	
			
			$st1='';
    		$st2='';
    		$msg_new='';
	    	if ($row['new']==1){
	    		$msg_new=AS_CASD_NOWDOK.'   ';
	    		$st1='<b>';
	    		$st2='</b>';
    		}	
			
    		if ($dok_type != $r['ID_dokument']){
    			$result .= '<tr><td colspan="5"><b>'.$r['nazwa'].'</b></td></tr>';
    			$dok_type=$r['ID_dokument'];
    		}
    		    		
$result .= '<tr ';						
				  if (($row['type_id'] == 4) || ($row['type_id'] == 3)) { 
				  		$result .= "bgcolor=\"lightyellow\" "; 
				  }else {   	
				  		$result .= "bgcolor=\"#e9e9e9\" onmouseover=\"this.bgColor='#ced9e2';\" onmouseout=\"this.bgColor='#e9e9e9'\" " ; 
				  } 
				  
				  	  $result .= 'style="border-top: #ffffe0 1px solid; border-bottom: #ffffe0 1px solid; cursor: hand" onclick="javascript:';						
				        switch($row['documenttype_id']) {
				            case 2: //email
				                $result .= "doc1=window.open('assistcases-email.htm','doc1','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=520') ; if (false == doc1.closed) doc1.focus();";
				                break;				           
				            case 7: { //fax // email
				            	if ($row['type_id']==1){ //fax
						            	if ($row['direction']==1)
						                	$result .= "window.open('FK_fax_in_preview2.php?id=".$row['interaction_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=530,height=710');";
						                else 
						                	$result .= "window.open('FK_fax_out_preview2.php?id=".$row['ext_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');";	
				            	}else if ($row['type_id']==2){ //email
						            	if ($row['direction']==1)
						                	$result .= "window.open('FK_email_in_view2.php?id=".$row['interaction_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=600,height=710');";
						                else 
						                	$result .= "window.open('FK_email_out_view.php?id=".$row['ext_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710');";	
				            	
				            	}
				                break;
				            }
				                
				        }        
        $result .= '" height="24">
							<td	width="20" align="center">';
    		

         switch ($row['type_id']) {
            case 1: //fax
                $result .= "<font size=\"+1\" face=\"webdings\" color=\"green\">ù</font>";
                break;
            case 2: //email
                $result .= "<font size=\"3\" color=\"red\" face=\"wingdings\">*</font>";
                break;           
        }
        
        $result .= '  </td>							
							<td	width="20" align="center">';
        
         switch ($row['direction']) {
            case 1: // from us
                $result .= "<font size=\"0\" face=\"wingdings\" color=\"green\">Á</font>";
                break;
            case 2: // to us
                $result .= "<font size=\"0\" face=\"wingdings\" color=\"red\">Ë</font>";
                break;
        }
        
        $result .= '</td>
							<td	width="100"	align="center" title="'. $row['interaction_name'].' '.$row['interaction_contact'] .'"><font color="blue">';
							if ($row['type_id']==2) { //email
									$result .= $st1.substr($row['interaction_contact'],0,30).$st2;
							}else{
								$result .= ($row['interaction_contact'] != "") ? $st1.$row['interaction_contact'] . "/" : "$st1" . StrTrim($row['interaction_name'], 30).$st2; 								
							}
							
						$result .= '</font></td>
							<td	width="135" title="'. $row['subject'].' '.$row['note'] .'">'. $st1. StrTrim($row['subject'], 22).$st2 .'</td>
							<td	width="110"	align="right">'. $st1 ;	

						if (substr($row['date'],0,10) == date("Y-m-d")) {
					            $result .= "<font color=\"blue\">".AS_CASD_DZIS." ". substr($row['date'],11, 5) . "</font>";
					        } else if (substr($row['date'],0,10) == date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")))) {
					            $result .= "<font color=\"darkblue\">".AS_CASD_WCZ." ". substr($row['date'],11, 5) . "</font>";
					        } else {
					            $result .= substr($row['date'],0, 16);
					        }

					$result .= $st2.'</td>
						</tr>';				
		}				    				
		$result .= '</table><br>';
	}
	
	$result .= '</form>';
	return $result;
	
}	

function rezerwy($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	$result .= '<a name="rezerwy_rezerwy"></a>
	<form method="POST" name="form_rezerwy" id="form_rezerwy" action="#rezerwy_rezerwy" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Rezerwy</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwy_rezerwy'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name="change[ch_rezerwy_rezerwy]" id="change[ch_rezerwy_rezerwy]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">
				<input type="image" src="img/act.gif" title="Zatwierdº" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[rezerwy_rezerwy] value=1>
				<input type="hidden" name="edit_form" value="1">				
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      
if (isset($change['rezerwy_rezerwy'])){
		$result .= '
		<script>
		function edycja_ryzyka(id,risk_id){
			//if (risk_id>0){
					document.getElementById(\'edit_form_action\').value=\'risk_edit\';	
					document.getElementById(\'edit_form_action_param\').value=id+\',\'+risk_id;	
					
					document.getElementById(\'change[ch_rezerwy_rezerwy]\').name=\'change[rezerwy_rezerwy]\';	
					
					document.getElementById(\'form_rezerwy\').submit();						
			//	}
		}
		
		function zapisz_rezerwe(){
				document.getElementById(\'edit_form_action\').value=\'risk_edit_save\';	
		}
		
		
		function anuluj_rezerwe(){
			return true;
		}
		</script>
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr>				
				<td width="30%" align="center"><b>Ryzyko cz±stkowe</b></td>	
				<td width="17%" align="center"><b>Suma ubezp.</b></td>
				<td width="17%" align="center"><b>Rezerwa<br>wykonawcy</b></td>
				<td width="17%" align="center"><b>Rezerwa<br>roszczenia</b></td>
				<td width="15%" align="center"><b>Zmiana</b></td>		
		 </tr >';
			 	$query = "SELECT coris_assistance_cases_reserve.*,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_reserve.ID_ryzyko ) As ryzyko, 
			(SELECT sum(coris_assistance_cases_claims_details.`reserve`) As sum_reserve FROM coris_assistance_cases_claims,coris_assistance_cases_claims_details  WHERE 
					coris_assistance_cases_claims.ID_case='$case_id' AND coris_assistance_cases_claims.ID=  coris_assistance_cases_claims_details.ID_claims AND coris_assistance_cases_claims_details.ID_risk=coris_assistance_cases_reserve.ID_ryzyko ) As sum_reserve_claims, 
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=coris_assistance_cases_reserve.ID_user ) As user
			FROM coris_assistance_cases_reserve WHERE case_id='$case_id' 			 						
			ORDER BY ID";			 	
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				$lista[] = $row_r['ID_ryzyko'];
			  $result .= '<tr>
			  	
				<td ><b>'. ($row_r['ryzyko']) .'</b>&nbsp;</td>	
								<td align="right"><b>'. print_currency($row_r['suma'],2) .' PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'. print_currency($row_r['sum_reserve_claims'],2) .' PLN</b></td>
				<td align="center"><a href="javascript:edycja_ryzyka('.$row_r['ID'].','.$row_r['ID_ryzyko'].');">edycja</a></td>				
			   </tr >';
			   
			}
			
	 		$query = "SELECT coris_assistance_cases_claims_details.ID_risk ,sum(coris_assistance_cases_claims_details.`reserve`) As sum_reserve,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_claims_details.ID_risk  ) As ryzyko 			
			FROM coris_assistance_cases_claims_details,coris_assistance_cases_claims WHERE coris_assistance_cases_claims.ID_case='$case_id' AND coris_assistance_cases_claims.ID=  coris_assistance_cases_claims_details.ID_claims  			 						
			 ". ( count($lista)>0 ? "AND coris_assistance_cases_claims_details.ID_risk   NOT IN (".implode(',',$lista).")" : '')." 
			GROUP BY coris_assistance_cases_claims_details.ID_risk";	
			$mysql_result = mysql_query($query);
			if (!$mysql_result) {echo '<br>QE '.$query.'<br><br>'.mysql_error();}
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				$lista[] = $row_r['ID'];
			  $result .= '<tr>
			  	
				<td ><b>'. ($row_r['ryzyko']) .'</b></td>	
								<td align="right"><b>0,00  PLN</b></td>
				<td align="right"><b>0,00  PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['sum_reserve'],2) .' PLN</b></td>
				<td align="center"><a href="javascript:edycja_ryzyka(0,'.$row_r['ID_risk'].');">edycja</a></td>	
			   </tr >';
			   
			}
			
			//	<td align="center"><input type="text" name="rezerwa_zmiana['.$row_r['ID'].']" id="rezerwa_zmiana['.$row_r['ID'].']" value=""  style="text-align: right;" size="15" maxlength="20"></td>						
		$result .= '</table><br>';		
		
		$edit_form_action = getValue('edit_form_action');
		
		if ($edit_form_action=='risk_edit'){
				$edit_form_action_param = getValue('edit_form_action_param');
				$tmp = explode(',',$edit_form_action_param);
				$reserve_id = intval($tmp[0]);
				$risk_id = intval($tmp[1]);
				
				//').value=id+\',\'+risk_id
				$reserve = 0.00;
				$reserve_claims = 0.00;
				$suma= 0.00;
				$ryzyko = '';				
				if ($reserve_id>0){
					$qt = "SELECT coris_assistance_cases_reserve.*,
					(SELECT sum(coris_assistance_cases_claims_details.`reserve`)  FROM coris_assistance_cases_claims,coris_assistance_cases_claims_details  WHERE 
					coris_assistance_cases_claims.ID_case='$case_id' AND coris_assistance_cases_claims.ID=  coris_assistance_cases_claims_details.ID_claims AND coris_assistance_cases_claims_details.ID_risk=coris_assistance_cases_reserve.ID_ryzyko ) As sum_reserve_claims,										
					
					(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_reserve.ID_ryzyko  ) As ryzyko
					
					FROM coris_assistance_cases_reserve WHERE ID='$reserve_id' LIMIT 1";
					$mt = mysql_query($qt);
					if (!$mt) {echo "Error q: ".$qt.'<br><br>'.mysql_error();}
				//	echo $qt;
					$rt = mysql_fetch_array($mt);			
					$reserve	= $rt['rezerwa'];
					$reserve_claims	= $rt['sum_reserve_claims'];					
					$suma	= $rt['suma'];				
					$ryzyko = $rt['ryzyko'];					
				}else{
					$reserve_id=0;
					$qt = "SELECT nazwa,(SELECT sum(coris_assistance_cases_claims_details.`reserve`) As sum_reserve FROM coris_assistance_cases_claims,coris_assistance_cases_claims_details  WHERE 
					coris_assistance_cases_claims.ID_case='$case_id' AND coris_assistance_cases_claims.ID=  coris_assistance_cases_claims_details.ID_claims AND coris_assistance_cases_claims_details.ID_risk='$risk_id' ) As sum_reserve_claims
					FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID='$risk_id'  ";		
					$mt = mysql_query($qt);
					if (!$mt) {echo "Error q: ".$qt.'<br><br>'.mysql_error();}
					$rt = mysql_fetch_array($mt);	
					$reserve_claims	= $rt['sum_reserve_claims'];	
					$ryzyko = $rt['nazwa'];							
				}
				
				$result .= '<input type="hidden" name="rez_rezerwa_id" id="rez_rezerwa_id" value="'.$reserve_id.'">
							<input type="hidden" name="rez_id_ryzyko" id="rez_id_ryzyko" value="'.$risk_id.'">
							<table cellpadding="4" cellspacing="0" border="1" align="center" width="95%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Edycja rezerwy:</b><small></td></tr>						
		  				<tr>				
						<td width="30%" align="center"><b>Ryzyko cz±stkowe</b></td>	
						<td width="17%" align="center"><b>Suma ubezp.</b></td>
						<td width="17%" align="center"><b>Rezerwa<br>wykonawcy</b></td>
						<td width="17%" align="center"><b>Rezerwa<br>roszczenia</b></td>
						</tr>
						<tr>
							<td ><b>'. $ryzyko .'</b></td>	
							<td align="right"><input type="text" name="rez_suma" id="rez_suma" value="'.print_currency($suma).'"  style="text-align: right;" size="15" maxlength="20"> PLN</td>
							<td align="right"><input type="text" name="rez_rezerwa" id="rez_rezerwa" value="'.print_currency($reserve).'"  style="text-align: right;" size="15" maxlength="20"> PLN</b></td>
							<td align="right"><b>'. print_currency($reserve_claims) .' PLN</b></td>
						</tr>
						<tr><td colspan="4" align="right">
								<input type="submit" name="reserv_add" onClick="return zapisz_rezerwe();" value="Zapisz">	
								&nbsp;&nbsp;&nbsp;<input type="submit" name="reserv_add" onClick="return anuluj_rezerwe();" value="Anuluj">	
						</td>
						</table>
						';
						
						
						$result .= '
					&nbsp;&nbsp;	
					
						</td></tr>';
				$result .= '</table><br>
				<script>
				function dodaj_rezerwe(){
						
						if (document.getElementById(\'id_ryzyko\').value > 0){
							if (document.getElementById(\'rezerwa\').value.replace(\',\',\'.\') > 0){
												
									document.getElementById(\'edit_form_action\').value=\'reservere_add\';	
									return true;
							}else{	alert(document.getElementById(\'rezerwa\').value);
									alert(\'ProszÍ podaÊ kwotÍ rezerwy.\');
									document.getElementById(\'rezerwa\').focus();
									return false;
							}					
						}else{
							alert(\'ProszÍ wybraÊ ryzyko.\');
							document.getElementById(\'id_ryzyko\').focus();
							return false;
						}		
				}
				</script>';
		
			
		}else{
		
				/*$result .= '<table cellpadding="4" cellspacing="0" border="0" align="center" width="95%">';
						$result .= '<tr bgcolor="#AAAAAA"><td><b>Nowa rezerwa:</b>&nbsp;&nbsp;<small><b>Ryzyko cz±stkowe: </b></small> '.wysw_ryzyko_czastkowe('id_ryzyko',0,0,$case_id) .'&nbsp;&nbsp;&nbsp;<small><b>Rezerwa:</b></samll>&nbsp;&nbsp; 
						<input type="text" name="rezerwa" id="rezerwa" value=""  style="text-align: right;" size="15" maxlength="20">';
						
						$result .= wysw_currency_pln('rezerwacurrency_id','PLN');	
						$result .= '
					&nbsp;&nbsp;	
					<input type="submit" name="reserv_add" onClick="return dodaj_rezerwe();" value="Dodaj">	
						</td></tr></table><br>';
						
						*/
				
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Nowa rezerwa:</b><small></td></tr>						
		  				<tr>				
						<td width="45%" align="center"><b>Ryzyko cz±stkowe</b></td>	
						<td width="25%" align="center"><b>Suma ubezp.</b></td>
						<td width="25%" align="center"><b>Rezerwa<br>wykonawcy</b></td>
						<td width="5%" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="right"> '.wysw_ryzyko_czastkowe3('id_ryzyko',0,0,$case_id,$row_case_ann['ryzyko_gl']) .'</td>	
							<td align="right"><input type="text" name="rezerwa_suma" id="rezerwa_suma" value="'.print_currency(0).'"  style="text-align: right;" size="15" maxlength="20"> '.wysw_currency_pln('rezerwacurrency_id','PLN').'</td>
							<td align="right"><input type="text" name="rezerwa" id="rezerwa" value="'.print_currency(0).'"  style="text-align: right;" size="15" maxlength="20"> '.wysw_currency_pln('rezerwacurrency_id','PLN').'</b></td>
							<td align="right"><b>&nbsp;</b></td>
						</tr>
						<tr><td colspan="4" align="right">
								<input type="submit" name="reserv_add" onClick="return dodaj_rezerwe();" value="Dodaj">	
								&nbsp;&nbsp;&nbsp;<input type="submit" name="reserv_add" onClick="return anuluj_rezerwe();" value="Anuluj">	
						</td>
						</table>';
				$result .= '
				<script>
				function dodaj_rezerwe(){
						
						if (document.getElementById(\'id_ryzyko\').value > 0){
							if (document.getElementById(\'rezerwa\').value.replace(\',\',\'.\') > 0){
												
									document.getElementById(\'edit_form_action\').value=\'reservere_add\';	
									return true;
							}else{
									alert(\'ProszÍ podaÊ kwotÍ rezerwy.\');
									document.getElementById(\'rezerwa\').focus();
									return false;
							}					
						}else{
							alert(\'ProszÍ wybraÊ ryzyko.\');
							document.getElementById(\'id_ryzyko\').focus();
							return false;
						}		
				}
				</script>';
		}
	}else{
			$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="30%" align="center"><b>Ryzyko cz±stkowe</b></td>	
				<td width="17%" align="center"><b>Suma ubezp.</b></td>
				<td width="17%" align="center"><b>Rezerwa<br>wykonawcy</b></td>
				<td width="17%" align="center"><b>Rezerwa<br>roszczenia</b></td>
				<td width="17%" align="center"><b>Data</b></td>	
				<td width="17%" align="center"><b>Uøytkownik</b></td>		
				
			   </tr >';
			
		/*	$query = "SELECt coris_assistance_cases_reserve.*,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_reserve.ID_ryzyko ) As ryzyko, 
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_reserve.ID_user ) As user
			FROM coris_assistance_cases_reserve WHERE case_id='$case_id' ORDER BY ID";
			$mysql_result = mysql_query($query);
			while ($row_r=mysql_fetch_array($mysql_result)){
			  $result .= '<tr>
				<td ><b>'. ($row_r['ryzyko']) .'</b></td>	
				<td align="right"><b>'. print_currency($row_r['suma'],2) .' PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa_claims'],2) .' PLN</b></td>
				<td align="right">'. ($row_r['date']).'</td>	
				<td align="center">'. ($row_r['user']) .'</td>						
			   </tr >';
			   
			}
			
			
			
			*/
		 	$query = "SELECT coris_assistance_cases_reserve.*,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_reserve.ID_ryzyko ) As ryzyko, 
			(SELECT sum(coris_assistance_cases_claims_details.`reserve`) As sum_reserve FROM coris_assistance_cases_claims,coris_assistance_cases_claims_details  WHERE 
					coris_assistance_cases_claims.ID_case='$case_id' AND coris_assistance_cases_claims.ID=  coris_assistance_cases_claims_details.ID_claims AND coris_assistance_cases_claims_details.ID_risk=coris_assistance_cases_reserve.ID_ryzyko ) As sum_reserve_claims, 
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=coris_assistance_cases_reserve.ID_user ) As user
			FROM coris_assistance_cases_reserve WHERE case_id='$case_id' 			 						
			ORDER BY ID";			 	
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				$lista[] = $row_r['ID_ryzyko'];
			  $result .= '<tr>
			  	
				<td ><b>'. ($row_r['ryzyko']) .'</b>&nbsp;</td>	
								<td align="right"><b>'. print_currency($row_r['suma'],2) .' PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'. print_currency($row_r['sum_reserve_claims'],2) .' PLN</b></td>
					<td align="right">'. ($row_r['date']).'</td>	
				<td align="center">'. ($row_r['user']) .'</td>					
			   </tr >';
			   
			}
			
	 		$query = "SELECT coris_assistance_cases_claims_details.ID_risk ,sum(coris_assistance_cases_claims_details.`reserve`) As sum_reserve,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_claims_details.ID_risk  ) As ryzyko 			
			FROM coris_assistance_cases_claims_details,coris_assistance_cases_claims WHERE coris_assistance_cases_claims.ID_case='$case_id' AND coris_assistance_cases_claims.ID=  coris_assistance_cases_claims_details.ID_claims  			 						
			". ( count($lista)>0 ? "AND coris_assistance_cases_claims_details.ID_risk   NOT IN (".implode(',',$lista).")" : ''  ) ."
			GROUP BY coris_assistance_cases_claims_details.ID_risk";	
			$mysql_result = mysql_query($query);
			if (!$mysql_result) {echo '<br>QE '.$query.'<br><br>'.mysql_error();}
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				$lista[] = $row_r['ID'];
			  $result .= '<tr>
			  	
				<td ><b>'. ($row_r['ryzyko']) .'</b></td>	
								<td align="right"><b>0,00  PLN</b></td>
				<td align="right"><b>0,00  PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['sum_reserve'],2) .' PLN</b></td>
					<td align="right">'. ($row_r['date']).'&nbsp;</td>	
				<td align="center">'. ($row_r['user']) .'&nbsp;</td>			
			   </tr >';
			   
			}
		$result .= '</table><br>';		
	}
	
	$result .= '</form>';
	return $result;
	
}	

function wyplaty($row){		  
       $result='';	
		global $global_link,$change,$case_id;
	$result .= '<a name="form_rezerwa"><form method="POST" style="padding:0px;margin:0px" action="#form_rezerwa">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Wyp≥aty</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwy_wyplaty'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_rezerwy_wyplaty] value=1>
				<input type="hidden" name="edit_form" value="1">
					<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="image" src="img/act.gif" title="Zatwierdº" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[rezerwy_wyplaty] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      

				
if (isset($change['rezerwy_wyplaty'])){
	if ($_SESSION['new_user']==0){
    		$result .= '<div align="right" style="margin-right: 40px;margin-bottom:10px"><input type="button" value="'.  AS_CASD_MSG_BUTFIN .' " style="font-weight: bold; width: 135px" title="'.  AS_CASD_MSG_PRZEDOFIN .' " onclick="MM_openBrWindow(\'../finances/FK_cases_details.php?case_id='. $case_id .' \',\'\',\'scrollbars=yes,resizable=yes,top=50,left=170,width=650,height=570\')"></div>
    		 ';
	}  
		$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr>
				<td width="25%" align="center"><b>Ryzyko cz±stkowe</b></td>	
				<td width="10%" align="center"><b>Nr faktury</b></td>
				<td width="15%" align="center"><b>Data</b></td>	
				<td width="15%" align="center"><b>Kwota</b></td>		
				<td width="25%" align="center"><b>Opis</b></td>						
				<td width="60" align="center"> <b>Operat <br>szkod.</b></td>									
				<td width="60" align="center"> <b>Zlec. wyp≥.</b></td>			
			   </tr >';
			 	$query = "SELECt coris_assistance_cases_pay.*,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_pay.ID_ryzyko ) As ryzyko, 
			(SELECT nazwa FROM coris_signal_ryzyko_operat    WHERE coris_signal_ryzyko_operat.ID=coris_assistance_cases_pay.ID_opis_rachunku  ) As opis, 
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_pay.ID_user ) As user
			FROM coris_assistance_cases_pay  WHERE case_id='$case_id' ORDER BY ID";
			$mysql_result = mysql_query($query);
			while ($row_r=mysql_fetch_array($mysql_result)){
				
				$qi = "SELECT invoice_out_id ,invoice_out_no ,invoice_out_year, gross_amount,currency_id,invoice_out_date     FROM coris_finances_invoices_out   WHERE invoice_out_id = '".$row_r['ID_invoice_out']."'";					
				$mi = mysql_query($qi);
				$ri = mysql_fetch_array($mi);			
				/// 'A'.$row2['invoice_out_no'].'/'.$row2['invoice_out_year'].' - '.number_format($row2['gross_amount'],2,',',' ').' '.$row2['currency_id'];		
						
			
			  $result .= '<tr>
				<td >'. ($row_r['ryzyko']) .'</td>	
				<td align="right">A'.$ri['invoice_out_no'].'/'.$ri['invoice_out_year'].'</td>
				<td align="right">'. ($ri['invoice_out_date']).'</td>	
				<td align="right">'. number_format(($row_r['amount']),2,',',' ') .' '.$row_r['currency_id'].'</td>						
				<td align="center">'.$row_r['opis'].'</td>		
				<td align="center"> <a href="AS_case_pay_print.php?id='.$row_r['ID'].'&tryb=operat" target="_blank" title="Operat Szkodowy"><img src="img/print.gif" border=0></a> </td>			
				<td align="center"> <a href="AS_case_pay_print.php?id='.$row_r['ID'].'&tryb=zlecenie" target="_blank" title="Zlecenie wyp≥aty"><img src="img/print.gif" border=0></a> </td>			
							
			   </tr >';
			   
			}
		$result .= '</table><br>';		
		$result .= '<script>
		function getOperat(val,obj){

if (val > 0 ){
		ayax_action=1;
	
		var url = \'ayax/operat_vs_ryzyko_czastkowe.php\';
		var jsonRequest = new Request.JSON({url: url, encoding: \'UTF-8\' ,
		onComplete: function(jsonObj) {						
			aktualizacjaOperatu(obj,jsonObj);
		 ayax_action=0;
		}}).get({\'rid\': val});
}

}

function aktualizacjaOperatu(obj,items){	
    var len = items.length;
    
    
    
    if (obj){    
    		if (obj.name == \'add_zgloszenie_id_ryzyko[]\'){		    		    		    
    			lista = document.getElementsByName(\'add_zgloszenie_id_ryzyko[]\');
    			for (i=0;i<lista.length;i++){
    			
					if(lista[i] === obj){					
						oo = document.getElementsByName(\'add_zgloszenie_id_operat[]\');			
						obj = oo[i];
						//continue ;
					}
					
				}
				
			}						
			
			
			
    		obj.options.length=0;
		    for (var i = 0; i < len; i++){		    		
		    		obj.options[obj.options.length] = new Option(items[i].nazwa, items[i].ID, false, false);		    		
		    }
    }
}
</script>
		</script>
		';
		
		$result .= '<table cellpadding="4" cellspacing="0" border="0" align="center" width="95%">';
				$result .= '<tr bgcolor="#AAAAAA"><td rowspan="2"><b>Nowa wyp≥ata:</b></td><td><small><b>Ryzyko cz±stkowe: </b></small> '.wysw_ryzyko_czastkowe_wypl('id_ryzyko',0,0,$case_id, ' onChange="getOperat(this.value,document.getElementById(\'id_opis\'))"') .'&nbsp;&nbsp;&nbsp;
				<small><b>Faktura:</b></samll>&nbsp;&nbsp; '.wysw_lista_fakt('id_invoice',0,0,$case_id).'
				
				&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="pay_add" onClick="return dodaj_wyplate();" value="Dodaj">	
				</td></tr>
				<tr bgcolor="#AAAAAA"><td>
				';
				
				
				$result .= '			
			<small><b>Opis rachunku :</b></small> '.wysw_opis('id_opis',0,0,$case_id).'
				</td></tr>';
		$result .= '</table><br>
		<script>
		function dodaj_wyplate(){
				
				if (  !(document.getElementById(\'id_ryzyko\').value > 0)){														
					alert(\'ProszÍ wybraÊ ryzyko.\');
					document.getElementById(\'id_ryzyko\').focus();
					return false;
				}		
				if (  !(document.getElementById(\'id_invoice\').value > 0)){														
					alert(\'ProszÍ wybraÊ fakturÍ.\');
					document.getElementById(\'id_invoice\').focus();
					return false;
				}	
				
				if (  !(document.getElementById(\'id_opis\').value > 0)){														
					alert(\'ProszÍ wybraÊ opis.\');
					document.getElementById(\'id_opis\').focus();
					return false;
				}		
				document.getElementById(\'edit_form_action\').value=\'pay_add\';	
				return true;
		}
		
		
		</script>
		';

	}else{
			$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr>
				<td width="35%" align="center"><b>Ryzyko cz±stkowe</b></td>	
				<td width="15%" align="center"><b>Nr faktury</b></td>
				<td width="15%" align="center"><b>Data</b></td>	
				<td width="15%" align="center"><b>Kwota</b></td>		
				<td width="30%" align="center"><b>Opis</b></td>						
			   </tr >';
			 	$query = "SELECt coris_assistance_cases_pay.*,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_pay.ID_ryzyko ) As ryzyko, 
			(SELECT nazwa FROM coris_signal_ryzyko_operat    WHERE coris_signal_ryzyko_operat.ID=coris_assistance_cases_pay.ID_opis_rachunku  ) As opis, 
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_pay.ID_user ) As user
			FROM coris_assistance_cases_pay  WHERE case_id='$case_id' ORDER BY ID";
			$mysql_result = mysql_query($query);
			while ($row_r=mysql_fetch_array($mysql_result)){
				
				$qi = "SELECT invoice_out_id ,invoice_out_no ,invoice_out_year, gross_amount,currency_id,invoice_out_date FROM coris_finances_invoices_out   WHERE invoice_out_id = '".$row_r['ID_invoice_out']."'";					
				$mi = mysql_query($qi);
				$ri = mysql_fetch_array($mi);			
				/// 'A'.$row2['invoice_out_no'].'/'.$row2['invoice_out_year'].' - '.number_format($row2['gross_amount'],2,',',' ').' '.$row2['currency_id'];		
						
			
			  $result .= '<tr>
				<td >'. ($row_r['ryzyko']) .'</td>	
				<td align="right">A'.$ri['invoice_out_no'].'/'.$ri['invoice_out_year'].'</td>
				<td align="right">'. ($ri['invoice_out_date']).'</td>	
				<td align="right">'. number_format(($row_r['amount']),2,',',' ') .' '.$row_r['currency_id'].'</td>						
				<td align="center">'.$row_r['opis'].'</td>						
			   </tr >';
			   
			}
		$result .= '</table><br>';	
		
	 	$query = "SELECt coris_assistance_cases_coris_pay.*,
			(SELECT nazwa FROM coris_signal_ryzyka_czastkowe  WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_coris_pay.ID_ryzyko ) As ryzyko,
			(SELECT nazwa FROM coris_signal_ryzyko_operat    WHERE coris_signal_ryzyko_operat.ID=coris_assistance_cases_coris_pay.ID_opis_rachunku  ) As opis			
			FROM coris_assistance_cases_coris_pay  WHERE case_id='$case_id' ORDER BY ID";
			$mysql_result = mysql_query($query);
			if (mysql_num_rows($mysql_result)>0){
						$result .= '
					<small><b>Wyp≥aty Coris</b></small><br><table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
					  <tr>
							<td width="25%" align="center"><b>Ryzyko cz±stkowe</b></td>								
							<td width="15%" align="center"><b>Data</b></td>	
							<td width="15%" align="center"><b>Kwota</b></td>		
							<td width="20%" align="center"><b>Opis</b></td>						
							<td width="10%" align="center"><b>Status exportu</b></td>						
							<td width="30%" align="center"><b>Data exportu</b></td>						
						   </tr >';
						while ($row_r=mysql_fetch_array($mysql_result)){
																					
						  $result .= '<tr>
							<td >'. ($row_r['ryzyko']) .'</td>								
							<td align="right">'. ($row_r['date']).'</td>								
							<td align="right">'. number_format(($row_r['amount']),2,',',' ') .' '.$row_r['currency_id'].'</td>						
							<td align="center">'.$row_r['opis'].'</td>						
							<td align="center">'.($row_r['status']==0 ? 'TAK' : 'NIE').'</td>						
							<td align="center">'.$row_r['signal_export_date'].'&nbsp;</td>						
						   </tr >';
						   
						}
					$result .= '</table><br>';	
			}
	}
	
	$result .= '</form>';
	return $result;
	
}	

	
function zgloszenie($row,$row2,$row3){
	

	   $paxDob = array("","","");
       if ($row3['paxdob'] != "0000-00-00")
             $paxDob = split("-", $row3['paxdob']);           
     $paxpost = array("", "");
    if ($row3['paxpost'])
        $paxpost = split("-", $row3['paxpost']);
        

	   $ubezpieczaj_data_ur = array("","","");
       if ($row2['ubezpieczaj_data_ur'] != "0000-00-00")
             $ubezpieczaj_data_ur = split("-", $row2['ubezpieczaj_data_ur']);           
     $ubezpieczaj_kod = array("", "");
    if ($row2['ubezpieczaj_kod'])
        $ubezpieczaj_kod = split("-", $row2['ubezpieczaj_kod']);

        	   $poszk_data_ur = array("","","");
       if ($row2['poszk_data_ur'] != "0000-00-00")
             $poszk_data_ur = split("-", $row2['poszk_data_ur']);           
     
             
     $poszk_kod = array("", "");     
    if ($row2['poszk_kod'])
        $poszk_kod = split("-", $row2['poszk_kod']);

      $upowaz_data_ur = array("","","");
       if ($row2['upowaz_data_ur'] != "0000-00-00")
             $upowaz_data_ur = split("-", $row2['upowaz_data_ur']);    
                                
     $upowaz_kod = array("", "");
    if ($row2['upowaz_kod'])
        $upowaz_kod = split("-", $row2['upowaz_kod']);

        
       $result='';	
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_zgloszenie" id="form_zgloszenie">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Zg≥oszenie</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwa_zgloszenie'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_rezerwa_zgloszenie] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" onclick="return  validate();" src="img/act.gif" title="Zatwierdº" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[rezerwa_zgloszenie] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      
if (isset($change['rezerwa_zgloszenie'])){
	$result .= calendar();
	
	$result .= '<script language="JavaScript1.2">
		<!--
		function validate() {		
			if (document.getElementById(\'paxName\').value == "") {
				alert("ProszÍ wpisaÊ imiÍ poszkdowanego");
				document.getElementById(\'paxName\').focus();
				return false;
			}
			
			if (document.getElementById(\'paxSurname\').value == "") {
				alert("ProszÍ wpisaÊ nazwisko poszkdowanego");
				document.getElementById(\'paxSurname\').focus();
				return false;
			}
			return true;
		}
		
		function check_wysylka2(){
			if (document.getElementById(\'status2\').checked){
				if ( check_wysylka() ){
						if (confirm(\'Czy napewno ponowna wysy≥ka tej sprawy?\'))
							return true
						else
							return false	
				
				}else{
					return false;
				}
			}else
				return true;
		}
		
		function check_wysylka(){
	
		if (!document.getElementById(\'status\') || document.getElementById(\'status\').checked){
		
		  
		
					if (document.getElementById(\'paxName\').value == "") {
						alert("ProszÍ wpisaÊ imiÍ poszkdowanego");
						document.getElementById(\'paxName\').focus();
						return false;
					}
					
					if (document.getElementById(\'paxSurname\').value == "") {
						alert("ProszÍ wpisaÊ nazwisko poszkdowanego");
						document.getElementById(\'paxSurname\').focus();
						return false;
					}
					
			  if (document.getElementById(\'ubezpieczajacy_k\').checked ){
						  
				  		  if (document.getElementById(\'ubezpieczaj_imie\').value == "") {
								alert("ProszÍ wpisaÊ imiÍ ubezpieczaj±cego");
								document.getElementById(\'ubezpieczaj_imie\').focus();
								return false;
							}
							
							if (document.getElementById(\'ubezpieczaj_nazwisko\').value == "") {
								alert("ProszÍ wpisaÊ nazwisko ubezpieczaj±cego");
								document.getElementById(\'ubezpieczaj_nazwisko\').focus();
								return false;
							}
				  }else if (document.getElementById(\'ubezpieczajacy_i\').checked ){
				  			if (document.getElementById(\'ubezpieczajacy_instytucja\').value  > 0 ) {
				  			
				  			}else{
				  				alert("ProszÍ wybraÊ instytucjÍ ubezpieczaj±c±");
								document.getElementById(\'ubezpieczajacy_instytucja\').focus();
								return false;
				  			}
				  			
				  }else{
				  				alert("Brak ubezpieczaj±cego");
								document.getElementById(\'ubezpieczajacy_i\').focus();
								return false;
				  }
				  
				  
				 	if ( document.getElementById(\'policy\').value == "" )  {
						alert("Brak nr polisy!");
						document.getElementById(\'policy\').focus();
						return false;
					}
					
					/*else if ( !isPolisa(document.getElementById(\'policy\').value) )   {
						alert("B≥Ídny nr polisy!");
						document.getElementById(\'policy\').focus();
						return false;
					}*/
					
					
					/*if ( document.getElementById(\'policyamount\').value == ""  ) {
						alert("Brak kwoty ubezpieczenia!");
						document.getElementById(\'policyamount\').focus();
						return false;
					}						
					*/
				 	if ( !(document.getElementById(\'ryzyko_gl\').value > 0) ) {
						alert("ProszÍ wybraÊ ryzyko g≥Ûwne");
						document.getElementById(\'ryzyko_gl\').focus();
						return false;
					}
					
					if (document.getElementById(\'upowaz_imie\').value == "") {
						alert("ProszÍ wpisaÊ imiÍ upowaønionego");
						document.getElementById(\'upowaz_imie\').focus();
						return false;
					}
					
					if (document.getElementById(\'upowaz_nazwisko\').value == "") {
						alert("ProszÍ wpisaÊ nazwisko upowaønionego");
						document.getElementById(\'upowaz_nazwisko\').focus();
						return false;
					}
				
				';
				$date_tmp = explode('-',$row3['eventdate'])		;
			
				$result .=' 
							if ( \''.$row3['eventdate'].'\' == \'0000-00-00\' ||  !isDate('.$date_tmp[0].', '.$date_tmp[1].', '.$date_tmp[2].')){
						alert(\'Brak lub b≥Ídna data zdarzenia!!!\');	
						return false;	
					}
					
					
					return true;
			}else{
				return true;
			}	
		}
		
		
   
		//-->
		</script>';
		$result .= '<table cellpadding="5" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
<tr  bgcolor="#AABBCC" >
<td width="5%">&nbsp;</td>
					<td  colspan="3" title="'.($row2['status']==1 ? 'Data: '.$row2['status_date']."\n"."Uøytkownik: ".getUserName($row2['status_user_id']) : '' ).
		
		($row2['status2']==1 ? ', Ponowna wysy≥ka Data: '.$row2['status2_date']."\n"."Uøytkownik: ".getUserName($row2['status2_user_id']) : '' )
		.'"><b> Status zg≥oszenia: ';
					
					$status = $row2['status'];
					$status2 = $row2['status2'];
					$signal_status = $row2['signal_status'];
			
					
					if ($signal_status == 1 ){
							$result .= '<span title="'.($row2['status']==1 ? 'Data: '.$row2['status_date']."\n"."Uøytkownik: ".getUserName($row2['status_user_id']) : '' ).'">Wys≥ane</span>';																		
							$result .= '<input type="checkbox" id="status2" name="status2" value="1" '.($status2==1 ? 'checked' : '' ).' style="background-color:#AABBCC;" OnClick="return check_wysylka2();"> Ponowna wysy≥ka'; 						
					}else{
						$result .= 'Nie wys≥ane';																													
						$result .= '&nbsp;&nbsp;&nbsp;';
						$result .= '<input type="checkbox" id="status" name="status" '.($status==1 ? 'checked' : '' ).' style="background-color:#AABBCC;" value="1" onClick="return check_wysylka();"> Gotowe do wys≥ania'; 												
						$result .= '<input type="hidden" name="status_old"  value="'.$status.'">'; 						
					}

					$result .= '</b>
					</td>			
			</tr>		
		<tr bgcolor="#AAAAAA">
<td width="5%">&nbsp;</td>
					<td width="45%" colspan="3">
					<b>Diagnoza:</b> <input type="text" name="event" style="font: bold; color: red;" value="'. $row['event'] .'" size="60" maxlength="100" disabled>&nbsp;&nbsp;&nbsp; 					
		<b><small>Kraj zdarzenia:</small></b>&nbsp;<input type="text" name="country" id="country" value="'. $row['country_id'] .'"  size="1" disabled>                            
<select tabindex=-1 name="countryList" id="countryList" disabled>';

$result_c = mysql_query("SELECT country_id, name, prefix FROM coris_countries WHERE country_id='".$row['country_id']."' ORDER BY name");
while ($row_c = mysql_fetch_array($result_c)) {
	$result .= '<option value="'. $row_c['country_id'].'">'. substr($row_c['name'],0,13) .'</option>';
}

    $result .= '</select>	';
					$result .= '								
						</td>
					</tr>
			<tr  bgcolor="#AAAAAA" >		
<td width="5%">&nbsp;</td>
					<td  colspan="3"><table border=0 width="100%">
					<tr><td width="90" valign="top"><b>Okoliczno∂ci</b>:
					</td><td><textarea cols="69" rows="2" name="circumstances" style="font-family: Verdana; font-size: 8pt"  disabled>'. $row3['circumstances'] .'</textarea>
					</td></tr></table>
					
					</td>
					</tr>					
				<tr>
<td width="5%">&nbsp;</td>
					<td width="45%">
					<b>Choroba:</b>	';
					$result .= wysw_choroba('choroba',$row2['choroba']);
					$result .= '
								</td>
					<td width="5%">&nbsp;</td>
					<td width="45%">
					<b>Okoliczno∂ci:</b> ';									
					 $result .= wysw_okolicznosci('okolicznosci',$row2['okolicznosci']);	
							$result .= '
						</td>
					</tr>
				<tr>
<td width="5%">&nbsp;</td>
							<td colspan=3 >
							<b>Ryzyko g≥Ûwne:</b>	';
							$result .= wysw_ryzyko_gl('ryzyko_gl',$row2['ryzyko_gl']);
								$result .= ' </td> ';														
							$result .= '						
					</tr>
			</table>		

		<table cellpadding="1" cellspacing="0" border="0" align="center" width=90%>
			  <tr>				
				<td width="45%" >
					<b>'. AS_CASADD_POL .'</b>: 
					<input type="text" id="policy"  name="policy" value="'. $row3['policy'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30">
				</td>	
				<td width="5%"> &nbsp;</td>
				<td width="45%">';										
				$result .= '</td></tr>		';
						$result .= '</td></tr>	
			 <tr><td colspan="3"> <b>Agent:</b> ';
				$result .= wysw_biuro_podrozy('biurop_id',$row2['biurop_id'],0);										
				$result .= '</td></tr>		
				
		</table><br>';

				
	$result .= '
	<table cellpadding="10" cellspacing="0" border="0" align="center" width=95%>
	
	<tr bgcolor="#AAAAAA"><td width="100"><b>Ubezpieczony</b></td>
	<td >
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="paxSurname" name="paxsurname" style="font: bold;" value="'. $row['paxsurname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="50">
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="paxName"  name="paxname" style="font: bold;" value="'. $row['paxname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="25">
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('paxsex',$row['paxsex']).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input tabindex="14" type="text" id="paxDob_d" name="paxDob_d" value="'. $paxDob[2] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));">
					<input tabindex="15" type="text" id="paxDob_m" name="paxDob_m" value="'. $paxDob[1] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<input tabindex="16" type="text" id="paxDob_y" name="paxDob_y" value="'. $paxDob[0] .'" size="4" maxlength="4" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
							
                            
					<a href="javascript:void(0)" onclick="newWindowCal(\'paxDob\')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="pax_pesel" name="pax_pesel"  value="'. $row3['pax_pesel'] .'"  size="12" maxlength="11">
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" id="paxaddress" name="paxaddress" value="'.$row3['paxaddress'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" id="paxpost_1" name="paxpost_1" value="'. $paxpost[0] .'" size="1" maxlength="2" onKeyUp="move(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;<input type="text" id="paxpost_2" name="paxpost_2" value="'. $paxpost[1] .'" size="2" maxlength="3" onKeyDown="remove(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" id="paxcity" name="paxcity" value="'. $row3['paxcity'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'paxcountry',$row3['paxcountry']);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" id="paxphone" name="paxphone" value="'.$row3['paxphone'] .'" size="20" maxlength="30">
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" id="pax_email" name="pax_email" value="'.$row3['pax_email'] .'" size="25" maxlength="50">
                        	 </td>
                        </tr>
                  <tr>
				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('ubezpieczon_pay_type',$row2['ubezpieczon_pay_type'],0,'').'<br>
					
					<small><b>Bank:</b></small>&nbsp;<input name="ubezpieczon_bank_nazwa" id="ubezpieczon_bank_nazwa" value="'.$row2['ubezpieczon_bank_nazwa'].'" type="text" size="30">
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="ubezpieczon_konto" id="ubezpieczon_konto" value="'.format_konto($row2['ubezpieczon_konto']).'" type="text" size="35">
					
				</td></tr>   
			</table>			
	</td></tr>	
	
	<tr bgcolor="#CCCCCC"><td width="100"><b>Poszkodowany</b>
	<br><br>
	<div align="right" style="width:100px;"><a href="javascript:;" title="Kopiuj dane z ubezpieczonego" onClick="kopiuj_dane(\'poszk\');" style="font-size:7pt;color: green;"><b>kopiuj z ubezpiecz.</b></a></div>
	<div align="right" style="width:100px;margin-top:10px;"><a href="javascript:;"  onClick="usun_dane(\'poszk\');" style="font-size:7pt;color: red;"><b>usuÒ dane</b></a></div>
	</td>
	<td >
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="poszk_nazwisko" name="poszk_nazwisko" style="font: bold;" value="'. $row2['poszk_nazwisko'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="50">
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="poszk_imie"  name="poszk_imie" style="font: bold;" value="'. $row2['poszk_imie'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="25">
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('poszk_plec',$row2['poszk_plec']).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="poszk_data_ur_d" name="poszk_data_ur_d" value="'. $poszk_data_ur[2] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));">
					<input type="text" id="poszk_data_ur_m" name="poszk_data_ur_m" value="'. $poszk_data_ur[1] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<input type="text" id="poszk_data_ur_y" name="poszk_data_ur_y" value="'. $poszk_data_ur[0] .'" size="4" maxlength="4" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<a href="javascript:void(0)" onclick="newWindowCal(\'poszk_data_ur\')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="poszk_pesel" name="poszk_pesel"  value="'. $row2['poszk_pesel'] .'"  size="12" maxlength="11">
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="poszk_ulica" id="poszk_ulica" value="'.$row2['poszk_ulica'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" id="poszk_kod_1" name="poszk_kod_1" value="'. $poszk_kod[0] .'" size="1" maxlength="2" onKeyUp="move(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;<input type="text" name="poszk_kod_2" id="poszk_kod_2" value="'. $poszk_kod[1] .'" size="2" maxlength="3" onKeyDown="remove(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" id="poszk_miasto" name="poszk_miasto" value="'. $row2['poszk_miasto'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'poszk_panstwo',$row2['poszk_panstwo']);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" id="poszk_telefon" name="poszk_telefon" value="'.$row2['poszk_telefon'] .'" size="20" maxlength="30">
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" id="poszk_email" name="poszk_email" value="'.$row2['poszk_email'] .'" size="25" maxlength="50">
                        	 </td>
                        </tr>
                       <tr> 
                   				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('poszk_pay_type',$row2['poszk_pay_type'],0,'').'<br>
					<small><b>Bank:</b></small>&nbsp;<input name="poszk_bank_nazwa" id="poszk_bank_nazwa" value="'.$row2['poszk_bank_nazwa'].'" type="text" size="30">
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="poszk_konto" id="poszk_konto" value="'.format_konto($row2['poszk_konto']).'" type="text" size="35">
				</td></tr>   
     
			</table>			
	</td></tr>	

	<tr bgcolor="#AAAAAA"><td width="100"><b>Ubezpieczaj±cy</b>
	<br><br>
	<div align="right" style="width:100px;"><a href="javascript:;" title="Kopiuj dane z ubezpieczonego" onClick="kopiuj_dane(\'ubezpieczaj\');" style="font-size:7pt;color: green;"><b>kopiuj z ubezpiecz.</b></a></div>
	<div align="right" style="width:100px;margin-top:10px;"><a href="javascript:;"  onClick="usun_dane(\'ubezpieczaj\');" style="font-size:7pt;color: red;"><b>usuÒ dane</b></a></div>
	</td>
	<td >
			<table width="100%">
			<tr>
				<td width="150"><input type="radio" name="ubezpieczajacy"  id="ubezpieczajacy_i" value="I" style="background-color: #AAAAAA;" onClick="zmien_ubezpieczajacy(this.value);"> Instytucja: </td><td><div id="ubezpieczajacy_form_instytucja" >'.wysw_instytucja('ubezpieczajacy_instytucja',$row2['ubezpieczajacy_instytucja']).' </div>				 </td>
				</tr>
			<tr>
				<td><input type="radio" name="ubezpieczajacy" id="ubezpieczajacy_k" value="K" style="background-color: #AAAAAA;"  onClick="zmien_ubezpieczajacy(this.value);"> Klient: </td><td>				 </td>
				</tr>
			</table>	
			<div id="ubezpieczajacy_form_klient" ><table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="ubezpieczaj_nazwisko" name="ubezpieczaj_nazwisko" style="font: bold;" value="'. $row2['ubezpieczaj_nazwisko'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="50">
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="ubezpieczaj_imie"  name="ubezpieczaj_imie" style="font: bold;" value="'. $row2['ubezpieczaj_imie'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="25">
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('ubezpieczaj_plec',$row2['ubezpieczaj_plec']).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="ubezpieczaj_data_ur_d" name="ubezpieczaj_data_ur_d" value="'. $ubezpieczaj_data_ur[2] .'" size="1" maxlength="2" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));">
					<input type="text" id="ubezpieczaj_data_ur_m" name="ubezpieczaj_data_ur_m" value="'. $ubezpieczaj_data_ur[1] .'" size="1" maxlength="2" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<input type="text" id="ubezpieczaj_data_ur_y" name="ubezpieczaj_data_ur_y" value="'. $ubezpieczaj_data_ur[0] .'" size="4" maxlength="4" maxlength="4" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<a href="javascript:void(0)" onclick="newWindowCal(\'ubezpieczaj_data_ur\')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="ubezpieczaj_pesel" name="ubezpieczaj_pesel"  value="'. $row2['ubezpieczaj_pesel'] .'"  size="12" maxlength="11">
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="ubezpieczaj_ulica" value="'.$row2['ubezpieczaj_ulica'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="ubezpieczaj_kod_1" value="'. $ubezpieczaj_kod[0] .'" size="1" maxlength="2" onKeyUp="move(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;<input type="text" name="ubezpieczaj_kod_2" value="'. $ubezpieczaj_kod[1] .'" size="2" maxlength="3" onKeyDown="remove(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="ubezpieczaj_miasto" value="'. $row2['ubezpieczaj_miasto'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'ubezpieczaj_panstwo',$row2['ubezpieczaj_panstwo']);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="ubezpieczaj_telefon" value="'.$row2['ubezpieczaj_telefon'] .'" size="20" maxlength="30">
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" name="ubezpieczaj_email" value="'.$row2['ubezpieczaj_email'] .'" size="25" maxlength="50">
                        	 </td>
                        </tr>
                        <tr> 
                   				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('ubezpieczaj_pay_type',$row2['ubezpieczaj_pay_type'],0,'').'<br>
					<small><b>Bank:</b></small>&nbsp;<input name="ubezpieczaj_bank_nazwa" id="ubezpieczaj_bank_nazwa" value="'.$row2['ubezpieczaj_bank_nazwa'].'" type="text" size="30">
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="ubezpieczaj_konto" id="ubezpieczaj_konto" value="'.format_konto($row2['ubezpieczaj_konto']).'" type="text" size="35">
				</td></tr>   
 
			</table>	
			</div>		
	</td></tr>	
		<script>
		
function usun_dane(przedr){

if (confirm(\'Czy napewno chcesz usunaÊ dane?\')){
	var lista = new Array(\'_nazwisko\',\'_imie\',\'_plec\',\'_data_ur_d\',\'_data_ur_m\',\'_data_ur_y\',\'_pesel\',\'_ulica\',\'_kod_1\',\'_kod_2\',\'_miasto\',\'_panstwo\',\'_panstwolist\',\'_telefon\',\'_email\',\'_konto\',\'_bank_nazwa\');
	ilosc = lista.length;
	for (i=0;i<ilosc;i++){		
		document.getElementById(przedr + lista[i]).value=\'\';
	}
}
}
		
function kopiuj_dane(przedr){
 if (confirm(\'Czy napewno chcesz skopiowaÊ dane?\')){
	var lista_src = new Array(\'paxSurname\',\'paxName\',\'paxsex\',\'paxDob_d\',\'paxDob_m\',\'paxDob_y\',\'pax_pesel\',\'paxaddress\',\'paxpost_1\',\'paxpost_2\',\'paxcity\',\'paxcountry\',\'paxcountrylist\',\'paxphone\',\'pax_email\',\'ubezpieczon_pay_type\',\'ubezpieczon_konto\',\'ubezpieczon_bank_nazwa\');
	var lista = new Array(\'_nazwisko\',\'_imie\',\'_plec\',\'_data_ur_d\',\'_data_ur_m\',\'_data_ur_y\',\'_pesel\',\'_ulica\',\'_kod_1\',\'_kod_2\',\'_miasto\',\'_panstwo\',\'_panstwolist\',\'_telefon\',\'_email\',\'_pay_type\',\'_konto\',\'_bank_nazwa\');
	
	ilosc = lista.length;
	for (i=0;i<ilosc;i++){		
		document.getElementById(przedr + lista[i]).value=document.getElementById( lista_src[i]).value;
	}
}
}
		
	function zmien_ubezpieczajacy(val){
			
				if (val == \'K\'){
					document.getElementById(\'ubezpieczajacy_form_instytucja\').style.display = \'none\';
					document.getElementById(\'ubezpieczajacy_form_klient\').style.display = \'block\';
					document.getElementById(\'ubezpieczajacy_k\').checked = true;
				}else if (val == \'I\'){
					document.getElementById(\'ubezpieczajacy_form_instytucja\').style.display = \'block\';
					document.getElementById(\'ubezpieczajacy_form_klient\').style.display = \'none\';				
					document.getElementById(\'ubezpieczajacy_i\').checked = true;
				}else{
					document.getElementById(\'ubezpieczajacy_form_instytucja\').style.display = \'none\';
					document.getElementById(\'ubezpieczajacy_form_klient\').style.display = \'none\';				
					
				}
	}
	 zmien_ubezpieczajacy();
	 
	 zmien_ubezpieczajacy(\''.(($row2['ubezpieczajacy']=='K') ? 'K' : 'I' ).'\');
	</script>
	
	<tr bgcolor="#CCCCCC"><td width="100"><b>Upowaøniony</b>
	
	<br><br>
	<div align="right" style="width:100px;"><a href="javascript:;" title="Kopiuj dane z ubezpieczonego" onClick="kopiuj_dane(\'upowaz\');" style="font-size:7pt;color: green;"><b>kopiuj z ubezpiecz.</b></a></div>
	<div align="right" style="width:100px;margin-top:10px;"><a href="javascript:;"  onClick="usun_dane(\'upowaz\');" style="font-size:7pt;color: red;"><b>usuÒ dane</b></a></div>
	
	</td>
	<td >
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="upowaz_nazwisko" name="upowaz_nazwisko" style="font: bold;" value="'. $row2['upowaz_nazwisko'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="50">
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="upowaz_imie"  name="upowaz_imie" style="font: bold;" value="'. $row2['upowaz_imie'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="25">
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('upowaz_plec',$row2['upowaz_plec']).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="upowaz_data_ur_d" name="upowaz_data_ur_d" value="'. $upowaz_data_ur[2] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));">
					<input type="text" id="upowaz_data_ur_m" name="upowaz_data_ur_m" value="'. $upowaz_data_ur[1] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<input type="text" id="upowaz_data_ur_y" name="upowaz_data_ur_y" value="'. $upowaz_data_ur[0] .'" size="4" maxlength="4" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<a href="javascript:void(0)" onclick="newWindowCal(\'upowaz_data_ur\')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="upowaz_pesel" name="upowaz_pesel"  value="'. $row2['upowaz_pesel'] .'"  size="12" maxlength="11">
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="upowaz_ulica" value="'.$row2['upowaz_ulica'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="upowaz_kod_1" value="'. $upowaz_kod[0] .'" size="1" maxlength="2" onKeyUp="move(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;<input type="text" name="upowaz_kod_2" value="'. $upowaz_kod[1] .'" size="2" maxlength="3" onKeyDown="remove(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" id="upowaz_miasto" name="upowaz_miasto" value="'. $row2['upowaz_miasto'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'upowaz_panstwo',$row2['upowaz_panstwo']);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="upowaz_telefon" value="'.$row2['upowaz_telefon'] .'" size="20" maxlength="30">
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" name="upowaz_email" value="'.$row2['upowaz_email'] .'" size="25" maxlength="50">
                        	 </td>
                        </tr>
                      <tr> 
        				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('upowaz_pay_type',$row2['upowaz_pay_type'],0,'').'<br>
					<small><b>Bank:</b></small>&nbsp;<input name="upowaz_bank_nazwa" id=""upowaz_bank_nazwa" value="'.$row2['upowaz_bank_nazwa'].'" type="text" size="30">
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="upowaz_konto" id="upowaz_konto" value="'.($row2['upowaz_konto']).'" type="text" size="35">
				</td></tr>   

			</table>			
	</td></tr>	
		</table><br>	

		
			';
							
}else{
			$result .= '<table cellpadding="5" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
<tr  bgcolor="#AABBCC" >
<td width="5%">&nbsp;</td>
					<td  colspan="3" title="'.($row2['status']==1 ? 'Data: '.$row2['status_date']."\n"."Uøytkownik: ".getUserName($row2['status_user_id']) : '' ).($row2['status2']==1 ? ','."\n".' Ponowna wysy≥ka Data: '.$row2['status2_date']."\n"."Uøytkownik: ".getUserName($row2['status2_user_id']) : '' ).'"><b> Status zg≥oszenia: ';
					
					$status = $row2['status'];
					$status2 = $row2['status2'];
					$signal_status = $row2['signal_status'];
			
					
					if ($signal_status == 1 ){
							$result .= '<span >Wys≥ane</span>';																																										
							$result .= '<input type="checkbox" id="status2" name="status2" value="1" '.($status2==1 ? 'checked' : '' ).' style="background-color:#AABBCC;" disabled> Ponowna wysy≥ka'; 
					}else{
						$result .= 'Nie wys≥ane';																								
						
						$result .= '&nbsp;&nbsp;&nbsp;';
						$result .= '<input type="checkbox" name="status" value="1" '.($status==1 ? 'checked' : '' ).' style="background-color:#AABBCC;" disabled> Gotowe do wys≥ania'; 												
					}


					$result .= '</b>
					</td>			
			</tr>
		<tr  bgcolor="#AAAAAA" >
<td width="5%">&nbsp;</td>
					<td  colspan="3">
					<b>Diagnoza:</b> <input type="text" name="event" style="font: bold; color: red;" value="'. $row['event'] .'" size="60" maxlength="100" disabled>&nbsp;&nbsp;&nbsp; 					
		<b><small>Kraj zdarzenia:</small></b>&nbsp;<input type="text" name="country" id="country" value="'. $row['country_id'] .'"  size="1" disabled>                            
<select tabindex=-1 name="countryList" id="countryList" disabled>';

$result_c = mysql_query("SELECT country_id, name, prefix FROM coris_countries WHERE country_id='".$row['country_id']."' ORDER BY name");
while ($row_c = mysql_fetch_array($result_c)) {
	$result .= '<option value="'. $row_c['country_id'].'">'. substr($row_c['name'],0,13) .'</option>';
}

    $result .= '</select>	';
					$result .= '								
						</td>
					</tr>
			<tr  bgcolor="#AAAAAA" >		
<td width="5%">&nbsp;</td>
					<td  colspan="3"><table border=0 width="100%">
					<tr><td width="90" valign="top"><b>Okoliczno∂ci</b>:
					</td><td><textarea cols="69" rows="2" name="circumstances" style="font-family: Verdana; font-size: 8pt"  disabled>'. $row3['circumstances'] .'</textarea>
					</td></tr></table>
					
					</td>
					</tr>				
						
		<tr>
<td width="5%">&nbsp;</td>
						<td width="45%"><b>Choroba:</b>	';
					$result .= wysw_choroba('choroba',$row2['choroba'],1);
					$result .= '
											</td>
														<td width="5%">&nbsp;</td>
														<td width="45%">
														<b>Okoliczno∂ci:</b> ';								
							 $result .= wysw_okolicznosci('okolicznosci',$row2['okolicznosci'],1);	
							$result .= '
						</td>
					</tr>
				<tr>
<td width="5%">&nbsp;</td>
				<td colspan=3 ><b>Ryzyko g≥Ûwne:</b>	';
				$result .= wysw_ryzyko_gl('ryzyko_gl',$row2['ryzyko_gl'],1);
				$result .= ' </td> ';										
				$result .= '</tr>
			</table>		
		<table cellpadding="4" border=0 cellspacing="0" border="0" align="center" width=90%>
			  <tr>
				<td width="45%" ><b>'. AS_CASADD_POL .'</b>: <input type="text" name="policy" value="'. $row3['policy'] .'"  size="30" maxlength="30" disabled></td>	
				<td width="5%"> &nbsp;</td>
				<td width="45%">&nbsp;';										
				$result .= '</td></tr>	
			 <tr><td colspan="3"> <b>Agent:</b> ';
				$result .= wysw_biuro_podrozy('biurop_id',$row2['biurop_id'],1);										
				$result .= '</td></tr>			
		</table><br>';
				
				
///
	
					
///

$result .= '
	<table cellpadding="1" cellspacing="0" border="0" align="center" width=95%>
	
	<tr bgcolor="#AAAAAA"><td width="100"><b>Ubezpieczony</b></td>
	<td >
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="paxSurname" name="paxsurname" style="font: bold;" value="'. $row['paxsurname'] .'"  size="28" maxlength="50" disabled>
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="paxName"  name="paxname" style="font: bold;" value="'. $row['paxname'] .'" size="28" maxlength="25" disabled>
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('paxsex',$row['paxsex'],1).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="paxDob_d" name="paxDob_d" value="'. $paxDob[2] .'" size="1" disabled>
					<input type="text" id="paxDob_m" name="paxDob_m" value="'. $paxDob[1] .'" size="1" disabled>
					<input type="text" id="paxDob_y" name="paxDob_y" value="'. $paxDob[0] .'" size="4" disabled>					
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="pax_pesel" name="pax_pesel"  value="'. $row3['pax_pesel'] .'"  size="12" maxlength="11" disabled>
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="paxaddress" value="'.$row3['paxaddress'] .'" size="30" maxlength="50" disabled>&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="paxpost_1" value="'. $paxpost[0] .'" size="1" maxlength="2" disabled>&nbsp;<input type="text" name="paxpost_2" value="'. $paxpost[1] .'" size="2" maxlength="3" disabled>&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="paxcity" value="'. $row3['paxcity'] .'" size="25" maxlength="25" disabled>
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'paxcountry',$row3['paxcountry'],1);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="paxphone" value="'.$row3['paxphone'] .'" size="20" maxlength="30" disabled>
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" name="pax_email" value="'.$row3['pax_email'] .'" size="25" maxlength="50" disabled>
                        	 </td>
                        </tr>
                                              <tr> 
        				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('ubezpieczon_pay_type',$row2['ubezpieczon_pay_type'],1,'').'<br>
					<small><b>Bank:</b></small>&nbsp;<input name="ubezpieczon_bank_nazwa" id="ubezpieczon_bank_nazwa" value="'.$row2['ubezpieczon_bank_nazwa'].'" type="text" size="30" disabled>
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="ubezpieczon_konto" id="ubezpieczon_konto" value="'.format_konto($row2['ubezpieczon_konto']).'" type="text" size="35" disabled>
				</td></tr>   

			</table>		
	</td></tr>	
		<tr bgcolor="#CCCCCC"><td width="100"><b>Poszkodowany</b></td>
	<td >';
		
	if ($row2['poszk_nazwisko'] != '' ||  $row2['poszk_imie'] != ''){			
		$result .= '<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="poszk_nazwisko" name="poszk_nazwisko" style="font: bold;" value="'. $row2['poszk_nazwisko'] .'"  size="28" maxlength="50" disabled>
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="poszk_imie"  name="poszk_imie" style="font: bold;" value="'. $row2['poszk_imie'] .'"  size="28" maxlength="25"  disabled>
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('poszk_plec',$row2['poszk_plec'],1).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="poszk_data_ur_d" name="poszk_data_ur_d" value="'. $poszk_data_ur[2] .'" size="1"   disabled>
					<input type="text" id="poszk_data_ur_m" name="poszk_data_ur_m" value="'. $poszk_data_ur[1] .'" size="1"  disabled>
					<input type="text" id="poszk_data_ur_y" name="poszk_data_ur_y" value="'. $poszk_data_ur[0] .'" size="4"  disabled>
					
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="poszk_pesel" name="poszk_pesel"  value="'. $row2['poszk_pesel'] .'"  size="12" maxlength="11"  disabled>
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="poszk_ulica" value="'.$row2['poszk_ulica'] .'"  size="30" maxlength="50"  disabled>&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="poszk_kod_1" value="'. $poszk_kod[0] .'" size="1" maxlength="2" disabled>&nbsp;<input type="text" name="poszk_kod_2" value="'. $poszk_kod[1] .'" size="2" maxlength="3" o disabled>&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="poszk_miasto" value="'. $row2['poszk_miasto'] .'"  size="25" maxlength="25"  disabled>
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'poszk_panstwo',$row2['poszk_panstwo'],1);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="poszk_telefon" value="'.$row2['poszk_telefon'] .'" size="20" maxlength="30"  disabled>
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" name="poszk_email" value="'.$row2['poszk_email'] .'" size="25" maxlength="50"  disabled>
                        	 </td>
                        </tr>
                                                                     <tr> 
        				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('poszk_pay_type',$row2['poszk_pay_type'],1,'').'<br>
					<small><b>Bank:</b></small>&nbsp;<input name="poszk_bank_nazwa" id="poszk_bank_nazwa" value="'.$row2['poszk_bank_nazwa'].'" type="text" size="30" disabled>
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="poszk_konto" id="poszk_konto" value="'.format_konto($row2['poszk_konto']).'" type="text" size="35" disabled>
				</td></tr>   
 
			</table>		';
	}else{
		
		$result .= '&nbsp;';	
	}
	$result .= '</td></tr>	

	
	<tr bgcolor="#AAAAAA"><td width="100"><b>Ubezpieczaj±cy</b></td>
	<td >
			<table width="100%">
			<tr>
				<td width="150">
				<input type="radio" name="ubezpieczajacy"  id="ubezpieczajacy_i" value="I" style="background-color: #AAAAAA;"  disabled> Instytucja: 
				
				</td><td><div id="ubezpieczajacy_form_instytucja" >'.wysw_instytucja('ubezpieczajacy_instytucja',$row2['ubezpieczajacy_instytucja'],1).' </div>				 </td>
				</tr>
			<tr>
				<td>
				
				 <input type="radio" name="ubezpieczajacy" id="ubezpieczajacy_k" value="K" style="background-color: #AAAAAA;"   disabled> Klient:

				</td><td>				 </td>
				</tr>
			</table>	
			<div id="ubezpieczajacy_form_klient" >
			
			
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="ubezpieczaj_nazwisko" name="ubezpieczaj_nazwisko" style="font: bold;" value="'. $row2['ubezpieczaj_nazwisko'] .'"  size="28" maxlength="50" disabled>
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="ubezpieczaj_imie"  name="ubezpieczaj_imie" style="font: bold;" value="'. $row2['ubezpieczaj_imie'] .'" size="28" maxlength="25"  disabled>
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('ubezpieczaj_plec',$row2['ubezpieczaj_plec'],1).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="ubezpieczaj_data_ur_d" name="ubezpieczaj_data_ur_d" value="'. $ubezpieczaj_data_ur[2] .'" size="1"  disabled>
					<input type="text" id="ubezpieczaj_data_ur_m" name="ubezpieczaj_data_ur_m" value="'. $ubezpieczaj_data_ur[1] .'" size="1"  disabled>
					<input type="text" id="ubezpieczaj_data_ur_y" name="ubezpieczaj_data_ur_y" value="'. $ubezpieczaj_data_ur[0] .'" size="4"  disabled>
					
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="ubezpieczaj_pesel" name="ubezpieczaj_pesel"  value="'. $row2['ubezpieczaj_pesel'] .'"  size="12" maxlength="11"  disabled>
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="ubezpieczaj_ulica" value="'.$row2['ubezpieczaj_ulica'] .'"  size="30" maxlength="50"  disabled>&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="ubezpieczaj_kod_1" value="'. $ubezpieczaj_kod[0] .'" size="1" maxlength="2"  disabled>&nbsp;<input type="text" name="ubezpieczaj_kod_2" value="'. $ubezpieczaj_kod[1] .'" size="2" maxlength="3"  disabled>&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="ubezpieczaj_miasto" value="'. $row2['ubezpieczaj_miasto'] .'"  size="25" maxlength="25"  disabled>
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'ubezpieczaj_panstwo',$row2['ubezpieczaj_panstwo'],1);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="ubezpieczaj_telefon" value="'.$row2['ubezpieczaj_telefon'] .'" size="20" maxlength="30"  disabled>
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" name="ubezpieczaj_email" value="'.$row2['ubezpieczaj_email'] .'" size="25" maxlength="50"  disabled>
                        	 </td>
                        </tr>
                                                                                            <tr> 
        				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('ubezpieczaj_pay_type',$row2['ubezpieczaj_pay_type'],1,'').'<br>
					<small><b>Bank:</b></small>&nbsp;<input name="ubezpieczaj_bank_nazwa" id="ubezpieczaj_bank_nazwa" value="'.$row2['ubezpieczaj_bank_nazwa'].'" type="text" size="30" disabled>
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="ubezpieczaj_konto" id="ubezpieczaj_konto" value="'.format_konto($row2['ubezpieczaj_konto']).'" type="text" size="35" disabled>
				</td></tr>   
 
			</table>	
			</div>		
	</td></tr>	
<script>
	function zmien_ubezpieczajacy(val){
			
				if (val == \'K\'){
					document.getElementById(\'ubezpieczajacy_form_instytucja\').style.display = \'none\';
					document.getElementById(\'ubezpieczajacy_form_klient\').style.display = \'block\';
					document.getElementById(\'ubezpieczajacy_k\').checked = true;
				}else if (val == \'I\'){
					document.getElementById(\'ubezpieczajacy_form_instytucja\').style.display = \'block\';
					document.getElementById(\'ubezpieczajacy_form_klient\').style.display = \'none\';				
					document.getElementById(\'ubezpieczajacy_i\').checked = true;
				}else{
					document.getElementById(\'ubezpieczajacy_form_instytucja\').style.display = \'none\';
					document.getElementById(\'ubezpieczajacy_form_klient\').style.display = \'none\';				
					
				}
	}
	 zmien_ubezpieczajacy();
	 
	 zmien_ubezpieczajacy(\''.(($row2['ubezpieczajacy']=='K') ? 'K' : 'I' ).'\');
	</script>
	
	<tr bgcolor="#CCCCCC"><td width="100"><b>Upowaøniony</b></td>
	<td >';
				
		if ($row2['upowaz_nazwisko'] != '' ||  $row2['upowaz_imie'] != ''){		
			$result .= '<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="upowaz_nazwisko" name="upowaz_nazwisko" style="font: bold;" value="'. $row2['upowaz_nazwisko'] .'"  size="28" maxlength="50" disabled>
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="upowaz_imie"  name="upowaz_imie" style="font: bold;" value="'. $row2['upowaz_imie'] .'"  size="28" maxlength="25"  disabled>
				&nbsp;&nbsp;&nbsp;<small><b>P≥eÊ:</b></small>&nbsp;'.getPlec('upowaz_plec',$row2['upowaz_plec'],1).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="upowaz_data_ur_d" name="upowaz_data_ur_d" value="'. $upowaz_data_ur[2] .'" size="1"  disabled>
					<input type="text" id="upowaz_data_ur_m" name="upowaz_data_ur_m" value="'. $upowaz_data_ur[1] .'" size="1"  disabled>
					<input type="text" id="upowaz_data_ur_y" name="upowaz_data_ur_y" value="'. $upowaz_data_ur[0] .'" size="4"  disabled>
					
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PESEL</b></small>: <input type="text" id="upowaz_pesel" name="upowaz_pesel"  value="'. $row2['upowaz_pesel'] .'"  size="12" maxlength="11"  disabled>
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>Adres</b></small></td><td>
				  <input type="text" name="upowaz_ulica" value="'.$row2['upowaz_ulica'] .'" size="30" maxlength="50"  disabled>&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="upowaz_kod_1" value="'. $upowaz_kod[0] .'" size="1" maxlength="2"  disabled>&nbsp;<input type="text" name="upowaz_kod_2" value="'. $upowaz_kod[1] .'" size="2" maxlength="3"  disabled>&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="upowaz_miasto" value="'. $row2['upowaz_miasto'] .'"  disabled size="25" maxlength="25">
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  wysw_country( 'upowaz_panstwo',$row2['upowaz_panstwo'],1);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="upowaz_telefon" value="'.$row2['upowaz_telefon'] .'" size="20" maxlength="30"  disabled>
                    	   &nbsp;&nbsp;
                        	<small><b>Email: </b></small>&nbsp;
                        	<input type="text" name="upowaz_email" value="'.$row2['upowaz_email'] .'" size="25" maxlength="50" disabled>
                        	 </td>
                        </tr>
                                                                        <tr> 
        				<td align="right"><small><b>Wyp≥ata:</b></small></td><td>
					<small><b>Forma wyp≥aty:</b></small> '.wysw_forma_wyplaty('upowaz_pay_type',$row2['upowaz_pay_type'],1,'').'<br>
					
					<small><b>Bank:</b></small>&nbsp;<input name="upowaz_bank_nazwa" id=""upowaz_bank_nazwa" value="'.$row2['upowaz_bank_nazwa'].'" type="text" size="30" disabled>
					&nbsp;&nbsp;<small><b>Konto:</b></small>&nbsp;<input name="upowaz_konto" id="upowaz_konto" value="'.format_konto($row2['upowaz_konto']).'" type="text" size="35" disabled>
				</td></tr>                           
			</table>		';
		}else{
				$result .= '&nbsp;';			
		}	
	$result .= '</td></tr>	
	
	</table>	<br>';
///
	}
	
	$result .= '</form>';
	return $result;
	
}	



function wysw_country($name,$def,$tryb=0){
	if ($tryb){
		$result = ' <select style="font-size: 8pt;" name="'.$name.'list" disabled>';
		$result .=  '<option value="'.$def.'" >'.$def.'</option>';
		$result .='</select>';
		return $result;
		
	}
$result = '<input type="text" id="'.$name.'" name="'.$name.'" value="'.$def .'" size="3" maxlength="2" onBlur="document.getElementById(\''.$name.'list\').value = this.value.toUpperCase(); this.value = this.value.toUpperCase()" style="text-align: center">
                        <select style="font-size: 8pt;" name="'.$name.'list" id="'.$name.'list" onChange="document.getElementById(\''.$name.'\').value = this.value">
                            <option value=""></option>';

$mysql_result = mysql_query("SELECT country_id, name, prefix FROM coris_countries ORDER BY name");
while ($row2 = mysql_fetch_array($mysql_result)) {
	$result .=  '<option value="'.$row2['country_id'] .'" '. (($row2['country_id'] == $def) ? "selected" : "" ).'>'.$row2['name'] .'</option>';

}

	$result .='</select>';
	
	return $result;
}

function  wysw_instytucja($name,$def,$tryb=0){
	if ($tryb){
		if ($def> 0){
			$query = "SELECT kod,nazwa,miasto FROM coris_signal_institution   WHERE kod='$def' ORDER BY nazwa,miasto";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);		
			return substr($row2['nazwa'],0,70).' - '.$row2['miasto'];		
		}
	}else{
		$result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 7pt;">
					<option value=""></option>';
			$query = "SELECT kod,nazwa,miasto FROM coris_signal_institution   WHERE status=1 ORDER BY nazwa,miasto";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['kod'] .'" '. (($row2['kod'] == $def) ? "selected" : "") .'>'.substr($row2['nazwa'],0,65).' - '.$row2['miasto'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}



function  wysw_choroba($name,$def,$tryb=0){
	
	$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_choroby  WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 260px;" disabled>';					
				$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		$result = '<select name="'.$name.'" style="font-size: 8pt;">
					<option value=""></option>';
			$query = "SELECT ID,numer,nazwa FROM coris_signal_choroby  WHERE status=1 ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}


function  wysw_okolicznosci($name,$def,$tryb=0){	
	$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_okolicznosci  WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 220px;" disabled>';					
				$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;				
	}else{
		$result = '<select name="'.$name.'" style="font-size: 8pt;">
					<option value=""></option>';
			$query = "SELECT ID,numer,nazwa FROM coris_signal_okolicznosci   WHERE status=1 ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}


function  wysw_opis($name,$def,$tryb=0,$case_id){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyko_operat     WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;">
					<option value=""></option>';
			//$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyko_operat       WHERE status=1  ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			//while ($row2 = mysql_fetch_array($mysql_result)) {
				//		$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			//}
		  $result .= '</select>';
	}
	return $result;															
}



function  wysw_ryzyko_czastkowe_wypl($name,$def,$tryb=0,$case_id,$option=''){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyka_czastkowe    WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" '.$option.'>
					<option value=""></option>';
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyka_czastkowe      WHERE status=1 AND ID IN (SELECT    ID_ryzyko  FROM coris_assistance_cases_reserve  WHERE case_id ='$case_id') ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}
function  wysw_lista_fakt($name,$def,$tryb=0,$case_id){

		$result='';
	
	if ($tryb){
			$query = "SELECT invoice_out_id ,invoice_out_no ,invoice_out_year, gross_amount,currency_id    
			FROM coris_finances_invoices_out       
			WHERE invoice_out_id = '$def'";					
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return 'A'.$row2['invoice_out_no'].'/'.$row2['invoice_out_year'].' - '.number_format($row2['gross_amount'],2,',',' ').' '.$row2['currency_id'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;">
					<option value=""></option>';
			$query = "SELECT invoice_out_id ,invoice_out_no ,invoice_out_year, gross_amount,currency_id    
			FROM coris_finances_invoices_out       
			WHERE case_id='$case_id'  AND invoice_out_id NOT  IN 
			(SELECT    ID_invoice_out   FROM coris_assistance_cases_pay  WHERE case_id ='$case_id') ORDER BY invoice_out_date ";						
			
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['invoice_out_id'] .'" '. (($row2['invoice_out_id'] == $def) ? "selected" : "") .'>A'.$row2['invoice_out_no'].'/'.$row2['invoice_out_year'].' - '.number_format($row2['gross_amount'],2,',',' ').' '.$row2['currency_id'].'</option>';
			}
		  $result .= '</select>';
		
	}
	return $result;															
}

function wysw_forma_wyplaty($name,$def,$tryb=0,$option){
	$result = '<select name="'.$name.'" id="'.$name.'" '.$option.' '.($tryb ? 'disabled' : '').'>
									<option value="0" '.($def==0 ? 'selected' : '').'> </option>
									<option value="1" '.($def==1 ? 'selected' : '').'>Przelew bankowy</option>
									<option value="2" '.($def==2 ? 'selected' : '').'>Przekaz pocztowy</option>									
		</select>';	
	return $result;
	
}


    function StrTrim($string, $length) {
        return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
    }


?>