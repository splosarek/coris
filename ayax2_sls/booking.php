<?php
session_start();

require_once('./include/cn.php');
require_once('./include/include.php');
//require_once(dirname(__FILE__).'/include/booking.php');
require_once('./include/booking_nsimple.php');


//id_invoice_out=508&items_booking_type[0][invoice_item_id]=539&items_booking_type[0][booking_type_id]=11

$id_invoice_in = 0;
$id_invoice_out = 0;
$booking_user_id = 0;
//$id_invoice_out = intval(getValue('id_invoice_debitnote'));
$id_note_out = getValue('id_invoice_debitnote');

if (isset($_POST['id_invoice_out'])   ) {
  $id_invoice_out= $_POST['id_invoice_out'] ;

  
}else if (isset($_POST['id_invoice_in'])  ) {
  $id_invoice_in= $_POST['id_invoice_in'] ;


}else if ($id_note_out>0){

}else {

	exit('error');
}

$currency_id=getValue('bookingCurrencyCur');
$table_id=getValue('table_id');
$multiplier=getValue('bookingCurrencyMultipler');
$rate= str_replace(',','.',getValue('bookingCurrencyRate') );
$table_date=getValue('bookingCurrnecyDate');
$table_no=getValue('bookingCurrencyTable');

if ($currency_id == 'PLN'){
	$rate=1;
	$multiplier=1;
		$table_date="2000-01-01";
	$table_no="";

}

$client_charge=getValue('client_charge');

$items_booking_type = @$_POST['items_booking_type'];

/*$multiplier=1;
$rate=1;*/
//id_invoice_out=5427&items_booking_type[0][invoice_item_id]=5976&items_booking_type[0][booking_type_id]=11&
//bookingCurrencyRate=1&
//bookingCurrnecyDate=2016-03-08
//&bookingCurrencyCur=EUR
//&bookingCurrencyTable=sdfsdf
/*POBRANIE kursu waluty do ewidencji w dekrecie*/





