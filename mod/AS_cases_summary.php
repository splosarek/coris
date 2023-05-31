<?php

$lista_admin_user = array(26,39,4,76,79,121,256,261,352, 275, 321, 410, 367); // zmiana likwidatora
$list_admin_operating= array(39, 38, 102, 79, 261, 26, 315, 85, 98, 352, 275, 321, 367); // zmiana obslugujacego


function module_update(){
	global  $pageName,$lista_admin_user,$list_admin_operating;
	$result ='';

	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');

	$check_js = '';
	$message = '';


   if (isset($change['ch_summary_tow_ub']) && $case_id > 0  ){
   		$res=check_update($case_id,'summary_tow_ub');
		if ($res[0]){
			$contrahent_id = getValue('contrahent_id');
			$contrahent_id_old = getValue('contrahent_id_old');
			$client_ref = getValue('client_ref');


			if ($contrahent_id == 11086  && $contrahent_id_old <> 11086){
				$message .= '
				<script>
					alert(\'Prosze uzupelnic dane dot. typu i wariantu umowy oraz wprowadzic nr polisy i rezerwe\');
					document.location = \'AS_cases_details.php?case_id='.$case_id.'&mod=cardif_annonce\';
				</script>
			';

			}

			$query = "UPDATE coris_assistance_cases
			          SET client_id = '$contrahent_id',client_ref ='$client_ref'
			          WHERE case_id ='$case_id' LIMIT 1";
			$mysql_result = mysql_query($query);
			if ($mysql_result){
				//$message .= "Udpate OK";
				CaseInfo::updateFullNumber($case_id);
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}

			if ($contrahent_id == 14189){
 				include_once 'lib/lib_ace.php';
 				ACECase::aktualizacja_programu($case_id, getValue('ace_program'));
			}

            if ($contrahent_id == 17241){
                include_once 'lib/lib_barclaycard.php';
                BarclaycardCase::aktualizacja_programu($case_id, getValue('barclaycard_program'));
            }
			if ($contrahent_id == 5 || $contrahent_id == 7 || $contrahent_id == 2306 || $contrahent_id == 14500 ){
 				include_once 'lib/lib_vig.php';
 				VIGCase::aktualizacja_programu($case_id, getValue('vig_program'));
			}
			if ($contrahent_id == 18589 ){
 				include_once 'lib/lib_hansemerkur.php';
                HansemerkurCase::aktualizacja_programu($case_id, getValue('hansemerkur_program'));
			}
		}else{//error update
			echo $res[1];

		}

	}

	if (isset($change['ch_summary_zdarzenie']) && $case_id > 0  ){
   		$res=check_update($case_id,'ch_summary_zdarzenie');
		if ($res[0]){
			$eventdate = getValue('eventDate_y').'-'.getValue('eventDate_m').'-'.getValue('eventDate_d');
			$country_id = getValue('country');
			$post = getValue('post');
			$city = getValue('city');
            $pax_place_of_stay = getValue('paxplaceofstay');

			$notificationdate = getValue('notificationDate_y').'-'.getValue('notificationDate_m').'-'.getValue('notificationDate_d');
			$notificationTime = getValue('notificationTime');

			$opendate = getValue('openDate_y').'-'.getValue('openDate_m').'-'.getValue('openDate_d');

			$var = '';


			$claim_handler_user_id = getValue('claim_handler_user_id') > 0 ? getValue('claim_handler_user_id') : 0 ;
			$claim_handler_user_id_old = getValue('claim_handler_user_id_old') > 0 ? getValue('claim_handler_user_id_old') : 0 ;


			if (in_array($_SESSION['user_id'],$lista_admin_user)){
				if ( $claim_handler_user_id != $claim_handler_user_id_old ){
						if ($claim_handler_user_id > 0 ){
							$var =  ", claim_handler_user_id='".getValue('claim_handler_user_id')."', claim_handler_date=now() ";
							if ($claim_handler_user_id_old == 0)
								$var .= ",liquidation=1,liquidation_date=now(),liquidation_user_id='".Application::getCurrentUser()."'";
						}else{
							$var =  ", claim_handler_user_id=0, claim_handler_date=null ";
						}
				}
			}else{
				if ($claim_handler_user_id > 0 && $claim_handler_user_id_old == 0  ){
					$var =  ", claim_handler_user_id='".getValue('claim_handler_user_id')."', claim_handler_date=now() ";

					$var .= ",liquidation=1,liquidation_date=now(),liquidation_user_id='".Application::getCurrentUser()."'";

				}
			}
		//	echo $var;

			$query = "UPDATE coris_assistance_cases
			             SET country_id = '$country_id', city = '$city',post = '$post',
			                 eventdate='$eventdate' $var
                       WHERE case_id ='$case_id'
                       LIMIT 1";

            $query2 = "UPDATE coris_assistance_cases_details
                          SET notificationdate = '$notificationdate',notificationTime='$notificationTime',
                           pax_place_of_stay='$pax_place_of_stay'
                        WHERE case_id ='$case_id'
                        LIMIT 1";


			$mysql_result = mysql_query($query);
			$mysql_result2 = mysql_query($query2);
			if ($mysql_result && $mysql_result2){
				//$message .= "Udpate OK ".$query;
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}


            if (in_array($_SESSION['user_id'],$list_admin_operating)) {
                $operating_user_id = intval(getValue('operating_user_id'));
                CaseInfo::setCaseOperatingUser($case_id, $operating_user_id);
            }
		}else{//error update
			echo $res[1];

		}
	}

	if (isset($change['ch_summary_pacjent_info']) && $case_id > 0  ){
   		$res=check_update($case_id,'ch_summary_pacjent_info');
		if ($res[0]){
			$paxDob = getValue('paxDob_y').'-'.getValue('paxDob_m').'-'.getValue('paxDob_d');
			$paxSurname = getValue('paxSurname');
			$paxName = getValue('paxName');
			$paxSex = getValue('paxSex');


			$pax_pesel = getValue('pax_pesel');

			$benSurname = getValue('benSurname');
			$benName = getValue('benName');


			$query = "UPDATE coris_assistance_cases SET paxdob='$paxDob',paxsurname='$paxSurname',paxname='$paxName',paxsex='$paxSex',pax_pesel='$pax_pesel' WHERE case_id ='$case_id' LIMIT 1";
			$query2 = "UPDATE coris_assistance_cases_details
			             SET benSurname='$benSurname',benName='$benName'
                       WHERE case_id ='$case_id'
                       LIMIT 1";
			$mysql_result = mysql_query($query);
			$mysql_result = mysql_query($query2);

			if ($mysql_result ){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}
		}else{//error update
			echo $res[1];

		}
	}

	if (isset($change['ch_summary_pacjent_adres']) && $case_id > 0  ){
   		$res=check_update($case_id,'ch_summary_pacjent_adres');
		if ($res[0]){
			$paxpost = getValue('paxpost_1').'-'.getValue('paxpost_2');
			$paxaddress= getValue('paxaddress');
			$paxcity= getValue('paxcity');
			$paxcountry= getValue('paxcountry');
			$paxphone= getValue('paxphone');
			$paxmobile= getValue('paxmobile');
			$pax_email = getValue('pax_email');

			$query = "UPDATE coris_assistance_cases
			             SET pax_email='$pax_email'
                       WHERE case_id ='$case_id'
                       LIMIT 1";
			$mysql_result = mysql_query($query);

			if ($mysql_result  ){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}


			$query = "UPDATE coris_assistance_cases_details
			             SET paxpost='$paxpost',paxaddress='$paxaddress',paxcity='$paxcity',
			                 paxcountry='$paxcountry',paxphone='$paxphone',paxmobile='$paxmobile'
                       WHERE case_id ='$case_id'
                       LIMIT 1";


			$mysql_result = mysql_query($query);
			if ($mysql_result  ){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}
		}else{//error update
			echo $res[1];

		}
	}

	if (isset($change['ch_summary_diagnoza']) && $case_id > 0  ){		  //szkoda / diagnoza
   		$res=check_update($case_id,'ch_summary_diagnoza');
		if ($res[0]){

			$icd10= getValue('icd10');
			$event= getValue('event');
			$event_ng= getValue('event_ng')==1 ? 1 : 0;
			$event_nwu= getValue('event_nwu')==1 ? 1 : 0;
			$event_npmh= getValue('event_npmh')==1 ? 1 : 0;
			$event_us= getValue('event_us')==1 ? 1 : 0;
			$informer= getValue('informer');
			$circumstances= getValue('circumstances');

			$query = "UPDATE coris_assistance_cases SET icd10='$icd10',event='$event',event_ng='$event_ng',event_nwu='$event_nwu',event_npmh='$event_npmh',event_us='$event_us' WHERE case_id ='$case_id' LIMIT 1";
			$query2 = "UPDATE coris_assistance_cases_details SET informer='$informer',circumstances='$circumstances' WHERE case_id ='$case_id' LIMIT 1";

			$mysql_result = mysql_query($query);
			$mysql_result2 = mysql_query($query2);


			CaseInfo::CaseCauseUpdate($case_id,intval(getValue('cause_id')),intval(getValue('cause_id_old'))); // zapis przyczyny s?ownik

			$client_id = CaseInfo::getCaseClient($case_id);

			if ($client_id == 11242){
						include_once('lib/lib_uniqa.php');
						$rowUniqa = UniqaCase::CaseTypeUpdate($case_id,intval(getValue('uniqa_case_type')),intval(getValue('uniqa_case_type_old')));;
			}

			if ($mysql_result && $mysql_result2 ){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}
		}else{//error update
			echo $res[1];

		}
	}


	if (isset($change['ch_summary_polisa_info']) && $case_id > 0  ){		  //polisa
   		$res=check_update($case_id,'ch_summary_polisa_info');
		if ($res[0]){
			$mysql_result2=true;

				$validityFrom= getValue('validityFrom_y').'-'.getValue('validityFrom_m').'-'.getValue('validityFrom_d');
				$validityTo= getValue('validityTo_y').'-'.getValue('validityTo_m').'-'.getValue('validityTo_d');
				$validityFromDep= getValue('validityFromDep_y').'-'.getValue('validityFromDep_m').'-'.getValue('validityFromDep_d');
				$validityToDep= getValue('validityToDep_y').'-'.getValue('validityToDep_m').'-'.getValue('validityToDep_d');
				$purchasedate= getValue('purchasedate_y').'-'.getValue('purchasedate_m').'-'.getValue('purchasedate_d');


				$validityToEhic= getValue('validityToEhic_y').'-'.getValue('validityToEhic_m').'-'.getValue('validityToEhic_d');
				$validityToEhic_old= getValue('validityToEhic_old');
				$ehic_no= getValue('ehic_no');
				$ehic_no_old= getValue('ehic_no_old');

				$upd='';

				if ($ehic_no_old != $ehic_no || $validityToEhic_old != $validityToEhic){
					$upd = " ,ehic_no='$ehic_no',validityToEhic='$validityToEhic',ehic_user_id='".$_SESSION['user_id']."',ehic_date=now() ";
				}

				$policy= getValue('policy');
				$policy_series= getValue('policy_series');
				$cart_number= getValue('cart_number');

				$policyamount = getValue('policyamount');
				$policyamount = str_replace(',','.',$policyamount);
				$policycurrency_id = getValue('policycurrency_id');

				$query = "UPDATE coris_assistance_cases SET policy_series='$policy_series',policy='$policy',cart_number='$cart_number' WHERE case_id ='$case_id' LIMIT 1";
				$query2 = "UPDATE coris_assistance_cases_details SET validityfrom='$validityFrom',validityto='$validityTo',validityfromDep='$validityFromDep' ,validitytoDep ='$validityToDep',purchasedate ='$purchasedate',policyamount='$policyamount',policycurrency_id='$policycurrency_id' $upd WHERE case_id ='$case_id' LIMIT 1";
				$mysql_result2 = mysql_query($query2);


				$biurop_id=getValue('biurop_id');
				$client_id=getValue('client_id');

				if ($client_id==7592){ // signal
					$var = " biurop_id='$biurop_id' ";

					$qt = "SELECT case_id FROM coris_assistance_cases_announce  WHERE case_id='$case_id'";
					$mt = mysql_query($qt);

					if (mysql_num_rows($mt)==0){
						$query_a = "INSERT INTO coris_assistance_cases_announce SET case_id='$case_id', $var ";
					}else{
						$query_a = "UPDATE coris_assistance_cases_announce SET $var  WHERE case_id='$case_id' LIMIT 1";
					}

					$mysql_result_a = mysql_query($query_a);

					if (!$mysql_result_a){
						$message .= "<br>Update Error: ".$query_a."\n<br> ".mysql_error();
					}
				}



			$mysql_result = mysql_query($query);

			if ($mysql_result && $mysql_result2 ){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br>".$query2."<br> ".mysql_error();
			}
		}else{//error update
			echo $res[1];

		}
	}


	if (isset($change['ch_summary_tech_info']) && $case_id > 0  ){		  //tech info
   		$res=check_update($case_id,'ch_summary_tech_info');
		if ($res[0]){
			$mysql_result2=true;


				$marka_model= getValue('marka_model');
				$nr_rej= getValue('nr_rej');
				$vin= getValue('vin');
				$telefon1= getValue('telefon1');
				$telefon2= getValue('telefon2');
				$adress1= getValue('adress1');
				$adress2= getValue('adress2');

				$query = "UPDATE coris_assistance_cases SET marka_model='$marka_model', vin='$vin',nr_rej='$nr_rej',telefon1='$telefon1' ,telefon2='$telefon2',adress1='$adress1',adress2='$adress2'    WHERE case_id ='$case_id' LIMIT 1";
				//echo $query;



			$mysql_result = mysql_query($query);

			if ($mysql_result && $mysql_result2 ){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br>".$query2."<br> ".mysql_error();
			}
		}else{//error update
			echo $res[1];

		}
	}




   if (isset($change['ch_summary_uwagi_info']) && $case_id > 0  ){
   		$res=check_update($case_id,'ch_summary_uwagi_info');
		if ($res[0]){
			$comments = getValue('comments');

			$query = "UPDATE coris_assistance_cases_details SET comments = '$comments'  WHERE case_id ='$case_id' LIMIT 1";
			$mysql_result = mysql_query($query);
			if ($mysql_result){
				//$message .= "Udpate OK";
			}else{
				$message .= "Update Error: ".$query."\n<br> ".mysql_error();
			}
		}else{//error update
			echo $res[1];

		}

	}


	echo $message;
}

function module_main(){
	global $row_case,$case_id;

	$result = '';


	$row_case_ann = array();
		$type_id  = $row_case['type_id'];


	if ($row_case['client_id'] == 7592 ){
		$query2 = "SELECT * FROM coris_assistance_cases_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);
	}

	$result .= '<div style="float:right;width:280px;heigth:auto;padding:5px;" id="opearating_history_frame"></div>';
	$result .= '<div  style="float:left;">';
	$result .= '<div style="width:1022px;heigth:auto;">';
		$result .= '<div style="width: 335px;float:left;padding-right:5px;border: #6699cc 1px solid;height: 150px;background-color: #DFDFDF;margin:5px">';
			$result .= tow_ub($row_case);
		$result .= '</div>
			<div style="width:645px;float:right;padding-right:10px;border: #6699cc 1px solid;height: auto; background-color: #DFDFDF;margin: 5px">';
			$result .= zdarzenie_info($row_case);
		$result .= '</div>';
	$result .= '</div>';
	$result .= '<div style="clear:both;"></div>';





	$result .= '<div style="width: 435px;float:left;padding-right:5px;border: #6699cc 1px solid;height:190px;background-color: #DFDFDF;margin:5px">';
		$result .= pacjent_info($row_case);
	$result .= '</div><div style="width:545px;float:right;padding-right:10px;border: #6699cc 1px solid;height:190px; background-color: #DFDFDF;margin: 5px">';
	 		$result .= pacjent_adres($row_case);
		$result .= "</div>";
			$result .= '<div style="clear:both;"></div>';
	$result .= '</div>';

	$result .= '<div style="clear:both;"></div>';
	$result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
		$result .= diagnoza($row_case);
	$result .= '</div>';
	$result .= '<div style="clear:both;"></div>';

	$result .= '</div>';

if (($type_id==1 || $type_id==5)){
	$result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
		$result .= tech_info($row_case,$row_case_ann);
	$result .= '</div>';
	$result .= '<div style="clear:both;"></div>';
}

	$result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
		$result .= polisa_info($row_case,$row_case_ann);
	$result .= '</div>';

	$result .= '<div style="clear:both;"></div>';
	$result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
		$result .=  contants($row_case);
	$result .= '</div>';

	$result .= '<div style="clear:both;"></div>';
	$result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
		$result .= uwagi_info($row_case);
	$result .= '</div>';


	return $result;
}
function uwagi_info($row){


    $result='';
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'. COMMENTS .'</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['summary_uwagi_info'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_uwagi_info] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_uwagi_info] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';
if (isset($change['summary_uwagi_info'])){
		$result .= '<table cellpadding="0" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
				<tr>
				<td align="center">
				<textarea name="comments" cols="100" rows="5" style="font-family: Verdana; font-size: 8pt;">'. $row['comments'] .'</textarea>	</td></tr>
											</table>';
	}else{
		$result .= '<table cellpadding="0" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
		<tr>
			<td align="center">
				<textarea name="comments" cols="100" rows="5" style="font-family: Verdana; font-size: 8pt;" disabled>'. $row['comments'] .'</textarea>
				</td>
			</tr>
			</table>';
	}
	return $result;

}


