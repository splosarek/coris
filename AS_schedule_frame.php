<?php include('include/include.php');  ?>
<html>
    <head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
    </head>
	<body onload="<?php echo (isset($_GET['offset'])) ? "document.body.scrollTop = '$_GET[offset]'" : "" ?>">
    <style>
        body {
            background: #e0e0e0;
            font-family: Verdana;
            font-size: 8pt;
        }
		a {
			color: navy;
			text-decoration: none;
		}
        td {
            font-size: 8pt;
			word-wrap: break-word;
        }
        td.button {
			color: #999999;
            border-left: #999999 1px solid; 
            border-right: #999999 1px solid; 
            border-top: #999999 1px solid; 
            border-bottom: #999999 1px solid; 
            cursor: hand;
            line-height: 5px
        }
        td.header {
			background: #f0f0f0;
            border-left: #999999 1px solid;
            border-right: #999999 1px solid;
            border-bottom: #999999 1px solid;
            border-top: #999999 1px solid;
        }
        td.main {
            border-left: #c0c0c0 1px solid;
            border-right: #c0c0c0 1px solid;
            border-bottom: #c0c0c0 1px solid;
            border-top: #c0c0c0 1px solid;
            font-size: 8pt;
        }
        table.title {
            border-left: #000000 1px solid;
            border-right: #000000 1px solid;
            border-bottom: #000000 1px solid;
            border-top: #000000 1px solid;
        }
    </style>
