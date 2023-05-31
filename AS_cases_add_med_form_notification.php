<?php include('include/include.php'); 
require_once('include/pdf_utils.php');

		$query = "SELECT case_id, client_id, name FROM coris_assistance_cases, coris_contrahents WHERE case_id = $_POST[Sprawa] AND client_id = contrahent_id";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$do = $row['name'];

		$query = "SELECT case_id, number, year, type_id, client_id, coris_users.name, coris_users.surname FROM coris_assistance_cases, coris_users WHERE case_id = $_POST[Sprawa] AND coris_assistance_cases.user_id = coris_users.user_id";		
		$result = mysql_query($query, $cn);
		$row = mysql_fetch_array($result);
		$case_no = $row['number'] . '/' . substr($row['year'], 2) . '/' . $row['type_id'] . '/' . $row['client_id'];
		$redaktor = $row['name'] . ' ' . $row['surname'];
		
		$query = "SELECT coris_assistance_cases.case_id, coris_assistance_cases_details.case_id, paxname, paxsurname, paxaddress, paxpost, paxcity, paxcountry, paxdob, policy, policy_series, validityfrom, validityto, event, circumstances, notificationdate, informer, eventdate, coris_assistance_cases.country_id, city, coris_countries.name AS kraj, coris_countries.country_id FROM coris_assistance_cases, coris_assistance_cases_details, coris_countries WHERE coris_assistance_cases.case_id = $_POST[Sprawa] AND coris_assistance_cases_details.case_id = $_POST[Sprawa] AND coris_assistance_cases.country_id = coris_countries.country_id";
		$result = mysql_query($query, $cn);
		$row = mysql_fetch_array($result);
		$paxName = strtoupper($row['paxname']) . ' ' . strtoupper($row['paxsurname']);
		$paxAddress1 = '';
		if ($row['paxaddress'] != null):
			$paxAddress1 = $row['paxaddress'];
		endif;
		
		if ($row['paxcity'] != ""):
			if ($row['paxpost'] != "-"):
				$paxAddress2 = $row['paxpost'] . ' ' . $row['paxcity'] . ', ' . $row['paxcountry'];
			else:
				$paxAddress2 = $row['paxcity'] . ', ' . $row['paxcountry'];
			endif;
		else:
			$paxAddress2 = "";
		endif;
		
		$paxDOB = $row['paxdob'];
		$data_urodzenia ='';		
		if ($paxDOB != 0000-00-00) {
					$data_urodzenia  = $paxDOB;
		} 


		
		if ($row['validityfrom'] != 0000-00-00) {
			$waznoscod = 'od ' . $row['validityfrom'];
		} else {
			$waznoscod = "";
		}

		if ($row['validityto'] != 0000-00-00) {
			$waznoscdo = 'do ' . $row['validityto'];
		} else {
			$waznoscdo = "";
		}

		$waznosc = $waznoscod . ' ' . $waznoscdo;		

		

		if ($row['informer'] != null):
			if ($row['notificationdate'] != 0000-00-00) {
			$datazglosz = $row['notificationdate'] . ' - ' . $row['informer'];
			} else {
				$datazglosz = $row['informer'];
		}
		else:
			if ($row['notificationdate'] != 0000-00-00) {
				$datazglosz = $row['notificationdate'];
			} else {
				$datazglosz = "";
			}
		endif;
		
		
		if ($row['city'] != null):
			$miejzdarz = $row['kraj'] . ', ' . $row['city'];
		else:
			$miejzdarz = $row['kraj'];
		endif;
		
		$datazdarz = $row['eventdate'];
		$koszty = $_POST['koszty'];
		$okolicznosci = $row['circumstances'];
		$diagnoza = $row['event'];
		$polisa = $row['policy_series'] .' '.$row['policy'];
		
		$uwagi = str_replace("\n", "<BR>", $_POST['txtUwagi']);

		$template = '../fax/templates/med_form_notification.html';
		$tmpl = load_template($template);
		$nr = poprawNumer($_POST['faxto']);
 		if ($tmpl <> null){
 				$zmiany = array('<!--DO-->' => $do, '<!--CASENO-->' => $case_no,'<!--DATE-->' =>  date("d/m/Y"),'<!--REDAKTOR-->' => $redaktor,
 				'<!--PAXNAME-->' => $paxName,'<!--PAXADDRESS1-->' => $paxAddress1,'<!--PAXADDRESS2-->' => $paxAddress2,'<!--DATAURODZENIA-->' => $data_urodzenia,
 				'<!--POLISA-->' => $polisa,'<!--WAZNOSC-->' => $waznosc,'<!--DIAGNOZA-->' => $diagnoza,'<!--OKOLICZNOSCI-->' => $okolicznosci,
 				'<!--DATAZGLOSZENIA-->' => $datazglosz,'<!--DATAZDARZ-->' =>  $datazdarz,'<!--MIEJSCEZDARZ-->' =>  $miejzdarz,
 				'<!--KOSZTY-->' => $koszty,'<!--UWAGI-->' => $uwagi, '<!--STOPKA_FIRM-->' =>  STOPKA_FIRM);
    			//$fax_out = str_replace('<!--DO-->',$do,$tmpl);
    			$fax_out =  strtr($tmpl,$zmiany );		
    			
 		}else{
 			die('template error: '.$template);
 		
 		}
 		
 		$file = html2pdf($fax_out);    
 		if (file_exists($file)){
 			
 				$save_only = getValue('save_only') == 1 ? 1 : 0 ;
				if (isset($_POST['send_fax']) || isset($_POST['send_email'])){ 

        			if (isset($_POST['send_fax'])){	
       					echo AS_CASADD_WYSLFAXNANR.": ".$nr; 
   			 	
   			    		$dir = check_out_dir();
      					$new_file='../fax/out/'.$dir.'/pdf/'.basename($file);
   			       	
        				copy($file,$new_file);
        				unlink($file);   			    			 	
   			 	      
       					$message=$new_file;
       				
            		   	$id = wysylka_fax($dir, basename($new_file),$nr,$save_only );
            			if ($id>0)
         					sendFax($id,'', $message,  $nr,$save_only);
       			}else if(isset($_POST['send_email'])){
       				include_once('include/file_utils.php');
       				$lang = getValue('lang');
       				
       				$email_body= '';
       				$email_to = getValue('email_to');
       				$email_temat = getValue('email_temat');
       				
       					if (strpos($email_to,";"))	
					      		$email_to = str_replace(";",",",$email_to);
					      	if (strpos($email_cc,";"))	
					      		$email_cc = str_replace(";",",",$email_cc);
					      		
					      		
					      	 check_email($email_to);
					      	 
					      	 if ($email_cc != "")
					      	 	check_email($email_cc);

					      	 		
							$file_attach[] = array('tmp' => $file,'form'=>1,'filename'=>'zgloszenie.pdf'); 			  									  								      	
							$email_tmpl = '';
							if ($lang == 'PL')
								$email_tmpl = nl2br(AS_CASE_NOTIF_MSG_PL);
							else
								$email_tmpl = nl2br(AS_CASE_NOTIF_MSG_ENG);	
							
							$zmiany_at = array('<!--NADAWCA-->' => getUserName($_SESSION['user_id']));
							$email_body = strtr( $email_tmpl,$zmiany_at);
							
											
							$tmpl = load_template('../fax/templates/email_template.html');
      						$zmiany_at = array('<!--MESSAGE-->' => $email_body)	;
      						$email_body = strtr( $tmpl,$zmiany_at);
      						
      						if ($tmpl <> null){   	
					      		$id = wysylka_email($dir,$email_to,$email_cc,$email_temat,$email_body,$save_only);      	
					      		if ($id>0){
					      		 		sendEmail($id,$email_to,$email_cc,$email_temat,$email_body,$file_attach,$save_only);
					      		}
      						}
       			}
         					
   			 	}else{
   			 		sendPDF2Browser($file);
          			unlink($file);
          			exit ();
   			 	}
		} else{
			echo "Error file: ".$file;
			exit;
		}
		 
		
