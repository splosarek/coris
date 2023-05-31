<?php
//sleep(3);

//ECHO nl2br(print_r($_SERVER,1));
define('UPLOAD_TMP_DIR','../tmp/');

include_once('../include/include_ayax.php');
include_once('../lib/lib_ace.php');

$id=getValue('id');
$case_id=getValue('case_id');
$action=getValue('action');
$confirm=getValue('confirm')==1? 1 : 0;
$type=getValue('type') != '' ? getValue('type') : 'sms';

//echo nl2br(print_r($_REQUEST,1));
$result = '';
if ($case_id>0){
				if ( $action == 'form_edit_save' && $id > 0){
					$result = sms_save($case_id,$id,$type);
				}else if ($action == 'new_sms_save'){
					$result = new_sms_save($case_id,$type,$confirm);
				}else if ($id > 0){
					$result = sms_edit($id,$type);
				}else{
					$result = new_sms($case_id,$type,$confirm);
				}
}else{
	$result = "B³±d case_id=".$case_id;
}

echo iconv('latin2','UTF-8',$result);
exit();


function new_sms_save($case_id,$type,$confirm){

    $result = '';
	$internal = getValue('internal') == 1 ? 1 : 0;
	$reclamation = getValue('reclamation') == 1 ? 1 : 0;
	$after_form = getValue('after_form')  > 0  ? getValue('after_form') : 0;

	$interaction = new Interaction($case_id,0,Interaction::$_TYPE_SMS,INTERACTION::$DIRECTION_OUT);
			$sms = new SMS();

	$name = iconv('UTF-8','latin2//TRANSLIT//IGNORE',getValue('interaction_name'));
	$contact = str_replace( ' ', '', trim(iconv('UTF-8','latin2//TRANSLIT//IGNORE',getValue('interaction_contact'))) );

	$interaction->setInteractionName($name);
	$interaction->setInteractionContact($contact);
	$interaction->setInteractionSubject('');
	$interaction->setReclamation($reclamation);
	$interaction->setInternal($internal);
	if ($confirm==1)
			$interaction->setInteractionSubject('Potwierdzenie rejestracji');

	$sms_txt =  iconv('UTF-8','latin2//TRANSLIT//IGNORE',getValue('sms'));
	$sms->set_direction(Document::$DIRECT_OUT);
	$sms->setInternal($internal);
	$sms->setType(SMS::$TYPE_SMS_OUT);
	$sms->setReclamation($reclamation);
	$sms->setBody($sms_txt);
	$sms->setContact($name);
	$sms->setPhone($contact);
	$sms->setCreateDate(date('Y-m-d H:i:s'));
	$sms->setCreateUser($_SESSION['user_id']);;
	if ($confirm==1)
			$sms->setName('Potwierdzenie rejestracji');

	$interaction->addDocument($sms);
	$interaction->store();

	$prefix= '';
	$list_tow_prefix_1 = array(6,7,8,11,2201); // lista TU (EUROPA) prefix dla SMS 1 - dla innych zgodnie z definicj± w konfiguracji
	$case= new CorisCase($case_id);
	$client_id = $case->getClient_id();
	if ( in_array($client_id, $list_tow_prefix_1) )
		$prefix=1;
		//$query = "INSERT INTO coris_sms_out_spool  SET `date`=now(),
    	$query = "INSERT INTO store_coris_ssa.coris_sms_out_spool  SET `date`=now(),
    	ID_document_ssa='".$sms->getObjectId()."',`to`='".addslashes(stripslashes($name))."',
    	prefix='$prefix',
    	nr='".addslashes(stripslashes($contact))."',message='".addslashes(stripslashes($sms_txt))."'";

    	$mysql_result = mysql_query($query);
      	if (!$mysql_result){
      	//	echo "QE:".$query."\n\n<br>\n\n".mysql_error();
          	mail('krzysiek@evernet.com.pl','CORIS',$query."\n\n".$mysql_result."\n\n".mysql_error());
      	}

	//$result .= nl2br(print_r($interaction,1));
	//$result .= '<hr>'.nl2br(print_r($interaction->getDocument(),1));
	$result .=  "<table width=\"100%\"><tr><td align=\"center\" style=\"color:#dfdfdf;background-color:#6699cc;font-weight:bold;\">".SMS_ADDED_TO_CASE."</td></tr></table>";
	$result .= "<script> (function(){ init_documents('$case_id'); }).delay(2000);</script>";
	return $result;
}

