<?php
class VoyageClaimDetails{
	
	
	
	var $ID;
	var $claimID;
	var $userID;
	var $date;
	var $note;
	var $kwota_roszczenia;
	var $waluta = 'EUR';
	var $kwota_zaakceptowana;
	var $wyplata_zaakceptowana;
	
	var $franszyza;
	var $franszyza_kwota;
	
	var $odliczenie;
	var $odliczenie_kwota;
	
	var $refundacja;
	var $refundacja_kwota;
	
	
	var $kwota_rws;
	var $kwota_rezerwa;
	
	var $status;
	var $status2;
	var $status_note;
	
	var $status_userID;
	var $status_date;
		
	var $status2_userID;
	var $status2_date;	
	var $status2_note;
	
	var $status2_lista = array('','Wys³ane do decyzji','Do poprawy','Decyzja');
		
	var $delete;
	private $old_values=array();
	
	function __construct($id,$claim_id=0,$new=0){				
		$this->ID = $id;
		$this->claimID = $claim_id;

		if ($new==0){
			$this->load();
		}
	}
	/**
	 * @return the $userID
	 */
	public function getUserID() {
		return $this->userID;
	}

	/**
	 * @return the $date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return the $note
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @return the $kwota_roszczenia
	 */
	public function getKwota_roszczenia() {
		return $this->kwota_roszczenia;
	}

	/**
	 * @return the $waluta
	 */
	public function getWaluta() {
		return $this->waluta;
	}

	/**
	 * @return the $kwota_zaakceptowana
	 */
	public function getKwota_zaakceptowana() {
		return $this->kwota_zaakceptowana;
	}

	/**
	 * @return the $kwota_rws
	 */
	public function getKwota_rws() {
		return $this->kwota_rws;
	}

