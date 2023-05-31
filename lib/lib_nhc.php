<?php


Class NHCCase{

	static function getCaseInfo($case_id){
	
		$query2 = "SELECT * FROM coris_nhc_announce  WHERE case_id = '$case_id'";
		$mysql_result2 = mysql_query($query2);
		$row_case_ann = mysql_fetch_array($mysql_result2);			
		return $row_case_ann;
	}



	static function  wysw_policy_type($name,$def,$tryb=0,$option=''){
		
		$result='';
		
		if ($tryb){
				$query = "SELECT * FROM coris_nhc_code_a   WHERE product_code ='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 250px;" disabled>';					
					$result .= '<option value="'. $row['product_code'] .'">'. $row['ec_type'] .' | '.$row['description'].'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 250px;"  '.$option.'>
						<option value=""></option>';
				$query = "SELECT coris_nhc_code_a.* FROM coris_nhc_code_a   ORDER BY ec_type , description ";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['product_code'] .'" '. (($row2['product_code'] == $def) ? "selected" : "") .'>'.$row2['ec_type'].' | '.$row2['description'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}


	static function  wysw_main_causes($name,$def,$tryb=0,$product_code,$option=''){
		
		$result='';
		
		
		$col_ds = 'description_en';
		
		if ($_SESSION['GUI_language']=='pl'){
			
			$col_ds = 'description_pl';		
		}
		
		if ($tryb){
				$query = "SELECT * FROM coris_nhc_cause   WHERE main_cause  ='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 250px;" disabled>';					
					$result .= '<option value="'. $row['main_cause'] .'">'.$row[$col_ds].'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 250px;"  '.$option.'>
						<option value=""></option>';
				$query = "SELECT DISTINCT coris_nhc_cause.* FROM coris_nhc_cause,coris_nhc_code_g  WHERE  coris_nhc_cause.main_cause = coris_nhc_code_g.main_cause AND coris_nhc_code_g.product_code = '$product_code'  ORDER BY ".$col_ds;						
				$mysql_result = mysql_query($query);			
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['main_cause'] .'" '. (($row2['main_cause'] == $def) ? "selected" : "") .'>'.$row2[$col_ds].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}


	static function  wysw_diagn_code($name,$def,$tryb=0,$option=''){
		
		$result='';
		
		$col_gr = 'diagnose_group_en';
		$col_ds = 'description_en';
		
		if ($_SESSION['GUI_language']=='pl'){
			$col_gr = 'diagnose_group_pl';
			$col_ds = 'description_pl';		
		}
		
		if ($tryb){
				$query = "SELECT * FROM coris_nhc_diagnose    WHERE diagnose_code  ='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 400px;" disabled>';					
					$result .= '<option value="'. $row['diagnose_code'] .'">'.$row[$col_gr].' | '.$row[$col_ds].'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 400px;"  '.$option.'>
						<option value=""></option>';
				$query = "SELECT * FROM coris_nhc_diagnose    ORDER BY $col_gr ,$col_ds ";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['diagnose_code'] .'" '. (($row2['diagnose_code'] == $def) ? "selected" : "") .'>'.$row2[$col_gr].' | '.$row2[$col_ds].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}
 static function  wysw_rezerwy($name,$def,$tryb=0,$option=''){
	
	$result='';
	
	$col_gr = 'name_en';

	
	if ($_SESSION['GUI_language']=='pl'){
		$col_gr = 'name_pl';
	}
	
	if ($tryb){
			$query = "SELECT * FROM coris_nhc_reserve     WHERE ID   ='$def' ";						
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 200px;"  disabled>';					
				$result .= '<option value="'. $row['diagnose_code'] .'">'.$row[$col_gr].'</option>';
			 $result .= '</select>';
			 return $result;			
		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 200px;"  '.$option.'>
					<option value=""></option>';
			$query = "SELECT * FROM coris_nhc_reserve     ORDER BY $col_gr ";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2[$col_gr].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}

	static function  wysw_country_nhc($name,$def,$tryb=0,$option=''){
		
		$result='';
		
		if ($tryb){
				$query = "SELECT * FROM coris_nhc_country WHERE country_code  ='$def' ";						
				$mysql_result = mysql_query($query);
				$row = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 250px;" disabled>';					
					$result .= '<option value="'. $row['country_code'] .'">'.$row['country_name'].' | '.$row['country_code'].'</option>';
				 $result .= '</select>';
				 return $result;			
			
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 250px;"  '.$option.'>
						<option value=""></option>';
				$query = "SELECT * FROM coris_nhc_country     ORDER BY country_code  ";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['country_code'] .'" '. (($row2['country_code'] == $def) ? "selected" : "") .'>'.$row2['country_name'].' | '.$row2['country_code'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}

	static function  wysw_sales_object($name,$policy_type,$tryb=0,$sales_object,$option=''){	
		$result='';
		
		if ($tryb){
				$query = "SELECT ID,nazwa FROM coris_nhc_code_b   WHERE product_code  ='$product_code' AND sales_object='$sales_object' ";						
				$mysql_result = mysql_query($query);
				$row2 = mysql_fetch_array($mysql_result);			
				$result = '<select name="'.$name.'" style="font-size: 8pt;width: 150px;" disabled>';					
					$result .= '<option value="'. $row2['sales_object'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['description'].'</option>';
				 $result .= '</select>';
				 return $result;				
		}else{
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;width: 150px;" '.$option.'>
						<option value=""></option>';
				$query = "SELECT ID,nazwa FROM coris_nhc_code_b    WHERE product_code  ='$product_code'  ORDER BY description";						
				$mysql_result = mysql_query($query);
				while ($row2 = mysql_fetch_array($mysql_result)) {
							$result .= '<option value="'. $row2['sales_object'] .'" '. (($row2['product_code'] == $product_code && $row2['sales_object'] == $sales_object ) ? "selected" : "") .'>'.$row2['description'].'</option>';
				}
			  $result .= '</select>';
		}
		return $result;															
	}



	static function wysw_country($name,$def,$tryb=0){
		if ($tryb){
			$result = ' <select style="font-size: 8pt;" name="'.$name.'list" disabled>';
			$result .=  '<option value="'.$def.'" >'.$def.'</option>';
			$result .='</select>';
			return $result;
			
		}
		$result = '<input type="text" id="'.$name.'" name="'.$name.'" value="'.$def .'" size="3" maxlength="2" onBlur="document.getElementById(\''.$name.'list\').value = this.value.toUpperCase(); this.value = this.value.toUpperCase()" style="text-align: center">
		                        <select style="font-size: 8pt;" name="'.$name.'list" id="'.$name.'list" onChange="document.getElementById(\''.$name.'\').value = this.value">
		                            <option value=""></option>';
		
		$mysql_result = mysql_query("SELECT country_id, name, prefix FROM coris_countries ORDER BY name");
		while ($row2 = mysql_fetch_array($mysql_result)) {
			$result .=  '<option value="'.$row2['country_id'] .'" '. (($row2['country_id'] == $def) ? "selected" : "" ).'>'.$row2['name'] .'</option>';
		
		}
	
		$result .='</select>';
		
		return $result;
	}
	
}
?>