<?php
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
    	<script language="JavaScript1.2">
        <!--
            function newEntry(d, m, y, shift, type) {
				var url = "AS_schedule_frame_entry.php?offset="+ document.body.scrollTop + "&myDay="+ d +"&myMonth="+ m +"&myYear="+ y +"&shift="+ shift +"&type="+ type;
                window.open(url, 'NewEntry', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=200,height=270,left='+ (screen.availWidth - 200) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 270) / 2);
            }
            function newEntryDR(d, m, y, shift, type) {
                var url = "AS_schedule_frame_entry.php?offset="+ document.body.scrollTop +"&myDay="+ d +"&myMonth="+ m +"&myYear="+ y +"&shift="+ shift +"&type="+ type;
                window.open(url, 'NewEntry', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=200,height=270,left='+ (screen.availWidth - 200) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 270) / 2);
            }
            function newLeave(d, m, y) {
                var url = "AS_schedule_frame_leave.php?offset="+ document.body.scrollTop +"&myDay="+ d +"&myMonth="+ m +"&myYear="+ y;
                window.open(url, 'NewLeave', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=350,left='+ (screen.availWidth - 550) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 350) / 2);
            }
            function deleteEntry(e) {
                var url = "AS_schedule_frame_iframe.php?offset="+ document.body.scrollTop +"&myMonth=<?php echo $month ?>&myYear=<?php echo $year ?>&entry_id="+ e;
                if (confirm("Czy na pewno chcesz usunąć wpis?")) {
                    assistance_schedule_frame_iframe.location=url;
                }
            }
            function deleteLeave(l) {
                var url = "AS_schedule_frame_iframe.php?offset="+ document.body.scrollTop +"&myMonth=<?php echo $month ?>&myYear=<?php echo $year ?>&leave_id="+ l;
                if (confirm("Czy na pewno chcesz usunąć wpis?")) {
                    assistance_schedule_frame_iframe.location=url;
                }
            }
            function printSchedule() {
                var url = "AS_schedule_frame_print.php?myYear=<?php echo $year ?>&myMonth=<?php echo $month ?>";
                window.open(url, 'PrintSchedule', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=500,left='+ (screen.availWidth - 700) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 500) / 2);
            }
        //-->
    </script>
<?php
$months = array(1 => "Styczeń", "Luty", "Marzec", "Kwiecień", "Maj", "Czerwiec", "Lipiec", "Sierpień", "Wrzesień", "Październik", "Listopad", "Grudzień");


$query = "SELECT cas.entry_id, cas.user_id, uc.code, u.initials, cas.type_id, cas.shift_id, dayofmonth(cas.date) as day, SUBSTRING(cas.date_start, 12, 5) AS date_start, SUBSTRING(cas.date_end, 12, 5) AS date_end, cas.modified FROM coris_assistance_schedule cas, coris_users u, coris_colors uc WHERE u.user_id = cas.user_id AND u.color_id = uc.color_id AND cas.active = 1 AND Month(date) = $month AND Year(date) = $year ORDER BY cas.date, cas.type_id, cas.shift_id, cas.user_id";

$result = mysql_query($query, $cn) or die(mysql_error());

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

		$myArray[$i][$j][$k] = $myArray[$i][$j][$k] . " " . "<a href=\"javascript:void(0)\" onclick=\"deleteEntry($row[entry_id])\" title=\"$row[date_start] - $row[date_end]\"><font color=\"$row[code]\">$row[initials]</font></a><font style=\"vertical-align: super; font-size: 5pt;\">$small_date_start-$small_date_end</font>";
    } else {
		$myArray[$i][$j][$k] = $myArray[$i][$j][$k] . " " . "<a href=\"javascript:void(0)\" onclick=\"deleteEntry($row[entry_id])\" title=\"$row[date_start] - $row[date_end]\"><font color=\"$row[code]\">$row[initials]</font></a>";
    }
    $myArray_nm[$i][$j][$k] = $myArray_nm[$i][$j][$k] . " " . $row['initials'];
}

$query = "SELECT cas.leave_id, u.initials, uc.code, DAYOFMONTH(date_start) AS date_start_day, Month(date_start) AS date_start_month, Year(date_start) AS date_start_year, DAYOFMONTH(date_end) AS date_end_day, Month(date_end) AS date_end_month, Year(date_end) AS date_end_year, cas.date_start, cas.date_end FROM coris_assistance_schedule_leaves cas, coris_users u, coris_colors uc WHERE u.user_id = cas.user_id AND u.color_id = uc.color_id AND cas.active = 1 AND (Month(date_start) = $month  OR Month(date_end) = $month) AND (Year(date_start) = $year OR Year(date_end) = $year) ORDER BY cas.date_start, cas.date_end, cas.user_id";
$result = mysql_query($query, $cn) or die(mysql_error());

for ($i = 0; $i <= 31; $i++) {
    $leaves[$i] = "";
}
while ($row = mysql_fetch_array($result)) {
    $leave_start = mktime(0, 0, 0, $row['date_start_month'], $row['date_start_day'], $row['date_start_year']);
    $leave_end = mktime(0, 0, 0, $row['date_end_month'], $row['date_end_day'], $row['date_end_year']);
    for ($i = 0; $i <= 31; $i++) {
        $now = mktime(0, 0, 0, $month, $i, $year);
        if(($leave_start <= $now) && ($leave_end >= $now)) {
			$leaves[$i] = $leaves[$i] . " " . "<a href=\"javascript:void(0)\" onclick=\"deleteLeave($row[leave_id])\"><font color=\"$row[code]\">" . $row['initials'] . "</font></a>";
        }
    }
}

$query = "SELECT DAYOFMONTH(date) AS day FROM coris_assistance_schedule_holidays WHERE active = 1 AND Month(date) = $month AND Year(date) = $year ORDER BY date";
$result = mysql_query($query, $cn) or die(mysql_error());

for ($i = 0; $i <= 31; $i++) {
    $holiday[$i] = 0;
}
while ($row = mysql_fetch_array($result)) {
    $holiday[$row['day']] = 1;
}
?>
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr align="center">
                <td colspan="2">&nbsp;
                    
                </td>
                <td <?php echo ($month <= 3 && $year == 2004) ? "colspan=\"3\"" : "" ?> bgcolor="#eeeeee" class="header">
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
<?php
if ($month <= 3 && $year == 2004) {                
?>
                <td class="header">
                    Po<br>nocy
                </td>
                <td class="header">
                    Na<br>noc
                </td>
<?php
}
?>
                <td class="header" width="10%">
                    Urlop
                </td>
                <td class="header" width="20%">
                    8:00-16:00
                </td>
                <td class="header" width="20%">
                    9:00-17:00
                </td>
                <td class="header" width="10%">
                    DR rano
                </td>
                <td class="header" width="20%">
                    dni pow.<br>14:00-22:00<br>dni świąt.<br>12:00-20:00
                </td>
                <td class="header" width="10%">
                    DR wieczór
                </td>
                <td class="header" width="10%">
                    dni pow.<br>22:00-8:00<br>święta<br>20:00-9:00
                </td>
            </tr>
<?php
$monthdays = array(1=>31, 3=>31, 4=>30, 5=>31, 6=>30, 7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
$date = mktime(0, 0, 0, $month, 1, $year);
$leap = date("L", $date);
$monthdays[2] = ($leap ? 29 : 28);

for ($i = 1; $i <= $monthdays[$month]; $i++) {
    $day = date("D", mktime(0, 0, 0, $month, $i, $year));
?>
<tr align="center" bgcolor="<?php if ($day == "Sat" || $day == "Sun" || $holiday[$i] ) echo "#dddddd"; else if ($year == date("Y") && $month == date("m") && $i == date("d")) echo "#ced9e2"; else echo "#ffffff"; ?>">
                <td class="header"><?php echo $i ?></td>
                <td class="header"><?php echo $day ?></td>
<?php
if ($month <= 3 && $year == 2004) {                
?>
                <td class="main"><?php if ($i > 0) if ($myArray_nm[$i-1][1][4]) echo $myArray_nm[$i-1][1][4]; else echo "&nbsp;"; else echo "&nbsp;"; ?></td>
                <td class="main"><?php echo ($myArray_nm[$i][1][4]) ? $myArray_nm[$i][1][4] : "&nbsp;" ?></td>
<?php
}
?>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newLeave('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''" class="button">+</td><td align="center"><?php echo ($leaves[$i]) ? $leaves[$i] : "&nbsp;" ?></td></tr></table></td>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newEntry('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>', '1', '1')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''" class="button">+</td><td align="center"><?php echo ($myArray[$i][1][1]) ? ($myArray[$i][1][1]) : "&nbsp;" ?></td></tr></table></td>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newEntry('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>', '2', '1')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''" class="button">+</td><td align="center"><?php echo ($myArray[$i][1][2]) ? $myArray[$i][1][2] : "&nbsp;" ?></td></tr></table></td>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newEntryDR('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>', '1', '2')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''" class="button">+</td><td align="center"><?php echo ($myArray[$i][2][1]) ? $myArray[$i][2][1] : "&nbsp;" ?></td></tr></table></td>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newEntry('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>', '3', '1')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''" class="button">+</td><td align="center"><?php echo ($myArray[$i][1][3]) ? $myArray[$i][1][3] : "&nbsp;" ?></td></tr></table></td>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newEntryDR('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>', '2', '2')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''"  class="button">+</td><td align="center"><?php echo ($myArray[$i][2][2]) ? $myArray[$i][2][2] : "&nbsp;" ?></td></tr></table></td>
                <td class="main"><table width="100%"><tr><td width="2" onclick="newEntry('<?php echo $i ?>', '<?php echo $month ?>', '<?php echo $year ?>', '4', '1')" onmouseover="this.bgColor='lightyellow'" onmouseout="this.bgColor=''" class="button">+</td><td align="center"><?php echo ($myArray[$i][1][4]) ? $myArray[$i][1][4] : "&nbsp;" ?></td></tr></table></td>
            </tr>
<?php
}
?>
        </table>
        <iframe name="assistance_schedule_frame_iframe" src="AS_schedule_frame_iframe.php" width="0" height="0"></iframe>
    </body>
</html>
<?php
mysql_free_result($result);
?>