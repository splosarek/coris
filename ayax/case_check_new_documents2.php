<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$case_id = getValue('case_id');

$ilosc=0;
if ($case_id>0 ){
		$query = "SELECT count(*) FROM store_interaction WHERE ID_case='$case_id' AND new=1";
		
		$mysql_result = mysql_query($query);

		$row=mysql_fetch_array($mysql_result);

			$ilosc = $row[0];

		
}

echo json_encode(array('ilosc' => $ilosc  ) );

?>