<?php
//include('../finances/Locales/pl.php');


function module_update(){
	global  $pageName;
	$result ='';


	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');


	$check_js = '';
	$message = '';

	echo $message;
}


function module_main(){
	global $case_id;
	$result = '';

		$query = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention , attention2 FROM coris_assistance_cases WHERE case_id =  '".$case_id."'";;

		$mysql_result = mysql_query($query);
		$row_case_settings = mysql_fetch_array($mysql_result);


	$result .=  '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
//		$result .=  dokumenty($row_case_settings,0);
$result .= init_disp();
	$result .=  '</div>';


			$result .=  '<div style="clear:both;"></div>';
	return $result;
}


function init_disp(){
	global $case_id;

$result = '';

$query_currencies = "SELECT coris_finances_currencies.currency_id FROM coris_finances_currencies WHERE coris_finances_currencies.active = 1 AND coris_finances_currencies.insurance = 1 ORDER BY coris_finances_currencies.currency_id";
$currencies = mysql_query($query_currencies) or die(mysql_error());
$row_currencies = mysql_fetch_assoc($currencies);
$totalRows_currencies = mysql_num_rows($currencies);

$id_case = $case_id;

$query_case = sprintf("SELECT CONCAT_WS('/', coris_assistance_cases.number, coris_assistance_cases.`year`) AS case_number, coris_assistance_cases.number, coris_assistance_cases.`year`,coris_assistance_cases.type_id,coris_assistance_cases.client_id, coris_assistance_cases.paxname, coris_assistance_cases.paxsurname, IF(coris_assistance_cases.paxdob, coris_assistance_cases.paxdob, '') AS paxdob, coris_assistance_cases.policy, IF(coris_assistance_cases_details.policyamount, REPLACE(coris_assistance_cases_details.policyamount, '.', ','), '') AS policyamount, coris_assistance_cases_details.policycurrency_id, coris_assistance_cases_details.paxphone, coris_assistance_cases_details.paxmobile, coris_contrahents.contrahent_id, coris_contrahents.name,coris_assistance_cases.case_id FROM coris_assistance_cases, coris_assistance_cases_details, coris_contrahents WHERE coris_assistance_cases.case_id = %s AND coris_assistance_cases_details.case_id = coris_assistance_cases.case_id AND coris_assistance_cases.client_id = coris_contrahents.contrahent_id", $id_case);
$case = mysql_query($query_case) or die(mysql_error());
$row_case = mysql_fetch_assoc($case);
$totalRows_case = mysql_num_rows($case);

$id_invoices_in = $case_id;


$query_invoices_in = sprintf("SELECT coris_finances_invoices_in.refaktor, coris_finances_invoices_in.deleted, coris_finances_invoices_in.invoice_in_id, coris_finances_invoices_in.invoice_in_no, coris_finances_invoices_in.invoice_in_due_date, REPLACE(coris_finances_invoices_in.gross_amount, '.', ',') AS amount, coris_finances_invoices_in.currency_id,coris_finances_invoices_in.correct, coris_finances_invoices_in.booking, coris_finances_invoices_in.listing, coris_finances_invoices_in.payment,coris_finances_invoices_in.payment_confirmed,  coris_finances_invoices_in.sent_back, coris_finances_invoices_in.urgent,coris_finances_invoices_in.stop, DATE(coris_finances_invoices_in.date) AS date, coris_contrahents.name AS contrahent_name,coris_users.initials, coris_users.username, coris_finances_activities.value AS activity,coris_finances_invoices_in.client_amount,coris_finances_invoices_in.contrahent_id FROM  coris_contrahents, coris_users,coris_finances_invoices_in LEFT JOIN coris_finances_activities ON coris_finances_activities.activity_id = coris_finances_invoices_in.activity_id WHERE coris_finances_invoices_in.case_id = %s AND coris_finances_invoices_in.contrahent_id = coris_contrahents.contrahent_id AND coris_finances_invoices_in.user_id = coris_users.user_id AND coris_finances_invoices_in.active = 1 ORDER BY coris_finances_invoices_in.urgent DESC, coris_finances_invoices_in.invoice_in_due_date DESC", $id_invoices_in);
$invoices_in = mysql_query($query_invoices_in) or die(mysql_error());
/*$row_invoices_in = mysql_fetch_assoc($invoices_in);
$totalRows_invoices_in = mysql_num_rows($invoices_in);
*/
$query_invoices_out = sprintf("SELECT coris_finances_invoices_out.urgent, coris_finances_invoices_out.deleted, coris_finances_invoices_out.invoice_out_id, coris_finances_invoices_out.invoice_out_no,coris_finances_invoices_out.invoice_out_year, coris_finances_invoices_out.invoice_out_due_date, REPLACE(coris_finances_invoices_out.payment_amount, '.', ',') AS amount, REPLACE(coris_finances_invoices_out.net_amount, '.', ',') As net_amount,REPLACE(coris_finances_invoices_out.gross_amount, '.', ',') As gross_amount,  coris_finances_invoices_out.currency_id, coris_finances_invoices_out.booking, coris_finances_invoices_out.payment, DATE(coris_finances_invoices_out.date) AS date, coris_contrahents.name AS contrahent_name, coris_finances_invoices_out.user_id,coris_finances_invoices_out.contrahent_id

FROM coris_finances_invoices_out, coris_contrahents WHERE coris_finances_invoices_out.case_id = %s AND coris_finances_invoices_out.contrahent_id = coris_contrahents.contrahent_id  AND coris_finances_invoices_out.active = 1 ORDER BY coris_finances_invoices_out.urgent DESC, coris_finances_invoices_out.invoice_out_due_date DESC", $id_invoices_in);
$invoices_out = mysql_query($query_invoices_out) or die(mysql_error());
//echo $query_invoices_out ;
$totalRows_invoices_out = mysql_num_rows($invoices_out);



$query_note_out = "SELECT
	no.*,
	coris_contrahents.name AS contrahent_name
	FROM coris_finances_debitnote_out no, coris_contrahents
	WHERE no.case_id = '$case_id'
		AND no.contrahent_id = coris_contrahents.contrahent_id  AND no.active = 1
	ORDER BY no.urgent DESC, no.invoice_out_due_date DESC";
$note_out = mysql_query($query_note_out) or die(mysql_error());
$totalRows_note_out = mysql_num_rows($note_out);


$result .= '<script><!--

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function openWindow(winId, id) {
    		var url = "../coris/AS_cases_details.php?case_id=" + id;
			var childwin = window.open(url, \'\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=598,height=600,left=\'+ (screen.availWidth - 598) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 530) / 2);
			childwin.opener = parent;
}

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}

function invoiceCorrect(){

	ilosc = document.getElementById(\'invoice_in_ids_count\').value;
	lista = document.getElementsByName(\'invoice_in_ids\');

	ilosc_wybrana=0
	values=0;
	for (var i = 0; i < lista.length; i++) {
			if(lista[i].checked){
				ilosc_wybrana++;
				values = lista[i].value;
			}
	}

	if (ilosc_wybrana==0){
		alert(\'Proszê wybraæ fakturê do korekty!\');
	}else if (ilosc_wybrana>1){
		alert(\'Zaznaczono wiêcej ni¿ jedn± pozycjê!\');
	}else{
			MM_openBrWindow(\'../finances/FK_invoices_in_correct.php?invoice_in_id=\'+ values,\'\',\'scrollbars=yes,resizable=yes,width=1100,height=670,left=50,top=40\');
	}

}



function invoiceOutCorrect(){

	ilosc = document.getElementById(\'invoice_out_ids_count\').value;
	lista = document.getElementsByName(\'invoice_out_ids\');

	ilosc_wybrana=0
	values=0;
	for (var i = 0; i < lista.length; i++) {
			if(lista[i].checked){
				ilosc_wybrana++;
				values = lista[i].value;
			}
	}

	if (ilosc_wybrana==0){
		alert(\'Proszê wybraæ fakturê do korekty!\');
	}else if (ilosc_wybrana>1){
		alert(\'Zaznaczono wiêcej ni¿ jedn± pozycjê!\');
	}else{
			MM_openBrWindow(\'../finances/FK_invoices_out_correct.php?invoice_out_id=\'+ values,\'\',\'scrollbars=yes,resizable=yes,width=1120,height=670,left=50,top=40\');
	}

}

function invoiceOut(tryb) {
	var values = "";
	var values_ref = "";
	checkedForm = document.getElementById(\'form3\');

		 if (checkedForm) {
			 	if (document.getElementById(\'invoice_in_ids_count\').value == 1 && document.getElementById(\'invoice_in_ids\').checked) {

			 		values = checkedForm.invoice_in_ids.value;
			 		if (checkedForm.invoice_in_ref_count.value == 1 && checkedForm.invoice_in_ref.checked && checkedForm.invoice_in_ids.value==checkedForm.invoice_in_ref.value) {
						values_ref=1;
			 		}

			 } else if (checkedForm.invoice_in_ids_count.value > 1) {
			 	  var start = 1;
				  for (var i = 0; i < checkedForm.invoice_in_ids.length; i++) {
				  	 if (checkedForm.invoice_in_ids[i].checked){
				  		if (start == 1 ) {
							values += checkedForm.invoice_in_ids[i].value;
							start = 0;
						} else
							values +=  "," + checkedForm.invoice_in_ids[i].value;

						if (checkedForm.invoice_in_ref_count.value == 1 && checkedForm.invoice_in_ref.checked && checkedForm.invoice_in_ids[i].value==checkedForm.invoice_in_ref.value) 	values_ref=1;
						else if (checkedForm.invoice_in_ref_count.value >1 ){
							ii =  checkedForm.invoice_in_ids[i].value;
							for (var j = 0; j < checkedForm.invoice_in_ref.length; j++) {
									if (checkedForm.invoice_in_ref[j].value == ii)
											values_ref=1;
							}

						}
				  	 }
				  }

		 }
		 }
	if (values == "") {
		alert("'.FK_CD_MSG_BPOZDOREF .'");
		return;
	} else {
		if (values_ref==1){
			if (!confirm("'. FK_CD_MSG_REFERROR .'"))
			return;
		}
		if (!confirm("'. FK_CD_MSG_REFCONF .'"))
			return;
		MM_openBrWindow(\'../finances/FK_invoices_out_add.php?case_id='.$case_id.'&invoice_in_ids=\'+ values+\'&tryb=\'+tryb,\'\',\'scrollbars=yes,resizable=yes,width=1320,height=770,left=50,top=40\');
	}
}


function noteOut() {
	var values = "";
	var values_ref = "";
	checkedForm = document.getElementById(\'form3\');

		 if (checkedForm) {
			 	if (document.getElementById(\'invoice_in_ids_count\').value == 1 && document.getElementById(\'invoice_in_ids\').checked) {

			 		values = checkedForm.invoice_in_ids.value;
			 		if (checkedForm.invoice_in_ref_count.value == 1 && checkedForm.invoice_in_ref.checked && checkedForm.invoice_in_ids.value==checkedForm.invoice_in_ref.value) {
						values_ref=1;
			 		}

			 } else if (checkedForm.invoice_in_ids_count.value > 1) {
			 	  var start = 1;
				  for (var i = 0; i < checkedForm.invoice_in_ids.length; i++) {
				  	 if (checkedForm.invoice_in_ids[i].checked){
				  		if (start == 1 ) {
							values += checkedForm.invoice_in_ids[i].value;
							start = 0;
						} else
							values +=  "," + checkedForm.invoice_in_ids[i].value;

						if (checkedForm.invoice_in_ref_count.value == 1 && checkedForm.invoice_in_ref.checked && checkedForm.invoice_in_ids[i].value==checkedForm.invoice_in_ref.value) 	values_ref=1;
						else if (checkedForm.invoice_in_ref_count.value >1 ){
							ii =  checkedForm.invoice_in_ids[i].value;
							for (var j = 0; j < checkedForm.invoice_in_ref.length; j++) {
									if (checkedForm.invoice_in_ref[j].value == ii)
											values_ref=1;
							}

						}
				  	 }
				  }

		 }
		 }
	if (values == "") {
		alert("'.FK_CD_MSG_BPOZDOREF .'");
		return;
	} else {
		if (values_ref==1){
			if (!confirm("'. FK_CD_MSG_REFERROR .'"))
			return;
		}
		if (!confirm("'. FK_CD_MSG_REFCONF .'"))
			return;
		MM_openBrWindow(\'../finances/FK_debitnote_out_add.php?case_id='.$case_id.'&invoice_in_ids=\'+ values,\'\',\'scrollbars=yes,resizable=yes,width=1320,height=770,left=50,top=40\');
	}
}


function  noteOutCorrect(){

	ilosc = document.getElementById(\'note_out_ids_count\').value;
	lista = document.getElementsByName(\'note_out_ids\');

	ilosc_wybrana=0
	values=0;
	for (var i = 0; i < lista.length; i++) {
			if(lista[i].checked){
				ilosc_wybrana++;
				values = lista[i].value;
			}
	}

	if (ilosc_wybrana==0){
		alert(\'Proszê wybraæ fakturê do korekty!\');
	}else if (ilosc_wybrana>1){
		alert(\'Zaznaczono wiêcej ni¿ jedn± pozycjê!\');
	}else{
			MM_openBrWindow(\'../finances/FK_debitnote_out_correct.php?note_out_id=\'+ values,\'\',\'scrollbars=yes,resizable=yes,width=1120,height=670,left=50,top=40\');
	}

}


function invoiceOutHonorarium() {
		MM_openBrWindow(\'../finances/FK_invoices_out_honorarium_add.php?case_id='.$case_id.'\',\'\',\'scrollbars=yes,resizable=yes,width=1120,height=620,left=50,top=40\');
	}
//-->
</script>
<style type="text/css">
<!--
.style6 {color: #0099FF}
-->
</style>
</head>

<body>
<br>



<form action="<?php echo $editFormAction; ?>" method="POST" name="form2">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" width="700">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" colspan="4" nowrap style="border: #000000 1px solid;"><strong>'. FK_CD_CASEDETAILS .'</strong></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td width="98" align="right" nowrap>'. FK_CD_TOW .'&nbsp;</td>
    <td width="225"><input name="insurer" type="text" disabled="yes" id="insurer" value="'.$row_case['name'].'" size="30" maxlength="30">
      <input name="button" type="button" style="width: 20px" title="'. FK_CD_TOWDETAILS .'" onclick="MM_openBrWindow(\'GEN_contrahents_details.php?contrahent_id='.$row_case['contrahent_id'].'&action=view\',\'\',\'scrollbars=yes,resizable=yes,width=650,height=620\')" value="&gt;"></td>
    <td width="58"><div align="right">'. FK_CD_TEL .'&nbsp;</div></td>
    <td width="209"><input name="paxphone" type="text" id="paxphone" value="'.$row_case['paxphone'].'" size="15" maxlength="15"></td>
  </tr>
</table>
<input type="hidden" name="MM_update" value="form2">
<br>
</form>

<form name="form3" id="form3" method="post" action="">
<table width="700" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
	<tr valign="baseline" bgcolor="#CCCCCC">
	  <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. FK_CD_INVIN .'</strong></td>
	</tr>
	<tr valign="baseline" bgcolor="#FFFFFF">
	  <td align="right" nowrap></td>
	</tr>
	<tr valign="baseline" bgcolor="#FFFFFF">
	  <td align="right" nowrap><table width="700" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed">
        <tr bgcolor="#CCCCCC">
          <th width="20"><div align="center"></div></th>
          <th width="5">&nbsp;</th>
          <th width="5">&nbsp;</th>
          <th width="5">&nbsp;</th>
          <th width="5">&nbsp;</th>
          <th width="5">&nbsp;</th>
          <th width="20">&nbsp;</th>
          <th width="20">&nbsp;</th>
          <th width="90"><div align="center">'. FK_CD_DATE .'</div></th>
          <th width="100"><div align="center">'. FK_CD_INVNR .'</div></th>
          <th><div align="center">'. FK_CD_WYST .'</div></th>
          <th width="80"><div align="center">'. FK_CD_KWOT .'</div></th>
          <th width="30"><div align="center">'. FK_CD_CURR .'</div></th>
        <th width="40"><div align="center">'. FK_CD_USER .'</div></th>
          </tr>';

	$i = 0;
	$licznik=0;
	$licznik2=0;
		while ($row_invoices_in = mysql_fetch_assoc($invoices_in)){
		        $result .= '<tr bgcolor="';

		        if ($row_invoices_in['stop'] == 1) $result .=  "red";
		        else if ($row_invoices_in['urgent'] == 1) $result .= "yellow"; else $result .= ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ;

		        $result .='" ';
		        	 	$result .= ($row_invoices_in['deleted']==1 ? 'style="text-decoration: line-through;"' : '' );
		        $result .= '>
		          <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'../finances/FK_invoices_in_details.php?invoice_in_id='.$row_invoices_in['invoice_in_id'].'\',\'\',\'scrollbars=yes,resizable=yes,width=900,height=760,left=20,top=20\');"></td>
		          		          <td align="left" nowrap bgcolor="'. ($row_invoices_in['listing']==0 && $row_invoices_in['payment']==0 && $row_invoices_in['payment_confirmed']==0 ?  "#99FF66" : '' ).'" title="'.FK_ININDET_ZATW.'">&nbsp;</td>
		          <td align="left" nowrap bgcolor="'.( ($row_invoices_in['listing'] == 1) ?  "#FFCC66" : '' ).'" title="'.FK_ININDET_DOOPL.'">&nbsp;</td>
		          <td align="left" nowrap bgcolor="'. ( $row_invoices_in['payment']==1 ?  "#F000FF" : '' ) .'" title="'.FK_ININDET_OCZPL.'">&nbsp;</td>
		          <td align="left" nowrap bgcolor="'. ( $row_invoices_in['payment_confirmed']==1 ?  "#00CC66" : '' ) .'" title="'.FK_ININDET_OPL.'">&nbsp;</td>
		          <td align="left" nowrap bgcolor="'. ($row_invoices_in['correct']==1 ?  "#FF0000" : '' ) .'" title="'.FK_ININDET_CORRECT.'">&nbsp;</td>
		          <td align="center" nowrap>';


		          			if ( $row_invoices_in['client_amount'] > 0.00 && $row_invoices_in['refaktor']==1){
		          					$result .= ' <input name="invoice_in_ref" type="checkbox" id="invoice_in_ref" value="'.$row_invoices_in['invoice_in_id'].'" title="Faktura zrefakturowana" checked onClick="return false;" style="border: #FF0000 1px solid;">';
							$licznik2++;
		        		}

		          $result .= '</td>
		          <td align="center" nowrap>';

		          		if (  $row_invoices_in['client_amount'] > 0.00 ){
		          			$result .= ' <input name="invoice_in_ids" type="checkbox" id="invoice_in_ids" value="'.$row_invoices_in['invoice_in_id'].'" title="'.FK_CD_INVREF.'">';
							$licznik++;
		        		}

		          $result .= '</td>
		          <td align="center" nowrap>'.$row_invoices_in['date'].'</td>

		          <td align="left" nowrap><span class="style6">'. $row_invoices_in['invoice_in_no'].'</span></td>
		          <td align="left" >'.  "(".$row_invoices_in['contrahent_id'].")".$row_invoices_in['contrahent_name'] .'</td>
		          <td align="right" nowrap><strong>'. $row_invoices_in['amount'] .'</strong></td>
		          <td align="center" nowrap><strong>'. $row_invoices_in['currency_id'] .'</strong></td>
		          <td align="center" nowrap>'. $row_invoices_in['initials'].'</td>
		        </tr>
		        <tr>
		          <td nowrap style="background: #ffffff">&nbsp;</td>
		          <td colspan="11" align="left" nowrap style="background: lightyellow"><small>'. $row_invoices_in['activity'].'</small></td>
		        </tr>';
		        if ($row_invoices_in['correct']==1){


		          		$queryc = "SELECT coris_finances_invoices_in_correct.*,
						(SELECT initials FROM  coris_users   WHERE coris_users.user_id = coris_finances_invoices_in_correct.user_id) As initials

		FROM coris_finances_invoices_in_correct
		WHERE coris_finances_invoices_in_correct.invoice_in_id = '".$row_invoices_in['invoice_in_id']."'

		";
		$invoices_in_correct = mysql_query($queryc);

		while ($row_correct_invoice = mysql_fetch_array($invoices_in_correct)){
		        $result .= '<tr bgcolor="#FFAEB0">
		          <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'../finances/FK_invoices_in_correct.php?correct_inv_in_id='.$row_correct_invoice['ID'].'\',\'\',\'scrollbars=yes,resizable=yes,width=1100,height=750,left=20,top=20\');"></td>
		          <td align="center" colspan="7">&nbsp;</td>
		          <td align="center" nowrap>'.$row_correct_invoice['correct_date'].'</td>

		          <td align="left" nowrap><span class="style6">POK-'. $row_correct_invoice['correct_in_no'].'/'.$row_correct_invoice['correct_in_year'].'</span></td>
		          <td align="left" >'.  "(".$row_invoices_in['contrahent_id'].")".$row_invoices_in['contrahent_name'] .'</td>
		          <td align="right" nowrap><strong>'. $row_correct_invoice['gross_amount'] .'</strong></td>
		          <td align="center" nowrap><strong>'. $row_correct_invoice['currency_id'] .'</strong></td>
		          <td align="center" nowrap>'. $row_correct_invoice['initials'].'</td>
		        </tr>
		       ';


		}


		        }

		}
		$result .=  '<input type="hidden" name="invoice_in_ids_count" id="invoice_in_ids_count" value="'.$licznik.'">';
		$result .=  '<input type="hidden" name="invoice_in_ref_count" id="invoice_in_ref_count" value="'.$licznik2.'">';
		$result .= '
            </table>
	</tr>
	<tr>
		<td align="right" nowrap><div align="center">
		<input name="Button" type="button" onClick="MM_openBrWindow(\'../finances/FK_invoices_in_add.php?case_id='.$case_id.'&case_number='.$row_case['case_number'] .'&paxname='.urlencode(addslashes(stripslashes($row_case['paxsurname'].' '.$row_case['paxname']))).'\',\'\',\'scrollbars=yes,resizable=yes,width=900,height=740,left=50,top=40\')" value="'. INVOICEINADD .'" style="width: 120px">
		&nbsp;
		<input type="button" name="Button" value="'.FIN_REINVOICE.'" style="width: 120px" onClick="invoiceOut(0);">
		&nbsp;<input type="button" name="Button" value="'.FIN_REINVOICEWFEE.'" style="width: 150px" onClick="invoiceOut(1);">
		<input type="button" name="Button" value="'.FIN_REINVOICETONOTE.'" style="width: 120px" onClick="noteOut(0);">
		&nbsp;<input type="button" name="Button" value="'.FIN_CORRINVOICE.'" style="width: 150px" onClick="invoiceCorrect();">
		</div></td>
	</tr>
</table>
</form>
<br>';


$result .= '<br>
<table width="700" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. FK_CD_INVOUT .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed">
        <tr bgcolor="#CCCCCC">
          <th width="20"><div align="center"></div></th>
          <th width="5">&nbsp;</th>
          <th width="25">&nbsp;</th>
          <th width="90"><div align="center">'. FK_CD_DATE .'</div></th>

          <th width="100"><div align="center">'. FK_CD_INVNR .'</div></th>
          <th><div align="center">'. FK_CD_ODB .'</div></th>
          <th width="80"><div align="center">'. FK_CD_KWOT .'</div></th>
          <th width="30"><div align="center">'. FK_CD_CURR .'</div></th>
          <th width="40"><div align="center">'. FK_CD_USER .'</div></th>
        </tr>';

		$i = 0;
		$licznik_poz=0;
		while ($row_invoices_out = mysql_fetch_assoc($invoices_out)){
		        $result .= '<tr bgcolor="';
			 if ($row_invoices_out['urgent'] == 1) $result .= "yellow"; else $result .= ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ;



			 $result .= '"' ;
			 	$result .= ($row_invoices_out['deleted']==1 ? 'style="text-decoration: line-through;"' : '' );
			 $result .= '>
          <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'../finances/FK_invoices_out_details.php?invoice_out_id='.$row_invoices_out['invoice_out_id'].'\',\'\',\'scrollbars=yes,resizable=yes,width=1120,height=680\')"></td>
          <td align="left" nowrap bgcolor="';
			 		if ($row_invoices_out['payment'] == 1) $result .= "green";
			 		$result .='">&nbsp;</td>
          <td align="center" nowrap>'.
           ( ($row_invoices_out['invoice_out_no']>0  && $row_invoices_out['booking']==1 ) ? '<input name="invoice_out_ids" type="checkbox"  value="'.$row_invoices_out['invoice_out_id'].'" title="">' : '&nbsp' )
          .'</td>
          <td align="center" nowrap>'. $row_invoices_out['date'] .'</td>

          <td align="left" nowrap><span class="style6">';
			 		if ($row_invoices_out['invoice_out_no']>0)
			 		 	$result .= "A".$row_invoices_out['invoice_out_no'].'/'.substr($row_invoices_out['invoice_out_year'],2,2);
			 		 else
			 		 	$result .= '&nbsp;'	;
			 		 $result .= '</span></td>
          <td align="left" >'. "(".$row_invoices_out['contrahent_id'].")".$row_invoices_out['contrahent_name'].'</td>
          <td align="right" nowrap><strong>'.$row_invoices_out['gross_amount'].'</strong></td>
          <td align="center" nowrap><strong>'.$row_invoices_out['currency_id'].'</strong></td>
          <td align="center" nowrap>'. getUserInitials($row_invoices_out['user_id']).'</td>
        </tr>';

			$query = "SELECT * FROM coris_finances_invoices_out_positions  WHERE invoice_out_id='".$row_invoices_out['invoice_out_id']."' ";
			$mysq_result = mysql_query($query);
			$licznik = 1;
			if (mysql_num_rows($mysq_result)>0){
				$result .= '<tr><td colspan=1>&nbsp;</td><td colspan=8 align=left bgcolor="#FFFFFF">';
				while ($row_positions=mysql_fetch_array($mysq_result)){
					$result .= $licznik.'. '.$row_positions['activity_note'].'<br>';
					$licznik++;
				}
				$result .= '</td></tr>';
			}

			$licznik_poz++;


		 $query = "SELECT coris_finances_invoices_out_correct.*,
		(SELECT initials FROM  coris_users   WHERE coris_users.user_id = coris_finances_invoices_out_correct.user_id) As initials
		FROM coris_finances_invoices_out_correct
		WHERE coris_finances_invoices_out_correct.case_id = '$case_id' AND
		 coris_finances_invoices_out_correct.invoice_out_id = '".$row_invoices_out['invoice_out_id']."'
		";


		$invoices_out_correct = mysql_query($query);
		while ($row_correct_invoice = mysql_fetch_array($invoices_out_correct)){
		         $result .= '<tr bgcolor="#FFAEB0">
		          <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'../finances/FK_invoices_out_correct.php?correct_inv_out_id='.$row_correct_invoice['ID'].'\',\'\',\'scrollbars=yes,resizable=yes,width=1100,height=750,left=20,top=20\');"></td>		          <td colspan="2">&nbsp;</td>
		          <td align="center" nowrap>'.$row_correct_invoice['correct_date'].'</td>
		          <td align="left" nowrap><span class="style6">FK-'. $row_correct_invoice['correct_out_no'].'/'.$row_correct_invoice['correct_out_year'].'</span></td>


		          <td align="left" >'. "(".$row_invoices_out['contrahent_id'].")".$row_invoices_out['contrahent_name'].'</td>
		          <td align="right" nowrap><strong>'. $row_correct_invoice['gross_amount'] .'</strong></td>
		          <td align="center" nowrap><strong>'. $row_correct_invoice['currency_id'] .'</strong></td>
		          <td align="center" nowrap>'. $row_correct_invoice['initials'].'</td>
		        </tr>
		       ';


		}
			 $result .= '</tr>';

		}

      $result .= '</table>
      	<input type="hidden" name="invoice_out_ids_count" id="invoice_out_ids_count" value="'.$licznik_poz.'">
  </tr>
	<tr>
		<td align="center">
			<input name="" type="button" value="'.FK_CD_FEE .'" OnClick="invoiceOutHonorarium();">
			&nbsp;<input type="button" name="Button" value="'.FIN_CORRINVOICE.'" style="width: 150px" onClick="invoiceOutCorrect();">
	  </td>
	</tr>
</table>
<br>';




$result .= '<br>
<table width="700" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'. FK_CD_DEBITNOTE .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1" style="table-layout:fixed">
        <tr bgcolor="#CCCCCC">
          <th width="20"><div align="center"></div></th>
          <th width="5">&nbsp;</th>
          <th width="25">&nbsp;</th>
          <th width="90"><div align="center">'. FK_CD_DATE .'</div></th>

          <th width="100"><div align="center">'. FK_CD_NOTENR .'</div></th>
          <th><div align="center">'. FK_CD_ODB .'</div></th>
          <th width="80"><div align="center">'. FK_CD_KWOT .'</div></th>
          <th width="30"><div align="center">'. FK_CD_CURR .'</div></th>
          <th width="40"><div align="center">'. FK_CD_USER .'</div></th>
        </tr>';

		$i = 0;
		$licznik_poz=0;
		while ($row_note_out = mysql_fetch_assoc($note_out)){
		        $result .= '<tr bgcolor="';
			 if ($row_note_out['urgent'] == 1) $result .= "yellow"; else $result .= ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ;



			 $result .= '"' ;
			 	$result .= ($row_note_out['deleted']==1 ? 'style="text-decoration: line-through;"' : '' );
			 $result .= '>
          <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'../finances/FK_debitnote_out_details.php?debitnote_out_id='.$row_note_out['ID'].'\',\'\',\'scrollbars=yes,resizable=yes,width=1120,height=680\')"></td>
          <td align="left" nowrap bgcolor="';
			 		if ($row_note_out['payment'] == 1) $result .= "green";
			 		$result .='">&nbsp;</td>
          <td align="center" nowrap>'.
           ( ($row_note_out['invoice_out_no']>0  && $row_note_out['booking']==1 ) ? '<input name="note_out_ids" type="checkbox"  value="'.$row_note_out['ID'].'" title="">' : '&nbsp' )
          .'</td>
          <td align="center" nowrap>'. $row_note_out['date'] .'</td>

          <td align="left" nowrap><span class="style6">';
			 		if ($row_note_out['invoice_out_no']>0)
			 		 	$result .= "N".$row_note_out['invoice_out_no'].'/'.substr($row_note_out['invoice_out_year'],2,2);
			 		 else
			 		 	$result .= '&nbsp;'	;
			 		 $result .= '</span></td>
          <td align="left" >'. "(".$row_note_out['contrahent_id'].")".$row_note_out['contrahent_name'].'</td>
          <td align="right" nowrap><strong>'.$row_note_out['amount'].'</strong></td>
          <td align="center" nowrap><strong>'.$row_note_out['currency_id'].'</strong></td>
          <td align="center" nowrap>'. getUserInitials($row_note_out['user_id']).'</td>
        </tr>';

			$query = "SELECT * FROM coris_finances_debitnote_out_positions   WHERE ID_debitnote_out ='".$row_note_out['ID']."' ";
			$mysq_result = mysql_query($query);
			$licznik = 1;
			if (mysql_num_rows($mysq_result)>0){
				$result .= '<tr><td colspan=1>&nbsp;</td><td colspan=8 align=left bgcolor="#FFFFFF">';
				while ($row_positions=mysql_fetch_array($mysq_result)){
					$result .= $licznik.'. '.$row_positions['activity_note'].'<br>';
					$licznik++;
				}
				$result .= '</td></tr>';
			}

			$licznik_poz++;


		 $query = "SELECT coris_finances_debitnote_out_correct.*,
		(SELECT initials FROM  coris_users   WHERE coris_users.user_id = coris_finances_debitnote_out_correct.user_id) As initials
			FROM coris_finances_debitnote_out_correct
				WHERE coris_finances_debitnote_out_correct .case_id = '$case_id' AND
		 	coris_finances_debitnote_out_correct.ID_debitnote_out = '".$row_note_out['ID']."'
		";


		$invoices_out_correct = mysql_query($query);
		while ($row_correct_note = mysql_fetch_array($invoices_out_correct)){
		         $result .= '<tr bgcolor="#FFAEB0">
		          <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow(\'../finances/FK_debitnote_out_correct.php?correct_inv_out_id='.$row_correct_note['ID'].'\',\'\',\'scrollbars=yes,resizable=yes,width=1100,height=750,left=20,top=20\');"></td>		          <td colspan="2">&nbsp;</td>
		          <td align="center" nowrap>'.$row_correct_note['correct_date'].'</td>
		          <td align="left" nowrap><span class="style6">NK-'. $row_correct_note['correct_out_no'].'/'.$row_correct_note['correct_out_year'].'</span></td>

		          <td align="left" >'. "(".$row_note_out['contrahent_id'].")".$row_note_out['contrahent_name'].'</td>
		          <td align="right" nowrap><strong>'. $row_correct_note['amount'] .'</strong></td>
		          <td align="center" nowrap><strong>'. $row_correct_note['currency_id'] .'</strong></td>
		          <td align="center" nowrap>'. $row_correct_note['initials'].'</td>
		        </tr>
		       ';


		}
			 $result .= '</tr>';

		}

      $result .= '</table>
      	<input type="hidden" name="note_out_ids_count" id="note_out_ids_count" value="'.$licznik_poz.'">
  </tr>
	<tr>
		<td align="center">

			&nbsp;<input type="button" name="Button" value="'.FIN_CORRINVOICE.'" style="width: 150px" onClick="noteOutCorrect();">
	  </td>
	</tr>
</table>
<br>';




return $result;
}

?>