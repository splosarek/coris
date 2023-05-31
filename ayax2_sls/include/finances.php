<?php



function currency_invoice_out($param="",$default){

	$query_currencies = "SELECT coris_finances_currencies.currency_id FROM coris_finances_currencies WHERE coris_finances_currencies.active = 1 AND coris_finances_currencies.invoice_out=1 ORDER BY coris_finances_currencies.currency_id";
	$mysql_result = mysql_query($query_currencies);
	$result = '<select name="currency_out_id" class="required" id="currency_out_id" '.$param.'>';
    while ($row = mysql_fetch_array($mysql_result)){
    		$sel  = $row['currency_id']==$default ? 'SELECTED' : '';
       		$result .= '<option value="'.$row['currency_id'].'" '.$sel.'>'.$row['currency_id'].'</option>';
    }
	$result .= '</select>';
	mysql_free_result($mysql_result);
	return $result;
}


function getUserInitials($id){
	if ($id==0) return;
	$query = "SELECT initials FROM coris_users WHERE user_id ='$id'";
	$mysql_result = mysql_query($query);

	$row= mysql_fetch_array($mysql_result);
	return $row[0];
}

function getUserName($id){
	if ($id==0) return;
	$query = "SELECT name, surname FROM coris_users WHERE user_id ='$id'";
	$mysql_result = mysql_query($query);

	$row= mysql_fetch_array($mysql_result);
	return $row[0].' '.$row[1];
}

