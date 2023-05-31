<?php include('include/include.php');

/*
GEN_email_lists.php?case_id=<? echo $case_id?>&form=email_to
*/

$case_id = getValue('case_id');
$form = getValue('form');

html_start();

?>
<script language="JavaScript" type="text/JavaScript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
   window.open(theURL,winName,features);
}

function click_email(email){
	formant = opener.document.getElementById('<?php echo $form;?>');
	if (formant){
		if (formant.value==''){
			formant.value = email;
			
		}else
			formant.value = formant.value + ';'+email		
			
	}
	
	window.close();
	
}
</script>




<?php
$lista_email = array();
$lista_email_info = array();

$query = "SELECT contactno, contactdesc FROM coris_assistance_cases_contacts  WHERE case_id='$case_id' AND type_id=3 ";
$mysql_result = mysql_query($query);

while ($row=mysql_fetch_array($mysql_result)){

	$email = strtolower($row['contactno']);
	$lista_email[] = $email;
	$tmp = array($email => $row['contactdesc']);
	$lista_email_info = array_merge($lista_email_info,$tmp);
}
	
$query = "SELECT from_email,`to`,cc FROM coris_email_in,coris_email_in_to_case WHERE coris_email_in_to_case.ID_case='$case_id' AND coris_email_in.ID=coris_email_in_to_case.ID_email_in";

$mysql_result = mysql_query($query);
while ($row=mysql_fetch_array($mysql_result)){		
		$lista1 = mail_analiza($row['from_email']);			
		$lista2 = mail_analiza($row['to']);	
		$lista3 = mail_analiza($row['cc']);	
		$lista = array_merge($lista1,$lista2,$lista3);
		$lista_email = array_merge_recursive($lista_email,$lista);
}


$query = "SELECT email_to,email_cc FROM coris_email_out WHERE  ID_case='$case_id'";

$mysql_result = mysql_query($query);
while ($row=mysql_fetch_array($mysql_result)){		
		$lista1 = mail_analiza($row['email_to']);	
		$lista2 = mail_analiza($row['email_cc']);	
		
		$lista = array_merge($lista1,$lista2);
		$lista_email = array_merge_recursive($lista_email,$lista);
}

$query = " SELECT  email  FROM coris_contrahents,coris_assistance_cases WHERE coris_assistance_cases.case_id='$case_id' AND coris_contrahents.contrahent_id=coris_assistance_cases.client_id  ";
$mysql_result = mysql_query($query);
$row=mysql_fetch_array($mysql_result);
$lista1 = mail_analiza($row['email']);
foreach ($lista1 As $pozycja){
	$lista_email_info[$pozycja] = FK_EMAIL_TU;
	
}
$lista_email = array_merge_recursive($lista_email,$lista1);

$query = " SELECT  coris_contrahents.email,coris_contrahents.short_name  FROM coris_contrahents,coris_assistance_cases_expenses As ce WHERE ce.case_id='$case_id' AND coris_contrahents.contrahent_id=ce.contrahent_id  AND email <> '' ";
$mysql_result = mysql_query($query);
while ($row=mysql_fetch_array($mysql_result)){

	$lista1 = mail_analiza(strtolower($row['email']));
	foreach ($lista1 As $pozycja){
		$lista_email_info[$pozycja] = AS_CASD_WYKKR.': '.$row['short_name'];	
	}
			
}
$lista_email = array_merge_recursive($lista_email,$lista1);
sort($lista_email);
$lista_email = array_unique($lista_email);



function mail_analiza($email){
	
	$email = strtolower($email);
	
	$emails = str_replace(';',',',$email);
	$tmp = explode(',',$emails);
	$ilosc = count($tmp);
	$res = array();
	
	if ($ilosc==1){
		if (is_email($email))
			$res[] =  $email;		
	}else{				
		for($i=0;$i<$ilosc;$i++){
				if (is_email($tmp[$i])){
					$res[] = $tmp[$i];					
				}			
		}		
	}	
	return $res;
}
?>

<table style="table-layout:fixed" border="0" cellpadding="1" cellspacing="1" width="800">
	<tr bgcolor="#DFDFFF">
		<th nowrap width="25">&nbsp;</th>
		<th nowrap width="20">&nbsp;</th>
		<td nowrap width="160"><?= EMAIL ?></td>	
		<td nowrap width="350"><?= AS_CASADD_OPIS ?></td>		
	</tr>
	<?php 				
		foreach ($lista_email as $email){
	
	echo '<tr bgcolor="#FFFFCA">
		<td nowrap><input type="button" value="+" style="line-height: 4pt; height: 12pt; width: 20px" onclick="click_email(\''.$email.'\')"></td>    		
		<td nowrap><div align="right" style="font-weight: bold"></div></td>
		
		<td nowrap>'.$email.'</td>
		<td nowrap>'.(isset($lista_email_info[$email]) ? $lista_email_info[$email] : '').'</td>
	</tr>';
	}
	?>  
</table>
</body>
</html>
<?php
html_stop2();
?>