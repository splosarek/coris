<?php include('include/include.php'); 



if (isset($_GET['case_id'])) {

    if (isset($_GET['action'])) {
        mysql_query("BEGIN", $cn);

        $processing = (isset($_POST['processing'])) ? "1" : "0";
        $promotions = (isset($_POST['promotions'])) ? "1" : "0";

        $query = "UPDATE coris_assistance_cases SET paxname = '$_POST[paxname]', paxsurname = '$_POST[paxsurname]', paxdob = '$_POST[paxdob_y]-$_POST[paxdob_m]-$_POST[paxdob_d]', processing = $processing, promotions = $promotions WHERE case_id = '$_GET[case_id]'";

        if ($result = mysql_query($query, $cn)) {
            $query = "UPDATE coris_assistance_cases_details SET paxaddress = '$_POST[paxaddress]', paxpost = '$_POST[paxpost_1]-$_POST[paxpost_2]', paxcity = '$_POST[paxcity]', paxcountry = '$_POST[paxcountry]', paxphone = '$_POST[paxphone]', paxmobile = '$_POST[paxmobile]' WHERE case_id = '$_GET[case_id]'";

            if ($result = mysql_query($query, $cn)) {
                mysql_query("COMMIT", $cn);
                $updateOK = true;

            } else {
                mysql_query("ROLLBACK", $cn);
                die(mysql_error());
            }

        } else {
            mysql_query("ROLLBACK", $cn);
            die(mysql_error());
        }

    }

    $query = "SELECT ac.number, ac.year, ac.client_id, ac.type_id, ac.paxname, ac.paxsurname, ac.paxdob, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.costless, ac.unhandled, ac.processing, ac.promotions, ac.archive, ac.reclamation, ac.attention,  ac.attention2, acd.paxaddress, acd.paxpost, acd.paxcity, acd.paxcountry, acd.paxphone, acd.paxmobile FROM coris_assistance_cases ac, coris_assistance_cases_details acd WHERE ac.case_id = acd.case_id AND ac.case_id = $_GET[case_id]";

    if ($result = mysql_query($query, $cn)) {
        if ($row = mysql_fetch_array($result)) {
            $paxdob = array("","","");
            if ($row['paxdob'] != "0000-00-00")
                $paxdob = split("-", $row['paxdob']);

            $paxpost = array("", "");
            if ($row['paxpost'])
                $paxpost = split("-", $row['paxpost']);
        }
    } else {
        die(mysql_error());
    }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?php echo "$row[paxsurname], $row[paxname] [$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]] - ".AS_CASD_POSZK ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
	<body>
<?php
if (isset($updateOK)) {
    if ($updateOK) {
?>
        <script language="JavaScript1.2">
			if (opener && !opener.closed) {
				str = new String(opener.location);
				if (str.match(/AS_cases_view/)) {
					opener.assistcases_frame.location.reload();
				}
			}
		</script>
<?php
    }
}
?>
		<style>
			td.header {
				background: #6699cc;
				color: #ffffff;
				font-size: 8pt;
			}
            body {
                margin-top: 0.1cm;
                margin-bottom: 0.1cm;
                margin-left: 0.1cm;
                margin-right: 0.1cm;
            }
		</style>
        <script language="javascript">
        <!--
            function validate() {
                return true;
            }

			// TODO: Poprawia - aby nie by3o "for"
            function move(s) {
				e = window.event;
				var keyInfo = String.fromCharCode(e.keyCode);

				if (e['keyCode'] != 9 && e['keyCode'] != 16 && e['keyCode'] != 8) {
					for (var i = 0; i < form1.length; i++) {
						if (s.name == form1.elements[i].name) {
							if ((form1.elements[i].value.length == 2)) {
								form1.elements[i+1].focus();
								return false;
							}
						}
					}
				}
            }

			function remove(s) {
				e = window.event;
				var keyInfo = String.fromCharCode(e.keyCode);

				if (e['keyCode'] == 8) {
					for (var i = 0; i < form1.length; i++) {
						if (s.name == form1.elements[i].name) {
							if ((form1.elements[i].value.length == 0)) {
								form1.elements[i-1].focus();
								var rng = form1.elements[i-1].createTextRange();
								rng.select();
								return false;
							}
						}
					}
				}
			}

			// Kalendarz
            function y2k(number)    { return (number < 1000) ? number + 1900 : number; }
			var today;
			var day;
			var month;
			var year
            function newWindowCal(name) {

				today = new Date();
				day   = today.getDate();
				month = today.getMonth();
				year  = y2k(today.getYear());

				var width = 260;
				var height = 200;
				var left = (screen.availWidth - width) / 2;
				var top = ((screen.availHeight - 0.2 * screen.availHeight) - height) / 2;
                mywindow = window.open('calendar.php?name='+ name,'','resizable=no,width='+ width +',height='+ height +',left='+ left +',top='+ top);
            }
			
        //-->
        </script>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
            <form name="form1" action="AS_cases_details_contacts.php?case_id=<?php echo $_GET['case_id'] ?>&action=1" method="post" onsubmit="return validate()">
			<tr>
				<td width="90%">
					<table cellpadding="2" cellspacing="0" border="0" width="100%">
						<tr height="30">
							<td width="60%">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                                    <tr>
                                        <td width="35"><input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 50px; background: yellow" title="<?= AS_CASD_MSG_SAVEZM ?>">&nbsp;</td>
    <?php
    if (isset($updateOK)) {
        if ($updateOK) {
            echo "<td bgcolor=\"#6699cc\" align=\"center\"><font color=\"#dfdfdf\">".AS_CASD_MSG_DANZOSTZM."</font></td>";
        } else {
            echo "<td bgcolor=\"red\" align=\"center\"><font color=\"#dfdfdf\">".AS_CASD_MSG_BLZAP."</font></td>";
        }
    } else {
   // mysql_query("BEGIN", $cn);
        echo "<td></td>";
    }
    ?>
                                    </tr>
                                </table>
                            </td>
							<td bgcolor="#dfdfdf" align="right" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid;" rowspan="2" valign="top">
							<?PHP if (!(strcmp(1, $row['attention']))) {echo "<font style=\"background: red; color: yellow\">".ATTENTION2."</font> ";} 
							if ( $row['attention2'] ==1 ) {echo "<font style=\"background: #6699cc; color: yellow\">".ATTENTION2."</font>";} 
							?>
<?php
echo "<b>$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]</b><br>";

echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr height=\"3\"><td></td></tr></table>";


include('include/AS_cases_details_type_inc.php');
?>
							</td>
						</tr>
						<tr height="25">
							<td align="center" bgcolor="#eeeeee" style="border-top: #000000 1px solid; border-left: #000000 1px solid;"><?php echo $row['paxsurname'] ?>, <?php echo $row['paxname'] ?></td>
						</tr>
					</table>
                    <table cellpadding="2" cellspacing="0" border="0" width="100%" height="89%" bgcolor="#cccccc" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid; border-bottom: #000000 1px solid;">
                        <tr>
                            <td valign="top">
                                <table>
                                    <tr align="center" height="15">
										<td width="89" bgcolor="yellow" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid" align="center">
                                            <input type="button" value="<?= AS_CASD_SPR ?>" style="font-size: 7pt; line-height: 10px; height: 17px; width: 80px;" onclick="window.location='AS_cases_details.php?case_id=<?php echo $_GET['case_id'] ?>'">
                                        </td>
                                        <td width="89" bgcolor="yellow" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; cursor: default" align="center">
											<input type="button" value="<?= AS_CASD_POSZK2 ?>" style="background: yellow; font-size: 7pt; line-height: 10px; height: 17px; width: 80px;" disabled>
                                        </td>
										<td align="center" width="89" bgcolor="yellow" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
											<input type="button" value="<?= AS_CASD_UBEZP2 ?>" style="font-size: 7pt; line-height: 10px; height: 17px; width: 80px;" onclick="window.location='AS_cases_details_insurance.php?case_id=<?php echo $_GET['case_id'] ?>'">
										</td>
                                    </tr>
                                </table>
                                <table cellpadding="2" cellspacing="0" border="0" width="100%">
                                    <tr valign="top">
                                        <td width="60%">
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="lightyellow" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                                <tr>
                                                    <td colspan="2" align="right">
                                                        <small><font color="#6699cc"><?= AS_CASD_UBEZP ?></font></small>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70" align="right">
                                                        <small><b><?= AS_CASD_NAZW ?></b></small>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="paxsurname" value="<?php echo $row['paxsurname'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70" align="right">
                                                        <small><b><?= AS_CASD_IMIE ?></b></small>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="paxname" value="<?php echo $row['paxname'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70" align="right">
                                                        <small><?= AS_CASD_UR2 ?></small>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="paxdob_d" value="<?php echo $paxdob[2] ?>" size="1" onkeyup="move(this);">
                                                        <input type="text" name="paxdob_m" value="<?php echo $paxdob[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
                                                        <input type="text" name="paxdob_y" value="<?php echo $paxdob[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('paxdob')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <!-- Druga kolumna -->
                                        <td width="40%">
                                            <table cellpadding="2" cellspacing="4" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                                <tr valign="middle" height="38">
                                                    <td>
                                                        <input type="checkbox" name="processing" <?php echo ($row['processing']) ? "checked" : "" ?> style="background: yellow; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                                    </td>
                                                    <td>
                                                        <small><font color="#6699cc"><?= AS_CASD_ZGODAOSOB ?></font></small>
                                                    </td>
                                                </tr>
                                                <tr valign="middle" height="36">
                                                    <td>
                                                        <input type="checkbox" name="promotions" <?php echo ($row['promotions']) ? "checked" : "" ?> style="background: yellow; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                                    </td>
                                                    <td>
                                                        <small><font color="#6699cc"><?= AS_CASD_ZGODAOREKL ?></font></small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table cellpadding="2" cellspacing="0" border="0" width="100%">
                                    <tr valign="top">
                                        <td width="100%">
                                            <table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                                <tr>
                                                    <td colspan="2" align="right">
                                                        <small><font color="#6699cc"><?= AS_CASD_DANADR ?></font></small>&nbsp;
                                                    </td>
                                                </tr>
                                                <tr valign="middle">
                                                    <td width="70" align="right"><small><?= POST ?></small></td>
                                                    <td>
                                                        <input type="text" name="paxpost_1" value="<?php echo $paxpost[0] ?>" size="1" maxlength="2" onkeyup="move(this);">&nbsp;<input type="text" name="paxpost_2" value="<?php echo $paxpost[1] ?>" size="2" maxlength="3" onkeydown="remove(this);">&nbsp;&nbsp;<small><?= AS_CASES_MIAST ?></small>&nbsp;&nbsp;<input type="text" name="paxcity" value="<?php echo $row['paxcity'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                                                    </td>
                                                </tr>
                                                <tr valign="middle">
                                                    <td width="70" align="right"><small><?= ADDRESS ?></small></td>
                                                    <td>
                                                        <input type="text" name="paxaddress" value="<?php echo $row['paxaddress'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70" align="right"><small><?= COUNTRY?></small></td>
                                                    <td>
                                                        <input type="text" name="paxcountry" value="<?php echo $row['paxcountry'] ?>" size="3" maxlength="2" onblur="document.forms['form1'].elements['paxcountrylist'].value = document.forms['form1'].elements['paxcountry'].value.toUpperCase(); document.forms['form1'].elements['paxcountry'].value = document.forms['form1'].elements['paxcountry'].value.toUpperCase()" style="text-align: center">
                                                        <select style="font-size: 8pt;" name="paxcountrylist" onchange="document.forms['form1'].elements['paxcountry'].value = document.forms['form1'].elements['paxcountrylist'].value">
                                                            <option value=""></option>
<?php
$result = mysql_db_query("coris","SELECT country_id, name, prefix FROM coris_countries ORDER BY name");
while ($row2 = mysql_fetch_array($result)) {
?>
                                                            <option value="<?php echo $row2['country_id'] ?>" <?php echo ($row2['country_id'] == $row['paxcountry']) ? "selected" : "" ?>><?php echo $row2['name'] ?></option>
<?php
}
?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70" align="right"><small><?= PHONE ?></td>
                                                  <td>
                                                    <input type="text" name="paxphone" value="<?php echo $row['paxphone'] ?>" size="32" maxlength="30" onkeydown="remove(this);"></td>
                                                </tr>
                                                <tr>
                                                    <td width="70" align="right"><small><?= AS_CASADD_TELKOM ?></small></td>
                                                  <td>
                                                    <input type="text" name="paxmobile" value="<?php echo $row['paxmobile'] ?>" size="32" maxlength="30"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table cellpadding="2" cellspacing="1" border="0" width="100%">
                                    <tr height="20" bgcolor="#eeeeee">
                                        <td width="5%" align="center" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                            <input style="width: 20px;" type="button" value="+" onclick="window.open('AS_cases_details_contacts_add.php?case_id=<?php echo $_GET['case_id'] ?>','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=610,height=100,left='+ (screen.availWidth - 610) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 100) / 2);" title="Dodaj kontakt"> 
                                        </td>
                                        <td width="95%" align="right" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                            <font color="#6699cc"><small><?= AS_CASD_KONTWSPR ?>&nbsp;</small></font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" valign="top">
                                            <iframe name="AS_cases_details_contacts_frame" width="100%" height="151" frameborder="0" src="AS_cases_details_contacts_frame.php?case_id=<?php echo $_GET['case_id'] ?>" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-top: #6699cc 1px solid;"></iframe>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="10%">
                    <table cellpadding="2" cellspacing="0" border="0" style="border-top: #000000 1px solid; border-right: #000000 1px solid;">
                        <tr height="54">
                            <td bgcolor="<?php echo ($row['type_id'] == 1) ? "orange" : "#6699cc" ?>">
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="2" cellspacing="0" border="0" height="89%" bgcolor="#ffffff" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid;">
                        <tr>
                            <td valign="top" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr height="50">
                                        <td align="center"><font color="#6699cc" face="Webdings" title="teczka" style="font-size: 32pt">Ì</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_TECZKA2 ?></font></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_variables.php?case_id=<?php echo $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_USTAW ?>">'</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_USTAW ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_expenses.php?case_id=<?php echo $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 32pt" title="<?= AS_CASD_WYK ?>">@</font>&nbsp;<br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_WYK ?></font></a></td>
                                    </tr>
                                    <tr height="50">
										<td align="center"><a href="AS_cases_details_history.php?case_id=<?php echo $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_DOK ?>">Ò</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_DOK ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_note.php?case_id=<?php echo $_GET['case_id'] ?>"><font color="#ced9e2" face="webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_NOT ?>">¤</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_NOT ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_todo.php?case_id=<?php echo $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_ZAD ?>">ë</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_ZAD ?></font></a></td>
                                    </tr>
                                </table>
                                <!-- <a href="javascript:void(0)"><font color="#ced9e2" size="+4" face="webdings" onmouseover="this.color='green'" onmouseout="this.color='#ced9e2'" title="finanse">‘</font></a><br> -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </form>
        </table>
    </body>
</html>
<?php
}
?>
