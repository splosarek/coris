<?php 
require_once('include/include_ayax.php');
require_once('include/pdf_utils.php');
//require_once('include/mylibmail.php');
 
$case_id = getValue('case_id');
$warta = getValue('warta');

$case = new CorisCase($case_id);
$redaktor = Application:: getUserName(Application::getCurrentUser());


 $branch = $case->getBranchId();
  $target=1;
  
  if ($branch == 2 ){ //niemcy
  	//$target=5;
      if ( $case->getClient_id() == '17241') {
          $target = 9;
      }else if ( $case->getClient_id() == '17708') {
          $target = 10;
      }else {
          $target = 5;
      }

  	$STOPKA_F = BRANCH::getFaxFooter(2);
  	$header = '<div align="center" style="margin-left:100px; width:770px; text-align: left;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
  	$margins =  array('left'=> 0, 'right'=> 0,'top'=> 40,'bottom' => 23);		  
  }else if ($branch == 3 ){ //austria
  	$target=6;  
  	$STOPKA_F = BRANCH::getFaxFooter(3);;
  	$header = '<div align="center" style="margin-left:100px; width:770px; text-align: left;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
  	$margins =  array('left'=> 0, 'right'=> 0,'top'=> 40,'bottom' => 23);		  
  }else{

    if ( $case->getClient_id() == '18589') {
          $target = 13;
      }
  	$STOPKA_FIRM_N = BRANCH::getFaxFooter(1,$target);
  	//$STOPKA_F =  '<div  style="margin: auto; width: 700px; text-align: left; margin-top: 10px;border: #FFF solid 1px; ">'.STOPKA_FIRM_N.'</div>';
      $STOPKA_F = BRANCH::getFaxFooter(1);;
  	$header = '<div align="center" style="margin-left:100px; width:770px; text-align: left;margin-bottom: 20px;"><img src="graphics/logo_april_pl.jpg"  width="148" height="120" /></div>';
  	$margins =  array('left' => 0,'right'  => 0,'top'    =>40,'bottom' => 15);
  }
  
 		$query = "SELECT case_id, client_id, name FROM coris_assistance_cases, coris_contrahents 
 					WHERE case_id = '".$case_id."' AND client_id = contrahent_id";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$do = $row['name'];

		$case_no = $case->getCaseNumber();
		
		$query = "SELECT coris_assistance_cases.case_id, coris_assistance_cases_details.case_id, paxname, paxsurname, paxaddress, paxpost, paxcity, paxcountry, paxdob,pax_pesel, policy, policy_series, validityfrom, validityto,
    event, circumstances, notificationdate, informer, eventdate, coris_assistance_cases.country_id, city,	pax_email,paxmobile,paxphone, coris_countries.name AS kraj, coris_countries.country_id 
    FROM coris_assistance_cases, coris_assistance_cases_details, coris_countries 
		WHERE coris_assistance_cases.case_id = $_POST[Sprawa] 
			AND coris_assistance_cases_details.case_id = '$case_id' 
			AND coris_assistance_cases.country_id = coris_countries.country_id";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$paxName = strtoupper($row['paxname']) . ' ' . strtoupper($row['paxsurname']);
		$paxAddress1 = '';
		if ($row['paxaddress'] != null){
			$paxAddress1 = $row['paxaddress'];
		}
		
		if ($row['paxcity'] != ""){
			if ($row['paxpost'] != "-"){
				$paxAddress2 = $row['paxpost'] . ' ' . $row['paxcity'] . ', ' . $row['paxcountry'];
			}else{
				$paxAddress2 = $row['paxcity'] . ', ' . $row['paxcountry'];
			}
		}else{
			$paxAddress2 = "";
		}
		
		$paxDOB = $row['paxdob'];
		$data_urodzenia ='';		
		if ($paxDOB != 0000-00-00) {
					$data_urodzenia  = convert_date($paxDOB,$branch);
		} 
		
		$pesel = $row['pax_pesel'];

		$pax_email = $row['pax_email'];

		$phones = array();

		$paxmobile = $row['paxmobile'];
		if ( $paxmobile != "" ){
            $phones[] =  $paxmobile;
        }
		$paxphone = $row['paxphone'];
        if ( $paxphone != "" ){
            $phones[] =  $paxphone;
        }
		
		if ($row['validityfrom'] != 0000-00-00) {
			$waznoscod = 'od ' . convert_date($row['validityfrom'],$branch);
		} else {
			$waznoscod = "";
		}

		if ($row['validityto'] != 0000-00-00) {
			$waznoscdo = 'do ' . convert_date($row['validityto'],$branch);
		} else {
			$waznoscdo = "";
		}
		$waznosc = $waznoscod . ' ' . $waznoscdo;		

		if ($row['informer'] != null){
			if ($row['notificationdate'] != 0000-00-00) {
			$datazglosz = convert_date($row['notificationdate'],$branch) . ' - ' . $row['informer'];
			} else {
				$datazglosz = $row['informer'];
			}
		}else{
			if ($row['notificationdate'] != 0000-00-00) {
				$datazglosz = convert_date($row['notificationdate'],$branch);
			} else {
				$datazglosz = "";
			}
		}
		
		if ($row['city'] != null):
			$miejzdarz = $row['kraj'] . ', ' . $row['city'];
		else:
			$miejzdarz = $row['kraj'];
		endif;
		
		$datazdarz = convert_date($row['eventdate'],$branch);
		$koszty = getValue('koszty');
		$okolicznosci = $row['circumstances'];
		$diagnoza = $row['event'];
		$polisa = $row['policy_series'] .' '.$row['policy'];
		
		$uwagi = str_replace("\n", "<BR>", getValue('txtUwagi'));

		$nr = poprawNumer(getValue('faxto'));
		
		
		if ($warta){
				$kosztyc = getValue('kosztyC');
			    $kosztyw = getValue('kosztyW');
		}
		
		
		if ($warta)
			$template = 'med_form_notification_Warta.html';
		else		
			$template = 'med_form_notification_2013.html';		
				
		$doc_template =  new Template();	
		$doc_template->load_template(DIR_TEMPLATE.$template);

		$doc_template->set('<!--DO-->' , $do);
 		$doc_template->set('<!--CASENO-->' , $case_no);
 		$doc_template->set('<!--DATE-->' ,   date("d/m/Y") ) ;
 		$doc_template->set('<!--REDAKTOR-->' , $redaktor);
 		$doc_template->set('<!--PAXNAME-->' , $paxName);
 		$doc_template->set('<!--PAXADDRESS1-->' , $paxAddress1);
 		$doc_template->set('<!--PAXADDRESS2-->' , $paxAddress2);
 		$doc_template->set('<!--DATAURODZENIA-->' , $data_urodzenia);
 		$doc_template->set('<!--PESEL-->' , $pesel);
 		$doc_template->set('<!--POLISA-->' , $polisa);
 		$doc_template->set('<!--WAZNOSC-->' , $waznosc);
 		$doc_template->set('<!--DIAGNOZA-->' , $diagnoza.'&nbsp;');
 		$doc_template->set('<!--OKOLICZNOSCI-->' , $okolicznosci.'&nbsp;');
 		$doc_template->set('<!--DATAZGLOSZENIA-->' , $datazglosz);
 		$doc_template->set('<!--DATAZDARZ-->' ,  $datazdarz);
 		$doc_template->set('<!--MIEJSCEZDARZ-->' ,  $miejzdarz);
 		$doc_template->set('<!--KOSZTY-->' , $koszty);
 		$doc_template->set('<!--UWAGI-->' , $uwagi);
 		$doc_template->set('<!--STOPKA_FIRM-->' ,  '');
 		$doc_template->set('<!--PHONE-->' ,  implode(",",$phones));
 		$doc_template->set('<!--EMAIL-->' ,  $pax_email);

 		if ($warta){
				$kosztyc = getValue('kosztyC');
			    $kosztyw = getValue('kosztyW');
			    $doc_template->set('<!--KOSZTYC-->' , $kosztyc);
			    $doc_template->set('<!--KOSZTYW-->' , $kosztyw);
		}
 		 				    			
    	$fax_out = $doc_template->realize();	
    		
 		//$file = html2pdf_new($fax_out);
 		$zm = array('¡' => '&#260;', '¯' => '&#379;','¦' => '&#346;','¬' => '&#377;','Æ' => '&#262;','Ó' => '&#211;','£' => '&#321;','Ñ' => '&#323;','Ê' => '&#280;',
					'±' => '&#261;', '¿' => '&#380;','¶' => '&#347;','¼' => '&#378;','æ' => '&#263;','ó' => '&#243;','³' => '&#322;','ñ' => '&#324;','ê' => '&#281;',
					'ö' => '&#246;' , 'ü' => '&#252;'					
		);
 		$file = html2pdf_new($fax_out, strtr($STOPKA_F,$zm) ,strtr($header ,$zm),$margins );    
 		$contrahent_name = getValue('contrahent_name');
 		$contrahent_to_name = '';
 		if (file_exists($file)){
 			
 				$save_only = getValue('save_only') == 1 ? 1 : 0 ;
				if (isset($_POST['send_fax']) || isset($_POST['send_email'])){ 
	        			if (isset($_POST['send_fax'])){	
	        				
	        				if ($save_only==1) $nr = "ZG£OSZENIE";
	       					echo AS_CASADD_WYSLFAXNANR.": ".$nr;
	       					$contrahent_to_name = $nr; 	   			 		   			    		
	        			 //						       
					        $faxObj = prepareFaxFromFile( $nr,$file);					        
					        $interactionObject = prepareSendFax($case_id,$faxObj,$contrahent_to_name,$contrahent_name.'/'.$nr,$paxName);
					        $interactionObject->send($save_only);
					        unlink($file);
					         					     					  
					         if ($save_only==1)
   									echo '<br><b>'.FAX_HAS_BEEN_SAVED.'</b><br>';   
  							else      
    							    echo '<br><b>'.FAX_SENT_TO_OUT_QUEUE.'</b><br>';
     						
    						echo '<br><input type="button" value="'.INC_ZAMKOKNO.'" Onclick="window.close()">';
     							
	       				}else if(isset($_POST['send_email'])){
			       				include_once('include/file_utils.php');
			       				$lang = getValue('lang');
			       				
			       				$email_body= '';
			       				$email_to = strtolower(getValue('email_to'));
			       				$email_temat = getValue('email_temat');
			       				
			       				if (strpos($email_to,";"))	
								      		$email_to = str_replace(";",",",$email_to);															      		
								$contrahent_to_name = $email_to;								      		
								check_email($email_to);								      	 
							
								$email_tmpl = '';																
								if ($lang == 'PL'){
									$email_tmpl = nl2br(AS_CASE_NOTIF_MSG_PL);
								}else{
									$email_tmpl = nl2br(AS_CASE_NOTIF_MSG_ENG);
								}	
								
								$page_template =  new Template();	
								$page_template->set_template($email_tmpl);
								
								$page_template->set('<!--NADAWCA-->' , Application::getUserName(Application::getCurrentUser()));
								
								$email_body = $page_template->realize();

								

								$email_body .= '<br><br>'.Branch::getEmailFooter($branch);
								
								$page_template->load_template(DIR_TEMPLATE.'email_template_april.html');
								$page_template->set('<!--MESSAGE-->' , $email_body);	      						
	      						$email_body = $page_template->realize();
	      											        
					            $attachments = array();
					        	$attachments[] = array('filename' => 'zgloszenie.pdf', 'type' => 'file', 'content' => $file);					        	
																									        	      		      		
							    $emailObject = prepareEmail($email_to,'',$email_temat,$email_body,$attachments,$target);
								
							    $interactionObject = wysylka_email($case_id,$emailObject,$contrahent_to_name,$contrahent_name);
							    if ( $interactionObject->send($save_only) )
							    	unlink($file);

							  if ($save_only==1)
   									echo EMAIL_SAVED.':<br>';   
  								else      
    								echo INC_MAILWYSL.':<br>';  
     							echo '<br><input type="button" value="'.INC_ZAMKOKNO.'" Onclick="window.close()">';
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


function convert_date($date,$branch=1){
		
		/*if ($branch==2){
			$tmp = explode('-', $date);
			return $tmp[2].'.'.$tmp[1].'.'.$tmp[0];			
		}else{*/
			return $date;
		//}
} 
		
?>