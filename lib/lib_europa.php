<?php
include(dirname(__FILE__).'/Europa/EuropaClaim.php');
include(dirname(__FILE__).'/Europa/EuropaClaimDetails.php');
include(dirname(__FILE__).'/Europa/EuropaDecision.php');

Class EuropaCase{

	static public  $TYP_PRODUKTU = 0;
	static public  $WARIANT_PRODUKTU = 0;
	static public  $BIURO_PODROZY = 0;
	var $case_id = 0;
	var $typ_umowy = 0;
	var $wariant_umowy = 0;
	var $rodzaj  = 0;
	var $case_info = array();
	var $opcje_umowy = array();  // opcje umowy
	var $_biuro_podrozy = array();  // opcje umowy
	
	function __construct($case_id){
		$this->case_id=$case_id;		
		if ($this->case_id > 0 )
			 $this->getCaseInfo();
	}
	
	function getCaseInfo(){
	
		$query2 = "SELECT * FROM coris_europa_announce  WHERE case_id = '".$this->case_id."'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		$this->case_info = $row_case_ann;
		$this->typ_umowy = $row_case_ann['ID_typ_umowy'];
		$this->wariant_umowy = $row_case_ann['ID_wariant'];
		$this->rodzaj = $row_case_ann['ID_rodzaj'];
		$this->_biuro_podrozy = $row_case_ann['ID_biuro_podrozy'];		
		$this->opcje_umowy = $this->getOpcjeUmowy();
	}	
	
	function  getBiurPodrozyNazwa(){
		$query = "SELECT nazwa FROM coris_europa_biura_podrozy  WHERE ID = '".$this->_biuro_podrozy."'";
		$mysql_result = mysql_query($query);		
		$row = mysql_fetch_array($mysql_result);
		
		return  $row['nazwa'];			
	}
	
	
	function  getOpcjeUmowy(){
		$query = "SELECT ID_opcja FROM coris_europa_announce_opcje WHERE case_id = '".$this->case_id."'";
		$mysql_result = mysql_query($query);
		$res = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$res[] = $row['ID_opcja'];			
		}
		return $res;
	}
	
	static function getCaseeuropaInfo($case_id){
	
		$query2 = "SELECT * FROM coris_europa_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		return $row_case_ann;
	}
	
	
	function setStatus($new_status){					
		if ( $this->case_id > 0 && $new_status>0 ){						
			$query = "UPDATE coris_europa_announce SET ID_status='$new_status' WHERE  case_id = '".$this->case_id."' LIMIT 1";
			$mysql_result = mysql_query($query);
			if ($mysql_result){
					$qu = "INSERT INTO coris_europa_status_historia SET case_id='".$this->case_id."', ID_status='$new_status',ID_user='".$_SESSION['user_id']."',date=now();";										
					$mr = mysql_query($qu);										
					if (!$mr){
						echo "$mr <br>".mysql_error();					
					}
			}else{
					echo "$query <br>".mysql_error();					
			}
		}											
	}


	static  function  wysw_status($name,$def,$tryb=0,$typ_umowy){	
		$result='';		
		if ($tryb){
				$query = "SELECT * FROM coris_europa_status_szkody    WHERE ID_typ_umowy ='$typ_umowy' AND ID='$def' ORDER BY  kolejnosc";		
	//echo $query;
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
					$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			
				if ( !($typ_umowy > 0) )
					$typ_umowy = self::$TYP_PRODUKTU ;
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;"  '.$option.'>';
			//<option value=""></option>
				$query = "SELECT * FROM coris_europa_status_szkody  WHERE ID_typ_umowy ='$typ_umowy'  ORDER BY kolejnosc";						
				
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
			  $result .= '<input type="hidden" name="old_'.$name.'" value="'.$def.'">';
		}
		return $result;															
	}
	
	static function  wysw_typy_umowy($client_id,$name,$def,$tryb=0,$option=''){
	
	$result='';

	if ($tryb){
			$query = "SELECT * FROM coris_europa_typ_umowy   WHERE ID_client='$client_id' AND ID='$def' ORDER BY nazwa";		

			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);						
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
				$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;"  '.$option.'>';
		//<option value=""></option>
			$query = "SELECT * FROM coris_europa_typ_umowy WHERE ID_client='$client_id'   ORDER BY nazwa";						
			
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
						if ( !($def > 0) ){
							
							self::$TYP_PRODUKTU = $row2['ID'];
							//echo self::$TYP_PRODUKTU;
						}
			}
		  $result .= '</select>';
	}
	return $result;															
}


	static function  wysw_rodzaj_szkody($case_id,$name,$def,$tryb=0,$option=''){
	
	$result='';

	if ($tryb){
			$query = "SELECT * FROM coris_europa_zakres_ubezpieczenia    WHERE ID='$def' ORDER BY kolejnosc,nazwa";		

			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);						
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 300px;" disabled>';					
				$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].' ('.$row['symbol'].')</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		$var = '';
		if ($def>0){
			
			
			// if (){  // dodac warunek ze jesli sprawa z aktualnego miesiaca to mozna zmieniac a jesli nie to tylko to co jest
			//}
			
			$qt0 = "SELECT  DATE_FORMAT(date,'%Y-%m') As date FROM  coris_assistance_cases WHERE  case_id ='".$case_id."' ";
			$mt0 = mysql_query($qt0);
			$rt0 = mysql_fetch_array($mt0);
			$date = $rt0['date'];
			
			
			$qt = "SELECT * FROM coris_europa_rezerwy  WHERE case_id ='".$case_id."'";
			$mt = mysql_query($qt);
		
			if (mysql_num_rows($mt)>0 || $date != date('Y-m'))
					$var = " WHERE ID='$def' ";
		}			
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 300px;"  '.$option.'>';
		//<option value=""></option>
			$query = "SELECT * FROM coris_europa_zakres_ubezpieczenia  $var ORDER BY kolejnosc,nazwa";						
			
			$mysql_result = mysql_query($query);
			//echo $query;
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].' ('.$row2['symbol'].')</option>';
						if ( !($def > 0) ){
							
							self::$TYP_PRODUKTU = $row2['ID'];
							//echo self::$TYP_PRODUKTU;
						}
			}
		  $result .= '</select>';
	}
	return $result;															
}
	static function  wysw_biura_podrozy($name,$def,$tryb=0,$typ_umowy,$option=''){
	
	$result='';
	if ($tryb){
			$query = "SELECT * FROM coris_europa_biura_podrozy    WHERE ID_typ_umowy ='$typ_umowy' AND ID='$def' ORDER BY kolejnosc,nazwa";		

			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);						
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
				$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		
		if ( !($typ_umowy > 0) )
				$typ_umowy = self::$TYP_PRODUKTU ;
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;"  '.$option.'>';
		$result .= '<option value=""></option>';
			$query = "SELECT * FROM coris_europa_biura_podrozy WHERE ID_typ_umowy ='$typ_umowy'   ORDER BY kolejnosc,nazwa";						
			
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
						if ( !($def > 0) || $row2['ID'] == $def ){							
							self::$BIURO_PODROZY = $row2['ID'];
						
							//echo self::$TYP_PRODUKTU;
						}
			}
		  $result .= '</select>';
	}
	return $result;															
}

