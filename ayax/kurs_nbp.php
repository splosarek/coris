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


$table_id = getValue('table_id');
$table_date = getValue('table_date');
$currency = getValue('currency');


if ($currency=='PLN'){
		$status=1;
		$table_id = 1	;
		$rate = 1;
		$table_no = '';	
		$table_date = '';		
}else{
$mysql_result = getKursy($table_date,1,$currency,1,$table_id);

	$num_rows = mysql_num_rows($mysql_result);
	if ($num_rows>0){
		$row = mysql_fetch_array($mysql_result);
		$status=1;
		$table_id = $row['table_id']	;
		$rate = $row['rate']/$row['rate_to_pln_mult'];
		$table_no = $row['number'];	
		$table_date = $row['publication_date'];
	}else{
		
		
	}
}


$result = array('table_id'=>$table_id,'table_date' => $table_date,'table_no' => $table_no,'rate' => $rate,'ratetype_id' => $ratetype_id,'status' => $status);
echo json_encode(array('item' => $result));



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
		$query .= " coris_finances_currencies_tables.publication_date < '$publication_date' AND  coris_finances_currencies_tables.ratetype_id='".$ratetype_id."'";
			
		$query .= 	" AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";


		$mysql_result = mysql_query($query);
		return $mysql_result;
}



?>