	/**
	 * @return the $kwota_rezerwa
	 */
	public function getKwota_rezerwa() {
		return $this->kwota_rezerwa;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return the $status_note
	 */
	public function getStatus_note() {
		return $this->status_note;
	}

	/**
	 * @return the $status_userID
	 */
	public function getStatus_userID() {
		return $this->status_userID;
	}

	/**
	 * @return the $status_date
	 */
	public function getStatus_date() {
		return $this->status_date;
	}

	/**
	 * @param field_type $userID
	 */
	public function setUserID($userID) {
		$this->userID = $userID;
	}

	/**
	 * @param field_type $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param field_type $note
	 */
	public function setNote($note) {
		$this->note = $note;
	}

	/**
	 * @param field_type $kwota_roszczenia
	 */
	public function setKwota_roszczenia($kwota_roszczenia) {
		$this->kwota_roszczenia = $kwota_roszczenia;
	}

	/**
	 * @param field_type $waluta
	 */
	public function setWaluta($waluta) {
		$this->waluta = $waluta;
	}

	/**
	 * @param field_type $kwota_zaakceptowana
	 */
	public function setKwota_zaakceptowana($kwota_zaakceptowana) {
		$this->kwota_zaakceptowana = $kwota_zaakceptowana;
	}

	/**
	 * @param field_type $kwota_rws
	 */
	public function setKwota_rws($kwota_rws) {
		$this->kwota_rws = $kwota_rws;
	}

	/**
	 * @param field_type $kwota_rezerwa
	 */
	public function setKwota_rezerwa($kwota_rezerwa) {
		$this->kwota_rezerwa = $kwota_rezerwa;
	}

	/**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param field_type $status_note
	 */
	public function setStatus_note($status_note) {
		$this->status_note = $status_note;
	}

	/**
	 * @param field_type $status_userID
	 */
	public function setStatus_userID($status_userID) {
		$this->status_userID = $status_userID;
	}

	/**
	 * @param field_type $status_date
	 */
	public function setStatus_date($status_date) {
		$this->status_date = $status_date;
	}


	private function load(){
			$query = "SELECT * FROM coris_voyage_claims_details  WHERE ID = '$this->ID'";
			$mysql_result = mysql_query($query);
			if (mysql_num_rows($mysql_result) >0){
				$row = mysql_fetch_array($mysql_result);
					
				$this->setKwota_roszczenia($row['kwota_roszczenia']);							
				$this->setKwota_rws($row['kwota_rws']);		
				$this->setWaluta($row['currency_id']);		
				$this->setKwota_zaakceptowana($row['kwota_zaakceptowana']);						
				$this->setWyplata_zaakceptowana($row['wyplata_zaakceptowana']);	

				
				$this->setFranszyza($row['franszyza']);
				$this->setFranszyza_kwota($row['franszyza_kwota']);
				$this->setOdliczenie($row['odliczenie']);
				$this->setOdliczenie_kwota($row['odliczenie_kwota']);
				$this->setRefundacja($row['refundacja']);
				$this->setRefundacja_kwota($row['refundacja_kwota']);
						
				
				$this->setKwota_rezerwa($row['reserve']);		
									
				$this->setNote($row['note']);
				
				$this->setStatus($row['status']);
				
				$this->setStatus_note($row['note_status']);
				
				$this->setUserID($row['ID_user']);
				$this->setDate($row['date']);
				
				$this->setStatus_userID($row['status_ID_user']);							
				$this->setStatus_date($row['status_date']);

				$this->setStatus2($row['status2']);
				$this->setStatus2_userID($row['status2_ID_user']);							
				$this->setStatus2_date($row['status2_date']);			
				$this->setStatus2_note($row['status2_note']);			

				$this->old_values = $row;
			}
	}
	
	
	function zmienStatus2(){
			//if ($this->old_values['status2'] != $this->getStatus2() ){									
						$zm_status = " status2='".$this->getStatus2()."',status2_note='".$this->getStatus2_note()."', status2_ID_user='".$_SESSION['user_id']."',status2_date=now() ";
						$this->setStatus2_userID($_SESSION['user_id']);
						
						$query = "UPDATE  coris_voyage_claims_details SET 															
						$zm_status																		
						WHERE ID = '$this->ID' LIMIT 1";
						//echo $query;
						$mysql_result = mysql_query($query);	
						if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }		
						$this->updateLog();		
	  		//}					
	}
	
	
	function zmienStatus2DoAkceptacji(){
						$this->setStatus2(1);																						
						$query = "UPDATE  coris_voyage_claims_details SET 															
						status2=1																
						WHERE ID = '$this->ID' LIMIT 1";
						
						//echo $query;
						$mysql_result = mysql_query($query);	
						if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }
						$this->setStatus2_note('');
						$this->updateLog();
	}
	

	
	
	public function store(){						
			if ($this->ID > 0 ){				
				if ($this->delete){
					
					/*$query = "DELETE FROM  coris_assistance_cases_nreserve WHERE ID_claims = '$this->ID' LIMIT 1";
					$mysql_result = mysql_query($query);
					if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }
					//echo $query.'<br>'.mysql_error();*/
					
					
					$query = "DELETE FROM coris_voyage_claims_details WHERE ID = '$this->ID' LIMIT 1";
					$mysql_result = mysql_query($query);															
					if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }

					//echo '<hr>'.$query.'<br>'.mysql_error();
					
				}else{
					$zm_status='';
					
					if ($this->old_values['status'] != $this->getStatus() ){
						$zm_status= ",status='".$this->getStatus()."', status_ID_user='".$_SESSION['user_id']."',status_date=now() ";
						
						
					}					
					$query = "UPDATE  coris_voyage_claims_details SET 				
						kwota_roszczenia = '".str_replace(',', '.', $this->kwota_roszczenia)."',
						kwota_rws = '".str_replace(',', '.', $this->kwota_rws)."',
						reserve = '".str_replace(',', '.', $this->kwota_rezerwa)."',
						currency_id='$this->waluta',
						kwota_zaakceptowana='".str_replace(',', '.', $this->kwota_zaakceptowana)."',				
						wyplata_zaakceptowana='".str_replace(',', '.', $this->wyplata_zaakceptowana)."',
						franszyza='$this->franszyza',				
						franszyza_kwota='".str_replace(',', '.', $this->franszyza_kwota)."',
						odliczenie='$this->odliczenie',				
						odliczenie_kwota='".str_replace(',', '.', $this->odliczenie_kwota)."',				
						refundacja='$this->refundacja',					
						refundacja_kwota='".str_replace(',', '.', $this->refundacja_kwota)."',			
						note='".mysql_escape_string(stripslashes($this->note))."',
						ID_user='".$_SESSION['user_id']."',
						date=now(),									
						note_status='".mysql_escape_string(stripslashes($this->status_note))."'					
						$zm_status																		
						WHERE ID = '$this->ID' LIMIT 1";		
					$mysql_result = mysql_query($query);	
					if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }						
				}
			}else{			
				$query = "INSERT INTO coris_voyage_claims_details SET 
				ID_claims = '$this->claimID',
				kwota_roszczenia = '".str_replace(',', '.', $this->kwota_roszczenia)."',
				kwota_rws = '".str_replace(',', '.', $this->kwota_rws)."',
				reserve = '".str_replace(',', '.', $this->kwota_rezerwa)."',
				currency_id='$this->waluta',
				kwota_zaakceptowana='".str_replace(',', '.', $this->kwota_zaakceptowana)."',	
				wyplata_zaakceptowana='".str_replace(',', '.', $this->wyplata_zaakceptowana)."',
				franszyza='$this->franszyza',				
				franszyza_kwota='".str_replace(',', '.', $this->franszyza_kwota)."',
				odliczenie='$this->odliczenie',				
				odliczenie_kwota='".str_replace(',', '.', $this->odliczenie_kwota)."',
				refundacja='$this->refundacja',					
				refundacja_kwota='".str_replace(',', '.', $this->refundacja_kwota)."',			
				note='".mysql_escape_string(stripslashes($this->note))."',
				ID_user='".$_SESSION['user_id']."',
				date=now(),				
				status='1',
				note_status='".mysql_escape_string(stripslashes($this->status_note))."',
				status_ID_user='".$_SESSION['user_id']."',
				status_date=now() ";
				//echo '<br>'.$query;
				$mysql_result = mysql_query($query);
				if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }
				$this->ID = mysql_insert_id();								
			}
	}
	/**
	 * @return the $ID
	 */
	public function getID() {
		return $this->ID;
	}

	/**
	 * @return the $claimID
	 */
	public function getClaimID() {
		return $this->claimID;
	}

	/**
	 * @param field_type $ID
	 */
