<?php include('include/include.php'); 

$contrahent_id = getHttpValue('contrahent_id',0);
$initials_id = getHttpValue('initials_id',0);
$action = getHttpValue('action','');

$user_id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 0;



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_CN_IN_WARUM ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="Scripts/validate.js"></script>
</head>

<body><br>


<?php

if ($action=='add_save' && $contrahent_id>0){
		zapisz_form_add($contrahent_id);
}else if ($action=='update_save' && $contrahent_id>0 && $initials_id>0){
		zapisz_form_update($contrahent_id,$initials_id);

}else if ( $action=='add' && $contrahent_id>0 ){
	$tab = array();
	
	$c_id = getContrahnetParam($contrahent_id,'country_id');
	if ($c_id=='PL'){
		$tab = array('active_date' => date("Y-m-d"),'due_date_grid_fee' => 'dzien','due_date_grid_invoice' => 'dzien','due_date_fee' => 14,'currency_fee' => 'EUR','paymenttype_fee'=>3,'due_date_cost'=>14,'currency_cost' => 'PLN','paymenttype_cost'=>3,'paymenttype_invoice'=>3,'due_date_invoice'=>14,'currency_invoice' => 'PLN');
	}else{		
		$tab = array('active_date' => date("Y-m-d"),'due_date_grid_fee' => 'dzien','due_date_grid_invoice' => 'dzien','due_date_fee' => 14,'currency_fee' => 'EUR','paymenttype_fee'=>3,'due_date_cost'=>14,'currency_cost' => 'EUR','paymenttype_cost'=>3,'paymenttype_invoice'=>3,'due_date_invoice'=>14,'currency_invoice' => 'EUR','table_fee' => 1,'table_cost' => 1,'table_invoice' => 1);

	}
	
	wysw_form($tab,'add',$contrahent_id);
}else if( $action=='update' && $contrahent_id>0  && $initials_id>0){
	  $query = "SELECT * FROM coris_contrahents_initials WHERE ID='$initials_id' AND contrahent_id='$contrahent_id' ORDER BY active_date DESC ";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)>0){
      		$row=mysql_fetch_array($mysql_result);
      		wysw_form($row,'update',$contrahent_id);
      }	
}


function zapisz_form_add($contrahent_id){
	$table_incoice = 0;
	$active_date = getHttpValue('active_date');
	$due_date_fee = getHttpValue('due_date_fee');
	$table_fee = getHttpValue('table_fee');
	$table_fee_source = getHttpValue('table_fee_source');
	$amount_fee = str_replace(',','.',getHttpValue('amount_fee'));
	$paymenttype_fee = getHttpValue('paymenttype_fee');
	$currency_fee = getHttpValue('currency_fee');
	$due_date_cost = getHttpValue('due_date_cost') > 0 ?  getHttpValue('due_date_cost') : 0;
	$cost_fee = getHttpValue('cost_fee');
	$currency_cost = getHttpValue('currency_cost');
	$table_cost = getHttpValue('table_cost') > 0 ? getHttpValue('table_cost') : 0  ;
	
	$event_date_rate = getHttpValue('event_date_rate')==1 ? 1 : 0;
	$paymenttype_cost = getHttpValue('paymenttype_cost') > 0 ?  getHttpValue('paymenttype_cost') : 0 ;
	$paymenttype_invoice = getHttpValue('paymenttype_invoice');
	$due_date_invoice = getHttpValue('due_date_invoice');
	$currency_invoice = getHttpValue('currency_invoice');	
	$table_invoice = getHttpValue('table_invoice')> 0 ?   getHttpValue('table_invoice') : 0 ;	
	$table_invoice_source = getHttpValue('table_invoice_source');	
	$due_date_grid_fee = getHttpValue('due_date_grid_fee');
	$due_date_grid_invoice = getHttpValue('due_date_grid_invoice');
	$open_date_rate = getHttpValue('open_date_rate');
	$reasekurator = getHttpValue('reasekurator') > 0 ?  getHttpValue('reasekurator') : 0 ;


	$reasekurator_honorarium = getValue('reasekurator_honorarium') > 0 ?  getValue('reasekurator_honorarium') : 0 ;
	$lump  = getValue('lump') == 1 ? 1 : 0;
	$name = getValue('name');
	$end_date  = getValue('end_date') == '' ? "null" : "'".getValue('end_date')."'"  ;
	
	
	$query = "INSERT INTO coris_contrahents_initials (ID,contrahent_id ,due_date_fee,  currency_fee,  amount_fee,  table_fee,  paymenttype_fee,  currency_cost,  table_cost,  paymenttype_cost,  due_date_cost,  event_date_rate,  table_incoice,  paymenttype_invoice,  due_date_invoice,currency_invoice,  active_date,table_invoice, user_id,  date,due_date_grid_fee,due_date_grid_invoice,reasekurator,reasekurator_honorarium,lump, name,end_date,table_fee_source,table_invoice_source,open_date_rate ) 
	
	VALUES (null,'".$contrahent_id."','".$due_date_fee."','".$currency_fee."','".$amount_fee."','".$table_fee."','".$paymenttype_fee."','".$currency_cost."','".$table_cost."','".$paymenttype_cost."','".$due_date_cost."','".$event_date_rate."','".$table_incoice."','".$paymenttype_invoice."','".$due_date_invoice."','".$currency_invoice."','".$active_date."','".$table_invoice."','".$_SESSION['user_id']."',now(),'$due_date_grid_fee','$due_date_grid_invoice','$reasekurator','$reasekurator_honorarium',$lump, '$name',$end_date,'$table_fee_source','$table_invoice_source','$open_date_rate')";


	$mysql_result = mysql_query($query) or die (mysql_error());
	$id = mysql_insert_id();

	 echo "<script> document.location='GEN_contrahents_details.php?contrahent_id=$contrahent_id#umowy'</script>";
	  exit;
	
}

