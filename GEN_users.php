<?php include('include/include.php'); 


if (!check_admin() ){
	die ('Access denied');
}

$query_users = "SELECT coris_users.user_id, coris_users.username, coris_users.name, coris_users.surname,
                       IF(coris_users.doctor, 'T', 'N') AS doctor, IF(coris_users.staff, 'T', 'N') AS staff, coris_users.initials,
                       coris_users.ext, coris_users.active, coris_colors.name AS color_name, coris_users_departments.`value` AS department_value,
                       coris_users_groups.`value` AS group_value,
                        cb.name AS coris_branch_name
                FROM  coris_users_departments,
                      coris_users_groups,
                      coris_users
                      LEFT JOIN coris_colors ON coris_colors.color_id = coris_users.color_id
                      LEFT JOIN coris_branch AS cb ON coris_users.coris_branch_id=cb.ID
                WHERE coris_users.department_id = coris_users_departments.department_id
                  AND coris_users.group_id = coris_users_groups.group_id
                ORDER BY coris_users.surname ASC, coris_users.name ASC";

$users = mysql_query($query_users) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_USER_TITLE ?></title>
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
<table width="1206" border="0" cellpadding="1" cellspacing="1">
	<tr bgcolor="#DFDFFF">
		<th width="34">&nbsp; </th>
		<th width="17"><div align="right">ID</div></th>
		<th width="37"><?= COLOR ?></th>
		<th width="66"><?= SURNAME ?></th>
		<th width="33"><?= NAME ?></th>
		<th width="38"><?= LOGIN ?></th>
		<th width="169"><?= GEN_USER_DZI ?></th>
		<th width="190"><?= GEN_USER_GRU ?></th>
		<th width="47"><?= GEN_USER_LEK ?></th>
		<th width="48"><?= GEN_USER_ZAG ?></th>
		<th width="55"><?= GEN_USER_INI ?></th>
		<th width="41"><?= GEN_USER_WEW ?></th>
		<th width="391"><strong><?= GEN_USER_JEZ ?></strong></th>
		<th width="110"><?= BRANCH ?></th>
	</tr>
	<?php do { ?>
	<tr bgcolor="#FFFFCA" <?php if ($row_users['active'] == 0) { echo "disabled"; }?>>
		<td nowrap><input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_users_details.php?user_id=<?php echo $row_users['user_id']; ?>','','resizable=yes,width=650,height=530')"></td>
		<td><div align="right"><?php echo $row_users['user_id']; ?></div></td>
		<td style="background: <?php echo $row_users['color_name']; ?>"></td>
		<td><?php echo $row_users['surname']; ?></td>
		<td><?php echo $row_users['name']; ?></td>
		<td><?php echo $row_users['username']; ?></td>
		<td><?php echo $row_users['department_value']; ?></td>
		<td><?php echo $row_users['group_value']; ?></td>
		<td><?php echo $row_users['doctor']; ?></td>
		<td><?php echo $row_users['staff']; ?></td>
		<td><?php echo $row_users['initials']; ?></td>
		<td><?php echo $row_users['ext']; ?></td>
		<td nowrap><font color="#FF9900">
		<?php
		
			$query_languages = sprintf("SELECT coris_users_languages.`value` FROM coris_users_languages, coris_users2languages WHERE coris_users2languages.user_id = %s AND coris_users2languages.language_id = coris_users_languages.language_id ORDER BY coris_users_languages.`value`", $row_users['user_id']);
			$languages = mysql_query($query_languages) or die(mysql_error());
			$row_languages = mysql_fetch_assoc($languages);
			$totalRows_languages = mysql_num_rows($languages);		
			$i = 0;
			do {			
				echo $row_languages['value'];
				if (++$i < $totalRows_languages)
					echo ", ";
			} while ($row_languages = mysql_fetch_assoc($languages));
		?>		
		</font></td>
        <td><?php echo $row_users['coris_branch_name']; ?></td>
	</tr>
	<?php } while ($row_users = mysql_fetch_assoc($users)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($users);
mysql_free_result($languages);
?>
