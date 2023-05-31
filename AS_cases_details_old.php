<?php include('include/include.php'); 

$case_type = '';	
$case_type2 = getValue('case_type2');

	if (isset($_GET['action'])) {

		mysql_query("BEGIN", $cn);

		
		if ($case_type2=='tech'){
			
			$query = "UPDATE coris_assistance_cases SET client_id = $_POST[contrahent_id], client_ref = '$_POST[client_ref]', user_id = $_POST[user_id], paxname = '$_POST[paxName]', paxsurname = '$_POST[paxSurname]', paxdob = '$_POST[paxDob_y]-$_POST[paxDob_m]-$_POST[paxDob_d]',  event = '$_POST[event]', eventdate = '$_POST[eventDate_y]-$_POST[eventDate_m]-$_POST[eventDate_d]', country_id = '$_POST[country]', city = '$_POST[city]',marka_model ='".getValue('marka_model')."' ,nr_rej='".getValue('nr_rej')."',adress1='".getValue('adress1')."',adress2='".getValue('adress2')."' WHERE case_id = $_POST[case_id]";
			
			
			
			
			$query2 = "UPDATE coris_assistance_cases_details SET notificationdate = '$_POST[notificationDate_y]-$_POST[notificationDate_m]-$_POST[notificationDate_d]', informer = '$_POST[informer]', circumstances = '$_POST[circumstances]', comments = '$_POST[comments]',paxphone='".getValue('paxphone')."',paxmobile ='".getValue('paxmobile')."' WHERE case_id = $_POST[case_id] LIMIT 1";
			
		}else { //med
			$query = "UPDATE coris_assistance_cases SET client_id = $_POST[contrahent_id], client_ref = '$_POST[client_ref]', user_id = $_POST[user_id], paxname = '$_POST[paxName]', paxsurname = '$_POST[paxSurname]', paxdob = '$_POST[paxDob_y]-$_POST[paxDob_m]-$_POST[paxDob_d]', policy = '$_POST[policy]', event = '$_POST[event]', eventdate = '$_POST[eventDate_y]-$_POST[eventDate_m]-$_POST[eventDate_d]', country_id = '$_POST[country]', city = '$_POST[city]' WHERE case_id = $_POST[case_id]";
		
			$query2 = "UPDATE coris_assistance_cases_details SET notificationdate = '$_POST[notificationDate_y]-$_POST[notificationDate_m]-$_POST[notificationDate_d]', informer = '$_POST[informer]', validityfrom = '$_POST[validityFrom_y]-$_POST[validityFrom_m]-$_POST[validityFrom_d]', validityto = '$_POST[validityTo_y]-$_POST[validityTo_m]-$_POST[validityTo_d]', policypurchasedate = '$_POST[policyPurchaseDate_y]-$_POST[policyPurchaseDate_m]-$_POST[policyPurchaseDate_d]', policypurchaselocation = '$_POST[policyPurchaseLocation]', policyamount = '$_POST[policyAmount]', policycurrency_id = '$_POST[policycurrency_id]', circumstances = '$_POST[circumstances]', comments = '$_POST[comments]' WHERE case_id = $_POST[case_id]";
		}
		
		
		
		
		if ($result = mysql_query($query, $cn)) {
			
			if ($result = mysql_query($query2, $cn)) {
				mysql_query("COMMIT", $cn);
				$updateOK = true;
			} else {
				mysql_query("ROLLBACK", $cn);
				die (mysql_error());
			}
		} else {
			mysql_query("ROLLBACK", $cn);
			die (mysql_error());
		}
	}
$query = "SELECT ac.case_id, ac.number, ac.year, ac.client_id, ac.type_id, ac.client_ref, ac.user_id, ac.date, ac.paxname, ac.paxsurname, ac.paxdob, ac.policy, ac.event, ac.eventdate, ac.country_id, ac.city, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.archive, ac.costless, ac.unhandled, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention,ac.attention2, DATE(ac.archive_date) AS archive_date, acd.notificationdate, acd.informer, acd.validityfrom, acd.validityto, acd.policypurchasedate, acd.policypurchaselocation, acd.policyamount, acd.policycurrency_id, acd.circumstances, acd.comments,ac.marka_model,ac.nr_rej,acd.paxphone ,acd.paxmobile,ac.adress1,ac.adress2 FROM coris_assistance_cases ac, coris_assistance_cases_details acd WHERE ac.case_id = acd.case_id AND ac.active = 1";

	if ($_SESSION['new_user']==1){
			$query .= " AND `date` >= '2008-05-01 00:00:00' AND client_id=7592 ";			
	}
