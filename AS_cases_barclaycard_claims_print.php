<?php
require_once('include/include_ayax.php');
require_once('include/pdf_utils.php'); 
//require_once('include/include_ayax.php'); 
//require_once('include/include_mod.php');
//require_once('include/pdf_utils.php');
include('lib/lib_case.php');
//include('lib/CorisCase.php');
//include_once('lib/UserObject.php');
include_once('lib/lib_barclaycard.php');

  $id = getValue("id");
  
  if ($id>0) {
  
     
      $page_template =  new Template();
      
      $query = "SELECT * FROM coris_barclaycard_decisions  WHERE ID='$id'";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID=$id claims pay not exists!";exit();}
      
      $row = mysql_fetch_array($mysql_result);
      $case_id=$row['ID_case'];
      
      $barclaycard_case = new BarclaycardCase($case_id);
      $row_case_ann = $barclaycard_case->getCaseAnnounce($case_id);
        
        $row_case = CaseInfo::getFullCaseInfo($case_id);
         
        $coris_case = CaseInfo::getCaseInfo($case_id);
        $nr_szkody = $coris_case['fullNumber'];
        
        $stempel1 = ''; 
        $stempel2= '';
        $userid = $row_case['claim_handler_user_id'];
        //$userid = $row['ID_user'];
        //$userid2 =  $row['ID_user2'];

        if ($userid > 0){
        		$cor_user = new UserObject($userid);

        		//$stempel1 = '<div align="center">'.  $cor_user->getStempel().'<br>'. $cor_user->getName().' '.$cor_user->getSurname().'</div>';
        		$stempel1 = '<div align="">'.  $cor_user->getStempel().'</div>';
        }
        
        if ($userid2 > 0){
        		$cor_user = new UserObject($userid2);
        		$stempel2 = $cor_user->getStempel();
        }
    //    echo nl2br(print_r($row_case,1));

	  /*echo '<hr>'.$stempel1;
	  exit();
        
        $stempel1 = ''; 
        $stempel2= '';*/
        
 if ($row['type'] == 3){ //akceptacja        
      $page_template->load_template('../fax/templates/barclaycard_decyzja_tak.html');
        
      $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id'],'en') );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody. ' -&nbsp;'.$row_case['paxname'].' '.  $row_case['paxsurname'] );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
      
      
//      $page_template->set('<!--TEXT_1-->' , getTxt1($row['payment_amount']) );
      $page_template->set('<!--TEXT_1-->' , str_replace('<!--KWOTA-->', getTxt1($row['payment_amount']) , nl2br($row['text1']) ));
      //$page_template->set('<!--TEXT_1_1-->' , getTxt1_1($row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br($row['text4'])) );
      $page_template->set('<!--TEXT_1_1-->' , '' );
      $page_template->set('<!--TEXT_2-->' , str_replace(  '<!--ZESTAWIENIE-->',zestawienie($id) , nl2br($row['text2']) ) );
      
      $page_template->set('<!--BENEFICJENT-->' , nl2br($row['text4']) );
      $page_template->set('<!--ADRESAT-->' , nl2br($row['text5']) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
      $page_template->set('<!--KONTO_SWIFT-->' , $row_case_ann['wyplata_swift'] );

      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );      
                
      $body =  '<div style="">';
      $body .=  $page_template->realize();
      	$body .= '</div><DIV style="page-break-after:always"></DIV>';
      	
      	$body .=  '<div style="">';
        $body .=  $page_template->realize();
      	$body .= '</div><DIV style="page-break-after:always"></DIV>';
      	
      	$body .=  '<div style="">';
        $body .=  $page_template->realize();
      	$body .= '</div><DIV style="page-break-after:always"></DIV>';
      	
      	

      $page_template->load_template('../fax/templates/barclaycard_decyzje_operat.html');
      	
      	$page_template->set('<!--DATA-->' , $row['date'] );
        $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
        
      	$page_template->set('<!--NR_SZKODA-->' ,   $row_case['client_ref'] );
      	$page_template->set('<!--NR_SPRAWY-->' , $nr_szkody  );
      	//$page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'] );
      	$page_template->set('<!--AGENT-->' ,  $barclaycard_case->getBiurPodrozyNazwa() );
      	$page_template->set('<!--POLISA_OD-->' ,  $row_case['validityfrom'] );
      	$page_template->set('<!--POLISA_DO-->' ,  $row_case['validityto'] );
      	$page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate'] );
      	$page_template->set('<!--DATA_ZGLOSZENIA-->' ,  $row_case['notificationdate'] );
      	$page_template->set('<!--DATA_OTWARCIA-->' ,  substr($row_case['date'] , 0,10) );
      	$page_template->set('<!--POSZKODOWANY_INFO-->' ,  $row_case_ann['poszkodowany_info'] != '' ? ' / '.$row_case_ann['poszkodowany_info']:'' );
      	
      	
      	$page_template->set('<!--UBEZPIECZONY-->' ,  ( $row_case['paxsex']=='M' ? 'Pan ' : 'Pani '). $row_case['paxname'].' '.  $row_case['paxsurname'] );
      
      	$page_template->set('<!--STEMPEL1-->' , $stempel1 );
      	$page_template->set('<!--STEMPEL2-->' , $stempel2 );
      	    $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );

      	$page_template->set('<!--BENEFICJENT-->' , nl2br($row['text4']) );
      	$page_template->set('<!--SUMY_UBEZPIECZ-->' , stripslashes($row['text3']) );
      	$page_template->set('<!--WYPLATA-->' , Finance::print_currency($row['payment_amount']) .' EUR');
      	
      	$page_template->set('<!--SPOSOB_WYPLATY-->' ,  $row_case_ann['forma_wyplaty']==2 ? 'przekaz pocztowy' : 'przelew bankowy'  );

      	$body .=  '<div style="">';
      	$body .=  $page_template->realize();
      	$body .= '</div><DIV style="page-break-after:always"></DIV>';
      	$body .=  '<div style="">';
      	$body .=  $page_template->realize();
      	$body .=  '</div>';
      	
     // 	$body .= '<DIV style="page-break-after:always"></DIV>';
     // 	$body .=  $page_template->realize();
 		

       //$page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      //$body .=  $page_template->realize();
      	
  		 
       
          
  }else if ($row['type'] == 4){ //odmowa
      $page_template->load_template('../fax/templates/barclaycard_decyzja_nie.html');
      
      
      $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id'],'en') );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody . ' -&nbsp;'.$row_case['paxname'].' '.  $row_case['paxsurname']);
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
      
      
      $page_template->set('<!--TEXT_1-->' , nl2br($row['text1']) );
      $page_template->set('<!--TEXT_2-->' , nl2br($row['text2']) );
      
      $page_template->set('<!--BENEFICJENT-->' , nl2br($row['text4']) );
      $page_template->set('<!--ADRESAT-->' , nl2br($row['text5']) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
            
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );   
       
      $body =  '<div style="">';
      $body .=  $page_template->realize();
      	$body .= '</div><DIV style="page-break-after:always"></DIV>';
      	$body .=  '<div style="">';        
      $body .=  $page_template->realize();
      $body .=  '</div><DIV style="page-break-after:always"></DIV>';
      
      
      $page_template->load_template('../fax/templates/barclaycard_decyzje_operat_odmowa.html');
      	
      	$page_template->set('<!--DATA-->' , $row['date'] );
        $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
        
      	$page_template->set('<!--NR_SZKODA-->' ,   $row_case['client_ref'] );
      	$page_template->set('<!--NR_SPRAWY-->' , $nr_szkody  );
      	//$page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'] );
      	$page_template->set('<!--AGENT-->' ,  $barclaycard_case->getBiurPodrozyNazwa() );
      	$page_template->set('<!--POLISA_OD-->' ,  $row_case['validityfrom'] );
      	$page_template->set('<!--POLISA_DO-->' ,  $row_case['validityto'] );
      	$page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate'] );
      	$page_template->set('<!--DATA_ZGLOSZENIA-->' ,  $row_case['notificationdate'] );
      	$page_template->set('<!--DATA_OTWARCIA-->' ,  substr($row_case['date'] , 0,10) );
      	$page_template->set('<!--POSZKODOWANY_INFO-->' ,  $row_case_ann['poszkodowany_info'] != '' ? ' / '.$row_case_ann['poszkodowany_info']:'' );
      	
      	
      	$page_template->set('<!--UBEZPIECZONY-->' ,  ( $row_case['paxsex']=='M' ? 'Pan ' : 'Pani '). $row_case['paxname'].' '.  $row_case['paxsurname'] );
      
      	$page_template->set('<!--STEMPEL1-->' , $stempel1 );
      	$page_template->set('<!--STEMPEL2-->' , $stempel2 );
      	    $page_template->set('<!--KONTO_NAZWA-->' , '' );
      $page_template->set('<!--KONTO_NR-->' , '' );

      	$page_template->set('<!--BENEFICJENT-->' , '' );
      	$page_template->set('<!--SUMY_UBEZPIECZ-->' , stripslashes($row['text3']) );
      	$page_template->set('<!--WYPLATA-->' , '0 EUR');
      	
      	$page_template->set('<!--SPOSOB_WYPLATY-->' ,  ''  );
      
      	$body .=  '<div style="">';
      	$body .=  $page_template->realize();
      	$body .= '</div>';
      	
        
  }        
  
 
  
 

  		$tmp = file("../fax/templates/barclaycard_decyzja_header.html");
  		$header = implode('', $tmp);
  		$tmp = file("../fax/templates/barclaycard_decyzja_footer.html");
  		$footer = implode('', $tmp);
  		 $body = $header.$body.$footer;
 

   if ($body <> null){
       	 $zm = array('¡' => '&#260;', '¯' => '&#379;','¦' => '&#346;','¬' => '&#377;','Æ' => '&#262;','Ó' => '&#211;','£' => '&#321;','Ñ' => '&#323;','Ê' => '&#280;',
					'±' => '&#261;', '¿' => '&#380;','¶' => '&#347;','¼' => '&#378;','æ' => '&#263;','ó' => '&#243;','³' => '&#322;','ñ' => '&#324;','ê' => '&#281;',
					'ö' => '&#246;' , 'ü' => '&#252;'					
		);		
		//$margins =  array('left' => 0,'right'  => 0,'top'    =>30,'bottom' => 25); 
		//$margins =  array('left' => 0,'right'  => 0,'top'    =>79,'bottom' => 30); 
		$margins =  array('left' => 0,'right'  => 0,'top'    =>70,'bottom' => 30);
		
		//$header = '<div align="center" style="margin-left:100px; width:770px; text-align: right;margin-bottom: 20px;"><img style="width: 219px;height:61px" border="0" src="graphics/logoEuropa.gif"/></div>';
  		/*$STOPKA_F =  '
<p style="font-size:13px; text-align: right;font-family: Arial;">
	<b>TOWARZYSTWO UBEZPIECZEÑ<br/>EUROPA SA</b>
		<span style="font-size:11px;"><br/>53-333 Wroc³aw, ul. Powstañców ¦l±skich 2-4,  tel. (71) 334 17 00, fax (71) 334 17 07
			<br/>e-mail: sekretariat@tueuropa.pl, www.tueuropa.pl
		</span>
	<br/><br/>
<span style="font-size:8px;">S±d Rejonowy dla Wroc³awia Fabryczna we Wroc³awiu, KRS 0000002736, NIP 895-10-07-276, Kapita³ zak³adowy zarejestrowany i op³acony 31 500 000 z³</span>	
	</p>
  		';  	
		$STOPKA_F =  '<div  style="margin-left:65px; width: 770px; text-align: left; margin-top: 10px;border: #FFF solid 1px; ">'.$STOPKA_F.'</div>';		
		*/
		$header = '';
		//$header = '<div  style="margin-right:0px; width: 920px; text-align: left; margin-top: 0px;border: #FFF solid 1px; "><img src="graphics/barclaycard_papie_firmowy_head2.jpg" /></div>';
		//$STOPKA_F = '<div  style="margin-right:0px; width: 920px; text-align: left; margin-top: 0px;border: #FFF solid 1px; "><img src="graphics/barclaycard_papie_firmowy_footer.png" style="margin-right:20px"/></div>';
		$STOPKA_F='';
	//	$watermark = '<img src="graphics/barclaycard_papie_firmowy.jpg">';
		$watermark= dirname(__FILE__).'/../fax/templates/paper/barclaycard_background3.pdf';

	   if (getValue('test') == 1 ){
		   echo $body;
		   exit();
	   }
 		$file = html2pdf_new($body, strtr($STOPKA_F,$zm) ,strtr($header ,$zm),$margins,$watermark );
        if (file_exists($file) && filesize($file) > 0 ){                           
              sendPDF2Browser($file);
                    unlink($file);
                    exit ();
              
         }else
              echo " ERROR file pdf ".$file ;         
            
      echo $body;
        }else
            echo " ERROR template";        
   
}else
  echo " ERROR request";