/*	public function setID($ID) {
		$this->ID = $ID;
	}
*/
	/**
	 * @param field_type $claimID
	 */
	public function setClaimID($claimID) {
		$this->claimID = $claimID;
	}

	public function getStatusName($state=0){
		if ($state == 0){
			$state=$this->status;
		}
		$query = "SELECT nazwa  FROM coris_voyage_claims_details_status WHERE ID='$state' "; 
		$mysql_result = mysql_query($query);			
		$row = mysql_fetch_array($mysql_result);
		return $row['nazwa'];				
	}
	
	public function getStatus2Name($state=0){
	if ($state == 0){
			$state=$this->status2;
		}
		return $this->status2_lista[$state];				
	}
	/**
	 * @param field_type $delete
	 */
	public function setDelete() {
		$this->delete = true;
	}
	
	
	public function getDelete() {
		return $this->delete;
	}
	/**
	 * @return the $wyplata_zaakceptowana
	 */
	public function getWyplata_zaakceptowana() {
		return $this->wyplata_zaakceptowana;
	}

	/**
	 * @param field_type $wyplata_zaakceptowana
	 */             
	public function setWyplata_zaakceptowana($wyplata_zaakceptowana) {
		$this->wyplata_zaakceptowana = $wyplata_zaakceptowana;
	}
	/**
	 * @return the $status2
	 */
	public function getStatus2() {
		return $this->status2;
	}

	/**
	 * @return the $status2_userID
	 */
	public function getStatus2_userID() {
		return $this->status2_userID;
	}

	/**
	 * @return the $status2_date
	 */
	public function getStatus2_date() {
		return $this->status2_date;
	}

	/**
	 * @return the $status2_note
	 */
	public function getStatus2_note() {
		return $this->status2_note;
	}

	/**
	 * @param field_type $status2
	 */
	public function setStatus2($status2) {
		$this->status2 = $status2;
	}

	/**
	 * @param field_type $status2_userID
	 */
	public function setStatus2_userID($status2_userID) {
		$this->status2_userID = $status2_userID;
	}

	/**
	 * @param field_type $status2_date
	 */
	public function setStatus2_date($status2_date) {
		$this->status2_date = $status2_date;
	}

	/**
	 * @param field_type $status2_note
	 */
	public function setStatus2_note($status2_note) {
		$this->status2_note = $status2_note;
	}


	public function updateLog(){
			$query = "INSERT INTO coris_voyage_claims_details_status_log 
			SET ID_claims_details = '".$this->getID()."',
			ID_user = '".$_SESSION['user_id']."',
			date = now(),
			status1 = '".$this->getStatus()."',
			status2 = '".$this->getStatus2()."',
			note = '".mysql_escape_string($this->getStatus2_note())."'
			";
			
			$mysql_result = mysql_query($query);	
			if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }			
	}
	
	
	
	
	
	public function createPayment($case_id,$kwota){
		//return ;

		if ($case_id>0){
				if ($kwota > 0.0){
					$query = "INSERT INTO coris_voyage_payment 
					SET ID_case='$case_id',
					ID_claims_details = '".$this->getID()."',			
					date=now(),
					status=0,amount='".$kwota."',payment_currency='EUR'";
					
				
					$mysql_result = mysql_query($query);	
					if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }
				}					
		}
	}
	
	public function DeletePayment(){		
		$query = "DELETE FROM coris_voyage_payment 
			WHERE  ID_claims_details = '".$this->getID()."' AND payment=0 ";
			$mysql_result = mysql_query($query);	
			if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }				
	}
	
	public function DeleteDecision(){
	
		$query = "SELECT * FROM coris_voyage_decisions_details WHERE ID_claims_details = '".$this->getID()."'";
		$mr = mysql_query($query);
		while($row=mysql_fetch_array($mr)){
			
			$query = "DELETE FROM coris_voyage_decisions 
			WHERE  ID = '".$row['ID_decisions']."'";
			$mysql_result = mysql_query($query);	
		}
		
		$query = "DELETE FROM coris_voyage_decisions_details 
			WHERE  ID_claims_details = '".$this->getID()."'";
		
			$mysql_result = mysql_query($query);	
			if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }				
	}
	/**
	 * @return the $franszyza
	 */
	public function getFranszyza() {
		return $this->franszyza;
	}

	/**
	 * @return the $franszyza_kwota
	 */
	public function getFranszyza_kwota() {
		return $this->franszyza_kwota;
	}

	/**
	 * @return the $odliczenie
	 */
	public function getOdliczenie() {
		return $this->odliczenie;
	}

	/**
	 * @return the $odliczenie_kwota
	 */
	public function getOdliczenie_kwota() {
		return $this->odliczenie_kwota;
	}

	/**
	 * @return the $refundacja
	 */
	public function getRefundacja() {
		return $this->refundacja;
	}

	/**
	 * @param field_type $franszyza
	 */
	public function setFranszyza($franszyza) {
		$this->franszyza = $franszyza;
	}

	/**
	 * @param field_type $franszyza_kwota
	 */
	public function setFranszyza_kwota($franszyza_kwota) {
		$this->franszyza_kwota = $franszyza_kwota;
	}

	/**
	 * @param field_type $odliczenie
	 */
	public function setOdliczenie($odliczenie) {
		$this->odliczenie = $odliczenie;
	}

	/**
	 * @param field_type $odliczenie_kwota
	 */
	public function setOdliczenie_kwota($odliczenie_kwota) {
		$this->odliczenie_kwota = $odliczenie_kwota;
	}

	/**
	 * @param field_type $refundacja
	 */
	public function setRefundacja($refundacja) {
		$this->refundacja = $refundacja;
	}
	/**
	 * @return the $refundacja_kwota
	 */
	public function getRefundacja_kwota() {
		return $this->refundacja_kwota;
	}

	/**
	 * @param field_type $refundacja_kwota
	 */
	public function setRefundacja_kwota($refundacja_kwota) {
		$this->refundacja_kwota = $refundacja_kwota;
	}


}
?>