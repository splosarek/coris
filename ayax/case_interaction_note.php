<?php
//sleep(3);

//ECHO nl2br(print_r($_SERVER,1));
define('UPLOAD_TMP_DIR','../tmp/');

include_once('../include/include_ayax.php');

$id=getValue('id');
$case_id=getValue('case_id');
$action=getValue('action');
$type=getValue('type') != '' ? getValue('type') : 'note';

//echo nl2br(print_r($_REQUEST,1));
$result = '';
if ($case_id>0){
				if ( $action == 'form_edit_save' && $id > 0){
					$result = note_save($case_id,$id,$type);
				}else if ($action == 'new_note_save'){
					$result = new_note_save($case_id,$type);
				}else if ($id > 0){
					$result = note_edit($id,$type);
				}else{
					$result = new_note($case_id,$type);
				}
}else{
	$result = "B³±d case_id=".$case_id;
}

echo iconv('latin2','UTF-8',$result);
exit();


function new_note_save($case_id,$type){
	$internal = getValue('internal') == 1 ? 1 : 0;
	$reclamation = getValue('reclamation') == 1 ? 1 : 0;
	$after_form = getValue('after_form')  > 0  ? getValue('after_form') : 0;

	switch ($type){
		case 'note' :
			$interaction = new Interaction($case_id,0,Interaction::$_TYPE_NOTE);
			$note = new Note();
			break;
		case 'call_in':
			$interaction = new Interaction($case_id,0,Interaction::$_TYPE_CALL,Interaction::$DIRECTION_IN);
			$note = new Call();
			break;
		case 'call_out':
			$interaction = new Interaction($case_id,0,Interaction::$_TYPE_CALL,Interaction::$DIRECTION_OUT);
			$note = new Call();
			break;
	}

	$n_type=1;
	$n_type= getValue('type') == 'note' ? $n_type=1 : $type;
	$n_type= getValue('type') == 'call_out' ? $n_type=2 : $n_type;
	$n_type= getValue('type') == 'call_in' ? $n_type=3 : $n_type;

	$name = iconv('UTF-8','latin2//TRANSLIT//IGNORE',getValue('interaction_name'));
	$contact = iconv('UTF-8','latin2//TRANSLIT//IGNORE',getValue('interaction_contact'));


	$interaction->setInteractionName($name);
	$interaction->setInteractionContact($contact);
	$interaction->setInteractionSubject('');
	$interaction->setReclamation($reclamation);
	$interaction->setInternal($internal);


	$note_txt =  iconv('UTF-8','latin2//TRANSLIT//IGNORE',getValue('note'));

	$file_upload =  isset($_POST['file_upload']) ? $_POST['file_upload'] : null ;
	$file_upload_txt = isset($_POST['file_upload_txt']) ? $_POST['file_upload_txt'] : null;



	$note->setInternal($internal);
	$note->setType($n_type);
	$note->setReclamation($reclamation);
	$note->setBody($note_txt);
	$note->setContact($name);
	$note->setPhone($contact);
	$note->setCreateDate(date('Y-m-d H:i:s'));
	$note->setCreateUser($_SESSION['user_id']);;

	if (is_array($file_upload)){
		foreach ($file_upload As $key => $file){
			if (file_exists(UPLOAD_TMP_DIR.$file) && filesize(UPLOAD_TMP_DIR.$file)>0){
					$txt_note = iconv('UTF-8','latin2',$file_upload_txt[$key]);
					$note->addNoteAttachment(UPLOAD_TMP_DIR.$file,$file,$txt_note);
			}else{
				echo "File error: ".UPLOAD_TMP_DIR.$file;
			}
		}
	}

	$interaction->addDocument($note);
	$interaction->store();

	$result =  "<table width=\"100%\"><tr><td align=\"center\" style=\"color:#dfdfdf;background-color:#6699cc;font-weight:bold;\">".NOTE_ADDED_TO_CASE."</td></tr></table>";
	$result .= "<script> (function(){ init_documents('$case_id'); }).delay(2000);</script>";
	return $result;
}

