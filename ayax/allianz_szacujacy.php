<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');
include_once('../lib/lib_allianz.php');

$id = getValue('id');


$result = array();
$opcje = array();



if ($id>0){			
		$query2 = "SELECT *  FROM coris_allianz_kola_szacujacy   WHERE ID='$id' ";
		$mysql_result2 = mysql_query($query2);
					
		$row=mysql_fetch_array($mysql_result2);					
		$result = array('ID' => $row['ID'], 'nazwa' => iconv('latin2','UTF-8',$row['imie_nazwisko']), 'telefon' => $row['telefon'] );					
		   			   		
}

echo json_encode($result);

?>