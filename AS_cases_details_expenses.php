<?php include('include/include.php');

//$_GET['case_id'] = 1;

if (isset($_GET['case_id'])) {
    $query = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention , attention2 FROM coris_assistance_cases WHERE case_id = ".$_GET['case_id'];

	if ($_SESSION['new_user']==1){
			$query .= " AND `date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) ";			
	}

    if ($result = mysql_query($query)){
    	if (mysql_num_rows($result)==0)	die('brak sprawy');
        $row = mysql_fetch_array($result);
    }else
        die(mysql_error());
        
        
      // echo $query; 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?php echo "".$row['paxsurname'].", ".$row['paxname']." [".$row['number']."/". substr($row['year'],2,2) ."/".$row['type_id']."/".$row['client_id']."] - ".AS_CASD_WYK2 ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
        <body>
        <script language="JavaScript">
        <!--
            function RemovePositions() {
                var values = "";
                checkedForm = contrahents.form1;
//                alert(checkedForm.elements.length );
//                if (checkedForm.elements.length == 6 && checkedForm.expense_id.checked) {
//                    values = checkedForm.expense_id.value;
//                } else if (checkedForm.elements.length > 6) {
// alert(checkedForm.expense_id.length)
             //       for (var i = 0; i <= checkedForm.expense_id.length - 1; i++) {
					obj = checkedForm.elements['expense_id[]'];
				//	alert(checkedForm.expense_id[].value);
				    ilosc = contrahents.form1.count_expense.value-1;
				 if (ilosc==1){
				 	if (obj.checked)
                            values += obj.value + ",";
				 }else{   
             		for (var i = 0; i < ilosc ; i++) {
                        if (obj[i].checked)
                            values += obj[i].value + ",";
                    }
				 }
                //}
                if (values == "") {
                    alert("<?= AS_CASD_MSG_BRZAPOZDOUSWYK?>");
                    return;
                } else {
                    if (!confirm("<?= AS_CASD_MSG_CONFUSPOZ ?>"))
                        return;
                   
                    contrahents.document.location = "AS_cases_details_expenses_frame.php?case_id=<?= $_GET['case_id'] ?>&expense_id="+ values;
                }
            }
            
            function MM_openBrWindow(theURL,winName,features) { //v2.0
				  window.open(theURL,winName,features);
			}

        //-->
        </script>

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
    echo "<b>$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]</b><br>";

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
                                                                <table cellpadding="2" cellspacing="2" border="0" width="100%">
                                                                        <tr>
                                                                                <td style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
																					<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td width=50%>
                                                                                		<input type="button" value="+" style="font-weight: bold; width: 35px" title="<?= AS_CASD_MSG_DODWYK ?>" onclick="window.open('AS_cases_details_expenses_position_add.php?case_id=<?= $_GET['case_id'] ?>&type_id=<?= $row['type_id'] ?>','','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=600,height=470,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);">
                                                                                        <input type="button" value="-" style="font-weight: bold; width: 35px" title="<?= AS_CASD_MSG_USUNWYK ?>" onclick="RemovePositions();">
                                                                                       </td><td width=50% align="right">
                                                                                       		<?php if 	($_SESSION['new_user']==0){ 
                                                                                       			echo '<input type="button" value="'. AS_CASD_MSG_BUTFIN .'" style="font-weight: bold; width: 135px" title="'. AS_CASD_MSG_PRZEDOFIN .'" onclick="MM_openBrWindow(\'../finances/FK_cases_details.php?case_id='. $_GET['case_id'] .'\',\'\',\'scrollbars=yes,resizable=yes,top=50,left=170,width=650,height=570\')">';
																							}
																							?> 
                                                                                       		</td></tr>
                                                                                       	</table>
                                                                                </td>
                                                                        </tr>
                                                                        <tr>
                                                                                <td style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                                                                        <iframe name="contrahents" src="AS_cases_details_expenses_frame.php?case_id=<?= $_GET['case_id'] ?>" width="100%" height="420"></iframe>
																						<!-- AS_cases_details_expenses_frame.php?case_id=<?= $_GET['case_id'] ?> //-->
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
                                                        <td bgcolor="<?= ($row['type_id'] == 1) ? "orange" : "#6699cc" ?>">
                                                        </td>
                                                </tr>
                                        </table>
                    <table cellpadding="2" cellspacing="0" border="0" height="89%" bgcolor="#ffffff" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid;">
                        <tr>
                            <td valign="top" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" title="<?= AS_CASD_TECZKA2 ?>" style="font-size: 32pt">Ě</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_TECZKA2 ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_variables.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_USTAW ?>">'</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_USTAW ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><font color="#6699cc" face="Webdings" style="font-size: 32pt" title="<?= AS_CASD_WYK ?>">@</font>&nbsp;<br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_WYK ?></font></td>
                                    </tr>
                                    <tr height="50">
										<td align="center"><a href="AS_cases_details_history.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_DOK ?>">Ň</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_DOK ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_note.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_NOT ?>">¤</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_NOT ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_todo.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_ZAD ?>">ë</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_ZAD ?></font></a></td>
                                    </tr>
                                </table>
                                <!-- <a href="javascript:void(0)"><font color="#ced9e2" size="+4" face="webdings" onmouseover="this.color='green'" onmouseout="this.color='#ced9e2'" title="finanse"></font></a><br> -->
                            </td>
                        </tr>
                                </table>
                                <!-- <a href="javascript:void(0)"><font color="#ced9e2" size="+4" face="webdings" onmouseover="this.color='green'" onmouseout="this.color='#ced9e2'" title="finanse"></font></a><br> -->
                            </td>
                        </tr>
                    </table>
                                </td>
                        </tr>
                </table>
        </body>
</html>
<? } ?>
