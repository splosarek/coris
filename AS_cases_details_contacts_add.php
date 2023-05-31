<?php include('include/include.php'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= CONTACTADD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
    <body bgcolor="#d9d9d9">
        <script language="javascript">
        <!--
            function validate() {
                if (form1.type.value == 0) {
                    alert("Wybierz typ kontaktu");
                    form1.type.focus();
                    return false;
                }
                if (!form1.contactno.value) {
                    alert("Wpisz kontakt");
                    form1.contactno.focus();
                    return false;
                }
                if (form1.contacttype.value == 0) {
                    alert("Wybierz rodzaj kontaktu");
                    form1.contacttype.focus();
                    return false;
                }
             /* if (!form1.contactdesc.value) {
                    alert("Opisz kontakt");
                    form1.contactdesc.focus();
                    return false;
                } */
            }
        //-->
        </script>
<?


if (isset($_GET['action'])) {
    $query = "INSERT INTO coris_assistance_cases_contacts (case_id, type_id, contactno, contactdesc, contacttype_id, user_id, date) VALUES ('$_POST[case_id]', '$_POST[type]', '$_POST[contactno]', '$_POST[contactdesc]', '$_POST[contacttype]', '$_SESSION[user_id]', NOW())";

    if ($result = mysql_query($query, $cn)) {
        echo "<script>opener.AS_cases_details_contacts_frame.document.location.reload(); window.close();</script>";
        exit;
    } else {
        die (mysql_error());
    }

}
?>
        <form action="AS_cases_details_contacts_add.php?action=1" method="post" name="form1" onsubmit="return validate();">
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="popupTitle"><?= CONTACTADD ?>&nbsp;</td>
		</tr>
	</table>	
        <table cellpadding="2" cellspacing="0" width="100%">
            <tr height="0">
                <td><small><?= AS_CASADD_TYP ?></small></td>
                <td><small><?= AS_CASADD_NRADR ?></small></td>
                <td><small><?= AS_CASADD_RODZ ?></small></td>
                <td><small><?= AS_CASADD_OPIS ?></small></td>
            </tr>
            <tr>
                <td>
                    <select name="type" style="">
                        <option value="0"></option>
                        <option value="1">Tel.</option>
                                    <option value="2">Fax</option>
                                    <option value="3">Email</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="contactno" size="20" maxlength="50" style="text-align: center">
                </td>
                <td>
<?
$query = "SELECT contacttype_id, value FROM coris_assistance_cases_contacts_contacttypes ORDER BY listorder";

if ($result = mysql_query($query, $cn)) {
    echo "<select name=\"contacttype\">";
    echo "<option value=\"0\"></option>";
    while ($row = mysql_fetch_array($result)) {
        echo "<option value=\"$row[contacttype_id]\">$row[value]</option>";
    }
    echo "</select>";
    mysql_free_result($result);
} else {
    die (mysql_error());
}
?>
                </td>
                <td>
                    <input type="text" name="contactdesc" onchange="javascript:this.value=this.value.toUpperCase();" size="40" maxlength="100">
                </td>
            </tr>
            <!-- // TODO: ZROBIĆ ODSTĘP -->
            <tr>
                <td align="center" colspan="4">
                    <input type="hidden" name="case_id" value="<?= $_GET['case_id'] ?>">
                    <input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow">
                </td>
            </tr>
        </table>
	     </form>
    </body>
</html>
