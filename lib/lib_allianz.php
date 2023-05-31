<?php
//include(dirname(__FILE__).'\Allianz\AllianzClaim.php');
//include(dirname(__FILE__).'\Allianz\AllianzClaimDetails.php');
include(dirname(__FILE__).'/Allianz/AllianzClaim.php');
include(dirname(__FILE__).'/Allianz/AllianzClaimDetails.php');
include(dirname(__FILE__).'/Allianz/AllianzDecision.php');


Class AllianzCase{

	static $_admins = array(26, 39, 256,76,4);
	
/*
 Dominika Dwojewska - 275
Wioleta Jab³oñska - 274
Krzysztof Serafin - 277
Jakub Szarubka - 276
Hanna Ryshkevich - 218
Justyna Grzelak - 26
Dorota Dziuba³ko - 256
Micha³ Skrzypiec - 39
 */	
	static $_claim_handler_users = array(  275,274,277,276,218,26,256,39  );
	
	static function getUmowa(){
		return 1;		
	}
	
	static function getAdmins(){
		return 	self::$_admins;	
	}
	
	static function isAdmin($user =0){
			if ($user == 0)
				$user = $_SESSION['user_id'];
		return 	in_array($user, self::$_admins);	
	}
	
	
	static function getCaseInfo($case_id){	
		$query2 = "SELECT * FROM coris_allianz_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		if (mysql_numrows($mysql_result2) == 0){  	
			return false; 
		}else{		
			$row_case_ann = mysql_fetch_array($mysql_result2);			
			return $row_case_ann;
		}
	}

	
	static function getKoloDostepnaSumaUbezpieczenia($kolo_id){

		if ($kolo_id > 0 ){
				$query1 = "SELECT SUM(faktyczna_wyplata) FROM coris_allianz_wyplaty  WHERE ID_kolo = '$kolo_id' AND  in_system=0 AND `ignore`=0";
				
				$query2 = "SELECT SUM(cap.amount) FROM coris_allianz_payment cap,coris_allianz_announce caa, 
															coris_allianz_claims_details cacd,coris_allianz_claims cacl 
							 WHERE caa.ID_kolo = '$kolo_id' 		
							 	
						 		AND caa.case_id = cacl.ID_case 
						 		AND cacl.ID = cacd.ID_claims
						 		AND cap.ID_claims_details =  cacd.ID					
						";		
				//echo $query1;
				//echo '<br>'.$query2;
				$mysql_result = mysql_query($query1);
				if (!$mysql_result ) {echo "<br>QE $query1<br>".mysql_error(); }
				
				$row = mysql_fetch_array($mysql_result);		
				$suma1 = $row[0] > 0 ? $row[0] : 0 ;
				
				$mysql_result = mysql_query($query2);
				$row = mysql_fetch_array($mysql_result);		
				$suma2 = $row[0]  > 0 ? $row[0] : 0;
				
				$wyplaty =  Finance::ev_round($suma1+$suma2,2);
				
				$dane_ub = AllianzCase::ubezpieczenie($kolo_id);		
				//$suma_ubezpieczenia= print_currency($dane_ub['suma_ubezpieczenia']) ;
				$suma_ubezpieczenia= $dane_ub['suma_ubezpieczenia'] ;
				
				$status = 'OK';
				$status =  ($suma_ubezpieczenia-$wyplaty) <  $suma_ubezpieczenia*0.2 ? 'warning' : $status;
				$status =  ($suma_ubezpieczenia-$wyplaty) <= 0  ? 'error' : $status;
								
				return array('suma_ubezpieczenia' => $suma_ubezpieczenia,'wyplaty' => $wyplaty,
						'dostepna_suma_ubezpeczenia' =>  Finance::ev_round($suma_ubezpieczenia-$wyplaty,2), 				
						'status' => $status );
		}
	}

	
	static function getKoloInfo($kolo_id){	
		$query2 = "SELECT * FROM coris_allianz_kola  WHERE ID = '$kolo_id'";
		$mysql_result2 = mysql_query($query2);
		if (mysql_numrows($mysql_result2) == 0){  	
			return false; 
		}else{		
			$row = mysql_fetch_array($mysql_result2);			
			return $row;
		}
	}
	
	static function getKolaLowieckie($name,$def=0,$tryb=0,$option='',$header_info=1){	

			if ($tryb){ // wyswietlamy jedna wybrana pozycje w trybie readonly 									
					$query2 = "SELECT *  FROM coris_allianz_kola   WHERE ID='$def' ORDER BY miejscowosc ,nazwa";
					
					$mysql_result = mysql_query($query2);
					$row = mysql_fetch_array($mysql_result);						
					$result = '<select name="'.$name.'" style="font-size: 8pt;" disabled>';					
						$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
					 $result .= '</select>';
					 return $result;
						
				
			}else{
				if ($def == 0 ){ // jesli form rejestracji
					$query2 = "SELECT *  FROM coris_allianz_kola   WHERE active=1 ORDER BY miejscowosc ,nazwa";
				}else{
					$query2 = "SELECT *  FROM coris_allianz_kola   ORDER BY miejscowosc ,nazwa";
				}
				$mysql_result2 = mysql_query($query2);
		
			
				$result = '';
				if ($header_info)
					$result .= 'Ko³o [miejscowo¶æ - nazwa]<br>';
				$result .= '<select name="'.$name.'" id="'.$name.'" class="required1" '.$option.'>';
				$result .= '<option value=\'0\'> ... </option>';
				while ($row2=mysql_fetch_array($mysql_result2)){
						if ($row2['ID'] == $def)
							$result .= '<option value=\''.$row2['ID'].'\' SELECTED>'.$row2['miejscowosc'].' - '.$row2['nazwa'].'</option>';
						else	
							$result .= '<option value=\''.$row2['ID'].'\'>'.$row2['miejscowosc'].' - '.$row2['nazwa'].'</option>';
				}
				$result .= '<option value=\'new\'>  ----------  Dodaj nowe ko³o  ---------- </option>';
	   			$result .= '</select>';
	   			return $result;
			}
	}
	
	static function getSzacujacyRegister($sel=0,$tryb=0){		
		//$result = '<select name="szacujacy_id" id="szacujacy_id" class="required1" onChange="getSzacujacy(this.value)" >';//onChange="getSzacujacy(this.value)"
		$result = '<select name="szacujacy_id[]" id="szacujacy_id[]" class="required1"  size="6"  multiple="multiple" onChange="getSzacujacy(this.value)">';
			$result .= '<option value=\'0\'> --- wybierz kolo --- </option>';						
   			$result .= '</select>';
   			return $result;
	}
	static function getSzacujacy($case_id,$id_kolo,$sel=0,$tryb=0,$option=''){	
		
		if ($tryb){
			//$query2 = "SELECT *  FROM coris_allianz_kola_szacujacy   WHERE ID_umowa ='1' AND ID_kolo=$id_kolo AND ID='$sel' ORDER BY imie_nazwisko";
			$query2 = "SELECT *  FROM coris_allianz_announce_szacujacy  WHERE case_id ='$case_id' ORDER BY imie_nazwisko";
			//echo $query2;
		$mysql_result2 = mysql_query($query2);
			$result = '<select name="szacujacy_id[]" id="szacujacy_id[]" class="disabled" disabled multiple="multiple">';
			
			while ($row2=mysql_fetch_array($mysql_result2)){					
						$result .= '<option value=\''.$row2['ID'].'\' '.($row2['ID'] == $sel ? 'SELECTED' : '').'>'.$row2['imie_nazwisko'].', '.$row2['telefon'].'</option>';
					
			}
			
   			$result .= '</select>';
   			return $result;
					
		}else{
			
			$lista_szacujacych = self::listaSzacujacychWSprawie($id_kolo,$case_id);
		//
			$result = '<select name="szacujacy_id[]" size="6" id="szacujacy_id[]" class="required1" '.$option.' multiple="multiple">';
			foreach ($lista_szacujacych As $poz){
					$result .= '<option value="'.$poz['ID'].'"  '.($poz['selected']==1?' selected ' : '' ).'>'.$poz['nazwa'].', '.$poz['telefon'].'</option>';								
			}
			$result .= '<option value=\'new\'>--  Nowy szacuj±cy --</option>';
   			$result .= '</select>';
   			return $result;
		}
	}
	
	static function getWojewodztwa($name,$def=0,$tryb=0,$option=''){	
		
		
		if ($tryb){
			$query2 = "SELECT *  FROM _powiaty  WHERE NAZDOD='województwo' AND WOJ='$def'";
			$mysql_result2 = mysql_query($query2);
			$result = '<select name="'.$name.'" id="'.$name.'" class="desabled" disabled '.$option.'>';			
			while ($row2=mysql_fetch_array($mysql_result2)){					
						$result .= '<option value=\''.$row2['WOJ'].'\' '.($row2['WOJ'] == $def ? 'SELECTED' : '').'>'.$row2['NAZWA'].'</option>';					
			}
   			$result .= '</select>';
   			return $result;
			
		}else{
			$query2 = "SELECT *  FROM _powiaty  WHERE NAZDOD='województwo' ORDER BY NAZWA";
			$mysql_result2 = mysql_query($query2);
			$result = '<select name="'.$name.'" id="'.$name.'" class="required1" '.$option.'>';
			$result .= '<option value=\'0\'> ... </option>';
			while ($row2=mysql_fetch_array($mysql_result2)){					
						$result .= '<option value=\''.$row2['WOJ'].'\' '.($row2['WOJ'] == $def ? 'SELECTED' : '').'>'.$row2['NAZWA'].'</option>';
					
			}
   			$result .= '</select>';
   			return $result;
		}
	}
	
	
	static function getPowiatyRegister($sel=0,$tryb=0){	
		$query2 = "SELECT *  FROM _powiaty  WHERE GMI is null AND   RODZ is null AND WOJ=2 AND POW is not null ORDER BY NAZWA";
		$mysql_result2 = mysql_query($query2);
			$result = '<select name="pow_id" id="pow_id" class="required1" onChange="getGminy(this.value);">';
			$result .= '<option value=\'0\'> -- wybierz wojewodztwo --</option>';			
   			$result .= '</select>';
   			return $result;	
	}	
	

	static function getPowiaty($name,$woj_id,$def=0,$tryb=0,$option=''){	
			if ($tryb){ // wyswietlamy jedna wybrana pozycje w trybie readonly					 									
					$query2 = "SELECT *  FROM _powiaty   WHERE GMI is null AND   RODZ is null AND WOJ='$woj_id' AND POW ='$def'";					
					$mysql_result = mysql_query($query2);
					$row = mysql_fetch_array($mysql_result);						
					$result = '<select name="'.$name.'"  id="'.$name.'"  class="disabled" disabled>';					
						$result .= '<option value="'. $row['POW'] .'">'.$row['NAZWA'].'</option>';
					 $result .= '</select>';
					 return $result;
			}else{
				$query2 = "SELECT *  FROM _powiaty  WHERE GMI is null AND   RODZ is null AND WOJ='$woj_id' AND POW is not null ORDER BY NAZWA";				
				$mysql_result2 = mysql_query($query2);
				$result = '<select name="'.$name.'"  id="'.$name.'"  class="required1" '.$option.'>';
				if (mysql_num_rows($mysql_result2) > 0 ){
					$result .= '<option value=\'0\'> ... </option>';
					while ($row2=mysql_fetch_array($mysql_result2)){
							$result .= '<option value=\''.$row2['POW'].'\' '.($row2['POW'] == $def ? 'SELECTED' : '').'>'.$row2['NAZWA'].'</option>';
					}
				}else{
					$result .= '<option value=\'0\'> -- wybierz wojewodztwo --</option>';
				}	
	   			$result .= '</select>';
	   			return $result;
			}   			
	}	
	static function getGminyRegister($sel=0,$tryb=0){	
		$query2 = "SELECT *  FROM _powiaty  WHERE POW=1 AND WOJ=2 AND GMI is not null  ORDER BY NAZWA";
		$mysql_result2 = mysql_query($query2);
			$result = '<select name="gmina_id" id="gmina_id" class="required1">';
			$result .= '<option value=\'0\'> -- wbierz powiat-- </option>';			
   			$result .= '</select>';
   			return $result;
	}	
	
	static function getGminy($name,$woj_id,$pow_id,$def=0,$tryb=0,$option=''){	
		
		if($tryb){
			$query2 = "SELECT *  FROM _powiaty  WHERE POW='$pow_id' AND WOJ='$woj_id' AND GMI ='$def'  ORDER BY NAZWA";
			
			$mysql_result2 = mysql_query($query2);
			$result = '<select name="'.$name.'" id="'.$name.'" class="desabled" disabled>';
		
			while ($row2=mysql_fetch_array($mysql_result2)){					
						$result .= '<option value=\''.$row2['GMI'].'\' '.($row2['GMI'] == $def ? 'SELECTED' : '').'>'.$row2['NAZWA'].' - '.$row2['NAZDOD'].'</option>';					
			}
   			$result .= '</select>';
   			return $result;			
		}else{
			$query2 = "SELECT *  FROM _powiaty  WHERE POW=$pow_id AND WOJ='$woj_id' AND GMI is not null  ORDER BY NAZWA";
			$mysql_result2 = mysql_query($query2);
			$result = '<select name="'.$name.'" id="'.$name.'" class="required1">';
			if (mysql_num_rows($mysql_result2) > 0 ){
				$result .= '<option value=\'0\'> ... </option>';
				while ($row2=mysql_fetch_array($mysql_result2)){					
							$result .= '<option value=\''.$row2['GMI'].'\' '.($row2['GMI'] == $def ? 'SELECTED' : '').'>'.$row2['NAZWA'].' - '.$row2['NAZDOD'].'</option>';					
				}
			}else{
				$result .= '<option value=\'0\'> -- wbierz powiat-- </option>';	
			}
   			$result .= '</select>';
   			return $result;
		}
	}	
	
	
	static function listaSzacujacychWSprawie($kolo_id,$case_id){
		
		$query = "SELECT coris_allianz_kola_szacujacy.* ,coris_allianz_announce_szacujacy.case_id, coris_allianz_announce_szacujacy.imie_nazwisko As n_imie_nazwisko, coris_allianz_announce_szacujacy.telefon As n_telefon
		
		FROM coris_allianz_kola_szacujacy LEFT JOIN coris_allianz_announce_szacujacy ON coris_allianz_announce_szacujacy.ID_szacujacy =   coris_allianz_kola_szacujacy.ID AND coris_allianz_announce_szacujacy.case_id = '$case_id'
		WHERE coris_allianz_kola_szacujacy.ID_umowa = '".self::getUmowa()."' AND coris_allianz_kola_szacujacy.ID_kolo ='$kolo_id' 
		ORDER BY imie_nazwisko ";
		//echo $query;
		
		$mysql_result = mysql_query($query);
		$result=array();
		
		while ($row = mysql_fetch_array($mysql_result)){
			$result[] = array(
				'ID' => $row['ID'], 
				'nazwa'=> ($row['case_id'] != '' ? $row['n_imie_nazwisko'] : $row['imie_nazwisko']) ,
				'telefon'=> ($row['case_id'] != '' ? $row['n_telefon'] : $row['telefon']),
				'selected' => ($row['case_id'] != '' ? 1 : 0) 
			); 		
		}
		return $result;
	}
	
	
	static function listaSzacujacych($kid){

	
	$query = "SELECT * FROM coris_allianz_kola_szacujacy  WHERE ID_umowa = '".self::getUmowa()."' AND ID_kolo ='$kid' AND aktywnosc=1 ORDER BY imie_nazwisko ";
	$mysql_result = mysql_query($query);
	$result = array();
	while ($row = mysql_fetch_array($mysql_result)){
		$result[] = array(
			'ID' => $row['ID'], 
			'nazwa'=>iconv('latin2','UTF-8',$row['imie_nazwisko']),
			'tel' => $row['telefon']
		); 		
	}
	return $result;
}

	static function ubezpieczenie($kid){
		$query = "SELECT * FROM coris_allianz_ubezpieczenia   WHERE ID_umowa = '".self::getUmowa()."' AND ID_kolo ='$kid' ";
		$mysql_result = mysql_query($query);
		
		$row = mysql_fetch_array($mysql_result);
	
		$result = array(
			'suma_ubezpieczenia' => $row['suma_ubezpieczenia'], 
			'franszyza_rodzaj' =>$row['franszyza_rodzaj'],
			'franszyza_kwota' => $row['franszyza_kwota'],
			'nr_polisy' => ($row['kolo_nr_polisy'] != null ? $row['kolo_nr_polisy'] : '')
		); 		
		
		return $result;
	}
	
	static function listaGatunkowZwierzatWSprawie($case_id){
		
		$query = "SELECT coris_allianz_gatunek.ID,coris_allianz_gatunek.nazwa,coris_allianz_announce_gatunek.case_id 
		FROM coris_allianz_gatunek
		LEFT JOIN coris_allianz_announce_gatunek ON coris_allianz_announce_gatunek.ID_gatunek = coris_allianz_gatunek.ID AND coris_allianz_announce_gatunek.case_id='$case_id'
		ORDER BY coris_allianz_gatunek.ID;
		";
		$mysql_result = mysql_query($query);
		$result=array();
		
		while ($row = mysql_fetch_array($mysql_result)){
			$result[] = array(
				'ID' => $row['ID'], 
				'nazwa'=> $row['nazwa'],
				'selected' => ($row['case_id'] != '' ? 1 : 0) 
			); 		
		}
		return $result;
	}
	
	static function listaGatunkowZwierzatWSprawie2($case_id){
		
		$query = "SELECT coris_allianz_gatunek.ID,coris_allianz_gatunek.nazwa,coris_allianz_announce_gatunek.case_id 
		FROM coris_allianz_gatunek,coris_allianz_announce_gatunek
		WHERE  coris_allianz_announce_gatunek.ID_gatunek = coris_allianz_gatunek.ID 		
		AND coris_allianz_announce_gatunek.case_id='$case_id'
		ORDER BY coris_allianz_gatunek.ID;
		";
		
		
		$mysql_result = mysql_query($query);
		$result=array();
		
		while ($row = mysql_fetch_array($mysql_result)){
			$result[] =  $row['nazwa'];				 			
		}
		return implode(', ', $result);
	}

	static function  dodajKolo( $nazwa,$adres,$kod,$miejscowosc,$ZO,$konto_bankowe,$suma_ubezpieczenia,$franszyza_rodzaj,$franszyza_kwota){
		$query = "INSERT INTO  coris_allianz_kola  SET 
		nazwa = '".mysql_escape_string(nazwa)."',
		adres= '".mysql_escape_string($adres)."',
		kod= '".mysql_escape_string($kod)."',
		miejscowosc= '".mysql_escape_string($miejscowosc)."',
		ZO= '".mysql_escape_string($ZO)."',
		konto_bankowe= '".mysql_escape_string($konto_bankowe)."',
		manual=1  
		";		
		
		$mysql_result = mysql_query($query);
		if (!$mysql_result ){																						
				echo  "<br>Update Error: $query <br><br> ".mysql_error();
				return false;				
		}	
		$kid =  mysql_insert_id();		
		
		$quer2 = "INSERT INTO coris_allianz_ubezpieczenia SET 
			ID_umowa = '".self::getUmowa()."',
			ID_kolo  ='$kid',
			suma_ubezpieczenia='$suma_ubezpieczenia',
			franszyza_rodzaj = '$franszyza_rodzaj',
			franszyza_kwota  = '$franszyza_kwota'			
		";
		$mysql_result = mysql_query($quer2);
		if (!$mysql_result ){																						
				echo  "<br>Update Error: $quer2 <br><br> ".mysql_error();
				return false;				
		}	
			return $kid;
		}
	
	static function  dodajSzacujacego($ID_kolo,  $imie_nazwisko,  $telefon){
		$query = "INSERT INTO coris_allianz_kola_szacujacy SET 
		ID_umowa = '".self::getUmowa()."',
		ID_kolo  ='$ID_kolo',
		imie_nazwisko  ='".mysql_escape_string($imie_nazwisko)."',
		telefon='$telefon',  
		aktywnosc=1, manual=1  
		";
		$mysql_result = mysql_query($query);
		if (!$mysql_result ){																						
				echo  "<br>Update Error: $query <br><br> ".mysql_error();
				return false;				
		}	
		return mysql_insert_id();		
	}
	