/*	$query = "SELECT ac.case_id, ac.number, ac.year, ac.client_id, ac.type_id, ac.client_ref, ac.user_id, ac
	, ac.paxname, ac.paxsurname, ac.paxdob, ac.policy, ac.event, ac.eventdate, ac.country_id, ac.city, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.archive, ac.costless, ac.unhandled, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention, DATE(ac.archive_date) AS archive_date, acd.notificationdate, acd.informer, acd.validityfrom, acd.validityto, acd.policypurchasedate, acd.policypurchaselocation, acd.policyamount, acd.policycurrency_id, acd.circumstances, acd.comments FROM coris_assistance_cases ac, coris_assistance_cases_details acd WHERE ac.case_id = acd.case_id AND ac.active = 1";
*/
	if (isset($_GET['case_id'])) {
		$query .= " AND ac.case_id = $_GET[case_id]";
	} else if (isset($_GET['number'])) {
		$query .= " AND ac.number = $_GET[number] AND ac.year = $_GET[year]";
	}

	if (!$result = mysql_query($query)) {
		die (mysql_error());
	}

	if ($row = mysql_fetch_array($result)) {

		$paxDob = array("", "", "");
		if ($row['paxdob'] != "0000-00-00")
		$paxDob = split("-", $row['paxdob']);

		$eventDate = array("","","");
		if ($row['eventdate'] != "0000-00-00")
		$eventDate = split("-", $row['eventdate']);

		$notificationDate = array("", "", "");
		if ($row['notificationdate'] != "0000-00-00")
		$notificationDate = split("-", $row['notificationdate']);

		$openDate = split("-", $row['date']);

		$validityFrom = array("", "", "");
		if ($row['validityfrom'] != "0000-00-00")
		$validityFrom = split("-", $row['validityfrom']);

		$validityTo = array("", "", "");
		if ($row['validityto'] != "0000-00-00")
		$validityTo = split("-", $row['validityto']);

		$closureDate = array("", "", "");
		if ($row['archive_date'] != "0000-00-00")
		$closureDate = split("-", $row['archive_date']);

		$policyPurchaseDate = array("", "", "");
		if ($row['policypurchasedate'] != "0000-00-00")
		$policyPurchaseDate = split("-", $row['policypurchasedate']);
			
		$type_id  = $row['type_id'];
		$case_type = ($type_id==1) ? 'tech' : 'med';
	} else {

		echo "<html><head><meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-2\"></head><body bgcolor=\"#dfdfdf\"><center><table height=\"100%\" width=\"100%\" valign=\"middle\"><tr><td align=\"center\"><table bgcolor=\"#cccccc\"><tr><td align=\"center\" style=\"font-family: Verdana; font-size: 9pt\"><font color=\"red\">Sprawy o tym numerze nie ma w bazie danych, lub te? jest ona niedostepna.<br>Prosze o kontakt z dzia3em IT - <a href=\"mailto:it@coris.com.pl\">it@coris.com.pl</a>.<br><br>";
												if (isset($_GET['number'])) {
													echo "Prosze o podanie numeru referencji: </font>&nbsp; <tt>S$_GET[number]/". substr($_GET['year'], 2, 2) ."</tt><br><br>";
											} else if ($_GET['case_id']) {
												echo "Prosze o podanie numeru referencji: </font>&nbsp; <tt>C$_GET[case_id]</tt><br><br>";
										}
										echo "<font color=\"navy\">Przepraszamy za niedogodno?ci.</font></td></tr></table></td></tr></table><BR><BR><a href=\"javascript:void(0)\" onclick=\"self.close()\">Zamknij okno</a></center></body></html>";
	exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?php echo "$row[paxsurname], $row[paxname] [$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]] - ".AS_CASD_TECZKA ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body>
	<?php
		// TODO: Musia3em wychrzania, poniewa? po otwarciu dopiero co za3o?onej sprawy powodowa3o zak3adanie kolejnych spraw
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
		body {
			margin-top: 0.1cm;
			margin-bottom: 0.1cm;
			margin-left: 0.1cm;
			margin-right: 0.1cm;
		}
	</style>
	<script language="JavaScript1.2">
		<!--
		function validate(s) {
			if (s.contrahent_id.value == "" || s.contrahent_name.value == "") {
				alert("<?= AS_CASD_MSG_PROSZWYBRKL ?>");
				s.contrahent_id.focus();
				return false;
			}
			if (s.paxName.value == "") {
				alert("<?= AS_CASD_MSG_PROSZWPIMIE ?>");
				s.paxName.focus();
				return false;
			}
			
			if (s.paxSurname.value == "") {
				alert("<?= AS_CASD_MSG_PROSZWPNAZW ?>");
				s.paxSurname.focus();
				return false;
			}
			if ((form1.paxDob_d.value != "" || form1.paxDob_m.value != "" || form1.paxDob_y.value != "") && (form1.paxDob_d.value == "" || form1.paxDob_m.value == "" || form1.paxDob_y.value == "")) {
				alert("<?= AS_CASADD_MSG_WYWYCZ ?>");
				form1.paxDob_d.focus();
				return false;
			}
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
		<form name="form1" action="AS_cases_details.php?action=1&case_id=<?php echo $row['case_id'] ?>" method="post" onsubmit="return validate(this);">
		<input type="hidden" name="case_type2" value="<?php echo $case_type?>">
			<tr>
				<td width="90%">
					<table cellpadding="2" cellspacing="0" border="0" width="100%">
						<tr height="30">
							<td width="60%">
								<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
									<tr>
										<td width="35">
											<input type="hidden" name="case_id" value="<?php echo $row['case_id'] ?>">
											<input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 50px; background: yellow" title="<?= AS_CASD_MSG_SAVEZM ?>">&nbsp;
										</td>
										<?php
											if (isset($updateOK)) {
												if ($updateOK) {
													echo "<td bgcolor=\"#6699cc\" align=\"center\"><font color=\"#dfdfdf\">".AS_CASD_MSG_DANZOSTZM."</font></td>";
												} else {
													echo "<td bgcolor=\"red\" align=\"center\"><font color=\"#dfdfdf\">".AS_CASD_MSG_BLZAP ."</font></td>";
												}
											} else {
												echo "<td></td>";
											}
										?>
									</tr>
								</table>
							</td>
							<td bgcolor="#dfdfdf" align="right" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid;" rowspan="2" valign="top">
								<?PHP if (!(strcmp(1, $row['attention']))) {echo "<font style=\"background: red; color: yellow\">UWAGA</font>";} 
								if ( $row['attention2'] ==1 ) {echo "<font style=\"background: #6699cc; color: yellow\">".ATTENTION2."</font>";} 
								?>
								<?php
									echo "<b><input type=\"text\" style=\"text-align:right\" name=\"case_number\" value=\"".$row['number']."\" size=\"6\" tabindex=\"-1\" onChange=\"zmien_numer_sprawy(".$row['number'].");\">/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]</b><br>";

									echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr height=\"3\"><td></td></tr></table>";

include('include/AS_cases_details_type_inc.php');

								?>
							</td>
						</tr>
						<tr height="25">
							<td align="center" bgcolor="#eeeeee" style="border-top: #000000 1px solid; border-left: #000000 1px solid;">
								<font color="navy" size=3><b><?php echo $row['paxsurname'] ?><br><font size=2> <?php echo $row['paxname'] ?></b></font>
							</td>
						</tr>
					</table>
					<table cellpadding="2" cellspacing="0" border="0" width="100%" height="89%" bgcolor="#cccccc" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid; border-bottom: #000000 1px solid;">
						<tr>
							<td valign="top">
								<table>
									<tr>
										<td align="center" bgcolor="yellow" width="89" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid">
											<input type="button" value="<?= AS_CASD_SPR ?>" disabled style="background: yellow; font-size: 7pt; line-height: 10px; height: 17px; width: 80px;">
										</td>
										<td align="center" width="89" bgcolor="yellow" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
											<input type="button" value="<?= AS_CASD_POSZK2 ?>" style="font-size: 7pt; line-height: 10px; height: 17px; width: 80px;" onclick="window.location='AS_cases_details_contacts.php?case_id=<?php echo $row['case_id'] ?>'">
										</td>
										<td align="center" width="89" bgcolor="yellow" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
											<input type="button" value="<?= AS_CASD_UBEZP2 ?>" style="font-size: 7pt; line-height: 10px; height: 17px; width: 80px;" onclick="window.location='AS_cases_details_insurance.php?case_id=<?php echo $row['case_id'] ?>'">
										</td>
										<td width="105" bgcolor="#e0e0e0" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;" align="center">
											<!--                                    <input type="button" value="drukuj zg3oszenie" style="width: 120px;" onclick="window.location='AS_cases_details_contacts.php?case_id=<?php echo $row['case_id'] ?>'">
											-->
											<?php
												if ($row['client_id'] == 606 or
												$row['client_id'] == 607 or
												$row['client_id'] == 608 or
												$row['client_id'] == 609 or
												$row['client_id'] == 610 or
												$row['client_id'] == 611 or
												$row['client_id'] == 612 or
												$row['client_id'] == 613 or
												$row['client_id'] == 614 or
												$row['client_id'] == 615 or
												$row['client_id'] == 616 or
												$row['client_id'] == 617 or
												$row['client_id'] == 618 or
												$row['client_id'] == 619 or
												$row['client_id'] == 620 or
												$row['client_id'] == 621 or
												$row['client_id'] == 622 or
												$row['client_id'] == 623 or
												$row['client_id'] == 624 or
												$row['client_id'] == 625 or
												$row['client_id'] == 626 or
												$row['client_id'] == 627 or
												$row['client_id'] == 628 or
												$row['client_id'] == 630 or
												$row['client_id'] == 652) {
													echo "<input type=\"button\" value=\"".AS_CASD_WYSLZGL."\" style=\"font-size: 7pt; line-height: 10px; height: 17px; width: 95px;\" onclick=\"window.open('AS_cases_add_med_form_notes_Warta.php?case_id=$row[case_id]','','toolbar=no,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width=750,height=550,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.2) - 400) / 2)\">";
												} else {
													echo "<input type=\"button\" value=\"".AS_CASD_WYSLZGL."\" style=\"font-size: 7pt; line-height: 10px; height: 17px; width: 95px;\" onclick=\"window.open('AS_cases_add_med_form_notes.php?case_id=$row[case_id]','','toolbar=no,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width=750,height=550,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.2) - 400) / 2)\">";
												}
											?>
										</td>
									<!--	<td width="105" bgcolor="#e0e0e0" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;" align="center">
											<input type="button" value="<?= AS_CASD_DRUKZGL ?>" style="font-size: 7pt; line-height: 10px; height: 17px; width: 95px;" onclick="window.open('AS_forms_coversheet_print.php?case_id=<?php echo $row['case_id'] ?>','','toolbar=yes,scrollbars=yes,location=no,status=yes,menubar=yes,resizable=no,width=750,height=400,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.2) - 400) / 2);">
										</td>-->
									</tr>
								</table>
								<!--<td width="120" bgcolor="#e0e0e0" onmouseover="this.bgColor='#dddddd'" onmouseout="this.bgColor='#e0e0e0'" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; cursor: default" onclick="window.location='AS_cases_details_contacts.php?case_id=<?php echo $row['case_id'] ?>'"><small>ubezpieczenie</small></td>-->
								<!---->

								<table cellpadding="2" cellspacing="0" border="0" width="100%">
									<tr valign="top">
										<td width="60%">
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td colspan="2" align="right">
														<small><font color="#6699cc"><?= AS_CASADD_TOW ?></font></small>&nbsp;
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><?= AS_CASADD_KLIENT ?></small>
													</td>
													<td>
														<input type="text" name="contrahent_id" value="<?php echo $row['client_id'] ?>" size="5" style="text-align: center; text-align: center;" onblur="client_search_frame.location='GEN_contrahents_select_iframe.php?contrahent_id=' + this.value;">
														<input type="text" name="contrahent_name" size="22" disabled> <input type="button" style="width: 20px" tabindex="-1" title="Wyszukaj klienta" onclick="MM_openBrWindow('GEN_contrahents_select_frameset.php','','width=550,height=420')" value="&gt;">
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><?= CASENO ?></small>
													</td>
													<td>
														<input type="text" name="client_ref" value="<?php echo $row['client_ref'] ?>" size="26">
													</td>
												</tr>
											</table>
											<table><tr><td></td></tr></table>
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="lightyellow" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td colspan="2" align="right">
														<small><font color="#6699cc"><?= AS_CASD_UBEZP ?></font></small>&nbsp;
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><b><?= SURNAME ?></b></small>
													</td>
													<td>
														<input type="text" name="paxSurname" style="font: bold;" value="<?php echo $row['paxsurname'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><b><?= NAME ?></b></small>
													</td>
													<td>
														<input type="text" name="paxName" style="font: bold;" value="<?php echo $row['paxname'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="25">
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><?= AS_CASD_UR ?></small>
													</td>
													<td>
														<input type="text" name="paxDob_d" value="<?php echo $paxDob[2] ?>" size="1" onkeyup="move(this);">
														<input type="text" name="paxDob_m" value="<?php echo $paxDob[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="paxDob_y" value="<?php echo $paxDob[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('paxDob')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
													</td>
												</tr>
											</table>
										</td>
										<!-- Druga kolumna -->
										<td width="40%">
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td colspan="2"></td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><?= AS_CASES_ZDARZ ?></small>
													</td>
													<td>
														<input type="text" name="eventDate_d" value="<?php echo $eventDate[2] ?>" size="1" onkeyup="move(this);">
														<input type="text" name="eventDate_m" value="<?php echo $eventDate[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="eventDate_y" value="<?php echo $eventDate[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('eventDate')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><b><?= AS_CASD_ZGL ?></b></small>
													</td>
													<td>
														<input type="text" name="notificationDate_d" value="<?php echo $notificationDate[2] ?>" size="1" onkeyup="move(this);">
														<input type="text" name="notificationDate_m" value="<?php echo $notificationDate[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="notificationDate_y" value="<?php echo $notificationDate[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('notificationDate')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
													</td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><?= AS_CASES_OTW ?></small>
													</td>
													<td>
														<input type="text" name="openDate_d" value="<?php echo $openDate[2] ?>" size="1" disabled onkeyup="move(this);">
														<input type="text" name="openDate_m" value="<?php echo $openDate[1] ?>" size="1" disabled onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="openDate_y" value="<?php echo $openDate[0] ?>" size="4" disabled onkeydown="remove(this);">
													</td>
												</tr>
												<tr>
													<td colspan="2" height="3"></td>
												</tr>
												<tr>
													<td width="70" align="right">
														<small><font color="darkred"><?= AS_CASD_ZAMK ?></font></small>
													</td>
													<td>
														<input type="text" name="closureDate_d" value="<?php echo $closureDate[2] ?>" size="1" disabled onkeyup="move(this);">
														<input type="text" name="closureDate_m" value="<?php echo $closureDate[1] ?>" size="1" disabled onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="closureDate_y" value="<?php echo $closureDate[0] ?>" size="4" disabled onkeydown="remove(this);">
													</td>
												</tr>
											</table>
											<table><tr><td></td></tr></table>
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td colspan="2"></td>
												</tr>
												<tr>
													<td width="30" align="right"><small><?= AS_CASD_MIEJSC ?></small></td>
													<td><input type="text" name="city" value="<?php echo $row['city'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="16" maxlength="30"></td>
												</tr>
												<tr>
													<td width="30" align="right"><b><small><?= COUNTRY ?></small></b></td>
													<td>
													
<input type="text" name="country" id="country" value="<?php echo $row['country_id'] ?>" onchange="javascript:this.value=this.value.toUpperCase();aktualizuj_kraj(this.value)" size="1">                            
<select tabindex=-1 name="countryList" id="countryList" onchange="document.forms['form1'].elements['country'].value = document.forms['form1'].elements['countryList'].value" style="font-size: 8pt;">
	<option value=""></option>
<?php
$result_c = mysql_query("SELECT country_id, name, prefix FROM coris_countries ORDER BY name", $cn);
while ($row_c = mysql_fetch_array($result_c)) {
?>
                                    <option value="<?php echo $row_c['country_id']; ?>"><?php  echo substr($row_c['name'],0,13);  ?></option>
<?php

}
?>
                                </select>	

                                
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<table cellpadding="2" cellspacing="0" border="0" width="100%">

<?php
if ($case_type=='tech'){
	?>
	<tr>
										<td>
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td align="right" colspan="2">
														<small><font color="#6699cc"><?= AS_CASD_INF ?></font></small>&nbsp;
													</td>
												</tr>
												<tr>
													<td width="18%" align="right">
														<small><?=	AS_CASD_MARKMODEL ?></small>
													</td>
												  <td width="82%">
														<input type="text" name="marka_model" value="<?php echo $row['marka_model'] ?>"  size="32" maxlength="100">
														&nbsp;&nbsp;&nbsp;<small><?= REGISTRATION ?></small>
														<input type="text" name="nr_rej" value="<?php echo $row['nr_rej'] ?>"  size="18" maxlength="100">
													</td>
												</tr>
												<tr>
													<td width="18%" align="right">
														<small><?= AS_CASADD_TEL1 ?></small>
													</td>
													<td width="82%"><input type="text" name="paxphone" value="<?php echo $row['paxphone'] ?>" size="15" maxlength="100">													  <small>&nbsp;&nbsp;&nbsp;
												    <?= AS_CASADD_TEL2 ?></small>
                                                      <input type="text" name="paxmobile" value="<?php echo $row['paxmobile'] ?>"  size="15" maxlength="100">
</td>
												</tr>
												<tr>
													<td width="18%" align="right" valign="top">
														<small><?= AS_CASADD_ADRPOST ?></small>
													</td>
													<td>
														<input name="adress1" type="text" style="font-family: Verdana; font-size: 8pt" value="<?php echo $row['adress1'] ?>" size="65">
													</td>
												</tr>
<tr>
													<td width="18%" align="right" valign="top">
														<small><?= AS_CASADD_ADRDOC ?></small>
													</td>
													<td>
														<input name="adress2" type="text" style="font-family: Verdana; font-size: 8pt" value="<?php echo $row['adress2'] ?>" size="65">
													</td>
												</tr>												
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>
										</td>
									</tr>
	<?php	
		
}	
?>								<tr>
										<td>
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td align="right" colspan="2">
														<small><font color="#6699cc"><?= AS_CASADD_SZKOD ?></font></small>&nbsp;
													</td>
												</tr>
												<tr>
													<td width="14%" align="right">
														<small><b><u><font color=red><?php
														echo  ($case_type=='tech') ? AS_CASD_PRZYCZ :  AS_CASD_DIAGN ;
														?></font></u></b></small>
													</td>
													<td width="86%">
														<input type="text" name="event" style="font: bold; color: red;" value="<?php echo $row['event'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="70" maxlength="100">
													</td>
												</tr>
												<tr>
													<td width="14%" align="right">
														<small><?= INFORMER ?></small>
													</td>
													<td width="86%">
														<input type="text" name="informer" value="<?php echo $row['informer'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="70" maxlength="100">
													</td>
												</tr>
												<tr>
													<td width="14%" align="right" valign="top">
														<small><?= AS_CASADD_OKOLO ?></small>
													</td>
													<td>
														<textarea cols="69" rows="2" name="circumstances" style="font-family: Verdana; font-size: 8pt"><?php echo $row['circumstances'] ?></textarea>
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>
										</td>
									</tr>
<?php

?>									
									<tr valign="top">
										<td>

<?php 
if ($case_type=='tech'){
	?>
	<?php	
		
}else {
		
	?><table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
	
												<tr>
													<td align="right" colspan="4">
														<small><font color="#6699cc"><?= AS_CASADD_UBEZP ?></font></small>&nbsp;
													</td>
												</tr>
												<tr>
													<td colspan="2"></td>
												</tr>
												<tr style="background: #d0d0d0">
													<td width="50" align="right">
														<small><b><?= AS_CASADD_WAZNOD ?></b></small>
													</td>
													<td>
														<input type="text" name="validityFrom_d" value="<?php echo $validityFrom[2] ?>" size="1" onkeyup="move(this);">
														<input type="text" name="validityFrom_m" value="<?php echo $validityFrom[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="validityFrom_y" value="<?php echo $validityFrom[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('validityFrom')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
													</td>
													<td width="50" align="right">
														<small><b><?= AS_CASADD_WAZNDO ?></b></small>
													</td>
													<td>
														<input type="text" name="validityTo_d" value="<?php echo $validityTo[2] ?>" size="1" onkeyup="move(this);">
														<input type="text" name="validityTo_m" value="<?php echo $validityTo[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="validityTo_y" value="<?php echo $validityTo[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('validityTo')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
													</td>
												</tr>
												<tr>
													<td width="50" align="right">
														<small><?= AS_CASADD_POL ?></small>
													</td>
													<td width="175">
														<input type="text" name="policy" value="<?php echo $row['policy'] ?>" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30">
													</td>
													<td width="50" align="right">
														<small><?= SUM ?></small>
													</td>
													<td width="175">
														<input type="text" name="policyAmount" size="10" value="<?php echo ($row['policyamount'] != 0) ? str_replace(".", ",", $row['policyamount']) : "" ?>" style="text-align: right;">&nbsp;
														<?php
															$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 AND insurance = 1 ORDER BY currency_id";
															if ($result = mysql_query($query, $cn)) {
																echo "<select name=\"policycurrency_id\"><option></option>";
																	while ($row2 = mysql_fetch_array($result)) {
																		echo ($row['policycurrency_id'] == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
																	}
																} else {
																	die (mysql_error());
																}
															?>
														</select>
													</td>
												<!--	<td width="50" align="right">
														<small><?= DATEFROM ?></small>
													</td>
													<td width="150">
														<input type="text" name="policyPurchaseDate_d" value="<?php echo $policyPurchaseDate[2] ?>" size="1" onkeyup="move(this);">
														<input type="text" name="policyPurchaseDate_m" value="<?php echo $policyPurchaseDate[1] ?>" size="1" onkeyup="move(this);" onkeydown="remove(this);">
														<input type="text" name="policyPurchaseDate_y" value="<?php echo $policyPurchaseDate[0] ?>" size="4" onkeydown="remove(this);">
														<a href="javascript:void(0)" onclick="newWindowCal('policyPurchaseDate')" tabindex="-1" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
													</td>
													-->
												</tr>
											<!--	<tr valign="top">
													<td width="50" align="right">
														<small><?= SUM ?></small>
													</td>
													<td width="175">
														<input type="text" name="policyAmount" size="10" value="<?php echo ($row['policyamount'] != 0) ? str_replace(".", ",", $row['policyamount']) : "" ?>" style="text-align: right;">&nbsp;
														<?php
															$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 AND insurance = 1 ORDER BY currency_id";
															if ($result = mysql_query($query, $cn)) {
																echo "<select name=\"policycurrency_id\"><option></option>";
																	while ($row2 = mysql_fetch_array($result)) {
																		echo ($row['policycurrency_id'] == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
																	}
																} else {
																	die (mysql_error());
																}
															?>
														</select>
													</td>
													<td width="50" align="right">
														<small><?= AS_CASD_MIEJSCWYK ?></small>
													</td>
													<td width="150">
														<input name="policyPurchaseLocation" style="font-family: Verdana;" maxlength="50" value="<?php echo $row['policypurchaselocation'] ?>">
													</td>
												</tr> -->
												<tr>
													<td colspan="2"></td>
												</tr>
											</table>
<?php
}
?>
										</td>
									</tr>
								</table>
								<table cellpadding="2" cellspacing="0" border="0" width="100%">
									<tr>
										<td width="40%">
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td width="50" align="right"><small><?= AS_CASES_STATUS ?></small></td>
													<td>
														<table cellpadding="1" cellspacing="1" border="0" width="70">
															<tr height="15" align="center">
																<td bgcolor="<?php echo ($row['status_client_notified']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_ZGLOSZSZK ?>" style="border-left: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="<?php echo ($row['status_policy_confirmed']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_POTWWAZNPOL ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="<?php echo ($row['status_documentation']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DOK ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="<?php echo ($row['status_decision']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DEC ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="<?php echo ($row['status_assist_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZASSZAK ?>" style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="<?php echo ($row['status_account_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZRACHZAK ?>" style="border: #999999 1px solid; cursor: default;">&nbsp;</td>
																<td bgcolor="<?php echo ($row['status_settled']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_SPRROZL ?>" style="border: #999999 1px solid; cursor: default;">&nbsp;</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
										<td width="60%">
											<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td width="50" align="right"><small><?= AS_CASD_RED ?></small></td>
													<td>
														<?php
															$query = "SELECT user_id, surname, name FROM coris_users ORDER BY surname";
															$result = mysql_query($query, $cn);
															if ($result) {
																echo "<select name=\"username\" style=\"font-size: 8pt\" disabled>";
																	echo "<option></option>";
																	while ($row2 = mysql_fetch_array($result))
																	echo ($row['user_id'] == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[1], $row2[2]</option>" : "<option value=\"$row2[0]\">$row2[1], $row2[2]</option>";
																	echo "</select>";
																mysql_free_result($result);
															} else {
																die (mysql_error());
															}
														?>
														<input type="hidden" name="user_id" value="<?php echo $row['user_id'] ?>">
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<table cellpadding="0" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
												<tr>
													<td align="right">
														<small><font color="#6699cc"><?= COMMENTS ?></font></small>&nbsp;
													</td>
												</tr>
												<tr>
													<td>
														<center>
															<textarea name="comments" cols="80" rows="5" style="font-family: Verdana; font-size: 8pt;"><?php echo $row['comments'] ?></textarea>
														</center>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!--
								<center>
									<iframe src="AS_cases_details_console.php?case_id=<?php echo $row['case_id'] ?>" name="console" width="520" height="90"></iframe>
								</center>
								-->
							</td>
						</tr>
					</table>
				</td>
				<td width="10%" valign="top">
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
										<td align="center"><a href="AS_cases_details_variables.php?case_id=<?php echo $row['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_USTAW ?>">'</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_USTAW ?></font></a></td>
									</tr>
									<tr height="50">
										<td align="center"><a href="AS_cases_details_expenses.php?case_id=<?php echo $row['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 32pt" title="<?= AS_CASD_WYK ?>">@</font>&nbsp;<br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_WYK ?></font></a></td>
									</tr>
									<tr height="50">
										<td align="center"><a href="AS_cases_details_history.php?case_id=<?php echo $row['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_DOK ?>">Ò</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_DOK ?></font></a></td>
									</tr>
									<tr height="50">
										<td align="center"><a href="AS_cases_details_note.php?case_id=<?php echo $row['case_id'] ?>"><font color="#ced9e2" face="webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_NOT ?>">¤</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_NOT ?></font></a></td>
									</tr>
									<tr height="50">
										<td align="center">										  <a href="AS_cases_details_todo.php?case_id=<?php echo $row['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_ZAD ?>">ë</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_ZAD ?></font></a></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</form>
	</table>
	<iframe name="client_search_frame" width="0" height="0" src="GEN_contrahents_select_iframe.php?contrahent_id=<?php echo $row['client_id'] ?>"></iframe>
	<iframe name="change_case_number_frame" width="0" height="0" src=""></iframe>
	
	<script>
	function zmien_numer_sprawy(old_number){
		
		if (confirm('<?= AS_CASD_NOFMSG ?>')){
			sprawa_id = <?php echo $row['case_id'];?>;
			new_number = document.form1.case_number.value
			year = <?php echo $row['year'];?>;
			document.change_case_number_frame.location='GEN_change_case_number_iframe.php?case_id=' + sprawa_id + '&new_number=' + new_number +'&year=' + year + '&old_number=' + old_number;
		}else{
			document.form1.case_number.value=old_number;
		}
		
	}	

	aktualizuj_kraj('<?php echo $row['country_id'] ?>');

	function aktualizuj_kraj(kod_kraju){
	
	kod_kraju=kod_kraju.toUpperCase();                            		
	//ilosc= document.forms['form1'].elements['countryList'].length;
	ilosc= document.getElementById('countryList').length;
	zm=0;
	kr_status=0;
	for (i=0;i<ilosc;i++){
				if (document.getElementById('countryList').options[i].value == kod_kraju ){						
						document.getElementById('countryList').selectedIndex = i;
						document.getElementById('country').value = document.getElementById('country').value.toUpperCase();
						kr_status=1;
				}
	}
	if (kr_status==0){
			document.getElementById('country').value = "";
			document.getElementById('countryList').selectedIndex = 0 ;
			alert("<?= AS_CASD_BRKROSKR ?> " + kod_kraju );
	}
}
	
</script>
	
</body>
</html>
