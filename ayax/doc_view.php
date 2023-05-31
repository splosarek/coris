<?php


include_once('../include/include_ayax.php');

$id=getValue('id');
$tryb=getValue('tryb');
$case_id=getValue('case_id');
$action=getValue('action');
$type=getValue('type') != '' ? getValue('type') : 'note';

$action = getValue('action');

//$result = $id;
$result = '';
if ($id>0){
	$result = '<script>documentID='.$id.';</script>';
	try {
		$doc = $docObject->getNewDocument($id);
		if ($doc instanceof Email){
				if ($tryb=='case')
					$result .= emailViewCase($doc,$docObject);
				else
					$result .= emailView($doc,$docObject,$tryb);
		}else if ($doc instanceof Fax){
				if ($tryb=='case')
					$result .= faxViewCase($doc,$docObject);
				else
					$result .= faxView($doc,$docObject,$tryb);
		}else if ($doc instanceof SMS){
				if ($tryb=='case')
					$result .= smsViewCase($doc,$docObject);
				else
					$result .= smsView($doc,$docObject,$tryb);
		}

	}catch (Exception $e){
		$result .= 'Exception: '.$e->getMessage();
	}
}else{
	$result .= '<script>documentID=0;</script>';
	$result .= 'ID error: '.$id;

}


echo iconv('latin2','UTF-8',$result);
exit();


