<?php
//ubezpieczon_plec


function module_update(){			
	global  $pageName;
	$result ='';

	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');
	
	
	$check_js = '';
	$message = '';
	

	
	
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
		$result .=  sprawy($case_id,$row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
	

	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  rezerwy($case_id,$row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
		$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  wyplaty($case_id,$row_case_settings,$row_case_ann,$row_case);	
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




function sprawy($case_id,$row_case_settings,$row_case_ann,$row_case){		  
       $result='';	
	global $global_link,$change,$case_id;
	$result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Export sprawy</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['settings_status2'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
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
		
	
	}else{
		
		$result .= '<table cellpadding="5" cellspacing="0" border="1" align="center" width="550">
		  <tr><td width="250" align="right"><b>Status exportu</b></td><td width="300"> '.($row_case_ann['signal_status']==1 ? 'Export' : 'brak' ).'</td></tr>
		  <tr><td align="right"><b>Data ostatniego exportu</b></td><td> '.$row_case_ann['signal_export_date'].'&nbsp;</td></tr>
		  <tr><td align="right"><b>Signal potwierdzenie</b></td><td> '.($row_case_ann['signal_feedback']==1 ? 'Tak' : 'Nie').'&nbsp;</td></tr>
		  <tr><td align="right"><b>Data zaakceptowanego exportu</b></td><td> '.($row_case_ann['signal_feedback_date']).'&nbsp;</td></tr>
		  <tr><td align="right"><b>Signal numer</b></td><td> '.$row_case_ann['signal_number'].'&nbsp;</td></tr>
			</table>	
				';
		
		
		$query = "SELECT  csl.filename ,csld.* FROM coris_signal_log csl, coris_signal_log_detials csld WHERE  csld.ID_case  = '$case_id' AND csl.ID =  csld.ID_log AND csl.type='SZ' ";		
		$result .=  '<br><br><table cellpadding="1" cellspacing="0" border="1" align="center" >
		  <tr bgcolor="#BBBBBB">
				<td width="50" align="center"><b>Kod</b></td>	
				<td width="350" align="center"><b>Wiadmo¶æ</b></td>
				<td width="140" align="center"><b>Plik loga</b></td>	
				
				<td width="140" align="center"><b>Plik exportu</b></td>		
				<td width="50" align="center"><b>Linia</b></td>		
		</tr>		
				';

		$mr = mysql_query($query);
		
		while ($r = mysql_fetch_array($mr)){
				$result .= '<tr>';
					$result .= '<td align="center">'.$r['code'].'</td>';		
					$result .= '<td>';
						
							if ($r['message']==''){
								if ($r['code'] == "OK")
									$result .= 'Signal number: '.$r['signal_number'];
							}else{
								$result .= $r['message'];
							}
						
						$result .= '&nbsp;</td>';		
					$result .= '<td align="right"><a  target="_blank" href="../a_d_m/signal/export_signal/logs/'.$r['filename'].'">'.$r['filename'].'</a>&nbsp;</td>';												
					$result .= '<td align="right"><a  target="_blank" href="../a_d_m/signal/export_signal/'.(str_replace('.log','.txt',$r['filename'])).'">'.(str_replace('.log','.txt',$r['filename'])).'</a>&nbsp;</td>';		
					$result .= '<td align="right">'.$r['line_number'].'&nbsp;</td>';		
				$result .= '</tr>';
		
		}
		$result .= '</table><br>';
	}
	
	$result .= '</form>';
	return $result;
	
}	

function rezerwy($case_id,$row_case_settings,$row_case_ann,$row_case){		  
       $result='';	
	global $global_link,$change,$case_id;
	$result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Export rezerwy</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['settings_status2'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
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
		
	
	}else{
		
	
		
		
		$query = "SELECT  csl.filename ,csld.* FROM coris_signal_log csl, coris_signal_log_detials csld WHERE  csld.ID_case  = '$case_id' AND csl.ID =  csld.ID_log AND csl.type='RE' ";		
		$result .=  '<br><br><table cellpadding="1" cellspacing="0" border="1" align="center" >
		  <tr bgcolor="#BBBBBB">
				<td width="50" align="center"><b>Kod</b></td>	
				<td width="350" align="center"><b>Wiadmo¶æ</b></td>
				<td width="140" align="center"><b>Plik loga</b></td>	
				
				<td width="140" align="center"><b>Plik exportu</b></td>		
				<td width="50" align="center"><b>Linia</b></td>		
		</tr>		
				';

		$mr = mysql_query($query);
		
		while ($r = mysql_fetch_array($mr)){
				$result .= '<tr>';
					$result .= '<td align="center">'.$r['code'].'</td>';		
					$result .= '<td>';
						

								$result .= $r['message'];

						
						$result .= '&nbsp;</td>';		
					$result .= '<td align="right"><a target="_blank" href="../a_d_m/signal/export_signal/logs/'.$r['filename'].'">'.$r['filename'].'</a>&nbsp;</td>';												
					$result .= '<td align="right"><a  target="_blank" href="../a_d_m/signal/export_signal/'.(str_replace('.log','.txt',$r['filename'])).'">'.(str_replace('.log','.txt',$r['filename'])).'</a>&nbsp;</td>';		
					$result .= '<td align="right">'.$r['line_number'].'&nbsp;</td>';		
				$result .= '</tr>';
		
		}
		$result .= '</table><br>';
	}
	
	$result .= '</form>';
	return $result;
	
}	

function wyplaty($case_id,$row_case_settings,$row_case_ann,$row_case){		  
       $result='';	
	global $global_link,$change,$case_id;
	$result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Export wyplaty</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['settings_status2'])){
				$result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
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
		
	
	}else{
		
	
		
		
		$query = "SELECT  csl.filename ,csld.* FROM coris_signal_log csl, coris_signal_log_detials csld WHERE  csld.ID_case  = '$case_id' AND csl.ID =  csld.ID_log AND csl.type='WY' ";		
		$result .=  '<br><br><table cellpadding="1" cellspacing="0" border="1" align="center" >
		  <tr bgcolor="#BBBBBB">
				<td width="50" align="center"><b>Kod</b></td>	
				<td width="350" align="center"><b>Wiadmo¶æ</b></td>
				<td width="140" align="center"><b>Plik loga</b></td>	
				
				<td width="140" align="center"><b>Plik exportu</b></td>		
				<td width="50" align="center"><b>Linia</b></td>		
		</tr>		
				';

		$mr = mysql_query($query);
		
		while ($r = mysql_fetch_array($mr)){
				$result .= '<tr>';
					$result .= '<td align="center">'.$r['code'].'</td>';		
					$result .= '<td>';
						
						
								$result .= $r['message'];
						
						
						$result .= '&nbsp;</td>';		
					$result .= '<td align="right"><a  target="_blank" href="../a_d_m/signal/export_signal/logs/'.$r['filename'].'">'.$r['filename'].'</a>&nbsp;</td>';												
					$result .= '<td align="right"><a  target="_blank" href="../a_d_m/signal/export_signal/'.(str_replace('.log','.txt',$r['filename'])).'">'.(str_replace('.log','.txt',$r['filename'])).'</a>&nbsp;</td>';		
					$result .= '<td align="right">'.$r['line_number'].'&nbsp;</td>';		
				$result .= '</tr>';
		
		}
		$result .= '</table><br>';
	}
	
	$result .= '</form>';
	return $result;
	
}	

?>