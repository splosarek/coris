<?php



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
	global $case_id;
	$result = '';
	
		$query = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention , attention2 FROM coris_assistance_cases WHERE case_id =  '".$case_id."'";;

		$mysql_result = mysql_query($query);
		$row_case_settings = mysql_fetch_array($mysql_result);			


	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
//		$result .=  dokumenty($row_case_settings,0);	
		$result .= init_disp();
		$result .=  '</div>';	


			$result .=  '<div style="clear:both;"></div>';
	return $result;	
}


function init_disp(){
	global $case_id;
$result ='
<script>
   function edycja_alert(opcja) {
			        if (opcja > 0 ) {			        		
			        			document.getElementById(\'form_action\').value="alert_edit";
			        			document.getElementById(\'action_id\').value=opcja;
			        			document.getElementById(\'form_alert_edit\').submit();			        		
			        }
	}					      			    
</script>
							<form method="POST" name="form_alert_edit" id="form_alert_edit">
							<input type="hidden" name="form_action" id="form_action" value="">
							<input type="hidden" name="action_id" id="action_id" value="">							
							</form>
							
		
		
<table cellpadding="2" cellspacing="0" border="0" width="100%" height="89%" bgcolor="#cccccc" >
                        <tr>
                            <td valign="top">
                                    <table cellpadding="2" cellspacing="2" border="0" bgcolor="#cccccc" width="100%" height="100%">
                                        <tr height="20" bgcolor="#eeeeee">
                                            <td align="right" colspan="2" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                                <font color="#6699cc"><small>'. NOTIFICATION .'&nbsp;</small></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" valign="top">';



				
	$form_action = getValue('form_action');
	$action_id = getValue('action_id');

  if ( $form_action == 'alert_edit' && $action_id > 0 ){
				$result .= alert_edit($action_id,$case_id,$row,$tryb);					
  }else{  	
  		if ( $form_action == 'form_alert_save' && $action_id > 0 ){
			$tmp = zapisz_alert($action_id,$case_id);						
		}

		$result .= lista_alertow($case_id);
  }		
  
                                            $result .= '</td>
                                        </tr>
                                        <tr height="20" bgcolor="#eeeeee">
                                            <td width="5%" align="center" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid">
                                                <input style="width: 20px;" type="button" value="+" onclick="window.open(\'AS_cases_details_todo_add.php?case_id='.$case_id.'\',\'new_todo\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=320,height=230,left=\'+ (screen.availWidth - 300) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 225) / 2);" title="'.AS_CASD_DODZAD .'"> 
                                            </td>
                                            <td width="95%" align="right" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                                <font color="#6699cc"><small>'. AS_CASD_BIEZ .'&nbsp;</small></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" valign="top">
                                                <iframe name="todoframe2" width="100%" height="288" frameborder="0" src="AS_cases_details_todo_frame2.php?case_id='.$case_id.'" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-top: #6699cc 1px solid;"></iframe>
                                            </td>
                                        </tr>

                                    </table>
                            </td>
                        </tr>
                    </table>';	


return $result;
}


function lista_alertow($case_id){
	  $result = '<table cellpadding="0" cellspacing="1" border="0" width="450" align="center">
			                    <tr><td colspan="2" align="center"><b>ALERTY</b></td></tr>
			                  		<tr  align="center">														
							<td ><font color="#6699cc">'. AS_CASADD_KLIENT .'</font></td>							
							<td width="110"><font color="#6699cc">'. DATA .'</font></td>
						</tr>
						<tr height="5">
							<td colspan="5"></td>
						</td>  ';
		$query = "SELECT * FROM coris_assistance_cases_alerts WHERE case_id='$case_id' order by alert_id DESC ";
					$mr = mysql_query($query);
					while ( $r = mysql_fetch_array($mr) ) {
							$styl='';
    						if ($r['new']==1){    							
					    		$styl='font-weight:bold';
					    	}
							$result .= '<tr bgcolor="lightyellow"							
							style="'.$styl.';border-top: #ffffe0 1px solid; border-bottom: #ffffe0 1px solid; cursor:pointer;" onclick="edycja_alert('.$r['alert_id'].')"><td style="color:blue;padding:5px">'.$r['interaction_name'].'&nbsp;</td><td	width="110"	align="right">'. $st1;

							        if (substr($r['date'],0,10) == date("Y-m-d")) {
							            $result .= "<font color=\"blue\">".AS_CASD_DZIS." ". substr($r['date'],11, 5) . "</font>";
							        } else if (substr($r['date'],0,10) == date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")))) {
							            $result .= "<font color=\"darkblue\">".AS_CASD_WCZ." ". substr($r['date'],11, 5) . "</font>";
							        } else {
							            $result .= substr($r['date'],0, 16);
							        }

							$result .=  $st2.'</td></tr>';
							$result .= '<tr><td colspan="2" bgcolor="lightyellow" align="left"><small>'. nl2br($r['note']) .'</td></tr>';					
					}						
			$result .= '
			</table>';
			
		return $result;	
}

function  alert_edit($id,$case_id,$row,$tryb){
	
	$result = '';
	$raport = '';
	$alert_id = $id;
	//$alert_id = getValue('alert_id');

	$row=null;
if ($alert_id>0){
	$query = "SELECT * FROM coris_assistance_cases_alerts  WHERE alert_id='$alert_id' ";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)==0){
		echo "Error: ".$query;
		return "Brak notatatki";
	}else{
		$row=mysql_fetch_array($mysql_result);
	}
	
}else{
	echo 'B³±d wykonania: $alert_id=0';
	echo "<br>ref. ".$_SERVER['REfferrer'];
	return '';
}
	$case_id = $row['case_id'];
	$alert_type = $row['type_id'];
	$direction = $row['direction'];
	
	$interaction_name = ($alert_type == 4) ? "" : $_POST['interaction_name'];
	$interaction_contact = ($alert_type == 4) ? "" : $_POST['interaction_contact'];

    $query_case = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention FROM coris_assistance_cases WHERE case_id = '$case_id' ";	
	
	 if ($mr_result = mysql_query($query_case)){
    	if (mysql_num_rows($mr_result)==0)	die('brak sprawy');
        $row_case = mysql_fetch_array($mr_result);
    }else
        die(mysql_error());

    if ($result2 = mysql_query($query)){
    	if (mysql_num_rows($result2)==0)	die('brak sprawy');
        $row = mysql_fetch_array($result2);
    }else
        die(mysql_error());
        
        
  
             $c_user = "External remote user";                                                        	
  

        $result .= '
<script>
function alert_obrobiony(){
	if (confirm(\'Czy napewno zaznaczyæ alert jako obrobiony?\')){
				document.getElementById(\'form_action\').value= \'form_alert_save\';
					document.getElementById(\'action_id\').value=\''.$alert_id.'\';
				document.getElementById(\'form_alert_edit\').submit();				
	}else{
		return false;
	}
}			
</script>

	   
		<form  method="post" action="AS_cases_details.php?case_id='.$case_id.'&mod=todos">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                <tr><td colspan="2" align="center" style="padding-top:20px;font-size:14px"><b>Alert </b></td></tr>
	                                                <tr><td align="right" colspan=2><small><font color="#6699cc" title="'. AS_CASD_TXTCOFNZM .'" style="cursor: help">&lt;F12&gt; - '. AS_CASD_COFNZM .'</font></small>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                </tr>
                                                
                                                    <tr>
                                                        <td align="right" width=20%>'. AS_CASD_UTW .':&nbsp;</td><td>
                                                         <input disabled type="text" name="date" value="'. $row['date'] .'" style="text-align: center; width: 150px; background: #eeeeee">&nbsp;'. AS_CASD_PRZEZ .': '. $c_user .'                                                           
                                                        </td>                                                        
                                                    </tr>
                                                    <tr>
                                                        <td align="right" nowrap>'. AS_CASD_OSTMOD .':</td><td>';
                                                       
                                                        	$l_date = '';
                                                        	$l_user='';
                                                        if ($row['last_date'] == '0000-00-00 00:00:00'){
                                                        		$l_date=$row['date'];
                                                        		$l_user = getUserName($row['user_id']);
                                                        }else{
                                                        		$l_date=$row['last_date'];
                                                        		$l_user = getUserName($row['last_user_id']);
                                                        }
                                                        
                                                     
                                                        			$l_user = "External remote user";
                                                        	
                                                     //   }
                                                        
                                                        $result .= '<input disabled type="text" name="date" value="'. $l_date .'" style="text-align: center; width: 150px; background: #eeeeee">&nbsp;'. AS_CASD_PRZEZ .': '.  $l_user .'                                                           
                                                        </td>
                                                    </tr>
                                                    
                                                </table>
                                                <table cellpadding="0" cellspacing="2" border="0" align="center" width="450">
                                                    <tr>
                                                        <td colspan="2" height="2"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="50" align="left"><small>'. CONTACT .'</small>&nbsp;</td>
                                                        <td>
                                                            <input disabled type="text" name="interaction_name" disabled style="background: #cccccc;" maxlength="50" size="30" value="'.$row['interaction_name'].'">
                                                        </td>
                                                    

                                                </table>
                                                <center><textarea name="note" cols="80" rows="12" style="font-family: Verdana; font-size: 8pt;" disabled>'.  $row['note'].'</textarea>
                                                  
                                                 <br>
                                                  <table cellpadding="2" cellspacing="2" border="0">
                                                    <tr>
                                                      <td >
                                                        </td>
                                                      <td nowrap> </td>
                                                    </tr><tr>
                                                      <td colspan="2" > </td>
                                                        </tr>';
                                                               
                                            
                                            	if ($row['new']==1){
														$result .= '<tr><td><b>Nowy alert zewnêtrzny</b> &nbsp; <input type="checkbox" value="1" onCLick="return alert_obrobiony();"> Alert obrobiony</td></tr>';											
												}else{
														$result .= '<tr><td><b>Alert obrobiony przez</b>: '.getUserName($row['made_user_id']).', dnia: '.$row['made_date'].'</tr>';																								
														
												}  
												
												$result .= '<tr><td><a href="javascript:;" OnClick="window.open(\'AS_cases_details_todo_add.php?case_id='.$case_id.'&tryb=alert&txt='.base64_encode($row['note']).'\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=300,height=225,left=\'+ (screen.availWidth - 300) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 225) / 2);"><b>Utwórz zadanie</b></a></tr>';																								
                                            	
												
												$result .='
                                                  </table>
                                                  <br>
                                                </center>
                                                <center><input type="submit" name="sss" value="Powrót" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="Zapisz notatkê"></center><br>
'.  $raport;

	$result .= '</form>';
	return $result;
}

function zapisz_alert($note_id,$case_id){
	


	$query = "UPDATE coris_assistance_cases_alerts SET new=0,last_date=now(),last_user_id='".$_SESSION['user_id']."',
				made=1, made_user_id='".$_SESSION['user_id']."', made_date=now()	
				WHERE alert_id='$note_id' LIMIT 1";
	
	mysql_query($query) OR die(mysql_error());
	
		

}
?>