exit;



function getTxt1_1($forma_wyplaty,$nazwa_banku,$nr_konta,$beneficjent){
	//$row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br($row['text4'])
	if ($forma_wyplaty==1){
		$tmp = explode("\n",$beneficjent);
		$ben = '';
		foreach ($tmp As $pozycja){
			if (trim($pozycja) != '' && $ben==''){
				$ben = trim(strip_tags($pozycja));
				
			}
						
		}
		$result = 'Nale¿no¶æ zostanie przekazana na poni¿sze konto:<br/>'.$ben.'<br/>'.$nazwa_banku.'&nbsp;<br>'.$nr_konta.'&nbsp;';
	}
	
	if ($forma_wyplaty==2){
		$result = 'Nale¿no¶æ zostanie przekazana przekazem pocztowym na poni¿szy adres:<br/>'.$beneficjent;
	}
	
	return $result;
}
function getTxt1($kwota){
	$result  = '<b>'.Finance::print_currency($kwota).' EUR </b>'; //(s³ownie: '.Finance::slownie($kwota).')
	return $result;	
}


function zestawienie($id){
		$ed = new BarclaycardDecision($id);
		$result = '';
		$lista_pozycji = $ed->getList_details();
		$waluty=0;
		foreach ($lista_pozycji As $pozycja){
			
			if ($pozycja['currency_id'] != 'EUR'){
				$waluty=1;
				if ($pozycja['amount'] > 0.00){
					$result .= "\n".$pozycja['multiplier'].' '.$pozycja['currency_id'] .' = '.Finance::print_currency($pozycja['rate'],4). ' EUR';
					$result .= "\n".Finance::print_currency($pozycja['amount']).' '.$pozycja['currency_id'] .' = '.Finance::print_currency($pozycja['payment_amount']). ' EUR';
					$result .= "\n";
				}
			}			
		}
		
	//	if ($waluty==1){
			//$result .= "\n".'Podstaw± do wyliczenia nale¿no¶ci jest ¶redni kurs walut wg NBP obowi±zuj±cy w dniu wyp³aty ¶wiadczenia lub odszkodowania, za który przyjmuje siê datê obci±¿enia rachunku Towarzystwa kwot± odszkodowania - zgodnie z § 10 ust. 5 Ogólnych Warunków Ubezpieczenia TRAVEL WORLD TU EUROPA S.A.';
		//}
		return nl2br(trim($result)) ;
}
?>
