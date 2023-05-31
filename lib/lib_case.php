<?php


Class CaseInfo{
	
	static private  $TABLE_HISTORY = 'coris_assistance_cases_change_history';
	static private  $TABLE_HISTORY_PARAM = 'coris_assistance_cases_change_history_param';

	
	static function CaseCauseUpdate($case_id,$new_value,$old_value){						
			if ($new_value != $old_value){
				$qu = "UPDATE coris_assistance_cases SET ID_cause='$new_value' WHERE case_id='$case_id'	LIMIT 1";
				$mr = mysql_query($qu);
				if ($mr){
						self::CaseHistorySave($case_id,'CaseCause',$new_value,$old_value);
				}else{
					
					echo "QE: ".$qu."<br><br>".mysql_error();
				}
			}
	}
	
	
	static private function CaseHistorySave($case_id,$paramName,$new_value,$old_value){
		
			$query = "SELECT ID FROM  `".self::$TABLE_HISTORY_PARAM."` WHERE name='$paramName' ";
			$mysql_result = mysql_query($query);
			
			$pid = 0;
			if (mysql_num_rows($mysql_result) == 0){
				$qu = "INSERT INTO `".self::$TABLE_HISTORY_PARAM."` (name) VALUES ('$paramName') ";
				$mr = mysql_query($qu);
				if ($mr){
					$pid = mysql_insert_id();
				}				
			}else{
				$row = mysql_fetch_array($mysql_result);
				$pid = $row['ID'];
			}
			
			if ($pid > 0 ){
				$query = "INSERT INTO coris_assistance_cases_change_history  SET case_id='$case_id',ID_param='$pid',ID_user='".$_SESSION['user_id']."' ,date=now()";				
				
				if ( is_int($new_value) && is_int($old_value) ){
					$query .= ", old_value='$old_value',new_value='$new_value'";					
				}else{
					$query .= ", txt_old_value='$old_value',txt_new_value='$new_value'";					
				}
					
				$mr = mysql_query($query);
				if (!$mr){
					echo "QE: ".$query."<br><br>".mysql_error();
				}
			}						
	}
	
	
	static function getCaseCause($name,$def,$tryb=0,$typ,$option=''){	
	$result='';
		
	
	if ($tryb){
			$query = "SELECT l2.ID,l1.name As l1 ,l2.name As l2
				FROM coris_assistance_cases_cause_l2 l2, coris_assistance_cases_cause_l1  l1  WHERE l1.ID_case_type='$typ'  
					 AND l1.ID = l2.ID_causel1 AND l2.ID='$def'
					ORDER BY l1.`sort`, l2.`sort`";					
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 440px;" disabled>';					
				$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['l1'].' > '.$row2['l2'].'</option>';
			 $result .= '</select>';			    	 	
			 return $result;				
	}else{
	
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 440px;" '.$option.'>';
			$query = "SELECT l2.ID,l1.name As l1,l2.name As l2
				FROM coris_assistance_cases_cause_l2 l2, coris_assistance_cases_cause_l1  l1  WHERE l1.ID_case_type='$typ'  
					 AND l1.ID = l2.ID_causel1
					ORDER BY l1.`sort`, l2.`sort`";					
			
			$first = 1;
			$mysql_result = mysql_query($query);
			$result .= '<option></option>';
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['l1'].' > '.$row2['l2'].'</option>';							
			}
		  $result .= '</select>';
		  $result .= '<input type="hidden" name="'.$name.'_old" id="'.$name.'_old" value="'.$def.'">';
	}
	return $result;															
 }		
	
	
	static function getCaseInfo($case_id){
			
			$query = "SELECT coris_contrahents.contrahent_id ,coris_contrahents.name,coris_contrahents.o_klnagsim As shortName,
			coris_assistance_cases.paxname,coris_assistance_cases.paxname,coris_assistance_cases.paxsurname,
			coris_assistance_cases.type_id,coris_assistance_cases.number,coris_assistance_cases.year,coris_assistance_cases.client_ref, concat(number,'/',substring(year,3,2),'/',type_id,'/',client_id) As fullNumber,  
			eventdate,policy,client_ref,coris_assistance_cases.coris_branch_id,coris_assistance_cases.liquidation_user_id
			FROM coris_assistance_cases,coris_contrahents  WHERE coris_assistance_cases.case_id='$case_id' AND coris_contrahents.contrahent_id=coris_assistance_cases.client_id LIMIT 1" ;
			$mysql_result = mysql_query($query) OR die (mysql_error());
			
			if (mysql_num_rows($mysql_result)==0) return null;
			$row= mysql_fetch_array($mysql_result);
			return $row;
	}
	
	static function updateFullNumber($case_id){
				//$qu = "UPDATE coris_assistance_cases SET full_number=concat(number,'/',year,'/',type_id,'/',client_id)  WHERE case_id='$case_id'	LIMIT 1";
				$qu = "UPDATE coris_assistance_cases SET full_number=concat(number,'/',SUBSTRING(year,3,2),'/',type_id,'/',client_id)  WHERE case_id='$case_id'	LIMIT 1";
				$mr = mysql_query($qu);
				if ($mr){
						
				}else{
					
					echo "QE: ".$qu."<br><br>".mysql_error();
				}
		
				//
		
	} 
	
	static function getReserve($case_id,$expenses_id=0,$claims_id=0){

			if ($expenses_id > 0 ){
					$query = "SELECT rezerwa,currency_id  FROM coris_assistance_cases_nreserve WHERE ID_case ='$case_id' AND ID_expenses = '$expenses_id' ";
			}else if ($claims_id > 0 ){
					$query = "SELECT rezerwa,currency_id  FROM coris_assistance_cases_nreserve WHERE ID_case ='$case_id' AND ID_claims = '$claims_id' ";
			}else{
					$query = "SELECT SUM(rezerwa) As rezerwa ,currency_id  FROM coris_assistance_cases_nreserve WHERE ID_case ='$case_id' GROUP BY currency_id ";				
			}
			//	echo $query;	
			$mr = mysql_query($query);
			if ((mysql_num_rows($mr) == 0)){	
				return array('rezerwa' => 0.00,'currency_id' => 'PLN' );
			}else{				
				$row = mysql_fetch_array($mr);				
				return $row;
			}			
	}
	
	static function setReserve($case_id,$expenses_id,$rezerwa,$waluta,$claim_id=0){		 				
			if ($expenses_id > 0 )
				$query = "SELECT ID,rezerwa  FROM coris_assistance_cases_nreserve WHERE ID_case ='$case_id' AND ID_expenses = '$expenses_id' ";
		
			
			if ($claim_id > 0 )
				$query = "SELECT ID,rezerwa  FROM coris_assistance_cases_nreserve WHERE ID_case ='$case_id' AND ID_claims = '$claim_id' ";
		
			
			$mr = mysql_query($query);
			if ((mysql_num_rows($mr) == 0)){			
				$qi = "INSERT INTO coris_assistance_cases_nreserve
					 SET ID_case='$case_id', ID_expenses='$expenses_id', ID_claims = '$claim_id',	
                    rezerwa='$rezerwa',	currency_id='$waluta',
                    ID_user='".Application::getCurrentUser()."',date=now() ";
				$mr = mysql_query($qi);
			//	echo $qi;
				//echo '<hr>'.nl2br(print_r(debug_backtrace()));
				if (!$mr){
					echo "QE: ".$qi."<br><br>".mysql_error();
				}
				$rezerwa_id = mysql_insert_id();
				self::setReserveHistory($rezerwa_id,0,$rezerwa,$waluta);
			}else{
				$row = mysql_fetch_array($mr);				
				$old_rezerwa = $row['rezerwa'];	
				$rezerwa_id = $row['ID'];
				
				if ($old_rezerwa != $rezerwa){
					$qu = " UPDATE coris_assistance_cases_nreserve SET 	
                    rezerwa='$rezerwa',	currency_id='$waluta',
                    ID_user='".Application::getCurrentUser()."',date=now()
					WHERE ID='$rezerwa_id' ";
					//echo $qu;
					
					$mr = mysql_query($qu);
				if (!$mr){
					echo "QE: ".qu."<br><br>".mysql_error();
				}
					self::setReserveHistory($rezerwa_id,$old_rezerwa,$rezerwa,$waluta);
				}
			}
	}    	
	
	static function setReserveHistory($rezerwa_id,$old_rezerwa,$rezerwa,$waluta){
				$qi = 	" INSERT INTO coris_assistance_cases_nreserve_history 
				 SET ID_rezerwa='$rezerwa_id',rezerwa_stara= '$old_rezerwa',rezerwa_nowa='$rezerwa',	
				 currency_id='$waluta',
                    ID_user='".Application::getCurrentUser()."',date=now()
				";
				$mr = mysql_query($qi);			
	}
	
	
	static function setGLobalReserveStart($case_id,$case_rezerwa,$case_rezerwa_waluta,$expense_id=0,$claims_id=0){		
						
				$qi = "INSERT INTO coris_assistance_cases_global_reserve
					 SET case_id='$case_id', 	
                    rezerwa_globalna='$case_rezerwa',	currency_id='$case_rezerwa_waluta',
                    ID_user='".Application::getCurrentUser()."',date=now() ";
				$mr = mysql_query($qi);
				if (!$mr){
					echo "QE: ".$qi."<br><br>".mysql_error();
				}
				self::setGLobalReserveHistory($case_id,0,$case_rezerwa,$case_rezerwa_waluta,$expense_id,$claims_id);
				
	}
	
	static function checkGLobalReserve($case_id){		
			$query = "SELECT ID,rezerwa_globalna FROM coris_assistance_cases_global_reserve WHERE case_id='$case_id' ";
			$mr = mysql_query($query);
			if ((mysql_num_rows($mr) == 0)){		
					return false;			
			}else{
					return true;
			}
	}
	
	static function setGLobalReserve($case_id,$case_rezerwa,$case_rezerwa_waluta,$expense_id=0,$claims_id=0){		
			$query = "SELECT ID,rezerwa_globalna FROM coris_assistance_cases_global_reserve WHERE case_id='$case_id' ";
			$mr = mysql_query($query);
			if ((mysql_num_rows($mr) == 0)){			
				$qi = "INSERT INTO coris_assistance_cases_global_reserve
					 SET case_id='$case_id', 	
                    rezerwa_globalna='$case_rezerwa',	currency_id='$case_rezerwa_waluta',
                    ID_user='".Application::getCurrentUser()."',date=now() ";
				$mr = mysql_query($qi);
				if (!$mr){
					echo "QE: ".$qi."<br><br>".mysql_error();
				}
				self::setGLobalReserveHistory($case_id,0,$case_rezerwa,$case_rezerwa_waluta,$expense_id,$claims_id);
			}else{
				$row = mysql_fetch_array($mr);				
				$old_rezerwa = $row['rezerwa_globalna'];	
					
				if ($old_rezerwa != $case_rezerwa){
					$qu = " UPDATE coris_assistance_cases_global_reserve SET 	
                    rezerwa_globalna='$case_rezerwa',	currency_id='$case_rezerwa_waluta',
                    ID_user='".Application::getCurrentUser()."',date=now()
					WHERE case_id='$case_id' ";
					//echo $qu;					
					$mr = mysql_query($qu);
					
				if (!$mr){
					echo "QE: ".qu."<br><br>".mysql_error();
				}
					self::setGLobalReserveHistory($case_id,$old_rezerwa,$case_rezerwa,$case_rezerwa_waluta,$expense_id,$claims_id);
				}
			}
	}    	
	
	static function setGLobalReserveHistory($case_id,$old_case_rezerwa,$case_rezerwa,$case_rezerwa_waluta,$expense_id,$claims_id){
				$qi = 	" INSERT INTO coris_assistance_cases_global_reserve_history
				 SET case_id='$case_id',rezerwa_stara= '$old_case_rezerwa',rezerwa_nowa='$case_rezerwa',	
				 currency_id='$case_rezerwa_waluta',
				 ID_expenses ='$expense_id', ID_claims = '$claims_id' ,
                 ID_user='".Application::getCurrentUser()."',date=now()
				";
				$mr = mysql_query($qi);		
			  if (!$mr){
					echo "QE: ".$qi."<br><br>".mysql_error();
				}		
	}
	
	static function getGLobalReserve($case_id){
		
		$query2 = "SELECT * FROM coris_assistance_cases_global_reserve  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		
		
		if (mysql_num_rows($mysql_result2) > 0){
				$row=mysql_fetch_array($mysql_result2);
				return array('rezerwa' =>$row['rezerwa_globalna'],'currency_id' => $row['currency_id'] );
		}else{
				return array('rezerwa' => 0.00,'currency_id' => 'PLN' );
		}
		
		
	}
	static function getFullCaseInfo($case_id){
			
				$query = "SELECT ac.case_id, ac.number, ac.year, ac.full_number,ac.client_id, ac.type_id, ac.client_ref, ac.user_id, ac.date, ac.paxname, ac.paxsurname,ac.paxsex, ac.paxdob,ac.pax_email,ac.pax_pesel,
	 ac.policy,ac.policy_series, ac.cart_number, ac.event, ac.eventdate, ac.country_id, ac.city, ac.post, ac.watch, ac.ambulatory, ac.hospitalization, ac.transport, ac.decease, ac.archive, 
	 ac.costless, ac.unhandled, ac.reclamation, ac.status_client_notified, ac.status_policy_confirmed, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_account_complete, ac.status_settled, ac.attention,ac.attention2, DATE(ac.archive_date) AS archive_date, acd.notificationdate, acd.informer, acd.validityfrom, acd.validityto, acd.policypurchasedate, acd.policypurchaselocation, acd.policyamount, acd.policycurrency_id, acd.circumstances, acd.comments,ac.marka_model,ac.nr_rej,ac.vin,acd.paxphone ,acd.paxmobile,ac.adress1,ac.adress2,acd.paxaddress, acd.paxpost, acd.paxcity, acd.paxcountry, acd.paxphone, acd.paxmobile ,
	 acd.validityfromDep,acd.validitytoDep,ac.telefon1 ,ac.telefon2,ac.status_briefcase_found,ac.liquidation,ac.claim_handler_date,ac.claim_handler_user_id,
	acd.ehic_no,acd.validityToEhic,acd.ehic_user_id,acd.ehic_date, ac.ID_cause, ac.status_send,ac.coris_branch_id,ac.liquidation_user_id
	FROM coris_assistance_cases ac, coris_assistance_cases_details acd 
	WHERE ac.case_id = '$case_id' AND  ac.case_id = acd.case_id ";
		
			$mysql_result = mysql_query($query) OR die (mysql_error());
			
			if (mysql_num_rows($mysql_result)==0) return null;
			$row= mysql_fetch_array($mysql_result);
			return $row;
	}
	
	static function getCaseBarnch($case_id){
			
				$query = "SELECT ac.coris_branch_id
					FROM coris_assistance_cases ac 
				WHERE ac.case_id = '$case_id'  ";
		
			$mysql_result = mysql_query($query) OR die (mysql_error());
			
			if (mysql_num_rows($mysql_result)==0) return null;
			$row= mysql_fetch_array($mysql_result);
			return $row['coris_branch_id'];
	}
	
	
	static function getCaseClient($case_id){
			
			$query = "SELECT client_id  FROM coris_assistance_cases   WHERE coris_assistance_cases.case_id='$case_id' " ;
			$mysql_result = mysql_query($query) OR die (mysql_error());
			
			if (mysql_num_rows($mysql_result)==0) return null;
			$row= mysql_fetch_array($mysql_result);
			return $row['client_id'];
	}


	static function getCaseClientName($case_id){

			$query = "SELECT coris_contrahents.name  FROM coris_assistance_cases,coris_contrahents   WHERE coris_contrahents.contrahent_id =  coris_assistance_cases.client_id AND coris_assistance_cases.case_id='$case_id' " ;
			$mysql_result = mysql_query($query) OR die (mysql_error());

			if (mysql_num_rows($mysql_result)==0) return null;
			$row= mysql_fetch_array($mysql_result);
			return $row['name'];
	}

	
	static function getCountryName($country_id,$lang='pl'){
		$query = "SELECT * FROM coris_countries WHERE country_id='$country_id' ";
		$mysql_result = mysql_query($query);
		$row = mysql_fetch_array($mysql_result);
		if ($lang=='pl')
			return $row['name'];
		else 	
			return $row['name_eng'];	
	}
	
	
	function wysw_oszczednosci($def,$name){
                global $lang;

                      	

			$query = "SELECT * FROM coris_assistance_cases_expenses_saving ";	
		$mysql_result = mysql_query($query);
			$result = '<select name='.$name.' id='.$name.'>';
			$result .= '<option></option>';
			while ($row2 = mysql_fetch_array($mysql_result)) {
						//name_eng
						$val = ( ($lang=='en' && $row2['name_eng'] != '' ) ? $row2['name_eng'] : $row2['name'] );// 
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$val.'</option>';							
			}
		  $result .= '</select>';
		  
		  return $result;
                            	
	}
	
