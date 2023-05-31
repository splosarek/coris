<?php
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$sid = getValue('sid');
$wid = getValue('wid');
$result = array();

if ($sid>0 && $sid>0){
		$query = "SELECT *
		FROM coris_cardif_suma_ubezpieczenia  
		WHERE ID_wariant_umowy ='$wid' AND ID_swiadczenie='$sid' LIMIT 1";
		
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$result = array('suma'=> $row['suma'], 'currency_id' => $row['currency_id']);			
		}	
}else{
	$result = array('suma' => 0,'currency_id' => 'PLN');
}


echo json_encode($result);

?>