<?php
$pageName = "AS_cases_details.php";

include('include/include.php');
include('include/include_mod.php');
include('lib/lib_case.php');

//$finances_deport=array(22,100,18,154,155,4,76,115,39,26,128,16,20,202,204,73,98,26,85,121,79,87,129,123,122,218);
$finances_deport=array(118,22,100,18,102,111,154,155,4,76,115,39,26,128,16,20,202,204,73,98,26,85,121,79,87,129,123,122,218,259,263,233,255,144,10,81,316,315,21,346,38,261,366,352,342,407); // zakladka 'Finanse'
$finances_deport2=array(22,100,18,154,155,4,76,115,79,26,39,116,114,73,218,407);  // faktury odes?ane

$access_argoss_case=array(4,16,76); // do spraw argos
$access_branch_change=array(76,4,18, 26, 39, 100, 233, 315,38,261,352); // zmiana oddzialu


$raport = '';
$result  ='';

//$DBase = new DbTool();
$mod = getValue('mod');
$case_id=getValue('case_id');
if ( !(is_numeric($case_id) && $case_id>0) ){
	echo "Error case_id=".$case_id;
	exit();
}

$super_case_action = getValue('super_case_action');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $super_case_action== 'opencase'){
	openCase($case_id,0);

}


$action = getValue('action');

if ( in_array(Application::getCurrentUser(),$access_branch_change)){
    $branch_action = getValue('branch_action');
    if ($branch_action == 'save_new_branch'){
        $new_branch_id = intval(getValue('new_branch_id'));
        if ($new_branch_id > 0){
            $query = "UPDATE coris_assistance_cases SET coris_branch_id='$new_branch_id' WHERE case_id = '$case_id' ";
            mysql_query($query);

        }
    }

}


$global_link= $pageName.'?case_id='.$case_id.'&mod='.$mod;
$change = isset($_POST['change']) ? $_POST['change'] : null;

if ($mod=='')	{
		include('mod/AS_cases_summary.php');
	}else if ($mod=='settings')	{
		include('mod/AS_cases_settings.php');
	}else if ($mod=='doc')	{
		include('mod/AS_cases_documents.php');
	}else if ($mod=='doc2')	{
		include('mod/AS_cases_documents2.php');
	}else if ($mod=='rez')	{
		include('mod/AS_cases_rezerwa.php');
	}else if ($mod=='claims')	{
		include('mod/AS_cases_claims.php');
	}else if ($mod=='log')	{
		include('mod/AS_cases_log.php');
	}else if ($mod=='cardif_annonce')	{
		include('mod/AS_cardif_annonce.php');
	}else if ($mod=='todos')	{
		include('mod/AS_cases_todos.php');
	}else if ($mod=='finances')	{
		include('mod/AS_cases_finances.php');
	}else if ($mod=='inv_send')	{
		include('mod/AS_cases_inv_send.php');
	}else if ($mod=='rez_nhc')	{
		include('mod/AS_cases_rez_nhc.php');
	}else if ($mod=='reclamation')	{
		include('mod/AS_cases_reclamation.php');
	}else if ($mod=='europa_rez')	{
		include('mod/AS_europa_rez.php');
	}else if ($mod=='europa_claims')	{
		include('mod/AS_europa_claims.php');
	}else if ($mod=='skok_rez')	{
		include('mod/AS_skok_rez.php');
	}else if ($mod=='skok_claims')	{
		include('mod/AS_skok_claims.php');
	}else if ($mod=='allianz_rez')	{
		include('mod/AS_allianz_rez.php');
	}else if ($mod=='allianz_claims')	{
		include('mod/AS_allianz_claims.php');
	}else if ($mod=='allianz_wyplaty')	{
		include('mod/AS_allianz_wyplaty.php');
	}else if ($mod=='global_rez')	{
		include('mod/AS_case_global_rez.php');
	}else if ($mod=='ann_vig')	{
		include('mod/AS_vig_announce.php');
	}else if ($mod=='compensa_claims')	{
		include('mod/AS_vig_claims.php');
	}else if ($mod=='voyage_claims')	{
		include('mod/AS_voyage_claims.php');
	}else if ($mod=='barclaycard_claims')	{
		include('mod/AS_barclaycard_claims.php');
	}else if ($mod=='barclaycard_rez')	{
		include('mod/AS_barclaycard_rez.php');
	}else if ($mod=='chubb_rez')	{
		include('mod/AS_chubb_rez.php');
	}else if ($mod=='gothaer_claims')	{
		include('mod/AS_gothaer_claims.php');
	}else if ($mod=='branch_change' && in_array(Application::getCurrentUser(),$access_branch_change))	{
		include('mod/AS_cases_branch_change.php');
	}else if ($mod=='chubba_claims' )	{
		include('mod/AS_chubba_claims.php');
	}


