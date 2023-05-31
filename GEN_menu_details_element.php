<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE coris_menu_elements SET topic_id=%s, `order`=%s, `value`=%s, resource=%s, active=%s WHERE element_id=%s",
                       GetSQLValueString($_POST['topic_id'], "int"),
                       GetSQLValueString($_POST['order'], "int"),
                       GetSQLValueString($_POST['value'], "text"),
                       GetSQLValueString($_POST['resource'], "text"),
                       GetSQLValueString($_POST['active'], "int"),
                       GetSQLValueString($_POST['element_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $cn) or die(mysql_error());

  $updateGoTo = "GEN_menu_details_element.php?update=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  exit();
}

$colname_element = "1";
if (isset($_GET['element_id'])) {
  $colname_element = (get_magic_quotes_gpc()) ? $_GET['element_id'] : addslashes($_GET['element_id']);
}

$query_element = sprintf("SELECT element_id, topic_id, `order`, `value`, resource, active FROM coris_menu_elements WHERE element_id = %s", $colname_element);
$element = mysql_query($query_element, $cn) or die(mysql_error());
$row_element = mysql_fetch_assoc($element);
$totalRows_element = mysql_num_rows($element);


$query_topics = "SELECT topic_id, `value` FROM coris_menu_topics WHERE active = 1 ORDER BY value ASC";
$topics = mysql_query($query_topics, $cn) or die(mysql_error());
$row_topics = mysql_fetch_assoc($topics);
$totalRows_topics = mysql_num_rows($topics);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_MENU_SZCZEL ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body <?php if (isset($_GET["update"])) { echo "onload=\"opener.document.location='GEN_menu.php';\""; } ?>>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table align="center">
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_TEMAT ?></td>
			<td><select name="topic_id">
				<?php
do {  
?>
				<option value="<?php echo $row_topics['topic_id']?>"<?php if (!(strcmp($row_topics['topic_id'], $row_element['topic_id']))) {echo "SELECTED";} ?>><?php echo $row_topics['value']?></option>
				<?php
} while ($row_topics = mysql_fetch_assoc($topics));
  $rows = mysql_num_rows($topics);
  if($rows > 0) {
      mysql_data_seek($topics, 0);
	  $row_topics = mysql_fetch_assoc($topics);
  }
?>
				</select>
			</td>
		<tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_KOLEJNOSC ?></td>
			<td><input type="text" name="order" value="<?php echo $row_element['order']; ?>" size="10"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_NAZWA ?></td>
			<td><input type="text" name="value" value="<?php echo $row_element['value']; ?>" size="15"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_ZASOB ?></td>
			<td><input type="text" name="resource" value="<?php echo $row_element['resource']; ?>" size="20"></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_AKT ?></td>
			<td><select name="active">
				<option value="1" <?php if (!(strcmp(1, $row_element['active']))) {echo "SELECTED";} ?>><?= YES ?></option>
				<option value="0" <?php if (!(strcmp(0, $row_element['active']))) {echo "SELECTED";} ?>><?= NO ?></option>
				</select>
			</td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input type="submit" class="submit" value="<?= SAVE ?>"></td>
		</tr>
	</table>
	<input type="hidden" name="MM_update" value="form1">
	<input type="hidden" name="element_id" value="<?php echo $row_element['element_id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($element);

mysql_free_result($topics);
?>