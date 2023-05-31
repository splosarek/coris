<?php
ini_set('display_errors',0);
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$zid = getValue('zid');
$case_id = getValue('case_id');
$result = array();

include('../lib/lib_skok.php');
$skok_case = new skokCase($case_id);
		
if ($zid>0){
		$query = "SELECT *
		 FROM coris_skok_lista_swiadczen  
		WHERE ID ='$zid' ";
		
		$mysql_result = mysql_query($query);
//echo $query;
		$row=mysql_fetch_array($mysql_result);
		
		$limit2 = 0.0;
		$waluta2 = 'PLN';
		
		if ($row['ID_podlimit'] > 0 ){
			
			$query2 = "SELECT *
				 FROM coris_skok_lista_swiadczen  
				 WHERE ID ='".$row['ID_podlimit']."' ";		
			$mysql_result2 = mysql_query($query2);
			$row2=mysql_fetch_array($mysql_result2);	
			
			$limit2 = $row2['kwota'];
			$waluta2 = $row2['currency_id'];
			if ($waluta2 != 'PLN')
				$limit2 = $limit2*$skok_case->getKursWaluty($waluta2);		
		}
		
		$limit = $row['kwota'];
		$waluta = $row['currency_id'];
		if ($waluta != 'PLN')
			$limit = $limit*$skok_case->getKursWaluty($waluta);
								
		$aktualne_uzycie = sprawdzAktualneUzycie($case_id,$zid);
		
		
		
		$uzycie_globalne = $aktualne_uzycie['suma_globalna'];
		$uzycie = $aktualne_uzycie['suma'];  // dla tego swiadczenia
		
		
		$limit_globalny = $limit;
		if ($limit==0){
			$limit_globalny=$limit2;
		}
			
		$kwota_wolna_globalna = $limit_globalny - $uzycie_globalne;
		
		$kwota_wolna = $limit_globalny - $uzycie;
		
		if ($kwota_wolna_globalna > $kwota_wolna)
			$kwota_do = $kwota_wolna;
		else	
			$kwota_do = $kwota_wolna_globalna;
			
		$rezerwa_globalna = 	$skok_case->getRezerwaGlobalna();
		
		$result = array('suma'=> print_currency($kwota_do), 'rezerwa' => print_currency($rezerwa_globalna['rezerwa']));			
			
}else{
	$result = array('suma' => 0,'rezerwa' => 'PLN');
}


echo json_encode($result);


function sprawdzAktualneUzycie($case_id,$zid){
		global $skok_case;
	$query = "SELECT * FROM  coris_skok_rezerwy  WHERE case_id = '$case_id' ";
	$mysql_result = mysql_query($query);
	$suma_globalna=0.0;
	$suma = 0.0;
	while ($row=mysql_fetch_array($mysql_result)) {
		$swiadczenie = $row['ID_swiadczenie'];
		$rezerwa = $row['rezerwa'];
		$waluta = $row['currency_id'];
		if ($waluta!= 'PLN')
			$rezerwa = $rezerwa*$skok_case->getKursWaluty($waluta);
			
		if ($swiadczenie==$zid){
			
			$suma += $rezerwa;	
		}
		$suma_globalna += $rezerwa;		
	}
	
	return array('suma_globalna' => $suma_globalna,'suma'=>$suma);
}



?>