function print_paymenttypes($name,$default,$class,$onclick=''){

	$query = "SELECT * FROM coris_finances_paymenttypes";
	$mysql_result = mysql_query($query) or die(mysql_error());

		$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
          <option value=""></option>';
		while ($row_currencies = mysql_fetch_array($mysql_result)){
			$sel = ($row_currencies['paymenttype_id']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row_currencies['paymenttype_id'].'" '.$sel.'>'.$row_currencies['value'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}


function print_paymenttypes_name($id){
	$query = "SELECT * FROM coris_finances_paymenttypes WHERE paymenttype_id='$id'";
	$mysql_result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($mysql_result)>0){
		$row = mysql_fetch_array($mysql_result);
		return $row['value'];
	}
 		return '';
}

function print_ratetype($name,$default,$class,$onclick=''){

	$query = "SELECT coris_finances_currencies_tables_ratetypes.ratetype_id, coris_finances_currencies_tables_ratetypes.`value` FROM coris_finances_currencies_tables_ratetypes";
	$mysql_result = mysql_query($query) or die(mysql_error());

		$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
          <option value=""></option>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ratetype_id']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ratetype_id'].'" '.$sel.'>'.$row['value'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}

function print_table_source($name,$default,$class,$onclick=''){

	$query = "SELECT * FROM coris_finances_currencies_tables_source ORDER BY ID";
	$mysql_result = mysql_query($query) or die(mysql_error());

		$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>         ';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['value'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}


function print_currency_all($name,$default,$class,$onclick=''){

	$query_currencies = "SELECT coris_finances_currencies.currency_id FROM coris_finances_currencies WHERE coris_finances_currencies.active = 1 ORDER BY coris_finances_currencies.currency_id";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
          <option value=""></option>';
		while ($row_currencies = mysql_fetch_array($mysql_result)){
			$sel = ($row_currencies['currency_id']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row_currencies['currency_id'].'" '.$sel.'>'.$row_currencies['currency_id'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}

function print_booking_types($name,$default,$class,$onclick='',$grupa,$var=''){

	if ($var<>'') $var=' AND '.$var;
	$query_bookingtypes = "SELECT bookingtype_id, value FROM coris_finances_bookings_bookingtypes WHERE symbol = '$grupa' AND active = 1 $var ORDER BY kolejnosc,value";
	$mysql_result = mysql_query($query_bookingtypes) or die(mysql_error());
	$sel_name = '';
	$result = '';
	if ($onclick=='disabled'){
		$sel_name = $name.'_dis';
		$result .= '<input type=hidden name="'.$name.'" value="'.$default.'">';
	}else
		$sel_name = $name	;
	$result .= '<select name="'.$sel_name.'" class="'.$class.'" id="'.$sel_name.'" '.$onclick.'>';
        	if ($var=='')	$result .='<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['bookingtype_id']==$default) ? 'SELECTED' : '' ;

			$result .= ' <option value="'.$row['bookingtype_id'].'" '.$sel.'>'.$row['bookingtype_id'].' - '.$row['value'].'</option>';
	}
	$result .= '</select>';
	return $result;
}


function print_vatrates($name,$default,$class,$onclick='',$date=''){

	if ($date=='' ){
			if ( date('Y-m-d') >= '2011-01-01'){
				$var= 'current=1';
			}else{
				$var= 'archive=1';
			}
	}else{
			if ( $date >= '2011-01-01'){
				$var= 'current=1';
			}else{
				$var= 'archive=1';
			}

	}

	$query = "SELECT * FROM coris_finances_vatrates WHERE $var ORDER BY sort";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$rate= array();
	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
        		<option value="0" ></option>	';
	$rate[0] = 0;
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['vatrate_id']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['vatrate_id'].'" '.$sel.'>'.$row['value'].'</option>';

			$rate[$row['vatrate_id']] = $row['rate'];
	}
	$result .= '</select>';
	//return $result.'<script>vat_array = new Array('.implode(',',$rate).')</script>';
			 $result .= '<script>
		var vat_array = new Object();';
		foreach ($rate As $key=>$val)
			$result .= 'vat_array['.$key.'] = '.$val.';';

		$result .= '</script>';
		return $result;

}

function print_vatrates_ref($name,$default,$class,$onclick='',$tryb=false,$date=''){ // dorefaktury

	$zm = '';
	if ($date=='' ){
			if ( date('Y-m-d') >= '2011-01-01'){
				$zm = 'current';
			}else{
				$zm = 'archive';
			}
	}else{
			if ( $date >= '2011-01-01'){

				$zm = 'current';
			}else{
				$zm = 'archive';
			}
	}

	$query = "SELECT * FROM coris_finances_vatrates  ORDER BY sort";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$rate= array();
	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
        			';
	$rate[0] = 0;
	while ($row = mysql_fetch_array($mysql_result)){
		if ($row[$zm] == 1){
			$sel = ($row['vatrate_id']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['vatrate_id'].'" '.$sel.'>'.$row['description'].'</option>';
		}
			$rate[$row['vatrate_id']] = $row['rate'];
	}
	$result .= '</select>';
	if ($tryb){
		 $result .= '<script>
		var vat_array = new Object();';
		foreach ($rate As $key=>$val)
			$result .= 'vat_array['.$key.'] = '.$val.';';

		$result .= '</script>';
		return $result;


	}else{
		return $result;
	}
}

function print_vatrates_ref_hid($name,$default,$class,$onclick='',$tryb=false,$date=''){ // dorefaktury

	$zm = '';
	if ($date=='' ){
			if ( date('Y-m-d') >= '2011-01-01'){
				$zm = 'current';
			}else{
				$zm = 'archive';
			}
	}else{
			if ( $date >= '2011-01-01'){

				$zm = 'current';
			}else{
				$zm = 'archive';
			}
	}
	$result = '';
	$query = "SELECT * FROM coris_finances_vatrates  ORDER BY sort";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$rate= array();

	$rate[0] = 0;
	if ($row = mysql_fetch_array($mysql_result)){
		if ($row[$zm] == 1){
			//$sel = ($row['vatrate_id']==$default) ? 'SELECTED' : '' ;
			//$result .= ' <option value="'.$row['vatrate_id'].'" '.$sel.'>'.$row['description'].'</option>';
			if ($row['vatrate_id']==$default)
				$result .= '<input name="'.$name.'" class="'.$class.'" id="'.$name.'" value="'.$row['vatrate_id'].'" >';
		}
			$rate[$row['vatrate_id']] = $row['rate'];
	}
//	$result .= '</select>';
	if ($tryb){
		 $result .= '<script>
		var vat_array = new Object();';
		foreach ($rate As $key=>$val)
			$result .= 'vat_array['.$key.'] = '.$val.';';

		$result .= '</script>';
		return $result;


	}else{
		return $result;
	}
}


function getVatrate($name,$def,$option='',$var='',$date=''){

	$where = ' WHERE 1=1 ';


	$where .= $var != '' ? ' AND '.$var : '';

	$zm = '';
		if ($date=='' ){
			if ( date('Y-m-d') >= '2011-01-01'){
				$zm = 'current';
			}else{
				$zm = 'archive';
			}
	}else{
			if ( $date >= '2011-01-01'){

				$zm = 'current';
			}else{
				$zm = 'archive';
			}
	}
		$qv = "SELECT * FROM coris_finances_vatrates $where  ORDER BY `sort` ASC";

	$result = '<select name="'.$name.'" id="'.$name.'" '.$option.'>';

			$mr=  mysql_query($qv);
			$lista_stawek=array();
			while ($row_vat =mysql_fetch_array($mr)){
				if ($row_vat[$zm] == 1){
					$result .=  '<option value="'.$row_vat['vatrate_id'].'" '.($def==$row_vat['vatrate_id'] ? 'selected' : '' ).'>'.substr($row_vat['value'],0,15).'</option>';
				}
				$lista_stawek[$row_vat['vatrate_id']] = $row_vat['rate'];

				if ($row_vat['archive'] == 1){
					$lista_stawek_arh[$row_vat['vatrate_id']] = substr($row_vat['value'],0,15);
				}

				if ($row_vat['current'] == 1){
					$lista_stawek_curr[$row_vat['vatrate_id']] = substr($row_vat['value'],0,15);
				}
			}
		$result .= '</select>

		<script>

window.addEvent(\'domready\', function() {


        	stawki_vat = new Array();
        	stawki_vat_arch = new Array();
        	stawki_vat_curr = new Array();
        	';
        	foreach ($lista_stawek  As $key => $val)
        		$result .=  "stawki_vat[$key] = $val ; \n";


        	$lista = array();
        	foreach ($lista_stawek_arh  As $key => $val)
        		$lista[]  .=  " $key:  '$val' ";
        	$result .= "\n stawki_vat_arch=new Hash({ 	".implode(', ', $lista)."   });";

        	$lista = array();
        	foreach ($lista_stawek_curr  As $key => $val)
        		$lista[]  .=  " $key:  '$val' ";
        	$result .= "\n stawki_vat_curr=new Hash({ 	".implode(', ', $lista)."   });";

$result .= '
})';
       $result .= '</script>';
       return $result;

}
function print_contrahents_language($name,$default,$class,$onclick=''){

	$query = "SELECT * FROM coris_contrahents_language ORDER BY ID";
	$mysql_result = mysql_query($query) or die(mysql_error());

    $rate= array();
	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>';
	if (mysql_num_rows($mysql_result)>1)
		$result .= '<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['ID'].'" '.$sel.'>'.$row['value'].'</option>';
	}
	$result .= '</select>';
	return $result;
}

function print_coris_account($name,$default,$class,$onclick=''){

	$query = "SELECT * FROM coris_accounts ORDER BY sort,account_id DESC";
	$mysql_result = mysql_query($query) or die(mysql_error());

    $rate= array();
	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>';
	if (mysql_num_rows($mysql_result)>1)
		$result .= '<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['account_id']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['account_id'].'" '.$sel.'>'.substr($row['account'].' / '.$row['swift'].' / '.substr($row['name'],0,40).' /'.$row['country_id'],0,60).'</option>';
	}
	$result .= '</select>';
	return $result;
}


function print_contrahents_account($name,$default,$class,$onclick='',$contrahent_id){

	$query = "SELECT * FROM coris_contrahents_accounts WHERE contrahent_id = '$contrahent_id' ORDER BY `order`,account_id DESC";
	$mysql_result = mysql_query($query) or die(mysql_error());

    $rate= array();
	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>';
	if (mysql_num_rows($mysql_result)>1)
		$result .= '<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['account_id']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['account_id'].'" '.$sel.'>'.$row['account'].' / '.$row['swift'].' / '.substr($row['name'],0,40).' /'.$row['country_id'].'</option>';
	}
	$result .= '</select>';
	return $result;
}
function getChargeName($id,$type_id,$group_id){

	if ($id==0) return '';
	$query  = "SELECT value FROM coris_finances_charges WHERE charge_id ='$id' AND type_id = '$type_id' AND group_id='$group_id' ";
	$mysql_result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($mysql_result)==0)
		return '';
	else{
		$row=mysql_fetch_array($mysql_result);
		return $row[0];
	}
}

