<?php
include(dirname(__FILE__).'/VIG/VIGClaim.php');
include(dirname(__FILE__).'/VIG/VIGClaimDetails.php');
include(dirname(__FILE__).'/VIG/VIGDecision.php');

Class VIGCase{
	static  $TABLE_ANNONUNCE = 'coris_vig_announce';
	static function getCaseInfo($case_id){
	
		$query2 = "SELECT * FROM ".self::$TABLE_ANNONUNCE."  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);

        if (mysql_num_rows($mysql_result2) == 0 ){
            $mr = mysql_query("INSERT INTO ".self::$TABLE_ANNONUNCE." SET case_id = '$case_id' ");

            $query2 = "SELECT * FROM ".self::$TABLE_ANNONUNCE."  WHERE case_id = '$case_id'";
            $mysql_result2 = mysql_query($query2);
            $row_case_ann = mysql_fetch_array($mysql_result2);
            return $row_case_ann;
        }else {
            $row_case_ann = mysql_fetch_array($mysql_result2);
            return $row_case_ann;
        }


	}


	static function  umowa_dane( $case_id , $client_id,$tryb ){
			$dane = self::getCaseInfo($case_id);
			
			$result = '<br><table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="320">';
				$result .= '<tr><td width="60" align="right"><b><small>Program:</small></b></td><td>'.self::wysw_program($client_id, 'vig_program',$dane['ID_program'],$tryb,' style="width: 240px;" ').'</td></tr>';
			$result .= '</table>';
			
			return $result;
	}	

	static function  wysw_program($client_id,$name,$def,$tryb=0,$option=''){
	
		$result='';
		
		if ($tryb){
				$query = "SELECT * FROM coris_vig_program    WHERE  ID_client='$client_id'  AND ID='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 9px;width: 250px;" disabled>';					
					$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
				 $result .= '</select>';
				 return $result;						
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 9px;width: 250px;"  '.$option.'>
						<option value=""></option>';
				$query = "SELECT * FROM coris_vig_program WHERE ID_client='$client_id'  ORDER BY nazwa";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}

	static function aktualizacja_programu($case_id,$program){ 
	
			$qt = "SELECT case_id FROM ".self::$TABLE_ANNONUNCE."  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);			
			
			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO ".self::$TABLE_ANNONUNCE." SET case_id='$case_id', ID_program='$program' ";
								
			}else{
				$query = "UPDATE ".self::$TABLE_ANNONUNCE." SET ID_program='$program'  WHERE case_id='$case_id' LIMIT 1";				
			}

			$mt = mysql_query($query);	
			if (!$mt){echo "<br>QE: $query, <br>".mysql_error();}	
	}	
	
	
	static function rejestrujZmiany($case_id,$param_id,$form,$action,$zmiany){
					$qi = "INSERT coris_vig_action SET  case_id='$case_id',param_id='$param_id',form='$form',`action`='$action',ID_user='".$_SESSION['user_id']."',date=now()";														
			       	$mr = mysql_query($qi);
			       	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
			       	$id = mysql_insert_id();
			       	if ($id>0){
			       		foreach ($zmiany As $poz => $wart){
			       			$qi = "INSERT coris_vig_action_log SET  ID_action='$id',name='".$poz."',`table`='".$wart['table']."',`key_id`='".intval($wart['key_id'])."',`old_value`='".mysql_escape_string($wart['old'])."',new_value='".mysql_escape_string($wart['new'])."'";														
			       			$mr = mysql_query($qi);		
			       		 	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
							}	       			
			       		}
			       	}					
	}
	
	
static function getClaimsDetailsLista($case_id,$var){
		$result = array();
					
		$query = "SELECT cls.ID FROM coris_vig_claims cl,coris_vig_claims_details cls WHERE cl.ID_case = '$case_id' AND cl.ID=cls.ID_claims ".($var!='' ? ' AND '.$var : '')." ORDER BY cls.ID ";
		
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new VIGClaimDetails( $row['ID'] );
		}
		return $result;
	}
	
	static function getClaims($case_id){
		$result = array();
					
		$query = "SELECT ID FROM coris_vig_claims   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new VIGClaim($row['ID'], $case_id);
		}
		return $result;
	}
	
	static function getDecisions($case_id){
		$result = array();
			
		$query = "SELECT ID FROM coris_vig_decisions   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new VIGDecision($row['ID'], $case_id);
		}
		return $result;
	}
	
	/*static function  wysw_wariant_umowy($name,$def,$tryb=0,$typ_umowy,$option=''){	
		$result='';
		
		if ($tryb){
				$query = "SELECT ID,nazwa FROM coris_vig_wariant_umowy   WHERE ID_typ_umowy ='$typ_umowy' AND  ID='$def' ORDER BY nazwa";						
				$mysql_result = mysql_query($query);
				$row2 = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 300px;" disabled>';					
					$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				 $result .= '</select>';
				 return $result;				
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 300px;" '.$option.'>
						<option value=""></option>';
				$query = "SELECT ID,nazwa FROM coris_vig_wariant_umowy    WHERE ID_typ_umowy ='$typ_umowy' ORDER BY nazwa";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}

	static function  wysw_swiadczenie($name,$def,$typ_umowy,$tryb=0,$option=''){
	
			$result='';
		
		if ($tryb){
				$query = "SELECT ID,nazwa FROM coris_vig_swiadczenia  WHERE ID_typ_umowy ='$typ_umowy'  AND ID='$def' ORDER BY nazwa";						
				$mysql_result = mysql_query($query);
				$row2 = mysql_fetch_array($mysql_result);			
				return $row['nazwa'];		
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;width:220px;" '.$option.'>
						<option value=""></option>';
				$query = "SELECT ID,nazwa FROM coris_vig_swiadczenia  WHERE ID_typ_umowy ='$typ_umowy'  ORDER BY nazwa";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}
	*/	
}
?>