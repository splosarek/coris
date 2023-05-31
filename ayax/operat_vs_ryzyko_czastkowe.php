<?php
ini_set('display_errors',0);
//header('Content-type: application/json; charset=UTF-8');
//header('Content-type: text/plain; charset="iso-8859-2"');

		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$rid = getValue('rid');
$result = array();

if ($rid>0){
		$query = "SELECT  operat.ID, operat.numer, operat.nazwa 
		FROM coris_signal_ryzyko_operat operat,coris_signal_ryzyko_operat_vs_ryz_czastkowe    operat_vs
		WHERE status=1 AND operat_vs.ID_operat= operat.ID AND operat_vs.ID_ryzyko_czastkowe='$rid'  ORDER BY operat_vs.kolejnosc, nazwa";
		
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$result[] = array('ID'=>$row['ID'],'numer' => $row['numer'],'nazwa'=> iconv('latin2','UTF-8',$row['nazwa']));
			//$result[] = array('ID'=>$row['ID'],'numer' => $row['numer'],'nazwa'=> $row['nazwa']);
		}	
}

echo json_encode($result);

?>