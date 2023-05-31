<?php include('include/include.php');

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

		$insertSQL = sprintf("INSERT INTO coris_tickets (category_id, status_id, label_id, note, date, user_id) VALUES (%s, 1, %s, %s, NOW(), %s)",
		GetSQLValueString($_POST['category_id'], "int"),
		GetSQLValueString($_POST['label_id'], "int"),				
		GetSQLValueString($_POST['note'], "text"),
		GetSQLValueString($_POST['user_id'], "int"));
		
		
		$Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());

		$query_rs_ticket = sprintf("SELECT LAST_INSERT_ID() AS id");
		$rs_ticket = mysql_query($query_rs_ticket, $cn) or die(mysql_error());
		$row_rs_ticket = mysql_fetch_assoc($rs_ticket);
		$ticket_rs_ticket = $row_rs_ticket['id'];

		header("Location: GEN_tickets_add_end.php?ticket_id=$ticket_rs_ticket");
	}		

	
	$query_rs_labels = "SELECT label_id, default_value, value FROM coris_tickets_labels";
	$rs_labels = mysql_query($query_rs_labels, $cn) or die(mysql_error());
	$row_rs_labels = mysql_fetch_assoc($rs_labels);
	$totalRows_rs_labels = mysql_num_rows($rs_labels);

	$ip = (!empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : (( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
	if ($ip == "10.0.0.111") {
		$user_id = 4;
	} else if ($ip == "10.0.0.110") {
		$user_id = 1;
	} else {
		$user_id = 0;
	}

  
  $query_rs_users = "SELECT user_id, name, surname FROM coris_users WHERE active = 1 ORDER BY surname, name";
  $rs_users = mysql_query($query_rs_users, $cn) or die(mysql_error());
  $row_rs_users = mysql_fetch_assoc($rs_users);
  $totalRows_rs_users = mysql_num_rows($rs_users);
	
	
	$query_rs_categories = "SELECT category_id, value FROM coris_tickets_categories ORDER BY value";
	$rs_categories = mysql_query($query_rs_categories, $cn) or die(mysql_error());
	$row_rs_categories = mysql_fetch_assoc($rs_categories);
	$totalRows_rs_categories = mysql_num_rows($rs_categories);	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
		<title><?= GEN_TIC_ZP ?></title>
		<link href="Styles/general.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/JavaScript"> 
			<!--
				function validate(s) {
					if (s.user_id.value == 0) {
						alert("<?=GEN_TIC_PWN?>");
						s.user_id.focus();
						return false;
					}
					if (s.label_id.value == 0) {
						alert("<?= GEN_TIC_PWP ?>");
						s.label_id.focus();
						return false;
					}
					if (s.category_id.value == 0){
						alert("<?=GEN_TIC_PWK?>");
						s.category_id.focus();
						return false;
					}
				}
			//-->
		</script>
	</head>
	<br>
	<br>
	<body onload="form1.note.focus();">
		<form name="form1" method="POST" action="<?php echo $editFormAction ?>" onSubmit="return validate(this);">
			<center>
				<b><font style="font-size: 10pt;"><?= GEN_TIC_NZ ?></font></b>
				<table border="0" cellspacing="2" cellpadding="2">
					<tr>
						<td bgcolor="#f4efc4" class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" width="100"><?=GEN_TIC_ZG?>&nbsp;</td>
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
						<td class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" width="100"><?=GEN_TIC_PRI?>:&nbsp;</td>
									<td align="left">
										<select name="label_id" id="label_id">
											<option value="0"></option>
										<?php
											do {
											?>
											<option value="<?php echo $row_rs_labels['label_id']?>" <?php echo ($row_rs_labels['default_value']) ? "selected" : "" ?>><?php echo $row_rs_labels['value']?></option>
											<?php
											} while ($row_rs_labels = mysql_fetch_assoc($rs_labels));
											$rows = mysql_num_rows($rs_labels);
											if($rows > 0) {
												mysql_data_seek($rs_labels, 0);
												$row_rs_labels = mysql_fetch_assoc($rs_labels);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" bgcolor="#f4efc4" class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" valign="top" width="100"><?=GEN_TIC_ROD?></td>
									<td align="left">
										<select name="category_id" id="category_id">
											<option value="0"></option>
										<?php
											do {
											?>
											<option value="<?php echo $row_rs_categories['category_id']?>"><?php echo $row_rs_categories['value']?></option>
											<?php
											} while ($row_rs_categories = mysql_fetch_assoc($rs_categories));
											$rows = mysql_num_rows($rs_categories);
											if($rows > 0) {
												mysql_data_seek($rs_categories, 0);
												$row_rs_categories = mysql_fetch_assoc($rs_categories);
											}
										?>
										</select>
									</td>
								</tr>
							</table>
						</td>
					</tr>					
					<tr>
						<td colspan="2" bgcolor="#E0E0E0" class="frame">
							<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td align="right" valign="top" width="100"><?=GEN_TIC_TZ?>&nbsp;</td>
									<td align="left">
										<textarea name="note" rows="10" cols="100"></textarea>
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
							<input tabindex="-1" accesskey="r" name="Cancel" type="button" id="Cancel" style="background: orange; width: 100px;" onClick="document.location='GEN_tickets_frame.php'" value="<?=BUTT_CANCEL?>">
							&nbsp;
							<input accesskey="a" name="Submit" type="submit" id="Submit" value="<?= BUTT_SAVE ?>" style="background: yellow; width: 100px;">
						</td>
					</tr>
				</table>
			</center>
		</form>
	</body>
</html>
<?php
	mysql_free_result($rs_labels);
	mysql_free_result($rs_users);
	mysql_free_result($rs_categories);	
?>
