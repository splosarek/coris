<?php 


include_once('../include/include_ayax.php');

$case_id=getValue('case_id');
$tryb=getValue('tryb');
$element=getValue('element');


$result = '';

if ($tryb == 'list'){
	$result .=  form($case_id,$tryb,$element);
}else if ($tryb == 'search'){
	$result .=  form_search($tryb,$element);
}	
echo iconv('latin2','UTF-8',$result);
exit();


function form_search($tryb,$element){
	
		$result = '<iframe src="GEN_contrahents_select_frameset.php?ndocuments=1&tryb='.$tryb.'" width=550 height=420></iframe>';
		
		
		
		return $result ;
		
}

function form($case_id,$tryb,$element){
	
	
	$result = '
<script>
function click_email(email){
	formant = $(\''.$element.'\');
	if (formant){
		if (formant.value==\'\'){
			formant.value = email;
			
		}else
			formant.value = formant.value + \';\'+email;		

		myWindow.close();
	}
}
</script>
	';
	
	
$lista_email = array();

/*
 * Kontakty z paraametrow sprawy sprawy
 */
	$query = "SELECT contactno, contactdesc FROM coris_assistance_cases_contacts  WHERE case_id='$case_id' AND type_id=3 ";
	$mysql_result = mysql_query($query);

	while ($row=mysql_fetch_array($mysql_result)){
		$email = strtolower($row['contactno']);
		$desc = $row['contactdesc'];
		
		$lista_email[] = array('email' => $email, 'desc' => $desc);
	}
	
/*
 * Kontakty z historycznych maili
 */	
	$lista = Interaction::getEmailContact($case_id);
	
	foreach ($lista As $email=>$desc){
		$lista_email[] = array('email' => $email, 'desc' => $desc);
	}

/*
 * TU
 */	
	
	$query = " SELECT  email  FROM coris_contrahents,coris_assistance_cases WHERE coris_assistance_cases.case_id='$case_id' AND coris_contrahents.contrahent_id=coris_assistance_cases.client_id  ";
	$mysql_result = mysql_query($query);
	$row=mysql_fetch_array($mysql_result);
	$lista1 = Interaction::emailAdressExplode($row['email']);
	foreach ($lista1 As $email){		
		$lista_email[] = array('email' => $email, 'desc' => FK_EMAIL_TU);		
	}

	
/*
 * Kontrahenci
 */	
	
	$query = " SELECT  coris_contrahents.email,coris_contrahents.short_name  FROM coris_contrahents,coris_assistance_cases_expenses As ce WHERE ce.case_id='$case_id' AND coris_contrahents.contrahent_id=ce.contrahent_id  AND email <> '' ";
	$mysql_result = mysql_query($query);
	while ($row=mysql_fetch_array($mysql_result)){

	$lista1 =  Interaction::emailAdressExplode(strtolower($row['email']));
	foreach ($lista1 As $email){
			$lista_email[] = array('email' => $email, 'desc' => AS_CASD_WYKKR.$row['short_name']);			
	}
			
}
	
	
$result .= '<table  border="0" cellpadding="1" cellspacing="1" width="600">
	<tr bgcolor="#DFDFFF">
		<th nowrap width="25">&nbsp;</th>
		<th nowrap width="25">&nbsp;</th>
		<td nowrap width="200">'. EMAIL .'</td>	
		<td nowrap width="350">'. AS_CASADD_OPIS .'</td>		
	</tr>';
				
  foreach ($lista_email as $pozycja){
	
	$result .='<tr bgcolor="#FFFFCA">
		<td nowrap><input type="button" value="+" style="line-height: 4pt; height: 12pt; width: 20px" onclick="click_email(\''.$pozycja['email'].'\')"></td>    		
		<td nowrap><div align="right" style="font-weight: bold"></div></td>
		
		<td nowrap style="font-size:11px">'.$pozycja['email'].'</td>
		<td nowrap style="font-size:11px">'.$pozycja['desc'].'</td>
	</tr>';
	}
	  
	$result .= '</table>';
 	
	return $result;
}




?>