if (function_exists('module_update')){
	$raport .= module_update();
}

$row_case = row_case_info($case_id);

if ($row_case['type_id']==1 || $row_case['type_id']==5){
	$title_page = $row_case['marka_model'].', '.$row_case['nr_rej'].' ['.$row_case['number'].'/'. substr($row_case['year'],2,2) .'/'.$row_case['type_id'].'/'.$row_case['client_id'].'] - '.AS_CASD_TECZKA;
}else{
	$title_page = $row_case['paxsurname'].', '.$row_case['paxname'].' ['.$row_case['number'].'/'. substr($row_case['year'],2,2) .'/'.$row_case['type_id'].'/'.$row_case['client_id'].'] - '.AS_CASD_TECZKA;
}

html_start($title_page,'');//onload="focus();"
//html_start_utf($title_page,'');//onload="focus();"

$result  .= '<div style="width: 1320px;background-color: #cccccc;">';

	$result  .= case_title($row_case);

	$result  .= menu($case_id,$row_case);


if (function_exists('module_main')){
	if ( $_SESSION['coris_branch']==2  && !($row_case['coris_branch_id'] == 2 || $row_case['coris_branch_id'] == 3) ){
			$result .= '<div align="center" style="margin:100px;color:red;"><b>ACCESS DENIED</b>';
	}else if ($row_case['client_id']==113 && !in_array($_SESSION['user_id'],$access_argoss_case) ){ //argos
		$result .= '<div align="center" style="margin:100px;color:red;"><b>BRAK DOST?PU</b>';
	}else{
		$result  .= module_main();
	}
}

	$result  .= '&nbsp;</div>';

	echo $result ;

	html_stop2();



