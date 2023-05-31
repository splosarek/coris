<?php require_once('include/include.php'); 

include('lib/lib_case.php');
include_once('lib/lib_vig.php');



include_once('include/strona.php'); 

html_start();

echo naglowek();


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	
	echo wyniki_wyszukiwania();
}else{
	
	
	
}




function naglowek(){
	
	
	$search_sprawa = getValue('search_sprawa');
	$status_payment = intval(getValue('status_payment'));
	$search_kwota = str_replace(',', '.', getValue('search_kwota'));
	
	$decyzja_data_od = getValue('decyzja_data_od');
	$decyzja_data_do = getValue('decyzja_data_do');
	
	$platnosc_data_od = getValue('platnosc_data_od');
	$platnosc_data_do = getValue('platnosc_data_do');
	
	$zwrot_data_od = getValue('zwrot_data_od');
	$zwrot_data_do = getValue('zwrot_data_do');
	
	$result ='<form method="post"> <table WIDTH=770 cellpadding="1" cellspacing="0" border="1" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>Compensa roszczenia p³atno¶ci</strong>
    </td></tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
          <tr>                     
            <td align=right valign="top"><b>Sprawa:</b></td><td><input name="search_sprawa" type="text" size="19" maxlength="200" value="'.$search_sprawa.'"></td>
           <td >&nbsp;</td>
           <td align=right valign="top" nowrap><b>Decyzja data od:</b>&nbsp;</td>             
              <td><input name="decyzja_data_od" id="decyzja_data_od" type="text" size="11" maxlength="10" value="'.$decyzja_data_od.'" >
              &nbsp;&nbsp;&nbsp;<b>do</b>: 
              <input name="decyzja_data_do" id="decyzja_data_do" type="text" size="11" maxlength="10" value="'.$decyzja_data_do.'" >
             
            </td>                                  
          </tr>
          <tr>
          <td align=right valign="top"><b>Kwota:</b></td><td><input name="search_kwota" type="text" size="19" maxlength="200" value="'.str_replace('.', ',', $search_kwota).'"> PLN</td> 
            <td >&nbsp;</td>
 				<td align=right valign="top" nowrap><b>P³atno¶æ data od:</b>&nbsp;</td>             
              <td><input name="platnosc_data_od" id="platnosc_data_od" type="text" size="11" maxlength="10" value="'.$platnosc_data_od.'" >
              &nbsp;&nbsp;&nbsp;<b>do</b>: 
              <input name="platnosc_data_do" id="platnosc_data_do" type="text" size="11" maxlength="10" value="'.$platnosc_data_do.'" >             
            </td>
            </tr>
           <tr>                     
             <td align=right  valign="top"><b>Status:</b></td><td>
             <input name="status_payment" type="radio" style="background:#cccccc " value="1" '.($status_payment==1 ? 'checked' : '').'>
                    </b>Zap³acone<b>&nbsp;&nbsp;&nbsp; 
                    <input name="status_payment" type="radio" value="-1" style="background:#cccccc " '.($status_payment==-1 ? 'checked' : '').'>
                    </b>Niezpa³acone</b>
             </td>
           <td >&nbsp;</td>    
            	<td align=right valign="top" nowrap><b>Zwrot z TUE data od:</b>&nbsp;</td>             
              <td><input name="zwrot_data_od" id="zwrot_data_od" type="text" size="11" maxlength="10" value="'.$zwrot_data_od.'" >
              &nbsp;&nbsp;&nbsp;<b>do</b>: 
              <input name="zwrot_data_do" id="zwrot_data_do" type="text" size="11" maxlength="10" value="'.$zwrot_data_do.'" >             
            </td>
            </tr>
             
           
            <tr>
            <td colspan="5" align=right valign="bottom"> 
              <input name="Szukaj" type="submit" id="Szukaj" value="'.SEARCH .'">
            </td>
          </tr>
        </table>
      </td>
  </tr>

</table>
</form>
';
	
	
	return $result;
	
}



