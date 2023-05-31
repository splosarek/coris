<?php
include('lib/lib_cardif.php');

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
			
			
			$policy = getValue('policy');							
			$policy_series = getValue('policy_series');							
														
			$typ_umowy= getValue('typ_umowy');
			$wariant_ubezpieczenia= getValue('wariant_ubezpieczenia');

			
			$var2 = " policy='$policy',policy_series='$policy_series' ";			
			
			$var = " ID_typ_umowy='$typ_umowy', ID_wariant_ubezpieczenia='$wariant_ubezpieczenia' ";
/////////////////////			
			
			$qt = "SELECt case_id FROM coris_cardif_announce  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);			
			
			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO coris_cardif_announce SET case_id='$case_id', $var ";
								
			}else{
				$query = "UPDATE coris_cardif_announce SET $var  WHERE case_id='$case_id' LIMIT 1";				
			}						
			$query2 = "UPDATE coris_assistance_cases  SET $var2 WHERE case_id='$case_id' LIMIT 1 ";			
			
			
			
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

		
		$query2 = "SELECT * FROM coris_cardif_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		
if ($row_case_settings['client_id'] == 11086){
	$result .=  '<div style="width: 790px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  zgloszenie($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
	

	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 790px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  rezerwy($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
				
		
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 790px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw CARDIF</b></div>
	</div>
	';
	
}
	
	return $result;	
}

