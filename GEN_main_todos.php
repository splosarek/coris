<?php include('include/include.php'); 

if (isset($_GET['todo_id'])) {
	$complete = ($_GET['checked'] == "true") ? 1 : 0;
	$date_complete = ($complete) ? date("Y-m-d H:i:s") : "NULL";
	$user_id_complete = ($complete) ? $_SESSION['user_id'] : 0;
    $query = "UPDATE coris_assistance_cases_todos SET complete = $complete, date_complete = '$date_complete', user_id_complete = $user_id_complete WHERE todo_id = $_GET[todo_id]";
	if (!$result = mysql_query($query, $cn)) {
        die(mysql_error());
	}
}

if (isset($_GET['delete'])) {
    $query = "UPDATE coris_assistance_cases_todos SET active = 0 WHERE todo_id = $_GET[delete]";
	if (!$result = mysql_query($query, $cn)) {
        die(mysql_error());
	}
}

$colname_todos = "1";
if (isset($_SESSION['user_id'])) {
  $colname_todos = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}

$query_todos = sprintf("SELECT act.case_id, act.todo_id, act.important, act.value, DATE_FORMAT(act.date_due, '%%Y-%%m-%%d') AS date_due, DATE_FORMAT(act.date_due, '%%H:%%i') AS time_due, DATE_FORMAT(act.date_complete, '%%Y-%%m-%%d') AS date_complete, DATE_FORMAT(act.date_complete, '%%H:%%i') AS time_complete, act.complete, u.username, u2.username AS complete_username, CONCAT_WS('/', ac.number, ac.`year`, ac.type_id, ac.client_id) AS case_no, ac.paxname, ac.paxsurname FROM coris_assistance_cases_todos act, coris_users u, coris_assistance_cases ac, coris_assistance_cases_todos2users act2u LEFT JOIN coris_users u2 ON u2.user_id = act.user_id_complete WHERE u.user_id = act.user_id AND act.todo_id = act2u.todo_id AND act2u.user_id = %s AND act.active = 1 AND ac.case_id = act.case_id ORDER BY complete, act.date_due, important DESC", $colname_todos);
$todos = mysql_query($query_todos, $cn) or die(mysql_error());
$row_todos = mysql_fetch_assoc($todos);
$totalRows_todos = mysql_num_rows($todos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
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
<style>
	td {
		font-size: 8pt;
	}
	tr.complete {
		color: #999999;
	}
</style>
<script language="javascript">
<!--
	function clearToDo(s) {
		var url = "GEN_main_todos.php?todo_id="+s.value+"&checked="+ s.checked;
		location.href = url;
	}
	setTimeout("document.location = 'GEN_main_todos.php<?php echo (isset($_GET['case_id'])) ? "?case_id=$_GET[case_id]" : "" ?>'", 60000);
//-->
</script>
		<table width="100%"  border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td class="popupTitle"><?= AS_CASD_ZADWL ?>&nbsp; </td>
			</tr>
		</table>
		<table width="100%" cellpadding="2" cellspacing="0" border="0">
            <tr>
            	<th width="3%" align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">&nbsp;</th>
            	<th width="3%" align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">&nbsp;</th>				
                <th width="3%" align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">
                    <font color="#6699cc" size="+1">+</font>
                </th>
                <th align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">
                    <font color="#6699cc"><small><?= AS_CASD_ZADANIE2 ?></small></font>
                </th>
                <th colspan="3" align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;" width="30%">
                    <font color="#6699cc"><small><?= DUE2 ?></small></font>
                </th>
                <th colspan="3" align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;" width="30%">
                    <font color="#6699cc"><small><?= AS_CASD_ZAKON ?></small></font>
                </th>
            </tr>
            <?php if ($totalRows_todos) do { ?>
			<tr valign="top" <?php echo ($row_todos['complete'] == 1) ? "style=\"text-decoration: line-through; color: #bbbbbb\"" : "" ?> <?php echo ($row_todos['important'] && !$row_todos['complete']) ? "bgcolor=\"yellow\"" : "" ?>>
			  <td align="center" valign="middle" style="border-right: #6699cc 1px solid;"><input type="button" style="width: 20px" onClick="MM_openBrWindow('GEN_main_todos_details.php?todo_id=<?php echo $row_todos['todo_id']; ?>','','width=300,height=225,left=350,top=200')" value="&gt;"></td>
			  <td valign="middle" nowrap style="border-right: #6699cc 1px solid;"><input type="button" value="X" style="width: 20px" onclick="if (confirm('<?= GEN_FR_CZYNAPUSZAD ?>')) document.location='GEN_main_todos.php?delete=<?php echo $row_todos['todo_id']; ?>';"></td>
				<td align="center" valign="middle" style="border-right: #6699cc 1px solid;">
					<input type="checkbox" name="todo" value="<?php echo $row_todos['todo_id'] ?>" onclick="clearToDo(this);" <?php echo ($row_todos['complete'] == 1) ? "checked" : "" ?> style="background: #dfdfdf;">
			  </td>
				<td align="left">
					<a href="javascript:void(0)" onClick="MM_openBrWindow('AS_cases_details.php?case_id=<?php echo $row_todos['case_id']; ?>','case','width=598,height=600,left=200,top=50')"><?php echo $row_todos['case_no']; ?> <strong><?php echo $row_todos['paxname']; ?> <?php echo $row_todos['paxsurname']; ?></strong></a><br>
                    <em><?php echo $row_todos['value'] ?></em>
				</td>
                <td align="center" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;"><?php echo $row_todos['username'] ?></td>
				<td align="center" nowrap style="border-right: #6699cc 1px solid;">
					<?php echo $row_todos['date_due'] ?>
				</td>
				<td align="center" nowrap style="border-right: #6699cc 1px solid;">
					<?php echo $row_todos['time_due'] ?>
				</td>
				<td align="center" style="border-right: #6699cc 1px solid;"><?php echo ($row_todos['complete']) ? $row_todos['complete_username'] : "&nbsp"; ?></td>
				<td align="center" nowrap style="border-right: #6699cc 1px solid;">
					<?php echo ($row_todos['complete']) ? $row_todos['date_complete'] : "&nbsp;" ?>
				</td>
				<td align="center" nowrap style="">
					<?php echo ($row_todos['complete']) ? $row_todos['time_complete'] : "&nbsp;" ?>
				</td>
			</tr>
	<?php } while ($row_todos = mysql_fetch_assoc($todos)); ?>			
</table>
</body>
</html>
<?php
mysql_free_result($todos);
?>
