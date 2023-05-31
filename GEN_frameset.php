<?php
require_once('include/include.php'); 
	if (empty($_SESSION['session_id'])){
		header("Location: index.php?session_error=1");
		exit();
	
	}
 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <title><?= GEN_FR_CORSYST  ?></title>
        <script src="https://kit.fontawesome.com/032bf0d93e.js" crossorigin="anonymous"></script>
    </head>
    <frameset rows="40,*,22,70">
    	<frame HIDEFOCUS name="top" noresize src="GEN_menu_top.php" frameborder=0 scrolling="no" marginwidth=10 marginheight=4>
    	<frameset cols="155,*" name="group">
    	    <frame HIDEFOCUS name="menu" src="GEN_menu_left.php" frameborder=0 scrolling="auto" marginwidth=5 marginheight=5>
	        <frame HIDEFOCUS name="main" src="AS_cases.php" frameborder=0 scrolling="auto">
    	</frameset>
        <frame HIDEFOCUS name="info_menu" src="GEN_info_menu.php" noresize frameborder="0" scrolling="no">
        <frame HIDEFOCUS name="info" src="GEN_info_frame.php" noresize frameborder="0" scrolling="auto">
    	<noframes>
    	    <?= GEN_FR_FRAM ?>
    	</noframes>
    </frameset>
</html>
