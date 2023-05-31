<?php

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
?>