function menu($case_id,$row){
	global $pageName,$finances_deport,$finances_deport2;

	$mod = getValue('mod');

	$sel1 = ($mod=='' || $mod=='teczka') ? 'class="selected"':'';
	$sel2 = ($mod=='settings') ? 'class="selected"':'';
	$sel3 = ($mod=='rez') ? 'class="selected"':'';
	$sel4 = ($mod=='doc') ? 'class="selected"':'';
	$sel44 = ($mod=='doc2') ? 'class="selected"':'';
	$sel5 = ($mod=='claims') ? 'class="selected"':'';
	$sel6 = ($mod=='log') ? 'class="selected"':'';
	$sel7 = ($mod=='cardif_annonce') ? 'class="selected"':'';
	$sel8 = ($mod=='todos') ? 'class="selected"':'';
	$sel9 = ($mod=='finances') ? 'class="selected"':'';
	$sel10 = ($mod=='inv_send') ? 'class="selected"':'';
	$sel11 = ($mod=='rez_nhc') ? 'class="selected"':'';
	$sel12 = ($mod=='reclamation') ? 'class="selected"':'';

	$sel13 = ($mod=='europa_claims') ? 'class="selected"':'';
	$sel131 = ($mod=='compensa_claims') ? 'class="selected"':'';
    $sel132 = ($mod=='gothaer_claims') ? 'class="selected"':'';
	$sel133 = ($mod=='barclaycard_claims') ? 'class="selected"':'';
	$sel134 = ($mod=='barclaycard_rez') ? 'class="selected"':'';
	$sel135 = ($mod=='chubb_rez') ? 'class="selected"':'';
	$sel14 = ($mod=='europa_rez') ? 'class="selected"':'';

	$sel15 = ($mod=='skok_claims') ? 'class="selected"':'';
	$sel16 = ($mod=='skok_rez') ? 'class="selected"':'';

	$sel20 = ($mod=='allianz_claims') ? 'class="selected"':'';
	$sel21 = ($mod=='allianz_rez') ? 'class="selected"':'';
	$sel22 = ($mod=='allianz_wyplaty') ? 'class="selected"':'';

	$se28 = ($mod=='global_rez') ? 'class="selected"':'';
	$sel19 = ($mod=='ann_vig') ? 'class="selected"':'';
	$sel300 = ($mod=='chubba_claims') ? 'class="selected"':'';
    $sel1334 = ($mod=='voyage_claims') ? 'class="selected"':'';


	$result = '<div style="clear:both;"></div>
	<div id="menu_poziome"><ol>';
		$result .= '<li '.$sel1.'><a class="menu" href="'.$pageName.'?case_id='.$case_id.'">'.AS_TITLE_TECZKA.'</a></li>';
		$result .= '<li '.$sel2.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=settings">'.AC_CASE_SETTINGS.'</a></li>';
//		$result .= '<li '.$sel4.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=doc">'.AS_TITLE_DOKUM.' old</a></li>';
		$result .= '<li '.$sel44.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=doc2">'.AS_TITLE_DOKUM.'</a></li>';
		$result .= '<li '.$sel12.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=reclamation">'.AS_TITLE_REKLAM.'</a></li>';
		$result .= '<li '.$sel8.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=todos">'.MENUTASKS.'</a></li>';
	    //$result .= '<li '.$se28.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=global_rez">'.AS_TITLE_REZERWY.'</a></li>';

	if ($row['client_id'] == 7592)	{
		$result .= '<li '.$sel5.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=claims">'.MENU_CLAIMS.'</a></li>';
		$result .= '<li '.$sel3.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=rez">'.AS_TITLE_REZERWY. '</a></li>';
		$result .= '<li '.$sel6.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=log">Exporty</a></li>';
	}

	if ($row['client_id'] == 11 )	{
		$result .= '<li '.$sel13.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=europa_claims">'.MENU_CLAIMS.'</a></li>';
	}
	if ($row['client_id'] == 17241  )	{
		$result .= '<li '.$sel133.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=barclaycard_claims">'.MENU_CLAIMS.'</a></li>';
		$result .= '<li '.$sel134.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=barclaycard_rez">'.AS_TITLE_REZERWY.'</a></li>';
	}

	if ($row['client_id'] == 17708  )	{
		$result .= '<li '.$sel1334.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=voyage_claims">'.MENU_CLAIMS.'</a></li>';

	}


	if ($row['client_id'] == 14189   )	{
		$result .= '<li '.$sel300.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=chubba_claims">'.MENU_CLAIMS.'</a></li>';
	}
	if ($row['client_id'] == 17787  )	{
		$result .= '<li '.$sel135.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=chubb_rez">'.AS_TITLE_REZERWY.'</a></li>';
	}

	if ($row['client_id'] == 7  || $row['client_id'] == 5 )	{
		$result .= '<li '.$sel131.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=compensa_claims">'.MENU_CLAIMS.'</a></li>';
	}

	if ( $row['client_id'] == 496 )	{
		$result .= '<li '.$sel132.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=gothaer_claims">'.MENU_CLAIMS.'</a></li>';
	}

	if ($row['client_id'] == 2201 || $row['client_id'] == 11 )	{ //EUROPA
		//$result .= '<li '.$sel13.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=europa_claims">Roszczenia</a></li>';
		$result .= '<li '.$sel14.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=europa_rez">'.AS_TITLE_REZERWY.'</a></li>';

	}

	if ($row['client_id'] == 10 )	{ //SKOK
	//	$result .= '<li '.$sel15.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=skok_claims">Roszczenia</a></li>';
		$result .= '<li '.$sel16.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=skok_rez">'.AS_TITLE_REZERWY.' (SKOK)</a></li>';
	}

	if ($row['client_id'] == 11086)	{
		$result .= '<li '.$sel7.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=cardif_annonce">Cardif zg?oszenie</a></li>';
	}
	if ($row['client_id'] == 11170)	{ //NHC
		$result .= '<li '.$sel11.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=rez_nhc">'.AS_TITLE_MENU.'</a></li>';
	}

//	if ($row['client_id'] == 5 || $row['client_id'] == 2306 ||$row['client_id'] == 14500 )	{ //NHC
//		$result .= '<li '.$sel19.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=ann_vig">Ubezpieczenie</a></li>';
//	}

	if ($row['client_id'] == 9 )	{ //ALLIANZ
		$result .= '<li '.$sel20.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=allianz_claims">'.MENU_CLAIMS.'</a></li>';
		$result .= '<li '.$sel21.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=allianz_rez">'.AS_TITLE_REZERWY.' (AL)</a></li>';
		$result .= '<li '.$sel22.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=allianz_wyplaty">Wyp?aty</a></li>';
	}


	if (in_array($_SESSION['user_id'],$finances_deport) ){
		//if ($row['coris_branch_id'] != 2)
		 	$result .= '<li '.$sel9.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=finances">'.FK_EMAIL_FINANSE.'</a></li>';
	}
	if (in_array($_SESSION['user_id'],$finances_deport2) && ($row['client_id'] == 7592 || $row['client_id'] == 600 )){
		$result .= '<li '.$sel10.'><a  class="menu" href="'.$pageName.'?case_id='.$case_id.'&mod=inv_send">Faktury odes?ane</a></li>';
	}
	$result .= '</ol></div><div style="clear:both"></div>';


	return $result;
}


