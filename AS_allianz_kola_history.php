<?php 
require_once('include/include.php'); 
include_once('include/strona.php'); 
include_once('include/send_list.inc.php');

html_start();

$kolo_id = intval(getValue('id'));



if ($kolo_id>0){
	echo viewKolo($kolo_id);
}
		
function viewKolo($kolo_id){
	
		$result = '';
		
	 $query = "SELECT coris_allianz_kola.* ,coris_allianz_ubezpieczenia.kolo_nr_polisy,
  coris_allianz_ubezpieczenia.suma_ubezpieczenia,
  coris_allianz_ubezpieczenia.franszyza_rodzaj,
  coris_allianz_ubezpieczenia.franszyza_kwota
   FROM coris_allianz_kola LEFT JOIN coris_allianz_ubezpieczenia ON  coris_allianz_kola.ID=coris_allianz_ubezpieczenia.ID_kolo AND coris_allianz_ubezpieczenia.ID_umowa=1
   WHERE  coris_allianz_kola.ID='$kolo_id'";   	  	
  	$mysql_result = mysql_query($query);
  	if (mysql_num_rows($mysql_result) == 0) return 'BRAK KO£A ID:'.$kolo_id;
  	$row = mysql_fetch_array($mysql_result);
  	
  		 $result .= '<div align="center"><b>Historia spraw / p³atno¶ci ko³a <br> '.$row['nazwa'].' ('.$kolo_id.')</b></div> ';

  		 $query = " SELECT caa.*, cac.paxname, cac.paxsurname,cac.number,cac.year,cac.client_ref   
  		 		FROM coris_allianz_announce caa,coris_assistance_cases cac 
  		 		WHERE caa.ID_kolo = '$kolo_id' AND cac.case_id  = caa.case_id
				ORDER BY caa.ID_kolo
  		 		" ; 
  		$mysql_result = mysql_query($query);  	
  		$result .= '<br><br> 
  		<b>Sprawy:</b><br><table cellpadding="5" cellspacing="1">';
  		$result .= '<tr bgcolor="#999999" >';
					$result .= '
					<td align="center"><b>Nr Sprawy</b></td>
					<td align="center"><b>Nr Allianz</b></td>
					<td align="center"><b>Poszkodowany</b></td>
					<td align="center"><b>Miejscowo¶æ</b></td>
					<td align="center"><b>Roszczenie</b></td>															
					</tr>';
  		while ( $rowa = mysql_fetch_array($mysql_result)){
					
					
					$result .= '<tr bgcolor="#999999" >';
						$result .= '<td>'.$rowa['number'].'/'.substr($rowa['year'], 2).'</td>';
						$result .= '<td>'.$rowa['client_ref'].'&nbsp;</td>';
						$result .= '<td>'.$rowa['paxsurname'].' '.$rowa['paxname'].'</td>';
						$result .= '<td>'.$rowa['szk_lok_miejscowosc'].'</td>';
						$result .= '<td align="right">'.$rowa['kwota_roszczenia'].'</td>';
					$result .= 	'</tr>';	
	  }
		$result .= '</table><br><br>';			

		
		
		
		$query = " SELECT caa.*, cac.paxname, cac.paxsurname,cac.number,cac.year,cac.client_ref,
				cacd.kwota_roszczenia ,cacd.kwota_rws,cacd.wyplata_zaakceptowana,cacd.status ,cacd.refundacja_kwota 

				FROM coris_allianz_announce caa,coris_assistance_cases cac, coris_allianz_claims_details cacd,
				coris_allianz_claims cacl
				 WHERE caa.ID_kolo = '$kolo_id' 
				 		AND cac.case_id  = caa.case_id 
				 		AND cac.case_id = cacl.ID_case 
				 		AND cacl.ID = cacd.ID_claims 
				 ORDER BY cacd.ID
				 " ; 
  		$mysql_result = mysql_query($query);

  		
  		$status_list = array('','Nowe','W trakcie obslugi','Decyzja pozytywna','Dezycja odmowna');
  		
  		$result .= '<br>
  		<b>Roszczenia:</b><br><table cellpadding="5" cellspacing="1">';
  		$result .= '
  			<tr bgcolor="#999999" >';
					$result .= '
					<td align="center"><b>Nr Sprawy</b></td>
					<td align="center"><b>Nr Allianz</b></td>
					<td align="center"><b>Poszkodowany</b></td>					
					<td align="center"><b>Decyzja</b></td>															
					<td align="center"><b>Roszczenie</b></td>															
					<td align="center"><b>RWS</b></td>															
					<td align="center"><b>Wyp³ata</b></td>															
					<td align="center"><b>Refundacja</b></td>															
			</tr>';
			$suma = 0.0;					
		$suma_szacowania = 0.0;					
  		while ( $rowa = mysql_fetch_array($mysql_result)){
					$result .= '<tr bgcolor="#999999" >';
						$result .= '<td>'.$rowa['number'].'/'.substr($rowa['year'], 2).'</td>';
						$result .= '<td>'.$rowa['client_ref'].'&nbsp;</td>';
						$result .= '<td>'.$rowa['paxname'].' '.$rowa['paxsurname'].'</td>';
						$result .= '<td>'.$status_list[$rowa['status']].' &nbsp;</td>';
						$result .= '<td align="right">'.$rowa['kwota_roszczenia'].'</td>';
						$result .= '<td align="right">'.$rowa['kwota_rws'].'</td>';
						$result .= '<td align="right">'.$rowa['wyplata_zaakceptowana'].'</td>';
						$result .= '<td align="right">'.$rowa['refundacja_kwota'].'</td>';
					$result .= 	'</tr>';	
						$suma += $rowa['wyplata_zaakceptowana'];
					$suma_szacowania += $rowa['refundacja_kwota'];
	  }
	  
	   $result .= '<tr bgcolor="#777777" >';
						$result .= '<td colspan="6"><b>SUMA</b></td>';								
						$result .= '<td align="right"><b>'.number_format($suma,2,',',' ').'</b></td>';
						$result .= '<td align="right"><b>'.number_format($suma_szacowania,2,',',' ').'</b></td>';
						
					$result .= 	'</tr>';
		$result .= '</table><br><br>';			

		
		$query = " SELECT * FROM coris_allianz_wyplaty 
				 WHERE ID_kolo = '$kolo_id' 
				 		AND  in_system=0 AND  `ignore`=0
				 ORDER BY ID " ; 
  		$mysql_result = mysql_query($query);
  	
  		
  		$result .= '<hr><br>
  		<b>Wyp³aty historyczne na podstawie raportu z MS Excella (nie ujête w systemie):</b><br><table cellpadding="5" cellspacing="1">';
  		$result .= '
  			<tr bgcolor="#999999" >';
					$result .= '
					<td align="center"><b>Nr Sprawy</b></td>
					<td align="center"><b>Nr Allianz</b></td>
					<td align="center"><b>Poszkodowany</b></td>					
					<td align="center"><b>Wyp³ata</b></td>															
					<td align="center"><b>Franszyza</b></td>															
					<td align="center"><b>Faktyczna wyp³ata</b></td>															
					<td align="center"><b>Refundacja</b></td>															
					<td align="center"><b>Refundacje innych spraw</b></td>															
					<td align="center"><b>Info</b></td>															
			</tr>';
		$suma = 0.0;					
		$suma_szacowania = 0.0;					
		$suma_szacowania2 = 0.0;					
  		while ( $rowa = mysql_fetch_array($mysql_result)){
					$result .= '<tr bgcolor="#999999" >';
						$result .= '<td>'.$rowa['nr_sprawy'].'</td>';
						$result .= '<td>'.$rowa['nr_sprawy_allianz'].'&nbsp;</td>';
						$result .= '<td>'.$rowa['poszkodowany'].'</td>';						
						$result .= '<td align="right">'.$rowa['wyplata'].'</td>';
						$result .= '<td align="right">'.$rowa['franszyza'].'</td>';
						$result .= '<td align="right">'.$rowa['faktyczna_wyplata'].'</td>';
						$result .= '<td align="right">'.$rowa['koszty_szacowania'].'</td>';
						$result .= '<td align="right">'.$rowa['inne_koszty_szacowania'].'</td>';
						$result .= '<td align="right">'.$rowa['wyplata_info'].'</td>';
					$result .= 	'</tr>';
					$suma += $rowa['faktyczna_wyplata'];
					$suma_szacowania += $rowa['koszty_szacowania'];
					$suma_szacowania2 += $rowa['inne_koszty_szacowania'];
	  }
	  
	  			$result .= '<tr bgcolor="#777777" >';
						$result .= '<td colspan="5"><b>SUMA</b></td>';								
						$result .= '<td align="right"><b>'.number_format($suma,2,',',' ').'</b></td>';
						$result .= '<td align="right"><b>'.number_format($suma_szacowania,2,',',' ').'</b></td>';
						$result .= '<td align="right"><b>'.number_format($suma_szacowania2,2,',',' ').'</b></td>';
						$result .= '<td align="right">&nbsp;</td>';
					$result .= 	'</tr>';
					
		$result .= '</table><br><br>';			
		
		
		$suma_h = $suma;
	
		$query = " SELECT caa.*, cac.paxname, cac.paxsurname,cac.number,cac.year,cac.client_ref,
				cacd.kwota_roszczenia ,cacd.kwota_rws,cacd.wyplata_zaakceptowana,cacd.status ,cacd.refundacja_kwota 
				
				FROM coris_allianz_announce caa,coris_assistance_cases cac, coris_allianz_claims_details cacd,
				coris_allianz_claims cacl,coris_allianz_payment cap
				 WHERE caa.ID_kolo = '$kolo_id' 
				 		AND cac.case_id  = caa.case_id 
				 		AND cac.case_id = cacl.ID_case 
				 		AND cacl.ID = cacd.ID_claims 
				 		AND cap.ID_claims_details = cacd.ID
				 ORDER BY cacd.ID
				 " ; 
  		$mysql_result = mysql_query($query);

  		
  		$status_list = array('','Nowe','W trakcie obslugi','Decyzja pozytywna','Dezycja odmowna');
  		
  		$result .= '<br>
  		<b>Wyp³aty w systemie:</b><br><table cellpadding="5" cellspacing="1">';
  		$result .= '
  			<tr bgcolor="#999999" >';
					$result .= '
					<td align="center"><b>Nr Sprawy</b></td>
					<td align="center"><b>Nr Allianz</b></td>
					<td align="center"><b>Poszkodowany</b></td>					
					<td align="center"><b>Decyzja</b></td>															
					<td align="center"><b>Roszczenie</b></td>															
					<td align="center"><b>RWS</b></td>															
					<td align="center"><b>Wyp³ata</b></td>															
					<td align="center"><b>Refundacja</b></td>															
			</tr>';
			$suma = 0.0;					
		$suma_szacowania = 0.0;							
  		while ( $rowa = mysql_fetch_array($mysql_result)){
					$result .= '<tr bgcolor="#999999" >';
						$result .= '<td>'.$rowa['number'].'/'.substr($rowa['year'], 2).'</td>';
						$result .= '<td>'.$rowa['client_ref'].'&nbsp;</td>';
						$result .= '<td>'.$rowa['paxname'].' '.$rowa['paxsurname'].'</td>';
						$result .= '<td>'.$status_list[$rowa['status']].' &nbsp;</td>';
						$result .= '<td align="right">'.$rowa['kwota_roszczenia'].'</td>';
						$result .= '<td align="right">'.$rowa['kwota_rws'].'</td>';
						$result .= '<td align="right">'.$rowa['wyplata_zaakceptowana'].'</td>';
						$result .= '<td align="right">'.$rowa['refundacja_kwota'].'</td>';
					$result .= 	'</tr>';	
					$suma += $rowa['wyplata_zaakceptowana'];
					$suma_szacowania += $rowa['refundacja_kwota'];
	  }
	  $result .= '<tr bgcolor="#777777" >';
						$result .= '<td colspan="6"><b>SUMA</b></td>';								
						$result .= '<td align="right"><b>'.number_format($suma,2,',',' ').'</b></td>';
						$result .= '<td align="right"><b>'.number_format($suma_szacowania,2,',',' ').'</b></td>';
						
					$result .= 	'</tr>';
		$result .= '</table><br><br>';					
		
				
		$suma_c = $suma;
		

		
		$result .= '<hr>
  		<b>Podsumowanie po wszystkich wyp³atach:</b><br><table cellpadding="5" cellspacing="1">';
  		$result .= '
  			<tr bgcolor="#999999" >';
					$result .= '
					<td align="center"><b>Suma ubezpieczenia</b></td>
					<td align="center"><b>Wyp³aty</b></td>
					<td align="center"><b>Dostêpna suma ubezpieczenia</b></td>					
			</tr>';
					
		$ubezpieczenie = kolo_ubezpieczenie($kolo_id);
		
		$result .= '<tr bgcolor="#999999" >';
						
						$result .= '<td align="right"><b>'.number_format($ubezpieczenie['suma_ubezpieczenia'],2,',',' ').'</b></td>';						
						$result .= '<td align="right"><b>'.number_format($suma_h + $suma_c,2,',',' ').'</b></td>';
						$result .= '<td align="right"><b>'.number_format(($ubezpieczenie['suma_ubezpieczenia']-$suma_h - $suma_c),2,',',' ').'</b></td>';
						
					$result .= 	'</tr>';	
		$result .= '</table><br><br>';					

		
		return $result;
}

function kolo_ubezpieczenie($kolo_id){
	 $query = "SELECT coris_allianz_kola.* ,coris_allianz_ubezpieczenia.kolo_nr_polisy,
  coris_allianz_ubezpieczenia.suma_ubezpieczenia,
  coris_allianz_ubezpieczenia.franszyza_rodzaj,
  coris_allianz_ubezpieczenia.franszyza_kwota
   FROM coris_allianz_kola LEFT JOIN coris_allianz_ubezpieczenia ON  coris_allianz_kola.ID=coris_allianz_ubezpieczenia.ID_kolo AND coris_allianz_ubezpieczenia.ID_umowa=1
   WHERE  coris_allianz_kola.ID='$kolo_id'";   	  	
  	$mysql_result = mysql_query($query);

  	$row = mysql_fetch_array($mysql_result);
	return $row;	
}
html_stop2();

?>