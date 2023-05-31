<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>FAX TEST</title>
</head>

<body>
<?php 


if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
	$nr = trim($_POST['nr']);
	
	if ($nr!= ''){
		echo "<br>Wysylanie na nr: ".$nr;	
		$fax_file = 'test.pdf';
		send_fax(1,'assistanceDE','cl',$nr,$fax_file);
	}else{
		echo "<br>Błędny numer";		
	}
}

?>

<br><br>
<form method="post">
Nr faksu: <input type="text" size="20" name="nr" value="<?php echo $nr; ?>">  &nbsp;&nbsp;&nbsp;<input type="submit" calue="wyslij">

</form>


</body>

<?php 

function send_fax($id,$sender,$to,$nr,$fax_file){

	
		$zmiany = array('\'' => '', '"' => '');
    	$to = strtr($to,$zmiany);  
    
    	if ($sender == 'assistanceDE'){    		
	    	$send_modem = 'ttyIAX8';
	    	$dial = "'$to'@56$nr";    		
    	}else{
	    	$send_modem = getModems($sender);
	    	$dial = "'$to'@9$nr";
    	}   
	  
	  //  $sendfax = "/usr/local/bin/sendfax";
	  
	    $args = "-i 'srv$id' -h $send_modem@localhost -R  -D -n -d '$dial' $fax_file" ;
	    $command = "$sendfax $args";	  
	    usleep(200000);
	    $ret= exec($command, $result,$result2);
	    
	    echo nl2br(print_r($result,1));
	    echo nl2br(print_r($result2,1));
	    
	// echo "\n<br>".$command;	    
    //	zapisz_roport_fax_out($id,$fax_file,$result,$result2,$save_only);
    	
    	return true;
}

?>