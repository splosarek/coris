<?php
include('lib/lib_allianz.php');

function module_update(){			
	global  $pageName;
	$result ='';
	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');
		
	$check_js = '';
	$message = '';
	
	/* if (isset($change['ch_rezerwa_zgloszenie']) && $case_id > 0  ){		
	 
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
				
			
			$europa_case = new EuropaCase($case_id);
			//ID_status='$status_szkody'
			if ($status_szkody != $old_status_szkody)
					$europa_case->setStatus($status_szkody);		
		}else{//error update
			echo $res[1];
			
		}		

	}else if (isset($change['ch_rezerwy_rezerwy']) && $case_id > 0  ){		
		   		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){									
			$rezerwa_globalna_old = str_replace(',','.',getValue('rezerwa_globalna_old'));
			$rezerwa_globalna = str_replace(',','.',getValue('rezerwa_globalna'));
			$rezerwa_currency_id = getValue('rezerwa_currency_id');	
			$rezerwa_currency_id_old = getValue('rezerwa_currency_id_old');	
			
			if ($rezerwa_globalna != $rezerwa_globalna_old || $rezerwa_currency_id != $rezerwa_currency_id_old){						
					EuropaCase::aktualizujRezerweGlobalna($case_id,$rezerwa_globalna_old,$rezerwa_globalna,$rezerwa_currency_id);		
			}			
		}else{//error update
			echo $res[1];			
		}					
	}	
	*/
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
		
		$query2 = "SELECT * FROM coris_allianz_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		
	if ($row_case_settings['client_id'] == 9 ){
		$result .=  '<div style="width: 890px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		//	$result .=  zgloszenie($row_case_settings,$row_case_ann,$row_case);
		$result  .= 'TODO';	
		$result .=  '</div>';	
		
	
	/*	$result .=  '<div style="clear:both;"></div>';
		$result .=  '<div style="width: 790px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
			$result .=  rezerwy($row_case_settings,$row_case_ann);	
		$result .=  '</div>';	
		$result .=  '<div style="clear:both;"></div>';
					
		*/	
				$result .=  '<div style="clear:both;"></div>';		
	}else{
		$result = '<div style="width: 790px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
		<br><br><br><br><br><div align="center"><b>Tylko dla spraw TU ALLIANZ</b></div>
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
						
				$dane_ub = AllianzCase::ubezpieczenie($row2['ID_kolo']);
				$franszyza_info= 'Franszyza '.($dane_ub['franszyza_rodzaj']==1 ? 'Integralna' : '').($dane_ub['franszyza_rodzaj']==2 ? 'Redukcyjna' : '').' '.print_currency($dane_ub['franszyza_kwota']).' PLN';
				$suma_ubezpieczenia= print_currency($dane_ub['suma_ubezpieczenia']) ;
				$lista_gatunkow = AllianzCase::listaGatunkowZwierzatWSprawie($row2['case_id']);
				
if (isset($change['rezerwa_zgloszenie'])){
	$result .= calendar();
	
	$result .= '
	<script language="JavaScript1.2" src="Scripts/js_allianz_announce.js"></script>
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
		$result .= 'TODO';
		return $result;	
					
		$result .= '
	  <table cellpadding="5" cellspacing="1" border="0"  align="center" >
					<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Nr protoko³u:</b>	</td><td><input type="text" size="50" name="nr_protokolu" id="nr_protokolu" class="disabled" value="'.$row2['nr_protokolu'].'" readonly></td>
				</tr>
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Ko³o ³owieckie :</b>	</td><td>';					 					 					
						$result .= AllianzCase::getKolaLowieckie('kolo_id',$row2['ID_kolo'],1,'onChange="getKoloLowieckie(this.value);"');
						

						$result .= '<hr><table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><input type="text" size="50" name="kolo_nazwa" id="kolo_nazwa" class="required" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="required" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="11" name="kolo_kod" id="kolo_kod" class="required" readonly> <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="required" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="required" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="required" style="text-align:right;" value="" readonly> PLN, <span id="franszyza_info"></span></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr konta bankowego:</td><td><input type="text" size="50" name="kolo_konto" id="kolo_konto" class="required" ></td></tr>';
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				
				<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Lokalizacja szkody :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Województwo: </td><td>'.AllianzCase::getWojewodztwa('woj_id',0,0,'onChange="getPowiaty(this.value);"').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powiat: </td><td>'.AllianzCase::getPowiatyRegister().'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Gmina: </td><td>'.AllianzCase::getGminyRegister().'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Wie¶/miasto: </td><td><input type="text" size="50" name="lok_miejscowosc" id="lok_miejscowosc" class="required" ></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Nr dzia³ki: </td><td><input type="text" size="50" name="nr_dzialki" id="nr_dzialki" class="required" ></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Obwód ³owiecki nr: </td><td><input type="text" size="50" name="obwod_lowiecki" id="obwod_lowiecki" class="required" ></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Poszkodowany :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>Nazwisko: </td><td><input type="text" size="50" name="poszk_nazwisko" id="poszk_nazwisko" class="required" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Imiê: </td><td><input type="text" size="50" name="poszk_imie" id="poszk_imie" class="required" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Adres: </td><td><input type="text" size="50" name="poszk_adres" id="poszk_adres" class="required" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Kod Miejscowo¶æ:</td><td><input type="text" size="11" name="poszk_kod" id="poszk_kod" class="required" > <input type="text" size="35" name="poszk_miejscowosc" id="poszk_miejscowosc" class="required" ></td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
			<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Szkoda :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zg³oszenia szkody<br>do ko³a ³owieckiego: </td><td><input type="text" size="11" name="data_zgloszenia_do_kola" id="data_zgloszenia_do_kola" class="required" >  (YYYY-MM-DD)</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Szacuj±cy szkodê: </td><td>'.AllianzCase::getSzacujacyRegister().' Imiê Nazwisko: <input type="text" size="25" name="szacujacy_nazwa" id="szacujacy_nazwa" class="required" readonly> Tel:<input type="text" size="12" name="szacujacy_tel" id="szacujacy_tel" class="required" ></td></tr>';
														
							$result .= '<tr bgcolor="#BBBBBB"><td>Gatunek zwierzyny, <br>który wyrz±dzi³ szkodê: </td><td>
								<select multiple="multiple" class="required" size="6" name="gatunek_zwierzyny[]" onClick="sprawdz_zwierzyne()">
									<option value="1">Dzik</option>
									<option value="2">£o¶</option>
									<option value="3">Jeleñ</option>
									<option value="4">Daniel</option>
									<option value="5">Sarna</option>
									<option value="6">Inne</option>
								</select> Inne: <input type="text" size="35" name="gatunek_zwierzyny_inne" id="gatunek_zwierzyny_inne" class="required" >
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Rodzaj, stan i jako¶æ upraw: </td><td><textarea cols="80" rows="5"  class="required" name="rodzaj_stan_upraw" id="rodzaj_stan_upraw"></textarea></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
		<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Roszczenie :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>RWS: </td><td><input type="text" size="10" name="rws" id="rws" class="required" > PLN </td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Kwota roszczenia: </td><td><input type="text" size="10" name="kwota_roszczenia" id="kwota_roszczenia" class="required" > PLN</td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >		
		</table>							
	';
	}else{
		
		$result .= '
	  <table cellpadding="5" cellspacing="1" border="0"  align="center" >
					<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Nr protoko³u:</b>	</td><td><input type="text" size="50" name="nr_protokolu" id="nr_protokolu" class="disabled" value="'.$row2['nr_protokolu'].'" readonly></td>
				</tr>
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Ko³o ³owieckie :</b>	</td><td>';					 					 					
						$result .= AllianzCase::getKolaLowieckie('kolo_id',$row2['ID_kolo'],1,'');
						

						$result .= '<hr><table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><input type="text" size="50" name="kolo_nazwa" id="kolo_nazwa" class="disabled" readonly value="'.$row2['kolo_nazwa'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="disabled" readonly value="'.$row2['kolo_adres'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="11" name="kolo_kod" id="kolo_kod" class="disabled" readonly value="'.$row2['kolo_kod'].'"> <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="disabled" readonly value="'.$row2['kolo_miejscowosc'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="disabled" readonly value="'.$row2['kolo_zo'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="disabled" style="text-align:right;" value="'.$suma_ubezpieczenia.'" readonly> PLN, <span id="franszyza_info">'.$franszyza_info.'</span></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr konta bankowego:</td><td><input type="text" size="50" name="kolo_konto" id="kolo_konto" class="disabled" value="'.$row2['kolo_konto'].'"></td></tr>';
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				
				<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Lokalizacja szkody :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Województwo: </td><td>'.AllianzCase::getWojewodztwa('woj_id',$row2['szko_woj_id'],1,'').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powiat: </td><td>'.AllianzCase::getPowiaty('pow_id',$row2['szko_woj_id'],$row2['szk_pow_id'],1,'').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Gmina: </td><td>'.AllianzCase::getGminy('gmina_id',$row2['szko_woj_id'],$row2['szk_pow_id'],$row2['szk_gmina_id'],1,'').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Wie¶/miasto: </td><td><input type="text" size="50" name="lok_miejscowosc" id="lok_miejscowosc" class="disabled" value="'.$row2['szk_lok_miejscowosc'].'" readonly></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Nr dzia³ki: </td><td><input type="text" size="50" name="nr_dzialki" id="nr_dzialki" class="disabled" value="'.$row2['szk_nr_dzialki'].'" readonly></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Obwód ³owiecki nr: </td><td><input type="text" size="50" name="obwod_lowiecki" id="obwod_lowiecki" class="disabled" value="'.$row2['szk_obwod_lowiecki'].'" readonly></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Poszkodowany :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>Nazwisko: </td><td><input type="text" size="50" name="poszk_nazwisko" id="poszk_nazwisko" class="disabled" readonly value="'.$row['paxsurname'].'" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Imiê: </td><td><input type="text" size="50" name="poszk_imie" id="poszk_imie" class="disabled" readonly value="'.$row['paxname'].'" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Adres: </td><td><input type="text" size="50" name="poszk_adres" id="poszk_adres"  class="disabled" readonly value="'.$row3['paxaddress'].'" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Kod Miejscowo¶æ:</td><td><input type="text" size="11" name="poszk_kod" id="poszk_kod"  class="disabled" readonly value="'.$row3['paxpost'].'"> <input type="text" size="35" name="poszk_miejscowosc" id="poszk_miejscowosc"  class="disabled" readonly value="'.$row3['paxcity'].'" ></td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
			<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Szkoda :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zg³oszenia szkody<br>do ko³a ³owieckiego: </td><td><input type="text" size="11" name="data_zgloszenia_do_kola" id="data_zgloszenia_do_kola" class="disabled" value="'.$row2['data_zgloszenia_do_kola'].'" readonly >  </td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Szacuj±cy szkodê: </td><td>'.AllianzCase::getSzacujacy($row2['ID_kolo'],$row2['ID_szacujacy'],1).' Imiê Nazwisko: <input type="text" size="25" name="szacujacy_nazwa" id="szacujacy_nazwa" class="disabled" readonly value="'.$row2['szacujacy_imie_nazwisko'].'"> Tel:<input type="text" size="12" name="szacujacy_tel" id="szacujacy_tel" class="disabled" readonly value="'.$row2['szacujacy_tel'].'" ></td></tr>';
														
							$result .= '<tr bgcolor="#BBBBBB"><td>Gatunek zwierzyny, <br>który wyrz±dzi³ szkodê: </td><td>
								<select multiple="multiple" class="disabled" size="6" name="gatunek_zwierzyny[]" onClick="sprawdz_zwierzyne()" disabled>';
							foreach ($lista_gatunkow As $poz){
									$result .= '<option value="'.$poz['ID'].'"  '.($poz['selected']==1?' selected ' : '' ).'>'.$poz['nazwa'].'</option>';								
							}
									
								$result .= '</select> Inne: <input type="text" size="35" name="gatunek_zwierzyny_inne" id="gatunek_zwierzyny_inne" class="disabled" value="'.$row2['gatunek_zwierze_inne'].'" readonly >
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Rodzaj, stan i jako¶æ upraw: </td><td><textarea cols="80" rows="5"  class="disabled" name="rodzaj_stan_upraw" id="rodzaj_stan_upraw" readonly>'.$row2['rodzaj_stan_upraw'].'</textarea></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
		<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Roszczenie :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>RWS: </td><td><input type="text" size="10" name="rws" id="rws" class="disabled" style="text-align:right;" value="'.print_currency($row2['rws']).'" readonly > PLN </td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Kwota roszczenia: </td><td><input type="text" size="10" style="text-align:right;"  name="kwota_roszczenia" id="kwota_roszczenia" class="disabled" value="'.print_currency($row2['kwota_roszczenia']).'" readonly> PLN</td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >		
		</table>							
	';
	}
	
	$result .= '</form>';
	return $result;	
}	



function rezerwy($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	$decision_id=getValue('decision_id');
	
	$result .= '<script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>
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
		<table cellpadding="5" cellspacing="0" border="1" align="center" width=90%>
		<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>Rezerwa globalna:</b></td><td width="30%">
			<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.print_currency($row_case_ann['rezerwa_globalna']).'"  style="text-align: right;" size="15" maxlength="20"  >		
			<input type="hidden" name="rezerwa_globalna_old" id="rezerwa_globalna_old" value="'.print_currency($row_case_ann['rezerwa_globalna']).'">';   
			
			
				$result .= wysw_currency('rezerwa_currency_id',$row_case_ann['rezerwa_currency_id'],0,'  ');
				$result .= '	<input type="hidden" name="rezerwa_currency_id_old" id="rezerwa_currency_id_old" value="'.print_currency($row_case_ann['rezerwa_currency_id']).'">';
					
			$result .= '
			</td></tr>
		</table>	<br>
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr>				
				<td width="30%" align="center"><b>¦wiadczenie</b></td>	
				<td width="17%" align="center"><b>Suma ubezp.</b></td>
				<td width="17%" align="center"><b>Rezerwa</td>
				<td width="17%" align="center"><b>Wykonawca</td>
				<td width="15%" align="center"><b>Zmiana</b></td>		
		 </tr >';
			
			//(SELECT nazwa  FROM coris_europa_swiadczenia    WHERE coris_europa_swiadczenia.ID=ccr.ID_swiadczenie  ) As swiadczenie,
		/*	 	$query = "SELECT cer.*,
			
			(SELECT short_name  FROM coris_contrahents   WHERE coris_contrahents.contrahent_id=cace.contrahent_id  ) As wykonawca,
			cace.amount,cace.contrahent_id  
			FROM coris_europa_rezerwy as cer LEFT JOIN coris_assistance_cases_expenses as cace ON cer.ID_expenses = cace.expense_id 			
			WHERE cer.case_id = '$case_id'					
			ORDER BY ID";		*/
		
 	$query = "SELECT ccr.*,				 	
				 coris_europa_lista_swiadczen.ID,
				 coris_europa_lista_swiadczen.kwota As s_kwota,
				 coris_europa_lista_swiadczen.currency_id As s_currency_id,
				 coris_europa_zakres_ubezpieczenia.nazwa As zakres_nazwa,
				 coris_europa_swiadczenia.nazwa,
				 cace.contrahent_id,			
			(SELECT short_name  FROM coris_contrahents   WHERE coris_contrahents.contrahent_id=cace.contrahent_id  ) As wykonawca,
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=ccr.ID_user ) As user,
			cace.amount,cace.contrahent_id  
			FROM coris_europa_lista_swiadczen,coris_europa_swiadczenia,coris_europa_zakres_ubezpieczenia,
								coris_europa_rezerwy as ccr LEFT JOIN coris_assistance_cases_expenses as cace ON ccr.ID_expenses = cace.expense_id 			
			WHERE ccr.case_id = '$case_id'		
			AND coris_europa_lista_swiadczen.ID = 	ccr.ID_swiadczenie		
			AND coris_europa_swiadczenia.ID = coris_europa_lista_swiadczen.ID_swiadczenie
			AND coris_europa_zakres_ubezpieczenia.ID = coris_europa_lista_swiadczen.ID_zakres_ubezpieczenia
			ORDER BY zakres_nazwa,ccr.ID";				
			 
			$mysql_result = mysql_query($query);
			$lista = array();
		//	while ($row_r=mysql_fetch_array($mysql_result)){
$zakres = '';
			while ($row_r=mysql_fetch_array($mysql_result)){
				
				if ($zakres != $row_r['zakres_nazwa'] )	{
					
					$zakres = $row_r['zakres_nazwa'];
					
					$result .= '<tr><td colspan="6" bgcolor="#777777" style="margin:10px 10px 10px 10px;"><b>&nbsp;&nbsp;'.$zakres.'</b></td></tr>';
				}
				
			  $result .= '<tr>			  	
				<td ><b>'.$row_r['nazwa'].'</b>&nbsp;</td>										  
								<td align="right"><b>'. print_currency($row_r['s_kwota'],2,' ') .' '.($row_r['s_currency_id']).'</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2) .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>'.$row_r['wykonawca'] .'&nbsp;</b></td>
				<td align="center">'. ($row_r['ID_expenses'] > 0 ? '<a href="javascript:edycja_wykonawcy('.$row_r['ID_expenses'].',\''.$decision_id.'\');">edycja</a>' : '&nbsp;').'</td>				
			   </tr >';
			   
			}
			
		$result .= '</table><br>';		
		
		$edit_form_action = getValue('edit_form_action');
		
	
		

				
				$result .= '<table cellpadding="4" cellspacing="0" border="1" align="center" width="90%">';
						$result .= '<tr bgcolor="#AAAAAA"><td colspan="4"><b>Nowa rezerwa:</b><small></td></tr>								  				
						<tr><td colspan="4" align="right">
								<input type="button" value="Dodaj" style="font-weight: bold; " title="'. AS_CASD_MSG_DODWYK .'" onclick="window.open(\'AS_cases_details_expenses_position_add.php?case_id='.  $case_id .'&decision_id='.$decision_id.'&type_id='.  $row['type_id'] .'&tryb=europa\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=500,left=\'+ (screen.availWidth - 400) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);">
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
			
			
		<table cellpadding="5" cellspacing="0" border="1" align="center" width=90%>
		<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>Rezerwa globalna:</b></td><td width="30%">
			<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.print_currency($row_case_ann['rezerwa_globalna']).'"  style="text-align: right;" size="15" maxlength="20" disabled >				';   
			
			
				$result .= wysw_currency('rezerwa_currency_id',$row_case_ann['rezerwa_currency_id'],0,' disabled ');
				
			$result .= '</td></tr>
		</table>	<br>
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr bgcolor="#AAAAAA">
				<td width="35%" align="center"><b>Zakres / ¦wiadczenie</b></td>	
				<td width="17%" align="center"><b>Suma ubezp.</b></td>
				<td width="17%" align="center"><b>Rezerwa</td>
				<td width="17%" align="center"><b>Wykonawca</td>
				<td width="12%" align="center"><b>Data</b></td>	
				<td width="17%" align="center"><b>U¿ytkownik</b></td>		
				
			   </tr >';
		//(SELECT nazwa  FROM coris_europa_swiadczenia    WHERE coris_europa_swiadczenia.ID=ccr.ID_swiadczenie  ) As swiadczenie,
				 	$query = "SELECT ccr.*,				 	
				 coris_europa_lista_swiadczen.ID,
				 coris_europa_lista_swiadczen.kwota As s_kwota,
				 coris_europa_lista_swiadczen.currency_id As s_currency_id,
				 coris_europa_zakres_ubezpieczenia.nazwa As zakres_nazwa,
				 coris_europa_swiadczenia.nazwa,
				 cace.contrahent_id,			
			(SELECT short_name  FROM coris_contrahents   WHERE coris_contrahents.contrahent_id=cace.contrahent_id  ) As wykonawca,
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id=ccr.ID_user ) As user,
			cace.amount,cace.contrahent_id  
			FROM coris_europa_lista_swiadczen,coris_europa_swiadczenia,coris_europa_zakres_ubezpieczenia,
								coris_europa_rezerwy as ccr LEFT JOIN coris_assistance_cases_expenses as cace ON ccr.ID_expenses = cace.expense_id 			
			WHERE ccr.case_id = '$case_id'		
			AND coris_europa_lista_swiadczen.ID = 	ccr.ID_swiadczenie		
			AND coris_europa_swiadczenia.ID = coris_europa_lista_swiadczen.ID_swiadczenie
			AND coris_europa_zakres_ubezpieczenia.ID = coris_europa_lista_swiadczen.ID_zakres_ubezpieczenia
			ORDER BY zakres_nazwa,ccr.ID";		
			 
			$mysql_result = mysql_query($query);
			if (!$mysql_result) echo '<h3>'.$query.'<br>'. mysql_error().'</h3>';
			$lista = array();
			$zakres = '';
			while ($row_r=mysql_fetch_array($mysql_result)){
				
				if ($zakres != $row_r['zakres_nazwa'] )	{
					
					$zakres = $row_r['zakres_nazwa'];
					
					$result .= '<tr><td colspan="6" bgcolor="#777777" style="margin:10px 10px 10px 10px;"><b>&nbsp;&nbsp;'.$zakres.'</b></td></tr>';
				}
				
			  $result .= '<tr>			  	
				<td ><b>'.$row_r['nazwa'].'</b>&nbsp;</td>	
								<td align="right"><b>'. print_currency($row_r['s_kwota'],2,' ') .' '.($row_r['s_currency_id']).'</b></td>
				<td align="right"><b>'. print_currency($row_r['rezerwa'],2,' ') .' '.($row_r['currency_id']).'</b></td>
				<td align="right"><b>('.$row_r['contrahent_id'].')&nbsp;'.$row_r['wykonawca'] .'</b></td>
				<td align="right">'. substr(($row_r['date']),0,10).'</td>	
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


?>