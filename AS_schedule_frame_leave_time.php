<html>
    <head>
    </head>
    <body>
		<style>
			body {
				background: #dfdfdf;
				font-family: Verdana;
				font-size: 8pt;
				margin-top: 0.1cm;
				margin-bottom: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
			}
			td {
				font-size: 8pt;
			}
            tr.complete {
                color: #999999;
            }
		</style>
        <script language="JavaScript">

            var tim1, tim2, tim3;
            tim1 = tim2 = tim3 = false;

            function setTime(s) {
                switch (s) {
                    case 1:
                        tim1 = true;
                        break;
                    case 2:
                        tim2 = true;
                        break;
                    case 3:
                        tim3 = true;
                        break;
                }

                if (tim1 && tim2 && tim3) {
                    opener.form1.time.value = form1.hour.value + ":" + form1.min10.value + form1.min1.value;
                    window.close();
                }
                
            }
        </script>
        <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#d0d0d0">
            <form name="form1">
            <tr align="center">
                <td>
                    <input type="text" name="hour" size="1" style="width: 25px; text-align: center">
                </td>
                <td>
                    <font size="1">:</font>
                </td>
                <td>
                    <input type="text" name="min10" size="1" style="width: 15px; text-align: center">
                </td>
                <td>
                    <input type="text" name="min1" size="1" style="width: 15px; text-align: center">
                </td>
            </tr>
            <tr>
                <td width="50%" valign="top" align="center">
                    <table width="100%">
<?
for ($i = 0; $i < 24; $i++) {
?>
                        <tr onclick="form1.hour.value=<?= $i ?>; setTime(1)">
                            <td onmouseover="this.bgColor='#dddddd'" onmouseout="this.bgColor='#6699cc'" style="color: <?= ($i < 8 || $i > 22) ? "#99ccee" : "#ffffff" ?>; cursor: default" bgcolor="#6699cc" align="center"><?= $i ?></td>
                        </tr>
<?
}
?>
                    </table>
                </td>
                <td></td>
                <td width="25%" valign="top" align="center">
                    <table width="100%">
<?
for ($i = 0; $i < 6; $i++) {
?>
                        <tr onclick="form1.min10.value=<?= $i ?>; setTime(2)">
                            <td onmouseover="this.bgColor='#dddddd'" onmouseout="this.bgColor='#6699cc'" style="color: #ffffff; cursor: default" bgcolor="#6699cc" align="center"><?= $i ?></td>
                        </tr>
<?
}
?>
                    </table>
                </td>
                <td width="25%" valign="top" align="center">
                    <table width="100%">
<?
for ($i = 0; $i < 10; $i++) {
?>
                        <tr onclick="form1.min1.value=<?= $i ?>; setTime(3)">
                            <td onmouseover="this.bgColor='#dddddd'" onmouseout="this.bgColor='#6699cc'" style="color: #ffffff; cursor: default" bgcolor="#6699cc" align="center"><?= $i ?></td>
                        </tr>
<?
}
?>
                    </table>
                </td>
            </tr>
            </form>
        </table>
    </body>
</html>
