<?php require_once('include/include.php');
require_once('include/include_mod.php');
require_once('include/pdf_utils.php');


  $lista = array('&nbsp;','Upowa¿niony','Instytucja');      
    $lista_status = array('Oczekuj±cy','Zaakceptowany','Odrzucony');  
    $forma_wyplaty = array('','Przelew bankowy','Przekaz pocztowy');
    
 
  $id = getValue("id");
  $tryb = getValue('tryb');
  
  
  if ($id>0) {
  
      include('include/template.php');
      $page_template =  new Template();
      
      $query = "SELECT * FROM coris_assistance_cases_claims_pay WHERE ID='$id'";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID=$id claims pay not exists!";exit();}
      
      $row = mysql_fetch_array($mysql_result);
      $case_id=$row['ID_case'];
      
      
        $row_case = row_case_info($case_id);
        $row_claims = row_claims($row['ID_claims']);
        $row_case_ann = row_case_announce($case_id);
        $row_agent = row_agent($row_case_ann['biurop_id']);
        
 if ($tryb=='operat'){
        
      $page_template->load_template('../fax/templates/claims_operat.html');
      
      
      $lista = '';
     // $poprzednie_wyplaty = '<table border="0" width="450"><tr><td>Data wyp³aty</td><td align="right">Kwota w walucie</td> <td align="right">Waluta</td> </tr></table>';
    $poprzednie_wyplaty = '<table border="0" width="370">
       <tr><td width="40%">Data wyp³aty</td><td align="left" width="30%">Kwota w walucie</td> <td align="right" width="30%">Waluta</td> </tr>'.$lista.'</table>';
	  
      //$rachunek = $row['number']>0 ?  $row['number'] : 1;
      $rachunek = $row['number'] ;
      
      $sposob_wyplaty = $forma_wyplaty[$row['pay_type']];
      
      $beneficjent = '';
      if ($row['announcer']==1 ){ // upowazniony
      
      
        
      }else if ($row['announcer']==2 ){ // instytucja
        
      }
      
        $beneficjent .= ($row['sex'] == 'K' ? 'Pani ' : '') .($row['sex'] == 'M' ? 'Pan ' : ''). $row['name'].' '.$row['surname'];
        $beneficjent .= '<br>ul. '.$row['adress'];
        $beneficjent .= '<br>'.$row['post'].' '.$row['city'];
        $bank   = $row['bank_name'];
        $konto = format_konto($row['account_number']);
        
        
        
        $q = "SELECT rc.numer ,rc.nazwa, rc.nazwa_druk, (SELECT suma FROM coris_assistance_cases_reserve  WHERE coris_assistance_cases_reserve.case_id='$case_id' AND  coris_assistance_cases_reserve.ID_ryzyko = rc.ID ) As suma,rc_vg.kolejnosc, rc_vg.ID FROM coris_signal_ryzyka_czastkowe rc,coris_signal_ryzyka_czastkowe_vs_glowne rc_vg  WHERE rc.ID=rc_vg.ID_ryzko_czastkowe AND rc_vg.ID_ryzyko_glowne ='".$row_case_ann['ryzyko_gl']."'
        UNION
        SELECT rc.numer ,rc.nazwa, rc.nazwa_druk, (SELECT suma FROM coris_assistance_cases_reserve  WHERE coris_assistance_cases_reserve.case_id='$case_id' AND  coris_assistance_cases_reserve.ID_ryzyko = rc.ID) As suma,100,1000 FROM coris_signal_ryzyka_czastkowe rc WHERE rc.numer='550'
        
        ORDER BY kolejnosc,ID
        
        ";
        
        $mr = mysql_query($q);
        if (!$mr){echo $q.'<br>'.mysql_error();}
       $sumy_ubezpiecz  = '<table border="0" width="380">';
        
        while ($r = mysql_fetch_array($mr))
          $sumy_ubezpiecz .= '<tr><td align="left" wifth="80">'.$r['numer'].'</td><td width="190" align="left">'.($r['nazwa_druk']  != ''?  $r['nazwa_druk'] : $r['nazwa'] ).'</td> <td align="right" width="110">'.print_currency($r['suma']).' PLN</td> </tr>';                  
           
        
      
        
        $sumy_ubezpiecz .= '</table>';
        
        //$poprzednie_wyplaty .= '<table border="0" width="450">';
          //$poprzednie_wyplaty .= '<tr><td>Data wyp³aty</td><td>Kwota w walucie</td><td>Waluta</td></tr>';
          
        //$poprzednie_wyplaty .= '</table>';
        
        
      $page_template->set('%%SIGNAL_DATA%%' , $row['date'] );
      $page_template->set('%%SIGNAL_KOD_OPERATORA%%' ,  getUserInitials($row['ID_user']));
      $page_template->set('%%SIGNAL_NR_SZKODY%%' ,  $row_case['client_ref']);
      $page_template->set('%%SIGNAL_NAZWA_UBEZPIECZONEGO%%' ,  ($row_case['paxsex'] == 'K' ? 'Pani ' : '') .($row_case['paxsex'] == 'M' ? 'Pan ' : '') . $row_case['paxname'].' '.$row_case['paxsurname']);
      $page_template->set('%%SIGNAL_NR_POLISY%%' ,  $row_case['policy']);
      $page_template->set('%%SIGNAL_AGENT%%' ,  $row_agent['nazwa']);
      $page_template->set('%%SIGNAL_ODDZIAL%%' ,  $row_agent['miasto']);
      $page_template->set('%%SIGNAL_OKRES_UBEZPIECZENIA%%' ,  $row_case['validityfrom'].' - '.$row_case['validityto']);
      $page_template->set('%%SIGNAL_DATA_ZDARZENIA%%' ,  $row_case['eventdate']);
      $page_template->set('%%SIGNAL_DATA_ZGLOSZENIA%%' , substr($row_case['date'],0,10));
      $page_template->set('%%SIGNAL_NR_SPRAWY_CORIS%%' ,  $row_case['number'].'/'.substr($row_case['year'],2,2));
      $page_template->set('%%SIGNAL_SUMY_UBEZPIECZENIA%%' ,  $sumy_ubezpiecz);
      
            
      $page_template->set('%%SIGNAL_POPRZEDNIE_WYPLATY%%' ,  $poprzednie_wyplaty);
       
      $page_template->set('%%SIGNAL_WYPLATA%%' ,  print_currency($row['amount']).' PLN');
      $page_template->set('%%SIGNAL_NR_RACHUNKU%%' ,  $rachunek.'&nbsp;' );
      $page_template->set('%%SIGNAL_SPOSOB_WYPLATY%%' ,  $sposob_wyplaty);
      $page_template->set('%%SIGNAL_BENEFICJENT%%' ,  $beneficjent);
      $page_template->set('%%SIGNAL_BANK%%' ,  $bank);
      $page_template->set('%%SIGNAL_KONTO%%' ,  $konto);      
        
      $body =  $page_template->realize();        
    
  }else if ($tryb=='zlecenie'){
      $page_template->load_template('../fax/templates/claims_zlecenie.html');
      
      
      
      $poprzednie_wyplaty = '<table border="0" width="450"><tr><td>Data wyp³aty</td><td align="right">Kwota w walucie</td> <td align="right">Waluta</td> </tr></table>';
      $rachunek = '...';
      $sposob_wyplaty = $forma_wyplaty[$row['pay_type']];
      $sposob_wyplaty2 = $sposob_wyplaty.'<br>'. $row['bank_name'] .'<br>'. format_konto($row['account_number']);
      
      
      $beneficjent = '';
      if ($row['announcer']==1 ){ // upowazniony
      
      
        
      }else if ($row['announcer']==1 ){ // instytucja
        
      }
      
        $beneficjent .= $row['name'].' '.$row['surname'];
        $beneficjent .= '<br>ul. '.$row['adress'];
        $beneficjent .= '<br>'.$row['post'].' '.$row['city'];
        $bank = $row['bank_name'];
        $konto = format_konto($row['account_number']);
        
        $powod_wyplaty = 'odszkodowanie';
        
    
    
         $status = 'likwidowana';
      
         $q   = "SELECT *,
              (SELECT nazwa FROM coris_signal_ryzyko_operat WHERE coris_signal_ryzyko_operat.ID=coris_assistance_cases_claims_pay_position.ID_operat  ) As operat
         FROM coris_assistance_cases_claims_pay_position WHERE ID_claims_pay='$id' " ; 
         $mr = mysql_query($q);
         if (!$mr){echo $q.'<br>'.mysql_error();}
         
      $swiadczenia = '<table width="100%" cellpadding="5" cellspacing="0" border=1>
                <tr>
                <td width="20%" align="center"><b>¦wiadczenie</b></td>
                <td width="20%" align="center"><b>Numer umowy</b></td>
                <td width="20%" align="center"><b>Produkt</b></td>
                <td width="20%" align="center"><b>Suma</b></td>
                <td width="20%" align="center"><b>Uwagi</b></td>                
              </tr>  ';
      while ($row_rd=mysql_fetch_array($mr)){    
                $swiadczenia .= '                
                  <tr>
                    <td width="20%">'.$row_rd['operat'].'</td>
                    <td width="20%">'.$row_case['policy'].'</td>
                    <td width="20%">'.getNameproduct($row_case_ann['ryzyko_gl']).'</td>
                    <td width="20%" align="right">'.print_currency($row_rd['amount_pln']).' PLN</td>
                    <td width="20%">'.$row_rd['note'].'&nbsp;</textarea></td>
                  </tr>  ';
              }  
      $swiadczenia .= '</table>';
          
      $page_template->set('%%SIGNAL_DATA%%' , $row['date'] );
      $page_template->set('%%SIGNAL_KOD_OPERATORA%%' ,  getUserInitials($row['ID_user']));
      $page_template->set('%%SIGNAL_NR_SZKODY%%' ,  $row_case['client_ref']);
      $page_template->set('%%SIGNAL_NAZWA_UBEZPIECZONEGO%%' ,  ($row_case['paxsex'] == 'K' ? 'Pani ' : '') .($row_case['paxsex'] == 'M' ? 'Pan ' : '') .$row_case['paxname'].' '.$row_case['paxsurname']);
      $page_template->set('%%SIGNAL_NR_POLISY%%' ,  $row_case['policy']);
      $page_template->set('%%SIGNAL_AGENT%%' ,  $row_agent['nazwa']);
      $page_template->set('%%SIGNAL_ODDZIAL%%' ,  $row_agent['miasto']);
      $page_template->set('%%SIGNAL_OKRES_UBEZPIECZENIA%%' ,  $row_case['validityfrom'].' - '.$row_case['validityto']);
      $page_template->set('%%SIGNAL_DATA_ZDARZENIA%%' ,  $row_case['eventdate']);
      $page_template->set('%%SIGNAL_DATA_ZGLOSZENIA%%' , substr($row_case['date'],0,10));
      $page_template->set('%%SIGNAL_NR_SPRAWY_CORIS%%' ,  $row_case['number'].'/'.substr($row_case['year'],2,2));
      
                  
       
      $page_template->set('%%SIGNAL_WYPLATA%%' ,  print_currency($row['amount']).' PLN');
      $page_template->set('%%SIGNAL_NR_RACHUNKU%%' ,  $rachunek );      
      $page_template->set('%%SIGNAL_POWOD_WYPLATY%%' ,  $powod_wyplaty);
      $page_template->set('%%SIGNAL_ODBIORCA%%' ,  $beneficjent);

      $page_template->set('%%SIGNAL_NR_ZDARZENIA%%' ,  $row_case['client_ref']);
      $page_template->set('%%SIGNAL_STATUS%%' ,  $status);
      $page_template->set('%%SIGNAL_DATA_ZAAKCEPTOWANIA%%' ,  $row['date']);
      $page_template->set('%%SIGNAL_ZLECENIE_WYPLATY_UWAGI%%' ,  $row['note'].'&nbsp;');
      $page_template->set('%%SIGNAL_DO_WYPLATY%%' ,  print_currency($row['amount']).' PLN');
      $page_template->set('%%SIGNAL_SWIADCZENIA%%' ,  $swiadczenia );
      $page_template->set('%%SIGNAL_SPOSOB_WYPLATY_SZCZEGOLY%%' ,  $sposob_wyplaty2 );
      $page_template->set('%%SIGNAL_SPOSOB_WYPLATY%%' ,  $sposob_wyplaty );
      $page_template->set('%%SIGNAL_MIASTO_DATA%%' ,  "Warszawa, dn. ".$row_claims['announce_date'] );
  
        
      $body =  $page_template->realize();        
  
  }else if ($tryb=='uzasadnienie'){
      $body = 'TODO';
  }         
        if ($body <> null){                  
    /*  $file = html2pdf($body);          
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



function row_case_info($case_id){
  global $DBase;
  
  
  $query = "SELECT ac.case_id, ac.number, ac.year, ac.client_id, ac.type_id, ac.client_ref, ac.user_id, ac.date, ac.paxname, ac.paxsurname,ac.paxsex, ac.paxdob, ac.policy, ac.cart_number, ac.event, ac.eventdate, ac.country_id, ac.city, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.archive, ac.costless, ac.unhandled, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention,ac.attention2, DATE(ac.archive_date) AS archive_date, acd.notificationdate, acd.informer, acd.validityfrom, acd.validityto, acd.policypurchasedate, acd.policypurchaselocation, acd.policyamount, acd.policycurrency_id, acd.circumstances, acd.comments,ac.marka_model,ac.nr_rej,acd.paxphone ,acd.paxmobile,ac.adress1,ac.adress2,acd.paxaddress, acd.paxpost, acd.paxcity, acd.paxcountry, acd.paxphone, acd.paxmobile ,acd.validityfromDep,acd.validitytoDep,ac.telefon1 ,ac.telefon2,ac.status_briefcase_found,ac.liquidation
  FROM coris_assistance_cases ac, coris_assistance_cases_details acd 
  WHERE ac.case_id = '$case_id' AND  ac.case_id = acd.case_id AND ac.active = 1 ";
  
  
  $result = mysql_query($query);  
  if (!$result) {
    die ("Query Error: $query <br>".mysql_error());
  }

  if (mysql_num_rows($result) > 0 ){
      $row = mysql_fetch_array($result);
      return $row;
  }else{
    die('Case error, case_id='.$case_id);    
  }
}


function row_case_announce($case_id){
  global $DBase;
    
  $query = "SELECT * FROM coris_assistance_cases_announce  WHERE case_id = '$case_id'";    
  
  $result = mysql_query($query);  
  if (!$result) {
	  die ("Query Error: $query <br>".mysql_error());
  }

  if (mysql_num_rows($result) > 0 ){
  		$row = mysql_fetch_array($result);
  		return $row;
  }else{
  		die('Case error, case_id='.$case_id);    
  }
}

function row_claims($id){
  global $DBase;
  
  
  $query = "SELECT * FROM coris_assistance_cases_claims   WHERE ID = '$id'";    
  
  $result = mysql_query($query);  
  if (!$result) {
    die ("Query Error: $query <br>".mysql_error());
  }

  if (mysql_num_rows($result) > 0 ){
      $row = mysql_fetch_array($result);
      return $row;
  }else{
    die('Claims error, id='.$id);    
  }
}




?>
