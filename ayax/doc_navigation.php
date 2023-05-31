<?php


include_once('../include/include_ayax.php');

$id=getValue('id');



$action = getValue('action');
$tryb = getValue('tryb');

	$result = '';

if (!($id > 0)) die('Interaction error: '.$id);

if ($tryb == 'export'){
		$interaction = new Interaction(0,$id);
		$doc_id = $interaction->getDocument()->getObjectID();


		$interaction->getInteractionSubject();
		$document  = $interaction->getDocument();
			$document->getName();


		if ($document instanceof Email)	{
			$nazwa = trim($interaction->getInteractionSubject());
			if ($nazwa == '') $nazwa = "email_".time();
			$result .=' <input type="button" name=button_tresc value="'.AS_DOK_MSG_WSTTRMAILJAKZAL.'" style="background-color: red"
			onClick="wstaw_form(\'document\',\'Email: '.$nazwa.'.html\',\'DOC_get_content.php?id='.$doc_id.'&source=document&action=view\');" >';
		}else if ($document instanceof Fax){
			$document  = $interaction->getDocument();
			$result .=' <input type="button" name=button_tresc value="'.AS_DOK_MSG_WSTFAXJAKZAL.'" style="background-color: red"
			onClick="wstaw_form(\'document\',\'Fax: '.$document->getName().'_'.time().'.pdf\',\'DOC_get_content.php?id='.$doc_id.'&source=document&action=view\');" >';
		}else{
			$nazwa = trim($interaction->getInteractionSubject());
			if ($nazwa == '') $nazwa = "arrachment_".time();
			$result .=' <input type="button" name=button_tresc value="'.AS_DOK_MSG_WSTTRDOCJAKZAL.'" style="background-color: red"
			onClick="wstaw_form(\'document\',\''.$nazwa.'\',\'DOC_get_content.php?id='.$doc_id.'&source=document&action=view\');" >';
		}
}else{

	if ($action == 'save_int'){
				save_interaction($id);
	}

	$interaction = new Interaction(0,$id);
	$direction = $interaction->getDirection();

	if ($interaction-> getType() == Interaction::$_TYPE_SMS)
		exit();

	if ($action == 'reply'){

	}else if ($action == 'forward'){

	}else if ($action == 'resend_form'){
		$result .= form_resend($interaction);
	}else if ($action == 'resend'){
		$send_to = getValue('send_to');
		$result .= interaction_resend($interaction,$send_to);
	}else{
		 if ($tryb != 'print'){
				if ($direction==Interaction::$DIRECTION_IN){
					$result .= form_def_in($interaction);
				}else{
					$result .= form_def_out($interaction);
				}
		 }
	}
}

echo iconv('latin2','UTF-8',$result);
exit();


function save_interaction($id){
	$category_id = getValue('category_id');

	if ($category_id > 0 ){
			Interaction::updateCategoryId($id, $category_id);
	}
	$obrobiony = getValue('obrobiony');
	if ($obrobiony==1){
			Interaction::updateMade($id);
	}

	$reclamation = getValue('reclamation');
	if ($reclamation==1){
			Interaction::updateReclamation($id,1);
	}else{
			Interaction::updateReclamation($id,0);
	}
}

function form_def_out(Interaction $interaction){
	$result = '<input type="button" value="'.FK_EMAIL_PRZDALEJ.'" onClick="reply_doc(\'&action=forward\')">';
	$result .= '&nbsp;&nbsp;<input type="button" value="'.BUTT_SEN_AGAIN.'" onClick="load_navigation(\'doc_navigation.php?id='.$interaction->getObjectId().'&action=resend_form\')">';
	return $result;
}