function faxViewCase($document,$docObject){
		//$result = 'Fax '.$document->getObjectID();

$result = '

<table WIDTH=570 cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. PREVIEW .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right width=130>'. GEN_FAX_DAW .':&nbsp; </td>
        <td align=left><b>'. $document->get_date() .'</b>&nbsp;</td>
        <td><?= GEN_FAX_NRF ?>:&nbsp;<b>'.$document->get_number() .'</b></td>
      </tr>
 	  <tr>
        <td align=right nowrap>'. GEN_FAX_DAD .':&nbsp; </td>
        <td align=left><b>'. $document->get_date_send().'</b>&nbsp;</td>
        <td></b></td>
      </tr>
 	  <tr>
        <td align=right nowrap>'. GEN_FAX_OSW .':&nbsp; </td>
        <td align=left><b>'. Application::getUserName($document->getCreateUser()).'</b>&nbsp;</td>
        <td>
        	</td>
      </tr>
      <tr>
        <td align=right>'. GEN_FAX_ILS .': </td>
        <td align=left>'. $document->get_page_number() .'</td>
        <td align="right">&nbsp;`</td>
      </tr>
      <tr>
        <td align=right >'. GEN_FAX_STA .':&nbsp; </td>
        <td align=left><b>'. (($document->get_status()=='0') ? GEN_FAX_WYS : $document->get_status()) .'</b>&nbsp;</td>
        <td><strong><a href="javascript:;" onClick="preview_img();">'. PREVIEW .'</a>
        &nbsp;<a href="javascript:;" onClick="preview_details();">'. GEN_FAX_SZC .'</a>';
		if ($document->get_direction() == Document::$DIRECT_IN){
			$result .= '&nbsp;<a href="javascript:;" onClick="preview_tiff();">(TIFF)</a>';
		}
       $result .= '</strong></td>
      </tr>
      <tr>
        <td align=right>'. GEN_FAX_SPR .': &nbsp;</td>
        <td rowspan="2" align=left valign=top>';


        $result .= '</td>
      </tr>
    </table></td>
  </tr>
</table>
<iframe width="570" height="580" name="frame_preview"  id="frame_preview" src=""></iframe>
<script>
preview_img();
function preview_img(){
//	frame_preview.location=\'getAttachment.php?id='. $document->getObjectID().'\';
	$(\'frame_preview\').src=\'getAttachment.php?id='. $document->getObjectID().'\';
}

function preview_details(){
	$(\'frame_preview\').src=\'getAttachment.php?id='. $document->getObjectID() .'&attach=LOG\';
}

function preview_tiff(){
	$(\'frame_preview\').src=\'getAttachment.php?id='. $document->getObjectID() .'&attach=TIFF\';
}

function sendFax(url){
	document.location = url;
}

</script>
<script>doc_binded('.$document->getObjectID().')</script>
</form>';
	return $result ;
}

function faxView(Fax $document,$docObject){

$result = '
<input type="hidden" name="reclamation" value="">
<table WIDTH=570 cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. PREVIEW .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right width=130>'. GEN_FAX_DAW .':&nbsp; </td>
        <td align=left><b>'. $document->get_date() .'</b>&nbsp;</td>
        <td>'. GEN_FAX_NRF .':&nbsp;<b>'.$document->get_number() .'</b></td>
      </tr>
 	  <tr>
        <td align=right nowrap>'. GEN_FAX_DAD .':&nbsp; </td>
        <td align=left><b>'. $document->get_date_send().'</b>&nbsp;</td>
        <td></b></td>
      </tr>
 	  <tr>
        <td align=right nowrap>'. GEN_FAX_OSW .':&nbsp; </td>
        <td align=left><b>'. Application::getUserName($document->getCreateUser()).'</b>&nbsp;</td>
        <td>
        	</td>
      </tr>
      <tr>
        <td align=right>'. GEN_FAX_ILS .': </td>
        <td align=left>'. $document->get_page_number() .'</td>
        <td align="right">&nbsp;`</td>
      </tr>
      <tr>
        <td align=right >'. GEN_FAX_STA .':&nbsp; </td>
        <td align=left><b>'. (($document->get_status()=='0') ? GEN_FAX_WYS : $document->get_status()) .'</b>&nbsp;</td>
        <td><strong><a href="javascript:;" onClick="preview_img();">'. PREVIEW .'</a>
        &nbsp;<a href="javascript:;" onClick="preview_details();">'. GEN_FAX_SZC .'</a>';
		if ($document->get_direction() == Document::$DIRECT_IN){
			$result .= '&nbsp;<a href="javascript:;" onClick="preview_tiff();">(TIFF)</a>';
		}
       $result .= '</strong></td>
      </tr>   ';
      if ($document->get_direction() == Document::$DIRECT_IN){
	      $result .= '<tr>
	        <td align=right valign="top">'. FK_EMAIL_SPRWY .': &nbsp;</td>
	        <td align=left valign=top><div id="document_case_binded"></div>';
	       $result .= '</td>
      </tr>';
  	 } else{
     	/*$result .=  '<tr>
        	<td align=right valign="top">'.$document->get_direction().'</td>
        	<td align=left valign=top>';
     	 $result .= '</td>
      </tr>';*/
     }


     $result .= '
 <tr>
        <td colspan="3" >
<div id="case_add_form" name="case_add_form" style="position:relative; display:block;height:auto;margin-left:5px;">

</div>

</td>
      </tr>
    </table></td>
  </tr>
</table>
<iframe width="570" height="580" name="frame_preview" id="frame_preview" src=""></iframe>
<script>
preview_img();
function preview_img(){
	$(\'frame_preview\').src=\'getAttachment.php?id='. $document->getObjectID().'\';
}

function preview_details(){
	$(\'frame_preview\').src=\'getAttachment.php?id='. $document->getObjectID() .'&attach=LOG\';
}

function preview_tiff(){
	$(\'frame_preview\').src=\'getAttachment.php?id='. $document->getObjectID() .'&attach=TIFF\';
}

function sendFax(url){
	document.location = url;
}

</script>
<script>doc_binded('.$document->getObjectID().')</script>
</form>';



	return $result ;


}

function emailViewCase(Document $document,$docObject){
	$result = '<table WIDTH="590" border=0 cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" >
  <tr  align="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. EMAIL .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">

    <table width="570" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right width=80>'. DATE .':&nbsp; </td>
        <td align=left colspan="2"><b>'.  $document->get_date().'</b>&nbsp;</td>

      </tr>
      <tr>
        <td align=right>'. AS_DOC_NAD .': </td>
        <td align=left colspan="2"><b>'. $document->get_from() .'&nbsp;&nbsp;'. $document->get_from_email() .'</b></td>
        </td>
      </tr>
      <tr>
        <td align=right>'. AS_FORMSF_WYSLDO .': </td>
        <td align=left colspan="2"><b>'. htmlspecialchars($document->get_to(),ENT_QUOTES ,'ISO-8859-1' ) .'</b></td>
      </tr>';

      	if ($document->get_cc()<>''){
			$result .=  '      <tr>
		        <td align=right>CC: </td>
		        <td align=left colspan="2"><b>'.htmlspecialchars($document->get_cc(),ENT_QUOTES ,'ISO-8859-1') .'</b></td>
		      </tr>';

      	}
  	  $result .= '<tr>
        <td align=right>'. AS_DOK_TYT .': </td>
        <td align=left colspan="2"><b>'. $document->getName().'</b></td>
      </tr>';
  	 if ($document->get_direction() == Document::$DIRECT_IN){
	      $result .= '<tr>
	        <td align=right valign="top">'. FK_EMAIL_SPRWY .': &nbsp;</td>
	        <td align=left valign=top><div id="document_case_binded"></div>
	        <script>doc_binded(\''.$document->getObjectID().'&tryb=case\')</script>
	        </td>
	        <td align=left bgcolor="#DDDDDD" nowrap>      </td>
	        </tr>';
  	 }
      $result .= '<tr>
        <td colspan="3" >
			<div id="case_add_form" name="case_add_form" style="position:relative; display:block;height:auto;margin-left:5px;">';


		$result .= ' </div> </div>
	  </td>
      </tr>
    </table></td>
  </tr>';

   $att = $document->getAttchments()->get_list();
   if (count($att) > 0 ){
  	$result .=  '
   		<tr  bgcolor="#CCCCCC">
    	<td align="center" colspan="3" nowrap style="border: #000000 1px solid;">
    	<table width="100%" border="0" cellpadding="0" cellspacing="2">
    		<tr><td width=80>'.AS_FORMSF_ZAL.':&nbsp;</td><td>&nbsp;</td></tr>';
  		foreach ($att As $attachment){
    			$file_name = $attachment->getName();
    			$result .= '<tr><td>&nbsp;</td><td title="'.addslashes($file_name).'">';
    			$result .= '<a href="getAttachment.php?id='.$attachment->getObjectID().'&action=view" target="_blank" title="View '.addslashes($file_name).'">';
    			$file_name = $attachment->getName();
    			if (strlen($file_name)>50)
    				$result .= substr($file_name,0,50)."...";
    			else
    				$result .= $file_name;
    			$result .= '</a>';
    			$result .= " (";
    			$size = $attachment->getSize();
			if ($size>1048576)
					$result .= round($size/1048576,1)." MB";
			else if ($size>1024)
					$result .= round($size/1024,1)." KB";
			else
					$result .= $size." B";
    			$result .= ") ";
    			$result .= ' <a href="getAttachment.php?id='.$attachment->getObjectID().'&action=download"  title="'.addslashes($file_name).'"><img border=0 src="graphics/download.png"></a></td></tr>';


  		}
    	$result .= '</table>
    	</td></tr>
    	';
   }
    	$result .= ' <tr  bgcolor="#EEEEEE">
    	<td align="center" nowrap style="border: #000000 1px solid;" colspan="3">

    	<table width="100%" border="0" cellpadding="2" cellspacing="2" height="350">
    		<tr><td valign="top"> <div style="display: block; width: auto;height: auto;overflow:auto;background-color:#FFFFFF">';
    			if ($document->get_body_html()) {
                    //$result .= $document->getBody();
                    $result .= strtr($document->getBody(),array('<script type="text/javascript">'=>'','<script>' => '','</script>'=> '','<base '=> '<xbase ','<BASE '=> '<xbase ','<style'=> '<xstyle','<STYLE'=> '<XSTYLE'));
                }else
    					$result .= nl2br( $document->getBody() );
    		$result .= '</div>
    		</td></tr>
    		</table>
    	</td>
    	</tr>
</table>

';

return $result;
}
function emailView(Email $document,$docObject,$tryb){

$result = '<table WIDTH="590" border=0 cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" >
  <tr  align="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. EMAIL .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">

    <table width="600" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right width=80>'. DATE .':&nbsp; </td>
        <td align=left><b>'.  $document->get_date().'</b>&nbsp;</td>
        <td align="right"></td>
      </tr>
      <tr>
        <td align=right>'. AS_DOC_NAD .': </td>
        <td align=left colspan="2"><b>'. $document->get_from() .'&nbsp;&nbsp;'. $document->get_from_email() .'</b></td>
        </td>
      </tr>
      <tr>
        <td align=right>'. AS_FORMSF_WYSLDO .': </td>
        <td align=left colspan="2"><b>'. htmlspecialchars($document->get_to() ) .'</b></td>
      </tr>';

      	if ($document->get_cc()<>''){
			$result .=  '      <tr>
		        <td align=right>CC: </td>
		        <td align=left colspan="2"><b>'.htmlspecialchars($document->get_cc()) .'</b></td>
		      </tr>';

      	}
  	  $result .= '<tr>
        <td align=right>'. AS_DOK_TYT .': </td>
        <td align=left colspan="2"><b>'. $document->getName().'</b></td>
      </tr>';
  	 if ($document->get_direction() == Document::$DIRECT_IN){
	      $result .= '<tr>
	        <td align=right valign="top">'. FK_EMAIL_SPRWY .': &nbsp;</td>
	        <td align=left  valign=top><div id="document_case_binded"></div>
	        <script>doc_binded('.$document->getObjectID().')</script></td>
	        ';
	      $result .= '<td align=left bgcolor="#DDDDDD" nowrap>  </td>
	      </tr>';

  	 } else{
     /*	$result .=  '<tr>
        	<td align=right valign="top">'.$document->get_direction().'</td>
        	<td align=left  valign=top></td>
        		<td align=left bgcolor="#DDDDDD" nowrap> </td>';
        	$result .= '</tr>';
        	*/
     }


      $result .= '
      <tr>
        <td colspan="3" >
<div id="case_add_form" name="case_add_form" style="position:relative; display:block;height:auto;margin-left:5px;">

</div>

</td>
      </tr>
    </table></td>
  </tr>';

   $att = $document->getAttchments()->get_list();
   if (count($att) > 0 ){
  	$result .=  '
   		<tr  bgcolor="#CCCCCC">
    	<td align="center" colspan="3" nowrap style="border: #000000 1px solid;">

    	<table width="100%" border="0" cellpadding="0" cellspacing="2">
    		<tr><td width=80>'.AS_FORMSF_ZAL.':&nbsp;</td><td>&nbsp;</td></tr>';
  		foreach ($att As $attachment){
    			$file_name = $attachment->getName();
    			$result .= '<tr><td colspan="2" style="padding-left:15px" title="'.addslashes($file_name).'">';
    			$result .= '<a href="getAttachment.php?id='.$attachment->getObjectID().'&action=view" target="_blank" title="View '.addslashes($file_name).'">';
    			$file_name = $attachment->getName();
    			if (strlen($file_name)>50)
    				$result .= substr($file_name,0,50)."...";
    			else
    				$result .= $file_name;
    			$result .= '</a>';
    			$result .= " (";
    			$size = $attachment->getSize();
			if ($size>1048576)
					$result .= round($size/1048576,1)." MB";
			else if ($size>1024)
					$result .= round($size/1024,1)." KB";
			else
					$result .= $size." B";
    			$result .= ") ";
    			$result .= ' <a href="getAttachment.php?id='.$attachment->getObjectID().'&action=download"  title="'.addslashes($file_name).'"><img border=0 src="graphics/download.png"></a>';

    			if ($tryb=='export'){
    				$result .= '<input type="button" name=button_tresc value="'.AS_DOK_WSTZAL.'" style="background-color: red"
    				onClick="wstaw_form(\'document\',\''.$file_name.'\',\'DOC_get_content.php?id='.$attachment->getObjectID().'&source=document&action=view\');"
    				>';
    			}
    			$result .= '</td></tr>';
  		}
    	$result .= '</table>
    	</td></tr>
    	';
   }
    	$result .= ' <tr  bgcolor="#EEEEEE">
    	<td align="center" nowrap style="border: #000000 1px solid;" colspan="3">

    	<table width="100%" border="0" cellpadding="2" cellspacing="2" height="350">
    		<tr><td valign="top">';

    if ($tryb=='print'){
	    $result .= '<div style="display: block; width: 590px;height: auto;overflow:auto;background-color:#FFFFFF">';
    }else{
    	$result .= '<div style="display: block; width: 590px;height: 400px;overflow:auto;background-color:#FFFFFF">';
    }

    			if ($document->get_body_html()){
    					$result .= strtr($document->getBody(),array('<script type="text/javascript">'=>'','<script>' => '','</script>'=> '','<base '=> '<xbase ','<BASE '=> '<xbase ','<style'=> '<xstyle','<STYLE'=> '<XSTYLE'));

    					//$result .= $document->getBody();
    			}else{
    					$result .= nl2br( $document->getBody() );
    			}
    		$result .= '</div>
    		</td></tr>
    		</table>
    	</td>
    	</tr>
</table>

';

return $result;

}
function smsView(SMS $document,$docObject,$tryb){

$result = '<table WIDTH="590" border=0 cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" >
  <tr  align="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. SMS .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">

    <table width="600" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right width=80>'. DATE .':&nbsp; </td>
        <td align=left><b>'.  $document->getCreateDate().'</b>&nbsp;</td>
        <td align="right"></td>
      </tr>
      <tr>
        <td align=right>'. AS_DOC_NAD .': </td>
        <td align=left colspan="2"><b>'. $document->getPhone() .'</b></td>
        </td>
      </tr>
    ';

/*  	  $result .= '<tr>
        <td align=right>'. AS_DOK_TYT .': </td>
        <td align=left colspan="2"><b>'. $document->getName()..'</b></td>
      </tr>';*/
  	 if ($document->get_direction() == Document::$DIRECT_IN){
	      $result .= '<tr>
	        <td align=right valign="top">'. FK_EMAIL_SPRWY .': &nbsp;</td>
	        <td align=left  valign=top><div id="document_case_binded"></div>
	        <script>doc_binded('.$document->getObjectID().')</script></td>
	        ';
	      $result .= '<td align=left bgcolor="#DDDDDD" nowrap>  </td>
	      </tr>';

  	 } else{
     /*	$result .=  '<tr>
        	<td align=right valign="top">'.$document->get_direction().'</td>
        	<td align=left  valign=top></td>
        		<td align=left bgcolor="#DDDDDD" nowrap> </td>';
        	$result .= '</tr>';
        	*/
     }


      $result .= '
      <tr>
        <td colspan="3" >
<div id="case_add_form" name="case_add_form" style="position:relative; display:block;height:auto;margin-left:5px;">

</div>

</td>
      </tr>
    </table></td>
  </tr>';


    	$result .= ' <tr  bgcolor="#EEEEEE">
    	<td align="center" nowrap style="border: #000000 1px solid;" colspan="3">

    	<table width="100%" border="0" cellpadding="2" cellspacing="2" height="350">
    		<tr><td valign="top"> <div style="display: block; width: 590px;height: 400px;overflow:auto;background-color:#FFFFFF">';
    					$result .= nl2br( $document->getBody() );

    		$result .= '</div>
    		</td></tr>
    		</table>
    	</td>
    	</tr>
</table>

';

return $result;

}


?>