function case_title($row){
	global $access_branch_change;

    $action = getValue('action');
	$result = '<table cellpadding="2" cellspacing="0" border="0" width="100%">
						<tr height="30">
							<td width="50%" valign="top">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
									<tr>
										<td valign="top">
											<!-- <input type="hidden" name="case_id" value="'.$row['case_id'] .'">
											<input type="submit" value="'. SAVE .'" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 50px; background: yellow" title="'. AS_CASD_MSG_SAVEZM .'">&nbsp;-->

										</td>';

	 if (isset($updateOK)) {
												if ($updateOK) {
													$result .= "<td bgcolor=\"#6699cc\" align=\"center\"><font color=\"#dfdfdf\">".AS_CASD_MSG_DANZOSTZM."</font></td>";
												} else {
													$result .= "<td bgcolor=\"red\" align=\"center\"><font color=\"#dfdfdf\">".AS_CASD_MSG_BLZAP ."</font></td>";
												}
											} else {
												$result .= "<td></td>";
											}
	$result .= '</tr>
								</table>
							</td>
							<td  rowspan="2" valign="top" bgcolor="#dfdfdf" align="right" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid;">';

    $result .= '<table width="100%" border="0" heigh="50"><tr><td width="50%" valign="top" ><div style="float:left">'	;

    $result .= BRANCH . ': <b>' . $row['coris_branch'] . '</b>';

   if (in_array(Application::getCurrentUser(),$access_branch_change) && check_case_branch_change($row) ){

       if ($action == 'branch_change'){
           $result .= '<div style="margin-left:0px;margin-top:5px;">
            <form method="post" style="margin:0;padding:0;" action="AS_cases_details.php?case_id='.$row['case_id'].'"><input type="hidden" name="branch_action" value="save_new_branch">
            Nowy oddzia?:';
           $result .= print_user_coris_branch2('new_branch_id',$row['coris_branch_id']);
            $result .= '&nbsp;<input type="submit" value="Zmie?">';
           $result .= '</form></div>';
       }else{
    	    $result .= '<div style="margin-left:10px;margin-top:5px;"><a href="AS_cases_details.php?case_id='.$row['case_id'].'&action=branch_change">zmie? oddzia?</a></div>';
       }
   }
    $result .= '</div>';
    if ($action == 'branch_change'){

    }else{
        if ($row['coris_branch_id'] == 2){
            //$result .= '<div><img src="img/flaga_de.png" width="100"></div>';
            $result .= '<div style="float:left;margin-left:10px;"><img src="img/flaga_de.png" width="57"></div>';
        }else   if ($row['coris_branch_id'] == 3){
            $result .= '<div style="float:left;margin-left:10px;"><img src="img/flaga_at.png" width="57"></div>';
        }
    }
    $result .= '</td>';

	$result .= '<td width="50%" align="right" heigh="50">';
	if (!(strcmp(1, $row['attention']))) {$result .= "<font style=\"background: red; color: yellow\">UWAGA</font>";}

	if ( $row['attention2'] ==1 ) {$result .= "<font style=\"background: #6699cc; color: yellow\">".ATTENTION2."</font>";}




	//$result .= "<b><input type=\"text\" style=\"text-align:right\" name=\"case_number\" value=\"".$row['number']."\" size=\"6\" tabindex=\"-1\" onChange=\"zmien_numer_sprawy(".$row['number'].");\">/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]</b><br>";
	$result .= "<b>".$row['number']."/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]</b><br>";

	//$result .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr height=\"3\"><td></td></tr></table>";
$result .= "<font color=\"red\" title=\"\" style=\"cursor: default; font-size: 16pt;\">&nbsp;</font>";
    $result .= ($row['backoffice']) ? "<font color=\"black\" title=\"BACK-OFFICE\" style=\"cursor: default; font-size: 16pt;\">B</font>" : "";
	$result .= ($row['liquidation']) ? "<font color=\"#bdc8d1\" title=\"".AS_CASD_TECZKA_ZAL."\" style=\"cursor: default; font-size: 16pt;\">L</font>" : "";
	$result .= ($row['status_briefcase_found']) ? "<font color=\"#bdc8d1\" title=\"".($row['type_id']==1 ? DOC_FOTO : AS_CASD_TECZKA_ZAL)."\" style=\"cursor: default; font-size: 16pt;\">".($row['type_id']==1 ? 'F' : 'T')."</font>" : "";

	$result .= ($row['reclamation']) ? "<font color=\"red\" title=\"".AS_CASES_REKL."\" style=\"cursor: default; font-size: 16pt;\">R</font>" : "";
	$result .= ($row['costless']) ? "<img  src=\"img/bez-kosztow.gif\" border=\"0\" >&nbsp;" : "";
	$result .= ($row['unhandled']) ? "<img  src=\"img/bez-ryczaltu.gif\" border=\"0\" >&nbsp;" : "";
	$result .= ($row['archive']) ? "<img src=\"img/archiwum.gif\" border=\"0\" >&nbsp;" : "";
	$result .= ($row['watch']) ? "<font color=\"#bdc8d1\" face=\"webdings\" title=\"".AS_CASES_NOWEWIAD."\" style=\"cursor: default; font-size: 20pt;\">N</font>" : "";
	$result .= ($row['transport']) ? "<img  src=\"img/transport.gif\" border=\"0\" >&nbsp;" : "";
	$result .= ($row['decease']) ? "<img src=\"img/zgon.gif\" border=\"0\" >&nbsp;" : "";
	$result .= ($row['ambulatory']) ? "&nbsp;<font color=\"#bdc8d1\" title=\"".AS_CASES_AMB."\" style=\"cursor: default; font-size: 16pt;\">A</font>" : "";
	$result .= ($row['hospitalization']) ? "&nbsp;<font color=\"#bdc8d1\" title=\"".AS_CASES_HOSP."\" style=\"cursor: default; font-size: 16pt;\">H</font>" : "";


$result .= '</td></tr><tr><td colspan="2" align="right">';

$result .= '<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td align="left">';


																$result .= '	<table align="left" cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0"  >
												<tr>
													<td width="50" align="right"><small><b>'. AS_CASES_STATUS .': </b></small></td>
													<td>
														<table cellpadding="1" cellspacing="1" border="0" width="70">
															<tr height="15" align="center">
																<td bgcolor="'. (($row['status_client_notified']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_ZGLOSZSZK .'" style="border-left: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_policy_confirmed']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_POTWWAZNPOL .'" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_documentation']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_DOK .'>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_decision']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_DEC .'" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_assist_complete']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_DZASSZAK .'" style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_send']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_WYSLAC .'" style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_account_complete']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'.AS_CASES_DZRACHZAK .'" style="border: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="'. (($row['status_settled']) ? "lightgreen" : "#cccccc" ).'" width="5" title="'. AS_CASES_SPRROZL .'" style="border: #999999 1px solid; cursor: default;">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>';

													$result .= '</td>
													<td align="right"><small><b>'. AS_CASD_RED .': </b></small>&nbsp;&nbsp;';

															$query = "SELECT user_id, surname, name FROM coris_users WHERE user_id = ".$row['user_id'];


															$mysql_result = mysql_query($query );
															$row2 = mysql_fetch_array($mysql_result);
															$result .= $row2[1].", ".$row2[2];
						/*
																$result .= "<select name=\"username\" style=\"font-size: 8pt\" disabled>";
																	$result .= "<option></option>";
																	while ($row2 = mysql_fetch_array($mysql_result))
																		$result .= ($row['user_id'] == $row2[0]) ? "<option value=\"".$row2[0]."\" selected>".$row2[1].", ".$row2[2]."</option>" : "<option value=\"".$row2[0]."\">".$row2[1].", ".$row2[2]."</option>";
																	$result .= "</select>";
							*/
														$result .='
														<input type="hidden" name="user_id" value="'. $row['user_id'] .'">
													</td>
												</tr>
											</table>';
$result .= '</td></tr></table>';

	$result .='</td>
						</tr>
						<tr height="25">
							<td align="center" bgcolor="#eeeeee" style="border-top: #000000 1px solid; border-left: #000000 1px solid;">';
								if ($row['type_id']==1 || $row['type_id']==5){ // tech
									$result .= '<font color="navy" size=3><b>'. $row['marka_model'] .'<br><font size=2> '. $row['nr_rej'] .'</b></font>';
								}else{ //else
									$result .= '<font color="navy" size=3><b>'. $row['paxsurname'] .'<br><font size=2> '. $row['paxname'] .'</b></font>';
								}
							$result .= '</td>
						</tr>
					</table>';
	$result .= '<div style="clear:both; border-bottom: #000000 1px solid;"></div>';
	return $result;
}