function getCaseInfo($case_id){
	$query = "SELECT coris_contrahents.contrahent_id ,coris_contrahents.name,coris_contrahents.o_klnagsim As shortName,coris_assistance_cases.client_id,
					coris_assistance_cases.paxname,coris_assistance_cases.policy,coris_assistance_cases.paxname,coris_assistance_cases.paxsurname,coris_assistance_cases.type_id,
					coris_assistance_cases.number,coris_assistance_cases.year,coris_assistance_cases.client_ref, concat(number,'/',substring(year,3,2),'/',type_id,'/',client_id) As fullNumber,
					marka_model,nr_rej ,coris_assistance_cases.coris_branch_id
					FROM coris_assistance_cases,coris_contrahents
					WHERE coris_assistance_cases.case_id='$case_id' AND coris_contrahents.contrahent_id=coris_assistance_cases.client_id LIMIT 1" ;
	$mysql_result = mysql_query($query) OR die (mysql_error());

	if (mysql_num_rows($mysql_result)==0) return null;
	$row= mysql_fetch_array($mysql_result);
	return $row;
}


function getHttpValue($name,$value = null){

	if (isset($_POST[$name]) )
			$value =   addslashes(stripslashes(trim($_POST[$name]))) ;
	else if (isset($_GET[$name]) )
			$value =   addslashes(stripslashes(trim($_GET[$name]))) ;
	return $value;
}


