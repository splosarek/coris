<?php


include_once('../include/include_ayax.php');

$id = intval(getValue('id'));
$tryb = getValue('tryb');

$interaction = new Interaction(0,$id);
$doc_id = $interaction->getDocument()->getObjectID();

if ($tryb=='') $tryb =='case';

$result = '<script>

function reply_doc(option){
	opcja = \'DOC_new_document.php?int='.$interaction->getObjectId().'\'+option;
	window.open(opcja,\'\',\'toolbar=0,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width=\'+ (screen.availWidth - 6) + \',height=\'+ (screen.availHeight - 100) +\',left=0,top=0\');
}


	function wstaw_form(typ,pozycja,link){
			doc = opener.opener;
			if (opener){
					 if ( doc.addToList(typ,pozycja,link) )
					 	alert("'.AS_DOK_MSG_ZALDOD.'");
			}else
					alert("'.AS_DOK_MSG_BLOKNOZAMK.'");
		}


	function load_document(id,tryb){
		load_ayax(\'document_preview\',\'ayax/doc_view.php?action=view_case&id=\'+id+\'&tryb=\'+tryb);
	}

	function load_navigation(action){
		load_ayax(\'document_navigation\',\'ayax/\'+action);
	}


	function save_interaction(){
			var param;
			if ( $(\'category_id\') ){
				if ( $(\'category_id\').value==0){
						alert(\''. FK_EMAIL_PROSZWYBRKAT .'\');
						return false;
				}else{
						param=\'&category_id=\'+$(\'category_id\').value;
				}
			}

			if ( $(\'obrobiony\') && $(\'obrobiony\').checked){
					param += \'&obrobiony=\'+$(\'obrobiony\').value
			}

			if ( $(\'reclamation_form\') && $(\'reclamation_form\').checked){
					param += \'&reclamation=\'+$(\'reclamation_form\').value
			}

			load_navigation(\'doc_navigation.php?id='.$id.'&tryb='.$tryb.'&action=save_int\'+param);

}

</script>';

$case = new CorisCase( $interaction->getCaseID() );

	$result .= '

	<table WIDTH="590" border=0 cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" >
  <tr  align="baseline" bgcolor="#CCCCCC">
    <td nowrap style="border: #000000 1px solid;"><div align="center" >';

	if ($tryb=='case'){

		$result .= '<div style="float:left;margin-left:10px;"><a href="javascript:;" onCLick="print_interaction('.$id.')"><img src="img/print.gif"></a></div>';
	}

    $result .= '<strong>'.DOCUMENT.': '. $interaction->getTypeClassName() .' '.( $interaction->getDirection() == Interaction::$DIRECTION_IN ? DIRECT_IN : DIRECT_OUT  ).'</strong></div></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="left" nowrap style="border: #000000 1px solid;">

    <table width="580" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right width="120">'.DATEOFREGISTRATION.':&nbsp; </td>
        <td align=left><b>'. $interaction->getDate().'</b>&nbsp;</td>

      </tr>
      <tr>
        <td align=right>'. AS_DOC_NAD .': </td>
        <td align=left ><b>'.Application::getUserName($interaction->getUserId()) .'</b></td>
        </td>
      </tr>
      <tr>
        <td align=right>'.DOTYCZY_SPRAWY.': </td>
        <td align=left><b>'. $case->getCaseNumber() .' ('.$case->getPaxsurname().' '.$case->getPaxname().')</b></td>
      </tr>
      <tr>
        <td align=right>'.AS_DOK_TEMAT.': </td>
        <td align=left><b>'.$interaction->getInteractionSubject() .'</b></td>
      </tr>';
  $result .=  '</td></tr></table>';

  $result .=  '</td></tr></table>';

  $result .= '<div  id="document_navigation" style="width:580px;height:auto; margin:10px;">



  </div>';


$result .= '<br><br><div align="left">';
$result .='<div id="document_preview" style=""></div>';
$result .='<div>';
$result .= '<script>
		load_document(\''.$doc_id.'&tryb='.$tryb.'\',\''.$tryb.'\');
		load_navigation(\'doc_navigation.php?id='.$id.'&tryb='.$tryb.'\');
</script>
';


echo iconv('latin2','UTF-8',$result);
exit();


?>