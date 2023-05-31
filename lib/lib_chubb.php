<?php

Class ChubbCase{

    static $TABLE_ANNONUNCE = 'coris_chubb_announce';
    static $TABLE_REZERA_GLOBALBA_HISTORIA = 'coris_chubb_rezerwa_globalna_historia';
    static $TABLE_STATUS_HISTORIA = 'coris_chubb_status_historia';
    static $TABLE_STATUS_SZKODY = 'coris_chubb_status_szkody';
    static $TABLE_TYP_UMOWY = 'coris_chubb_typ_umowy';
    static $TABLE_ACTIONS = 'coris_chubb_actions';
    static $TABLE_ACTIONS_LOG = 'coris_chubb_actions_log';

	static public  $CLIENT_ID = 17787;

	static public  $TYP_PRODUKTU = 0;
	static public  $WARIANT_PRODUKTU = 0;
	static public  $BIURO_PODROZY = 0;

	var $case_id = 0;
	var $typ_umowy = 0;
	var $wariant_umowy = 0;
	var $rodzaj  = 0;
	var $case_info = array();
	
	
	
	function __construct($case_id){
		$this->case_id=$case_id;		
		if ($this->case_id > 0 )
			 $this->getCaseInfo();
	}
	
	function getCaseInfo(){
	
		$query2 = "SELECT * FROM `".self::$TABLE_ANNONUNCE."`  WHERE case_id = '".$this->case_id."'";
		$mysql_result2 = mysql_query($query2);

		if ( mysql_num_rows($mysql_result2) == 0 ){
		    $qi = "INSERT INTO `".self::$TABLE_ANNONUNCE."` SET case_id='".$this->case_id."' ";
		    $mr = mysql_query($qi);

            $query2 = "SELECT * FROM `".self::$TABLE_ANNONUNCE."`  WHERE case_id = '".$this->case_id."'";
            $mysql_result2 = mysql_query($query2);
        }
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		$this->case_info = $row_case_ann;
		$this->typ_umowy = $row_case_ann['ID_typ_umowy'];
		$this->wariant_umowy = $row_case_ann['ID_wariant'];
		$this->rodzaj = $row_case_ann['ID_rodzaj'];
				
		//$this->opcje_umowy = $this->getOpcjeUmowy();
	}	

	static function getCaseInfoStatic($case_id){

		$query2 = "SELECT * FROM `".self::$TABLE_ANNONUNCE."`  WHERE case_id = '".$case_id."'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);
		return  $row_case_ann;

	}

	
	
	static function getCaseAnnounce($case_id){
	
		$query2 = "SELECT * FROM `".self::$TABLE_ANNONUNCE."`   WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
        if (mysql_num_rows($mysql_result2) == 0 ){
                $mr = mysql_query("INSERT INTO `".self::$TABLE_ANNONUNCE."`  SET case_id = '$case_id' ");

                $query2 = "SELECT * FROM `".self::$TABLE_ANNONUNCE."`   WHERE case_id = '$case_id'";
                $mysql_result2 = mysql_query($query2);
                $row_case_ann = mysql_fetch_array($mysql_result2);
                return $row_case_ann;
        }else {
            $row_case_ann = mysql_fetch_array($mysql_result2);
            return $row_case_ann;
        }
	}
	
	
	function setStatus($new_status){					
		if ( $this->case_id > 0 && $new_status>0 ){						
			$query = "UPDATE `".self::$TABLE_ANNONUNCE."`  SET ID_status='$new_status' WHERE  case_id = '".$this->case_id."' LIMIT 1";
			$mysql_result = mysql_query($query);
			if ($mysql_result){
					$qu = "INSERT INTO `".self::$TABLE_STATUS_HISTORIA."` SET case_id='".$this->case_id."', ID_status='$new_status',ID_user='".$_SESSION['user_id']."',date=now();";
					$mr = mysql_query($qu);										
					if (!$mr){
						echo "$mr <br>".mysql_error();					
					}
			}else{
					echo "$query <br>".mysql_error();					
			}
		}											
	}

    function setZgloszenie($zid){
		if ( $this->case_id > 0 && $zid>0 ){
			$query = "UPDATE `".self::$TABLE_ANNONUNCE."`  SET ID_zgloszenie='$zid' WHERE  case_id = '".$this->case_id."' LIMIT 1";
			$mysql_result = mysql_query($query);
			if ($mysql_result){

					echo "$query <br>".mysql_error();

			}else{
					echo "$query <br>".mysql_error();
			}
		}
	}


	static  function  wysw_status($name,$def,$tryb=0,$option=""){
		$result='';		
		if ($tryb){
				$query = "SELECT * FROM `".self::$TABLE_STATUS_SZKODY."`    WHERE  ID='$def' ORDER BY  kolejnosc";
	//echo $query;
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
					$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			

			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;"  '.$option.'>';

				$query = "SELECT * FROM `".self::$TABLE_STATUS_SZKODY."`  ORDER BY kolejnosc";
				
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
			$query = "SELECT * FROM `".self::$TABLE_TYP_UMOWY."`   WHERE ID_client='$client_id' AND ID='$def' ORDER BY nazwa";		

			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);						
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;" disabled>';					
				$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;"  '.$option.'>';
		//<option value=""></option>
			$query = "SELECT * FROM `".self::$TABLE_TYP_UMOWY."` WHERE ID_client='$client_id'   ORDER BY nazwa";						
			
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




    static function aktualizacja_programu($case_id,$program){

        $qt = "SELECt case_id FROM ".self::$TABLE_ANNONUNCE."  WHERE case_id='$case_id'";
        $mt = mysql_query($qt);

        if (mysql_num_rows($mt)==0){
            $query = "INSERT INTO ".self::$TABLE_ANNONUNCE." SET case_id='$case_id', ID_wariant='$program' ";

        }else{
            $query = "UPDATE ".self::$TABLE_ANNONUNCE." SET ID_wariant='$program'  WHERE case_id='$case_id' LIMIT 1";
        }

        $mt = mysql_query($query);
        if (!$mt){echo "<br>QE: $query, <br>".mysql_error();}
    }

    static function  umowa_dane( $case_id , $tryb ){
        $dane = self::getCaseInfoStatic($case_id);

        $result = '<br><table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="320">';
        $result .= '<tr><td width="60" align="right"><b><small>Program:</small></b></td><td>'.self::wysw_wariant_umowy( 'chubb_program',$dane['ID_wariant'],$tryb,' style="width: 240px;" ').'</td></tr>';
        $result .= '</table>';

        return $result;
    }






	static function aktualizujRezerweGlobalna($case_id,$rezerwa_globalna_old,$rezerwa_globalna,$waluta,$id_expenses=0,$id_claims=0){		
			$query = "UPDATE `".self::$TABLE_ANNONUNCE."`  SET rezerwa_globalna = '$rezerwa_globalna',rezerwa_currency_id='$waluta' WHERE case_id ='$case_id' ";
			$mr = mysql_query($query);
			if ($mr ){
					//echo "$query <br>".mysql_error();	
					//history rezerwa_globalna
					$query = "INSERT INTO `".self::$TABLE_REZERA_GLOBALBA_HISTORIA."`  SET case_id=$case_id,rezerwa_stara='$rezerwa_globalna_old',rezerwa_nowa='$rezerwa_globalna',currency_id='$waluta',ID_expenses='$id_expenses',ID_claims='$id_claims',ID_user='".$_SESSION['user_id']."',date=now();";
					$mr = mysql_query($query);		
				
			}else{
					echo "$query <br>".mysql_error();			
			}
	
	}
	
	
	function getRezerwaGlobalna(){
			$query = "SELECT * FROM `".self::$TABLE_ANNONUNCE."`  WHERE case_id='".$this->case_id."'";
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);
			return array('rezerwa' => $row['rezerwa_globalna'],'waluta' => $row['rezerwa_currency_id']);
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
		$query = "SELECT   coris_finances_currencies_tables_rates.rate  AS rate_to_EUR, coris_finances_currencies_tables_rates.multiplier AS rate_to_EUR_mult,  (coris_finances_currencies_tables_rates.multiplier  / coris_finances_currencies_tables_rates.rate)  AS rate_to_ext, coris_finances_currencies_tables_rates.table_id, coris_finances_currencies_tables_rates.rate AS rate, coris_finances_currencies_tables.quotation_date, coris_finances_currencies_tables.publication_date, coris_finances_currencies_tables.ratetype_id,coris_finances_currencies_tables.number
			FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
			WHERE coris_finances_currencies_tables.source_id='$table_source' AND coris_finances_currencies_tables.publication_date < '$publication_date' AND coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id  AND coris_finances_currencies_tables.ratetype_id='".$ratetype_id."' AND coris_finances_currencies_tables_rates.currency_id = '$table_currency' ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);		
		return ( $row['rate_to_EUR']/$row['rate_to_EUR_mult'] );
	}
	
	
	static function rejestrujZmiany($case_id,$param_id,$form,$action,$zmiany){
					$qi = "INSERT `".self::$TABLE_ACTIONS."` SET  case_id='$case_id',param_id='$param_id',form='$form',`action`='$action',ID_user='".$_SESSION['user_id']."',date=now()";
			       	$mr = mysql_query($qi);
			       	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
			       	$id = mysql_insert_id();
			       	if ($id>0){
			       		foreach ($zmiany As $poz => $wart){
			       			$qi = "INSERT `".self::$TABLE_ACTIONS_LOG." SET  ID_action='$id',name='".$poz."',`table`='".$wart['table']."',`key_id`='".intval($wart['key_id'])."',`old_value`='".mysql_escape_string($wart['old'])."',new_value='".mysql_escape_string($wart['new'])."'";
			       			$mr = mysql_query($qi);		
			       		 	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
							}	       			
			       		}
			       	}					
	}
	
	

}
?>