<?php
require_once('include/include_ayax.php');
require_once('include/pdf_utils.php'); 
//require_once('include/include_ayax.php'); 
//require_once('include/include_mod.php');
//require_once('include/pdf_utils.php');
include('lib/lib_case.php');
//include('lib/CorisCase.php');
//include_once('lib/UserObject.php');
include_once('lib/lib_gothaer.php');


define('STOPKA_FIRM_N', '
<div align="center" style="margin: auto; width: 770px; text-align: left; margin-top:0px;border: #FFF solid 1px;">
	<img src="graphics/goether_footer.png"  width="770"/>					
</div>
');

  $id = getValue("id");
  
  if ($id>0) {
  
     
      $page_template =  new Template();
      
      $query = "SELECT * FROM ".GothaerCase::$TABLE_CLAIMS_DECISIONS."  WHERE ID='$id'";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID=$id claims pay not exists!";exit();}
      
      $row = mysql_fetch_array($mysql_result);
      $case_id=$row['ID_case'];
      
      $gothaer_case = new GothaerCase($case_id);
      $row_case_ann = $gothaer_case->getCaseInfo($case_id);
        
        $row_case = CaseInfo::getFullCaseInfo($case_id);
         
        $coris_case = CaseInfo::getCaseInfo($case_id);
        $nr_szkody = $coris_case['fullNumber'];
        
        $stempel1 = ''; 
        $stempel2= '';
       // $userid = $row['ID_user'];
       // $userid2 =  $row['ID_user2'];

        /*if ($userid > 0){
        		$cor_user = new UserObject($userid);
        		$stempel1 = $cor_user->getStempel();
        }
        
        if ($userid2 > 0){
        		$cor_user = new UserObject($userid2);
        		$stempel2 = $cor_user->getStempel();
        }
        */
        
        $stempel1 = ''; 
        $stempel2= '';

        $userid = $row_case['claim_handler_user_id'];
      //$userid = 275;
      if ($userid > 0){
          $cor_user = new UserObject($userid);

          //$stempel1 = '<div align="center">'.  $cor_user->getStempel().'<br>'. $cor_user->getName().' '.$cor_user->getSurname().'</div>';
          $stempel1 = '<div align="center">'.  $cor_user->getStempel().'</div>';
      }

 if ($row['type'] == 3){ //akceptacja        
      $page_template->load_template('../fax/templates/gothaer_decyzja_tak.html');


     $zm = array('<!--DATA_ZGLOSZENIA-->' => $row_case['notificationdate'],
         '<!--DATA_ZDARZENIA-->' => $row_case['eventdate'],
         '<!--POLISA-->'=> $row_case['policy_series'].' '.$row_case['policy'],
         '<!--KWOTA-->'=> getTxt1($row['payment_amount'])
     );


     $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id']) );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
     $page_template->set('<!--UBEZPIECZONY-->' ,  $row_case['paxname'].' '.  $row_case['paxsurname'] );
      
//      $page_template->set('<!--TEXT_1-->' , getTxt1($row['payment_amount']) );
      //$page_template->set('<!--TEXT_1-->' , str_replace('<!--KWOTA-->', getTxt1($row['payment_amount']) , nl2br($row['text1']) ));
      $page_template->set('<!--TEXT_1-->' , strtr( nl2br($row['text1']),$zm ) );


      //$page_template->set('<!--TEXT_1_1-->' , getTxt1_1($row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br($row['text4'])) );
     $page_template->set('<!--TEXT_1_1-->' , '');

     $zm2 = array(
         '<!--ZESTAWIENIE-->' => zestawienie($id),
         '<!--PLATNOSC-->' =>getTxt1_1($row_case_ann['forma_wyplaty'],$row_case_ann['wyplata_nazwa_banku'],$row_case_ann['wyplata_nr_konta_bankowego'],nl2br($row['text4']))
     );
     $page_template->set('<!--TEXT_2-->' , strtr( nl2br($row['text2']),$zm2 ) );
      
      $page_template->set('<!--TYP_SZKODY-->' , $row['text6'] );
      $page_template->set('<!--BENEFICJENT-->' , nl2br($row['text4']) );
      $page_template->set('<!--ADRESAT-->' , nl2br($row['text5']) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
            
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );      
                
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
        $body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
        $body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	
      	

      $page_template->load_template('../fax/templates/gothaer_decyzje_operat.html');
      	
      	$page_template->set('<!--DATA-->' , $row['date'] );
        $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'].' '.$row_case['policy']);
        
      	$page_template->set('<!--NR_SZKODA-->' ,   $row_case['client_ref'] );
      	$page_template->set('<!--NR_SPRAWY-->' , $nr_szkody  );
      	//$page_template->set('<!--NR_POLISY-->' ,  $row_case['policy_series'] );
      	//$page_template->set('<!--AGENT-->' ,  $europa_case->getBiurPodrozyNazwa() );
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

      	$page_template->set('<!--BENEFICJENT-->' , nl2br($row['text4']) );
      	$page_template->set('<!--SUMY_UBEZPIECZ-->' , stripslashes($row['text3']) );
      	$page_template->set('<!--WYPLATA-->' , Finance::print_currency($row['payment_amount']) .' PLN');
      	
      	$page_template->set('<!--SPOSOB_WYPLATY-->' ,  $row_case_ann['forma_wyplaty']==2 ? 'przekaz pocztowy' : 'przelew bankowy'  );

      	$body .=  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';
      	$body .=  $page_template->realize();
     // 	$body .= '<DIV style="page-break-after:always"></DIV>';
     // 	$body .=  $page_template->realize();
 		

       //$page_template->set('<!--ORGINAL-->' , 'KOPIA' );
      //$body .=  $page_template->realize();
      	
  		 
       
          
  }else if ($row['type'] == 4){ //odmowa
      $page_template->load_template('../fax/templates/gothaer_decyzja_nie.html');

     $zm = array('<!--DATA_ZGLOSZENIA-->' => $row_case['notificationdate'],
         '<!--DATA_ZDARZENIA-->' => $row_case['eventdate'],
         '<!--POLISA-->'=> $row_case['policy_series'].' '.$row_case['policy'],
         '<!--KWOTA-->'=> getTxt1($row['payment_amount']));


     $page_template->set('<!--DATA-->' , $row['date'] );
      $page_template->set('<!--DATA_ZDARZENIA-->' ,  $row_case['eventdate']);
      $page_template->set('<!--KRAJ_ZDARZENIA-->' ,  CaseInfo::getCountryName($row_case['country_id']) );
      $page_template->set('<!--NR_POLISY-->' ,  $row_case['policy']);
      $page_template->set('<!--NR_SZKODA-->' ,  $nr_szkody );
      $page_template->set('<!--SZK_ALLIANZ-->' ,  $row_case['client_ref']);
     $page_template->set('<!--UBEZPIECZONY-->' ,  $row_case['paxname'].' '.  $row_case['paxsurname'] );
      
      $page_template->set('<!--TEXT_1-->' , strtr( nl2br($row['text1']),$zm ) );
      $page_template->set('<!--TEXT_2-->' , nl2br($row['text2']) );
     $page_template->set('<!--TYP_SZKODY-->' , $row['text6'] );
      $page_template->set('<!--BENEFICJENT-->' , nl2br($row['text4']) );
      $page_template->set('<!--ADRESAT-->' , nl2br($row['text5']) );
      
      
      $page_template->set('<!--KONTO_NAZWA-->' , $row_case_ann['wyplata_nazwa_banku'] );
      $page_template->set('<!--KONTO_NR-->' , $row_case_ann['wyplata_nr_konta_bankowego'] );
            
      $page_template->set('<!--ORGINAL-->' , 'ORYGINA£' );
      $page_template->set('<!--STEMPEL1-->' , $stempel1 );
      $page_template->set('<!--STEMPEL2-->' , $stempel2 );   
       
      $body =  $page_template->realize();
      	$body .= '<DIV style="page-break-after:always"></DIV>';        
      $body .=  $page_template->realize();
      	
        
  }        
  
 
  
 

  		$tmp = file("../fax/templates/gothaer_decyzja_header.html");
  		$header = implode('', $tmp);
  		$tmp = file("../fax/templates/gothaer_decyzja_footer.html");
  		$footer = implode('', $tmp);
  		 $body = $header.$body.$footer;
 

   if ($body <> null){
       	 $zm = array('¡' => '&#260;', '¯' => '&#379;','¦' => '&#346;','¬' => '&#377;','Æ' => '&#262;','Ó' => '&#211;','£' => '&#321;','Ñ' => '&#323;','Ê' => '&#280;',
					'±' => '&#261;', '¿' => '&#380;','¶' => '&#347;','¼' => '&#378;','æ' => '&#263;','ó' => '&#243;','³' => '&#322;','ñ' => '&#324;','ê' => '&#281;',
					'ö' => '&#246;' , 'ü' => '&#252;'					
		);		
/*		$margins =  array('left' => 0,'right'  => 0,'top'    =>30,'bottom' => 25); 
		
		$header = '<div align="center" style="margin-left:100px; width:770px; text-align: right;margin-bottom: 20px;"><img style="width: 219px;height:61px" border="0" src="graphics/logoVIG.gif"/></div>';
  		$STOPKA_F =  '
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
			$STOPKA_F =  '<div  style="margin: auto; width: 770px; text-align: left;margin-left:80px; margin-top: 10px;;border: #FFF solid 0px; ">'.STOPKA_FIRM_N.'</div>';
  	$header = '<div align="center" style="margin-left:80px; width:770px; text-align: left;margin-bottom: 20px;;border: #FFF solid 0px; "><img src="graphics/goether_header.png"  width="100%" /></div>';
  	$margins =  array('left' => 0,'right'  => 0,'top'    =>40,'bottom' => 15);
  	
 		$file = html2pdf_new(strtr($body,$zm), strtr($STOPKA_F,$zm) ,strtr($header ,$zm),$margins );                    
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
        $result = 'Powy¿sza kwota wyp³acona zostanie przelewem na konto: '.$nr_konta.'&nbsp;';
    }

    if ($forma_wyplaty==2){
        $result = 'Nale¿no¶æ zostanie przekazana na przekazem pocztowym na poni¿szy adres:<br/>'.$beneficjent;
    }

    return $result;
}
function getTxt1($kwota){
	$result  = '<b>'.Finance::print_currency($kwota).' PLN (s³ownie: '.Finance::slownie($kwota).')</b>';
	return $result;	
}


function zestawienie($id){
		$ed = new GothaerDecision($id);
		$result = '';
		$lista_pozycji = $ed->getList_details();
		$waluty=0;
		foreach ($lista_pozycji As $pozycja){
			
			if ($pozycja['currency_id'] != 'PLN'){
				$waluty=1;
				if ($pozycja['amount'] > 0.00){
					$result .= "\n".$pozycja['multiplier'].' '.$pozycja['currency_id'] .' = '.Finance::print_currency($pozycja['rate'],4). ' PLN';
					$result .= "\n".Finance::print_currency($pozycja['amount']).' '.$pozycja['currency_id'] .' = '.Finance::print_currency($pozycja['payment_amount']). ' PLN';
					$result .= "\n";
				}
			}			
		}
		
		return nl2br($result) ;
}
?>
