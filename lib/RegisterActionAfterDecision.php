<?php


class RegisterActionAfterDecision{
	
	
	static function register($case_id,$dec_id){
		
		$user_id = $_SESSION['user_id'];
		
		$query = "INSERT INTO coris_action_after_decision SET case_id='$case_id', ID_decision='$dec_id',reg_dec_date=now(),ID_user='$user_id' ";
		mysql_query($query);
		return mysql_insert_id();
		
	}
	
	
	static function registerUserAnswer($case_id,$dec_id,$answer){
		$query = "UPDATE coris_action_after_decision SET user_action_date=now(),user_action='$answer' WHERE case_id='$case_id' AND ID_decision='$dec_id' ";
		$mr = mysql_query($query);
	}
	
	static function registerUserAction($case_id,$dec_id,$action,$reserv_id){
		$query = "UPDATE coris_action_after_decision SET user_reserve_date=now(),user_reserv_action='$action',ID_reserve_change='$reserv_id'  WHERE case_id='$case_id' AND ID_decision='$dec_id' ";
		echo $query;
		
		$mr = mysql_query($query);
		//exit;
	}
	
}


?>