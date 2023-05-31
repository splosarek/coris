<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');
include_once('../lib/lib_allianz.php');

$kid = getValue('kid');

$result = array();
$opcje = array();



if ($kid>0){
		$query = "SELECT *  FROM coris_allianz_kola   WHERE ID='$kid' ";										
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);

		$result= array();
		
		$result['kolo_dane'] = array(
		'ID' => $row['ID'],		
		'nazwa' => iconv('latin2','UTF-8',$row['nazwa']),		
		'adres' => iconv('latin2','UTF-8',$row['adres']),		
		'kod' => $row['kod'],		
		'miejscowosc' => iconv('latin2','UTF-8',$row['miejscowosc']),		
		'ZO' => iconv('latin2','UTF-8',$row['ZO']),		
		'konto_bankowe' => ($row['konto_bankowe'] != null ? $row['konto_bankowe'] : '')		
		
		);
		$result['ubezpieczenie'] = AllianzCase::ubezpieczenie($kid);			
		$result['szacujacy'] = AllianzCase::listaSzacujacych($kid);			
}else if ($kid=='new'){
	$result= array();
		
		$result['kolo_dane'] = array(
		'ID' => '',		
		'nazwa' => '',		
		'adres' => '',		
		'kod' => '',		
		'miejscowosc' => '',		
		'ZO' => '',		
		'konto_bankowe' => ''		
		
		);
		$result['szacujacy'] =  array('new' => '--  Nowy szacuj±cy --');
	
}

echo json_encode($result);


?>