static  function  wysw_status_history($case_id){	
		$result='';		
		
				$query = "SELECT ch.*,cs.nazwa As status, cs2.nazwa As status2 FROM coris_assistance_cases_status cs ,
					coris_assistance_cases_state_history ch 
						LEFT JOIN  coris_assistance_cases_status2 cs2 ON (ch.ID_case_state2 = cs2.ID)
				 			
					WHERE  ch.ID_case='$case_id' 
					AND  cs.ID = ch.ID_case_state
					ORDER BY  `date`";		

			//	echo $query ;
				$mysql_result = mysql_query($query);
				
				$result .= '<b>Historia statusów</b>
				<br><table  cellpadding="5" cellspacing="1">';
				$result .= '<tr bgcolor="#BBBBBB">';
					$result .= '<td align="center"><b>Data</b></td>';
					$result .= '<td align="center"><b>U¿ytkownik</b></td>';
					$result .= '<td align="center"><b>Nowy status</b></td>';					
				$result .= '</tr>';
				
				while ($row2 = mysql_fetch_array($mysql_result)) {
					$result .= '<tr bgcolor="#EEEEEE">';
						$result .= '<td>'.$row2['date'].'</td>';
						$result .= '<td>'.Application::getUserName($row2['ID_user']).'</td>';
						$result .= '<td>'.$row2['status']. ($row2['ID_case_state'] == '3'  ?  ' - '.$row2['status2'] : '').' </td>';					
					$result .= '</tr>';
					
				}
			  
			  	$result .= '</table>';
			  
		
		return $result;															
	}