function wysylka_fax($dir,$file,$nr,$save_only){

  if ($save_only==1) $nr = "ZG£OSZENIE";
	
  $query = "INSERT INTO coris_fax_out (ID,ID_section,`date`,ID_user,user_send,`dir`,`file`,number,ID_case,ID_contrahents,company,`to`) ";
  $query .= " values (null,1,now(),'".$_SESSION['user_id']."','".addslashes(stripslashes(getUserName($_SESSION['user_id'])))."','$dir','$file','$nr','".$_POST['case_id']."','".addslashes(stripslashes($_POST['contrahent_id']))."','".addslashes(stripslashes($_POST['contrahent_name']))."','".addslashes(stripslashes($_POST['contrahent_to_name']))."')";
  $result = mysql_query($query);
  if ($result){
    $fid =  mysql_insert_id();
    $query = "INSERT INTO coris_assistance_cases_interactions (`interaction_id`, `case_id`, `type_id`, `documenttype_id`, `user_id`, `direction`, `interaction_name`, `interaction_contact`, `subject`, `note`, `date`,ext_id) ";
    $query .= " VALUES(null,'".$_POST['case_id']."',1,7,'".$_SESSION['user_id']."',2,'".addslashes(stripslashes($_POST['paxname']))."','$nr','".addslashes(stripslashes($_POST['paxname']))."','',now(),'$fid')";    
    $result = mysql_query($query);
    if (!$result) { echo mysql_error(); echo $query;}
    return $fid;
  }else{
    echo "query ERROR: ".$query;
    return null;
  }
  
}