function zapisz_form_update($contrahent_id,$initials_id){
	$table_incoice = 0;
	$active_date = getHttpValue('active_date');
	
	
	
	$due_date_fee = getHttpValue('due_date_fee');
	$table_fee = getHttpValue('table_fee');
	$table_fee_source = getHttpValue('table_fee_source');
	$amount_fee = str_replace(',','.',getHttpValue('amount_fee'));
	$paymenttype_fee = getHttpValue('paymenttype_fee');
	$currency_fee = getHttpValue('currency_fee');
	$due_date_cost = getHttpValue('due_date_cost') > 0 ?  getHttpValue('due_date_cost') : 0;
	$cost_fee = getHttpValue('cost_fee');
	$currency_cost = getHttpValue('currency_cost');
	$table_cost = getHttpValue('table_cost') > 0 ? getHttpValue('table_cost') : 0  ;
	
	$event_date_rate = getHttpValue('event_date_rate') == 1 ? 1 : 0;
	
	$paymenttype_cost = getHttpValue('paymenttype_cost') > 0 ?  getHttpValue('paymenttype_cost') : 0 ;
	$paymenttype_invoice = getHttpValue('paymenttype_invoice');	
	$due_date_invoice = getHttpValue('due_date_invoice');
	$currency_invoice = getHttpValue('currency_invoice');
	$paymenttype_invoice = getHttpValue('paymenttype_invoice');	
	$table_invoice = getHttpValue('table_invoice')> 0 ?   getHttpValue('table_invoice') : 0 ;	
	$table_invoice_source = getHttpValue('table_invoice_source');		
	$due_date_grid_fee = getHttpValue('due_date_grid_fee');
	$due_date_grid_invoice = getHttpValue('due_date_grid_invoice');
	$open_date_rate = getHttpValue('open_date_rate');
	
	$reasekurator = getValue('reasekurator') > 0 ?  getValue('reasekurator') : 0 ;
	$reasekurator_honorarium = getValue('reasekurator_honorarium') > 0 ?  getValue('reasekurator_honorarium') : 0 ;
	$lump  = getValue('lump') == 1 ? 1 : 0;
	$name = getValue('name');
	$end_date  = getValue('end_date') == '' ? "null" : "'".getValue('end_date')."'"  ;
	
	
	$query = "UPDATE coris_contrahents_initials SET due_date_fee='".$due_date_fee."',currency_fee='".$currency_fee."',
	amount_fee='".$amount_fee."',table_fee='".$table_fee."',paymenttype_fee='".$paymenttype_fee."',currency_cost='".$currency_cost."',
	table_cost='".$table_cost."',paymenttype_cost='".$paymenttype_cost."',due_date_cost='".$due_date_cost."',event_date_rate='".$event_date_rate."',table_incoice='".$table_incoice."',paymenttype_invoice='".$paymenttype_invoice."',due_date_invoice='".$due_date_invoice."',currency_invoice='$currency_invoice',table_invoice='$table_invoice',active_date='".$active_date."',end_date=$end_date,
	reasekurator_honorarium='$reasekurator_honorarium',lump='$lump',name='$name',
	user_id='".$_SESSION['user_id']."',date=now(),
	due_date_grid_fee='$due_date_grid_fee', due_date_grid_invoice='$due_date_grid_invoice',reasekurator='$reasekurator',
	table_fee_source='$table_fee_source',table_invoice_source='$table_invoice_source',open_date_rate='$open_date_rate'
	WHERE ID='$initials_id' LIMIT 1";
	
	$mysql_result = mysql_query($query) or die (mysql_error());
	$id = mysql_insert_id();

	 echo "<script> document.location='GEN_contrahents_details.php?contrahent_id=$contrahent_id#umowy'</script>";
	  exit;
}