function contants($row){

$result = '         <table cellpadding="2" cellspacing="1" border="0" width="100%">
									<tr>
                                        <td  width="50%" align="left" >
                                            <font color="#6699cc"><small><font color="#6699cc"><b>'. AS_CASD_KONTWSPR .'</b></small></font>&nbsp;</td>

                                           <td width="50%" align="right"><input style="width: 20px;" type="button" value="+" onClick="window.open(\'AS_cases_details_contacts_add.php?case_id='. $_GET['case_id'] .'\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=610,height=100,left=\'+ (screen.availWidth - 610) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 100) / 2);" title="Dodaj kontakt">&nbsp;&nbsp;&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" valign="top">
                                            <iframe name="AS_cases_details_contacts_frame" width="100%" height="80" frameborder="0" src="AS_cases_details_contacts_frame.php?case_id='.$_GET['case_id'] .'" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-top: #6699cc 1px solid;"></iframe></td>
                                    </tr>
                                </table>';

	return $result;
}

function polisa_info($row,$row2){


		$validityFrom = array("", "", "");
		if ($row['validityfrom'] != "0000-00-00")
		$validityFrom = split("-", $row['validityfrom']);

		$validityTo = array("", "", "");
		if ($row['validityto'] != "0000-00-00")
		$validityTo = split("-", $row['validityto']);

		$validityFromDep = array("", "", "");
		if ($row['validityfromDep'] != "0000-00-00")
		$validityFromDep = split("-", $row['validityfromDep']);

		$validityToDep = array("", "", "");
		if ($row['validitytoDep'] != "0000-00-00")
		$validityToDep = split("-", $row['validitytoDep']);

		$validityToEhic = array("", "", "");
		if ($row['validityToEhic'] != "0000-00-00")
		$validityToEhic = split("-", $row['validityToEhic']);

		$purchasedate = array("", "", "");
		if ($row['purchasedate'] != "0000-00-00")
		$purchasedate = split("-", $row['purchasedate']);

		$result='';

		$type_id  = $row['type_id'];
		$case_type = ($type_id==1 || $type_id==5) ? 'tech' : 'med';  // techniczne - Argos to tech

	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_polisa_info" id="form_polisa_info">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'.  AS_CASADD_UBEZP .'</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['summary_polisa_info'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_polisa_info] value=1>
				<input type="hidden" name="summary_polisa_info_case_type" value="'.$case_type.'">
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_polisa_info] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';

	if (isset($change['summary_polisa_info'])){ // ubezpieczenie
		$result .= calendar();
		$result .= '<table cellpadding="3" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
												<tr>
													<td colspan="4"></td>
												</tr>


												<tr style="background: #d0d0d0">
													<td width="160" align="right">
														<small><b>'.AS_TITLE_POLISA.' '. AS_CASADD_WAZNOD .'</b></small>
													</td>
													<td colspan="3">
														<input type="text" name="validityFrom_d" id="validityFrom_d" value="'.$validityFrom[2] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityFrom_m" id="validityFrom_m" value="'. $validityFrom[1] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityFrom_y" id="validityFrom_y" value="'. $validityFrom[0] .'" size="4" maxlength="4"  onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'validityFrom\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>

													&nbsp;&nbsp;&nbsp;	<small><b>'. AS_CASADD_WAZNDO .'</b></small>
														<input type="text" name="validityTo_d" id="validityTo_d" value="'. $validityTo[2] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityTo_m" id="validityTo_m" value="'. $validityTo[1] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityTo_y" id="validityTo_y" value="'. $validityTo[0] .'" size="4" maxlength="4"  onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'validityTo\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>
													</td>
									</tr>
									<tr>
													<td align="right">
														<small><b>'. AS_CASADD_POL .':</b></small>
													</td>
							<td colspan="3"><small><b>'.AS_CASES_POLSER.': </b></small><input type="text" name="policy_series" id="policy_series" onchange="javascript:this.value=this.value.toUpperCase();" size="20" maxlength="20" value="'. $row['policy_series'] .'">
													<small><b>'.AS_CASES_POLNO.' </b></small><input type="text" name="policy" id="policy" value="'. $row['policy'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="30">	';


					if ($row['client_id'] == 7592){ //signal
							$result .= '&nbsp;&nbsp;<small><b>Agent:</b></small>&nbsp;&nbsp;';
							$result .= wysw_biuro_podrozy('biurop_id',$row2['biurop_id'],0);
							$result .= '<input type="hidden" name="client_id" value="'.$row['client_id'].'">';
					}
					$result .= '</td><tr>';


					$result .= '
<tr style="background: #d0d0d0">

					<td align="right"><small><b>'.AS_TITLE_SUMA_UBEZP.':</b></small></td><td colspan="3"> <input type="text" name="policyamount"  id="policyamount" size="10" value="'.( ($row['policyamount'] != 0) ? str_replace(".", ",", $row['policyamount']) : "" ).'" style="text-align: right;">&nbsp;&nbsp;';
					$result .= wysw_currency('policycurrency_id',$row['policycurrency_id']);

					$result .= '</td>
					</tr>';

				if ($case_type=='med'){
					$result .= '<tr >
													<td width="150" align="right">
														<small><b>'.AS_TITLE_SUMA_DATA_WYJAZDU.' '. AS_CASADD_WAZNOD .'</b></small>
													</td>
													<td colspan="3">
														<input type="text" name="validityFromDep_d"  id="validityFromDep_d" value="'.$validityFromDep[2] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityFromDep_m" id="validityFromDep_m" value="'. $validityFromDep[1] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityFromDep_y" id="validityFromDep_y" value="'. $validityFromDep[0] .'" size="4" maxlength="4"  onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'validityFromDep\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>

														&nbsp;&nbsp;&nbsp;&nbsp;<small><b>'. AS_CASADD_WAZNDO .'</b></small>

														<input type="text" name="validityToDep_d" id="validityToDep_d" value="'. $validityToDep[2] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityToDep_m" id="validityToDep_m" value="'. $validityToDep[1] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityToDep_y" id="validityToDep_y" value="'. $validityToDep[0] .'" size="4" maxlength="4"  onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'validityToDep\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>


                                                        &nbsp;&nbsp;&nbsp;&nbsp;<small><b>Data zakupu</b></small>

														<input type="text" name="purchasedate_d"  id="purchasedate_d" value="'. $purchasedate[2] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="purchasedate_m"  id="purchasedate_m" value="'. $purchasedate[1] .'" size="1" maxlength="2"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="purchasedate_y" id="purchasedate_y" value="'. $purchasedate[0] .'" size="4" maxlength="4"  onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'purchasedate\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>



													&nbsp;&nbsp;&nbsp;
														<small><b>'. AS_CASADD_KART .':</b></small>
													&nbsp;&nbsp;<input type="text" name="cart_number" id="cart_number" value="'. $row['cart_number'] .'" size="25" maxlength="30" >

													</td>
												</tr>
													<tr style="background: #d0d0d0">
					<td align="right"><small><b>Karta EKUZ/EHIC:</b></small></td><td colspan="3"><small><b>Nr.</b></small> <input type="text" name="ehic_no"  id="ehic_no" size="21" maxlength="20"  value="'.$row['ehic_no'] .'"  >

					<input type="hidden" name="ehic_no_old"  id="ehic_no_old" value="'.$row['ehic_no'] .'"  >
					<input type="hidden" name="validityToEhic_old"  id="validityToEhic_old" value="'.$row['validityToEhic'] .'"  >
					&nbsp;&nbsp;';

					$result .= '<small><b>Wa?na do:</b></small>	<input type="text" name="validityToEhic_d" id="validityToEhic_d" value="'. $validityToEhic[2] .'" size="1"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityToEhic_m" id="validityToEhic_m" value="'. $validityToEhic[1] .'" size="1"  onkeyup="move_formant(this,document.getElementById(\'form_polisa_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
														<input type="text" name="validityToEhic_y" id="validityToEhic_y" value="'. $validityToEhic[0] .'" size="4" onkeydown="remove_formant(this,document.getElementById(\'form_polisa_info\'),event);">
													<a href="javascript:void(0)" onclick="newWindowCal(\'validityToEhic\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>

													';
					$result .= '</td>
					</tr>';
				}
			$result .= '</table>';


	}else{
		$result .= '<table cellpadding="3" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
												<tr>
													<td colspan="4"></td>
												</tr>


												<tr style="background: #d0d0d0">
													<td width="160" align="right" >
														<small><b>'.AS_TITLE_POLISA.' '. AS_CASADD_WAZNOD .'</b></small>
													</td>
													<td colspan="3">
														<input type="text" name="validityFrom_d" value="'.$validityFrom[2] .'" size="1"  disabled>
														<input type="text" name="validityFrom_m" value="'. $validityFrom[1] .'" size="1"  disabled>
														<input type="text" name="validityFrom_y" value="'. $validityFrom[0] .'" size="4"  disabled>

													&nbsp;&nbsp;&nbsp;	<small><b>'. AS_CASADD_WAZNDO .'</b></small>
														<input type="text" name="validityTo_d" value="'. $validityTo[2] .'" size="1" disabled>
														<input type="text" name="validityTo_m" value="'. $validityTo[1] .'" size="1" disabled>
														<input type="text" name="validityTo_y" value="'. $validityTo[0] .'" size="4" disabled>
													</td>
									</tr>
										<tr>
													<td align="right">
														<small><b>'. AS_CASADD_POL .':</b></small>
													</td>
													<td colspan="3" ><small><b>'.AS_CASES_POLSER.': </b></small><input type="text" name="policy_series" id="policy_series" onchange="javascript:this.value=this.value.toUpperCase();" size="20" maxlength="20" value="'. $row['policy_series'] .'" disabled>
													<small><b>'.AS_CASES_POLNO.' </b></small><input type="text" name="policy" id="policy" value="'. $row['policy'] .'" size="25" maxlength="30" disabled>
													';
								if ($row['client_id'] == 7592){ //signal

									$result .= '&nbsp;&nbsp;<small><b>Agent</b></small>: '.wysw_biuro_podrozy('biurop_id',$row2['biurop_id'],1);

								}
					$result .= '</td>';
					$result .= '</tr>
					<tr style="background: #d0d0d0">

					<td align="right"><small><b>'.AS_TITLE_SUMA_UBEZP.':</b></small></td><td colspan="3"> <input type="text" name="policyamount"  id="policyamount" size="10" value="'.( ($row['policyamount'] != 0) ? str_replace(".", ",", $row['policyamount']) : "" ).'" style="text-align: right;" disabled>&nbsp;&nbsp;';
					$result .= wysw_currency('policycurrency_id',$row['policycurrency_id'],1);

					$result .= '</td>
					</tr>';
				if ($case_type=='med'){
					$result .= '<tr >
													<td width="150" align="right">
														<small><b>'.AS_TITLE_SUMA_DATA_WYJAZDU.' '. AS_CASADD_WAZNOD .'</b></small>
													</td>
													<td colspan="3">
														<input type="text" name="validityFromDep_d" value="'.$validityFromDep[2] .'" size="1" disabled>
														<input type="text" name="validityFromDep_m" value="'. $validityFromDep[1] .'" size="1" disabled>
														<input type="text" name="validityFromDep_y" value="'. $validityFromDep[0] .'" size="4" disabled>
														&nbsp;&nbsp;&nbsp;&nbsp;<small><b>'. AS_CASADD_WAZNDO .'</b></small>

														<input type="text" name="validityToDep_d" value="'. $validityToDep[2] .'" size="1"  disabled>
														<input type="text" name="validityToDep_m" value="'. $validityToDep[1] .'" size="1"  disabled>
														<input type="text" name="validityToDep_y" value="'. $validityToDep[0] .'" size="4" disabled>
													&nbsp;&nbsp;&nbsp;
													
													    <small><b>Data zakupu</b></small>
														<input type="text" name="purchasedate_d" value="'. $purchasedate[2] .'" size="1"  disabled>
														<input type="text" name="purchasedate_m" value="'. $purchasedate[1] .'" size="1"  disabled>
														<input type="text" name="purchasedate_y" value="'. $purchasedate[0] .'" size="4" disabled>
													&nbsp;&nbsp;&nbsp;
														<small><b>'. AS_CASADD_KART .':</b></small>
													&nbsp;&nbsp;<input type="text" name="cart_number" id="cart_number" value="'. $row['cart_number'] .'" size="25" maxlength="30" disabled>
													</td>
													</tr>
				<tr style="background: #d0d0d0">
					<td align="right"><small><b>Karta EKUZ/EHIC:</b></small></td><td colspan="3"><small><b>Nr.</b></small> <input type="text" name="ehic_no"  id="ehic_no" size="21" maxlength="20"  value="'.$row['ehic_no'] .'"  disabled>&nbsp;&nbsp;';

					$result .= '<small><b>Wa?na do:</b></small>	<input type="text" name="validityToEhic_d" value="'. @$validityToEhic[2] .'" size="1"  disabled>
														<input type="text" name="validityToEhic_m" value="'. @$validityToEhic[1] .'" size="1"  disabled>
														<input type="text" name="validityToEhic_y" value="'. @$validityToEhic[0] .'" size="4" disabled>';


					$result .= '</td>
					</tr>';
				}

					$result .= '</table>';
	}

	$result .= '</form>';



