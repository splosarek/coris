<?php 
include('include/include.php');
include('lib/RegisterActionAfterDecision.php');

$_CONFIG['cases_details_insurance_decisions']['decision_with_reserve'][] =  array( 'client_id' => '11',	'mod' => 'europa_rez' );
$_CONFIG['cases_details_insurance_decisions']['decision_with_reserve'][] =  array( 'client_id' => '2201',	'mod' => 'europa_rez' );
$_CONFIG['cases_details_insurance_decisions']['decision_with_reserve'][] =  array( 'client_id' => '11086',	'mod' => 'cardif_annonce' );
		
	
$case_id = getValue('case_id');
$user_action = getValue('user_action');
$dec_id = getValue('dec_id');


if ($dec_id > 0 && $user_action!=''){
	
	
	RegisterActionAfterDecision::registerUserAnswer($case_id, $dec_id, $user_action);
	
	if ($user_action == 'Nie'){	
				
	}else if ($user_action == 'Tak'){
		$mod = get_contrahent_mod($case_id);

		if ($mod!=''){		
			//echo "<script language=\"JavaScript1.2\">opener.document.location='AS_cases_details.php?case_id=$case_id&mod=$mod&change[rezerwy_rezerwy]=1' </script>";
			echo "<script language=\"JavaScript1.2\">opener.document.getElementById('form_decyzje_action').action='AS_cases_details.php?case_id=$case_id&mod=$mod' </script>";
			echo "<script language=\"JavaScript1.2\">opener.document.getElementById('form_decyzje_action_input').name='change[rezerwy_rezerwy]'; </script>";			
			echo "<script language=\"JavaScript1.2\">opener.document.getElementById('form_decyzje_action_dec_id').value='$dec_id'; </script>";			
			echo "<script language=\"JavaScript1.2\">opener.document.getElementById('form_decyzje_action').submit();</script>";			
		}
	}

	echo "<script language=\"JavaScript1.2\">window.close(); </script>";
			
	exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= AS_CASD_DODDEC ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
<body>
	<script language="JavaScript1.2">
		<!--
		function checkboxSelect(s) {
			if (s.checked) {
				s.checked = false;
			} else {
				s.checked = true;
			}
		}
		function validate(s) {
			if (s.type_id.value == 0) {
				alert("<?= AS_CASD_PRZAZDECZLISTROZW ?>");
				s.type_id.focus();
				return false;
			}

			var re = /\d{4}-\d{2}-\d{2}/;
			if (s.date.value == "" || !re.test(s.date.value)) {
				alert("<?= AS_CASD_PRWPPOPRDATDEC ?>");
				s.date.focus();
				return false;
			}
			re = /\d{2}:\d{2}/;
			if (s.time.value == "" || !re.test(s.time.value)) {
				alert("<?= AS_CASD_PRWPPOPRGODZWYDDEC  ?>");
				s.time.focus();
				return false;
			}
		 if (s.type_id.value != '4') {
				
			if (s.amount.value == "") {
				alert("<?= AS_CASD_PRWPGWARKW ?>");
				s.amount.focus();
				return false;
			} else if (!s.amount.value.match(/^\d*$/) && !s.amount.value.match(/^\d*,\d\d$/) && !s.amount.value.match(/^\d*\.\d\d$/)) {
				alert("<?= AS_CASD_BLFORMKWGWAR ?>");
				s.amount.focus();
				return false;
			}
			if (s.currency_id.value == 0) {
				alert("<?= AS_CASD_MSG_PROSZWYBRWAL ?>");
				s.currency_id.focus();
				return false;
			}	
		 }
		}
		
		function setTime() {
			var width = 50;
			var height = 455;
			var left = (screen.availWidth - width) / 2;
			var top = ((screen.availHeight - 0.2 * screen.availHeight) - height) / 2;
			window.open('AS_cases_details_insurance_decisions_add_time.php','time','resizable=no,width='+ width +',height='+ height +',left='+ left +',top='+ top);
		}

		//-->
	</script>
	<script language="JavaScript" src="CalendarPopup.js"></script>
	<script language="JavaScript">
		<!--
    	var cal = new CalendarPopup();		
		cal.setMonthNames(<?= MONTHS_NAME ?>); 
		cal.setDayHeaders(<?= DAY_NAME ?>); 
		cal.setWeekStartDay(1); 
		cal.setTodayText('<?= TODAY ?>');
		//-->
	</script>
	<?php
        $row3=array();
		if (isset($_GET['action'])) {

		
			$type_id = getValue('type_id');
			$note = getValue('note');
			
			$decision_date = $_POST['date'] . " " . $_POST['time'];
			//mysql_query("BEGIN");

			if ($_POST['type_id'] > 0) { // mamy decyzjê towarzystwa...

			$query = "INSERT INTO coris_assistance_cases_decisions (case_id, type_id, amount, currency_id, decision_date, note, user_id, date) VALUES ('$case_id', '$type_id', '". str_replace(",", ".", $_POST['amount']) . "', '$_POST[currency_id]', '$decision_date', '$note', '$_SESSION[user_id]', NOW())";

			if ($result = mysql_query($query)) {
				$dec_id = mysql_insert_id();
				
				$query = "UPDATE coris_assistance_cases_details SET policyamount = '". str_replace(",", ".", $_POST['policyamount']) . "', policycurrency_id = '$_POST[policycurrency_id]' WHERE case_id = '$case_id'";
				if ($result = mysql_query($query)) {

					// Wpisujê do historii zdarzenie pt. dodanie notatki
					$query = "INSERT INTO coris_assistance_cases_history (case_id, actiontype_id, actiongroup_id, user_id, session_id, date) VALUES ('$case_id', '2', '1', '$_SESSION[user_id]', '$_SESSION[session_id]', NOW())";
					if ($result = mysql_query($query)) {
						$query = "UPDATE coris_assistance_cases SET status_decision = 1 WHERE case_id = '$case_id'";
						if ($result = mysql_query($query)) {																			
							form_2krok($dec_id,$case_id);
							exit;
						} else {
							
							die (mysql_error());
						}
					} else {
						
						die (mysql_error());
					}
				} else {
					
					die (mysql_error());
				}
			}
		}
	} else {
		$query = "SELECT policyamount, policycurrency_id FROM coris_assistance_cases_details WHERE case_id = '$case_id'";
		if ($result = mysql_query($query)) {
			$row3 = mysql_fetch_array($result);
		} else {
			die (mysql_error());
		}
	}

	
	
wysw_form($case_id);	

function check_contrahent_monit($contrahent_id){
	global $_CONFIG;
	
	
	$dec_with_reserver = $_CONFIG['cases_details_insurance_decisions']['decision_with_reserve'];	
		
	foreach ($dec_with_reserver As $poz){
			
		if ($poz['client_id'] == $contrahent_id){
			return true;
		}
	}	
	return false;	
}

function get_contrahent_mod($case_id){
	global $_CONFIG;
	
	$case_info = getCaseInfo($case_id);	
	$contrahent_id  = $case_info['contrahent_id'];
							
	$dec_with_reserver = $_CONFIG['cases_details_insurance_decisions']['decision_with_reserve'];	
		
	foreach ($dec_with_reserver As $poz){
			
		if ($poz['client_id'] == $contrahent_id){
			return $poz['mod'];
		}
	}	
	return false;	
}



function form_2krok($dec_id,$case_id){
//	echo "<hr>dec_id:$dec_id, case_id:$case_id";	
	$case_info = getCaseInfo($case_id);	
	$contrahent_id  = $case_info['contrahent_id'];
	
	
	
	echo "<script language=\"JavaScript1.2\">opener.document.getElementById('form_decyzje').submit(); </script>";

	if (check_contrahent_monit($contrahent_id)){
			
			RegisterActionAfterDecision::register($case_id,$dec_id);
			
			echo '<div align="center" style="padding-top:50px;font-weight: bold">Uzupe³nij / popraw rezerwê</div>';
	
			echo '<form method="post">';
			echo '<input type="hidden" name="dec_id" value="'.$dec_id.'">';			
			echo '<br><br><table width="25%" border=0 align="center"><tr>';
				echo '<td align="center">';
				echo '<input type="submit" name="user_action" value="Tak" >';
				
				echo '</td><td align="center">';
			
				echo '<input type="submit" name="user_action" value="Nie" >';	
			
				
			
					echo '</td>';		
					echo '</tr></table>';	
			echo "</form>";
	}else{
		echo "<script language=\"JavaScript1.2\">window.close(); </script>";
		
	}
}

function wysw_form($case_id){
    global $row3;
?>
	<form action="AS_cases_details_insurance_decisions_add.php?action=1&case_id=<?php echo $case_id ?>" method="post" name="form1" onsubmit="return validate(this);">
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="popupTitle"><?= AS_CASD_DODDEC ?>&nbsp;</td>
		</tr>
	</table>		
	<table cellpadding=4 cellspacing=0 width="100%">
		<tr>
			<td align="center">
				<table width="100%">
					<tr>
						<td width="55%" class="frame">
							<table width="100%">
								<tr>
									<td align="right" colspan="2">
										<small><font color="#6699cc"><?= AS_CASES_DEC ?></font></small>&nbsp;
									</td>
								</tr>
								<tr>
									<td align="right"><small><?= AS_CASES_DEC ?></small></td>
									<td>
										<div align="left">
										  <select name="type_id" id="type_id" style="font-size: 8pt;">
										      <?php
												function StrTrim($string, $length) {
													return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
												}
												$query = "SELECT type_id, value FROM coris_assistance_cases_decisions_types ORDER BY value";
												if ($result = mysql_query($query)) {
													while ($row = mysql_fetch_array($result)) {
														echo "<option value=\"$row[type_id]\">". StrTrim($row['value'], 35) ."</option>";
													}
												} else {
													die (mysql_error());
												}
												
											?>
									      </select>
								      </div></td>
								</tr>
								<tr>
									<td align="right" title="Data gwarancji"><small><?= AS_CASD_DATGWAR  ?></small></td>
									<td>
										<div align="left">
										<input type="text" name="date"  id="date" size="11" value="<?php echo date("Y-m-d") ?>" style="text-align: center"> 
										<a href="javascript:void(0)" onclick="cal.select(document.form1.date,'anchor1','yyyy-MM-dd'); return false;" tabindex="-1" style="text-decoration: none" name="anchor1" id="anchor1"><img src="img/kalendarzBlue.gif" border="0"></a>
										<input type="text" name="time"  id="time" size="5" value="<?php echo date("G:i") ?>" style="text-align: center">
										&nbsp;<a href="javascript:void(0)" onclick="setTime()" tabindex="-1" style="text-decoration: none"><img src="img/ZegarBlue.gif" border="0"></a>
									    </div></td>
								</tr>
								<tr>
									<td align="right" title="Gwarantowana suma"><small><?= AS_CASD_KWOTGWAR ?></small></td>
									<td>
										<div align="left">
										  <input type="text" name="amount" size="10" maxlength="10" style="text-align: right">
										  <select name="currency_id" style="font-size: 8pt;">
										      <option value="0"></option>
										      <?php

                                              $currencyDefault = '';
                                                if('2' == getValue('branch_id') || '3' == getValue('branch_id'))
                                                {
                                                    $currencyDefault = "EUR";
                                                }
												$query = "SELECT currency_id FROM coris_finances_currencies WHERE insurance = 1 AND active = 1 ORDER BY currency_id";
												if ($result = mysql_query($query)) {
													while ($row = mysql_fetch_array($result)) {
													?>
												      <option value="<?php echo $row['currency_id'] ?>" <?php echo ($currencyDefault == $row['currency_id']?'selected="selected"':'');?>><?php echo $row['currency_id'] ?></option>
												      <?php
													}
												} else {
													die (mysql_error());
												}
											?>
									      </select>
								      </div></td>
								</tr>
							</table>
					  </td>
						<td width="45%" valign="top">
							<table width="100%">
								<tr>
									<td align="right" colspan="2">
										<small><font color="#6699cc"><?= AS_CASADD_UBEZP ?></font></small>&nbsp;
									</td>
								</tr>
								<tr>
									<td width="75" align="right">
										<small><?= SUM ?></small>
									</td>
									<td width="150">
										<input type="text" name="policyamount"  size="10" maxlength="10" value="<?php echo ($row3['policyamount'] != 0) ? str_replace(".", ",", $row3['policyamount']) : "" ?>" style="font-size: 8pt; text-align: right;">&nbsp;
										<select name="policycurrency_id" style="font-size: 8pt;">
											<option></option>
											<?php
                                                if('2' == getValue('branch_id') || '3' == getValue('branch_id'))
                                                 {
                                                     $row3['policycurrency_id'] = "EUR";
                                                 }
												$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 AND insurance = 1 ORDER BY currency_id";
												if ($result = mysql_query($query)) {
													while ($row2 = mysql_fetch_array($result)) {
														echo ($row3['policycurrency_id'] == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
													}
												} else {
													die (mysql_error());
												}
											?>
										</select>
									</td>
								</tr>
							</table>
						</td>
				    </tr>
					<tr>
						<td colspan="2">
							<table width="100%">
								<tr>
									<td align="right"><small><?= NOTE ?></small></td>
									<td>
										<textarea name="note" rows="4" cols="68" style="font-family: Verdana; font-size: 8pt" onKeyPress="return (this.value.length < 255);" onPaste="return ((form1.note.value.length + window.clipboardData.getData('Text').length) < 255 );" wrap="virtual"></textarea>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASD_ZAPDECWSPR ?>">
						</td>
					</tr>
			  </table>
			</td>
		</tr>		
	</table>
	</form>	
	<script>document.getElementById('type_id').focus();</script>
<?php 
}
?>	
</body>
</html>
