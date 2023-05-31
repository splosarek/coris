<?php

class Finance{
	
	
	
	static function ev_round($liczba,$precyzja=0){
		$mnoznik = pow(10,$precyzja);
		if ($liczba>0){
			$wyn = $liczba*$mnoznik + 0.5;	
			return (self::ev_intval($liczba*$mnoznik + 0.5))/$mnoznik;	
		}else{
			return (self::ev_intval($liczba*$mnoznik - 0.5))/$mnoznik;	
		}
	}


	static function ev_intval($liczba){
		$liczba_tmp = (String) $liczba;
		$poz = strpos($liczba_tmp,'.');
		if ($poz === false ){
			return $liczba;
		}else{
			return substr($liczba_tmp,0,$poz);
		}
	}

	
	static function print_currency($val,$prec=2,$sep=''){
		if (is_numeric($val)){   		
			return number_format(self::ev_round($val,$prec), $prec, ',', $sep);		
		}else{
			$val = str_replace(',','.',$val);				
			return number_format(self::ev_round($val,$prec), $prec, ',',$sep);		
		}
	}
	

	static function slownie($kwota){
		
		include_once('Numbers/Words.php'); 
		if (class_exists('Numbers_Words')){
			$kw = str_replace(',','.',$kwota);
			$gr = self::ev_round(($kw - floor($kw))*100);
		
			$ret = Numbers_Words::toWords(floor($kw),"pl") ;
            $ret = iconv('UTF-8','iso-8859-2',$ret);
			$slownie =  $ret.' PLN';
			if ($gr>0){
				$slownie .=  ', '.$gr.'/100 groszy';
			}
			
			return $slownie;
		}
	}
	
	
	static function checkNRB($nrb) {
		 // Usuniecie spacji
		  $nrb = str_replace(' ', '', $nrb);
		  // Usuniecie -
		  $nrb = str_replace('-', '', $nrb);
			
		  if (strlen($nrb)!=26)
		   return 0;
		  $W = array(1,10,3,30,9,90,27,76,81,34,49,5,50,15,53,45,62,38,89,17,
		                   73,51,25,56,75,71,31,19,93,57);
		
		  $nrb .= "2521";
		  $nrb = substr($nrb,2).substr($nrb,0,2);
		  $Z =0;
		  for ($i=0;$i<30;$i++)
		    $Z += $nrb[29-$i] * $W[$i];
		  if ($Z % 97 == 1)
		    return 1;
		  else
		    return 0;
	}
	
	static function print_currency_all($name,$default,$class,$onclick=''){
		$query_currencies = "SELECT coris_finances_currencies.currency_id FROM coris_finances_currencies WHERE coris_finances_currencies.active = 1 ORDER BY coris_finances_currencies.currency_id";
		$mysql_result = mysql_query($query_currencies) or die(mysql_error());
		$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'><option value=""></option>';
		while ($row_currencies = mysql_fetch_array($mysql_result)){
			$sel = ($row_currencies['currency_id']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row_currencies['currency_id'].'" '.$sel.'>'.$row_currencies['currency_id'].'</option>'; 	
		}
  		$result .= '</select>';	
  		return $result;
	}
	
	
	static function getKurs($publication_date,$ratetype_id,$currency_id,$table_id=0,$table_source=1){
		$query = "SELECT 			
			coris_finances_currencies_tables_rates.rate ,
			coris_finances_currencies_tables_rates.multiplier AS multiplier,
			coris_finances_currencies_tables_rates.rate   AS rate_to_pln,						 			
			(coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate)  AS rate_to_ext, 
			coris_finances_currencies_tables_rates.table_id, coris_finances_currencies_tables.quotation_date, 
			coris_finances_currencies_tables.publication_date, 
			coris_finances_currencies_tables.ratetype_id,
			coris_finances_currencies_tables.number As table_no 
		FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
		WHERE ";
		if ( $table_id >0){
			$query .= " coris_finances_currencies_tables.table_id='$table_id' ";
		}else{
			$query .= " coris_finances_currencies_tables.source_id='$table_source' 
			AND coris_finances_currencies_tables.publication_date < '$publication_date' 
			AND  coris_finances_currencies_tables.ratetype_id='".$ratetype_id."'";
		}
		$query .= " AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  
				AND coris_finances_currencies_tables_rates.currency_id = '$currency_id' 
				ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

		//echo $query;
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row;
	}


	static function getKursTD($publication_date,$ratetype_id,$currency_id,$table_id=0,$table_source=1){ //today rate
		$query = "SELECT 			
			coris_finances_currencies_tables_rates.rate ,
			coris_finances_currencies_tables_rates.multiplier AS multiplier,
			coris_finances_currencies_tables_rates.rate   AS rate_to_pln,						 			
			(coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate)  AS rate_to_ext, 
			coris_finances_currencies_tables_rates.table_id, coris_finances_currencies_tables.quotation_date, 
			coris_finances_currencies_tables.publication_date, 
			coris_finances_currencies_tables.ratetype_id,
			coris_finances_currencies_tables.number As table_no 
		FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
		WHERE ";
		if ( $table_id >0){
			$query .= " coris_finances_currencies_tables.table_id='$table_id' ";
		}else{
			$query .= " coris_finances_currencies_tables.source_id='$table_source' 
			AND coris_finances_currencies_tables.publication_date <= '$publication_date' 
			AND  coris_finances_currencies_tables.ratetype_id='".$ratetype_id."'";
		}
		$query .= " AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  
				AND coris_finances_currencies_tables_rates.currency_id = '$currency_id' 
				ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

	//	echo $query;
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		return $row;
	}



    static function  getBankTransferDataClaims($case_id,$source,$payment_id){

        $case = new CorisCase($case_id);

        $tow_id = $case->getClient_id();

        $tables1 = array(
            14189 => 'coris_chubba_payment',
            11 => 'coris_europa_payment',
            496 => 'coris_gothaer_payment',
            5 => 'coris_vig_payment',
            7 => 'coris_vig_payment',
            17241 => 'coris_barclaycard_payment',
            17708 => 'coris_voyage_payment',
        );

        $tables2 = array(
            14189 => 'coris_chubba_announce',  //1
            11 => 'coris_europa_announce',  //2
            496 => 'coris_gothaer_announce',  //3
            7 => 'coris_vig_announce',  //4
            5 => 'coris_vig_announce',  //5
            17241 => 'coris_barclaycard_announce',  //6
            17708 => 'coris_voyage_announce',  //7

        );


        $query = "SELECT * FROM ".$tables2[$tow_id]." WHERE 	case_id = '$case_id' ";

        $mr = mysql_query($query);
        $row = mysql_fetch_array($mr);

        $bank_account = $row['wyplata_nr_konta_bankowego'];
        $bank_name = $row['wyplata_nazwa_banku'];
        $bank_swift = $row['wyplata_swift'];


        return array(
            'name' => $case->getPaxname() . ' '.$case->getPaxsurname(),
            'address' => $case->getPaxAddress(),
            'post' => $case->getPaxPost(),
            'city' => $case->getPaxCity(),
            'country' => $case->getPaxCountry(),
            'account_no' => $bank_account,
            'bank' => $bank_name,
            'bank_swift' => $bank_swift
        );
    }

}

?>