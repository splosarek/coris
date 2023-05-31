<?php

define('BOOKING_OKRES',1);  //0 standard, 1 OKRES przelomu roku

$d = date('Y-m-d');
if ($d  >= '2008-05-01')
	define('KURS_IDENT',1);
else 	
	define('KURS_IDENT',0);	

	

function booking_assis_in($row,$symdow,$numdow,$bookingtype_id,$rate,$multiplier,$cel_id){
    
    	$contrahent_id = $row['contrahent_id'];
	    $case_id = $row['case_id'];
	    
	    $case_konto_anal = check_case($case_id);
    
	    $invoice_in_id = $row['invoice_in_id'];
	    $data_dok = substr(str_replace('-','',$row['invoice_in_date']),2);
	    $data_plat = substr(str_replace('-','',$row['invoice_in_due_date']),2);
	    
	    $data_dow = date("ymd");
	    $f_num = $row['invoice_in_no'];
	    $case = get_case_no($row['case_id']);
	    $case_no = get_case_assist_no($row['case_id']);
	    $waluta = $row['currency_id'];
	    
	    $type_case  = check_type_case($row['case_id']);
	    $case_name  = getPaxNameCase($row['case_id']);
	    $tow_id = getTowID($row['case_id']); // towarzystwo
    
	    $vatrate_id  = $row['vatrate_id'];
	    $vatrate = getStawkaVat ($vatrate_id);
	    
	    $kz= ($waluta=='PLN') ? 1 :2 ;
	    
	    $gross_amount = $row['gross_amount'];
	    $client_amount= $row['client_amount'];
	    $coris_amount= $row['coris_amount'];
	    $cicp_amount= $row['cicp_amount'];
	    
	    $kwota = $gross_amount;
	    $kwota_pln = ($waluta=='PLN') ? $kwota : ev_round(($kwota*$rate)/$multiplier,2);
	    $client_amount_pln= ($waluta=='PLN') ? $client_amount : ev_round(($client_amount*$rate)/$multiplier,2);
	    $coris_amount_pln= ($waluta=='PLN') ? $coris_amount : ev_round(($coris_amount*$rate)/$multiplier,2);
	    $cicp_amount_pln = ($waluta=='PLN') ? $cicp_amount : ev_round(($cicp_amount*$rate)/$multiplier,2);
    
        
	    $client_amount_pln_net = 0.00;
	    $coris_amount_pln_net = 0.00;
	    $cicp_amount_pln_net = 0.00;
	    
	    $vatrate_id  = $row['vatrate_id'];
	    $simple_vatrate_id = getVatSimple($vatrate_id);
	    $vatrate = getStawkaVat ($vatrate_id);
	    
	    if ($vatrate_id==0 || $vatrate_id == 5 ){
	    	$client_amount_pln_net = $client_amount_pln;
	    	$coris_amount_pln_net = $coris_amount_pln;
	    	$cicp_amount_pln_net = $cicp_amount_pln;	    	
	    }else{
	    	if ($vatrate > 0){
	    		$client_amount_pln_net = ev_round($client_amount_pln/(1+$vatrate/100),2);
	    		$coris_amount_pln_net = ev_round($coris_amount_pln/(1+$vatrate/100),2);
	    		$cicp_amount_pln_net = ev_round($cicp_amount_pln/(1+$vatrate/100),2);	    		
	    	}else{
	    		$client_amount_pln_net = $client_amount_pln;
	    		$coris_amount_pln_net = $coris_amount_pln;
	    		$cicp_amount_pln_net = $cicp_amount_pln;
	    	}	    	
	    }
	    
	    
	    $licz=1;
	    
	    $simple_waluta = ($kz==1) ? 0 : get_simple_waluta($waluta);
	    
	    $ident1p = '';
	    $ident2p = '';
	    if ($simple_waluta>0){ 
	      $ident2p .= KURS_IDENT==0 ? $simple_waluta : '' ;
	      $ident1p .= KURS_IDENT==0 ? $simple_waluta : '' ;
	    }
	    $ident1 = 'F'.$f_num;
	    $ident2 = 'S'.$case_no;
    
    
	    $mod = "+W";
	    if ($bookingtype_id == 6){
	      $mod = "+H";
	    }
        
    	$contrahent_name =substr(str_replace('\'','',$row['short_name']),0,12);
    	$tresc1 = $contrahent_name.'-'.substr($f_num,0,19-strlen($contrahent_name)); 
  
     
    //winien
  		if ($bookingtype_id==6){  //honoraria
    		$type_417 = 8;
    	if ($tow_id==7592)
      		$type_417=7;
    	else if ($tow_id==12)
      		$type_417=2;
    	else if ( in_array($tow_id,array(606,607,608,609,610,611,612,613,614,615,616,617,618,619,620,621,622,623,624,625,626,627,628,629,630,652)))
      		$type_417=3;
            
    	$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
    		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',417,".($type_417).",".($contrahent_id).",0,0,0,0,0,0,0,0,0,0,0,0,0,$kwota_pln,'$tresc1','$ident1p$ident2','$data_plat','$data_dok','N',0)";    
     	$cur=mysql_query($queryu);
    	if(!$cur){  error_raport($queryu); return false;  }
    	$licz++;   
  }else{
    
    if ($client_amount>0.00){ // wciezar klienta
    	if ($vatrate > 0 || $vatrate_id>0){
			$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
      			VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',409,8,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$client_amount_pln_net,'$tresc1','".$ident1p.$ident2."','$data_plat','$data_dok','N',0)";    
      		
      		$cur=mysql_query($queryu);
	   		if(!$cur){  error_raport($queryu); return false;      }
      		$licz++;  
      		    		
      		$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
      			VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',998,'".($cel_id>0 ? $cel_id : 5)."','".$simple_vatrate_id."',".($contrahent_id).",0,0,0,0,0,0,0,0,0,0,0,0,$client_amount_pln_net,'$tresc1','".$ident1p.$ident2."','$data_plat','$data_dok','N',0)";    
      		
      		$cur=mysql_query($queryu);
	   		if(!$cur){  error_raport($queryu); return false;      }
      		$licz++;  
			if ($vatrate>0)
      			$client_amount_pln_vat = ev_round($client_amount_pln_net*($vatrate/100),2); 
      		else 	
      			$client_amount_pln_vat = 0;
      			
      		$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
      			VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',222,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$client_amount_pln_vat,'$tresc1','".$ident1p.$ident2."','$data_plat','$data_dok','N',0)";    
      		
      		$cur=mysql_query($queryu);
	   		if(!$cur){  error_raport($queryu); return false;      }
      		$licz++;        		
      	}else{
		      	$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
		      		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',300,".($type_case).",".($case).",0,0,0,0,0,0,0,0,0,0,0,0,0,$client_amount_pln,'$tresc1','$ident1p$ident2','$data_plat','$data_dok','N',0)";    
		     	$cur=mysql_query($queryu);
		    	if(!$cur){  error_raport($queryu); return false;  }
		    	$licz++;
		    	
		       	$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
		       		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',950,".($type_case).",".($tow_id).",0,0,0,0,0,0,0,0,0,0,0,0,0,$client_amount_pln_net,'$tresc1','$ident1p$ident2','$data_plat','$data_dok','N',0)";
		      	$cur=mysql_query($queryu);
		      	if(!$cur){error_raport($queryu); return false; }
		      	$licz++; 
      	}
    }

    if ($cicp_amount>0.00){ // w ciezar CICP
      	$queryu = "INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
      		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',201,".($kz).",".(7597).",23,0,0,0,0,0,0,0,0,0,0,0,0,$cicp_amount_pln_net,'$tresc1','23".$ident2."','$data_plat','$data_dok','N',0)";    
      	$cur=mysql_query($queryu);
      	if(!$cur){ error_raport($queryu); return false; }
    	$licz++;
            
    	$cicp_amount_pln_eur = ev_round($cicp_amount_pln / $rate_cicp_euro,2);
    	$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
    		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',901,".($kz).",".(7597).",23,0,0,0,0,0,0,0,0,0,0,0,0,$cicp_amount_pln_net,'$tresc1','23".$ident2."','$data_plat','$data_dok','N',0)";
        $cur=mysql_query($queryu);
      	
        if(!$cur){ error_raport($queryu);  return false; }
      	$licz++; 
    }

    if ($coris_amount>0.00){ // w ciezar CORIS      
      	$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )  
      		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',409,9,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$coris_amount_pln_net,'$tresc1','".$ident1p.$ident2."','$data_plat','$data_dok','N',0)";    
      	$cur=mysql_query($queryu);
	   	if(!$cur){  error_raport($queryu); return false;      }
      	$licz++;        	      	    	      
    }    

///MA          
          
  }  

  		$queryu1="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  ) 
  			VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,211,".($kz).",".($contrahent_id).",'".$simple_waluta."',0,0,0,0,".$kwota_pln.",'$tresc1','$ident1p$ident2','$data_plat','$data_dok','N',0)";
  		$cur=mysql_query($queryu1);
       	if(!$cur){ error_raport($queryu1);return false;}
  		$licz++;   
  
  		if ($kz==2){  //zagranica      
        	$queryu2="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  ) 
        		VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,911,".($kz).",".($contrahent_id).",'".$simple_waluta."',0,0,0,0,".$kwota.",'$tresc1','$ident1p$ident2','$data_plat','$data_dok','N',0)";         
         	$cur=mysql_query($queryu2);         
         	if(!$cur){  error_raport($queryu2);return false;   }    
		}

	return true;  
}


