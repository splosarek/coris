<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE coris_menu_topics SET `order`=%s, `value`=%s, active=%s WHERE topic_id=%s",
                       GetSQLValueString($_POST['order'], "int"),
                       GetSQLValueString($_POST['value'], "text"),
                       GetSQLValueString($_POST['active'], "int"),
                       GetSQLValueString($_POST['topic_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $cn) or die(mysql_error());

	if (isset($_POST['department_id'])) {
		$query = sprintf("DELETE FROM coris_menu_topics2departments WHERE topic_id = %s", 
				GetSQLValueString($_POST['topic_id'], "int"));

		if (!$result = mysql_query($query, $cn))
			die (mysql_error());
	
		 foreach ($_POST['department_id'] as $department_id) {
			  if ($department_id != "") {
				$query = sprintf("INSERT INTO coris_menu_topics2departments (topic_id, department_id) VALUES (%s, %s)", 
                        GetSQLValueString($_POST['topic_id'], "int"),
						GetSQLValueString($department_id, "int"));
	
				if (!$result = mysql_query($query, $cn))
					die (mysql_error());
			  }
		 }
	}  


  $updateGoTo = "GEN_menu_details_topic.php?update=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  exit();
}

$colname_topic = "1";
if (isset($_GET['topic_id'])) {
  $colname_topic = (get_magic_quotes_gpc()) ? $_GET['topic_id'] : addslashes($_GET['topic_id']);
}

$query_topic = sprintf("SELECT topic_id, `order`, `value`, active FROM coris_menu_topics WHERE topic_id = %s", $colname_topic);
$topic = mysql_query($query_topic, $cn) or die(mysql_error());
$row_topic = mysql_fetch_assoc($topic);
$totalRows_topic = mysql_num_rows($topic);

$id_departments = "0";
if (isset($_GET['topic_id'])) {
  $id_departments = (get_magic_quotes_gpc()) ? $_GET['topic_id'] : addslashes($_GET['topic_id']);
}

$query_departments = sprintf("SELECT coris_users_departments.department_id, coris_users_departments.`value`, coris_menu_topics2departments.department_id AS department_id_sel FROM coris_users_departments LEFT JOIN coris_menu_topics2departments ON coris_users_departments.department_id = coris_menu_topics2departments.department_id AND coris_menu_topics2departments.topic_id = %s ORDER BY coris_users_departments.`value`", $id_departments);
$departments = mysql_query($query_departments, $cn) or die(mysql_error());
$row_departments = mysql_fetch_assoc($departments);
$totalRows_departments = mysql_num_rows($departments);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?=  GEN_MENU_SZCZTEM ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body <?php if (isset($_GET["update"])) { echo "onload=\"opener.location='GEN_menu.php';\""; } ?>>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table width="100%" align="center">
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_KOLEJNOSC ?></td>
			<td><input name="order" type="text" value="<?php echo $row_topic['order']; ?>" size="10"></td>
		    <td><div align="right"><?= GEN_MENU_DZIALY ?></div></td>
		    <td rowspan="4"><select name="department_id[]" size="6" multiple id="department_id[]">
		    	<?php
do {  
?>
		    	<option value="<?php echo $row_departments['department_id']?>"<?php if (!(strcmp($row_departments['department_id'], $row_departments['department_id_sel']))) {echo "SELECTED";} ?>><?php echo $row_departments['value']?></option>
		    	<?php
} while ($row_departments = mysql_fetch_assoc($departments));
  $rows = mysql_num_rows($departments);
  if($rows > 0) {
      mysql_data_seek($departments, 0);
	  $row_departments = mysql_fetch_assoc($departments);
  }
?>
	    	</select></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_NAZWA ?></td>
			<td><input type="text" name="value" value="<?php echo $row_topic['value']; ?>" size="15"></td>
		    <td>&nbsp;</td>
	    </tr>
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_AKT ?></td>
			<td><select name="active">
					<option value="1" <?php if (!(strcmp(1, $row_topic['active']))) {echo "SELECTED";} ?>><?= YES ?></option>
					<option value="0" <?php if (!(strcmp(0, $row_topic['active']))) {echo "SELECTED";} ?>><?= NO ?></option>
				</select>
			</td>
		    <td>&nbsp;</td>
	    </tr>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input name="Submit" type="submit" class="submit" id="Submit" value="<?= SAVE ?>"></td>
		    <td>&nbsp;</td>
	    </tr>
	</table>
	<input type="hidden" name="MM_update" value="form1">
	<input type="hidden" name="topic_id" value="<?php echo $row_topic['topic_id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($topic);

mysql_free_result($departments);
?>