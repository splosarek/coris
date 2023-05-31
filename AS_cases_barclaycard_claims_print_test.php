<?php require_once('include/include.php');
require_once('include/include_mod.php');
require_once('include/pdf_utils.php');
include('lib/lib_case.php');
include('lib/CorisCase.php');
include_once('lib/UserObject.php');
include_once('lib/lib_barclaycard.php');

  $lista = getValue("lista");
  
  $pozycje_tmp = explode(',',$lista )  ;
  $pozycje = array();
  foreach ($pozycje_tmp AS $idka){
  	if ($idka > 0 )
  		$pozycje[] = $idka;  	
  }
  $Payment_amount = 0.0;
  
  if (count($pozycje) > 0 ) {
  
      include('include/template.php');
      $page_template =  new Template();
      $lista_roszczen = array();
      $decyzja=4;
      
      $query = "SELECT * FROM coris_barclaycard_claims_details  WHERE ID IN (".implode(',', $pozycje).") ";
      //echo $query;
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID= $lista claims pay not exists!";exit();}      
      while ($row = mysql_fetch_array($mysql_result)){
      		$lista_roszczen[] = $row;      	
      		if ($row['status'] == 3 )
      				$decyzja=3;
      				
      		/*
      		 				$kurs = Finance::getKurs(date("Y-m-d"),1,$pozycja['currency_id']);						
				$tabled_id = $kurs['table_id'] ;
				$rate = $kurs['rate'];
				$multiplier = $kurs['multiplier'];					
				$platnosc = Finance::ev_round( ( $row['amount'] * $rate) / $multiplier ,2);		

      		 */		
      }

      
		$zestawienie = zestawienie($lista_roszczen);
	
      $query = "SELECT * FROM  coris_barclaycard_claims WHERE ID='".$lista_roszczen[0][ID_claims]."'";
      $mysql_result = mysql_query($query);
      $row_claim = mysql_fetch_array($mysql_result);
      
      $row = $row_claim;
      $case_id=$row['ID_case'];
      
      $barclaycard_case = new BarclaycardCase($case_id);
      $row_case_ann = $barclaycard_case->getCaseAnnounce($case_id);
        
        $row_case = CaseInfo::getFullCaseInfo($case_id);

	  $coris_case = CaseInfo::getCaseInfo($case_id);
        $nr_szkody = $coris_case['fullNumber'];
        
        $stempel1 = ''; 
        $stempel2= '';
        $userid = $row['ID_user'];
        $userid2 =  $row['ID_user2'];
               
        $stempel1 = ''; 
        $stempel2= '';

	  $userid = $coris_case['claim_handler_user_id'];
	  if ($userid > 0){
		  $cor_user = new UserObject($userid);

		  //$stempel1 = '<div align="center">'.  $cor_user->getStempel().'<br>'. $cor_user->getName().' '.$cor_user->getSurname().'</div>';
		  $stempel1 = '<div align="">'.  $cor_user->getStempel().'</div>';
	  }

 if ($decyzja == 3){ //akceptacja        
      $page_template->load_template('../fax/templates/barclaycard_decyzja_tak.html');
        
      $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id'],'en') );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody. ' - '.$row_case['paxname'].' '.  $row_case['paxsurname'] );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
      
      
//      $page_template->set('<!--TEXT_1-->' , getTxt1($row['payment_amount']) );
      $page_template->set('<!--TEXT_1-->' , str_replace('<!--KWOTA-->', getTxt1($Payment_amount) , nl2br(generateTxt($decyzja,$case_id,1) ) ));
      //$page_template->set('<!--TEXT_1_1-->' , getTxt1_1($row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br(nl2br(generateTxt($decyzja,$case_id,'beneficjent'))) ));//$row['text4']
      $page_template->set('<!--TEXT_1_1-->' , '' );//$row['text4']
      $page_template->set('<!--TEXT_2-->' , str_replace(  '<!--ZESTAWIENIE-->',$zestawienie , generateTxt($decyzja,$case_id,2) ) ) ;
      
      $page_template->set('<!--BENEFICJENT-->' , nl2br(generateTxt($decyzja,$case_id,'beneficjent')) );
      $page_template->set('<!--ADRESAT-->' , nl2br(generateTxt($decyzja,$case_id,'adresat')) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
      $page_template->set('<!--KONTO_SWIFT-->' , $row_case_ann['wyplata_swift'] );

      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );      
                
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
        $body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
        $body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
      	

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
      	$page_template->set('<!--POSZKODOWANY_INFO-->' ,  $row_case_ann['poszkodowany_info'] != '' ? ' / '.$row_case_ann['poszkodowany_info']:'' );
      	
      	
      	$page_template->set('<!--UBEZPIECZONY-->' ,  ( $row_case['paxsex']=='M' ? 'Pan ' : 'Pani '). $row_case['paxname'].' '.  $row_case['paxsurname'] );
      
      	$page_template->set('<!--STEMPEL1-->' , $stempel1 );
      	$page_template->set('<!--STEMPEL2-->' , $stempel2 );
      	    $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );

      	$page_template->set('<!--BENEFICJENT-->' , nl2br(generateTxt($decyzja,$case_id,4) ) );//$row['text4']
      	$page_template->set('<!--SUMY_UBEZPIECZ-->' , stripslashes(generateTxt($decyzja,$case_id,'sumy_ubezpieczenia') ) ); //$row['text3']
      	$page_template->set('<!--WYPLATA-->' , Finance::print_currency($Payment_amount) .' EUR');

      	$body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	$body .=  $page_template->realize();
     // 	$body .= '<DIV style="page-break-after:always"></DIV>';
     // 	$body .=  $page_template->realize();
 		

       //$page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      //$body .=  $page_template->realize();
      	
  		 
       
          
  }else if ($decyzja == 4){ //odmowa
      $page_template->load_template('../fax/templates/barclaycard_decyzja_nie.html');
      
      
      $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id'],'en') );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody . ' - '.$row_case['paxname'].' '.  $row_case['paxsurname']);
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
      
      
      $page_template->set('<!--TEXT_1-->' , nl2br( nl2br(generateTxt($decyzja,$case_id,1)) ) );
      $page_template->set('<!--TEXT_2-->' , nl2br( nl2br(generateTxt($decyzja,$case_id,2)) ) );
      
      $page_template->set('<!--BENEFICJENT-->' , nl2br(generateTxt($decyzja,$case_id,'beneficjent')) );
      $page_template->set('<!--ADRESAT-->' , nl2br(generateTxt($decyzja,$case_id,'adresat')) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
            
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );   
       
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';        
      $body .=  $page_template->realize();      	        
  }        
  
 
  
 

  		$tmp = file("../fax/templates/barclaycard_decyzja_header.html");
  		$header = implode('', $tmp);
  		$tmp = file("../fax/templates/barclaycard_decyzja_footer.html");
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
		$result = 'Nale¿no¶æ zostanie przekazana na poni¿sze konto:<br/>'.$ben.'</br>'.$nazwa_banku.'&nbsp;<br>'.$nr_konta.'&nbsp;';
	}
	
	if ($forma_wyplaty==2){
		$result = 'Nale¿no¶æ zostanie przekazana na przekazem pocztowym na poni¿szy adres:<br/>'.$beneficjent;
	}
	
	return $result;
}
function getTxt1($kwota){
	$result  = '<b>'.Finance::print_currency($kwota).' EUR </b>';//(s³ownie: '.Finance::slownie($kwota).')
	return $result;	
}


