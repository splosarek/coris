<?php
//

function html_start($title='',$body_param=''){

echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>'.$title.'</title>
<link href="Styles/general.css?20130624" rel="stylesheet" type="text/css">


<script type="text/javascript" language="javascript" src="Scripts/javascript.js?201705041"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>
<script type="text/javascript" src="Scripts/Fx.ProgressBar.js"></script>
<script type="text/javascript" src="Scripts/Swiff.Uploader.js"></script>
<script type="text/javascript" src="Scripts/FancyUpload3.Attach.js"></script>
    <script type="text/javascript" src="Scripts/mocha.js"></script>
    <!--<script type="text/javascript" src="Scripts/mocha-init.js"></script>-->
<script type="text/javascript" src="Scripts/script.js?20170412"></script>





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
<link href="Styles/general.css?20130624" rel="stylesheet" type="text/css">
<script language="javascript" src="Scripts/javascript.js?20130624"></script>
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
?>