function getContrahnetParam($id,$param){
	$query = "SELECT $param FROM coris_contrahents WHERE contrahent_id='$id'  LIMIT 1";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_array($mysql_result);
	return $row[0];
}


function getContrahnetInitials($id,$case_id){

	$case_date = '';
	if ($case_id>0){
		$query = "SELECT  notificationdate FROM coris_assistance_cases_details WHERE case_id='$case_id' ";
		$mysql_result = mysql_query($query);
		$row=mysql_fetch_array($mysql_result);
		$case_date = $row[0];
	}else {
		$case_date = 'now()';
		raporting_mail("case_id=0","case_id=0\n\n".$query);

	}
	$query = "SELECT * FROM coris_contrahents_initials  WHERE contrahent_id='$id' AND active_date <= '$case_date' ORDER BY active_date desc ,ID desc LIMIT 1";
	$mysql_result = mysql_query($query) or die($query."<br>".mysql_error());
	$row=mysql_fetch_array($mysql_result);


	if ($row['reasekurator'] == 0 ) $row['reasekurator'] = $id;

	if ($row['reasekurator_honorarium'] == 0 ) $row['reasekurator_honorarium'] = $id;

	if ($row['due_date_fee'] == 0) $row['due_date_fee']=60;
	if ($row['due_date_grid_fee'] == '') $row['due_date_grid_fee']='dzien';
	if ($row['table_fee'] == 0) $row['table_fee']=1;

	$row['table_fee_source'] = $row['table_fee_source']>1 ? $row['table_fee_source'] : 1 ;
	$row['table_invoice_source'] = $row['table_invoice_source']>1 ? $row['table_invoice_source'] : 1 ;
	$row['open_date_rate'] = $row['open_date_rate']==1 ? 1 : 0 ;

	if ($row['paymenttype_fee'] == 0) $row['paymenttype_fee']=3;

	if ($row['due_date_invoice'] == 0) $row['due_date_invoice']=60;
	if ($row['due_date_grid_invoice'] == '') $row['due_date_grid_invoice']='dzien';

	if ($row['paymenttype_invoice'] == 0) $row['paymenttype_invoice']=3;
	if ($row['table_invoice'] == 0) $row['table_invoice']=1;

	if ($row['currency_fee'] == ''){
		if (getContrahnetParam($id,'country_id') == 'PL')
			$row['currency_fee']='PLN';
		else
			$row['currency_fee']='EUR';
	}

	if ($row['currency_invoice'] == ''){
		if (getContrahnetParam($id,'country_id') == 'PL')
			$row['currency_invoice']='PLN';
		else
			$row['currency_invoice']='EUR';
	}

	$row['amount_fee'] = $row['amount_fee'];

	$query2 = "SELECT default_urgent,default_currency_in,default_payment_due,default_paymenttype_id,default_ignore_contrahent_nip,default_reduction,vat_enable,boook_760_4 FROM coris_contrahents  WHERE contrahent_id='$id'  LIMIT 1";
	$mysql_result2 = mysql_query($query2) or die($query2."<br>".mysql_error());
	$row2=mysql_fetch_array($mysql_result2);

	$row['urgent'] = $row2['default_urgent'];
	$row['currency_in'] = $row2['default_currency_in'];
	$row['default_ignore_contrahent_nip'] = $row2['default_ignore_contrahent_nip'];
	$row['default_paymenttype_id'] = $row2['default_paymenttype_id'];
	$row['default_reduction'] = $row2['default_reduction'];
	$row['vat_enable'] = $row2['vat_enable'];
	$row['boook_760_4'] = $row2['boook_760_4'];

	if ($row2['default_payment_due']>0){
		$row['default_payment_due'] = $row2['default_payment_due'];
	}else{
			if (getContrahnetParam($id,'country_id') == 'PL')
				$row['default_payment_due'] = 14;
			else
				$row['default_payment_due'] = 30;
	}


	$row['country_id']=getContrahnetParam($id,'country_id');
	return $row;
}
/*
function print_currency($val,$prec=2,$sep=''){
	if (is_numeric($val)){
		return number_format($val, $prec, ',', $sep);
	}else{
		$val = str_replace(',','.',$val);
		return number_format($val, $prec, ',',$sep);
	}
}
*/