function zestawienie($lista_roszczen){
		global $Payment_amount;
		//$ed = new EuropaDecision($id);
		$result = '';
		//$lista_pozycji = $ed->getList_details();
		$waluty=0;
		foreach ($lista_roszczen As $pozycja){
			
			if ($pozycja['currency_id'] != 'EUR'){
				$waluty=1;
				
				$kurs = Finance::getKurs(date("Y-m-d"),1,$pozycja['currency_id']);						
				$tabled_id = $kurs['table_id'] ;
				$rate = $kurs['rate'];
				$multiplier = $kurs['multiplier'];					
				$platnosc = Finance::ev_round( ( $pozycja['kwota_zaakceptowana'] * $rate) / $multiplier ,2);		
					
				if ($pozycja['kwota_zaakceptowana'] > 0.00){
					$result .= "\n".$multiplier.' '.$pozycja['currency_id'] .' = '.Finance::print_currency($rate,4). ' EUR';
					$result .= "\n".Finance::print_currency($pozycja['kwota_zaakceptowana']).' '.$pozycja['currency_id'] .' = '.Finance::print_currency($platnosc). ' EUR';
					$result .= "\n";
				}
				$Payment_amount += $platnosc;
			}	else{
				$Payment_amount += $pozycja['kwota_zaakceptowana'];
			}
		}
		
	/*	if ($waluty==1){
			$result .= "\n".'Podstaw± do wyliczenia nale¿no¶ci jest ¶redni kurs walut wg NBP obowi±zuj±cy w dniu wyp³aty ¶wiadczenia lub odszkodowania, za który przyjmuje siê datê obci±¿enia rachunku Towarzystwa kwot± odszkodowania - zgodnie z § 10 ust. 5 Ogólnych Warunków Ubezpieczenia TRAVEL WORLD TU EUROPA S.A.';
		}*/
		return nl2br($result) ;
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function generateTxt($typ,$case_id,$nr){
	
		$row_case_announce = BarclaycardCase::getCaseAnnounce($case_id);
		$row_case= CaseInfo::getFullCaseInfo($case_id);
		
		if ($nr==1)
			return agetTxt1($typ,$row_case,$row_case_announce);
			
		if ($nr==2)	
			return agetTxt2($typ,$row_case,$row_case_announce);
		if ($nr=='sumy_ubezpieczenia')	
			return getSumyUbezp($typ,$row_case,$row_case_announce);
		if ($nr=='adresat')	
			return getAdresat($row_case,$row_case_announce);
		if ($nr=='beneficjent')	
			return getAdresat($row_case,$row_case_announce);
}	


function getAdresat($row_case,$row_case_announce){
	
	$result = $row_case['paxsex']=='M' ? 'Herr ' : 'Frau ';
	$result .= $row_case['paxname'].' '.$row_case['paxsurname']."\n";
	$result .= ''.$row_case['paxaddress']."\n";
	$result .= $row_case['paxpost'].' '.$row_case['paxcity']."\n";
		
	return $result;
}
/*
function agetTxt1($typ,$row_case,$row_case_announce){
	 	global $Payment_amount;
	$odszkodowanie = Finance::print_currency($Payment_amount);	
	$slownie = Finance::slownie($odszkodowanie);		
	
	if ( $typ==3 ){  // poz
		$result = 'Uprzejmie zawiadamiamy, ¿e w zwi±zku ze zg³oszon± szkod± ..... TU EUROPA S.A. Biuro Likwidacji Szkód Turystycznych przyzna³o odszkodowanie w wysoko¶ci <!--KWOTA-->';
	//	$result = '';
	}	
	
	if ( $typ==4 ) // odm
		$result = 'Uprzejmie zawiadamiamy, ¿e w zwi±zku ze zg³oszonymi roszczeniami TU EUROPA S.A. Biuro Likwidacji Szkód Turystycznych odmawia wyp³aty odszkodowania za  ...... .';	
	
	return $result;
} 

function agetTxt2($typ,$row_case,$row_case_announce){
	if ( $typ==3 ){  // poz
		$result = 'Powy¿sza kwota stanowi zwrot kosztów ....... zgodnie z zestawieniem poni¿ej: ';
		$result  .= "\n\n<!--ZESTAWIENIE-->";
	}
		

	if ( $typ==4 ) // odm
		$result = '';				
	return $result;
}*/

function agetTxt1($typ,$row_case,$row_case_announce){

/*	$odszkodowanie = Finance::print_currency($Payment_amount);
	$slownie = Finance::slownie($odszkodowanie);*/

	if ( $typ ==3 ){  // poz
		$result = 'Sehr geehrte Frau / Sehr geehrter Herr ';
		$result .= "\n\n".'wir haben die Leistungsprüfung abgeschlossen und die geltend gemachten Ansprüche anerkannt.  Folgende Betrag  ist fällig geworden: <!--KWOTA-->';
		//	$result = '';
	}

	if ( $typ ==4 ) { // odm
		$result = 'Sehr geehrte Frau  / Sehr geehrter Herr .';
		$result .= "\n\n".'nach Auswertung der Unterlagen  und aus den jetzt vorliegenden Informationen können wir Ihren Forderungsanspruch leider nicht geltend machen. ';
	}

	return $result;
}

function agetTxt2($typ,$row_case,$row_case_announce){
	if ( $typ==3 ){  // poz
		$result = 'Der oben genannte Betrag setzt sich wie folgt zusammen: ';
		$result  .= "\n\n<!--ZESTAWIENIE-->";

		$result .= "\n\n".'Als Grundlage für die Berechnung der Auszahlung haben wir den Tageswechselkurs der Deutschen Bundesbank  an dem die Rechnung  beglichen wurde, angewendet.';

		$result .= "\n\n\n\n".'Diesen Betrag werden wir in den nächsten Tagen auf das uns bekannte Konto überweisen. ';

	}


	if ( $typ==4 ) { // odm
		$result = 'Laut den Allgemeinen Versicherungsbedingungen .................';
		$result .= "\n" . 'Da Ihre Forderung .................';
		$result .= "\n" . 'Ihr Fall ist somit von der Reiseversicherung nicht gedeckt. .................';
	}
	return $result;
}

function getSumyUbezp($typ,$row_case,$row_case_announce){
	if ( $typ==3 )  // poz
/*		$result = '
Leczenie amb. (wypadek)                    ... EUR
Leczenie szpitalne (wypadek)               ... EUR
Leczenie amb. (choroba)                     ... EUR
Leczenie szpitalne (choroba)	           ....EUR
Koszty ratownictwa			... EUR
Koszty transportu			... EUR
Pomoc w podró¿y		           ... EUR
Baga¿ podró¿ny			           ... EUR
Opó¼nienie dostarczenia baga¿u           ... EUR
OC					... EUR';
*/
		$result = '
<table>
<tr><td>Leczenie amb. (wypadek)</td><td align="right">... EUR</td></tr>
<tr><td>Leczenie szpitalne (wypadek)</td><td align="right">... EUR</td></tr>
<tr><td>Leczenie amb. (choroba)</td><td align="right">... EUR</td></tr>
<tr><td>Leczenie szpitalne (choroba)</td><td align="right">... EUR</td></tr>
<tr><td>Koszty ratownictwa</td><td align="right">... EUR</td></tr>
<tr><td>Koszty transportu</td><td align="right">... EUR</td></tr>
<tr><td>Pomoc w podró¿y</td><td align="right">... EUR</td></tr>
<tr><td>Baga¿ podró¿ny</td><td align="right">... EUR</td></tr>
<tr><td>Opó¼nienie dostarczenia baga¿u</td><td align="right">... EUR</td></tr>
<tr><td>OC</td><td align="right">... EUR</td></tr>
</table>
';

		if ( $typ ==4 ) // odm
		$result = '';				
	return $result;
}
?>
