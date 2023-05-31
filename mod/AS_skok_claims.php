<?php


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
	
				$zgloszenie_id_ryzyko = getValue('zgloszenie_id_ryzyko') > 0 ? getValue('zgloszenie_id_ryzyko') : 0 ;
				$rate_table_id = getValue('rate_table_id') > 0 ? getValue('rate_table_id') : 0 ;
				
				$zgloszenie_kwota = str_replace(',','.',getValue('zgloszenie_kwota'));
			//	$add_amount_pln = str_replace(',','.',getValue('amount_pln'));
				
				$zgloszenie_currency_id = getValue('zgloszenie_currency_id');
				$zgloszenie_opis_roszczenia = getValue('zgloszenie_opis_roszczenia');
				$zgloszenie_id_operat = getValue('zgloszenie_id_operat');
				
				$data_zgloszenia = getValue('data_zgloszenia');
				$announcer = getValue('announcer');
				$zgloszenie_id_instytucja = getValue('zgloszenie_id_instytucja') > 0 ?  getValue('zgloszenie_id_instytucja') : 0 ;
				$zgloszenie_uwagi = getValue('zgloszenie_uwagi');
													
				$query  = "INSERT INTO coris_assistance_cases_skok_claims  SET ID_case ='$case_id',
				announcer ='$announcer',ID_institution='$zgloszenie_id_instytucja',announce_date='$data_zgloszenia',note='$zgloszenie_uwagi',ID_user='".$_SESSION['user_id']."',date=now()";		
				$mysql_result = mysql_query($query);
				$poz=0;
				if ($mysql_result){
					//$message .= "Udpate OK";
					$poz = mysql_insert_id();
				}else{
					$message .= "Insert Error: ".$query."\n<br> ".mysql_error();				
				}		
				
				
				$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);
				
				if ($zgloszenie_currency_id == 'PLN' ){
					$add_amount_pln=$zgloszenie_kwota;																						
				}else{											
					//$add_amount_pln = str_replace(',','.',addslashes(stripslashes(trim($_POST['amount_pln'][$key]))));																													 
					$add_amount_pln =	ev_round(getKursyX('',1,$zgloszenie_currency_id,1,$rate_table_id)*$zgloszenie_kwota,2);											
				}
										
				if ($poz>0){// insert pozycje
					if (getValue('zgloszenie_pozycja_usun') != 1){
						$query  = "INSERT INTO coris_assistance_cases_skok_claims_details   SET  ID_claims  ='$poz',amount ='$zgloszenie_kwota',amount_accept_pln = '".$add_amount_pln."',amount_accept ='$zgloszenie_kwota',reserve  ='".($add_amount_pln*1.05)."',currency_id ='$zgloszenie_currency_id',currency_table_id='$rate_table_id',ID_risk='$zgloszenie_id_ryzyko',ID_operat='$zgloszenie_id_operat',status=0,note='$zgloszenie_uwagi', ID_user='".$_SESSION['user_id']."'";		
						$mysql_result = mysql_query($query);	
						if ($mysql_result){
							
						//	$poz = mysql_insert_id();
						}else{
							$message .= "Insert  Error: ".$query."\n<br> ".mysql_error();				
						}
					}
					
					$tmp = isset( $_POST['add_zgloszenie_opis_roszczenia'] ) ?  $_POST['add_zgloszenie_opis_roszczenia']  : null ;
						
						if (is_array($tmp)){
								foreach ( $tmp As   $key => $val ){									
										$zgloszenie_opis_roszczenia = addslashes(stripslashes(trim($val)));

										$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota'][$key]))));																								
										//$add_amount_pln = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_amount_pln'][$key]))));																								
										
										$zgloszenie_currency_id= addslashes(stripslashes(trim($_POST['add_zgloszenie_currency_id'][$key])));
										$zgloszenie_id_ryzyko	= addslashes(stripslashes(trim($_POST['add_zgloszenie_id_ryzyko'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['add_zgloszenie_id_ryzyko'][$key]))) : 0 ;
										$zgloszenie_id_operat	= addslashes(stripslashes(trim($_POST['add_zgloszenie_id_operat'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['add_zgloszenie_id_operat'][$key]))) : 0 ;
										$rate_table_id	= addslashes(stripslashes(trim($_POST['add_rate_table_id'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['add_rate_table_id'][$key]))) : 0 ;
										
										$add_zgloszenie_pozycja_usun = addslashes(stripslashes(trim($_POST['add_zgloszenie_pozycja_usun'][$key])));		
										
										$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);
										
										if ($zgloszenie_currency_id == 'PLN' ){
											$add_amount_pln=$zgloszenie_kwota;																						
										}else{											
											//$add_amount_pln = str_replace(',','.',addslashes(stripslashes(trim($_POST['amount_pln'][$key]))));																													 
											$add_amount_pln =	ev_round(getKursyX('',1,$zgloszenie_currency_id,1,$rate_table_id)*$zgloszenie_kwota,2);											
										}
										
										if ($add_zgloszenie_pozycja_usun != 1 && $add_amount_pln>0){
											$qu  = "INSERT INTO coris_assistance_cases_skok_claims_details   SET  ID_claims  ='$poz',amount ='$zgloszenie_kwota',amount_accept ='$zgloszenie_kwota',
											amount_accept_pln = '".$add_amount_pln."',
											reserve  ='".($add_amount_pln*1.05)."',currency_id ='$zgloszenie_currency_id',currency_table_id='$rate_table_id',ID_risk='$zgloszenie_id_ryzyko',ID_operat='$zgloszenie_id_operat',status=0,note='$zgloszenie_opis_roszczenia', ID_user='".$_SESSION['user_id']."'";
											$mysql_result = mysql_query($qu);
											
											if ($mysql_result){
												//$message .= "Udpate OK ".$query;							
											}else{
												$message .= "UPDATE Error: ".$qu."\n<br> ".mysql_error();				
											}									
										}
								}
						}		
									
				}
				
			}else if ($edit_form_action == 'claims_save'){
					
						$claims_id = getValue('claims_id');
						$data_zgloszenia = getValue('data_zgloszenia');
						$announcer = getValue('announcer');
						$zgloszenie_id_instytucja = getValue('zgloszenie_id_instytucja') > 0 ?  getValue('zgloszenie_id_instytucja') : 0 ;
						$zgloszenie_uwagi = getValue('zgloszenie_uwagi');																	
							
						$query  = "UPDATE coris_assistance_cases_skok_skok_claims  SET announcer ='$announcer',ID_institution='$zgloszenie_id_instytucja',announce_date='$data_zgloszenia',note='$zgloszenie_uwagi' WHERE ID='$claims_id'";		
						$mysql_result = mysql_query($query);
						$poz=0;
						if ($mysql_result){
							//$message .= "Udpate OK ".$query;							
						}else{
							$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
						}		
						
						$tmp = isset( $_POST['zgloszenie_opis_roszczenia'] ) ?  $_POST['zgloszenie_opis_roszczenia']  : null ;
						
						if (is_array($tmp)){
								foreach ( $tmp As   $key => $val ){
									$zgloszenie_pozycja_usun = @addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_usun'][$key])));		
									if ($zgloszenie_pozycja_usun){
										$qt = "SELECT ID FROm coris_assistance_cases_skok_claims_pay_position WHERE ID_claims_details = '".$key."'";
										$mt = mysql_query($qt);
										if (mysql_num_rows($mt) == 0 ){																					
											$del = "DELETE FROM coris_assistance_cases_skok_claims_details   WHERE ID_claims='$claims_id' AND ID='$key' AND status=0 LIMIT 1";											
											$md = mysql_query($del);
										}									
									}else{
									
										
										$zgloszenie_opis_roszczenia = addslashes(stripslashes(trim($val)));
										$statu_opis_roszczenia = addslashes(stripslashes(trim($_POST['statu_opis_roszczenia'][$key])));								
																
										
										$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_kwota'][$key]))));								
										$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);
										
										$zgloszenie_currency_id= addslashes(stripslashes(trim($_POST['zgloszenie_currency_id'][$key])));
										$zgloszenie_id_ryzyko	= addslashes(stripslashes(trim($_POST['zgloszenie_id_ryzyko'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['zgloszenie_id_ryzyko'][$key]))) : 0 ;							
										$zgloszenie_id_operat	= addslashes(stripslashes(trim($_POST['zgloszenie_id_operat'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['zgloszenie_id_operat'][$key]))) : 0 ;				
										
										$roszczenie_status_old = addslashes(stripslashes(trim($_POST['roszczenie_status_old'][$key])));
										$roszczenie_status = addslashes(stripslashes(trim($_POST['roszczenie_status'][$key])));
										
										$zgloszenie_kwota_akcept = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_kwota_akcept'][$key]))));	
														
										$rate_table_id	= addslashes(stripslashes(trim($_POST['rate_table_id'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['rate_table_id'][$key]))) : 0 ;
										
										$zgloszenie_kwota_akcept = ev_round($zgloszenie_kwota_akcept,2);
										if ($zgloszenie_currency_id == 'PLN' ){
											$add_amount_pln=$zgloszenie_kwota_akcept;																						
										}else{											
											//$add_amount_pln = str_replace(',','.',addslashes(stripslashes(trim($_POST['amount_pln'][$key]))));																													 
											$add_amount_pln =	ev_round(getKursyX('',1,$zgloszenie_currency_id,1,$rate_table_id)*$zgloszenie_kwota_akcept,2);																		
										}
											$zgloszenie_reserve	=1.05 * $add_amount_pln;		
										//$zgloszenie_reserve = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_reserve'][$key]))));
																		
										$var='';
										
										if ($roszczenie_status_old == 0 && ($roszczenie_status==1 || $roszczenie_status==2)){
											$var = " ,status='$roszczenie_status',status_ID_user='".$_SESSION['user_id']."' ";									
										}
										
										$qu = "UPDATE coris_assistance_cases_skok_claims_details   SET   amount ='$zgloszenie_kwota',currency_id ='$zgloszenie_currency_id',
										currency_table_id='$rate_table_id',ID_risk='$zgloszenie_id_ryzyko',ID_operat='$zgloszenie_id_operat',
										amount_accept='$zgloszenie_kwota_akcept',amount_accept_pln = '".$add_amount_pln."',										
										note='$zgloszenie_uwagi',note_status='$statu_opis_roszczenia',reserve='$zgloszenie_reserve' $var WHERE ID='$key' LIMIT 1";
										$mysql_result = mysql_query($qu);
										
										if ($mysql_result){
											//$message .= "Udpate OK11 ".$qu;							
										}else{
											$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
										}	
									}								
								}
						}						
						$tmp = isset( $_POST['add_zgloszenie_opis_roszczenia'] ) ?  $_POST['add_zgloszenie_opis_roszczenia']  : null ;
						if (is_array($tmp)){
								foreach ( $tmp As   $key => $val ){
										$zgloszenie_opis_roszczenia = addslashes(stripslashes(trim($val)));
										$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota'][$key]))));		
									//	$add_amount_pln = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_amount_pln'][$key]))));	
										
																
										$zgloszenie_currency_id= addslashes(stripslashes(trim($_POST['add_zgloszenie_currency_id'][$key])));
										$zgloszenie_id_ryzyko	= addslashes(stripslashes(trim($_POST['add_zgloszenie_id_ryzyko'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['add_zgloszenie_id_ryzyko'][$key]))) : 0 ;
										
										$add_zgloszenie_pozycja_usun = addslashes(stripslashes(trim($_POST['add_zgloszenie_pozycja_usun'][$key])));					
										$zgloszenie_id_operat	= addslashes(stripslashes(trim($_POST['add_zgloszenie_id_operat'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['add_zgloszenie_id_operat'][$key]))) : 0 ;				
										$rate_table_id	= addslashes(stripslashes(trim($_POST['add_rate_table_id'][$key]))) > 0 ?  addslashes(stripslashes(trim($_POST['add_rate_table_id'][$key]))) : 0 ;
										
										$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);
										
										$zgloszenie_kwota_akcept = $zgloszenie_kwota;
										
										
										
										if ($zgloszenie_currency_id == 'PLN' ){
											$add_amount_pln=$zgloszenie_kwota_akcept;																						
										}else{											
											//$add_amount_pln = str_replace(',','.',addslashes(stripslashes(trim($_POST['amount_pln'][$key]))));																													 
											$add_amount_pln =	ev_round(getKursyX('',1,$zgloszenie_currency_id,1,$rate_table_id)*$zgloszenie_kwota_akcept,2);									
										}
										//$zgloszenie_reserve	=1.05 * $add_amount_pln;		
										
										
										$zgloszenie_reserve	=1.05 * $add_amount_pln;
										if ($add_zgloszenie_pozycja_usun != 1  && $add_amount_pln>0){													
											$qu  = "INSERT INTO coris_assistance_cases_skok_claims_details   SET  ID_claims  ='$claims_id',amount ='$zgloszenie_kwota',amount_accept_pln = '".$add_amount_pln."',	currency_id ='$zgloszenie_currency_id',currency_table_id='$rate_table_id',ID_risk='$zgloszenie_id_ryzyko',ID_operat='$zgloszenie_id_operat',amount_accept='$zgloszenie_kwota_akcept',reserve='$zgloszenie_reserve',status=0,note='$zgloszenie_opis_roszczenia', ID_user='".$_SESSION['user_id']."'";
											$mysql_result = mysql_query($qu);
											
											if ($mysql_result){
												//$message .= "Udpate OK ".$qu;							
											}else{
												$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
											}									
										}
								}
						}	

						
					$zgloszenie_usun = getValue('zgloszenie_usun');								
					if ($zgloszenie_usun==1){
						$qt = "SELECT ID FROM coris_assistance_cases_skok_claims_details   WHERE ID_claims='$claims_id' ";
						$mt = mysql_query($qt);
						if (mysql_num_rows($mt) == 0 ){																					
												$del = "DELETE FROM coris_assistance_cases_skok_claims  WHERE ID='$claims_id' LIMIT 1";																																			
												mysql_query($del);
						}
					}				
											
											
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

							$q1 = "SELECT * FROM coris_assistance_cases_skok_claims  WHERE ID_case='$case_id' AND  ID = '$roszczenie'";						
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
									$qi = "SELECT * FROM coris_skok_institution2 WHERE kod='".$r['ID_institution']."'";
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


						$qi = "INSERT INTO coris_assistance_cases_skok_claims_pay  SET ID_case='$case_id', ID_claims='$roszczenie', ID_user='".$_SESSION['user_id']."',date=now(), status0_user_id='".$_SESSION['user_id']."',status0_date =now(),  amount=0.00 ,pay_type='$pay_type'  ";
						$qi .= " ,bank_name='$bank_name',account_number='$account_number',note='$note',announcer='$announcer',ID_institution='".$r['ID_institution']."',sex='$sex',name='".addslashes(stripslashes($name))."',surname='".addslashes(stripslashes($surname))."',adress='".addslashes(stripslashes($adress))."',post='$post',city='".addslashes(stripslashes($city))."' ";
						
						
						$mysql_result = mysql_query($qi);
						if ($mysql_result){
											//$message .= "Udpate OK ".$query;							
										}else{
											$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
						}
						if ($mysql_result){
								$cp_id = mysql_insert_id();
								 
								 $query = "SELECT coris_assistance_cases_skok_claims_details.*,
							(SELECT nazwa FROM coris_skok_ryzyka_czastkowe WHERE coris_skok_ryzyka_czastkowe.ID=coris_assistance_cases_skok_claims_details.ID_risk  ) As ryzyko, 
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_skok_claims_details.ID_user ) As user,
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_skok_claims_details.status_ID_user ) As status_user
							FROM coris_assistance_cases_skok_claims_details WHERE ID_claims='".$r['ID']."' AND ID IN (".implode(',',$roszczenie_pozycja).") ORDER BY ID";
							
							$mrd = mysql_query($query);
							$suma = 0.0;
							while ($row_rd=mysql_fetch_array($mrd)){		
										$pozycja=$row_rd['ID'];
										
										if ($row_rd['currency_id'] == 'PLN')
											$amount_pln = $row_rd['amount_accept'];
										else
											$amount_pln = (ev_round(getKursyX('',1,$row_rd['currency_id'],1,$row_rd['currency_table_id'])*$row_rd['amount_accept'],2));
										
										$claim_note = addslashes(stripslashes($_POST['claim_note'][$pozycja]));
										$qi = "INSERT INTO coris_assistance_cases_skok_claims_pay_position SET ID_claims_pay = '$cp_id', note='$claim_note' ";
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
										$mrx = mysql_query("UPDATE coris_assistance_cases_skok_claims_details  SET status=1 WHERE ID ='".$row_rd['ID']."'");																					
							}
							
							$qu = " UPDATE  coris_assistance_cases_skok_claims_pay SET amount = '$suma' WHERE ID='$cp_id' LIMIT 1"	;
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
						$query = "UPDATE coris_assistance_cases_skok_claims_pay  SET status_send='$status_send' WHERE ID=$pay_id "	;						
						$mysql_result = mysql_query($query);
					}
					
					if ($old_status==1 && $status==2){ // powtorna wysylka sprawy
						$query = "UPDATE coris_assistance_cases_skok_claims_pay  SET status_send='0' WHERE ID=$pay_id "	;						
						$mysql_result = mysql_query($query);
					}
					
					if ($status>0 && $status != $old_status && $pay_id>0){
							$val  = ", status".$status."_user_id='".$_SESSION['user_id']."', status".$status."_date=now()  ";
							if ($status==1){
									$qq1= "SELECT announcer, ID_institution FROM coris_assistance_cases_skok_claims_pay WHERE ID='$pay_id' AND number=0";
									$mm = mysql_query($qq1);
									
									if ( mysql_num_rows($mm)>0 ){
											$rr = mysql_fetch_array($mm);
									
											$qq = "SELECT MAX(number)+1  FROM coris_assistance_cases_skok_claims_pay WHERE ID_case='$case_id' AND announcer='".$rr['announcer']."' AND ID_institution='".$rr['ID_institution']."' ";
											$mm = mysql_query($qq);
											$rr = mysql_fetch_array($mm);
																
											$val .= " , number='".$rr[0]."' ";			
									}
								
							}
					
							$query = "UPDATE coris_assistance_cases_skok_claims_pay  SET status='$status' $val WHERE ID=$pay_id "	;
						
						
							$mysql_result = mysql_query($query);
								if ($mysql_result){
												//$message .= "Udpate OK ".$query;			
												if ($status==1 || $status==3) { // zatwierdzone, poprawione
													$query = "UPDATE coris_assistance_cases_skok_claims_details  SET status=1 WHERE ID IN (SELECT ID_claims_details FROM  coris_assistance_cases_skok_claims_pay_position WHERE ID_claims_pay ='$pay_id')";							
													$mr=mysql_query($query);
												}
					    						if ($status==2 ) { // zatwierdzone, poprawione
													$query = "UPDATE coris_assistance_cases_skok_claims_details  SET status=0 WHERE ID IN (SELECT ID_claims_details FROM  coris_assistance_cases_claims_pay_position WHERE ID_claims_pay ='$pay_id')";							
													$mr=mysql_query($query);
												}				
								}else{
										$message .= "UPDATE Error: ".$query."\n<br> ".mysql_error();				
								}
																	
					}
					
					$status_zlecenie = getValue('status_zlecenie') == 1 ? 1 : 0 ;
					if ($status_zlecenie == 1 ){
							$q = "INSERT INTO coris_assistance_cases_skok_claims_lista_platnosci 
							SET ID_claims_pay = '$pay_id',
							ID_platnosc = 0,
							date= now(),
							 user_id  = '".$_SESSION['user_id']."',
							 status=0;
							";
						$rs = mysql_query($q);
						if ($rs){
							$qu = "UPDATE coris_assistance_cases_skok_claims_pay SET status_zlecenie=1,status_zlecenie_date=now(),status_zlecenie_user_id='".$_SESSION['user_id']."' WHERE ID=$pay_id LIMIT 1 ";
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
					
				
					$query = "UPDATE coris_assistance_cases_skok_claims_details  SET status=0 WHERE ID IN (SELECT ID_skok_claims_details FROM  coris_assistance_cases_skok_claims_pay_position WHERE ID_claims_pay ='$pay_id')";
					$mr=mysql_query($query);
					
					$query2 = "DELETE  FROM  coris_assistance_cases_skok_claims_pay_position WHERE ID_claims_pay ='$pay_id' ";				
					$mr=mysql_query($query2);
					
					$query3 = "DELETE  FROM  coris_assistance_cases_skok_claims_pay WHERE ID='$pay_id' ";				
					$mr=mysql_query($query3);
					
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
							
							$qq1= "SELECT * FROM coris_assistance_cases_skok_claims_pay WHERE ID='$pay_id' ";
							$mm = mysql_query($qq1);
							$row_pay = mysql_fetch_array($mm);
								
								
							$row_case = row_case_info($case_id);

							$q1 = "SELECT * FROM coris_assistance_cases_skok_claims  WHERE ID_case='$case_id' AND  ID = '".$row_pay['ID_claims']."'";						
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
									$qi = "SELECT * FROM coris_skok_institution2 WHERE kod='".$r['ID_institution']."'";
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
							
							$qq = "SELECT ID,ID_claims_details  FROM coris_assistance_cases_skok_claims_pay_position WHERE ID_claims_pay='$pay_id'";
							$mm = mysql_query($qq);
							while ($rr = mysql_fetch_array($mm)){
									$qq1 = "SELECT coris_assistance_cases_skok_claims_details.*,
									(SELECT nazwa FROM coris_skok_ryzyka_czastkowe WHERE coris_skok_ryzyka_czastkowe.ID=coris_assistance_cases_skok_claims_details.ID_risk  ) As ryzyko 																
									FROM coris_assistance_cases_skok_claims_details WHERE  ID ='".$rr['ID_claims_details']."'";
									$mr1 = mysql_query($qq1);
									$row_rd=mysql_fetch_array($mr1);
									
									if ($row_rd['currency_id'] == 'PLN')
											$amount_pln = $row_rd['amount_accept'];
										else
											$amount_pln = (ev_round(getKursyX('',1,$row_rd['currency_id'],1,$row_rd['currency_table_id'])*$row_rd['amount_accept'],2));
									$qi = "UPDATE  coris_assistance_cases_skok_claims_pay_position SET ";
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
					
								 								 													
							
							$query = "UPDATE coris_assistance_cases_skok_claims_pay SET announcer='$announcer', ID_institution='".$r['ID_institution']."',
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

		
		$query2 = "SELECT * FROM coris_assistance_cases_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		
if ( $row_case_settings['client_id'] == 10){
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  roszczenia($row_case_settings,$row_case_ann);	
	$result .=  '</div>';	
	$result .=  '<div style="clear:both;"></div>';
	$result .=  '<div style="width: 840px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  wyplaty($row_case_settings,$row_case_ann,$row_case);	
	$result .=  '</div>';			
			$result .=  '<div style="clear:both;"></div>';		
}else{
	$result = '<div style="width: 840px; height: 300px;border: #6699cc 1px solid;background-color: #DFDFDF;margin: 5px;">	
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw Skok</b></div>
	</div>
	';
	
}
			$result .=  '<div style="clear:both;"></div>';
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
			lista_id= new Array(0);
			ayax_action=0;
			
		function zmien_daty_kursow(){
			obj = document.getElementsByName(\'zgloszenie_currency_id\');		
			if (obj[0])
				przelicz(obj[0],0);
			
			lista = document.getElementsByName(\'add_zgloszenie_currency_id[]\');	
			for (i=0;i<lista.length;i++){
				obj = lista[i];
				if (obj)
					przelicz(obj,0);
			}
			
			if ( lista_id ){
				for (i=0;i<lista_id.length;i++){
				
					obj = document.getElementsByName(\'zgloszenie_currency_id[\'+lista_id[i]+\']\');					
					if (obj[0])
						przelicz(obj[0],lista_id[i]);
				}
				}
		}	
					
			
		function przelicz(obj, index2){
			//alert (obj.name);
			lista = document.getElementsByName(obj.name);
			index = 0;
		if (index2 > 0 ){
			index=index2;
			tryb=3;
		}else{	
			for (i=0;i<lista.length;i++){
				if (lista[i] === obj)					{
					index=i;
				}
			}						
			
			if (obj.name.substring(0,3) == \'add\'){		
				tryb=1
			}else{
				tryb=0;
			}
		}
		//alert(index)
			
			if (obj.name == \'add_zgloszenie_currency_id[]\' || obj.name == \'zgloszenie_currency_id\' || (tryb==3 && obj.name.substring(0,22)==\'zgloszenie_currency_id\') ){			
					getKurs(obj.value,index,tryb);					
			}else{
			 	aktualizacjaKursu_krok2(tryb,index)
			}
			
		}	
			
		
		function aktualizacjaKursu(tryb,index,table_id,table_date,table_no,rate){
			if (tryb==1){			
				inp_name_rate = \'add_rate[]\';	
				inp_name_rate_opis = \'add_rate_opis[]\';			
				inp_name_rate_table_id = \'add_rate_table_id[]\';								
			}else{
				inp_name_rate = \'rate\';
				inp_name_rate_opis = \'rate_opis\';
				inp_name_rate_table_id = \'rate_table_id\';
			}
		  if (tryb==3)	{
		  
		  		oo_wal = document.getElementById(inp_name_rate+\'[\'+index+\']\');			
				oo_wal.value= String(rate).replace(\'.\',\',\');
			
				oo_wal = document.getElementById(inp_name_rate_opis+\'[\'+index+\']\');						
				oo_wal.innerHTML= \'<small>\'+table_date+\'</small>\';
			
				oo_wal = document.getElementById(inp_name_rate_table_id+\'[\'+index+\']\');			
				oo_wal.value=table_id;
		  
		  }else{
			oo_wal = document.getElementsByName(inp_name_rate);			
			oo_wal[index].value=String(rate).replace(\'.\',\',\');;
			
			oo_wal = document.getElementsByName(inp_name_rate_opis);						
			oo_wal[index].innerHTML= \'<small>\'+table_date+\'</small>\';
			
			oo_wal = document.getElementsByName(inp_name_rate_table_id);			
			oo_wal[index].value=table_id;
		  }
			
			 aktualizacjaKursu_krok2(tryb,index);
		}

		function aktualizacjaKursu_krok2(tryb,index){
		
			if (tryb==1 ){
				inp_name_kwota = \'add_zgloszenie_kwota[]\';
				inp_name_kwota2 = \'add_zgloszenie_kwota_akcept[]\';
				inp_name_kwota_pln = \'add_amount_pln[]\';
				inp_name_rate = \'add_rate[]\';
				inp_name_waluta = \'add_zgloszenie_currency_id[]\';			
				
			}else{
				inp_name_kwota = \'zgloszenie_kwota\';
				inp_name_kwota2 = \'zgloszenie_kwota_akcept\';
				inp_name_kwota_pln = \'amount_pln\';
				inp_name_rate = \'rate\';
				inp_name_waluta = \'zgloszenie_currency_id\';				
			}
		if (tryb==3){
			//alert(inp_name_kwota2+\'[\'+index+\']\');
			oo_wal = document.getElementById(inp_name_kwota2+\'[\'+index+\']\');	
			if (oo_wal){
			
			}else{		
				oo_wal = document.getElementById(inp_name_kwota+\'[\'+index+\']\');			
			}
			src_amount = oo_wal.value.replace(\',\',\'.\');
						
			src_amount = (src_amount*1.0).toFixed(2);	
			src_amount_txt = String(src_amount)	;			
			oo_wal.value = src_amount_txt.replace(\'.\',\',\'); // aktualizacja wartosc zrodlowej po zakragleniu
		
			
			
			//alert(src_amount);
			oo_wal = document.getElementById(inp_name_rate+\'[\'+index+\']\');			
			kurs = oo_wal.value.replace(\',\',\'.\');
			
			wynik=src_amount*kurs;
			wynik=wynik.toFixed(2);
			
			//alert(wynik);
			
			wynik_str = String(wynik);
			oo_wal = document.getElementById(inp_name_kwota_pln+\'[\'+index+\']\');			
			oo_wal.value = wynik_str.replace(\'.\',\',\');			
		
		}else{
			//alert(inp_name_kwota);
			//alert(index);
			oo_wal = document.getElementsByName(inp_name_kwota);			
			src_amount = oo_wal[index].value.replace(\',\',\'.\');
			
			src_amount = (src_amount*1.0).toFixed(2);
			src_amount_txt = String(src_amount)	;						
			oo_wal[index].value = src_amount_txt.replace(\'.\',\',\');// aktualizacja wartosc zrodlowej po zakragleniu			
				
			
			oo_wal = document.getElementsByName(inp_name_rate);			
			kurs = oo_wal[index].value.replace(\',\',\'.\');
			
			wynik=src_amount*kurs;
			wynik=wynik.toFixed(2);
			
			wynik_str = String(wynik);
			oo_wal = document.getElementsByName(inp_name_kwota_pln);			
			oo_wal[index].value = wynik_str.replace(\'.\',\',\');			
		}
		}

		
		
function getKurs(currency,index,tryb){
		ayax_action=1;
		table_id = 0;			
		table_date= document.getElementById(\'data_zgloszenia\').value;
		
		var url = \'ayax/kurs_nbp.php\';
		var jsonRequest = new Request.JSON({url: url, 
		onComplete: function(jsonObj) {
		var item_get = jsonObj.item;
		
		var table_id = item_get.table_id;
		var table_date = item_get.table_date;
		var table_no = item_get.table_no;
		var rate = item_get.rate;
		var status = item_get.status;
		if (status)
			aktualizacjaKursu(tryb,index,table_id,table_date,table_no,rate);
		else
			alert(\'B³±d kursu waluty\')	; ayax_action=0;
		}}).get({\'table_id\': table_id, \'table_date\': table_date, \'currency\': currency});
}
</script>
';
			$result .= '
			<script>
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
					alert(\'Przeliczanie walut w trakcie, porsze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'claims_save\';	
					document.getElementById(\'form_roszczenia\').submit();					
				}
			}
						
			function dodaj_roszczenie(){						


					if ( !(document.getElementById(\'zgloszenie_id_ryzyko\').value >0 ) ){																	
							alert(\'Proszê podaæ ryzyko.\');
							document.getElementById(\'zgloszenie_id_ryzyko\').focus();
							return false;
					}
					
					if ( !(document.getElementById(\'zgloszenie_id_operat\').value >0 ) ){																	
							alert(\'Proszê podaæ operat.\');
							document.getElementById(\'zgloszenie_id_operat\').focus();
							return false;
					}
					
					if ( !(document.getElementById(\'zgloszenie_kwota\').value.replace(\',\',\'.\') >0 ) ){																	
							alert(\'Proszê podaæ kwotê roszczenia.\');
							document.getElementById(\'zgloszenie_kwota\').focus();
							return false;
					}
					
					document.getElementById(\'edit_form_action\').value=\'claims_add\';	
					document.getElementById(\'form_roszczenia\').submit();						
			}
			
			
			function dodaj_pozycje(obj){
				//	alert(document.getElementById(\'panel_pozycje\').innerHTML);
					pozycja = \'<table width="100%" border="1" cellspacing="0" cellpadding="3"><tr><td><input type="checkbox" name="add_zgloszenie_pozycja_usun[]" id="add_zgloszenie_pozycja_usun[]" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td><td width="270"><table width="100%"><tr><td><b>Opis:</b> </td><td><textarea cols="35" rows="2" name="add_zgloszenie_opis_roszczenia[]" id="add_zgloszenie_opis_roszczenia[]"></textarea></td></tr><tr><td><b>Ryzyko:</b> </td><td>'.wysw_ryzyko_czastkowe2('add_zgloszenie_id_ryzyko[]',0,0,$case_id,$row_case_ann['ryzyko_gl'],'onChange="getOperat(this.value,this);"').'</td></tr><tr><td><b>Operat:</b> </td><td>'.wysw_ryzyko_operat2('add_zgloszenie_id_operat[]',0,0,$case_id).'</td></tr></table></td><td align="right" width="82" valign="top"><input style="text-align:right;" type="text" size="10" value="0" name="add_zgloszenie_kwota[]" id="add_zgloszenie_kwota[]" onChange="przelicz(this,0);"></td><td align="center" width="70"  valign="top">'.str_replace("\n","",wysw_currency_all('add_zgloszenie_currency_id[]','PLN',0,'onChange=przelicz(this,0);')).'</td><td align="center" width="85"  valign="top"><input style="text-align:right;" type="text" size="6" value="1,0" name="add_rate[]" id="add_rate[]" readonly><br><div id="add_rate_opis[]" name="add_rate_opis[]"></div></td><td align="right"  valign="top"><input style="text-align:right;" type="text" size="10" value="" name="add_amount_pln[]" id="add_amount_pln[]" readonly></td></tr></table><input type="hidden" name="add_rate_table_id[]" id="add_rate_table_id[]" value="">\';
						
					document.getElementById(\'panel_pozycje\').innerHTML +=  pozycja;		
					
			}			
			

function getOperat(val,obj){

if (val > 0 ){
		ayax_action=1;
	
		var url = \'ayax/operat_vs_ryzyko_czastkowe.php\';
		var jsonRequest = new Request.JSON({url: url, encoding: \'UTF-8\' ,
		onComplete: function(jsonObj) {						
			aktualizacjaOperatu(obj,jsonObj);
		 ayax_action=0;
		}}).get({\'rid\': val});
}

}

function aktualizacjaOperatu(obj,items){	
    var len = items.length;
    
    
    
    if (obj){    
    		if (obj.name == \'add_zgloszenie_id_ryzyko[]\'){		    		    		    
    			lista = document.getElementsByName(\'add_zgloszenie_id_ryzyko[]\');
    			for (i=0;i<lista.length;i++){
    			
					if(lista[i] === obj){					
						oo = document.getElementsByName(\'add_zgloszenie_id_operat[]\');			
						obj = oo[i];
						//continue ;
					}
					
				}
				
			}						
			
			
			
    		obj.options.length=0;
		    for (var i = 0; i < len; i++){		    		
		    		obj.options[obj.options.length] = new Option(items[i].nazwa, items[i].ID, false, false);		    		
		    }
    }
}
</script>
			';
			
			//<textarea cols=35 rows="2" name="add_zgloszenie_opis_roszczenia[]" id="add_zgloszenie_opis_roszczenia[]"></textarea><br>'.str_replace("\n","",wysw_ryzyko_czastkowe('add_zgloszenie_id_ryzyko[]',0,0,$case_id)).'</td><td align="right" width="82" valign="top"><input style="text-align:right;" type="text" size="10" value="0" name="add_zgloszenie_kwota[]" id="add_zgloszenie_kwota[]" onChange="przelicz(this,0);"></td><td align="center" width="70"  valign="top">'.str_replace("\n","",wysw_currency_all('add_zgloszenie_currency_id[]','PLN',0,'onChange=przelicz(this,0);')).'</td><td align="center" width="85"  valign="top"><input style="text-align:right;" type="text" size="6" value="1.0" name="add_rate[]" id="add_rate[]" readonly><br><div id="add_rate_opis[]" name="add_rate_opis[]"></div></td><td align="right"  valign="top"><input style="text-align:right;" type="text" size="10" value="" name="add_amount_pln[]" id="add_amount_pln[]" readonly></td></tr></table><input type="hidden" name="add_rate_table_id[]" id="add_rate_table_id[]" value="">\';
				$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#AAAAAA">
		      		<td width="8%" align="center">&nbsp;</td>	
		      		<td width="12%" align="center"><b>Data zg³.</b></td>	
					<td width="80%" align="center"><b>Zg³aszaj±cy</b></td>
			  </tr><tr>';
				
			$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' ";
			$mr = mysql_query($q1);
			while  ($r = mysql_fetch_array($mr)){				 
				$result .= '<tr>
						<td>&nbsp;<a href="javascript:edycja_zgloszenia('.$r['ID'].')">Edycja</a>&nbsp;</td>											
						<td>'.$r['announce_date'].'</td><td>';																			
							
						$result .= '<b>'.$lista[$r['announcer']].'</b>';
							if ($r['announcer']==2)
							$result .= 	' :  '.wysw_instytucja2('zgloszenie_id_instytucja',$r['ID_institution'],1);
					$result .= '</td></tr>';	  
		 		$result .= '<td colspan="3" align="right">';	

//		 		
				$result .= lista_roszczen_szczegoly($r);
				$result .= '</td></tr>'	;
			}				
						$result .= '</td></tr></table>'	;
		
					
						
						$result  .= '<hr>';
						
			if (getValue('edit_form_action') == 'claims_edit' && getValue('edit_form_action_param') > 0  ){
						
					$q2 = "SELECT * FROM coris_assistance_cases_claims WHERE ID='".getValue('edit_form_action_param')."'";
					$mr2 = mysql_query($q2);
					$r2 = mysql_fetch_array($mr2);
					
					$result .= '
					<input type="hidden" name="claims_id" value="'.getValue('edit_form_action_param').'">
					<table align="center" cellpadding="1" cellspacing="0" border="1"  width=95%><tr bgcolor="#BBBBBB"><td width="120" rowspan=3 valign="top">
						<b>Edycja zg³oszenia:</b> </td><td>												
						<b>Data:</b> <input type="text" size="10" name="data_zgloszenia" id="data_zgloszenia" value="'.$r2['announce_date'].'" onChange="zmien_daty_kursow(this.value);">';							
						$result .= '&nbsp;&nbsp;&nbsp;&nbsp;<b>Zg³aszaj±cy:</b> <select name="announcer" id="announcer" onChange="zmien_zglaszajacy(this.value)">
						
						<option value="1"  '.($r2['announcer']==1 ? 'selected'  : '').'>Upowa¿niony</option>
						<option value="2" '.($r2['announcer']==2 ? 'selected'  : '').'>Instytucja</option>
						</select>
						';
						
						$result .= '						
						 <div id="claims_form_instytucja" ><b>Instytucja:</b> '.wysw_instytucja2('zgloszenie_id_instytucja',$r2['ID_institution']).' </div>						
						</td></tr>
						<tr bgcolor="#BBBBBB"><td><b>Uwagi:</b><br> <textarea cols="80" rows="3" id="zgloszenie_uwagi" name="zgloszenie_uwagi">'.$r2['note'].'</textarea>
						';
						
						$result .= '</td></tr>
						<tr bgcolor="#BBBBBB"><td><b>Roszczenia</b>:';
						
						$result .= roszczenia_szczegoly($r2);
						
						$result .= '<div id="panel_pozycje"></div>
						<br>
						<div align="right"><input type="button" value="Dodaj pozycjê"  onClick="dodaj_pozycje(this);">&nbsp;&nbsp;&nbsp;</div><br>									<br>
						<div align="right"><input type="button" value="Zapisz"  onClick="zapisz_roszczenie();">&nbsp;&nbsp;&nbsp;</div><br>						
						';
						
						$result .= '</td></tr>
						
						<tr bgcolor="#AAAAAA"><td colspan="2" align="right"></td></tr>
						</table>';
			$result .= '
			<script>
			function zmien_zglaszajacy(val){			
				if (val == 1 ){
					document.getElementById(\'claims_form_instytucja\').style.display = \'none\';					
				}else if (val == 2){
					document.getElementById(\'claims_form_instytucja\').style.display = \'block\';
				}else{
					document.getElementById(\'claims_form_instytucja\').style.display = \'none\';					
				}
				
			}
			zmien_zglaszajacy(document.getElementById(\'announcer\').value)
	 </script>';			
				
			}	else { 		// nowe zg³oszenie
						$result .= '<table align="center" cellpadding="1" cellspacing="0" border="1"  width=95%><tr bgcolor="#BBBBBB"><td width="120" rowspan=3 valign="top">
						<b>Nowe zg³oszenie:</b> </td><td>												
						<b>Data:</b> <input type="text" size="10" name="data_zgloszenia" id="data_zgloszenia" value="'.date("Y-m-d").'" onChange="zmien_daty_kursow();">';							
						$result .= '&nbsp;&nbsp;&nbsp;&nbsp;<b>Zg³aszaj±cy:</b> <select name="announcer" id="announcer" onChange="zmien_zglaszajacy(this.value)" >
						
						<option value="1">Upowa¿niony</option>
						<option value="2">Instytucja</option>
						</select>
						';
						
						$result .= '
						 <div id="claims_form_instytucja" ><b>Instytucja:</b> '.wysw_instytucja2('zgloszenie_id_instytucja',$r2['ID_institution']).' </div>		
						</td></tr>
						<tr bgcolor="#BBBBBB"><td><b>Uwagi:</b><br> <textarea cols="80" rows="3" id="zgloszenie_uwagi" name="zgloszenie_uwagi"></textarea>
						';
						
						$result .= '</td></tr>
						<tr bgcolor="#BBBBBB"><td><b>Roszczenia</b>:
						<table  cellpadding="1" cellspacing="0" border=1 width="100%"><tr  bgcolor="#999999">
						
						<td align="center"><b>Usuñ</b></td>						
						<td align="center"><b>Opis roszczenia / ryzyko cz±stkowe</b></td>						
						<td align="center"><b>Kwota w wal.</b></td>
						<td align="center"><b>Waluta</b></td>
						<td align="center"><b>Kurs</b></td>
						<td align="center"><b>Kwota w PLN</b></td>
						</tr>
						<tr><td align="center"><input type="checkbox" name="zgloszenie_pozycja_usun" id="zgloszenie_pozycja_usun" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td>
						<td><table width="100%">
							<tr><td><b>Opis:</b> </td><td><textarea cols=35 rows="2" name="zgloszenie_opis_roszczenia" id="zgloszenie_opis_roszczenia"></textarea></td></tr>
						<tr><td><b>Ryzyko:</b> </td><td>'.wysw_ryzyko_czastkowe2('zgloszenie_id_ryzyko',0,0,$case_id,$row_case_ann['ryzyko_gl'], ' onChange="getOperat(this.value,document.getElementById(\'zgloszenie_id_operat\'));"').'</td></tr>
						<tr><td><b>Operat:</b> </td><td>'.wysw_ryzyko_operat2('zgloszenie_id_operat',0,0,$case_id).'</td></tr>
						</table>
						</td>
						<td><input style="text-align:right;" type="text" size="10" value="0" name="zgloszenie_kwota" id="zgloszenie_kwota"  onChange="przelicz(this);"></td>
						<td>'.wysw_currency_all('zgloszenie_currency_id','PLN',0,'onChange=przelicz(this,0);').'</td>
						<td align="center"><input style="text-align:right;" type="text" size="6" value="1,0" name="rate" id="rate" readonly><br><br><div id="rate_opis" name="rate_opis"></div></td>
						<td><input style="text-align:right;" type="text" size="10" value="" name="amount_pln" id="amount_pln" readonly></td>	
						</table><input type="hidden" name="rate_table_id" id="rate_table_id" value="">
							<div id="panel_pozycje"></div>
						<br>
						<div align="right"><input type="button" value="Dodaj pozycjê"  onClick="dodaj_pozycje(this);">&nbsp;&nbsp;&nbsp;</div><br>									<br>
						<div align="right"><input type="button" value="Dodaj"  onClick="dodaj_roszczenie();">&nbsp;&nbsp;&nbsp;</div><br>						
						';
						
						$result .= '</td></tr>						
						<tr bgcolor="#AAAAAA"><td colspan="3" align="right"></td></tr>
						</table>
						';
						
							$result .= '
			<script>
			function zmien_zglaszajacy(val){			
				if (val == 1 ){
					document.getElementById(\'claims_form_instytucja\').style.display = \'none\';					
				}else if (val == 2){
					document.getElementById(\'claims_form_instytucja\').style.display = \'block\';
				}else{
					document.getElementById(\'claims_form_instytucja\').style.display = \'none\';					
				}
			}
			zmien_zglaszajacy(document.getElementById(\'announcer\').value);
			
	 </script>';		
		
			}

	}else{ // view
					$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#AAAAAA">
		      		<td width="12%" align="center"><b>Data zg³.</b></td>	
					<td width="88%" align="center"><b>Zg³aszaj±cy</b></td>
			  </tr><tr>';
				
			$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' ";
			$mr = mysql_query($q1);
			while  ($r = mysql_fetch_array($mr)){
				 
				$result .= '<tr>
						<td>'.$r['announce_date'].'</td><td>';													
						
							
				$result .= '<b>'.$lista[$r['announcer']].'</b>';
						
					if ($r['announcer']==2)
							$result .= 	' :  '.wysw_instytucja2('zgloszenie_id_instytucja',$r['ID_institution'],1);
					$result .= '</td></tr>';	  
		 		$result .= '<td colspan="2" align="right">';	
		 		
		 		$result .= lista_roszczen_szczegoly($r);
				
						$result .= '</td></tr>';	
			}				
						$result .= '</td></tr></table>'	;
		
	}
	
	$result .= '</form>';
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

				
if (isset($change['claims_wyplaty'])){
	
	$result .= '
		<script>
		function dodaj_wyplate(){
			document.getElementById(\'edit_form_action\').value=\'pay_add\';											
					document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty]\';	
					
					document.getElementById(\'form_wyplaty\').submit();		
		}
		
	
		function dodaj_wyplate2(){
			document.getElementById(\'edit_form_action\').value=\'pay_add2\';											
					document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty]\';	
					
					document.getElementById(\'form_wyplaty\').submit();		
		}
		
	function zapisz_wyplate(){
			if (confirm(\'Czy napewno?\')){
				document.getElementById(\'edit_form_action\').value=\'pay_save\';																				
					document.getElementById(\'form_wyplaty\').submit();		
			}
		}
		
		
		function edycja_wyplaty(id){
					document.getElementById(\'edit_form_action\').value=\'edit_pay\';											
					document.getElementById(\'edit_form_action_val\').value=id;	
													
					document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty]\';	
					
					document.getElementById(\'form_wyplaty\').submit();		
		}
		
		
		
		</script>
	
	';
		
			$result .= '
		<table cellpadding="3" cellspacing="0" border="1" align="center" width=90%>
		  <tr>
				<td width="80" align="center"><b>Data</b></td>	
				<td width="50" align="center"><b>U¿ytk.</b></td>
				<td width="100" align="center"><b>Kwota</b></td>	
				<td width="100" align="center"><b>Forma wyp³aty</b></td>	
				<td width="80" align="center"><b>Status</b></td>												
				<td width="80" align="center"><b>Status<br> wysy³ka</b></td>			
				<td width="60" align="center"><b>Operat <br>szkod.</b></td>									
				<td width="60" align="center"><b>Zlec. wyp³.</b></td>									
				<td width="60" align="center"><b>Uzas.</b></td>									
				<td width="60" align="center"><b>Info</b></td>									
				<td width="60" align="center" title="Zlecenie wyp³aty"><b>Zlec.</b></td>									
			   </tr >';
			 	$query = "SELECT coris_assistance_cases_claims_pay.*,
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_pay.ID_user ) As user
			FROM coris_assistance_cases_claims_pay WHERE ID_case='$case_id' ORDER BY ID";
			$mysql_result = mysql_query($query);
			while ($row_r=mysql_fetch_array($mysql_result)){
													
			  $result .= '<tr>
				<td >'. ($row_r['date']) .'</td>	
				<td align="center">'.$row_r['user'].'</td>
				<td align="right">'. print_currency($row_r['amount']).' PLN</td>						
				<td align="center">'.$forma_wyplaty[$row_r['pay_type']].'</td>		
				
				
					<td align="center">'.$lista_status_wyplata[$row_r['status']].'</td>						
				<td align="center">'.($row_r['status_send']==1 ? 'Wys³ane' : '&nbsp;').'</td>				
							
					
				<td align="center"> <a href="AS_case_claims_pay_print.php?id='.$row_r['ID'].'&tryb=operat" target="_blank" title="Operat Szkodowy"><img src="img/print.gif" border=0></a> </td>			
				<td align="center"> <a href="AS_case_claims_pay_print.php?id='.$row_r['ID'].'&tryb=zlecenie" target="_blank" title="Zlecenie wyp³aty"><img src="img/print.gif" border=0></a> </td>			
				<td align="center"> <a href="AS_case_claims_pay_print.php?id='.$row_r['ID'].'&tryb=uzasadnienie" target="_blank" title="Uzasadnienie"><img src="img/print.gif" border=0></a> </td>			
				<td align="center"> <a href="javascript:edycja_wyplaty('.$row_r['ID'].');" title="Szczegó³y sprawy">Info</a> </td>
				
				<td align="center">'.($row_r['status_zlecenie']==1 ? 'Dodane' : '&nbsp;').'</td>				
			   </tr >';			   
			}
			
		$result .= '</table><br>';			
					
				if(getValue('edit_form_action') == 'pay_add'){
						$result .= '
							  	  <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
							      <tr bgcolor="#AAAAAA">
							      		<td width="5%" align="center">&nbsp;</td>	
							      		<td width="12%" align="center"><b>Data zg³.</b></td>	
										<td width="83%" align="center"><b>Zg³aszaj±cy</b></td>
								  </tr><tr>';
									
								$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' AND ID IN ( SELECT DISTINCT coris_assistance_cases_claims_details.ID_claims FROM coris_assistance_cases_claims_details,coris_assistance_cases_claims WHERE coris_assistance_cases_claims.ID_case='$case_id'  AND coris_assistance_cases_claims_details.ID_claims=coris_assistance_cases_claims.ID  ) ";//AND coris_assistance_cases_claims_details.status=1 
								$mr = mysql_query($q1);
								$ilosc=0;
								while  ($r = mysql_fetch_array($mr)){
									 
									$result .= '<tr>
											<td><input type="radio" name="roszczenie"  id="roszczenie"  value="'.$r['ID'].'"></td>												
											<td>'.$r['announce_date'].'</td><td>';													
											
												
									$result .= '<b>'.$lista[$r['announcer']].'</b>';
											
										$result .= '</td></tr>';	  
							 		$result .= '<td colspan="3" align="right">';	
									$result .= lista_roszczen_szczegoly($r,1);
							 		
										
											$result .= '</td></tr>
											
											<tr><td colspan="3"><hr></td></tr>
											
											<hr>';	
										$ilosc++;			
								}				
											$result .= '</tr></tr></table>'	;
								if ($ilosc>0)
											$result .= '<div align="right"><input type="button" name="pay_add" onClick="dodaj_wyplate2();" value="Dodaj wyp³atê">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';	
						
							
					
					
				}else if(getValue('edit_form_action') == 'pay_add2'){
					//roszczenie = ID
					//roszczenie_pozycja[ID][key]
					$roszczenie = getValue('roszczenie');
					$roszczenie_pozycja = $_POST['roszczenie_pozycja'];
					
					if ( $roszczenie>0 && is_array($roszczenie_pozycja) ){
						$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' AND ID  IN ( SELECT DISTINCT coris_assistance_cases_claims_details.ID_claims FROM coris_assistance_cases_claims_details,coris_assistance_cases_claims WHERE coris_assistance_cases_claims.ID_case='$case_id'  AND coris_assistance_cases_claims_details.ID_claims=coris_assistance_cases_claims.ID   ) AND ID = '$roszczenie'";//AND coris_assistance_cases_claims_details.status=1
						
						$mr = mysql_query($q1);
								$ilosc=0;
						$r = mysql_fetch_array($mr);		
						$result .= '<input type="hidden" name="roszczenie" value="'.$roszczenie.'">';
						$result .= '<div align="center"><b>Przygotowanie wyp³aty</b></div><br>';	
						
						$row_agent = row_agent($row_case_ann['biurop_id']);
						
						$result .= '<table cellpadding="3" cellspacing="0"  width="90%" align="center" border="1">
						<tr><td colspan="2"> 
							<table border=1 cellpadding="5" cellspacing="0">
								<tr ><td align="right"> <b>Nr szkody:</b> </td><td>&nbsp;'.$row_case['client_ref'].'</td></tr>
								<tr><td align="right"> <b>Ubezpieczony:</b> </td><td> '.$row_case['paxname'].' '.$row_case['paxsurname'].'</td></tr>
								<tr><td align="right"> <b>Data zdarzenia:</b> </td><td> '.$row_case['eventdate'].' </td></tr>
								<tr ><td align="right"> <b>Data zg³oszenia:</b> </td><td> '.substr($row_case['date'],0,10).'</td></tr>
								<tr><td align="right"> <b>Data zaakceptowania:</b> </td><td> '.date('Y-m-d').' </td></tr>
								<tr><td align="right"> <b>Nr polisy:</b> </td><td> '.$row_case['policy'].'</td></tr>
								<tr ><td align="right"> <b>Okres ubezpieczenia:</b> </td><td> '.$row_case['validityfrom'].' - '.$row_case['validityto'].'</td></tr>
								<tr ><td align="right"> <b>Agent:</b> </td><td>'.$row_agent['nazwa'].'&nbsp;</td></tr>
								<tr><td align="right"> <b>Oddzia³:</b> </td><td>'.$row_agent['miasto'].'&nbsp;</td></tr>
							</table>	
						
						</td></tr>
						
						<tr><td width="50%" valign="top">
							<b>Odbiorca:</b>  '.$lista[$r['announcer']].'<br>';
							$konto_numer='';
							$bank_nazwa='';
							$wyplata_typ=0;
							
							if ($r['announcer']==1){//upowa¿niony
									$result .= ($row_case_ann['upowaz_plec']=='K' ? 'Pani' : '').($row_case_ann['upowaz_plec']=='M' ? 'Pan' : '').' '.$row_case_ann['upowaz_imie'].' '.$row_case_ann['upowaz_nazwisko'];
									$result .= '<br>ul. '.$row_case_ann['upowaz_ulica'];
									$result .= '<br>'.$row_case_ann['upowaz_kod']. ' '.$row_case_ann['upowaz_miasto'];		
									$konto_numer = 	$row_case_ann['upowaz_konto'];				
									$bank_nazwa = 	$row_case_ann['upowaz_bank_nazwa'];				
									$wyplata_typ = 	$row_case_ann['upowaz_wyplata_typ'];					
															
							}else if ($r['announcer']==2){//Instytucja
									$qi = "SELECT * FROM coris_skok_institution2 WHERE kod='".$r['ID_institution']."'";
									$mri = mysql_query($qi);
									$ri = mysql_fetch_array($mri);
									
									$result .= $ri['nazwa'];
									$result .= '<br>ul. '.$ri['ulica'];
									$result .= '<br>'.$ri['kod_pocz']. ' '.$ri['miasto'];			
									$konto_numer = $ri['KONTO'];
									$bank_nazwa = '';
									$wyplata_typ = 1;	
							}
						$result .= '</td><td width="50%"  valign="top"><b>Sposób wyp³aty:</b> ';
								$result .= '<select name="pay_type" id="pay_type" onChange="zmien_platnosc(this.value)">
									<option value="1" '.($wyplata_typ==1 ? 'selected' : '').'>Przelew bankowy</option>
									<option value="2" '.($wyplata_typ==2 ? 'selected' : '').'>Przekaz pocztowy</option>									
								</select>
								';
								$result .= '
								<script>
								
									function zmien_platnosc(val){
										if (val==1){
											document.getElementById(\'account_number_div\').style.display = \'block\';
										}else{
											document.getElementById(\'account_number_div\').style.display = \'none\';
										}
									}
								</script>
								<div id="account_number_div">
								
								<b>Nazwa Banku:</b> <input type="text size="30" name="bank_name" id="bank_name" value="'.$bank_nazwa.'">
								<br><b>Nr konta:</b> <input type="text size="40" name="account_number" id="account_number" value="'.format_konto($konto_numer).'">';
						$result .= '</td></tr>
						<tr><td colspan="2">
						<b>Uwagi:</b><br>
						<textarea name="note" id="note" cols="100" rows="2"></textarea>
						</td></tr>						
						<tr><td colspan="2">
						<b>¦wiadczenia:</b>
							<table width="100%" cellpadding="5" cellspacing="0" border=1>
							
							<tr>
								<td width="20%" align="center"><b>¦wiadczenie</b></td>
								<td width="20%" align="center"><b>Numer umowy</b></td>
								<td width="20%" align="center"><b>Produkt</b></td>
								<td width="20%" align="center"><b>Suma</b></td>
								<td width="20%" align="center"><b>Uwagi</b></td>								
							</tr>	';
							$query = "SELECT coris_assistance_cases_claims_details.*,
							(SELECT nazwa FROM coris_skok_ryzyko_operat WHERE coris_skok_ryzyko_operat.ID=coris_assistance_cases_claims_details.ID_operat  ) As operat, 
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.ID_user ) As user,
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.status_ID_user ) As status_user
							FROM coris_assistance_cases_claims_details WHERE ID_claims='".$r['ID']."' AND ID IN (".implode(',',$roszczenie_pozycja[$roszczenie]).")  ORDER BY ID";
						
							$mrd = mysql_query($query);
							$suma = 0.0;
							while ($row_rd=mysql_fetch_array($mrd)){		
								$result .= '
								<input type="hidden" name="roszczenie_pozycja[]" value="'.$row_rd['ID'].'">
									<tr>
										<td width="20%">'.$row_rd['operat'].'</td>
										<td width="20%">'.$row_case['policy'].'&nbsp;</td>
										<td width="20%">'.getNameproduct($row_case_ann['ryzyko_gl']).'</td>
										<td width="20%" align="right">'.print_currency($row_rd['amount_accept_pln']).' PLN</td>
										<td width="20%"><textarea rows="2" cols="20" name="claim_note['.$row_rd['ID'].']"></textarea></td>
									</tr>	';
								$suma += $row_rd['amount_accept_pln'];
							}	
							$result .= '<tr><td colspan="3" ><b>Suma do wyp³aty:</b> </td><td align="right"><b>'.print_currency($suma).' PLN</b></td><td>&nbsp;</td></tr>';
							$result .= '</table>
						</td></tr>
						
						</table>';
						$result .= '<div align="right"><input type="button" name="pay_save" onClick="zapisz_wyplate();" value="przygotuj wyp³ate">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';
						
					}else{
						$result .= '<div align="center"><b>Brak pozycji do wyp³aty</b></div><br>';	
						$result .= '<div align="center"><input type="button" name="pay_add" onClick="dodaj_wyplate();" value="Powrót">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';	
					}
					}else if(getValue('edit_form_action') == 'edit_pay'  ){ //edycja wyplaty		
											
/////////////////////////////////////////////////////////////						

							$result .= '
							<script>
							function skasuj_wyplate(id){
								if (confirm(\'Czy napewno chcesz usun±æ wyp³atê?\')){
										document.getElementById(\'edit_form_action\').value=\'delete_pay\';											
										document.getElementById(\'edit_form_action_val\').value=id;	
															
									//	document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty111]\';						
										document.getElementById(\'form_wyplaty\').submit();							
								}
																
							}

							function aktualizuj_wyplate(id){
									document.getElementById(\'edit_form_action\').value=\'edit_pay\';											
									document.getElementById(\'edit_form_action_val\').value=id;	
									document.getElementById(\'pay_action\').value=\'aktualizacja\';	
													
									document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty]\';						
									document.getElementById(\'form_wyplaty\').submit();		
							}

							function zapisz_status_wyplaty(id){
								//status
								//old_status		
								document.getElementById(\'edit_form_action\').value=\'save_status_pay\';											
								document.getElementById(\'edit_form_action_val\').value=id;	
									document.getElementById(\'pay_action\').value=\'save\';					
								//document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty111]\';						
								document.getElementById(\'form_wyplaty\').submit();							
							}


							
							</script>
							';
								$pay_id = getValue('edit_form_action_val');
								//$q1 = "SELECT * FROM coris_assistance_cases_claims  WHERE ID_case='$case_id' AND ID  IN ( SELECT DISTINCT coris_assistance_cases_claims_details.ID_claims FROM coris_assistance_cases_claims_details,coris_assistance_cases_claims WHERE coris_assistance_cases_claims.ID_case='$case_id'  AND coris_assistance_cases_claims_details.ID_claims=coris_assistance_cases_claims.ID   ) AND ID = '$roszczenie'";//AND coris_assistance_cases_claims_details.status=1
								$q1 = "SELECT * FROM coris_assistance_cases_claims_pay  WHERE ID='$pay_id'";
								
						$mr = mysql_query($q1);
								$ilosc=0;
						$r = mysql_fetch_array($mr);
								
						$result .= '
						
						<input type="hidden" name="pay_action"  id="pay_action" value="">
						<input type="hidden" name="wyplata" value="'.$roszczenie.'">';
						$result .= '<hr>
						<div align="center"><b>Wyp³ata szczegó³y</b></div><br>';	
						
						$row_agent = row_agent($row_case_ann['biurop_id']);
						
						$result .= '<table cellpadding="3" cellspacing="0"  width="90%" align="center" border="1">
						<tr><td colspan="2"> 
							<table border=1 cellpadding="5" cellspacing="0">
								<tr ><td align="right"> <b>Nr szkody:</b> </td><td>&nbsp;'.$row_case['client_ref'].'</td></tr>
								<tr><td align="right"> <b>Ubezpieczony:</b> </td><td> '.$row_case['paxname'].' '.$row_case['paxsurname'].'</td></tr>
								<tr><td align="right"> <b>Data zdarzenia:</b> </td><td> '.$row_case['eventdate'].' </td></tr>
								<tr ><td align="right"> <b>Data zg³oszenia:</b> </td><td> '.substr($row_case['date'],0,10).'</td></tr>
								<tr><td align="right"> <b>Data zaakceptowania:</b> </td><td> '.date('Y-m-d').' </td></tr>
								<tr><td align="right"> <b>Nr polisy:</b> </td><td> '.$row_case['policy'].'</td></tr>
								<tr ><td align="right"> <b>Okres ubezpieczenia:</b> </td><td> '.$row_case['validityfrom'].' - '.$row_case['validityto'].'</td></tr>
								<tr ><td align="right"> <b>Agent:</b> </td><td>'.$row_agent['nazwa'].'&nbsp;</td></tr>
								<tr><td align="right"> <b>Oddzia³:</b> </td><td>'.$row_agent['miasto'].'&nbsp;</td></tr>
							</table>	
						
						</td></tr>
						
						<tr><td width="50%" valign="top">
							<b>Odbiorca:</b>  '.$lista[$r['announcer']].'<br>';
							$konto_numer='';
							$bank_nazwa='';
							$wyplata_typ=0;
								
							
							$wyplata_typ =$r['pay_type'];
							
							$konto_numer = $ri['account_number'];							
							
									$result .= ($r['sex']=='K' ? 'Pani' : '').($r['sex']=='M' ? 'Pan' : '').' '.$r['name'].' '.$r['surname'];
									$result .= '<br>ul. '.$r['adress'];
									$result .= '<br>'.$r['post']. ' '.$r['city'];		
																		
						$result .= '</td><td width="50%"  valign="top"><b>Sposób wyp³aty:</b> ';
							if ($wyplata_typ==1){
									$result .= 'Przelew bankowy																
												<br><b>Nazwa Banku:</b> '.$r['bank_name'].'
												<br><b>Nr konta:</b> '.format_konto($r['account_number']);
							}else if ($wyplata_typ==2){
									$result .= 'Przekaz pocztowy';		
							}								
								
						$result .= '</td></tr>
						<tr><td colspan="2">
						<b>Uwagi:</b><br>
						<textarea name="note" id="note" cols="100" rows="2" disabled>'.$r['note'].'</textarea>
						</td></tr>						
						<tr><td colspan="2">
						<b>¦wiadczenia:</b>
							<table width="100%" cellpadding="5" cellspacing="0" border=1>
							
							<tr>
								<td width="20%" align="center"><b>¦wiadczenie</b></td>
								<td width="20%" align="center"><b>Numer umowy</b></td>
								<td width="20%" align="center"><b>Produkt</b></td>
								<td width="20%" align="center"><b>Suma</b></td>
								<td width="20%" align="center"><b>Uwagi</b></td>								
							</tr>	';
							$query = "SELECT coris_assistance_cases_claims_pay_position .*,
							(SELECT nazwa FROM coris_skok_ryzyko_operat WHERE coris_skok_ryzyko_operat.ID=coris_assistance_cases_claims_pay_position.ID_operat  ) As operat
							FROM coris_assistance_cases_claims_pay_position  WHERE ID_claims_pay ='".$pay_id."' ORDER BY ID";
						
							$mrd = mysql_query($query);
							$suma = 0.0;
							while ($row_rd=mysql_fetch_array($mrd)){		
								$result .= '
								<input type="hidden" name="roszczenie_pozycja[]" value="'.$row_rd['ID'].'">
									<tr>
										<td width="20%">'.$row_rd['operat'].'</td>
										<td width="20%">'.$row_case['policy'].'&nbsp;</td>
										<td width="20%">'.getNameproduct($row_case_ann['ryzyko_gl']).'</td>
										<td width="20%" align="right">'.print_currency($row_rd['amount_pln']).' PLN</td>
										<td width="20%"><textarea rows="2" cols="20" name="pay_position_note" disabled>'.$row_rd['note'].'</textarea></td>
									</tr>	';
								$suma += $row_rd['amount_accept_pln'];
							}	
							$result .= '<tr><td colspan="3" ><b>Suma do wyp³aty:</b> </td><td align="right"><b>'.print_currency($r['amount']).' PLN</b></td><td>&nbsp;</td></tr>';
							$result .= '</table>
						</td></tr>
						<tr><td colspan="2">						';
						if ($r['status'] == 2 || $r['status'] == 0) // do poprawy lub do weryfikacji
								$result .= '<br><div align="right"><input type="button" name="pay_save" onClick="skasuj_wyplate('.$pay_id.')" value="Usuñ wyp³atê" style="background-color:red;">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';
						if ($r['status'] == 2 || $r['status'] == 0) // do poprawy lub do weryfikacji
							$result .= '<br><div align="right"><input type="button" name="pay_save" onClick="aktualizuj_wyplate('.$pay_id.')" value="Aktualizuj wyp³atê">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';
						$result .= '</td></tr>

						<tr><td colspan="2">						';
						$result .= '
						Aktualny status: <b>'.$lista_status_wyplata[$r['status']].'</b>
						<br>Zmieñ status na:
							<input type="hidden" name="old_status" value="'.$r['status'].'">
							<select name="status" id="status">
								<option value="0">Nie zmieniaj</option>';
								foreach ($lista_status_wyplata As $key => $poz){	
									if ($key == 0)
										continue;			
									if (	$r['status'] == 0 || $r['status'] == 3 ){ // do weryfikacji lub do poprawy
										if (   ($key==1 || $key==2) && check_claim_admin() )
											$result .= '<option value="'.$key.'">'.$poz.'</option>';
									}else if (	$r['status'] == 2 ){ // do poprawy
										if ($key==3 && ( check_claim_handler_user() || check_claim_admin() ))
											$result .= '<option value="'.$key.'">'.$poz.'</option>';											
									}else if (	$r['status'] == 1 ){ // zatwierdzone (ponowna wysylka)
										if ($key==2 &&  check_claim_admin()  )
											$result .= '<option value="'.$key.'">'.$poz.'</option>';											
									}										
									
								}
							$result .= '</select>';
						if ($r['status'] == 1 )
								$result .= '<br>Wysy³ka: <input type="hidden" name="old_status_send" value="'.$r['status_send'].'"><input type="checkbox" name="status_send" value="1" '.($r['status_send']==1 ? 'checked disabled' : '').'>';
						$result .= '<br><br><b>Lista p³atno¶ci:</b><br>'.getListaPlatnosci($pay_id);
						if ($r['status'] == 1 && check_claim_admin() ){		
								$result .= '<br>Dodaj do listy p³atno¶ci: <input type="checkbox" name="status_zlecenie" value="1" >';
						}
						$result .= '<div align="right"><input type="button" name="pay_save" onClick="zapisz_status_wyplaty('.$pay_id.')" value="Zatwierd¼">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';
						$result .= '</td></tr>
						</table>';
						
/////////////////////////////////////////////////////////////
		
				}else{				
					
					$result .= '<div align="right"><input type="button" name="pay_add" onClick="dodaj_wyplate();" value="Dodaj">&nbsp;&nbsp;&nbsp;&nbsp;</div><br>';	
				}
			$result .= '<br>';	

	}else{
			$result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		  <tr>
				<td width="10%" align="center"><b>Data</b></td>	
				<td width="10%" align="center"><b>U¿ytkownik</b></td>
				<td width="15%" align="center"><b>Kwota</b></td>	
				<td width="15%" align="center"><b>Forma wyp³aty</b></td>												
				<td width="15%" align="center"><b>Status</b></td>												
				<td width="15%" align="center"><b>Status wysy³ka</b></td>												
				<td width="15%" align="center"><b>Status zlecenie wyp³aty</b></td>												
			   </tr >';
			 	$query = "SELECT coris_assistance_cases_claims_pay.*,
			(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_pay.ID_user ) As user
			FROM coris_assistance_cases_claims_pay WHERE ID_case='$case_id' ORDER BY ID";
			$mysql_result = mysql_query($query);
			while ($row_r=mysql_fetch_array($mysql_result)){
													
			  $result .= '<tr>
				<td  align="center">'. ($row_r['date']) .'</td>	
				<td align="center">'.$row_r['user'].'</td>
				<td align="right">'. print_currency($row_r['amount']).' PLN</td>						
				<td align="center">'.$forma_wyplaty[$row_r['pay_type']].'</td>						
				<td align="center">'.$lista_status_wyplata[$row_r['status']].'</td>						
				<td align="center">'.($row_r['status_send']==1 ? 'Wys³ane' : '&nbsp;').'</td>						
				<td align="center">'.($row_r['status_zlecenie']==1 ? 'Dodane' : '&nbsp;').'</td>						
			   </tr >';			   
			}
			
		$result .= '</table><br>';			
		
		
		
	}	
	$result .= '</form>';
	return $result;	
}	



function  wysw_instytucja2($name,$def,$tryb=0){
	if ($tryb){
		if ($def> 0){
			$query = "SELECT kod,nazwa,miasto FROM coris_skok_institution2   WHERE kod='$def' ORDER BY nazwa,miasto";									
			$mysql_result = mysql_query($query);			
			$row2 = mysql_fetch_array($mysql_result);		
			$result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 7pt;width:350px" disabled>';
			 $result .= '<option value="'. $row2['kod'] .'" '. (($row2['kod'] == $def) ? "selected" : "") .'>'.substr($row2['nazwa'],0,65).' - '.$row2['miasto'].'</option>';
			   $result .= '</select>';
			 return $result;	
		}
	}else{
		$result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 7pt;">
					<option value=""></option>';
			$query = "SELECT kod,nazwa,miasto FROM coris_skok_institution2 ORDER BY nazwa,miasto";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['kod'] .'" '. (($row2['kod'] == $def) ? "selected" : "") .'>'.substr($row2['nazwa'],0,65).' - '.$row2['miasto'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}


function getKursy($publication_date,$ratetype_id,$table_currency,$table_source,$table_id){
	
	
	$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_pln, 
			coris_finances_currencies_tables_rates.multiplier AS rate_to_pln_mult,  
			(coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate) AS rate_to_ext, 
			coris_finances_currencies_tables_rates.table_id,
			coris_finances_currencies_tables_rates.rate AS rate,
			coris_finances_currencies_tables.quotation_date,
			coris_finances_currencies_tables.publication_date, 
			coris_finances_currencies_tables.ratetype_id,
			coris_finances_currencies_tables.number
			   
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
			WHERE coris_finances_currencies_tables.source_id='$table_source'  AND ";						
	if ( $table_id>0)
		$query .= " coris_finances_currencies_tables.table_id='$table_id' ";
	else
		$query .= " coris_finances_currencies_tables.publication_date <= '$publication_date' AND  coris_finances_currencies_tables.ratetype_id='".$ratetype_id."'";
			
		$query .= 	" AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";


		$mysql_result = mysql_query($query);
		return $mysql_result;
}

/*
	tryb=0 = 
	tryb=1 = 	
*/
function roszczenia_szczegoly($r2,$tryb=0){
	global $lista_status,$lista;
	
	$result = '<table border=1 width="100%" cellspacing="0" cellpadding="3"><tr  bgcolor="#999999">
				<td align="center"><b>Usuñ</b></td>
				<td align="center" width="270"><b>Opis roszczenia / ryzyko cz±stkowe</b></td>				
				<td align="center"><b>Kwota</b></td>
				<td align="center"><b>Waluta</b></td>
				<td align="center" width="85"><b>Kurs</b></td>
				<td align="center"><b>Kwota w PLN</b></td>
						
						</tr>';
						
					$q3 = "SELECT *  FROM coris_assistance_cases_claims_details WHERE ID_claims='".$r2['ID']."' ORDER BY ID";
					$mr3 = mysql_query($q3);
					$lista=array();
					$del_status = '';
					
					if (mysql_num_rows($mr3)==0){
						$result .= '<tr><td colspan="6"><br>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="zgloszenie_usun" value="1" style="background-color:#BBBBBB;"><b style="color:red;">Usuñ roszczenie</b><br><br></td></tr>';
						
					}
					while ($r3 = mysql_fetch_array($mr3)){
						$del_status = '';
						$stan  = ($r3['status'] == 1  || $r3['status'] == 2)  ? 'disabled' : '';
						
						if ($stan =='disabled'){
								$del_status = 'disabled';							
						}else{
							$qt = "SELECT ID FROm coris_assistance_cases_claims_pay_position WHERE ID_claims_details = '".$r3['ID']."'";
							$mt = mysql_query($qt);
							if (mysql_num_rows($mt) > 0 ){
									$del_status = 'disabled';								
							}else 
								$del_status = '';								
						}
						
						$wartosc_pln = '';
						$table_id = '';
						$rate = '';
						$rate_opis = '';
						
						if ($r3['currency_id'] == 'PLN'){
								$table_id=1;
								$rate=1;
								$wartosc_pln = $r3['amount'];
						}else{
							$table_id=$r3['currency_table_id'];
							$table_date=date('Y-m-d');
							$mr_k = getKursy($table_date,1,$r3['currency_id'],1,$table_id);

							$num_rows = mysql_num_rows($mr_k);
							if ($num_rows>0){
									$r_k = mysql_fetch_array($mr_k);
									$status=1;
									$table_id = $r_k['table_id']	;
									$rate = $r_k['rate']/$r_k['rate_to_pln_mult'];
									$table_no = $r_k['number'];	
									$rate_opis = $r_k['publication_date'];
									$wartosc_pln = ev_round($r3['amount']*$rate,2);
								}else{
									
									
								}
						}
						
 						$result .= '	
							<tr>
							<td rowspan=3><input '.$del_status.' type="checkbox" title="Usuñ pozycjê" name="zgloszenie_pozycja_usun['.$r3['ID'].']" id="zgloszenie_pozycja_usun['.$r3['ID'].']"  value="1" style="background-color:#BBBBBB;"></td><td>
							<table width="100%">							
								<tr><td><b>Opis:</b> </td><td><textarea '.$stan.' cols="35" rows="2" name="zgloszenie_opis_roszczenia['.$r3['ID'].']" id="zgloszenie_opis_roszczenia['.$r3['ID'].']">'.$r3['note'].'</textarea></td></tr>
								<tr><td><b>Ryzyko:</b> </td><td>'.wysw_ryzyko_czastkowe('zgloszenie_id_ryzyko['.$r3['ID'].']',$r3['ID_risk'],0,$case_id,$stan.' onChange="getOperat(this.value,document.getElementById(\'zgloszenie_id_operat['.$r3['ID'].']\'));"').'</td></tr>
								<tr><td><b>Operat:</b> </td><td>'.wysw_ryzyko_operat2('zgloszenie_id_operat['.$r3['ID'].']',$r3['ID_operat'],0,$r3['ID_risk'],$stan).'</td></tr>
								</table>
							</td>							
							<td align="right" valign="top"><input '.$stan.' style="text-align:right;" type="text" size="10" value="'.print_currency($r3['amount']).'" name="zgloszenie_kwota['.$r3['ID'].']" id="zgloszenie_kwota['.$r3['ID'].']"></td>
							<td align="center"  valign="top"  width="80">'.wysw_currency_all('zgloszenie_currency_id['.$r3['ID'].']',$r3['currency_id'],0,$stan.' onChange="przelicz(this,'.$r3['ID'].');"').'</td>
							<td align="center"  valign="top"><input style="text-align:right;" type="text" size="6" value="'.print_currency($rate,4).'" name="rate['.$r3['ID'].']" id="rate['.$r3['ID'].']" readonly><br><div id="rate_opis['.$r3['ID'].']" name="rate_opis['.$r3['ID'].']"><small>'.$rate_opis.'</small></div></td>
							<td  align="right"  valign="top">&nbsp;</td>
							<input type="hidden" name="rate_table_id['.$r3['ID'].']" id="rate_table_id['.$r3['ID'].']" value="'.$table_id.'">
							</tr>
							<tr>
								<td colspan="1" align="right"><b>Kwota zaakceptowana:</b> </td>
									<td colspan="1" align="right"><input '.$stan.' style="text-align:right;" type="text" size="10" value="'.print_currency($r3['amount_accept'],2).'" name="zgloszenie_kwota_akcept['.$r3['ID'].']" id="zgloszenie_kwota_akcept['.$r3['ID'].']" onChange="przelicz(this,'.$r3['ID'].');"></td>
									<td colspan="2" align="right">&nbsp;</td>
									<td colspan="1" align="right"><input style="text-align:right;" type="text" size="10" value="'.print_currency($wartosc_pln,2).'" name="amount_pln['.$r3['ID'].']" id="amount_pln['.$r3['ID'].']" readonly></td>
									
									
									
									
									
									</td>
								</tr>
							<tr  bgcolor="#AAAAAA">
								<td colspan="5">
									<b>Uwagi:</b><textarea '.$stan.' cols="80" rows="2" name="statu_opis_roszczenia['.$r3['ID'].']" id="statu_opis_roszczenia['.$r3['ID'].']">'.$r3['note_status'].'</textarea>
								</td>
							<tr>';


									$result .= '<tr><td colspan="5" height="5" bgcolor="#777777">&nbsp;</td></tr>';			
								$lista[] ='"'.$r3['ID'].'"';										
					}
						
					$result .= '</table>
					<script>
						lista_id = Array('.implode(',',$lista).');
					</script>
					';
						
			return $result;				
}

function lista_roszczen_szczegoly($r,$tryb=0){
	global $lista_status,$lista_status_roszczenie,$case_id;
	
			 		$result ='<table cellpadding="1" cellspacing="0" border="1"  width=95%>
						  <tr bgcolor="#BBBBBB">';
						  
			 		if ($tryb)
			 			$result .= '<td width="5%" align="center">&nbsp;</td>';
						  $result .= '<td width="70" align="center"><b>Rezerwa <br>w PLN</b></td>
							<td width="80" align="center"><b>Ryzyko cz±stkowe</b></td>	
							<td width="80" align="center"><b>Operat</b></td>		
								<td width="70" align="center"><b>Kwota</b></td>
								<td width="50" align="center"><b>Waluta</b></td>	
								<td width="100" align="center"><b>Kurs</b></td>	
								<td width="70" align="center"><b>Kwota <br>w PLN</b></td>
								<td width="70" align="center"><b>Kwota zaakcept.</b></td>		
								<td width="70" align="center"><b>Status</b></td>		
								
							   </tr >';
							
							$query = "SELECT coris_assistance_cases_claims_details.*,
							(SELECT nazwa FROM coris_skok_ryzyka_czastkowe WHERE coris_skok_ryzyka_czastkowe.ID=coris_assistance_cases_claims_details.ID_risk  ) As ryzyko, 
							(SELECT nazwa FROM coris_skok_ryzyko_operat  WHERE coris_skok_ryzyko_operat.ID=coris_assistance_cases_claims_details.ID_operat  ) As operat, 
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.ID_user ) As user,
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.status_ID_user ) As status_user
							FROM coris_assistance_cases_claims_details WHERE ID_claims='".$r['ID']."' ".($tryb==1 ? ' ' : '')." 
							".(  $tryb==1 ? " AND ID NOT IN (SELECT ID_claims_details FROM coris_assistance_cases_claims_pay_position,coris_assistance_cases_claims_pay  WHERE coris_assistance_cases_claims_pay.ID_case ='$case_id' AND coris_assistance_cases_claims_pay.ID=coris_assistance_cases_claims_pay_position.ID_claims_pay )" :'')."
							
							ORDER BY ID";
						
							$mysql_result = mysql_query($query);
							while ($row_r=mysql_fetch_array($mysql_result)){								
										$wartosc_pln = '';
										$table_id = '';
										$rate = '';
										$rate_opis = '&nbsp;';
										
										if ($row_r['currency_id'] == 'PLN'){
												$table_id=1;
												$rate=1;
												$wartosc_pln = $row_r['amount'];
										}else{
											$table_id=$row_r['currency_table_id'];
											$table_date=date('Y-m-d');
											$mr_k = getKursy($table_date,1,$row_r['currency_id'],1,$table_id);
				
											$num_rows = mysql_num_rows($mr_k);
											if ($num_rows>0){
													$r_k = mysql_fetch_array($mr_k);
													$status=1;
													$table_id = $r_k['table_id']	;
													$rate = $r_k['rate']/$r_k['rate_to_pln_mult'];
													$table_no = $r_k['number'];	
													$rate_opis = "1 ".$row_r['currency_id']." = ".$rate." PLN" ."<br><small>(".$r_k['publication_date'].")</small>";
													$wartosc_pln = ev_round($row_r['amount_accept']*$rate,2);
												}else{
													
													
												}
										}
							  $result .= '<tr>';
								//<td align="left" title="'.str_replace('"',"'",strip_tags($row_r['note'])).'">'.substr($row_r['note'],0,100).' ...</td>				
							  	if ($tryb)
							  		$result .= '<td align="center"><input type="checkbox" name="roszczenie_pozycja['.$r['ID'].'][]" id="roszczenie_pozycja['.$r['ID'].'][]" value="'.$row_r['ID'].'"></td>';
							 $result .= ' <td align="right" ><b>'. print_currency($row_r['reserve']) .'</b></td>	
								<td ><b>'. ($row_r['ryzyko']) .'&nbsp;</b></td>	
								<td align="left" title="'.str_replace('"',"'",strip_tags($row_r['note'])).'">'.$row_r['operat'].'</td>						
								<td align="right"><b>'. print_currency($row_r['amount']) .'</b></td>								
								<td align="center">'.($row_r['currency_id']).'</td>	
								<td align="left">'.$rate_opis.'</td>	
								<td align="right"><b>'. print_currency($wartosc_pln) .'</b></td>
								<td align="right"><b>'.( $row_r['amount_accept']>'0' ?  str_replace('.',',',$row_r['amount_accept']) : '0.00' ).'</b></td>
								<td align="center">'.$lista_status_roszczenie[$row_r['status']].'</td>						
								
								
							   </tr >';
							   
							}
						$result .= '</table>';	
						
					return $result;	
	
}



function getKursyX($publication_date,$ratetype_id,$table_currency,$table_source,$table_id){
	
	
	$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_pln, 
			coris_finances_currencies_tables_rates.multiplier AS rate_to_pln_mult,  
			(coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate) AS rate_to_ext, 
			coris_finances_currencies_tables_rates.table_id,
			coris_finances_currencies_tables_rates.rate AS rate,
			coris_finances_currencies_tables.quotation_date,
			coris_finances_currencies_tables.publication_date, 
			coris_finances_currencies_tables.ratetype_id,
			coris_finances_currencies_tables.number
			   
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
			WHERE coris_finances_currencies_tables.source_id='$table_source'  AND ";						
	if ( $table_id>0)
		$query .= " coris_finances_currencies_tables.table_id='$table_id' ";
	else
		$query .= " coris_finances_currencies_tables.publication_date <= '$publication_date' AND  coris_finances_currencies_tables.ratetype_id='".$ratetype_id."'";
			
		$query .= 	" AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";


		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return ( $row['rate_to_pln']/$row['rate_to_pln_mult'] );
}

function check_claim_handler_user(){
	global $row_case;
	//	return false;	
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

function getListaPlatnosci($pay_id){
	
	$query = "SELECT * FROM coris_assistance_cases_claims_lista_platnosci WHERE ID_claims_pay='$pay_id' ORDER BY ID DESC";
	
	$mysql_result = mysql_query($query);
	
	$result = '';
	if (mysql_num_rows($mysql_result) > 0 ){
			$result .= '<table width="300" border="1" cellpadding="1" cellspacing="0">';
			while ($row=mysql_fetch_array($mysql_result)){
				$result .= '<tr><td>'.$row['date'].'</td><td>'.getUserInitials($row['user_id']).'</td></tr>';				
			}
			
			$result .= '</table>';
	}	
	return $result;
	
}

?>