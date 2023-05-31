<?php include('include/include.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= @CONTRAHENTS ?></title>
</head>

<frameset rows="97,*" cols="*">
	<frame name="contrahents_select_menu" src="GEN_contrahents_select_menu.php?fax=<?php echo getValue('fax');?>&branch_id=<?php echo getValue('branch_id');?>" noresize>
	<frame name="contrahents_select_frame" src="GEN_contrahents_select_frame.php?fax=<?php echo getValue('fax');?>&branch_id=<?php echo getValue('branch_id');?>" noresize>
</frameset>
<noframes><body>
</body></noframes>
</html>
