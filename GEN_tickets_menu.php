<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
		<title>Zgłoszenia</title>
		<link href="Styles/general.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<form name="form1" onSubmit="parent.frame.document.location='GEN_tickets_frame.php?action=search&searched_text='+ form1.searched_text.value; return false;">
			<table width="100%"  border="0" cellspacing="2" cellpadding="2">
				<tr>
					<td class="popupTitle" width="40px" align="center">
						<input tabindex="-1" accesskey="n" type="button" value="Nowe" onclick="parent.frame.document.location='GEN_tickets_add.php?offset='+parent.frame.document.body.scrollTop">
					</td>
					<td class="popupTitle">Zgłoszenia&nbsp;</td>
				</tr>
			</table>
		</form>
	</body>
</html>
