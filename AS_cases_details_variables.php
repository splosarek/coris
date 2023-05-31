<?php include('include/include.php'); 

	


	if (isset($_GET['case_id'])) {

		if (isset($_GET['action'])) {

			$status_client_notified = (isset($_POST['status_client_notified'])) ? 1 : 0;
			$status_policy_confirmed = (isset($_POST['status_policy_confirmed'])) ? 1 : 0;
			$status_documentation = (isset($_POST['status_documentation'])) ? 1 : 0;
			$status_decision = (isset($_POST['status_decision'])) ? 1 : 0;
			$status_assist_complete = (isset($_POST['status_assist_complete'])) ? 1 : 0;

			$attention = (isset($_POST['attention'])) ? 1 : 0;
			$attention2 = (isset($_POST['attention2'])) ? 1 : 0;

			$ambulatory = (isset($_POST['ambulatory'])) ? 1 : 0;
			$hospitalization = (isset($_POST['hospitalization'])) ? 1 : 0;
			$transport = (isset($_POST['transport'])) ? 1 : 0;
			$decease = (isset($_POST['decease'])) ? 1 : 0;
			$costless = (isset($_POST['costless'])) ? 1 : 0;
			$only_info = (isset($_POST['only_info'])) ? 1 : 0;
			$unhandled = (isset($_POST['unhandled'])) ? 1 : 0;
			$archive = (isset($_POST['archive'])) ? 1 : 0;
			
			$holowanie = (isset($_POST['holowanie'])) ? 1 : 0;
			$wynajem_samochodu =(isset($_POST['wynajem_samochodu'])) ? 1 : 0 ;

			if (!$_POST['reclamation_trigger']) {
				$reclamation = (isset($_POST['reclamation'])) ? 1 : 0;
			} else {
				$reclamation = 1;
			}

			$query = "UPDATE coris_assistance_cases SET type_id = '$_POST[type_id]', genre_id = '$_POST[genre_id]', ambulatory = '$ambulatory', hospitalization = '$hospitalization', transport = '$transport', decease = '$decease', costless = '$costless', only_info = '$only_info', unhandled = '$unhandled', archive = '$archive', reclamation = '$reclamation', status_client_notified = '$status_client_notified', status_policy_confirmed = '$status_policy_confirmed', status_documentation = '$status_documentation', status_decision = '$status_decision', status_assist_complete = '$status_assist_complete', attention = '$attention',attention2 = '$attention2',holowanie='$holowanie',wynajem_samochodu='$wynajem_samochodu' WHERE case_id = '$_GET[case_id]' LIMIT 1";

			if ($result = mysql_query($query)) {

				if ($reclamation) {
					$query = "INSERT INTO coris_assistance_cases_reclamations (case_id, reclamation_text, user_id, date) VALUES ($_GET[case_id], '$_POST[reclamation_text]', '$_SESSION[user_id]', NOW())";

					if ($result = mysql_query($query)) {
						$updateOK = true;
					} else {
						die(mysql_error());
					}

				} else {
					$updateOK = true;
				}

			} else {
				die(mysql_error());
			}
		}
		
		if (isset($_GET['archive'])) {
			if ($_GET['archive'] == 1) {
				$query = "UPDATE coris_assistance_cases SET archive = 1, archive_date = NOW() WHERE case_id = $_GET[case_id]";
			} else {
				$query = "UPDATE coris_assistance_cases SET archive = 0, archive_date = NULL WHERE case_id = $_GET[case_id]";
			}
			if ($result = mysql_query($query)) {
				$updateOK = true;
			} else {
				die(mysql_error());
			}			
		}

		$query = "SELECT ac.number, ac.year, ac.client_id, ac.type_id, ac.genre_id, ac.paxname, ac.paxsurname, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.costless,ac.only_info, ac.costless, ac.unhandled, ac.archive, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention, ac.attention2, acr.reclamation_text, ac.attention, ac.attention2,ac.holowanie,ac.wynajem_samochodu FROM coris_assistance_cases ac LEFT JOIN coris_assistance_cases_reclamations acr ON ac.case_id = acr.case_id WHERE ac.case_id = '$_GET[case_id]'";
		
			if ($_SESSION['new_user']==1){
			$query .= " AND ac.`date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) ";			
			}

		if ($result = mysql_query($query)) {

			if (!$row = mysql_fetch_array($result)) {
				echo "<center>Brak sprawy</center>";
				exit;
			}

		} else {
			die(mysql_error());
		}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?php echo "$row[paxsurname], $row[paxname] [$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]] - ".USTAWIENIA ?></title>
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
		<script language="JavaScript1.2">
			<!--
			function checkboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

			function zaznacz_uwaga2(s) {
				
				at= document.getElementById('attention');
				at2=  document.getElementById('attention2');
				
				if (s=='attention') {
					at.checked = true;
					at2.checked = false;										
				} else {
					at2.checked = true;
					at.checked = false;
				}
			}

			function zaznacz_uwaga(s) {
				at= document.getElementById('attention');
				at2=  document.getElementById('attention2');
				
				if (s=='attention') {					
					if (!at.checked)
							at.checked = false;
					else		
						at.checked = true;
					at2.checked = false;										
				} else {
					if (!at2.checked)
							at2.checked = false;
					else		
						at2.checked = true;
					
					at.checked = false;
				}
			}
			
			
			function UcheckboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

			
			function validate(s) {
				if (s.reclamation.checked && s.reclamation_text.value == "") {
					alert("<?= AS_CASDTXTABYWYSLREKL ?>");
					s.reclamation_text.focus();
					return false;
				}
				if (s.status_assist_complete.checked && !(s.status_client_notified.checked && s.status_policy_confirmed.checked && s.status_documentation.checked && s.status_decision)) {
					if (!confirm("<?= AS_CASD_TXTNIEWSZETAPY ?>")) {
						s.status_assist_complete.checked = false;
					}
				}
			}

			//function Guarantee(s) {
			//	window.open('AS_cases_details_variables_popup_guarantee.php?case_id='+ s,'Guarantee','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=180,left='+ (screen.availWidth - 500) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 180) / 2);
			//}
			//-->
		</script>

		<style>
			a, a:visited, a:active, a:hover {
				color: #000000;
				text-decoration: none;
			}
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
						<form action="AS_cases_details_variables.php?action=1&case_id=<?php echo $_GET['case_id'] ?>" method="post" name="form1" onsubmit="return validate(this);">
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
													echo "<td></td>";
												}
											?>
										</tr>
									</table>
								</td>
								<td bgcolor="#dfdfdf" align="right" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid;" rowspan="2" valign="top">
								<?PHP 
									if ( $row['attention'] ==1 ) {echo "<font style=\"background: red; color: yellow\">".ATTENTION2."</font>";} 
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
									<table cellpadding="2" cellspacing="0" border="0" width="100%">
										<tr valign="top">
											<td width="100%" colspan="2">
												<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td colspan="4" align="right">
															<small><font color="#6699cc"><?= AS_CASD_TYPRODZSPR ?></font></small>&nbsp;
														</td>
													</tr>
													<tr>
														<td width="10%">&nbsp;</td>
														<td width="40%">
															<select name="type_id" style="font-size: 8pt;" onChange="redirect(this.options.selectedIndex)">
																<?php
																	$query = "SELECT type_id, value FROM coris_assistance_cases_types ORDER BY type_id";
																	$result = mysql_query($query);
																	while ($row2 = mysql_fetch_array($result)) {
																	?>
																	<option value="<?php echo $row2['type_id'] ?>" <?php echo ($row2['type_id'] == $row['type_id']) ? "selected" : "" ?>><?php echo $row2['value'] ?></option>
																	<?php
																	}
																?>
															</select>
														</td>
														<td width="10%">&nbsp;</td>
														<td width="40%">
															<select name="genre_id" style="font-size: 8pt;">
																<option value=""></option>
																<?php
																	$query = "SELECT genre_id, value FROM coris_assistance_cases_genres WHERE type_id = $row[type_id] ORDER BY genre_id";
																	$result = mysql_query($query);
																	while ($row2 = mysql_fetch_array($result)) {
																	?>
																	<option value="<?php echo $row2['genre_id'] ?>" <?php echo ($row2['genre_id'] == $row['genre_id']) ? "selected" : "" ?>><?php echo $row2['value'] ?></option>
																	<?php
																	}
																?>
															</select>
														</td>
													</tr>
													<tr>
														<td colspan="4">
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr valign="top">
											<td width="100%" colspan="2">
												<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td colspan="3" align="right">
															<small><font color="#6699cc"><?= AS_CASADD_INNE2 ?></font></small>&nbsp;
														</td>
													</tr>
													<tr >
														<td width="33%">
															<input type="checkbox" name="unhandled" style="background: #dfdfdf" <?php echo ($row['unhandled']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.unhandled);"><?= AS_CASES_BEZRYCZHON2 ?></font>
														</td>
														<td width="33%">
															<input type="checkbox" name="costless" style="background: #dfdfdf" <?php echo ($row['costless']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.costless);"><?= AS_CASES_BEZKOSZT ?></font>
														</td>
														<td width="33%">
														<?php if (!$row['archive']) { ?>
														  <input name="Button" type="button" style="width: 140px" onClick="document.location='AS_cases_details_variables.php?case_id=<?php echo $_GET['case_id'] ?>&archive=1'" value="<?= AS_CASD_PRZDOARCH ?>">
														<?php } else { ?>
														  <input name="Button" type="button" style="width: 140px" onClick="document.location='AS_cases_details_variables.php?case_id=<?php echo $_GET['case_id'] ?>&archive=0'" value="<?= AS_CASD_OTWPON ?>">														
														<?php } ?>
														</td>
													</tr>
														<tr>
														<td colspan="3"><input type="checkbox" name="only_info" style="background: #dfdfdf" <?php echo ($row['only_info']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.only_info);"><?= AS_CASD_TYLKINF ?></font>
														</td>
													</tr>
												<tr >
														<td width="33%">
															<input type="checkbox" name="holowanie" style="background: #dfdfdf" <?php echo ($row['holowanie']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.holowanie);"><?= TOWING ?></font>
														</td>
														<td width="33%">
															<input type="checkbox" name="wynajem_samochodu" style="background: #dfdfdf" <?php echo ($row['wynajem_samochodu']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.wynajem_samochodu);"><?= AS_CASES_WYNSAM ?></font>
														</td>
														<td width="33%">
														&nbsp;
														</td>
													</tr>
													<tr>
														<td colspan="3">
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<!-- Podzia3 na typy -->
										<!-- Medyczna -->
										<tr valign="top">
											<td width="50%">
												<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td align="right">
															<small><font color="#6699cc"><?= AS_CASD_LECZ ?></font></small>&nbsp;
														</td>
													</tr>
													<tr height="20">
														<td>
															&nbsp;<input type="checkbox" name="ambulatory" style="background: #dfdfdf" <?php echo ($row['ambulatory']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.ambulatory);"><?= AS_CASD_AMB ?></font>
														</td>
													</tr>
													<tr height="20">
														<td>
															&nbsp;<input type="checkbox" name="hospitalization" style="background: #dfdfdf" <?php echo ($row['hospitalization']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.hospitalization);"><?= AS_CASES_HOSP ?></font>
														</td>
													</tr>
												</table>
											</td>
											<td width="50%">
												<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td align="right">
															<small><font color="#6699cc"><?= AS_CASD_RODZSZK ?></font></small>&nbsp;
														</td>
													</tr>
													<tr height="20">
														<td>
															&nbsp;<input type="checkbox" name="decease" style="background: #dfdfdf" <?php echo ($row['decease']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.decease);"><?= AS_CASES_ZGON ?></font>
														</td>
													</tr>
													<tr height="20">
														<td>
															&nbsp;<input type="checkbox" name="transport" style="background: #dfdfdf" <?php echo ($row['transport']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.transport);"><?= AS_CASES_TRANSP ?></font>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr valign="top">
											<td width="100%" colspan="2">
												<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td colspan="5" align="right">
															<small><font color="#6699cc"><?= AS_CASES_STATUS ?></font></small>&nbsp;
														</td>
													</tr>
													<tr>
														<td colspan="5" align="right">
															<table cellpadding="1" cellspacing="1" border="0" width="70">
																<tr height="15" align="center">
																	<td bgcolor="<?= ($row['status_client_notified']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_ZGLOSZSZK ?>" style="border-left: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																	<td bgcolor="<?= ($row['status_policy_confirmed']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_POTWWAZNPOL ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																	<td bgcolor="<?= ($row['status_documentation']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DOK ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																	<td bgcolor="<?= ($row['status_decision']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DEC ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																	<td bgcolor="<?= ($row['status_assist_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZASSZAK ?>" style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid; cursor: default;">&nbsp;</td>
																	<td bgcolor="<?= ($row['status_account_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZRACHZAK ?>" style="border: #999999 1px solid; cursor: default;">&nbsp;</td>
																	<td bgcolor="<?= ($row['status_settled']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_SPRROZL ?>" style="border: #999999 1px solid; cursor: default;">&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr align="center">
														<td width="20%" title="<?= AS_CASD_ZAZN ?>">
															<input type="checkbox" name="status_client_notified" style="background: #dfdfdf" <?php echo ($row['status_client_notified']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.status_client_notified);"><?= AS_CASD_ZGL ?></font>
														</td>
														<td width="20%" title="<?= AS_CASD_ZAZNZOSPOTWWAZN ?>">
															<input type="checkbox" name="status_policy_confirmed" style="background: #dfdfdf" <?php echo ($row['status_policy_confirmed']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.status_policy_confirmed);"><?= AS_CASD_POTWPOL ?></font>
														</td>
														<td width="20%" title="<?= AS_CASD_ZAZJUZYSKWSZDOK ?>">
															<input type="checkbox" name="status_documentation" style="background: #dfdfdf" <?php echo ($row['status_documentation']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.status_documentation);"><?= AS_CASES_DOK ?></font>
														</td>
														<td width="20%" title="<?= AS_CASD_ZAZJESDECYTU?>">
															<input type="checkbox" name="status_decision" onclick="if (<?php echo $row['status_decision'] ?> && this.checked) alert('<?= AS_CASD_PRWPISDECWTECZ ?>');" style="background: #dfdfdf" <?php echo ($row['status_decision']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="if (<?php echo $row['status_decision'] ?> && !form1.status_decision.checked) alert('<?= AS_CASD_PRWPISDECWTECZ ?>'); checkboxSelect(form1.status_decision);"><?= AS_CASES_DEC ?></font>
															<!--
															//TODO: zmieniam na popup przy zaznaczeniu wyst1pienia gwarnacji
															<input type="checkbox" name="status_decision" style="background: #dfdfdf" <?php echo ($row['status_decision']) ? "checked" : "" ?> onclick="<?php echo ($row['status_decision']) ? "" : "Guarantee('$_GET[case_id]'); return false;" ?>">&nbsp;<font style="cursor: default" onclick="<?php echo ($row['status_decision']) ? "checkboxSelect(form1.status_decision);" : "Guarantee('$_GET[case_id]');" ?>">Decyzja</font>
															//-->
														</td>
														<td width="20%" title="<?= AS_CASD_ZAZNZAKDZASST ?>">
															<input type="checkbox" name="status_assist_complete" style="background: #dfdfdf" <?php echo ($row['status_assist_complete']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="checkboxSelect(form1.status_assist_complete);" color="green"><b><?= AS_CASD_ZAKONCZ ?></b></font>
														</td>
													</tr>
													<tr>
														<td colspan="5">
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr valign="top">
											<td width="100%" colspan="2">
												<table cellpadding="1" cellspacing="1" border="" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td width="50%" align="center" <?php echo ($row['attention2']) ? "bgcolor=\"#6699cc\" style=\"color: yellow\"" : "style=\"color: red\""; ?>>
															<input type="checkbox" name="attention2" id="attention2" style="background: #6699cc" onclick="zaznacz_uwaga('attention2');"  <?php echo ($row['attention2']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="zaznacz_uwaga('attention2');" title="<?= AS_CASD_PROSZZAZNSZCZEGSPR2 ?>"><b><?= AS_CASD_UWAGNATASPR2 ?></b></font>
														</td>
														<td width="50%" align="center" <?php echo ($row['attention']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\""; ?>>
															<input type="checkbox" name="attention"  id="attention" style="background: red;" onclick="zaznacz_uwaga('attention');" <?php echo ($row['attention']) ? "checked" : "" ?>>&nbsp;<font style="cursor: default" onclick="zaznacz_uwaga('attenction');" title="<?= AS_CASD_PROSZZAZNSZCZEGSPR ?>"><b><?= AS_CASD_UWAGNATASPR ?></b></font>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr valign="top">
											<td width="100%" colspan="2">
												<table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="100%" style="border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
													<tr>
														<td align="right" colspan="2">
															<input type="hidden" name="reclamation_trigger" value="<?php echo $row['reclamation'] ?>"><input type="checkbox" name="reclamation" <?php echo ($row['reclamation']) ? "checked disabled" : "" ?> style="background: #dfdfdf;" onclick="if (this.checked) { form1.reclamation_text.disabled = false; form1.reclamation_text.style.backgroundColor='#eeeeee'; } else { form1.reclamation_text.style.backgroundColor='#dfdfdf'; form1.reclamation_text.disabled = true; }"><small>&nbsp;<font color="#6699cc" style="cursor: default" onclick="checkboxSelect(form1.reclamation); if (form1.reclamation.checked) { form1.reclamation_text.disabled = false; form1.reclamation_text.style.backgroundColor='#eeeeee'; } else { form1.reclamation_text.style.backgroundColor='#dfdfdf'; form1.reclamation_text.disabled = true; }"><?= AS_CASES_REKL ?></font></small>&nbsp;

														</td>
													</tr>
													<tr>
														<td align="center" colspan="2">
															<textarea name="reclamation_text" rows="11" cols="80" style="background: #dddddd; font-family: Verdana; font-size: 8pt;" <?php echo ($row['reclamation']) ? "" : "disabled" ?>><?php echo $row['reclamation_text'] ?></textarea>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</form>
								</table>
							</td>
						</tr>
					</table>
				</td>
				<td width="10%">
					<table cellpadding="2" cellspacing="0" border="0" style="border-top: #000000 1px solid; border-right: #000000 1px solid">
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
										<td align="center"><a href="AS_cases_details.php?case_id=<?php echo $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" title="teczka" style="font-size: 32pt">Ì</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_TECZKA2 ?></font></a></td>
									</tr>
									<tr height="50">
										<td align="center"><font color="#6699cc" face="Webdings" style="font-size: 42pt" title="<?= AS_CASD_USTAW ?>">'</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_USTAW ?></font></td>
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
								<!-- <a href="javascript:void(0)"><font color="#ced9e2" size="+4" face="webdings" onmouseover="this.color='green'" onmouseout="this.color='#ced9e2'" title="finanse">'</font></a><br> -->
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<script language="JavaScript1.2">
			<!--
			/*
			Double Combo Script Credit
			By Website Abstraction (www.wsabstract.com)
			Over 200+ free JavaScripts here!
			*/

			var groups=document.form1.type_id.options.length;
			var group=new Array(groups);
			for (i=0; i<groups; i++)
			group[i]=new Array();

			<?php
				$query = "SELECT type_id, genre_id, value FROM coris_assistance_cases_genres ORDER BY type_id, genre_id";
				$result = mysql_query($query);
				$old_genre_id = 0;
				while ($row2 = mysql_fetch_array($result)) {
					if ($row2['genre_id'] != $old_genre_id)
					echo "group[". ($row2['type_id'] - 1) ."][0]=new Option(\"\", \"0\");\n";
					echo "group[". ($row2['type_id'] - 1) ."][$row2[genre_id]]=new Option(\"$row2[value]\", \"$row2[genre_id]\");\n";
					$old_genre_id = $row2['genre_id'];
				}
			?>
			var temp=document.form1.genre_id;

			function redirect(x){
				for (m=temp.options.length-1;m>0;m--)
				temp.options[m]=null
				for (i=0;i<group[x].length;i++){
					temp.options[i]=new Option(group[x][i].text,group[x][i].value)
				}
				temp.options[0].selected=true
			}
			//-->
		</script>
	</body>
</html>
<?php } ?>