function note_save($case_id,$id,$type){
	$note_id = getValue('internal') == 1 ? 1 : 0;
	$reclamation = getValue('reclamation') == 1 ? 1 : 0;
	$after_form = getValue('after_form')  > 0  ? getValue('after_form') : 0;


	switch ($type){
		case 'note' :
			$interaction = new Interaction($case_id,$id,Interaction::$_TYPE_NOTE);

			break;
		case 'call_in':
			//$interaction = new Interaction($case_id,0,Interaction::$_TYPE_CALL,Interaction::$DIRECTION_IN);
			//$note = new Call();
			break;
		case 'call_out':
			//$interaction = new Interaction($case_id,0,Interaction::$_TYPE_CALL,Interaction::$DIRECTION_OUT);
			//$note = new Call();
			break;
	}
	 if ($type != 'note') return 'Error Save';

	$note_made =  getValue('note_made') == 1 ? 1 : 0;

	if ($note_made == 1){
		Interaction::updateMade($interaction->getObjectId());
	}

	$result =  "<table width=\"100%\"><tr><td align=\"center\" style=\"color:#dfdfdf;background-color:#6699cc;font-weight:bold;\">".NOTE_WAS_SAVE."</td></tr></table>";
	$result .= "<script> (function(){ init_documents('$case_id'); }).delay(2000);</script>";
	return $result;
}

