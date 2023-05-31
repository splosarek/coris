<?php
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$rid = getValue('tid');
$result = array();

if ($rid>0){
		$query = "SELECT ID,nazwa
		FROM coris_cardif_wariant_umowy 
		WHERE ID_typ_umowy ='$rid'  ORDER BY nazwa";
		
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$result[] = array('ID'=>$row['ID'],'nazwa'=> iconv('latin2','UTF-8',$row['nazwa']));			
		}	
}

echo json_encode($result);

?>