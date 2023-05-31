<?php  require_once('include/include.php'); 


$contrahent_id =  addslashes(stripslashes(trim($_GET['contrahent_id'])));
$invoice_id =  addslashes(stripslashes(trim($_GET['invoice_id'])));
 
$query_contrahent = "SELECT * FROM  coris_contrahents WHERE contrahent_id='$contrahent_id'";
$contrahent_result = mysql_query($query_contrahent) or die(mysql_error());
$row_contrahent = mysql_fetch_array($contrahent_result);


$kraj = getCountryName($row_contrahent['country_id'],'pl');


if ($invoice_id>0){
	$query_acount = "SELECT coris_contrahents_accounts.* FROM coris_contrahents_accounts ,coris_finances_invoices_in  WHERE coris_finances_invoices_in.invoice_in_id = '$invoice_id' AND coris_contrahents_accounts .account_id = coris_finances_invoices_in.account_id AND coris_contrahents_accounts.contrahent_id='$contrahent_id'";
    //  echo $query_acount;  
	$mysql_result_account = mysql_query($query_acount);
	$row_account = mysql_fetch_array($mysql_result_account);
	$bank = $row_account['name'];
	$konto_kod = $row_account['post'];
	$konto_miasto = $row_account['city'];
	$konto_ulica = $row_account['address'];
	$konto_kraj  = 	$row_account['country_id'];	
	$konto =  $row_account['account'];
	 $swift = $row_account['swift'];        
}

$inv_out = '';


 
 $tmpl = load_template('../fax/templates/dane_kontrahenta.html');
  if ($tmpl <> null){
    
  	$lista_zm=array('%%CONTRAHENTID%%' => $contrahent_id,
  					'%%SHORTNAME%%'=>$row_contrahent['short_name'],
  					'%%NAME%%'=>$row_contrahent['name'],
  					'%%KOD%%'=>$row_contrahent['post'],
  					'%%MIASTO%%' => $row_contrahent['city'],
  					'%%ULICA%%' => $row_contrahent['address'], 
  					'%%KRAJ%%' => $kraj,  '%%KONTAKT%%' => '',
  					'%%NIP%%' => $row_contrahent['nip'],'%%REGON%%'  =>  $row_contrahent['regon'],'%%TELEFON%%' =>  $row_contrahent['phone1'],
  					'%%FAX%%'  => $row_contrahent['fax1'], '%%EMAIL%%' =>  $row_contrahent['email'],
  					'%%BANK%%' => $bank, '%%SWIFT%%' => $swift, '%%KONTO_MIASTO%%' => $konto_miasto,'%%KONTO_KOD%%' => $konto_kod,
  					'%%KONTO_ULICA%%' => $konto_ulica,'%%KONTO_KRAJ%%' => $konto_kraj,
  					'%%KONTO%%' => $konto
  					);
  	$inv_out = strtr($tmpl,$lista_zm);
 
echo $inv_out;
  }
exit;    

function load_template($file){

   if (!file_exists($file)) return null;

  $fcontents = file ($file);
  $plik = implode('',$fcontents);
  return $plik;
}

function getCountryName($country_id,$lang='pl'){
	$query = "SELECT * FROM coris_countries WHERE country_id='$country_id' ";
	$mysql_result = mysql_query($query);
	$row = mysql_fetch_array($mysql_result);
	if ($lang=='pl')
		return $row['name'];
	else 	
		return $row['name'].'-'.$row['name_eng'];	
}
?>