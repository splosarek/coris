<?php
include('lib/lib_allianz.php');

function module_update(){			
	global  $pageName;
	$result ='';
	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');
		
	$check_js = '';
	$message = '';
	
	if (isset($change['ch_rezerwa_zgloszenie']) && $case_id > 0  ){		
	 
   		$res=check_update($case_id,'rezerwa_zgloszenie');
		if ($res[0]){
					$row_case_ann = AllianzCase::getCaseInfo($case_id);
					$row_case = row_case_info($case_id);
					
					$update_roznice = array();					 
					$new_claim_handler_user = intval(getValue('new_claim_handler_user'));
					
					if ($new_claim_handler_user >0 && AllianzCase::isAdmin()){						
						$query =  "UPDATE coris_assistance_cases SET   claim_handler_user_id='".$new_claim_handler_user."', claim_handler_date=now() WHERE case_id='$case_id' LIMIT 1";
							$update_roznice['claim_handler_user_id'] = array('old' => $row_case['claim_handler_user_id'], 'new' => $new_claim_handler_user);
						$mr = mysql_query($query);
						if (!$mr ){																						
							echo  "<br>UPDATE Error: $query <br><br> ".mysql_error();				
						}	
					}					
					
					
					
					$nr_protokolu = getValue('nr_protokolu');
					$kolo_id = getValue('kolo_id');
					$kolo_nazwa = getValue('kolo_nazwa');
					$kolo_adres = getValue('kolo_adres');
					$kolo_kod = getValue('kolo_kod');
					$kolo_miejscowosc = getValue('kolo_miejscowosc');
					$kolo_zo = getValue('kolo_zo');
					$kolo_konto = getValue('kolo_konto');
					$woj_id = getValue('woj_id');
					$pow_id = getValue('pow_id');
					$gmina_id = getValue('gmina_id');
					$lok_miejscowosc = getValue('lok_miejscowosc');
					$nr_dzialki = getValue('nr_dzialki');
					$obwod_lowiecki = getValue('obwod_lowiecki');					
					//$data_zgloszenia_do_kola = getValue('data_zgloszenia_do_kola');
					
					$szacujacy_id = getValue('szacujacy_id');
					$szacujacy_nazwa = getValue('szacujacy_nazwa');
					$szacujacy_tel = getValue('szacujacy_tel');
					
					$gatunek_zwierzyny = getValue('gatunek_zwierzyny');
					$gatunek_zwierzyny_inne = getValue('gatunek_zwierzyny_inne');
					$rodzaj_stan_upraw = getValue('rodzaj_stan_upraw');
					
					
					$rws = getValue('rws') != '' ? str_replace(',','.',getValue('rws')) : 0.0;
					$kwota_roszczenia = getValue('kwota_roszczenia') != '' ? str_replace(',','.',getValue('kwota_roszczenia')) : 0.0;
					
					$poszk_nazwisko = getValue('poszk_nazwisko');		
					$poszk_imie = getValue('poszk_imie');		
					$poszk_adres = getValue('poszk_adres');		
					$poszk_kod = getValue('poszk_kod');		
					$poszk_miejscowosc = getValue('poszk_miejscowosc');		
					$poszk_tel = getValue('poszk_tel');		
					$poszk_email = getValue('poszk_email');		
					
					$kolo_nr_polisy = getValue('kolo_nr_polisy');		
					$nr_sprawy_allianz = getValue('nr_sprawy_allianz');		
					$powierzchnia_dzialki = getValue('powierzchnia_dzialki');		
					
				$data_zgloszenia_do_kola = getValue('data_zgloszenia_do_kola_y').'-'.getValue('data_zgloszenia_do_kola_m').'-'.getValue('data_zgloszenia_do_kola_d');
				$eventdate=getValue('eventDate_y').'-'.getValue('eventDate_m').'-'.getValue('eventDate_d');
				$notificationdate=getValue('notificationDate_y').'-'.getValue('notificationDate_m').'-'.getValue('notificationDate_d');

					if ($row_case_ann){ // jest rekord - UPDATE
						
								$update = array();
								$update2 = array();
								$update3 = array();
							//	$update_roznice = array();
								
								if ($row_case_ann['nr_protokolu'] != $nr_protokolu){
										$update_roznice['nr_protokolu'] = array('old' => $row_case_ann['nr_protokolu'], 'new' => $nr_protokolu);
										$update[] = "nr_protokolu = '".$nr_protokolu."'";										
								}
								
								if ($row_case_ann['ID_kolo'] != $kolo_id){
										$update_roznice['ID_kolo'] = array('old' => $row_case_ann['ID_kolo'], 'new' => $kolo_id);
		                    		$update[] = "ID_kolo = '".$kolo_id."'";
								}
								
								if ($row_case_ann['kolo_nazwa'] != $kolo_nazwa){
										$update_roznice['kolo_nazwa'] = array('old' => $row_case_ann['kolo_nazwa'], 'new' => $kolo_nazwa);
		                    			$update[] = "kolo_nazwa = '".$kolo_nazwa."'";
								}
								
		                    	if ($row_case_ann['kolo_adres'] != $kolo_adres){
										$update_roznice['kolo_adres'] = array('old' => $row_case_ann['kolo_adres'], 'new' => $kolo_adres);
										$update[] = "kolo_adres = '".$kolo_adres."'";
		                    	}
		                    	
		                    	if ($row_case_ann['kolo_kod'] != $kolo_kod){
										$update_roznice['kolo_kod'] = array('old' => $row_case_ann['kolo_kod'], 'new' => $kolo_kod);										
		                    			$update[] = "kolo_kod = '".$kolo_kod."'";
		                    	}

		                    	if ($row_case_ann['kolo_miejscowosc'] != $kolo_miejscowosc){
										$update_roznice['kolo_miejscowosc'] = array('old' => $row_case_ann['kolo_miejscowosc'], 'new' => $kolo_miejscowosc);
		                    			$update[] = "kolo_miejscowosc = '".$kolo_miejscowosc."'";
		                    	}
		                    	if ($row_case_ann['kolo_zo'] != $kolo_zo){
										$update_roznice['kolo_zo'] = array('old' => $row_case_ann['kolo_zo'], 'new' => $kolo_zo);
										$update[] = "kolo_zo = '".$kolo_zo."'";
		                    	}
		                    	if ($row_case_ann['kolo_konto'] != $kolo_konto){
										$update_roznice['kolo_konto'] = array('old' => $row_case_ann['kolo_konto'], 'new' => $kolo_konto);
		                    		$update[] = "kolo_konto = '".$kolo_konto."'";
		                    	}
		                    		
		                    	if ($row_case_ann['szko_woj_id'] != $woj_id){
										$update_roznice['szko_woj_id'] = array('old' => $row_case_ann['szko_woj_id'], 'new' => $woj_id);
										$update[] = "szko_woj_id = '".$woj_id."'";
		                    	}

		                    	if ($row_case_ann['szk_pow_id'] != $pow_id){
										$update_roznice['szk_pow_id'] = array('old' => $row_case_ann['szk_pow_id'], 'new' => $pow_id);
										$update[] = "szk_pow_id = '".$pow_id."'";
		                    	}

		                    	if ($row_case_ann['szk_gmina_id'] != $gmina_id){
										$update_roznice['szk_gmina_id'] = array('old' => $row_case_ann['szk_gmina_id'], 'new' => $gmina_id);
										$update[] = "szk_gmina_id = '".$gmina_id."'";
		                    	}

		                    	if ($row_case_ann['szk_lok_miejscowosc'] != $lok_miejscowosc){
										$update_roznice['szk_lok_miejscowosc'] = array('old' => $row_case_ann['szk_lok_miejscowosc'], 'new' => $lok_miejscowosc);
										$update[] = "szk_lok_miejscowosc = '".$lok_miejscowosc."'";
		                    	}
									
		                    	if ($row_case_ann['szk_nr_dzialki'] != $nr_dzialki){
										$update_roznice['szk_nr_dzialki'] = array('old' => $row_case_ann['szk_nr_dzialki'], 'new' => $nr_dzialki);
										$update[] = "szk_nr_dzialki = '".$nr_dzialki."'";
		                    	}
									
		                    	if ($row_case_ann['powierzchnia_dzialki'] != $powierzchnia_dzialki){
										$update_roznice['powierzchnia_dzialki'] = array('old' => $row_case_ann['powierzchnia_dzialki'], 'new' => $powierzchnia_dzialki);
										$update[] = "powierzchnia_dzialki = '".$powierzchnia_dzialki."'";
		                    	}
									
		                    	if ($row_case_ann['szk_obwod_lowiecki'] != $obwod_lowiecki){
										$update_roznice['szk_obwod_lowiecki'] = array('old' => $row_case_ann['szk_obwod_lowiecki'], 'new' => $obwod_lowiecki);
										$update[] = "szk_obwod_lowiecki = '".$obwod_lowiecki."'";
		                    	}
									
								if ($row_case_ann['data_zgloszenia_do_kola'] != $data_zgloszenia_do_kola){
									$update_roznice['data_zgloszenia_do_kola'] = array('old' => $row_case_ann['data_zgloszenia_do_kola'], 'new' => $data_zgloszenia_do_kola);
									$update[] = "data_zgloszenia_do_kola =  '".$data_zgloszenia_do_kola."'";
								}

								/*if ($row_case_ann['ID_szacujacy'] != $szacujacy_id){
										$update_roznice['ID_szacujacy'] = array('old' => $row_case_ann['ID_szacujacy'], 'new' => $szacujacy_id);
										$update[] = "ID_szacujacy = '".$szacujacy_id."'";
								}
								if ($row_case_ann['szacujacy_imie_nazwisko'] != $szacujacy_nazwa){
										$update_roznice['szacujacy_imie_nazwisko'] = array('old' => $row_case_ann['szacujacy_imie_nazwisko'], 'new' => $szacujacy_nazwa);
										$update[] = "szacujacy_imie_nazwisko = '".$szacujacy_nazwa."'";
								}
								if ($row_case_ann['szacujacy_tel'] != $szacujacy_tel){
									$update_roznice['szacujacy_tel'] = array('old' => $row_case_ann['szacujacy_tel'], 'new' => $szacujacy_tel);
									$update[] = "szacujacy_tel = '".$szacujacy_tel."'";
								}*/
								
								if ($row_case_ann['gatunek_zwierze_inne'] != $gatunek_zwierzyny_inne){
										$update_roznice['gatunek_zwierze_inne'] = array('old' => $row_case_ann['gatunek_zwierze_inne'], 'new' => $gatunek_zwierzyny_inne);
										$update[] = "gatunek_zwierze_inne = '".$gatunek_zwierzyny_inne."'";
								}
								if ($row_case_ann['rodzaj_stan_upraw'] != $rodzaj_stan_upraw){
										$update_roznice['rodzaj_stan_upraw'] = array('old' => $row_case_ann['rodzaj_stan_upraw'], 'new' => $rodzaj_stan_upraw);
										$update[] = "rodzaj_stan_upraw = '".$rodzaj_stan_upraw."'";
								}
								if ($row_case_ann['rws'] != $rws){
										$update_roznice['rws'] = array('old' => $row_case_ann['rws'], 'new' => $rws);
										$update[] = "rws = '".$rws."'";
								}
								if ($row_case_ann['kwota_roszczenia'] != $kwota_roszczenia){
										$update_roznice['kwota_roszczenia'] = array('old' => $row_case_ann['kwota_roszczenia'], 'new' => $kwota_roszczenia);
										$update[] = "kwota_roszczenia = '".$kwota_roszczenia."'";
								}
									
								
								
								if (count($update) > 0 ){
										$query = "UPDATE coris_allianz_announce SET 
										".implode(', ', $update)."
										WHERE case_id='$case_id' LIMIT 1";
										$mr = mysql_query($query);
										if (!$mr ){																						
											echo  "<br>Insert Error: $query <br><br> ".mysql_error();				
									 	}	
								}
/* Aktualizacja szacujacych*/								
								$query = "SELECT * FROM  coris_allianz_announce_szacujacy  WHERE case_id  ='$case_id'" ;
								$mysql_result = mysql_query($query);
								$old_lista = array();									       	
								while ($row=mysql_fetch_array($mysql_result)) {
									        		$old_lista[]  = $row['ID_szacujacy'];
									        		
									        		if ( !in_array($row['ID_szacujacy'],$szacujacy_id)){
									        			$qd = "DELETE FROM  coris_allianz_announce_szacujacy  WHERE  case_id  ='$case_id' AND ID_szacujacy ='".$row['ID_szacujacy']."'";        		
									        			$mr = mysql_query($qd);        			
									        		}
								}									
								foreach ($szacujacy_id As $poz ){									        														        					
									if ($poz > 0 ){
										if (!in_array($poz,$old_lista)){
											AllianzCase::dodajSzacujacegoDoSprawy($case_id, $poz)  ;      						
									  	}        						
									 }else if ($poz=='new'){
									 	$poz = AllianzCase::dodajSzacujacego($kolo_id, $szacujacy_nazwa, $szacujacy_tel);
									    AllianzCase::dodajSzacujacegoDoSprawy($case_id, $poz)  ; 
									 }        		        		
								}
					
/* Aktualizacja gatunkow*/		

								$query = "SELECT * FROM  coris_allianz_announce_gatunek  WHERE case_id  ='$case_id'" ;
								$mysql_result = mysql_query($query);
								$old_lista = array();									       	
								while ($row=mysql_fetch_array($mysql_result)) {
									        		$old_lista[]  = $row['ID_gatunek'];
									        		
									        		if ( !in_array($row['ID_gatunek'],$gatunek_zwierzyny)){
									        			$qd = "DELETE FROM  coris_allianz_announce_gatunek  WHERE  case_id  ='$case_id' AND ID_gatunek ='".$row['ID_gatunek']."'";        		
									        			$mr = mysql_query($qd);        			
									        		}
								}									
								foreach ($gatunek_zwierzyny As $poz ){									        														        					
									if ($poz > 0 ){
										if (!in_array($poz,$old_lista)){
											AllianzCase::dodajGatunekDoSprawy($case_id, $poz)  ;      						
									  	}        						
									 }        		        		
								}
					
								
								
								
					
					
								if ($row_case['client_ref'] != $nr_sprawy_allianz){
										$update_roznice['client_ref'] = array('old' => $row_case['client_ref'], 'new' => $nr_sprawy_allianz);
										$update2[] = "client_ref = '".$nr_sprawy_allianz."'";
								}
								
					
								if ($row_case['policy'] != $kolo_nr_polisy){
										$update_roznice['policy'] = array('old' => $row_case['policy'], 'new' => $kolo_nr_polisy);
										$update2[] = "policy = '".$kolo_nr_polisy."'";
								}
								
					
								if ($row_case['paxsurname'] != $poszk_nazwisko){
										$update_roznice['poszk_nazwisko'] = array('old' => $row_case['paxsurname'], 'new' => $poszk_nazwisko);
										$update2[] = "paxsurname = '".$poszk_nazwisko."'";
								}
								
								if ($row_case['paxname'] != $poszk_imie){
										$update_roznice['poszk_imie'] = array('old' => $row_case['paxname'], 'new' => $poszk_imie);
										$update2[] = "paxname = '".$poszk_imie."'";
								}
								
								 
								if ($row_case['pax_email'] != $poszk_email){
										$update_roznice['poszk_email'] = array('old' => $row_case['pax_email'], 'new' => $poszk_email);
										$update2[] = "pax_email = '".$poszk_email."'";
								}
								
								 
								if ($row_case['eventdate'] != $eventdate){
										$update_roznice['eventdate'] = array('old' => $row_case['eventdate'], 'new' => $eventdate);
										$update2[] = "eventdate = '".$eventdate."'";
								}
								
								 
								
								if ($row_case['notificationdate'] != $notificationdate){
										$update_roznice['notificationdate'] = array('old' => $row_case['notificationdate'], 'new' => $notificationdate);
										$update3[] = "notificationdate  = '".$notificationdate."'";
								}
								
								 
								
								if ($row_case['paxaddress'] != $poszk_adres){
										$update_roznice['poszk_adres'] = array('old' => $row_case['pax_email'], 'new' => $poszk_adres);
										$update3[] = "paxaddress = '".$poszk_adres."'";
								}
								
										
								if ($row_case['paxpost'] != $poszk_kod){
										$update_roznice['poszk_kod'] = array('old' => $row_case['paxpost'], 'new' => $poszk_kod);
										$update3[] = "paxpost = '".$poszk_kod."'";
								}
								
										
								if ($row_case['paxcity'] != $poszk_miejscowosc){
										$update_roznice['poszk_miejscowosc'] = array('old' => $row_case['paxcity'], 'new' => $poszk_miejscowosc);
										$update3[] = "paxcity = '".$poszk_miejscowosc."'";
								}
								
										
							
								if ($row_case['paxphone'] != $poszk_tel){
										$update_roznice['poszk_tel'] = array('old' => $row_case['paxphone'], 'new' => $poszk_tel);
										$update3[] = "paxphone = '".$poszk_tel."'";
								}

								
								if (count($update2) > 0 ){
										$query = "UPDATE coris_assistance_cases SET 
										".implode(', ', $update2)."
										WHERE case_id='$case_id' LIMIT 1";
										$mr = mysql_query($query);
										if (!$mr ){																						
											echo  "<br>UPDATE Error: $query <br><br> ".mysql_error();				
									 	}	
								}
								
								if (count($update3) > 0 ){
										$query = "UPDATE coris_assistance_cases_details SET 
										".implode(', ', $update3)."
										WHERE case_id='$case_id' LIMIT 1";
										$mr = mysql_query($query);
										if (!$mr ){																						
											echo  "<br>UPDATE Error: $query <br><br> ".mysql_error();				
									 	}	
								}
								if (count($update_roznice) > 0 ){
											AllianzCase::rejestrujZmiany($case_id,$case_id,'Zgloszenie','UPDATE',$update_roznice);
								}
					}else{ //brak rekordu - INSERT  (np. po zmianie kontrahenta)							
						
							$query = "INSERT coris_allianz_announce SET 
		                    		case_id='$case_id',
		                    		nr_protokolu = '".$nr_protokolu."',
		                    		ID_kolo = '".$kolo_id."',
		                    		kolo_nazwa = '".$kolo_nazwa."',
		                    		kolo_adres = '".$kolo_adres."',
		                    		kolo_kod = '".kolo_kod."',
		                    		kolo_miejscowosc = '".$kolo_miejscowosc."',
		                    		kolo_zo = '".$kolo_zo."',
		                    		kolo_konto = '".$kolo_konto."',
		                    		
		                    		szko_woj_id = '".$woj_id."',
		                    		szk_pow_id = '".$pow_id."',
		                    		szk_gmina_id = '".$gmina_id."',
									szk_lok_miejscowosc = '".$lok_miejscowosc."',
									szk_nr_dzialki = '".$nr_dzialki."',
									szk_obwod_lowiecki = '".$obwod_lowiecki."',
									powierzchnia_dzialki = '".$powierzchnia_dzialki."',
									
									data_zgloszenia_do_kola =  '".$data_zgloszenia_do_kola."',
									
									gatunek_zwierze_inne = '".$gatunek_zwierzyny_inne."',
									rodzaj_stan_upraw = '".$rodzaj_stan_upraw."',
									rws = '".$rws."',
									kwota_roszczenia = '".$kwota_roszczenia."'							                    	
		                    	";
		                    	
	                    			$mr = mysql_query($query);
											
									if (!$mr ){																						
											echo  "<br>Insert Error: $query <br><br> ".mysql_error();				
									 }		
									
									if (is_array($szacujacy_id)){
										foreach ($szacujacy_id  As $pozycja ){
											if ($pozycja == 'new'){
												$sz_id = AllianzCase::dodajSzacujacego($kolo_id, getValue('szacujacy_nazwa'), getValue('szacujacy_tel') );                    			
											}else{
												$sz_id = $pozycja;								
											}								
											if ($sz_id > 0 ){
													$szacujacy_dane = AllianzCase::infoSzacujacy($sz_id);									
													$qi = "INSERT INTO coris_allianz_announce_szacujacy SET
														 case_id='$case_id',ID_szacujacy='$sz_id',
														 imie_nazwisko= '".mysql_escape_string($szacujacy_dane['nazwa'])."',
															telefon = '".mysql_escape_string($szacujacy_dane['tel'])."'";																
							            			$mr = mysql_query($qi);											
													if (!$mr ){																						
															echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
													 }	
												}								
										}								
				                	}
									
									 
									 if (is_array($gatunek_zwierzyny)){
										foreach ($gatunek_zwierzyny As $gatunek){
											$query = "INSERT coris_allianz_announce_gatunek 
												SET  case_id='$case_id',ID_gatunek='$gatunek'";
				            					$mr = mysql_query($query);
												
												if (!$mr ){																						
														echo  "<br>Update Error: $query <br><br> ".mysql_error();				
												 }		
										}													
									}						
					}		
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
	
	echo $message;	
}

function module_main(){
	global $case_id, $row_case;

	$result = '';	
		$query = "SELECT ac.number, ac.year, ac.client_id, ac.event, ac.country_id, ac.type_id, ac.genre_id, ac.paxname, ac.paxsurname,ac.pax_email,ac.paxsex, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.costless,ac.only_info, ac.costless, ac.unhandled, ac.archive, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention, ac.attention2, acr.reclamation_text, ac.attention, ac.attention2,ac.holowanie,ac.wynajem_samochodu,ac.eventdate,ac.claim_handler_user_id		
		FROM coris_assistance_cases ac LEFT JOIN coris_assistance_cases_reclamations acr ON ac.case_id = acr.case_id WHERE ac.case_id = '".$case_id."'";			
		$mysql_result = mysql_query($query);
		$row_case_settings = mysql_fetch_array($mysql_result);			
		
		/*$query2 = "SELECT * FROM coris_allianz_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);*/			
		$row_case_ann = AllianzCase::getCaseInfo($case_id);
		
	if ($row_case_settings['client_id'] == 9 ){
		$result .=  '<div style="width: 1000px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
			$result .=  zgloszenie($row_case_settings,$row_case_ann,$row_case);	
		$result .=  '</div>';	
		
	
	/*	$result .=  '<div style="clear:both;"></div>';
		$result .=  '<div style="width: 950px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
			$result .=  rezerwy($row_case_settings,$row_case_ann);	
		$result .=  '</div>';	
		$result .=  '<div style="clear:both;"></div>';
					
		*/	
				$result .=  '<div style="clear:both;"></div>';		
	}else{
		$result = '<div style="width: 950px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
		<br><br><br><br><br><div align="center"><b>Tylko dla spraw TU ALLIANZ</b></div>
		</div>
		';
	
}
	
	return $result;	
}

function zgloszenie($row,$row2,$row3){

	 
        
       $result='';	
	global $global_link,$change;
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_zgloszenie" id="form_zgloszenie" >
	
	
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

				$eventDate_tmp = $row['eventdate'] != '0000-00-00' ?  explode('-', $row['eventdate']) : array(); 
				$notificationdate_tmp = $row3['notificationdate'] != '0000-00-00' ? explode('-', $row3['notificationdate']) : array() ;
				$data_zgloszenia_do_kola_tmp = $row2['data_zgloszenia_do_kola'] != '0000-00-00' ? explode('-', $row2['data_zgloszenia_do_kola']) : array();
		
if (isset($change['rezerwa_zgloszenie'])){
	$result .= calendar();
	
	$result .= '
	<script language="JavaScript1.2" src="Scripts/js_allianz_announce.js"></script>
	<script language="JavaScript1.2">
		<!--
		    function validate() {
					return true;
                if ($(\'nr_protokolu\').value == "") {
                    alert("Brak nr. protoko³u");
                    $(\'nr_protokolu\').focus();
                    return false;
                }

                if ($(\'kolo_id\').value == 0) {
                    alert("Brak ko³a ³owieckiego");
                    $(\'kolo_id\').focus();
                    return false;
                }

                if ($(\'woj_id\').value == 0) {
                    alert("Lokalizacja szkody: Brak wojewodztwa");
                    		$(\'woj_id\').focus();
                    return false;
                }
               
                if ($(\'pow_id\').value == 0) {
                    alert("Lokalizacja szkody: Brak powiatu");
                    		$(\'pow_id\').focus();
                    return false;
                }
               
                if ($(\'gmina_id\').value == 0) {
                    alert("Lokalizacja szkody: Brak gminy");
                    		$(\'woj_id\').focus();
                    return false;
                }
               
                if ($(\'lok_miejscowosc\').value == "") {
                    alert("Lokalizacja szkody: Brak nazwy miasta/wsi");
                    		$(\'lok_miejscowosc\').focus();
                    return false;
                }
               
                if ($(\'nr_dzialki\').value == "") {
                    alert("Lokalizacja szkody: Brak numeru dzia³ki");
                    		$(\'nr_dzialki\').focus();
                    return false;
                }
               
                if ($(\'obwod_lowiecki\').value == "") {
                    alert("Lokalizacja szkody: Brak numeru obwodu ³owieckiego");
                    		$(\'obwod_lowiecki\').focus();
                    return false;
                }
               
                if ($(\'poszk_nazwisko\').value == "") {
                    alert("Poszkodowany: Brak nazwiska");
                    		$(\'poszk_nazwisko\').focus();
                    return false;
                }
               
                if ($(\'poszk_imie\').value == "") {
                    alert("Poszkodowany: Brak imienia");
                    		$(\'poszk_imie\').focus();
                    return false;
                }
               
                if ($(\'poszk_adres\').value == "") {
                    alert("Poszkodowany: Brak adresu");
                    		$(\'poszk_adres\').focus();
                    return false;
                }
               
                if ($(\'poszk_kod\').value == "") {
                    alert("Poszkodowany: Brak kodu pocztowego");
                    		$(\'poszk_kod\').focus();
                    return false;
                }
               
                if ($(\'poszk_miejscowosc\').value == "") {
                    alert("Poszkodowany: Brak miejscowo¶ci");
                    		$(\'poszk_miejscowosc\').focus();
                    return false;
                }
               
                if ($(\'data_zgloszenia_do_kola\').value == "") {
                    alert("Szkoda: Brak daty zg³oszenia szkody do K£");
                    		$(\'data_zgloszenia_do_kola\').focus();
                    return false;
                }
               
                if ($(\'szacujacy_id\').value == 0) {
                    alert("Szkoda: Brak szacuj±cego szkodê");
                    		$(\'szacujacy_id\').focus();
                    return false;
                }
               
                if ($(\'rodzaj_stan_upraw\').value == 0) {
                    alert("Szkoda: Brak informacji o rodzaju i stanie upraw");
                    		$(\'rodzaj_stan_upraw\').focus();
                    return false;
                }
               
                if ($(\'rodzaj_stan_upraw\').value == 0) {
                    alert("Szkoda: Brak informacji o rodzaju i stanie upraw");
                    		$(\'rodzaj_stan_upraw\').focus();
                    return false;
                }
               
                if ($(\'rws\').value == 0) {
                    alert("Szkoda: Brak kwoty RWS");
                    		$(\'rws\').focus();
                    return false;
                }
               
                if ($(\'kwota_roszczenia\').value == 0) {
                    alert("Szkoda: Brak kwoty roszczenia");
                    		$(\'kwota_roszczenia\').focus();
                    return false;
                }
               
				
    		return true;
                
              
            }									
   
            
        function move_formant(s,e) {
            var form1 = document.getElementById(\'form_zgloszenie\');
			//e = window.event;
			//var keyInfo = String.fromCharCode(e.keyCode);
        	if(window.event)
        		var keyInfo  = window.event.keyCode; // IE
        	else
        		var keyInfo  = e.charCode;

			if (keyInfo != 9 && keyInfo != 16 && keyInfo != 8) {
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

		function remove_formant(s,e) {
			var form1 = document.getElementById(\'form_zgloszenie\');
			if(window.event)
        		var keyInfo  = window.event.keyCode; // IE
        	else
        		var keyInfo  = e.charCode;
    		
			if (keyInfo == 8) {
				for (var i = 0; i < form1.length; i++) {
					if (s.name == form1.elements[i].name) {
						if ((form_reg.elements[i].value.length == 0)) {
							form1.elements[i-1].focus();
							var rng = form1.elements[i-1].createTextRange();
							rng.select();
							return false;
						}
					}
				}
			}
		}
		//-->
		</script>';		

		$result .= '
	  <table cellpadding="5" cellspacing="1" border="0"  align="center" >
	  			<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Likwidator:</b> </td><td> '.Application::getUserName($row['claim_handler_user_id']);
					if (AllianzCase::isAdmin())
							$result .= ' &nbsp;&nbsp;&nbsp; Zmiana likwidatora na: '.AllianzCase::listaLikwidatorow('new_claim_handler_user');
					
					$result .= '</td></tr>
				
					<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Nr protoko³u:</b>	</td><td><input type="text" size="50" name="nr_protokolu" id="nr_protokolu" class="required1" value="'.$row2['nr_protokolu'].'" </td>
				</tr>
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Ko³o ³owieckie :</b>	</td><td>';					 					 					
						$result .= AllianzCase::getKolaLowieckie('kolo_id',$row2['ID_kolo'],0,'onChange="getKoloLowieckie(this.value);"');
						
						

						$result .= '<hr><table cellpadding="5" cellspacing="1">';
							//$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><input type="text" size="50" name="kolo_nazwa" id="kolo_nazwa" class="required1" value="'.addslashes($row2['kolo_nazwa']).'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><textarea type="text" cols="50" rows="2" name="kolo_nazwa" id="kolo_nazwa" class="required1">'.$row2['kolo_nazwa'].'</textarea></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="required1" value="'.$row2['kolo_adres'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="kolo_kod" id="kolo_kod" class="required1" value="'.$row2['kolo_kod'].'" > <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="required1" value="'.$row2['kolo_miejscowosc'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="required1" value="'.$row2['kolo_zo'].'" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="required1" style="text-align:right;" value="'.$suma_ubezpieczenia.'" readonly> PLN, <span id="franszyza_info">'.$franszyza_info.'</span></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr polisy: </td><td><input type="text" size="40" name="kolo_nr_polisy" id="kolo_nr_polisy" class="required1"  value="'.$row3['policy'].'" > </td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr konta bankowego:</td><td><input type="text" size="50" name="kolo_konto" id="kolo_konto" class="required1" value="'.$row2['kolo_konto'].'"></td></tr>';
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				
				<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Lokalizacja szkody :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Województwo: </td><td>'.AllianzCase::getWojewodztwa('woj_id',$row2['szko_woj_id'],0,' onChange="getPowiaty(this.value)" ').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powiat: </td><td>'.AllianzCase::getPowiaty('pow_id',$row2['szko_woj_id'],$row2['szk_pow_id'],0,' onChange="getGminy(this.value)" ').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Gmina: </td><td>'.AllianzCase::getGminy('gmina_id',$row2['szko_woj_id'],$row2['szk_pow_id'],$row2['szk_gmina_id'],0,'').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Wie¶/miasto: </td><td><input type="text" size="50" name="lok_miejscowosc" id="lok_miejscowosc" class="required1" value="'.$row2['szk_lok_miejscowosc'].'"></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Nr dzia³ki: </td><td><input type="text" size="50" name="nr_dzialki" id="nr_dzialki" class="required1" value="'.$row2['szk_nr_dzialki'].'"></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powierzchnia dzia³ki: </td><td><input type="text" size="10" name="powierzchnia_dzialki" id="powierzchnia_dzialki" class="required1" style="text-align:right;" value="'.$row2['powierzchnia_dzialki'].'"> ha</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Obwód ³owiecki nr: </td><td><input type="text" size="50" name="obwod_lowiecki" id="obwod_lowiecki" class="required1" value="'.$row2['szk_obwod_lowiecki'].'"></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Poszkodowany :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>Nazwisko: </td><td><input type="text" size="50" name="poszk_nazwisko" id="poszk_nazwisko" class="required1" value="'.$row['paxsurname'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Imiê: </td><td><input type="text" size="50" name="poszk_imie" id="poszk_imie" class="required1" value="'.$row['paxname'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Adres: </td><td><input type="text" size="50" name="poszk_adres" id="poszk_adres" class="required1" value="'.$row3['paxaddress'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="poszk_kod" id="poszk_kod" class="required1" value="'.$row3['paxpost'].'"> <input type="text" size="35" name="poszk_miejscowosc" id="poszk_miejscowosc" class="required1" value="'.$row3['paxcity'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Tel: </td><td><input type="text" size="15" name="poszk_tel" id="poszk_tel" class="required1" value="'.$row3['paxphone'].'"></td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Email: </td><td><input type="text" size="40" name="poszk_email" id="poszk_email" class="required1" value="'.$row['pax_email'].'"></td></tr>';														
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
			<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Szkoda :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Nr sprawy Allianz: </td><td><input type="text" size="30" name="nr_sprawy_allianz" id="nr_sprawy_allianz" class="required1" value="'.$row3['client_ref'].'"></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zdarzenia: </td><td>
											<input type="text" name="eventDate_d" id="eventDate_d" size="1" value="'.$eventDate_tmp[2].'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;
											<input type="text" name="eventDate_m" id="eventDate_m" size="1" value="'.$eventDate_tmp[1].'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;
											<input type="text" name="eventDate_y" id="eventDate_y" size="4" value="'.$eventDate_tmp[0].'" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);">
											<a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal(\'eventDate\')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zg³oszenia szkody<br>do ko³a ³owieckiego: </td><td>
											<input type="text" name="data_zgloszenia_do_kola_d" id="data_zgloszenia_do_kola_d" size="1" value="'.$data_zgloszenia_do_kola_tmp[2].'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;
											<input type="text" name="data_zgloszenia_do_kola_m" id="data_zgloszenia_do_kola_m" size="1" value="'.$data_zgloszenia_do_kola_tmp[1].'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;
											<input type="text" name="data_zgloszenia_do_kola_y" id="data_zgloszenia_do_kola_y" size="4" value="'.$data_zgloszenia_do_kola_tmp[0].'" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);">
											<a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal(\'data_zgloszenia_do_kola\')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small>
											
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data wp³yniêcia<br> do CORIS: </td><td>
							<input type="text" name="notificationDate_d" id="notificationDate_d" size="1" value="'.$notificationdate_tmp[2].'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;
											<input type="text" name="notificationDate_m" id="notificationDate_m" size="1" value="'.$notificationdate_tmp[1].'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;
											<input type="text" name="notificationDate_y" id="notificationDate_y" size="4" value="'.$notificationdate_tmp[0].'" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);">
											<a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal(\'notificationDate\')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small>
							</td></tr>';														
							
							$result .= '<tr bgcolor="#BBBBBB"><td>Szacuj±cy szkodê: </td><td>'.AllianzCase::getSzacujacy($row2['case_id'],$row2['ID_kolo'],$row2['ID_szacujacy'],0,' onChange="getSzacujacy(this.value)" ').' Imiê Nazwisko: <input type="text" size="25" name="szacujacy_nazwa" id="szacujacy_nazwa" class="required1" value="'.$row2['szacujacy_imie_nazwisko'].'" readonly> Tel:<input type="text" size="12" name="szacujacy_tel" id="szacujacy_tel" class="required1" value="'.$row2['szacujacy_tel'].'" readonly></td></tr>';
														
							$result .= '<tr bgcolor="#BBBBBB"><td>Gatunek zwierzyny, <br>który wyrz±dzi³ szkodê: </td><td>
								<select multiple="multiple" class="required1" size="6" name="gatunek_zwierzyny[]" onClick="sprawdz_zwierzyne()">';
									foreach ($lista_gatunkow As $poz){
										$result .= '<option value="'.$poz['ID'].'"  '.($poz['selected']==1?' selected ' : '' ).'>'.$poz['nazwa'].'</option>';								
									}
								$result .= '</select> Inne: <input type="text" size="35" name="gatunek_zwierzyny_inne" id="gatunek_zwierzyny_inne" class="required1" value="'.$row2['gatunek_zwierze_inne'].'">
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Rodzaj, stan i jako¶æ upraw: </td><td><textarea cols="80" rows="5"  class="required1" name="rodzaj_stan_upraw" id="rodzaj_stan_upraw">'.$row2['rodzaj_stan_upraw'].'</textarea></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
		<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Roszczenie :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>RWS: </td><td><input type="text" size="10" name="rws" id="rws" class="required1"  style="text-align:right;" value="'.print_currency($row2['rws']).'"> PLN </td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Kwota roszczenia: </td><td><input type="text" size="10" name="kwota_roszczenia" id="kwota_roszczenia" class="required1"  style="text-align:right;" value="'.print_currency($row2['kwota_roszczenia']).'"> PLN</td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >		
		</table>							
	';
	}else{
		 
							
		$result .= '
	  <table cellpadding="5" cellspacing="1" border="0"  align="center" >
	  	  			<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Likwidator:</b> </td><td> '.Application::getUserName($row['claim_handler_user_id']);
					$result .= '</td></tr>
					<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Nr protoko³u:</b>	</td><td><input type="text" size="50" name="nr_protokolu" id="nr_protokolu" class="disabled" value="'.$row2['nr_protokolu'].'" readonly></td>
				</tr>
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Ko³o ³owieckie :</b>	</td><td>';					 					 					
						$result .= AllianzCase::getKolaLowieckie('kolo_id',$row2['ID_kolo'],1,'');
						

						$result .= '<hr><table cellpadding="5" cellspacing="1">';
						//	$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><input type="text" size="50" name="kolo_nazwa" id="kolo_nazwa" class="disabled" readonly value="'.addslashes($row2['kolo_nazwa']).'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><textarea type="text" cols="50" rows="2" name="kolo_nazwa" id="kolo_nazwa" class="disabled" readonly >'.$row2['kolo_nazwa'].'</textarea></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="disabled" readonly value="'.$row2['kolo_adres'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="kolo_kod" id="kolo_kod" class="disabled" readonly value="'.$row2['kolo_kod'].'"> <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="disabled" readonly value="'.$row2['kolo_miejscowosc'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="disabled" readonly value="'.$row2['kolo_zo'].'"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="disabled" style="text-align:right;" value="'.$suma_ubezpieczenia.'" readonly> PLN, <span id="franszyza_info">'.$franszyza_info.'</span></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr polisy: </td><td><input type="text" size="40" name="kolo_nr_polisy" id="kolo_nr_polisy" class="disabled"  value="'.$row3['policy'].'" readonly> </td></tr>';							
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
							$result .= '<tr bgcolor="#BBBBBB"><td>Nr dzia³ki: </td><td><textarea cols="60" rows="2"  class="disabled" name="nr_dzialki" id="nr_dzialki" readonly>'.$row2['szk_nr_dzialki'].'</textarea></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powierzchnia dzia³ki: </td><td><input type="text" size="10" name="powierzchnia_dzialki" id="powierzchnia_dzialki" class="disabled" style="text-align:right;" value="'.$row2['powierzchnia_dzialki'].'" readonly> ha</td></tr>';							
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
							$result .= '<tr bgcolor="#AAAAAA"><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="poszk_kod" id="poszk_kod"  class="disabled" readonly value="'.$row3['paxpost'].'"> <input type="text" size="35" name="poszk_miejscowosc" id="poszk_miejscowosc"  class="disabled" readonly value="'.$row3['paxcity'].'" ></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Tel: </td><td><input type="text" size="15" name="poszk_tel" id="poszk_tel" class="disabled" value="'.$row3['paxphone'].'" readonly></td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Email: </td><td><input type="text" size="40" name="poszk_email" id="poszk_email" class="disabled" value="'.$row['pax_email'].'" readonly></td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
			<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Szkoda :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
						$result .= '<tr bgcolor="#BBBBBB"><td>Nr sprawy Allianz: </td><td><input type="text" size="30" name="nr_sprawy_allianz" id="nr_sprawy_allianz" class="disabled" readonly value="'.$row3['client_ref'].'"></td></tr>';						
						$result .= '<tr bgcolor="#BBBBBB"><td>Data zdarzenia: </td><td>
											<input type="text" name="eventDate_d" id="eventDate_d" size="1" value="'.$eventDate_tmp[2].'"  maxlength="2" style="text-align: center" class="disabled" readonly>&nbsp;
											<input type="text" name="eventDate_m" id="eventDate_m" size="1" value="'.$eventDate_tmp[1].'"  maxlength="2" style="text-align: center" class="disabled" readonly>&nbsp;
											<input type="text" name="eventDate_y" id="eventDate_y" size="4" value="'.$eventDate_tmp[0].'"  maxlength="4" style="text-align: center" class="disabled" readonly>
											</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zg³oszenia szkody<br>do ko³a ³owieckiego: </td><td>
											<input type="text" name="data_zgloszenia_do_kola_d" id="data_zgloszenia_do_kola_d" size="1" value="'.$data_zgloszenia_do_kola_tmp[2].'" maxlength="2" style="text-align: center" class="disabled" readonly>&nbsp;
											<input type="text" name="data_zgloszenia_do_kola_m" id="data_zgloszenia_do_kola_m" size="1" value="'.$data_zgloszenia_do_kola_tmp[1].'" maxlength="2" style="text-align: center" class="disabled" readonly>&nbsp;
											<input type="text" name="data_zgloszenia_do_kola_y" id="data_zgloszenia_do_kola_y" size="4" value="'.$data_zgloszenia_do_kola_tmp[0].'" maxlength="4" style="text-align: center" class="disabled" readonly>																						
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data wp³yniêcia<br> do CORIS: </td><td>
							<input type="text" name="notificationDate_d" id="notificationDate_d" size="1" value="'.$notificationdate_tmp[2].'" maxlength="2" style="text-align: center" readonly class="disabled">&nbsp;
											<input type="text" name="notificationDate_m" id="notificationDate_m" size="1" value="'.$notificationdate_tmp[1].'" maxlength="2" style="text-align: center" class="disabled" readonly>&nbsp;
											<input type="text" name="notificationDate_y" id="notificationDate_y" size="4" value="'.$notificationdate_tmp[0].'" maxlength="4" style="text-align: center" class="disabled" readonly>											
							</td></tr>';							
							$result .= '<tr bgcolor="#BBBBBB"><td>Szacuj±cy szkodê: </td><td>'.AllianzCase::getSzacujacy($row2['case_id'],$row2['ID_kolo'],$row2['ID_szacujacy'],1).' </td></tr>';														
							$result .= '<tr bgcolor="#BBBBBB"><td>Gatunek zwierzyny, <br>który wyrz±dzi³ szkodê: </td><td>
								<select multiple="multiple" class="disabled" size="6" name="gatunek_zwierzyny[]" onClick="sprawdz_zwierzyne()" disabled>';
							foreach ($lista_gatunkow As $poz){
									if ( $poz['selected']==1 )
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