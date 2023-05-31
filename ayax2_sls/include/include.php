<?php
require_once(dirname(__FILE__).'/cn.php');


function  getValue($zm){
	if (isset($_POST[$zm]))
		return isset($_POST[$zm]) ?  addslashes(stripslashes(trim($_POST[$zm]))) : '';
	else
		return isset($_GET[$zm]) ?  addslashes(stripslashes(trim($_GET[$zm]))) : '';
}

?>