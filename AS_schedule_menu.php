<html>
    <head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
    </head>
    <body>
    <style>
        body {
            background: #dfdfdf;
            font-family: Verdana;
            font-size: 9pt;
        }
        td {
            font-size: 9pt;
        }
    </style>
    <script language="JavaScript">
        <!--
        function updateSchedule() {
            var url = "AS_schedule_frame.php?myYear="+ form1.myYear.value +"&myMonth="+ form1.myMonth.value;
            parent.AS_schedule_frame.document.location=url;
        }
        //-->
    </script>
<?php
echo "<div align=\"right\">";
$months = array(1 => "styczeń", "luty", "marzec", "kwiecień", "maj", "czerwiec", "lipiec", "sierpień", "wrzesień", "październik", "listopad", "grudzień");

$month = date("m");

echo "<form name=\"form1\">";
echo "<a href=\"javascript:void(0)\" onclick=\"parent.AS_schedule_frame.printSchedule()\" style=\"text-decoration: none\"><font style=\"font-size: 6pt; vertical-align: super; \">drukuj&nbsp;</font><img src=\"graphics/ico_print.gif\" border=\"0\"></a>";
echo " <select name=\"myMonth\" onchange=\"updateSchedule();\">";
for ($i = 1; $i <= 12; $i++) {
    if ($i == $month)
        echo "<option value=\"$i\" selected>$months[$i]</option>";
    else 
        echo "<option value=\"$i\">$months[$i]</option>";
}
echo "</select>";

$year = date("Y");

echo " <select name=\"myYear\" onchange=\"updateSchedule();\">";
for ($i = $year - 4; $i <= $year + 1; $i++) {
    if ($i == $year)
        echo "<option value=\"$i\" selected>$i</option>";
    else 
        echo "<option value=\"$i\">$i</option>";
}
echo "</select>";
echo "</form>";
echo "</div>";
?>
    </body>
</html>