function print_currency($val,$prec=2,$sep=''){
	if (is_numeric($val)){
		return number_format(ev_round($val,$prec), $prec, ',', $sep);
	}else{
		$val = str_replace(',','.',$val);
		return number_format(ev_round($val,$prec), $prec, ',',$sep);
	}
}

function getActivityName($activity_id,$lang='pl' ){
	$ext = ($lang=='eng' || $lang=='en') ? '_eng' : '';

	$query = "SELECT value$ext FROM coris_finances_activities WHERE activity_id ='$activity_id '";
	$mysql_result = mysql_query($query);
	$row = mysql_fetch_array($mysql_result);
	return $row[0];
}
/*
function activity_lista($name,$default,$type_id,$group_id){
   $result = '<select name="'.$name.'" style="font-size: 8pt;">
                                 ';

$query = "SELECT fc.charge_id, fc.value FROM coris_finances_charges  fc WHERE fc.active = 1 AND fc.type_id = '$type_id' AND group_id='$group_id' ORDER BY charge_id";

*/

function print_charges($name,$default,$default_txt,$class,$onclick='',$type_id,$group_id){

	if ($var<>'') $var=' AND '.$var;
	$query = "SELECT fc.charge_id, fc.value FROM coris_finances_charges  fc WHERE fc.active = 1 AND fc.type_id = '$type_id' AND group_id='$group_id' ORDER BY charge_id";

	//$query_bookingtypes = $query_activities = "SELECT coris_finances_activities.activity_id, coris_finances_activities.value FROM coris_finances_activities ORDER BY coris_finances_activities.value";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$sel_name = '';
	$result = '';
	$disabled = 0;
	if ($onclick=='disabled'){
		$disabled=1;
		$sel_name = $name.'_dis';
		$result .= '<input type=hidden name="'.$name.'"  id="'.$name.'" value="'.$default.'">';
		$result .= '<input type=hidden name="'.$name.'_txt" value="'.$defaul_txt.'">';
	}else
		$sel_name = $name	;

	//$result .= '<input id="'.$sel_name.'_txt" name="'.$sel_name.'_txt" type="text" class="in" value="'.$default_txt.'" size="50"  onChange="document.getElementById(\''.$sel_name.'\').selectedIndex=0;" '. ($disabled==1 ? 'disabled' : ''). '><br>'	;
	//onchange="getElementById(\''.$sel_name.'_txt\').value=getElementById(\''.$sel_name.'_txt\').value = this.options[this.selectedIndex].text" '.$onclick.'
	$result .= '<select name="'.$sel_name.'" class="'.$class.'" id="'.$sel_name.'"  '.$onclick.'>';
        	if ($var=='')	$result .='<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['charge_id']==$default) ? 'SELECTED' : '' ;

			$result .= ' <option value="'.$row['charge_id'].'" '.$sel.'>'.$row['value'].'</option>';
	}
	$result .= '</select>
	';
	return $result;
}

