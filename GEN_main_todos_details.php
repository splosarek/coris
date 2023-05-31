<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE coris_assistance_cases_todos SET important=%s, `value`=%s, date_due=%s WHERE todo_id=%s",
                       GetSQLValueString(isset($_POST['important']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['value'], "text"),
					   GetSQLValueString($_POST['due_date'] . " " . $_POST['due_time'], "text"),
                       GetSQLValueString($_POST['todo_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $cn) or die(mysql_error());

	
	$query_todo2users = sprintf("DELETE FROM coris_assistance_cases_todos2users WHERE coris_assistance_cases_todos2users.todo_id = %s", $_POST['todo_id']);
	$todo2users = mysql_query($query_todo2users, $cn) or die(mysql_error());

	foreach ($_POST['user_id'] as $user_id) {
		if ($user_id != "") {
			$query = sprintf("INSERT INTO coris_assistance_cases_todos2users (todo_id, user_id) VALUES (%s, %s)", 
					GetSQLValueString($_POST['todo_id'], "int"),
					GetSQLValueString($user_id, "int"));

			if (!$result = mysql_query($query, $cn))
				die (mysql_error());
		}
	}	

  $updateGoTo = "GEN_main_todos_details.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  exit();
}

$id_users = "0";
if (isset($_GET['todo_id'])) {
  $id_users = (get_magic_quotes_gpc()) ? $_GET['todo_id'] : addslashes($_GET['todo_id']);
}

$query_users = sprintf("SELECT coris_users.user_id, coris_users.username, coris_assistance_cases_todos2users.user_id AS user_id_sel FROM coris_users LEFT JOIN coris_assistance_cases_todos2users ON coris_assistance_cases_todos2users.user_id = coris_users.user_id AND coris_assistance_cases_todos2users.todo_id = %s WHERE coris_users.department_id = 7 AND coris_users.active = 1 ORDER BY coris_users.username", $id_users);
$users = mysql_query($query_users, $cn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

$id_todo = "0";
if (isset($_GET['todo_id'])) {
  $id_todo = (get_magic_quotes_gpc()) ? $_GET['todo_id'] : addslashes($_GET['todo_id']);
}

$query_todo = sprintf("SELECT coris_assistance_cases_todos.todo_id, coris_assistance_cases_todos.case_id, coris_assistance_cases_todos.important, coris_assistance_cases_todos.`value`, DATE(coris_assistance_cases_todos.date_due) AS due_date, DATE_FORMAT(coris_assistance_cases_todos.date_due, '%%H:%%i') AS due_time FROM coris_assistance_cases_todos WHERE coris_assistance_cases_todos.todo_id = %s", $id_todo);
$todo = mysql_query($query_todo, $cn) or die(mysql_error());
$row_todo = mysql_fetch_assoc($todo);
$totalRows_todo = mysql_num_rows($todo);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= AS_CASD_SZCZZAD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<script language="JavaScript" src="CalendarPopup.js"></script>
    <script language="JavaScript">
		<!--
    	var cal = new CalendarPopup();		
		cal.setMonthNames(<?= MONTHS_NAME ?>); 
		cal.setDayHeaders(<?= DAY_NAME ?>); 
		cal.setWeekStartDay(1); 
		cal.setTodayText('<?= TODAY ?>');
		//-->
	</script>
<script language="JavaScript">
        <!--
            function setTime() {
                window.open('AS_cases_details_todo_add_time.php','time','resizable=no,width=50,height=455');
            }
        //-->
        </script>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1">		
<table width="100%"  border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td class="popupTitle"><?= AS_CASD_SZCZZAD ?>&nbsp;</td>
  </tr>
</table>
<table cellpadding=4 cellspacing=0 width="100%">
    <tr>
      <td colspan="2"><table width="100%">
          <tr valign="top">
            <td width="70%"><small><?= AS_CASD_ZADANIE ?></small><br>
                <textarea name="value" cols="25" rows="4" id="value"><?php echo $row_todo['value']; ?></textarea>
            </td>
            <td width="30%"><small><?= DUE ?></small><br>
                <table cellspacing="0" cellpadding="0" border="0">
                  <tr>
                    <td align="right"><input name="due_date" type="text" id="due_date" style="text-align: center" value="<?php echo $row_todo['due_date']; ?>" size="11"></td>
                    <td align="center"><a href="javascript:void(0)" onclick="cal.select(document.form1.due_date,'anchor1','yyyy-MM-dd');" tabindex="-1" style="text-decoration: none" name="anchor1" id="anchor1"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a></td>
                  </tr>
                  <tr>
                    <td align="right"><input name="due_time" type="text" id="due_time" style="text-align: center" value="<?php echo $row_todo['due_time']; ?>" size="5"></td>
                    <td align="center"><a href="javascript:void(0)" onclick="setTime()" tabindex="-1" style="text-decoration: none"><font face="Wingdings" style="font-size: 11pt; color: #000000">À</font></a></td>
                  </tr>
                  <tr>
                    <td align="right"><font color="red"><b><?= AS_CASD_WAZ ?></b></font></td>
                    <td align="center"><input <?php if (!(strcmp($row_todo['important'],1))) {echo "checked";} ?> type="checkbox" name="important" style="background: #dfdfdf"></td>
                  </tr>
              </table></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td align="right" valign="middle" style="border-top: #cccccc 1px solid;"><small><?= AS_CASD_ZADWL ?></small> </td>
      <td style="border-top: #cccccc 1px solid;" align="left"><select name="user_id[]" size="5" multiple id="user_id[]">
        <?php
do {  
?>
        <option value="<?php echo $row_users['user_id']?>"<?php if (!(strcmp($row_users['user_id'], $row_users['user_id_sel']))) {echo "SELECTED";} ?>><?php echo $row_users['username']?></option>
        <?php
} while ($row_users = mysql_fetch_assoc($users));
  $rows = mysql_num_rows($users);
  if($rows > 0) {
      mysql_data_seek($users, 0);
	  $row_users = mysql_fetch_assoc($users);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" align="center" style="border-top: #cccccc 1px solid;"><input type="hidden" name="case_id" value="<?= $_GET['case_id'] ?>"><input type="hidden" name="todo_id" value="<?php echo $_GET['todo_id'] ?>">
          <input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow">
      </td>
    </tr>
</table>
<input type="hidden" name="MM_update" value="form1">
</form>
<script>form1.note.focus();</script>
</body>
</html>
<?php
mysql_free_result($users);

mysql_free_result($todo);
?>
