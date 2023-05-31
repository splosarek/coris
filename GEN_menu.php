<?php include('include/include.php'); 

if (isset($_GET['up']) || isset($_GET['down'])) {
	if (isset($_GET['up'])) {
		if (isset($_GET['topic_id'])) {
			$query_order = sprintf("UPDATE coris_menu_topics SET `order` = `order`-1 WHERE topic_id = %s", $_GET['topic_id']);
		} else if (isset($_GET['element_id'])) {
			$query_order = sprintf("UPDATE coris_menu_elements SET `order` = `order`-1 WHERE element_id = %s", $_GET['element_id']);	
		}
	} else if (isset($_GET['down'])) {
		if (isset($_GET['topic_id'])) {
			$query_order = sprintf("UPDATE coris_menu_topics SET `order` = `order` + 1 WHERE topic_id = %s", $_GET['topic_id']);	
		} else if (isset($_GET['element_id'])) {
			$query_order = sprintf("UPDATE coris_menu_elements SET `order` = `order` + 1 WHERE element_id = %s", $_GET['element_id']);	
		}
	}
	mysql_query($query_order, $cn) or die(mysql_error());
}

if (isset($_GET['delete'])) {
	if (isset($_GET['topic_id'])) {
		$query_delete = sprintf("UPDATE coris_menu_topics SET active = 0 WHERE topic_id = %s", $_GET['topic_id']);		
	} else if (isset($_GET['element_id'])) {
		$query_delete = sprintf("UPDATE coris_menu_elements SET active = 0 WHERE element_id = %s", $_GET['element_id']);			
	}
	mysql_query($query_delete, $cn) or die(mysql_error());	
}


$query_topics = "SELECT topic_id, `order`, `value`, active FROM coris_menu_topics ORDER BY `order` ASC";
$topics = mysql_query($query_topics, $cn) or die(mysql_error());
$row_topics = mysql_fetch_assoc($topics);
$totalRows_topics = mysql_num_rows($topics);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Menu</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body onload="<?php echo (isset($_GET['offset'])) ? "document.body.scrollTop = '$_GET[offset]'" : "" ?>">
<strong><br>
<?= GEN_MENU_TEMATY ?></strong> <input name="Button" type="button" onClick="MM_openBrWindow('GEN_menu_add_topic.php?offset='+ document.body.scrollTop,'','width=200,height=100')" value="<?= BUTT_ADD ?>">
<br>

<table width="666" border="0" cellpadding="1" cellspacing="1">
	<tr bgcolor="#DFDFFF">
		<th width="21">&nbsp; </th>
		<th width="20">&nbsp;</th>
		<th width="17">ID</th>
		<th width="48"><?= GEN_MENU_KOLEJNOSC ?></th>
		<th><?= GEN_MENU_NAZWA ?></th>
	    <th>&nbsp;</th>
	    <th><?= GEN_MENU_UPR ?></th>
    </tr>
	<?php do { ?>
	<tr bgcolor="#FFFFCA" <?php if (!(strcmp($row_topics['active'], 0))) {echo "disabled";} ?>>
		<td nowrap><input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_menu_details_topic.php?offset='+ document.body.scrollTop + '&topic_id=<?php echo $row_topics['topic_id']; ?>','','width=400,height=100')" title="<?= AS_CASADD_SZCZ ?>"></td>
		<td nowrap><input <?php if (!(strcmp($row_topics['active'], 0))) {echo "disabled";} ?> type="button" value="X" style="width: 20px" onclick="if (confirm('<?= GEN_MENU_CZYCHCUSTEM ?>')) document.location='GEN_menu.php?offset='+ document.body.scrollTop +'&delete=1&topic_id=<?php echo $row_topics['topic_id']; ?>';" title="<?= AS_CASD_DEL ?>"></td>
		<td nowrap><div align="right"><?php echo $row_topics['topic_id']; ?></div>
		</td>
		<td nowrap><div align="center"><?php echo $row_topics['order']; ?></div>
	    </td>
		<td width="220" nowrap><?php echo $row_topics['value']; ?></td>
		<td width="23" nowrap><div align="center"><a title="<?= GEN_MENU_PRZESGOR ?>" onclick="document.location='GEN_menu.php?offset='+ document.body.scrollTop +'&topic_id=<?php echo $row_topics['topic_id'] ?>&up=1'" style="cursor: pointer">&lt;</a><a title="<?= GEN_MENU_PRZESDOL ?>" onclick="document.location='GEN_menu.php?offset='+ document.body.scrollTop +'&topic_id=<?php echo $row_topics['topic_id'] ?>&down=1'" style="cursor: pointer">&gt;</a></div></td>
		<td width="295" nowrap><font color="#FF9900">
		<?php
			
			$query_departments = sprintf("SELECT coris_users_departments.`value` FROM coris_users_departments, coris_menu_topics2departments WHERE coris_menu_topics2departments.topic_id = %s AND coris_menu_topics2departments.department_id = coris_users_departments.department_id ORDER BY coris_users_departments.`value`", $row_topics['topic_id']);
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
		</font></td>
	</tr>
	<?php } while ($row_topics = mysql_fetch_assoc($topics)); ?>