function print_activities($name,$default,$default_txt,$class,$onclick='',$tryb=0,$lang='pl'){

//	if ($var<>'') $var=' AND '.$var;
	//$query = "SELECT fc.charge_id, fc.value FROM coris_finances_charges  fc WHERE fc.active = 1 AND fc.type_id = '$type_id' AND group_id='$group_id' ORDER BY charge_id";
	$query = "SELECT coris_finances_activities.activity_id, coris_finances_activities.value, coris_finances_activities.value_eng FROM coris_finances_activities ORDER BY coris_finances_activities.value";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$sel_name = '';
	$result = '';
	$disabled = 0;
	if ($onclick=='disabled'){
		$disabled=1;
		$sel_name = $name.'_dis';
		$result .= '<input type=hidden name="'.$name.'" value="'.$default.'">';
		$result .= '<input type=hidden name="'.$name.'_txt" value="'.$defaul_txt.'">';
	}else
		$sel_name = $name	;

	$result .= '<input id="'.$sel_name.'_txt" class="'.$class.'" name="'.$sel_name.'_txt" type="text" class="in" value="'.$default_txt.'" size="50"  onChange="document.getElementById(\''.$sel_name.'\').selectedIndex=0;" '.$onclick.'>&nbsp;&nbsp;&nbsp;'	;
	if ($tryb==1) $result .= '<br>';
	$result .= '<select name="'.$sel_name.'" class="'.$class.'" id="'.$sel_name.'" onchange="document.getElementById(\''.$sel_name.'_txt\').value=document.getElementById(\''.$sel_name.'_txt\').value = this.options[this.selectedIndex].text" '.$onclick.'>';
        	if ($var=='')	$result .='<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['activity_id']==$default) ? 'SELECTED' : '' ;

			$result .= ' <option value="'.$row['activity_id'].'" '.$sel.'>'.$row['value'.($lang=='en' ? '_eng' : '')].'</option>';
	}
	$result .= '</select>
	';
	return $result;
}

/*         <option value="<?php echo $row_activities['activity_id']?>"><?php echo $row_activities['value']?></option>

} while ($row_activities = mysql_fetch_assoc($activities));
  $rows = mysql_num_rows($activities);
  if($rows > 0) {
      mysql_data_seek($activities, 0);
	  $row_activities = mysql_fetch_assoc($activities);
  }
*/

function print_charge_name($type_id,$group_id,$charge_id){

	$query = "SELECT fc.charge_id, fc.value FROM coris_finances_charges  fc WHERE fc.charge_id = '$charge_id' AND fc.type_id = '$type_id' AND group_id='$group_id' ";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($mysql_result);
	return $row['value'];

}
/*
function ev_round($liczba,$precyzja=0){
	$mnoznik = pow(10,$precyzja);
	if ($liczba>0){
		$wyn = $liczba*$mnoznik + 0.5;
		return (intval($liczba*$mnoznik + 0.5))/$mnoznik;
	}else{
		return (intval($liczba*$mnoznik - 0.5))/$mnoznik;
	}
}
*/








function getStawkaVat($vatrate_id){
	$qv = "SELECT rate FROM coris_finances_vatrates WHERE vatrate_id='$vatrate_id'";
			$mr=  mysql_query($qv);

			$row_vat =mysql_fetch_array($mr);
			return $row_vat[0]*100;


}

function  getContrahentDetails($contrahent_id){
	$query_contrahent = "SELECT * FROM  coris_contrahents WHERE contrahent_id='$contrahent_id'";
	$contrahent_result = mysql_query($query_contrahent) or die(mysql_error());
	$row_contrahent = mysql_fetch_array($contrahent_result);
	return $row_contrahent;
}

?>