function check_case_branch_change($row){

	$query = "SELECT count(*) FROM coris_finances_invoices_in WHERE case_id ='".$row['case_id']."' ";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$il_in = $row[0];

	$query = "SELECT count(*) FROM coris_finances_invoices_out WHERE case_id ='".$row['case_id']."' ";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$il_out = $row[0];


	if ($il_in ==0 && $il_out==0 && $row['archive']==0 && $row['status_assist_complete']==0 )
		return true;
	else
		return false;
}

function row_case_info($case_id){
	global $DBase;

	$query = "SELECT ac.case_id, ac.number, ac.year, ac.client_id, ac.type_id, ac.client_ref, ac.user_id, ac.date, ac.paxname,
                     ac.paxsurname,ac.paxsex, ac.paxdob,ac.pax_email,ac.pax_pesel,
                     acd.benSurname,acd.benName,
                     ac.policy,ac.policy_series, ac.cart_number, ac.icd10,ac.event,
                     ac.event_ng,ac.event_nwu,ac.event_npmh,ac.event_us,
                     ac.eventdate, ac.country_id, ac.city, ac.post, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease,
                     ac.archive, ac.costless, ac.unhandled, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed,
                     ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled,
                     ac.attention,ac.attention2, DATE(ac.archive_date) AS archive_date, acd.notificationdate, acd.notificationTime, acd.informer, acd.validityfrom,
                     acd.validityto, acd.policypurchasedate, acd.policypurchaselocation, acd.policyamount, acd.policycurrency_id,
                     acd.circumstances, acd.comments,ac.marka_model,ac.nr_rej,ac.vin,acd.paxphone ,acd.paxmobile,ac.adress1,ac.adress2,
                     acd.paxaddress, acd.paxpost, acd.paxcity, acd.paxcountry, acd.paxphone, acd.paxmobile, acd.validityfromDep,
                     acd.validitytoDep,ac.telefon1 ,ac.telefon2,ac.status_briefcase_found,ac.liquidation,ac.backoffice,ac.claim_handler_date,
                     ac.claim_handler_user_id, acd.ehic_no,acd.validityToEhic,acd.ehic_user_id,acd.ehic_date, ac.ID_cause, ac.status_send,
                     acd.pax_place_of_stay, cb.name AS coris_branch, cb.ID AS coris_branch_id,ac.operating_user_id,acd.purchasedate
	            FROM coris_assistance_cases_details acd, coris_assistance_cases ac
	       LEFT JOIN coris_branch cb ON cb.ID=ac.coris_branch_id
	           WHERE ac.case_id = '$case_id' AND  ac.case_id = acd.case_id AND ac.active = 1 ";

	if ($_SESSION['new_user']==1){
			$query .= " AND ac.`date` >= '2008-05-01 00:00:00' AND (ac.client_id=7592 OR ac.client_id=600 )";
	}
//	$query .= " AND ac.number = $_GET[number] AND ac.year = $_GET[year]";
	$result = mysql_query($query);
	if (!$result) {
		die ("Query Error: $query <br>".mysql_error());
	}

	if (mysql_num_rows($result) > 0 ){
			$row = mysql_fetch_array($result);
			return $row;
	}else{
		die('Case error, case_id='.$case_id);
	}
}
/*
function getCountryName($country_id,$lang='pl'){
	$query = "SELECT * FROM coris_countries WHERE country_id='$country_id' ";
	$mysql_result = mysql_query($query);
	$row = mysql_fetch_array($mysql_result);
	if ($lang=='pl')
		return $row['name'];
	else
		return $row['name'].'-'.$row['name_eng'];
}
*/

