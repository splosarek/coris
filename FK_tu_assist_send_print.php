<?php 
require_once('include/include.php'); 
include_once('include/strona.php');
include_once('include/send_list.inc.php');

$pageName = 'FK_payments.php';
$payment_id = getValue('payment_id');



 

$export=0;
if ($_SERVER['REQUEST_METHOD']=='POST'){
	$export=1;
			 @header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  			@header ("Cache-Control: no-store ");
  			@header ("Pragma: no-cache");
  			@header("Content-Description: File Download");    
  			header("Content-type: application/octet-stream");          			
  			//@header("Content-Transfer-Encoding: chunked");
  			@header("Content-Transfer-Encoding: binary");
  			@header('Content-Disposition: attachment; filename="export_zlecenie_wyplaty_'.$payment_id.'_'.date('Y-m-d_His').'.xls"');
  			
  	
echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<style>
.text{
  mso-number-format:"\@";/*force text*/
}
</style>
</head>

<body leftmargin="0" topmargin="0" >';	
}else{

	html_start();
}
?>


<table width="95%" align="center">
<tr><td>
  
  <?php 
  
  


if ( $payment_id>0 ){
	
	initForm($payment_id);
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////

function initForm($payment_id){
	global $export;
	
	$query = "SELECT *  FROM coris_assistance_cases_wysylki WHERE ID='$payment_id' ";   	  		  
  	$mysql_result = mysql_query($query);
	$r = mysql_fetch_array($mysql_result);
	$query = "SELECT *,( SELECT concat(name,' ',surname)  FROM coris_users 
	WHERE coris_users.user_id = status_send_user_id )  As username 
	
	FROM coris_assistance_cases  
    WHERE  status_send=1  AND status_send_list=".$payment_id."  ORDER BY status_send_date ";  

  	$mysql_result = mysql_query($query);
//	$row = mysql_fetch_array($mysql_result);
	//echo mysql_error();
  $i = 0;
 
  if (!$export){
		 echo '
		 <a href="javascript:;" onclick="window.print();"><img src="img/print.gif" border=0></a><br>';
  }
  
 if (!$export){
	echo '<form method="POST"><input type="submit" value="export do excela"></form>'	;	
 }
?>

 <?php
  if (!$export){
 echo ' <table  border="0" cellpadding="1" cellspacing="1">
 <tr ><td width="60"  align="right"><b>Nr: </b></td><td align="left">'.$r['ID'].'</td></tr>
 <tr >
 <td align="right"><b>Data utworzenia: </b></td><td align="left">'.$r['date'].'</td></tr>
 <tr ><td   align="right"><b>Użytkownik: </b></td><td align="left">'.getUserName($r['user_id']).'</td></tr>
 <tr ><td   align="right"><b>Info: </b></td><td align="left">'.nl2br($r['info']).'</td></tr>  
 </table>'; 
  }
 ?>
<hr>
<table  border="1" cellpadding="1" cellspacing="0"  align="center">
<tr><td colspan="6"><b><?php  echo getContrahnetParam( $r['client_id'], 'name'); ?></b></td></tr>
<tr><td colspan="6"><div style="margin-left:400px"><b>WYSYŁKA</b></div></td></tr>
<tr><td colspan="6">&nbsp;</td></tr>

 <tr bgcolor="#CCCCCC">
  	<td > &nbsp;</td>
  	<td colspan="3" align="center"> &nbsp;	</td>
 	 <td colspan="2"> &nbsp;</td>			 	 		
 </tr>
<tr bgcolor="#CCCCCC">    
    <th width="20">Lp.</th>
          <th width="80">Imię i nazwisko </th>
    <th width="100">Nr sprawy Coris</th>
    <th  width="100">Nr sprawy TU</th>
    <th width="160">Data wysyłki</th>
    <th width="100">Sporządzający</th>
  </tr>
 
 <?php
 $licznik=1;
   	$suma_all = 0.00;
  	$suma_wal = array();
  	$wal = array();
  while ($row = mysql_fetch_array($mysql_result)){

  		
  		$q = "SELECT * FROM coris_assistance_cases   WHERE case_id = '".$row['ID_case']."'";
  		$mr = mysql_query($q);
  		$row_case = mysql_fetch_array($mr);
  		

  	
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td ><?php echo $licznik++; ?></td>          
      <td nowrap><span class="style4" class="text"><?php echo $row['paxname'].' '.$row['paxsurname']; ?></span> </td>
    <td class="text"><?php echo $row['number'].'/'.substr($row['year'],2); ?></td>
    <td class="text"><?php echo $row['client_ref']; ?>&nbsp;</td>
    <td class="text"><?php echo $row['status_send_date']; ?></td> 
    <td class="text"><?php echo $row['username']; ?></td>
       
  </tr>
  <?php 
  	
  	
  	
  } 

?>
</table>
<br><br>
<table cellpadding="25" cellspacing="0" >
<tr><td colspan="4" width="300">Warszawa, dnia ........................</td><td colspan="4">Zatwierdzający</td></tr>
<tr><td colspan="4">&nbsp;</td><td colspan="4">................................................</td></tr>
</table>
<br><br>
<?php


} 
?>
</td></tr>
</table>

<?php

html_stop2();
?>