function wysylka_email($dir,$email_to,$email_cc,$email_temat,$email_body,$save_only=0){
	$email_body = addslashes(stripslashes($email_body));
	$email_temat = addslashes(stripslashes($email_temat));

	if ($save_only==1) $email_to = 'ZG£OSZENIE';
	
  $query = "INSERT INTO coris_email_out (ID,ID_section,`date`,ID_user,user_send,`dir`,email_to,email_cc,email_temat,email_body,ID_case,ID_contrahents,company,`to`) ";
  $query .= " values (null,1,now(),'".$_SESSION['user_id']."','".$_POST['editor']."','$dir','$email_to','$email_cc','$email_temat','$email_body','".$_POST['case_id']."','".$_POST['contrahent_id']."','".$_POST['contrahent_name']."','".$_POST['contrahent_to_name']."')";
  $result = mysql_query($query);
  if ($result){
    $fid =  mysql_insert_id();
    $query = "INSERT INTO coris_assistance_cases_interactions (`interaction_id`, `case_id`, `type_id`, `documenttype_id`, `user_id`, `direction`, `interaction_name`, `interaction_contact`, `subject`, `note`, `date`,ext_id) ";
    $query .= " VALUES(null,'".$_POST['case_id']."',2,7,'".$_SESSION['user_id']."',2,'".$_POST['paxname']."','$email_to','".$email_temat."','',now(),'$fid')";    
    $result = mysql_query($query);
    if (!$result) { echo mysql_error(); echo $query;}
    return $fid;
  }else{
    echo "query ERROR: ".$query;
    return null;
  }
  
} 

function check_email($email_to){
	
      	if(strpos($email_to,",") ){
      			$tmp=explode(",",$email_to);
      			for($i=0;$i<count($tmp);$i++){      			
      				if(!is_email($tmp[$i])){
      					echo GEN_FAX_BLE . ": <h2>".$tmp[$i]."</h2>";
      					exit;
      				}      			
      			}
      	}else{
      		if(!is_email($email_to)){
      			echo GEN_FAX_BLE . ":<h2>".$email_to."</h2>";
      			exit;
      		}
      	}	 		
}
?>