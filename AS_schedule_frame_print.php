<?php include('include/include.php'); 

?>
<html>
    <head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
    </head>
    <body onload="if (window.print) window.print();">
    <style>
        body {
            background: #ffffff;
            font-family: Verdana;
            font-size: 8pt;
        }
        td {
            font-size: 8pt;
        }
        td.button {
            border-left: #000000 1px solid; 
            border-right: #000000 1px solid; 
            border-top: #000000 1px solid; 
            border-bottom: #000000 1px solid; 
            cursor: hand;
            line-height: 5px
        }
        td.header {
            border-left: #000000 1px solid;
            border-right: #000000 1px solid;
            border-bottom: #000000 1px solid;
            border-top: #000000 1px solid;
        }
        td.main {
            border-left: #999999 1px solid;
            border-right: #999999 1px solid;
            border-bottom: #999999 1px solid;
            border-top: #999999 1px solid;
            font-size: 8pt;
        }
        td.doctor {
            border-left: #999999 1px solid;
            border-right: #999999 1px solid;
            border-bottom: #999999 1px solid;
            border-top: #999999 1px solid;
            font-size: 8pt;
            background: #eeeeee;
        }
        td.special {
            background: #cccccc;
            border-left: #666666 1px solid;
            border-right: #666666 1px solid;
            border-bottom: #666666 1px solid;
            border-top: #666666 1px solid;
            font-size: 8pt;
        }
        table.title {
            border-left: #000000 1px solid;
            border-right: #000000 1px solid;
            border-bottom: #000000 1px solid;
            border-top: #000000 1px solid;
        }
    </style>
<?
if (isset($_GET['myMonth'])) {
    $month = $_GET['myMonth'];
} else {
    $month = date("n");
}
if (isset($_GET['myYear'])) {
    $year = $_GET['myYear'];
} else {
    $year = date("Y");
}
?>
<?php
$months = array(1 => "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");


$query = "SELECT cas.entry_id, cas.user_id, u.initials, cas.type_id, cas.shift_id, dayofmonth(cas.date) as day, SUBSTRING(cas.date_start, 12, 5) AS date_start, SUBSTRING(cas.date_end, 12, 5) AS date_end, cas.modified FROM coris_assistance_schedule cas, coris_users u WHERE u.user_id = cas.user_id AND cas.active = 1 AND Month(date) = $month AND Year(date) = $year ORDER BY cas.date, cas.type_id, cas.shift_id, cas.user_id";
$result = mysql_query($query, $cn);

$day = 1;

for ($i = 0; $i <= 31; $i++) {
    for ($j = 0; $j <= 2; $j++) {
        for ($k = 0; $k <= 4; $k++) {
            $myArray[$i][$j][$k] = "";
            $myArray_nm[$i][$j][$k] = "";
        }
    }
}


while ($row = mysql_fetch_array($result)) {
    $i = $row['day'];
    $j = $row['type_id'];
    $k = $row['shift_id'];


    if ($row['modified'] == 1) {

        if (preg_match("/^0\d/", $row['date_start']))
            $small_date_start = substr($row['date_start'], 1, 4);
        else 
            $small_date_start = $row['date_start'];

        if (preg_match("/^0\d/", $row['date_end']))
            $small_date_end = substr($row['date_end'], 1, 4);
        else
            $small_date_end = $row['date_end'];

        if (strstr($small_date_start, ":00")) {
            if (preg_match("/^\d:/", $small_date_start)) {
                $small_date_start = substr($small_date_start, 0, 1);
            } else {
                $small_date_start = substr($small_date_start, 0, 2);
            }
        }

        if (strstr($small_date_end, ":00")) {
            if (preg_match("/^\d:/", $small_date_end)) {
                $small_date_end = substr($small_date_end, 0, 1);
            } else {
                $small_date_end = substr($small_date_end, 0, 2);
            }
        }

        $myArray[$i][$j][$k] = $myArray[$i][$j][$k] . " " . "$row[initials]<font style=\"vertical-align: super; font-size: 5pt;\">$small_date_start-$small_date_end</font>";
    } else {
        $myArray[$i][$j][$k] = $myArray[$i][$j][$k] . " " . "$row[initials]";
    }
    $myArray_nm[$i][$j][$k] = $myArray_nm[$i][$j][$k] . " " . $row['initials'];
}

$query = "SELECT cas.leave_id, u.initials, DAYOFMONTH(date_start) AS date_start_day, Month(date_start) AS date_start_month, Year(date_start) AS date_start_year, DAYOFMONTH(date_end) AS date_end_day, Month(date_end) AS date_end_month, Year(date_end) AS date_end_year, cas.date_start, cas.date_end FROM coris_assistance_schedule_leaves cas, coris_users u WHERE u.user_id = cas.user_id AND cas.active = 1 AND (Month(date_start) = $month  OR Month(date_end) = $month) AND (Year(date_start) = $year OR Year(date_end) = $year) ORDER BY cas.date_start, cas.date_end, cas.user_id";
$result = mysql_query($query, $cn);

for ($i = 0; $i <= 31; $i++) {
    $leaves[$i] = "";
}
while ($row = mysql_fetch_array($result)) {
    $leave_start = mktime(0, 0, 0, $row['date_start_month'], $row['date_start_day'], $row['date_start_year']);
    $leave_end = mktime(0, 0, 0, $row['date_end_month'], $row['date_end_day'], $row['date_end_year']);
    for ($i = 0; $i <= 31; $i++) {
        $now = mktime(0, 0, 0, $month, $i, $year);
        if(($leave_start <= $now) && ($leave_end >= $now)) {
            $leaves[$i] = $leaves[$i] . " " . $row['initials'];
        }
    }
}

