<?php require_once('include/include.php'); 

$pageName = 'FK_payments.php';
$payment_id = getValue('payment_id');

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
	
	$query = "SELECT *  FROM coris_finances_invoices_in_forward_send WHERE ID='$payment_id' ";   	  		  
  	$mysql_result = mysql_query($query);
	$r = mysql_fetch_array($mysql_result);
	
		$query = "SELECT *
	FROM coris_assistance_cases, coris_finances_invoices_in_forward   
    WHERE coris_finances_invoices_in_forward.case_id =  coris_assistance_cases.case_id 
    AND coris_finances_invoices_in_forward.status=1 AND coris_finances_invoices_in_forward.status_send_list=".$payment_id ."
    ORDER BY year,number "; 
		
	
  	$mysql_result = mysql_query($query);
//	$row = mysql_fetch_array($mysql_result);
	//echo $query;
  $i = 0;
 
  if (!$export){
		 echo '
		 <a href="javascript:;" onclick="window.print();"><img src="img/print.gif" border=0></a><br>';
  }
?>

 <?php
  if (!$export){
 echo ' <table  border="0" cellpadding="1" cellspacing="1">
 <tr ><td width="60"  align="right"><b>Nr: </b></td><td align="left">'.$r['ID'].'</td></tr>
 <tr >
 <td align="right"><b>Data utworzenia: </b></td><td align="left">'.$r['date'].'</td></tr>
 <tr ><td   align="right"><b>U�ytkownik: </b></td><td align="left">'.getUserName($r['user_id']).'</td></tr>
 <tr ><td   align="right"><b>Info: </b></td><td align="left">'.nl2br($r['info']).'</td></tr>  
 </table>'; 
  }
 ?>
<hr>
<table  border="1" cellpadding="1" cellspacing="0"  align="center">
<tr><td colspan="10"><b>Signal Iduna Polska TU S.A.</b></td></tr>
<tr><td colspan="10"><div style="margin-left:400px"><b>WYSY�KI FAKTUR</b></div></td></tr>
<tr><td colspan="10">&nbsp;</td></tr>

 <tr bgcolor="#CCCCCC">
  	<td > &nbsp;</td>
  	<td colspan="5" align="center"> &nbsp;	</td>
 	 <td colspan="4"> &nbsp;</td>			 	 		
 </tr>
<tr bgcolor="#CCCCCC">    
    <th width="20">Lp.</th>
    <th width="100">Nr sprawy Coris</th>
    <th  width="100">Nr sprawy SI</th>
    <th width="50">Nazwisko </th>
    <th width="50">Imi�</th>
    <th width="50">Kraj zdarzenia</th>
    <th width="70">Nr faktury</th>
    <th width="90">Kwota</th>
    <th width="40">Waluta</th>
    <th width="220">Uwagi</th>  

  </tr>
 
 <?php
 $licznik=1;
   	$suma_all = 0.00;
  	$suma_wal = array();
  	$wal = array();
  while ($row = mysql_fetch_array($mysql_result)){
  		$q = "SELECT * FROM coris_assistance_cases_announce  WHERE case_id = '".$row['ID_case']."'";
  		$mr = mysql_query($q);
  		$row_ann = mysql_fetch_array($mr);
  		
  		$q = "SELECT * FROM coris_assistance_cases   WHERE case_id = '".$row['ID_case']."'";
  		$mr = mysql_query($q);
  		$row_case = mysql_fetch_array($mr);
  		

  	
  	?>
  <tr bgcolor="<?PHP echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
  <td ><?php echo $licznik++; ?></td>          
      <td ><?php echo $row['number'].'/'.substr($row['year'],2); ?></td>  
      <td ><?php echo $row['client_ref']; ?>&nbsp;</td>        
    <td nowrap class="style4"><?php echo $row['paxsurname']; ?> </td>  
    <td nowrap class="style4"><?php echo $row['paxname']; ?> </td>
    
    <td align="center" ><?php echo $row['country_id']; ?></td> 
    <td align="right"><?php echo $row['invoice_in_no']; ?></td> 
    <td align="right"><?php echo $row['amount']; ?></td> 
    <td align="right" ><?php echo $row['currency_id']; ?></td> 
    <td ><?php echo $row['note']; ?>&nbsp;</td> 
  </tr>
  <?php 
  	
  	
  	
  } 

 
 
 
 




?>
</table>
<br><br>
<table cellpadding="25" cellspacing="0" >
<tr><td colspan="4" width="300">Warszawa, dnia ........................</td><td colspan="4">Zatwierdzaj�cy</td></tr>
<tr><td colspan="4">&nbsp;</td><td colspan="4">................................................</td></tr>
</table>
<br><br>
<?php

 if (!$export){
	echo '<form method="POST"><input type="submit" value="export do excela"></form>'	;	
 }
} 
?>
</td></tr>
</table>

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