<?php
require_once('include/cn.php');
include_once ("include/setstring.php");
include_once('include/main.php'); // funkcje pomocnicze


include_once('lib/Application_config.php');
include_once('lib/Application.php');
include_once('lib/CorisCase.php');
include_once('lib/Branch.php');
include_once('lib/UserObject.php');
include_once('lib/Finance.php');
include_once('lib/Documents.php');
include_once('lib/ICD10.php');




session_start();
//include_once('include/include_ayax2.php'); // funkcje pomocnicze

if (empty($_SESSION['GUI_language'])){
	$_SESSION['GUI_language']='pl'	;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && getValue('ch_language') != '' ){
	$_SESSION['GUI_language'] = getValue('ch_language');
}


if ($_SESSION['GUI_language']=='en'){
	require_once(dirname(__FILE__).'/../../Locales/en.php');
}else{	
	require_once(dirname(__FILE__).'/../../Locales/pl.php');
		
}



include_once('include/strona.php');




if (empty($_SESSION['session_id'])){
	$lista = array('index.php','GEN_info_frame.php');	
	$script = basename($_SERVER['SCRIPT_FILENAME']);	
	if (!in_array($script,$lista))
		die ('Blad sesji, prosze ponownie sie zalogowac: <a href="index.php" target="_top">logowanie</a>')			;
		
}

function check_admin(){
	$admin_deport = array(4,76,118);
	return in_array($_SESSION['user_id'],$admin_deport) ? true : false;
}

?>