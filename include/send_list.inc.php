<?php

class SendList{
	
	
	
	
	
function check_user($user){
	$query = "SELECT user_id FROM coris_users WHERE username='$user' OR initials='$user' ";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result)>0){
		$row= mysql_fetch_array($mysql_result);
		return $row[0];
	}else
		return "null";
}

	static function getTowList($tow_id=0){
			$query = "SELECT contrahent_id,name FROM coris_contrahents WHERE send_lists=1 ORDER BY name ";
			$mysql_result = mysql_query($query);	
			$result='';
			$result .= '<select name="tow_id" onChange="submit();">';
			$result .= '<option value="0"> ---- </option>'; 
			while	($row= mysql_fetch_array($mysql_result)){
				$result .= '<option value="'.$row['contrahent_id'].'" '.( $row['contrahent_id']==$tow_id ? 'selected' : '' ).'>'.$row['name'].' ('.$row['contrahent_id'].')</option>';		
			}
			$result .= '</select>';
			return $result;
	}	
	
	
}


?>