<?php


Class CardifCase{

	function getCaseCardifInfo($case_id){
	
		$query2 = "SELECT * FROM coris_cardif_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		return $row_case_ann;
	}

}

function  wysw_typy_umowy($name,$def,$tryb=0,$option=''){
	
	$result='';
	
	if ($tryb){
			$query = "SELECT * FROM coris_cardif_typ_umowy   WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 300px;" disabled>';					
				$result .= '<option value="'. $row['ID'] .'">'.$row['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 300px;"  '.$option.'>
					<option value=""></option>';
			$query = "SELECT * FROM coris_cardif_typ_umowy   ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}

function  wysw_wariant_umowy($name,$def,$tryb=0,$typ_umowy,$option=''){	
	$result='';
	
	if ($tryb){
			$query = "SELECT ID,nazwa FROM coris_cardif_wariant_umowy   WHERE ID_typ_umowy ='$typ_umowy' AND  ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 300px;" disabled>';					
				$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;				
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 300px;" '.$option.'>
					<option value=""></option>';
			$query = "SELECT ID,nazwa FROM coris_cardif_wariant_umowy    WHERE ID_typ_umowy ='$typ_umowy' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}

function  wysw_swiadczenie($name,$def,$typ_umowy,$tryb=0,$option=''){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,nazwa FROM coris_cardif_swiadczenia  WHERE ID_typ_umowy ='$typ_umowy'  AND ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;width:220px;" '.$option.'>
					<option value=""></option>';
			$query = "SELECT ID,nazwa FROM coris_cardif_swiadczenia  WHERE ID_typ_umowy ='$typ_umowy'  ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}




?>