function wyniki_wyszukiwania(){
		$search_sprawa = getValue('search_sprawa');
	$status_payment = intval(getValue('status_payment'));
	$search_kwota = str_replace(',', '.', getValue('search_kwota'));
	
	$decyzja_data_od = getValue('decyzja_data_od');
	$decyzja_data_do = getValue('decyzja_data_do');
	
	$platnosc_data_od = getValue('platnosc_data_od');
	$platnosc_data_do = getValue('platnosc_data_do');
	
	$zwrot_data_od = getValue('zwrot_data_od');
	$zwrot_data_do = getValue('zwrot_data_do');
	
	$var='';
	
	if ($status_payment==1){
		$var .= " AND cep.payment=1 "; 
	}
	
	if ($status_payment==-1){
		$var .= " AND cep.payment=0 "; 
	}
	
	if ($decyzja_data_od != '' ){
		$var .= " AND ced.date >= '$decyzja_data_od' "; 
	}
	
	if ($decyzja_data_do != '' ){
		$var .= " AND ced.date <= '$decyzja_data_do' "; 
	}
	
	if ($platnosc_data_od != '' ){
		$var .= " AND cep.payment_date >= '$platnosc_data_od' "; 
	}
	
	if ($platnosc_data_do != '' ){
		$var .= " AND cep.payment_date <= '$platnosc_data_do' "; 
	}
	
	
	if ($zwrot_data_od != '' ){
		$var .= " AND cep.refund_date  >= '$zwrot_data_od' "; 
	}
	
	if ($zwrot_data_do != '' ){
		$var .= " AND cep.refund_date  <= '$zwrot_data_do' "; 
	}
	
	if ($search_kwota != '' ){
		$var .= " AND cedd.payment_amount  = '$search_kwota' "; 
	}
	
	if ($search_sprawa != '' ){
		$search__tmp = explode('/', $search_sprawa);
		$number = $search__tmp[0];
		$year =  '20'.substr($search__tmp[1],0,2 );
		
		$var .= " AND (cac.full_number = '$search_sprawa' OR (cac.number='$number' AND cac.year='$year') ) "; 
	}
	
	$result = '
<style>
.form_date{
	font-size:10px;
	width:75px;
}
</style>
<form method="post">	
	
	 <input type="hidden" name="action_list"  value="1">
	 
	 <input type="hidden" name="search_sprawa" value="'.$search_sprawa.'">
	 <input type="hidden" name="status_payment" value="'.$status_payment.'">
	 <input type="hidden" name="search_kwota" value="'.$search_kwota.'">
	 <input type="hidden" name="decyzja_data_od" value="'.$decyzja_data_od.'">
	 <input type="hidden" name="decyzja_data_do" value="'.$decyzja_data_do.'">
	 <input type="hidden" name="platnosc_data_od" value="'.$platnosc_data_od.'">
	 <input type="hidden" name="platnosc_data_do" value="'.$platnosc_data_do.'">	 
	 <input type="hidden" name="zwrot_data_od" value="'.$zwrot_data_od.'">
	 <input type="hidden" name="zwrot_data_do" value="'.$zwrot_data_do.'">	 
	 ';
	
	$action_list = getValue('action_list');
	
	if ($action_list==1){
		$f_payment_date = getValue('f_payment_date');
		$f_refund_date = getValue('f_refund_date');
		
		if (is_array($f_payment_date)){
			foreach ($f_payment_date As $key => $val ){
				if ($val != ''){
					$query = "UPDATE coris_vig_payment SET payment=1, payment_date='$val' WHERE ID='$key' LIMIT 1";
					//echo "<br>$query";
					mysql_query($query);
					
				}
			}
			
		}
		
		if (is_array($f_refund_date)){
			foreach ($f_refund_date As $key => $val ){
				if ($val != ''){
					$query = "UPDATE coris_vig_payment SET refund =1, refund_date ='$val' WHERE ID='$key' LIMIT 1";
					mysql_query($query);
					//echo "<br>R $key $val";
				}
			}
			
		}
	}
	
	 $query = "SELECT cep.ID,cep.ID_case,cep.payment_date,cep.refund_date,	 	 
	 cedd.amount  As p_mount, cedd.currency_id, cedd.payment_amount   As p_mount_pln,(cedd.rate/cedd.multiplier) As p_rate,
	 ced.text4 As beneficjent,ced.date
	 
	 
    FROM 
     coris_assistance_cases cac,    
    coris_vig_payment cep,  coris_vig_decisions_details cedd, coris_vig_claims_details cecd,
    coris_vig_decisions ced
    WHERE 
    cac.case_id = cep.ID_case             
    AND  cecd.ID = cep.ID_claims_details
    AND   cecd.ID = cedd.ID_claims_details
    
    AND cedd.ID_decisions = ced.ID
    
    $var
    ORDER BY cep.ID DESC";  
//echo $query;

  	$mysql_result = mysql_query($query);
  	echo mysql_error();
  	/*
  	$query = "SELECT * FROM coris_vig_decisions  WHERE ID='$id'";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID=$id claims pay not exists!";exit();}      
      $row = mysql_fetch_array($mysql_result);
      */	
  	$result .= '
  	 <script>
 
	function druk(){
		lista_pozycji=\'\';
		lista = document.getElementsByName(\'pozcyje[]\');
 		ilosc = lista.length;

 		for (i=0;i<ilosc;i++){
 			if (lista[i].checked){
				lista_pozycji += lista[i].value+\',\';
			}
 		} 	 	
	
 		if (lista_pozycji == \'\'){
 			alert(\'Zaznacz pozycje do drukowania\');
		}else{
				MM_openBrWindow(\'FK_vig_claims_pay_print.php?lista=\'+lista_pozycji,\'\',\'scrollbars=yes,resizable=yes,width=1500,height=800,left=20,top=20\');
		}
		
		
	}
 
 function zaznacz(){
 	lista = document.getElementsByName(\'pozcyje[]\');
 	ilosc = lista.length;

 	for (i=0;i<ilosc;i++){
 		lista[i].checked=true;
 	} 	 	
 } 
 function odznacz(){
 	lista = document.getElementsByName(\'pozcyje[]\');
 	ilosc = lista.length;

 	for (i=0;i<ilosc;i++){
 		lista[i].checked=false;
 	} 	 	
 }
 </script>
<a href="javascript:zaznacz();" >Zaznacz wszystkie</a>&nbsp;|&nbsp;<a href="javascript:odznacz();" >Odznacz wszystkie</a>
  	<br><br> <table width="95%" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" >
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="20">&nbsp; </th>
    <th width="100">Nr sprawy</th>
    <th width="150">Beneficjent </th>
    <th width="100">forma p³atno¶ci</th>
    <th width="220">Nr konta bankowego</th>
    <th width="80">Kwota do zap³aty</th>    
    <th width="50">Waluta</th>
    <th width="80">Kurs</th>	
    <th width="80">PLN</th> 
    <th width="80">data decyzji</th>     
    <th width="80">data p³atno¶ci</th> 
    <th width="80">data refundacji</th> 
	
    
    <th width="80">Nr zdarzenia ubezp.</th>
    <th width="80">Ubezpieczony</th>
    <th width="80">Nr umowy ubezp.</th>
  </tr>';
  	$i=0;
  	 while ($row = mysql_fetch_array($mysql_result)){  	  				  	  	
  		$row_case = CaseInfo::getFullCaseInfo($row['ID_case']);

		$row_case_ann = VIGCase::getCaseInfo( $row['ID_case'] );
      
  		$result .= '<tr bgcolor="'.( ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ) .'">
  			<td nowrap><input type="checkbox" name="pozcyje[]" value="'.$row['ID'].'" style="width: 20px" ></td>
  			<td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="open_case2(\''.$row['ID_case'].'&mod=compensa_claims\',\'\');"></td>
  		';
        
		    $result .= '<td >'.$row_case['full_number'].'</td>';
		    $result .= '<td nowrap><span class="style4">'.nl2br($row['beneficjent']).'</span> </td>';
		    $result .= '<td >'. ($row_case_ann['forma_wyplaty']==2 ? 'przekaz pocztowy' : 'przelew bankowy').'</td>';
		    $result .= '<td >'. ($row_case_ann['forma_wyplaty']==1 ? $row_case_ann['wyplata_nr_konta_bankowego'] : '&nbsp;' ).'</td>';
		    $result .= '<td >'. $row['p_mount'].'</td>';
		    $result .= '<td >'. $row['currency_id'].'</td>';
		    $result .= '<td >'. print_currency($row['p_rate'],4).'</td>';  
		    $result .= '<td >'. print_currency($row['p_mount_pln'],2).'</td>    ';
		    $result .= '<td >'. $row['date'].'</td>';
		    $result .= '<td >'. ($row['payment_date'] != '' ? $row['payment_date'] : '<input type="text" class="form_date" name="f_payment_date['.$row['ID'].']" value="">').'</td>';
		    $result .= '<td >'. ($row['refund_date'] != '' ? $row['refund_date'] : 	 '<input type="text" class="form_date" name="f_refund_date['.$row['ID'].']" value="">').'</td>';
		    $result .= '<td >'. $row_case['client_ref'].'</td>';
		    $result .= '<td >'. $row_case['paxname'].' '.$row_case['paxsurname'].'</td>';
		    $result .= '<td >'. $row_case['policy'].'</td>';
		$result .= '</tr>';
   } 
	$result .= '</table>

	<br>
	<input type="button" onCLick="druk();" value="Drukuj zaznaczone"> <span style="margin-left:150px;" ><input type="submit" value="Zapisz zmiany"></span>
	
	</form>
	';	
	return $result;
}
function initForm(){
	
	
	 $query = "SELECT clp.ID As cID,cp.*,cpp.amount As p_mount,cpp.currency_id As p_currency,cpp.ID_risk As p_risk,cpp.ID_operat As p_operat
	 ,cpp.amount_pln  As p_mount_pln, 
	 (SELECT rate/multiplier FROM coris_finances_currencies_tables_rates  WHERE coris_finances_currencies_tables_rates.table_id= currency_table_id  AND coris_finances_currencies_tables_rates.currency_id = cpp.currency_id) As p_rate,
	 (SELECT publication_date  FROM coris_finances_currencies_tables   WHERE coris_finances_currencies_tables.table_id= currency_table_id  ) As p_date
	 
    FROM coris_assistance_cases_claims_lista_platnosci clp,  coris_assistance_cases_claims_pay cp,coris_assistance_cases_claims_pay_position cpp 
    WHERE clp.status=0 AND cp.ID=clp.ID_claims_pay AND cp.ID=cpp.ID_claims_pay ORDER BY clp.ID DESC";  

  		
  $mysql_result = mysql_query($query);

  echo mysql_error();

  $i = 0;
?> 
 <form method="POST" id="form_action">
 <input type="hidden" name="action_name" id="action_name" value="">
 <input type="hidden" name="action_param" id="action_param" value="">
 </form>
 
 <br>
 <form method="POST" id="form_big">
 <table  border="0" cellpadding="1" cellspacing="1"  align="center">
 <tr ><td colspan="7"  align="right">		
		</td></tr>
</table>
<a href="javascript:zaznacz();" >Zaznacz wszystkie</a>&nbsp;|&nbsp;<a href="javascript:odznacz();" >Odznacz wszystkie</a>
<br><br> <table width="95%" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" align="center">
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="20">&nbsp; </th>
    <th width="80">Imiê i nazwisko </th>
    <th width="100">Ulica</th>
    <th  width="100">Miejscowo¶æ</th>
    <th width="60">Kod pocztowy</th>
    <th width="180">Nr konta bankowego</th>
    <th width="80">Kwota do zap³aty</th>    
    <th width="80">Waluta</th>
    <th width="80">Kurs</th>
    <th width="80">Data k.</th>
    <th width="80">PLN</th>    
    <th width="80">Nr zdarzenia ubezp.</th>
    <th width="80">Ubezpieczony</th>
    <th width="80">Nr umowy ubezp.</th>
    <th width="80">Rodzaj zdarzenia ubezp.</th>
    <th width="80">Produkt</th>    
     <th width="20">Usuñ</th>        
  </tr>
 <?php
  while ($row = mysql_fetch_array($mysql_result)){
  		$q = "SELECT * FROM coris_assistance_cases_announce  WHERE case_id = '".$row['ID_case']."'";
  		$mr = mysql_query($q);
  		$row_ann = mysql_fetch_array($mr);
  		
  		$q = "SELECT * FROM coris_assistance_cases   WHERE case_id = '".$row['ID_case']."'";
  		$mr = mysql_query($q);
  		$row_case = mysql_fetch_array($mr);
  		
  	
  	
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td nowrap><input type="checkbox" name="pozcyje[]" value="<?php echo $row['cID']; ?>" style="width: 20px" ></td>
  <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="open_case2('<?php echo $row['ID_case']; ?>&mod=claims','')"></td>
        
    <td nowrap><span class="style4"><?php echo $row['name'].' '.$row['surname']; ?></span> </td>
    <td ><?php echo $row['adress']; ?></td>
    <td ><?php echo $row['city']; ?></td>
    <td ><?php echo $row['post']; ?></td>
    <td ><?php echo ($row['pay_type']==1 ? $row['account_number'] : '&nbsp;' ); ?></td>
    <td ><?php echo $row['p_mount']; ?></td>
    <td ><?php echo $row['p_currency']; ?></td>
    <td ><?php echo print_currency($row['p_rate'],8); ?></td>
    <td ><?php echo ($row['p_currency']=='PLN' ? '&nbsp;' : $row['p_date']); ?></td>
    <td ><?php echo $row['p_mount_pln']; ?></td>    
    <td ><?php echo $row_case['client_ref']; ?></td>
    <td ><?php echo $row_case['paxname'].' '.$row_case['paxsurname']; ?></td>
    <td ><?php echo $row_case['policy']; ?></td>
    <td ><?php echo getOperat($row['p_operat']); ?></td>
    <td ><?php echo getRyzykoGlowne($row_ann['ryzyko_gl']); ?></td>
  	<td ><input type="button" value="x" onClick="usun_pozycje(<?php echo $row['cID']; ?>)"></td>   
    
    
  </tr>
  <?php } 

?>

</table>
<br><br>
<input type="submit" name="gen_wysylka" value="Utwórz wysy³kê">
<?php
} 
?>
</td></tr>
</table>
</form>




<?php
html_stop2();
?>