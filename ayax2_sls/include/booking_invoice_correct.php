<?php
require_once('include/booking_nsimple.php'); 



function booking_in_correct($correct_inv_in_id){
	
	
	$query = "SELECT coris_finances_invoices_in_correct.*,cc.country_id,cc.short_name FROM coris_finances_invoices_in_correct,coris_contrahents As cc  WHERE coris_finances_invoices_in_correct.ID='$correct_inv_in_id' AND coris_finances_invoices_in_correct.booking=0 AND  cc.contrahent_id = coris_finances_invoices_in_correct.contrahent_id " ;
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)==0) {echo "ERROR: ".$query ; return; };
	$row_invoice_in_correct = mysql_fetch_array($mysql_result);

	
	$id_invoice_in = $row_invoice_in_correct['invoice_in_id'];
	$query = "SELECT * FROM coris_finances_invoices_in  WHERE invoice_in_id='$id_invoice_in'";	
	$mysql_result = mysql_query($query);
	$row_invoice_in = mysql_fetch_array($mysql_result);
	
	
	$cel_id = $row_invoice_in['cel_id'];
	$rodzaj =  'FKZ';   
	$bookingtype_id = $row_invoice_in['bookingtype_id']; //dekrat taki jak korygowanej faktury
	$booking_user_id = $_SESSION['user_id'];
	$id_invoice_in = $correct_inv_in_id;
	
	
$currency_id=$row_invoice_in_correct['currency_id'];
$table_id=$row_invoice_in_correct['table_id'];

$multiplier=1;
$rate=1;

//POBRANIE kursu waluty do ewidencji w dekrecie
if ($currency_id != '' && $currency_id != 'PLN'){  
	$query = "SELECT * FROM coris_finances_currencies_tables_rates  WHERE table_id ='$table_id' AND currency_id ='$currency_id'";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result) == 0){
		echo "<script> alert('".GEN_BOOKIFR_MSG_BKURSWAL.": $currency_id table_id=$table_id')</script>";
			exit();
	}
	$row = mysql_fetch_array($mysql_result);
	$multiplier = $row['multiplier'];
	$rate = $row['rate'];
}
	$kz= ($currency_id=='PLN') ? 1 :2 ;			
     
	$booking_date = date("Y-m-d");       
    $booking_no = booking($row_invoice_in_correct,$row_invoice_in,$rodzaj,$bookingtype_id,$currency_id,$table_id,$multiplier,$rate);
   if ( $booking_no ){	   
	 
     $query_booking = "UPDATE coris_finances_invoices_in_correct SET booking=1,booking_no='$booking_no',booking_date='$booking_date',booking_user_id='$booking_user_id',bookingtype_id='$bookingtype_id',cel_id='$cel_id' WHERE ID='$id_invoice_in' LIMIT 1";
	 $res = mysql_query($query_booking);
   }else{
   			echo "<script> alert('".GEN_BOOKIFR_MSG_BDEKR."')</script>";
			exit();
   }
}


function booking_out_correct($correct_inv_out_id){


	$query = "SELECT coris_finances_invoices_out_correct.*,cc.country_id,cc.short_name FROM coris_finances_invoices_out_correct,coris_contrahents As cc  WHERE coris_finances_invoices_out_correct.ID='$correct_inv_out_id' AND coris_finances_invoices_out_correct.booking=0 AND  cc.contrahent_id = coris_finances_invoices_out_correct.contrahent_id " ;
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)==0) {echo "ERROR: ".$query ; return; };
	$row_invoice_out_correct = mysql_fetch_array($mysql_result);

	
	$id_invoice_put = $row_invoice_out_correct['invoice_out_id'];
	$query = "SELECT * FROM coris_finances_invoices_out  WHERE invoice_out_id='$id_invoice_put'";	
	$mysql_result = mysql_query($query);
	$row_invoice_out = mysql_fetch_array($mysql_result);
	
	
	$bookingtype_id = $row_invoice_out['bookingtype_id']; //dekrat taki jak korygowanej faktury
	$booking_user_id = $_SESSION['user_id'];
	
	
$currency_id=$row_invoice_out_correct['currency_id'];
$table_id=$row_invoice_out_correct['table_id'];

$multiplier=1;
$rate=1;

