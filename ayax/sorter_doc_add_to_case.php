<?php


include_once('../include/include_ayax.php');

	
$form_action=getValue('form_action');
$form_action_value=getValue('form_action_value');


$doc_id=getValue('doc_id');
$case_id=getValue('case_id');
$action=getValue('action');

$result = '';
if ($case_id > 0 && $doc_id>0 ){
	
	if ($action=='add'){
		$category_id=getValue('category_id');
		$reclamation=getValue('reclamation')=='true' ? 1 : 0;
		
		$result .= form_add_to_case_save($case_id,$doc_id,$category_id,$reclamation,1);//do poprawy
		
	}else{
		$caseObject = new CorisCase($case_id); 
		$result .= form_add_to_case($caseObject,$case_id,$doc_id);
	}
}

echo iconv('latin2','UTF-8',$result);
exit();


function form_add_to_case_save($case_id,$doc_id,$category_id,$reclamation,$destination){
	 	global $docObject;
	 	$result = '';
	 	
	 	if ( Interaction::checkBindDocumentToCase($case_id,$doc_id) ){
	 			$result = '<script>			
				alert(\'Dokument ju¿ jest do³±czony do tej sprawy/ This document already exists in this case\');	
		
		</script>';
	 		return $result;
	 	}
		$id = Interaction::bindDocumentToCase($case_id,$doc_id,$category_id,$reclamation,$destination);
		if ($reclamation)	CorisCase::set_case_reclamation($case_id);
		if ( CorisCase::check_archiveS($case_id) ){
			CorisCase::openCase($case_id,$category_id);
		}
		if ($id>0){
		$result = '<script>			
				doc_binded('.$doc_id.');	
				refresh_form_doc_list();		
		</script>';
		}else{
			$result .= 'Bind error';
		}
			
		return $result ;
}

function  form_add_to_case(CorisCase $caseObject,$case_id,$doc_id){
	$lang = $_SESSION['GUI_language'];

	$result = '<hr>'. FK_EMAIL_SPRWY .': &nbsp;&nbsp;
  <input name="case_name" type="text" class="amount-disabled" size="20" maxlength="30" readonly="reedonly" value="'.$caseObject->getNumber().'/'.$caseObject->getYear().'">
  &nbsp;&nbsp;&nbsp;'. FK_EMAIL_KATEGORIA  .': 
            <select name="category_id"  id="category_id" class="date-required">';

	$query2 = "SELECT * FROM coris_fax_in_category  WHERE ID_section=1 ";
	$mysql_result2 = mysql_query($query2) or die(mysql_error());
	$result .=  '<option value=\'0\'> ... </option>';
	while ($row2=mysql_fetch_array($mysql_result2)){
				$result .=  '<option value=\''.$row2['ID'].'\'>'.( ($lang=='en' && $row2['name_eng'] != '' ) ? $row2['name_eng'] : $row2['name'] ).'</option>';
	}

   $result .= '</select>            <br>
                <div align="left" style="color:red;"><b>Reklamacja</b><input style="background: #cccccc ;"  type="checkbox" id="reclamation" name="reclamation" value="1" >
<span name="signal_category" id="signal_category"></span>
          <br>
          <br>
          <div align=center>
          <input type="button" name="but" value="'. FK_EMAIL_DODAJ  .'" OnClick="save_ad_doc_to_case('.$case_id.','.$doc_id.');"></div>';
  return $result;
}


?>