</table>
<br>
<br>
<strong><?= GEN_MENU_MENU ?></strong> 
<input name="Button" type="button" onClick="MM_openBrWindow('GEN_menu_add_element.php?offset='+ document.body.scrollTop,'','width=220,height=150')" value="<?= BUTT_ADD ?>">
<br>
	<?php
	$topics = mysql_query($query_topics, $cn) or die(mysql_error());
	$row_topics = mysql_fetch_assoc($topics);
	$totalRows_topics = mysql_num_rows($topics);
	do { 
		$id_elements = "0";
		if (isset($row_topics['topic_id'])) {
		  $id_elements = (get_magic_quotes_gpc()) ? $row_topics['topic_id'] : addslashes($row_topics['topic_id']);
		}	
		$query_elements = sprintf("SELECT coris_menu_elements.element_id, coris_menu_elements.`order`, coris_menu_elements.`value`, coris_menu_elements.resource, coris_menu_elements.active FROM coris_menu_elements WHERE topic_id =  %s ORDER BY coris_menu_elements.`order` ASC", $id_elements);
		$elements = mysql_query($query_elements, $cn) or die(mysql_error());
		$row_elements = mysql_fetch_assoc($elements);
		$totalRows_elements = mysql_num_rows($elements);
	?>
<table width="575" border="0" cellpadding="1" cellspacing="1">
	<tr>
		<th colspan="7" bgcolor="#CCCCCC"><div align="center"><?php echo $row_topics['value']; ?></div></th>
	</tr>
	<tr bgcolor="#DFDFFF">
		<th width="25">&nbsp; </th>
		<th width="20">&nbsp;</th>
		<th width="32">ID</th>
		<th width="48"><?= GEN_MENU_KOLEJNOSC ?></th>
		<th width="149"><?= GEN_MENU_NAZWA ?></th>
		<th colspan="2"><?= GEN_MENU_ZASOB ?></th>		
	</tr>	
	<?php if ($totalRows_elements) do { ?>
	<tr bgcolor="#FFFFCA" <?php if (!(strcmp($row_elements['active'], 0))) {echo "disabled";} ?>>
		<td nowrap><input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_menu_details_element.php?offset='+ document.body.scrollTop + '&element_id=<?php echo $row_elements['element_id']; ?>','','width=220,height=150')" title="<?= AS_CASADD_SZCZ ?>"></td>
		<td nowrap><input <?php if (!(strcmp($row_elements['active'], 0))) {echo "disabled";} ?> type="button" value="X" style="width: 20px" onclick="if (confirm('<?= GEN_MENU_CZYCHCUSELEM ?>')) document.location='GEN_menu.php?offset='+ document.body.scrollTop + '&delete=1&element_id=<?php echo $row_elements['element_id']; ?>';" title="<?= AS_CASD_DEL ?>"></td>
		<td nowrap><div align="right"><?php echo $row_elements['element_id']; ?></div>
		</td>
		<td nowrap><div align="center"><?php echo $row_elements['order']; ?></div>
		</td>
		<td nowrap><?php echo $row_elements['value']; ?></td>
		<td width="255" nowrap><?php echo $row_elements['resource']; ?></td>		
	    <td width="24" nowrap><div align="center"><a title="<?= GEN_MENU_PRZESGOR ?>" onclick="document.location='GEN_menu.php?offset='+ document.body.scrollTop +'&element_id=<?php echo $row_elements['element_id'] ?>&up=1'" style="cursor: pointer">&lt;</a><a title="<?= GEN_MENU_PRZESDOL ?>" onclick="document.location='GEN_menu.php?offset='+ document.body.scrollTop +'&element_id=<?php echo $row_elements['element_id'] ?>&down=1'" style="cursor: pointer">&gt;</a></div></td>
	</tr>	
	<?php
		} while ($row_elements= mysql_fetch_assoc($elements));		
	?>
</table>
<br>
	<?php } while ($row_topics = mysql_fetch_assoc($topics)); ?>
</body>
</html>
<?php
mysql_free_result($topics);

mysql_free_result($departments);

mysql_free_result($elements);
?>