if ($id_note_out>0){

	$invoice_data = getNoteOutData($id_note_out);
	if ($invoice_data == null ) exit();

	$bookingtype_id = getValue('bookingtype_id');

	//items_booking_type
	//sls_finance_debit_note


	if ($invoice_data['booking'] == 1 )exit('{"status": "error","message": "Dokument zadekretowany"}');
	$currency_id = $invoice_data['currency_id'];
    $booking_date = date("Y-m-d");

	$table_id=0;




	//	$query_poz = "UPDATE sls_finance_debit_note  SET booking_type_id ='$bookingtype_id' WHERE id= '$id_note_out '";
	//	$res = mysql_query($query_poz);


	$booking_no = false;

      $booking_no = booking($invoice_data,'07',$bookingtype_id,$currency_id,$table_id,$multiplier,$rate,$table_date,$table_no,$bookingtype_id);
  if ( $booking_no ){

	  $booking_user_id = $_SESSION['USER_ID'];
	  $query_booking = "UPDATE sls_finance_debit_note  SET booking=1,booking_no='$booking_no',booking_date='$booking_date',booking_user_id='$booking_user_id',booking_type_id='$bookingtype_id' WHERE id='$id_note_out' LIMIT 1";
	  mysql_query($query_booking);


	  echo  '{"status": "OK"}';
	  exit();

  }else{
	  echo  '{"status": "error","message": "Blad dekretacji"}';
	  exit();
  }

}else if ($id_invoice_out>0){

	$invoice_data = getInvoiceOutData($id_invoice_out);
	$invoice_tems_data = getInvoiceOutItemsData($id_invoice_out);
	if ($invoice_data == null ) exit();

	if ($invoice_data['booking'] == 1 )exit('{"status": "error","message": "Dokument zadekretowany"}');
	$currency_id = $invoice_data['currency_id'];
    $booking_date = date("Y-m-d");

	$table_id=0;

    $dekret_prefix = '32';

	foreach($items_booking_type As $item){
		$invoice_item_id = $item['invoice_item_id'];
		$booking_type_id = $item['booking_type_id'];

		$query_poz = "UPDATE sls_finance_invoice_out_items  SET booking_type_id ='$booking_type_id' WHERE id= '$invoice_item_id '";
		$res = mysql_query($query_poz);
    }
	$booking_no = false;
	$bookingtype_id=0;
    $booking_no = booking($invoice_data,$dekret_prefix,$bookingtype_id,$currency_id,$table_id,$multiplier,$rate,$table_date,$table_no,$items_booking_type);

   if ( $booking_no ){
     	$booking_user_id = $_SESSION['USER_ID'];
     	$query_booking = "UPDATE sls_finance_invoice_out SET booking=1,booking_no='$booking_no',booking_date='$booking_date',booking_user_id='$booking_user_id',booking_type_id='$bookingtype_id' WHERE id='$id_invoice_out' LIMIT 1";
		mysql_query($query_booking);
	    echo  '{"status": "OK"}';
	    exit();
  }else{
	   echo  '{"status": "error","message": "Blad dekretacji"}';
       exit();
  }	
}else if ($id_invoice_in>0){
	$invoice_data = getInvoiceInData($id_invoice_in);
	$invoice_tems_data = getInvoiceInItemsData($id_invoice_in);
	if ($invoice_data == null ) exit('error invoice in data');

	if ($invoice_data['booking'] == 1 )exit('{status: "error",message: "Dokument zadekretowany"}');
	$currency_id = $invoice_data['currency_id'];
	$booking_date = date("Y-m-d");

	$table_id=0;
	//$dekret_prefix = '02';
	$dekret_prefix_list = array();
	foreach($items_booking_type As $item){
		$invoice_item_id = $item['invoice_item_id'];
		$booking_type_id = $item['booking_type_id'];

		$query_poz = "UPDATE sls_finance_invoice_in_items  SET booking_type_id ='$booking_type_id' WHERE id= '$invoice_item_id '";
		$res = mysql_query($query_poz);

		$q = "SELECT amount_coris,amount_client FROM sls_finance_invoice_in_items  WHERE id= '$invoice_item_id ' ";
		$mr = mysql_query($q);
		$r = mysql_fetch_array($mr);

        if ($booking_type_id == 20 || $booking_type_id == 21 ){
            $dekret_prefix_list[] = '13';
        }else {
            if ($r['amount_coris'] > 0.0 && $currency_id == 'PLN') {
                $dekret_prefix_list[] = '12';
            }
            if (($r['amount_coris'] > 0.0 && $currency_id != 'PLN') || $r['amount_client'] > 0.0) {
                $dekret_prefix_list[] = '02';
            }
        }

	}

	$dekret_prefix_list = array_unique($dekret_prefix_list);
	$booking_no = false;
	$bookingtype_id=0;


	$booking_no_list = array();

	foreach ( $dekret_prefix_list As $dekret_prefix ) {
		$booking_no = booking($invoice_data, $dekret_prefix, $bookingtype_id, $currency_id, $table_id, $multiplier, $rate, $table_date, $table_no, $items_booking_type);
		if ( $booking_no ){
			$booking_no_list[] = $booking_no;
		}else{

			echo  '{"status": "error","message": "Blad dekretacji"}';
			exit();
		}
	}
	//if ( $booking_no ){

		$booking_user_id = $_SESSION['USER_ID'];
		$query_booking = "UPDATE sls_finance_invoice_in SET booking=1,booking_no='".implode(';',$booking_no_list)."',booking_date='$booking_date',booking_user_id='$booking_user_id' WHERE id='$id_invoice_in' LIMIT 1";
		mysql_query($query_booking);

		echo  '{"status": "OK"}';
		exit();

/*	}else{
		echo  '{"status": "error","message": "Blad dekretacji"}';
		exit();
	}*/
}


