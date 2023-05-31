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
  
  $id = getValue('id');
  
  $query = "SELECT *  FROM coris_assistance_cases_claims_platnosci  ORDER BY ID DESC ";   	
  		
  $mysql_result = mysql_query($query);
  

  $i = 0;
 ?>
<table width="450" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed" align="center">
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="80">Nr</th>
    <th width="150">Data</th>
    <th >U¿ytkownik</th>
  </tr>
 <?php
  while ($row = mysql_fetch_array($mysql_result)){ ?>
  <tr bgcolor="<?PHP  echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
    <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow('FK_signal_claims_pay_print.php?payment_id=<?php echo $row['ID']; ?>','','scrollbars=yes,resizable=yes,width=1500,height=800,left=20,top=20')"></td>
        
    <td nowrap align="center"><span class="style4"><?php echo $row['ID'] ?></span> </td>
    <td align="center" nowrap><?php echo $row['date']; ?></td>
    <td nowrap><span class="style4"><?php echo getUserName($row['user_id']); ?></span> </td>        
  </tr>
  <?php } 

?>
</table>
<?php
 
?>
</td></tr>
</table>
<?php
html_stop2();
?>