function booking_assis_out($row,$symdow,$numdow,$bookingtype_id,$rate,$multiplier){
  
    $contrahent_id = $row['contrahent_id'];
    $case_id = $row['case_id'];

    $case_konto_anal = check_case($case_id);
    if ($case_konto_anal==0){
      //continue;
      return false;
    }
    
    $invoice_out_id = $row['invoice_out_id'];
    $data_dok = substr(str_replace('-','',$row['invoice_out_date']),2);
    $data_plat = substr(str_replace('-','',$row['invoice_out_due_date']),2);
    
    $data_dow = date("ymd");
    $f_num = "A".$row['invoice_out_no'].'/'.substr($row['invoice_out_year'],2);
    $case = get_case_no($row['case_id']);
    $case_no = get_case_assist_no($row['case_id']);
    $waluta = $row['currency_id'];
    
    $type_case  = check_type_case($row['case_id']);
    
    $kz= ($waluta=='PLN') ? 1 :2 ;
    
    $gross_amount = $row['gross_amount'];
    $kwota = $gross_amount;
    
    
   /* if ($row['currency_id2'] != ''){
    	
    	 $rr = getKursy($table_id,$row['ratetype_id'],$row['currency_in_id']);
    	
    	$rate = 
    	$multiplier = ;
    	
    	
    }
*/
    $query_poz = "SELECT currency_id,SUM(gross_amount) As gross_amount FROM coris_finances_invoices_out_positions  WHERE invoice_out_id= '$invoice_out_id ' GROUP BY currency_id ";
    $mysql_result_poz = mysql_query($query_poz);            
    $row_poz = mysql_fetch_array($mysql_result_poz);
      
       $waluta_src = $row_poz['currency_id'];
      

     
      $kwota_src = $row_poz['gross_amount'];
    
    if ($waluta_src == 'PLN')
        $kwota_pln = $kwota_src;
    else       
        $kwota_pln = ($waluta=='PLN') ? $kwota : ev_round(($kwota*$rate)/$multiplier,2);
    
    
    
    $licz=1;
    
    $simple_waluta = ($kz==1) ? 0 : get_simple_waluta($waluta);

    
    $ident1p = '';
    $ident2p = '';
    if ($simple_waluta>0){ 
      $ident2p .= KURS_IDENT==0 ? $simple_waluta : '';
      $ident1p .= KURS_IDENT==0 ? $simple_waluta : '' ;
    }
    $ident1 = 'F'.$f_num;
    $ident2 = 'S'.$case_no;
    
    $ident1_ = 'F'.$f_num;
    $ident2_ = 'S'.$case_no;      
  
    $tresc1 = substr(str_replace('\'','',$row['short_name']),0,19-strlen($ident1)).' '.$ident1_; 
    $tresc2 = substr(str_replace('\'','',$row['short_name']),0,19-strlen($ident2)).' '.$ident2_;
     
    /*
    */
    //winien
    $queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
          VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',201,".($kz).",".($contrahent_id).",".($simple_waluta).",0,0,0,0,0,0,0,0,0,0,0,0,$kwota_pln,'$tresc2','".$ident1p.$ident1."','$data_plat','$data_dok','N',0)";
    
    $cur=mysql_query($queryu);
    if(!$cur){error_raport($queryu);return false; }
    
    $licz++;   
    if ($kz==2){  //zagranica
      
      	//$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
         // VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',901,".($kz).",".($contrahent_id).",".($simple_waluta).",0,0,0,0,0,0,0,0,0,0,0,0,$kwota,'".$ident1p.$ident2."','$tresc1','$data_plat','$data_dok','N',0)";
       	$queryu="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
          VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',901,".($kz).",".($contrahent_id).",".($simple_waluta).",0,0,0,0,0,0,0,0,0,0,0,0,$kwota,'".$tresc2."','$ident1p.$ident1','$data_plat','$data_dok','N',0)";
       	$cur=mysql_query($queryu);
      	if(!$cur){error_raport($queryu);return false; }
      	$licz++; 
    
    }
    //ma
    $query_poz = "SELECT * FROM coris_finances_invoices_out_positions  WHERE invoice_out_id= '$invoice_out_id '";
    $mysql_result_poz = mysql_query($query_poz);    
    //$waluta_inv = $waluta;
    $ident1p_inv=$ident1p;
    while ($row_poz = mysql_fetch_array($mysql_result_poz)){
      
      $waluta = $row_poz['currency_id'];
      $vatrate_id = $row_poz['vatrate_id'];
	 $simple_vatrate_id	 = getVatSimple($vatrate_id);
      
      if ($waluta<>'PLN'){
        //$rate_w=1;
      //  $multiplier_w=1;
        $simple_waluta_w = '';
        if ($row_poz['invoice_in_position_id']>0){
          $query_w = "SELECT rate,multiplier FROM coris_finances_currencies_tables_rates AS tr, coris_finances_invoices_in As fin WHERE tr.currency_id='$waluta' AND tr.table_id = fin.table_id AND  fin.invoice_in_id = '".$row_poz['invoice_in_position_id']."'";
        }else{
          $query_w = "SELECT rate,multiplier FROM coris_finances_currencies_tables_rates AS tr WHERE tr.currency_id='$waluta' AND tr.table_id = '".$row['table_id']."'";        
        }      

          $mysql_result_w = mysql_query($query_w);
            if (mysql_num_rows($mysql_result_w) > 0){
                $row_w = mysql_fetch_array($mysql_result_w);
                $rate = $row_w[0];      
                $multiplier = $row_w[1];
                $simple_waluta_w = get_simple_waluta($waluta);
                $ident1p = KURS_IDENT==0 ? $simple_waluta_w : '' ;
                
                 if ($row['currency_id2'] != ''){
                 	 	$simple_waluta_w = get_simple_waluta($row['currency_id2']);
                		$ident1p = KURS_IDENT==0 ? $simple_waluta_w : '';
                 	
                 }            
            }else{
              mail("krzysiek@poczta.evernet.com.pl","DEKRET ERROR", "Error dekret waluta : \n".$query_w."\n\n".mysql_error());
            }
                      
      }
      $kwota = $row_poz['gross_amount'];
      $kwota_vat = $row_poz['vat_amount'];
      $kwota_net = $row_poz['net_amount'];
       
       $kwota1 = ($waluta=='PLN') ? $kwota : ev_round(($kwota*$rate)/$multiplier,2);
       $kwota_vat = ($waluta=='PLN') ? $kwota_vat : ev_round(($kwota_vat*$rate)/$multiplier,2);
       $kwota_net = ($waluta=='PLN') ? $kwota_net : ev_round(($kwota_net*$rate)/$multiplier,2);
       

   if ($vatrate_id ==0 || $vatrate_id ==5 ){ // bez vatowe i zwolnione   
      if($bookingtype_id==8 || $bookingtype_id==10){//koszty      		
	        $queryu1="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
	            VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,300,".($type_case).",".($case).",0,0,0,0,0,".$kwota1.",'$tresc1','".$ident1p.$ident2."','$data_plat','$data_dok','N',0)";
	                
	      	$licz++; 
	        $queryu2="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
	            VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,950,".($type_case).",".($contrahent_id).",0,0,0,0,0,".$kwota1.",'$tresc1','".$ident1p_inv.$ident2."','$data_plat','$data_dok','N',0)";
		      
         $cur=mysql_query($queryu1);
         if(!$cur){error_raport($queryu1);return false; }
         
         	$cur=mysql_query($queryu2);         
           	if(!$cur){error_raport($queryu2);return false; }
   
      }else if($bookingtype_id==9 || $bookingtype_id==11){////honoraria
      			$queryu3='';		      	
		        	$queryu1="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,700,".($kz).",".($type_case).",".($contrahent_id).",0,0,0,0,".$kwota1.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";
		      		$licz++; 
		        	$queryu2="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,997,".(5).",".($contrahent_id).",0,0,0,0,0,".$kwota1.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";		      	
         $cur=mysql_query($queryu1);
         if(!$cur){error_raport($queryu1);return false; }
         
         $cur=mysql_query($queryu2);         
         if(!$cur){error_raport($queryu2);return false; }
		
         
        
      }
       $licz++;
    }else{// z vat
		if ($kz==1){ // kraj
					$queryu1="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,700,".($kz).",".($type_case).",".($contrahent_id).",0,0,0,0,".$kwota_net.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";
		      		$licz++; 
		        	$queryu2="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,997,".$simple_vatrate_id.",".($contrahent_id).",0,0,0,0,0,".$kwota_net.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";
		      		
		      		$licz++; 
		        	$queryu3="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,222,1,0,0,0,0,0,0,".$kwota_vat.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";
		        	$licz++;
		}else{
					$queryu1="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,701,".($kz).",".($contrahent_id).",0,0,0,0,0,".$kwota_net.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";
		      		$licz++;
		      		 
		        	$queryu2="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,997,".$simple_vatrate_id.",".($contrahent_id).",0,0,0,0,0,".$kwota_net.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";		      		
		      		$licz++; 
		      		
		        	$queryu3="INSERT INTO coris_finances_bookings_cvde  (OKRES_,SYMDOW,NUMDOW,POZDOW,DATDOW,KON_WN_1,KON_WN_2,KON_WN_3,KON_WN_4,KON_WN_5,KON_WN_6,KON_WN_7,KON_WN_8,KON_MA_1,KON_MA_2,KON_MA_3,KON_MA_4,KON_MA_5,KON_MA_6,KON_MA_7,KON_MA_8,KWOTA_,TRESC_,IDENT_,DATPLA,DATDOK,NALZAP,ILOSC_  )
		            	VALUES (".BOOKING_OKRES.",'".$symdow."',".$numdow.",$licz,'$data_dow',0,0,0,0,0,0,0,0,222,1,0,0,0,0,0,0,".$kwota_vat.",'$tresc1','$ident2','$data_plat','$data_dok','N',0)";   						
		        	$licz++; 
		}   	
		
    	$cur=mysql_query($queryu1);
		if(!$cur){error_raport($queryu1);return false; }

		$cur=mysql_query($queryu2);         
		if(!$cur){error_raport($queryu2);return false; }

		$cur=mysql_query($queryu3);      	   
		if(!$cur){error_raport($queryu2);return false; }   
    	
    
    }
 }
    return true;
    
}