$query = "SELECT DAYOFMONTH(date) AS day FROM coris_assistance_schedule_holidays WHERE active = 1 AND Month(date) = $month AND Year(date) = $year ORDER BY date";
$result = mysql_query($query, $cn);

for ($i = 0; $i < 31; $i++) {
    $holiday[$i] = 0;
}
while ($row = mysql_fetch_array($result)) {
    $holiday[$row['day']] = 1;
}
?>
        <table width="100%" cellpadding="2" cellspacing="0" class="title">
            <tr>
                <td height="30" align="center" valign="middle" bgcolor="#dfdfdf">
                    <b><?= $months[$month] ?> / <?= $year ?></b>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr align="center">
                <td colspan="2">&nbsp;
                    
                </td>
                <td <?= ($month <= 3 && $year == 2004) ? "colspan=\"3\"" : "" ?> bgcolor="#eeeeee" class="header">
                    <b>Braki</b>
                </td>
                <td colspan="3" bgcolor="#eeeeee" class="header">
                    <b>Rano</b>
                </td>
                <td colspan="2" bgcolor="#eeeeee" class="header">
                    <b>Po południu</b>
                </td>
                <td bgcolor="#eeeeee" class="header">
                    <b>Noc</b>
                </td>
            </tr>
            <tr align="center">
                <td colspan="2">&nbsp;
                    
                </td>
<?
if ($month <= 3 && $year == 2004) {                
?>
                <td class="header">
                    Po<br>nocy
                </td>
                <td class="header">
                    Na<br>noc
                </td>
<?
}
?>
                <td class="header" width="80">
                    Urlop
                </td>
                <td class="header" width="80">
                    8:00-16:00
                </td>
                <td class="header" width="100">
                    9:00-17:00
                </td>
                <td class="header" width="60">
                    DR rano
                </td>
                <td class="header" width="100">
                    dni pow.<br>14:00-22:00<br>dni świąt.<br>12:00-20:00
                </td>
                <td class="header" width="60">
                    DR wieczór
                </td>
                <td class="header" width="80">
                    dni pow.<br>22:00-8:00<br>święta<br>20:00-9:00
                </td>
            </tr>
<?
$monthdays = array(1=>31, 3=>31, 4=>30, 5=>31, 6=>30, 7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
$date = mktime(0, 0, 0, $month, 1, $year);
$leap = date("L", $date);
$monthdays[2] = ($leap ? 29 : 28);

for ($i = 1; $i <= $monthdays[$month]; $i++) {
    $day = date("D", mktime(0, 0, 0, $month, $i, $year));
    $special = ($day == "Sat" || $day == "Sun" || $holiday[$i]) ? 1 : 0;
?>
            <tr align="center">
                <td class="header" bgcolor="#bbbbbb"><?= ($special) ? "<B>$i</B>" : $i ?></td>
                <td class="header" bgcolor="#bbbbbb"><?= ($special) ? "<B>$day</B>" : $day ?></td>
<?
if ($month <= 3 && $year == 2004) {                
?>
                <td class="<?= ($special) ? "special" : "main" ?>"><? if ($i > 0) if ($myArray_nm[$i-1][1][4]) echo $myArray_nm[$i-1][1][4]; else echo "&nbsp;"; else echo "&nbsp;"; ?></td>
                <td class="<?= ($special) ? "special" : "main" ?>"><?= ($myArray_nm[$i][1][4]) ? $myArray_nm[$i][1][4] : "&nbsp;" ?></td>
<?
}
?>
                <td class="<?= ($special) ? "special" : "main" ?>" width="80"><?= ($leaves[$i]) ? $leaves[$i] : "&nbsp;" ?></td>
                <td class="<?= ($special) ? "special" : "main" ?>" width="80"><?= ($myArray[$i][1][1]) ? ($myArray[$i][1][1]) : "&nbsp;" ?></td>
                <td class="<?= ($special) ? "special" : "main" ?>" width="100"><?= ($myArray[$i][1][2]) ? $myArray[$i][1][2] : "&nbsp;" ?></td>
                <td class="<?= ($special) ? "special" : "doctor" ?>" width="60"><?= ($myArray[$i][2][1]) ? $myArray[$i][2][1] : "&nbsp;" ?></td>
                <td class="<?= ($special) ? "special" : "main" ?>" width="100"><?= ($myArray[$i][1][3]) ? $myArray[$i][1][3] : "&nbsp;" ?></td>
                <td class="<?= ($special) ? "special" : "doctor" ?>" width="60"><?= ($myArray[$i][2][2]) ? $myArray[$i][2][2] : "&nbsp;" ?></td>
                <td class="<?= ($special) ? "special" : "main" ?>" width="80"><?= ($myArray[$i][1][4]) ? $myArray[$i][1][4] : "&nbsp;" ?></td>
            </tr>
<?
}
?>
        </table>
        <br>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td width="90"><b>Utworzono:</b></td>
                <td><?= date('Y-m-d G:i') ?></td>
            <tr>
            <tr>
                <td width="90"><b>Stacja:</b></td>
                <td><?= (!empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : (( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR ) ?></td>
            </tr>
        </table>
    </body>
</html>