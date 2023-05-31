<?php require_once('include/include.php');
require_once('include/include_mod.php');
require_once('include/pdf_utils.php');
include('lib/lib_case.php');
include('lib/CorisCase.php');
include_once('lib/UserObject.php');
include_once('lib/lib_allianz.php');

  $id = getValue("id");
  
  if ($id>0) {
  
      include('include/template.php');
      $page_template =  new Template();
      
      $query = "SELECT * FROM coris_allianz_decisions  WHERE ID='$id'";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID=$id claims pay not exists!";exit();}
      
      $row = mysql_fetch_array($mysql_result);
      $case_id=$row['ID_case'];
      
      $row_case = CaseInfo::getCaseInfo($case_id);
      $row_claims = new AllianzClaimDetails($row['ID_claims_details']);
      $row_case_ann = AllianzCase::getCaseInfo($case_id);
        
        
      $ubezpieczenie = AllianzCase::ubezpieczenie($row_case_ann['ID_kolo']);
	  $suma_ubezp = $ubezpieczenie['suma_ubezpieczenia'];
	  $franszyza_rodzaj = $ubezpieczenie['franszyza_rodzaj'];
	 $franszyza_wartosc = $ubezpieczenie['franszyza_kwota'];
	 // $franszyza_wartosc = $row_claims->getFranszyza_kwota();
		 
	  $franszyza = false;
      //  if ( $franszyza_rodzaj ==2 && $row_claims->getWyplata_zaakceptowana() <  $row_claims->getKwota_zaakceptowana() ){
	  		if ( $franszyza_rodzaj ==2 && $row_claims->getFranszyza() && $row_claims->getFranszyza_kwota() > 0 ){
				  $franszyza= true;      	
        }
        
        $coris_case = getCaseInfo($case_id);
        $nr_szkody = $coris_case['fullNumber'];
        
        $stempel1 = ''; 
        $stempel2= '';
        $userid = $row_claims->getStatus_userID();
        $userid2 =  $row_claims->getStatus2_userID();
        if ($userid > 0){
        		$cor_user = new UserObject($userid);
        		$stempel1 = $cor_user->getStempel();
        }
        
        if ($userid2 > 0){
        		$cor_user = new UserObject($userid2);
        		$stempel2 = $cor_user->getStempel();
        }
        
        $adres_korespondencyjny = '';
        $kolo = AllianzCase::getKoloInfo($row_case_ann['ID_kolo']);
		if (trim($kolo['adres_do_korespondencji']) != ''){
			$adres_korespondencyjny = '<br><br>Adres korespondencyjny: <br><b>'.nl2br(trim($kolo['adres_do_korespondencji'])).'</b>'	;
		}
        
 if ($row['type'] == 3){ //akceptacja        
      $page_template->load_template('../fax/templates/allianz_decyzja_tak.html');
        
      $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
      
      $page_template->set('<!--KOLO_NAZWA-->' ,  $row_case_ann['kolo_nazwa']);
      $page_template->set('<!--KOLO_ADRES-->' ,  $row_case_ann['kolo_adres']);
      $page_template->set('<!--KOLO_MIASTO-->' ,  $row_case_ann['kolo_kod'].' '.$row_case_ann['kolo_miejscowosc']);
      
      $page_template->set('<!--KOLO_ADRES_KORESPONDENCYJNY-->',$adres_korespondencyjny);
      
      $page_template->set('<!--TEXT_1-->' , nl2br($row['text1']) );
      $page_template->set('<!--TEXT_2-->' , nl2br($row['text2']) );
      
      $page_template->set('<!--KWOTA1-->' , print_currency( $row_claims->getKwota_zaakceptowana() ) );
      $page_template->set('<!--KWOTA2-->' , print_currency($row['amount2']) );
      $page_template->set('<!--SUMA-->' , print_currency($row['amount2'] + $row_claims->getKwota_zaakceptowana() ) );

      $page_template->set('<!--FRANSZYZY_FORM-->' , ($franszyza ? '' : 'display:none') );      
      $page_template->set('<!--KWOTA_FRANSZYZY-->' , print_currency($franszyza_wartosc )) ;      
      $page_template->set('<!--SUMA_PO-->' , print_currency($row['payment_amount']) );
      
      $page_template->set('<!--KONTO_NAZWA-->' , ' ' );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['kolo_konto'] );
      $page_template->set('<!--KONTO_KWOTA-->' , print_currency($row['payment_amount']) );
      
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );
      //$page_template->set('<!--ORGINAL-->' , 'KOPIA' );
            
        
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      $body .=  $page_template->realize();
 		$body .= '<DIV style="page-break-after:always"></DIV>';

       $page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      $body .=  $page_template->realize();
      	
  		 
       
          
  }else if ($row['type'] == 4){ //odmowa
      $page_template->load_template('../fax/templates/allianz_decyzja_nie.html');
      
      
      $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody);
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
      
      $page_template->set('<!--KOLO_NAZWA-->' ,  $row_case_ann['kolo_nazwa']);
      $page_template->set('<!--KOLO_ADRES-->' ,  $row_case_ann['kolo_adres']);
      $page_template->set('<!--KOLO_MIASTO-->' ,  $row_case_ann['kolo_kod'].' '.$row_case_ann['kolo_miejscowosc']);
      $page_template->set('<!--KOLO_ADRES_KORESPONDENCYJNY-->',$adres_korespondencyjny);
      
      $page_template->set('<!--TEXT_1-->' , nl2br($row['text1']) );
      $page_template->set('<!--TEXT_2-->' , nl2br($row['text2']) );
      
      $page_template->set('<!--KWOTA1-->' ,print_currency( $row_claims->getWyplata_zaakceptowana() ) );
      $page_template->set('<!--KWOTA2-->' , print_currency($row['amount2']) );
      $page_template->set('<!--SUMA-->' , print_currency($row['amount2'] + $row_claims->getWyplata_zaakceptowana() ) ) ;

      
      $page_template->set('<!--KONTO_NAZWA-->' , ' ' );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['kolo_konto'] );
      $page_template->set('<!--KONTO_KWOTA-->' , print_currency($row['payment_amount']) );
            
       $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      //$page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );
       
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      $body .=  $page_template->realize();
 		$body .= '<DIV style="page-break-after:always"></DIV>';

       $page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      $body .=  $page_template->realize();
      
        
  }        
  
 
  
 

  		$tmp = file("../fax/templates/allianz_decyzja_header.html");
  		$header = implode('', $tmp);
  		$tmp = file("../fax/templates/allianz_decyzja_footer.html");
  		$footer = implode('', $tmp);
  		 $body = $header.$body.$footer;
 
  
   if ($body <> null){
     /*$file = html2pdf($body);                
        if (file_exists($file) && filesize($file) > 0 ){                           
              sendPDF2Browser($file);
                    unlink($file);
                    exit ();
              
         }else
              echo " ERROR file pdf ".$file ;         
       */      
      echo $body;
        }else
            echo " ERROR template";        
   
}else
  echo " ERROR request";

exit;





?>
