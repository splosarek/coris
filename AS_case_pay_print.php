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
      
      $query = "SELECT * FROM coris_assistance_cases_pay  WHERE ID='$id'";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)==0) {echo "Error: ID=$id claims pay not exists!";exit();}
      
      $row = mysql_fetch_array($mysql_result);
      $case_id=$row['case_id'];
      $inv_id = $row['ID_invoice_out'];
      
      $row_case = row_case_info($case_id);
      $row_inv = row_inv($row['ID_invoice_out']);
      $row_case_ann = row_case_announce($case_id);
      $row_agent = row_agent($row_case_ann['biurop_id']);
      $row_account = row_account($row_inv['account_id']);
       
      $bank   = $row_account['name'];
      $konto = format_konto($row_account['account']);
            
      $beneficjent = "CORIS Varsovie Sp. z o. o.";
      $beneficjent .= '<br>ul. Piastów ¦l±skich 65';
      $beneficjent .= '<br>01-494 Warszawa';
       $rachunek = 'A'.$row_inv['invoice_out_no'].'/'.$row_inv['invoice_out_year'];      
        
   if ($tryb=='operat'){
        
      $page_template->load_template('../fax/templates/claims_operat.html');
      
      
      
      
      $lista='';
      
      $ql = "SELECT * FROM coris_assistance_cases_pay  WHERE case_id='$case_id' AND ID<$id";
      $mr = mysql_query($ql);
      while($rr = mysql_fetch_array($mr))
      		$lista .= '<tr><td align="left">'.substr($rr['date'],0,10).'</td><td  align="right">'.print_currency($rr['amount']).'</td><td  align="right">'.$rr['currency_id'].'</td></tr>';
      		
    //  $poprzednie_wyplaty = '<table border="0" width="450"><tr><td>Data wyp³aty</td><td align="left">Kwota w walucie</td> <td align="left">Waluta</td> </tr>'.$lista.'</table>';
	  $poprzednie_wyplaty = '<table border="0" width="370">
       <tr><td width="40%">Data wyp³aty</td><td align="left" width="30%">Kwota w walucie</td> <td align="right" width="30%">Waluta</td> </tr>'.$lista.'</table>';
	 
      
      $sposob_wyplaty = $forma_wyplaty[1];
                
        $q = "SELECT rc.numer ,rc.nazwa, rc.nazwa_druk, (SELECT suma FROM coris_assistance_cases_reserve  WHERE coris_assistance_cases_reserve.case_id='$case_id' AND  coris_assistance_cases_reserve.ID_ryzyko = rc.ID ) As suma,rc_vg.kolejnosc, rc_vg.ID FROM coris_signal_ryzyka_czastkowe rc,coris_signal_ryzyka_czastkowe_vs_glowne rc_vg  WHERE rc.ID=rc_vg.ID_ryzko_czastkowe AND rc_vg.ID_ryzyko_glowne ='".$row_case_ann['ryzyko_gl']."'
        UNION
        SELECT rc.numer ,rc.nazwa, rc.nazwa_druk, (SELECT suma FROM coris_assistance_cases_reserve  WHERE coris_assistance_cases_reserve.case_id='$case_id' AND  coris_assistance_cases_reserve.ID_ryzyko = rc.ID) As suma,100,1000 FROM coris_signal_ryzyka_czastkowe rc WHERE rc.numer='550'
        ORDER BY kolejnosc,ID";
        
        $mr = mysql_query($q);
        if (!$mr){echo $q.'<br>'.mysql_error();}
        $sumy_ubezpiecz  = '<table border="0" width="380">';
        
        while ($r = mysql_fetch_array($mr))
          $sumy_ubezpiecz .= '<tr><td align="left" wifth="80">'.$r['numer'].'</td><td width="190" align="left">'.($r['nazwa_druk']  != ''?  $r['nazwa_druk'] : $r['nazwa'] ).'</td> <td align="right" width="110">'.print_currency($r['suma']).' PLN</td> </tr>';                  
        
      $sumy_ubezpiecz .= '</table>';
        

        
        
      $page_template->set('%%SIGNAL_DATA%%' , substr($row['date'],0,10) );
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
      $page_template->set('%%SIGNAL_NR_RACHUNKU%%' ,  $rachunek );
      $page_template->set('%%SIGNAL_SPOSOB_WYPLATY%%' ,  $sposob_wyplaty);
      $page_template->set('%%SIGNAL_BENEFICJENT%%' ,  $beneficjent);
      $page_template->set('%%SIGNAL_BANK%%' ,  $bank);
      $page_template->set('%%SIGNAL_KONTO%%' ,  $konto);      
        
      $body =  $page_template->realize();        
    
  }else if ($tryb=='zlecenie'){
      $page_template->load_template('../fax/templates/claims_zlecenie.html');
    
    
      $sposob_wyplaty = $forma_wyplaty[1];
      $sposob_wyplaty2 = $sposob_wyplaty.'<br>'. $bank .'<br>'. $konto;
        $powod_wyplaty = 'odszkodowanie';
        
         $status = 'likwidowana';
      
         $q   = "SELECT nazwa As operat FROM coris_signal_ryzyko_operat WHERE coris_signal_ryzyko_operat.ID='".$row['ID_opis_rachunku']."'" ; 
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
      $row_rd=mysql_fetch_array($mr);
                $swiadczenia .= '                
                  <tr>
                    <td width="20%">'.$row_rd['operat'].'</td>
                    <td width="20%">'.$row_case['policy'].'</td>
                    <td width="20%">'.getNameproduct($row_case_ann['ryzyko_gl']).'</td>
                    <td width="20%" align="right">'.print_currency($row['amount']).' PLN</td>
                    <td width="20%">&nbsp;</td>
                  </tr>  ';
                
      $swiadczenia .= '</table>';
          
      $page_template->set('%%SIGNAL_DATA%%' , substr($row['date'],0,10) );
      $page_template->set('%%SIGNAL_KOD_OPERATORA%%' ,  getUserInitials($row_inv['user_id']));
      $page_template->set('%%SIGNAL_NR_SZKODY%%' ,  $row_case['client_ref']);
      $page_template->set('%%SIGNAL_NAZWA_UBEZPIECZONEGO%%' , ($row_case['paxsex'] == 'K' ? 'Pani ' : '') .($row_case['paxsex'] == 'M' ? 'Pan ' : '') . $row_case['paxname'].' '.$row_case['paxsurname']);
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
      $page_template->set('%%SIGNAL_DATA_ZAAKCEPTOWANIA%%' ,  $row_inv['invoice_out_date']);
      $page_template->set('%%SIGNAL_ZLECENIE_WYPLATY_UWAGI%%' ,  'Nr faktury: <b>'.$rachunek.'</b>');
      $page_template->set('%%SIGNAL_DO_WYPLATY%%' ,  print_currency($row['amount']).' PLN');
      $page_template->set('%%SIGNAL_SWIADCZENIA%%' ,  $swiadczenia );
      $page_template->set('%%SIGNAL_SPOSOB_WYPLATY_SZCZEGOLY%%' ,  $sposob_wyplaty2 );
      $page_template->set('%%SIGNAL_SPOSOB_WYPLATY%%' ,  $sposob_wyplaty );
      $page_template->set('%%SIGNAL_MIASTO_DATA%%' ,  "Warszawa, dn. ".substr($row['date'],0,10) );
  
        
      $body =  $page_template->realize();        
  
  }else if ($tryb=='uzasadnienie'){
      $body = 'TODO';
  }
         
        if ($body <> null){
          
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

function row_inv($id){
  global $DBase;
  
  
  $query = "SELECT * FROM coris_finances_invoices_out  WHERE invoice_out_id  = '$id'";    
  
  $result = mysql_query($query);  
  if (!$result) {
    die ("Query Error: $query <br>".mysql_error());
  }

  if (mysql_num_rows($result) > 0 ){
      $row = mysql_fetch_array($result);
      return $row;
  }else{
    die('inv error, id='.$id);    
  }
}


function row_account($id){
  global $DBase;
  
  
  $query = "SELECT * FROM coris_accounts  WHERE account_id = '$id'";    
  
  $result = mysql_query($query);  
  if (!$result) {
    die ("Query Error: $query <br>".mysql_error());
  }

  if (mysql_num_rows($result) > 0 ){
      $row = mysql_fetch_array($result);
      return $row;
  }else{
    die('account error, id='.$id);    
  }
}

/*
function format_konto($txt){
	
  $txt = str_replace(' ','',$txt);
  $tab = array();
  $start=0;
  if ( !(substr($txt,0,2) > 0 ) ){
  	$start=2;  	
  	$tab[] = substr($txt,0,2);
  	$txt = substr($txt,3,strlen($txt)-2);
  }
  for ($i=0;$i<strlen($txt);$i++){
    if ($i==0)
      $tab[] = substr($txt,0,2);
    else{
      $tab[] = substr($txt,($i-1)*4+2,4);
    }
  }
  
  return implode(' ',$tab);
  
}
*/
?>