function sms_save($case_id,$id,$type){
	$sms_id = getValue('internal') == 1 ? 1 : 0;
	$reclamation = getValue('reclamation') == 1 ? 1 : 0;
	$after_form = getValue('after_form')  > 0  ? getValue('after_form') : 0;



	$interaction = new Interaction($case_id,$id,Interaction::$_TYPE_SMS);

	$sms_made =  getValue('obrobiony') == 1 ? 1 : 0;

	if ($sms_made == 1){
		Interaction::updateMade($interaction->getObjectId());
	}


	Interaction::updateReclamation($interaction->getObjectId(), $reclamation);
	if ($interaction-> getDirection() == Interaction::$DIRECTION_IN ){
			$category_id = getValue('category_id')  > 0  ? getValue('category_id') : 0;
			Interaction::updateCategoryId($interaction->getObjectId(), $category_id);
	}

	$result =  "<table width=\"100%\"><tr><td align=\"center\" style=\"color:#dfdfdf;background-color:#6699cc;font-weight:bold;\">".sms_WAS_SAVE."</td></tr></table>";
	$result .= "<script> (function(){ init_documents('$case_id'); }).delay(2000);</script>";
	return $result;
}

function  sms_edit($id){

	$result = '';
	$raport = '';
	$sms_id = $id;

	$row=null;
if ($id>0){
	$interaction = new Interaction(0,$id);
}else{
	echo 'B³±d wykonania: $sms_id=0';
	echo "<br>ref. ".$_SERVER['REfferrer'];
	exit();
}

	$case_id = $interaction->getCaseID();
	$sms_type = $interaction->getType();
	$direction = $interaction->getDirection();

	$sms = $interaction->getDocument();

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

  						$(\'form_sms_edit\').addEvent(\'submit\', function(e){
  								save_new_sms2(\'case_document_view\',\'form_sms_edit\',\'ayax/case_interaction_sms.php?action=form_edit_save\');
  							//	if ($type(e) == \'event\')
  								//	e.stop();
  								return;

  					});
  					//initloader();
  				});


		function notaka_obrobiona(){
					if (confirm(\'Czy napewno zaznaczyæ notatkê jako obrobion±?\')){
								document.getElementById(\'form_action\').value= \'form_edit_save\';
								save_new_sms(\'form_sms_edit\',\'ayax/case_interaction_sms.php?action=form_edit_save\');
							//	document.getElementById(\'form_sms_edit\').submit();
					}else{
						return false;
					}
	}


			function check() {


				return true;
			}



			window.onload = function() {
				var securedForm = $(\'sms\');
				securedForm.onkeydown = registerText;
			}
		</script>
		<form  method="post" enctype="multipart/form-data" name="form_sms_edit" id="form_sms_edit" onSubmit="return check();">
		<input type="hidden" name="form_action" id="form_action" value="form_edit_save">
		<input type="hidden" name="id" value="'.  $interaction->getObjectId() .'">
		<input type="hidden" name="sms_id" value="'.  $interaction->getObjectId() .'">
		<input type="hidden" name="case_id" value="'.$case_id.'">
		';

		$result .= '<div align="center" style="color:#dfdfdf;background-color:#6699cc;font-weight:bold;padding:8px;font-size:17px;">'.$sms->getTypeName().'</div>';

        $result .= '<br><br><table cellpadding="0" cellspacing="0" border="0" width="70%">


			<tr><td align="right" width=20%><b>'. AS_CASD_UTW .':</b>&nbsp;</td>
				<td><input readonly type="text" name="date" value="'. $interaction->getDate() .'" style="text-align: center; width: 150px; background: #eeeeee">&nbsp;'. AS_CASD_PRZEZ .': '.  Application::getUserName($interaction->getUserId()).'</td>
			</tr>';

		$result .= '<tr><td colspan="2">';

    		$result .=' <br><br><table cellpadding="0" cellspacing="2" border="0" align="center" >
		<tr>
		<td align="right"><small>'. PHONE .':</small>&nbsp;</td><td><input readonly class="disabled" type="text" name="interaction_contact"  maxlength="50" size="25"  value="'.htmlspecialchars($sms->getPhone(),ENT_QUOTES ,'ISO-8859-1').'">&nbsp;
          <td align="right"><small>'. CONTACT .':</small>&nbsp;</td>
          <td><input type="text" name="interaction_name"  readonly class="disabled" id="interaction_name" maxlength="50" size="30" value="'.$sms->getContact().'"><td>

          </td>
    </table>
    <br>';


     $result .= '
     </td></tr>
     <tr><td >&nbsp;</td><td><textarea name="sms" id="sms" cols="70" rows="4" style="font-family: Verdana; font-size: 8pt;" readonly class="disabled">'.  $sms->getBody().'</textarea></td></tr>
        </table>
        <br>';

			$result .= '
             <br>
             <table cellpadding="2" cellspacing="2" border="0" align="center" width="500"><tr colspan="2"><td><hr></td><tr>';

			if ($interaction->getDirection() == INTERACTION::$DIRECTION_IN){
				$result .= '<tr><td colspan="2">';
				$result .=  FK_EMAIL_KATEGORIA .':
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

			$result .= '<div align="left" style="color:red;"><b>'.AS_CASES_REKL.'</b><input style="background: #cccccc ;"  type="checkbox" name="reclamation" id="reclamation" value="1" '. ($interaction->getReclamation() ==1 ? 'checked' : '' ) .'>&nbsp;&nbsp;&nbsp;
</div>';

	if ( $interaction->getNew()==1){
		$result .= '<div align=right><b>dokument obrobiony</b><input style="background: #cccccc ;"  type="checkbox" name="obrobiony"  id="obrobiony" value="1">&nbsp;&nbsp;&nbsp;
		</div>';
	}else if ($interaction->getMade() ==1){
		$result .= '<div align=left>'.FK_EMAIL_DOKOBRPRZEZ.': <b>'.Application::getUserName($interaction->getMadeUserId()).', '.$interaction->getMadeDate().'</b></div>';
	}


			$result .= '</td></tr>
			<tr colspan="2"><td><hr></td><tr>';

			}

//			if ($_SESSION['new_user']==0){
  //          		$result .= ' <tr><td colspan="2" style="color: red;">'.INTERNAL.': <input type="checkbox" value="1"  disabled name="internal" '.($interaction->getInternal()==1 ? 'checked' : '').'></td></tr>  ';
    //         }
		if ($interaction->getDirection() == INTERACTION::$DIRECTION_OUT){
			$result .= ' <tr><td colspan="2" >'.AS_CASES_REKL.': <input type="checkbox" value="1" name="reclamation" '.($interaction->getReclamation() == 1 ? 'checked' : '').'></td></tr>  ';
             $result .='<tr colspan="2"><td><hr></td><tr>';
		}
           $result .='</table><br>
  </center>

  <div align="center"><input type="submit" name="ssss" value="'. SAVE.'" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="'. AS_CASD_ZAPNOT .'" ></div>
 <br><center><input type="button" name="sss" value="Powrót" style="color: black; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: lightgray" title="Powrót" onClick="init_documents('.$case_id.');"></center><br>
'.  $raport;
	$result .= '</form>';
	return $result;
}




function new_sms($case_id,$type,$confirm){

	$title = 'SMS';
	$interaction_name='';
	$interaction_contact='';
	$message='';
	if ($confirm==1){
		$case = new CorisCase($case_id);

		$title .= ' Potwierdzenie rejestracji';
		$interaction_name='';
				$zmiany=array(' ' => '','-' => '','+' => '00');
		$interaction_contact= strtr($case->getPaxmobile(),$zmiany);
		$case_program = ACECase::getCaseProgram($case_id);
		$nr_tel =$case_program['tel'];

		//$message='Potwierdzamy rejestracjê przedmiotowej szkody dotycz±cej ubezpieczonego: '.$case->getPaxname().' '.$case->getPaxsurname().' pod numerem sprawy '.$case->getFullNumber().'. W dalszym procesie likwidacji prosimy o kontakt emailowy lub telefoniczny z biurem April Assistance Polska pod nr 22 568 98 22.';
		$message='Witamy. Potwierdzamy rejestracjê szkody na nazwisko '.$case->getPaxname().' '.$case->getPaxsurname().' pod numerem '.$case->getFullNumber().'. Kontakt z Centrum Pomocy pod nr '.$nr_tel.'.';
		//
	}
	$raport = '';
$result = '';
$result .= '
<script language="JavaScript" type="text/javascript">
	window.addEvent(\'domready\', function() {
  					$(\'form_new_sms\').addEvent(\'submit\', function(e){
  								if ($type(e) == \'event\')
  									e.stop();
  								save_new_sms(\'form_new_sms\',\'ayax/case_interaction_sms.php?action=new_sms_save\');

  					});
  					initloader();
  				});

	function check(s) {
			    if ( $(\'interaction_contact\') && $(\'interaction_contact\').value == "") {
					alert("'.AS_CASD_PRWPNRTEL .'");
					$(\'interaction_contact\').focus();
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
		  <form action="ayax/case_interaction_sms.php?action=new_sms_save" method="post" id="form_new_sms" >
			<input type="hidden" name="type"  id="type"  value="'.$type.'">
			<input type="hidden" name="form_action" value="sms_add_save">
			<input type="hidden" name="confirm" value="'.$confirm.'">
			<input type="hidden" name="case_id" value="'.$case_id.'">
			<div align="center" style="color:#dfdfdf;background-color:#6699cc;font-weight:bold;padding:8px;font-size:17px;">'.$title.'</div>
			<br><br>
<div align="center">';

    $result .=' <table cellpadding="0" cellspacing="2" border="0" align="center" width="500">
		<tr>
		<td align="right"><small>'. PHONE .':</small>&nbsp;</td><td><input type="text" name="interaction_contact" id="interaction_contact"  maxlength="50" size="25" value="'.$interaction_contact.'">&nbsp;</td>
           <td align="right"><small>'. CONTACT .':</small>&nbsp;</td>
          <td><input type="text" name="interaction_name"   id="interaction_name" maxlength="50" size="30" value="'.$interaction_name.'"><td>

    </table>
    <br>';

	$result .=' <textarea name="sms" id="sms" cols="70" rows="4" style="font-family: Verdana; font-size: 8pt;">'.$message.'</textarea>
    <br><br>';


$result .=  '<table cellpadding="2" cellspacing="2" border="0" width="500"><tr colspan="2"><td><hr></td><tr>';
    //  if ($_SESSION['new_user']==0){
     //    $result .= ' <tr><td colspan="2" style="color: red;">'.INTERNAL.': <input type="checkbox" value="1" name="internal"></td></tr>  ';
     // }
	$result .= ' <tr><td colspan="2" >'.AS_CASES_REKL.': <input type="checkbox" value="1" name="reclamation"></td></tr> ';

$result .= '<tr colspan="2"><td><hr></td><tr></table>
<br>
</div>
<div align="center"><input type="submit" name="ssss" value="'. SEND .'" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="'. AS_CASD_ZAPNOT .'"  onClick="return check(this.form);" ></div><br><br>

<div align="center"><input type="button" name="sss" value="Powrót" style="color: black; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: lightgray" title="Powrót" onClick="init_documents('.$case_id.');"></div>
'. $raport ;
$result .= '</form>';
return $result;
}



?>