return $result;

}


function tech_info($row,$row2){ // techniczne - Argos to tech


	global $global_link,$change;
	$result = '<form method="POST" style="padding:0px;margin:0px" name="form_tech_info" id="form_tech_info">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'.  AS_CASD_INF .'</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['summary_tech_info'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_tech_info] value=1>
				<input type="hidden" name="summary_tech_info_case_type" value="'.$case_type.'">
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_tech_info] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';





	if (isset($change['summary_tech_info'])){
								$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
												<tr>
													<td width="18%" align="right">
														<small><b>'.	AS_CASD_MARKMODEL .':</b></small>
													</td>
												  <td width="82%">
														<input type="text" name="marka_model" value="'. $row['marka_model'] .'"  size="32" maxlength="100">
														&nbsp;&nbsp;&nbsp;<small><b>'. REGISTRATION .':</b></small><input type="text" name="nr_rej" value="'.  $row['nr_rej'] .'"  size="18" maxlength="100">
														&nbsp;&nbsp;&nbsp;<small><b>VIN: </b></small><input type="text" name="vin" id="vin" value="'.  $row['vin'] .'"  size="30" maxlength="50">
													</td>
												</tr>
												<tr>
													<td width="18%" align="right">
														<small><b>'. AS_CASADD_TEL1 .':</b></small>
													</td>
													<td width="82%"><input type="text" name="telefon1" value="'.  $row['telefon1'] .'" size="15" maxlength="100">													  <small>&nbsp;&nbsp;&nbsp;<b>'. AS_CASADD_TEL2 .':</b></small>
                                                      <input type="text" name="telefon2" value="'.  $row['telefon2'] .'"  size="15" maxlength="100"></td>
												</tr>
												<tr>
													<td width="18%" align="right" valign="top">
														<small><b>'. AS_CASADD_ADRPOST .':</b></small>
													</td>
													<td>
														<input name="adress1" type="text" style="font-family: Verdana; font-size: 8pt" value="'.  $row['adress1'] .'" size="100">
													</td>
												</tr>
<tr>
													<td width="18%" align="right" valign="top">
														<small><b>'. AS_CASADD_ADRDOC .':</b></small>
													</td>
													<td>
														<input name="adress2" type="text" style="font-family: Verdana; font-size: 8pt" value="'.  $row['adress2'] .'" size="100">
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>';
	}else{
		$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
												<tr>
													<td width="18%" align="right">
														<small><b>'.	AS_CASD_MARKMODEL .': </b></small>
													</td>
												  <td width="82%"><input type="text" name="marka_model" value="'. $row['marka_model'] .'"  size="32" maxlength="100" disabled>
									&nbsp;&nbsp;&nbsp;<small><b>'. REGISTRATION .': </b></small> <input type="text" name="nr_rej" value="'.  $row['nr_rej'] .'"  size="18" maxlength="100" disabled>&nbsp;&nbsp;&nbsp;<small><b>VIN: </b></small><input type="text" name="vin" id="vin" value="'.  $row['vin'] .'"  size="30" maxlength="50" disabled>
													</td>
												</tr>
												<tr>
													<td width="18%" align="right">
														<small><b>'. AS_CASADD_TEL1 .': </b></small>
													</td>
													<td width="82%"><input type="text" name="telefon1" value="'.  $row['telefon1'] .'" size="15" maxlength="100" disabled> <small>&nbsp;&nbsp;&nbsp;<b>'. AS_CASADD_TEL2 .': </b></small> <input type="text" name="telefon2" value="'.  $row['telefon2'] .'"  size="15" maxlength="100" disabled>
</td>
												</tr>
												<tr>
													<td width="18%" align="right" valign="top">
														<small><b>'. AS_CASADD_ADRPOST .': </b></small>
													</td>
													<td><input name="adress1" type="text" style="font-family: Verdana; font-size: 8pt" value="'.  $row['adress1'] .'" size="100" disabled></td>
												</tr>
<tr>
													<td width="18%" align="right" valign="top">
														<small><b>'. AS_CASADD_ADRDOC .': </b></small>
													</td>
													<td><input name="adress2" type="text" style="font-family: Verdana; font-size: 8pt" value="'.  $row['adress2'] .'" size="100" disabled></td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>';

	}


	$result .= '</form>';



