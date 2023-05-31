<?php

ini_set('display_errors',false);
ini_set('display_startup_errors',false);


$result = array();
$log_content='';
$result['time'] = date('r');

$object_id=addslashes(stripslashes(trim(@$_GET['object_id'])));
$object_type=addslashes(stripslashes(trim(@$_GET['object_type'])));
	

try{
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
		
			$return = array(
				'status' => '1',
				'name' => $_FILES['Filedata']['name']
			);			
			$info = @getimagesize($_FILES['Filedata']['tmp_name']);	
			if ($info) {
				$return['width'] = $info[0];
				$return['height'] = $info[1];
				$return['mime'] = $info['mime'];
			}
			
			if (is_uploaded_file($_FILES['Filedata']['tmp_name'])){	
					include('include/fotog.php');
					
					$fg = new FotoGalery();
					
					if ($object_type=='event'){
					
		     				$fg->setSizeMini(180,150);
		     				$fg->setSizeMax(560,450);     				
		     				$dir = 'event';         			        	
					}else{				
			 				$fg->setSizeMini(150,500);
		     				$fg->setSizeMax(800,800);
		     				$dir = 'artist';         			  	
					}
			 	$foto1 = $fg->dodaj_plik('Filedata','','../images/'.$dir.'/'); 
		         	if ($foto1[0] == 0 ){			         		
					         		$error = $foto1[1];
					         		$log_content =  "\nError image (1) ".$error;
		         	}else{
					         		$return['src'] = '/images/'.$dir.'/m/' .$foto1[1];
		 			 				$return['link'] = '/images/'.$dir.'/m/' .$foto1[1];
		 			 				$return['img_name'] = $foto1[1];
					         		$foto = $foto1[1];			         		
		         	} 			 
			}else{
				$log_content = "\nError file (1) ".$_FILES['Filedata']['tmp_name'];
			}
		}
		
		$result = "\n\nobject_type=".$object_type."\nobject_id=".$object_id."\n".$log_content;
		file_log($result);
		
		if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
			// header('Content-type: text/xml');
	
			echo '<response>';
			foreach ($return as $key => $value) {
				echo "<$key><![CDATA[$value]]></$key>";
			}
			echo '</response>';
		} else {
			// header('Content-type: application/json');
			//echo json_encode($return);
			echo arr2json($return);
		}
}catch(Exception $e){
		file_log($e->getMessage()."\n".$e->getTraceAsString());
		echo arr2json(array(	'status' => '0',
				'error' => $e->getMessage() ));
}
		function file_log($txt){
			if (file_exists('log/script.log') && filesize('log/script.log') > 102400) {
				unlink('log/script.log');
			}

			$log = @fopen('log/script.log', 'a');
			if ($log) {								
				$raport = date("Y-m-d H:i:s");
				fputs($log, $raport."\n".print_r($txt, true) . "\n---\n");
				fclose($log);
			}
		}

	function arr2json($arr){
        foreach($arr as $k=>&$val) $json[] = '"'.$k.'":'.php2js($val);
        if(count($json) > 0) return '{'.implode(',', $json).'}';
        else return '';
    }
    
    function php2js($val){
        if(is_array($val)) return arr2json($val);
        if(is_string($val)) return '"'.addslashes($val).'"';
        if(is_bool($val)) return 'Boolean('.(int) $val.')';
        if(is_null($val)) return '""';
        return $val;
    }

    
?>