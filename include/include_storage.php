<?php
require_once('include/cn.php');

session_start();

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

if (empty($_SESSION['session_id'])){
	$lista = array('index.php','GEN_info_frame.php');	
	$script = basename($_SERVER['SCRIPT_FILENAME']);	
	if (!in_array($script,$lista))
		die ('Blad sesji, prosze ponownie sie zalogowac: <a href="index.php" target="_top">logowanie</a>')			;		
}

function setstring () {
	$defaultlanguage = "pl";
	
	if (isset($_GET['lang']))
		$_SESSION['lang'] = $_GET['lang'];
	
	$lang = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : $defaultlanguage;
	$args = func_get_args();
	
	if (count($args)) {
		if (in_array($lang, $args)) {
			for ($i = 0; $i < count ($args); $i++) {
				if ($args[$i] == $lang) {
					return $args[$i + 1];
				}
			}
		}
	}
	return "";
}

function html_start($title='',$body_param=''){

echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>'.$title.'</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="Scripts/javascript.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>
<script type="text/javascript" src="Scripts/Fx.ProgressBar.js"></script>
<script type="text/javascript" src="Scripts/Swiff.Uploader.js"></script>
<script type="text/javascript" src="Scripts/FancyUpload3.Attach.js"></script>

<script type="text/javascript" src="Scripts/script.js"></script>



<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<style type="text/css">
<!--
.style4 {color: #009966}
-->
</style>
</head>

<body leftmargin="0" topmargin="0" '.$body_param.'>';
}

function html_start_utf($title='',$body_param=''){

echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>'.$title.'</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="javascript" src="Scripts/javascript.js"></script>
<script language="javascript" src="Scripts/mootools.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
<style type="text/css">
<!--
.style4 {color: #009966}
-->
</style>
</head>

<body leftmargin="0" topmargin="0" '.$body_param.'>';
}


function html_stop2(){
	echo '</body>
</html>';
}


function  getValue($zm){
  if (isset($_POST[$zm])){
     if (is_array($_POST[$zm]))
       return $_POST[$zm];
     else
       return isset($_POST[$zm]) ?  addslashes(stripslashes(trim($_POST[$zm]))) : '';
  }else{      
     if (isset($_GET[$zm]) && is_array($_GET[$zm]))     
       return $_GET[$zm];
     else
       return isset($_GET[$zm]) ?  addslashes(stripslashes(trim($_GET[$zm]))) : '';  
  }
}
?>