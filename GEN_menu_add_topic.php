<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coris_menu_topics (`order`, `value`, active) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['order'], "int"),
                       GetSQLValueString($_POST['value'], "text"),
                       GetSQLValueString($_POST['active'], "int"));

  
  $Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());

  $insertGoTo = "GEN_menu_add_topic.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_MENU_DODTEM ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table align="center">
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_KOLEJNOSC ?></td>
			<td><input type="text" name="order" value="" size="10"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_NAZWA  ?></td>
			<td><input type="text" name="value" value="" size="15"></td>
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