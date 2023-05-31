<?php 
include('include/include.php'); 
?>
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
                function entrySubmit1() {
                    var url = "AS_schedule_frame_entry.php?offset=<?= $_GET['offset'] ?>&action=1&myDay=<?= $_GET['myDay'] ?>&myMonth=<?= $_GET['myMonth'] ?>&myYear=<?= $_GET['myYear'] ?>&shift=<?= $_GET['shift'] ?>&type=<?= $_GET['type'] ?>&user_id="+ form1.user_id.value;
                    document.location=url;
                }
                <?  if (isset($_GET['action']) && $_GET['action'] == 1) { ?>

                function entrySubmit2() {
                    var modified = 0;
                    
                    if (form1.time_start_old.value != form1.time_start.value || form1.time_end_old.value != form1.time_end.value) {
                        modified = 1;
                    }

                    var url = "AS_schedule_frame_entry.php?offset=<?= $_GET['offset'] ?>&action=2&myDay=<?= $_GET['myDay'] ?>&myMonth=<?= $_GET['myMonth'] ?>&myYear=<?= $_GET['myYear'] ?>&shift=<?= $_GET['shift'] ?>&type="+ form1.type.value +"&user_id=<?= $_GET['user_id'] ?>&date_start="+ form1.date_start.value +"&date_end="+ form1.date_end.value +"&time_start="+ form1.time_start.value +"&time_end="+ form1.time_end.value +"&modified="+ modified;
                        
                    document.location=url;
                }
                <? } ?>
            //-->
        </script>
        <center>
        <form name="form1">
<?php
if (!isset($_GET['action'])) {
    if ($_GET['type'] == 2) {
        $query = "SELECT user_id, name, surname FROM coris_users WHERE staff = 1 AND doctor = 1 AND department_id = 7 ORDER BY surname";
    } else {
        $query = "SELECT user_id, name, surname FROM coris_users WHERE staff = 1 AND doctor = 0 AND department_id = 7 ORDER BY surname";
    }
    $result = mysql_query($query, $cn);
?>
    <table height="100%" width="100%">
        <tr>
            <td valign="middle" align="center">
                <select name="user_id" size="14" onchange="entrySubmit1()">
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
    </table>
<?
} else if ($_GET['action'] == 1) {

    $type = $_GET['type'];
    
    switch ($_GET['shift']) {
        case 1:
            if ($_GET['type'] == 1) {
                $time_start = "8:00";
                $time_end = "16:00";
            } else { //lekarze
                $time_start = "9:00";
                $time_end = "17:00";
            }
            $date_start = "$_GET[myYear]-$_GET[myMonth]-$_GET[myDay]";
            $date_end = $date_start;
            break;
        case 2:
            if ($_GET['type'] == 1) {
                $time_start = "9:00";
                $time_end = "17:00";
            } else { //lekarze
                $time_start = "16:00";
                $time_end = "22:00";
            }

            $date_start = "$_GET[myYear]-$_GET[myMonth]-$_GET[myDay]";
            $date_end = $date_start;
            break;
        case 3:
            if (date("D", mktime(0, 0, 0, $_GET['myMonth'], $_GET['myDay'], $_GET['myYear'])) == "Sat" || date("D", mktime(0, 0, 0, $_GET['myMonth'], $_GET['myDay'], $_GET['myYear'])) == "Sun") {
                $time_start = "12:00";
                $time_end = "20:00";
            } else {
                $time_start = "14:00";
                $time_end = "22:00";
            }
            $date_start = "$_GET[myYear]-$_GET[myMonth]-$_GET[myDay]";
            $date_end = $date_start;
            break;
        case 4:
            if (date("D", mktime(0, 0, 0, $_GET['myMonth'], $_GET['myDay'], $_GET['myYear'])) == "Sat" || date("D", mktime(0, 0, 0, $_GET['myMonth'], $_GET['myDay'], $_GET['myYear'])) == "Sun") {
                $time_start = "20:00";
                $time_end = "9:00";
            } else {
                $time_start = "22:00";
                $time_end = "8:00";
            }
            $date_start = "$_GET[myYear]-$_GET[myMonth]-$_GET[myDay]";
            $day_end = (int) $_GET['myDay'] + 1;
            $date_end = "$_GET[myYear]-$_GET[myMonth]-$day_end";
            break;
    }
?>
    <table cellpadding="2" cellspacing="2" width="100%" height="100%">
        <tr valign="middle">
            <td align="center">
            Godzina od: <input type="text" name="time_start" value="<?= $time_start ?>" size="5" style="text-align: center"><br>
            Godzina do: <input type="text" name="time_end" value="<?= $time_end ?>" size="5" style="text-align: center"><br><br>
            <input type="button" value="Zapisz" onclick="entrySubmit2();">
            </td>
        </tr>
    </table>

    <input type="hidden" name="type" value="<?= $type ?>">

    <input type="hidden" name="time_start_old" value="<?= $time_start ?>">
    <input type="hidden" name="time_end_old" value="<?= $time_end ?>">
            
    <input type="hidden" name="date_start" value="<?= $date_start ?>">
    <input type="hidden" name="date_end" value="<?= $date_end ?>">
<?
} else if ($_GET['action'] == 2) {

    // Dla lekarzy
	// ZMIENILEM, ZEBY NIE MIELI JAKO DEFAULT
    //$modified = ($_GET['type'] == 2) ? 1 : $_GET['modified'];
    $modified = $_GET['modified'];

    $myDate = date('Y-m-d', mktime(0, 0, 0, $_GET['myMonth'], $_GET['myDay'], $_GET['myYear']));

    $query = "SELECT entry_id FROM coris_assistance_schedule WHERE user_id = '$_GET[user_id]' AND shift_id = '$_GET[shift]' AND `date` like '$myDate' AND active = 1";
	
	$result = mysql_query($query, $cn) or die(mysql_error());
    if (!$row = mysql_fetch_array($result)) {
        $query = "INSERT INTO coris_assistance_schedule(user_id, type_id, shift_id, `date`, date_start, date_end, date_created, modified) VALUES ($_GET[user_id], $_GET[type], $_GET[shift], '$myDate', '$_GET[date_start] $_GET[time_start]', '$_GET[date_end] $_GET[time_end]', NOW(), $modified)";
        $result = mysql_query($query, $cn);
    }

    echo "<script>window.opener.parent.AS_schedule_frame.location = \"AS_schedule_frame.php?offset=$_GET[offset]&myMonth=$_GET[myMonth]&myYear=$_GET[myYear]\";window.close()</script>";
}
?>
        </form>
        </center>
    </body>
</html>
