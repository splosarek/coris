<?php include('include/include.php');


$currentPage = $_SERVER["PHP_SELF"];

$maxRows_messages = 100;
$pageNum_messages = 0;
if (isset($_GET['pageNum_messages'])) {
  $pageNum_messages = $_GET['pageNum_messages'];
}
$startRow_messages = $pageNum_messages * $maxRows_messages;

$id_messages = "1";
if (isset($_SESSION['user_id'])) {
  $id_messages = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}

$query_messages = sprintf("SELECT coris_messages.message_id, coris_messages.`value`, coris_messages.urgent, coris_messages.`date`, coris_messages.user_id, coris_users.username, coris_colors.code, u2.surname AS recipient_username FROM coris_messages, coris_messages2recipients LEFT JOIN coris_users ON coris_users.user_id = coris_messages.user_id LEFT JOIN coris_colors ON coris_users.color_id = coris_colors.color_id LEFT JOIN coris_users u2 ON coris_messages2recipients.recipient_user_id = u2.user_id WHERE coris_messages.message_id = coris_messages2recipients.message_id AND (coris_messages2recipients.recipient_user_id = %s OR coris_messages.user_id = %s) AND DATEDIFF(coris_messages.`date`, NOW()) < 15 AND coris_messages.active = 1", $id_messages, $id_messages);
$query_limit_messages = sprintf("%s LIMIT %d, %d", $query_messages, $startRow_messages, $maxRows_messages);
$messages = mysql_query($query_limit_messages, $cn) or die(mysql_error());
$row_messages = mysql_fetch_assoc($messages);

if (isset($_GET['totalRows_messages'])) {
  $totalRows_messages = $_GET['totalRows_messages'];
} else {
  $all_messages = mysql_query($query_messages);
  $totalRows_messages = mysql_num_rows($all_messages);
}
$totalPages_messages = ceil($totalRows_messages/$maxRows_messages)-1;

$queryString_messages = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_messages") == false && 
        stristr($param, "totalRows_messages") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_messages = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_messages = sprintf("&totalRows_messages=%d%s", $totalRows_messages, $queryString_messages);

$query = sprintf("UPDATE coris_messages2recipients SET `read` = 1 WHERE recipient_user_id = %s AND `read` = 0", 
			  GetSQLValueString($id_messages, "int"));
if (!$result = mysql_query($query, $cn))
	die (mysql_error());

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Untitled Document</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
<!--
setTimeout("document.location = 'GEN_messages_iframe1.php'", 5000);
//-->
</script> 
<body onLoad="document.body.scrollTop = document.body.scrollHeight;">
<table border="0">
   <tr>
      <td align="center"><input name="" type="button" <?php if (!$pageNum_messages > 0) { echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value="<<|" onclick="document.location='<?php printf("%s?pageNum_messages=%d%s", $currentPage, 0, $queryString_messages); ?>'">
      </td>
      <td align="center"><input name="" type="button" <?php if (!$pageNum_messages > 0) { echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value="<<" onclick="document.location='<?php printf("%s?pageNum_messages=%d%s", $currentPage, max(0, $pageNum_messages - 1), $queryString_messages); ?>'">
      </td>
      <td align="center"><input name="" type="button" <?php if ($pageNum_messages >= $totalPages_messages) {  echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value=">>" onclick="document.location='<?php printf("%s?pageNum_messages=%d%s", $currentPage, min($totalPages_messages, $pageNum_messages + 1), $queryString_messages); ?>'">
      </td>
      <td align="center"><input name="" type="button" <?php if ($pageNum_messages >= $totalPages_messages) { echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value="|>>" onclick="document.location='<?php printf("%s?pageNum_messages=%d%s", $currentPage, $totalPages_messages, $queryString_messages); ?>'">
      </td>
   </tr>
</table>
<?php 
	$old_id = 0;
	$message = '';
	$multiple = 0;
	do { 
		if ($row_messages['user_id'] == $id_messages) {
			$multiple = 1;
			if ($old_id == $row_messages['message_id']) {
				echo ", <b>" . $row_messages['recipient_username'] . "</b>";									
				continue;
			} else {
				echo " " . $message . "<br>-&gt; <b>" . $row_messages['recipient_username'] . "</b>";						
			}
		} else {
			$multiple = 0;
			echo "<br>[<b><font color=\"" . $row_messages['code'] . "\">" . $row_messages['username'] . "</font></b>]: ";
			if ($row_messages['urgent']) {
				echo "<font style=\"background: yellow\">" . $row_messages['value'] . "</font>"; 
			} else  {
				echo $row_messages['value']; 			
			}
		}			
		$old_id = $row_messages['message_id'];
		$message = $row_messages['value'];			
	} while ($row_messages = mysql_fetch_assoc($messages)); 
	if ($multiple) 
		echo " ". $message;
?>
</body>
</html>
<?php
mysql_free_result($messages);
?>
