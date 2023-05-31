<?php include('include/include.php'); 
require_once('access.php'); 

if (isset($_GET['delete']) && isset($_GET['page_id'])) {
	$query = sprintf("DELETE FROM coris_pages WHERE page_id = %s", 
			GetSQLValueString($_GET['page_id'], "int"));
	mysql_query($query, $cn) or die(mysql_error());
}

$query_pages = "SELECT coris_pages.page_id, coris_pages.resource, coris_pages.note FROM coris_pages ORDER BY coris_pages.resource";
$pages = mysql_query($query_pages, $cn) or die(mysql_error());
$row_pages = mysql_fetch_assoc($pages);
$totalRows_pages = mysql_num_rows($pages);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= AS_FORMSF_STR ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body>
<br>
<strong><?= AS_FORMSF_STR ?></strong>&nbsp;<input name="Button" type="button" onClick="MM_openBrWindow('GEN_pages_add.php','','width=230,height=235')" value="<?= FK_EMAIL_DODAJ ?>">
<table width="934"  border="0" cellspacing="1" cellpadding="1">
	<tr bgcolor="#DFDFFF">
		<th width="21">&nbsp; </th>
		<th width="20">&nbsp;</th>
		<th width="17">ID</th>
		<th width="160"><?= GEN_MENU_ZASOB ?></th>
		<th width="231"><?= NOTE ?></th>
		<th width="231"><?= GEN_MENU_DZIALY ?></th>
		<th width="232"><?= GEN_MENU_GRUPY ?></th>
	</tr>
	<?php if ($totalRows_pages) do { ?>
	<tr valign="top" bgcolor="#FFFFCA">
		<td nowrap><input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_pages_details.php?offset='+ document.body.scrollTop +'&page_id=<?php echo $row_pages['page_id']; ?>','','width=230,height=235')" title="<?= AS_DOK_MSG_SZCZ ?>"></td>
		<td nowrap><input type="button" value="X" style="width: 20px" onclick="if (confirm('<?= GEN_MES_CZYCHUSSTR ?>')) document.location='GEN_pages.php?offset='+ document.body.scrollTop +'&delete=1&page_id=<?php echo $row_pages['page_id']; ?>';" title="<?= AS_CASD_DEL ?>"></td>
		<td nowrap><div align="right"><?php echo $row_pages['page_id']; ?></div></td>
		<td nowrap><?php echo $row_pages['resource']; ?></td>
		<td><?php echo $row_pages['note']; ?></td>
		<td>
			<font color="#FF9900">
			<?php
			
			$query_departments = sprintf("SELECT coris_users_departments.`value` FROM coris_users_departments, coris_pages2departments WHERE coris_pages2departments.page_id = %s AND coris_pages2departments.department_id = coris_users_departments.department_id ORDER BY coris_users_departments.`value`", $row_pages['page_id']);
			$departments = mysql_query($query_departments, $cn) or die(mysql_error());
			$row_departments = mysql_fetch_assoc($departments);
			$totalRows_departments = mysql_num_rows($departments);		
			$i = 0;
			do {			
				echo $row_departments['value'];
				if (++$i < $totalRows_departments)
					echo ", ";
			} while ($row_departments = mysql_fetch_assoc($departments));
		?>		
			</font>				
		</td>
		<td>
			<font color="#FF9900">
			<?php
			
			$query_groups = sprintf("SELECT coris_users_groups.`value` FROM coris_users_groups, coris_pages2groups WHERE coris_pages2groups.page_id = %s AND coris_pages2groups.group_id = coris_users_groups.group_id ORDER BY coris_users_groups.`value`", $row_pages['page_id']);
			$groups = mysql_query($query_groups, $cn) or die(mysql_error());
			$row_groups = mysql_fetch_assoc($groups);
			$totalRows_groups = mysql_num_rows($groups);		
			$i = 0;
			do {			
				echo $row_groups['value'];
				if (++$i < $totalRows_groups)
					echo ", ";
			} while ($row_groups = mysql_fetch_assoc($groups));
		?>		
			</font>				
		</td>
	</tr>
	<?php } while ($row_pages = mysql_fetch_assoc($pages)); ?>
</table>

<br>
</body>
</html>
<?php
mysql_free_result($pages);
?>
