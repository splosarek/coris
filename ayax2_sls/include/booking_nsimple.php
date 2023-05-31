<?php



class BookingSimpleN
{

	//define('BOOKING_OKRES',1);  //0 standard, 1 OKRES przelomu roku

	function booking_sls_in($data_dow, $row, $symdow, $numdow, $bookingtype_id, $rate, $multiplier, $currency_id, $table_cur_name, $date_cur, $table_source,  $client_charge)
    {
        $honoraria_lista = array(5);


        $row_contrahent = $this->check_contrahent($row['last_contrahent_id']);
        $contrahent_id = $row_contrahent['source_id'];


        $invoice_in_id = $row['id'];


        $data_dok = substr(str_replace('-', '', $row['date_exposure']), 2);
        //$data_plat = substr(str_replace('-', '', $row['date_payment']), 2);
        $data_plat = substr($row['date_payment'], 0, 10);

        //    $data_dow = date("ymd");
        $f_num = $row['number_full'];
        $case = $this->get_case_no($row['case_id']);
        $case_no = $this->get_case_assist_no($row['case_id']);
        $waluta = $row['currency_id'];

        $type_case = $this->check_type_case($row['case_id']);
        $case_name = $this->getPaxNameCase($row['case_id']);
        $tow_id = $this->getTowID($row['case_id']); // towarzystwo

        $type_case = $this->check_type_case($row['case_id']);


        $kz = ($waluta == 'PLN') ? 1 : 2;

        $net_amount = $row['net_amount'];
        $vat_amount = $row['vat_amount'];
        $fakt_gross_amount = $row['gross_amount'];


        $fakt_kwota = $fakt_gross_amount;
        $kwota_pln = $fakt_kwota;


        $simple_waluta = ($kz == 1) ? 0 : $this->get_simple_waluta($waluta);

        $ident1p = '';
        $ident2p = '';

        if ($simple_waluta > 0) {
            $ident2p .= $simple_waluta;
            $ident1p .= $simple_waluta;
        }

        $ident1 = 'F' . str_replace(" ", "", $f_num);;
        $ident2 = '' . $case_no;


        $tresc1 = str_replace('\'', '', $row_contrahent['full_name']) . " - " . $ident1;

        $licz = 1;


        $query_poz = "SELECT * FROM sls_finance_invoice_in_items  WHERE  	invoice_id= '$invoice_in_id '";
        $mysql_result_poz = mysql_query($query_poz);
        //$waluta_inv = $waluta;
        $ident1p_inv = $ident1p;
        while ($row_poz = mysql_fetch_array($mysql_result_poz)) {

            $vatrate_id = $row_poz['vat_rate_id'];
            $vatrate = $this->getStawkaVat($vatrate_id);


            $booking_type_id = $row_poz['booking_type_id'];
            $gross_amount = $row_poz['gross_amount'];
            $net_amount = $row_poz['net_amount'];
            $vat_amount = $row_poz['vat_amount'];
            $coris_amount = $row_poz['amount_coris'];
            $client_amount = $row_poz['amount_client'];


            $vat_amount_pln = ($waluta == 'PLN') ? $vat_amount : ev_round(($vat_amount * $rate) / $multiplier, 2);
            $net_amount_pln = ($waluta == 'PLN') ? $net_amount : ev_round(($net_amount * $rate) / $multiplier, 2);
            $gross_amount_pln = ($waluta == 'PLN') ? $gross_amount : ev_round(($gross_amount * $rate) / $multiplier, 2);
            $client_amount_pln = ($waluta == 'PLN') ? $client_amount : ev_round(($client_amount * $rate) / $multiplier, 2);
            $coris_amount_pln = ($waluta == 'PLN') ? $coris_amount : ev_round(($coris_amount * $rate) / $multiplier, 2);

            $kwota = $gross_amount;
            $client_amount_pln_net = 0.00;
            $coris_amount_pln_net = 0.00;

            //$kwota_vat = 0.0;
            $kwota_netto_pln = 0.0;
            if ($gross_amount_pln == $client_amount_pln) {
                //	$kwota_vat = $vat_amount_pln;
                $kwota_netto_pln = $client_amount_pln;
            } else {

                $kwota_netto_pln = $client_amount_pln;
              //  $kwota_vat = ev_round($client_amount_pln - $kwota_netto_pln, 2);
            }

            $client_amount_pln_net = $client_amount_pln;

            if ( $coris_amount_pln == $gross_amount ){
                $coris_amount_pln_net = $net_amount_pln;
            }else{
                $coris_amount_pln_net =  $net_amount_pln;
            }

           // $coris_amount_pln_net = $coris_amount_pln;


            if ($symdow == '02') {
                //winien
                $fakt_kwota = 0.0;
                if (in_array($booking_type_id, $honoraria_lista) && $client_amount > 0.00) {  //honoraria
                    $queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
		    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','416-" . $this->genContrahent($contrahent_id) . "',$kwota_netto_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'$date_cur','$table_cur_name',0)";    //$data_dok


                    $cur = mysql_query($queryu);
                    if (!$cur) {
                        $this->error_raport($queryu);
                        return false;
                    }
                    $licz++;


                    /*if ( $vatrate > 0) { //z vat
                        $queryu = "INSERT INTO sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                            VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','222-2-" . $this->genPozycja($vatrate, 2) . "',$kwota_vat,'$tresc1','$ident2','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";    //$data_dok
                        $cur = mysql_query($queryu);
                        if (!$cur) {
                            $this->error_raport($queryu);
                            return false;
                        }
                        $licz++;
                    }*/
                    $fakt_kwota += $client_amount;
                } else {  // koszty
                    if ($client_amount > 0.00) { // wciezar klienta
                        $queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','390-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$client_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

                        $cur = mysql_query($queryu);
                        if (!$cur) {
                            $this->error_raport($queryu);
                            return false;
                        }
                        $licz++;


                        $fakt_kwota += $client_amount;
                    }
                }

                if ($coris_amount_pln > 0.00 && $currency_id != 'PLN') { // w ciezar CORIS // honoraria zagranica

                    if ($gross_amount_pln == $coris_amount_pln) {
                        //$kwota_vat = $vat_amount_pln;
                        $kwota_netto_pln = $coris_amount_pln;
                    } else {
                        //$kwota_vat = ev_round($coris_amount_pln * $vatrate / 100, 2);
                        //$kwota_netto_pln = ev_round($coris_amount_pln *  100 / (100 +  $vatrate ), 2);
                        $kwota_netto_pln = $coris_amount_pln;
                        //$kwota_vat = ev_round($coris_amount_pln - $kwota_netto_pln , 2);
                    }

                    $queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
						VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','416-" . $this->genContrahent($contrahent_id) . "',$kwota_netto_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'$date_cur','$table_cur_name',0)";    //$data_dok


                    $cur = mysql_query($queryu);
                    if (!$cur) {
                        $this->error_raport($queryu);
                        return false;
                    }
                    $licz++;
                    $fakt_kwota += $coris_amount;

                    /*if ( $vatrate > 0) { //z vat
                        $queryu = "INSERT INTO sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
            VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','222-2-" . $this->genPozycja($vatrate, 2) . "',$kwota_vat,'$tresc1','$ident2','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";    //$data_dok
                        $cur = mysql_query($queryu);
                        if (!$cur) {
                            $this->error_raport($queryu);
                            return false;
                        }
                        $licz++;
                    }*/
                }


                ///MA
                if ($kz == 2) {  //zagranica
                    $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','212-" . $this->genNrKont($contrahent_id) . "-" . $this->genPozycja($simple_waluta, 2) . "',$fakt_kwota,'$tresc1','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

                } else {
                    $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','211-" . $this->genNrKont($contrahent_id) . "',$fakt_kwota,'$tresc1','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok
                }
                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }


            } else if ($symdow == '12') {  /// cięzar Coris i PLN
                if ($coris_amount_pln > 0.00) { // w ciezar CORIS

                    $symdow = '12';
                    $konto = '420-9';


                    if ($tow_id == 15074) { //greenval
                        $konto = '420-1';
                    } else if ($tow_id == 11470) { //AIG
                        $konto = '420-2';
                    } else if ($tow_id == 152) { //UNIQA
                        $konto = '420-3';
                    }

                    $queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
	    			VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','" . $konto . "',$coris_amount_pln_net,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN','1',1.0,'2000-01-01','$table_cur_name',0)";    //$data_dok
                    $cur = mysql_query($queryu);
                    if (!$cur) {
                        $this->error_raport($queryu);
                        return false;
                    }
                    $licz++;


                    $queryu3 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','222-2-" . $this->genPozycja($vatrate, 2) . "',$vat_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";
                    //VALUES ('".$symdow."',".$numdow.",'".$symdow."/".substr($data_dow,0,4)."/".$this->genNrDow($numdow)."','MA','222-1',$kwota_vat,'$tresc1','".$ident2."','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";
                    $licz++;
                    $cur = mysql_query($queryu3);
                    if (!$cur) {
                        $this->error_raport($queryu3);
                        return false;
                    }
                }


                ///MA
                if ($kz == 2) {  //zagranica
                    $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','212-" . $this->genNrKont($contrahent_id) . "-" . $this->genPozycja($simple_waluta, 2) . "',$coris_amount,'$tresc1','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

                } else {
                    $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','211-" . $this->genNrKont($contrahent_id) . "',$coris_amount,'$tresc1','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok
                }
                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }

            } else if ($symdow == '13' && $booking_type_id == 20) {  ///koszt refakturowany ALD POLSKA
                $tresc1 = "koszt refakturowany ALD POLSKA";


                /*Dekret:   strona  (MA) 211-kontrahent (01584-AUTOPOL) _ w kwocie  brutto
                druga strona (WN)     761-4 (koszty refakturowane)_kwota netto
                222-2-23( VAT NALICZONY _stawka VAT)_kwota VAT
                Identyfikator operacji: FV174/03/16 S0/207/20/2016.  Opis operacji :koszt refakturowany ALD POLSKA
                  */


                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
			VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','211-" . $this->genNrKont($contrahent_id) . "',$gross_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }

                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
			VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','761-4',$net_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }

                $queryu3 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','222-2-" . $this->genPozycja($vatrate, 2) . "',$vat_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";
                //VALUES ('".$symdow."',".$numdow.",'".$symdow."/".substr($data_dow,0,4)."/".$this->genNrDow($numdow)."','MA','222-1',$kwota_vat,'$tresc1','".$ident2."','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";
                $licz++;
                $cur = mysql_query($queryu3);
                if (!$cur) {
                    $this->error_raport($queryu3);
                    return false;
                }
            } else if ($symdow == '13' && $booking_type_id == 21) {  ///
                $tresc1 = "Koszt  refakturowany ZKF";


                if ($contrahent_id == 544 || $contrahent_id == 564  ) {
                    $tresc1 = "Koszt refakturowany ZK-NOTA";
                }
                /*Dekret:
                Dekret:   strona  (MA) 211-kontrahent (np.:01584-AUTOPOL) _ w kwocie  brutto
                druga strona     (WN)  761-4 (koszty refakturowane)_kwota brutto ( w ciężar klienta)
                                                          ( stawka VAT ZW )
                Identyfikator operacji:  numer sprawy/numer faktury           Opis : Koszt refakturowany ZK   */


                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
			VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','211-" . $this->genNrKont($contrahent_id) . "',$gross_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }

                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
			VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','761-4',$net_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }


                $queryu3 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','222-2-" . $this->genPozycja($vatrate, 2) . "',$vat_amount_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";
                //VALUES ('".$symdow."',".$numdow.",'".$symdow."/".substr($data_dow,0,4)."/".$this->genNrDow($numdow)."','MA','222-1',$kwota_vat,'$tresc1','".$ident2."','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";
                $licz++;
                $cur = mysql_query($queryu3);
                if (!$cur) {
                    $this->error_raport($queryu3);
                    return false;
                }


            }
            return true;
        }
    }




	function booking_sls_note_out($data_dow, $row, $symdow, $numdow, $bookingtype_id, $rate, $multiplier, $currency_id, $table_cur_name, $date_cur, $table_source)
	{


		$row_contrahent = $this->check_contrahent($row['last_contrahent_id']);

		$contrahent_id = $row_contrahent['source_id'];

		$old_dekret =false;

		$case_konto_anal = $this->check_claim($row['claim_id']);
		if ($case_konto_anal == 0) {
			//continue;
			return false;
		}

		$invoice_out_id = $row['id'];
		$data_dok = substr(str_replace('-', '', $row['exposure_date']), 2);
		//$data_plat = substr(str_replace('-', '', $row['date_payment']), 2);
        $data_plat = substr($row['date_payment'] ,0,10 );

		$f_num = $row['number_full'] ;
		$case = $this->get_claim_no($row['claim_id']);
		$case_no = $this->get_claim_assist_no($row['claim_id']);

		$waluta = $row['currency_id'];

		$tow_id = $this->getTowIDClaim($row['claim_id']); // towarzystwo

		$type_case = $this->check_type_claim($row['claim_id']);

		$kz = ($waluta == 'PLN') ? 1 : 2;

		$total_compensation_amount = $row['total_compensation_amount'];
		$total_cost_amount = $row['total_cost_amount'];
		$total_fee_amount = $row['total_fee_amount'];

		//$total_amount = $row['total_amount'];


		$row_inv = $row;

		$total_compensation_amount_val = 0.0;
		$total_cost_amount_val = 0.0;
		$total_fee_amount_val = 0.0;

		if ( $waluta == 'PLN') {
			$total_compensation_amount_val = $total_compensation_amount;
			$total_cost_amount_val = $total_cost_amount;
			$total_fee_amount_val = $total_fee_amount;
		} else {
			$total_compensation_amount_val = ev_round(($total_compensation_amount * $multiplier) / $rate , 2);
			$total_cost_amount_val = ev_round(($total_cost_amount * $multiplier) / $rate , 2);;
			$total_fee_amount_val = ev_round(($total_fee_amount * $multiplier) / $rate , 2);;
		}
		$licz = 1;


		$ident1p = '';
		$ident2p = '';

		$simple_waluta = ($kz == 1) ? 0 : $this->get_simple_waluta($waluta);
		if ($simple_waluta > 0) {
			$ident2p .=  $simple_waluta ;
			$ident1p .= $simple_waluta ;
		}

		$ident1 = '' . str_replace(" ","",$f_num);
		$ident2 = '' . $case_no;

		$ident1_ = 'F' . $f_num;
		$ident2_ = '' . $case_no;


		$tresc1 = str_replace('\'', '', $row_contrahent['full_name']) ." - ".$ident1;
		$tresc2 = str_replace('\'', '', $row_contrahent['full_name']) ." - ".$ident1 ;


		/*
    */

		if ($total_fee_amount > 0.0 ) {
			$konto_honoraria = 706;

			if ($kz == 1) {  //kraj
				$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','201-" . $this->genNrKont($contrahent_id) . "',$total_fee_amount,'$tresc2','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
			} else {//zagranica
				$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','202-" . $this->genNrKont($contrahent_id) . "-" . $this->genPozycja($simple_waluta, 2) . "',$total_fee_amount_val,'$tresc2','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

			}



			$licz++;
			$cur = mysql_query($queryu);
			if (!$cur) {
				$this->error_raport($queryu);
				return false;
			}

			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    					VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$total_fee_amount,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok


			$cur = mysql_query($queryu);
			if (!$cur) {
				$this->error_raport($queryu);
				return false;
			}
			$licz++;

		}

		if ($total_cost_amount_val > 0.0 || $total_compensation_amount_val > 0.0 ) {
			$sum = $total_cost_amount_val + $total_compensation_amount_val;

			$konto = '';
			$konto_odszkodowanie = '';
			/*
			 Ryzyko 10 na konta 290/319  +
            Ryzyko 4 na konta 294/318
               Ryzyko 9 na konta 299/318
            Pozostałe ryzyka na konta 296/318

			 */

			if ($type_case == '10') {
				$konto = '290';
				$konto_odszkodowanie = '319'; //
			} else if ($type_case == '4') {
				$konto = '294';
				$konto_odszkodowanie = '318';
			} else if ($type_case == '9') {
				$konto = '299';
				$konto_odszkodowanie = '318';
			} else {
				$konto = '296';
				$konto_odszkodowanie = '318'; //
			}
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','".$konto."-" . $this->genNrKont($contrahent_id) . "-" . $case . "',$sum,'$tresc2','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

			$cur = mysql_query($queryu);
			if (!$cur) {
				$this->error_raport($queryu);
				return false;
			}
			$licz++;

			if ($total_compensation_amount > 0.0 ){
				$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                      VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','".$konto_odszkodowanie."-" . $this->genNrKont($contrahent_id) ."-" .$case . "',$total_compensation_amount,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

				$cur = mysql_query($queryu1);
				if (!$cur) {
					$this->error_raport($queryu1);
					return false;
				}
			}

			if ($total_cost_amount > 0.0 ){

			    if ($contrahent_id == 544 || $contrahent_id == 564  ) {
                    $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','760-4',$total_cost_amount,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
                }else {
                    $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','390-" . $this->genPozycja($type_case, 2) . "-" . $case . "',$total_cost_amount,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
                }

				$cur = mysql_query($queryu1);
				if (!$cur) {
					$this->error_raport($queryu1);
					return false;
				}
			}
		}

		return true;
	}


	function booking_sls_out($data_dow, $row, $symdow, $numdow, $bookingtype_id, $rate, $multiplier, $currency_id, $table_cur_name, $date_cur, $table_source,$items_booking_type)
	{
		$honoraria_lista = array(10,11);
		$koszty_lista = array(12,13,14,15,16,17,18,19);

		$row_contrahent = $this->check_contrahent($row['last_contrahent_id']);

		$contrahent_id = $row_contrahent['source_id'];
		$case_id = $row['case_id'];
        $old_dekret =false;

		$case_konto_anal = $this->check_case($case_id);
		if ($case_konto_anal == 0) {
			//continue;
			return false;
		}

		$invoice_out_id = $row['id'];
		$data_dok = substr(str_replace('-', '', $row['date_exposure']), 2);
		//$data_plat = substr(str_replace('-', '', $row['date_payment']), 2);
		$data_plat = substr($row['date_payment'] ,0,10 );

		//$data_dow = date("ymd");
		//$f_num = "A" . $row['invoice_out_no'] . '/' . substr($row['invoice_out_year'], 2);
		$f_num = $row['number_full'] ;
		$case = $this->get_case_no($row['case_id']);
		$case_no = $this->get_case_assist_no($row['case_id']);
		$waluta = $row['currency_id'];

		//$type_case  = $this->check_type_case($row['case_id']);

		$tow_id = $this->getTowID($row['case_id']); // towarzystwo


		$type_case = $this->check_type_case($row['case_id']);


		$kz = ($waluta == 'PLN') ? 1 : 2;

		$gross_amount = $row['gross_amount'];
		$kwota = $gross_amount;


		$row_inv = $row;

		$query_poz = "SELECT currency_id,SUM(gross_amount) As gross_amount FROM sls_finance_invoice_out_items  WHERE invoice_id= '$invoice_out_id ' GROUP BY currency_id ";
		$mysql_result_poz = mysql_query($query_poz);
		$row_sum_poz = mysql_fetch_array($mysql_result_poz);

		$ilosc_wierszy = mysql_num_rows($mysql_result_poz);
		if ($ilosc_wierszy == 1 && $row_sum_poz['currency_id'] == 'PLN') {
			$kwota_pln = $row_sum_poz['gross_amount'];
		} else {

			$waluta_src = $row_inv['currency_id'];
			$kwota_src = $row_inv['gross_amount'];

			if ($waluta_src == 'PLN')
				$kwota_pln = $kwota_src;
			else
				$kwota_pln = ($waluta == 'PLN') ? $kwota : ev_round(($kwota * $rate) / $multiplier, 2);
		}
		$licz = 1;


		$ident1p = '';
		$ident2p = '';

		$simple_waluta = ($kz == 1) ? 0 : $this->get_simple_waluta($waluta);
		if ($simple_waluta > 0) {
			$ident2p .=  $simple_waluta ;
			$ident1p .= $simple_waluta ;
		}

        $ident1 = '' . str_replace(" ","",$f_num);
        $ident2  = '' . $case_no;

		$ident1_ = 'F' . $f_num;
		$ident2_ = '' . $case_no;


		$tresc1 = str_replace('\'', '', $row_contrahent['full_name']) ." - ".$ident1;
		$tresc2 = str_replace('\'', '', $row_contrahent['full_name']) ." - ".$ident1;


		if ($kz == 1) {  //kraj
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','201-" . $this->genNrKont($contrahent_id) . "',$kwota_pln,'$tresc2','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
		} else {//zagranica
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','202-" . $this->genNrKont($contrahent_id) . "-" . $this->genPozycja($simple_waluta, 2) . "',$kwota,'$tresc2','" . $ident2 . $ident1 . "','$waluta','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

		}

		$cur = mysql_query($queryu);
		if (!$cur) {
			$this->error_raport($queryu);
			return false;
		}

		$licz++;


		$waluta = $row_inv['currency_id'];

		//ma
		$query_poz = "SELECT * FROM sls_finance_invoice_out_items  WHERE invoice_id= '$invoice_out_id '";
		$mysql_result_poz = mysql_query($query_poz);
		//$waluta_inv = $waluta;
		$ident1p_inv = $ident1p;
		while ($row_poz = mysql_fetch_array($mysql_result_poz)) {
			$pos_id = $row_poz['id'];
			$bookingtype_id = $row_poz['booking_type_id'];


			$waluta_src = $row_poz['currency_id'];

			$vatrate_id = $row_poz['vat_rate_id'];
			$vatrate = $this->getStawkaVat($vatrate_id);

			if ($waluta <> 'PLN') {


					$rate =$rate;
					$multiplier = $multiplier;


			}

			if ($waluta <> 'PLN' && $waluta_src == 'PLN' ) {  // jesli nie modyfikowana recznie i faktura przychodzaca w PLN to wkot yw pLN do dekretu biezemy z tych faktur
				$kwota_net = $row_poz['net_amount'];
				$kwota_vat = $row_poz['vat_amount'];
				$kwota1 = $row_poz['gross_amount'];
			} else {
				$kwota = $row_poz['gross_amount'];
				$kwota_vat = $row_poz['vat_amount'];
				$kwota_net = $row_poz['net_amount'];

				$kwota_net = ($waluta == 'PLN') ? $kwota_net : ev_round(($kwota_net * $rate) / $multiplier, 2);
				$kwota_vat = ($waluta == 'PLN') ? $kwota_vat : ev_round($kwota_net * $vatrate / 100, 2);
				$kwota1 = ($waluta == 'PLN') ? $kwota : ev_round($kwota_net * (1 + $vatrate / 100), 2);

			}

			$konto_honoraria = 706;
            if ($bookingtype_id == '22'){ //SPRZEDAŻ REFAKTUROWANA ZK
                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','760-4',$kwota_net,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";


                $cur = mysql_query($queryu1);
                if (!$cur) {
                    $this->error_raport($queryu1);
                    return false;
                }


                if ($vatrate > 0.0) {
                    $queryu3 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                              VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','222-1-" . $this->genPozycja($vatrate, 2) . "',$kwota_vat,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";
                    //VALUES ('".$symdow."',".$numdow.",'".$symdow."/".substr($data_dow,0,4)."/".$this->genNrDow($numdow)."','MA','222-1',$kwota_vat,'$tresc1','".$ident2."','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";
                    $licz++;
                    $cur = mysql_query($queryu3);
                    if (!$cur) {
                        $this->error_raport($queryu3);
                        return false;
                    }
                }
            }else {

                if ($vatrate == 0) { // bez vatowe i zwolnione
                    if (in_array($bookingtype_id, $koszty_lista)) {//koszty
                        $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                              VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','390-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$kwota1,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

                        $cur = mysql_query($queryu1);
                        if (!$cur) {
                            $this->error_raport($queryu1);
                            return false;
                        }
                    } else if (in_array($bookingtype_id, $honoraria_lista)) {////honoraria
                        //706-1-17-02306 (-(kraj-1 lub zagranica-2)-( rodzaj sprawy-2 miejsca )-(kontrahent-5 miejsc)
                        $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                                VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota1,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

                        $licz++;
                        $cur = mysql_query($queryu1);
                        if (!$cur) {
                            $this->error_raport($queryu1);
                            return false;
                        }
                    }
                    $licz++;
                } else {// z vat
                    if ($kz == 1) { // kraj

                        /*	$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota_net,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";

                        $licz++;*/

                        if (in_array($bookingtype_id, $koszty_lista)) {//koszty
                            $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                              VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','390-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$kwota_net,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

                            $cur = mysql_query($queryu1);
                            if (!$cur) {
                                $this->error_raport($queryu1);
                                return false;
                            }
                        } else if (in_array($bookingtype_id, $honoraria_lista)) {////honoraria
                            //706-1-17-02306 (-(kraj-1 lub zagranica-2)-( rodzaj sprawy-2 miejsca )-(kontrahent-5 miejsc)
                            $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                                VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota_net,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

                            $licz++;
                            $cur = mysql_query($queryu1);
                            if (!$cur) {
                                $this->error_raport($queryu1);
                                return false;
                            }
                        }
                    } else {        //zagranica
                        $licz++;
                        if (in_array($bookingtype_id, $honoraria_lista)) {////honoraria
                            $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                                VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota_net,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";

                            $licz++;
                            $cur = mysql_query($queryu1);
                            if (!$cur) {
                                $this->error_raport($queryu1);
                                return false;
                            }
                        } else {
                            $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                              VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','390-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$kwota_pln,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok

                            $cur = mysql_query($queryu1);
                            if (!$cur) {
                                $this->error_raport($queryu1);
                                return false;
                            }
                        }
                    }


                    //if ($kwota_vat>0){
                    if ($vatrate > 0.0) {
                        $queryu3 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                              VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','222-1-" . $this->genPozycja($vatrate, 2) . "',$kwota_vat,'$tresc1','" . $ident2 . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN','1','1.0','2000-01-01','',0)";
                        //VALUES ('".$symdow."',".$numdow.",'".$symdow."/".substr($data_dow,0,4)."/".$this->genNrDow($numdow)."','MA','222-1',$kwota_vat,'$tresc1','".$ident2."','PLN','$data_dow','$data_plat',null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";
                        $licz++;
                        $cur = mysql_query($queryu3);
                        if (!$cur) {
                            $this->error_raport($queryu3);
                            return false;
                        }
                    }
                }
            }
		}
		return true;
	}


	function booking_assis_out_correct($data_dow, $row, $row_src, $symdow, $numdow, $bookingtype_id, $rate, $multiplier, $currency_id, $table_cur_name, $date_cur, $table_source)
	{
		$honoraria_lista = array(9, 11);

		$contrahent_id = $row['contrahent_id'];
		$case_id = $row['case_id'];
        $old_dekret = $this->check_old_dekret($case_id);
		$case_konto_anal = $this->check_case($case_id);
		if ($case_konto_anal == 0) {
			//continue;
			return false;
		}

		$invoice_out_id = $row['ID'];
		$data_dok = substr(str_replace('-', '', $row['correct_date']), 2);
		//$data_plat = '';
        $data_plat = substr($row['date_payment'] ,0,10 );

		$f_src_num = "A" . $row_src['invoice_out_no'] . '/' . substr($row_src['invoice_out_year'], 2);

		$f_num = 'KOR-' . $row['correct_out_no'] . '/' . $row['correct_out_year'];
		$case = $this->get_case_no($row['case_id']);
		$case_no = $this->get_case_assist_no($row['case_id']);
		$waluta = $row['currency_id'];

		$tow_id = $this->getTowID($row['case_id']); // towarzystwo
		if ($tow_id == '7592' && $table_source == 2)
			$type_case = 99;
		else
			$type_case = $this->check_type_case($row['case_id']);


		$boook_760_4 = getContrahnetParam($contrahent_id, 'boook_760_4');

		$kz = ($waluta == 'PLN') ? 1 : 2;

		$gross_amount = $row['gross_amount'];
		$kwota = $gross_amount;


		$query_poz = "SELECT currency_id,SUM(gross_amount) As gross_amount FROM coris_finances_invoices_out_correct_positions   WHERE ID_correct_invoice_out= '$invoice_out_id ' GROUP BY currency_id ";
		$mysql_result_poz = mysql_query($query_poz);
		$row_poz = mysql_fetch_array($mysql_result_poz);



		$waluta_src = $row['currency_id'];
		$kwota_src = $row['gross_amount'];


		if ($waluta_src == 'PLN')
			$kwota_pln = $kwota_src;
		else
			$kwota_pln = ($waluta == 'PLN') ? $kwota : ev_round(($kwota * $rate) / $multiplier, 2);

		$licz = 1;

		$simple_waluta = ($kz == 1) ? 0 : $this->get_simple_waluta($waluta);

		$ident1p = '';
		$ident2p = '';
		if ($simple_waluta > 0) {
			$ident2p .= KURS_IDENT == 0 ? $simple_waluta : '';
			$ident1p .= KURS_IDENT == 0 ? $simple_waluta : '';
		}
		$ident1 = (KURS_IDENT == 0 ? 'F' : '') . $f_num;
		$ident2 = '' . $case_no;

		$ident1_ = (KURS_IDENT == 0 ? 'F' : '') . $f_num;
		$ident2_ = '' . $case_no;

		$tresc1 = substr(str_replace('\'', '', $row['short_name']), 0, 19 - strlen($ident1)) . ' ' . $ident1_;
		$tresc2 = substr(str_replace('\'', '', $row['short_name']), 0, 19 - strlen($ident2)) . ' ' . $ident2_;

		$tresc2 = "Korekta do fakt: " . $f_src_num;



		$konto_honoraria = 700;
		$coris_case = getCaseInfo($case_id);
		$branch = $coris_case['coris_branch_id'];
		$grupa_april = getContrahnetParam($tow_id, '`group`') == 1 ? 1 : 0;

		if ($branch == 2 && $grupa_april == 1) { // coris DE
			$konto_honoraria = 701;
		} else if ($branch == 3 && $grupa_april == 1) { // coris AT
			$konto_honoraria = 702;
		}

		$leasing = array(11224, 11225, 11284);
		if ($contrahent_id == 11221 && in_array($tow_id, $leasing)) { // Leasingi - tow: 11224, 11225 ,11284, 9951, kontrahent 11221
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','250-1',$kwota_pln,'$tresc2','" . $ident1p . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1.0,1.0,'2000-01-01','',0)";
		} else if ($contrahent_id == 11221 && $tow_id == 9951) { // Leasingi - tow: 9951, kontrahent 11221
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','250-2',$kwota_pln,'$tresc2','" . $ident1p . $ident1 . "','PLN','$data_dow','$data_plat',null,'PLN',1.0,1.0,'2000-01-01','',0)";
		} else if ($kz == 1) {  //kraj
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','201-" . $this->genNrKont($contrahent_id) . "',$kwota_pln,'$tresc2','" . $ident1p . $ident1 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
		} else {//zagranica
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','202-" . $this->genNrKont($contrahent_id) . "-" . $this->genPozycja($simple_waluta, 2) . "',$kwota,'$tresc2','" . $ident1p . $ident1 . "','$waluta','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

		}

		$cur = mysql_query($queryu);
		if (!$cur) {
			$this->error_raport($queryu);
			return false;
		}

		$licz++;

		//ma
		$query_poz = "SELECT * FROM coris_finances_invoices_out_correct_positions   WHERE ID_correct_invoice_out= '$invoice_out_id '";
		$mysql_result_poz = mysql_query($query_poz);
		//$waluta_inv = $waluta;
		$ident1p_inv = $ident1p;
		while ($row_poz = mysql_fetch_array($mysql_result_poz)) {

			$query_src = "SELECT * FROM coris_finances_invoices_out_positions  WHERE invoice_out_position_id= '" . $row_poz['ID_invoice_out_position'] . "'";
			$mysql_result_poz_src = mysql_query($query_src);
			$row_poz_src = mysql_fetch_array($mysql_result_poz_src);

			// $waluta = $row_poz['currency_id'];
			$vatrate_id = $row_poz['vatrate_id'];
			$vatrate = $this->getStawkaVat($vatrate_id);
			$booking_pos_type_id = $row_poz_src['bookingtype_id'];

			$kwota = $row_poz['gross_amount'];
			$kwota_vat = $row_poz['vat_amount'];
			$kwota_net = $row_poz['net_amount'];

			$kwota_net = ($waluta == 'PLN') ? $kwota_net : ev_round(($kwota_net * $rate) / $multiplier, 2);
			$kwota_vat = ($waluta == 'PLN') ? $kwota_vat : ev_round($kwota_net * $vatrate / 100, 2);
			$kwota1 = ($waluta == 'PLN') ? $kwota : ev_round($kwota_net * (1 + $vatrate / 100), 2);

			if ($vatrate_id == 0 || $vatrate_id == 5) { // bez vatowe i zwolnione
				if ($bookingtype_id == 8 || $bookingtype_id == 10) {//koszty
                    if ($old_dekret) {
                         $queryu1="INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('".$symdow."',".$numdow.",'".$symdow."/".substr($data_dow,0,4)."/".$this->genNrDow($numdow)."','MA','300-".$this->genPozycja($type_case,2)."-".$this->genNrSpr($case)."',$kwota1,'$tresc1','".$ident1p.$ident2."','PLN','$data_dow',".($data_plat != '' ? "'".$data_plat."'" : 'null').",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
                    }else {
                        $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','301-" . $this->sprawdz_nr_kontraktu($tow_id) . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$kwota1,'$tresc1','" . $ident1p . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
                        $cur = mysql_query($queryu1);
                    }
					if (!$cur) {
						$this->error_raport($queryu1);
						return false;
					}
				} else if ($bookingtype_id == 9 || $bookingtype_id == 11) {////honoraria
					$queryu3 = '';
					$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota1,'$tresc1','" . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
					$licz++;
					$cur = mysql_query($queryu1);
					if (!$cur) {
						$this->error_raport($queryu1);
						return false;
					}
				}
				$licz++;
			} else {// z vat
				if ($kz == 1) { // kraj
					if ($boook_760_4 && !in_array($bookingtype_id, $honoraria_lista) && !in_array($booking_pos_type_id, $honoraria_lista)) {
						$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','760-4',$kwota_net,'$tresc1','" . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";
					} else {
						$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    				VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota_net,'$tresc1','" . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";
					}
					$licz++;
				} else {        //zagranica
					$licz++;
					if ($boook_760_4 && !in_array($bookingtype_id, $honoraria_lista) && !in_array($booking_pos_type_id, $honoraria_lista)) {
						$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
	    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','760-4',$kwota_net,'$tresc1','" . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";
					} else {
						$queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
	    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','$konto_honoraria-" . $kz . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrKont($contrahent_id) . "',$kwota_net,'$tresc1','" . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";
					}
					$licz++;
				}

				$cur = mysql_query($queryu1);
				if (!$cur) {
					$this->error_raport($queryu1);
					return false;
				}

				if ($vatrate_id != 0 && $vatrate_id != 2 && $vatrate_id != 5) {
					$queryu3 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','222-1-" . $this->genPozycja($vatrate, 2) . "',$kwota_vat,'$tresc1','" . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN','1','1.0','2000-01-01','',0)";
					$licz++;

					$cur = mysql_query($queryu3);
					if (!$cur) {
						$this->error_raport($queryu3);
						return false;
					}
				}
			}
		}
		return true;

	}

	function booking_assis_note_out_correct($data_dow, $row, $row_src, $symdow, $numdow, $bookingtype_id, $rate, $multiplier, $currency_id, $table_cur_name, $date_cur, $table_source)
	{


		$contrahent_id = $row['contrahent_id'];
		$case_id = $row['case_id'];
        $old_dekret = $this->check_old_dekret($case_id);
		$case_konto_anal = $this->check_case($case_id);
		if ($case_konto_anal == 0) {
			//continue;
			return false;
		}

		$invoice_out_id = $row['ID'];
		$data_dok = substr(str_replace('-', '', $row['correct_date']), 2);
		$data_plat = '';
        $data_plat = substr($row['date_payment'] ,0,10 );
		//$data_plat = substr(str_replace('-','',$row['invoice_out_due_date']),2);

		//$data_dow = date("ymd");
		$f_src_num = "A" . $row_src['invoice_out_no'] . '/' . substr($row_src['invoice_out_year'], 2);

		$f_num = 'KORN-' . $row['correct_out_no'] . '/' . $row['correct_out_year'];
		$case = $this->get_case_no($row['case_id']);
		$case_no = $this->get_case_assist_no($row['case_id']);
		$waluta = $row['currency_id'];

		//$type_case  = $this->check_type_case($row['case_id']);

		$tow_id = $this->getTowID($row['case_id']); // towarzystwo
		$type_case = $this->check_type_case($row['case_id']);


		$kz = ($waluta == 'PLN') ? 1 : 2;

		$gross_amount = $row['amount'];
		$kwota = $gross_amount;


		$query_poz = "SELECT currency_id,SUM(amount) As amount
    	FROM coris_finances_debitnote_out_correct_positions   WHERE ID_correct_debitnote_out = '$invoice_out_id ' GROUP BY currency_id ";
		$mysql_result_poz = mysql_query($query_poz);
		$row_poz = mysql_fetch_array($mysql_result_poz);

		/*$waluta_src = $row_poz['currency_id'];
     $kwota_src = $row_poz['gross_amount'];
    */

		$waluta_src = $row['currency_id'];
		$kwota_src = $row['amount'];


		if ($waluta_src == 'PLN')
			$kwota_pln = $kwota_src;
		else
			$kwota_pln = ($waluta == 'PLN') ? $kwota : ev_round(($kwota * $rate) / $multiplier, 2);

		$licz = 1;

		$simple_waluta = ($kz == 1) ? 0 : $this->get_simple_waluta($waluta);

		$ident1p = '';
		$ident2p = '';
		if ($simple_waluta > 0) {
			$ident2p .= KURS_IDENT == 0 ? $simple_waluta : '';
			$ident1p .= KURS_IDENT == 0 ? $simple_waluta : '';
		}
		$ident1 = (KURS_IDENT == 0 ? 'F' : '') . $f_num;
		$ident2 = '' . $case_no;

		$ident1_ = (KURS_IDENT == 0 ? 'F' : '') . $f_num;
		$ident2_ = '' . $case_no;

		$tresc1 = substr(str_replace('\'', '', $row['short_name']), 0, 19 - strlen($ident1)) . ' ' . $ident1_;
		$tresc2 = substr(str_replace('\'', '', $row['short_name']), 0, 19 - strlen($ident2)) . ' ' . $ident2_;

		$tresc2 = "Korekta do fakt: " . $f_src_num;
		/*
    */
		if ($kz == 1) {  //kraj
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','201-" . $this->genNrKont($contrahent_id) . "',$kwota_pln,'$tresc2','" . $ident1p . $ident1 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
		} else {//zagranica
			$queryu = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
    		VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','WN','202-" . $this->genNrKont($contrahent_id) . "-" . $this->genPozycja($simple_waluta, 2) . "',$kwota,'$tresc2','" . $ident1p . $ident1 . "','$waluta','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'$currency_id','$multiplier','$rate','$date_cur','$table_cur_name',0)";    //$data_dok

		}

		$cur = mysql_query($queryu);
		if (!$cur) {
			$this->error_raport($queryu);
			return false;
		}

		$licz++;

		//ma
		$query_poz = "SELECT * FROM coris_finances_debitnote_out_correct_positions   WHERE ID_correct_debitnote_out= '$invoice_out_id '";
		$mysql_result_poz = mysql_query($query_poz);
		//$waluta_inv = $waluta;
		$ident1p_inv = $ident1p;
		while ($row_poz = mysql_fetch_array($mysql_result_poz)) {

			$query_src = "SELECT * FROM  coris_finances_debitnote_out_positions  WHERE ID= '" . $row_poz['ID_debitnote_out_position '] . "'";
			$mysql_result_poz_src = mysql_query($query_src);
			$row_poz_src = mysql_fetch_array($mysql_result_poz_src);


			$kwota = $row_poz['amount'];


			$kwota1 = ($waluta == 'PLN') ? $kwota : ev_round(($kwota * $rate) / $multiplier, 2);

            if ($old_dekret) {
                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                        VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','300-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$kwota1,'$tresc1','" . $ident1p . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
            }else {
                $queryu1 = "INSERT INTO  sls_finances_bookings_cvden  (SYMDOW,NUMDOW,IDN_DEKRETU ,STRONA_WN_MA ,KONTO ,KWOTA ,OPIS_POZYCJI ,IDENT_FAKTURY,WALUTA_IDN ,DATA_DEKRETU ,DATA_PLATNOSCI ,DATA_ZAPLATY ,WALUTA_T_KURSOWEJ ,MNOZNIK ,KURS ,DATA_T_KURSOWEJ ,IDN_T_KURSOWEJ , simple_send )
                VALUES ('" . $symdow . "'," . $numdow . ",'" . $symdow . "/" . substr($data_dow, 0, 4) . "/" . $this->genNrDow($numdow) . "','MA','301-" . $this->sprawdz_nr_kontraktu($tow_id) . "-" . $this->genPozycja($type_case, 2) . "-" . $this->genNrSpr($case) . "',$kwota1,'$tresc1','" . $ident1p . $ident2 . "','PLN','$data_dow'," . ($data_plat != '' ? "'" . $data_plat . "'" : 'null') . ",null,'PLN',1,1.0,'2000-01-01','',0)";    //$data_dok
                $cur = mysql_query($queryu1);
            }
			if (!$cur) {
				$this->error_raport($queryu1);
				return false;
			}

			$licz++;

		}
		return true;

	}





	function getPaxNameCase($case_id)
	{
		$query = "SELECT paxname,paxsurname  FROM coris_assistance_cases WHERE case_id='$case_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row[1] . ' ' . $row[0];

	}

	function getTowID($case_id)
	{
		$query = "SELECT   sls_claim.client_id FROM sls_case, sls_claim  WHERE sls_case.ID='$case_id' AND sls_case.claim_id = sls_claim.id ";
		//$query = "SELECT client_id  FROM coris_assistance_cases WHERE case_id='$case_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row[0];

	}

	function getTowIDClaim($claim_id)
	{
		$query = "SELECT   sls_claim.client_id FROM  sls_claim  WHERE sls_claim.id='$claim_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row[0];

	}

	function get_suma_pozycji_waluta($invoice_out_id)
	{
		$query = "SELECT SUM(gross_amount) FROM sls_finance_invoice_out_items  WHERE invoice_id='$invoice_out_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row[0];


	}

	function  get_simple_waluta($waluta)
	{
		$query = "SELECT simple_id FROM coris_finances_currencies  WHERE currency_id='$waluta'";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row[0];

	}

	function check_country_contrahent($contrahent_id)
	{
		$query = "SELECT address_country_id   FROM  sls_contrahent_entity WHERE id='$contrahent_id'";
		//$query = "SELECT country_id FROM coris_contrahents WHERE contrahent_id='$contrahent_id'";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row[0] == 'PL' ? 1 : 2;

	}

	function check_contrahent($contrahent_id) {
		$query="";
		$query = "SELECT *  FROM  sls_contrahent_entity WHERE id='$contrahent_id'";
		/*if ($contrahent_id >= 300000)
			$query = "SELECT country_id FROM sls_contrahents WHERE ID='$contrahent_id'";

		else
			$query = "SELECT country_id FROM coris_contrahents WHERE contrahent_id='$contrahent_id'";*/
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row;

	}

	function get_claim_no($claim_id){
		//$query = "SELECT number,year  FROM coris_assistance_cases WHERE case_id='$case_id' ";
		$query = "SELECT sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.number_risk,sls_claim.number,sls_claim.number_fr1,sls_claim.number_fr2,sls_claim.number_fr3,sls_claim.number_fr4,sls_claim.number_fr5,sls_claim.forinting_number
   							FROM sls_claim  WHERE  sls_claim.id='$claim_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$fronting = $row['forinting_number'];
		$number='';
		if ($fronting==0) {
			$number = $row['number'];
		}else{
			$number = $row['number_fr'.$fronting];
		}

		$konto_number = "" .  sprintf('%02d',intval(substr($row['year'], 2))) ."" . $this->genNrSpr5(intval($number)) . "" . sprintf('%02d', $fronting);


		/*$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$number = $row['number'];*/
		//return $number.$rok;
		//$konto_number = 1000000 * intval(substr($row['year'], 2)) + intval($row['number']);
		return $konto_number;

	}
	function get_case_no($case_id){
		//$query = "SELECT number,year  FROM coris_assistance_cases WHERE case_id='$case_id' ";
		$query = "SELECT sls_case.applicant_name,sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.number_risk,sls_claim.number,sls_claim.number_fr1,sls_claim.number_fr2,sls_claim.number_fr3,sls_claim.number_fr4,sls_claim.number_fr5,sls_claim.forinting_number
   							FROM sls_case, sls_claim  WHERE sls_case.ID='$case_id' AND sls_case.claim_id = sls_claim.id";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$fronting = $row['forinting_number'];
		$number='';
		if ($fronting==0) {
			$number = $row['number'];
		}else{
			$number = $row['number_fr'.$fronting];
		}

		$konto_number = "" .  sprintf('%02d',intval(substr($row['year'], 2))) ."" . $this->genNrSpr5(intval($number)) . "" . sprintf('%02d', $fronting);


		/*$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$number = $row['number'];*/
		//return $number.$rok;
		//$konto_number = 1000000 * intval(substr($row['year'], 2)) + intval($row['number']);
		return $konto_number;

	}

	function get_claim_assist_no($claim_id)
	{

		$query = "SELECT sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.risk_type_id    FROM  sls_claim  WHERE sls_claim.id='$claim_id'  ";
		//$query = "SELECT number,year  FROM coris_assistance_cases WHERE case_id='$case_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$number = $row['number'];

		//return intval($row['number']) . '/' . substr($row['year'], 2);
		return $row['full_number'];
	}
	function get_case_assist_no($case_id)
	{

		$query = "SELECT sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.risk_type_id    FROM sls_case, sls_claim  WHERE sls_case.ID='$case_id' AND sls_case.claim_id = sls_claim.id ";
		//$query = "SELECT number,year  FROM coris_assistance_cases WHERE case_id='$case_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$number = $row['number'];

		//return intval($row['number']) . '/' . substr($row['year'], 2);
		return $row['full_number'];
	}

	function check_type_claim($claim_id)
	{
		//$query = "SELECT type_id  FROM coris_assistance_cases WHERE case_id='$case' ";
		$query = "SELECT sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.number_risk    FROM  sls_claim  WHERE sls_claim.id  ='$claim_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row['number_risk'];
	}

	function check_type_case($case_id)
	{
		//$query = "SELECT type_id  FROM coris_assistance_cases WHERE case_id='$case' ";
		$query = "SELECT sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.number_risk    FROM sls_case, sls_claim  WHERE sls_case.ID='$case_id' AND sls_case.claim_id = sls_claim.id  ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row['number_risk'];
	}

	function check_claim($claim_id) {

		$query = "SELECT sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.number_risk,sls_claim.number,sls_claim.number_fr1,sls_claim.number_fr2,sls_claim.number_fr3,sls_claim.number_fr4,sls_claim.number_fr5,sls_claim.forinting_number
   							FROM sls_claim  WHERE sls_claim.id='$claim_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$fronting = $row['forinting_number'];
		$number='';
		if ($fronting==0) {
			$number = $row['number'];
		}else{
			$number = $row['number_fr'.$fronting];
		}

		$konto_number = "" .  sprintf('%02d',intval(substr($row['year'], 2))) ."" . $this->genNrSpr5(intval($number)) . "" . sprintf('%02d', $fronting);

		$query = "SELECT count(*) FROM sls_finances_bookings_cvsp WHERE NR_SPR='" . $konto_number . "'";



		$mysql_result = mysql_query($query);
		$row_ch = mysql_fetch_array($mysql_result);
		$numRecords = $row_ch[0];
		mysql_free_result($mysql_result);


		if ($numRecords == 0) {
			$nazwa_spr = mysql_escape_string(stripslashes($row['number_risk'] . '-' . $row['applicant_name'] ));
			$queryu = "INSERT INTO sls_finances_bookings_cvsp (NR_SPR,NAZSPR,KONANL)  VALUES ( '" . $konto_number . "','" . ($nazwa_spr) . "'," . $konto_number . ")";
			$cur = mysql_query($queryu);
			if (!$cur) {
				echo "<br>Error insert case: " . $queryu;
				return false;
			} else
				return $konto_number;
		} else {
			return $konto_number;
		}
	}
	function check_case($case_id) {

		$query = "SELECT sls_case.applicant_name,sls_claim.full_number,sls_claim.number,sls_claim.year,sls_claim.number_risk,sls_claim.number,sls_claim.number_fr1,sls_claim.number_fr2,sls_claim.number_fr3,sls_claim.number_fr4,sls_claim.number_fr5,sls_claim.forinting_number
   							FROM sls_case, sls_claim  WHERE sls_case.ID='$case_id' AND sls_case.claim_id = sls_claim.id ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$rok = substr($row['year'], 2);
		$fronting = $row['forinting_number'];
		$number='';
		if ($fronting==0) {
			$number = $row['number'];
		}else{
			$number = $row['number_fr'.$fronting];
		}

		$konto_number = "" .  sprintf('%02d',intval(substr($row['year'], 2))) ."" . $this->genNrSpr5(intval($number)) . "" . sprintf('%02d', $fronting);

		$query = "SELECT count(*) FROM sls_finances_bookings_cvsp WHERE NR_SPR='" . $konto_number . "'";

		$mysql_result = mysql_query($query);
		$row_ch = mysql_fetch_array($mysql_result);
		$numRecords = $row_ch[0];
		mysql_free_result($mysql_result);


		if ($numRecords == 0) {
			$nazwa_spr = mysql_escape_string(stripslashes($row['number_risk'] . '-' . $row['applicant_name'] ));
			$queryu = "INSERT INTO sls_finances_bookings_cvsp (NR_SPR,NAZSPR,KONANL)  VALUES ( '" . $konto_number . "','" . ($nazwa_spr) . "'," . $konto_number . ")";
			$cur = mysql_query($queryu);
			if (!$cur) {
				echo "<br>Error insert case: " . $queryu;
				return false;
			} else
				return $konto_number;
		} else {
			return $konto_number;
		}
	}


