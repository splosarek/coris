<?php require_once('include/include.php'); 

   		 header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0

          
$tryb = getValue('tryb');
$todo_type = getValue('todo_type');
$important = getValue('important');
$status = getValue('status');
$user = getValue('user');


html_start(AS_CASTD_TITLEALL,'onload="focus();"');

$id_todos = "1";
if (isset($_SESSION['user_id'])) {
  $id_todos = (get_magic_quotes_gpc()) ? $_SESSION['user_id'] : addslashes($_SESSION['user_id']);
}

$id_user = $_SESSION['user_id'];
if ( ! ($id_user>0)) {echo "Jest exit";exit;}

$ch_fut='';
$ch_past = '';

if ($tryb=='past')
	$ch_past = 'checked';
else if ($tryb=='future') 	
	$ch_fut = 'checked';
else  
 	$ch_past = 'checked';  // domyslne

$important_imp = '';
$important_normal = '';
$important_all = '';

if ($important=='important')
	$important_imp = 'checked';
else if ($important=='normal')	
	$important_normal = 'checked';
else if ($important=='all')	
	$important_all = 'checked';
else 
	$important_imp = 'checked';
	
$status_0= '';
$status_1 = '';

if ($status=='1')
	$status_1 = 'checked';
else if ($status=='0')	
	$status_0 = 'checked';
else 
	$status_0 = 'checked';
	
$user_0 = '';
$user_all = ''	;


if ($user>'1')
	$user_0 = 'checked';
else if ($user=='0')	
	$user_all = 'checked';
else 
	$user_0 = 'checked';

?>
<script>
function show_todo(case_id){		
			open_case(case_id,'');
			window.close();
}
</script>
<form name="form1" method="post">
<table width="790" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong><?= AS_CASTD_LISTZAD ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
        <table width="100%" border="1" cellpadding="2" cellspacing="2">
          <tr> 
            <td align=right width="17%"><b><?= AS_CASD_ZAD2?>:</b></td>
            <td align=left  colspan="2"><b>
              <input name="user" type="radio" style="background:#cccccc " value="<?php echo $_SESSION['user_id']; ?>" <?php echo $user_0;?>>
              </b><?= AS_CASTD_UZYTKOW ?><b>&nbsp; 
              <input name="user" type="radio" value="0" style="background:#cccccc " <?php echo $user_all;?>>
              </b><?= AS_CASTD_WSZYST ?> &nbsp;&nbsp;&nbsp;
              <b><?= AS_CASES_STATUS ?>:</b> 
              <input type="radio" name="status" value="1"  style="background-color:#CCCCCC" <?php echo $status_1; ?>>
              <?= AS_CASTD_ZROB ?> 
              <input type="radio" name="status" value="0" style="background-color:#CCCCCC" <?php  echo $status_0; ?>>
              <?= AS_CASTD_NZROB ?>
                <?php
                    if (isset($_SESSION['coris_branch']) && $_SESSION['coris_branch'] == 1){
                        echo '&nbsp;&nbsp;&nbsp;' . BRANCH . ':&nbsp;' . print_user_coris_branch('coris_branch_id', getValue('coris_branch_id'), 'onChange="form1.submit()"');
                    }else if (isset($_SESSION['coris_branch']) && $_SESSION['coris_branch'] == 2){
                        echo '&nbsp;&nbsp;&nbsp;' . BRANCH . ':&nbsp;' . print_user_coris_branch_de('coris_branch_id', getValue('coris_branch_id'), 'onChange="form1.submit()"');
                    }
                ?>
            </td>

          </tr>
          <tr>
            <td align=right width="17%"><b><?= AS_CASTD_TYPSPR ?>:</b>&nbsp;</td>
            <td align=left width="79%">
              <select name="todo_type" >
                <option value="all" <?php echo ($todo_type== 'all' || $todo_type== '') ? 'selected' : '' ;?>><?= AS_CASTD_WSZYST ?></option>
                <option value="medic" <?php echo ($todo_type== 'medic') ? 'selected' : '' ;?> ><?= AS_CASTD_TYLMED ?></option>
                <option value="tech" <?php echo($todo_type== 'tech') ? 'selected' : '' ;?>><?= AS_CASTD_TYLTECH ?></option>
              </select>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><?= AS_CASD_ZAD2 ?>:</b> 
              <input type="radio" name="tryb" value="past"    style="background-color:#CCCCCC" <?php echo $ch_past; ?>>
              <?= AS_CASTD_PRZESZLE ?> 
              <input type="radio" name="tryb" value="future"  style="background-color:#CCCCCC" <?php  echo $ch_fut; ?>>
              <?= AS_CASTD_PRZ ?> </td>
            <td align=center width="4%">&nbsp;</td>
          </tr>
          <tr> 
            <td align=right width="17%"><b><?= AS_CASADD_WAZN ?>:</b>&nbsp;</td>
            <td align=left width="79%"> 
              <input type="radio" name="important" value="important"  style="background-color:#CCCCCC" <?php echo $important_imp; ?>>
              <?= URGENT ?> 
              <input type="radio" name="important" value="normal"  style="background-color:#CCCCCC" <?php  echo $important_normal; ?>>
              <?= NORM ?>
               <input type="radio" name="important" value="all"  style="background-color:#CCCCCC" <?php  echo $important_all; ?>>
              <?= AS_CASTD_WSZYST ?> </td>
            <td align=center width="4%">
              <input name="Szukaj" type="submit" id="Szukaj" value="<?= SEARCH ?>">
            </td>
          </tr>
        </table>
      </td>
  </tr>
