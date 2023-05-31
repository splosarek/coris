<?php
include('lib/lib_nhc.php');

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
														
			$policy_type= getValue('policy_type');
			$main_cause= getValue('main_cause');
			$person= getValue('person');
			$country_inc= getValue('country_inc');
			$diagn_code= getValue('diagn_code');
			

			
			$var2 = " policy='$policy' ";			
			
			
			$person = ($person>0) ? $person : '0';
			
			$pin = getValue('pin');							
			$pax_email = getValue('pax_email');	
			
			$paxname = getValue('paxname');			
			$paxsurname = getValue('paxsurname');						
			$paxsex = getValue('paxsex');						
			$paxDob = getValue('paxDob_y').'-'.getValue('paxDob_m').'-'.getValue('paxDob_d');
						
			
			$var2 .= " , paxname='$paxname',paxsurname='$paxsurname',paxsex='$paxsex',paxdob='$paxDob',pax_email='$pax_email' ";
			
			
			$paxaddress = getValue('paxaddress');			
			$paxpost = getValue('paxpost_1').'-'.getValue('paxpost_2');			
			$paxcity= getValue('paxcity');
			$paxcountry= getValue('paxcountry');
			$paxphone= getValue('paxphone');									
			$circumstances= getValue('circumstances');
			
			$var3 = " paxaddress='$paxaddress',paxpost='$paxpost',paxcountry='$paxcountry',paxcity='$paxcity',paxphone='$paxphone',circumstances='$circumstances' ";			
			
			
			$var = " ID_policy_type='$policy_type', ID_main_cause='$main_cause',person=$person,country_inc='$country_inc',diagn_code='$diagn_code',pin='$pin' ";