return $result;

}


function diagnoza($row){
	    $result='';
	global $global_link,$change;

	$type_id  = $row['type_id'];
	$case_type = ($type_id==1 || $type_id==5) ? 'tech' : 'med';

	$result .= '<form method="POST" style="padding:0px;margin:0px">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'. AS_CASADD_SZKOD .'</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['summary_diagnoza'])){
				$result .= '<div style="float:right;padding:2px">
				<input type="hidden" name=change[ch_summary_diagnoza] value=1>
				<input type="hidden" name="summary_diagnoza_case_type" value="'.$case_type.'">
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_diagnoza] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}
	    $result .= '</td>
			</tr>
			</table>';
if (isset($change['summary_diagnoza'])){
		$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
												<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b>'. ( ($case_type=='tech') ? AS_CASD_PRZYCZ :  AS_CASD_DIAGN  ) . ':</b></font></u></b></small>
													</td>
													<td width="86%">
														<input type="text" name="event" style="font: bold; color: red;" value="'. $row['event'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="100" maxlength="100">
														';
												if ($row['client_id'] == 11242){
														include_once('lib/lib_uniqa.php');
														$rowUniqa = UniqaCase::getCaseInfo($row['case_id']);
														$result .= '&nbsp;<small><b>Typ sprawy:</b></small> '.UniqaCase::wysw_case_type('uniqa_case_type',$rowUniqa['ID_type'],0);

												}
														$result .= '
													</td>
												</tr>';
									if ($case_type=='tech'){
											$result .= '
											<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b>'. AS_CASD_PRZYCZ  . ':</b></font></u></b></small>
													</td><td width="86%">'.CaseInfo::getCaseCause('cause_id',$row['ID_cause'],0,$row['type_id']).'
													</td>
												</tr>';
									}else  if ($row['type_id'] == 2 ){
										$result .= '
											<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b></b></font></u></b></small>
													</td><td width="86%">
													<div style="float:left;"><input type="checkbox" value="1" name="event_ng"  id="event_ng" '.($row['event_ng'] == 1 ? 'checked' : '').'><span style="cursor: default"  onclick="FormcheckboxSelect(\'event_ng\');"> '.AC_CASE_NAG_ZACH.'</span></div>
													<div style="float:left;margin-left:20px;"><input type="checkbox" value="1" name="event_nwu" id="event_nwu" '.($row['event_nwu'] == 1 ? 'checked' : '').'><span style="cursor: default"  onclick="FormcheckboxSelect(\'event_nwu\');"> '.AC_CASE_NWU.'</span></div>
													<div style="float:left;margin-left:20px;"><input type="checkbox" value="1" name="event_npmh" id="event_npmh" '.($row['event_npmh'] == 1 ? 'checked' : '').'><span style="cursor: default"  onclick="FormcheckboxSelect(\'event_npmh\');"> '.AC_CASE_NPMH.'</span></div>
													<div style="float:left;margin-left:20px;"><input type="checkbox" value="1" name="event_us"  id="event_us" '.($row['event_us'] == 1 ? 'checked' : '').'><span style="cursor: default"  onclick="FormcheckboxSelect(\'event_us\');"> '.AC_CASE_US.'</span></div>
													<div style="clear:both"></div><br>
													</td>
												</tr>';
										if ($row['client_id'] == 17787 ) {
                                            $result .= '
											<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b>Diagnoza ICD-10</b></font></u></b></small>
													</td><td width="86%">
												        	<input type="text" name="icd10" id="icd10" style=""   value="' . $row['icd10'] . '" onChange="checkCodeICD10($(\'icd10\'))" size="10" maxlength="10"> <span style="margin-left:10px;margin-right:10px;cursor: pointer"><img src="img/ico_loupe.png" title="Szukaj kodu" width="13" onclick="icd10_search($(\'icd10\'))"></span>   <div id="icd10-desc" style="display: inline-block;  font-style: italic;font-weight: bold;">' . ICD10::getCodeName($row['icd10']) . '</div>
													</td>
												</tr>';

                                        }
									}
												$result .= '<tr>
													<td width="14%" align="right">
														<small><b>'. INFORMER .':</b></small>
													</td>
													<td width="86%">
														<input type="text" name="informer" value="'. $row['informer'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="100" maxlength="100">
													</td>
												</tr>
												<tr>
													<td width="14%" align="right" valign="top">
														<small><b>'. AS_CASADD_OKOLO .':</b></small>
													</td>
													<td>
														<textarea cols="100" rows="4" name="circumstances" style="font-family: Verdana; font-size: 8pt">'. $row['circumstances'] .'</textarea>
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>';
	}else{
			$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >

											<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b>'. ( ($case_type=='tech') ? AS_CASD_PRZYCZ :  AS_CASD_DIAGN  ) . ':</b></font></u></b></small>
													</td><td width="86%">
														<input type="text" name="event" style="font: bold; color: red;" value="'. $row['event'] .'" size="100" maxlength="100" disabled>
															';
												if ($row['client_id'] == 11242){
														include_once('lib/lib_uniqa.php');
														$rowUniqa = UniqaCase::getCaseInfo($row['case_id']);
														$result .= '&nbsp;<small><b>Typ sprawy:</b></small> '.UniqaCase::wysw_case_type('uniqa_case_type',$rowUniqa['ID_type'],1);

												}
														$result .= '</td>
												</tr>';

										if ($case_type=='tech'){

											$result .= '
											<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b>'. AS_CASD_PRZYCZ  . ':</b></font></u></b></small>
													</td><td width="86%">'.CaseInfo::getCaseCause('cause_id',$row['ID_cause'],1,$row['type_id']).'
													</td>
												</tr>';
										}else if ($row['type_id'] == 2 ){
											$result .= '
											<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><b></b></font></u></b></small>
													</td><td width="86%">
													<div style="float:left;"><input type="checkbox" value="1" name="event_ng" '.($row['event_ng'] == 1 ? 'checked' : '').' disabled> '.AC_CASE_NAG_ZACH.'</div>
													<div style="float:left;margin-left:20px;"><input type="checkbox" value="1" name="event_nwu" '.($row['event_nwu'] == 1 ? 'checked' : '').' disabled> '.AC_CASE_NWU.'</div>
													<div style="float:left;margin-left:20px;"><input type="checkbox" value="1" name="event_npmh" '.($row['event_npmh'] == 1 ? 'checked' : '').' disabled> '.AC_CASE_NPMH.'</div>
													<div style="float:left;margin-left:20px;"><input type="checkbox" value="1" name="event_us" '.($row['event_us'] == 1 ? 'checked' : '').' disabled> '.AC_CASE_US.'</div>
														<div style="clear:both"></div><br>
													</td>
												</tr>';
                                            if ($row['client_id'] == 17787 ) {
                                                $result .= '
                                                <tr>
                                                        <td width="14%" align="right">
                                                            <small><b><u><font color=red><b>Diagnoza ICD-10</b></font></u></b></small>
                                                        </td><td width="86%">
                                                                <input type="text" name="icd10" id="icd10" style=""   value="' . $row['icd10'] . '" onChange="" size="10" maxlength="10" readonly disabled>   <div id="icd10-desc" style="display: inline-block;  font-style: italic;font-weight: bold;">' . ICD10::getCodeName($row['icd10']) . '</div>
                                                        </td>
                                                    </tr>';
                                            }
                                        }

												$result .=	'
												<tr>
													<td width="14%" align="right">
														<small><b>'. INFORMER .':</b></small>
													</td>
													<td width="86%">
														<input type="text" name="informer" value="'. $row['informer'] .'"  size="100" maxlength="100"  disabled>
													</td>
												</tr>
												<tr>
													<td width="14%" align="right" valign="top">
														<small><b>'. AS_CASADD_OKOLO .':</b></small>
													</td>
													<td>
														<textarea cols="100" rows="4" name="circumstances" style="font-family: Verdana; font-size: 8pt"  disabled>'. $row['circumstances'] .'</textarea>
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>';
	}
	$result .= '</form>';
	return $result;
}

