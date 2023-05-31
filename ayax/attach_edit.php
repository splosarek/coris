<?php 


include_once('../include/include_ayax.php');

$id=getValue('fid');
$type=getValue('type');
$name=getValue('name');
$id_object=getValue('id_object');

$result = '';

$result = form_navigation($type,$id,$name,$id_object);
	
echo iconv('latin2','UTF-8',$result);
exit();



function form_navigation($source,$id,$name,$id_object){
	$dir_tmp = DIR_TMP;
	$action=getValue('action');
	$cmd=getValue('cmd');
	$ilosc_stron = 0;
	
	if ($action == 'save'){
		
		$ilosc_tmp = PDFTools::pdfGetParameters('Pages:', $dir_tmp.$id);
		
		if ($cmd == 'delete'){
			$page_from=getValue('page_from');
			$page_to=getValue('page_to');
			
			if ( $page_from>0 && $page_to > 0 ){
			 	
			 		
				 if ($page_to>$ilosc_tmp)
				 	$page_to = $ilosc_tmp;
				 
				 if  ($page_from==1){
				 		$page_to++;
				 		$zakres= "A$page_to-end";				 
				 }else if($page_to==$ilosc_tmp){
				 	$page_from--;
				 	$zakres = "A1-$page_from";	
				 }else{
				 	$page_to++;
				 	$page_from--;
				 	$zakres = "A1-$page_from A$page_to-end";
				 }
				 
				 $file = PDFTools::deletePagePDF($dir_tmp.$id, $zakres);
				 
				 if ($file && file_exists($dir_tmp.$file) && filesize($dir_tmp.$file)>0){
						$tmp = $file; 	
				 }else{
				 		return "Delete Page error";
				 }									
			}
		}else if ($cmd == 'extract'){
				$page_list=getValue('page_list');
			
				$tmp_zakres = explode(',',$page_list);
				$zakres = implode(' ',$tmp_zakres);
				
				$file = PDFTools::extractPagePDF($dir_tmp.$id, $zakres);
				 
				 if ($file && file_exists($dir_tmp.$file) && filesize($dir_tmp.$file)>0){
						$tmp = $file; 	
				 }else{
				 		return "Extract Page error";
				 }	
		}		 		
		$ilosc_stron = PDFTools::pdfGetParameters('Pages:', $dir_tmp.$tmp);	
	}else{	
		$link = $source=='upload' || $source=='file' ? 'DOC_get_content.php?id='.$id.'&source='.$source.'&action=raw' : $id;
		//$link = $id;  	     		
  		parse_str(substr($link,strpos($link, '?')+1),$tmp_array);
  		$id = $tmp_array['id'];
  		
  		$content= getContent($source,$id);
  		
			if ($content){
					$dir_tmp = DIR_TMP;
					$tmp = tempnam($dir_tmp, "pdf");		
		  			rename($tmp,$tmp.'.pdf');
		  			$tmp = basename($tmp.'.pdf');
		  			$fs = fopen($dir_tmp.$tmp, 'wb');
		  			if ($fs){
		  				fwrite($fs,$content);
		  				fflush($fs);
		  				fclose($fs);  			 			
		  			}else{
		  				return "File error: ".$dir_tmp.$tmp;
		  			}    				
				
		  			$ilosc_stron = PDFTools::pdfGetParameters('Pages:', $dir_tmp.$tmp);
		  			
			}else{
				return "Attach error: ".$source.', '.$id;		
			}
	}
 
	
	
	
	

	$result = '
<script>
function save_change(){
	removeFromList(\''.$id_object.'\');
	addToList(\'tmp\',\''.$name.'\',\'DOC_get_content.php?id=\'+$(\'file_id\').value+\'&source=upload&action=view\');
	myWindow.close();
}

function delete_pages(){
		reload(\'cmd=delete&page_from=\'+$(\'page_from\').value+\'&page_to=\'+$(\'page_to\').value);
}

function extract_pages(){
	reload(\'cmd=extract&page_list=\'+$(\'page_list\').value);
}

function reload(param){
			load_ayax(\'edit_attach\', \'ayax/attach_edit.php?action=save&fid=\'+$(\'file_id\').value+\'&type=\'+$(\'file_source\').value+\'&name=\'+$(\'name\').value+\'&id_object=\'+$(\'id_object\').value+\'&\'+param);		
}

function load_document(id,source){
		$(\'document_preview\').src = \'DOC_get_content.php?id=\'+id+\'&source=\'+source+\'&action=view\';		
}
</script>		
<div id="edit_attach">
	<input type="hidden" name="id_object" id="id_object" value="'.$id_object.'">
	<input type="hidden" name="file_source" id="file_source" value="tmp">
	<input type="hidden" name="file_id" id="file_id" value="'.$tmp.'">
	<input type="hidden" name="name" id="name" value="'.$name.'">
	<table WIDTH="674" cellpadding="1" cellspacing="0"  style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. AS_FORMSF_EDZAL .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
    
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right widht=25%>'. AS_FORMSF_ILSTRON .': </td>
        <td align=left   width=5%>'. $ilosc_stron .'&nbsp;</td>
        <td align="right" width=70%>
        </td>
      </tr>';
      if($ilosc_stron>1){  
      $result .= '<tr>
        <td align=right widht=25%>'. AS_FORMSF_DELSTRON .' </td>
        <td align=left   width=5%></td>
        <td align="left" width=70%>'. AS_FORMSF_DELSTRONOD .': <input type=text name="page_from" id="page_from" size="3" maxlength="3" value=0>
        &nbsp;&nbsp;'. AS_FORMSF_DELSTRONDO .': <input type=text name="page_to" id="page_to" size="3" maxlength="3" value=0></strong> &nbsp;&nbsp;&nbsp;
        <input type="button" name="wykonaj" value="'. AS_FORMSF_WYKONAJ .'" onClick="delete_pages();">         
        </td>
 <tr>
        <td align=right widht=25% nowrap>'. AS_FORMSF_POZSTR .' </td>
        <td align=left   width=5%></td>
        <td align="left" width=70%>'. AS_FORMSF_STR .': <input type=text name="page_list" id="page_list" size="20" maxlength="20" value=""> np. 1,2,3,5-10
        </strong> &nbsp;&nbsp;&nbsp;<input type="button" name="wykonaj" value="'. AS_FORMSF_WYKONAJ .'"  onClick="extract_pages();"> 
        
        </td>        
        </tr>';
       } 
       $result .= '  
        <tr>
        	<td  colspan=3><hr>
        	</td>
        </tr>	
  		<tr>
        	<td align="center" colspan=3>';
 		if($ilosc_stron>0){ 
 				$result .= '<input type="button" name="wykonaj" value="'. AS_FORMSF_ZATW .'" onclick="save_change();">&nbsp;&nbsp; '; 
 		} 
 	
      	$result .= '<input type="button" name="wykonaj" value="'. CLOSE .'" onclick="myWindow.close();">
        
        </td>        
        
      </tr>
    </table></td>
  </tr>
</table>
<iframe width="670" height="580" name="document_preview" id="document_preview" src=""></iframe>
</div>';
      	$result .= '<script> load_document(\''.$tmp.'\',\'tmp\');</script>';
      	return $result;
}
	

?>