echo  '{"status": "error","message": "Blad dekretacji (2)"}';

function booking($row,$rodzaj,$bookingtype_id,$currency_id,$table_id,$multiplier,$rate,$table_date,$table_no,$items_booking_type){
	
	$BOOKING_SIMPLE = new BookingSimpleN();
	///check w simple
	$inv_id = 0;
	if ($rodzaj == '02' || $rodzaj == '12' || $rodzaj == '13' )	{
			$inv_id = $row['id'];
	
	}else if ($rodzaj=='32'){
		$inv_id = $row['id'];
	
	}else if ($rodzaj=='07'){//nota
		$inv_id = $row['id'];
	
	}else if ($rodzaj=='ZA'){
		$inv_id = $row['id'];
	
	}else 
		return false;
	

	
	/*SPRAWDZENIE CZY contrahnet przeslany do simple*/
/* 	if (!check_contrahent_simple($contrahent_id)){
 		 	echo "<script> alert('Brak contrahenta w simple')</script>";
 		 	return false;
 	}
	*/
	 $year = date('Y');	
	 $query = "INSERT INTO `sls_finances_bookings` ( `ID` , `ID_invoices` , `year` , `symbol` , `no`,currency_id,table_id,multiplier,rate,`date`,`number`,`table_date` )
	 SELECT NULL , '$inv_id', '$year', '$rodzaj',IF( MAX( no ) >0, MAX( no ) +1, 1),'$currency_id','$table_id','$multiplier','$rate',now(),'$table_no','$table_date' FROM `sls_finances_bookings` WHERE year='$year' AND symbol='$rodzaj'" ;


  	$mysql_result =  mysql_query($query);
  if ($mysql_result){	
  	 $id = mysql_insert_id();

  	 $query = "SELECT symbol,no FROM sls_finances_bookings WHERE ID = '$id'";
  	 $mysql_result2 = mysql_query($query);
  	 $row2 = mysql_fetch_array($mysql_result2);
  	 $symdow=$row2[0];
  	 $numdow=$row2[1];
  	 
  	 $booking_nubmer = $row2[0].'-'.$row2[1];
  	 

	  $table_cur_name = $table_no;
	  $table_source = 1;
	  $date_cur = $table_date;

	  $data_dow = date("Y-m-d");   
  	 $symbol = $symdow;
  	 $no = $numdow;
	  $book_res= false;
  if ($rodzaj=='32' ){ //ZKF
	  $book_res = $BOOKING_SIMPLE->booking_sls_out($data_dow,$row,$symbol,$no,$bookingtype_id,$rate,$multiplier,$currency_id,$table_cur_name,$date_cur,$table_source,$items_booking_type);
  }if ($rodzaj=='07'){ // nota
  	  	   //$book_res = booking_assis_note_out($row,$symdow,$numdow,$bookingtype_id,$rate,$multiplier);
  	  	   $book_res = $BOOKING_SIMPLE->booking_sls_note_out($data_dow,$row,$symbol,$no,$bookingtype_id,$rate,$multiplier,$currency_id,$table_cur_name,$date_cur,$table_source);
  	  	   //$book_res=true;
  }else  if ($rodzaj=='02' || $rodzaj=='12' || $rodzaj=='13' ){
  	  	   $book_res = $BOOKING_SIMPLE->booking_sls_in($data_dow,$row,$symbol,$no,$bookingtype_id,$rate,$multiplier,$currency_id,$table_cur_name,$date_cur,$table_source,$items_booking_type);
  }
  	  
  	  if (!$book_res){
  	  		$query = "DELETE FROM coris_finances_bookings WHERE ID='$id' LIMIT 1";
  	  		$mysql_result - mysql_query($query);
  	  		return  false;
  	  		
  	  }
  	  		
	 return $booking_nubmer;
  }
  return false;
}



?>