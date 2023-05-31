<?php
//ubezpieczon_plec


function module_update(){			
	global  $pageName;
	$result ='';

	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');
	
	
	$check_js = '';
	$message = '';
	

	
	
	
	if (isset($change['ch_rezerwy_rezerwy']) && $case_id > 0  ){		
   		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){			   	
						
			
			$edit_form_action   = getValue('edit_form_action') ;							
									
								
			if ($edit_form_action == 'invoice_add'){
				
				$invoice_in_no = getValue('invoice_in_no');
				$amount= str_replace(',','.',getValue('amount'));				
				$currency_id = getValue('currency_id');
				$note = getValue('note');
						
				$query  = "INSERT INTO coris_finances_invoices_in_forward   SET case_id ='$case_id',invoice_in_no='$invoice_in_no',
				amount='$amount',currency_id ='$currency_id',note='$note', ID_user='".$_SESSION['user_id']."',date=now(), status=0";		
				$mysql_result = mysql_query($query);
				$poz=0;
				if ($mysql_result){
					//$message .= "Udpate OK";
					$poz = mysql_insert_id();
				}else{
					$message .= "Update Error: ".$query."\n<br> ".mysql_error();				
				}										
			}					
						
			if ($edit_form_action=='invoice_edit_save'){			
				$invoice_in_no = getValue('invoice_in_no');
				$amount= str_replace(',','.',getValue('amount'));				
				$currency_id = getValue('currency_id');
				$note = getValue('note');
				$invoice_id = getValue('invoice_id');
																			
				if ($amount > 0 ){	
					$queryu = "UPDATE coris_finances_invoices_in_forward SET invoice_in_no ='$invoice_in_no',amount ='$amount',currency_id='$currency_id',note='$note', ID_user='".$_SESSION['user_id']."',date=now()
					WHERE ID='$invoice_id' AND case_id ='$case_id' LIMIT 1";
					$mysql_result = mysql_query($queryu);			
					if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();								
					$key = $invoice_id;
									
				}else{
					$queryu = "DELETE FROM  coris_finances_invoices_in_forward 
					WHERE ID='$invoice_id' AND case_id ='$case_id' LIMIT 1";
					$mysql_result = mysql_query($queryu);			
					if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();								
					
				}						
			}		
		}else{//error update
			echo $res[1];
			
		}		

	}	

	



	
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
		
if ($row_case_settings['client_id'] == 7592 || $row_case_settings['client_id'] == 600){	
	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  faktury_odeslane($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
	
		
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 840px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw SIGNAL IDUNA</b></div>
	</div>
	';
	
}
	
	return $result;	
}

