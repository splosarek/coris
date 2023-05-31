<?php include('include/include.php'); 

if (isset($_GET['case_id'])) {
    
	$query = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention, attention2 FROM coris_assistance_cases WHERE case_id = $_GET[case_id]";

	if ($_SESSION['new_user']==1){
			$query .= " AND `date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 )";			
	}
    if ($result = mysql_query($query)){
    	if (mysql_num_rows($result)==0)	die('brak sprawy');
        $row = mysql_fetch_array($result);
    }else
        die(mysql_error());

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?php echo "$row[paxsurname], $row[paxname] [$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]] - ".AS_CASD_DOZR ?></title>
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
		</style>
		<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
			<tr>
				<td width="90%">
					<table cellpadding="2" cellspacing="0" border="0" width="100%">
						<tr height="30">
							<td width="60%"></td>
							<td bgcolor="#dfdfdf" align="right" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid;" rowspan="2" valign="top">
							<?PHP if (!(strcmp(1, $row['attention']))) {echo "<font style=\"background: red; color: yellow\">".ATTENTION2."</font>";} 
							if ( $row['attention2'] ==1 ) {echo "<font style=\"background: #6699cc; color: yellow\">".ATTENTION2."</font>";} 
							?>							
    <?
    echo "<b>$row[number]/". substr($row['year'],2) ."/$row[type_id]/$row[client_id]</b><br>";

    echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr height=\"3\"><td></td></tr></table>";

  
include('include/AS_cases_details_type_inc.php');

    ?>
							</td>
						</tr>
						<tr height="25">
							<td align="center" bgcolor="#eeeeee" style="border-top: #000000 1px solid; border-left: #000000 1px solid;"><?= $row['paxsurname'] ?>, <?= $row['paxname'] ?></td>
						</tr>
					</table>
                    <table cellpadding="2" cellspacing="0" border="0" width="100%" height="89%" bgcolor="#cccccc" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid; border-bottom: #000000 1px solid;">
                        <tr>
                            <td valign="top">
                                    <table cellpadding="2" cellspacing="2" border="0" bgcolor="#cccccc" width="100%" height="100%">
                                        <tr height="20" bgcolor="#eeeeee">
                                            <td align="right" colspan="2" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                                <font color="#6699cc"><small><?= NOTIFICATION ?>&nbsp;</small></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" valign="top">
                                                <iframe name="todoframe1" width="100%" height="100" frameborder="0" src="AS_cases_details_todo_frame1.php" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-top: #6699cc 1px solid;"></iframe>
                                            </td>
                                        </tr>
                                        <tr height="20" bgcolor="#eeeeee">
                                            <td width="5%" align="center" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid">
                                                <input style="width: 20px;" type="button" value="+" onclick="window.open('AS_cases_details_todo_add.php?case_id=<?= $_GET['case_id'] ?>','new_todo','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=300,height=225,left='+ (screen.availWidth - 300) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 225) / 2);" title="<?= AS_CASD_DODZAD ?>"> 
                                            </td>
                                            <td width="95%" align="right" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                                <font color="#6699cc"><small><?= AS_CASD_BIEZ ?>&nbsp;</small></font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" valign="top">
                                                <iframe name="todoframe2" width="100%" height="288" frameborder="0" src="AS_cases_details_todo_frame2.php?case_id=<?= $_GET['case_id'] ?>" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-top: #6699cc 1px solid;"></iframe>
                                            </td>
                                        </tr>

                                    </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="10%">
                    <table cellpadding="2" cellspacing="0" border="0" style="border-top: #000000 1px solid; border-right: #000000 1px solid">
                        <tr height="54">
                            <td bgcolor="<?= ($row['type_id'] == 1) ? "orange" : "#6699cc" ?>">
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="2" cellspacing="0" border="0" height="89%" bgcolor="#ffffff" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid;">
                        <tr>
                            <td valign="top" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" title="teczka" style="font-size: 32pt">Ě</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_TECZKA2 ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_variables.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_USTAW ?>">'</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_USTAW ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_expenses.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 32pt" title="<?= AS_CASD_WYK ?>">@</font>&nbsp;<br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_WYK ?></font></a></td>
                                    </tr>
                                    <tr height="50">
										<td align="center"><a href="AS_cases_details_history.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_DOK ?>">Ň</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_DOK ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_note.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_NOT ?>">¤</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_NOT ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><font color="#6699cc" face="Webdings" style="font-size: 42pt;" title="<?= AS_CASD_ZADANIE ?>">ë</font><br><font color="#999999" style="font-size: 7pt;"><?=  AS_CASD_ZADANIE ?></font></td>
                                    </tr>
                                </table>
                                <!-- <a href="javascript:void(0)"><font color="#ced9e2" size="+4" face="webdings" onmouseover="this.color='green'" onmouseout="this.color='#ced9e2'" title="finanse">?</font></a><br> -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
<? } ?>