function pacjent_adres($row){
    $lang = $_SESSION['GUI_language'];
	$paxpost = array("", "");
    if ($row['paxpost'])
        $paxpost = split("-", $row['paxpost']);


       $result='';
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_paxadr" id="form_paxadr">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b></b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['summary_pacjent_adres'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_pacjent_adres] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_pacjent_adres] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';
if (isset($change['summary_pacjent_adres'])){
   	$result .= ' <table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" >
          <tr valign="middle">
                    <td width="70" align="right"><small><b>'. ADDRESS .': </b></small></td>
                    <td>
                        <input type="text" name="paxaddress" value="'.$row['paxaddress'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">
                    </td>
                </tr>
                <tr valign="middle">
                    <td width="70" align="right"><small><b>'. POST .': </b></small></td>
                    <td>
                        <input type="text" name="paxpost_1" value="'. $paxpost[0] .'" size="1" maxlength="2" onKeyUp="move_formant(this,document.getElementById(\'form_paxadr\'),event);">&nbsp;
                        <input type="text" name="paxpost_2" value="'. $paxpost[1] .'" size="2" maxlength="3" onKeyDown="remove_formant(this,document.getElementById(\'form_paxadr\'),event);">&nbsp;&nbsp;
                        <small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="paxcity" value="'. $row['paxcity'] .'" onChange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                    </td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b>'. COUNTRY .': </b></small></td>
                    <td>
                        <input type="text" id="paxcountry" name="paxcountry" value="'.$row['paxcountry'] .'" size="3" maxlength="2" onBlur="document.forms[\'form_paxadr\'].elements[\'paxcountrylist\'].value = document.forms[\'form_paxadr\'].elements[\'paxcountry\'].value.toUpperCase(); document.forms[\'form_paxadr\'].elements[\'paxcountry\'].value = document.forms[\'form_paxadr\'].elements[\'paxcountry\'].value.toUpperCase()" style="text-align: center">';

    $result .= Application :: countryList($row['paxcountry'], $lang, 'paxcountrylist', 'style="font-size: 8pt;" onChange="document.forms[\'form_paxadr\'].elements[\'paxcountry\'].value = document.forms[\'form_paxadr\'].elements[\'paxcountrylist\'].value"');

	$result .='
                    </td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b>'. PHONE .': </b></td>
                  <td><input type="text" name="paxphone" value="'.$row['paxphone'] .'" size="32" maxlength="30" onKeyDown="remove_formant(this);"></td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b>'. AS_CASADD_TELKOM .': </b></small></td>
                  <td><input type="text" name="paxmobile" value="'. $row['paxmobile'] .'" size="32" maxlength="30"></td>
                </tr>
<tr>
				<td width="70" align="right">
					<small><b>Email: </b></small>
				</td>
				<td>
				 <input type="text" id="pax_email" name="pax_email" value="'.$row['pax_email'] .'" size="32" maxlength="50">
			</td>
			</tr>
            </table>';
   }else{
			$result .= ' <table cellpadding="1" cellspacing="1" border="0" width="100%" >
          <tr valign="middle">
                    <td width="70" align="right"><small><b>'. ADDRESS .': </b></small></td>
                    <td>
                        <input type="text" name="paxaddress" value="'.$row['paxaddress'] .'" " size="30" maxlength="50" disabled>
                    </td>
                </tr>
                <tr valign="middle">
                    <td width="70" align="right"><small><b>'. POST .': </b></small></td>
                    <td>
                        <input type="text" name="paxpost_1" value="'. $paxpost[0] .'" size="1" maxlength="2"  disabled>&nbsp;<input type="text" name="paxpost_2" value="'. $paxpost[1] .'" size="2" maxlength="3"  disabled>&nbsp;&nbsp;<small><b>'. AS_CASES_MIAST .': </b></small>&nbsp;<input type="text" name="paxcity" value="'. $row['paxcity'] .'"  size="25" maxlength="25"  disabled>
                    </td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b>'. COUNTRY .': </b></small></td>
                    <td>
                        <input type="text" id="paxcountry" name="paxcountry" value="'.$row['paxcountry'] .'" size="3" maxlength="2" style="text-align: center"  disabled>';

    $result .= Application :: countryList($row['paxcountry'], $lang, 'paxcountrylist', ' disabled="disabled" style="font-size: 8pt;"');


	$result .='
                    </td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b>'. PHONE .': </b></td>
                  <td><input type="text" name="paxphone" value="'.$row['paxphone'] .'" size="32" maxlength="30" disabled></td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b>'. AS_CASADD_TELKOM .': </b></small></td>
                  <td><input type="text" name="paxmobile" value="'. $row['paxmobile'] .'" size="32" maxlength="30" disabled></td>
                </tr>
<tr>
				<td width="70" align="right">
					<small><b>Email: </b></small>
				</td>
				<td>
				 <input type="text" id="pax_email" name="pax_email" value="'.$row['pax_email'] .'" size="32" maxlength="50" disabled>
			</td>
			</tr>
            </table>';
   }

   $result .= '</form>';
   return $result;

}

