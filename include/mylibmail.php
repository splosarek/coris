<?

function send_plain_mail($temat,$tresc,$email_to_lista,$email_from){
    $charset = 'iso-8859-2';

    $body = $tresc;
    $z=0;

    $temat = przyg_temat($temat,$charset);

    for ($i=0;$i<count($email_to_lista)  && $z<count($email_to_lista) ;$i++){
         $tab=array();
         for($j=0;$j<35 && $z<count($email_to_lista) ;$j++){
                    $tab[] = $email_to_lista[$z];
                    $z++;
         }
        // echo "<br><br><br>lista:<br>".implode( ", ", $tab );
        $naglowek = przyg_naglowek("text/plain; charset=$charset",'8bit',$email_from,implode( ", ", $tab ));
        @mail( $email_from, $temat, $body, $naglowek,"-f$email_from");
     }
     return count($email_to_lista);
}

function send_html_mail($email_id,$temat,$tresc,$email_to_lista,$email_cc_lista,$email_from,$file_attach,$save_only=0){
      $charset = 'iso-8859-2';        
      $z=0;    
	  $temat = przyg_temat($temat,$charset);
    
	  if (!(is_array($file_attach) && count($file_attach)>0)){ //bez zalacznika
       	 $naglowek = przyg_naglowek("text/html; charset=$charset",'8bit',$email_from,$email_to_lista,$email_cc_lista);
       	 //$body = nl2br($tresc);
       	 $body = $tresc;
	  }else{ //  z zalacznikiem
	  	
	  	   $boundary= "----=" ."_0_". md5( uniqid("myboundary") );;  
	  	    $naglowek = przyg_naglowek("multipart/mixed;\n boundary=\"$boundary\"",$charset,$email_from,$email_to_lista,$email_cc_lista);
    	   $zal_txt = przyg_tresc($tresc,'text/html',$boundary,$charset);  // przygotowanie tresci
    	   
    	   $zalaczniki = "";
    	   if (is_array($file_attach) && count($file_attach)>0 ){
    	   			for($i=0;$i<count($file_attach);$i++){
    	   				$zalaczniki .= add_zalacznik($email_id,$file_attach[$i],$boundary);
    	   			}
    	   }else{
					$zalaczniki .= add_zalacznik($email_id,$file_attach,$boundary);
    	   }
    	   
    	    $body = "This is a multi-part message in MIME format.\n\n--$boundary\n";
    		$body .= $zal_txt;
    		$body .= $zalaczniki;
    		$body .=  "\n--$boundary--\n"; 
			
    		
	  }
        
	  
	 if ($save_only==1)
	 	$res = 1;
	 else
       	$res =  mail($email_to_lista, $temat, $body, $naglowek,"-f$email_from");
     	
       update_email_out($email_id,'size=\''.strlen($body).'\', status=\''.$res.'\'');
       if ($res){
     		return;    
		}else{
     			echo "Error sending email";
     			exit();
     	}	
}

