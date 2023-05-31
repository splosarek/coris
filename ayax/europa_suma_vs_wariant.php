<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$zid = getValue('zid');
$result = array();

if ($zid>0){
		$query = "SELECT *
		 FROM coris_europa_lista_swiadczen 
		WHERE ID_wariant='$zid' AND ID_podlimit=0 ORDER BY ID LIMIT 1";
		
		$mysql_result = mysql_query($query);
//echo $query;
		while ($row=mysql_fetch_array($mysql_result)) {
			$result = array('suma'=> print_currency($row['kwota']), 'currency_id' => $row['currency_id']);			
		}	
}else{
	$result = array('suma' => 0,'currency_id' => 'PLN');
}


echo json_encode($result);

?>