static  function  wysw_wariant_umowy($name,$def,$tryb=0,$typ_umowy,$option=''){	
	$result='';
	
	
	if ($tryb){
			$query = "SELECT ID,nazwa FROM coris_europa_wariant_umowy   WHERE ID_typ_umowy ='$typ_umowy' AND  opcja=0 AND ID='$def' ORDER BY kolejnosc";				
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
				$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;				
	}else{
		if ( !($typ_umowy > 0) )
				$typ_umowy = self::$TYP_PRODUKTU ;
	
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;" '.$option.'>';
			$query = "SELECT ID,nazwa FROM coris_europa_wariant_umowy   WHERE opcja=0  AND  ID_typ_umowy ='$typ_umowy' ORDER BY kolejnosc";					
			
			$first = 1;
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
							if ( $first ){							
									self::$WARIANT_PRODUKTU = $row2['ID'];			
									$first=0;			
							}
			}
		  $result .= '</select>';
	}
	return $result;															
}

	static  function  wysw_wariant_umowy_kod($name,$def,$tryb=0,$typ_umowy,$option=''){	
		$result='';
		
	
		if ($tryb){
			if ( !($typ_umowy > 0) )
				$typ_umowy = self::$TYP_PRODUKTU ;
			$query = "SELECT ID,nazwa,kod_taryfy FROM coris_europa_wariant_umowy   WHERE ID_typ_umowy ='$typ_umowy' AND  opcja=0 AND ID='$def' ORDER BY kolejnosc";				
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
				$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].' ('.$row2['kod_taryfy'].')</option>';
			 $result .= '</select>';
			 $first = 1;
   	 		 if ( $first || ($row2['ID'] == $def)){							
									self::$WARIANT_PRODUKTU = $row2['ID'];			
									$first=0;										
			 }

			 return $result;				
	}else{
	
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;" '.$option.'>';
			$query = "SELECT coris_europa_wariant_umowy.ID,coris_europa_wariant_umowy.nazwa,coris_europa_wariant_umowy.kod_taryfy 
				FROM coris_europa_wariant_umowy,coris_europa_pakiet    WHERE opcja=0  
					AND  coris_europa_pakiet.ID_wariant  = coris_europa_wariant_umowy.ID
					AND coris_europa_pakiet.ID_biuro  = '".self::$BIURO_PODROZY."'
					AND ID_typ_umowy ='$typ_umowy' ORDER BY kolejnosc";					
			
			$first = 1;
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].' ('.$row2['kod_taryfy'].')</option>';
							if ( $first || ($row2['ID'] == $def)){							
									self::$WARIANT_PRODUKTU = $row2['ID'];			
									$first=0;										
							}
			}
		  $result .= '</select>';
	}
	return $result;															
}


