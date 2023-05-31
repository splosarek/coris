<?php include('include/include.php'); 
require_once('access.php'); 


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE coris_pages SET resource=%s, note=%s WHERE page_id=%s",
                       GetSQLValueString($_POST['resource'], "text"),
                       GetSQLValueString($_POST['note'], "text"),					   
                       GetSQLValueString($_POST['page_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $cn) or die(mysql_error());

	if (isset($_POST['department_id'])) {
		$query = sprintf("DELETE FROM coris_pages2departments WHERE page_id = %s", 
				GetSQLValueString($_POST['page_id'], "int"));

		if (!$result = mysql_query($query, $cn))
			die (mysql_error());
	
		 foreach ($_POST['department_id'] as $department_id) {
			  if ($department_id != "") {
				$query = sprintf("INSERT INTO coris_pages2departments (page_id, department_id) VALUES (%s, %s)", 
                        GetSQLValueString($_POST['page_id'], "int"),
						GetSQLValueString($department_id, "int"));
	
				if (!$result = mysql_query($query, $cn))
					die (mysql_error());
			  }
		 }
	}  

	if (isset($_POST['group_id'])) {
		$query = sprintf("DELETE FROM coris_pages2groups WHERE page_id = %s", 
				GetSQLValueString($_POST['page_id'], "int"));

		if (!$result = mysql_query($query, $cn))
			die (mysql_error());
	
		 foreach ($_POST['group_id'] as $group_id) {
			  if ($group_id != "") {
				$query = sprintf("INSERT INTO coris_pages2groups (page_id, group_id) VALUES (%s, %s)", 
                        GetSQLValueString($_POST['page_id'], "int"),
						GetSQLValueString($group_id, "int"));
	
				if (!$result = mysql_query($query, $cn))
					die (mysql_error());
			  }
		 }
	} 

  $updateGoTo = "GEN_pages_details.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$id_page = "1";
if (isset($_GET['page_id'])) {
  $id_page = (get_magic_quotes_gpc()) ? $_GET['page_id'] : addslashes($_GET['page_id']);
}

$query_page = sprintf("SELECT coris_pages.page_id, coris_pages.resource, coris_pages.note FROM coris_pages WHERE coris_pages.page_id = %s", $id_page);
$page = mysql_query($query_page, $cn) or die(mysql_error());
$row_page = mysql_fetch_assoc($page);
$totalRows_page = mysql_num_rows($page);

$id_departments = "1";
if (isset($_GET['page_id'])) {
  $id_departments = (get_magic_quotes_gpc()) ? $_GET['page_id'] : addslashes($_GET['page_id']);
}

$query_departments = sprintf("SELECT coris_users_departments.department_id, coris_users_departments.`value`, coris_pages2departments.department_id AS department_id_sel FROM coris_users_departments LEFT JOIN coris_pages2departments ON coris_users_departments.department_id = coris_pages2departments.department_id AND coris_pages2departments.page_id = %s ORDER BY coris_users_departments.`value`", $id_departments);
$departments = mysql_query($query_departments, $cn) or die(mysql_error());
$row_departments = mysql_fetch_assoc($departments);
$totalRows_departments = mysql_num_rows($departments);

$id_groups = "1";
if (isset($_GET['page_id'])) {
  $id_groups = (get_magic_quotes_gpc()) ? $_GET['page_id'] : addslashes($_GET['page_id']);
}

$query_groups = sprintf("SELECT coris_users_groups.group_id, coris_users_groups.`value`, coris_pages2groups.group_id AS group_id_sel FROM coris_users_groups LEFT JOIN coris_pages2groups ON coris_users_groups.group_id = coris_pages2groups.group_id AND coris_pages2groups.page_id = %s ORDER BY coris_users_groups.`value`", $id_groups);
$groups = mysql_query($query_groups, $cn) or die(mysql_error());
$row_groups = mysql_fetch_assoc($groups);
$totalRows_groups = mysql_num_rows($groups);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_MES_SZCZSTR  ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table align="center">
		<tr valign="baseline">
			<td align="right" valign="baseline" nowrap><div align="right"><?= GEN_MENU_ZASOB ?></div></td>
			<td valign="top"><input name="resource" type="text" value="<?php echo $row_page['resource']; ?>" size="20" maxlength="255"></td>
	    </tr>
		<tr valign="baseline">
			<td align="right" valign="baseline" nowrap><?= NOTE ?></td>
			<td><input name="note" type="text" id="note" value="<?php echo $row_page['note']; ?>" size="20" maxlength="255"></td>
		</tr>
		<tr valign="baseline">
			<td align="right" valign="top" nowrap><div align="right"><?= GEN_MENU_DZIALY ?></div></td>
			<td><select name="department_id[]" size="5" multiple id="department_id[]">
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
			<td align="right" valign="top" nowrap><div align="right"><?= GEN_MENU_GRUPY ?></div></td>
			<td><select name="group_id[]" size="5" multiple id="group_id[]">
				<?php
do {  
?>
				<option value="<?php echo $row_groups['group_id']?>"<?php if (!(strcmp($row_groups['group_id'], $row_groups['group_id_sel']))) {echo "SELECTED";} ?>><?php echo $row_groups['value']?></option>
				<?php
} while ($row_groups = mysql_fetch_assoc($groups));
  $rows = mysql_num_rows($groups);
  if($rows > 0) {
      mysql_data_seek($groups, 0);
	  $row_groups = mysql_fetch_assoc($groups);
  }
?>
			</select></td>
		</tr>
		<tr valign="baseline">
			<td nowrap align="right">&nbsp;</td>
			<td><input type="submit" class="submit" value="<?= SAVE ?>"></td>
	    </tr>
	</table>
	<input type="hidden" name="MM_update" value="form1">
	<input type="hidden" name="page_id" value="<?php echo $row_page['page_id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($page);

mysql_free_result($departments);

mysql_free_result($groups);
?>
