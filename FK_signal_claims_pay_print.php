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
	
	$query = "SELECT *  FROM coris_assistance_cases_claims_platnosci WHERE ID='$payment_id' ";   	  		  
  	$mysql_result = mysql_query($query);
	$r = mysql_fetch_array($mysql_result);
	
  	$query = "SELECT clp.ID As cID,cp.*,cpp.amount As p_mount,cpp.currency_id As p_currency,cpp.ID_risk As p_risk,cpp.ID_operat As p_operat
  	,cpp.amount_pln  As p_mount_pln, 
	 (SELECT rate/multiplier FROM coris_finances_currencies_tables_rates  WHERE coris_finances_currencies_tables_rates.table_id= currency_table_id  AND coris_finances_currencies_tables_rates.currency_id = cpp.currency_id) As p_rate,
	  (SELECT publication_date  FROM coris_finances_currencies_tables   WHERE coris_finances_currencies_tables.table_id= currency_table_id  ) As p_date
    FROM coris_assistance_cases_claims_lista_platnosci clp,  coris_assistance_cases_claims_pay cp,coris_assistance_cases_claims_pay_position cpp 
    WHERE clp.status=1 AND cp.ID=clp.ID_claims_pay AND cp.ID=cpp.ID_claims_pay AND clp.ID_platnosc ='$payment_id' ORDER BY clp.ID";  
  	$mysql_result = mysql_query($query);
//	$row = mysql_fetch_array($mysql_result);
	//echo mysql_error();
  $i = 0;
 
  if (!$export){
		 echo '
		 <a href="javascript:;" onclick="alert(\'Pamiêtaj o zmianie orientacji papieru\');window.print();"><img src="img/print.gif" border=0></a><br>';
  }
?>

 <?php
  if (!$export){
 echo ' <table  border="0" cellpadding="1" cellspacing="1">
 <tr ><td width="60"  align="right"><b>Nr: </b></td><td align="left">'.$r['ID'].'</td></tr>
 <tr >
 <td align="right"><b>Data utworzenia: </b></td><td align="left">'.$r['date'].'</td></tr>
 <tr ><td   align="right"><b>U¿ytkownik: </b></td><td align="left">'.getUserName($r['user_id']).'</td></tr>
 <tr ><td   align="right"><b>Info: </b></td><td align="left">'.nl2br($r['info']).'</td></tr>  
 </table>'; 
  }
 ?>
<hr>
<table width="98%" border="1" cellpadding="1" cellspacing="0"  align="center">
<tr><td colspan="16"><b>Signal Iduna Polska TU S.A.</b></td></tr>
<tr><td colspan="16"><div style="margin-left:400px"><b>ZLECENIE WYP£ATY - PRZELEW BANKOWY</b></div></td></tr>
<tr><td colspan="16">&nbsp;</td></tr>

 <tr bgcolor="#CCCCCC">
  	<td> &nbsp;</td>

  	<td colspan="5" align="center"> <b>Odbiorca ¶wiadczenia</b>	</td>
 	 <td colspan="10"> &nbsp;</td>			 	 		
 </tr>
<tr bgcolor="#CCCCCC">    
    <th width="20">Lp.</th>
    <th width="150">Imiê i nazwisko </th>
    <th width="100">Ulica</th>
    <th  width="100">Miejscowo¶æ</th>
    <th width="60">Kod poczt.</th>
    <th width="180">Nr konta bankowego</th>
    <th width="80">Kwota do zap³aty</th>    
    <th width="40">Waluta</th>
    <th width="50">Kurs</th>
    <th width="50">Data k.</th>
    <th width="70">PLN</th>       
    <th width="80">Nr zdarzenia ubezp.</th>
    <th width="80">Ubezpieczony</th>
    <th width="80">Nr umowy ubezp.</th>
    <th width="80">Rodzaj zdarzenia ubezp.</th>
    <th width="60">Produkt</th>          
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
    <td ><span class="style4"><?php echo $row['name'].' '.$row['surname']; ?></span>&nbsp;</td>
    <td ><?php echo $row['adress']; ?>&nbsp;</td>
    <td ><?php echo $row['city']; ?>&nbsp;</td>
    <td ><?php echo $row['post']; ?>&nbsp;</td>
    <td ><?php echo ($row['pay_type']==1 ? $row['account_number'] : '&nbsp;' ); ?>&nbsp;</td>
    <td align="right"><?php echo print_currency($row['p_mount']); ?></td>
    <td align="center"><?php echo $row['p_currency']; ?></td>
    <td align="right"><?php echo print_currency($row['p_rate'],6); ?></td>
	<td ><?php echo ($row['p_currency']=='PLN' ? '&nbsp;' : $row['p_date']); ?></td>	
    <td align="right"><?php echo print_currency($row['p_mount_pln']); ?></td>        
    <td ><?php echo $row_case['client_ref']; ?>&nbsp;</td>
    <td ><?php echo $row_case['paxname'].' '.$row_case['paxsurname']; ?></td>
    <td ><?php echo $row_case['policy']; ?>&nbsp;</td>
    <td ><?php echo getOperat($row['p_operat']); ?></td>
    <td align="center"><?php echo getRyzykoGlowne($row_ann['ryzyko_gl']); ?></td>  	        
  </tr>
  <?php 
  	
  	$suma_all += $row['p_mount'];
  	$suma_wal[$row['p_currency']] +=$row['p_mount'];
  	$wal[] = $row['p_currency'];
  	
  	
  } 

 $wal = array_unique($wal);
 
 
 
echo '<tr><td colspan="5">&nbsp;</td>';
echo '<td  colspan="1"><b>Suma (wszystkie waluty):</b></td>
		<td  colspan="1" align="right">'.print_currency($suma_all).'</td>
		<td  colspan="1">&nbsp;</td>
		<td colspan="8">&nbsp;</td>
</tr>';


foreach ($wal As $poz){
	
echo '<tr><td colspan="5">&nbsp;</td>';
echo '<td  colspan="1"><b>Suma:</b></td>
		<td  colspan="1" align="right">'.print_currency($suma_wal[$poz]).'</td>
		<td  colspan="1" align="center">'.$poz.'</td>
		<td colspan="8">&nbsp;</td>
</tr>';
	
}
?>
</table>
<br><br>
<table cellpadding="25" cellspacing="0" >
<tr><td colspan="4" width="300">Warszawa, dnia ........................</td><td colspan="4">Zatwierdzaj±cy do wyp³aty</td></tr>
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