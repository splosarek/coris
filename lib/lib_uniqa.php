<?php


Class UniqaCase{

	static function getCaseInfo($case_id){
	
		$query2 = "SELECT * FROM coris_uniqa_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		return $row_case_ann;
	}

	static function CaseTypeUpdate($case_id,$new_value,$old_value){						
			if ($new_value != $old_value){
				$qt = "SELECT * FROM coris_uniqa_announce WHERE  case_id='$case_id' ";
				 $mt = mysql_query($qt);
				 
				 if (mysql_num_rows($mt)==0){
				 	$qi = "INSERT INTO coris_uniqa_announce SET ID_type='$new_value', case_id='$case_id'";
				 	$mi = mysql_query($qi);
					if (!$mi){						
						echo "QE: ".$qu."<br><br>".mysql_error();
					}				 	
				 }else{				 
					$qu = "UPDATE coris_uniqa_announce SET ID_type='$new_value' WHERE case_id='$case_id'	LIMIT 1";
					$mr = mysql_query($qu);
					if ($mr){
						//	self::CaseHistorySave($case_id,'CaseCause',$new_value,$old_value);
					}else{
						
						echo "QE: ".$qu."<br><br>".mysql_error();
					}
				 }
			}
	}
	
	static function  wysw_case_type($name,$def,$tryb=0,$option=''){		
		$result='';		
		if ($tryb){
				$query = "SELECT * FROM  coris_uniqa_case_type   WHERE ID ='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 150px;" disabled>';					
					$result .= '<option value="'. $row['ID'] .'">'. $row['name'] .'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 150px;"  '.$option.'>
						<option value=""></option>';
				$query = "SELECT * FROM coris_uniqa_case_type   ORDER BY ID ";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['name'].'</option>';
				}
			  $result .= '</select>';
			 $result .= '<input type="hidden" name="'.$name.'_old" id="'.$name.'_old" value="'.$def.'">';
		}
		return $result;															
	}
}
?>