<?php
include(dirname(__FILE__).'/Chubba/ChubbaClaim.php');
include(dirname(__FILE__).'/Chubba/ChubbaClaimDetails.php');
include(dirname(__FILE__).'/Chubba/ChubbaDecision.php');


Class ChubbaCase{
	static  $TABLE_ANNONUNCE = 'coris_chubba_announce';
	static  $TABLE_ACTION = 'coris_chubba_action';
	static  $TABLE_ACTION_LOG = 'coris_chubba_action_log';
	static  $TABLE_CLAIMS = 'coris_chubba_claims';
	static  $TABLE_CLAIMS_DETAILS = 'coris_chubba_claims_details';
	static  $TABLE_CLAIMS_DECISIONS = 'coris_chubba_decisions';
	static  $TABLE_CLAIMS_DECISIONS_DETAILS = 'coris_chubba_decisions_details';
	static  $TABLE_CLAIMS_DETAILS_STATUS = 'coris_chubba_claims_details_status';
	static  $TABLE_CLAIMS_DETAILS_STATUS_LOG = 'coris_chubba_claims_details_status_log';
	static  $TABLE_PAYMENT= 'coris_chubba_payment';


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

	static function rejestrujZmiany($case_id,$param_id,$form,$action,$zmiany){
					$qi = "INSERT ".self::$TABLE_ACTION." SET  case_id='$case_id',param_id='$param_id',form='$form',`action`='$action',ID_user='".$_SESSION['user_id']."',date=now()";														
			       	$mr = mysql_query($qi);
			       	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
					}	
			       	$id = mysql_insert_id();
			       	if ($id>0){
			       		foreach ($zmiany As $poz => $wart){
			       			$qi = "INSERT ".self::$TABLE_ACTION_LOG." SET  ID_action='$id',name='".$poz."',`table`='".$wart['table']."',`key_id`='".intval($wart['key_id'])."',`old_value`='".mysql_escape_string($wart['old'])."',new_value='".mysql_escape_string($wart['new'])."'";														
			       			$mr = mysql_query($qi);		
			       		 	if (!$mr ){																						
								echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
							}	       			
			       		}
			       	}					
	}
	
	
    static function getClaimsDetailsLista($case_id,$var){
		$result = array();
					
		$query = "SELECT cls.ID FROM ".self::$TABLE_CLAIMS."  cl,".self::$TABLE_CLAIMS_DETAILS."  cls WHERE cl.ID_case = '$case_id' AND cl.ID=cls.ID_claims ".($var!='' ? ' AND '.$var : '')." ORDER BY cls.ID ";
		
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new ChubbaClaimDetails( $row['ID'] );
		}
		return $result;
	}
	
	static function getClaims($case_id){
		$result = array();
					
		$query = "SELECT ID FROM ".self::$TABLE_CLAIMS."   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		$result = array();
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new ChubbaClaim($row['ID'], $case_id);
		}
		return $result;
	}
	
	static function getDecisions($case_id){
		$result = array();
			
		$query = "SELECT ID FROM ".self::$TABLE_CLAIMS_DECISIONS."   WHERE ID_case = '$case_id' ";
		$mysql_result = mysql_query($query);
		
		while ($row = mysql_fetch_array($mysql_result)){
			$result[]= new ChubbaDecision($row['ID'], $case_id);
		}
		return $result;
	}
    
}
?>