function  faktury_odeslane($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	$result .= '<a name="rezerwy_rezerwy"></a>
	<form method="POST" name="form_rezerwy" id="form_rezerwy" action="#rezerwy_rezerwy" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Faktury odes³ane</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwy_rezerwy'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name="change[ch_rezerwy_rezerwy]" id="change[ch_rezerwy_rezerwy]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
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
		function edycja_faktury(id,risk_id){
			//if (risk_id>0){
					document.getElementById(\'edit_form_action\').value=\'risk_edit\';	
					document.getElementById(\'edit_form_action_param\').value=id;						
					document.getElementById(\'change[ch_rezerwy_rezerwy]\').name=\'change[rezerwy_rezerwy]\';						
					document.getElementById(\'form_rezerwy\').submit();						
			//	}
		}
		
		function zapisz_fakture(){
				document.getElementById(\'edit_form_action\').value=\'invoice_edit_save\';	
		}
		
		
		function anuluj_fakture(){
			return true;
		}
		</script>					
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="20%" align="center"><b>Nr faktury</b></td>	
				<td width="15%" align="center"><b>Kwota</b></td>
				<td width="10%" align="center"><b>Waluta</td>
				<td width="20%" align="center"><b>Uwagi</td>
				<td width="12%" align="center"><b>Status</b></td>		
				<td width="10%" align="center"><b>U¿ytkownik</b></td>			
				<td width="10%" align="center"><b>Zmiana</b></td>			
				
			   </tr >';
			

		 	$query = "SELECT coris_finances_invoices_in_forward.*,					
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=coris_finances_invoices_in_forward.ID_user ) As user
			FROM coris_finances_invoices_in_forward WHERE case_id='$case_id' 			 						
			ORDER BY ID";			 	
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				
			  $result .= '<tr>
			  	
				<td align="right"><b>'. ($row_r['invoice_in_no']) .'</b>&nbsp;</td>									
				<td align="right"><b>'. print_currency($row_r['amount'],2) .'</b></td>
						<td align="center"><b> '.($row_r['currency_id']).'</b></td>
				
					<td align="right" title="'.addslashes($row_r['note']).'">'. substr((stripslashes(($row_r['note']))),0,20).'&nbsp;</td>	
				<td align="center">'. ($row_r['status']==1 ? "<b>Wys³ane</b>\nData:".$row_r['status_date']."\nUser: ".getUserInitials($row_r['status_ID_user']) : '<b>Nie wys³ane</b>') .'</td>					
				<td align="center">'. ($row_r['user']) .'</td>					
					<td align="center">'. ( $row_r['status']==0 ? '<a href="javascript:edycja_faktury('.$row_r['ID'].');">edycja</a>' : '&nbsp;').'</td>				
			   </tr >';
			   
			}
			
		$result .= '</table><br>';		
		
		$edit_form_action = getValue('edit_form_action');
		
		if ($edit_form_action=='risk_edit'){
				$invoice_id = getValue('edit_form_action_param');
			
					$qt = "SELECT coris_finances_invoices_in_forward.*					
					FROM coris_finances_invoices_in_forward  WHERE ID='$invoice_id' LIMIT 1";
					$mt = mysql_query($qt);
					if (!$mt) {echo "Error q: ".$qt.'<br><br>'.mysql_error();}
				//	echo $qt;
					$rt = mysql_fetch_array($mt);			
					$amount	= $rt['amount'];
					$invoice_in_no	= $rt['invoice_in_no'];					
					$currency_id	= $rt['currency_id'];				
					$ryzyko = $rt['ryzyko'];					
					$note = $rt['note'];					

					$result .= '<input type="hidden" name="invoice_id" id="invoice_id" value="'.$invoice_id.'">							
							<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Edycja faktury:</b><small></td></tr>						
		  					<tr>				
						<td width="25%" align="center"><b>Nr faktury</b></td>	
						<td width="27%" align="center"><b>Kwota</b></td>
						<td width="58%" align="center"><b>Uwagi</b></td>

						</tr>
						<tr>
							<td align="right" valign="top"><input type="text" name="invoice_in_no" id="invoice_in_no" size="15" value="'.$invoice_in_no.'"></td>	
							<td align="right"><input type="text" name="amount" id="amount" value="'.print_currency($amount).'"  style="text-align: right;" size="15" maxlength="20"> '.wysw_currency_all('currency_id',$currency_id).'<br>
							<br><b>Aby usun±æ fakturê wpisz 0</b></td>							
							<td align="right"><textarea cols="50" rows="3" name="note" id="note">'.$note.'</textarea></td>
						</tr>
						<tr><td colspan="4" align="right">
								<input type="submit" name="reserv_add" onClick="return zapisz_fakture();" value="Zapisz">	
								&nbsp;&nbsp;&nbsp;<input type="submit" name="reserv_add" onClick="return anuluj_fakture();" value="Anuluj">	
						</td>
						</table>
						';
						
						
						$result .= '
					&nbsp;&nbsp;	
					
						</td></tr>';
				$result .= '</table><br>
				<script>
				function dodaj_fakture(){
						
						if (document.getElementById(\'invoice_in_no\').value == \'\'){
							alert(\'Proszê podaæ numer faktury.\');
							document.getElementById(\'invoice_in_no\').focus();
							return false;
						
						}else{
							 if (document.getElementById(\'amount\').value.replace(\',\',\'.\') > 0){
												
									document.getElementById(\'edit_form_action\').value=\'invoice_add\';	
									return true;
							}else{	alert(document.getElementById(\'amount\').value);
									alert(\'Proszê podaæ kwotê faktury.\');
									document.getElementById(\'amount\').focus();
									return false;
							}					
						}
						return false;		
				}
				</script>';
		
			
		}else{							
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Nowa faktura:</b><small></td></tr>						
		  				<tr>				
						<td width="25%" align="center"><b>Nr faktury</b></td>	
						<td width="25%" align="center"><b>Kwota</b></td>
						<td width="50%" align="center"><b>Uwagi</b></td>

						</tr>
						<tr>
							<td align="right"><input type="text" name="invoice_in_no" id="invoice_in_no" value="" size="15"></td>	
							<td align="right"><input type="text" name="amount" id="amount" value="'.print_currency(0).'"  style="text-align: right;" size="15" maxlength="20"> '.wysw_currency_all('currency_id','EUR').'</td>							
							<td align="right"><textarea cols="50" rows="3" name="note" id="note"></textarea></td>
						</tr>
						<tr><td colspan="4" align="right">
								<input type="submit" name="reserv_add" onClick="return dodaj_fakture();" value="Dodaj">	
								&nbsp;&nbsp;&nbsp;<input type="submit" name="reserv_add" onClick="return anuluj_fakture();" value="Anuluj">	
						</td>
						</table>';
				$result .= '
				<script>
				function dodaj_fakture(){
						
							
						if (document.getElementById(\'invoice_in_no\').value == \'\'){
							alert(\'Proszê podaæ numer faktury.\');
							document.getElementById(\'invoice_in_no\').focus();
							return false;
						
						}else{
							 if (document.getElementById(\'amount\').value.replace(\',\',\'.\') > 0){
												
									document.getElementById(\'edit_form_action\').value=\'invoice_add\';	
									return true;
							}else{	
									alert(\'Proszê podaæ kwotê faktury.\');
									document.getElementById(\'amount\').focus();
									return false;
							}					
						}
						return false;	
				}
				</script>';
		}
	}else{
			$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="20%" align="center"><b>Nr faktury</b></td>	
				<td width="15%" align="center"><b>Kwota</b></td>
				<td width="10%" align="center"><b>Waluta</td>
				<td width="30%" align="center"><b>Uwagi</td>
				<td width="15%" align="center"><b>Status</b></td>		
				<td width="10%" align="center"><b>U¿ytkownik</b></td>			
				
			   </tr >';
			

		 	$query = "SELECT coris_finances_invoices_in_forward.*,					
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=coris_finances_invoices_in_forward.ID_user ) As user
			FROM coris_finances_invoices_in_forward WHERE case_id='$case_id' 			 						
			ORDER BY ID";			 	
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				
			  $result .= '<tr>
			  	
				<td align="right"><b>'. ($row_r['invoice_in_no']) .'</b>&nbsp;</td>									
				<td align="right"><b>'. print_currency($row_r['amount'],2) .'</b></td>
						<td align="center"><b> '.($row_r['currency_id']).'</b></td>
				
					<td align="right" title="'.addslashes($row_r['note']).'">'. substr((stripslashes(($row_r['note']))),0,20).'&nbsp;</td>	
				<td align="center">'. ($row_r['status']==1 ? "<b>Wys³ane</b>\nData:".$row_r['status_date']."\nUser: ".getUserInitials($row_r['status_ID_user']) : '<b>Nie wys³ane</b>') .'</td>					
				<td align="center">'. ($row_r['user']) .'</td>					
			   </tr >';
			   
			}
			
	 		
		$result .= '</table><br>';		
	}
	
	$result .= '</form>';
	return $result;
	
}	

    function StrTrim($string, $length) {
        return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
    }


?>