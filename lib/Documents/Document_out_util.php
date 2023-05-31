<?php




function check_email($email_to){

      	if(strpos($email_to,",") ){
      			$tmp=explode(",",$email_to);
      			for($i=0;$i<count($tmp);$i++){
      				if(!is_email($tmp[$i])){
      					echo GEN_FAX_BLE . ": <h2>".$tmp[$i]."</h2>";
      					exit;
      				}
      			}
      	}else{
      		if(!is_email($email_to)){
      			echo GEN_FAX_BLE . ":<h2>".$email_to."</h2>";
      			exit;
      		}
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


function prepareFax($nr,$content,$target){

  $fax = new Fax();
  $fax->set_destination($target);
  $fax->set_number($nr);

  $fax->setBody($content);
  $fax->set_date(date('Y-m-d H:i:s'));
  $fax->set_date_send(date('0000-00-00 00:00:00'));
  $fax->set_from_number(DEF_FAX_SENDER_NR);
  $fax->set_date(date('Y-m-d H:i:s'));
  $fax->set_date_send(date('0000-00-00 00:00:00'));

  return $fax;
}

function prepareFaxFromFile($nr,$file,$target){

  $fax = new Fax();
  $fax->set_destination($target);
  $fax->set_direction(FAX::$DIRECT_OUT);
  $fax->set_number($nr);

  $ilosc_stron = PDFTools::pdfGetParameters('Pages:',$file);
  $fax->set_page_number($ilosc_stron);

  $content = file_get_contents($file);
  $fax->setBody($content);

  $fax->set_date(date('Y-m-d H:i:s'));
  $fax->set_date_send(date('0000-00-00 00:00:00'));
  $fax->set_from_number(DEF_FAX_SENDER_NR);

  return $fax;
}


function prepareSendFax($case_id,Fax $fax,$contrahent_name='',$contrahent_to_name='',$paxname='',$reclamation=0,$target,$template_id=0){

   $fax_id = $fax->store();

  $interaction = new Interaction($case_id,0,Interaction::$_TYPE_FAX,Interaction::$DIRECTION_OUT);

  $fax->set_destination($target);
  $interaction->addDocument($fax);
  $interaction->setInteractionContact($fax->get_number());
  $interaction->setInteractionName($contrahent_to_name);
  $interaction->setInteractionSubject($paxname);
  $interaction->setReclamation($reclamation);
  $interaction->set_template_id($template_id);

  $interaction->store();
  return $interaction;

}

function prepareEmail($email_to,$email_cc,$email_temat,$email_body,$attachments,$target){


  $email_body = addslashes(stripslashes($email_body));
  $email_temat = addslashes(stripslashes($email_temat));


  $email = new Email();
  $email->set_destination($target);

  $email->set_to($email_to);

 // $email->set_from_email( ($target == 5 ? 'assistance-germany@pl.april.com' : DEF_EMAIL_SENDER) );
  //$email->set_from_email( ($target == 6 ? 'assistance-austria@pl.april.com' : DEF_EMAIL_SENDER) );

    if($target == 5)
        $email->set_from_email('assistance-germany@pl.april.com');
    else  if($target == 6) {
        $email->set_from_email('assistance-austria@pl.april.com');
    }else  if($target == 9) {
         $email->set_from_email('Barclaycard-Reiseversicherung@april.com');
         $email->set_from('Barclaycard Assistance');
    }else  if($target == 12) {
         $email->set_from_email('bestdoctors@april.com');
         $email->set_from('Best Doctors Germany');
    }else  if($target == 10) {
         $email->set_from_email('Voyage-prive.de@april.com');
         $email->set_from('Voyage-prive');
     }else  if($target == 13) {
         $email->set_from_email('hansemerkur@pl.april.com');
         $email->set_from('HanseMerkur Reiseversicherung AG');
     }else
        $email->set_from_email(DEF_EMAIL_SENDER);


  if ($email_cc != '')
  	$email->set_cc($email_cc);

  $email->setName($email_temat);
  $email->setBody($email_body);
  $email->set_body_html(1);

  $email->set_date(date('Y-m-d H:i:s'));
  $email->set_date_send(date('Y-m-d H:i:s'));

  $email->set_direction(Document::$DIRECT_OUT);

  foreach ($attachments  As $poz){
  		 $source = $poz['type'];
  		 $id = $poz['content'];
  		 $filename = $poz['filename'];

  	     $link = $source=='upload' || $source=='file'  ? 'DOC_get_content.php?id='.$id.'&source='.$source.'&action=raw' : $id;

  		parse_str(substr($link,strpos($link, '?')+1),$tmp_array);
  		$id = $tmp_array['id'];

  		$content= getContent($source,$id);

  		$tmp = explode('.',basename($filename));
		$type = Document::_mime_types($tmp[count($tmp)-1]);

		$link_src= 'DOC_get_content.php?id='.$id.'&source='.$source.'&action=view';
  	$email->addEmailAttachment($filename, $type, $content,$link_src);
  }
  return $email;
}


function merge_fax_attachment_to_pdf($dir_tmp,$attachments){

	$lista_plikow = array();

	foreach ($attachments  As $poz){
  		 $source = $poz['type'];
  		 $id = $poz['content'];
  		 $filename = $poz['filename'];

  	     $link = $source=='upload' || $source=='file'  ? 'DOC_get_content.php?id='.$id.'&source='.$source.'&action=raw' : $id;

  		parse_str(substr($link,strpos($link, '?')+1),$tmp_array);
  		$id = $tmp_array['id'];

		if ($source == 'document' ) {
				 	$doc = new Document($id);
					$type=$doc->getContentType();
		}else{
			$tmp = explode('.',basename($filename));
			$type = Document::_mime_types($tmp[count($tmp)-1]);
		}

		if (  !($type == 'application/pdf' || $type == 'PDF') ){
					echo 'Nieprawid³owy za³±cznik: '.basename($id);
					return false;
		}

		$content= getContent($source,$id);

		$tmp = tempnam($dir_tmp, "pdf");
  		rename($tmp,$tmp.'.pdf');
  		$tmp = basename($tmp.'.pdf');
  		$fs = fopen($dir_tmp.$tmp, 'wb');
  		if ($fs){
  			fwrite($fs,$content);
  			fflush($fs);
  			fclose($fs);
  			$lista_plikow[] = $dir_tmp.$tmp;
  		}else{

  			echo "ERROR file: ".$dir_tmp.$tmp;
  		}
	}

	return $lista_plikow;
}

function getContent($source,$id){

	switch ($source) {
		case 'document':
				if ($id>0){
				  	$doc = new Document($id);
				  	$content=$doc->getBody();
					$name=$doc->getName();
					$type=$doc->getContentType();
				}
		;
		break;
		case 'upload':
				$dir_tmp = DIR_TMP;
				if (file_exists($dir_tmp.basename($id))){
					$content = file_get_contents($dir_tmp.basename($id));
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
				}
		;
		break;
		case 'tmp':
				$dir_tmp = DIR_TMP;
				if (file_exists($dir_tmp.basename($id))){
					$content = file_get_contents($dir_tmp.basename($id));
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
				}else{
					echo "Error: File not found: ".$dir_tmp.basename($id);
				}
		;
		break;
		case 'file':
				$dir_tmp = '';
				if (file_exists($id)){
					$content = file_get_contents($id);
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
				}else{
					die ('File error: '.$dir_tmp.basename($id));
				}
		;
		break;

		case 'form':
				$dir_tmp = '../fax/forms/';
				if (file_exists($dir_tmp.basename($id))){
					$content = file_get_contents($dir_tmp.basename($id));
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
				}
		;
		break;


		default:
			die ('Source error: '.$source)
			;
		break;
	}
	return $content;
}

function wysylka_email($case_id,Email $email,$contrahent_to_name='',$contrahent_name='',$reclamation=0,$target,$template_id=0){

  $email_id = $email->store();

  $interaction = new Interaction($case_id,0,Interaction::$_TYPE_EMAIL,Interaction::$DIRECTION_OUT);

  $interaction->set_destination($target);
  $interaction->addDocument($email);
  $interaction->setInteractionContact($email->get_to());
  $interaction->setInteractionName($contrahent_to_name);
  $interaction->setInteractionSubject($email->getName());
  $interaction->setReclamation($reclamation);
    $interaction->set_template_id($template_id);
  //$interaction->setStatus(Document::);

  $interaction->store();
   return $interaction;
}




?>