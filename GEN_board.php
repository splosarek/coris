

<body bgcolor="#dfdfdf">
	<style>
		body {
			margin-top: 0.1cm;
		}
	</style>
	<table cellpadding="4" cellspacing="2" width="100%" border="0">
		<tr height="2"><td></td></tr>
	</table>
<!--
	<table cellpadding="4" cellspacing="2" width="100%" border="0">
<?
$query = "SELECT b.subject, b.value, b.urgent, DATE_FORMAT(b.date, '%Y-%m-%d %H:%i') AS date, u.name, u.surname FROM coris_board b, coris_users u WHERE b.user_id = u.user_id ORDER BY b.date DESC";
if ($result = mysql_query($query, $cn)) {
    while ($row = mysql_fetch_array($result)) {
?>
		<tr bgcolor="#cccccc">
			<td rowspan="2" width="1%" bgcolor="<?= ($row['urgent'] == 0) ? "#ced9e2" : "#c98282" ?>">&nbsp;</td>
			<td width="85%">
				<font color="#ffffff">
					<?= $row['subject'] ?>
				</font>
			</td>
			<td align="center">
				<font color="#ffffff">
					<small><?= $row['date'] ?></small>
				</font>
			</td>
		</tr>
		<tr bgcolor="dfdfdf">
			<td width="85%"><small><?= $row['value'] ?></small></td>
			<td align="center"><font color="#6699cc"><?= $row['name'] ?></font></td>
		</tr>
		<tr><td colspan="3" style="border-top: #ffffff 1px solid;">&nbsp;</td></tr>
<?
    }
    mysql_free_result($result);
} else {
    die(mysql_error());
}
?>
	</table>
//-->
</body>
</html>
