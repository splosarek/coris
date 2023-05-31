<?php include('include/include.php');

	$query_rs_ticket = sprintf("SELECT coris_tickets.ticket_id, coris_tickets.category_id, coris_tickets.status_id, coris_tickets.label_id, coris_tickets.note, DATE(coris_tickets.date) AS date, TIME(coris_tickets.date) AS time, coris_tickets.user_id, coris_users.name, coris_users.surname, coris_users.username, coris_tickets_categories.value AS category, coris_tickets_statuses.value AS status, coris_tickets_labels.value AS label FROM coris_tickets, coris_users, coris_tickets_categories, coris_tickets_statuses, coris_tickets_labels WHERE coris_tickets.ticket_id = %s AND coris_tickets.user_id = coris_users.user_id AND coris_tickets.category_id = coris_tickets_categories.category_id AND coris_tickets.status_id = coris_tickets_statuses.status_id AND coris_tickets.label_id = coris_tickets_labels.label_id",
	GetSQLValueString($_GET['ticket_id'], "int"));
	$rs_ticket = mysql_query($query_rs_ticket, $cn) or die(mysql_error());
	$row_rs_ticket = mysql_fetch_assoc($rs_ticket);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<body>
	<br>
	<br>
	<br>
	<center>
	<b><?= GEN_TIC_ZZP ?></b>
	<br><br>
	<input type="button" value="<?=BUT_RET?>" onclick="document.location='GEN_tickets_frame.php'">&nbsp;<input type="button" value="<?=BUTT_CLOSE?>" onclick="top.window.close();">
	<br>
	</center>
<?php
	$string = GEN_TIC_ZZP . $row_rs_ticket[ticket_id].".\n\n". GEN_TIC_PNO . "\t- " . $row_rs_ticket[category] . "\n" .GEN_TIC_ZGP ."\t- ". $row_rs_ticket[name] . ' ' . $row_rs_ticket[surname]. "\n" .GEN_TICAE_STA . "\t- " . $row_rs_ticket[status] . "\n" .GEN_TICAE_PRI ."\t- ". $row_rs_ticket[label] . "\n\n" .GEN_TIC_DAU . "\t- " . $row_rs_ticket[date] . '(' .$row_rs_ticket[time] . ")\n\n". GEN_TIC_WIAA ."\n". $row_rs_ticket[note];
mail("$row_rs_ticket[username]@server.coris.com.pl", '[' . GEN_TIC_ZGL . $row_rs_ticket[ticket_id] .']'. GEN_TIC_TZP, $string);

	$string = GEN_TIC_UNZ . $row_rs_ticket[ticket_id] .".\n\n" . GEN_TIC_DOI ."\t- ". $row_rs_ticket[category] . "\n" .GEN_TIC_ZGP ."\t- ". $row_rs_ticket[name] . $row_rs_ticket[surname] . "\n" .GEN_TICAE_STA . "\t- " . $row_rs_ticket[status] . "\n". GEN_TICAE_PRI . "\t- ". $row_rs_ticket[label] . "\n\n" .GEN_TIC_DAU. "\t- " . $row_rs_ticket[date] .' (' .$row_rs_ticket[time] .")\n\n" . GEN_TIC_WIAA ."\n". $row_rs_ticket[note];
mail("it@server.coris.com.pl", '[' . GEN_TIC_ZGL . $row_rs_ticket[ticket_id] .'] '. GEN_TIC_DZI, $string);

?>	
</body>
</html>
<?php
	mysql_free_result($rs_ticket);	
?>
