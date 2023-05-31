<?php


function ereg_MatchedHTMLTags($tagname) {
   return "^(.*)(<[ \\n\\r\\t]*$tagname(>|[^>]*>))(.*)(<[ \\n\\r\\t]*[\/][ \\n\\r\\t]*$tagname(>|[^>]*>))(.*)$";
}

function getTemplateOld($tmpl,$lang){
	$query = "SELECT * FROM coris_fax_templates  WHERE ID='$tmpl'";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result) == 0) return;
	$row = mysql_fetch_array($mysql_result);
	$body = '';
	if ($row['cover']>0){
		$query2 = "SELECT * FROM coris_fax_templates  WHERE ID='".$row['cover']."'";
		$mysql_result2 = mysql_query($query2);
		$row2 = mysql_fetch_array($mysql_result2);
		if ($row2['body_'.$lang]<>''){
			$body .= $row2['body_'.$lang];
			$body .= "\n<!--NewPage-->\n";
		}
	}

	$body .= $row['body_'.$lang];
	return array($row['title'],$body);
}


function getTemplate($tmpl, $lang)
{
    if('pl' != $lang && 'uk' != $lang)
    {
        $lang= 'pl';
    }

    $sql = "SELECT name, content_pl, content_uk, default_template_email, default_template_email_cc,attachments
            FROM coris_document_templates
            WHERE ID='$tmpl'
            AND status=1";


    $mysqlRes = mysql_query($sql) or die($sql.mysql_error());;
	if (mysql_num_rows($mysqlRes) == 0) return;

	$row = mysql_fetch_assoc($mysqlRes);

	$attachments = array();

	if ( $row['attachments'] != "" ) {
	        $q2 = "SELECt * FROM coris_forms WHERE ID IN (".$row['attachments'].")";

	        $mr = mysql_query($q2);
	        while($r = mysql_fetch_array($mr)){

	            $attachments[] = array('name' => $r['file'], 'file' => $r['file'] );

            }
            //echo nl2br(print_r($attachments,1));
    }


    return array($row['name'],
        ($row['content_' . $lang] != '' ? $row['content_' . $lang] : $row['content_pl']),
        'default_template_email' => $row['default_template_email'],
        'default_template_email_cc'=>  $row['default_template_email_cc'],
        'attachments' => $attachments

    );
}

function dodaj_attach($id,$other_attach,$other_attach_id){


		switch ($other_attach){

			case 'email_in_content':
				gen_file_email_content($id,'in',$other_attach_id);
				break;
   			case 'email_in_attach':
				gen_file_email_attach($id,'in',$other_attach_id);
				break;
   			case 'email_out_content':
				gen_file_email_content($id,'out',$other_attach_id);
				break;
   			case 'email_out_attach':
				gen_file_email_attach($id,'out',$other_attach_id);
				break;
   			case 'fax_in':
				gen_file_email_fax($id,'in',$other_attach_id);
				break;
   			case 'fax_out':
				gen_file_email_fax($id,'out',$other_attach_id);
				break;
			case 'form':
				gen_file_form($id,$other_attach_id);
				break;
		}


}