function form_def_in(Interaction $interaction){
	$lang = $_SESSION['GUI_language'];

	$result = '<input type="button" value="'.FK_EMAIL_PRZDALEJ.'" onClick="reply_doc(\'&action=forward\')">';
	$result .= '&nbsp;&nbsp;<input type="button" value="'.FK_EMAIL_ODPOWIEDZ.'" onClick="reply_doc(\'&action=reply\')">';

	$result .= '<hr>'. FK_EMAIL_KATEGORIA .':
            <select name="category_id" id="category_id" class="date-required">';

	$query2 = "SELECT * FROM coris_fax_in_category  WHERE ID_section=1 ";
	$mysql_result2 = mysql_query($query2) or die(mysql_error());
	$result .= '<option value=\'0\'> ... </option>';
	while ($row2=mysql_fetch_array($mysql_result2)){
			if ($row2['ID'] == $interaction->getCategoryId())
				$result .= '<option value=\''.$row2['ID'].'\' SELECTED>'.( ($lang=='en' && $row2['name_eng'] != '' ) ? $row2['name_eng'] : $row2['name'] ).'</option>';
			else
				$result .= '<option value=\''.$row2['ID'].'\'>'.( ($lang=='en' && $row2['name_eng'] != '' ) ? $row2['name_eng'] : $row2['name'] ).'</option>';
	}

   $result .= '</select>            <br>';

	$case = new CorisCase($interaction->getCaseID());
	if ($case->getClient_id()==7592){
			$qu = "SELECT * FROM coris_signal_dokumenty_interactions  WHERE  ID_interactions='".$interaction->getObjectId()."' " ;
			$mr = mysql_query($qu);
			$lista = array();
			while ($r = mysql_fetch_array($mr)){
				$lista[] = $r['ID_dokument'];
			}
			//$result = '';

			$query = "SELECT * FROM coris_signal_dokumenty  WHERE status=1";
			$mysql_result= mysql_query($query);
			$licznik = 1;
			$llista = array();
			$plista = array();

			while ($rowx = mysql_fetch_array($mysql_result)){
				if ( $licznik%2 )
					$plista[] = '<span style="margin-left:5px;margin-right:20px"><input style="background-color:#CCCCCC" type="checkbox" name="dokumenty[]" value="'.$rowx['ID'].'" '.(in_array($rowx['ID'],$lista) ? 'checked' : '').'>&nbsp;'.$rowx['nazwa'].'</span>';
				else
					$llista[]  = '<span style="margin-left:5px;margin-right:20px"><input  style="background-color:#CCCCCC" type="checkbox" name="dokumenty[]" value="'.$rowx['ID'].'" '.(in_array($rowx['ID'],$lista) ? 'checked' : '').'>&nbsp;'.$rowx['nazwa'].'</span>';

				$licznik++;
			}
			$result .= '<table width="100%"><tr><td width="50%" valign="top">'.implode('<br>',$plista).'</td><td width="50%" valign="top">'.implode('<br>',$llista).'</td></tr></table>';
	}

		$result .= '<div align="left" style="color:red;"><b>Reklamacja</b><input style="background: #cccccc ;"  type="checkbox" name="reclamation_form" id="reclamation_form" value="1" '. ($interaction->getReclamation() ==1 ? 'checked' : '' ) .'>&nbsp;&nbsp;&nbsp;
</div>';

	if ( $interaction->getNew()==1){
		$result .= '<div align=right><b>email obrobiony</b><input style="background: #cccccc ;"  type="checkbox" name="obrobiony"  id="obrobiony" value="1">&nbsp;&nbsp;&nbsp;
		</div>';
	}else if ($interaction->getMade() ==1){
		$result .= '<div align=left>'.FK_EMAIL_DOKOBRPRZEZ.': <b>'.Application::getUserName($interaction->getMadeUserId()).', '.$interaction->getMadeDate().'</b></div>';
	}

     $result .= '<br>
          <div align=center>
          <input type="button" name="but" value="'.FK_EMAIL_DOKPOPR.'" OnClick="save_interaction();">
          </div>
			';
	return $result;
}


function form_resend(Interaction $interaction){
	$result = '<b>'.FK_EMAIL_DOKWYSLPON.'</b> <input checked type="radio" name="send_as" id="send_as" value="'.$interaction->getType().'"> ';
	if ($interaction->getType() == Interaction::$_TYPE_EMAIL)
		$result .= 'Email';

	if ($interaction->getType() == Interaction::$_TYPE_FAX)
		$result .= 'Fax';

	$result .= '<br><b>'.FK_EMAIL_DOKWYSLPONDO.'</b> <input type="text" name="send_to" id="send_to" size="30" value="'.$interaction->getInteractionContact().'">';
	$result .= '<br><br><div align="center"><input type="button" value="'.SEND.'" onClick="load_navigation(\'doc_navigation.php?id='.$interaction->getObjectId().'&action=resend&send_to=\'+document.getElementById(\'send_to\').value )">
	&nbsp;&nbsp;&nbsp;<input type="button" value="'.CANCEL.'" onClick="load_navigation(\'doc_navigation.php?id='.$interaction->getObjectId().'\')">
	</div>';

	return $result;
}


function interaction_resend(Interaction $interaction_old,$send_to){
		//wysylka_ponowana dokumentu;

	  $interaction = new Interaction(null,$interaction_old->getObjectId(),null,null,1);
	  $interaction->setInteractionContact($send_to);

	  $doc = $interaction_old->getDocument();
	  if ($doc instanceof Email){
	  	$interaction->getDocument()->update_to($send_to);
	  }else if ($doc instanceof Fax){
	  	$interaction->getDocument()->update_number($send_to);
	  }
  	  $interaction->store();
  	  $interaction->send(0,$interaction->get_destination());
      $result =  "Document wys³any: ".$interaction->getObjectId();

	  return $result;
}






?>