<?php
session_start();


$dir_base= dirname(__FILE__).'/../';
ini_set('include_path',get_include_path().PATH_SEPARATOR .$dir_base.'lib/'.PATH_SEPARATOR.$dir_base );

include_once('include/include_storage.php');


include_once('Application_config.php');
include_once('Application.php');
include_once('Branch.php');
include_once('CorisCase.php');
include_once('ICD10.php');
include_once('UserObject.php');
include_once('Finance.php');
include_once('Documents.php');
?>