function pacjent_info($row){
	global $global_link,$change;

	 $paxDob = array("","","");
       if ($row['paxdob'] != "0000-00-00")
             $paxDob = split("-", $row['paxdob']);


          $result='';

	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_pacjent_info" id="form_pacjent_info">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'. AS_CASD_UBEZP .'</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['summary_pacjent_info'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_pacjent_info] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" onclick="return validate();" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_pacjent_info] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';
if (isset($change['summary_pacjent_info'])){

	$result .= '<script language="JavaScript1.2">
		<!--
		function validate() {
			if (document.getElementById(\'paxName\').value == "") {
				alert("'.AS_CASD_MSG_PROSZWPIMIE .'");
				document.getElementById(\'paxName\').focus();
				return false;
			}

			if (document.getElementById(\'paxSurname\').value == "") {
				alert("'. AS_CASD_MSG_PROSZWPNAZW .'");
				document.getElementById(\'paxSurname\').focus();
				return false;
			}
			if ((document.getElementById(\'paxDob_d\').value != "" || document.getElementById(\'paxDob_m\').value != "" || document.getElementById(\'paxDob_y\').value != "") && (document.getElementById(\'paxDob_d\').value == "" || document.getElementById(\'paxDob_m\').value == "" || document.getElementById(\'paxDob_y\').value == "")) {
				alert("'. AS_CASADD_MSG_WYWYCZ .'");
				document.getElementById(\'paxDob_d\').focus();
				return false;
			}
			return true;
		}
		//-->
		</script>
		';
	$result .= calendar();
	$result .= '<table cellpadding="1" cellspacing="1" border="0"  width="100%" >
			<tr>
				<td width="70" align="right">
					<small><b>'. SURNAME .'</b></small>
				</td>
				<td>
					<input type="text" id="paxSurname" name="paxSurname" style="font: bold;" value="'. $row['paxsurname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="35" maxlength="50">
				</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small><b>'. NAME .'</b></small>
				</td>
				<td>
					<input type="text" id="paxName"  name="paxName" style="font: bold;" value="'. $row['paxname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="35" maxlength="50">
				</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small><b>'.AS_TITLE_PLEC.'</b></small>
				</td>
				<td>'. getPlec('paxSex',$row['paxsex'],0).'
				</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small>'. AS_CASD_UR .'</small>
				</td>
				<td>
					<input type="text" id="paxDob_d" name="paxDob_d" value="'. $paxDob[2] .'" size="1" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_pacjent_info\'),event);">
					<input type="text" id="paxDob_m" name="paxDob_m" value="'. $paxDob[1] .'" size="1" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_pacjent_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_pacjent_info\'),event);">
					<input type="text" id="paxDob_y" name="paxDob_y" value="'. $paxDob[0] .'" size="4" maxlength="4" onkeydown="remove_formant(this,document.getElementById(\'form_pacjent_info\'),event);">
					<a href="javascript:void(0)" onclick="newWindowCal(\'paxDob\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>
				</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small>PESEL</small>
				</td>
				<td>
				  <input type="text" id="pax_pesel" name="pax_pesel"  value="'. $row['pax_pesel'] .'"  size="12" maxlength="11">
			</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small><b>'.AS_BENEFICIARY.'</b></small>
				</td>
				<td>
				  <small>'. SURNAME .': </small><input type="text" id="benSurname" name="benSurname" value="'. $row['benSurname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="15" maxlength="50">
				  &nbsp;&nbsp;<small>'. NAME .': </small><input type="text" id="benName" name="benName" value="'. $row['benName'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="15" maxlength="50">
			</td>
			</tr>
		</table>';
}else{
		$result .= '<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td width="70" align="right">
					<small><b>'. SURNAME .'</b></small>
				</td>
				<td>
					<input type="text" id="paxSurname" name="paxSurname" style="font: bold;" value="'. $row['paxsurname'] .'" size="35" maxlength="50" disabled>
				</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small><b>'. NAME .'</b></small>
				</td>
				<td>
					<input type="text" id="paxName"  name="paxName" style="font: bold;" value="'. $row['paxname'] .'"  size="35" maxlength="50"  disabled>
				</td>
			</tr>
						<tr>
				<td width="70" align="right">
					<small><b>'.AS_TITLE_PLEC.'</b></small>
				</td>
				<td>'. getPlec('paxSex',$row['paxsex'],1).'
				</td>
			</tr>

			<tr>
				<td width="70" align="right">
					<small>'. AS_CASD_UR .'</small>
				</td>
				<td>
					<input type="text" id="paxDob_d" name="paxDob_d" value="'. $paxDob[2] .'" size="1"  disabled>
					<input type="text" id="paxDob_m" name="paxDob_m" value="'. $paxDob[1] .'" size="1"  disabled>
					<input type="text" id="paxDob_y" name="paxDob_y" value="'. $paxDob[0] .'" size="4"  disabled>
				</td>
			</tr>
			<tr>
				<td width="70" align="right">
					<small>PESEL</small>
				</td>
				<td>
				  <input type="text" id="pax_pesel" name="pax_pesel"  value="'. $row['pax_pesel'] .'"  size="12" maxlength="11" disabled>
			</td>
			</tr>
<tr>
				<td width="70" align="right">
					<small><b>'.AS_BENEFICIARY.'</b></small>
				</td>
				<td>
				  <small>'. SURNAME .': </small><input type="text" id="benSurname" name="benSurname" value="'. $row['benSurname'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="15" maxlength="50" disabled>
				  &nbsp;&nbsp;<small>'. NAME .': </small><input type="text" id="benName" name="benName" value="'. $row['benName'] .'" onchange="javascript:this.value=this.value.toUpperCase();" size="15" maxlength="50" disabled>
			</td>
			</tr>
		</table>';
}

	$result .= '</form>';

	return $result ;
}

function zdarzenie_info($row){
		global $global_link,$change,$lista_admin_user,$list_admin_operating;
    $lang = $_SESSION['GUI_language'];
	$eventDate = array("","","");
	if ($row['eventdate'] != "0000-00-00")
	$eventDate = split("-", $row['eventdate']);

	$notificationDate = array("", "", "");
	if ($row['notificationdate'] != "0000-00-00")
	$notificationDate = split("-", $row['notificationdate']);
	$notificationTime = $row['notificationTime'];

	$openDate = array("", "", "");
	if ($row['date'] != "0000-00-00 00:00:00")
	$openDate = split("-", substr($row['date'],0,10));
	$openDateTime =  substr($row['date'],11,8);

	$claimDate = array("", "", "");
	if ($row['claim_handler_date'] != "0000-00-00")
	$claimDate = split("-", $row['claim_handler_date']);

	$closeDate = array("", "", "");
	if ($row['archive_date'] != "0000-00-00")
	$closeDate = split("-", $row['archive_date']);


	$result='';

	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_zdarzenie_info" id="form_zdarzenie_info">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b></b></font></small>&nbsp;
				</td>
				<td align="right">';

	global $global_link,$change;
	if (isset($change['summary_zdarzenie'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_zdarzenie] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_zdarzenie] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';

 if (isset($change['summary_zdarzenie']) && check_edit($row['case_id'],'summary_zdarzenie') ){
     // tryb edycji
 	$result .= calendar();
	$result .= '<table cellpadding="1" cellspacing="1" border="0"  width="100%">
		<tr><td colspan="3">&nbsp;
 	<div style="float:left;margin-left:5px;width:170px"><small><b>'. AS_CASD_RED .': </b></small>&nbsp;&nbsp;'.getUserName($row['user_id']) . '</div>

 	<div style="float:left;margin-left:15px;"><small><b>Obs?uguj?cy: </b></small>';


     if (in_array($_SESSION['user_id'],$list_admin_operating)) {
         $result .= '<input type="hidden" name="operating_user_id_old" value="' . $row['operating_user_id'] . '">
            &nbsp;&nbsp;' . listaUser('operating_user_id', $row['operating_user_id']) . '';
     }else{
         $result .= getUserName($row['operating_user_id']);

     }
     $result .= '</div><div style="float:right;margin-right:5px;"><small><b>'.AS_CASD_LIKWID.': </b></small>&nbsp;&nbsp;';

												if ($row['claim_handler_user_id']>0){

														if (in_array($_SESSION['user_id'],$lista_admin_user)){
															$result .= listaUser('claim_handler_user_id',$row['claim_handler_user_id']);
														}else{
															$result .= getUserName($row['claim_handler_user_id']);
														}
												}else{
													$result .= listaUser('claim_handler_user_id');
												}

 											$result .= '</div>
												</td></tr>
												<tr>
													<td  align="left" nowrap>&nbsp;
													<small><b>'.AS_TITLE_OTWARCIE.': </b></small> &nbsp;
														<input type="text" id="openDate_d" name="openDate_d" value="'. $openDate[2] .'" size="1" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);"  disabled>
														<input type="text" id="openDate_m" name="openDate_m" value="'.  $openDate[1] .'" size="1" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);"  disabled>
														<input type="text" id="openDate_y" name="openDate_y" value="'.  $openDate[0] .'" size="4" maxlength="4" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);" disabled>
														<input type="text" value="'.$openDateTime.'"  style="width:58px;font-size: 7pt;"  disabled>
												</td>
												<td> &nbsp;</td>
												<td align="right" nowrap>
														<small><b>'. AS_CASES_ZDARZ .'</b></small>
														<input type="text" id="eventDate_d" name="eventDate_d" value="'. $eventDate[2] .'" size="1" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);">
														<input type="text" id="eventDate_m" name="eventDate_m" value="'.  $eventDate[1] .'" size="1" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);">
														<input type="text" id="eventDate_y" name="eventDate_y" value="'.  $eventDate[0] .'" size="4" maxlength="4" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'eventDate\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>
													</td>
												</tr>
												<tr>
													<td  align="left">&nbsp;
														<small><b>'. AS_CASD_ZGL .': </b></small>
														<input type="text" id="notificationDate_d" name="notificationDate_d" value="'. $notificationDate[2] .'" size="1" maxlength="2" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);">
														<input type="text" id="notificationDate_m" name="notificationDate_m" value="'.  $notificationDate[1] .'" size="1" maxlength="2" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);">
														<input type="text" id="notificationDate_y" name="notificationDate_y" value="'.  $notificationDate[0] .'" size="4" maxlength="4" maxlength="4" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);">
														<a href="javascript:void(0)" onclick="newWindowCal(\'notificationDate\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>
														<input type="text" name="notificationTime" size="8" value="'.$notificationTime.'" style="width:58px;font-size: 7pt;"  >
													</td>

													<td> &nbsp;</td>
														<td  align="right">
														<small><b>'.AS_TITLE_PRZYPISANIE.'</b></small>
														<input type="text" id="claimDate_d" name="claimDate_d" value="'. @$claimDate[2] .'" size="1" maxlength="2" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);" disabled>
														<input type="text" id="claimDate_m" name="claimDate_m" value="'.  @$claimDate[1] .'" size="1" maxlength="2" maxlength="2" onkeyup="move_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);"  disabled>
														<input type="text" id="claimDate_y" name="claimDate_y" value="'.  @$claimDate[0] .'" size="4" maxlength="4" maxlength="4" onkeydown="remove_formant(this,document.getElementById(\'form_zdarzenie_info\'),event);"  disabled>
														<!-- <a href="javascript:void(0)" onclick="newWindowCal(\'claimDate\')" tabindex="-1" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a> -->
													</td>
												</tr>';