function gen_file_email_fax($id,$tryb,$aid){
	$file_fax= '';
	$dir_pdf = '';
	$attach_name = '';
	$file_type = '';
	if ($tryb=='in'){
			$query = "SELECT * FROM coris_fax_in  WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
			$row=mysql_fetch_array($mysql_result);

			$file =  $row['file'];
    		$file_name =  $row['filename'];
    		$pdf_file = $row['pdf_file'];
    		$attach_name = $row['pdf_file'];

    		if ($pdf_file <> ''){
    			$dir_pdf = '/home/coris/fax/in/'.$row['dir'].'/pdf/';
    			//echo "file: ".$dir_pdf.$pdf_file;

    			if (file_exists($dir_pdf.$pdf_file)){
    					$file_fax = 	$pdf_file;
    					$file_type = 'application/pdf';
    			}else {
    				    $dir_pdf = '';
    			}
    		}else{
    			include_once('include/pdf_utils.php');
    			$dir_src = '../fax/in/'.$row['dir'].'/img/';
    			$tiff_file = $row['filename'].'.tif';

    			$dir_pdf = '../fax/in/'.$row['dir'].'/pdf/';
    			$pdf_file = $row['filename'].'.pdf';
    			$attach_name = $pdf_file;
    			$res = fax_in_tiff2pdf($aid,$dir_src.$tiff_file,$dir_pdf.$pdf_file);
    			if (!$res){

    				$dir_pdf = '';
    			}


    		}
	}else if ($tryb=='out'){
			$query = "SELECT * FROM coris_fax_out   WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
			$row=mysql_fetch_array($mysql_result);

			$file =  $row['file'];
    		$file_name =  $row['file'];
    		$pdf_file = $row['file'];
    		$attach_name = $row['file'];

    		 $dir_pdf = '/home/coris/fax/out/'.$row['dir'].'/pdf/';

    		if ($pdf_file <> ''){
    			if (file_exists($dir_pdf.$pdf_file)){
    					$file_fax = 	$pdf_file;
    					$file_type = 'application/pdf';
    			}else {
    				    $dir_pdf = '';

    			}

    		}


	}
	//mail("krzysiek@evernet.com.pl","Coris test ".$tryb,$query."\n\n".print_r($row,true));

	if ($dir_pdf <> '' && $pdf_file<>''){

		$attach_name = addslashes(stripcslashes($attach_name));
     			$query = "INSERT INTO coris_email_tmp_attachment (ID,ID_email,tempname ,`dir`,file,`file_type`,size)
     			VALUES (null,'$id','".$pdf_file."','$dir_pdf','$attach_name','$file_type','".filesize($dir_pdf.$pdf_file)."')";
     		//	echo $query;
     			$mysql_result = mysql_query($query) OR die(mysql_error());
     		//	echo $query;
	}

}



function gen_file_email_content($id,$tryb,$aid){

	if ($tryb=='in'){

			$query = "SELECT * FROM coris_email_in WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
			$row=mysql_fetch_array($mysql_result);

			$query2 = "SELECT * FROM coris_email_in_content   WHERE ID='$aid'";
			$mysql_result_email2 = mysql_query($query2);
			$row_email2 = mysql_fetch_array($mysql_result_email2);

			$content = $row_email2['content'];
			$contentHtml = $row_email2['contentHtml'];

			$subject  = $row['subject'];
			$to  = $row['to'];
			$cc  = $row['cc'];
			$from   = ($row['from'] <> '' ) ? '"'.$row['from'].'"' : '';
			$from_email  = $row['from_email'];
			$date  = $row['date'];

	}else if ($tryb=='out'){

			$query = "SELECT *  FROM coris_email_out WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
			$row=mysql_fetch_array($mysql_result);
			$content = '';
			$contentHtml = $row['email_body'];
			$subject  = $row['email_temat'];
			$to  = $row['email_to'];
			$cc  = $row['email_cc'];
			$from   = "";
			$from_email  = "assist@coris.com.pl";
			$date  = $row['date_send'];
	}


/*$naglowek = '
------------------- '.INC_WIADORG.' / Original message-------------------
Temat/Subject: '.$subject.'
Od/From:    '.$from.' <'.$from_email.'>
Data:  '.$date.'
Do/To:    '.$to.'
Dow/CC:   '.$cc.'
------------------------------------------------------------------------------------

';*/
$naglowek = '
------------------- Original message-------------------
Subject: '.$subject.'
From:    '.$from.' <'.$from_email.'>
Date:  '.$date.'
To:    '.$to.'
CC:   '.$cc.'
------------------------------------------------------------------------------------

';
	//$dir =  "d:/work/projekty/coris/www/fax/tmp/";//TMP_DIR;
	$dir =  TMP_DIR;

	$body = '';
	$file_tmp = '';
	$attach_name = '';
	$file_type = '';
	if (trim($content)==''){
		$file_type = 'text/html';
		$attach_name = 'message_'.$aid.'.html';
		$body = nl2br(htmlspecialchars($naglowek,ENT_QUOTES ,'ISO-8859-1')).$contentHtml;
			$email_body = $body;
            $tmpfname = tempnam($dir,"email_"  );

            $ff = fopen($tmpfname,"w");
            if ($ff){
            	fwrite($ff,$email_body);
            	fflush($ff);
            	fclose($ff);
            }else{
            	echo "Error file ".$tmpfname;
            	$tmpfname = '';
            }
	}else{
			$file_type = 'text/plain';
			$attach_name = 'message_'.$aid.'.txt';
			$email_body = $naglowek.$content;
			$tmpfname = tempnam($dir,"email_"  );
            $ff = fopen($tmpfname,"w");
            if ($ff){
            	fwrite($ff,$email_body);
            	fflush($ff);
            	fclose($ff);
            }else{
            	echo "Error file ".$tmpfname;
            	$tmpfname = '';
            }
	}



	if ($tmpfname <> ''){


			$attach_name = addslashes(stripslashes($attach_name));
     			$query = "INSERT INTO coris_email_tmp_attachment (ID,ID_email,tempname ,`dir`,file,`file_type`,size)
     			VALUES (null,'$id','".addslashes(stripslashes(basename($tmpfname)))."','$dir','$attach_name','$file_type','".filesize($tmpfname)."')";
     		//	echo $query;
     			$mysql_result = mysql_query($query) OR die(mysql_error());
     		//	echo $query;
	}

}