static  function  wysw_status($name,$def,$def2,$tryb=0){	
		$result='';		
		if ($tryb){
				$query = "SELECT * FROM coris_assistance_cases_status    WHERE  ID='$def' ORDER BY  kolejnosc";		

				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 150px; margin:5px;" disabled>';					
					$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
				 $result .= '</select>';
				 $result .= self::wysw_status2($name.'_2',$def, $def2,$tryb);
				 return $result;						
		}else{							
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 150px;margin:5px;"  '.$option.' onChange="aktualizacja_statusu(this.value,'.$name.'_2'.')">';
			//<option value=""></option>
				$query = "SELECT * FROM coris_assistance_cases_status   ORDER BY kolejnosc";						
				
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
			  $result .= self::wysw_status2($name.'_2',$def, $def2,$tryb);
			  $result .= '<input type="hidden" name="old_'.$name.'"  id="old_'.$name.'" value="'.$def.'">';
			  $result .= '
			  <script>
			  function aktualizacja_statusu(val,obj){
					if (val==3){
						$(obj).style.visibility=\'visible\';
					}else{
						$(obj).style.visibility=\'hidden\';
					}
					
				}
			  
			  </script>
			  ';
		}
		return $result;															
	}

static  function  wysw_status2($name,$def,$def2,$tryb=0){	
		$result='';
		$style='';
		if ($def==3){
				$style="visibility:visible;";
		}else{
			$style="visibility:hidden;";
		}		
		
		if ($tryb){
				$query = "SELECT * FROM coris_assistance_cases_status2    WHERE  ID='$def2' ORDER BY  kolejnosc";		
				
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 150px; margin:5px;'.$style.'" disabled>';					
					$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
				 $result .= '</select>';
				 return $result;						
		}else{							
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 150px;margin:5px;'.$style.'" '.$option.'>';
			//<option value=""></option>
				$query = "SELECT * FROM coris_assistance_cases_status2   ORDER BY kolejnosc";						
				
				$mysql_result = mysql_query($query);
				$result .= '<option value=""></option>';
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def2) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
			  $result .= '<input type="hidden" name="old_'.$name.'" id="old_'.$name.'" value="'.$def2.'">';
		}
		return $result;															
	}
	
	
	static function setCaseState($case_id,$case_state,$case_state2){		
			$query = "SELECT ID_case_state,ID_case_state2 FROM coris_assistance_cases WHERE case_id='$case_id' ";
			$mr = mysql_query($query);
			
			$row = mysql_fetch_array($mr);				
			$old_state = $row['ID_case_state'];	
			$old_state2 = $row['ID_case_state2'];	
					
				if ($old_state != $case_state || $old_state2!=$case_state2 ){
					
					$query = "UPDATE coris_assistance_cases SET 
							ID_case_state ='$case_state',ID_case_state2='$case_state2'					
						WHERE case_id ='$case_id' LIMIT 1";												
								
					$mr = mysql_query($query);
					
					if (!$mr){
						echo "QE: ".$query."<br><br>".mysql_error();
					}
					self::setCaseStateHistory($case_id,$case_state,$case_state2);
				}
	}
	    		
	static function setCaseStateHistory($case_id,$case_state,$case_state2){
				$qi = 	" INSERT INTO coris_assistance_cases_state_history
				 SET  	ID_case='$case_id' ,
				 ID_case_state ='$case_state',ID_case_state2='$case_state2',	
                 ID_user='".Application::getCurrentUser()."',date=now()
				";
				$mr = mysql_query($qi);		
			  if (!$mr){
					echo "QE: ".$qi."<br><br>".mysql_error();
				}		
	}
	static function setCaseOperatingUser($case_id,$operating_user_id){		
			$query = "SELECT operating_user_id FROM coris_assistance_cases WHERE case_id='$case_id' ";
			$mr = mysql_query($query);
			
			$row = mysql_fetch_array($mr);				
			$old_user = $row['operating_user_id'];	
			
					
				if ($old_user != $operating_user_id  ){
					
					$query = "UPDATE coris_assistance_cases SET 
							operating_user_id	 = '$operating_user_id'			
						WHERE case_id ='$case_id' LIMIT 1";												
								
					$mr = mysql_query($query);
					
					if (!$mr){
						echo "QE: ".$query."<br><br>".mysql_error();
					}
					self::setCaseOperatingUserHistory($case_id,$operating_user_id);
				}
	}
	    		
	static function setCaseOperatingUserHistory($case_id,$operating_user_id){
				$qi = 	" INSERT INTO coris_assistance_cases_operating_history
				 SET  	ID_case='$case_id' ,
				 operating_user_id ='$operating_user_id',	
                 ID_user='".Application::getCurrentUser()."',date=now()
				";
				$mr = mysql_query($qi);		
			  if (!$mr){
					echo "QE: ".$qi."<br><br>".mysql_error();
				}		
	}
	
}

?>