function add_zalacznik(Document $attach,$boundary){
	$zal = "";
	$ctype = "";
	//if (is_array($file_attach)){
	//	if (file_exists($file_attach['tmp'])){
			  	//	if ($file_attach['filename'] == '') $file_attach['file'] = basename($file_attach['tmp']);
			  	//	if ($file_attach['ctype'] != '') $ctype=$file_attach['ctype'];
			  		
			   // 	$linesz= filesize( $file_attach['tmp'] )+1;
    		   	//	$fp= fopen( $file_attach['tmp'], 'r' );
    		   	//	if ($fp){
    		   			
    		   		//	$katalog = check_dir_mail('out');
    		   		//	$katalog_dest = MAIL_SPOOL.'out/'.$katalog;
    		   		//	$mime_types="";
    		   		//	$pos = strrpos($file_attach['tmp'],".");
    		   		//	if ($pos === false || $ctype != '') {
    		   		//		$mime_types = $ctype;
    		   		//	}else{
    		   		//		$mime_types = check_mime(substr($file_attach['filename'],$pos+1));
    		   		//	}
    		   			$mime_types = $attach -> getContentType();    		   			    		   			        					    		   		
    		   			$file_content =  $attach->getBody();
    		   			
    		   			$zal =  zalacznik($file_content,$mime_types,'',$attach->getName(),$boundary);
    		   			
    		   		/*	if ($zal<>''){
        					$filename_dec = correct_file_name(basename($file_attach['filename']));
        					list($usec, $sec) = explode(" ",microtime()); 
							$tmp =  substr(intval(((float)$usec + (float)$sec)*100000),4); 
        					$file_name_arch = $email_id.'_'.$tmp.'_'.$filename_dec;

    		   				if (!copy($file_attach['tmp'],$katalog_dest.$file_name_arch)){
    		   					send_raport("Error copy file: from $file_attach to $katalog_dest.$file_name_arch email_id: $email_id");
    		   				}    		   			
        					saveEmailAttach($email_id,$katalog,$filename_dec,$file_name_arch,$mime_types,$linesz-1);
    		   			}
    		   			
    		   		}*/
			
		//}
	
//	}else{
  /*  	    	if (file_exists($file_attach)){
    	       		$linesz= filesize( $file_attach )+1;
    		   		$fp= fopen( $file_attach, 'r' );
    		   		if ($fp){
    		   			
    		   			$katalog = check_dir_mail('out');
    		   			$katalog_dest = MAIL_SPOOL.'out/'.$katalog;
    		   			$mime_types="";
    		   			$pos = strrpos($file_attach,".");
    		   			if ($pos === false) {
    		   			}else{
    		   				$mime_types = check_mime(substr($file_attach,$pos+1));
    		   			}
    		   			    		   			        					    		   			
    		   			$file_content =  fread( $fp, $linesz);
    		   			$zal =  zalacznik($file_content,$mime_types,"",basename($file_attach),$boundary);
    		   			
    		   			if ($zal<>''){
        					$filename_dec = basename($file_attach);
        					list($usec, $sec) = explode(" ",microtime()); 
							$tmp =  substr(intval(((float)$usec + (float)$sec)*100000),4); 
        					$file_name_arch = $email_id.'_'.$tmp.'_'.$filename_dec;

    		   				if (!copy($file_attach,$katalog_dest.$file_name_arch)){
    		   					send_raport("Error copy file: from $file_attach to $katalog_dest.$file_name_arch email_id: $email_id");
    		   				}    		   			
        					saveEmailAttach($email_id,$katalog,$filename_dec,$file_name_arch,$mime_types,$linesz-1);
    		   			}
    		   			
    		   		}
    	    	}
	}
	*/
    	    	return $zal;
}

function check_mime($ext){
	$result = "application/octet-stream";
	
	$query = "SELECT name FROM coris_email_mimetypes WHERE ext='$ext'";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)>0){
		$row = mysql_fetch_array($mysql_result);
		$result = $row[0];
	}
		
	return $result;

}
function przyg_temat($temat,$charset){
  return "=?$charset?Q?".str_replace("+","_",str_replace("%","=",urlencode($temat)))."?=";
}

function przyg_naglowek($content_type,$enc,$mail_from,$mail_to,$mail_cc){
         $headers = "MIME-Version: 1.0\n";
         $headers .= "Content-type: $content_type\n";
         $headers .= "Content-Transfer-Encoding: $enc\n";
         $headers .= "From: $mail_from\n";
         if ($mail_cc != "")
         	$headers .= "CC: $mail_cc\n";
         	         
         //$headers .= "Bcc: $mail_to\n";
         
         $headers .= "Reply-To: $mail_from\n";
//         $headers .= "Return-path: $mail_from <$mail_from>\n";
         $headers .= "X-Mailer: Evernet Mailer ver 1.2c\n";

         return $headers;

}



function send($email_from, $temat, $body, $naglowek,$return){
   return @mail( $email_from, $temat, $body, $naglowek,$return );
}

function zalaczniki($dir,$images,$fID,$boundary){
  $ata= array();
  $k=0;

  $sep= chr(13) . chr(10);  
  // for each attached file, do...
  for( $i=0; $i < count( $images); $i++ ) {
    
    $filename = $images[$i];
    $basename = basename($dir.$filename);
    $ctype = 'image/gif';  // content-type
    $disposition = 'inline';
    
    if( ! file_exists( $dir.$filename) ) {
      echo "Class Mail, method attach : file $filename can't be found"; //exit;
    }

      $contentID = $fID[$i];

    $subhdr= "--$boundary\nContent-type: $ctype;  name=\"$basename\"\nContent-Transfer-Encoding: base64\nContent-ID: <$contentID>\n\n\n";//Content-Disposition: inline;\n filename=\"$basename\"
//    Content-Disposition: inline;  filename="lasketchup3_.jpg"
    $ata[$k++] = $subhdr;
    // non encoded line length
    $linesz= filesize( $dir.$filename )+1;
    $fp= fopen( $dir.$filename, 'r' );
    $ata[$k++] = chunk_split(base64_encode(fread( $fp, $linesz)));
    fclose($fp);
  }

  return implode($sep, $ata);
}

				
function zalacznik($tresc,$ctype,$charset,$name,$boundary){
  $ata= array();
  $k=0;

  $sep= chr(13) . chr(10);  

   $subhdr= "--$boundary\nContent-type: $ctype;  ";
   if ($charset<>"")
   	$subhdr .="charset=\"$charset\"";   
   $subhdr .="\nContent-Transfer-Encoding: base64\nContent-Disposition: attachment; \n filename=\"$name\"\n\n";//Content-Disposition: inline;\n filename=\"$basename\"
   
    $ata[] = $subhdr; 
    $ata[] = chunk_split(base64_encode($tresc));    

  return  implode($sep, $ata);
}


