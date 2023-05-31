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
	
	$qi = "INSERT INTO coris_finances_invoices_in_forward_send  SET
	user_id='".$_SESSION['user_id']."',
	date=now(),
	info='$info',status=1	
	";
	
	$mr = mysql_query($qi);
	if ($mr){
		$id = mysql_insert_id();
		foreach ($lista As $pozycja){		
			$qu = "UPDATE coris_finances_invoices_in_forward   SET  status_send_list = '$id',status =1 WHERE status =0 AND ID='$pozycja'";
			$mr = mysql_query($qu);
		}
	}
	
	echo "Paczka utworzona -> <a href=\"javascript:;\" onClick=\"MM_openBrWindow('FK_signal_inv_send_print.php?payment_id=".$id."','','scrollbars=yes,resizable=yes,width=760,height=800,left=20,top=20')\">szczegó³y</a>";	
	
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
	
		$query = "SELECT *
	FROM coris_assistance_cases, coris_finances_invoices_in_forward   
    WHERE coris_finances_invoices_in_forward.case_id =  coris_assistance_cases.case_id 
    AND coris_finances_invoices_in_forward.status=0
    AND coris_finances_invoices_in_forward.ID IN (".implode(',',$lista).") 
    ORDER BY year,number ";  
		
	
  		
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
<br><br> <table  border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" align="center">
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="20">&nbsp; </th>
    <th width="100">Nr sprawy Coris</th>
    <th  width="100">Nr sprawy SI</th>
    <th width="50">Nazwisko </th>
    <th width="50">Imiê</th>
    <th width="50">Kraj zdarzenia</th>
    <th width="70">Nr faktury</th>
    <th width="90">Kwota</th>
    <th width="40">Waluta</th>
    <th width="220">Uwagi</th>    
  
  </tr>
 <?php
  while ($row = mysql_fetch_array($mysql_result)){	  	  	
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td nowrap><input type="checkbox" name="pozcyje[]" value="<?php echo $row['ID']; ?>" style="width: 20px" checked ></td>
  <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="open_case2('<?php echo $row['case_id']; ?>&mod=inv_send','')"></td>

   <td ><?php echo $row['number'].'/'.substr($row['year'],2); ?></td>  
      <td ><?php echo $row['client_ref']; ?></td>        
    <td nowrap class="style4"><?php echo $row['paxsurname']; ?> </td>  
    <td nowrap class="style4"><?php echo $row['paxname']; ?> </td>
    
    <td align="center" ><?php echo $row['country_id']; ?></td> 
    <td align="right"><?php echo $row['invoice_in_no']; ?></td> 
    <td align="right"><?php echo $row['amount']; ?></td> 
    <td align="right" ><?php echo $row['currency_id']; ?></td> 
    <td ><?php echo $row['note']; ?></td> 
     
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
	
	
	$query = "SELECT *
	FROM coris_assistance_cases, coris_finances_invoices_in_forward   
    WHERE coris_finances_invoices_in_forward.case_id =  coris_assistance_cases.case_id 
    AND coris_finances_invoices_in_forward.status=0
    ORDER BY year,number ";  

  		
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
<br><br> <table  border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" align="center">
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="20">&nbsp; </th>
    <th width="100">Nr sprawy Coris</th>
    <th  width="100">Nr sprawy SI</th>
    <th width="50">Nazwisko </th>
    <th width="50">Imiê</th>
    <th width="50">Kraj zdarzenia</th>
    <th width="70">Nr faktury</th>
    <th width="90">Kwota</th>
    <th width="40">Waluta</th>
    <th width="220">Uwagi</th>    
  
  </tr>
 <?php
  while ($row = mysql_fetch_array($mysql_result)){	  	  	
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td nowrap><input type="checkbox" name="pozcyje[]" value="<?php echo $row['ID']; ?>" style="width: 20px" ></td>
  <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="open_case2('<?php echo $row['case_id']; ?>&mod=inv_send','')"></td>

   <td ><?php echo $row['number'].'/'.substr($row['year'],2); ?></td>  
      <td ><?php echo $row['client_ref']; ?></td>        
    <td nowrap class="style4"><?php echo $row['paxsurname']; ?> </td>  
    <td nowrap class="style4"><?php echo $row['paxname']; ?> </td>
    
    <td align="center" ><?php echo $row['country_id']; ?></td> 
    <td align="right"><?php echo $row['invoice_in_no']; ?></td> 
    <td align="right"><?php echo $row['amount']; ?></td> 
    <td align="right" ><?php echo $row['currency_id']; ?></td> 
    <td ><?php echo $row['note']; ?></td> 
     
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