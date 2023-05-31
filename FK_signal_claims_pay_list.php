<?php require_once('include/include.php'); 

$pageName = 'FK_payments.php';

function check_user($user){
	$query = "SELECT user_id FROM coris_users WHERE username='$user' OR initials='$user' ";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)>0){
		$row= mysql_fetch_array($mysql_result);
		return $row[0];
	}else
		return "null";
}

include_once('include/strona.php'); 



html_start();

?>


<table width="95%" align="center">
<tr><td>
  
  <?php 
  
  
$action_name = getValue('action_name');
$action_param = getValue('action_param');

if ($action_name=='del_poz' && $action_param>0 ){
	
	$query = "SELECT * FROM coris_assistance_cases_claims_lista_platnosci WHERE ID='$action_param'";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)>0){
			$row = mysql_fetch_array($mysql_result);
			$cl_pay = $row['ID_claims_pay'];
			
			$query = "DELETE FROM coris_assistance_cases_claims_lista_platnosci WHERE ID='$action_param' LIMIT 1";
			$mr = mysql_query($query);
			if (mysql_affected_rows()){
						$query = "SELECT * FROM coris_assistance_cases_claims_lista_platnosci WHERE ID_claims_pay ='$cl_pay'";
						$mysql_result = mysql_query($query);
						if (mysql_num_rows($mysql_result)==0){
							$qu = "UPDATE coris_assistance_cases_claims_pay SET status_zlecenie=0,status_zlecenie_date=0,status_zlecenie_user_id=0 WHERE ID='$cl_pay' LIMIT 1";
							$mr = mysql_query($qu);
							
						}
			}
	}
}


if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['gen_wysylka'])){
	
	sysylkaGen();

}else if ($_SERVER['REQUEST_METHOD']=='POST' && $action_name=='generate_pay'){
	
	utworzWysylke();

}else{	
	initForm();		
}
 	



function utworzWysylke(){
	
	
	$lista = $_POST['pozcyje'];
	
	
	if (count($lista)==0){
		echo "<b>Brak pozycji do wysy³ki</b>";		
		initForm();		
		return;		
	}
	
	$info = getValue('info');
	
	$qi = "INSERT INTO coris_assistance_cases_claims_platnosci  SET
	user_id='".$_SESSION['user_id']."',
	date=now(),
	info='$info',status=1	
	";
	
	$mr = mysql_query($qi);
	if ($mr){
		$id = mysql_insert_id();
		foreach ($lista As $pozycja){			
			$qu = "UPDATE coris_assistance_cases_claims_lista_platnosci SET ID_platnosc='$id', status=1 WHERE ID_platnosc=0 AND ID='$pozycja' ";
			$mr = mysql_query($qu);
		}
	}
	
	echo "Paczka utworzona -> <a href=\"javascript:;\" onClick=\"MM_openBrWindow('FK_signal_claims_pay_print.php?payment_id=".$id."','','scrollbars=yes,resizable=yes,width=1500,height=800,left=20,top=20')\">szczegó³y</a>";	
	
}
 
 ?>
 
 <script>
 function usun_pozycje(id){
 	if (confirm('Czy napewno chcesz usun±æ pozycjê z listy?')){
 			document.getElementById('action_name').value='del_poz';	
 			document.getElementById('action_param').value=id;	
 			document.getElementById('form_action').submit();	
 	
 	}
 }
 
 function zaznacz(){
 	lista = document.getElementsByName('pozcyje[]');
 	ilosc = lista.length;

 	for (i=0;i<ilosc;i++){
 		lista[i].checked=true;
 	} 	 	
 } 
 function odznacz(){
 	lista = document.getElementsByName('pozcyje[]');
 	ilosc = lista.length;

 	for (i=0;i<ilosc;i++){
 		lista[i].checked=false;
 	} 	 	
 }
 </script>
<?php


function sysylkaGen(){
	
	$lista = $_POST['pozcyje'];
	
	
	if (count($lista)==0){
		echo "<b>Brak pozycji do wysy³ki</b>";
		
		initForm();
		
		return;
		
	}
	
	 $query = "SELECT clp.ID As cID,cp.*,cpp.amount As p_mount,cpp.amount_pln  As p_mount_pln, 
	 (SELECT rate/multiplier FROM coris_finances_currencies_tables_rates  WHERE coris_finances_currencies_tables_rates.table_id= currency_table_id  AND coris_finances_currencies_tables_rates.currency_id = cpp.currency_id) As p_rate,
 	  (SELECT publication_date  FROM coris_finances_currencies_tables   WHERE coris_finances_currencies_tables.table_id= currency_table_id  ) As p_date,
	cpp.currency_id As p_currency,cpp.ID_risk As p_risk,cpp.ID_operat As p_operat
    FROM coris_assistance_cases_claims_lista_platnosci clp,  coris_assistance_cases_claims_pay cp,coris_assistance_cases_claims_pay_position cpp 
    WHERE clp.status=0 AND cp.ID=clp.ID_claims_pay AND cp.ID=cpp.ID_claims_pay AND clp.ID IN (".implode(',',$lista).") ORDER BY clp.ID DESC";  

  		
  $mysql_result = mysql_query($query);
  echo mysql_error();

  $i = 0;
?> 

 <form method="POST" id="form_big">
 <input type="hidden" name="action_name" value="generate_pay">
 <table  border="0" cellpadding="1" cellspacing="1"  align="center">
 <tr ><td colspan="1"  align="center">	<b>Nowa wysy³ka</b>	</td>		</tr>
 <tr ><td colspan="1"  align="center"> <br><br><textarea name="info" rows="5" cols="80"></textarea>	</td>		</tr>
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
  <td nowrap><input type="checkbox" name="pozcyje[]" value="<?php echo $row['cID']; ?>" style="width: 20px" checked></td>
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
    
    
    
  </tr>
  <?php } 

?>

</table>
<br><br>
<div align="center"><input type="submit" name="gen_wysylka2" value="Utwórz wysy³kê"></div>
<?php
} 



//////////////////////////////////////////////////////////////////////////////////////////////////////

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
</body>
</html>
<?php

function getOperat($id){
	$qx = "SELECT nazwa FROM coris_signal_ryzyko_operat WHERE ID='$id'";
	$mr = mysql_query($qx);
	$row = mysql_fetch_array($mr);
	return $row['nazwa'];	
}

function getRyzykoGlowne($id){
	$qx = "SELECT numer FROM coris_signal_ryzyka_glowne  WHERE ID='$id'";
	$mr = mysql_query($qx);
	$row = mysql_fetch_array($mr);
	return $row['numer'];	
}
html_stop2();
?>