//POBRANIE kursu waluty do ewidencji w dekrecie
if ($currency_id != '' && $currency_id != 'PLN'){  
	$query = "SELECT * FROM coris_finances_currencies_tables_rates  WHERE table_id ='$table_id' AND currency_id ='$currency_id'";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result) == 0){
		echo "<script> alert('".GEN_BOOKIFR_MSG_BKURSWAL.": $currency_id table_id=$table_id')</script>";
			exit();
	}
	$row = mysql_fetch_array($mysql_result);
	$multiplier = $row['multiplier'];
	$rate = $row['rate'];
}
	$kz= ($currency_id=='PLN') ? 1 :2 ;			

	
     $booking_date = date("Y-m-d");
     $booking_no = booking($row_invoice_out_correct,$row_invoice_out,'FKS',$bookingtype_id,$currency_id,$table_id,$multiplier,$rate);
  	if ( $booking_no ){	 
  	
  		
     	$booking_user_id = $_SESSION['user_id'];
     	$query_booking = "UPDATE coris_finances_invoices_out_correct SET booking=1,booking_no='$booking_no',booking_date='$booking_date',booking_user_id='$booking_user_id',bookingtype_id='$bookingtype_id' WHERE ID='$correct_inv_out_id' LIMIT 1";
		$mr = mysql_query($query_booking);
	
	
  }else{
  	echo "<script> alert('".GEN_BOOKIFR_MSG_BDEKR."')</script>";
			exit();
  }		


}


function booking($row,$row_src,$rodzaj,$bookingtype_id,$currency_id,$table_id,$multiplier,$rate){
	$BOOKING_SIMPLE = new BookingSimpleN();
	
	$inv_id = 0;
	if ($rodzaj == 'FKZ')	{
			$inv_id = $row['ID'];
	
	}else if ($rodzaj=='FKS'){
		$inv_id = $row['ID'];
	
	}else 
		return false;
	
	$contrahent_id = $row['contrahent_id'];
	
	 $year = date('Y');	
	 $query = "INSERT INTO `coris_finances_bookings` ( `ID` , `ID_invoices` ,`ID_invoice_correct`, `year` , `symbol` , `no`,currency_id,table_id,multiplier,rate,date ) 
	 SELECT NULL , 0 ,'$inv_id', '$year', '$rodzaj',IF( MAX( no ) >0, MAX( no ) +1, 1),'$currency_id','$table_id','$multiplier','$rate',now() FROM `coris_finances_bookings` WHERE year='$year' AND symbol='$rodzaj'" ;
  	$mysql_result =  mysql_query($query);
  if ($mysql_result){	
  	 $id = mysql_insert_id();

  	 $query = "SELECT symbol,no FROM coris_finances_bookings WHERE ID = '$id'";
  	 $mysql_result2 = mysql_query($query);
  	 $row2 = mysql_fetch_array($mysql_result2);
  	 $symdow=$row2[0];
  	 $numdow=$row2[1];
  	 
  	 $booking_nubmer = $row2[0].'-'.$row2[1];
  	 
  	  $qw = "SELECT number,publication_date,source_id FROM coris_finances_currencies_tables  WHERE table_id='$table_id'";
	  $mrw = mysql_query($qw);
	  $rw = mysql_fetch_array($mrw);
	  $table_cur_name = $rw['number'];
	  $table_source = $rw['source_id'];
	  $date_cur = $rw['publication_date'];

	  $data_dow = date("Y-m-d");   
  	 $symbol = $symdow;
  	 $no = $numdow;
  	  if ($rodzaj=='FKS'){
  	  	  // $book_res = booking_assis_out($row,$symdow,$numdow,$bookingtype_id,$rate,$multiplier);
  	  	   
  	  	   $book_res = $BOOKING_SIMPLE->booking_assis_out_correct($data_dow,$row,$row_src,$symbol,$no,$bookingtype_id,$rate,$multiplier,$currency_id,$table_cur_name,$date_cur,$table_source);
  	  	   
  	  }else  if ($rodzaj=='FKZ'){
  	  	 //  $book_res = booking_assis_in($row,$symdow,$numdow,$bookingtype_id,$rate,$multiplier);
  	  	   $book_res = $BOOKING_SIMPLE->booking_assis_in_correct($data_dow,$row,$row_src,$symbol,$no,$bookingtype_id,$rate,$multiplier,$currency_id,$table_cur_name,$date_cur,$table_source);
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