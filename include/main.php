<?php


function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}


function currency_invoice_out($param=""){
	
	$query_currencies = "SELECT coris_finances_currencies.currency_id FROM coris_finances_currencies WHERE coris_finances_currencies.active = 1 AND coris_finances_currencies.invoice_out=1 ORDER BY coris_finances_currencies.currency_id";
	$mysql_result = mysql_query($query_currencies);
	$result = '<select name="currency_out_id" class="required" id="currency_out_id" '.$param.'>';
    while ($row = mysql_fetch_array($mysql_result)){
       		$result .= '<option value="'.$row['currency_id'].'">'.$row['currency_id'].'</option>';
    }
	$result .= '</select>';
	mysql_free_result($mysql_result);
	return $result;
} 


function is_email2($string){
    $string = trim($string);
    $ret = ereg(
                '^([A-Za-z0-9_]|\\-|\\.)+'.
                '@'.
                '(([A_Za-z0-9_]|\\-)+\\.)+'.
                '[A-Za-z]{2,10}$',
                $string);
    return($ret);
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
	return $row[1].' '.$row[0];
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

function print_booking_types($name,$default,$class,$onclick='',$grupa){
	
	$query_bookingtypes = "SELECT bookingtype_id, value FROM coris_finances_bookings_bookingtypes WHERE symbol = '$grupa' AND active = 1 ORDER BY value";
	$mysql_result = mysql_query($query_bookingtypes) or die(mysql_error());

	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
        		<option value="0" ></option>';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['bookingtype_id']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['bookingtype_id'].'" '.$sel.'>'.$row['bookingtype_id'].' - '.$row['value'].'</option>';
	}
	$result .= '</select>';
	return $result;
}


function print_vatrates($name,$default,$class,$onclick=''){
	
	$query = "SELECT * FROM coris_finances_vatrates ORDER BY rate";
	$mysql_result = mysql_query($query) or die(mysql_error());
	$rate= array();
	$result = '<select name="'.$name.'" class="'.$class.'" id="'.$name.'" '.$onclick.'>
        		';
	while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['vatrate_id']==$default) ? 'SELECTED' : '' ;
			$result .= ' <option value="'.$row['vatrate_id'].'" '.$sel.'>'.$row['value'].'</option>';
			
			$rate[] = $row['rate'];
	}
	$result .= '</select>';
	return $result.'<script>vat_array = new Array('.implode(',',$rate).')</script>';
}

function getNumberCase($cid){
	$query = "SELECT number,  year  FROM coris_assistance_cases  WHERE case_id='$cid'";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)==0)
		return null;
	else{
		$row=mysql_fetch_array($mysql_result);
		return array('year' => $row['year'], 'number' => $row['number']);
	}	
		
}
 
function getChargeName($id){

	if ($id==0) return '';
	$query  = "SELECT value FROM coris_finances_charges WHERE charge_id ='$id'";
	$mysql_result = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($mysql_result)==0)
		return '';
	else{
		$row=mysql_fetch_array($mysql_result);
		return $row[0];
	}
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


/*function ev_round($liczba,$precyzja=0){
	$mnoznik = pow(10,$precyzja);
	if ($liczba>0){
		//$res = gmp_int(gmp_init($liczba*$mnoznik + 0.5));		
		//return $res/$mnoznik;
		return (sprintf('%f.0',($liczba*$mnoznik + 0.5))/$mnoznik);
		//return (intval($liczba*$mnoznik + 0.5))/$mnoznik;
	}else{
		$res = gmp_int(gmp_init($liczba*$mnoznik - 0.5));		
		return $res/$mnoznik;
		//return (intval($liczba*$mnoznik - 0.5))/$mnoznik;	
	}
}
*/

function ev_round($liczba,$precyzja=0){
	$mnoznik = pow(10,$precyzja);
	if ($liczba>0){
		$wyn = $liczba*$mnoznik + 0.5;	
		return (ev_intval($liczba*$mnoznik + 0.5))/$mnoznik;	
	}else{
		return (ev_intval($liczba*$mnoznik - 0.5))/$mnoznik;	
	}
}


