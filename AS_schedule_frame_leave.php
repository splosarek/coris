<?php include('include/include.php');  ?>
<html>
    <head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
    </head>
    <body>
        <style>
            body {
                background: #cccccc;
                font-family: Verdana;
                font-size: 9pt;
            }
            td {
                font-size: 9pt;
            }
        </style>
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

            function setTime() {
                window.open('AS_schedule_leave_time.php','time','resizable=no,width=50,height=455');
            }

            function restart() {
                mywindow.close();
            }
            function makeArray0() {
                for (i = 0; i<makeArray0.arguments.length; i++)
                    this[i] = makeArray0.arguments[i];
            }
            var names = new makeArray0('Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec','Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień');

            function submitEntry() {
                if (!form1.user_id.value) {
                    alert("Wybierz pracownika!");
                    return;
                }
                if (!form1.date_start.value) {
                    alert("Wybierz datę począkową");
                    return;
                }
                if (!form1.date_end.value) {
                    alert("Wybierz datę końcową");
                    return;
                }
                start = new Date(form1.date_start_year.value,form1.date_start_month.value,form1.date_start_day.value);
                end = new Date(form1.date_end_year.value,form1.date_end_month.value,form1.date_end_day.value);
                if (start > end) {
                    alert("Koniec urlopu wypada przed jego początkiem!");
                    return;
                }

                var url = "AS_schedule_frame_leave.php?offset=<?= $_GET['offset'] ?>&myMonth=<?= $_GET['myMonth'] ?>&myYear=<?= $_GET['myYear'] ?>&action=1&date_start="+ form1.date_start.value +"&date_end="+ form1.date_end.value +"&user_id="+ form1.user_id.value;
                document.location=url;
            }

        //-->
        </script>
<?php
if (!isset($_GET['action'])) {
?>
                <form name="form1">
                <table width="100%">
                    <tr>
                        <td width="50%" align="center">
                            <b>Od</b>
                        </td>
                        <td width="50%" align="center">
                            <b>Do</b>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td width="50%" align="center">
                            <iframe width="230" height="200" border="0" frameborder="0" src="AS_schedule_frame_leave_calendar_start.htm"></iframe>
                        </td>
                        <td width="50%" align="center">
                            <iframe width="230" height="200" border="0" frameborder="0" src="AS_schedule_frame_leave_calendar_end.htm"></iframe>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <table>
                                <tr>
                                    <td align="right">
                                        Pracownik:
                                    </td>
                                    <td>
<?
    $query = "SELECT user_id, name, surname FROM coris_users WHERE staff = 1 ORDER BY surname";
	
    $result = mysql_query($query, $cn) or die(mysql_error());
?>
        <select name="user_id" style="color: red">
            <option></option>
<?
        while ($row = mysql_fetch_array($result)) {
?>
        <option value="<?= $row['user_id'] ?>"><?= $row['surname'] ?>, <?= $row['name'] ?></option>
<?
        }
        mysql_free_result($result);
?>
        </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td align="right">Okres urlopu:</td>
                                    <td><input type="text" name="date_start" style="color: red; text-align: center" size="7">&nbsp;-&nbsp;<input type="text" name="date_end" style="color: red; text-align: center" size="7">
                                    <input type="hidden" name="date_start_day">
                                    <input type="hidden" name="date_start_month">
                                    <input type="hidden" name="date_start_year">
                                    <input type="hidden" name="date_end_day">
                                    <input type="hidden" name="date_end_month">
                                    <input type="hidden" name="date_end_year">
                                    </td>
                                 </tr>
                             </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" height="8"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="button" style="width: 100px;" value="Zapisz" onclick="submitEntry();">
                        </td>
                    </tr>
                </table>
                </form>
<? 
} else { 
	
    $query = "INSERT INTO coris_assistance_schedule_leaves (user_id, date_start, date_end, date_created) VALUES ($_GET[user_id], '$_GET[date_start]', '$_GET[date_end]', NOW())";
    $result = mysql_query($query, $cn);
    echo "<script>window.opener.parent.AS_schedule_frame.location = \"AS_schedule_frame.php?offset=$_GET[offset]&myMonth=$_GET[myMonth]&myYear=$_GET[myYear]\";window.close()</script>";
}
?>
            </body>
</html>
