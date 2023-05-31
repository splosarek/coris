<?php include('include/include.php'); 



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coris_menu_elements (topic_id, `order`, `value`, resource, active) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['topic_id'], "int"),
                       GetSQLValueString($_POST['order'], "int"),
                       GetSQLValueString($_POST['value'], "text"),
                       GetSQLValueString($_POST['resource'], "text"),
                       GetSQLValueString($_POST['active'], "int"));

  
  $Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());

  $insertGoTo = "GEN_menu_add_element.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$query_topics = "SELECT topic_id, `value` FROM coris_menu_topics WHERE active = 1 ORDER BY value ASC";
$topics = mysql_query($query_topics, $cn) or die(mysql_error());
$row_topics = mysql_fetch_assoc($topics);
$totalRows_topics = mysql_num_rows($topics);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_MENU_DODELEM ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table align="center">
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_TEMAT ?></td>
			<td><select name="topic_id">
					<?php 
do {  
?>
					<option value="<?php echo $row_topics['topic_id']?>" ><?php echo $row_topics['value']?></option>
					<?php
} while ($row_topics = mysql_fetch_assoc($topics));
?>
				</select>
			</td>
		<tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_KOLEJNOSC ?></td>
			<td><input type="text" name="order" value="" size="10"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_NAZWA  ?></td>
			<td><input type="text" name="value" value="" size="15"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_ZASOB ?></td>
			<td><input type="text" name="resource" value="" size="20"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_AKT ?></td>
			<td><select name="active">
					<option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>><?= YES ?></option>
					<option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>><?= NO ?></option>
				</select>
			</td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input type="submit" class="submit" value="<?= SAVE ?>"></td>
		</tr>
	</table>
	<input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($topics);
?>
