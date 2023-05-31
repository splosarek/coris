<?php


include_once('../include/include_ayax.php');



	
$form_action=getValue('form_action');
$form_action_value=getValue('form_action_value');


$doc_id=getValue('doc_id');
$case_id=getValue('case_id');
$action=getValue('action');
$tryb=getValue('tryb');


$result = '';


if ($action=='unbind' && $tryb != 'case'){		
		$result .= unbind_case($doc_id,$case_id);
		$result .= '<script>refresh_form_doc_list();</script>';
}

		$result .= list_case($doc_id,$tryb);



echo iconv('latin2','UTF-8',$result);
exit();



function unbind_case($doc_id,$case_id){
	global $docObject;
	
	$result = Interaction::unbindCase($doc_id,$case_id);

	return $result;
}

function  list_case($doc_id,$tryb){
		global $docObject;
		
		$result = '';
		
		$lista = Interaction::getBindedCaseToDocument($doc_id);
		
		$ilosc = count($lista);
			foreach ($lista As $row){
				$result .=  $row['number'].'/'.$row['year'];
				if ( $tryb != 'case')
					$result .= '<input type="submit" value="X" style="width: 20px" onclick="return unbind_document('.$doc_id.','.$row['case_id'].')" title="'.FK_EMAIL_USZTEJSPR.'">';
				$result .= '<br>';
			}
			//$ilosc==0 && $document->get_status() > 0
			if ( $ilosc==0 ){
				$result .=  '<input type="button" name="delete_email" value="Skasuj dokument" OnClick="delete_document('.$doc_id.')">';
			};
  return $result;
}


?>