function przyg_tresc($body,$enc,$boundary,$charset){

 if ($enc=='base64'){
    $bodyx = chunk_split(base64_encode($body));
 }else  if ($enc=='quoted-printable'){
      ;
 }else
     $bodyx = $body;

  $fullBody = '';

  if ( ($enc=='') && ($charset=='') ){
     $fullBody .=  $bodyx  ;
  }else{
     $fullBody .= "Content-Type: text/html;\n charset=\"$charset\"\n";
     $fullBody .= "Content-Transfer-Encoding: $enc\n\n" . $bodyx ."\n";
  }

  return $fullBody;
}

function is_email1($string){
    $string = trim($string);
    $ret = ereg(
                '^([A-Za-z0-9_]|\\-|\\.)+'.
                '@'.
                '(([A-Za-z0-9_]|\\-)+\\.)+'.
                '[A-Za-z]{2,10}$',
                $string);
    return($ret);
}


function check_dir_mail($name){  	
		$dir1 = date("Y");
		$dir2 = date("m");
		$dir3 = date("d");
		$lok = MAIL_SPOOL.'/'.$name;
		
        if (!file_exists($lok.'/'.$dir1)){
          mkdir($lok.'/'.$dir1);
          mkdir($lok.'/'.$dir1.'/'.$dir2);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/attach');
        }else if (!file_exists($lok.'/'.$dir1.'/'.$dir2)){
          mkdir($lok.'/'.$dir1.'/'.$dir2);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/attach');
        }else if (!file_exists($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3)){          
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/attach');
        }
        
        if (file_exists($lok.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/attach')){
          return $dir1.'/'.$dir2.'/'.$dir3.'/';
        }else{
          return '';
        }      
}



function is_email($string){
    $string = trim($string);
    $ret = ereg(
                '^([A-Za-z0-9_]|\\-|\\.)+'.
                '@'.
                '(([A_Za-z0-9_]|\\-)+\\.)+'.
                '[A-Za-z]{2,10}$',
                $string);
    return($ret);
}
function send_html_mail_new(Email $emailObject,$save_only){
      $charset = 'iso-8859-2';        
      $z=0;    
      
      $temat = $emailObject->getName();
	  $temat = przyg_temat($temat,$charset);
    
	  $file_attach = $emailObject->getAttchments()->get_list();
	  if (!( count($file_attach)>0)){ //bez zalacznika
       	 $naglowek = przyg_naglowek("text/html; charset=$charset",'8bit',$emailObject->get_from_email(),$emailObject->get_to(),$emailObject->get_cc());
       	 //$body = nl2br($tresc);
       	 $body = $emailObject->getBody();
	  }else{ //  z zalacznikiem	  	
	   	   $boundary= "----=" ."_0_". md5( uniqid("myboundary") );;  
	  	    $naglowek = przyg_naglowek("multipart/mixed;\n boundary=\"$boundary\"",$charset,$emailObject->get_from_email(),$emailObject->get_to(),$emailObject->get_cc());    	
  	       $zal_txt = przyg_tresc($emailObject->getBody(),'text/html',$boundary,$charset);  // przygotowanie tresci
    	   
    	   $zalaczniki = "";
    	   if (is_array($file_attach) && count($file_attach)>0 ){
//    	   			for($i=0;$i<count($file_attach);$i++){
    	   			foreach($file_attach As $attach){
    	   				$zalaczniki .= add_zalacznik($attach,$boundary);
    	   			}
    	   }else{
					$zalaczniki .= add_zalacznik($attach,$boundary);
    	   }
    	   
    	    $body = "This is a multi-part message in MIME format.\n\n--$boundary\n";
    		$body .= $zal_txt;
    		$body .= $zalaczniki;
    		$body .=  "\n--$boundary--\n"; 			
	  }
        
	  $email_to_lista = $emailObject->get_to();
	  $email_from = $emailObject->get_from_email();
	
	  if ($save_only){
	  			$res=2;
	  }else{
       			$res =  mail($email_to_lista, $temat, $body, $naglowek,"-f$email_from");
	  }
     	
      $emailObject->update_status($res);
      $emailObject->update_size(count($file_attach));   
	  return $res;    
}

?>