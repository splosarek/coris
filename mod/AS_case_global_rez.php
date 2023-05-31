<?php
//include('lib/lib_europa.php');

function module_update(){
	global  $pageName;
	$result ='';

	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');

	$check_js = '';
	$message = '';


 if (isset($change['ch_rezerwa_zgloszenie_europa']) && $case_id > 0  ){
	 	include_once('lib/lib_europa.php');
   		$res=check_update($case_id,'rezerwa_zgloszenie');
		if ($res[0]){


			$policy = getValue('policy');
			$policy_series = getValue('policy_series');

			$typ_umowy= getValue('typ_umowy');
			$wariant_ubezpieczenia= getValue('wariant_ubezpieczenia');
			$status_szkody= getValue('status_szkody');
			$old_status_szkody= getValue('old_status_szkody');
			$rodzaj_szkody= getValue('rodzaj_szkody');
			$biuro_podrozy= getValue('biuro_podrozy');

			//$opcje_ubezpieczenia= getValue('opcje_ubezpieczenia');
			$opcje_ubezpieczenia= $_POST['opcje_ubezpieczenia'];

			$var2 = " policy='$policy',policy_series='$policy_series' ";

			//opcje
			if (is_array($opcje_ubezpieczenia)){
				$query = "DELETE  FROM coris_europa_announce_opcje WHERE case_id ='$case_id' AND ID_opcja NOT IN (".implode(',',$opcje_ubezpieczenia).")  ";
			}else{
					$query = "DELETE  FROM coris_europa_announce_opcje WHERE case_id ='$case_id'  ";
			}
			$mysql_result = mysql_query($query);
		//	echo $query. " <br>".mysql_error();
		//	echo nl2br(print_r($opcje_ubezpieczenia,1))			;
			if (is_array($opcje_ubezpieczenia)){
				foreach ($opcje_ubezpieczenia As $poz){
					$query = "INSERT INTO coris_europa_announce_opcje  SET  case_id ='$case_id',ID_opcja='$poz' ";
					$mysql_result = mysql_query($query);
					//echo $query. " <br>".mysql_error();
				}
			}
			$var = " ID_typ_umowy='$typ_umowy', ID_wariant='$wariant_ubezpieczenia',ID_biuro_podrozy='$biuro_podrozy',ID_rodzaj='$rodzaj_szkody' ";
/////////////////////
			$qt = "SELECt case_id FROM coris_europa_announce  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);

			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO coris_europa_announce SET case_id='$case_id', $var ";

			}else{
				$query = "UPDATE coris_europa_announce SET $var  WHERE case_id='$case_id' LIMIT 1";
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


			/*$europa_case = new EuropaCase($case_id);
			//ID_status='$status_szkody'
			if ($status_szkody != $old_status_szkody)
					$europa_case->setStatus($status_szkody);*/
		}else{//error update
			echo $res[1];

		}

	}else  if (isset($change['ch_rezerwa_zgloszenie_cardiff']) && $case_id > 0  ){
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

	}else if (isset($change['ch_rezerwy_rezerwy']) && $case_id > 0  ){
		   		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){
			if ( CaseInfo::getCaseBarnch($case_id) == 1 ){

				$rezerwa_globalna_old = str_replace(',','.',getValue('rezerwa_globalna_old'));
				$rezerwa_globalna = str_replace(',','.',getValue('rezerwa_globalna'));
				$rezerwa_currency_id = getValue('rezerwa_currency_id');
				$rezerwa_currency_id_old = getValue('rezerwa_currency_id_old');
				if (getValue('rezerwa_globalna_lock') != 1)
						CaseInfo::setGLobalReserve($case_id,$rezerwa_globalna,$rezerwa_currency_id);
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

		$query2 = "SELECT * FROM coris_assistance_cases_global_reserve  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);

		if ($row_case_settings['client_id'] == 2201 || $row_case_settings['client_id'] == 11){
			include_once('lib/lib_europa.php');
			$query2 = "SELECT * FROM coris_europa_announce  WHERE case_id = '$case_id'";
			$mysql_result2 = mysql_query($query2);
			$row_case_ann_europa = mysql_fetch_array($mysql_result2);

			$result .=  '<div style="width: 990px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
				$result .=  zgloszenie_europa($row_case_settings,$row_case_ann_europa,$row_case);
			$result .=  '</div>';
		}

		if ($row_case_settings['client_id'] == 11086 ){
				include('lib/lib_cardif.php');
				$query2 = "SELECT * FROM coris_cardif_announce  WHERE case_id = '$case_id'";
				$mysql_result2 = mysql_query($query2);
				$row_case_ann_cardif = mysql_fetch_array($mysql_result2);


			$result .=  '<div style="width: 990px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
				$result .=  zgloszenie_cardiff($row_case_settings,$row_case_ann_cardif,$row_case);
			$result .=  '</div>';
		}

	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 990px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
		$result .=  rezerwy($row_case_settings,$row_case_ann);
	$result .=  '</div>';
	$result .=  '<div style="clear:both;"></div>';

	$result .=  decyzje($row_case_settings);

			$result .=  '<div style="clear:both;"></div>';


	return $result;
}


function zgloszenie_cardiff($row,$row2,$row3){



       $result='';
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_zgloszenie" id="form_zgloszenie">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Szczegó³y ubezpieczenia</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwa_zgloszenie'])){
				$result .= '<div style="float:rigth;padding:2px">
				<input type=hidden name=change[ch_rezerwa_zgloszenie_cardiff] value=1>
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


function zgloszenie_europa($row,$row2,$row3){



       $result='';
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_zgloszenie" id="form_zgloszenie">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Szczegó³y ubezpieczenia</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwa_zgloszenie'])){
				$result .= '<div style="float:rigth;padding:2px">
				<input type=hidden name=change[ch_rezerwa_zgloszenie_europa] value=1>
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
	<script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>
	<script language="JavaScript1.2">
		<!--
		function validate() {

			if (document.getElementById(\'typ_umowy\').value == 0 ) {
				alert("Prosze wyraæ typ umowy");
				document.getElementById(\'typ_umowy\').focus();
				return false;
			}
			//alert(1);

			//alert (document.getElementById(\'wariant_ubezpieczenia\').length);
			if (document.getElementById(\'wariant_ubezpieczenia\').value > 0 || document.getElementById(\'wariant_ubezpieczenia\').length < 2) {

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
					<td  align="right">
					<b>Typ umowy:</b>	</td><td>';
					$result .= EuropaCase::wysw_typy_umowy($row['client_id'],'typ_umowy',$row2['ID_typ_umowy'],0);
					$result .= '
					</td>
			</tr>';
			if ($row['client_id']==11){
				$result .= '<tr bgcolor="#AAAAAA">	<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Biuro podró¿y:</b>	</td><td>';
					$result .= EuropaCase::wysw_biura_podrozy('biuro_podrozy',$row2['ID_biuro_podrozy'],0,$row2['ID_typ_umowy'],'onChange="getWariantUmowyEuropaKod(this.value,document.getElementById(\'typ_umowy\').value,\'wariant_ubezpieczenia\',\'opcje_ubezpieczenia\');"');
						$result .= '
					</td>
					</tr>';

			$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td> ';
					 $result .= EuropaCase::wysw_wariant_umowy_kod('wariant_ubezpieczenia',$row2['ID_wariant'],0,$row2['ID_typ_umowy'],'onChange="getWariantUmowyEuropaKodOpcje(this.value,\'opcje_ubezpieczenia\');"');
							$result .= '
						</td>
					</tr><tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td> ';
					 $result .= EuropaCase::wysw_opcje_umowy_kod('opcje_ubezpieczenia',$row2['case_id'],0,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>';
			$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Zakres ubezpieczenia:</b></td><td> ';
					 $result .= EuropaCase::wysw_rodzaj_szkody($row2['case_id'],'rodzaj_szkody',$row2['ID_rodzaj'],0,$row2['ID_typ_umowy']);

							$result .= '
						</td>
					</tr>';

/*				$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Sprawa zale¿na:</b></td><td> ';
					 //$result .= EuropaCase::wysw_rodzaj_szkody('rodzaj_szkody',$row2['ID_rodzaj'],0,$row2['ID_typ_umowy']);
					 $result .= '<input type="text" id="sprawa_zal"  name="sprawa_zal" value=""  size="30" maxlength="30">';
							$result .= '
						</td>
					</tr>';

*/
			}else{
				$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td> ';
					 $result .= EuropaCase::wysw_wariant_umowy('wariant_ubezpieczenia',$row2['ID_wariant'],0,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr><tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td> ';
					 $result .= EuropaCase::wysw_opcje_umowy('opcje_ubezpieczenia',$row2['case_id'],0,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>';
			}
			/*	$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Status:</b></td><td> ';
					 $result .= EuropaCase::wysw_status('status_szkody',$row2['ID_status'],0,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>';*/
			  $result .= '<tr>
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
				$result .= EuropaCase::wysw_typy_umowy($row['client_id'],'typ_umowy',$row2['ID_typ_umowy'],1,'onChange="getWariantUmowy(this.value,\'wariant_ubezpieczenia\');"');
					$result .= '
								</td></tr>';

			if ($row['client_id']==11){
				$result .= '<tr bgcolor="#AAAAAA">	<td width="5%">&nbsp;</td>
					<td align="right">
					<b>Biuro podró¿y:</b>	</td><td>';
					$result .= EuropaCase::wysw_biura_podrozy('biuro_podrozy',$row2['ID_biuro_podrozy'],1,$row2['ID_typ_umowy'],'');
						$result .= '
					</td>
					</tr>';

			$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td> ';
					 $result .= EuropaCase::wysw_wariant_umowy_kod('wariant_ubezpieczenia',$row2['ID_wariant'],1,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr><tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td> ';
					 $result .= EuropaCase::wysw_opcje_umowy_kod('opcje_ubezpieczenia',$row2['case_id'],1,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>';
								$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Zakres ubezpieczenia:</b></td><td> ';
					 $result .= EuropaCase::wysw_rodzaj_szkody($row2['case_id'],'rodzaj_szkody',$row2['ID_rodzaj'],1,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>';

			/*	$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Sprawa zale¿na:</b></td><td> ';
					 //$result .= EuropaCase::wysw_rodzaj_szkody('rodzaj_szkody',$row2['ID_rodzaj'],0,$row2['ID_typ_umowy']);
					 $result .= '<input type="text" id="sprawa_zal"  name="sprawa_zal" value=""  size="30" maxlength="30" disabled>';
							$result .= '
						</td>
					</tr>';
					*/
			}else{
				$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Wariant umowy:</b></td><td> ';
					 $result .= EuropaCase::wysw_wariant_umowy('wariant_ubezpieczenia',$row2['ID_wariant'],1,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>
					<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td> ';
					 $result .= EuropaCase::wysw_opcje_umowy('opcje_ubezpieczenia',$row2['case_id'],1,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>';
			}


			  /*	$result .= '<tr bgcolor="#AAAAAA">
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Status:</b></td><td> ';
					 $result .= EuropaCase::wysw_status('status_szkody',$row2['ID_status'],1,$row2['ID_typ_umowy']);
							$result .= '
						</td>
					</tr>*/
			 $result .= '<tr>
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
	global $global_link,$change,$case_id,$lang;
	$decision_id=getValue('decision_id');

	$branch = CaseInfo::getCaseBarnch($case_id);
	$checkRezerwy = CaseInfo::checkGLobalReserve($case_id);

	$result .= '<script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>
	<a name="rezerwy_rezerwy"></a>
	<form method="POST" name="form_rezerwy" id="form_rezerwy" action="#rezerwy_rezerwy" style="padding:0px;margin:0px" onSubmit="return sprawdz_rezerwa_globalna()">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'.AS_TITLE_REZERWY.'</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['rezerwy_rezerwy'])){
				$result .= '<div style="float:rigth;padding:2px">
				<input type=hidden name="change[ch_rezerwy_rezerwy]" id="change[ch_rezerwy_rezerwy]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name=decision_id" id="decision_id" value="'.$decision_id.'">
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

		$rezerwa = CaseInfo::getReserve($case_id);
if (isset($change['rezerwy_rezerwy'])){
		$result .= '
		<script language="JavaScript">

			function EditContrahent(s) {
				window.open(\'AS_cases_details_expenses_position_details.php?expense_id=\'+ s+\'&branch_id=1\',\'PositionDetails\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=620,height=820,left=\'+ (screen.availWidth - 400) / 2 +\',top=\'+ 50 );
			}


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

		function sprawdz_rezerwa_globalna(){ ';
		if (  !($branch == 1 && $checkRezerwy) ){
			$result .= 'return true;';
		}

$result .= '
			rezerwa_globalna_var = 1.00 *$(\'rezerwa_globalna\').value.replace(\',\',\'.\');
			suma_rezerw_var = 1.00 *$(\'suma_rezerw\').value.replace(\',\',\'.\');
			if (rezerwa_globalna_var < suma_rezerw_var ){
					alert(\'Rezerwa globalna poni¿ej sumy rezerw!!!\');
					return false;
			}
			return true;
		}
		</script>';

if ($branch == 1 && $checkRezerwy ){

$result .='		<table cellpadding="5" cellspacing="0" border="1" align="center" width=90%>
		<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>'.AS_REZ_REZGLOB.':</b></td><td width="30%">
			<input type="hidden" name="rezerwa_globalna_lock" id="rezerwa_globalna_lock" value="0">
			<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.print_currency($row_case_ann['rezerwa_globalna']).'"  style="text-align: right;" size="15" maxlength="20"  onChange="sprawdz_rezerwa_globalna()">
			<input type="hidden" name="rezerwa_globalna_old" id="rezerwa_globalna_old" value="'.print_currency($row_case_ann['rezerwa_globalna']).'">';
				$result .= wysw_currency2('rezerwa_currency_id',$row_case_ann['currency_id'],1,'  ');
				$result .= '	<input type="hidden" name="rezerwa_currency_id_old" id="rezerwa_currency_id_old" value="'.print_currency($row_case_ann['currency_id']).'">';
			$result .= '
			</td></tr>
			<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>'.AS_REZ_REZDOWYK.':</b></td><td width="30%">
				<input type="text" name="rezerwa_do_wykorzystania" id="rezerwa_do_wykorzystania" value="'.print_currency($row_case_ann['rezerwa_globalna'] - $rezerwa['rezerwa']).'"  style="text-align: right;" size="15" maxlength="20" readonly class="disabled" >
				<input type="hidden" name="rezerwa_wykorzystana" id="rezerwa_wykorzystana" value="'.print_currency($row_case_ann['rezerwa_globalna']).'">';
				$result .= wysw_currency2('rr_currency_id',$rezerwa['currency_id'],1,'  ');
			$result .= '
			</td></tr>
		</table>	<br>';

}
$result .='	<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="30%" align="center"><b>'.AS_REZ_ZAKRESSW.'</b></td>
				<td width="17%" align="center"><b>'.AS_REZ_KWOTSW.'</b></td>
				<td width="17%" align="center"><b>'.AS_REZ_REZ.'</td>
				<td width="17%" align="center"><b>'.AS_REZ_WYK.'</td>
				 <td width="35"><b>'. AS_CASES_DEC .'</b></td>
                <td width="10"><b>'. AS_CASD_GWAR  .'</b></td>
                <td width="10"><b>'. INVOICE  .'</b></td>
                <td width="10"><b>'. FK_PAYDET_PLATN .'</b></td>
				<td width="15%" align="center"><b>'.AS_REZ_ZM.'</b></td>
		 </tr >';


$query = "SELECT 1 As rodzaj,ace.expense_id As ext_id, ace.contrahent_id, ace.amount, ace.currency_id, ace.date,
	  u.username, acpa.value,acpa.value_eng, c.name AS company, ace.client_amount ,ace.guarantee
		FROM  coris_assistance_cases_nreserve cscn,
		coris_users u, coris_finances_activities acpa, coris_contrahents c, coris_assistance_cases_expenses ace
		WHERE ace.expense_id = cscn.ID_expenses
		AND	ace.case_id = '$case_id' AND ace.active = 1 AND ace.user_id = u.user_id
		AND ace.activity_id = acpa.activity_id AND ace.contrahent_id = c.contrahent_id
		UNION
		 SELECT 2 , cecd.ID, 0, cecd.kwota_zaakceptowana, cecd.currency_id, cecd.date, u.username, cecd.note, '', '', cecd.kwota_roszczenia, 0
FROM coris_assistance_cases_nreserve cscn, coris_europa_claims_details cecd, coris_europa_claims cec, coris_users u
WHERE cec.ID_case ='$case_id'
AND cec.ID  = cecd.ID_claims
AND cscn.ID_claims = cecd.ID
AND u.user_id = cecd.ID_user
		";
			$mysql_result = mysql_query($query);
		echo "<br><br>".mysql_error();
	//	echo $query;
			$lista = array();
		//	while ($row_r=mysql_fetch_array($mysql_result)){
$zakres = '';
			$suma_rezerw = 0.0;
			while ($row_r=mysql_fetch_array($mysql_result)){
				if ($row_r['rodzaj'] == 1){//zlecenie
					$rezerwa = CaseInfo::getReserve($case_id,$row_r['ext_id']);
					$suma_rezerw += $rezerwa['rezerwa'];
					$val = ( ($lang=='en' && $row_r['value_eng'] != '' ) ? $row_r['value_eng'] : $row_r['value'] );
				  $result .= '<tr>
					<td ><b>'.((strlen($val) < 25) ? $val : substr($val, 0, 25) . "..." ).'</b>&nbsp;';

				  if ($row['client_id'] == 2201 || $row['client_id'] == 11){ // europa


				  }else if ($row['client_id'] == 11086){ //cardiff


				  }




				  	$result .= '</td>
									<td align="right"><b>'. print_currency($row_r['amount'],2,' ') .' '.($row_r['currency_id']).'</b></td>
					<td align="right"><b>'. print_currency($rezerwa['rezerwa'],2) .' '.($rezerwa['currency_id']).'</b></td>
					<td align="right"><b>'.((strlen($row['company']) < 20) ? $row_r['company'] : substr($row_r['company'], 0, 20) . "...") .'&nbsp;</b></td>
					<td align="center" style="padding:0px;"><b>'.sprawdz_decyzje($row_r['ext_id']).'</td>
					<td align="center"><b>'.sprawdz_gwarancje($row_r).'</td>
					<td align="center"><b>'.sprawdz_fakture($row_r['ext_id']).'</td>
					<td align="center"><b>'.sprawdz_platnosc($row_r['ext_id']).'</td>
					<td align="center">'. ($row_r['ext_id'] > 0 ? '<a href="javascript:EditContrahent('.$row_r['ext_id'].');">edycja</a>' : '&nbsp;').'</td>
				   </tr >';
				}else if ($row_r['rodzaj'] == 2){//roszczenie
						$rezerwa = CaseInfo::getReserve($case_id,0,$row_r['ext_id']);
						$suma_rezerw += $rezerwa['rezerwa'];
						$val =  $row_r['value'] ;

							$dane= roszczenie_sprawdz_decyzje($row_r['ext_id']);


							$decyzja = '';
							$gwarancja = '';
							$faktura = '';
							$platnosc = '';

						if ($dane['status'] == 3 ){ // pozytywna
							$decyzja = '<div style="background-color:green;color:lightyellow;padding:5px;" >Pozytywna</div>';
							$dane_dec = roszczenie_sprawdz_wyg_decyzje($row_r['ext_id']);

							if (is_array($dane_dec)){
								$gwarancja = '<img src="graphics/ico_Check.png" width="20" title="">';
								$faktura = '<img src="graphics/ico_Check.png" width="20" title="">';
							}
						}else if ($dane['status'] == 4 ){ // odmowa
							$decyzja = '<div style="background-color:#ff0000;color:white;padding:5px;"> Odmowna</div>';

						}

					  $result .= '<tr>
						<td ><b>Roszczenie: '.((strlen($val) < 25) ? $val : substr($val, 0, 25) . "..." ).'</b>&nbsp;</td>
										<td align="right"><b>'. print_currency($row_r['amount'],2,' ') .' '.($row_r['currency_id']).'</b></td>
						<td align="right"><b>'. print_currency($rezerwa['rezerwa'],2) .' '.($rezerwa['currency_id']).'</b></td>
						<td align="right"><b> Likwidacja &nbsp;</b></td>
						<td align="center"  style="padding:0px;"><b>'.$decyzja.'</td>
						<td align="center"><b>'.$gwarancja.'</td>
						<td align="center"><b>'.$faktura.'</td>
						<td align="center"><b>'.$platnosc.'</td>

						<td align="center">&nbsp;</td>
					   </tr >';
			 	}


			}
				if ($suma_rezerw > 0.0 ){
				$result .= '<tr><td colspan="2" align="right"><b>SUMA: &nbsp</b></td><td align="right"><b>'.print_currency($suma_rezerw).'</b></td><td colspan="7">&nbsp;</td></tr>';
			}
		$result .= '</table><br>';
		$result .= '<input type="hidden" name="suma_rezerw" id="suma_rezerw" value="'.$suma_rezerw.'">';



		$edit_form_action = getValue('edit_form_action');
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>'.AS_REZ_NREZ.':</b><small></td></tr>
						<tr><td colspan="4" align="right">
								<input type="button" value="Dodaj" style="font-weight: bold; " title="'. AS_CASD_MSG_DODWYK .'" onclick="window.open(\'AS_cases_details_expenses_position_add.php?case_id='.  $case_id .'&decision_id='.$decision_id.'&type_id='.  $row['type_id'] .'&tryb=rezerwy\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=500,left=\'+ (screen.availWidth - 400) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);">
								&nbsp;&nbsp;&nbsp;<input type="submit" name="reserv_add" onClick="return anuluj_rezerwe();" value="'.CANCEL.'">
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
			<script>
				function sprawdz_rezerwa_globalna(){

					return true;
				}
			</script>';

if ($branch == 1 && $checkRezerwy ){

$result .= '<table cellpadding="5" cellspacing="0" border="1" align="center" width=90%>
		<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>'.AS_REZ_REZGLOB.':</b></td><td width="30%">
			<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.print_currency($row_case_ann['rezerwa_globalna']).'"  style="text-align: right;" size="15" maxlength="20" readonly class="disabled">				';


				$result .= wysw_currency('rezerwa_currency_id',$row_case_ann['currency_id'],0,' disabled ');

			$result .= '</td></tr>
				<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>'.AS_REZ_REZDOWYK.':</b></td><td width="30%">
				<input type="text" name="rezerwa_do_wykorzystania" id="rezerwa_do_wykorzystania" value="'.print_currency($row_case_ann['rezerwa_globalna'] - $rezerwa['rezerwa']).'"  style="text-align: right;" size="15" maxlength="20" readonly class="disabled" >
				<input type="hidden" name="rezerwa_wykorzystana" id="rezerwa_wykorzystana" value="'.print_currency($row_case_ann['rezerwa_globalna']).'">';


				$result .= wysw_currency2('rr_currency_id',$rezerwa['currency_id'],0,' disabled ');


			$result .= '
			</td></tr>
		</table>	<br>';
}

$result .= '<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="35%" align="center"><b>'.AS_REZ_ZAKRESSW.'</b></td>
				<td width="17%" align="center"><b>'.AS_REZ_KWOTSW.'.</b></td>
				<td width="17%" align="center"><b>'.AS_REZ_REZ.'</td>
				<td width="17%" align="center"><b>'.AS_REZ_WYK.'</td>
				 <td width="35"><b>'. AS_CASES_DEC .'</b></td>
                <td width="10"><b>'. AS_CASD_GWAR  .'</b></td>
                <td width="10"><b>'. INVOICE  .'</b></td>
                <td width="10"><b>'. FK_PAYDET_PLATN .'</b></td>
				<td width="12%" align="center"><b>'.DATE.'</b></td>
				<td width="17%" align="center"><b>'.USER.'</b></td>

			   </tr >';

 	$query = "SELECT 1 As rodzaj,ace.expense_id As ext_id, ace.contrahent_id, ace.amount, ace.currency_id, ace.date,
	  u.username, acpa.value,acpa.value_eng, c.name AS company, ace.client_amount ,ace.guarantee
		FROM  coris_assistance_cases_nreserve cscn,
		coris_users u, coris_finances_activities acpa, coris_contrahents c, coris_assistance_cases_expenses ace
		WHERE ace.expense_id = cscn.ID_expenses
		AND	ace.case_id = '$case_id' AND ace.active = 1 AND ace.user_id = u.user_id
		AND ace.activity_id = acpa.activity_id AND ace.contrahent_id = c.contrahent_id
		UNION
		 SELECT 2 , cecd.ID, 0, cecd.kwota_zaakceptowana, cecd.currency_id, cecd.date, u.username, cecd.note, '', '', cecd.kwota_roszczenia, 0
FROM coris_assistance_cases_nreserve cscn, coris_europa_claims_details cecd, coris_europa_claims cec, coris_users u
WHERE cec.ID_case ='$case_id'
AND cec.ID  = cecd.ID_claims
AND cscn.ID_claims = cecd.ID
AND u.user_id = cecd.ID_user
		UNION
		 SELECT 3 , cecd.ID, 0, cecd.kwota_zaakceptowana, cecd.currency_id, cecd.date, u.username, cecd.note, '', '', cecd.kwota_roszczenia, 0
FROM coris_assistance_cases_nreserve cscn, coris_vig_claims_details cecd, coris_vig_claims cec, coris_users u
WHERE cec.ID_case ='$case_id'
AND cec.ID  = cecd.ID_claims
AND cscn.ID_claims = cecd.ID
AND u.user_id = cecd.ID_user
		";
 	//echo $query;
			$mysql_result = mysql_query($query);
			if (!$mysql_result) echo '<h3>'.$query.'<br>'. mysql_error().'</h3>';
			$lista = array();
			$zakres = '';
			$suma_rezerw='';
			while ($row_r=mysql_fetch_array($mysql_result)){

				if ($row_r['rodzaj'] == 1){//zlecenie
						$rezerwa = CaseInfo::getReserve($case_id,$row_r['ext_id']);
						$suma_rezerw += $rezerwa['rezerwa'];
							$val = ( ($lang=='en' && $row_r['value_eng'] != '' ) ? $row_r['value_eng'] : $row_r['value'] );
					  $result .= '<tr>
						<td ><b>'.((strlen($val) < 25) ? $val : substr($val, 0, 25) . "..." ).'</b>&nbsp;</td>
										<td align="right"><b>'. print_currency($row_r['amount'],2,' ') .' '.($row_r['currency_id']).'</b></td>
						<td align="right"><b>'. print_currency($rezerwa['rezerwa'],2) .' '.($rezerwa['currency_id']).'</b></td>
						<td align="right"><b>'.((strlen($row_r['company']) < 20) ? $row_r['company'] : substr($row_r['company'], 0, 20) . "...") .'&nbsp;</b></td>
						<td align="center" style="padding:0px;"><b>'.sprawdz_decyzje($row_r['ext_id']).'</td>
						<td align="center"><b>'.sprawdz_gwarancje($row_r).'</td>
						<td align="center"><b>'.sprawdz_fakture($row_r['ext_id']).'</td>
						<td align="center"><b>'.sprawdz_platnosc($row_r['ext_id']).'</td>
						<td align="center">'.$row_r['date'].'</td>
						<td align="center">'.$row_r['username'].'</td>
					   </tr >';
			 	}else if ($row_r['rodzaj'] == 2){//roszczenie europa
							$rezerwa = CaseInfo::getReserve($case_id,0,$row_r['ext_id']);
							$suma_rezerw += $rezerwa['rezerwa'];
							$val =  $row_r['value'] ;

							$dane= roszczenie_sprawdz_decyzje($row_r['ext_id']);

							$decyzja = '';
							$gwarancja = '';
							$faktura = '';
							$platnosc = '';

						if ($dane['status'] == 3 ){ // pozytywna
							$decyzja = '<div style="background-color:green;color:lightyellow;padding:5px;" >Pozytywna</div>';
							$dane_dec = roszczenie_sprawdz_wyg_decyzje($row_r['ext_id']);

							if (is_array($dane_dec)){
								$gwarancja = '<img src="graphics/ico_Check.png" width="20" title="">';
								$faktura = '<img src="graphics/ico_Check.png" width="20" title="">';
							}
						}else if ($dane['status'] == 4 ){ // odmowa
							$decyzja = '<div style="background-color:#ff0000;color:white;padding:5px;"> Odmowna</div>';

						}

					  $result .= '<tr>
						<td ><b>Roszczenie: '.((strlen($val) < 25) ? $val : substr($val, 0, 25) . "..." ).'</b>&nbsp;</td>
										<td align="right"><b>'. print_currency($row_r['amount'],2,' ') .' '.($row_r['currency_id']).'</b></td>
						<td align="right"><b>'. print_currency($rezerwa['rezerwa'],2) .' '.($rezerwa['currency_id']).'</b></td>
						<td align="right"><b> Likwidacja &nbsp;</b></td>
						<td align="center" style="padding:0px;"><b>'.$decyzja.'</td>
						<td align="center"><b>'.$gwarancja.'</td>
						<td align="center"><b>'.$faktura.'</td>
						<td align="center"><b>'.$platnosc.'</td>
						<td align="center">'.$row_r['date'].'</td>
						<td align="center">'.$row_r['username'].'</td>
					   </tr >';
			 	}else if ($row_r['rodzaj'] == 3){//roszczenie compensa
							$rezerwa = CaseInfo::getReserve($case_id,0,$row_r['ext_id']);
							$suma_rezerw += $rezerwa['rezerwa'];
							$val =  $row_r['value'] ;

							$dane= roszczenie_sprawdz_decyzje_vig($row_r['ext_id']);

							$decyzja = '';
							$gwarancja = '';
							$faktura = '';
							$platnosc = '';

						if ($dane['status'] == 3 ){ // pozytywna
							$decyzja = '<div style="background-color:green;color:lightyellow;padding:5px;" >Pozytywna</div>';
							$dane_dec = roszczenie_sprawdz_wyg_decyzje_vig($row_r['ext_id']);

							if (is_array($dane_dec)){
								$gwarancja = '<img src="graphics/ico_Check.png" width="20" title="">';
								$faktura = '<img src="graphics/ico_Check.png" width="20" title="">';
							}
						}else if ($dane['status'] == 4 ){ // odmowa
							$decyzja = '<div style="background-color:#ff0000;color:white;padding:5px;"> Odmowna</div>';

						}

					  $result .= '<tr>
						<td ><b>Roszczenie: '.((strlen($val) < 25) ? $val : substr($val, 0, 25) . "..." ).'</b>&nbsp;</td>
										<td align="right"><b>'. print_currency($row_r['amount'],2,' ') .' '.($row_r['currency_id']).'</b></td>
						<td align="right"><b>'. print_currency($rezerwa['rezerwa'],2) .' '.($rezerwa['currency_id']).'</b></td>
						<td align="right"><b> Likwidacja &nbsp;</b></td>
						<td align="center" style="padding:0px;"><b>'.$decyzja.'</td>
						<td align="center"><b>'.$gwarancja.'</td>
						<td align="center"><b>'.$faktura.'</td>
						<td align="center"><b>'.$platnosc.'</td>
						<td align="center">'.$row_r['date'].'</td>
						<td align="center">'.$row_r['username'].'</td>
					   </tr >';
			 	}

			}
			if ($suma_rezerw > 0.0 ){
				$result .= '<tr><td colspan="2" align="right"><b>SUMA: &nbsp</b></td><td align="right"><b>'.print_currency($suma_rezerw).'</b></td><td colspan="7">&nbsp;</td></tr>';
			}


		$result .= '</table><br>';
	}
	$result .= '</form>';
	return $result;
}


    function StrTrim($string, $length) {
        return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
    }


    function sprawdz_gwarancje($row_r){

    	if ($row_r['guarantee']==1) {
    		$query = "SELECT * FROM coris_assistance_cases_expenses_guarantee  WHERE  ID_expense='".$row_r['ext_id']."'  ORDER BY ID DESC LIMIT 1";
    		$mr = mysql_query($query);
    		if (mysql_num_rows($mr) > 0){
    			$row2 = mysql_fetch_array($mr);



    			$text = Application::getUserName($row2['ID_user'] ) .' '.$row2['date'];
    			return '<img src="graphics/ico_Check.png" width="20" title="'.$text.'">';
    		}
    			//return '<div style="background-color:green" title="'.$rr['decision_date'].' '.Application::getUserName($rr['user_id']).', '.htmlspecialchars($rr['note']).'">'.$rr['value'].'</div>';

    	}else{
    		return '&nbsp;';

    	}

    }

    function  sprawdz_fakture($expense_id)  {
		$query= "SELECT invoice_in_id,user_id ,date FROM coris_finances_invoices_in WHERE expense_id='$expense_id' AND active=1 ";
		$mysql_result = mysql_query($query);
		$ilosc= mysql_num_rows($mysql_result);
		if ($ilosc == 0){
			//return NO;
			return '&nbsp;';
		}else{
			//return YES. ($ilosc>1  ? '('.$ilosc.')' : '');
			$row2 = mysql_fetch_array($mysql_result);
			$text = Application::getUserName($row2['user_id'] ) .' '.$row2['date'];
			return '<img src="graphics/ico_Check.png" width="20" title="'.$text.'">';
		}
	}

	function  sprawdz_platnosc($expense_id)  {

		/*
		 $query_payment = "SELECT  ID_payment FROM coris_finances_payments_position WHERE ID_invoice_in='$id_invoice_in' ";
        	//echo $query_payment ;
        	$mysql_result_payment = mysql_query($query_payment);
        	while($row_payment = mysql_fetch_array($mysql_result_payment)){
        		echo '<input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'FK_payments_details.php?payment_id='.$row_payment['ID_payment'].'\',\'\',\'scrollbars=yes,resizable=yes,width=700,height=720,left=20,top=20\')">&nbsp;&nbsp;';
        	}
		 */
		$query= "SELECT payment_confirmed,invoice_in_id FROM coris_finances_invoices_in WHERE expense_id='$expense_id' ";
	//	echo $query;
		$mysql_result = mysql_query($query);
		$ilosc= mysql_num_rows($mysql_result);
		if ($ilosc == 0){
				return '&nbsp;';
		}
		$res = true;
		$daty_zaplaty = array();
		while ( $row=mysql_fetch_array($mysql_result) ){
			if ($row['payment_confirmed']==0){
				$res = false;
			}else{
				$id_invoice_in = $row['invoice_in_id'];
				$query_payment = "SELECT  cfp.settled_operation_date
					FROM  coris_finances_payments cfp,coris_finances_payments_position  cfpp
					WHERE 	cfp.payment_id = cfpp.ID_payment
						AND cfpp.ID_invoice_in='$id_invoice_in' ";
			//	echo $query_payment;
				$mysql_result_payment = mysql_query($query_payment);
				while($row_payment = mysql_fetch_array($mysql_result_payment)){
        			$daty_zaplaty[] = $row_payment['settled_operation_date'];
        		}

			}
		}

		if ($res){
			return '<img src="graphics/ico_Check.png" width="20" title="'.implode(', ',$daty_zaplaty).'">';
			//return YES;
		}else{
			//return NO;
			return '&nbsp;';
		}
	}


	function roszczenie_sprawdz_decyzje($claims_id){

		$query = "SELECT * FROM coris_europa_claims_details

			WHERE coris_europa_claims_details.ID='$claims_id'  ";
		$mr = mysql_query($query);

		if (mysql_num_rows($mr) == 0){
				return '&nbsp;';
		}else{
				$rr = mysql_fetch_array($mr);
				return $rr;
		}
	}

	function roszczenie_sprawdz_wyg_decyzje($claims_id){

		$query = "SELECT * FROM  coris_europa_claims_details

			WHERE ID_claims_details='$claims_id'  ";
		$mr = mysql_query($query);

		if (mysql_num_rows($mr) == 0){
				return '&nbsp;';
		}else{
				$rr = mysql_fetch_array($mr);
				return $rr;
		}
	}
	function roszczenie_sprawdz_decyzje_vig($claims_id){

		$query = "SELECT * FROM coris_vig_claims_details

			WHERE ID='$claims_id'  ";
		$mr = mysql_query($query);

		if (mysql_num_rows($mr) == 0){
				return '&nbsp;';
		}else{
				$rr = mysql_fetch_array($mr);
				return $rr;
		}
	}

	function roszczenie_sprawdz_wyg_decyzje_vig($claims_id){

		$query = "SELECT * FROM  coris_vig_decisions_details

			WHERE ID_claims_details='$claims_id'  ";
		$mr = mysql_query($query);

		if (mysql_num_rows($mr) == 0){
				return '&nbsp;';
		}else{
				$rr = mysql_fetch_array($mr);
				return $rr;
		}
	}

	function sprawdz_decyzje($expense_id){

			$query = "SELECT coris_assistance_cases_decisions.*,coris_assistance_cases_decisions_types.value FROM coris_assistance_cases_decisions,coris_assistance_cases_decisions_types

				WHERE coris_assistance_cases_decisions.ID_expenses='$expense_id' AND coris_assistance_cases_decisions.active=1
				AND coris_assistance_cases_decisions.type_id = coris_assistance_cases_decisions_types.type_id
				ORDER BY decision_id desc ";
			$mr = mysql_query($query);
			//echo $query;
			if (mysql_num_rows($mr) == 0){
					return '&nbsp;';
			}else{
					$rr = mysql_fetch_array($mr);

					if ( $rr['type_id'] == 4 || $rr['type_id'] == 9 || $rr['type_id'] == 10  )	//odmowa
						return '<div style="background-color:#ff0000;color:white;padding:5px;" title="'.$rr['decision_date'].' '.Application::getUserName($rr['user_id']).', '.htmlspecialchars($rr['note'],ENT_QUOTES ,'ISO-8859-1').'">'.$rr['value'].'</div>';
					else	if ( $rr['type_id'] == '3' ) //warunkowa zgoda
						return '<div style="background-color:darkorange;color:white;padding:5px;" title="'.$rr['decision_date'].' '.Application::getUserName($rr['user_id']).', '.htmlspecialchars($rr['note'],ENT_QUOTES ,'ISO-8859-1').'">'.$rr['value'].'</div>';
					else
						return '<div style="background-color:green;color:lightyellow;;padding:5px;" title="'.$rr['decision_date'].' '.Application::getUserName($rr['user_id']).', '.htmlspecialchars($rr['note'],ENT_QUOTES ,'ISO-8859-1').'">'.$rr['value'].'</div>';
			}

	}



	function decyzje($row){
	    $result='';
	global $global_link,$change,$case_id;

	$query = "SELECT acd.decision_id, acd.type_id, acd.amount, acd.currency_id, acd.decision_date, acd.note, acd.date, acdt.value, u.name, u.surname,u.initials
			FROM coris_assistance_cases_decisions acd, coris_users u, coris_assistance_cases_decisions_types acdt
				WHERE case_id = '$case_id'
				AND acd.ID_expenses=0
				AND acd.user_id = u.user_id AND acd.type_id = acdt.type_id AND acd.active = 1 ORDER BY decision_date DESC";

	$mysql_result = mysql_query($query);

	if (mysql_num_rows($mysql_result) == 0) return'';
	$result .=  '<div style="width: 300px;float:left;padding-right:10px;border: #6699cc 1px solid;height:auto;background-color: #DFDFDF;margin:5px">';



	$result .= '<form method="POST" action="" style="padding:0px;margin:0px" name="form_decyzje_action" id="form_decyzje_action">';
		$result .= '<input type="hidden" id="form_decyzje_action_input" name="form_decyzje_action_input" value="1">';
		$result .= '<input type="hidden" id="form_decyzje_action_dec_id" name="decision_id" value="0">';
	$result .= '</form>';

	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_decyzje" id="form_decyzje">

	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'. AS_CASD_DEC .' (stare)</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['settings_decyzje'])){
				$result .= '<div style="float:rigth;padding:2px">
				<input type=hidden name=change[ch_settings_decyzje] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>

				<input type="hidden" name="action" id="action" value="">
				<input type="hidden" name="lista"  id="lista" value="">
				</div>';
	}else{
		$result .= '<div style="float:rigth;padding:3px">
				<input type=hidden name=change[settings_decyzje] value=1>
				<input type="hidden" name="edit_form" value="1">
				<!--<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;">--></div>';

	}

				$result .= '</td>
			</tr>
			</table>';

	$result .= '

		 <script language="javascript">
        <!--
		function NewDecision() {
			var decision = window.open(\'AS_cases_details_insurance_decisions_add.php?case_id='.$case_id.'\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=240,left=\'+ (screen.availWidth - 500) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 220) / 2);
			//Niepotrzebne, bo nie dzia3a
			//decision.opener = parent;
		}
			function checkboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

            function validate() {
                return true;
            }

			// TODO: Poprawia - aby nie by3o "for"
            function move(s) {
				e = window.event;
				var keyInfo = String.fromCharCode(e.keyCode);

				if (e[\'keyCode\'] != 9 && e[\'keyCode\'] != 16 && e[\'keyCode\'] != 8) {
					for (var i = 0; i < form1.length; i++) {
						if (s.name == form1.elements[i].name) {
							if ((form1.elements[i].value.length == 2)) {
								form1.elements[i+1].focus();
								return false;
							}
						}
					}
				}
            }

			function remove(s) {
				e = window.event;
				var keyInfo = String.fromCharCode(e.keyCode);

				if (e[\'keyCode\'] == 8) {
					for (var i = 0; i < form1.length; i++) {
						if (s.name == form1.elements[i].name) {
							if ((form1.elements[i].value.length == 0)) {
								form1.elements[i-1].focus();
								var rng = form1.elements[i-1].createTextRange();
								rng.select();
								return false;
							}
						}
					}
				}
			}

            function removeDecisions() {
                var values = "";
				//checkedForm = decisions_frame.form1;
				checkedForm = document.getElementById(\'form_decyzje\');

				lista = document.getElementsByName(\'decisioncheck\');
                if (lista.length== 1 && lista[0].checked ) {
                    values = lista[0].value;
                } else if (checkedForm.elements.length > 1) {
                    for (var i = 0; i < lista.length; i++)
                        if (lista[i].checked)
                            values += lista[i].value + ",";
                }
                if (values == "") {
                    alert("'. AS_CASD_BRZAZDECDOUS .'");
                    return;
                } else {
                    if (!confirm("'. AS_CASD_CZYCHCUSDEC .'"))
                        return;

                    document.getElementById(\'action\').value = \'remove\';
                    document.getElementById(\'lista\').value = values ;
                     document.getElementById(\'form_decyzje\').submit();
					//var url = "AS_cases_details_insurance_decisions.php?action=remove&case_id='. $case_id.'&decision_id="+ values;
                    //decisions_frame.location = url;
                }
            }
        //-->
        </script>
		';
if (isset($change['settings_decyzje'])){


		$result .= '<table cellpadding="2" cellspacing="1" border="0" >
                                    <tr height="20" bgcolor="#eeeeee">
                                        <td  align="center" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                         <!--   <input style="width: 20px;" type="button" value="+" onclick="NewDecision();" title="'. AS_CASD_NOWDEC .'"> -->
									<!--   		<input style="width: 20px;" type="button" value="-" onclick="removeDecisions()" title="'. AS_CASD_USUNDEC .'"> -->
                                        </td>

                                    </tr></table>';

		$result .= '<table width="100%" cellpadding="2" cellspacing="1" border="0">';


	$i = 1;
	while ($row2 = mysql_fetch_array($mysql_result)) {


		$result .= '<tr bgcolor="'. (($i % 2) ? "#eeeeee" : "#e0e0e0").'" style="cursor: default" title="'.  "Data: ".$row2['date']."\nWprowadzaj±cy: ".$row2['name']."  ".$row2['surname'].'">
				<td width="5%" align="center"><input style="background: lightyellow" type="checkbox" name="decisioncheck" value="'. $row2['decision_id'] .'"></td>
				<td width="24%" align="center"><font color="#6699cc">'. $row2['value'] .' </font></td>
				<td width="20%" align="right"><font color="navy">'. str_replace(".", ",", $row2['amount']) .'</font></td>
				<td width="6%">'. $row2['currency_id'] .'</td>
				<td width="30%" align="center"><small>'. $row2['decision_date'] .'</small></td>
				<td width="15%" align="center"><font color="#6699cc">'. $row2['initials'] . '</font></td>
			</tr>
			<tr>
				<td bgcolor="lightyellow" colspan="6"><font color="#999999"><i><small>'. $row2['note'] .'</small></i></td>
			</tr>';
		$i++;
	}

		$result .= '</table>';
}else{
	$result .= '<table width="100%" cellpadding="2" cellspacing="1" border="0">';


	$i = 1;
	while ($row2 = mysql_fetch_array($mysql_result)) {
		$result .= '<tr bgcolor="'. (($i % 2) ? "#eeeeee" : "#e0e0e0").'" style="cursor: default" title="'.  "Data: ".$row2['date']."\nWprowadzaj±cy: ".$row2['name']."  ".$row2['surname'].'">

				<td width="24%" align="center"><font color="#6699cc">'. $row2['value'] .' </font></td>
				<td width="20%" align="right"><font color="navy">'. str_replace(".", ",", $row2['amount']) .'</font></td>
				<td width="6%">'. $row2['currency_id'] .'</td>
				<td width="30%" align="center"><small>'. $row2['decision_date'] .'</small></td>
				<td width="15%" align="center"><font color="#6699cc">'. $row2['initials'] .'</font></td>
			</tr>
			<tr>
				<td bgcolor="lightyellow" colspan="6"><font color="#999999"><i><small>'. $row2['note'] .'</small></i></td>
			</tr>';
		$i++;
	}

		$result .= '</table>';


}

$result .= '</form>';
$result .=  '</div>';
return $result;
}
?>