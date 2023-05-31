<?php require_once('include/include.php'); 

   		 header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0

          
$tryb = getValue('tryb')=='' ? 'new' : getValue('tryb');



html_start(AS_CASTD_TITLE,'onload="focus();"');


$ch_fut='';
$ch_past = '';

if ($tryb=='all')
	$ch_fut = 'checked';	
else 	
	$ch_past = 'checked';
?>
<script>
function show_todo(case_id){		
		 //   open_case(case_id+'&mod=todos','');
		    open_case(case_id,'');
			window.close();
}
</script>
<form name="form1" method="post">
<table width="630" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>ALERTY</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td align=center>Alerty:&nbsp; &nbsp;&nbsp; 
              <input type="radio" name="tryb" value="new" onclick="document.form1.submit()" style="background-color:#CCCCCC" <?php echo $ch_past; ?>>
              Nowe
              <input type="radio" name="tryb" value="all"  onclick="document.form1.submit()" style="background-color:#CCCCCC" <?php  echo $ch_fut; ?>>
              Wszystkie </td>
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
		<th width="130" nowrap><?= DATE ?></th>
		<th width="150"><?= AS_CASTD_SPRNR ?></th>
		<th width="80">Zg³aszaj±cy</th>
		<th width="320"><?= AS_CASD_ZADANIE ?></th>		
		

	</tr>
	<?php 

	$var = ($tryb=='new') ? ' AND ca.new =1  ' : '  '; 
	$coris_branch = intval($_SESSION['coris_branch']); // popr		
	if ($coris_branch==1){ 
		$coris_branch_var =  "ac.coris_branch_id = '1'";
	}else if ($coris_branch==2){ 	 
		$coris_branch_var = " (ac.coris_branch_id = '2' OR ac.coris_branch_id = '3' )";
	}
	$query = "SELECT ca.new,ca.made,ca.case_id, ca.interaction_name ,ca.note,ca.date, CONCAT_WS('/', ac.number, ac.`year`, ac.type_id, ac.client_id) AS case_no, ac.paxname,ac.paxsurname
	FROM coris_assistance_cases_alerts ca, coris_assistance_cases ac 
		WHERE ca.case_id = ac.case_id  AND $coris_branch_var
	$var  ORDER BY ca.alert_id DESC";

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
	  		<td align="right" >'.$row['date'].'</td>	 
	  		<td align="center" >'.$row['case_no'].'<br>'.$row['paxsurname'].' '.$row['paxname'].'</td>
	  		<td align="left" >'.$row['interaction_name'].'</td>	  
	  		<td align="left" ><small>'.substr($row['note'],0,300).'</small></td>	  		
	  		 		
	  		
	  	</tr>';
  } ?> 	</table>
	</td>
  </tr>
</table>

</form>
</body>
</html>