function ev_intval($liczba){
	$liczba_tmp = (String) $liczba;
	$poz = strpos($liczba_tmp,'.');
	if ($poz === false ){
		return $liczba;
	}else{
		return substr($liczba_tmp,0,$poz);
	}
}

function getHttpValue($name,$value = null){

	if (isset($_POST[$name]) )
			$value =   addslashes(stripslashes(trim($_POST[$name]))) ;
	else if (isset($_GET[$name]) )
			$value =   addslashes(stripslashes(trim($_GET[$name]))) ;
	return $value;
}
/*
function print_currency($val,$prec=2,$sep=''){
	if (is_numeric($val)){   		
		return number_format($val, $prec, ',', $sep);		
	}else{
		$val = str_replace(',','.',$val);				
		return number_format($val, $prec, ',', $sep);		
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


function getContrahnetParam($id,$param){
	$query = "SELECT $param FROM coris_contrahents WHERE contrahent_id='$id'  LIMIT 1";	
	$mysql_result = mysql_query($query) or die(mysql_error());
	$row=mysql_fetch_array($mysql_result);
	return $row[0];
}

/*
function  getValue($zm){
  if (isset($_POST[$zm]))
     return isset($_POST[$zm]) ?  addslashes(stripslashes(trim($_POST[$zm]))) : '';
   else      
     return isset($_GET[$zm]) ?  addslashes(stripslashes(trim($_GET[$zm]))) : '';  
}*/

function  getValue($zm){
  if (isset($_POST[$zm])){
     if (is_array($_POST[$zm]))
       return $_POST[$zm];
     else
       return isset($_POST[$zm]) ?  addslashes(stripslashes(trim($_POST[$zm]))) : '';
  }else{      
     if (isset($_GET[$zm]) && is_array($_GET[$zm]))     
       return $_GET[$zm];
     else
       return isset($_GET[$zm]) ?  addslashes(stripslashes(trim($_GET[$zm]))) : '';  
  }
}


function getCaseInfo($case_id){
	$query = "SELECT coris_contrahents.contrahent_id ,coris_contrahents.name,coris_contrahents.o_klnagsim As shortName,coris_assistance_cases.paxname,coris_assistance_cases.paxname,
					coris_assistance_cases.paxsurname,coris_assistance_cases.type_id,coris_assistance_cases.number,coris_assistance_cases.year,coris_assistance_cases.client_ref, 
					concat(number,'/',substring(year,3,2),'/',type_id,'/',client_id) As fullNumber , marka_model,nr_rej 
	
	FROM coris_assistance_cases,coris_contrahents  
	WHERE coris_assistance_cases.case_id='$case_id' AND coris_contrahents.contrahent_id=coris_assistance_cases.client_id LIMIT 1" ;
	$mysql_result = mysql_query($query) OR die (mysql_error());
	
	if (mysql_num_rows($mysql_result)==0) return null;
	$row= mysql_fetch_array($mysql_result);
	return $row;
}

function search_case($nr){
	
			$query_cases = "SELECT case_id  FROM coris_assistance_cases WHERE ";

			$case_number = explode("/",$nr);
			if (count($case_number)>1)
				$query_cases .= " number = '$case_number[0]' AND year = '$case_number[1]' ";		
			else 
				$query_cases .= " number = '$case_number[0]' ";
					
	
				$mysql_result = mysql_query($query_cases);
				$result = array();
				while ($row = mysql_fetch_array($mysql_result)){
					$result[] = $row[0];
					
				}
				
				return implode(',',$result);
}