function  note_edit($id){

	$result = '';
	$raport = '';
	$note_id = $id;

	$row=null;
if ($id>0){
	$interaction = new Interaction(0,$id);
}else{
	echo 'B³±d wykonania: $note_id=0';
	echo "<br>ref. ".$_SERVER['REfferrer'];
	exit();
}

	$case_id = $interaction->getCaseID();
	$note_type = $interaction->getType();
	$direction = $interaction->getDirection();

	$note = $interaction->getDocument();

    $query_case = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention FROM coris_assistance_cases WHERE case_id = '$case_id'";
	if ($_SESSION['new_user']==1){
			$query_case .= " AND `date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) ";
	}

	 if ($mr_result = mysql_query($query_case)){
    	if (mysql_num_rows($mr_result)==0)	die('brak sprawy');
        $row_case = mysql_fetch_array($mr_result);
    }else
        die(mysql_error());



        $result .= '<script language="JavaScript">
        		window.addEvent(\'domready\', function() {
  					$(\'form_note_edit\').addEvent(\'submit\', function(e){
  								//if ($type(e) == \'event\')
  									//e.stop();
									save_new_note2(\'case_document_view\',\'form_note_edit\',\'ayax/case_interaction_note.php?action=form_edit_save\');
									return;
  					});
  					//initloader();
  				});


		function notaka_obrobiona(){
					if (confirm(\'Czy napewno zaznaczyæ notatkê jako obrobion±?\')){
								document.getElementById(\'form_action\').value= \'form_edit_save\';
								save_new_note2(\'case_document_view\',\'form_note_edit\',\'ayax/case_interaction_note.php?action=form_edit_save\');
							//	document.getElementById(\'form_note_edit\').submit();
					}else{
						return false;
					}
	}


			function check() {
				alert(1);
				if ( $(\'interaction_name\') && $(\'interaction_name\').value == "") {
					alert("Proszê wpisaæ rozmówcê");
					$(\'interaction_name\').focus();
					return false;
				}
				if ($(\'note\') && $(\'note\').value == "") {
					alert("Proszê wpisaæ tre¶æ notatki");
					$(\'note\').focus();
					return false;
				}
				return true;
			}

			/*function lock(s) {
				if (s.value == 0 || s.value == 1) {
					$(\'interaction_name\').value = "";
					$(\'interaction_name\').disabled = true;
					$(\'interaction_name\').style.background = \'#cccccc\';
					form1.interaction_contact.value = "";
					form1.interaction_contact.disabled = true;
					form1.interaction_contact.style.background = \'#cccccc\';
				} else {
					$(\'interaction_name\').disabled = false;
					$(\'interaction_name\').style.background = \'#ffffff\';
					form1.interaction_contact.disabled = false;
					form1.interaction_contact.style.background = \'#ffffff\';
				}
			}*/

			window.onload = function() {
				var securedForm = $(\'note\');
				securedForm.onkeydown = registerText;
			}
		</script>
		<form  method="post" enctype="multipart/form-data" name="form_note_edit" id="form_note_edit" onSubmit="return check();">
		<input type="hidden" name="form_action" id="form_action" value="form_edit_save">
		<input type="hidden" name="id" value="'.  $interaction->getObjectId() .'">
		<input type="hidden" name="note_id" value="'.  $interaction->getObjectId() .'">
		<input type="hidden" name="case_id" value="'.$case_id.'">
		';

		$result .= '<div align="center" style="color:#dfdfdf;background-color:#6699cc;font-weight:bold;padding:8px;font-size:17px;">'.$note->getTypeName().'</div>';

        $result .= '<br><br><table cellpadding="0" cellspacing="0" border="0" width="100%">


			<tr><td align="right" width=20%><b>'. AS_CASD_UTW .':</b>&nbsp;</td>
				<td><input type="text" readonly name="date" value="'. $interaction->getDate() .'" style="text-align: center; width: 150px; background: #eeeeee">&nbsp;'. AS_CASD_PRZEZ .': '.  Application::getUserName($interaction->getUserId()).'</td>
			</tr>
			<tr><td align="right" nowrap><b>'. AS_CASD_OSTMOD .'</b>:&nbsp;</td><td>';

          $l_date = '';
          $l_user='';
          $l_date_tmp = $interaction->getLastDate();
          if ($l_date_tmp == '0000-00-00 00:00:00'){
          	$l_date=$l_date_tmp;
            $l_user = Application::getUserName($interaction->getUserId());
          }else{
            $l_date=$row['last_date'];
            $l_user = Application::getUserName($interaction->getLast_UserId());
          }
          $result .= '<input type="text" readonly name="date" value="'. $l_date .'" style="text-align: center; width: 150px; background: #eeeeee">&nbsp;'. AS_CASD_PRZEZ .': '.  $l_user .'</td></tr>
		<tr><td colspan="2">';
        if ($note_type == Interaction::$_TYPE_NOTE){

		}else{
    		$result .=' <br><br><table cellpadding="0" cellspacing="2" border="0" align="center" >
		<tr>
          <td align="right"><small>'. CONTACT .':</small>&nbsp;</td>
          <td><input type="text" name="interaction_name"  readonly class="disabled" id="interaction_name" maxlength="50" size="30" value="'.htmlspecialchars($note->getContact(),ENT_QUOTES ,'ISO-8859-1').'"><td>
          <td align="right"><small>'. PHONE .':</small>&nbsp;</td><td><input readonly class="disabled" type="text" name="interaction_contact"  maxlength="50" size="25"  value="'.htmlspecialchars($note->getPhone(),ENT_QUOTES ,'ISO-8859-1').'">&nbsp;
          </td>
    </table>
    <br>';
}

     $result .= '
     </td></tr>
     <tr><td >&nbsp;</td><td><textarea name="note" id="note" cols="65" rows="12" style="font-family: Verdana; font-size: 8pt;" readonly class="disabled">'.  htmlspecialchars($note->getBody(),ENT_QUOTES ,'ISO-8859-1').'</textarea></td></tr>
        </table>
        <br>';
			$att = $interaction->getDocument()->getAttachments();
            if (count($att->get_list()) > 0 ){
            	$result .= lista_foto($att);
            }
			$result .= '
             <br>
             <table cellpadding="2" cellspacing="2" border="0" align="center" width="500"><tr colspan="2"><td><hr></td><tr>';
			if ($_SESSION['new_user']==0){
            		$result .= ' <tr><td colspan="2" style="color: red;">'.INTERNAL.': <input type="checkbox" value="1"  disabled name="internal" '.($interaction->getInternal()==1 ? 'checked' : '').'></td></tr>  ';
             }
	$result .= ' <tr><td colspan="2" >'.AS_CASES_REKL.': <input type="checkbox" value="1" disabled name="reclamation" '.($interaction->getReclamation() == 1 ? 'checked' : '').'></td></tr>  ';
			if ($interaction->getExternal()==1){ // zmienic na nowa strukture
					if ($interaction->getNew()==1){
						$result .= '<tr><td><b>Nowa notatka zewnêtrzna</b> &nbsp; <input name="note_made" type="checkbox" value="1" onCLick="return notaka_obrobiona();"> Notatka obrobiona</td></tr>';
					}else {
						$result .= '<tr><td><b>Notatka zewnêtrzna obrobiona przez</b>:<br> '.Application::getUserName($interaction->getMadeUserId()).', dnia: '.$interaction->getMadeDate().'</tr>';
					}
			 }
             $result .='
             <tr colspan="2"><td><hr></td><tr>
             </table><br>
  </center>
 <center><input type="button" name="sss" value="Powrót" style="color: black; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: lightgray" title="Powrót" onClick="init_documents('.$case_id.');"></center><br>
'.  $raport;
	$result .= '</form>';
	return $result;
}




function new_note($case_id,$type){

	$title = AS_CASD_NOT3;
	switch ($type){
		case 'note' :
			$title = AS_CASD_NOT3;
			break;
		case 'call_in':
			$title = AS_CASD_ROZMPRZ;
			break;
		case 'call_out':
			$title = AS_CASD_ROZMWYCH;
			break;
	}
	$raport = '';
$result = '';
$result .= '
<script language="JavaScript" type="text/javascript">
	window.addEvent(\'domready\', function() {
  					$(\'form_new_note\').addEvent(\'submit\', function(e){
  								if ($type(e) == \'event\')
  									e.stop();
								save_new_note(\'form_new_note\',\'ayax/case_interaction_note.php?action=new_note_save\');
  					});
  					initloader();
  				});

	function check(s) {
			    if ( $(\'interaction_name\') && $(\'interaction_name\').value == "") {
					alert("'.AS_CASD_PRWPROZM .'");
					$(\'interaction_name\').focus();
					return false;
				}

				if (s.note.value == "") {
					alert("'.AS_CASD_PRWPTRNOT .'");
					s.note.focus();
					return false;
				}
				return true;
	}
</script>
';

$result .= '
<link href="/coris/Styles/style_upload.css" rel="stylesheet" type="text/css">
		  <form action="ayax/case_interaction_note.php?action=new_note_save" method="post" id="form_new_note" >
			<input type="hidden" name="type"  id="type"  value="'.$type.'">
			<input type="hidden" name="form_action" value="note_add_save">
			<input type="hidden" name="case_id" value="'.$case_id.'">
			<div align="center" style="color:#dfdfdf;background-color:#6699cc;font-weight:bold;padding:8px;font-size:17px;">'.$title.'</div>
			<br><br>
<div align="center">';
if ($type=='note'){

}else{
    $result .=' <table cellpadding="0" cellspacing="2" border="0" align="center" width="500">
		<tr>
          <td align="right"><small>'. CONTACT .':</small>&nbsp;</td>
          <td><input type="text" name="interaction_name"   id="interaction_name" maxlength="50" size="30"><td>
          <td align="right"><small>'. PHONE .':</small>&nbsp;</td><td><input type="text" name="interaction_contact"  maxlength="50" size="25">&nbsp;
          </td>
    </table>
    <br>';
}
	$result .=' <textarea name="note" id="note" cols="80" rows="22" style="font-family: Verdana; font-size: 8pt;"></textarea>
    <br><br>';
$result .= '<div align="left" style="width:500px">
					<a href="#" id="demo-attach">'.ATTACH_FILE .'</a><ul id="demo-list"></ul>
			</div>';

$result .=  '<table cellpadding="2" cellspacing="2" border="0" width="500"><tr colspan="2"><td><hr></td><tr>';
      if ($_SESSION['new_user']==0){
         $result .= ' <tr><td colspan="2" style="color: red;">'.INTERNAL.': <input type="checkbox" value="1" name="internal"></td></tr>  ';
      }
	$result .= ' <tr><td colspan="2" >'.AS_CASES_REKL.': <input type="checkbox" value="1" name="reclamation"></td></tr> ';

$result .= '<tr colspan="2"><td><hr></td><tr></table>
<br>
</div>
<div align="center"><input type="submit" name="ssss" value="'. SAVE .'" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="'. AS_CASD_ZAPNOT .'"  onClick="return check(this.form);" ></div><br><br>

<div align="center"><input type="button" name="sss" value="Powrót" style="color: black; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: lightgray" title="Powrót" onClick="init_documents('.$case_id.');"></div>
'. $raport ;
$result .= '</form>';
return $result;
}

function lista_foto($attachments){
	$result = '
	<div style="height:150px;width:600px;overflow:auto">
	<div align="left"><b>Dokumenty:</b></div><table width="550" border=0>';
	foreach ($attachments->get_list() As $position){

		$result .= '<tr><td valign=top width=150 align="center">
		<a href="getAttachment.php?id='.$position->getObjectID().'&amp;action=view" title="view '.$position->getName().'" target="_blank">'.$position->getName().'</a>
		&nbsp;<a href="getAttachment.php?id='.$position->getObjectID().'&amp;action=download" title="download '.$position->getName().'"><img src="graphics/download.png" border="0"></a>
		</td><td width="400" valign=top align=left>'.nl2br($position->getNote()).'</td></tr>';
		$result .='<tr><td colspan=2><hr></td></tr>';
	}

	$result .= '</table></div>';
	return $result;

}



?>