</table>
  <table  width="790" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;table-layout:fixed;">
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1"  style="table-layout:fixed;">
	<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>
		<th width="150"><?= AS_CASTD_SPRNR ?></th>
		<th width="240"><?= AS_CASD_ZADANIE ?></th>
		<th width="80"><?= AS_CASD_ZADOD ?></th>
		<th width="80"><?= AS_CASD_ZADDO ?></th>
		<th width="140" nowrap><?= DATE ?></th>
		<th align="center" width="70"><?= AS_CASADD_WAZN ?></th>
	</tr>
	<?php 

	$var = array('');
	
	$var[] = ($user>1 || $user=='') ? ' act2u.user_id = '.$id_user.' ' : ' 1=1 '; 
	$var[] = ($status=='1') ? ' act.complete = 1 ' : 'act.complete = 0 '; 
	$var[] = ($tryb=='past') ? ' act.date_due <= NOW() ' : ' act.date_due > NOW() '; 
	
	//$var[] = ($important=='important') ? ' important=1 ' : ' important=0 '; 
	
	
	if ($important=='all'){
		
		
	}else if ($important=='important'){
		$var[] = ' important=1 ';
	}else if ($important=='normal'){
		$var[] = ' important=0 ';
	}
	
	if ($todo_type=='all'){		
		
	}else if ($todo_type=='medic'){
		$var[] = ' ac.type_id IN (2,3) ';	
	}else if ($todo_type=='tech'){
		$var[] = ' ac.type_id = 1 ';					
	}

    if (isset($_SESSION['coris_branch']) && $_SESSION['coris_branch'] == 2){
        $var[] = " (ac.coris_branch_id='2' OR ac.coris_branch_id='3' ) ";
    }else if('' != getValue('coris_branch_id')){
        $var[] = "ac.coris_branch_id='" . getValue('coris_branch_id') . "'";
    }



	$var_sql = implode(' AND ' , $var );
	
	$query = "SELECT act.todo_id, act.case_id, act.important, act.value,
	                 CONCAT_WS('/', ac.number, ac.`year`, ac.type_id, ac.client_id) AS case_no,
	                 act2u.user_id,act.date_due,coris_users.username, ac.paxname, ac.paxsurname,
	                 cu.username As username_od
	            FROM coris_users, coris_users cu, coris_assistance_cases_todos act,
	                 coris_assistance_cases_todos2users act2u, coris_assistance_cases ac
	       LEFT JOIN coris_branch cb ON cb.ID=ac.coris_branch_id
               WHERE act.todo_id = act2u.todo_id
                 AND act.case_id = ac.case_id
                 $var_sql
                 AND coris_users.user_id=act2u.user_id
                 AND act.active = 1
                 AND cu.user_id = act.user_id
            ORDER BY important DESC,act.date_due";
//echo $query;


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
	  		<td align="left" nowrap>'.$row['case_no'].'<br>'.$row['paxsurname'].' '.$row['paxname'].'</td>
	  		<td align="left" >'.$row['value'].'</td>	  		
	  		<td align="left" >'.$row['username_od'].'</td>	  		
	  		<td align="left" >'.$row['username'].'</td>	  		
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