function getPaxNameCase($case_id){
  $query = "SELECT paxname,paxsurname  FROM coris_assistance_cases WHERE case_id='$case_id' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  return $row[1].' '.$row[0];

}

function getTowID($case_id){
  $query = "SELECT client_id  FROM coris_assistance_cases WHERE case_id='$case_id' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  return $row[0];

}

function get_suma_pozycji_waluta($invoice_out_id){
  $query = "SELECT SUM(gross_amount) FROM coris_finances_invoices_out_positions WHERE invoice_out_id='$invoice_out_id' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  return $row[0];
  return $row[0];

}

function  get_simple_waluta($waluta){
  $query = "SELECT simple_id FROM coris_finances_currencies  WHERE currency_id='$waluta'";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  return $row[0];

}

function check_country_contrahent($contrahent_id){
    $query = "SELECT country_id FROM coris_contrahents WHERE contrahent_id='$contrahent_id'";
    $mysql_result = mysql_query($query);
    $row = mysql_fetch_array($mysql_result);
    return $row[0] == 'PL' ? 1 : 2;

}

function get_case_no($case_id){
  $query = "SELECT number,year  FROM coris_assistance_cases WHERE case_id='$case_id' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  
  $rok = substr($row['year'],2);
  $number = $row['number'];
  //return $number.$rok;
  return $konto_number = 1000000  * intval(substr($row['year'],2)) + intval($row['number']);
  
}