/*	function check_contrahent_simple($contrahent_id)
	{
		$query = "SELECT o_klsimple FROM  coris_contrahents  WHERE contrahent_id='$contrahent_id' ";
		$mysql_result = mysql_query($query);
		if (mysql_num_rows($mysql_result) == 0) return false;
		$row = mysql_fetch_array($mysql_result);

		if ($row[0] != "")
			return true;
		return
			false;
	}

	function check_contrahent_konto_anal($contrahent_id)
	{
		$query = "SELECT o_klsimple FROM  coris_contrahents  WHERE contrahent_id='$contrahent_id' AND o_klsimple<>''";
		$mysql_result = mysql_query($query);
		if (mysql_num_rows($mysql_result) == 0) return false;
		$row = mysql_fetch_array($mysql_result);

		$query = "SELECT count(*) FROM coris_finances_bookings_sbko WHERE IKONTR='" . $row[0] . "'";
		$count = mysql_query($query);
		$row = mysql_fetch_array($count);
		$numRecords = $row[0];
		mysql_free_result($count);

		if ($numRecords > 0)
			return true;
		return
			false;
	}*/


	function convert_txt_to_simple($txt)
	{
//  $tab_iso_to_mazovia = array(chr(161)=>chr(143), chr(198)=>chr(149), chr(202)=>chr(144), chr(163)=>chr(156), chr(209)=>chr(165), chr(211)=>chr(163), chr(166)=>chr(152), chr(172)=>chr(160), chr(175)=>chr(161), chr(177)=>chr(134), chr( 230)=>chr(141), chr( 234)=>chr(145), chr( 179)=>chr(146), chr( 241)=>chr(164), chr( 243)=>chr(162), chr( 182)=>chr(158), chr( 188)=>chr(166), chr( 191)=>chr(167));
		//return strtr($txt,$tab_iso_to_mazovia);
		return $txt;
	}

	/*�   �   �   �   �   �   �   �   �
ISO    ->161 198 202 163 209 211 166 172 175
MAZOVIA->143 149 144 156 165 163 152 160 161


�   �   �   �   �   �   �   �   �
ISO     -> 177 230 234 179 241 243 182 188 191
MAZOVIA -> 134 141 145 146 164 162 158 166 167
*/

	function getVatSimple($vatrate_id)
	{
		if ($vatrate_id > 0) {
			$query = "SELECT simple_id FROM coris_finances_vatrates WHERE vatrate_id='$vatrate_id'";
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);
			return $row['simple_id'];
		} else {
			return 5;

		}

	}

	function error_raport($queryu)
	{
		echo "<br>Error insert dekret: \n" . $queryu . "\n\n" . mysql_error();
		mail("krzysiek@poczta.evernet.com.pl", "DEKRET ERROR", "Error insert dekret: \n" . $queryu . "\n\n" . mysql_error() . "\n\n" . print_r(debug_backtrace(), 1));
	}


	function getKursy($table_id, $ratetype_id, $table_currency)
	{


		$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_pln, coris_finances_currencies_tables_rates.multiplier AS rate_to_pln_mult,  REPLACE((coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate), '.', ',')  AS rate_to_ext, coris_finances_currencies_tables_rates.table_id, REPLACE(coris_finances_currencies_tables_rates.rate, '.', ',') AS rate, coris_finances_currencies_tables.quotation_date, coris_finances_currencies_tables.publication_date, coris_finances_currencies_tables.ratetype_id,coris_finances_currencies_tables.number
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables
			WHERE coris_finances_currencies_tables.table_id = '$table_id' AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables.ratetype_id='" . $ratetype_id . "' AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";


		$mysql_result = mysql_query($query);
		return $mysql_result;
	}

	function getStawkaVat($vatrate_id){

		$vatrates = array(1=>'23',2=>'8',3=>'5',4=>'4',5=>'0',6=>'0',7=>'0',8=>'22',9=>'7',10=>'3');
		return $vatrates[$vatrate_id];
		/*$qv = "SELECT rate FROM coris_finances_vatrates WHERE vatrate_id='$vatrate_id'";
		$mr = mysql_query($qv);
		$row_vat = mysql_fetch_array($mr);
		return $row_vat[0] * 100;*/
	}

	function genNrDow($nr)
	{
		return sprintf('%05d', $nr);
	}


	function genContrahent($nr)
	{
		return sprintf('%05d', $nr);
	}

	function genNrSpr($nr)
	{
		return sprintf('%08d', $nr);
	}

	function genNrSpr6($nr)
	{
		return sprintf('%06d', $nr);
	}

	function genNrSpr5($nr)
	{
		return sprintf('%05d', $nr);
	}

	function genPozycja($nr, $ilosc)
	{
		return sprintf('%0' . $ilosc . 'd', $nr);
	}


	function genNrKont($nr)
	{
		return sprintf('%05d', $nr);
	}





}

