<?php
require_once('include/include.php');
require_once('include/include_mod.php');
require_once('include/pdf_utils.php');
require_once('lib/lib_case.php');
require_once('lib/CorisCase.php');
include_once('lib/UserObject.php');
include_once('lib/lib_gothaer.php');

  $lista = getValue("lista");
  
  $pozycje_tmp = explode(',',$lista )  ;
  $pozycje = array();
  foreach ($pozycje_tmp AS $idka){
  	if ($idka > 0 )
  		$pozycje[] = $idka;  	
  }
  $Payment_amount = 0.0;
  
  if (count($pozycje) > 0 ) {

      //require_once('include/template.php');
      $page_template =  new Template();
      $lista_roszczen = array();
      $decyzja=4;
      
      $query = "SELECT * FROM ".GothaerCase::$TABLE_CLAIMS_DETAILS."  WHERE ID IN (".implode(',', $pozycje).") ";
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
	
      $query = "SELECT * FROM  ".GothaerCase::$TABLE_CLAIMS." WHERE ID='".$lista_roszczen[0][ID_claims]."'";
      $mysql_result = mysql_query($query);
      $row_claim = mysql_fetch_array($mysql_result);
      
      $row = $row_claim;
      $case_id=$row['ID_case'];
      
      $gothaer_case = new GothaerCase($case_id);
      $row_case_ann = $gothaer_case->getCaseInfo($case_id);
        
        $row_case = CaseInfo::getFullCaseInfo($case_id);
         
        $coris_case = getCaseInfo($case_id);
        $nr_szkody = $coris_case['fullNumber'];
        
        $stempel1 = ''; 
        $stempel2= '';
        $userid = $row['ID_user'];
        $userid2 =  $row['ID_user2'];
               
        $stempel1 = ''; 
        $stempel2= '';

      $userid = $row_case['claim_handler_user_id'];
     // $userid = 275;
      if ($userid > 0){
          $cor_user = new UserObject($userid);
          $stempel1 = '<div align="center">'.  $cor_user->getStempel().'</div>';
      }

 if ($decyzja == 3){ //akceptacja        
      $page_template->load_template('../fax/templates/gothaer_decyzja_tak.html');
        
      $page_template->set('<!--DATA-->' , substr($row['date'],0,10) );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id']) );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);


//      $page_template->set('<!--TEXT_1-->' , getTxt1($row['payment_amount']) );

     $zm = array('<!--DATA_ZGLOSZENIA-->' => $row_case['notificationdate'],
		'<!--DATA_ZDARZENIA-->' => $row_case['eventdate'],
		'<!--POLISA-->'=> $row_case['policy_series'].' '.$row_case['policy'],
	 	'<!--KWOTA-->'=> getTxt1($Payment_amount)

         );

     $zm2 = array(
         '<!--ZESTAWIENIE-->' => $zestawienie,
         '<!--PLATNOSC-->' => getTxt1_1($row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br(nl2br(generateTxt($decyzja,$case_id,'beneficjent'))) )
     );
      $page_template->set('<!--TEXT_1-->' , strtr( nl2br(generateTxt($decyzja,$case_id,1) ),$zm ));
      //$page_template->set('<!--TEXT_1_1-->' , getTxt1_1($row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br(nl2br(generateTxt($decyzja,$case_id,'beneficjent'))) ));//$row['text4']
     $page_template->set('<!--TEXT_1_1-->' , '' );
     //$page_template->set('<!--TEXT_2-->' , str_replace(  '<!--ZESTAWIENIE-->',$zestawienie , generateTxt($decyzja,$case_id,2) ) ) ;
     $page_template->set('<!--TEXT_2-->' , strtr(  generateTxt($decyzja,$case_id,2) ),$zm2 ) ;

     $page_template->set('<!--UBEZPIECZONY-->' ,  $row_case['paxname'].' '.  $row_case['paxsurname'] );
      $page_template->set('<!--BENEFICJENT-->' , nl2br(generateTxt($decyzja,$case_id,'beneficjent')) );
      $page_template->set('<!--ADRESAT-->' , nl2br(generateTxt($decyzja,$case_id,'adresat')) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
            
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA�' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );      
                
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
        $body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
        $body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
      	

      $page_template->load_template('../fax/templates/gothaer_decyzje_operat.html');
      	
      	$page_template->set('<!--DATA-->' , substr($row['date'],0,10) );
        $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
        
      	$page_template->set('<!--NR_SZKODA-->' ,   $row_case['client_ref'] );
      	$page_template->set('<!--NR_SPRAWY-->' , $nr_szkody  );
      	//$page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'] );
      	$page_template->set('<!--AGENT-->' ,  CaseInfo::getCaseClientName($case_id) );
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
      	$page_template->set('<!--WYPLATA-->' , Finance::print_currency($Payment_amount) .' PLN');

      	$body .=  $page_template->realize();
      //	$body .= '<DIV style="page-break-after:always"></DIV>';
      //	$body .=  $page_template->realize();
     // 	$body .= '<DIV style="page-break-after:always"></DIV>';
     // 	$body .=  $page_template->realize();
 		

       //$page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      //$body .=  $page_template->realize();
      	
  		 
       
          
  }else if ($decyzja == 4){ //odmowa
      $page_template->load_template('../fax/templates/gothaer_decyzja_nie.html');

     $zm = array('<!--DATA_ZGLOSZENIA-->' => $row_case['notificationdate'],
         '<!--DATA_ZDARZENIA-->' => $row_case['eventdate'],
         '<!--POLISA-->'=> $row_case['policy_series'].' '.$row_case['policy'],
         '<!--KWOTA-->'=> getTxt1($Payment_amount) );


      $page_template->set('<!--DATA-->' , substr($row['date'],0,10) );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id']) );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
     $page_template->set('<!--UBEZPIECZONY-->' ,  $row_case['paxname'].' '.  $row_case['paxsurname'] );
      
      //$page_template->set('<!--TEXT_1-->' , nl2br( nl2br(generateTxt($decyzja,$case_id,1)) ) );
      $page_template->set('<!--TEXT_1-->' , strtr(nl2br(generateTxt($decyzja,$case_id,1) ),$zm ) );
      $page_template->set('<!--TEXT_2-->' ,  nl2br(generateTxt($decyzja,$case_id,2))  );
      
      $page_template->set('<!--BENEFICJENT-->' , nl2br(generateTxt($decyzja,$case_id,'beneficjent')) );
      $page_template->set('<!--ADRESAT-->' , nl2br(generateTxt($decyzja,$case_id,'adresat')) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
            
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA�' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );   
       
      $body =  $page_template->realize();
    //  	$body .= '<DIV style="page-break-after:always"></DIV>';
   //   $body .=  $page_template->realize();
  }        
  
 
  
 

  		$tmp = file("../fax/templates/gothaer_decyzja_header.html");
  		$header = implode('', $tmp);
  		$tmp = file("../fax/templates/gothaer_decyzja_footer.html");
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
		$result = 'Powy�sza kwota wyp�acona zostanie przelewem na konto: '.$nr_konta.'&nbsp;';
	}
	
	if ($forma_wyplaty==2){
		$result = 'Nale�no�� zostanie przekazana na przekazem pocztowym na poni�szy adres:<br/>'.$beneficjent;
	}
	
	return $result;
}
function getTxt1($kwota){
	$result  = '<b>'.Finance::print_currency($kwota).' PLN (s�ownie: '.Finance::slownie($kwota).')</b>';
	return $result;	
}


function zestawienie($lista_roszczen){
		global $Payment_amount;
		//$ed = new VIGDecision($id);
		$result = '';
		//$lista_pozycji = $ed->getList_details();
		$waluty=0;
		foreach ($lista_roszczen As $pozycja){
			
			if ($pozycja['currency_id'] != 'PLN'){
				$waluty=1;
				
				$kurs = Finance::getKurs(date("Y-m-d"),1,$pozycja['currency_id']);						
				$tabled_id = $kurs['table_id'] ;
				$rate = $kurs['rate'];
				$multiplier = $kurs['multiplier'];					
				$platnosc = Finance::ev_round( ( $pozycja['kwota_zaakceptowana'] * $rate) / $multiplier ,2);		
					
				if ($pozycja['kwota_zaakceptowana'] > 0.00){
					$result .= "\n".$multiplier.' '.$pozycja['currency_id'] .' = '.Finance::print_currency($rate,4). ' PLN';
					$result .= "\n".Finance::print_currency($pozycja['kwota_zaakceptowana']).' '.$pozycja['currency_id'] .' = '.Finance::print_currency($platnosc). ' PLN';
					$result .= "\n";
				}
				$Payment_amount += $platnosc;
			}			
		}
		
		if ($waluty==1){
			$result .= "\n".'';
		}
		return nl2br($result) ;
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function generateTxt($typ,$case_id,$nr){
	
		$row_case_announce = GothaerCase::getCaseInfo($case_id);
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
	
	$result = $row_case['paxsex']=='M' ? 'Pan ' : 'Pani ';
	$result .= $row_case['paxname'].' '.$row_case['paxsurname']."\n";
	$result .= 'ul. '.$row_case['paxaddress']."\n";
	$result .= $row_case['paxpost'].' '.$row_case['paxcity']."\n";
		
	return $result;
}

function agetTxt1($typ,$row_case,$row_case_announce){
	 	global $Payment_amount;
	$odszkodowanie = Finance::print_currency($Payment_amount);	
	$slownie = Finance::slownie($odszkodowanie);
    if ( $typ==3 ){  // poz
        $result = $row_case['paxsex']=='M' ? 'Szanowny Panie,' : 'Szanowna Pani,';
        $result .= "\n\n";
        $result .= 'W odpowiedzi na korespondencj� otrzyman� w dniu <!--DATA_ZGLOSZENIA--> April Polska Sp. o.o. dzia�aj�c w imieniu i na rzecz Gothaer TU SA  na podstawie art. 29 w zwi�zku z art. 28 ustawy o dzia�alno�ci ubezpieczeniowej z dnia 11 wrze�nia 2015 r. uprzejmie informuje, �e po rozpatrzeniu wniesionych roszcze�, dotycz�cych szkody powsta�ej w�dniu <!--DATA_ZDARZENIA--> wynikaj�cej z polisy ubezpieczeniowej <!--POLISA--> wyp�aci kwot� w wysoko�ci: <!--KWOTA-->.';
        //	$result = '';
    }

    if ($typ==4 ) {// odm
        $result = $row_case['paxsex'] == 'M' ? 'Szanowny Panie,' : 'Szanowna Pani,';
        $result .= "\n\n";
        $result .= 'W odpowiedzi na korespondencj� otrzyman� w dniu <!--DATA_ZGLOSZENIA--> April Polska Sp. z o.o. dzia�aj�c w imieniu i na rzecz Gothaer TU SA  na podstawie art. 29 w zwi�zku z art. 28 ustawy o dzia�alno�ci ubezpieczeniowej z dnia 11 wrze�nia 2015 r. informuje, �e po rozpatrzeniu wniesionych roszcze�, dotycz�cych szkody powsta�ej w�dniu <!--DATA_ZDARZENIA--> wynikaj�cej z polisy ubezpieczeniowej <!--POLISA--> nie znajduje podstaw prawnych do refundacji poniesionych koszt�w leczenia za granic�.';
    }
    return $result;
} 

function agetTxt2($typ,$row_case,$row_case_announce){
    if ( $typ==3 ){  // poz
        $result = 'Uprzejmie informujemy, i� podstaw� do wyliczenia nale�no�ci jest �redni kurs NBP obowi�zuj�cy w dniu ustalenia �wiadczenia lub odszkodowania, � 14 ust. 4 Warunk�w Ubezpieczenia koszt�w leczenia za granic� i poza krajem sta�ego pobytu dla posiadaczy Kart Planeta M�odych i Kart Planeta M�odych Plus.';
        $result  .= "\n\n";
        $result  .= "Maj�c na uwadze powy�sze informujemy, i� nale�na kwota zosta�a wyliczona zgodnie z poni�szym zestawieniem:";
        $result  .= "\n<!--ZESTAWIENIE-->";
        $result  .= "\n<!--PLATNOSC-->";


    }


    if ( $typ==4 ) { // odm
        $result = 'Wyja�niamy, i� zgodnie z  � ... Warunk�w Ubezpieczenia Koszt�w leczenia za granic� i poza krajem sta�ego pobytu dla posiadaczy Kart Planeta M�odych i Kart Planeta M�odych Plus: 
Jak wynika z dokumentacji zgromadzonej w przedmiotowej sprawie ...

Maj�c na uwadze powy�sze zmuszeni jeste�my oddali� roszczenia wnoszone w zakresie ...

';
    }
	return $result;
}


function getSumyUbezp($typ,$row_case,$row_case_announce){
	if ( $typ==3 )  // poz
/*		$result = '
Leczenie amb. (wypadek)                    ... PLN
Leczenie szpitalne (wypadek)               ... PLN
Leczenie amb. (choroba)                     ... PLN
Leczenie szpitalne (choroba)	           ....PLN
Koszty ratownictwa			... PLN
Koszty transportu			... PLN
Pomoc w podr�y		           ... PLN
Baga� podr�ny			           ... PLN
Op�nienie dostarczenia baga�u           ... PLN
OC					... PLN';
*/
        $result = '
<table>
<tr><td>Leczenie amb. (wypadek)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie szpitalne (wypadek)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie amb. (choroba)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie szpitalne (choroba)</td><td align="right">... PLN</td></tr>
<tr><td>Rehabilitacja</td><td align="right">... PLN</td></tr>
<tr><td>Sprz�t rehabilitacyjny</td><td align="right">... PLN</td></tr>
<tr><td>Pomoc w podr�y</td><td align="right">... PLN</td></tr>
<tr><td>Baga� podr�ny</td><td align="right">... PLN</td></tr>
<tr><td>Op�nienie dostarczenia baga�u</td><td align="right">... PLN</td></tr>
<tr><td>Stomatologia</td><td align="right">... PLN</td></tr>
</table>';

		if ( $typ ==4 ) // odm
		$result = '';				
	return $result;
}
?>
