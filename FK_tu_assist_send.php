<?php 
require_once('include/include.php'); 
include_once('include/strona.php'); 
include_once('include/send_list.inc.php');

html_start();

$tow_id = intval(getValue('tow_id'));


echo '<form method="post"><div align="center">Towarzystwo: '.SendList::getTowList($tow_id).'</div></form>';


if ($tow_id>0){
	showList($tow_id);	
}

function showList($tow_id){
?>



<table width="95%" align="center">
<tr><td>
  
  <?php 
  
  $id = getValue('id');
  
  $query = "SELECT *  FROM coris_assistance_cases_wysylki  WHERE client_id='$tow_id' ORDER BY ID DESC ";   	  	
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
    <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow('FK_tu_assist_send_print.php?payment_id=<?php echo $row['ID']; ?>','','scrollbars=yes,resizable=yes,width=760,height=800,left=20,top=20')"></td>
        
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

}

html_stop2();

?>