<?php 
require_once('include/include.php'); 
include_once('include/strona.php'); 
include_once('include/send_list.inc.php');

html_start();

$tow_id = intval(getValue('tow_id'));
echo '<form method="post"><div align="center">Towarzystwo: '.SendList::getTowList($tow_id).'</div></form>';


if ($tow_id > 0 ){
		startForm($tow_id);
}

function startForm($tow_id){

	echo '<div align="center" style="font-weight: bold;font-size:17px;">Przygotowanie wysy³ki akt</div>
		<table width="95%" align="center">
		<tr><td>'; 
  
		$action_name = getValue('action_name');
		$action_param = getValue('action_param');
		
		if ($action_name=='del_poz' && $action_param>0 ){
			
		}
						
		if ($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['gen_wysylka'])){			
			wysylkaGen($tow_id);		
		}else if ($_SERVER['REQUEST_METHOD']=='POST' && $action_name=='generate_pay'){			
			utworzWysylke($tow_id);		
		}else{	
			initForm($tow_id);		
		}
 	
	echo '</td></tr></table>';		
}


function utworzWysylke($tow_id){
	
	
	$lista = $_POST['pozcyje'];
	
	
	if (count($lista)==0){
		echo "<b>Brak pozycji do wysy³ki</b>";		
		initForm();		
		return;		
	}
	
	$info = getValue('info');
	
	$qi = "INSERT INTO coris_assistance_cases_wysylki  SET
	client_id='".$tow_id."',
	user_id='".$_SESSION['user_id']."',
	date=now(),
	info='$info',status=1	
	";
	
	$mr = mysql_query($qi);
	if ($mr){
		$id = mysql_insert_id();
		foreach ($lista As $pozycja){
			
			$qu = "UPDATE coris_assistance_cases SET  status_send_list = '$id' WHERE status_send_list =0 AND case_id='$pozycja'";
			$mr = mysql_query($qu);
		}
	}
	
	echo "Paczka utworzona -> <a href=\"javascript:;\" onClick=\"MM_openBrWindow('FK_tu_assist_send_print.php?payment_id=".$id."','','scrollbars=yes,resizable=yes,width=760,height=800,left=20,top=20')\">szczegó³y</a>";	
	
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


function wysylkaGen($tow_id){
	
	$lista = $_POST['pozcyje'];
	
	
	if (count($lista)==0){
		echo "<b>Brak pozycji do wysy³ki</b>";
		
		initForm();
		
		return;
		
	}
	
	$query = "SELECT *,( SELECT concat(name,' ',surname)  FROM coris_users WHERE coris_users.user_id = status_send_user_id )  As username FROM coris_assistance_cases  
    WHERE client_id='.$tow_id.' AND status_send=1  AND status_send_list=0 AND case_id IN (".implode(',',$lista).") ORDER BY status_send_date ";  

  		
  $mysql_result = mysql_query($query);
  echo mysql_error();

  $i = 0;
 

echo '<form method="POST" id="form_big">
 <input type="hidden" name="tow_id" id="action_param" value="'.$tow_id.'">
 <input type="hidden" name="action_name" value="generate_pay">
 <table  border="0" cellpadding="1" cellspacing="1"  align="center">
 <tr ><td colspan="1"  align="center">	<b>Nowa wysy³ka</b>	</td>		</tr>
 <tr ><td colspan="1"  align="center"> <br><br><textarea name="info" rows="5" cols="80"></textarea>	</td>		</tr>
</table>
<a href="javascript:zaznacz();" >Zaznacz wszystkie</a>&nbsp;|&nbsp;<a href="javascript:odznacz();" >Odznacz wszystkie</a>

<br><div align="center"><input type="submit" name="gen_wysylka2" value="Utwórz wysy³kê"></div>
<br><br> <table  border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" align="center">
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="20">&nbsp; </th>
    <th width="80">Imiê i nazwisko </th>
    <th width="100">Nr sprawy Coris</th>
    <th  width="100">Nr sprawy TU</th>
    <th width="160">Data wysy³ki</th>
    <th width="100">Sporz±dzaj±cy</th>      
  </tr>';
 
  while ($row = mysql_fetch_array($mysql_result)){
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td nowrap><input type="checkbox" name="pozcyje[]" value="<?php echo $row['case_id']; ?>" style="width: 20px" checked></td>
  <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="open_case2('<?php echo $row['case_id']; ?>&mod=settings','')"></td>
        
   <td nowrap><span class="style4"><?php echo $row['paxname'].' '.$row['paxsurname']; ?></span> </td>
    <td ><?php echo $row['number'].'/'.substr($row['year'],2); ?></td>
    <td ><?php echo $row['client_ref']; ?></td>
    <td ><?php echo $row['status_send_date']; ?></td> 
    <td ><?php echo $row['username']; ?></td>
    
    
    
  </tr>
  <?php } 

?>

</table>
<br><br>
<div align="center"><input type="submit" name="gen_wysylka2" value="Utwórz wysy³kê"></div>
</form>
<?php
} 



//////////////////////////////////////////////////////////////////////////////////////////////////////

function initForm($tow_id){
	global $tow_id;
	
	$query = "SELECT *,( SELECT concat(name,' ',surname)  FROM coris_users WHERE coris_users.user_id = status_send_user_id )  As username FROM coris_assistance_cases  
    WHERE client_id='.$tow_id.' AND status_send=1 AND status_send_list=0 ORDER BY status_send_date ";  

  		
  $mysql_result = mysql_query($query);

  echo mysql_error();

  $i = 0;
echo '
 <form method="POST" id="form_action">
 <input type="hidden" name="action_name" id="action_name" value="">
 <input type="hidden" name="action_param" id="action_param" value="">
 <input type="hidden" name="tow_id" id="action_param" value="'.$tow_id.'">
 </form>';
 
echo ' <br>
 <form method="POST" id="form_big">
 
 <input type="hidden" name="tow_id" id="action_param" value="'.$tow_id.'">
 <table  border="0" cellpadding="1" cellspacing="1"  align="center">
 <tr ><td colspan="7"  align="right">		
		</td></tr>
</table>
<a href="javascript:zaznacz();" >Zaznacz wszystkie</a>&nbsp;|&nbsp;<a href="javascript:odznacz();" >Odznacz wszystkie</a>
<br><br><input type="submit" name="gen_wysylka" value="Utwórz wysy³kê">

<br><br> <table  border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" align="center">
<tr bgcolor="#CCCCCC">
	    <th width="20">&nbsp; </th>
	    <th width="20">&nbsp; </th>
	    <th width="80">Imiê i nazwisko </th>
	    <th width="100">Nr sprawy Coris</th>
	    <th  width="100">Nr sprawy TU</th>
	    <th width="160">Data wysy³ki</th>
	    <th width="100">Sporz±dzaj±cy</th>
  
  </tr>';
 
  while ($row = mysql_fetch_array($mysql_result)){  	
  		$q = "SELECT * FROM coris_assistance_cases   WHERE case_id = '".$row['ID_case']."'";
  		$mr = mysql_query($q);
  		$row_case = mysql_fetch_array($mr);
  		
  	
  	
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td nowrap><input type="checkbox" name="pozcyje[]" value="<?php echo $row['case_id']; ?>" style="width: 20px" ></td>
  <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="open_case2('<?php echo $row['case_id']; ?>&mod=settings','')"></td>
        
    <td nowrap><span class="style4"><?php echo $row['paxname'].' '.$row['paxsurname']; ?></span> </td>
    <td ><?php echo $row['number'].'/'.substr($row['year'],2); ?></td>
    <td ><?php echo $row['client_ref']; ?></td>
    <td ><?php echo $row['status_send_date']; ?></td> 
    <td ><?php echo $row['username']; ?></td>
  
  </tr>
  <?php 
  } 


	echo '
	</table>
	<br><br>
	<input type="submit" name="gen_wysylka" value="Utwórz wysy³kê">
	</form>
	';
} 



html_stop2();
?>