function check_update($case_id,$name){

	return array(true,'OK');
}

function check_edit($case_id,$name){

	return array(true,'OK');
}

function check_edit_archive($case_id,$name){
	global $row_case,$pageName,$case_id;

	$result = array('status' => false, 'txt' => '' );
	if ($row_case['archive'] == 1){
		$result['status'] = true;
		$result['txt'] = '<div align="center"><br>Sprawa przeniesiona do archiwum.<br> <b>Brak mo?liwo?ci edycji</b>';
		$result['txt'] .= '<br><br>Czy chcesz ponownie otworzy? spraw?? <br><form method="post"><input type="hidden" name="super_case_action" value="opencase"><input type="submit" value="Ponownie otw?rz spraw?" onCLick="return confirm(\'Czy na pewno chcesz ponownie otworzy? praw??\');"/></form>';
		$result['txt'] .= '<a href="'.$pageName.'?case_id='.$case_id.'">Powr?t do sprawy</a></div>';
	}else{

	}

	return $result;
}

  function openCase($case_id){
	$case_id=intval($case_id);
	if ($case_id > 0 ) {
		$query = "UPDATE coris_assistance_cases SET  archive = 0, archive_date = null,archive_user_id=0  WHERE coris_assistance_cases.case_id = '$case_id' ";
		$mysql_result = mysql_query($query);
		if (!$mysql_result)
			throw new Exception("Error q: " . $query . "\n" . mysql_error());

	}
}
?>