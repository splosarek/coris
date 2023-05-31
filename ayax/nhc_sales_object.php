<?php
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');


$policy_type = getValue('policy_type');
$result = array();

if ($policy_type>0){
		$query = "SELECT *
		FROM coris_nhc_code_b  
		WHERE product_code  ='$policy_type'  ORDER BY description ";
	
		$mysql_result = mysql_query($query);

		while ($row=mysql_fetch_array($mysql_result)) {
			$result[] = array('ID'=>$row['sales_object_description'],'nazwa'=> iconv('latin2','UTF-8',$row['description']));			
		}	
}

echo json_encode($result);

?>