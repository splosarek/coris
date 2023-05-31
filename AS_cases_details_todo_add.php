<?php include('include/include.php');



$query_users = "SELECT user_id, username FROM coris_users WHERE ( department_id = 7 OR department_id = 10 )  AND active = 1 ORDER BY username";
$users = mysql_query($query_users) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= AS_CASD_DODZAD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
	<style>
		a, a:hover, a:visited {
			color: #000000;
			text-decoration: underline;
		}
	</style>
<body bgcolor="#dfdfdf">
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
<?
if (isset($_GET['action'])) {

    if (isset($_POST['important'])) {
        $important = 1;
    } else {
        $important = 0;
    }

    $date_due = $_POST['date_due'] . " " . $_POST['due_time'];

    $query = "INSERT INTO coris_assistance_cases_todos (case_id, important, value, date_due, user_id,date) VALUES ('$_POST[case_id]', '$important','$_POST[note]','$date_due','$_SESSION[user_id]',now())";

	if ($result = mysql_query($query)) {

		
		$query_todo = "SELECT todo_id FROM coris_assistance_cases_todos WHERE todo_id = @@IDENTITY";
		$todo = mysql_query($query_todo) or die(mysql_error());
		$row_todo = mysql_fetch_assoc($todo);

		foreach ($_POST['users_id'] as $users_id) {
			if ($users_id != "") {
				$query = sprintf("INSERT INTO coris_assistance_cases_todos2users (todo_id, user_id) VALUES (%s, %s)", 
						GetSQLValueString($row_todo['todo_id'], "int"),
						GetSQLValueString($users_id, "int"));
				
				if (!$result = mysql_query($query))
					die (mysql_error());
			}
		}	
		  if (getValue('tryb') == 'alert'){
		  	//echo "<script>alert('Zadanie zostało dodane, widoczne w zakładce \"Zadania\"'); window.close();</script>";
		  	echo "<script>window.opener.parent.todoframe2.document.location = 'AS_cases_details_todo_frame2.php?case_id=$_POST[case_id]'; window.close();</script>";
		  }else{
        	echo "<script>window.opener.parent.todoframe2.document.location = 'AS_cases_details_todo_frame2.php?case_id=$_POST[case_id]'; window.close();</script>";
		  }
		  
        exit;
    } else {
        die(mysql_error());
    }
}
?>
        <script language="JavaScript">
        <!--
            function y2k(number)    { return (number < 1000) ? number + 1900 : number; }

            var today = new Date();
            var day   = today.getDate();
            var month = today.getMonth();
            var year  = y2k(today.getYear());

            function padout(number) { return (number < 10) ? '0' + number : number; }

            function restartCal() {
                mywindow.close();
            }
            function newWindowCal() {
                mywindow=open('AS_cases_details_todo_new_calendar.htm','cal','resizable=no,width=260,height=200');
                mywindow.location.href = 'AS_cases_details_todo_new_calendar.htm';
                if (mywindow.opener == null) mywindow.opener = self;
            }

            function setTime() {
                //window.open('AS_cases_details_todo_new_time.php','time','resizable=no,width=50,height=455');
                window.open('AS_cases_details_todo_add_time.php','time','resizable=no,width=50,height=455');
                
            }

            function restart() {
                mywindow.close();
            }
            function makeArray0() {
                for (i = 0; i<makeArray0.arguments.length; i++)
                    this[i] = makeArray0.arguments[i];
            }
            var names = new makeArray0(<?= MONTHS_NAME ?>);

        //-->
        </script>
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="popupTitle"><?= AS_CASD_DODZAD ?>&nbsp;</td>
		</tr>
	</table>	
<table cellpadding=4 cellspacing=0 width="100%">
        <form action="AS_cases_details_todo_add.php?action=1" method="post" name="form1" id="form1">
        <input type="hidden" name="tryb"  id="tryb" value="<?php echo getValue('tryb'); ?>">
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr valign="top">
                            <td width="70%">
                                <small><?= AS_CASD_ZADANIE  ?></small><br>
                                <textarea name="note" cols="25" rows="4"><?php 
                                if (getValue('tryb') == 'alert')
                                	echo base64_decode(getValue('txt'));
                                
                                
                                ?></textarea>
                            </td>
                            <td width="30%">
                                <small><?= DUE ?></small><br>
                                <table cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td align="right"><input type="text" name="date_due" id="date_due" size="11" value="<?= date("Y-m-d") ?>" style="text-align: center"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="cal.select(document.form1.date_due,'anchor1','yyyy-MM-dd');" tabindex="-1" style="text-decoration: none" name="anchor1" id="anchor1"><img   src="img/kalendarz.gif" border="0"  ></a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><input type="text" name="due_time" id="due_time" size="5" value="<?= date("G:i") ?>" style="text-align: center"></td>
                                        <td align="center"><a href="javascript:void(0)" onclick="setTime()" tabindex="-1" style="text-decoration: none"><img   src="img/Zegar.gif" border="0"  ></a></td>
                                    </tr>
                                    <tr>
                                        <td align="right"><font color="red"><b><?= AS_CASD_WAZ ?></b></font></td>
                                        <td align="center"><input type="checkbox" name="important" id="important" style="background: #dfdfdf"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
              <td align="right" valign="middle" style="border-top: #cccccc 1px solid;"><small><?= AS_CASD_ZADWL ?></small> </td>
              <td style="border-top: #cccccc 1px solid;" align="left"><select name="users_id[]" size="5" multiple id="users_id[]">
                <?php
do {  
?>
                <option value="<?php echo $row_users['user_id']?>"<?php if (!(strcmp($row_users['user_id'], $_SESSION['user_id']))) {echo "SELECTED";} ?>><?php echo $row_users['username']?></option>
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
                <td colspan="2" align="center" style="border-top: #cccccc 1px solid;">
                    <input type="hidden" name="case_id" value="<?= $_GET['case_id'] ?>"><input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow">
                </td>
            </tr>
        </form>
        </table>
        <script>form1.note.focus();</script>
</body>
</html>
<?php
mysql_free_result($users);
?>