function calendar(){
	return '
	<script>
	// Kalendarz
		function y2k(number)    { return (number < 1000) ? number + 1900 : number; }
		var today;
		var day;
		var month;
		var year
		function newWindowCal(name) {

			today = new Date();
			day   = today.getDate();
			month = today.getMonth();
			year  = y2k(today.getFullYear());

			var width = 260;
			var height = 200;
			var left = (screen.availWidth - width) / 2;
			var top = ((screen.availHeight - 0.2 * screen.availHeight) - height) / 2;
			mywindow = window.open(\'calendar.php?name=\'+ name,\'\',\'resizable=yes,width=\'+ width +\',height=\'+ height +\',left=\'+ left +\',top=\'+ top);
		}		
	</script>';
	
}

function set_case_reclamation($interaction_id){
		$query = "UPDATE coris_assistance_cases,coris_assistance_cases_interactions SET  coris_assistance_cases.reclamation=1 WHERE coris_assistance_cases_interactions.interaction_id = '$interaction_id' AND coris_assistance_cases.case_id = coris_assistance_cases_interactions.case_id ";		
		$mysql_result = mysql_query($query);
		if (!$mysql_result) echo "\n<br>Error q: ".$query."\n<br>".mysql_error();		
}


function SprawdzNumerNRB($p_iNRB){
  // Usuniecie spacji
  $iNRB = str_replace(' ', '', $p_iNRB);
  // Usuniecie -
  $iNRB = str_replace('-', '', $p_iNRB);
  
  
  // Sprawdzenie czy przekazany numer zawiera 26 znaków
  if(strlen($iNRB) != 26)
    return false;
 
  // Zdefiniowanie tablicy z wagami poszczególnych cyfr				
  $aWagiCyfr = array(1, 10, 3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53, 45, 62, 38, 89, 17, 73, 51, 25, 56, 75, 71, 31, 19, 93, 57);
 
  // Dodanie kodu kraju (w tym przypadku dodajemy kod PL)		
  $iNRB = $iNRB.'2521';
  $iNRB = substr($iNRB, 2).substr($iNRB, 0, 2); 
 
  // Wyzerowanie zmiennej
  $iSumaCyfr = 0;
 
  // Pætla obliczaj±ca sumæ cyfr w numerze konta
  for($i = 0; $i < 30; $i++) 
    $iSumaCyfr += $iNRB[29-$i] * $aWagiCyfr[$i];
 
  // Sprawdzenie czy modulo z sumy wag poszczegolnych cyfr jest rowne 1
  return ($iSumaCyfr % 97 == 1);
}



function print_user_expertnes($name,$default){

	$query_currencies = "SELECT * FROM coris_users_expertness ORDER BY sort ";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" id="'.$name.'" >
          <option value="0"></option>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['name'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}


function print_user_position($name, $default){

	$query_currencies = "SELECT * FROM coris_users_position ORDER BY sort ";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" id="'.$name.'" >
          <option value="0"></option>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['name_pl'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}


function print_user_coris_branchCase($name, $default, $extra=""){

	$query_currencies = "SELECT * FROM coris_branch ORDER BY sort ";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" id="'.$name.'" ' . $extra . '>
          <option value=""></option>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['name'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}

function print_user_coris_branch($name, $default, $extra=""){

	$query_currencies = "SELECT * FROM coris_branch WHERE user_access=1 ORDER BY sort ";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" id="'.$name.'" ' . $extra . '>
          <option value=""></option>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['name'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}





function print_user_coris_branch2($name, $default, $extra=""){

	$query_currencies = "SELECT * FROM coris_branch ORDER BY sort ";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" id="'.$name.'" ' . $extra . '>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['name'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}

function print_user_coris_branch_de($name, $default, $extra=""){

	$query_currencies = "SELECT * FROM coris_branch WHERE ID=2 OR ID=3 ORDER BY sort ";
	$mysql_result = mysql_query($query_currencies) or die(mysql_error());

		$result = '<select name="'.$name.'" id="'.$name.'" ' . $extra . '>';
		while ($row = mysql_fetch_array($mysql_result)){
			$sel = ($row['ID']==$default) ? 'SELECTED' : '' ;
          	$result .= '<option value="'.$row['ID'].'" '.$sel.'>'.$row['name'].'</option>';
		}
  		$result .= '</select>';
  		return $result;
}

function getCountryName($country_id, $lang='pl'){
	$query = "SELECT * FROM coris_countries WHERE country_id='$country_id' ";
	$mysql_result = mysql_query($query);
	$row = mysql_fetch_array($mysql_result);
	if ($lang=='pl')
		return $row['name'];
	else
		return $row['name'].'-'.$row['name_eng'];
}
