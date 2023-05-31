<?php

function get_parameters($param,$dir,$file){
  
  if ($dir == '')
    $command = "/usr/bin/pdfinfo ".$file;      
  else 
    $command = "/usr/bin/pdfinfo ".$dir."/".$file;
    
    
  
  $ilosc = '0';
   $result = '';
        exec($command, $result);        
        
        for ($i=0;$i<count($result);$i++){
          $linia = $result[$i];
                   
          if (strpos($linia,'Pages:') === false){
          }else{
            $linia = str_replace($param,'',$linia);
            $ilosc = trim($linia);
          }
        }
  return $ilosc;
}


function dodaj_pdf_to_fax($file,$file_attach){
	
	$zalaczniki = '' ;
	 if (is_array($file_attach) && count($file_attach)>0 ){
    	   			for($i=0;$i<count($file_attach);$i++){
    	   				$zalaczniki .=   ' '.$file_attach[$i]['tmp'];
    	   			}    	   			
     }
     
      $dir = '../fax/tmp/';
  
  $tmp = tempnam($dir, "pdf");
  rename($tmp,$tmp.'.pdf');
  $tmp= basename($tmp.'.pdf');                      
    
     $command = "/usr/bin/pdftk $file $zalaczniki output $dir$tmp";    
     
     
    exec($command, $result);

  return $dir.$tmp;
  
}


function sklej_pdfy($pdf1,$pdf2){
  $dir = '../fax/tmp/';
  
  $tmp = tempnam($dir, "pdf");
  rename($tmp,$tmp.'.pdf');
  $tmp= basename($tmp.'.pdf');                      
    
     $command = "/usr/bin/pdftk $pdf1 $pdf2 output $dir$tmp";    
    exec($command, $result);
	if (file_exists($dir.$tmp) && filesize($dir.$tmp) > 0 )
  		return $dir.$tmp;
  	else 
  		return null;	
}

function load_template($file){

   if (!file_exists($file)) {echo $file;return null;}

  $fcontents = file ($file);
  $plik = implode('',$fcontents);
  return $plik;
}

function html2pdf($html){
  
  $zmiany = array('¿' => 'z', '¯' => 'Z');
  $html =  strtr($html,$zmiany );  
  
  $fax = tempnam("../fax/tmp/", "fax");    
    $fp = fopen($fax, "a");
    fputs($fp ,$html);
    fclose($fp);
    rename($fax,$fax.'.html');
    
    
    $file_input = BASE_URL.'/fax/tmp/'.basename($fax.'.html');
    $file_out= basename($fax);
     
    passthru('cd ../fax/html2ps/ && ./html2pdf.php '.$file_input.' '.$file_out,$result);   // konwersja html2pdf
    
    $file = '../fax/html2ps/out/'.$file_out.'.pdf'; // wynikowy plik pdf + sciezka
    
    if (file_exists($file) && filesize($file)>0)
      unlink($fax.'.html');
    
    if (file_exists($file))
      return $file;
    else   
      return null;  
}



function html2pdf_new($html,$footer='',$header='',$margins=  array('left' => 0,'right'  => 0,'top'    => 0,'bottom' => 0),$watermark=''){

 	//$zmiany = array('¿' => 'z', '¯' => 'Z');
  	//$html =  strtr($html,$zmiany );  
  
  	$dir_out = dirname(__FILE__)."/../tmp/";
  	
  	$fax = tempnam($dir_out, "fax");   
  	$file_out= basename($fax);
  	
  	$file = $dir_out.$file_out.'.pdf';
  	 
	
	Html2pdfConvert::convert($html, $file, DIR_TEMPLATE,$footer,$header,$margins);
	if ($watermark != '' && file_exists($watermark)){
		
		$file_tmp = PDFTools::pdfAddWatermark($dir_out,$file,$watermark);
		if ($file_tmp != ''){
				rename($file_tmp, $file);
		}
	}else{
		//echo "<br>ERROR FILE: ".$watermark;
		//exit();
		
	}
	
    if (file_exists($file)){
       unlink($fax);	
      return $file;
    }else   
      return null;  
      
}

function fax_in_tiff2pdf($fid,$tiff_file,$pdf_file){
    
          
          $command = '/usr/bin/tiff2pdf '.$tiff_file .' -o '.$pdf_file;      
          exec($command, $result);
          
          //echo "\n<br>".$command."\n<br>".$result;
          if (file_exists($pdf_file) && filesize($pdf_file)>0){      
            $query = "UPDATE coris_fax_in SET pdf_file='".basename($pdf_file)."' WHERE ID='$fid' LIMIT 1";
            $mysql_result = mysql_query($query);
            return basename($pdf_file);  
          }else{
            return false;            
          }
}

function poprawNumer($nr){
  $nr=trim($nr);
  $zmiany = array(' ' => '', '/' => '', '\\' => '','-' => '', '(' => '', ')' => '', '+' => '00');
  $nr =  strtr($nr,$zmiany );    
  return $nr;
}