function gen_file_form($id,$aid){



			$query = "SELECT file,file_type  FROM coris_forms WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result) > 0 ){
			$row=mysql_fetch_array($mysql_result);
			$file = $row['file'];
			$tmpname = $row['file'];
			$file_type = $row['file_type'];
			$completeFilePath = '../fax/forms/';

		if (file_exists($completeFilePath.$tmpname)){
				$file = addslashes(stripslashes($file));
	     			$query = "INSERT INTO coris_email_tmp_attachment (ID,ID_email,tempname ,`dir`,file,`file_type`,size)
	     			VALUES (null,'$id','".addslashes(stripslashes($tmpname))."','$completeFilePath','$file','$file_type','".filesize($completeFilePath.$tmpname)."')";
	     			$mysql_result = mysql_query($query) OR die(mysql_error());
		}
	}

}

function gen_file_email_attach($id,$tryb,$aid){

	if ($tryb=='in'){

			$query = "SELECT file,tempname,dir,file_type  FROM coris_email_in_attachment WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
			$row=mysql_fetch_array($mysql_result);
			$file = $row['file'];
			$tmpname = $row['tempname'];
			$dir = $row['dir'];
			$file_type = $row['file_type'];
			$completeFilePath = MAIL_SPOOL.'in/'.$dir.'attach/';

	}else if ($tryb=='out'){

			$query = "SELECT file,tempname,dir,file_type  FROM coris_email_out_attachment WHERE ID='$aid' ";
			$mysql_result = mysql_query($query);
			$row=mysql_fetch_array($mysql_result);
			$file = $row['file'];
			$tmpname = $row['tempname'];
			$dir = $row['dir'];
			$file_type = $row['file_type'];
			$completeFilePath = MAIL_SPOOL.'out/'.$dir;
	}



	if (file_exists($completeFilePath.$tmpname)){


			$file = addslashes(stripslashes($file));
     			$query = "INSERT INTO coris_email_tmp_attachment (ID,ID_email,tempname ,`dir`,file,`file_type`,size)
     			VALUES (null,'$id','".addslashes(stripslashes($tmpname))."','$completeFilePath','$file','$file_type','".filesize($completeFilePath.$tmpname)."')";
     			$mysql_result = mysql_query($query) OR die(mysql_error());
	}

}

function dodaj_plik($id,$file){
	include_once("include/file_utils.php");
             		$dir =     TMP_DIR;
             		$tmpfname = tempnam($dir,"email_"  ).'.tmp';

             			$plik = move_file($dir,$_FILES[$file]['tmp_name'],basename($tmpfname));

             			$query = "INSERT INTO coris_email_tmp_attachment (ID,ID_email,tempname ,`dir`,file,`file_type`,size)
             			VALUES (null,'$id','".$plik."','$dir','".addslashes(stripcslashes($_FILES[$file]['name']))."','".$_FILES[$file]['type']."','".$_FILES[$file]['size']."')";
             			$mysql_result = mysql_query($query) OR die(mysql_error());

}




?>