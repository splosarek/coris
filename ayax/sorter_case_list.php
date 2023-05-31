<?php


include_once('../include/include_ayax.php');




$result  = lista();
	
echo iconv('latin2','UTF-8',$result);
exit();



function lista(){
	
$case_number=getValue('case_number');
$country=getValue('country');
$name=getValue('name');

$name = iconv('UTF-8','latin2',$name);	
$country_id=getValue('country_id') ;	
$branch_id=getValue('branch_id') ;	
$search=getValue('search') ;	



if ($search != 1 ){
$result = '
<table WIDTH="355" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. FK_EMAIL_SPR .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
    <form name="form_case_list" id="form_case_list" method="post">
    <input type="hidden" name="search" value="1">
    <input type="hidden" name="branch_id" value="'.$branch_id.'">
    <table width="100%" border="0" cellpadding="2" cellspacing="2">
    
      <tr>
        <td align=right>'. FK_EMAIL_NR .':&nbsp; </td>
        <td align=left><input name="case_number" type="text" size="10" maxlength="30" style="width:80px" value="'.  $case_number .'">
          &nbsp;&nbsp;&nbsp;'. SURNAME .':&nbsp;
          <input name="name" type="text" size="18" maxlength="50" style="width:80px" value="'. $name .'"></td>
        </tr>
      <tr>
        <td align=right>'. COUNTRY .':&nbsp;</td>
        <td align=left>
				   <input name="country_id" type="text" size="2" maxlength="2" style="width:25px;" onblur="znajdz_kraj();" value="'. $country_id .'"> &nbsp;';

    $result .= Application :: countryList($country_id, $_SESSION['GUI_language'], "country", 'style="font-size: 7pt;" onchange="country_id.value=this.value"');

    $result .= '
				&nbsp;&nbsp;   <span align=right>
				   <input name="Szukaj" type="submit" id="Szukaj2"  style="font-size:10px" value="'. SEARCH .'"></span>
			</td>
        </tr>
      
    </table>
    </form>
    </td>
  </tr>
</table>';
}
$result .= '<div id="case_list_result">
<table  WIDTH="355"  cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>
		<th width="100" nowrap>'. CASENO .'</th>
		<th align="center" width="150">'. AS_CASADD_POSZK .'</th>
		<th width="70">'. FK_EMAIL_DATOTW .'</th>
		
	</tr>';
	
	
	
	$var ='';
	if ($case_number<>''){
		if (strpos($case_number,'/')){
			$tmp = explode('/',$case_number);
			
			$var .= " AND number = '".$tmp[0]."' AND year='".$tmp[1]."' ";
		
		}else{
			$var .= " AND number = '$case_number' ";
		}
	}
	
	if ($name<>''){
		$var .= " AND (paxsurname LIKE '%$name%' OR paxname LIKE '%$name%')";
	}
	
	if ($country<>''){
		$var .= " AND  country_id='$country' ";
	}
	
	//$query_files = "SELECT * FROM coris_fax_in ORDER BY ID DESC";
	$limit='';
	//if ( $var=='' )//&& $_SERVER['REQUEST_METHOD'] == 'GET'
		$limit = ' LIMIT 10';

	 if (isset($_SESSION['coris_branch']) &&  ($_SESSION['coris_branch'] == 2 || $_SESSION['coris_branch'] == 3) ){
		$var .= " AND  (coris_branch_id  ='2' OR coris_branch_id  ='3' )";
	}else if ( isset($_SESSION['coris_branch']) &&  $_SESSION['coris_branch'] == 1 ){		
		if ($branch_id == 1)		
			$var .= " AND  coris_branch_id  ='1' ";
		if ($branch_id == 2)	
			$var .= " AND  (coris_branch_id  ='2' || coris_branch_id  ='3') ";
	}		
		
	$query = "SELECT case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date, watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled, reclamation, status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete, status_settled, attention FROM coris_assistance_cases WHERE active = 1 $var ORDER BY year DESC, number desc $limit";
//	echo $query;
	$mysql_result = mysql_query($query) or die(mysql_error());
	$i=0;	
	while ($row = mysql_fetch_assoc($mysql_result)){
		$col1 = ($i % 2) ? "#e9e9e9" : "#dddddd";
		$result .= '<tr bgcolor="'. $col1 .'" onmouseover="this.bgColor=\'#ced9e2\';" onmouseout="this.bgColor=\''. $col1 .'\';" >
		  		<td align="center"><input type="button" value="&gt;" title="'.FK_FAX_DOLDOKDOSPR.'" style="width: 20px" onClick="select_case('. $row['case_id'] .' )"></td>	  		
		  		<td align="center" nowrap style=" cursor: hand;" title="'.FK_FAX_PRZDOSPR.'" onclick="open_case(\''.$row['case_id'].'\',\'casewindow'. $row['case_id'] .'\');"><span class="style4">'.$row['number'].'/'.$row['year'].'</span></td>
		  		<td align="center">'.$row['paxsurname'].' '.$row['paxname'].'</td>
	
		  		<td align="center" nowrap>'.$row['date'].'</td>
		  		 
		  	</tr>';
		  	$i++;
  }

  $result .= '</table></div>
	</td>
  </tr>
</table>';

  if ($search != 1 ){
$result .= '<script>

function znajdz_kraj(){
		w =  document.form_case_list.country_id.value
		warunek = w.toUpperCase();
		document.form_case_list.country_id.value = warunek;
		ilosc= document.form_case_list.country.length;
		zm=0;
		for (i=0;i<ilosc;i++){
			if (document.form_case_list.country.options[i].value == warunek){
					document.form_case_list.country.selectedIndex = i;
					zm=1
			}
		}
		if (!zm)
			alert(\''. FK_EMAIL_BRAKKROSYMB .': \' + warunek )
}

function dolacz_fax(caseid,case_name,klient_id){
 if (parent.document.frame_preview.document.form1.fax_id)	{
	if (parent.document.frame_preview.document.form1.fax_id.value>0 && confirm(\''. FK_FAX_CZYCHDOLDOKDOSPR .'\')){
		parent.document.frame_preview.document.form1.case_id.value = caseid;
		parent.document.frame_preview.document.form1.case_name.value = case_name;		
		parent.document.frame_preview.add_to_case(klient_id);
	}
	if (parent.document.frame_preview.document.form1.fax_id.value == 0)
		alert(\''. FK_FAX_PRWSKDOKDODOL .'\');
 }else if (parent.document.frame_preview.document.form1.email_id){
 	if (parent.document.frame_preview.document.form1.email_id.value>0 && confirm(\''. FK_EMAIL_CZYCHCESZDOLEMAILDOSPR .'\')){
		parent.document.frame_preview.document.form1.case_id.value = caseid;
		parent.document.frame_preview.document.form1.case_name.value = case_name;		
		parent.document.frame_preview.add_to_case(klient_id);
	}
	if (parent.document.frame_preview.document.form1.email_id.value == 0)
		alert(\''. FK_FAX_PRWSKDOKDODOL .'\');
 }else{
 		alert(\''. FK_FAX_PRWSKDOKDODOL .'\');
 }
}
  					
		$(\'form_case_list\').addEvent(\'submit\', function(e){  	  		  																 			
					if ($type(e) == \'event\')		
							e.stop();					
					save_form(\'case_list_result\',\'form_case_list\',\'ayax/sorter_case_list.php\');   														
		});  								 
</script>';
}	
	return $result ; 
}

?>