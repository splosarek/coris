<?php
include_once('lib/lib_barclaycard.php');

	$lista_admin_user = array(7,26,39,4);
		
	$lista = array('&nbsp;','Upowa¿niony','Instytucja');			
    $lista_status = array('Oczekuj±cy','Zaakceptowany','Odrzucony');	
    
    $lista_status_wyplata = array('Do weryfikacji','Zatwierdzone','Do poprawy','Poprawione');	
    
    $forma_wyplaty = array('','Przelew bankowy','Przekaz pocztowy');
    $lista_status_roszczenie = array('&nbsp;','Wyp³ata');

function module_update(){			
	global  $pageName;
	$result ='';

	
//	return ;
	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');	
	
	$check_js = '';
	$message = '';
    if (isset($change['ch_claims_wyplaty']) && $case_id > 0  ) {
        $edit_form_action   = getValue('edit_form_action') ;
        $edit_form_action_val   = intval(getValue('edit_form_action_val')) ;
        if ($edit_form_action == 'invoice_add'  && $edit_form_action_val > 0){

            $query = "SELECT * FROM coris_barclaycard_payment WHERE ID='$edit_form_action_val'";
            $ms = mysql_query($query);
            if (mysql_num_rows($ms) > 0 ){
                $row = mysql_fetch_array($ms);
                $payment_id =    $row['ID'];
                $ID_case=    $row['ID_case'];
                $ID_claims_details =    $row['ID_claims_details'];
                $amount =    $row['amount'];
                $payment_currency =    $row['payment_currency'];


                $case = new CorisCase($ID_case);

                //

                 $kurs = Finance::getKurs(date("Y-m-d"),1,$payment_currency);
                $table_id=$kurs['table_id'];


                $insertSQL1 = "INSERT INTO coris_assistance_cases_expenses SET 
                          case_id='".$ID_case."',                            
  					        contrahent_id='17241', 
  					        amount='$amount',  					      
  					        currency_id = '".$payment_currency."', 
  					        activity_id=71,  					         
  					        date = now(), 
  					        number_of_units=1,
  					        user_id = '".$_SESSION['user_id']."',  					        
  					        final=1,  					        
  					        client_amount='$amount',
  					        client_charge_id=5,
  					        coris_amount=0,
  					        coris_charge_id=0,  					       
                            table_id='$table_id',    
                            activity_date = curdate(),
                            active=1                                                    
  					        ";
                $Result1 = mysql_query($insertSQL1) or die($insertSQL1.'<br><br>'.mysql_error());
                $exp_id =mysql_insert_id();
                $insertSQL = "INSERT INTO coris_finances_invoices_in SET 
                          case_id='".$ID_case."', 
                            invoice_in_no='".mysql_escape_string($case->getPaxname()." ".$case->getPaxsurname())."', 
                            invoice_in_date=curdate(),
                            invoice_in_due_date=curdate(),
  					        paymenttype_id=3, 
  					        contrahent_id='17241', 
  					        amount='$amount',
  					        gross_amount='$amount',
  					        vatrate_id=0,
  					        vat_amount=0 , 
  					        currency_id = '".$payment_currency."', 
  					        activity_id=71,
  					        activity_note='Refundacja', 
  					        date = now(), 
  					        user_id = '".$_SESSION['user_id']."',
  					        expense_id='$exp_id',
  					        urgent=1,
  					        reduction=0 ,
  					        client_amount='$amount',
  					        client_charge_id=5,
  					        coris_amount=0,
  					        coris_charge_id=0,
  					        note='Wprowadzone bez dekretacji i bez dodawania do LP - refundacja',
  					        type=1,
                            ID_claim_source=6,
                            ID_claim_payment='".$payment_id."',
                            table_id='$table_id'
  					        ";


                //echo $insertSQL;
                $Result1 = mysql_query($insertSQL) or die($insertSQL.'<br><br>'.mysql_error());
                $inv_id =mysql_insert_id();

                if ($inv_id > 0 ){
                    $query = "UPDATE coris_barclaycard_payment SET 	ID_invoice_in='$inv_id', status=1  WHERE ID='$payment_id'";
                    $ms = mysql_query($query);

                }

            }
        }
    }else	if (isset($change['ch_claims_zgloszenia']) && $case_id > 0  ){
   		$res=check_update($case_id,'rezerwy_rezerwy');
		if ($res[0]){			   	
			$edit_form_action   = getValue('edit_form_action') ;							
			if ($edit_form_action == 'claims_add'){	
				
				$data_zgloszenia = getValue('data_zgloszenia');				
				$zgloszenie_uwagi = getValue('zgloszenie_uwagi');
								
				$zgloszenie_kwota = str_replace(',','.',getValue('zgloszenie_kwota'));
				$zgloszenie_waluta = str_replace(',','.',getValue('currency_id'));
				$zgloszenie_kwota_rws = str_replace(',','.',getValue('zgloszenie_kwota_rws'));
				$zgloszenie_opis_roszczenia = getValue('zgloszenie_opis_roszczenia');					
				$zgloszenie_currency_id = 'EUR';
				
				$cl = new BarclaycardClaim(0,$case_id,1);
				$cl->setAnnounce_date($data_zgloszenia);
				$cl->setNote($zgloszenie_uwagi);

				$cls = new BarclaycardClaimDetails(0, 0,1);
				$cls->setKwota_roszczenia($zgloszenie_kwota);
				$cls->setWaluta($zgloszenie_waluta);
				
				 if ( $zgloszenie_waluta == 'EUR' ){
					$cls->setKwota_rezerwa($zgloszenie_kwota); 	
				}else{
						$table_id = @addslashes(stripslashes(trim($_POST['zgloszenie_table_id'])));										
						$kurs_array = Finance::getKurs('','',$zgloszenie_waluta,$table_id,3);
						$kwota_pln= Finance::ev_round($zgloszenie_kwota *$kurs_array['rate'] / $kurs_array['multiplier'],2);
						$cls->setKwota_rezerwa($kwota_pln);												
				}
				
				
				$cls->setKwota_rws($zgloszenie_kwota_rws);
				$cls->setNote($zgloszenie_opis_roszczenia);			
				$cls->setRefundacja(1);
				$cls->setFranszyza(1);
				//$cls->setRefundacja_kwota(40);	
							
				$cl->addClaimDetails($cls);					
								
				$tmp = isset( $_POST['add_zgloszenie_opis_roszczenia'] ) ?  $_POST['add_zgloszenie_opis_roszczenia']  : null ;						
				if (is_array($tmp)){
					foreach ( $tmp As   $key => $val ){									
						$zgloszenie_opis_roszczenia = addslashes(stripslashes(trim($val)));
						$zgloszenie_kwota = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota'][$key]))));																								
						$zgloszenie_waluta = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_currency_id'][$key]))));																								
						//$zgloszenie_kwota_rws = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota_rws'][$key]))));																																																	
						$zgloszenie_currency_id= 'PLN';																			
						$add_zgloszenie_pozycja_usun = addslashes(stripslashes(trim($_POST['add_zgloszenie_pozycja_usun'][$key])));												
						$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);					
																					
						if ($add_zgloszenie_pozycja_usun != 1 && $zgloszenie_kwota>0){
								$cls = new BarclaycardClaimDetails(0, 0,1);
								$cls->setKwota_roszczenia($zgloszenie_kwota);
								$cls->setWaluta($zgloszenie_waluta);
								
								 if ( $zgloszenie_waluta == 'EUR' ){
										$cls->setKwota_rezerwa($zgloszenie_kwota); 	
								 }else{
								 		$table_id = @addslashes(stripslashes(trim($_POST['add_table_id'][$key])));																		 											
										$kurs_array = Finance::getKurs('','',$zgloszenie_waluta,$table_id,3);
										$kwota_pln= Finance::ev_round($zgloszenie_kwota *$kurs_array['rate'] / $kurs_array['multiplier'],2);
										$cls->setKwota_rezerwa($kwota_pln);
								 }
								
								
								$cls->setKwota_rws($zgloszenie_kwota_rws);
								$cls->setNote($zgloszenie_opis_roszczenia);
													
								$cl->addClaimDetails($cls);										
						}											
					}								
				}
				$cl->store();	

			/*if($zgloszenie_kwota_zaakceptowana > 0.00 || $zgloszenie_pozycja_status==4){							
									 if ( $zgloszenie_waluta == 'PLN' ){
										CaseInfo::setReserve($case_id,0,$zgloszenie_kwota_zaakceptowana,'PLN',$key);
								 }else{								 									 												
										$table_id = @addslashes(stripslashes(trim($_POST['roszczenie_table_id'][$key])));										
										$kurs_array = Finance::getKurs('','',$zgloszenie_waluta,$table_id);
																				
										$kwota_pln= Finance::ev_round($zgloszenie_kwota_zaakceptowana *$kurs_array['rate'] / $kurs_array['multiplier'],2);
										CaseInfo::setReserve($case_id,0,$kwota_pln,'PLN',$key);										
								 }
								}*/
			}else if ($edit_form_action == 'claims_save'){
					
				$claims_id = getValue('claims_id');																		
				$data_zgloszenia = getValue('data_zgloszenia');				
				$zgloszenie_uwagi = getValue('zgloszenie_uwagi');
				
				$cl = new BarclaycardClaim($claims_id,$case_id);
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
						$zgloszenie_waluta = str_replace(',','.',addslashes(stripslashes(trim($_POST['currency_id'][$key]))));
						$zgloszenie_kwota_zaakceptowana = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_kwota_zaakceptowana'][$key]))));
						$zgloszenie_wyplata_zaakceptowana = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_wyplata_zaakceptowana'][$key]))));
						
						$zgloszenie_pozycja_franszyza = intval ($_POST['zgloszenie_pozycja_franszyza'][$key]);
						$zgloszenie_pozycja_franszyza_wartosc = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_franszyza_wartosc'][$key]))));
						
						$zgloszenie_pozycja_inne_odl = intval ($_POST['zgloszenie_pozycja_inne_odl'][$key]);
						$zgloszenie_pozycja_inne_odl_wartosc = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_pozycja_inne_odl_wartosc'][$key]))));
						
						$zgloszenie_pozycja_refundacja = intval ($_POST['zgloszenie_pozycja_refundacja'][$key]);
						$zgloszenie_wyplata_refundacji = str_replace(',','.',addslashes(stripslashes(trim($_POST['zgloszenie_wyplata_refundacji'][$key]))));
						
						if ($zgloszenie_pozycja_status==4){ //odmowa
							$zgloszenie_kwota_zaakceptowana=0.0;	
						}

					 	if ( $zgloszenie_waluta == 'EUR' ){
										$pozycjaCL->setKwota_rezerwa($zgloszenie_kwota); 	
								 }else{
								 		$table_id = @addslashes(stripslashes(trim($_POST['roszczenie_table_id'][$key])));																		 											
										$kurs_array = Finance::getKurs('','',$zgloszenie_waluta,$table_id,3);
										$kwota_pln= Finance::ev_round($zgloszenie_kwota *$kurs_array['rate'] / $kurs_array['multiplier'],2);
										//echo '<br>rez: '.$kwota_pln;
										$pozycjaCL->setKwota_rezerwa($kwota_pln);
								 }

						if ($zgloszenie_pozycja_usun){
							$pozycjaCL->setDelete();
						}else{
							$pozycjaCL->setKwota_roszczenia($zgloszenie_kwota);
							
							$pozycjaCL->setWaluta($zgloszenie_waluta);
							
							//$pozycjaCL->setKwota_rezerwa($zgloszenie_kwota);
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
								$pozycjaCL->setStatus2_note($zgloszenie_akceptacja_uwagi);
								$pozycjaCL->setStatus2(3);
								$pozycjaCL->zmienStatus2();	
								// generate decyzja + platnosc
							//	$pozycjaCL->createDecision($case_id);
								
								
							}
													
							if ($zgloszenie_akceptacja==2){ // do poprawy
								$pozycjaCL->setStatus2_note($zgloszenie_akceptacja_uwagi);
								$pozycjaCL->setStatus2(2);
								$pozycjaCL->zmienStatus2();								
							}							
					}
					$cofnij_akceptacje   = @addslashes(stripslashes(trim($_POST['cofnij_akceptacje'][$key])));
					if ($cofnij_akceptacje==1 ){  // wycofanie akceptacji //&& BarclaycardCase::isAdmin()
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
						$zgloszenie_waluta = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_currency_id'][$key]))));																								
						$zgloszenie_kwota_rws = str_replace(',','.',addslashes(stripslashes(trim($_POST['add_zgloszenie_kwota_rws'][$key]))));																																																	
						//$zgloszenie_currency_id= 'PLN';																			
						$add_zgloszenie_pozycja_usun = addslashes(stripslashes(trim($_POST['add_zgloszenie_pozycja_usun'][$key])));												
						$zgloszenie_kwota = ev_round($zgloszenie_kwota,2);					
																					
						if ($add_zgloszenie_pozycja_usun != 1 ){//&& $zgloszenie_kwota>0
								$cls = new BarclaycardClaimDetails(0, $claims_id,1);
								$cls->setKwota_roszczenia($zgloszenie_kwota);
								$cls->setWaluta($zgloszenie_waluta);
								if ( $zgloszenie_waluta == 'EUR' ){
										$cls->setKwota_rezerwa($zgloszenie_kwota); 	
								 }else{
								 		$table_id = @addslashes(stripslashes(trim($_POST['add_table_id'][$key])));																		 											
										$kurs_array = Finance::getKurs('','',$zgloszenie_waluta,$table_id,3);
										$kwota_pln= Finance::ev_round($zgloszenie_kwota *$kurs_array['rate'] / $kurs_array['multiplier'],2);
										$cls->setKwota_rezerwa($kwota_pln);
								 }					  			
							//	$cls->setKwota_rezerwa($zgloszenie_kwota);
							//	$cls->setKwota_rws($zgloszenie_kwota_rws);
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
				$rezerwa_globalna_old = str_replace(',','.',getValue('rezerwa_globalna_old'));
				$rezerwa_globalna = str_replace(',','.',getValue('rezerwa_globalna'));
				$rezerwa_currency_id = getValue('rezerwa_currency_id');	
				$rezerwa_currency_id_old = getValue('rezerwa_currency_id_old');	
				$rezerwa_claims_id = getValue('rezerwa_claims_id');	
				if (getValue('case_rezerwa_globalna_zmiana') == 1)
						CaseInfo::setGLobalReserve($case_id,$rezerwa_globalna,$rezerwa_currency_id,0,$rezerwa_claims_id);
			
		}
	}else 	if (isset($change['ch_claims_decyzje']) && $case_id > 0  ){		
		$res=check_update($case_id,'rezerwy_decyzje');
		if ($res[0]){			   	
				
			$edit_form_action   = getValue('edit_form_action') ;		
			
			$decyzja_lista_pozycji = getValue('decyzja_lista_pozycji');
			
			if ($edit_form_action=='decissions_do_poprawy'){						
				if (is_array($decyzja_lista_pozycji)){
					foreach ($decyzja_lista_pozycji As $poz ){						
						$poz = intval($poz);
						if ($poz>0){
							$cls = new BarclaycardClaimDetails($poz);
							$cls->setStatus2(2);
							$cls->zmienStatus2();						
						}
					}
				}
			}else if ($edit_form_action=='decissions_gen'){							
				if (is_array($decyzja_lista_pozycji)){
						$lista = array();
						foreach ($decyzja_lista_pozycji As $poz ){						
							$poz = intval($poz);
							
							if ($poz>0){															
								$cls = new BarclaycardClaimDetails($poz);
								$lista[] = $cls;
							}								
						}
                    BarclaycardDecision::createDecision($case_id,$lista);
						foreach ($lista As $cls ){
								$cls->setStatus2(3);
								$cls->zmienStatus2();
						}
				}			
			}else if ($edit_form_action=='decissions_save'){			
					
					$decisions_id = getValue('decisions_id');
					$tekst1 = getValue('tekst1');
					$tekst2 = getValue('tekst2');
					$tekst3 = getValue('tekst3');
					$tekst4 = getValue('tekst4');
					$tekst5 = getValue('tekst5');
					$data_decyzji = getValue('data_decyzji');

                BarclaycardDecision::updateDecision($decisions_id, $tekst1, $tekst2, $tekst3, $tekst4, $tekst5, $data_decyzji);
				
			}
			
		}				
	}else 	if (isset($change['ch_claims_zgloszenia']) && $case_id > 0  ){		
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
						
						
						//$mysql_result = mysql_query($qi);
						if ($mysql_result){
											//$message .= "Udpate OK ".$query;							
										}else{
											$message .= "UPDATE Error: ".$qi."\n<br> ".mysql_error();				
						}
						if ($mysql_result){
								$cp_id = mysql_insert_id();
								 
								 $query = "SELECT coris_assistance_cases_claims_details.*,
							(SELECT nazwa FROM coris_signal_ryzyka_czastkowe WHERE coris_signal_ryzyka_czastkowe.ID=coris_assistance_cases_claims_details.ID_risk  ) As ryzyko, 
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.ID_user ) As user,
							(SELECT initials  FROM coris_users  WHERE coris_users.user_id =coris_assistance_cases_claims_details.status_ID_user ) As status_user
							FROM coris_assistance_cases_claims_details WHERE ID_claims='".$r['ID']."' AND ID IN (".implode(',',$roszczenie_pozycja).") ORDER BY ID";
							
							$mrd = mysql_query($query);
						//	$suma = 0.0;
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
									//	$mysql_result = mysql_query($qi);
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
							
														
								
						
						}	
						
					}					
			}else if ($edit_form_action=='save_status_pay'){
				
			}
		}
	}else 	if (isset($change['ch_claims_info']) && $case_id > 0  ){		
		$res=check_update($case_id,'claims_info');
		if ($res[0]){			   	
			$wyplata_nr_konta_bankowego  = getValue('wyplata_nr_konta_bankowego');
			$wyplata_swift  = getValue('wyplata_swift');
			$wyplata_nazwa_banku  = getValue('wyplata_nazwa_banku');
			
			
			$forma_wyplaty  = intval(getValue('forma_wyplaty'));
			
			$poszkodowany_info  = getValue('poszkodowany_info');

			$row_case_ann = BarclaycardCase::getCaseAnnounce($case_id);
				
			$update_roznice = array();	
			$update = array();	 													
										
			
			if ($row_case_ann['wyplata_nr_konta_bankowego'] != $wyplata_nr_konta_bankowego){
				$update_roznice['wyplata_nr_konta_bankowego'] = array('old' => $row_case_ann['wyplata_nr_konta_bankowego'], 'new' => $wyplata_nr_konta_bankowego);
				$update[] = "wyplata_nr_konta_bankowego = '".$wyplata_nr_konta_bankowego."'";										
			}							
								
			if ($row_case_ann['wyplata_nazwa_banku'] != $wyplata_nazwa_banku){
				$update_roznice['wyplata_nazwa_banku'] = array('old' => $row_case_ann['wyplata_nazwa_banku'], 'new' => $wyplata_nazwa_banku);
				$update[] = "wyplata_nazwa_banku = '".$wyplata_nazwa_banku."'";										
			}
			if ($row_case_ann['wyplata_swift'] != $wyplata_swift){
				$update_roznice['wyplata_swift'] = array('old' => $row_case_ann['wyplata_swift'], 'new' => $wyplata_swift);
				$update[] = "wyplata_swift = '".$wyplata_swift."'";
			}

			if ($row_case_ann['poszkodowany_info'] != $poszkodowany_info){
				$update_roznice['poszkodowany_info'] = array('old' => $row_case_ann['poszkodowany_info'], 'new' => $poszkodowany_info);
				$update[] = "poszkodowany_info = '".$poszkodowany_info."'";										
			}
								
			if ($row_case_ann['forma_wyplaty'] != $forma_wyplaty){
				$update_roznice['forma_wyplaty'] = array('old' => $row_case_ann['forma_wyplaty'], 'new' => $forma_wyplaty);
				$update[] = "forma_wyplaty = '".$forma_wyplaty."'";										
			}
								
			if (count($update) > 0 ){
										$query = "UPDATE coris_barclaycard_announce SET 
										".implode(', ', $update)."
										WHERE case_id='$case_id' LIMIT 1";
										$mr = mysql_query($query);
									
										if (!$mr ){																						
											echo  "<br>Update Error: $query <br><br> ".mysql_error();				
									 	}	
			}
			if (count($update_roznice) > 0 ){
				BarclaycardCase::rejestrujZmiany($case_id,$case_id,'Roszczenie','UPDATE',$update_roznice);
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
		
		$row_case_ann =BarclaycardCase::getCaseAnnounce($case_id);
		
		/*$query2 = "SELECT * FROM coris_assistance_cases_global_reserve  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_gr = mysql_fetch_array($mysql_result2);*/
		
if ($row_case_settings['client_id'] == 17241){
		$result .=  '<div style="width: 940px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  info($row_case,$row_case_settings,$row_case_ann);
	$result .=  '</div>';
	$result .=  '<div style="width: 940px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  roszczenia($row_case_settings,$row_case_ann);//,$row_case_gr
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
	<br><br><br><br><br><div align="center"><b>Tylko dla spraw TU Barclaycard</b></div>
	</div>
	';
	
}
			$result .=  '<div style="clear:both;"></div>';
	return $result;	
}


function info($row,$row2,$row_case_ann){		  
       $result='';	
	global $global_link,$change,$case_id;
	global $lista_status,$lista;
		
						
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_info" id="form_info" onSubmit="return check_form();">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>&nbsp;</b></font></small>&nbsp;
				</td>
				<td align="right">';

	if (isset($change['claims_info'])){
		
			if (check_claim_handler_user() || check_claim_admin()	){
	
				$result .= '<div style="float:rigth;padding:2px">								
				<input type="hidden" name="change[ch_claims_info]" id="change[ch_claims_info]" value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
				<input type="hidden" name="edit_form_action_param" id="edit_form_action_param" value="">
				<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
				<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
			}else{				
				$result .= '<div style="float:rigth;padding:2px">								
				<input type="hidden" name="err_change[ch_claims_info]" id="err_change[ch_claims_info]" value=1>
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
				<input type=hidden name=change[claims_info] value=1>
				<input type="hidden" name="edit_form" value="1">				
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';
		
	}
				
				$result .= '</td>	
			</tr>
			</table>';	      
//	$result .= 'TODO';
//	return $result;
	
	
if (isset($change['claims_info'])){
				$result .= '
			<script src="Scripts/iban.js" type="text/javascript" language="JavaScript"> </script>
			<script type="text/javascript" language="JavaScript">
		
			
			function check_form(){	
				if ($(\'wyplata_nr_konta_bankowego\').value != \'\')
					return  sprawdz_konto($(\'wyplata_nr_konta_bankowego\'));
				
			}
				function sprawdz_konto(obj){
						val = obj.value;
						val = val.replace(/ /g,\'\');
						iban = val.replace(/-/g,\'\');
						if (!IBANokay(\'\'+iban)){
      						alert(\'Bledny numer konta\');
      						obj.style.backgroundColor=\'red\';
      						return false;
      					}else{
							obj.style.backgroundColor=\'white\';
						//	format_iban(obj,iban);																					
							return true;
						}
      				}	
      			function format_iban(obj,iban){
      				s = iban.substr(0,2);
      			 	for (var i = 2; i < iban.length; ++i){
					    if ((i-2) % 4 == 0)
					      s += " ";
					    s += iban.charAt(i);
					  }
  					obj.value =  s;
  				}
			</script>	
		 	   <table cellpadding="5" cellspacing="0" border="1" align="center" width=70%>
		 	   <tr><td width="180"> <b>'.AS_CL_PAYMENTFORM.'</b> </td><td> 
		 	   				<input type="radio" name="forma_wyplaty" value="1" '.($row_case_ann['forma_wyplaty']==1 ? 'checked' : '' ).'> Przelew bankowy
		 	   				<br><input type="radio" name="forma_wyplaty" value="2" '.($row_case_ann['forma_wyplaty']==2 ? 'checked' : '' ).'> Przekaz pocztowy
		 	   			</td>
		 	   	<tr>
		 	   	<tr><td><b>'.AS_CL_WIRETR.':</b></td><td>		
		 	   <table cellpadding="5" cellspacing="0" border="1"  >
		       		<tr><td width="115" align="center"><b>'.AC_CL_NAMEBANK.':</b></td><td ><input type="text" name="wyplata_nazwa_banku" id="wyplata_nazwa_banku" value="'.$row_case_ann['wyplata_nazwa_banku'].'" size="50"> </td></tr>
		       		<tr><td width="115" align="center"><b>SWIFT:</b></td><td ><input type="text" name="wyplata_swift" id="wyplata_swift" value="'.$row_case_ann['wyplata_swift'].'" size="50"> </td></tr>
		      		<tr><td align="center"><b>'.AC_CL_ACCNO.'</b></td><td > <input type="text" name="wyplata_nr_konta_bankowego" id="wyplata_nr_konta_bankowego" value="'.$row_case_ann['wyplata_nr_konta_bankowego'].'" size="50"  onBlur="return sprawdz_konto(this)"> </td></tr>
		      	';				
			  $result .= '</table>
			  </td></tr>
			  
			
			  
			  </table><br>
			  ';
	}else{ // view					
			$result .= '
				<script type="text/javascript" language="JavaScript">
					function check_form(){			
						return  true;
					}
				</script>				
		
			   <table cellpadding="5" cellspacing="0" border="1" align="center" width=70%>
		 	   <tr><td width="180"> <b>'.AS_CL_PAYMENTFORM.'</b> </td><td> 
		 	   				'.($row_case_ann['forma_wyplaty']==1 ? 'Przelew bankowy' : '' ).'
		 	   				'.($row_case_ann['forma_wyplaty']==2 ? 'Przekaz pocztowy' : '' ).'		 	   				
		 	   			&nbsp;</td>
		 	   	<tr>
		 	   	<tr><td><b>'.AS_CL_WIRETR.':</b></td><td>		
		    <table cellpadding="5" cellspacing="0" border="1" >
		      <tr><td width="115" align="center"><b>'.AC_CL_NAMEBANK.':</b></td><td >'.$row_case_ann['wyplata_nazwa_banku'].' &nbsp;</td></tr>
		      <tr><td width="115" align="center"><b>SWIFT:</b></td><td >'.$row_case_ann['wyplata_swift'].' &nbsp;</td></tr>
		      <tr><td align="center"><b>'.AC_CL_ACCNO.'</b></td><td > '. $row_case_ann['wyplata_nr_konta_bankowego'].'&nbsp;</td></tr>';
		 $result .= '</table>
			  </td></tr>			  
				  
			  </table><br>
			  ';
	}
	
	$result .= '</br></form>';
	return $result;
	
}



function roszczenia($row,$row_case_ann,$row_case_gr){		  
       $result='';	
	global $global_link,$change,$case_id;
	global $lista_status,$lista;
		
	$rezerwa = CaseInfo::getReserve($case_id);

	$branch = CaseInfo::getCaseBarnch($case_id);
	$checkRezerwy = CaseInfo::checkGLobalReserve($case_id);	
	
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_roszczenia" id="form_roszczenia">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'.MENU_CLAIMS.'</b></font></small>&nbsp;
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
					// sprawdzenie czy kwota zaakceptowana nie jest wyzsza niz kwota roszczenia.
					
										
					for (i=0;i<lista_pozycji.length;i++){
							idt = lista_pozycji[i];
							zgloszenie_kwota = 1.0 * (document.getElementById(\'zgloszenie_kwota[\'+idt+\']\').value.replace(\',\',\'.\') ) ;
							zgloszenie_kwota_zaakceptowana = 1.0 * (document.getElementById(\'zgloszenie_kwota_zaakceptowana[\'+idt+\']\').value.replace(\',\',\'.\')); 
							
							zgloszenie_pozycja_status = document.getElementById(\'zgloszenie_pozycja_status[\'+idt+\']\').value; 
							
							//3 - poztywna
							//4 - odmowna
							
							if (zgloszenie_pozycja_status == 4 ){
									//document.getElementById(\'zgloszenie_kwota_zaakceptowana[\'+idt+\']\').value = \'0,00\';
									//zgloszenie_kwota_zaakceptowana = 0.0;
									if (zgloszenie_kwota_zaakceptowana > 0 ){
											alert(\'Przy decyzji odmownej proszê wyzerowaæ kwotê zaakceptowan±.\');
											return false;
									}
							}
							if (zgloszenie_pozycja_status == 3 && zgloszenie_kwota_zaakceptowana == 0.0){
									alert(\'B³±d kwota zaakceptowana zerowa.\');	
									document.getElementById(\'zgloszenie_kwota_zaakceptowana[\'+idt+\']\').focus();								
									return false;
							}
									
									
							if ( zgloszenie_kwota_zaakceptowana > zgloszenie_kwota ){																	
									alert(\'B³±d kwota zaakceptowana wiêksza ni¿ kwota roszczenia.\');									
									return false;
							}
							
							
					}

					
					lista = document.getElementsByName(\'add_zgloszenie_kwota[]\');
					for (i=0;i<lista.length;i++){
							if ( !(lista[i].value.replace(\',\',\'.\') >0 ) ){																	
									alert(\'Proszê podaæ kwotê roszczenia.\');
									lista[i].focus();
									return false;
							}
					}
					
			
			
				if (ayax_action==1){
					alert(\'Przeliczanie walut w trakcie, prosze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'claims_save\';	
					document.getElementById(\'form_roszczenia\').submit();					
				}
			}
						
			function dodaj_roszczenie(){						
					if ( !(document.getElementById(\'zgloszenie_kwota\').value.replace(\',\',\'.\') >0 ) ){																	
							alert(\'Proszê podaæ kwotê roszczenia.\');
							document.getElementById(\'zgloszenie_kwota\').focus();
							return false;
					}
					
					lista = document.getElementsByName(\'add_zgloszenie_kwota[]\');
					for (i=0;i<lista.length;i++){
							if ( !(lista[i].value.replace(\',\',\'.\') >0 ) ){																	
									alert(\'Proszê podaæ kwotê roszczenia.\');
									lista[i].focus();
									return false;
							}
					}
					
					document.getElementById(\'edit_form_action\').value=\'claims_add\';	
					document.getElementById(\'form_roszczenia\').submit();						
			}
			
			
			function dodaj_pozycje(obj){
				//	alert(document.getElementById(\'panel_pozycje\').innerHTML);
					pozycja = \'<table  cellpadding="1" cellspacing="0" border=1 width="100%"><tr  bgcolor="#BBBBBB"><tr><td align="center" width="34"><input type="checkbox" name="add_zgloszenie_pozycja_usun[]" id="add_zgloszenie_pozycja_usun[]" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td><td width="343"><textarea cols=50 rows="2" name="add_zgloszenie_opis_roszczenia[]" id="add_zgloszenie_opis_roszczenia[]"></textarea></td><td align="center" width="145"><input style="text-align:right;" type="text" size="10" value="0" name="add_zgloszenie_kwota[]" id="add_zgloszenie_kwota[]"  onChange="przelicz2(this);"> '.Finance::print_currency_all('add_currency_id[]','EUR','',' onChange="przelicz2(this.getPrevious());"').'<input type="hidden" name="add_table_id[]" id="add_table_id[]"  value="0" ><input type="hidden" name="add_pln_value[]" id="add_pln_value[]"  value="0"></td></tr></table>\';					
					//document.getElementById(\'panel_pozycje\').innerHTML +=  pozycja;			
				//	$(\'panel_pozycje\').set(\'html\',  $(\'panel_pozycje\').get(\'html\') +  pozycja);
					//$(\'panel_pozycje\').append( pozycja );
					 $(\'panel_pozycje\').adopt(new Element(\'div\', {html: pozycja}).getChildren())
									
			}					
</script>
			';
			$lista = BarclaycardCase::getClaims($case_id);		

if ($branch == 1 && $checkRezerwy ){			
				$result .= '	<table cellpadding="5" cellspacing="0" border="1" align="center" width=90%>
		<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>Rezerwa globalna:</b></td><td width="30%">
			<input type="hidden" name="case_rezerwa_globalna_zmiana" id="case_rezerwa_globalna_zmiana" value="0"> 
			<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.print_currency($row_case_gr['rezerwa_globalna']).'"  style="text-align: right;" size="15" maxlength="20"  readonly class="disabled" >		
			
			<input type="hidden" name="rezerwa_globalna_old" id="rezerwa_globalna_old" value="'.print_currency($row_case_gr['rezerwa_globalna']).'">
			<input type="hidden" name="rezerwa_claims_id" id="rezerwa_claims_id" value="0">';   
						
				$result .= wysw_currency2('rezerwa_currency_id',$row_case_gr['currency_id'],1,' class="disabled" ');
				$result .= '	<input type="hidden" name="rezerwa_currency_id_old" id="rezerwa_currency_id_old" value="'.print_currency($row_case_gr['currency_id']).'">';
				
			$result .= '
			</td></tr>			
			<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>Rezerwa do wykorzystania:</b></td><td width="30%">
				<input type="text" name="rezerwa_do_wykorzystania" id="rezerwa_do_wykorzystania" value="'.print_currency($row_case_gr['rezerwa_globalna'] - $rezerwa['rezerwa']).'"  style="text-align: right;" size="15" maxlength="20" readonly class="disabled" >		
				<input type="hidden" name="rezerwa_wykorzystana" id="rezerwa_wykorzystana" value="'.print_currency($row_case_gr['rezerwa_globalna']).'">';   
				$result .= wysw_currency2('rr_currency_id',$rezerwa['currency_id'],1,'  ');
			$result .= '
			</td></tr>						
		</table><br>';
}			
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      								
		      		<td width="15%" align="center"><b>'.AS_CASADD_DATZGLOSZ.'</b></td>						
					<td width="40%" align="center"><b>Note</b></td>					
					<td width="20%" align="center"><b>'.USER.'</b></td>					
					<td width="20%" align="center"><b>'.DATE_OFREGLASTMOD.'</b></td>
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
						  <td width="70" align="center"><b>Kwota zaakcept.</b></td>								  	
						  <td width="70" align="center"><b>Status</b></td>										
						  <td width="70" align="center"><b></b></td>										
						  <td width="70" align="center"><b>'.USER.'</b></td>										
							 </tr >';	
					
						  foreach ($pozycja->getClaimDetails() As $pozycjaCL  ){
						  				$result .= '<tr>';
						  					$result .= '<td >&nbsp;</td>';
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_roszczenia()).' '.$pozycjaCL->getWaluta().'</td>';																									
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_zaakceptowana()).' '.$pozycjaCL->getWaluta().'</td>';																										
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
				$cl = new BarclaycardClaim($edit_cl, $case_id);
					
				$result .= '
					<script>
					function przelicz(id,tryb_init){
						//kwota_start = 1.0 * $(\'zgloszenie_kwota_zaakceptowana[\'+id+\']\').value.replace(\',\',\'.\');
						kwota_start = 1.0 * $(\'zgloszenie_kwota[\'+id+\']\').value.replace(\',\',\'.\');
					//	alert(kwota_start);
						 roszczenie_sprawdz_rezerwe('.$case_id.',id,kwota_start,\'rezerwa_globalna\',\'rezerwa_do_wykorzystania\',$(\'zgloszenie_kwota[\'+id+\']\'),tryb_init);
						//sprawdz_rezerwe('.$case_id.',id ,\'rezerwa_globalna\',\'rezerwa_do_wykorzystania\',$(\'zgloszenie_kwota_zaakceptowana[\'+id+\']\'));
					}	

					function przelicz2(obj){
						kwota_start = 1.0 * obj.value.replace(\',\',\'.\');
						
						valuta = obj.getNext().value;
						
						 roszczenie_sprawdz_rezerwe2('.$case_id.',valuta,kwota_start,\'rezerwa_globalna\',\'rezerwa_do_wykorzystania\',obj );
					
					}
					</script>
									
					<input type="hidden" name="claims_id" value="'.$edit_cl.'">
					<table align="center" cellpadding="1" cellspacing="0" border="1"  width=95%><tr bgcolor="#BBBBBB"><td width="120" rowspan=4 valign="top">
						<b>Edycja zg³oszenia:</b> </td><td>												
						<b>'.DATE.':</b> <input type="text" size="10" name="data_zgloszenia" id="data_zgloszenia" value="'.$cl->getAnnounce_date().'" ></td></tr>
						
						<tr bgcolor="#BBBBBB"><td><b>'.MENU_CLAIMS.'</b>:';
						
					$result .= '<table  cellpadding="1" cellspacing="0" border=1 width="100%">
						<tr  bgcolor="#BBBBBB">						
							<td align="center"><b>'.AS_CASD_DEL.'</b></td>						
							<td align="center"><b>'.AC_CL_DESCCLAIMS.'</b></td>						
							<td align="center"><b>'.AC_CL_VALCLAIMS.'</b></td>

						</tr>';
						$lista_pozycji = array();
			  			foreach ($cl->getClaimDetails() As $pozycjaCL  ){
			  						$dis= '';
			  						if ($pozycjaCL->getStatus2()==0 || $pozycjaCL->getStatus2()==2){
			  								
			  							
			  						}else{
			  							$dis= ' disabled class="disabled" ';
			  							
			  						}				
										$result .= '<tr>										
											<td align="center" rowspan="3"><input type="checkbox" '.$dis.' name="zgloszenie_pozycja_usun['.$pozycjaCL->getID().']" id="zgloszenie_pozycja_usun['.$pozycjaCL->getID().']" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td>
											<td><textarea '.$dis.' cols=50 rows="2" name="zgloszenie_opis_roszczenia['.$pozycjaCL->getID().']" id="zgloszenie_opis_roszczenia['.$pozycjaCL->getID().']">'.$pozycjaCL->getNote().'</textarea></td>
											<td align="center">
												<input  type="hidden" value="'.print_currency($pozycjaCL->getKwota_roszczenia()).'" name="zgloszenie_rezerwa_org['.$pozycjaCL->getID().']" id="zgloszenie_rezerwa_org['.$pozycjaCL->getID().']"   value="0.0">
												<input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getKwota_roszczenia()).'" name="zgloszenie_kwota['.$pozycjaCL->getID().']" id="zgloszenie_kwota['.$pozycjaCL->getID().']"  onChange="przelicz('.$pozycjaCL->getID().');" > '.print_currency_all('currency_id['.$pozycjaCL->getID().']',$pozycjaCL->getWaluta(),'',$dis).'											
												<input type="hidden" name="roszczenie_table_id['.$pozycjaCL->getID().']" id="roszczenie_table_id['.$pozycjaCL->getID().']" value="0">														
												<input type="hidden" name="roszczenie_pln_value['.$pozycjaCL->getID().']" id="roszczenie_pln_value['.$pozycjaCL->getID().']"  value="'.$pozycjaCL->getKwota_rezerwa().'">											
											</td>											
										</tr>
										<tr bgcolor="#888888">
											<td  align="right"><b>Kwota zaakceptowana: </b></td>
											<td align="center" ><input '.$dis.' style="text-align:right;" type="text" size="10" value="'.print_currency($pozycjaCL->getKwota_zaakceptowana()).'" name="zgloszenie_kwota_zaakceptowana['.$pozycjaCL->getID().']" id="zgloszenie_kwota_zaakceptowana['.$pozycjaCL->getID().']"  > </td>
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
													$result .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Wy¶lij do decyzji:</b> <input type="checkbox" name="wyslij_do_akceptacji['.$pozycjaCL->getID().']" id="wyslij_do_akceptacji['.$pozycjaCL->getID().']" value="1" onClick="return sprawdz_status('.$pozycjaCL->getID().');">';												
									}
								$result .' </td></tr>';																								
								$result .= '<tr bgcolor="#BBBBBB"><td colspan=4><b>'.COMMENTS.':</b><br> <textarea cols="80" rows="3" id="zgloszenie_uwagi" name="zgloszenie_uwagi">'.$cl->getNote().'</textarea>';						
						$result .= '</td></tr>';
						
			  				if ($pozycjaCL->getStatus2()==2 || $pozycjaCL->getStatus2()==3 ){
									$result .= '<tr bgcolor="#ff9999">';
										$result .= '<td colspan="4">';
														$result .= '<div align="center">Data: '.$pozycjaCL->getStatus2_date().', '.Application::getUserName( $pozycjaCL->getStatus2_userID() );																																		
														$result .= '<hr><div style="width:500px;" align="left"><i>'.$pozycjaCL->getStatus2_note().'&nbsp;</i></div>';								
													if ( $pozycjaCL->getStatus2()==3 ){ //&& AllianzCase::isAdmin()
															$result .= '<hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:black;">Cofnij akceptacje:</b> <input type="checkbox" name="cofnij_akceptacje['.$pozycjaCL->getID().']" id="cofnij_akceptacje['.$pozycjaCL->getID().']" value="1" onClick="return confirm(\'Czy napewno? Cofniêcie akceptacji spowoduje usuniêcie z systemu decyzji oraz wyp³aty!!!\');">';
													}																																																														
										$result .= '</td>';										
									$result .= '</tr>';
								}
								$result .= '
								<script>
									 przelicz('.$pozycjaCL->getID().',1);
								</script>
								';
								$lista_pozycji[] = '"'.$pozycjaCL->getID().'"';
						  }

						  $result .= '<tr><td colspan="4"><div id="panel_pozycje"></div></td></tr>';
						  $result .= '
								<script>
									 lista_pozycji = new Array('.implode(',',$lista_pozycji).');
								</script>';
									
						  								  
						$result .= '</table>';
						
						
						$result .= '
						<script>
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
						$result .= '<div align="left"><input type="button" value="Dodaj pozycjê"  onClick="dodaj_pozycje(this);">&nbsp;&nbsp;&nbsp;</div><br>';						
						$result .= '
						
						<div align="right" style="padding:10px"><input type="button" value="'.BUTT_SAVE.'"  id="przycisk_save" onClick="zapisz_roszczenie();">&nbsp;&nbsp;&nbsp;</div>						
						';						
						$result .= '</td></tr>						
						<tr bgcolor="#AAAAAA"><td colspan="2" align="right"></td></tr>';
						
			
								
						$result .= '</table>';
			
				
			}	else { 		// nowe zg³oszenie
				
					$result .= '
					<script>
					function przelicz2(obj){
						kwota_start = 1.0 * obj.value.replace(\',\',\'.\');
						
						valuta = obj.getNext().value;
						
						 roszczenie_sprawdz_rezerwe2('.$case_id.',valuta,kwota_start,\'rezerwa_globalna\',\'rezerwa_do_wykorzystania\',obj );
					
					}
					</script>
					
					';
						$result .= '<table align="center"  cellpadding="1" cellspacing="0" border="1"  width=80%><tr bgcolor="#BBBBBB"><td width="120" rowspan=4 valign="top">
						<b>Nowe zg³oszenie:</b> </td><td>												
						<b>'.	AS_CASADD_DATZGLOSZ.':</b> <input type="text" size="10" name="data_zgloszenia" id="data_zgloszenia" value="'.date("Y-m-d").'" ></td></tr>';													
						$result .= '<tr bgcolor="#DDDDDD"><td><b>'.MENU_CLAIMS.'</b>:
						<table  cellpadding="1" cellspacing="0" border=1 width="100%">
						<tr  bgcolor="#BBBBBB">						
							<td align="center"><b>'.AS_CASD_DEL.'</b></td>						
							<td align="center"><b>'.AC_CL_DESCCLAIMS.'</b></td>						
							<td align="center"><b>'.AC_CL_VALCLAIMS .'</b></td>							
						</tr>
						<tr>
							<td align="center"><input type="checkbox" name="zgloszenie_pozycja_usun" id="zgloszenie_pozycja_usun" value="1" title="Usuñ pozycjê" style="background-color:#BBBBBB;"></td>
							<td><textarea cols=50 rows="2" name="zgloszenie_opis_roszczenia" id="zgloszenie_opis_roszczenia"></textarea></td>
							<td align="center">
							
								<input style="text-align:right;" type="text" size="10" value="0" name="zgloszenie_kwota" id="zgloszenie_kwota"  onChange="przelicz2(this);" > 
								'.print_currency_all('currency_id','EUR','',' onChange="przelicz2(this.getPrevious());"').'

								
								<input type="hidden" name="zgloszenie_table_id" id="zgloszenie_table_id"  value="">		
								<input type="hidden" name="roszczenie_pln_value" id="roszczenie_pln_value"  value="">		
								
							</td>							
						</tr>					
								
						</table>
						<div id="panel_pozycje"></div>	
							
												</td></tr>
						';
						$result .= '<tr bgcolor="#BBBBBB"><td><b>'.COMMENTS.':</b> <textarea cols="80" rows="3" id="zgloszenie_uwagi" name="zgloszenie_uwagi"></textarea>';						
						$result .= '</td></tr>';
						
						
						$result .= '
						<tr><td>
											
						<div style="float:left;padding:15px;"><input type="button" value="'.AS_CL_ADDPOS.'"  onClick="dodaj_pozycje(this);"></div>
						<div style="float:right;padding:15px;"><input type="button" value="'.BUTT_SAVE.'" id="przycisk_save" onClick="dodaj_roszczenie();" ></div>

						</td></tr>
						
																
						</table><br>';									
			}

	}else{ // view
if ($branch == 1 && $checkRezerwy ){		
		$result .= '	<table cellpadding="5" cellspacing="0" border="1" align="center" width=90%>
		<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>Rezerwa globalna:</b></td><td width="30%">
			<input type="hidden" name="rezerwa_globalna_lock" id="rezerwa_globalna_lock" value="1"> 
			<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.print_currency($row_case_gr['rezerwa_globalna']).'"  style="text-align: right;" size="15" maxlength="20"  readonly class="disabled" >		
			
			<input type="hidden" name="rezerwa_globalna_old" id="rezerwa_globalna_old" value="'.print_currency($row_case_gr['rezerwa_globalna']).'">
			<input type="hidden" name="rezerwa_claims_id" id="rezerwa_claims_id" value="0">';   
						
				$result .= wysw_currency2('rezerwa_currency_id',$row_case_gr['currency_id'],1,' class="disabled" ');
				$result .= '	<input type="hidden" name="rezerwa_currency_id_old" id="rezerwa_currency_id_old" value="'.print_currency($row_case_gr['currency_id']).'">';
				
			$result .= '
			</td></tr>			
			<tr bgcolor="#AAAAAA"><td width="70%" align="right"><b>Rezerwa do wykorzystania:</b></td><td width="30%">
				<input type="text" name="rezerwa_do_wykorzystania" id="rezerwa_do_wykorzystania" value="'.print_currency($row_case_gr['rezerwa_globalna'] - $rezerwa['rezerwa']).'"  style="text-align: right;" size="15" maxlength="20" readonly class="disabled" >		
				<input type="hidden" name="rezerwa_wykorzystana" id="rezerwa_wykorzystana" value="'.print_currency($row_case_gr['rezerwa_globalna']).'">';   
				$result .= wysw_currency2('rr_currency_id',$rezerwa['currency_id'],1,'  ');
			$result .= '
			</td></tr>
		</table><br>';
}			
					$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="15%" align="center"><b>'.	AS_CASADD_DATZGLOSZ.'</b></td>						
					<td width="45%" align="center"><b>Note</b></td>					
					<td width="20%" align="center"><b>'.USER.'</b></td>					
					<td width="20%" align="center"><b>'.DATE_OFREGLASTMOD.'</b></td>
			  </tr><tr>';
				
			$lista = BarclaycardCase::getClaims($case_id);					
						
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
						  <td width="70" align="center"><b>'.AS_CL_CLAIM.'</td>						  						  							
						  <td width="90" align="center"><b>Kwota zaakcept.</b></td>		
						  <td width="70" align="center"><b>Status</b></td>										
						  <td width="70" align="center"><b></b></td>										
						  <td width="70" align="center"><b>'.USER.'</b></td>										
							 </tr >';						
						  foreach ($pozycja->getClaimDetails() As $pozycjaCL  ){
						  				$result .= '<tr>';
						  					$result .= '<td >&nbsp;</td>';
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_roszczenia()).' '.$pozycjaCL->getWaluta().'</td>';													
													
											$result .= '<td align="right">'.print_currency($pozycjaCL->getKwota_zaakceptowana()).' '.$pozycjaCL->getWaluta().'</td>';													
																		
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
		
						
	$result .= '<form method="POST" style="padding:0px;margin:0px" name="form_decyzje" id="form_decyzje" onSubmit="return check_form_decyzje();">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>'.AS_CASD_DEC.'</b></font></small>&nbsp;
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
					check_form_decyzje();
				if (ayax_action==1){
					alert(\'Prosze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'decissions_save\';	
					document.getElementById(\'form_decyzje\').submit();					
				}
			}
			
			
			function generuj_decyzje(){	
				if (ayax_action==1){
					alert(\'Prosze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'decissions_gen\';	
					document.getElementById(\'form_decyzje\').submit();					
				}
			}
						
			
			function do_poprawy(){	
				if (ayax_action==1){
					alert(\'Prosze ponownie spróbowaæ za chwilê. \')
				}else{		
					document.getElementById(\'edit_form_action\').value=\'decissions_do_poprawy\';	
					document.getElementById(\'form_decyzje\').submit();					
				}
			}

			
			function check_form_decyzje(){					
				if ( $(\'tekst3\') && $(\'suma_ubepieczenie_div\') )
							$(\'tekst3\').value=$(\'suma_ubepieczenie_div\').innerHTML;
				
			}
			
			
			function probny_wydruk(){
					lista = document.getElementsByName(\'decyzja_lista_pozycji[]\');
				
					pozycje=\'\';
					for (i=0;i<lista.length;i++){
						if (lista[i].checked){
							pozycje += lista[i].value + \',\';
						}
					}
					if (pozycje == \'\'){
						alert(\'Zaznacz pozycje\');
						return;						
					}			
					popup(\'AS_cases_barclaycard_claims_print_test.php?lista=\'+pozycje,\'wydruk\',1000,800);
						
			}	
</script>
			';
			$lista = BarclaycardCase::getDecisions($case_id);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      								
		      		<td width="15%" align="center"><b>Status</b></td>						
		      		<td width="15%" align="center"><b>'.AS_CASES_DEC.'</b></td>						
		      		<td width="15%" align="center"><b>'.DATE.'</b></td>						
					<td width="15%" align="center"><b>'.AC_CL_TOPAID.'</b></td>									
					<td width="20%" align="center"><b>'.USER.'</b></td>					
					<td width="20%" align="center"><b>'.USER_APPROVEDBY.'</b></td>										
					<td width="5%" align="center">&nbsp;</td>
			  </tr><tr>';						
						
			foreach ($lista As $pozycja  ){
				$cl = $pozycja;				 			
				$result .= '<tr bgcolor="#BBBBBB">';
					
					$result .= '<td align="center">'.$cl->getStatusName().'</td>';
					$result .= '<td align="center">'.$cl->getTypeName().'</td>';													
					$result .= '<td>'.$cl->getDate().'</td>';													
					$result .= '<td align="right">'.print_currency($cl->getPayment_amount()).' '.$cl->getCurrency_id().'</td>';																								
					$result .= '<td>'.Application::getUserName($cl->getID_user()).'</td>';												
					$result .= '<td>'.Application::getUserName($cl->getAccept_ID_user()).' &nbsp;</td>';		
					$result .= '<td align="center"><a href="javascript:edycja_decyzji('.$cl->getID().')">Edycja</a>&nbsp;</td>';																																
				$result .= '</tr>';	  
		 		
				if (getValue('edit_form_action') == 'decissions_edit' && getValue('edit_form_action_param') == $cl->getID()  ){
						$result .= '<tr><td>&nbsp;</td>
							<td colspan="7">';
						if ($cl->GetStatus() == 0){
								$txt1 =	$cl->generateTxt(1);
								$txt2 = $cl->generateTxt(2);
								$txt3 = $cl->generateTxt('sumy_ubezpieczenia');
								$adresat = $cl->generateTxt('adresat');
								$beneficjent = $cl->generateTxt('beneficjent');
						}else{							
							$txt1 = $cl->getText1();
							$txt2 = $cl->getText2();
							$txt3 = $cl->getText3();
							$adresat = $cl->getText5();
							$beneficjent = $cl->getText4();
						}
						$result  .= '<hr>';
						$result .= '
						<input type="hidden" name="decisions_id" value="'.$cl->getID().'">
						<table align="center" cellpadding="1" cellspacing="0" border="1"  width=95%>
						<tr bgcolor="#BBBBBB"><td width="10" rowspan=1 valign="top">&nbsp;</td>																			
							<td><b>'.AS_CASES_DEC.':</b> '.$cl->getTypeName().'&nbsp;&nbsp;&nbsp; <b>Data decyzji:</b> <input type="text" size="11" name="data_decyzji" value="'.$cl->getDate().'"> </td>
						</tr>	
						<tr bgcolor="#BBBBBB"><td width="10" rowspan=1 valign="top">&nbsp;</td>																			
							<td><b>'.MENU_CLAIMS.':</b>'; 
								$lista_pozycji = $cl->getList_details();	
									
								$result .= '<br><br><table  width="100%" cellpadding="1" cellspacing="0" border="1" bgcolor="#999999">
						<tr>		  
								<td width="70" align="center"><b>Status</td>						  						  							
						  		<td align="center"><b>'.AS_CL_CLAIM.'</b></td>		
						  		<td width="90" align="center"><b>Kwota zaakcept.</b></td>		
						  		<td width="90" align="center"><b>Kwota w EUR</b></td>		
						  		<td width="70" align="center"><b>Kurs</b></td>
						  </tr>
						  ';
								
								foreach ($lista_pozycji As $poz){
									$result .= '<tr>';
										$result .= '<td align="left"> '.($poz['type']==3? 'Pozytywna' : 'Odmowa' ).'</td>';

										$cls = new BarclaycardClaimDetails($poz['ID_claims_details']);
										
										$result .= '<td align="left"> '.$cls->getNote().'  </td>';	
										$result .= '<td align="right"> '.Finance::print_currency($poz['amount']).' '.$poz['currency_id'].'</td>';	
										$result .= '<td align="right"> '.Finance::print_currency($poz['payment_amount']).' EUR</td>';
										$result .= '<td align="right"> '.str_replace('.', ',',  $poz['rate'] / $poz['multiplier']).'</td>';	
									$result .= '</tr>';
								}
								$result .= '</table><br>';	
							$result .= '</td>
						</tr>	
						<tr bgcolor="#BBBBBB"><td width="10" rowspan=3 valign="top">&nbsp;</td>																			
							<td>														
							<div style="width:340px;float:left;"><b>Adresat decyzji:</b><br> <textarea cols="50" rows="3" id="tekst5" name="tekst5">'.$adresat.'</textarea>
							</div>
							<div style="width:340px;float:right;"><b>Beneficjent :</b><br> <textarea cols="50" rows="3" id="tekst4" name="tekst4">'.$beneficjent.'</textarea>
							</div>
							<br><br>
							<b>'.AS_CASES_DEC.' - nag³ówek:</b><br> <textarea cols="110" rows="3" id="tekst1" name="tekst1">'.$txt1.'</textarea>
							<br><br>
							<b>'.AS_CASES_DEC.' - uzasadnienie:</b><br> <textarea cols="110" rows="6" id="tekst2" name="tekst2">'.$txt2.'</textarea>							
							
							<br><br>
							<b>Operat - sumy ubezpieczenia:</b><br> 
								<!-- <textarea cols="80" rows="10" id="tekst3" name="tekst3" style="font-face:Tahoma;font-size:10px;">'.$txt3.'</textarea> -->
								<div id="suma_ubepieczenie_div" contentEditable="true" style="background-color:#FFFFFF;width:300px;height:195px">'.stripslashes($txt3).'</div>
								<input type="hidden" id="tekst3" name="tekst3" value="">														
							</td></tr>
							';
							
							$result .= '
							<tr bgcolor="#BBBBBB"><td>';										
							$result .= '<div align="right" style="padding:10px"><input type="button" value="'.BUTT_SAVE.'"  onClick="zapisz_decyzje();">&nbsp;&nbsp;&nbsp;</div>';
							
							$result .= '</td></tr>												
							</table>';
						$result .= '</td></tr>';				
				}
						  
					
			}					
						
				 $result .= '</table>';


				$result .= '<br><div align="center"><b>Lista zaakceptowanych roszczeñ</b></div><br>';
				$lista = BarclaycardCase::getClaimsDetailsLista($case_id,'status2=1');		
						$result .= '
		   			 <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      				<tr bgcolor="#999999">
		      					<td width="5%" align="center">&nbsp;</td>		      								
		      					<td width="30%" align="center"><b>'.AS_CASADD_OPIS.'</b></td>		      								
					      		<td width="12%" align="center"><b>'.AS_CL_CLAIM.'</b></td>						
					      		<td width="12%" align="center"><b>Kwota zaakceptowana</b></td>						
					      		<td width="16%" align="center"><b>'.AS_CASES_DEC.'</b></td>						
					      		<td width="10%" align="center"><b>'.DATE.'</b></td>																							
								<td width="15%" align="center"><b>'.USER.'</b></td>																							
							
						  </tr><tr>';						
			if (count($lista) > 0 ){			
				foreach ($lista As $cls  ){
							 			
					$result .= '<tr bgcolor="#BBBBBB">';
						$result .= '<td align="center"><input type="checkbox" name="decyzja_lista_pozycji[]" value="'.$cls->getID().'"></td>';
						$result .= '<td align="left"> '.$cls->getNote().'</td>';																		
						$result .= '<td align="right">'.print_currency($cls->getKwota_roszczenia()).' '.$cls->getWaluta().'</td>';													
						$result .= '<td align="right">'.print_currency($cls->getKwota_zaakceptowana()).' '.$cls->getWaluta().'</td>';													
						$result .= '<td align="right">'.$cls->getStatusName().'</td>';																									
						$result .= '<td align="center">'.$cls->getStatus_date().'</td>';												
						$result .= '<td align="center">'.Application::getUserName($cls->getStatus_userID()).'</td>';												
					$result .= '</tr>';	  
				}	
				$result .= '<tr bgcolor="#BBBBBB"><td colspan="7">&nbsp;&nbsp;&nbsp;<img src="img/arrow_ltr.png">  
				<!--<a href="javascript:;" onClick="">Zaznacz wszystkie</a> / <a href="javascript:;" onClick="">Usuñ zaznaczenie</a> -->
				 <b>Zaznaczone:</b> <a href="javascript:;" onClick="generuj_decyzje();">Generuj decyzje</a>  / <a href="javascript:;" onClick="do_poprawy();">Cofnij do poprawy</a>
				 / <a href="javascript:;" onClick="probny_wydruk();">Próbny wydruk</a>
				 </td></tr>';					
			}else{

			}				
				$result .= '</table>';
				

	}else{ // view
					
		  $lista = BarclaycardCase::getDecisions($case_id);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      								
		      		<td width="12%" align="center"><b>Status</b></td>						
		      		<td width="12%" align="center"><b>'.AS_CASES_DEC.'</b></td>						
		      		<td width="10%" align="center"><b>'.DATE.'</b></td>						
					<td width="10%" align="center"><b>'.AC_CL_TOPAID.'</b></td>									
					<td width="15%" align="center"><b>'.USER.'</b></td>					
					<td width="15%" align="center"><b>'.USER_APPROVEDBY.'</b></td>										
					<td width="5%" align="center">&nbsp;</td>
			  </tr><tr>';						
						
			foreach ($lista As $pozycja  ){
				$cl = $pozycja;				 			
				$result .= '<tr bgcolor="#BBBBBB">';
					
					$result .= '<td align="center">'.$cl->getStatusName().' '.($cl->getStatus()==1 ? '<a href="AS_cases_barclaycard_claims_print.php?id='.$cl->getId().'" target="_blank"><img border="0" src="img/print.gif"></a>' : '' ).'</td>';
					$result .= '<td align="center">'.$cl->getTypeName().'</td>';													
					$result .= '<td>'.$cl->getDate().'</td>';																														
					$result .= '<td align="right">'.print_currency($cl->getPayment_amount()).' '.$cl->getCurrency_id().'</td>';																									
					$result .= '<td>'.Application::getUserName($cl->getID_user()).'</td>';												
					$result .= '<td>'.Application::getUserName($cl->getAccept_ID_user()).' &nbsp;</td>';	
					$result .= '<td align="center">&nbsp;</td>';																												
				$result .= '</tr>';	  
		 		
						  
					
			}					
						
				 $result .= '</table>';
		
	}
	
	$result .= '</br></form>';
	return $result;
	
}
/*
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
	
			 $query = "SELECT * FROM coris_barclaycard_payment WHERE ID_case= '$case_id'";
			 $mysql_result = mysql_query($query);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="5%" align="center">&nbsp;</td>
		      		<td width="10%" align="center"><b>'.DATE.'</b></td>								
		      		<td width="12%" align="center"><b>Zap³acone</b></td>								      							
		      						
					<td width="10%" align="center"><b>'.AC_CL_TOPAID.'</b></td>																							
			  </tr><tr>';						
			while($row = mysql_fetch_array($mysql_result)){								 		
				$result .= '<tr bgcolor="#BBBBBB">';
					$result .= '<td align="center">&nbsp;</td>';
					$result .= '<td align="center">'.$row['date'].'</td>';	
					$result .= '<td align="center">'.($row['payment']==1 ? 'TAK' : 'NIE').'</td>';													
																											
					$result .= '<td align="right">'.print_currency($row['amount']).' EUR</td>';
																																	
				$result .= '</tr>';	  		 								  				
			}
		$result .= '</table><br>';
	}else{
			 $query = "SELECT * FROM coris_barclaycard_payment WHERE ID_case= '$case_id'";
			 $mysql_result = mysql_query($query);		
						$result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="5%" align="center">&nbsp;</td>
		      		<td width="10%" align="center"><b>'.DATE.'</b></td>								
		      		<td width="12%" align="center"><b>Zap³acone</b></td>								      							
		      						
					<td width="10%" align="center"><b>'.AC_CL_TOPAID.'</b></td>																							
			  </tr><tr>';						
			while($row = mysql_fetch_array($mysql_result)){								 		
				$result .= '<tr bgcolor="#BBBBBB">';
					$result .= '<td align="center">&nbsp;</td>';
					$result .= '<td align="center">'.$row['date'].'</td>';	
					$result .= '<td align="center">'.($row['payment']==1 ? 'TAK' : 'NIE').'</td>';													
																											
					$result .= '<td align="right">'.print_currency($row['amount']).' EUR</td>';
				$result .= '</tr>';	  		 								  				
		}
		$result .= '</table><br>';
	}	
	$result .= '</form>';
	return $result;	
}	*/

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

            $result .= '<div style="float:right;padding:2px">								
					<input type=hidden name="change[ch_claims_wyplaty]" id="change[ch_claims_wyplaty]" value="1">
					<input type="hidden" name="edit_form" value="1">
					<input type="hidden" name="edit_form_action" id="edit_form_action" value="">
					<input type="hidden" name="edit_form_action_val" id="edit_form_action_val" value="">
					
					<input type="image" src="img/act.gif" title="Zatwierd¼" border="0" style="background-color:transparent;">							&nbsp;
					<a href="'.$global_link.'"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
					</div>';
        }else{
            $result .= '<div style="float:right;padding:2px">								
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
        $result .= '<div style="float:right;padding:3px">								
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


        $result .= '
			<script>
			ayax_action=0;
			function utworz_fakture(id){
				if (id>0){
					document.getElementById(\'edit_form_action\').value=\'invoice_add\';	
					document.getElementById(\'edit_form_action_val\').value=id;	
					
				//	document.getElementById(\'change[ch_claims_wyplaty]\').name=\'change[claims_wyplaty]\';	
					
					document.getElementById(\'form_wyplaty\').submit();		
				
				}
			}
		</script>';

        $query = "SELECT * FROM coris_barclaycard_payment WHERE ID_case= '$case_id'";
        $mysql_result = mysql_query($query);
        $result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="5%" align="center">&nbsp;</td>
		      		<td width="10%" align="center"><b>'.DATE.'</b></td>								
		      		<td width="12%" align="center"><b>Zap³acone</b></td>								      							
		      						
					<td width="10%" align="center"><b>'.AC_CL_TOPAID.'</b></td>																							
			  </tr><tr>';
        while($row = mysql_fetch_array($mysql_result)){
            $result .= '<tr bgcolor="#BBBBBB">';
            $result .= '<td align="center">'.($row['status']==0  ? '<input type="button"  style="margin-top: 5px;margin-bottom: 5px;" value="Utwórz fakture"  onClick="utworz_fakture('.$row['ID'].');"' : '<input value=">" style="width: 20px" onclick="MM_openBrWindow(\'../finances/FK_invoices_in_details.php?nreload=1&invoice_in_id='.$row['ID_invoice_in'].'\',\'\',\'scrollbars=yes,resizable=yes,width=900,height=760,left=20,top=20\');" type="button">').'</td>';
            $result .= '<td align="center">'.$row['date'].'</td>';
            $result .= '<td align="center">'.($row['payment']==1 ? 'TAK' : 'NIE').'</td>';

            $result .= '<td align="right">'.print_currency($row['amount']).' '.$row['payment_currency'].'</td>';

            $result .= '</tr>';
        }
        $result .= '</table><br>';
    }else{
        $query = "SELECT * FROM coris_barclaycard_payment WHERE ID_case= '$case_id'";
        $mysql_result = mysql_query($query);
        $result .= '
		    <table cellpadding="1" cellspacing="0" border="1" align="center" width=90%>
		      <tr bgcolor="#999999">
		      		<td width="5%" align="center">&nbsp;</td>
		      		<td width="10%" align="center"><b>'.DATE.'</b></td>								
		      		<td width="12%" align="center"><b>Zap³acone</b></td>								      							
		      						
					<td width="10%" align="center"><b>'.AC_CL_TOPAID.'</b></td>																							
			  </tr><tr>';
        while($row = mysql_fetch_array($mysql_result)){
            $result .= '<tr bgcolor="#BBBBBB">';
            $result .= '<td align="center">'.($row['status'] > 0 ? '<input value=">" style="width: 20px" onclick="MM_openBrWindow(\'../finances/FK_invoices_in_details.php?nreload=1&invoice_in_id='.$row['ID_invoice_in'].'\',\'\',\'scrollbars=yes,resizable=yes,width=900,height=760,left=20,top=20\');" type="button">' : '').'</td>';
            $result .= '<td align="center">'.$row['date'].'</td>';
            $result .= '<td align="center">'.($row['status']>0 && check_invoice_payment($row['ID_invoice_in']) ? 'TAK' : 'NIE').'</td>';

            $result .= '<td align="right">'.print_currency($row['amount']).' '.$row['payment_currency'].'</td>';
            $result .= '</tr>';
        }
        $result .= '</table><br>';
    }
    $result .= '</form>';
    return $result;
}

function check_invoice_payment($inv_id){
    $query = "SELECT payment_confirmed FROM coris_finances_invoices_in WHERE invoice_in_id = '$inv_id' ";
    $mr = mysql_query($query);
    if ($row = mysql_fetch_array($mr)){
        return $row['payment_confirmed'];
    }
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