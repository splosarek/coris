<?php
class VoyageClaim{
				
	var $ID;
	var $caseID;
	var $userID;
	var $date;
	var $note;
	var $announce_date;
	var $new_claims=false;
	
	private $delete=false;
	private $old_values=array();
	
	var $ClaimDetails=array();
	
	function __construct($id,$case_id,$new_claims=0){
		$this->ID = $id;		
		$this->caseID = $case_id;		
		$this->new_claims = $new_claims;

		if ($new_claims==0){
			$this->load();
		}
	}
	
		
	//coris_voyage_claims 

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
	 * @return the $announce_date
	 */
	public function getAnnounce_date() {
		return $this->announce_date;
	}

	/**
	 * @return the $ClaimDetail
	 */
	public function getClaimDetails() {
		return $this->ClaimDetails;
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
	 * @param field_type $announce_date
	 */
	public function setAnnounce_date($announce_date) {
		$this->announce_date = $announce_date;
	}

	/**
	 * @param field_type $ClaimDetail
	 */
	public function setClaimDetails($ClaimDetails) {
		$this->ClaimDetails = $ClaimDetails;
	}
	
	
	/**
	 * @param field_type $ClaimDetail
	 */
	public function addClaimDetails($ClaimDetails) {
		$this->ClaimDetails[] = $ClaimDetails;
	}
		
	
	private function load(){
			$query = "SELECT * FROM coris_voyage_claims  WHERE ID = '$this->ID'";			
			$mysql_result = mysql_query($query);
			if (mysql_num_rows($mysql_result) >0){
				$row = mysql_fetch_array($mysql_result);
																				
				$this->setCaseID($row['ID_case']);							
				$this->setAnnounce_date($row['announce_date']);							
				$this->setNote($row['note']);
				$this->setUserID($row['ID_user']);							
				$this->setDate($row['date']);			
								
				
				$this->old_values = $row;
				
				$query2 = "SELECT ID FROM coris_voyage_claims_details  WHERE ID_claims = '".$this->ID."'";
				$mysql_result = mysql_query($query2);			
				while ($row2 = mysql_fetch_array($mysql_result)){
						$this->addClaimDetails(new VoyageClaimDetails($row2['ID'],$this->ID) );
				}				
			}
	}
	
	public function store(){
			
			if ($this->new_claims){  //INSERT			
					$query = "INSERT INTO coris_voyage_claims SET 
					ID_case='$this->caseID',
					announce_date='".$this->announce_date."',
					note='".mysql_escape_string(stripslashes($this->note))."',
					ID_user='".$_SESSION['user_id']."',
					date=now()	
					";	
					$mysql_result = mysql_query($query);
					//echo '<br><hr>'.$query;
					if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); return; }
					$this->ID = mysql_insert_id();
					
					foreach ($this->ClaimDetails As $pozycja ){
						$pozycja->setClaimID($this->ID);
						$pozycja->store();
						
						 CaseInfo::setReserve($this->caseID,0,$pozycja->getKwota_rezerwa(),'PLN',$pozycja->getID());
					}
					
			}else{ //UPDATE
					$update_roznice= array(); 
					
					$query = "UPDATE  coris_voyage_claims SET
						announce_date='".$this->announce_date."',
						note='".mysql_escape_string(stripslashes($this->note))."',
						ID_user='".$_SESSION['user_id']."',
						date=now()
						
						WHERE ID = '$this->ID' LIMIT 1";
					
					/*if ($row_case['paxsurname'] != $poszk_nazwisko){
										$update_roznice['poszk_nazwisko'] = array('old' => $row_case['paxsurname'], 'new' => $poszk_nazwisko);
										$update2[] = "paxsurname = '".$poszk_nazwisko."'";
					}
					
					*/
					$mysql_result = mysql_query($query);	
					if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }	
					
					foreach ($this->ClaimDetails As $pozycja ){					
							$update_roznice[] = $pozycja->store();
							if ( !$pozycja->getDelete() ){
									CaseInfo::setReserve($this->caseID,0,$pozycja->getKwota_rezerwa(),'PLN',$pozycja->getID());
							}
					}
					/*
					if (count($update_roznice) > 0 ){
											EuropaCase::rejestrujZmiany($this->caseID,'CLAIMS','UPDATE',$update_roznice);
					}
					*/
			}
	}
	/**
	 * @return the $ID
	 */
	public function getID() {
		return $this->ID;
	}

	/**
	 * @return the $caseID
	 */
	public function getCaseID() {
		return $this->caseID;
	}

	/**
	 * @param field_type $caseID
	 */
	public function setCaseID($caseID) {
		$this->caseID = $caseID;
	}
	/**
	 * @param field_type $delete
	 */
	public function setDelete() {
		$this->delete = true;
	}


	
}
