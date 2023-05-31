<?php include('include/include.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
	<body bgcolor="#eeeeee">
		<style>
			body {
				margin-top: 0.1cm;
				margin-bottom: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
			}
			a:link, a:active, a:visited {
				color: #6699cc;
			}
		</style>
<?php
if (isset($_GET['case_id'])) {

	if (isset($_GET['action']) && $_GET['action'] == "remove") {
        $decisions = split(",", $_GET['decision_id']);
        foreach ($decisions as $decision_id) {
			if ($decision_id != "") {
				$query = "UPDATE coris_assistance_cases_decisions SET active = 0 WHERE decision_id = '$decision_id'";
                if (!$result = mysql_query($query, $cn))
                   die (mysql_error());
            }
        }
	}

	$query = "SELECT acd.decision_id, acd.type_id, acd.amount, acd.currency_id, acd.decision_date, acd.note, acd.date, acdt.value, u.name, u.surname FROM coris_assistance_cases_decisions acd, coris_users u, coris_assistance_cases_decisions_types acdt WHERE case_id = '$_GET[case_id]' AND acd.user_id = u.user_id AND acd.type_id = acdt.type_id AND acd.active = 1 ORDER BY decision_date DESC";
}

if ($result = mysql_query($query, $cn)) {
?>
		<script language="JavaScript1.2">
		<!--
            function openWindow(winId, id) {
                var url = "AS_cases_details.php?case_id=" + id;
                winId = window.open(url, winId, 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=598,height=530, left='+ (screen.availWidth - 598) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 530) / 2);
                if (false == winId.closed)
                    winId.focus();
            }
		//-->
		</script>
		<table width="100%" cellpadding="2" cellspacing="1" border="0">
			<form name="form1" onsubmit="return false;">
<?php
	$i = 1;
	while ($row = mysql_fetch_array($result)) {
?>
			<tr bgcolor="<?php echo ($i % 2) ? "#eeeeee" : "#e0e0e0" ?>" style="cursor: default" title="<?php echo "Data: $row[date]\nWprowadzający: $row[name]  $row[surname]" ?>">
				<td width="5%" align="center"><input style="background: lightyellow" type="checkbox" name="decisioncheck" value="<?php echo $row['decision_id'] ?>"></td>
				<td width="24%" align="center"><font color="#6699cc"><?php echo $row['value'] ?></font></td>
				<td width="20%" align="right"><font color="navy"><?php echo str_replace(".", ",", $row['amount']) ?></font></td>
				<td width="6%"><?php echo $row['currency_id'] ?></td>
				<td width="30%" align="center"><?php echo $row['decision_date'] ?></td>
				<td width="15%" align="center"><font color="#6699cc"><?php echo $row['name'] . "<BR>" . $row['surname'] ?></font></td>
			</tr>
			<tr>
				<td bgcolor="lightyellow" colspan="6"><font color="#999999"><i><small><?php echo $row['note'] ?></small></i></td>
			</tr>
<?php
		$i++;
	}
?>
			</form>
		</table>
<?php
	mysql_free_result($result);
} else {
	die (mysql_error());
}
?>
	</body>
</html>