function sendFax($id,$to, $message,  $nr,$save_only=0){

   

    
    $nr = $save_only ==1  ? $nr :  poprawNumer($nr);
      
    
    if ($save_only==0){
    	
    	$query = "INSERT INTO coris_fax_out_spool  SET `date`=now(),
    	ID_fax_out='$id',`to`='".addslashes(stripslashes($to))."',nr='".addslashes(stripslashes($nr))."',message_file='".addslashes(stripslashes($message))."'";
    	$mysql_result = mysql_query($query);
      	if (!$mysql_result){
          	mail('krzysiek@evernet.com.pl','CORIS',$query."\n\n".$mysql_result."\n\n".mysql_error());
      	}
      	$result = '';
    	$result2 = '';    	
    	    	
    /*	$zmiany = array('\'' => '', '"' => '');
    	$to = strtr($to,$zmiany);  
    
    	$modems=array('ttyS5','ttyS4');
    
	    $ret = rand(0,1);
	    $send_modem = $modems[$ret];
	    //$send_modem = $modems[0];
	    
	    $fax = $message;
	    $dial = "'$to'@9$nr";   
	  
	    $sendfax = "/usr/local/bin/sendfax";
	  
	    $args = "-i '$id' -h $send_modem@localhost -R  -D -n -d '$dial' $fax" ;
	    $command = "$sendfax $args";	  
	    $ret= exec($command, $result,$result2);
	  */      	
    }else{
    	$result = '';
    	$result2 = 'done';    	
    }
    zapisz_roport_fax_out($id,$message,$result,$result2,$save_only);
  
  if ($result2 == '0'){
    echo '<script> window.close() </script>';
  }else{  
    echo "<br>Ret Value: ".$result2;
    echo "<br>Result: ".print_r($result,true);
    echo '<br><input type="button" value="'.INC_ZAMKOKNO.'" Onclick="window.close()">';
  }    
}



/*
function sendFaxNew(Fax $faxObj,$save_only=0){   
    
	$nr = $faxObj->get_number();
    
    $nr = $save_only ==1  ? $nr :  poprawNumer($nr);
      
  	$to =  str_replace(' ','_',$faxObj->get_contrahent());  
    if ($save_only==0){    	
    	$query = "INSERT INTO coris.coris_fax_out_spool  SET `date`=now(),
    			ID_document='".$faxObj->getObjectID()."',
    			`to`='".addslashes(stripslashes($to))."',
    			nr='".addslashes(stripslashes($faxObj->get_number()))."',
    			message_file=null";
    	$mysql_result = mysql_query($query);
      	if (!$mysql_result){
          	mail('krzysiek@evernet.com.pl','CORIS',$query."\n\n".$mysql_result."\n\n".mysql_error());
      	}
      	$result = '';
    	$status = '0';    	    	    	     	
  
     }else{
    	$result = '';
    	$status = 'done';    	
    }
    	
    $faxObj->update_status($status);        

    //  zapisz_roport_fax_out($id,$message,$result,$result2,$save_only);
}
*/
/*
function sendFax(Fax $faxObj,$message,$save_only=0){   
    
	$nr = $faxObj->get_number();
    
    $nr = $save_only ==1  ? $nr :  poprawNumer($nr);
      
  	$to =  str_replace(' ','_',$faxObj->get_contrahent());  
    if ($save_only==0){    	
    	$query = "INSERT INTO coris.coris_fax_out_spool  SET `date`=now(),
    			ID_document='".$faxObj->getObjectID()."',
    			`to`='".addslashes(stripslashes($to))."',
    			nr='".addslashes(stripslashes($faxObj->get_number()))."',
    			message_file='".addslashes(stripslashes($message))."'";
    	$mysql_result = mysql_query($query);
      	if (!$mysql_result){
          	mail('krzysiek@evernet.com.pl','CORIS',$query."\n\n".$mysql_result."\n\n".mysql_error());
      	}
      	$result = '';
    	$status = '0';    	    	    	     	
  
     }else{
    	$result = '';
    	$status = 'done';    	
    }
    	
    $faxObj->update_status($status);        
  //  $ilosc_stron = PDFTools::pdfGetParameters('Pages:',$message);
  //  $faxObj->update_page_number($ilosc_stron);
    
  //  zapisz_roport_fax_out($id,$message,$result,$result2,$save_only);
  	
  
 // if ($result2 == '0'){
    echo '<script> window.close() </script>';
 // }else{  
  //  echo "<br>Ret Value: ".$result2;
   // echo "<br>Result: ".print_r($result,true);
    //echo '<br><input type="button" value="'.INC_ZAMKOKNO.'" Onclick="window.close()">';
  //}    
}
*/
/*
 * do skasowania po przeniesieniu funkcjonalnosci od klasy Fax
 */
