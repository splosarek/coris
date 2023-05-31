<?php require_once('include/include.php'); 

$tryb=0;
$year='';
$new_number='';
$case_id='';
$old_number='';
if (isset($_GET['case_id']) && isset($_GET['new_number']) && isset($_GET['year']) && isset($_GET['old_number'])  ) {
  $case_id = $_GET['case_id'] ;
  $new_number = $_GET['new_number'] ;
  $year = $_GET['year'] ;
  $old_number = $_GET['old_number'];
}else{
	echo '<script language="JavaScript">
		alert(\'Błędne dane\');
	</script>
	';
	exit();

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Untitled Document</title>
</head>

<body>
<script language="JavaScript">
<?
if ($tryb==0){
	$query = "SELECT case_id FROM coris_assistance_cases WHERE number='$new_number' AND year='$year'";
	$mysql_result = mysql_query($query); 
	if (mysql_num_rows($mysql_result)>0){
		echo "parent.document.form1.case_number.value = '". $old_number."'\n";
		echo "alert('Sprawa o takim numerze już istnieje!!');\n";
	}else{
		$query = "UPDATE coris_assistance_cases SET number='$new_number' WHERE case_id='$case_id' LIMIT 1";
		$mysql_result = mysql_query($query); 
		if ($mysql_result){
		    echo "parent.opener.document.location.reload(); ";
			echo "alert('Numer zmieniony');\n";
		}else {
			echo "parent.document.form1.case_number.value = '". $old_number."'\n";
			echo "alert('Błąd zmiany numeru sprawy '.".addslashes($query).");\n";	
		}
	}
			
}
		
?>
	

</script>
</body>
</html>