if ($row['archive']==1){

	$result .= ' 	<tr><td align="right"><small><font color="darkred"><b>'. AS_CASD_ZAMK .'</b></font></small>
	<input type="text" id="closeDate_d" name="closeDate_d" value="'. $closeDate[2] .'" size="1" disabled>
	<input type="text" id="closeDate_m" name="closeDate_m" value="'.  $closeDate[1] .'" size="1"  disabled>
	<input type="text" id="closeDate_y" name="closeDate_y" value="'.  $closeDate[0] .'" size="4"  disabled>
												</td><td> &nbsp;</td><td> &nbsp;</td>
											</tr>	';

}
												$result .= '
												<tr>
													<td  align="left" colspan="3" style="padding-left:12px">
<b><small>'. COUNTRY .'</small></b>&nbsp;<input type="text" name="country" id="country" value="'. $row['country_id'] .'" onchange="javascript:this.value=this.value.toUpperCase();aktualizuj_kraj(this.value)" size="1">';
     $result .= Application :: countryList($row['country_id'], $lang, 'countryList', ' onchange="document.forms[\'form2\'].elements[\'country\'].value = document.forms[\'form2\'].elements[\'countryList\'].value" style="font-size: 8pt;width:160px;" ');


    //$result .= '</td></tr>';

    // kod, miejsce, adres pobytu
    // $result .= '<tr><td colspan="3" style="padding-left:12px">'	;
