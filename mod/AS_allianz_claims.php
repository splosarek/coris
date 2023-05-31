<?php
include_once('lib/lib_allianz.php');

$lista_admin_user = array(7,26,39,76,4);
		
	$lista = array('&nbsp;','Upowa¿niony','Instytucja');			
    $lista_status = array('Oczekuj±cy','Zaakceptowany','Odrzucony');	
    
    $lista_status_wyplata = array('Do weryfikacji','Zatwierdzone','Do poprawy','Poprawione');	
    
    $forma_wyplaty = array('','Przelew bankowy','Przekaz pocztowy');
    $lista_status_roszczenie = array('&nbsp;','Wyp³ata');

function module_update(){			
	global  $pageName;
	$result ='';
	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');	
	
	$check_js = '';
	$message = '';
	if (isset($change['ch_claims_zgloszenia']) && $case_id > 0  ){		
   		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){			   	
			$edit_form_action   = getValue('edit_form_action') ;							
			if ($edit_form_action == 'claims_add'){	
				
				$data_zgloszenia = getValue('data_zgloszenia');				
				$zgloszenie_uwagi = getValue('zgloszenie_uwagi');
								
				$zgloszenie_kwota = str_replace(',','.',getValue('zgloszenie_kwota'));
				$zgloszenie_kwota_rws = str_replace(',','.',getValue('zgloszenie_kwota_rws'));
				$zgloszenie_opis_roszczenia = getValue('zgloszenie_opis_roszczenia');					
				$zgloszenie_currency_id = 'PLN';
				
				$cl = new AllianzClaim(0,$case_id,1);
				$cl->setAnnounce_date($data_zgloszenia);
				$cl->setNote($zgloszenie_uwagi);

				$cls = new AllianzClaimDetails(0, 0,1);
				$cls->setKwota_roszczenia($zgloszenie_kwota);
				$cls->setKwota_rezerwa($zgloszenie_kwota);
				$cls->setKwota_rws($zgloszenie_kwota_rws);
				$cls->setNote($zgloszenie_opis_roszczenia);			
				$cls->setRefundacja(1);
				$cls->setFranszyza(1);
				$cls->setRefundacja_kwota(40);	
							
				$cl->addClaimDetails($cls);					
								
				$tmp = isset( $_POST['add_zgloszenie_opis_roszczenia'] ) ?  $_POST['add_zgloszenie_opis_roszczenia']  : null ;						
				if (is_array($tmp)){
					foreach ( $tmp As   $key => $val ){									
						$zgloszenie_opis_roszczenia = addslashes(stripslashes(trim($val)));
						$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota'][$key]))));																								
						$zgloszenie_kwota_rws = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota_rws'][$key]))));																																																	
						$zgloszenie_currency_id= 'PLN';																			
						$add_zgloszenie_pozycja_usun = addslashes(stripslashes(trim($_POST['add_zgloszenie_pozycja_usun'][$key])));												
						$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);					
																					
						if ($add_zgloszenie_pozycja_usun != 1 && $zgloszenie_kwota>0){
								$cls = new AllianzClaimDetails(0, 0,1);
								$cls->setKwota_roszczenia($zgloszenie_kwota);
								$cls->setKwota_rezerwa($zgloszenie_kwota);
								$cls->setKwota_rws($zgloszenie_kwota_rws);
								$cls->setNote($zgloszenie_opis_roszczenia);
													
								$cl->addClaimDetails($cls);										
						}											
					}								
				}
				$cl->store();		
			}else if ($edit_form_action == 'claims_save'){
					
				$claims_id = getValue('claims_id');																		
				$data_zgloszenia = getValue('data_zgloszenia');				
				$zgloszenie_uwagi = getValue('zgloszenie_uwagi');
				
				$cl = new AllianzClaim($claims_id,$case_id);
				$cl->setAnnounce_date($data_zgloszenia);
				$cl->setNote($zgloszenie_uwagi);
			
				foreach ($cl->getClaimDetails() As $pozycjaCL  ){
					$key = $pozycjaCL->getID();
					
					if (isset($_POST['zgloszenie_opis_roszczenia'][$key])){
						$zgloszenie_pozycja_usun = @addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_usun'][$key])));
						$zgloszenie_pozycja_status = @addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_status'][$key])));
						$wyslij_do_akceptacji = @addslashes(stripslashes(trim($_POST['wyslij_do_akceptacji'][$key])));
						
						$zgloszenie_opis_roszczenia = @addslashes(stripslashes(trim($_POST['zgloszenie_opis_roszczenia'][$key])));
						$zgloszenie_uwagi_roszczenia = @addslashes(stripslashes(trim($_POST['zgloszenie_uwagi_roszczenia'][$key])));					
						$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_kwota'][$key]))));
						$zgloszenie_kwota_rws = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_kwota_rws'][$key]))));
						$zgloszenie_kwota_zaakceptowana = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_kwota_zaakceptowana'][$key]))));
						$zgloszenie_wyplata_zaakceptowana = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_wyplata_zaakceptowana'][$key]))));
						
						$zgloszenie_pozycja_franszyza = intval ($_POST['zgloszenie_pozycja_franszyza'][$key]);
						$zgloszenie_pozycja_franszyza_wartosc = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_franszyza_wartosc'][$key]))));
						
						$zgloszenie_pozycja_inne_odl = intval ($_POST['zgloszenie_pozycja_inne_odl'][$key]);
						$zgloszenie_pozycja_inne_odl_wartosc = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_inne_odl_wartosc'][$key]))));
						
						$zgloszenie_pozycja_refundacja = intval ($_POST['zgloszenie_pozycja_refundacja'][$key]);
						$zgloszenie_wyplata_refundacji = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_wyplata_refundacji'][$key]))));
						


						
						if ($zgloszenie_pozycja_usun){
							$pozycjaCL->setDelete();
						}else{
							$pozycjaCL->setKwota_roszczenia($zgloszenie_kwota);
							$pozycjaCL->setKwota_rezerwa($zgloszenie_kwota);
							$pozycjaCL->setKwota_rws($zgloszenie_kwota_rws);
							$pozycjaCL->setKwota_zaakceptowana($zgloszenie_kwota_zaakceptowana);
							$pozycjaCL->setWyplata_zaakceptowana($zgloszenie_wyplata_zaakceptowana);

							$pozycjaCL->setFranszyza($zgloszenie_pozycja_franszyza);
							$pozycjaCL->setFranszyza_kwota($zgloszenie_pozycja_franszyza_wartosc);
							$pozycjaCL->setOdliczenie($zgloszenie_pozycja_inne_odl);
							$pozycjaCL->setOdliczenie_kwota($zgloszenie_pozycja_inne_odl_wartosc);
							$pozycjaCL->setRefundacja($zgloszenie_pozycja_refundacja);
							$pozycjaCL->setRefundacja_kwota($zgloszenie_wyplata_refundacji);
							
							$pozycjaCL->setNote($zgloszenie_opis_roszczenia);	
							$pozycjaCL->setStatus_note($zgloszenie_uwagi_roszczenia);	
							$pozycjaCL->setStatus($zgloszenie_pozycja_status);	
							
							if ($wyslij_do_akceptacji==1){
								$pozycjaCL->zmienStatus2DoAkceptacji();		
							}
						}
					}
					
					$zgloszenie_akceptacja   = @addslashes(stripslashes(trim($_POST['zgloszenie_akceptacja'][$key])));
					

					if ($zgloszenie_akceptacja==1 || $zgloszenie_akceptacja==2){
							$zgloszenie_akceptacja_uwagi   = @addslashes(stripslashes(trim($_POST['zgloszenie_akceptacja_uwagi'][$key])));
						
							if ($zgloszenie_akceptacja==1){ // akceptacja			
								$cas = AllianzCase::getCaseInfo($case_id);
								
								$status_sumy_ubezpieczenia = AllianzCase::getKoloDostepnaSumaUbezpieczenia($cas['ID_kolo']);								
  							
  								
  								if ( ev_round($pozycjaCL->getWyplata_zaakceptowana(),2) <= ev_round($status_sumy_ubezpieczenia['dostepna_suma_ubezpeczenia'],2) ){ //sprawdzamy czy wyplata nie przekracza dost. sum ubezp. 								
										$pozycjaCL->setStatus2_note($zgloszenie_akceptacja_uwagi);
										$pozycjaCL->setStatus2(3);
										$pozycjaCL->zmienStatus2();	
										// generate decyzja + platnosc
										$pozycjaCL->createDecision($case_id);
										$pozycjaCL->createPayment($case_id);										
  								}else{
  										echo '<script>alert("Blad!!! Kwota wyplaty przekracza sume ubezpieczenia. Roszczenie nie zosta³o zaakceptowane!!WA:'.$pozycjaCL->getWyplata_zaakceptowana().' DSU:'.$status_sumy_ubezpieczenia['dostepna_suma_ubezpeczenia'].'")</script>';
  								}
							}
													
							if ($zgloszenie_akceptacja==2){ // do poprawy
								$pozycjaCL->setStatus2_note($zgloszenie_akceptacja_uwagi);
								$pozycjaCL->setStatus2(2);
								$pozycjaCL->zmienStatus2();								
							}							
					}
					
					
					$cofnij_akceptacje   = @addslashes(stripslashes(trim($_POST['cofnij_akceptacje'][$key])));
					if ($cofnij_akceptacje==1 && AllianzCase::isAdmin()){  // wycofanie akceptacji
						$pozycjaCL->setStatus2(1);
						$pozycjaCL->zmienStatus2();	
						$pozycjaCL->DeletePayment();
						$pozycjaCL->DeleteDecision();
						
					}
				}
				
				$tmp = isset( $_POST['add_zgloszenie_opis_roszczenia'] ) ?  $_POST['add_zgloszenie_opis_roszczenia']  : null ;						
				if (is_array($tmp)){
					foreach ( $tmp As   $key => $val ){									
						$zgloszenie_opis_roszczenia = addslashes(stripslashes(trim($val)));
						$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota'][$key]))));																								
						$zgloszenie_kwota_rws = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota_rws'][$key]))));																																																	
						$zgloszenie_currency_id= 'PLN';																			
						$add_zgloszenie_pozycja_usun = addslashes(stripslashes(trim($_POST['add_zgloszenie_pozycja_usun'][$key])));												
						$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);					
																					
						if ($add_zgloszenie_pozycja_usun != 1 ){//&& $zgloszenie_kwota>0
								$cls = new AllianzClaimDetails(0, 0,1);
								$cls->setKwota_roszczenia($zgloszenie_kwota);
								$cls->setKwota_rezerwa($zgloszenie_kwota);
								$cls->setKwota_rws($zgloszenie_kwota_rws);
								$cls->setNote($zgloszenie_opis_roszczenia);					
								$cl->addClaimDetails($cls);											
						}											
					}								
				}
				$cl->store();		
				
					
					$zgloszenie_usun = getValue('zgloszenie_usun');								
					if ($zgloszenie_usun==1){
						$qt = "SELECT ID FROM coris_assistance_cases_claims_details   WHERE ID_claims='$claims_id' ";
						$mt = mysql_query($qt);
						if (mysql_num_rows($mt) == 0 ){																					
									$del = "DELETE FROM coris_assistance_cases_claims  WHERE ID='$claims_id' LIMIT 1";																																			
									mysql_query($del);
						}
					}				
			}			
		}
	}else 	if (isset($change['ch_claims_decyzje']) && $case_id > 0  ){		
		$res=check_update($case_id,'rezerwy_decyzje');
		if ($res[0]){			   	
				
			$edit_form_action   = getValue('edit_form_action') ;		
			
			if ($edit_form_action=='decissions_save'){			
					
					$decisions_id = getValue('decisions_id');
					$tekst1 = getValue('tekst1');
					$tekst2 = getValue('tekst2');
					AllianzDecision::updateDecision($decisions_id, $tekst1, $tekst2);
	
			}
		}				
	}else 	if (isset($change['ch_claims_wyplaty']) && $case_id > 0  ){		
		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){			   	
						
			
			$edit_form_action   = getValue('edit_form_action') ;		
			
			if ($edit_form_action=='pay_save'){
					$roszczenie = getValue('roszczenie');
					$roszczenie_pozycja = $_POST['roszczenie_pozycja'];
					
					if ($roszczenie>0 &&  is_array($roszczenie_pozycja)){
							$pay_type = getValue('pay_type')		;					
							
							$bank_name=getValue('bank_name');
							$account_number = getValue('account_number');
							$note = getValue('note');
							
							$row_case = row_case_info($case_id);

							$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' AND  ID = '$roszczenie'";						
							$mr = mysql_query($q1);							
							$r = mysql_fetch_array($mr);		
					
							
							$q2="SELECT * FROM coris_assistance_cases_announce WHERE case_id ='$case_id' ";
							$mr2 = mysql_query($q2);
							$row_case_ann = mysql_fetch_array($mr2);
							
							$announcer=$r['announcer'];
							$name='';
							$surname='';
							$adress='';
							$post='';
							$city='';
							$sex = '';

							if ($announcer==1){//upowa¿niony
									$sex =   $row_case_ann['upowaz_plec'];
									$name =   $row_case_ann['upowaz_imie'];
									$surname = $row_case_ann['upowaz_nazwisko'];
									$adress .= $row_case_ann['upowaz_ulica'];
									$post .= $row_case_ann['upowaz_kod'];
									$city = $row_case_ann['upowaz_miasto'];		
									
									$konto_numer = 	$row_case_ann['upowaz_konto'];				
									$bank_nazwa = 	$row_case_ann['upowaz_bank_nazwa'];				
									$wyplata_typ = 	$row_case_ann['upowaz_pay_type'];					
															
							}else if ($announcer==2){//Instytucja
									$qi = "SELECT * FROM coris_signal_institution2 WHERE kod='".$r['ID_institution']."'";
									$mri = mysql_query($qi);
									$ri = mysql_fetch_array($mri);
									
										
									$name =   $ri['nazwa'];
									$surname ='';
									$adress .= $ri['ulica'];
									$post .= $ri['kod_pocz'];
									$city = $ri['miasto'];
									
									$konto_numer = $ri['KONTO'];
									$bank_nazwa = '';
									$wyplata_typ = 1;	
							}


						$qi = "INSERT INTO coris_assistance_cases_claims_pay  SET ID_case='$case_id', ID_claims='$roszczenie', ID_user='".$_SESSION['user_id']."',date=now(), status0_user_id='".$_SESSION['user_id']."',status0_date =now(),  amount=0.00 ,pay_type='$pay_type'  ";
						$qi .= " ,bank_name='$bank_name',account_number='$account_number',note='$note',announcer='$announcer',ID_institution='".$r['ID_institution']."',sex='$sex',name='".addslashes(stripslashes($name))."',surname='".addslashes(stripslashes($surname))."',adress='".addslashes(stripslashes($adress))."',post='$post',city='".addslashes(stripslashes($city))."' ";
						
						
						$mysql_result = mysql_query($qi);
						if ($mysql_result){
											//$message .= "Udpate OK ".$query;							
										}else{
											$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
						}
						if ($mysql_result){
								$cp_id = mysql_insert_id();
								 
								 $query = "SELECT coris_assistance_cases_claims_details.*,
							(SELECT nazwa FROM coris_signal_ryzyka_czastkowe WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_claims_details.ID_risk  ) As ryzyko, 
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.ID_user ) As user,
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.status_ID_user ) As status_user
							FROM coris_assistance_cases_claims_details WHERE ID_claims='".$r['ID']."' AND ID IN (".implode(',',$roszczenie_pozycja).") ORDER BY ID";
							
							$mrd = mysql_query($query);
							$suma = 0.0;
							while ($row_rd=mysql_fetch_array($mrd)){		
										$pozycja=$row_rd['ID'];
										
										/*if ($row_rd['currency_id'] == 'PLN')
											$amount_pln = $row_rd['amount_accept'];
										else{
											
										}
											$amount_pln = (ev_round(getKursyX('',1,$row_rd['currency_id'],1,$row_rd['currency_table_id'])*$row_rd['amount_accept'],2));
										*/
										$claim_note = addslashes(stripslashes($_POST['claim_note'][$pozycja]));
										$qi = "INSERT INTO coris_assistance_cases_claims_pay_position SET ID_claims_pay = '$cp_id', note='$claim_note' ";
										$qi .= " ,amount='".$row_rd['amount_accept']."', amount_pln='".$amount_pln."',currency_id ='".$row_rd['currency_id']."',currency_table_id='".$row_rd['currency_table_id']."',
										ID_risk ='".$row_rd['ID_risk']."',ID_operat ='".$row_rd['ID_operat']."',ID_claims_details='".$row_rd['ID']."'
										  ";										
										$mysql_result = mysql_query($qi);
											if ($mysql_result){
												//$message .= "Udpate OK ".$query;							
											}else{
												$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
											}
									//	$suma += $row_rd['amount_accept_pln'];				
										$suma += $amount_pln;				
										$mrx = mysql_query("UPDATE coris_assistance_cases_claims_details  SET status=1 WHERE ID ='".$row_rd['ID']."'");																					
							}
							
							$qu = " UPDATE  coris_assistance_cases_claims_pay SET amount = '$suma' WHERE ID='$cp_id' LIMIT 1"	;
							$mysql_result = mysql_query($qu);
							
														
								
							/*	foreach ($roszczenie_pozycja As $pozycja){
									$claim_note = addslashes(stripslashes($_POST['claim_note'][$pozycja]));
										$qi = "INSERT INTO coris_assistance_cases_claims_pay_position SET ID_claims_pay = '$cp_id', note='$claim_note' ";
										
										$mysql_result = mysql_query($qi);
											if ($mysql_result){
												//$message .= "Udpate OK ".$query;							
											}else{
												$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
											}
								
									
									
								}
						*/
						}	
						
					}					
			}else if ($edit_form_action=='save_status_pay'){
				
				$pay_id  = getValue('edit_form_action_val');			
							//status
							//old_status		
					$status=	getValue('status');	
					$old_status=	getValue('old_status');	
						
					$status_send=	getValue('status_send');	
					$old_status_send=	getValue('old_status_send');	
					
					if ($status_send>0 && $status_send != $old_status_send && $pay_id>0){
						$query = "UPDATE coris_assistance_cases_claims_pay  SET status_send='$status_send' WHERE ID=$pay_id "	;						
						$mysql_result = mysql_query($query);
					}
					
					if ($old_status==1 && $status==2){ // powtorna wysylka sprawy
						$query = "UPDATE coris_assistance_cases_claims_pay  SET status_send='0' WHERE ID=$pay_id "	;						
						$mysql_result = mysql_query($query);
					}
					
					if ($status>0 && $status != $old_status && $pay_id>0){
							$val  = ", status".$status."_user_id='".$_SESSION['user_id']."', status".$status."_date=now()  ";
							if ($status==1){
									$qq1= "SELECT announcer, ID_institution FROM coris_assistance_cases_claims_pay WHERE ID='$pay_id' AND number=0";
									$mm = mysql_query($qq1);
									
									if ( mysql_num_rows($mm)>0 ){
											$rr = mysql_fetch_array($mm);
									
											$qq = "SELECT MAX(number)+1  FROM coris_assistance_cases_claims_pay WHERE ID_case='$case_id' AND announcer='".$rr['announcer']."' AND ID_institution='".$rr['ID_institution']."' ";
											$mm = mysql_query($qq);
											$rr = mysql_fetch_array($mm);
																
											$val .= " , number='".$rr[0]."' ";			
									}
								
							}
					
							$query = "UPDATE coris_assistance_cases_claims_pay  SET status='$status' $val WHERE ID=$pay_id "	;
						
						
							$mysql_result = mysql_query($query);
								if ($mysql_result){
												//$message .= "Udpate OK ".$query;			
												if ($status==1 || $status==3) { // zatwierdzone, poprawione
													$query = "UPDATE coris_assistance_cases_claims_details  SET status=1 WHERE ID IN (SELECT ID_claims_details FROM  coris_assistance_cases_claims_pay_position WHERE ID_claims_pay ='$pay_id')";							
													$mr=mysql_query($query);
												}
					    						if ($status==2 ) { // zatwierdzone, poprawione
													$query = "UPDATE coris_assistance_cases_claims_details  SET status=0 WHERE ID IN (SELECT ID_claims_details FROM  coris_assistance_cases_claims_pay_position WHERE ID_claims_pay ='$pay_id')";							
													$mr=mysql_query($query);
												}				
								}else{
										$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
								}
																	
					}
					
					$status_zlecenie = getValue('status_zlecenie') == 1 ? 1 : 0 ;
					if ($status_zlecenie == 1 ){
							$q = "INSERT INTO coris_assistance_cases_claims_lista_platnosci 
							SET ID_claims_pay = '$pay_id',
							ID_platnosc = 0,
							date= now(),
							 user_id  = '".$_SESSION['user_id']."',
							 status=0;
							";
						$rs = mysql_query($q);
						if ($rs){
							$qu = "UPDATE coris_assistance_cases_claims_pay SET status_zlecenie=1,status_zlecenie_date=now(),status_zlecenie_user_id='".$_SESSION['user_id']."' WHERE ID=$pay_id LIMIT 1 ";
							$mr = mysql_query($qu);
							if (!$mr){
									$message .= "UPDATE Error: ".$qu."\n<br> ".mysql_error();	
							}												
						}else{
							$message .= "INSERT Error: ".$query."\n<br> ".mysql_error();		
						}
						
					}
					if ($status==1 || $old_status ==1 ){
						global $change;
						$change['claims_wyplaty']=1;
						$_POST['edit_form_action']='edit_pay';
						$_POST['edit_form_action_val']=$pay_id;
						
			
					}
			
					
			
					
					$message .= $edit_form_action_val ;			
				
			
		}else if ($edit_form_action=='delete_pay'){
				$pay_id  = getValue('edit_form_action_val');
					
				/*
					$query = "UPDATE coris_assistance_cases_claims_details  SET status=0 WHERE ID IN (SELECT ID_claims_details FROM  coris_assistance_cases_claims_pay_position WHERE ID_claims_pay ='$pay_id')";
					$mr=mysql_query($query);
					
					$query2 = "DELETE  FROM  coris_assistance_cases_claims_pay_position WHERE ID_claims_pay ='$pay_id' ";				
					$mr=mysql_query($query2);
					
					$query3 = "DELETE  FROM  coris_assistance_cases_claims_pay WHERE ID='$pay_id' ";				
					$mr=mysql_query($query3);
					*/
					$message .= 'Wyp³ata skasowana';							
					
			}
		
		}
	}else 	if (isset($change['claims_wyplaty']) && $case_id > 0  ){		
		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){			   	
						
			
			$edit_form_action   = getValue('edit_form_action') ;		
			if ($edit_form_action=='edit_pay'){
					$pay_action = getValue('pay_action');
					if ($pay_action == 'aktualizacja'){
							$pay_id  = getValue('edit_form_action_val');
							
							$qq1= "SELECT * FROM coris_assistance_cases_claims_pay WHERE ID='$pay_id' ";
							$mm = mysql_query($qq1);
							$row_pay = mysql_fetch_array($mm);
								
								
							$row_case = row_case_info($case_id);

							$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' AND  ID = '".$row_pay['ID_claims']."'";						
							$mr = mysql_query($q1);							
							$r = mysql_fetch_array($mr);		
					
							
							$q2="SELECT * FROM coris_assistance_cases_announce WHERE case_id ='$case_id' ";
							$mr2 = mysql_query($q2);
							$row_case_ann = mysql_fetch_array($mr2);
							
							$announcer=$r['announcer'];
							$name='';
							$surname='';
							$adress='';
							$post='';
							$city='';
							$sex='';
							if ($announcer==1){//upowa¿niony									
									$sex =   $row_case_ann['upowaz_plec'];
									$name =   $row_case_ann['upowaz_imie'];
									$surname = $row_case_ann['upowaz_nazwisko'];
									$adress .= $row_case_ann['upowaz_ulica'];
									$post .= $row_case_ann['upowaz_kod'];
									$city = $row_case_ann['upowaz_miasto'];		
									
									$konto_numer = 	$row_case_ann['upowaz_konto'];				
									$bank_nazwa = 	$row_case_ann['upowaz_bank_nazwa'];				
									$wyplata_typ = 	$row_case_ann['upowaz_pay_type'];					
															
							}else if ($announcer==2){//Instytucja
									$qi = "SELECT * FROM coris_signal_institution2 WHERE kod='".$r['ID_institution']."'";
									$mri = mysql_query($qi);
									$ri = mysql_fetch_array($mri);
									
										
									$name =   $ri['nazwa'];
									$surname ='';
									$adress .= $ri['ulica'];
									$post .= $ri['kod_pocz'];
									$city = $ri['miasto'];
									
									$konto_numer = $ri['KONTO'];
									$bank_nazwa = '';
									$wyplata_typ = 1;	
							}

							
							
							
							$amount=0.0;
							
							$qq = "SELECT ID,ID_claims_details  FROM coris_assistance_cases_claims_pay_position WHERE ID_claims_pay='$pay_id'";
							$mm = mysql_query($qq);
							while ($rr = mysql_fetch_array($mm)){
									$qq1 = "SELECT coris_assistance_cases_claims_details.*,
									(SELECT nazwa FROM coris_signal_ryzyka_czastkowe WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_claims_details.ID_risk  ) As ryzyko 																
									FROM coris_assistance_cases_claims_details WHERE  ID ='".$rr['ID_claims_details']."'";
									$mr1 = mysql_query($qq1);
									$row_rd=mysql_fetch_array($mr1);
									
									/*if ($row_rd['currency_id'] == 'PLN')
											$amount_pln = $row_rd['amount_accept'];
										else
											$amount_pln = (ev_round(getKursyX('',1,$row_rd['currency_id'],1,$row_rd['currency_table_id'])*$row_rd['amount_accept'],2));
									*/
									$qi = "UPDATE  coris_assistance_cases_claims_pay_position SET ";
									//$qi .= " amount='".$row_rd['amount_accept']."', amount_pln=".$row_rd['amount_accept_pln'].",currency_id ='".$row_rd['currency_id']."',currency_table_id='".$row_rd['currency_table_id']."',
									$qi .= " amount='".$row_rd['amount_accept']."', amount_pln=".$amount_pln.",currency_id ='".$row_rd['currency_id']."',currency_table_id='".$row_rd['currency_table_id']."',
									ID_risk ='".$row_rd['ID_risk']."',ID_operat ='".$row_rd['ID_operat']."',ID_claims_details='".$row_rd['ID']."'
									WHERE ID='".$rr['ID']."'";		
									
									$rrr = mysql_query($qi)	;
									
											if ($rrr){
												//$message .= "Udpate OK ".$query;							
											}else{
												$message .= "UPDATE Error: ".$qi."\n<br> ".mysql_error();				
											}
								//$amount += $row_rd['amount_accept_pln'];								
								$amount += $amount_pln;								
							}
					
								 								 													
							
							$query = "UPDATE coris_assistance_cases_claims_pay SET announcer='$announcer', ID_institution='".$r['ID_institution']."',
							sex='$sex', name='".addslashes(stripslashes($name))."', surname='".addslashes(stripslashes($surname))."', adress='".addslashes(stripslashes($adress))."',
							post='".addslashes(stripslashes($post))."', city='".addslashes(stripslashes($city))."', bank_name='".addslashes(stripslashes($bank_nazwa))."',
							account_number='$konto_numer', pay_type='$wyplata_typ',amount='$amount'							
							WHERE ID='$pay_id' LIMIT 1";
							$rrr = mysql_query($query)	;
									if ($rrr){
												//$message .= "Udpate OK ".$query;							
											}else{
												$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
											}
							//$message .= 'aktualizacja '.$pay_id	;
					}							
			}
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

		
		$row_case_ann = AllianzCase::getCaseInfo($case_id);		
		
if ($row_case_settings['client_id'] == 9){
		$result .=  '<div style="width: 940px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  ubezpieczenie($row_case,$row_case_settings,$row_case_ann);	
	$result .=  '</div>';
	$result .=  '<div style="width: 940px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  roszczenia($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 940px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  decyzje($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';		
$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 940px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  wyplaty($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';	
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 940px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw TU ALLIANZ</b></div>
	</div>
	';
	
}
			$result .=  '<div style="clear:both;"></div>';
	return $result;	
}




function ubezpieczenie($row_case,$row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	global $lista_status,$lista;
		
						
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_ubezpieczenie" id="form_ubezpieczenie">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Ubezpieczenie</b></font></small>&nbsp;
				</td>
				<td align="right">';

	
				$result .= '</td>	
			</tr>
			</table>';	      
				
				$dane_ub = AllianzCase::ubezpieczenie($row_case_ann['ID_kolo']);
				$franszyza_info= 'Franszyza '.($dane_ub['franszyza_rodzaj']==1 ? 'Integralna' : '').($dane_ub['franszyza_rodzaj']==2 ? 'Redukcyjna' : '').' '.print_currency($dane_ub['franszyza_kwota']).' PLN';
				$suma_ubezpieczenia= print_currency($dane_ub['suma_ubezpieczenia']) ;
				
  		$result = '
  		<script>
  		
  		function xopen_history(id){			 
			window.open(\'AS_allianz_kola_history.php?id=\' + id, \'HistoriaKola\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=700,left=\'+ (screen.availWidth - 750) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
		}
  		function xopen_note(id){			 
			window.open(\'AS_allianz_kola_note.php?id=\' + id, \'HistoriaKola\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=700,left=\'+ (screen.availWidth - 750) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
		}
  		</script>';
  		
  		$status_sumy_ubezpieczenia = AllianzCase::getKoloDostepnaSumaUbezpieczenia($row_case_ann['ID_kolo']);
  		
  		
  		$style = 'style="background-color:#00FF00"';
  		
  		if ($status_sumy_ubezpieczenia['status'] == 'warning' )
  			$style = 'style="background-color:#FFFF00"';
		
  		if ($status_sumy_ubezpieczenia['status'] == 'error' )
  			$style = 'style="background-color:#FF0000"';
  			
		$result .= '
		<input type="hidden" name="dostepna_suma_ubezpieczenia" id="dostepna_suma_ubezpieczenia" value="'.$status_sumy_ubezpieczenia['dostepna_suma_ubezpeczenia'].'">
		    <table cellpadding="5" cellspacing="0" border="1" align="center" width=60%>
		      <tr><td width="200" align="center"><b>Nr sprawy Allianz</b></td><td >'.$row_case['client_ref'].' &nbsp;</td></tr>
		      <tr><td width="200" align="center"><b>Nr polisy</b></td><td >'.$row_case['policy_series'].' '. $row_case['policy'].'&nbsp;</td></tr>
		      <tr><td align="center"><b>Suma ubezpieczenie</b></td><td >'.$suma_ubezpieczenia.' PLN</td></tr>							      				
		      <tr><td align="center"><b>Franszyza</b></td><td >'.$franszyza_info.'</td></tr>					
		      <tr '.$style.'><td align="center" '.$style.'><b>Dostêpna suma ubezpieczenia</b></td><td > '.print_currency($status_sumy_ubezpieczenia['dostepna_suma_ubezpeczenia']).'  PLN</td></tr>
		      <tr><td align="center">&nbsp;</td><td ><a href="javascript:;" onClick="xopen_history('.$row_case_ann['ID_kolo'].');"><b>Historia wyp³at ko³a £owieckiego</b></a></td></tr>
		      <tr><td align="center">&nbsp;</td><td ><a href="javascript:;" onClick="xopen_note('.$row_case_ann['ID_kolo'].');"><b>Lista notatek ko³a £owieckiego</b></a></td></tr>';
			  
				$kolo = AllianzCase::getKoloInfo($row_case_ann['ID_kolo']);
				if (trim($kolo['adres_do_korespondencji']) != '')
				$result .= '<tr bgcolor="C1573A"><td align="center"><b>Adres do korespondencji</b></td><td >'.nl2br(trim($kolo['adres_do_korespondencji'])).'</td></tr>';
			  $result .= '</table><br>
			  ';
	
	$result .= '</form>';
	return $result;
	
}


function roszczenia($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	global $lista_status,$lista;
		
						
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_roszczenia" id="form_roszczenia">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Roszczenia</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['claims_zgloszenia'])){
		
			if (check_claim_handler_user() || check_claim_admin()	){
	
				$result .= '<div style="float:rigth;padding:2px">								
				<input type="hidden" name="change[ch_claims_zgloszenia]" id="change[ch_claims_zgloszenia]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
			}else{				
				$result .= '<div style="float:rigth;padding:2px">								
				<input type="hidden" name="err_change[ch_claims_zgloszenia]" id="err_change[ch_claims_zgloszenia]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">				
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
				
				$result .= '<br><br><br><br><br><div align="left"><b>Brak uprawnieñ</b></div><br><br><br><br><br>';
				$result .= '</td>	</tr></table>';
				return $result;
			}
	
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[claims_zgloszenia] value=1>
				<input type="hidden" name="edit_form" value="1">				
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';		
	}				
		$result .= '</td>	
			</tr>
			</table>';	      
if (isset($change['claims_zgloszenia'])){
	
	
			$result .= '
			<script>
			ayax_action=0;
			function edycja_zgloszenia(id){
				if (id>0){
					document.getElementById(\'edit_form_action\').value=\'claims_edit\';	
					document.getElementById(\'edit_form_action_param\').value=id;	
					
					document.getElementById(\'change[ch_claims_zgloszenia]\').name=\'change[claims_zgloszenia]\';	
					
					document.getElementById(\'form_roszczenia\').submit();		
				
				}
			}
			
			function zapisz_roszczenie(){	
				if (ayax_action==1){
					alert(\'Przeliczanie walut w trakcie, prosze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'claims_save\';	
					document.getElementById(\'form_roszczenia\').submit();					
				}
			}
						
			function dodaj_roszczenie(){						
					if (  (document.getElementById(\'zgloszenie_kwota\').value.replace(\',\',\'.\') == \'\' ) ){																	
							alert(\'Proszê podaæ kwotê roszczenia.\');
							document.getElementById(\'zgloszenie_kwota\').focus();
							return false;
					}
					
					document.getElementById(\'edit_form_action\').value=\'claims_add\';	
					document.getElementById(\'form_roszczenia\').submit();						
			}
			
			
			function dodaj_pozycje(obj){
				//	alert(document.getElementById(\'panel_pozycje\').innerHTML);
					pozycja = \'<table  cellpadding="1" cellspacing="0" border=1 width="100%"><tr  bgcolor="#BBBBBB"><tr><td align="center" width="34"><input type="checkbox" name="add_zgloszenie_pozycja_usun[]" id="add_zgloszenie_pozycja_usun[]" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td><td width="343"><textarea cols=50 rows="2" name="add_zgloszenie_opis_roszczenia[]" id="add_zgloszenie_opis_roszczenia[]"></textarea></td><td align="center" width="118"><input style="text-align:right;" type="text" size="10" value="0" name="add_zgloszenie_kwota[]" id="add_zgloszenie_kwota[]"  > PLN</td><td align="center"><input style="text-align:right;" type="text" size="10" value="0" name="add_zgloszenie_kwota_rws[]" id="add_zgloszenie_kwota_rws[]"  > PLN</td></tr></table>\';					
					document.getElementById(\'panel_pozycje\').innerHTML +=  pozycja;		
					
			}		

			
</script>
			';
			$lista = AllianzCase::getClaims($case_id);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      								
		      		<td width="15%" align="center"><b>Data zg³oszenia</b></td>						
					<td width="40%" align="center"><b>Note</b></td>					
					<td width="20%" align="center"><b>U¿ytkownik</b></td>					
					<td width="20%" align="center"><b>Data rej. / ostatniej zm.</b></td>
					<td width="5%" align="center">&nbsp;</td>
			  </tr><tr>';						
						
			foreach ($lista As $pozycja  ){
				$cl = $pozycja;				 			
				$result .= '<tr bgcolor="#BBBBBB">';					
					$result .= '<td align="center">'.$cl->getAnnounce_date().'</td>';													
					$result .= '<td>'.$cl->getNote().'&nbsp;</td>';													
					$result .= '<td>'.Application::getUserName($cl->getUserID()).'</td>';													
					$result .= '<td>'.$cl->getDate().'</td>';
					$result .= '<td align="center"><a href="javascript:edycja_zgloszenia('.$cl->getID().')">Edycja</a>&nbsp;</td>';																												
				$result .= '</tr>';	  
		 		$result .= '<td colspan="5" align="right">';	
		 				$result .='<table cellpadding="1" cellspacing="0" border="1"  width=90%>
						  <tr bgcolor="#CCCCCC">';
		 				$result .= '<td width="5%" align="center">&nbsp;</td>';
						  $result .= '
						  <td width="70" align="center"><b>Roszczenie</td>
						  <td width="70" align="center"><b>RWS</td>						  							
						  <td width="70" align="center"><b>Kwota zaakcept.</b></td>		
						  <td width="90" align="center"><b>Wyp³ata zaakcept.</b></td>		
						  <td width="70" align="center"><b>Status</b></td>										
						  <td width="70" align="center"><b></b></td>										
						  <td width="70" align="center"><b>U¿ytkownik</b></td>										
							 </tr >';	
					
						  foreach ($pozycja->getClaimDetails() As $pozycjaCL  ){
						  				$result .= '<tr>';
						  					$result .= '<td >&nbsp;</td>';
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_roszczenia()).'</td>';													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_rws()).'&nbsp;</td>';													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_zaakceptowana()).'</td>';													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getWyplata_zaakceptowana()).'</td>';													
											$result .= '<td align="center">'.$pozycjaCL->getStatusName().'</td>';													
											$result .= '<td align="center">'.$pozycjaCL->getStatus2Name().'&nbsp;</td>';													
											$result .= '<td>'.Application::getUserName($pozycjaCL->getStatus_userID()).'</td>';													
																																		
										$result .= '</tr>';	  
						  }
						  $result .= '</table>';
						  
						$result .= '</td></tr>';	
			}					
						
				 $result .= '</table>';

			$result  .= '<hr>';						
			if (getValue('edit_form_action') == 'claims_edit' && getValue('edit_form_action_param') > 0  ){
						
				$edit_cl = getValue('edit_form_action_param');
				
				$cl = new AllianzClaim($edit_cl, $case_id);
					
				$dane_ub = AllianzCase::ubezpieczenie($row_case_ann['ID_kolo']);
				$franszyza_info= ($dane_ub['franszyza_rodzaj']==1 ? 'Integralna' : '').($dane_ub['franszyza_rodzaj']==2 ? 'Redukcyjna' : '').' '.print_currency($dane_ub['franszyza_kwota']).' PLN';
				$suma_ubezpieczenia= print_currency($dane_ub['suma_ubezpieczenia']) ;
				
					$result .= '
					<script>
					function przelicz(id){
						form_zgloszenie_wyplata_zaakceptowana = $(\'zgloszenie_wyplata_zaakceptowana[\'+id+\']\');		
							
						franszyza_rodzaj =$(\'franszyza_rodzaj\').value; //(1- Integralna, 2 - Redukcyjna)  
						franszyza_kwota= 1.0 * $(\'franszyza_kwota\').value.replace(\',\',\'.\');
						
						kwota_start = 1.0 * $(\'zgloszenie_kwota_zaakceptowana[\'+id+\']\').value.replace(\',\',\'.\');
						kwota = kwota_start;
						
						if ($(\'zgloszenie_pozycja_franszyza[\'+id+\']\').checked){
							if ($(\'franszyza_rodzaj\').value == 2 ) {  //redukcyjna
									
									if (franszyza_kwota<= kwota_start  ){
										$(\'zgloszenie_pozycja_franszyza_wartosc[\'+id+\']\').value = $(\'franszyza_kwota\').value.replace(\'.\',\',\');
										kwota = kwota_start - franszyza_kwota;
									}else{
										franszyza_kwota = kwota_start;
										$(\'zgloszenie_pozycja_franszyza_wartosc[\'+id+\']\').value = (\'\' + kwota.toFixed(2)).replace(\'.\',\',\');
										kwota = kwota_start - franszyza_kwota;								
									}
									
							} else if ($(\'franszyza_rodzaj\').value == 1 && kwota_start <  franszyza_kwota ){
									
									form_zgloszenie_wyplata_zaakceptowana.value =  \'0,00\';
									return;
							} 
						}else{
							$(\'zgloszenie_pozycja_franszyza_wartosc[\'+id+\']\').value = \'0,00\'; 
						}
						
						if ($(\'zgloszenie_pozycja_inne_odl[\'+id+\']\').checked){
								odl = 1.0 * $(\'zgloszenie_pozycja_inne_odl_wartosc[\'+id+\']\').value.replace(\',\',\'.\');
								kwota = (kwota - odl);
								if (kwota < 0 )
									kwota = 0.00;
						
						}
						
						
						
																
						form_zgloszenie_wyplata_zaakceptowana.value =   (\'\' + kwota.toFixed(2)).replace(\'.\',\',\');
						if ( kwota > $(\'dostepna_suma_ubezpieczenia\').value.replace(\',\',\'.\') ){
								alert(\'Uwaga kwota wyp³aty jest wy¿sza ni¿ dostêpna suma ubezpieczenia!\');
						}										
					}	

					function dolicz_refundacje(formant,id){
						if (formant.checked){
								$(\'zgloszenie_wyplata_refundacji[\'+id+\']\').value = \'40,00\';								
						}else{
								$(\'zgloszenie_wyplata_refundacji[\'+id+\']\').value = \'0,00\';
						}
					}
					</script>
				
					<input type="hidden" name="franszyza_rodzaj"  id="franszyza_rodzaj" value="'.$dane_ub['franszyza_rodzaj'].'">
					<input type="hidden" name="franszyza_kwota" id="franszyza_kwota" value="'.$dane_ub['franszyza_kwota'].'">
					<input type="hidden" name="claims_id" value="'.$edit_cl.'">
					<table align="center" cellpadding="1" cellspacing="0" border="1"  width=95%><tr bgcolor="#BBBBBB"><td width="120" rowspan=3 valign="top">
						<b>Edycja zg³oszenia:</b> </td><td>												
						<b>Data:</b> <input type="text" size="10" name="data_zgloszenia" id="data_zgloszenia" value="'.$cl->getAnnounce_date().'" ></td></tr>
						<tr bgcolor="#BBBBBB"><td><b>Uwagi:</b><br> <textarea cols="80" rows="3" id="zgloszenie_uwagi" name="zgloszenie_uwagi">'.$cl->getNote().'</textarea>
						';
						
						$result .= '</td></tr>
						<tr bgcolor="#BBBBBB"><td><b>Roszczenia</b>:';
						
					$result .= '<table  cellpadding="1" cellspacing="0" border=1 width="100%">
						<tr  bgcolor="#BBBBBB">						
							<td align="center"><b>Usuñ</b></td>						
							<td align="center"><b>Opis roszczenia</b></td>						
							<td align="center"><b>Kwota roszczenia.</b></td>
							<td align="center"><b>Kwota RWS</b></td>
						</tr>';
						
			  			foreach ($cl->getClaimDetails() As $pozycjaCL  ){
			  						$dis= '';
			  						if ($pozycjaCL->getStatus2()==0 || $pozycjaCL->getStatus2()==2){
			  								
			  							
			  						}else{
			  							$dis= ' disabled class="disabled" ';
			  							
			  						}				
										$result .= '<tr>										
											<td align="center" rowspan="7"><input type="checkbox" '.$dis.' name="zgloszenie_pozycja_usun['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_usun['.$pozycjaCL->getID().']" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td>
											<td><textarea '.$dis.' cols=50 rows="2" name="zgloszenie_opis_roszczenia['.$pozycjaCL->getID().']" id="zgloszenie_opis_roszczenia['.$pozycjaCL->getID().']">'.$pozycjaCL->getNote().'</textarea></td>
											<td align="center"><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getKwota_roszczenia()).'" name="zgloszenie_kwota['.$pozycjaCL->getID().']" id="zgloszenie_kwota['.$pozycjaCL->getID().']"  > PLN</td>
											<td align="center"><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getKwota_rws()).'" name="zgloszenie_kwota_rws['.$pozycjaCL->getID().']" id="zgloszenie_kwota['.$pozycjaCL->getID().']"  > PLN</td>
										</tr>
										<tr bgcolor="#888888">
											<td  align="right"><b>Kwota zaakceptowana: </b></td>
											<td align="center" ><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getKwota_zaakceptowana()).'" name="zgloszenie_kwota_zaakceptowana['.$pozycjaCL->getID().']" id="zgloszenie_kwota_zaakceptowana['.$pozycjaCL->getID().']"  onChange="przelicz('.$pozycjaCL->getID().');"> PLN </td>
											<td>&nbsp;</td>
										</tr>
										<tr bgcolor="#78c470">
											<td  align="center" colspan="3"><b>Odliczenia: </b></td>											
										</tr>
										<tr bgcolor="#78c470">
											<td  align="left" style="padding-left:150px;"><b>Zastosuj</b> <input onChange="przelicz('.$pozycjaCL->getID().');" type="checkbox" '.$dis.' name="zgloszenie_pozycja_franszyza['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_franszyza['.$pozycjaCL->getID().']" value="1" '.($pozycjaCL->getFranszyza()==1 ? 'checked' : '').'><b>Franszyza: </b> '.$franszyza_info.'</td>
											<td align="center" ><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getFranszyza_kwota()).'" name="zgloszenie_pozycja_franszyza_wartosc['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_franszyza_wartosc['.$pozycjaCL->getID().']"  onChange="przelicz('.$pozycjaCL->getID().');"> PLN </td>
											<td>&nbsp;</td>
										</tr>
										<tr bgcolor="#78c470">
											<td  align="left" style="padding-left:150px;"><b>Zastosuj</b> <input onChange="przelicz('.$pozycjaCL->getID().');" type="checkbox" '.$dis.' name="zgloszenie_pozycja_inne_odl['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_inne_odl['.$pozycjaCL->getID().']" value="1" '.($pozycjaCL->getOdliczenie()==1 ? 'checked' : '').'><b>Inne odliczenia: </b></td>
											<td align="center" ><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getOdliczenie_kwota()).'" name="zgloszenie_pozycja_inne_odl_wartosc['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_inne_odl_wartosc['.$pozycjaCL->getID().']"  onChange="przelicz('.$pozycjaCL->getID().');"> PLN </td>
											<td>&nbsp;</td>
										</tr>
										
										<tr bgcolor="#960018">
											<td  align="right"><b>Wyp³ata zaakceptowana: </b></td>
											<td align="center" ><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getWyplata_zaakceptowana()).'" name="zgloszenie_wyplata_zaakceptowana['.$pozycjaCL->getID().']" id="zgloszenie_wyplata_zaakceptowana['.$pozycjaCL->getID().']"  onChange="przelicz('.$pozycjaCL->getID().');" readonly class="disabled"> PLN </td>
											<td>&nbsp;</td>
										</tr>
										<tr bgcolor="#960018">
											<td  align="right"><input type="checkbox" '.$dis.' onClick="dolicz_refundacje(this,'.$pozycjaCL->getID().')" name="zgloszenie_pozycja_refundacja['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_refundacja['.$pozycjaCL->getID().']" value="1" '.($pozycjaCL->getRefundacja()==1 ? 'checked' : '').'><b>Dolicz refundacjê kosztów szacowania szkody: </b></td>
											<td align="center" ><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getRefundacja_kwota()).'" name="zgloszenie_wyplata_refundacji['.$pozycjaCL->getID().']" id="zgloszenie_wyplata_refundacji['.$pozycjaCL->getID().']"  > PLN </td>
											<td>&nbsp;</td>
										</tr>
										<tr bgcolor="#888888">
											<td colspan="4">
												<span align="absmiddle"><b>Uwagi wewnêtrzne:</b></span><textarea  '.$dis.' cols=80 rows="3" name="zgloszenie_uwagi_roszczenia['.$pozycjaCL->getID().']" id="zgloszenie_uwagi_roszczenia['.$pozycjaCL->getID().']">'.$pozycjaCL->getStatus_note().'</textarea>
											</td>
										</tr>
										<tr bgcolor="#888888">
											<td colspan="4">
												<b>Status:</b><select '.$dis.' name="zgloszenie_pozycja_status['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_status['.$pozycjaCL->getID().']" onChange="skasuj_wyslij('.$pozycjaCL->getID().')">
													<option value="2" '.($pozycjaCL->getStatus()==2 ? 'selected' : '').'>W trakcie obs³ugi</option>
													<option value="3" '.($pozycjaCL->getStatus()==3 ? 'selected' : '').'>Decyzja pozytywna</option>												
													<option value="4" '.($pozycjaCL->getStatus()==4 ? 'selected' : '').'>Decyzja odmowna</option>												
												</select>		
												<input type="hidden" name=" name="old_zgloszenie_pozycja_status['.$pozycjaCL->getID().']" value="'.$pozycjaCL->getStatus().'">';
										
			  									
													$result .= '<b style="color:#ff9999">'.$pozycjaCL->getStatus2Name().'&nbsp;</b>';												
												
												
												if ($pozycjaCL->getStatus2()==0 || $pozycjaCL->getStatus2()==2){
													$result .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Wy¶lij do akceptacji:</b> <input type="checkbox" name="wyslij_do_akceptacji['.$pozycjaCL->getID().']" id="wyslij_do_akceptacji['.$pozycjaCL->getID().']" value="1" onClick="return sprawdz_status('.$pozycjaCL->getID().');">';
												
												}
								$result .' </td></tr>';
								
								if ($pozycjaCL->getStatus2()==1 && AllianzCase::isAdmin() ){
									$result .= '<tr bgcolor="#ff9999">';
										$result .= '<td colspan="4">';
														$result .= '<div align="center"><b>Akceptacja:</b> <input type="radio" name="zgloszenie_akceptacja['.$pozycjaCL->getID().']" id="zgloszenie_akceptacja['.$pozycjaCL->getID().']" value="1" onChange="return check_suma_ubezpieczenia(this,'.$pozycjaCL->getWyplata_zaakceptowana().');">';																				
														$result .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Do poprawy:</b> <input type="radio" name="zgloszenie_akceptacja['.$pozycjaCL->getID().']" id="zgloszenie_akceptacja['.$pozycjaCL->getID().']" value="2" >';
														$result .= '<hr><b>Komentarz</b><br>';																				
														$result .= '<textarea  cols=80 rows="3" name="zgloszenie_akceptacja_uwagi['.$pozycjaCL->getID().']" id="zgloszenie_akceptacja_uwagi['.$pozycjaCL->getID().']"></textarea></div>';																														
										$result .= '</td>';										
									$result .= '</tr>';
									
								}
								
								if ($pozycjaCL->getStatus2()==2 || $pozycjaCL->getStatus2()==3 ){
									$result .= '<tr bgcolor="#ff9999">';
										$result .= '<td colspan="4">';
														$result .= '<div align="center">Data: '.$pozycjaCL->getStatus2_date().', '.Application::getUserName( $pozycjaCL->getStatus2_userID() );																																		
														$result .= '<hr><div style="width:500px;" align="left"><i>'.$pozycjaCL->getStatus2_note().'&nbsp;</i></div>';								
													if ( $pozycjaCL->getStatus2()==3 && AllianzCase::isAdmin()){
															$result .= '<hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:black;">Cofnij akceptacje:</b> <input type="checkbox" name="cofnij_akceptacje['.$pozycjaCL->getID().']" id="cofnij_akceptacje['.$pozycjaCL->getID().']" value="1" onClick="return confirm(\'Czy napewno? Cofniêcie akceptacji spowoduje usuniêcie z systemu decyzji oraz wyp³aty!!!\');">';
													}																																																														
										$result .= '</td>';										
									$result .= '</tr>';
								}
						  }
						$result .= '</table>';
						$result .= '
						<script>
						
						function check_suma_ubezpieczenia(obj,suma){
							if ( suma > $(\'dostepna_suma_ubezpieczenia\').value.replace(\',\',\'.\') ){
								alert(\'Blokada akceptacji, kwota wyp³aty (\'+suma+\') jest wy¿sza ni¿ dostêpna suma ubezpieczenia (\'+$(\'dostepna_suma_ubezpieczenia\').value+\')!!!\');
								obj.checked=false;
								return false;
							}else{
								return true;
							}
						}
						
						function sprawdz_status(id){
							formant = \'zgloszenie_pozycja_status[\'+id+\']\';
							if ($(formant).value==2 ){
								alert(\'Prosze zmieniæ status\');
								return false;
							}
							return true;
						}
						
						function skasuj_wyslij(id){
							$(\'wyslij_do_akceptacji[\'+id+\']\').checked=false;
						}
						</script>
						';
						//$result .= roszczenia_szczegoly($r2);
//						<div align="right"><input type="button" value="Dodaj pozycjê"  onClick="dodaj_pozycje(this);">&nbsp;&nbsp;&nbsp;</div><br>									<br>						
						$result .= '<div id="panel_pozycje"></div>
						
						<div align="right" style="padding:10px"><input type="button" value="Zapisz"  onClick="zapisz_roszczenie();">&nbsp;&nbsp;&nbsp;</div>						
						';
						
						$result .= '</td></tr>
						
						<tr bgcolor="#AAAAAA"><td colspan="2" align="right"></td></tr>
						</table>';
			
				
			}	else { 		// nowe zg³oszenie
						$result .= '<table align="center"  cellpadding="1" cellspacing="0" border="1"  width=80%><tr bgcolor="#BBBBBB"><td width="120" rowspan=3 valign="top">
						<b>Nowe zg³oszenie:</b> </td><td>												
						<b>Data zg³oszenia:</b> <input type="text" size="10" name="data_zgloszenia" id="data_zgloszenia" value="'.date("Y-m-d").'" ></td></tr>';													
						$result .= '<tr bgcolor="#BBBBBB"><td><b>Uwagi:</b> <textarea cols="80" rows="3" id="zgloszenie_uwagi" name="zgloszenie_uwagi"></textarea>';
						
						$result .= '</td></tr>
						<tr bgcolor="#DDDDDD"><td><b>Roszczenia</b>:
						<table  cellpadding="1" cellspacing="0" border=1 width="100%">
						<tr  bgcolor="#BBBBBB">						
							<td align="center"><b>Usuñ</b></td>						
							<td align="center"><b>Opis roszczenia</b></td>						
							<td align="center"><b>Kwota roszczenia.</b></td>
							<td align="center"><b>Kwota RWS</b></td>
						</tr>
						<tr>
							<td align="center"><input type="checkbox" name="zgloszenie_pozycja_usun" id="zgloszenie_pozycja_usun" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td>
							<td><textarea cols=50 rows="2" name="zgloszenie_opis_roszczenia" id="zgloszenie_opis_roszczenia"></textarea></td>
							<td align="center"><input style="text-align:right;" type="text" size="10" value="0" name="zgloszenie_kwota" id="zgloszenie_kwota"  > PLN</td>
							<td align="center"><input style="text-align:right;" type="text" size="10" value="0" name="zgloszenie_kwota_rws" id="zgloszenie_kwota_rws"  > PLN</td>
						</tr>							
						</table>
						<div id="panel_pozycje"></div>						
						<div style="float:left;padding:15px;"><input type="button" value="Dodaj pozycjê"  onClick="dodaj_pozycje(this);"></div>
						<div style="float:right;padding:15px;"><input type="button" value="Zapisz"  onClick="dodaj_roszczenie();" ></div>';
						
						$result .= '</td></tr>												
						</table><br>';									
			}

	}else{ // view
					$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="15%" align="center"><b>Data zg³oszenia</b></td>						
					<td width="45%" align="center"><b>Note</b></td>					
					<td width="20%" align="center"><b>U¿ytkownik</b></td>					
					<td width="20%" align="center"><b>Data rej. / ostatniej zm.</b></td>
			  </tr><tr>';
				
			$lista = AllianzCase::getClaims($case_id);					
						
			foreach ($lista As $pozycja  ){
				$cl = $pozycja;				 			
				$result .= '<tr bgcolor="#BBBBBB">';
					$result .= '<td align="center">'.$cl->getAnnounce_date().'</td>';													
					$result .= '<td>'.$cl->getNote().'&nbsp;</td>';													
					$result .= '<td>'.Application::getUserName($cl->getUserID()).'</td>';													
					$result .= '<td>'.$cl->getDate().'</td>';																												
				$result .= '</tr>';	  
		 		$result .= '<td colspan="4" align="right">';	
		 				$result .='<table cellpadding="1" cellspacing="0" border="1"  width=90%>
						  <tr bgcolor="#CCCCCC">';
		 				$result .= '<td width="5%" align="center">&nbsp;</td>';
						  $result .= '
						  <td width="70" align="center"><b>Roszczenie</td>
						  <td width="70" align="center"><b>RWS</td>						  							
						  <td width="90" align="center"><b>Kwota zaakcept.</b></td>		
						  <td width="90" align="center"><b>Wyp³ata zaakcept.</b></td>		
						  <td width="70" align="center"><b>Status</b></td>										
						  <td width="70" align="center"><b></b></td>										
						  <td width="70" align="center"><b>U¿ytkownik</b></td>										
							 </tr >';	
					
						  foreach ($pozycja->getClaimDetails() As $pozycjaCL  ){
						  				$result .= '<tr>';
						  					$result .= '<td >&nbsp;</td>';
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_roszczenia()).'</td>';													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_rws()).'&nbsp;</td>';													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_zaakceptowana()).'</td>';													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getWyplata_zaakceptowana()).'</td>';													
											$result .= '<td align="center">'.$pozycjaCL->getStatusName().'</td>';													
											$result .= '<td align="center">'.$pozycjaCL->getStatus2Name().'&nbsp;</td>';													
											$result .= '<td>'.Application::getUserName($pozycjaCL->getStatus_userID()).'</td>';													
																																		
										$result .= '</tr>';	  
						  }
						  $result .= '</table>';
						  
						$result .= '</td></tr>';	
			}				
						$result .= '</td></tr></table><br>'	;
		
	}
	
	$result .= '</form>';
	return $result;
	
}



function decyzje($row,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	global $lista_status,$lista;
		
						
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_decyzje" id="form_decyzje">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Decyzje</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['claims_decyzje'])){
		
			if (check_claim_handler_user() || check_claim_admin()	){
	
				$result .= '<div style="float:rigth;padding:2px">								
				<input type="hidden" name="change[ch_claims_decyzje]" id="change[ch_claims_decyzje]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
			}else{				
				$result .= '<div style="float:rigth;padding:2px">								
				<input type="hidden" name="err_change[ch_claims_decyzje]" id="err_change[ch_claims_decyzje]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">				
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
				
				$result .= '<br><br><br><br><br><div align="left"><b>Brak uprawnieñ</b></div><br><br><br><br><br>';
				$result .= '</td>	</tr></table>';
				return $result;
			}
	
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[claims_decyzje] value=1>
				<input type="hidden" name="edit_form" value="1">				
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      
//	$result .= 'TODO';
//	return $result;
	
	
if (isset($change['claims_decyzje'])){
	
	
			$result .= '
			<script>
			ayax_action=0;
			function edycja_decyzji(id){
				if (id>0){
					document.getElementById(\'edit_form_action\').value=\'decissions_edit\';	
					document.getElementById(\'edit_form_action_param\').value=id;	
					
					document.getElementById(\'change[ch_claims_decyzje]\').name=\'change[claims_decyzje]\';	
					
					document.getElementById(\'form_decyzje\').submit();		
				
				}
			}
			
			function zapisz_decyzje(){	
				if (ayax_action==1){
					alert(\'Prosze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'decissions_save\';	
					document.getElementById(\'form_decyzje\').submit();					
				}
			}
						
			
</script>
			';
			$lista = AllianzCase::getDecisions($case_id);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      								
		      		<td width="15%" align="center"><b>Decyzja</b></td>						
		      		<td width="15%" align="center"><b>Data</b></td>						
					<td width="40%" align="center"><b>Kwota</b></td>					
					<td width="40%" align="center"><b>Zwrot kosztów</b></td>					
					<td width="40%" align="center"><b>Do wyp³aty</b></td>									
					<td width="20%" align="center"><b>U¿ytkownik</b></td>					
					<td width="20%" align="center"><b>U¿ytkownik akceptujacy</b></td>										
					<td width="5%" align="center">&nbsp;</td>
			  </tr><tr>';						
						
			foreach ($lista As $pozycja  ){
				$cl = $pozycja;				 			
				$result .= '<tr bgcolor="#BBBBBB">';
					
					$result .= '<td align="center">'.$cl->getTypeName().'</td>';													
					$result .= '<td>'.$cl->getDate().'</td>';													
						$result .= '<td align="right">'.print_currency($cl->getAmount()).'</td>';													
					$result .= '<td align="right">'.print_currency($cl->getAmount2()).'</td>';													
					$result .= '<td align="right">'.print_currency($cl->getPayment_amount()).'</td>';																								
					$result .= '<td>'.Application::getUserName($cl->getID_user()).'</td>';												
					$result .= '<td>'.Application::getUserName($cl->getAccept_ID_user()).'</td>';		
					$result .= '<td align="center"><a href="javascript:edycja_decyzji('.$cl->getID().')">Edycja</a>&nbsp;</td>';																																
				$result .= '</tr>';	  
		 		
				if (getValue('edit_form_action') == 'decissions_edit' && getValue('edit_form_action_param') == $cl->getID()  ){
						$result .= '<tr><td>&nbsp;</td>
							<td colspan="7">';
						if ($cl->GetStatus() == 0){
								$txt1 =	$cl->generateTxt(1);
								$txt2 = $cl->generateTxt(2);
						}else{							
							$txt1 = $cl->getText1();
							$txt2 = $cl->getText2();
						}
						$result  .= '<hr>';
						$result .= '
						<input type="hidden" name="decisions_id" value="'.$cl->getID().'">
						<table align="center" cellpadding="1" cellspacing="0" border="1"  width=95%>
						<tr bgcolor="#BBBBBB"><td width="10" rowspan=3 valign="top">&nbsp;</td>												
							
							<td>
							<b>Decyzja:</b><br> <textarea cols="110" rows="6" id="tekst1" name="tekst1">'.$txt1.'</textarea>
							<br><br>
							<b>Uzasadnienie:</b><br> <textarea cols="110" rows="6" id="tekst2" name="tekst2">'.$txt2.'</textarea>							
							</td></tr>
							';
							
							$result .= '
							<tr bgcolor="#BBBBBB"><td>';										
							$result .= '<div align="right" style="padding:10px"><input type="button" value="Zapisz"  onClick="zapisz_decyzje();">&nbsp;&nbsp;&nbsp;</div>';
							
							$result .= '</td></tr>												
							</table>';
						$result .= '</td></tr>';				
				}
						  
					
			}					
						
				 $result .= '</table>';

									
				

	}else{ // view
					
		  $lista = AllianzCase::getDecisions($case_id);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      								
		      		<td width="12%" align="center"><b>Status</b></td>						
		      		<td width="12%" align="center"><b>Decyzja</b></td>						
		      		<td width="10%" align="center"><b>Data</b></td>						
					<td width="10%" align="center"><b>Kwota</b></td>					
					<td width="10%" align="center"><b>Zwrot kosztów</b></td>					
					<td width="10%" align="center"><b>Do wyp³aty</b></td>									
					<td width="15%" align="center"><b>U¿ytkownik</b></td>					
					<td width="15%" align="center"><b>U¿ytkownik akceptujacy</b></td>										
					<td width="5%" align="center">&nbsp;</td>
			  </tr><tr>';						
						
			foreach ($lista As $pozycja  ){
				$cl = $pozycja;				 			
				$result .= '<tr bgcolor="#BBBBBB">';
					
					$result .= '<td align="center">'.$cl->getStatusName().' '.($cl->getStatus()==1 ? '<a href="AS_cases_allianz_claims_print.php?id='.$cl->getId().'" target="_blank"><img border="0" src="img/print.gif"></a>' : '' ).'</td>';													
					$result .= '<td align="center">'.$cl->getTypeName().'</td>';													
					$result .= '<td>'.$cl->getDate().'</td>';													
					$result .= '<td align="right">'.print_currency($cl->getAmount()).'</td>';													
					$result .= '<td align="right">'.print_currency($cl->getAmount2()).'</td>';													
					$result .= '<td align="right">'.print_currency($cl->getPayment_amount()).'</td>';																									
					$result .= '<td>'.Application::getUserName($cl->getID_user()).'</td>';												
					$result .= '<td>'.Application::getUserName($cl->getAccept_ID_user()).'</td>';	
					$result .= '<td align="center">&nbsp;</td>';																												
				$result .= '</tr>';	  
		 		
						  
					
			}					
						
				 $result .= '</table>';
		
	}
	
	$result .= '</br></form>';
	return $result;
	
}

function wyplaty($row,$row_case_ann,$row_case){		  
       $result='';	
		global $global_link,$change,$case_id;
		global $lista_status,$lista,$forma_wyplaty,$lista_status_wyplata;
		
		
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_wyplaty" id="form_wyplaty">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>Wyp³aty</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['claims_wyplaty'])){
				if (check_claim_handler_user() || check_claim_admin()	){
	
					$result .= '<div style="float:rigth;padding:2px">								
					<input type=hidden name="change[ch_claims_wyplaty]" id="change[ch_claims_wyplaty]" value="1">
					<input type="hidden" name="edit_form" value="1">
					<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
					<input type="hidden" name="edit_form_action_val" id="edit_form_action_val" value="">
					
					<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
					<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
					</div>';
				}else{				
						$result .= '<div style="float:rigth;padding:2px">								
						<input type=hidden name="err_change[ch_claims_wyplaty]" id="err_change[ch_claims_wyplaty]" value="1">
						<input type="hidden" name="edit_form" value="1">
						<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
						<input type="hidden" name="edit_form_action_val" id="edit_form_action_val" value="">
						
						
						<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
						</div>';
						
						$result .= '<br><br><br><br><br><div align="left"><b>Brak uprawnieñ</b></div><br><br><br><br><br>';
						$result .= '</td>	</tr></table>';
						return $result;
			}
	}else{
		$result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[claims_wyplaty] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      
//$result .= 'TODO';
//	return $result;
				
	if (isset($change['claims_wyplaty'])){
	
			 $query = "SELECT * FROM coris_allianz_payment WHERE ID_case= '$case_id'";
			 $mysql_result = mysql_query($query);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="5%" align="center">&nbsp;</td>						
		      		<td width="12%" align="center"><b>Status</b></td>								      							
		      		<td width="10%" align="center"><b>Data</b></td>						
					<td width="10%" align="center"><b>Do wyp³aty</b></td>																							
			  </tr><tr>';						
						
			while($row = mysql_fetch_array($mysql_result)){								 		
				$result .= '<tr bgcolor="#BBBBBB">';
					$result .= '<td align="center">&nbsp;</td>';
					$result .= '<td align="center">Nie wys³ane do Allianz</td>';													
					$result .= '<td align="center">'.$row['date'].'</td>';																							
					$result .= '<td align="right">'.print_currency($row['payment_amount']).'</td>';													
																																	
				$result .= '</tr>';	  		 								  				
		
		}
		$result .= '</table><br>';
		
		

	}else{
			 $query = "SELECT * FROM coris_allianz_payment WHERE ID_case= '$case_id'";
			 $mysql_result = mysql_query($query);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="5%" align="center">&nbsp;</td>						
		      		<td width="12%" align="center"><b>Status</b></td>								      							
		      		<td width="10%" align="center"><b>Data</b></td>						
					<td width="10%" align="center"><b>Do wyp³aty</b></td>																							
			  </tr><tr>';						
						
			while($row = mysql_fetch_array($mysql_result)){								 		
				$result .= '<tr bgcolor="#BBBBBB">';
					$result .= '<td align="center">&nbsp;</td>';
					$result .= '<td align="center">Nie wys³ane do Allianz</td>';													
					$result .= '<td align="center">'.$row['date'].'</td>';																							
					$result .= '<td align="right">'.print_currency($row['payment_amount']).'</td>';													
																																	
				$result .= '</tr>';	  		 								  				
		
		}
		$result .= '</table><br>';
	}	
	$result .= '</form>';
	return $result;	
}	




function check_claim_handler_user(){
	global $row_case;
	return true;	
	if ($row_case['claim_handler_user_id']>0 && $row_case['claim_handler_user_id'] == $_SESSION['user_id'] )
		return true;
	else 
		return false;			
}

function check_claim_admin(){
	global $lista_admin_user;
	
	if (in_array($_SESSION['user_id'],$lista_admin_user) )
		return true;
	else 
		return false;			
}


?>