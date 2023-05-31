<?php

include('include/include_ayax.php');


$id = getValue('id');
$source = getValue('source');
$action = getValue('action');

$content='';
$name='';
$type='';

if ($id != '' ){
	switch ($source) {
		case 'document':
				if ($id>0){
				  	$doc = new Document($id);

				  	$content = '';
					if ($doc->get_document_type() == 4 ){ // Email
				  			$tmp =  add_email_content($id);
				  			$content .= $tmp['content'];
				  			$name .= $tmp['name'];
				  			$type .= $tmp['type'];
				  	}if ($doc->get_document_type() == 6 ){ // SMS
				  			$tmp =  add_sms_content($id);
				  			$content .= $tmp['content'];
				  			$name .= $tmp['name'];
				  			$type .= $tmp['type'];
				  	}else{
				  		$content .= $doc->getBody();
				  		$name=$doc->getName();
						$type=$doc->getContentType();
				  	}

				}
		;
		break;
		case 'upload':
				$dir_tmp = 'tmp/';
				if (file_exists($dir_tmp.basename($id))){
					$content = file_get_contents($dir_tmp.basename($id));
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
				}
		;
		break;
		case 'tmp':
				$dir_tmp = 'tmp/';
				if (file_exists($dir_tmp.basename($id))){
					$content = file_get_contents($dir_tmp.basename($id));
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
				}
		;
		break;
		case 'file':
				$dir_tmp = '';
				if (file_exists($id)){
					$content = file_get_contents($dir_tmp.basename($id));
					$name = basename($id);

					$tmp = explode('.',basename($id));
					$type = Document::_mime_types($tmp[count($tmp)-1]);
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
	if ($action != '' && $content !='' )
	 		sendContent($action,$content,$name,$type);
}

function sendContent($action,$content,$name,$type){
	if ($action=='raw'){

	}else{
			  @header("Pragma: public");
		      @header("Expires: 0");
		      @header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		      @header("Cache-Control: public");

		      if ($action=='download'){
		      	 @header("Content-type: ".$type);
		      	 @header("Content-Description: File Transfer");
		      	 @header("Content-Transfer-Encoding: chunked");
		      	 @header("Content-Disposition: attachment; filename=".$name);

		      }else	{
		   		 @header("Content-type: ".$type);
		      	 @header("Content-Disposition: inline; filename=".$name);

		      }
		      @header("Content-Length: ". strlen($content));
	}
		      echo $content;
}


function add_sms_content($id){

	$sms= new SMS($id);




 $naglowek = '------------------- '.$sms->getTypeName().' -------------------
Data:  '.$sms->getCreateDate().'
';

 if ($sms->getType() == SMS::$TYPE_SMS_IN )
 	$naglowek .= 'Od/From:    '.$sms->getPhone();
 else
 	$naglowek .= 'Do/To:    '.$sms->getPhone() .' / '.$sms->getContact();
$naglowek .= '
------------------------------------------------------

';


		$body = nl2br( $sms->getBody() );
    		$content = $naglowek.$body;
    		$name = 'SMS: '.htmlspecialchars($sms->getPhone(),ENT_QUOTES ,'ISO-8859-1').'.txt';
    		$type = 'text/plain';




 	return array('name' => $name, 'type' => $type,'content' => $content);
}

function add_email_content($id){

	$email = new Email($id);

 /*$naglowek = '
------------------- '.INC_WIADORG.' / Original message -------------------
Temat/Subject: '.$email->getName().'
Od/From:    '.$email->get_from().' &lt;'.$email->get_from_email().'&gt;
Data:  '.$email->get_date().'
Do/To:    '.$email->get_to().'
Dow/CC:   '.$email->get_cc().'
------------------------------------------------------------------------------------

'; */
    $naglowek = '
------------------- Original message -------------------
Subject: '.$email->getName().'
From:    '.$email->get_from().' &lt;'.$email->get_from_email().'&gt;
Date:  '.$email->get_date().'
To:    '.$email->get_to().'
CC:   '.$email->get_cc().'
------------------------------------------------------------------------------------

';

 	if ($email->get_body_html()){
    		$body = $email->getBody();
    		$content = nl2br(htmlspecialchars($naglowek,ENT_QUOTES ,'ISO-8859-1')).$body;
    		$name = 'Email: '.htmlspecialchars($email->getName(),ENT_QUOTES ,'ISO-8859-1').'.html';
    		$type = 'text/html';
 	}else{
    		$body = nl2br( $email->getBody() );
    		$content = $naglowek.$body;
    		$name = 'Email: '.htmlspecialchars($email->getName(),ENT_QUOTES ,'ISO-8859-1').'.txt';
    		$type = 'text/plain';
 	}

	return array('name' => $name, 'type' => $type,'content' => $content);
}
?>