function get_case_assist_no($case_id){
  $query = "SELECT number,year  FROM coris_assistance_cases WHERE case_id='$case_id' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  
  $rok = substr($row['year'],2);
  $number = $row['number'];
  
  return  intval($row['number']) .'/'.substr($row['year'],2) ;

}

function check_type_case($case){
  $query = "SELECT type_id  FROM coris_assistance_cases WHERE case_id='$case' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  return $row[0];
}
function check_case($case_id){
  
  $query = "SELECT number,year,paxname,paxsurname,type_id  FROM coris_assistance_cases WHERE case_id='$case_id' ";
  $mysql_result = mysql_query($query);
  $row = mysql_fetch_array($mysql_result);
  
  $rok = substr($row['year'],2);
  $konto_number = 1000000  * intval(substr($row['year'],2)) + intval($row['number']);
  
  $query = "SELECT count(*) FROM coris_finances_bookings_cvsp WHERE NR_SPR='0".$konto_number."'";
  
  $mysql_result = mysql_query( $query);
  $row_ch = mysql_fetch_array($mysql_result);
   $numRecords  = $row_ch[0];
  mysql_free_result($mysql_result);
  
  
  if ($numRecords==0){
     $nazwa_spr = $row['type_id'].'-'.$row['paxname'].' '.$row['paxsurname'];
     $queryu="INSERT INTO coris_finances_bookings_cvsp (NR_SPR,NAZSPR,KONANL)  VALUES ( '0".$konto_number."','".mysql_escape_string($nazwa_spr)."',".$konto_number.")";
     $cur=mysql_query($queryu);
     if(!$cur){
       echo "<br>Error insert case: ".$queryu;
       return false;
     }else 
         return $konto_number;
  }else{
    return $konto_number;
  }  
}


