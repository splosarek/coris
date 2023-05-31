<?php include('include/include.php'); 
	

	if (isset($_GET['year'])) {
		$query_rs_tickets = "SELECT coris_tickets.ticket_id, coris_tickets.category_id, coris_tickets.status_id, coris_tickets.label_id, coris_tickets.note, DATE(coris_tickets.date) AS date, TIME(coris_tickets.date) AS time, DAYOFWEEK(coris_tickets.date) AS dayofweek, coris_tickets.user_id, coris_users.surname, coris_tickets_categories.value AS category, coris_tickets_statuses.value AS status, coris_tickets_labels.value AS label, coris_tickets_labels.color FROM coris_tickets, coris_users, coris_tickets_categories, coris_tickets_statuses, coris_tickets_labels WHERE coris_tickets.user_id = coris_users.user_id AND coris_tickets.category_id = coris_tickets_categories.category_id AND coris_tickets.status_id = coris_tickets_statuses.status_id AND coris_tickets.label_id = coris_tickets_labels.label_id AND YEAR(coris_tickets.date) = $_GET[year] AND MONTH(coris_tickets.date) = $_GET[month] AND coris_tickets.active = 1 ORDER BY coris_tickets.ticket_id DESC, coris_tickets.date";
	} else {
		$query_rs_tickets = "SELECT coris_tickets.ticket_id, coris_tickets.category_id, coris_tickets.status_id, coris_tickets.label_id, coris_tickets.note, DATE(coris_tickets.date) AS date, TIME(coris_tickets.date) AS time, DAYOFWEEK(coris_tickets.date) AS dayofweek, coris_tickets.user_id, coris_users.surname, coris_tickets_categories.value AS category, coris_tickets_statuses.value AS status, coris_tickets_labels.value AS label, coris_tickets_labels.color FROM coris_tickets, coris_users, coris_tickets_categories, coris_tickets_statuses, coris_tickets_labels WHERE coris_tickets.user_id = coris_users.user_id AND coris_tickets.category_id = coris_tickets_categories.category_id AND coris_tickets.status_id = coris_tickets_statuses.status_id AND coris_tickets.label_id = coris_tickets_labels.label_id AND YEAR(coris_tickets.date) = YEAR(CURDATE()) AND MONTH(coris_tickets.date) = MONTH(CURDATE()) AND coris_tickets.active = 1 ORDER BY coris_tickets.ticket_id DESC, coris_tickets.date";
	}

	$rs_tickets = mysql_query($query_rs_tickets, $cn) or die(mysql_error());
	$row_rs_tickets = mysql_fetch_assoc($rs_tickets);
	$totalRows_rs_tickets = mysql_num_rows($rs_tickets);

	$page = 'GEN_tickets_frame.php?';
	if (!empty($_SERVER['QUERY_STRING'])) {
		$page .= urlencode($_SERVER['QUERY_STRING']);
	}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
		<title><?= GEN_TIC_ZP ?></title>
		<link href="Styles/general.css" rel="stylesheet" type="text/css">
	</head>
	<body onload="<?php echo (isset($_GET['offset'])) ? "document.body.scrollTop = '$_GET[offset]'" : "" ?>">
	<form name="form1" id="form1" onsubmit="return false;">
		<table width="100%" border="0" cellpadding="1" cellspacing="1">
			<tr><td height="2"></td></tr>
			<tr>
				<td align="center">
					<b><?= GEN_TICFRA_MON ?></b>:
					<?php
						$months = array(1 => M01, M02, M03, M04, M05, M06, M07, M08, M09, M10, M11, M12);
						$month = (isset($_GET['month'])) ? $_GET['month'] : date("m");
						echo " <select name=\"month\" onchange=\"document.location='GEN_tickets_frame.php?year='+ form1.year.value +'&month='+ form1.month.value;\">";
							for ($i = 1; $i <= 12; $i++) {
								if ($i == $month)
								echo "<option value=\"$i\" selected>$months[$i]</option>";
								else 
								echo "<option value=\"$i\">$months[$i]</option>";
							}
							echo "</select>";

						$year = (isset($_GET['year'])) ? $_GET['year'] : date("Y");
						$curyear = date("Y");

						echo " <select name=\"year\" onchange=\"document.location='GEN_tickets_frame.php?year='+ form1.year.value +'&month='+ form1.month.value;\">";
							for ($i = $curyear - 4; $i <= $curyear; $i++) {
								if ($i == $year)
								echo "<option value=\"$i\" selected>$i</option>";
								else 
								echo "<option value=\"$i\">$i</option>";
							}
							echo "</select>";
					?>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="2" cellspacing="1">
			<?php
				$days = array(0, W1, W2, W3, W4, W5, W6, W7);
				$iterator = 0;
				$old_date = '';
				if ($totalRows_rs_tickets) {
					do { 
						if ($old_date != $row_rs_tickets['date']) {
						?>
						<tr>
							<td colspan="6" height="5"></td>
						</tr>
						<tr>
							<td colspan="6" align="right">
								<b><font style="font-size: 10pt;"><?php echo $row_rs_tickets['date']; ?>, <?php echo $days[$row_rs_tickets['dayofweek']]; ?></font></b>
							</td>
						</tr>
						<?php
						}
					?>
					<tbody <?php echo ($row_rs_tickets['status_id'] == 2) ? "disabled" : "" ?>  bgcolor="#f4efc4" 
					onclick="document.location='GEN_tickets_details.php?offset='+ document.body.scrollTop +'&ticket_id=<?php echo $row_rs_tickets['ticket_id']; ?>&document_location=<?php echo $page ?>'" style="cursor: default" 
				<?php if ($row_rs_tickets['status_id'] == 1) { ?>
					onmouseover="this.bgColor='#f4efe4'" onmouseout="this.bgColor='#f4efc4'"
				<?php } ?>
					>
						<tr>
							<td width="3%" align="right" bgcolor="#f4efc4"><b><?php echo $row_rs_tickets['ticket_id']; ?></b></td>
							<td width="15%" align="center"><i><?php echo $row_rs_tickets['time']; ?></i></td>
							<td width="25%" align="left"><?=GEN_TIC_PRO?>:&nbsp;<font color="blue"><?php echo $row_rs_tickets['category']; ?></font></td>
							<td width="17%" align="left"><?=GEN_TIC_PRI?>:&nbsp;<font color="<?php echo $row_rs_tickets['color']; ?>"><?php echo $row_rs_tickets['label']; ?></font></td>
							<td width="18%" align="left"><?=GEN_TIC_STA?><?php echo $row_rs_tickets['status']; ?></td>									
							<td width="22%" align="center"><?php echo $row_rs_tickets['surname']; ?></td>
						</tr>
						<tr>
							<td bgcolor="#f4eb9d" align="left">&nbsp;</td>
							<td bgcolor="lightyellow" colspan="5" align="left"><?php echo nl2br($row_rs_tickets['note']); ?></td>
						</tr>
					</tbody>
					<tr>
						<td colspan="6" height="2"></td>
					</tr>
					<?php 
						$old_date = $row_rs_tickets['date'];
						$iterator++;
					} while ($row_rs_tickets = mysql_fetch_assoc($rs_tickets)); 
				}
			?>
		</table>
		</form>
	</body>
</html>
<?php
	mysql_free_result($rs_tickets);
?>
