<?php
include('lib/lib_vig.php');

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
			
			
			$program = getValue('program');							
			$var = " ID_program='$program' ";			
			
			
/////////////////////			
			
			$qt = "SELECt case_id FROM coris_vig_announce  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);			
			
			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO coris_vig_announce SET case_id='$case_id', $var ";
								
			}else{
				$query = "UPDATE coris_vig_announce SET $var  WHERE case_id='$case_id' LIMIT 1";				
			}						
						
			$mysql_result = mysql_query($query);					
			
			if ($mysql_result){
				//$message .= "Udpate OK, ".$query;
			}else{
				$message .= "<br>Update Error: ".$query."\n<br> ".mysql_error();				
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

		
		$query2 = "SELECT * FROM coris_vig_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		
if ($row_case_settings['client_id'] == 5 || $row_case_settings['client_id'] == 2306 || $row_case_settings['client_id'] == 14500 ){
	$result .=  '<div style="width: 790px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  zgloszenie($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
	

	$result .=  '<div style="clear:both;"></div>';
	/*$result .=  '<div style="width: 790px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  rezerwy($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	*/
	$result .=  '<div style="clear:both;"></div>';
				
		
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 790px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw VIG</b></div>
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
	
	<script language="JavaScript1.2">
		<!--
		function validate() {		
	/*		if (document.getElementById(\'typ_umowy\').value == 0 ) {
				alert("Prosze wyraæ typ umowy");
				document.getElementById(\'typ_umowy\').focus();
				return false;
			}
			if (document.getElementById(\'wariant_ubezpieczenia\').value > 0 ) {
			}else{
				alert("Prosze wybraæ wariant umowy");
				document.getElementById(\'wariant_ubezpieczenia\').focus();
				return false;
			}*/			
			return true;
		}												
   
		//-->
		</script>';
		$result .= '<table cellpadding="5" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
					
				<tr bgcolor="#AAAAAA">	<td width="5%">&nbsp;</td>
					<td width="120" align="right">
					<b>Program:</b>	</td><td>';
					$result .= VIGCase::wysw_program($row['client_id'],'program',$row2['ID_program'],0,'');
					$result .= '
								</td></tr>
		</table><br>';
					/*
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

					 */					
	}else{		
		$result .= '<table cellpadding="5" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
					
				<tr bgcolor="#AAAAAA">	<td width="5%">&nbsp;</td>
					<td  align="right"><b>Program:</b></td><td>	';
					$result .= VIGCase::wysw_program($row['client_id'],'program',$row2['ID_program'],1);
					$result .= '
								</td></tr>
			
		</table><br>';
	}
	/*
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
	 */
	$result .= '</form>';
	return $result;	
}	



function rezerwy($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	

	return $result;	
}
	

    function StrTrim($string, $length) {
        return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
    }


?>