<?php
session_start();

    	  header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



	$col_ds = 'description_en';
	
	if ($_SESSION['GUI_language']=='pl'){
		
		$col_ds = 'description_pl';		
	}
	
$policy_type = getValue('policy_type');
$result = array();

if ($policy_type>0){
		$query = "SELECT DISTINCT coris_nhc_cause.* FROM coris_nhc_cause,coris_nhc_code_g  WHERE  coris_nhc_cause.main_cause = coris_nhc_code_g.main_cause AND coris_nhc_code_g.product_code = '$policy_type'  ORDER BY ".$col_ds;
	
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$result[] = array('ID'=>$row['main_cause'],'nazwa'=> iconv('latin2','UTF-8',$row[$col_ds]));			
		}	
}

echo json_encode($result);

?>