function check_contrahent_simple($contrahent_id){
  $query = "SELECT o_klsimple FROM  coris_contrahents  WHERE contrahent_id='$contrahent_id' ";
  $mysql_result = mysql_query($query);
  if (mysql_num_rows($mysql_result)==0) return false;
  $row = mysql_fetch_array($mysql_result);
    
  if ($row[0] != "")
    return true;
  return 
    false;   
}

function check_contrahent_konto_anal($contrahent_id){
  $query = "SELECT o_klsimple FROM  coris_contrahents  WHERE contrahent_id='$contrahent_id' AND o_klsimple<>''";
  $mysql_result = mysql_query($query);
  if (mysql_num_rows($mysql_result)==0) return false;
  $row = mysql_fetch_array($mysql_result);
  
  $query = "SELECT count(*) FROM coris_finances_bookings_sbko WHERE IKONTR='".$row[0]."'";
  $count = mysql_query( $query);
    $row = mysql_fetch_array($count);
    $numRecords = $row[0];
  mysql_free_result($count);
  
  if ($numRecords>0)
    return true;
  return 
    false;   
}


function convert_txt_to_simple($txt){
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

function getVatSimple($vatrate_id){
	if ($vatrate_id > 0){
		$query = "SELECT simple_id FROM coris_finances_vatrates WHERE vatrate_id='$vatrate_id'";
		$mysql_result = mysql_query($query);
		$row=mysql_fetch_array($mysql_result);
		return $row['simple_id'];
	}else{
		return 5;
		
	}
	
}

function error_raport($queryu){
	mail("krzysiek@poczta.evernet.com.pl","DEKRET ERROR", "Error insert dekret: \n".$queryu."\n\n".mysql_error());	
}


function getKursy($table_id,$ratetype_id,$table_currency){
	
	
	$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_pln, coris_finances_currencies_tables_rates.multiplier AS rate_to_pln_mult,  REPLACE((coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate), '.', ',')  AS rate_to_ext, coris_finances_currencies_tables_rates.table_id, REPLACE(coris_finances_currencies_tables_rates.rate, '.', ',') AS rate, coris_finances_currencies_tables.quotation_date, coris_finances_currencies_tables.publication_date, coris_finances_currencies_tables.ratetype_id,coris_finances_currencies_tables.number
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
			WHERE coris_finances_currencies_tables.table_id = '$table_id' AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables.ratetype_id='".$ratetype_id."' AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";


		$mysql_result = mysql_query($query);
		return $mysql_result;
}

?>