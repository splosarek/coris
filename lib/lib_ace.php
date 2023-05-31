<?php


Class ACECase{
	
	static $TABLE_ANNONUNCE = 'coris_ace_announce';
	static $TABLE_program = 'coris_ace_program';
	
	static function getCaseInfo($case_id){
	
		$query2 = "SELECT * FROM ".self::$TABLE_ANNONUNCE."  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		return $row_case_ann;
	}
	static function getCaseProgram($case_id){
	
		$query2 = "SELECT * FROM ".self::$TABLE_ANNONUNCE.",".self::$TABLE_program." 
					WHERE ".self::$TABLE_ANNONUNCE.".case_id = '$case_id'
						AND ".self::$TABLE_program.".ID = ".self::$TABLE_ANNONUNCE.".ID_program 
					";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		return $row_case_ann;
	}
	
	static function  umowa_dane( $case_id , $tryb ){
			$dane = self::getCaseInfo($case_id);
			
			$result = '<br><table cellpadding="1" cellspacing="1" border="0" bgcolor="#e0e0e0" width="320">';
				$result .= '<tr><td width="60" align="right"><b><small>Program:</small></b></td><td>'.self::wysw_program( 'ace_program',$dane['ID_program'],$tryb,' style="width: 240px;" ').'</td></tr>';
			$result .= '</table>';
			
			return $result;
	}	
	static function  wysw_program( $name,$def,$tryb=0,$option=''){	
		$result='';	
		if ($tryb){
				$query = "SELECT * FROM ".self::$TABLE_program."    WHERE  ID='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" '.$option.' style="font-size: 9px;width: 300px;" disabled  >';					
					$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
				 $result .= '</select>';
				 return $result;					
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" '.$option.'  style="font-size: 9px;width: 300px;"  >
						<option value=""></option>';
				$query = "SELECT * FROM ".self::$TABLE_program."   ORDER BY `sort`";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}

	static function aktualizacja_programu($case_id,$program){ 
	
			$qt = "SELECt case_id FROM ".self::$TABLE_ANNONUNCE."  WHERE case_id='$case_id'";
			$mt = mysql_query($qt);			
			
			if (mysql_num_rows($mt)==0){
				$query = "INSERT INTO ".self::$TABLE_ANNONUNCE." SET case_id='$case_id', ID_program='$program' ";
								
			}else{
				$query = "UPDATE ".self::$TABLE_ANNONUNCE." SET ID_program='$program'  WHERE case_id='$case_id' LIMIT 1";				
			}

			$mt = mysql_query($query);	
			if (!$mt){echo "<br>QE: $query, <br>".mysql_error();}	
	}	
	
}
?>