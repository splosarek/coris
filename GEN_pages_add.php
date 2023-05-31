<?php include('include/include.php'); 
require_once('access.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coris_pages (resource, note) VALUES (%s, %s)",
                       GetSQLValueString($_POST['resource'], "text"),
                       GetSQLValueString($_POST['note'], "text"));

  
  $Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());

	$query_page = "SELECT page_id FROM coris_pages WHERE page_id = @@IDENTITY";
	$page = mysql_query($query_page, $cn) or die(mysql_error());
	$row_page = mysql_fetch_assoc($page);	

	if (isset($row_page['page_id'])) {
		if (isset($_POST['department_id'])) {	
			 foreach ($_POST['department_id'] as $department_id) {
				  if ($department_id != "") {
					$query = sprintf("INSERT INTO coris_pages2departments (page_id, department_id) VALUES (%s, %s)", 
							GetSQLValueString($row_page['page_id'], "int"),
							GetSQLValueString($department_id, "int"));
		
					if (!$result = mysql_query($query, $cn))
						die (mysql_error());
				  }
			 }
		}  
	
		if (isset($_POST['group_id'])) {
			 foreach ($_POST['group_id'] as $group_id) {
				  if ($group_id != "") {
					$query = sprintf("INSERT INTO coris_pages2groups (page_id, group_id) VALUES (%s, %s)", 
							GetSQLValueString($row_page['page_id'], "int"),
							GetSQLValueString($group_id, "int"));
		
					if (!$result = mysql_query($query, $cn))
						die (mysql_error());
				  }
			 }
		} 
	}

  $insertGoTo = "GEN_pages_add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$query_departments = "SELECT coris_users_departments.department_id, coris_users_departments.`value` FROM coris_users_departments ORDER BY coris_users_departments.`value`";
$departments = mysql_query($query_departments, $cn) or die(mysql_error());
$row_departments = mysql_fetch_assoc($departments);
$totalRows_departments = mysql_num_rows($departments);


$query_groups = "SELECT coris_users_groups.group_id, coris_users_groups.`value` FROM coris_users_groups ORDER BY coris_users_groups.`value`";
$groups = mysql_query($query_groups, $cn) or die(mysql_error());
$row_groups = mysql_fetch_assoc($groups);
$totalRows_groups = mysql_num_rows($groups);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_MES_DODSTR ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table align="center">
		<tr valign="baseline">
			<td nowrap align="right"><?= GEN_MENU_ZASOB ?></td>
			<td><input name="resource" type="text" value="" size="20" maxlength="255"></td>
		</tr>
		<tr valign="baseline">
			<td align="right" valign="top" nowrap><?= NOTE ?></td>
			<td><input name="note" type="text" id="note" size="15" maxlength="255"></td>
		</tr>
		<tr valign="baseline">
			<td align="right" valign="top" nowrap><?= GEN_MENU_DZIALY ?></td>
			<td><select name="department_id[]" size="5" multiple id="department_id[]">
				<?php
do {  
?>
				<option value="<?php echo $row_departments['department_id']?>"><?php echo $row_departments['value']?></option>
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
			<td align="right" valign="top" nowrap><?= GEN_MENU_GRUPY ?></td>
			<td><select name="group_id[]" size="5" multiple id="group_id[]">
				<?php
do {  
?>
				<option value="<?php echo $row_groups['group_id']?>"><?php echo $row_groups['value']?></option>
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
	<input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($departments);

mysql_free_result($groups);
?>
