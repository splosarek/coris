<?php
ini_set('display_errors',0);

    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$wid = getValue('wid');
$case_id = getValue('case_id');


$result = array();
$opcje = array();

if ($wid>0 ){
	
			$query = "SELECT coris_europa_wariant_umowy.ID,coris_europa_wariant_umowy.kod_taryfy,coris_europa_wariant_umowy.opcja_status,coris_europa_wariant_umowy.nazwa,coris_europa_announce_opcje.ID_opcja 
					FROM coris_europa_wariant_umowy LEFT JOIN coris_europa_announce_opcje ON ID_opcja=coris_europa_wariant_umowy.ID 
															AND coris_europa_announce_opcje.case_id = '$case_id'   
					WHERE
					
					 coris_europa_wariant_umowy.opcja=1  AND  coris_europa_wariant_umowy.ID_parent ='$wid' ORDER BY kolejnosc";	
			
		
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$opcje[] = array('ID'=>$row['ID'],'ID_opcja'=>$row['ID_opcja'],'opcja_status'=>$row['opcja_status'],'nazwa'=> iconv('latin2','UTF-8',$row['nazwa'].' ('.$row['kod_taryfy'].')'));			
		}	
}

echo json_encode(array('wariant' => $result,'opcje' => $opcje));

?>