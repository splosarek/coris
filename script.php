<?php

define('TMP_DIR','tmp/');

$result = array();

$result['time'] = date('r');
$result['addr'] = substr_replace(gethostbyaddr($_SERVER['REMOTE_ADDR']), '******', 0, 6);
$result['agent'] = $_SERVER['HTTP_USER_AGENT'];

if (count($_GET)) {
	$result['get'] = $_GET;
}
if (count($_POST)) {
	$result['post'] = $_POST;
}
if (count($_FILES)) {
	$result['files'] = $_FILES;
}

// Validation

$error = false;

if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
	$error = 'Invalid Upload';
}


if ($error) {
	$return = array(
		'status' => '0',
		'error' => $error
	);
} else {  	
	$fname_org = $_FILES['Filedata']['name'];
	$fname = correct_file_name($_FILES['Filedata']['name']);
	$fname = move_file(TMP_DIR,$_FILES['Filedata']['tmp_name'],$fname);
	 
	$return = array(
		'status' => '0',
		'name' => $fname_org,
		'link' => $fname,
		'error' => 'dddd'
	);		
}




$log = @fopen('script.log', 'a');
if ($log) {
  fputs($log, print_r($return, true) . "\n---\n");
  fclose($log);
}


if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
	// header('Content-type: text/xml');

	// Really dirty, use DOM and CDATA section!
	echo '<response>';
	foreach ($return as $key => $value) {
		//echo "<$key><![CDATA[$value]]></$key>";
	}
	echo '</response>';
} else {
	// header('Content-type: application/json');

//	echo json_encode(array());
	echo json_encode($return);
}



function correct_file_name($name){
	$name = iconv('UTF-8','ISO-8859-2//IGNORE',$name);
  $name=trim($name);
	
  $zmiany = array(' ' => '', '/' => '', '\\' => '','-' => '', '(' => '', ')' => '', '+' => '');
  $trans_win_iso = array(chr(185) => chr(177),chr(165) => chr(161),chr(159) => chr(188),chr(143) => chr(172),chr(156) => chr(182),chr(140) => chr(166));
  $trans_iso_to_ang_lower = array(chr(177) => chr(97), chr(161) => chr(97 ),chr(175) => chr(122),chr(191)=>chr(122),chr(188)=>chr(122),chr(172)=>chr(122),chr(182)=>chr(115), chr(166)=>chr(115),chr(234)=>chr(101),chr(202)=>chr(101),chr(230)=>chr(99),chr(198)=>chr(99),chr(243)=>chr(111),chr(211)=>chr(111),chr(179)=>chr(108),chr(163)=>chr(108),chr(241)=>chr(110),chr(209)=>chr(110));
  
  $name =  strtr($name,$zmiany );    
  
  $name = strtr($name,$trans_win_iso);
  $name = strtr($name,$trans_iso_to_ang_lower);
  return $name;
}


function move_file($dir,$uploadFile,$newName){

      $fileNameOrg = $newName;
      $fileName_ = ''.$newName;
      $fileName = $dir.'/'.$newName;

       $i=0;
       while ( file_exists($fileName) ){ // spradzanie czy taki plik juz istnieje jesli tak to zmiana nazwy
         $poz = strpos($fileNameOrg,strrchr($fileNameOrg,'.'));
         $fileName_ = substr($fileNameOrg,0,$poz).'_'.$i.'.'.substr($fileNameOrg,$poz+1,strlen($fileNameOrg));
         $fileName = $dir.'/'.$fileName_;
         $i++;
       }

       move_uploaded_file($uploadFile, $fileName );
       return $fileName_;
}

?>