<?php include('include/include.php');

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

    mysql_query("BEGIN", $cn);
    		
		$insertSQL = sprintf("INSERT INTO coris_tickets_comments (ticket_id, note, user_id, date) VALUES (%s, %s, %s, NOW())",
		GetSQLValueString($_POST['ticket_id'], "int"),
		GetSQLValueString($_POST['note'], "text"),
		GetSQLValueString($_POST['user_id'], "int"));

		
		if ($Result1 = mysql_query($insertSQL, $cn)) {

			$updateSQL = sprintf("UPDATE coris_tickets SET status_id = %s WHERE ticket_id = %s",
			GetSQLValueString($_POST['status_id'], "int"),
			GetSQLValueString($_POST['ticket_id'], "int"));
	
			
			if ($Result1 = mysql_query($updateSQL, $cn)) {
				mysql_query("COMMIT");
				
				$query_rs_comment = sprintf("SELECT LAST_INSERT_ID() AS id");
				$rs_comment = mysql_query($query_rs_comment, $cn) or die(mysql_error());
				$row_rs_comment = mysql_fetch_assoc($rs_comment);
				$comment_rs_comment = $row_rs_comment['id'];				
				
				header("Location: GEN_tickets_details_end.php?comment_id=$comment_rs_comment&ticket_id=". $_POST['ticket_id'] ."&offset=". $_POST['offset'] ."&document_location=". urlencode($_POST['document_location']));
				exit();
		 	} else {
				mysql_query("ROLLBACK", $cn);
		 		die(mysql_error());
		 	}		
		
	 	} else {
			mysql_query("ROLLBACK", $cn);	 	
	 		die(mysql_error());
	 	}

	}
	
	$ip = (!empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : (( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
	if ($ip == "10.0.0.111") {
		$user_id = 4;
	} else if ($ip == "10.0.0.110") {
		$user_id = 1;
	} else {
		$user_id = 0;
	}

	
	$query_rs_ticket = "SELECT coris_tickets.ticket_id, coris_tickets.category_id, coris_tickets.status_id, coris_tickets.label_id, coris_tickets.note, DATE(coris_tickets.date) AS date, TIME(coris_tickets.date) AS time, DAYOFWEEK(coris_tickets.date) AS dayofweek, coris_tickets.user_id, coris_users.surname, coris_tickets_categories.value AS category, coris_tickets_statuses.value AS status, coris_tickets_labels.value AS label, coris_tickets_labels.color FROM coris_tickets, coris_users, coris_tickets_categories, coris_tickets_statuses, coris_tickets_labels WHERE coris_tickets.user_id = coris_users.user_id AND coris_tickets.category_id = coris_tickets_categories.category_id AND coris_tickets.status_id = coris_tickets_statuses.status_id AND coris_tickets.label_id = coris_tickets_labels.label_id AND coris_tickets.ticket_id = $_GET[ticket_id]";
	$rs_ticket = mysql_query($query_rs_ticket, $cn) or die(mysql_error());
	$row_rs_ticket = mysql_fetch_assoc($rs_ticket);
	$totalRows_rs_ticket = mysql_num_rows($rs_ticket);

	
	$query_rs_comments = "SELECT coris_tickets_comments.comment_id, coris_tickets_comments.ticket_id, coris_tickets_comments.note, coris_tickets_comments.user_id, DATE(coris_tickets_comments.date) AS date, TIME(coris_tickets_comments.date) AS time, coris_users.surname FROM coris_tickets_comments, coris_users WHERE coris_tickets_comments.user_id = coris_users.user_id AND coris_tickets_comments.ticket_id = $_GET[ticket_id] AND coris_tickets_comments.active = 1 ORDER BY coris_tickets_comments.date";
	$rs_comments = mysql_query($query_rs_comments, $cn) or die(mysql_error());
	$row_rs_comments = mysql_fetch_assoc($rs_comments);
	$totalRows_rs_comments = mysql_num_rows($rs_comments);

	
	$query_rs_statuses = "SELECT status_id, value FROM coris_tickets_statuses";
	$rs_statuses = mysql_query($query_rs_statuses, $cn) or die(mysql_error());
	$row_rs_statuses = mysql_fetch_assoc($rs_statuses);
	$totalRows_rs_statuses = mysql_num_rows($rs_statuses);

  
  $query_rs_users = "SELECT user_id, name, surname FROM coris_users WHERE active = 1 ORDER BY surname, name";
  $rs_users = mysql_query($query_rs_users, $cn) or die(mysql_error());
  $row_rs_users = mysql_fetch_assoc($rs_users);
  $totalRows_rs_users = mysql_num_rows($rs_users);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
		<title><?=GEN_TICDET_TITLE?></title>
		<link href="Styles/general.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/JavaScript"> 
			<!--
				function validate(s) {
					if (s.user_id.value == 0) {
						alert("<?= GEN_TIC_PWNAZ ?>");
						s.user_id.focus();
						return false;
					}
					if (s.note.value == "") {
						alert("<?= GEN_TIC_PWT ?>");
						s.note.focus();
						return false;
					}
					if (s.status_id.value == 0) {
						alert("");
						s.status_id.focus();
						return false;						
					}
				}
			//-->
		</script>
	</head>
	<br>
	<body onload="form1.note.focus();">
		<form name="form1" method="POST" action="<?php echo $editFormAction ?>" onSubmit="return validate(this);">
			<center>
				<font style="font-size: 10pt;">Zg³oszenie nr</font>&nbsp;&nbsp;<b><?php echo $row_rs_ticket['ticket_id']; ?></b>&nbsp;&nbsp;z&nbsp;dnia&nbsp;&nbsp;<b><?php echo $row_rs_ticket['date']; ?></b>
				<br>
				<hr noshade size="1" color="#000000">
				<b><font style="font-size: 10pt;"><?= GEN_TIC_NK ?></font></b>
				<table border="0" cellspacing="2" cellpadding="2">
					<tr>
						<td bgcolor="#f4efc4" class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" width="100"><?= GEN_TIC_UZ ?>&nbsp;</td>
									<td align="left">
										<select name="user_id" id="user_id">
											<option value="0"></option>
										<?php
											do {
											?>
											<option value="<?php echo $row_rs_users['user_id']?>" <?php if (!(strcmp($row_rs_users['user_id'], $user_id))) {echo "SELECTED";} ?>><?php echo $row_rs_users['surname']?> <?php echo $row_rs_users['name']?></option>
											<?php
											} while ($row_rs_users = mysql_fetch_assoc($rs_users));
											$rows = mysql_num_rows($rs_users);
											if($rows > 0) {
												mysql_data_seek($rs_users, 0);
												$row_rs_users = mysql_fetch_assoc($rs_users);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#E0E0E0" class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" valign="top" width="100"><?= GEN_TIC_TR ?>&nbsp;</td>
									<td align="left">
										<textarea name="note" rows="5" cols="100"></textarea>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td bgcolor="#f4efc4" class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" width="100"><?=GEN_TIC_STA?>:&nbsp;</td>
									<td align="left">
										<select name="status_id" id="status_id">
											<option value="0"></option>
										<?php
											do {
											?>
											<option value="<?php echo $row_rs_statuses['status_id']?>" <?php if (!(strcmp($row_rs_ticket['status_id'], $row_rs_statuses['status_id']))) {echo "SELECTED";} ?>><?php echo "$row_rs_statuses[status_id] - $row_rs_statuses[value]" ?></option>
											<?php
											} while ($row_rs_statuses = mysql_fetch_assoc($rs_statuses));
											$rows = mysql_num_rows($rs_statuses);
											if($rows > 0) {
												mysql_data_seek($rs_statuses, 0);
												$row_rs_statuses = mysql_fetch_assoc($rs_statuses);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td align="center">
							<input type="hidden" name="MM_insert" id="MM_insert" value="form1"> 
							<input type="hidden" name="ticket_id" id="ticket_id" value="<?php echo $_GET['ticket_id']; ?>"> 
							<input type="hidden" name="offset" id="offset" value="<?php echo $_GET['offset']; ?>"> 							
							<input type="hidden" name="document_location" id="document_location" value="<?php echo $_GET['document_location']; ?>"> 
							<input tabindex="-1" accesskey="r" name="Cancel" type="button" id="Cancel" style="background: orange; width: 100px;" onClick="document.location='<?php echo $_GET['document_location']; ?>&offset=<?php echo $_GET['offset'] ?>'" value="<?=BUTT_CANCEL?>">
							&nbsp;
							<input accesskey="a" name="Submit" type="submit" id="Submit" value="<?=BUTT_SAVE?>" style="background: yellow; width: 100px;">
						</td>
					</tr>
				</table>				
				<br>
				<table border="0" cellspacing="2" cellpadding="2" width="90%">
					<tr bgcolor="#f4efc4">
						<td width="15%" align="center"><i><?php echo $row_rs_ticket['time']; ?></i></td>
						<td width="25%" align="left"><?= GEN_TIC_PRO ?>:&nbsp;<font color="blue"><?php echo $row_rs_ticket['category']; ?></font></td>
						<td width="19%" align="left"><?=GEN_TIC_PRI?>:&nbsp;<font color="<?php echo $row_rs_ticket['color']; ?>"><?php echo $row_rs_ticket['label']; ?></font></td>
						<td width="19%" align="left"><?=GEN_TIC_STA?><?php echo $row_rs_ticket['status']; ?></td>						
						<td width="22%" align="center"><?php echo $row_rs_ticket['surname']; ?></td>
					</tr>
					<tr>
						<td bgcolor="lightyellow" colspan="5" align="left"><?php echo nl2br($row_rs_ticket['note']); ?></td>
					</tr>
					<?php
									if ($totalRows_rs_comments) {
									?>
					<tr>
						<td colspan="5" style="border-left: #E0E0E0 8px solid;">
							<table width="100%" border="0" cellpadding="2" cellspacing="1">
								<?php
										do { 
									?>
										<tr bgcolor="#f4efc4">
											<td width="20%" align="center"><i><?php echo $row_rs_comments['time']; ?></i></td>
											<td width="55%" align="left">&nbsp;</td>
											<td width="22%" align="center"><?php echo $row_rs_comments['surname']; ?></td>
										</tr>
										<tr>
											<td colspan="3" bgcolor="lightyellow" align="left"><?php echo nl2br($row_rs_comments['note']); ?></td>
										</tr>
									<?php 
									} while ($row_rs_comments = mysql_fetch_assoc($rs_comments)); 
								?>
							</table>
						</td>
					</tr>
						<?php
						}
					?>
				</table>

			</center>
		</form>
	</body>
</html>
<?php
	mysql_free_result($rs_ticket);
	mysql_free_result($rs_comments);
	mysql_free_result($rs_statuses);
	mysql_free_result($rs_users);
?>