function wysw_form($row,$tryb,$contrahent_id){ 
	$due_date_grid_invoice = $row['due_date_grid_invoice'];
	$due_date_grid_fee = $row['due_date_grid_fee'];
	
	$fee_grid_sel_m =  $due_date_grid_fee == 'miesiac' ? 'selected' : '';
	$fee_grid_sel_d = $due_date_grid_fee == 'dzien' ? 'selected' : '';
	$fee_grid_sel_t = $due_date_grid_fee == 'tydzien' ? 'selected' : '';
	
	$invoice_grid_sel_m =  $due_date_grid_invoice == 'miesiac' ? 'selected' : '';
	$invoice_grid_sel_d = $due_date_grid_invoice == 'dzien' ? 'selected' : '';
	$invoice_grid_sel_t = $due_date_grid_invoice == 'tydzien' ? 'selected' : '';

//	/onSubmit="return validate('pl', 'name', 'r', 'country_id', 'l', 'account', 'ra', 'user_id', 'l')">
	?>
<form name="form1" method="POST" action="GEN_contrahents_details_initials.php" >
<input type="hidden" name="contrahent_id" >
<?php
	if ($tryb == 'add')
		echo '<input type="hidden" name="action" value="add_save">';
	else if ($tryb == 'update'){
		echo '<input type="hidden" name="action" value="update_save">';
		echo '<input type="hidden" name="initials_id" value="'.$row['ID'].'">';		
	}

?>
<table align="center" cellpadding="1" cellspacing="0" border="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?= GEN_CN_IN_WARUM ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?= GEN_CN_IN_DATOD ?>&nbsp;</td>
    <td><input name="active_date" type="text" id="active_date" size="10" maxlength="10" value="<?php echo ($row['active_date']<>'' ? $row['active_date'] : date("Y-m-d") );?>">
    do: 
    <input name="end_date" type="text" id="end_date" size="10" maxlength="10" value="<?php echo $row['end_date'] ; ?>">
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?= GEN_CN_IN_RYCZALT ?>: <input type="checkbox" name="lump" value="1" <?php echo ($row['lump']==1 ? 'checked' : ''); ?>>
    </td>
  </tr>
   <tr >
    <td align="right" nowrap><?= GEN_CN_IN_NAME ?>&nbsp;</td>
    <td><textarea cols="60" rows="2" name="name"><?php echo $row['name'];?></textarea></td>
    
   </tr>
  <tr bgcolor="#CCCCCC" valign="baseline">
    <td colspan="2" align="center" bgcolor="#CCCCCC" nowrap><strong><?= GEN_CN_IN_HON ?></strong>&nbsp;</td>
    
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_IN_TERMPL ?>&nbsp;&nbsp;</td>
    <td><input name="due_date_fee" type="text" id="due_date_fee" size="3" maxlength="3" value="<?php echo $row['due_date_fee']; ?>">
      &nbsp;
      <select name="due_date_grid_fee" >
        <option value="dzien" <? echo $fee_grid_sel_d; ?>><?= FK_INVOUTADD_TERMPLATDN ?></option>
        <option value="tydzien" <? echo $fee_grid_sel_t; ?>><?= FK_INVOUTADD_TERMPLATTYG ?></option>
        <option value="miesiac" <? echo $fee_grid_sel_m; ?>><?= FK_INVOUTADD_TERMPLATMIES ?></option>
      </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= FK_INVOUTADD_KWOT ?>&nbsp;</td>
    <td><input name="amount_fee" type="text" id="city" size="10" maxlength="10" value="<?php echo print_currency($row['amount_fee']); ?>">
      &nbsp;<?= FK_PAY_WAL ?>:&nbsp;
      <input name="currency_fee2" id="currency_fee2" type="text" size="4" maxlength="3" value="<?php echo $row['currency_fee']; ?>" onblur="document.forms['form1'].elements['currency_fee'].value = document.forms['form1'].elements['currency_fee2'].value.toUpperCase(); document.forms['form1'].elements['currency_fee2'].value = document.forms['form1'].elements['currency_fee2'].value.toUpperCase()">
      <?php echo print_currency_all('currency_fee',$row['currency_fee'],'',"onchange=\"document.forms['form1'].elements['currency_fee2'].value = document.forms['form1'].elements['currency_fee'].value\""); ?>&nbsp;
      &nbsp;<?= GEN_CN_IN_RABWAL_KR ?>&nbsp;<?php echo print_ratetype('table_fee',$row['table_fee'],''); ?>&nbsp;<?= GEN_CN_IN_ZRKURS ?>&nbsp;<?php echo print_table_source('table_fee_source',$row['table_fee_source'],''); ?>
      </td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= MG_FRMP_FORMPLAT ?> &nbsp;</td>
    <td><?php echo print_paymenttypes('paymenttype_fee',$row['paymenttype_fee'],'');?></td>
  </tr>
  
      <tr valign="baseline">
    <td   align="right" nowrap><b>Reasekurator</b> </td><td><input type="text" name="reasekurator_honorarium" size="5" value="<?php echo $row['reasekurator_honorarium']; ?>"></td>
    </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap><hr></td>
    </tr>
  <tr valign="baseline">
    <td nowrap colspan="2" align="center" bgcolor="#CCCCCC"><strong><?= GEN_CN_IN_KOSZT ?></strong>&nbsp;</td>
    
  </tr>
<!--
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>Termin płatności&nbsp;&nbsp;</td>
    <td><input name="due_date_cost" type="text" id="due_date_cost" size="3" maxlength="3"  value="<?php echo $row['due_date_cost']; ?>"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">Waluta&nbsp;&nbsp;</td>
    <td><?php echo print_currency_all('currency_cost',$row['currency_cost'],''); ?>&nbsp;&nbsp;&nbsp;Tabela waluty&nbsp;<?php echo print_ratetype('table_cost',$row['table_cost'],''); ?></td>
  </tr>
  -->
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_IN_KURSZDNZ ?> &nbsp;</td>
   <td><input type="checkbox" name="event_date_rate" id="event_date_rate" value="1" onclick="document.getElementById('open_date_rate').checked=false;"  <?php echo ($row['event_date_rate']==1 ? 'checked' : '' );?>></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_IN_KURSZDNO ?> &nbsp;</td>
   <td><input type="checkbox" name="open_date_rate" id="open_date_rate" value="1"  onclick="document.getElementById('event_date_rate').checked=false;" <?php echo ($row['open_date_rate']==1 ? 'checked' : '' );?>></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right" bgcolor="#DDDDDD"><strong><?= GEN_CN_IN_FAKT ?></strong>&nbsp;</td>
    <td bgcolor="#DDDDDD">&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= FK_INVOUTADD_TERMPLAT ?>&nbsp;</td>
    <td><input name="due_date_invoice" type="text" id="due_date_invoice" size="3" maxlength="3"  value="<?php echo $row['due_date_invoice']; ?>">
      &nbsp;
      <select name="due_date_grid_invoice" >
        <option value="dzien" <? echo $invoice_grid_sel_d; ?>><?= FK_INVOUTADD_TERMPLATDN ?></option>
        <option value="tydzien" <? echo $invoice_grid_sel_t; ?>><?= FK_INVOUTADD_TERMPLATTYG ?></option>
        <option value="miesiac" <? echo $invoice_grid_sel_m; ?>><?= FK_INVOUTADD_TERMPLATMIES ?></option>
      </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= FK_PAY_WAL ?>&nbsp;</td>
    <td><input name="currency_invoice2" id="currency_invoice2" type="text" size="4" maxlength="3" value="<?php echo $row['currency_invoice']; ?>" onblur="document.forms['form1'].elements['currency_invoice'].value = document.forms['form1'].elements['currency_invoice2'].value.toUpperCase(); document.forms['form1'].elements['currency_invoice2'].value = document.forms['form1'].elements['currency_invoice2'].value.toUpperCase()">      
	<?php echo print_currency_all('currency_invoice',$row['currency_invoice'],'',"onchange=\"document.forms['form1'].elements['currency_invoice2'].value = document.forms['form1'].elements['currency_invoice'].value\""); ?>&nbsp;&nbsp;&nbsp;<?= GEN_CN_IN_RABWAL ?> &nbsp;<?php echo print_ratetype('table_invoice',$row['table_invoice'],''); ?>&nbsp;&nbsp;<?= GEN_CN_IN_ZRKURS ?> &nbsp;<?php echo print_table_source('table_invoice_source',$row['table_invoice_source'],''); ?></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;<?= MG_FRMP_FORMPLAT ?>&nbsp;</td>
    <td><?php echo print_paymenttypes('paymenttype_invoice',$row['paymenttype_invoice'],'');?></td>
  </tr>

      <tr valign="baseline">
    <td   align="right" nowrap><b>Reasekurator</b> </td><td><input type="text" name="reasekurator" size="5" value="<?php echo $row['reasekurator']; ?>"></td>
    </tr>
     <tr valign="baseline">
    <td colspan="2" align="right" nowrap><hr></td>
    </tr>
  <tr valign="baseline">
    <td height="22" align="right" nowrap>&nbsp;</td>
    <td><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
        <input name="offset" type="hidden" id="offset" value="<?PHP echo $_GET['offset'] ?>">
        <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&offset=<?PHP echo $_GET['offset'] ?>'">
        <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
</form>
<?php }



?>

</body>
</html>