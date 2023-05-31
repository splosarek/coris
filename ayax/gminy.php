<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');
include_once('../lib/lib_allianz.php');

$wid = getValue('wid');
$pid = getValue('pid');

$result = array();
$opcje = array();



if ($wid>0 && $pid>0){			
		$query2 = "SELECT *  FROM _powiaty  WHERE POW='$pid' AND WOJ='$wid' AND GMI is not null  ORDER BY NAZWA";
		$mysql_result2 = mysql_query($query2);
		$result= array();			
			while ($row=mysql_fetch_array($mysql_result2)){					
						$result[] = array('ID' => $row['GMI'], 'nazwa' => iconv('latin2','UTF-8',$row['NAZWA'] . ' - ' . $row['NAZDOD'] ));					
			}   			
   			
}

echo json_encode($result);

?>