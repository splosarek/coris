<?php

require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');




$table_id = 0;
$table_date = '';
$table_no = '';
$rate = 1;
$currency;
$ratetype_id = 1;
$status = 0;


$case_id = getValue('case_id');
$currency = getValue('currency_id');
$table_id = intval(getValue('table_id'));


if ($currency=='PLN'){
		$status=1;
		$table_id = 1	;
		$rate = 1;
		$table_no = '';	
		$table_date = '';		
}else{
	
	$contrahent_details = CaseCurrency::getContrahnetInitials($case_id);
	
	$table_source = $contrahent_details['table_source'];
	$table_invoice = $contrahent_details['table_invoice'];
	
	$currency_date = $contrahent_details['currency_date'];	
	$currency_date = $currency_date != '0000-00-00' ? $currency_date : date('Y-m-d');
	
	$contrahent_id = $contrahent_details['contrahent_id'];	
	
	$mysql_result = CaseCurrency::getKurs($currency_date,$table_invoice,$currency,$table_source,$table_id);

	$num_rows = mysql_num_rows($mysql_result);
	if ($num_rows>0){
		$row = mysql_fetch_array($mysql_result);
		$status=1;
		$table_id = $row['table_id']	;
		$rate = $row['rate']/$row['rate_to_pln_mult'];
		$table_no = $row['number'];	
		$table_date = $row['publication_date'];
		$table_source = $row['source'];
	}else{
		
		
	}
}


$result = array('table_id'=>$table_id,'table_date' => $table_date,'table_no' => $table_no,'rate' => $rate,'ratetype_id' => $ratetype_id,'table_source' => $table_source,'status' => $status);
echo json_encode(array('item' => $result));


class CaseCurrency{

static function getKurs($publication_date,$ratetype_id,$table_currency,$table_source,$table_id){
			
	$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_pln, 
			coris_finances_currencies_tables_rates.multiplier AS rate_to_pln_mult,  
			(coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate) AS rate_to_ext, 
			coris_finances_currencies_tables_rates.table_id,
			coris_finances_currencies_tables_rates.rate AS rate,
			coris_finances_currencies_tables.quotation_date,
			coris_finances_currencies_tables.publication_date, 
			coris_finances_currencies_tables.ratetype_id,
			coris_finances_currencies_tables.number,
			coris_finances_currencies_tables_source.value As source    
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables,coris_finances_currencies_tables_source 
			  
			WHERE coris_finances_currencies_tables.source_id='$table_source'  AND coris_finances_currencies_tables_source.ID = coris_finances_currencies_tables.source_id AND";						
	if ( $table_id>0)
		$query .= " coris_finances_currencies_tables.table_id='$table_id' ";
	else
		$query .= " coris_finances_currencies_tables.publication_date < '$publication_date' AND  coris_finances_currencies_tables.ratetype_id='".$ratetype_id."'";
			
		$query .= 	" AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

		$mysql_result = mysql_query($query);
		
		if (mysql_num_rows($mysql_result) == 0 && ($table_source==1 && ($ratetype_id==2 || $ratetype_id==3)) ){
			return self::getKurs($publication_date,1,$table_currency,$table_source,$table_id);
			//return $mysql_result;
		}else if (mysql_num_rows($mysql_result) == 0 && $table_source==2  ){ // jesli brak kursu w CITI to bierzemy sredni NBP
			return self::getKurs($publication_date,1,$table_currency,1,$table_id);			
		}else{ 		
			return $mysql_result;
		}
}



static function getContrahnetInitials($case_id){
	
	$case_date = '';
	if ($case_id>0){
		$query = "SELECT cac.date,cac.eventdate ,cacd.notificationdate,cac.client_id FROM coris_assistance_cases_details cacd,coris_assistance_cases cac 
				WHERE cac.case_id='$case_id' AND cac.case_id=cacd.case_id ";		
		
		$mysql_result = mysql_query($query);
		$row_case=mysql_fetch_array($mysql_result);
		$case_date = $row_case['notificationdate'];				
		$contrahent_id = $row_case['client_id'];						
	}else {
		$case_date = 'now()';
		raporting_mail("case_id=0","case_id=0\n\n".$query);		
		return ;		
	}
	$query = "SELECT * FROM coris_contrahents_initials  WHERE contrahent_id='$contrahent_id' AND active_date <= '$case_date' ORDER BY active_date desc ,ID desc LIMIT 1";
	$mysql_result = mysql_query($query) or die($query."<br>".mysql_error());
	$row=mysql_fetch_array($mysql_result);		
	
	
	//rodzaj kursy
	$table_invoice = $row['table_invoice'] > 0 ?   $row['table_invoice'] : 1;	
				
	//zrodlo walut
	$table_source = $row['table_invoice_source']>1 ? $row['table_invoice_source'] : 1 ;	
	
	//daty
	

	$notificationdate = $row_case['notificationdate'];
	$eventdate = $row_case['eventdate'];
	$openDate = substr($row_case['date'],0,10);
	
	$currency_date = $notificationdate;
	
	if ($row['event_date_rate'] == 1 && $eventdate != '000-00-00' )
		$currency_date = $eventdate;
		
	if ($row['open_date_rate'] == 1  && $openDate != '000-00-00')
		$currency_date = $openDate;
		
		
	
	
	return array('contrahent_id' => $contrahent_id,'currency_date' => $currency_date, 'table_source' => $table_source ,'table_invoice' => $table_invoice );
	
  }
}
?>