static  function  wysw_opcje_umowy($name,$case_id,$tryb=0,$typ_umowy,$option=''){	
	$result='';
	
	if ($tryb){
			
			$query = "SELECT ID,nazwa,coris_europa_announce_opcje.ID_opcja FROM coris_europa_wariant_umowy LEFT JOIN coris_europa_announce_opcje ON ID_opcja=coris_europa_wariant_umowy.ID AND coris_europa_announce_opcje.case_id = '$case_id'   WHERE coris_europa_wariant_umowy.opcja=1  AND  coris_europa_wariant_umowy.ID_typ_umowy ='$typ_umowy' ORDER BY kolejnosc";	
		//	echo $query;			
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<input style="background-color:#AAAAAA;" name="'.$name.'[]" type="checkbox" value="'.$row2['ID'].'" '.(($row2['ID_opcja'] > 0 ) ? "checked" : "") .' disabled>'.$row2['nazwa'].'<br>';
			}
	}else{
		
			if ( !($typ_umowy > 0) )
				$typ_umowy = self::$TYP_PRODUKTU ;
				
			$query = "SELECT ID,nazwa,coris_europa_announce_opcje.ID_opcja FROM coris_europa_wariant_umowy LEFT JOIN coris_europa_announce_opcje ON ID_opcja=coris_europa_wariant_umowy.ID AND coris_europa_announce_opcje.case_id = '$case_id'   WHERE coris_europa_wariant_umowy.opcja=1  AND  coris_europa_wariant_umowy.ID_typ_umowy ='$typ_umowy' ORDER BY kolejnosc";	
			
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<input style="background-color:#AAAAAA;" name="'.$name.'[]" type="checkbox" value="'.$row2['ID'].'" '.(($row2['ID_opcja'] > 0 ) ? "checked" : "") .'>'.$row2['nazwa'].'<br>';
			}
		
	}
	return $result;															
}
static  function  wysw_opcje_umowy_kod($name,$case_id,$tryb=0,$typ_umowy,$option=''){	
	$result='<div id="'.$name.'_lista">';
	
	if ($tryb){
			
		/*	if ( !($typ_umowy > 0) ){
				$typ_umowy = self::$TYP_PRODUKTU ;
				
			}*/
			$wariant_umowy = self::$WARIANT_PRODUKTU;				
			$query = "SELECT coris_europa_wariant_umowy.ID,coris_europa_wariant_umowy.opcja_status,coris_europa_wariant_umowy.nazwa,coris_europa_announce_opcje.ID_opcja 
					FROM coris_europa_wariant_umowy LEFT JOIN coris_europa_announce_opcje ON ID_opcja=coris_europa_wariant_umowy.ID 
															AND coris_europa_announce_opcje.case_id = '$case_id'   
					WHERE
					
					 coris_europa_wariant_umowy.opcja=1  AND  coris_europa_wariant_umowy.ID_parent ='$wariant_umowy' 
					 AND coris_europa_wariant_umowy.ID_parent AND ID_parent>0
					 ORDER BY kolejnosc";	
			//echo $query;
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<input style="background-color:#AAAAAA;" name="'.$name.'[]" type="checkbox" value="'.$row2['ID'].'" '.(($row2['ID_opcja'] > 0 ) ? "checked" : "") .'    disabled>'.$row2['nazwa'].'<br>';
			
			}
	}else{
		
			if ( !($typ_umowy > 0) ){
				$typ_umowy = self::$TYP_PRODUKTU ;
				
			}
				$wariant_umowy = self::$WARIANT_PRODUKTU;
			$query = "SELECT coris_europa_wariant_umowy.ID,coris_europa_wariant_umowy.opcja_status,coris_europa_wariant_umowy.nazwa,coris_europa_announce_opcje.ID_opcja 
					FROM coris_europa_wariant_umowy LEFT JOIN coris_europa_announce_opcje ON ID_opcja=coris_europa_wariant_umowy.ID 
															AND coris_europa_announce_opcje.case_id = '$case_id'   
					WHERE
					
					 coris_europa_wariant_umowy.opcja=1  AND  coris_europa_wariant_umowy.ID_parent ='$wariant_umowy' 
					 AND coris_europa_wariant_umowy.ID_parent AND ID_parent>0
					 ORDER BY kolejnosc";	
			//echo $query;
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<input style="background-color:#AAAAAA;" name="'.$name.'[]" type="checkbox" value="'.$row2['ID'].'" '.(($row2['ID_opcja'] > 0 ) ? "checked" : "") .'  '.($row2['opcja_status']==1 ? 'name="'.$name.'[]_fake" checked disabled' : ' name="'.$name.'[]" ').'>'.$row2['nazwa'].'<br>';
						if ($row2['opcja_status']==1){
							$result .= '<input type="hidden" name="'.$name.'[]" value="'.$row2['ID'].'">';
						}
			
			}
		
	}
	
	$result .= '</div>';
	return $result;															
}



  static function getSumaUbezpieczenia($wariant=0){
			
			
			$wariant = $wariant > 0 ? $wariant : self::$WARIANT_PRODUKTU;
			
			if ($wariant > 0 ){
					$query = "SELECT * FROM coris_europa_lista_swiadczen WHERE ID_wariant='$wariant' AND ID_podlimit =0 ";
					$mysql_result = mysql_query($query);
					if (mysql_num_rows($mysql_result)>0){
							$row = mysql_fetch_array($mysql_result);
							return array( 'kwota' => print_currency($row['kwota']),'waluta' => $row['currency_id']);
					}				
			}					
	}
	
	
	static function aktualizujRezerweGlobalna($case_id,$rezerwa_globalna_old,$rezerwa_globalna,$waluta,$id_expenses=0,$id_claims=0){		
			$query = "UPDATE coris_europa_announce SET rezerwa_globalna = '$rezerwa_globalna',rezerwa_currency_id='$waluta' WHERE case_id ='$case_id' ";
			$mr = mysql_query($query);
			if ($mr ){
					//echo "$query <br>".mysql_error();	
					//history rezerwa_globalna
					$query = "INSERT INTO coris_europa_rezerwa_globalna_historia  SET case_id=$case_id,rezerwa_stara='$rezerwa_globalna_old',rezerwa_nowa='$rezerwa_globalna',currency_id='$waluta',ID_expenses='$id_expenses',ID_claims='$id_claims',ID_user='".$_SESSION['user_id']."',date=now();";
					$mr = mysql_query($query);		
				
			}else{
					echo "$query <br>".mysql_error();			
			}
	
	}
	
	
	function getRezerwaGlobalna(){
			$query = "SELECT * FROM coris_europa_announce WHERE case_id='".$this->case_id."'";
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);
			return array('rezerwa' => $row['rezerwa_globalna'],'waluta' => $row['rezerwa_currency_id']);
	}

	
	function listaSwiadczenZlecenie($id_swiadczenie=0){												    			
		$result = '
		
				<tr><td  align="right"><small><b>¦wiadczenie: </b></small></td>
				<td align="left" colspan="1">'. $this->wyswZakresUbezpieczenia('id_swiadczenie',$id_swiadczenie,0,'class="required" onChange="europa_getSumaUbezp_do_wyk('.$this->case_id.',this.value,\'suma_ubezpieczenia\',\'rezerwa_globalna\');"').'				
				</tr>
				<tr> 		                
                <td align="right">';

		//$result .= wysw_swiadczenie('id_swiadczenie',$id_swiadczenie,$case_cardif_info['ID_typ_umowy'],0,'class="required" onChange="getSumaUbezp(this.value,\'typ_umowy\',\'suma_ubezpieczenia\');"') ;
		
		$result .= '&nbsp;</td><td align="right" style="padding-right:120px;"> 
				<small><b>Suma ubezp. do wykorzystania:</b></small> <input type="text" name="suma_ubezpieczenia" id="suma_ubezpieczenia" value="'.$suma_ubezpieczenia.'"  style="text-align: right;" size="8" maxlength="20" class="required" disabled>'. wysw_currency('sumacurrency_id','PLN',0,'class="required"  disabled') .'										
				<br><small><b>Rezerwa globalna do wykorzystania: </b></small>
					<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.$rezerwa_globalna.'"  style="text-align: right;" size="8" maxlength="20" class="required" disabled>'. wysw_currency('rezerwa_currency_id','PLN',0,'class="required" disabled') .'               
                <script>document.getElementById(\'amount\').value=\''.$amount.'\';</script>                
                </td></tr>
               <tr><td align="right" colspan="2">&nbsp;</td></tr>';			
		
		return $result;
	}

		function listaSwiadczenZlecenieEdit($expense_id){												    						
				$query = "SELECT * FROM coris_europa_rezerwy WHERE ID_expenses='$expense_id'";
				$mysql_result = mysql_query($query);
				//echo $query;
				if (mysql_num_rows($mysql_result) == 0 ){
					return 	$this->listaSwiadczenZlecenie();
				}else{ 
					$row = mysql_fetch_array($mysql_result);
				}
				
				$id_swiadczenie =$row['ID_swiadczenie'];
				$suma_ubezpieczenia = $row[''];
				$rezerwa_globalna = $row[''];
		$result = '		
				<tr><td  align="right"><small><b>¦wiadczenie: </b></small></td>
				<td align="left" colspan="1">'. $this->wyswZakresUbezpieczenia('id_swiadczenie',$id_swiadczenie,0,'class="required" onChange="europa_getSumaUbezp_do_wyk('.$this->case_id.',this.value,\'suma_ubezpieczenia\',\'rezerwa_globalna\');"').'				
				</tr>
				<tr> 		                
                <td align="right">';
		
		//$result .= wysw_swiadczenie('id_swiadczenie',$id_swiadczenie,$case_cardif_info['ID_typ_umowy'],0,'class="required" onChange="getSumaUbezp(this.value,\'typ_umowy\',\'suma_ubezpieczenia\');"') ;
		
		$result .= '&nbsp;</td><td align="right" style="padding-right:120px;"> 
				<small><b>Suma ubezp. do wykorzystania:</b></small> <input type="text" name="suma_ubezpieczenia" id="suma_ubezpieczenia" value="'.$suma_ubezpieczenia.'"  style="text-align: right;" size="8" maxlength="20" class="required" disabled>'. wysw_currency('sumacurrency_id','PLN',0,'class="required"  disabled') .'										
				<br><small><b>Rezerwa globalna do wykorzystania: </b></small>
					<input type="text" name="rezerwa_globalna" id="rezerwa_globalna" value="'.$rezerwa_globalna.'"  style="text-align: right;" size="8" maxlength="20" class="required" disabled>'. wysw_currency('rezerwa_currency_id','PLN',0,'class="required" disabled') .'               
                
                <script>europa_getSumaUbezp_do_wyk('.$this->case_id.','.$id_swiadczenie.',\'suma_ubezpieczenia\',\'rezerwa_globalna\')</script>                
                </td></tr>
               <tr><td align="right" colspan="2">&nbsp;</td></tr>';			
		
		return $result;
	}

	
	
	function wyswZakresUbezpieczenia($name,$def,$tryb=0,$option=''){
			
		$query = "SELECT coris_europa_lista_swiadczen.ID,coris_europa_swiadczenia.nazwa ,coris_europa_zakres_ubezpieczenia.nazwa As zakres_nazwa 
			FROM coris_europa_lista_swiadczen,coris_europa_swiadczenia,coris_europa_zakres_ubezpieczenia		   
			WHERE coris_europa_swiadczenia.ID = coris_europa_lista_swiadczen.ID_swiadczenie
			AND coris_europa_zakres_ubezpieczenia.ID = coris_europa_lista_swiadczen.ID_zakres_ubezpieczenia
			AND (coris_europa_zakres_ubezpieczenia.ID= '".$this->rodzaj."')
			AND ( coris_europa_lista_swiadczen.ID_wariant = '".$this->wariant_umowy."' 		
			";
			
		//echo $query;	
			//$dd = count($this->opcje_umowy);
			$query .= (is_array($this->opcje_umowy) && count($this->opcje_umowy)>0) ? "OR coris_europa_lista_swiadczen.ID_wariant IN (".implode(',',$this->opcje_umowy).")" : '';
			
			$query .= ") ORDER BY 	coris_europa_lista_swiadczen.ID_zakres_ubezpieczenia,coris_europa_lista_swiadczen.kolejnosc,coris_europa_lista_swiadczen.ID  		";
		//echo $query;
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 450px;" '.$option.'>';
			$mysql_result = mysql_query($query);
			$result .= '<option value="0"> --- wybierz ¶wiadczenie --- </option>';
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['zakres_nazwa'].' -> '.$row2['nazwa'].'</option>';
			}
			
			$result .= '</select>';
			
		return $result;	
		
	}
	
	
	function getKursWaluty($waluta){
					$query2 = "SELECT * FROM coris_assistance_cases_details   WHERE case_id = '".$this->case_id."'";
					$mysql_result2 = mysql_query($query2);
					$row_cased= mysql_fetch_array($mysql_result2);	

					$date = $row_cased['policypurchasedate'];
					$date2 = $row_cased['validityfrom'];
					
					$date_kurs=$date;
					if ( $date == '' || $date == '0000-00-00' ){
						if ( $date2 == '' || $date2 == '0000-00-00' ){
								$query2 = "SELECT * FROM coris_assistance_cases  WHERE case_id = '".$this->case_id."'";
								$mysql_result2 = mysql_query($query2);
								$row_case= mysql_fetch_array($mysql_result2);	
														
								$eventdate  = $row_case['eventdate'];																					
								$opendate  = substr($row_case['date'],0,10);																					
								
								if ( $eventdate == '' || $eventdate == '0000-00-00' ){
									$date_kurs=$opendate;
								}else{
									$date_kurs=$eventdate;
								}						
						}else{
							$date_kurs=$date2;
						}
								
					}
					

			
					$kurs = $this->getKursy($date_kurs,1,$waluta,1);
					return $kurs;
	}
	
	function getKursy($publication_date,$ratetype_id,$table_currency,$table_source){		
		$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_pln, coris_finances_currencies_tables_rates.multiplier AS rate_to_pln_mult,  (coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate)  AS rate_to_ext, coris_finances_currencies_tables_rates.table_id, coris_finances_currencies_tables_rates.rate AS rate, coris_finances_currencies_tables.quotation_date, coris_finances_currencies_tables.publication_date, coris_finances_currencies_tables.ratetype_id,coris_finances_currencies_tables.number
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
			WHERE coris_finances_currencies_tables.source_id='$table_source' AND coris_finances_currencies_tables.publication_date < '$publication_date' AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables.ratetype_id='".$ratetype_id."' AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);		
		return ( $row['rate_to_pln']/$row['rate_to_pln_mult'] );
	}
	
	
	static function rejestrujZmiany($case_id,$param_id,$form,$action,$zmiany){
					$qi = "INSERT coris_europa_action SET  case_id='$case_id',param_id='$param_id',form='$form',`action`='$action',ID_user='".$_SESSION['user_id']."',date=now()";														
			       	$mr = mysql_query($qi);
			       	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
			       	$id = mysql_insert_id();
			       	if ($id>0){
			       		foreach ($zmiany As $poz => $wart){
			       			$qi = "INSERT coris_europa_action_log SET  ID_action='$id',name='".$poz."',`table`='".$wart['table']."',`key_id`='".intval($wart['key_id'])."',`old_value`='".mysql_escape_string($wart['old'])."',new_value='".mysql_escape_string($wart['new'])."'";														
			       			$mr = mysql_query($qi);		
			       		 	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
							}	       			
			       		}
			       	}					
	}
	
	
	static function getClaimsDetailsLista($case_id,$var){
		$result = array();
					
		$query = "SELECT cls.ID FROM coris_europa_claims cl,coris_europa_claims_details cls WHERE cl.ID_case = '$case_id' AND cl.ID=cls.ID_claims ".($var!='' ? ' AND '.$var : '')." ORDER BY cls.ID ";
		
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new EuropaClaimDetails( $row['ID'] );
		}
		return $result;
	}
	
	static function getClaims($case_id){
		$result = array();
					
		$query = "SELECT ID FROM coris_europa_claims   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new EuropaClaim($row['ID'], $case_id);
		}
		return $result;
	}
	
	static function getDecisions($case_id){
		$result = array();
			
		$query = "SELECT ID FROM coris_europa_decisions   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new EuropaDecision($row['ID'], $case_id);
		}
		return $result;
	}
}
?>