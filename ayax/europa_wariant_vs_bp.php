<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$tid = getValue('tid');
$bid = getValue('bid');

$result = array();
$opcje = array();

if ($bid>0 && $tid>0){
		$query = "SELECT coris_europa_wariant_umowy.ID,coris_europa_wariant_umowy.nazwa,coris_europa_wariant_umowy.kod_taryfy 
				FROM coris_europa_wariant_umowy,coris_europa_pakiet    WHERE opcja=0  
					AND  coris_europa_pakiet.ID_wariant  = coris_europa_wariant_umowy.ID
					AND coris_europa_pakiet.ID_biuro  = '".$bid."'
					AND ID_typ_umowy ='$tid' ORDER BY kolejnosc";					
		
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$result[] = array('ID'=>$row['ID'],'nazwa'=> iconv('latin2','UTF-8',$row['nazwa'].' ('.$row['kod_taryfy'].')'));			
		}	

		$query = "SELECT * FROM coris_europa_biura_podrozy  WHERE  ID='$bid' ";
		$mysql_result = mysql_query($query);
		$row=mysql_fetch_array($mysql_result);
		$seria_polisy = $row['seria_polisy'];
		
}

echo json_encode(array('wariant' => $result,'opcje' => $opcje,'seria_polisy' => $seria_polisy ));

?>