function zapisz_roport_fax_out($id,$file,$result,$result2,$save_only=0){
      $ilosc_stron = get_parameters('Pages:','',$file);
      $query = "UPDATE coris_fax_out SET send_log='".addslashes(print_r($result,true))."',send_status='".addslashes($result2)."',page_number='$ilosc_stron' ".($save_only==1 ? " ,status='done'" : '' )." WHERE ID='$id' LIMIT 1";
      $mysql_result = mysql_query($query);
      if (!$mysql_result){
          mail('krzysiek@evernet.com.pl','CORIS',$query."\n\n".$mysql_result."\n\n".mysql_error());
      }
      
}

function sendEmail($id,$email_to,$email_cc,$email_temat,$email_body,$file_attach,$save_only=0 ){
  include("include/mylibmail.php");
  $email_from = EMAIL_FROM;
  
  send_html_mail($id,$email_temat,$email_body,$email_to,$email_cc,$email_from,$file_attach,$save_only);
  
  
  if ($save_only==1)
   	echo 'Mail zapisany:<br>';   
  else
      
    echo INC_MAILWYSL.':<br>';      
     echo '<br><input type="button" value="'.INC_ZAMKOKNO.'" Onclick="window.close()">';

}

function sendEmailNew2(Email $emailObject,$save_only=0 ){
  require_once("include/mylibmail.php");    
  $res = send_html_mail_new($emailObject,$save_only);
  
  return $res;
}

function sendEmailNew(Email $emailObject,$save_only=0 ){
  require_once("include/mylibmail.php");
  

  
  $res = send_html_mail_new($emailObject,$save_only);
  
  
  if ($save_only==1)
   	echo 'Mail zapisany:<br>';   
  else      
    echo INC_MAILWYSL.':<br>';

    
     echo '<br><input type="button" value="'.INC_ZAMKOKNO.'" Onclick="window.close()">';
  return $res;   

}

/*
 * do skasowania po przeniesieniu funkcjonalnosci od klasy Email
 */
function update_email_out($email_id,$param){
  $query = "UPDATE coris_email_out SET $param WHERE ID='$email_id' LIMIT 1";
  
  if (!mysql_query($query)){
    send_raport($query."\n\n".mysql_errno());
    return ;
  }
}

/*
 * do skasowania po analizie
 */
function saveEmailAttach_($email_id,$dir,$file_org,$file_name,$mime_types,$linesz){
  /*
  //email_id,$katalog,$filename_dec,$file_name_arch,$mime_types,$linesz
  $file_org = addslashes(stripslashes($file_org));
  $file_name = addslashes(stripslashes($file_name));
  
  
  $query = "INSERT INTO coris_email_out_attachment  (ID,id_email,file ,tempname ,dir ,file_type,size) VALUES ";
  $query .= " (null,'$email_id','$file_org','$file_name','$dir','$mime_types','$linesz')";
  if (!mysql_query($query)){
    send_raport($query."\n\n".mysql_errno());
    return ;
  }
      
  $query = "UPDATE coris_email_out  SET attachment=attachment+1 WHERE ID='$email_id' LIMIT 1";
  mysql_query($query);
  */
}
 

function sendPDF2Browser($file){
    @header("Content-Type: application/pdf");
    @header("Cache-Control: private");
    @header("Content-Disposition: inline; filename=".$file);
    @header("Content-Length: " . filesize($file));
       $fp = fopen($file, 'rb');  
       fpassthru($fp);  
       fclose($fp)  ;
}

function check_out_dir(){

    $year = date('Y');
       $month = date('m');
       
        if (!file_exists('../fax/out/'.$year)){
          mkdir('../fax/out/'.$year);
          mkdir('../fax/out/'.$year.'/'.$month);
          mkdir('../fax/out/'.$year.'/'.$month.'/pdf');
        }else if (!file_exists('../fax/out/'.$year.'/'.$month)){
          mkdir('../fax/out/'.$year.'/'.$month);
          mkdir('../fax/out/'.$year.'/'.$month.'/pdf');
        }
        if (file_exists('../fax/out/'.$year.'/'.$month.'/pdf')){
          return $year.'/'.$month;
        }else{
          return '';
        }
        
}


function check_in_dir(){

    $year = date('Y');
       $month = date('m');
       
        if (!file_exists('../fax/in/'.$year)){
          mkdir('../fax/in/'.$year);
          mkdir('../fax/in/'.$year.'/'.$month);
          mkdir('../fax/in/'.$year.'/'.$month.'/pdf');
        }else if (!file_exists('../fax/in/'.$year.'/'.$month)){
          mkdir('../fax/in/'.$year.'/'.$month);
          mkdir('../fax/in/'.$year.'/'.$month.'/pdf');
        }
        if (file_exists('../fax/in/'.$year.'/'.$month.'/pdf')){
          return $year.'/'.$month;
        }else{
          return '';
        }
                
}

function send_raport($txt){
  mail("krzysiek@evernet.com.pl","SYstem coris  mail: ",$txt);
}
?>
