<?php include('include/include.php');  ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
    <body>
        <style>
            body {
                margin-top: 0.1cm;
                margin-bottom: 0.1cm;
                margin-left: 0.1cm;
                margin-right: 0.1cm;
            }
            td {
                font-size: 8pt;
            }
        </style>
        <table cellpadding="2" cellspacing="0" border="0" width="100%">
<?

if (isset($_GET['case_id'])) {
	
    $query = "SELECT acc.type_id, acct.value AS type_value, acc.contactno, acc.contactdesc, acc.date, u.name, u.surname, accc.color, accc.value FROM coris_assistance_cases_contacts acc, coris_assistance_cases_contacts_contacttypes accc, coris_users u, coris_assistance_cases_contacts_types acct WHERE acc.user_id = u.user_id AND acc.contacttype_id = accc.contacttype_id AND acc.type_id = acct.type_id AND acc.case_id = $_GET[case_id]";
	
    if ($result = mysql_query($query, $cn)) {
        $i = 0;
        while ($row = mysql_fetch_array($result)) {
?>
            <tr bgcolor="<?= ($i % 2) ? "#e0e0e0" : "" ?>" title="<?= AS_CASADD_TYP.": $row[type_value]\n".AS_CASADD_RODZ.": $row[value]\n".AS_CASD_DODPRZ.": $row[name] $row[surname]\n".DATE.": $row[date]" ?>" height="22">
                <td width="5%" style="cursor: default">
                    
<?
                        if ($row['type_id'] == 1)
                            echo '<img src="img/Tele.gif">';
                        else if ($row['type_id'] == 2) 
                            echo '<img src="img/Fax.gif">';
                        else if ($row['type_id'] == 3)
                            echo '<img src="img/Email.gif">';
?>
                    
                </td>
                <td align="center" width="23%" style="cursor: default"><small><font color="<?= $row['color'] ?>"><?= $row['value'] ?></font></small></td>
                <td width="27%" style="cursor: default"><b><small><?= $row['contactno'] ?></small></b></td>
                <td width="45%" style="cursor: default"><i><small><?= $row['contactdesc'] ?></small></i></td>
            </tr>
<?
            $i++;
        }
        mysql_free_result($result);
    } else {
        die (mysql_error());
    }

}
?>
        </title>
    </body>
</html>