function getNoteOutData($id){

	$query = "SELECT * FROM sls_finance_debit_note WHERE id='$id'";
	$mr = mysql_query($query);
	$row = mysql_fetch_array($mr);
	return $row;
}


function getNoteOutItemsData($id){

	$query = "SELECT * FROM sls_finance_debit_note_items WHERE debit_note_id='$id'";
	$mr = mysql_query($query);
	$result = array();
	while ( $row = mysql_fetch_array($mr)){
		$result[] = $row;
	}
	return $result;
}


function getInvoiceOutData($id){

	$query = "SELECT * FROM sls_finance_invoice_out WHERE id='$id'";
	$mr = mysql_query($query);
	$row = mysql_fetch_array($mr);
	return $row;
}

function getInvoiceOutItemsData($id){

	$query = "SELECT * FROM sls_finance_invoice_out_items WHERE invoice_id='$id'";
	$mr = mysql_query($query);
	$result = array();
	while ( $row = mysql_fetch_array($mr)){
		$result[] = $row;
	}
	return $result;
}

function getInvoiceInData($id){

	$query = "SELECT * FROM sls_finance_invoice_in WHERE id='$id'";
	$mr = mysql_query($query);
	$row = mysql_fetch_array($mr);
	return $row;
}

function getInvoiceInItemsData($id){

	$query = "SELECT * FROM sls_finance_invoice_in_items WHERE invoice_id='$id'";
	$mr = mysql_query($query);
	$result = array();
	while ( $row = mysql_fetch_array($mr)){
		$result[] = $row;
	}
	return $result;
}

function ev_round($liczba,$precyzja=0){
	$mnoznik = pow(10,$precyzja);
	if ($liczba > 0.00){
		$wyn = $liczba*$mnoznik + 0.5;
		return (ev_intval($liczba*$mnoznik + 0.5))/$mnoznik;
	}else{
		return (ev_intval($liczba*$mnoznik - 0.5))/$mnoznik;
	}
}


function ev_intval($liczba){
	$liczba_tmp = (String) $liczba;
	$poz = strpos($liczba_tmp,'.');
	if ($poz === false ){
		return $liczba;
	}else{
		return substr($liczba_tmp,0,$poz);
	}
}
?>