//	$result .= '</td><td>&nbsp;</td><td  align="right">'	;
    $result .= 	'&nbsp;&nbsp;<small><b>'. POST .'</b>&nbsp;</small>
                 <input type="text" name="post" value="'. $row['post'] .'"
                        onchange="javascript:this.value=this.value.toUpperCase();" size="6" maxlength="10">&nbsp;&nbsp;&nbsp;&nbsp;
                <small><b>'. AS_CASD_MIEJSC .'</b>&nbsp;</small>
                <input type="text" name="city" value="'. $row['city'] .'"
                    onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="30">
                ';
    $result .= '</td></tr>';
    // adres pobytu
    $result .= '<tr><td colspan="3" style="padding-left:12px">';
    $result .= '<small><b>'. AS_CASD_ADRES_POBYTU .'</b>&nbsp;</small>';
    $result .= '<input type="text" name="paxplaceofstay" size="88" value="' . $row['pax_place_of_stay'] . '">';


    $result .= '</td>
												</tr>
											</table>

										</td>
									</tr>
								</table>


	<script>
	aktualizuj_kraj(\''.$row['country_id'].'\');

	function aktualizuj_kraj(kod_kraju){

		kod_kraju=kod_kraju.toUpperCase();

		ilosc= document.getElementById(\'countryList\').length;
		zm=0;
		kr_status=0;
		for (i=0;i<ilosc;i++){
					if (document.getElementById(\'countryList\').options[i].value == kod_kraju ){
							document.getElementById(\'countryList\').selectedIndex = i;
							document.getElementById(\'country\').value = document.getElementById(\'country\').value.toUpperCase();
							kr_status=1;
					}
		}
		if (kr_status==0){
				document.getElementById(\'country\').value = "";
				document.getElementById(\'countryList\').selectedIndex = 0 ;
				alert("'.AS_CASD_BRKROSKR .'" + kod_kraju );
		}
}

</script>
								';



 }else{
 	 // podgl?d danych
     $result .= '<table cellpadding="1" cellspacing="1" border="0"  width="100%">
 	<tr><td colspan=3>

 	<div style="float:left;margin-left:5px;width:170px"><small><b>'. AS_CASD_RED .': </b></small>&nbsp;&nbsp;'.getUserName($row['user_id']).'</div>
 	<div style="float:left;margin-left:15px;"><small><b>Obs?uguj?cy: </b></small>&nbsp;&nbsp;'.getUserName($row['operating_user_id']). ($row['operating_user_id'] > 0 ? ' <a style="margin-left:15px;" href="javascript:;" onclick="load_history_operating('.$row['case_id'].')">Historia</a>' : '<span style="margin-left:15px;">&nbsp;</span>' ).'</div>

	<div style="float:right;margin-right:5px;"><small><b>'.AS_CASD_LIKWID.': </b></small>&nbsp;&nbsp;'.getUserName($row['claim_handler_user_id']).'</div>
												</td></tr>
												<tr>
													<td  align="left" nowrap>&nbsp;
													<small><b>'.AS_TITLE_OTWARCIE.': </b></small> &nbsp;
	<input type="text" id="openDate_d" name="openDate_d" value="'. $openDate[2] .'" size="1" disabled>
	<input type="text" id="openDate_m" name="openDate_m" value="'.  $openDate[1] .'" size="1"  disabled>
	<input type="text" id="openDate_y" name="openDate_y" value="'.  $openDate[0] .'" size="4"  disabled>
	<input type="text" value="'.$openDateTime.'"  style="width:62px;"  disabled>
												</td>
												<td> &nbsp;</td>
												<td align="right" nowrap>&nbsp;
												<small><b>'. AS_CASES_ZDARZ .': </b></small>
	<input type="text" id="eventDate_d" name="eventDate_d" value="'. $eventDate[2] .'" size="1" disabled>
	<input type="text" id="eventDate_m" name="eventDate_m" value="'.  $eventDate[1] .'" size="1"  disabled>
	<input type="text" id="eventDate_y" name="eventDate_y" value="'.  $eventDate[0] .'" size="4"  disabled>
												</td>												</tr>
												<tr>
													<td  align="left">&nbsp;
													<small><b>'. AS_CASD_ZGL .': </b></small>
	<input type="text" id="notificationDate_d" name="notificationDate_d" value="'. $notificationDate[2] .'" size="1" disabled>
	<input type="text" id="notificationDate_m" name="notificationDate_m" value="'.  $notificationDate[1] .'" size="1" disabled>
	<input type="text" id="notificationDate_y" name="notificationDate_y" value="'.  $notificationDate[0] .'" size="4" disabled>
	<input type="text" name="notificationTime" size="8" value="'.$notificationTime.'" style="width:58px;font-size: 7pt;" disabled >
													</td>
													<td> &nbsp;</td>
												<td align="right" nowrap>
												<small><b>'.AS_TITLE_PRZYPISANIE.': </b></small>
	<input type="text" id="claimDate_d" name="claimDate_d" value="'. @$claimDate[2] .'" size="1" disabled>
	<input type="text" id="claimDate_m" name="claimDate_m" value="'.  @$claimDate[1] .'" size="1"  disabled>
	<input type="text" id="claimDate_y" name="claimDate_y" value="'.  @$claimDate[0] .'" size="4"  disabled>
												</td></tr>';
if ($row['archive']==1){

	$result .= ' 	<tr><td align="right"><small><font color="darkred"><b>'. AS_CASD_ZAMK .'</b></font></small>
	<input type="text" id="closeDate_d" name="closeDate_d" value="'. $closeDate[2] .'" size="1" disabled>
	<input type="text" id="closeDate_m" name="closeDate_m" value="'.  $closeDate[1] .'" size="1"  disabled>
	<input type="text" id="closeDate_y" name="closeDate_y" value="'.  $closeDate[0] .'" size="4"  disabled>
												</td><td> &nbsp;</td><td> &nbsp;</td>
											</tr>	';

}
$result .= '											</tr>
												<tr><td  colspan="3" style="padding-left:12px">
<b><small>'. COUNTRY .'</small></b>&nbsp;<input type="text" name="country" id="country" value="'. $row['country_id'] .'"  size="1" disabled>';

     $result .= Application :: countryList($row['country_id'], $lang, 'countryList', ' disabled="disabled" style="width:160px;"');



    //$result .= '</td></tr>
	//											<tr><td colspan="3" style="padding-left:12px">'	;

											//	$result .= '</td><td>&nbsp;</td><td  align="right">'	;
    $result .= 	'&nbsp;&nbsp;<small><b>'. POST .'</b>&nbsp;</small>
                <input type="text" name="post" value="'. $row['post'] .'"
                    onchange="javascript:this.value=this.value.toUpperCase();" size="6" maxlength="10" disabled>&nbsp;&nbsp;&nbsp;&nbsp;
                <small><b>'. AS_CASD_MIEJSC .'</b>&nbsp;</small>
                <input type="text" name="city" value="'. $row['city'] .'"
                    onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="30" disabled>
    ';

    $result .= '</td></tr>';
    $result .= '<tr><td colspan="3" style="padding-left:12px">';
    $result .= '<small><b>'. AS_CASD_ADRES_POBYTU .'</b>&nbsp;</small>';
    $result .= '<input type="text" name="paxplaceofstay" size="88" disabled="disabled" value="' . $row['pax_place_of_stay'] . '">';

    $result .= '</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>';
 }


 	$result .= '</form>';

    return $result;

}


function tow_ub($row){
	global $global_link,$change;
	$result = '';




	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form1">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'. AS_CASADD_TOW .'</b></font></small>&nbsp;
				</td>
				<td align="right">';


	if (isset($change['summary_tow_ub'])){
				$result .= '<div style="float:right;padding:2px">
				<input type=hidden name=change[ch_summary_tow_ub] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" onclick="return validate();" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
	}else{
		$result .= '<div style="float:right;padding:3px">
				<input type=hidden name=change[summary_tow_ub] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

	}

				$result .= '</td>
			</tr>
			</table>';
 if (isset($change['summary_tow_ub']) && check_edit($row['case_id'],'summary_tow_ub')){

 		$result .= '<script language="JavaScript1.2">
		<!--
		function validate() {
			if (document.getElementById(\'contrahent_id\').value == "" || document.getElementById(\'contrahent_name\').value == "") {
				alert("'. AS_CASD_MSG_PROSZWYBRKL .'");
				document.getElementById(\'contrahent_id\').focus();
				return false;
			}
			return true;
		}
		//-->
		</script>
		';
	$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0"  >
			<tr>
				<td width="58" align="right"><small><b>'. AS_CASADD_KLIENT .':</b></small></td>
				<td nowrap><input type="hidden" name="contrahent_id_old" value="'. $row['client_id'] .'">
					<input type="text" name="contrahent_id" value="'. $row['client_id'] .'" size="5" style="text-align: center; text-align: center;" onblur="client_search_frame.location=\'GEN_contrahents_select_iframe.php?contrahent_id=\' + this.value + \'&branch_id=' . $row['coris_branch_id'] . '\';">
					<input type="text" name="contrahent_name" size="30" style="font-size:9px" disabled> <input type="button" style="width: 20px" tabindex="-1" title="Wyszukaj klienta" onclick="MM_openBrWindow(\'GEN_contrahents_select_frameset.php?branch_id=' . $row['coris_branch_id'] . '\',\'\',\'width=550,height=420\')" value="&gt;">
				</td>
			</tr>
			<tr>
				<td align="right"><small><b>'. CASENO .':</b></small></td>
				<td>
					<input type="text" name="client_ref" value="'. $row['client_ref'] .'" size="26">
				</td>
			</tr>
		</table>
		';

     if ($row['client_id'] == 17241){
         include_once 'lib/lib_barclaycard.php';
         $result .= BarclaycardCase::umowa_dane($row['case_id'],0);
     }
     if ($row['client_id'] == 18589){
         include_once 'lib/lib_hansemerkur.php';
         $result .= HansemerkurCase::umowa_dane($row['case_id'],0);
     }
     if ($row['client_id'] == 14189){
 			include_once 'lib/lib_ace.php';
 			$result .= ACECase::umowa_dane($row['case_id'],0);
 		}
 		if ( $row['client_id'] == 5 ||$row['client_id'] == 7 || $row['client_id'] == 2306 || $row['client_id'] == 14500 ){
 			include_once 'lib/lib_vig.php';
 			$result .= VIGCase::umowa_dane($row['case_id'],$row['client_id'],0);
 		}
 		$result .= '<iframe name="client_search_frame" width="0" height="0" src="GEN_contrahents_select_iframe.php?contrahent_id='. $row['client_id'] .'"></iframe>';
 }else{

 	$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" >
			<tr>
				<td width="70" align="right"><small><b>'. AS_CASADD_KLIENT .':</b></small></td>
				<td><input type="text" name="contrahent_id" value="'. $row['client_id'] .'" size="5" style="text-align: center; text-align: center;" disabled>&nbsp;&nbsp;
				<input type="text" name="contrahent_name" size="30" style="font-size:9px" value="'.getContrahnetParam( $row['client_id'],'short_name').'" disabled><input type="hidden" name="contrahent_name" size="22">

				</td>
			</tr>
			<tr>
				<td width="70" align="right"><small><b>'. CASENO .':</b></small></td>
				<td><input type="text" name="client_ref" value="'. $row['client_ref'] .'" size="26" disabled></td>
			</tr>
		</table>';
 		if ($row['client_id'] == 17241){
 			include_once 'lib/lib_barclaycard.php';
 			$result .= BarclaycardCase::umowa_dane($row['case_id'],1);
 		}

 		if ($row['client_id'] == 14189){
 			include_once 'lib/lib_ace.php';
 			$result .= ACECase::umowa_dane($row['case_id'],1);
 		}

 		if ($row['client_id'] == 5 || $row['client_id'] == 7 || $row['client_id'] == 2306 || $row['client_id'] == 14500 ){
 			include_once 'lib/lib_vig.php';
 			$result .= VIGCase::umowa_dane($row['case_id'],$row['client_id'],1);
 		}

     if ($row['client_id'] == 18589){
         include_once 'lib/lib_hansemerkur.php';
         $result .= HansemerkurCase::umowa_dane($row['case_id'],1);
     }

 }

 $result .= '</form>';
	return $result;
}


function listaUser($name,$user_id=0){

		$query = "SELECT user_id, surname, name FROM coris_users WHERE active=1 ORDER BY surname, name ";


		$mysql_result = mysql_query($query );


		$result='';
		//if ($user_id>0)
			$result.= '<input type="hidden" name="'.$name.'_old" value="'.$user_id.'">';

		$result .= "<select name=\"".$name."\" id=\"".$name."\" style=\"font-size: 8pt;width:130px\" >";
		$result .= "<option value=\"0\"></option>";
				while ($row2 = mysql_fetch_array($mysql_result))
					$result .=  "<option value=\"".$row2[0]."\"  ".($row2[0]==$user_id ? 'selected' : '').">".$row2[1].", ".$row2[2]."</option>";
				$result .= "</select>";

		return $result;
}


?>