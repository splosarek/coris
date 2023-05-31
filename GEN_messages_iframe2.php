<?php include('include/include.php'); 


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$user_id = "1";
	if (isset($_SESSION['user_id'])) {
	  $user_id = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
	}
  $insertSQL = sprintf("INSERT INTO coris_messages (`value`, urgent, user_id, date) VALUES (%s, %s, %s, NOW())",
                       GetSQLValueString($_POST['message'], "text"),
                       GetSQLValueString(isset($_POST['urgent']) ? "true" : "", "defined","1","0"),							
                       GetSQLValueString($user_id, "int"));

  
  $Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());

	
	$query_message = "SELECT message_id FROM coris_messages WHERE message_id = @@identity";
	$message = mysql_query($query_message, $cn) or die(mysql_error());
	$row_message = mysql_fetch_assoc($message);
	$totalRows_message = mysql_num_rows($message);


	if (isset($_POST['user_id'])) {
		 foreach ($_POST['user_id'] as $user_id) {
			  if ($user_id != "") {
				$query = sprintf("INSERT INTO coris_messages2recipients (message_id, recipient_user_id) VALUES (%s, %s)", 
                       GetSQLValueString($row_message['message_id'], "int"),
	                    GetSQLValueString($user_id, "int"));

					if (!$result = mysql_query($query, $cn))
						die (mysql_error());
			  }
		 }
	}  
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Untitled Document</title>
</head>

<body onLoad="top.iframe1.document.location.reload(); top.form1.message.value=''; top.form1.message.focus();">
</body>
</html>