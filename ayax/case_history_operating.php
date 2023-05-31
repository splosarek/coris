<?php

define('UPLOAD_TMP_DIR','../tmp/');

include_once('../include/include_ayax.php');

$id=getValue('id');
$case_id=getValue('case_id');
$action=getValue('action');
$type=getValue('type') != '' ? getValue('type') : 'note';

//echo nl2br(print_r($_REQUEST,1));
$result = '';
if ($case_id>0){							
	$result = lista($case_id);
					
}else{
	$result = "B³±d case_id=".$case_id;	
}

echo iconv('latin2','UTF-8',$result);

exit();


function  lista($case_id){
	
	$query = "SELECT cu.surname As surname1,cu.name As name1,ch.date,cu1.surname As surname2,cu1.name As name2 FROM coris_assistance_cases_operating_history ch, coris_users cu, coris_users cu1
		WHERE 
		ch.ID_case = '$case_id'
		AND cu.user_id = ch.operating_user_id 	
		AND cu1.user_id = ch.ID_user
		ORDER BY ch.ID DESC";
	
	$mr = mysql_query($query);
	
	
	$result .= '
	<div style="margin:5px;text-align:right;"><img src="img/noact.gif" style="cursor:pointer;" onclick="clear_div(\'opearating_history_frame\');">
	</div>
	<div style="border: #6699cc 1px solid;height: auto;background-color: #DFDFDF;margin:5px;">
			<table width="100%" cellpadding="5" cellspacing="1" border="0" align="center" >';
		$result .= '<tr style="background-color:#AAAAAA">
          <td style="background-color:#AAAAAA"><b>Obs³uguj±cy</b></td>
          <td style="background-color:#AAAAAA"><b>Data</b></td>
          <td style="background-color:#AAAAAA"><b>Zmieni³</b></td>                    
          </td>';
		while ($row = mysql_fetch_array($mr)){
			$result .= '<tr >
          <td >'.$row['surname1'].' '.$row['name1'].'</td>
          <td >'.$row['date'].'</td>
          <td >'.$row['surname2'].' '.$row['name2'].'</td>                    
          </td>';
			
		}
    $result .= '</table>
    </div>';
 

    		
	return $result;
}







?>