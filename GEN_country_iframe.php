<?php require_once('include/include.php'); 

$tryb=0;
if (isset($_GET['country'])   ) {
  $country = $_GET['country'] ;
}else
	exit();




?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
</head>

<body>
<script language="JavaScript">
<?
if ($tryb==0){
$query = "SELECT REPLACE(ROUND(($sum * coris_finances_currencies_tables_rates.multiplier)  / coris_finances_currencies_tables_rates.rate  , 2) , '.', ',') AS amount, coris_finances_currencies_tables_rates.table_id, REPLACE(coris_finances_currencies_tables_rates.rate, '.', ',') AS rate, coris_finances_currencies_tables.quotation_date, coris_finances_currencies_tables.publication_date, coris_finances_currencies_tables.ratetype_id
FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
WHERE coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id AND coris_finances_currencies_tables.ratetype_id=1   AND coris_finances_currencies_tables_rates.currency_id = '$currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

//echo $query;
$mysql_result = mysql_query($query);
//echo mysql_error();
$num_rows = mysql_num_rows($mysql_result);
$row = mysql_fetch_array($mysql_result);

	if ($num_rows>0){
			echo "parent.".$amount.".value= '".$row['amount']."'\n";
		   	echo "parent.document.form1.publication_date.value = '". $row['publication_date']."'\n";
		   	echo "parent.document.form1.rate.value = '". $row['rate']."'\n";
		    echo "parent.document.form1.table_id.value = '". $row['table_id']."'\n";
		   	echo "parent.document.form1.ratetype_id.selectedIndex = '". $row['ratetype_id']."'\n";
		   	
		   	
		//	echo $row['table_id']; 
	}else{
		echo "alert('Error curency');\n";
		echo "parent.".$amount.".value= '0.00'";
		

	}
}else if ($tryb==1){
	
	$sum=1;
$query = "SELECT REPLACE(ROUND(($sum * coris_finances_currencies_tables_rates.multiplier)  / coris_finances_currencies_tables_rates.rate  , 2) , '.', ',') AS amount, coris_finances_currencies_tables_rates.table_id, REPLACE(coris_finances_currencies_tables_rates.rate, '.', ',') AS rate, coris_finances_currencies_tables.quotation_date, coris_finances_currencies_tables.publication_date, coris_finances_currencies_tables.ratetype_id
FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
WHERE coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables.ratetype_id=1 AND coris_finances_currencies_tables_rates.currency_id = '$currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";


$mysql_result = mysql_query($query);
echo mysql_error();
$num_rows = mysql_num_rows($mysql_result);
$row = mysql_fetch_array($mysql_result);

	if ($num_rows>0){
		//	echo "parent.".$amount.".value= '".$row['amount']."'\n";
		   	echo "parent.document.form1.publication_date.value = '". $row['publication_date']."'\n";
		   	echo "parent.document.form1.rate.value = '". $row['rate']."'\n";
		    echo "parent.document.form1.table_id.value = '". $row['table_id']."'\n";
		   	echo "parent.document.form1.ratetype_id.selectedIndex = '". $row['ratetype_id']."'\n";
		   	
		   	
		//	echo $row['table_id']; 
	}else{
		echo "alert('Error curency');\n";
		echo "parent.".$amount.".value= '0.00'";
		

	}

}


		
?>
	

</script>
</body>
</html>