function zgloszenie($row,$row2,$row3){

	 
        
       $result='';	
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_zgloszenie" id="form_zgloszenie">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Zg³oszenie</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwa_zgloszenie'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_rezerwa_zgloszenie] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" onclick="return  validate();" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
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
	
	$result .= '
	<script language="JavaScript1.2" src="Scripts/js_cardif_announce.js"></script>
	<script language="JavaScript1.2">
		<!--
		function validate() {		
			if (document.getElementById(\'typ_umowy\').value == 0 ) {
				alert("Prosze wyraæ typ umowy");
				document.getElementById(\'typ_umowy\').focus();
				return false;
			}
			if (document.getElementById(\'wariant_ubezpieczenia\').value > 0 ) {
			}else{
				alert("Prosze wybraæ wariant umowy");
				document.getElementById(\'wariant_ubezpieczenia\').focus();
				return false;
			}			
			return true;
		}												
   
		//-->
		</script>';
		$result .= '<table cellpadding="5" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
					
				<tr bgcolor="#AAAAAA">	<td width="5%">&nbsp;</td>
					<td width="120" align="right">
					<b>Typ umowy:</b>	</td><td>';
					$result .= wysw_typy_umowy('typ_umowy',$row2['ID_typ_umowy'],0,'onChange="getWariantUmowy(this.value,\'wariant_ubezpieczenia\');"');
					$result .= '
								</td></tr>
			<tr bgcolor="#AAAAAA">							
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td> ';									
					 $result .= wysw_wariant_umowy('wariant_ubezpieczenia',$row2['ID_wariant_ubezpieczenia'],0,$row2['ID_typ_umowy']);	
							$result .= '
						</td>
					</tr>			
			  <tr>	
			  		<td width="5%">&nbsp;</td>			
				<td align="right" >
					<b>'. AS_CASADD_POL .'</b>: </td><td>
					<small><b>Seria: </b></small><input type="text" name="policy_series" id="policy_series" onchange="javascript:this.value=this.value.toUpperCase();" size="20" maxlength="20" value="'. $row3['policy_series'] .'">
					&nbsp; <small><b>Nr: </b></small><input type="text" id="policy"  name="policy" value="'. $row3['policy'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30">
				</td>	
				</tr>	
		</table><br>';					
	}else{		
		$result .= '<table cellpadding="5" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
					
				<tr bgcolor="#AAAAAA">	<td width="5%">&nbsp;</td>
					<td  align="right"><b>Typ umowy:</b></td><td>	';
					$result .= wysw_typy_umowy('typ_umowy',$row2['ID_typ_umowy'],1);
					$result .= '
								</td></tr>
			<tr bgcolor="#AAAAAA">							
					<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Wariant umowy:</b></td><td> ';									
					 $result .= wysw_wariant_umowy('wariant_ubezpieczenia',$row2['ID_wariant_ubezpieczenia'],1,$row2['ID_typ_umowy']);	
							$result .= '
						</td>
					</tr>			
			  <tr>				
			  <td width="5%">&nbsp;</td>
				<td align="right" >
					<b>'. AS_CASADD_POL .'</b>:</td><td> 
					<small><b>Seria: </b></small><input type="text" name="policy_series" id="policy_series" disabled size="20" maxlength="20" value="'. $row3['policy_series'] .'">
					&nbsp; <small><b>Nr: </b></small><input type="text" id="policy"  name="policy" value="'. $row3['policy'] .'"  size="30" maxlength="30" disabled>
				</td>	
			</tr>	
		</table><br>';
	}
	
	$result .= '</form>';
	return $result;	
}	



function rezerwy($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	
	$decision_id=getValue('decision_id');
	$result .= '<script language="JavaScript1.2" src="Scripts/js_cardif_announce.js"></script>
	<a name="rezerwy_rezerwy"></a>
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
				<input type="hidden" name=decision_id" id="decision_id" value="'.$decision_id.'">
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
				<td width="30%" align="center"><b>¦wiadczenie</b></td>	
				<td width="17%" align="center"><b>Suma ubezp.</b></td>
				<td width="17%" align="center"><b>Rezerwa</td>
				<td width="17%" align="center"><b>Wykonawca</td>
				<td width="15%" align="center"><b>Zmiana</b></td>		
		 </tr >';
			 	$query = "SELECT ccr.*,
			(SELECT nazwa  FROM coris_cardif_swiadczenia    WHERE coris_cardif_swiadczenia.ID=ccr.ID_swiadczenie  ) As swiadczenie,
			(SELECT short_name  FROM coris_contrahents   WHERE coris_contrahents.contrahent_id=cace.contrahent_id  ) As wykonawca,
			cace.amount,cace.contrahent_id  
				FROM coris_cardif_cases_reserve as ccr LEFT JOIN coris_assistance_cases_expenses as cace ON ccr.ID_expenses = cace.expense_id 			
			WHERE ccr.case_id = '$case_id'			
			AND (ccr.ID_expenses = 0 OR (ccr.ID_expenses > 0 AND cace.active=1 ))		
			ORDER BY ID";		
			// echo $query;
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				
			  $result .= '<tr>			  	
				<td ><b>'. ($row_r['swiadczenie']) .'</b>&nbsp;</td>	
								<td align="right"><b>'. print_currency($row_r['suma'],2) .' PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'.$row_r['wykonawca'] .'&nbsp;</b></td>
					<td align="center">'. ($row_r['ID_expenses'] > 0 ? '<a href="javascript:edycja_wykonawcy('.$row_r['ID_expenses'].',\''.$decision_id.'\');">edycja</a>' : '&nbsp;').'</td>				
			   </tr >';
			   
			}
			
		$result .= '</table><br>';		
		
		$edit_form_action = getValue('edit_form_action');
		
	
		

				
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Nowa rezerwa:</b><small></td></tr>								  				
						<tr><td colspan="4" align="right">
								<input type="button" value="Dodaj" style="font-weight: bold; " title="'. AS_CASD_MSG_DODWYK .'" onclick="window.open(\'AS_cases_details_expenses_position_add.php?case_id='.  $case_id .'&decision_id='.$decision_id.'&type_id='.  $row['type_id'] .'&tryb=cardif\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=400,left=\'+ (screen.availWidth - 400) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);">
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
									alert(\'Proszê podaæ kwotê rezerwy.\');
									document.getElementById(\'rezerwa\').focus();
									return false;
							}					
						}else{
							alert(\'Proszê wybraæ ryzyko.\');
							document.getElementById(\'id_ryzyko\').focus();
							return false;
						}		
				}
				</script>';
	
	}else{
			$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="30%" align="center"><b>¦wiadczenie</b></td>	
				<td width="17%" align="center"><b>Suma ubezp.</b></td>
				<td width="17%" align="center"><b>Rezerwa</td>
				<td width="17%" align="center"><b>Wykonawca</td>
				<td width="17%" align="center"><b>Data</b></td>	
				<td width="17%" align="center"><b>U¿ytkownik</b></td>		
				
			   </tr >';
		
				 	$query = "SELECT ccr.*,
			(SELECT nazwa  FROM coris_cardif_swiadczenia    WHERE coris_cardif_swiadczenia.ID=ccr.ID_swiadczenie  ) As swiadczenie,
			(SELECT short_name  FROM coris_contrahents   WHERE coris_contrahents.contrahent_id=cace.contrahent_id  ) As wykonawca,
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=ccr.ID_user ) As user,
			cace.amount,cace.contrahent_id  
			FROM coris_cardif_cases_reserve as ccr LEFT JOIN coris_assistance_cases_expenses as cace ON ccr.ID_expenses = cace.expense_id 			
			WHERE ccr.case_id = '$case_id'		
			AND (ccr.ID_expenses = 0 OR (ccr.ID_expenses > 0 AND cace.active=1 ))					
			ORDER BY ID";		
			 
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				
			  $result .= '<tr>			  	
				<td ><b>'. ($row_r['swiadczenie']) .'</b>&nbsp;</td>	
								<td align="right"><b>'. print_currency($row_r['suma'],2) .' PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'.$row_r['wykonawca'] .'&nbsp;</b></td>
				<td align="right">'. ($row_r['date']).'</td>	
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