/////////////////////			
			
			$qt = "SELECt case_id FROM coris_nhc_announce  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);			
			
			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO coris_nhc_announce SET case_id='$case_id', $var ";
								
			}else{
				$query = "UPDATE coris_nhc_announce SET $var  WHERE case_id='$case_id' LIMIT 1";				
			}						
			
			$query2 = "UPDATE coris_assistance_cases  SET $var2 WHERE case_id='$case_id' LIMIT 1 ";			
			
			$query3 = "UPDATE coris_assistance_cases_details   SET $var3 WHERE case_id='$case_id' LIMIT 1 ";
						
			$mysql_result = mysql_query($query);
									
			if ($mysql_result){				
			}else{
				$message .= "<br>Update Error: ".$query."\n<br> ".mysql_error();				
			}		
			$mysql_result2 = mysql_query($query2);

			if ($mysql_result2){				
			}else{
				$message .= "<br>Update 2 Error: ".$query2."\n<br> ".mysql_error();				
			}		
			
			$mysql_result3 = mysql_query($query3);
			if ($mysql_result3){				
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
					$id_ryzyko = getValue('rez_id_ryzyko');
				$rez_rezerwa_id = getValue('rez_rezerwa_id');								
				$rez_rezerwa = getValue('rez_rezerwa');												
				$rezerwacurrency_id = getValue('rezerwacurrency_id');												
				$rez_rezerwa = str_replace(',','.',trim($rez_rezerwa));		
						
				$query = "INSERT INTO coris_nhc_cases_reserve  SET case_id ='$case_id',ID_swiadczenie ='$id_ryzyko',rezerwa ='$rez_rezerwa',currency_id='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(),status=1 ";
				$mysql_result = mysql_query($query);
				$poz=0;
				if ($mysql_result){
					//$message .= "Udpate OK";
					$poz = mysql_insert_id();
				}else{
					$message .= "Update Error: ".$query."\n<br> ".mysql_error();				
				}		
				
				if ($poz>0){// insert history					 							
					$query  = "INSERT INTO coris_nhc_reserve_history   SET  ID_reserve  ='$poz',reserve_new  ='$rez_rezerwa',currency_id ='$rezerwacurrency_id',ID_user='".$_SESSION['user_id']."',date=now()";	
					$mysql_result = mysql_query($query);					
				}
				
			}	
			
			if ($edit_form_action=='risk_edit_save'){
				
				$id_ryzyko = getValue('rez_id_ryzyko');
				$rez_rezerwa_id = getValue('rez_rezerwa_id');								
				$rez_rezerwa = getValue('rez_rezerwa');												
				$rezerwacurrency_id = getValue('rezerwacurrency_id');												
				$rez_rezerwa = str_replace(',','.',trim($rez_rezerwa));		
				
				$key=0;
				if ($rez_rezerwa_id>0){													
					$queryu = "UPDATE coris_nhc_cases_reserve  SET case_id ='$case_id',ID_swiadczenie ='$id_ryzyko',rezerwa ='$rez_rezerwa', ID_user='".$_SESSION['user_id']."',date=now(),status=1  WHERE ID='$rez_rezerwa_id' LIMIT 1";
					$mysql_result = mysql_query($queryu);			
					if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();								
					$key = $rez_rezerwa_id;
				}else{
					
					$queryu = "INSERT INTO coris_nhc_cases_reserve  SET case_id ='$case_id',ID_swiadczenie ='$id_ryzyko',rezerwa ='$rez_rezerwa',currency_id='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(),status=1 ";
					$mysql_result = mysql_query($queryu);				
					if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();								
					$key = mysql_insert_id();
				}
				
				$query  = "INSERT INTO coris_nhc_reserve_history   SET  ID_reserve  ='$key',reserve_new  ='$rez_rezerwa',currency_id ='$rezerwacurrency_id',ID_user='".$_SESSION['user_id']."',date=now()";		
				$mysql_result = mysql_query($query);
				if (!$mysql_result)	$message .= "Update Error: ".$query."\n<br> ".mysql_error();							
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

		
		$query2 = "SELECT * FROM coris_nhc_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		
if ($row_case_settings['client_id'] == 11170){
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  zgloszenie($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
	

	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  rezerwy($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
				
		
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 840px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw NHC</b></div>
	</div>
	';
	
}
	
	return $result;	
}

function zgloszenie($row,$row2,$row3){

	 
        
       $result='';	              
	global $global_link,$change;
	
	
	$eventDate = array("","","");
	if ($row3['eventdate'] != "0000-00-00")
		$eventDate = split("-", $row3['eventdate']);

	
	$paxDob = array("","","");
    if ($row3['paxdob'] != "0000-00-00")
    	$paxDob = split("-", $row3['paxdob']);  
             
	$paxpost=explode('-',$row3['paxpost']);
	
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
	<script language="JavaScript1.2" src="Scripts/js_nhc_announce.js"></script>
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
		$result .= '<table cellpadding="5" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
					
				<tr bgcolor="#AAAAAA">
					<td width="240" align="right">
					<b>'.AS_NHC_POLICY_TYPE.':</b>	</td><td>';
					$result .= NHCCase::wysw_policy_type('policy_type',$row2['ID_policy_type'],0,'onChange="NHCgetMainCases(this.value,\'main_cause\');"');
					$result .= '
								</td></tr>
			<tr bgcolor="#AAAAAA">							
					
					<td  align="right">
					<b>'.AS_NHC_MAIN_CAUSES.':</b></td><td> ';									
					 $result .= NHCCase::wysw_main_causes('main_cause',$row2['ID_main_cause'],0,$row2['ID_policy_type'],'');;	
							$result .= '
						</td>
					</tr>						
			  <tr bgcolor="#CCCCCC">				  					
				<td align="right" >
					<b>'. AS_NHC_POLICY_IDENT .'</b>: </td><td>
					<input type="text" id="policy"  name="policy" value="'. $row3['policy'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="10" maxlength="8">
				</td>	
				</tr>
					  <tr  bgcolor="#CCCCCC">	
						
				<td align="right" >
					<b>'. AS_NHC_PERSON_CAT_REF .'</b>: </td><td>
					<input type="text" id="person"  name="person" value="'. $row2['person'] .'" size="3" maxlength="2"> [0-99]
				</td>		</tr>
			
					  <tr  bgcolor="#CCCCCC">	
			  					
				<td align="right" >
					<b>'. AS_NHC_EPAD_C_N .'</b>: </td><td>
					<input type="text" id="epad"  name="epad" value="'. $row2['epad'] .'" size="11" maxlength="10" disabled>  (auto)
				</td>	
				</tr>
				
				

			  <tr bgcolor="#AAAAAA">							
					
					<td  align="right">
					<b>'.AS_NHC_INJURY_DATA.':</b></td><td> ';									
					$result .= '<input type="text" id="eventDate_d" name="eventDate_d" value="'. $eventDate[2] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zdarzenie_info\'));">
														<input type="text" id="eventDate_m" name="eventDate_m" value="'.  $eventDate[1] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zdarzenie_info\'));" onkeydown="remove(this,document.getElementById(\'form_zdarzenie_info\'));">
														<input type="text" id="eventDate_y" name="eventDate_y" value="'.  $eventDate[0] .'" size="4" maxlength="4" onkeydown="remove(this,document.getElementById(\'form_zdarzenie_info\'));">
														<a href="javascript:void(0)" onclick="newWindowCal(\'eventDate\')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>';
							$result .= '
						</td>
					</tr>
<tr bgcolor="#AAAAAA">												
					<td  align="right">
					<b>'.AS_NHC_NHC_COUNTRY.':</b></td><td> ';									
					 $result .= NHCCase::wysw_country_nhc('country_inc',$row2['country_inc'],0,'');;	
							$result .= '
						</td>
					</tr>						 								
<tr bgcolor="#AAAAAA">							
					<td  align="right">
					<b>'.AS_NHC_DIAGN_C.':</b></td><td> ';									
					 $result .= NHCCase::wysw_diagn_code('diagn_code',$row2['diagn_code'],0,'');;	
							$result .= '
						</td>
					</tr>			
<tr bgcolor="#AAAAAA">							
					<td  align="right">
					<b>'.AS_NHC_CLAIM_DESC.':</b></td><td> ';									
					 $result .= '<textarea name="circumstances" cols="80" rows="5" style="font-family: Verdana; font-size: 8pt;">'.$row3['circumstances'].'</textarea>';	
							$result .= '
						</td>
					</tr>			
			  <tr>	
		</table><br>
		
		
		';
		$result .= '
	<table cellpadding="10" cellspacing="0" border="0" align="center" width=95%>
	
	<tr bgcolor="#AAAAAA"><td width="100"><b>'.AS_TITLE_INSURED.'</b></td>
	<td >
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="paxSurname" name="paxsurname" style="font: bold;" value="'. $row['paxsurname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="50">
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="paxName"  name="paxname" style="font: bold;" value="'. $row['paxname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="28" maxlength="25">
				&nbsp;&nbsp;&nbsp;<small><b>'.AS_TITLE_PLEC.':</b></small>&nbsp;'.getPlec('paxsex',$row['paxsex']).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input tabindex="14" type="text" id="paxDob_d" name="paxDob_d" value="'. $paxDob[2] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));">
					<input tabindex="15" type="text" id="paxDob_m" name="paxDob_m" value="'. $paxDob[1] .'" size="1" maxlength="2" onkeyup="move(this,document.getElementById(\'form_zgloszenie\'));" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
					<input tabindex="16" type="text" id="paxDob_y" name="paxDob_y" value="'. $paxDob[0] .'" size="4" maxlength="4" onkeydown="remove(this,document.getElementById(\'form_zgloszenie\'));">
							
                            
					<a href="javascript:void(0)" onclick="newWindowCal(\'paxDob\')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PIN</b></small>: <input type="text" id="pin" name="pin"  value="'. $row2['pin'] .'"  size="15" maxlength="14">
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>'.ADDRESS.'</b></small></td><td>
				  <input type="text" id="paxaddress" name="paxaddress" value="'.$row3['paxaddress'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" id="paxpost_1" name="paxpost_1" value="'. $paxpost[0] .'" size="1" maxlength="2" onKeyUp="move(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;<input type="text" id="paxpost_2" name="paxpost_2" value="'. $paxpost[1] .'" size="2" maxlength="3" onKeyDown="remove(this,document.getElementById(\'form_zgloszenie\'));">&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" id="paxcity" name="paxcity" value="'. $row3['paxcity'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  NHCCase::wysw_country( 'paxcountry',$row3['paxcountry']);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" id="paxphone" name="paxphone" value="'.$row3['paxphone'] .'" size="20" maxlength="30">
                    	   &nbsp;&nbsp;
                        	<small><b>'.EMAIL.': </b></small>&nbsp;
                        	<input type="text" id="pax_email" name="pax_email" value="'.$row3['pax_email'] .'" size="25" maxlength="50">
                        	 </td>
                        </tr>
                
			</table>			
	</td></tr>	
	
	</table>';
				
							
	}else{
		
		$result .= '<table cellpadding="5" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
					
					<tr bgcolor="#AAAAAA">
					<td width="240" align="right">
					<b>'.AS_NHC_POLICY_TYPE.':</b>	</td><td>';
					$result .= NHCCase::wysw_policy_type('policy_type',$row2['ID_policy_type'],1,'onChange="NHCgetMainCases(this.value,\'main_cause\');"');
					$result .= '</td></tr>
			<tr bgcolor="#AAAAAA">												
					<td  align="right">
					<b>'.AS_NHC_MAIN_CAUSES.':</b></td><td> ';									
					 $result .= NHCCase::wysw_main_causes('main_cause',$row2['ID_main_cause'],1,$row2['ID_policy_type'],'');;	
							$result .= '</td>
			</tr>						
			 <tr bgcolor="#CCCCCC">				  					
				<td align="right" >
					<b>'. AS_NHC_POLICY_IDENT .'</b>: </td><td>
					<input type="text" id="policy"  name="policy" value="'. $row3['policy'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="10" maxlength="8" disabled></td>	
				</tr>
				<tr  bgcolor="#CCCCCC">						
					<td align="right" >
						<b>'. AS_NHC_PERSON_CAT_REF .'</b>: </td><td>
						<input type="text" id="person"  name="person" value="'. $row2['person'] .'" size="3" maxlength="2" disabled>
					</td>		
				</tr>			
				<tr  bgcolor="#CCCCCC">				  				
					<td align="right" >
						<b>'. AS_NHC_EPAD_C_N .'</b>: </td><td>
						<input type="text" id="epad"  name="epad" value="'. $row2['epad'] .'" size="11" maxlength="10" disabled>  (auto)
					</td>	
				</tr>							
			  <tr bgcolor="#AAAAAA">												
					<td  align="right">
					<b>'.AS_NHC_INJURY_DATA.':</b></td><td> ';									
				$result .= '<input type="text" id="eventDate_d" name="eventDate_d" value="'. $eventDate[2] .'" size="1" maxlength="2" disabled>
					<input type="text" id="eventDate_m" name="eventDate_m" value="'.  $eventDate[1] .'" size="1" maxlength="2" disabled>
					<input type="text" id="eventDate_y" name="eventDate_y" value="'.  $eventDate[0] .'" size="4" maxlength="4" disabled>';
							$result .= '
						</td>
				</tr>
				<tr bgcolor="#AAAAAA">												
					<td  align="right">
					<b>'.AS_NHC_NHC_COUNTRY.':</b></td><td> ';									
					 $result .= NHCCase::wysw_country_nhc('country_inc',$row2['country_inc'],1,'');;	
							$result .= '</td>
				</tr>						 								
				<tr bgcolor="#AAAAAA">							
					<td  align="right">
					<b>'.AS_NHC_DIAGN_C.':</b></td><td> ';									
					 $result .= NHCCase::wysw_diagn_code('diagn_code',$row2['diagn_code'],1,'');;	
							$result .= '</td>
				</tr>			
				<tr bgcolor="#AAAAAA">							
					<td  align="right">
					<b>'.AS_NHC_CLAIM_DESC.':</b></td><td> ';									
					 $result .= '<textarea name="circumstances" cols="80" rows="5" style="font-family: Verdana; font-size: 8pt;" disabled>'.$row3['circumstances'].'</textarea>';	
							$result .= '</td>
				</tr>						  
		</table><br>';
							
		$result  .='		<table cellpadding="1" cellspacing="0" border="0" align="center" width=95%>
		<tr bgcolor="#AAAAAA"><td width="100"><b>'.AS_TITLE_INSURED.'</b></td>
	<td >
			<table cellpadding="1" cellspacing="0" border="1" align="center" width=100%>
			  <tr>				
				<td width="70" align="right"><small><b>'. SURNAME .'</b></small></td><td><input type="text" id="paxSurname" name="paxsurname" style="font: bold;" value="'. $row['paxsurname'] .'"  size="28" maxlength="50" disabled>
				&nbsp;&nbsp;<small><b>'. NAME .'</b></small>&nbsp;<input type="text" id="paxName"  name="paxname" style="font: bold;" value="'. $row['paxname'] .'" size="28" maxlength="25" disabled>
				&nbsp;&nbsp;&nbsp;<small><b>'.AS_TITLE_PLEC.':</b></small>&nbsp;'.getPlec('paxsex',$row['paxsex'],1).'
				</td><tr>
			<tr><td align="right"><small><b>'. AS_CASD_UR .'</b></small></td><td>
&nbsp;
					<input type="text" id="paxDob_d" name="paxDob_d" value="'. $paxDob[2] .'" size="1" disabled>
					<input type="text" id="paxDob_m" name="paxDob_m" value="'. $paxDob[1] .'" size="1" disabled>
					<input type="text" id="paxDob_y" name="paxDob_y" value="'. $paxDob[0] .'" size="4" disabled>					
					&nbsp;&nbsp;&nbsp;&nbsp;<small><b>PIN</b></small>: <input type="text" id="pin" name="pin"  value="'. $row2['pin'] .'"  size="15" maxlength="14" disabled>
				</td>
				</tr>
				<tr>
				<td align="right"><small><b>'.ADDRESS.'</b></small></td><td>
				  <input type="text" name="paxaddress" value="'.$row3['paxaddress'] .'" size="30" maxlength="50" disabled>&nbsp;&nbsp;&nbsp;
				  <small><b>'. POST .': </b></small>&nbsp;                    
                        <input type="text" name="paxpost_1" value="'. $paxpost[0] .'" size="1" maxlength="2" disabled>&nbsp;<input type="text" name="paxpost_2" value="'. $paxpost[1] .'" size="2" maxlength="3" disabled>&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="paxcity" value="'. $row3['paxcity'] .'" size="25" maxlength="25" disabled>
                       </td></tr><tr>
                        <td align="right">
                        <small><b>'. COUNTRY .': </b></small></td><td>';						
									$result .=  NHCCase::wysw_country( 'paxcountry',$row3['paxcountry'],1);
				$result .= '</td>
				</tr>
				<tr>
                        <td align="right">
                        	<small><b>'.PHONE.': </b></small></td><td>
                        	<input type="text" name="paxphone" value="'.$row3['paxphone'] .'" size="20" maxlength="30" disabled>
                    	   &nbsp;&nbsp;
                        	<small><b>'.EMAIL.': </b></small>&nbsp;
                        	<input type="text" name="pax_email" value="'.$row3['pax_email'] .'" size="25" maxlength="50" disabled>
                        	 </td>
                        </tr>                                  
			</table>		
	</td></tr>
	</table>	';					
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
		  
		  		<td width="30%" align="center"><b>Ryzyko</b></td>					
				<td width="17%" align="center"><b>Rezerwa</b></td>				
				<td width="17%" align="center"><b>Data</b></td>	
				<td width="17%" align="center"><b>U¿ytkownik</b></td>													
				<td width="15%" align="center"><b>Zmiana</b></td>		
		 </tr >';
			 	$query = "SELECT ccr.*,
			(SELECT name_pl   FROM coris_nhc_reserve     WHERE coris_nhc_reserve.ID=ccr.ID_swiadczenie  ) As swiadczenie,		
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=ccr.ID_user ) As user	
			
			FROM coris_nhc_cases_reserve ccr
			WHERE ccr.case_id = '$case_id'	";			 	
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				//$lista[] = $row_r['ID_ryzyko'];
			  $result .= '<tr>
			  	
				<td ><b>'. ($row_r['swiadczenie']) .'</b>&nbsp;</td>									
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
	<td align="right">'. ($row_r['date']).'</td>	
				<td align="center">'. ($row_r['user']) .'</td>			
				<td align="center"><a href="javascript:edycja_ryzyka('.$row_r['ID'].','.$row_r['ID_swiadczenie'].');">edycja</a></td>				
			   </tr >';
			   
			}
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
				
					$qt = "SELECT ccr.*,
			(SELECT name_pl   FROM coris_nhc_reserve     WHERE coris_nhc_reserve.ID=ccr.ID_swiadczenie  ) As swiadczenie,		
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=ccr.ID_user ) As user	
			
			FROM coris_nhc_cases_reserve ccr WHERE ID='$reserve_id' LIMIT 1";
					$mt = mysql_query($qt);
					if (!$mt) {echo "Error q: ".$qt.'<br><br>'.mysql_error();}
				//	echo $qt;
					$rt = mysql_fetch_array($mt);			
					$reserve	= $rt['rezerwa'];
				
					$ryzyko = $rt['swiadczenie'];					
				
				
				$result .= '<input type="hidden" name="rez_rezerwa_id" id="rez_rezerwa_id" value="'.$reserve_id.'">
							<input type="hidden" name="rez_id_ryzyko" id="rez_id_ryzyko" value="'.$risk_id.'">
							<table cellpadding="4" cellspacing="0" border="1" align="center" width="90	%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Edycja rezerwy:</b><small></td></tr>						
		  				<tr>				
						<td width="30%" align="center"><b>Ryzyko</b></td>							
						<td width="40%" align="center"><b>Rezerwa</b></td>
						</tr>
						<tr>
							<td ><b>'. $ryzyko .'</b></td>	
							<td align="right"><input type="text" name="rez_rezerwa" id="rez_rezerwa" value="'.print_currency($reserve).'"  style="text-align: right;" size="15" maxlength="20"> '.$rt['currency_id'].'<input type="hidden" name="rezerwacurrency_id" value="'.$rt['currency_id'].'"></td>														
						</tr>
						<tr><td colspan="2" align="right">
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
						
						if (document.getElementById(\'rez_id_ryzyko\').value > 0){
							if (document.getElementById(\'rez_rezerwa\').value.replace(\',\',\'.\') > 0){
												
									document.getElementById(\'edit_form_action\').value=\'reservere_add\';	
									return true;
							}else{	
									alert(\'Proszê podaæ kwotê rezerwy.\');
									document.getElementById(\'rez_rezerwa\').focus();
									return false;
							}					
						}else{
							alert(\'Proszê wybraæ ryzyko.\');
							document.getElementById(\'rez_id_ryzyko\').focus();
							return false;
						}		
				}
				</script>';
		
			
		}else{
		

				
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Nowa rezerwa:</b><small></td></tr>						
		  				<tr>				
						<td width="45%" align="center"><b>Ryzyko</b></td>							
						<td width="25%" align="center"><b>Rezerwa</td>
						<td width="5%" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="right"> '.NHCCase::wysw_rezerwy('rez_id_ryzyko',0,0,'class="required"') .'</td>							
							<td align="right"><input type="text" name="rez_rezerwa" id="rez_rezerwa" value="'.print_currency(0).'"  style="text-align: right;" size="15" maxlength="20"> '.wysw_currency('rezerwacurrency_id','PLN').'</b></td>
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
						
						if (document.getElementById(\'rez_id_ryzyko\').value > 0){
							if (document.getElementById(\'rez_rezerwa\').value.replace(\',\',\'.\') > 0){
												
									document.getElementById(\'edit_form_action\').value=\'reservere_add\';	
									return true;
							}else{	
									alert(\'Proszê podaæ kwotê rezerwy.\');
									document.getElementById(\'rez_rezerwa\').focus();
									return false;
							}					
						}else{
							alert(\'Proszê wybraæ ryzyko.\');
							document.getElementById(\'rez_id_ryzyko\').focus();
							return false;
						}		
				}
				</script>';
		}
	}else{
			$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="30%" align="center"><b>Ryzyko</b></td>					
				<td width="17%" align="center"><b>Rezerwa</b></td>				
				<td width="17%" align="center"><b>Data</b></td>	
				<td width="17%" align="center"><b>U¿ytkownik</b></td>						
			   </tr >';
			
	 	$query = "SELECT ccr.*,
			(SELECT name_pl   FROM coris_nhc_reserve     WHERE coris_nhc_reserve.ID=ccr.ID_swiadczenie  ) As swiadczenie,		
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=ccr.ID_user ) As user	
			
			FROM coris_nhc_cases_reserve ccr
			WHERE ccr.case_id = '$case_id'	
			";						 	
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				//$lista[] = $row_r['ID_ryzyko'];
			  $result .= '<tr>
			  	
				<td ><b>'. ($row_r['swiadczenie']) .'</b>&nbsp;</td>	
								
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>				
					<td align="right">'. ($row_r['date']).'</td>	
				<td align="center">'. ($row_r['user']) .'</td>					
			   </tr >';
			   
			}
				 
		$result .= '</table><br>';		
	}
	
	$result .= '</form>';
	return $result;
	
}	

/*
function rezerwy($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
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
			ORDER BY ID";		
			 
			$mysql_result = mysql_query($query);
			$lista = array();
			while ($row_r=mysql_fetch_array($mysql_result)){
				
			  $result .= '<tr>			  	
				<td ><b>'. ($row_r['swiadczenie']) .'</b>&nbsp;</td>	
								<td align="right"><b>'. print_currency($row_r['suma'],2) .' PLN</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'.$row_r['wykonawca'] .'&nbsp;</b></td>
				<td align="center">'. ($row_r['ID_expenses'] > 0 ? '<a href="javascript:edycja_wykonawcy('.$row_r['ID_expenses'].');">edycja</a>' : '&nbsp;').'</td>				
			   </tr >';
			   
			}
			
		$result .= '</table><br>';		
		
		$edit_form_action = getValue('edit_form_action');
		
	
		

				
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Nowa rezerwa:</b><small></td></tr>								  				
						<tr><td colspan="4" align="right">
								<input type="button" value="Dodaj" style="font-weight: bold; " title="'. AS_CASD_MSG_DODWYK .'" onclick="window.open(\'AS_cases_details_expenses_position_add.php?case_id='.  $case_id .'&type_id='.  $row['type_id'] .'&tryb=cardif\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=400,left=\'+ (screen.availWidth - 400) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);">
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
*/

?>