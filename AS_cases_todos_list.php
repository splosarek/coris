<?php require_once('include/include.php'); 

   		 header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0

          
$tryb = getValue('tryb');
$branch_id = getValue('branch_id'); 


html_start(AS_CASTD_TITLE,'onload="focus();"');

$id_todos = "1";
if (isset($_SESSION['user_id'])) {
  $id_todos = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}

$id_user = $_SESSION['user_id'];
if ( ! ($id_user>0)) exit;

$ch_fut='';
$ch_past = '';

if ($tryb=='past')
	$ch_past = 'checked';
else 	
	$ch_fut = 'checked';
?>
<script>
function show_todo(case_id){		
		    open_case(case_id,'');
			window.close();
}
</script>
<form name="form1" method="post">
<input type="hidden" name="branch_id" value="<?= $branch_id ?>">
<table width="700" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong><?= AS_CASTD_LISTZAD ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td align=center><?= AS_CASD_ZAD2?>:&nbsp; &nbsp;&nbsp; 
              <input type="radio" name="tryb" value="past" onclick="document.form1.submit()" style="background-color:#CCCCCC" <?php echo $ch_past; ?>>
              <?= AS_CASTD_ZAL?> 
              <input type="radio" name="tryb" value="future"  onclick="document.form1.submit()" style="background-color:#CCCCCC" <?php  echo $ch_fut; ?>>
              <?= AS_CASTD_PRZ ?> </td>
          </tr>
        </table>
      </td>
  </tr>
</table>
<table  cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1" >
	<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>
		<th width="150"><?= AS_CASTD_SPRNR ?></th>
		<th width="320"><?= AS_CASD_ZADANIE ?></th>
		<th width="80"><?= AS_CASADD_WAZNOD ?></th>
		<th width="120" nowrap><?= DATE ?></th>
		<th align="center" width="40"><?= AS_CASD_WAZ2 ?></th>
	</tr>
	<?php 

	$var = ($tryb=='future') ? ' AND act.date_due > NOW() ' : ' AND act.date_due <= NOW() ';

	
	
	if ($branch_id==2){
		$query = "SELECT act.todo_id, act.case_id, act.important, act.value, CONCAT_WS('/', ac.number, ac.`year`, ac.type_id, ac.client_id) AS case_no,act.date_due,ac.paxname,ac.paxsurname,cu.username As username_od  
				FROM coris_assistance_cases_todos act 
				
				, coris_assistance_cases ac,coris_users cu 
				WHERE  (ac.coris_branch_id='2' OR  ac.coris_branch_id='3'  ) AND act.case_id = ac.case_id $var AND act.complete = 0 AND act.active = 1  AND cu.user_id = act.user_id ORDER BY important DESC";
//	echo $query;	
	}else{	
		$query = "SELECT act.todo_id, act.case_id, act.important, act.value, CONCAT_WS('/', ac.number, ac.`year`, ac.type_id, ac.client_id) AS case_no, act2u.user_id,act.date_due,ac.paxname,ac.paxsurname,cu.username As username_od  FROM coris_assistance_cases_todos act, coris_assistance_cases ac, coris_assistance_cases_todos2users act2u,coris_users cu WHERE act.todo_id = act2u.todo_id AND act2u.user_id = '$id_user' AND act.case_id = ac.case_id $var AND act.complete = 0 AND act.active = 1  AND cu.user_id = act.user_id ORDER BY important DESC";
	}
	$mysql_result = mysql_query($query) or die(mysql_error());

	

	while ($row = mysql_fetch_assoc($mysql_result)){
	$st1 = 'bgcolor="#90EE90"';
	$st2= '';
	$date = date("Y-m-d H:i:s");
	
	//if ( $date>$row['date_due']){
	if ($row['important']==1)
			$st1 = 'bgcolor="#E9967A"'		;
	
	echo '<tr '.$st1.'>
	  		<td align="center"><input title="'.AS_CASTD_OTWZAD.'" type="button" value="&gt;" style="width: 20px" onClick="show_todo('.$row['case_id'].')"></td>
	  		<td align="left" >'.$row['case_no'].'<br>'.$row['paxsurname'].' '.$row['paxname'].'</td>
	  		<td align="left" >'.$row['value'].'</td>	  
	  		<td align="left" >'.$row['username_od'].'</td>	  		
	  		<td align="left" >'.$row['date_due'].'</td>	  		
	  		<td align="center" >'.($row['important']==1 ? URGENT : NORM ).'</td>	  			  		 
	  	</tr>';
  } ?> 	</table>
	</td>
  </tr>
</table>

</form>
</body>
</html>