static function usunGatunek($case_id,$gatunek_id){	
		$query = "DELETE  FROM coris_allianz_announce_gatunek    WHERE case_id='$case_id'  AND ID_gatunek ='$gatunek_id'   ";
		$mysql_result = mysql_query($query);			
	}
	
	static function sprawdzGatunek($case_id,$gatunek_id){	
		$query = "SELECT * FROM coris_allianz_announce_gatunek    WHERE case_id='$case_id'  AND ID_gatunek ='$gatunek_id'   ";
		$mysql_result = mysql_query($query);
		if (mysql_num_rows($mysql_result) > 0 ) 	
			return true;
		else
			return false;	
	}
	
	static function usunSzacujacego($case_id,$szacujacy_id){	
		$query = "DELETE  FROM coris_allianz_announce_szacujacy   WHERE case_id='$case_id'  AND ID_szacujacy='$szacujacy_id'   ";
		$mysql_result = mysql_query($query);			
	}
	
	static function sprawdzSzacujacego($case_id,$szacujacy_id){	
		$query = "SELECT * FROM coris_allianz_announce_szacujacy   WHERE case_id='$case_id'  AND ID_szacujacy='$szacujacy_id'   ";
		$mysql_result = mysql_query($query);
		if (mysql_num_rows($mysql_result) > 0 ) 	
			return true;
		else
			return false;	
	}
	
	static function dodajSzacujacegoDoSprawy($case_id,$sz_id){
				if (!self:: sprawdzSzacujacego($case_id,$sz_id)){
						$szacujacy_dane = self::infoSzacujacy($sz_id);									
									$qi = "INSERT INTO coris_allianz_announce_szacujacy SET
										 case_id='$case_id',ID_szacujacy='$sz_id',
										 imie_nazwisko= '".mysql_escape_string($szacujacy_dane['nazwa'])."',
											telefon = '".mysql_escape_string($szacujacy_dane['tel'])."'";																
			        		$mr = mysql_query($qi);											
					if (!$mr ){																						
											echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
				}
		
	}
	
	static function dodajGatunekDoSprawy($case_id,$gatunek_id){
				if (!self:: sprawdzGatunek($case_id, $gatunek_id)){											
								$qi = "INSERT coris_allianz_announce_gatunek 
									SET  case_id='$case_id',ID_gatunek='$gatunek_id'";														
			        			$mr = mysql_query($qi);											
					if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
				}
		
	}
	
	static function infoSzacujacy($kid){
		$query = "SELECT * FROM coris_allianz_kola_szacujacy  WHERE ID = '$kid' ";
		$mysql_result = mysql_query($query);
		$result = array();
		if ($row = mysql_fetch_array($mysql_result)){
			$result= array(
				'ID' => $row['ID'], 
				'nazwa'=>$row['imie_nazwisko'],
				'tel' => $row['telefon']
			);
		} 			
		return $result;
	}
	
	static function rejestrujZmiany($case_id,$param_id,$form,$action,$zmiany){
					$qi = "INSERT coris_allianz_action SET  case_id='$case_id',param_id='$param_id',form='$form',`action`='$action',ID_user='".$_SESSION['user_id']."',date=now()";														
			       	$mr = mysql_query($qi);
			       	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
			       	$id = mysql_insert_id();
			       	if ($id>0){
			       		foreach ($zmiany As $poz => $wart){
			       			$qi = "INSERT coris_allianz_action_log SET  ID_action='$id',name='".$poz."',`table`='".$wart['table']."',`key_id`='".intval($wart['key_id'])."',`old_value`='".mysql_escape_string($wart['old'])."',new_value='".mysql_escape_string($wart['new'])."'";														
			       			$mr = mysql_query($qi);		
			       		 	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
							}	       			
			       		}
			       	}					
	}
	
	static function getClaims($case_id){
		$result = array();
					
		$query = "SELECT ID FROM coris_allianz_claims   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new AllianzClaim($row['ID'], $case_id);
		}
		return $result;
	}
	
	static function getDecisions($case_id){
		$result = array();
			
		$query = "SELECT ID FROM coris_allianz_decisions   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new AllianzDecision($row['ID'], $case_id);
		}
		return $result;
	}
	
	static function slownie($kwota){
		
		include_once('Numbers/Words.php'); 
		if (class_exists('Numbers_Words')){
			$kw = str_replace(',','.',$kwota);
			$gr = ev_round(($kw - floor($kw))*100);
		
			$ret = Numbers_Words::toWords(floor($kw),"pl") ; 
			$slownie =  $ret.' PLN';
			if ($gr>0){
				$slownie .=  ', '.$gr.'/100 groszy';
			}
			
			return $slownie;
		}
	}
	
	static function listaLikwidatorow($name='user_id',$option=''){
			$query = "SELECT user_id, surname, name FROM coris_users WHERE active = 1 AND user_id IN (".implode(', ', self::$_claim_handler_users).") ORDER BY surname";
			$mysql_result = mysql_query($query);   		
			$result = "<select name=\"".$name."\" id=\"".$name."\" ".$option.">";
    		$result .= "<option></option>";     
		    while ($row = mysql_fetch_array($mysql_result)){		
		        $result .= '<option value="'.$row['user_id'].'" >'.$row['surname'].', '.$row['name'].'</option>';
		    }
        $result .= "</select>";
		return $result;
	}
}


	



?>