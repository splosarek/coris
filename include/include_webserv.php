<?php
//session_start();
define('PATH_ROOT' , dirname(__FILE__).'/../');



ini_set('include_path',get_include_path().PATH_SEPARATOR .PATH_ROOT.'/lib/'.PATH_SEPARATOR.PATH_ROOT );

//include_once('include/include_storage.php');
include_once('Application_config.php');
include_once('Documents/Documents.php');
include_once('Application.php');
include_once('CorisCase.php');
include_once('UserObject.php');
include_once('lib/Finance.php');


?>