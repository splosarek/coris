<?php

class AllianzDecision{
	
	var $ID;
	var $ID_claims_details;
	var $claims_details;
	var $ID_case;
	var $type; 
	var $type_list = array(3=>'Pozytywna',4=>'Odmowna'); 
	
	
	var $ID_user;
	var $accept_ID_user;
	var $date;
	var $status;
	var $status_list = array('Do uzupe³nienia','Gotowe');
	var $text1;
	var $text2;
	var $amount;
	var $amount2;
	var $payment_amount;
	
	var $franszyza= false;
	
	
	function __construct($id){				
		$this->ID = $id;		
		$this->load();
		
	}
	
	
	private function load(){
			$query = "SELECT * FROM coris_allianz_decisions  WHERE ID = '$this->ID'";
			$mysql_result = mysql_query($query);
			if (mysql_num_rows($mysql_result) >0){
				$row = mysql_fetch_array($mysql_result);
					
				$this->setID_claims_details($row['ID_claims_details']);
				$this->claims_details = new AllianzClaimDetails($this->getID_claims_details());
				
				$this->setID_case($row['ID_case']);
				$this->setType($row['type']);
				$this->setID_user($row['ID_user']);
				$this->setAccept_ID_user($row['ID_user2']);
				$this->setDate($row['date']);
				$this->setStatus($row['status']);
				$this->setText1($row['text1']);
				$this->setText2($row['text2']);
				$this->setAmount($row['amount']);
				$this->setAmount2($row['amount2']);
				$this->setPayment_amount($row['payment_amount']);
				
			}
	}
	
	/**
	 * @return the $ID
	 */
	public function getID() {
		return $this->ID;
	}

	/**
	 * @return the $ID_claims_details
	 */
	public function getID_claims_details() {
		return $this->ID_claims_details;
	}

	/**
	 * @return the $claims_details
	 */
	public function getClaims_details() {
		return $this->claims_details;
	}

	/**
	 * @return the $ID_case
	 */
	public function getID_case() {
		return $this->ID_case;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $ID_user
	 */
	public function getID_user() {
		return $this->ID_user;
	}

	/**
	 * @return the $accept_ID_user
	 */
	public function getAccept_ID_user() {
		return $this->accept_ID_user;
	}

	/**
	 * @return the $date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return the $text1
	 */
	public function getText1() {
		return $this->text1;
	}

	/**
	 * @return the $text2
	 */
	public function getText2() {
		return $this->text2;
	}

	/**
	 * @return the $amount
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @return the $amount2
	 */
	public function getAmount2() {
		return $this->amount2;
	}

	/**
	 * @return the $payment_amount
	 */
	public function getPayment_amount() {
		return $this->payment_amount;
	}

	/**
	 * @param field_type $ID
	 */
	public function setID($ID) {
		$this->ID = $ID;
	}

	/**
	 * @param field_type $ID_claims_details
	 */
	public function setID_claims_details($ID_claims_details) {
		$this->ID_claims_details = $ID_claims_details;
	}

	/**
	 * @param field_type $claims_details
	 */
	public function setClaims_details($claims_details) {
		$this->claims_details = $claims_details;
	}

	/**
	 * @param field_type $ID_case
	 */
	public function setID_case($ID_case) {
		$this->ID_case = $ID_case;
	}

	/**
	 * @param field_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param field_type $ID_user
	 */
	public function setID_user($ID_user) {
		$this->ID_user = $ID_user;
	}

	/**
	 * @param field_type $accept_ID_user
	 */
	public function setAccept_ID_user($accept_ID_user) {
		$this->accept_ID_user = $accept_ID_user;
	}

	/**
	 * @param field_type $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param field_type $text1
	 */
	public function setText1($text1) {
		$this->text1 = $text1;
	}

	/**
	 * @param field_type $text2
	 */
	public function setText2($text2) {
		$this->text2 = $text2;
	}

	/**
	 * @param field_type $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	/**
	 * @param field_type $amount2
	 */
	public function setAmount2($amount2) {
		$this->amount2 = $amount2;
	}

	/**
	 * @param field_type $payment_amount
	 */
	public function setPayment_amount($payment_amount) {
		$this->payment_amount = $payment_amount;
	}
	
	function getTypeName(){
		return $this->type_list[$this->type];
	}
	
	
	function getStatusName(){
		return $this->status_list[$this->status];
	}

function generateTxt($nr){
	
		 $row_case_announce = AllianzCase::getCaseInfo($this->ID_case);
		$row_case= CaseInfo::getCaseInfo($this->ID_case);
		
		if ($nr==1)
			return $this->getTxt1($row_case,$row_case_announce);
			
		if ($nr==2)	
			return $this->getTxt2($row_case,$row_case_announce);
}	

function getTxt1($row_case,$row_case_announce){
	 	
	
	$data_zdarzenia = $row_case['eventdate'];
	$rolnik = $row_case['paxname'].' '.$row_case['paxsurname'];
	$zwierz = AllianzCase::listaGatunkowZwierzatWSprawie2($row_case_announce['case_id']);
	$odszkodowanie = print_currency($this->getPayment_amount());
	
	$slownie = AllianzCase::slownie($odszkodowanie);
	$pow_dzialki = $row_case_announce['powierzchnia_dzialki'] != '' ? $row_case_announce['powierzchnia_dzialki'] : ' ......... ';
	$uprawa = $row_case_announce['rodzaj_stan_upraw'] != '' ? $row_case_announce['rodzaj_stan_upraw'] : ' ......... ';
	$dzialki = $row_case_announce['szk_nr_dzialki'] != '' ? $row_case_announce['szk_nr_dzialki'] : '';
	
	
	if ( $this->getType()==3 )  // poz
		$result = 'Uprzejmie zawiadamiamy, ¿e po rozpatrzeniu wniesionych roszczeñ o odszkodowanie za szkodê powsta³± w dniu '.$data_zdarzenia.' z tytu³u ubezpieczenia OC dobrowolne kó³ ³owieckich
przyznajemy odszkodowanie za szkodê w uprawie '.$uprawa.' Pana '.$rolnik.' wyrz±dzona przez '.$zwierz.', na dzia³ce '.$dzialki.' o ca³kowitej pow. '.$pow_dzialki.' ha 
w wysoko¶ci:  '.$odszkodowanie.' PLN (s³ownie: '.$slownie.' )
wed³ug nastêpuj±cego wyliczenia:';	
	
	if ( $this->getType()==4 ) // odm
		$result = 'Po rozpatrzeniu wniesionych roszczeñ o odszkodowanie za szkodê powsta³± w dniu '.$data_zdarzenia.' polegaj±c± na: ...................
szkodzie  Pana '.$rolnik.', wyrz±dzona przez '.$zwierz.', na dzia³ce '.$dzialki.' o ca³kowitej pow. '.$pow_dzialki.' ha  z przykro¶ci± zawiadamiamy, ¿e odszkodowanie z tytu³u ubezpieczenia OC dobrowolne kó³ ³owieckich  nie przys³uguje.';	
	
	return $result;
}

function getTxt2($row_case,$row_case_announce){

	$polisa = 	$row_case['policy'];
	$kolo_nazwa = $row_case_announce['kolo_nazwa'];
	$kolo_miasto = $row_case_announce['kolo_miejscowosc'];
	//$odszkodowanie = print_currency($this->getPayment_amount());
//	$odszkodowanie = print_currency($this->getAmount());
	$odszkodowanie = print_currency($this->getClaims_details()->getKwota_zaakceptowana());
	
	
	$ubezpieczenie = AllianzCase::ubezpieczenie($row_case_announce['ID_kolo']);
	$suma_ubezp = $ubezpieczenie['suma_ubezpieczenia'];
	$suma_ubezp_slownie = AllianzCase::slownie($suma_ubezp);
	$szkoda_nr = $row_case['client_ref'];

	
	
	
	
	$franszyza_info = '';
	
	
	if ($this->getClaims_details()->getFranszyza() && $this->getClaims_details()->getFranszyza_kwota() > 0 ){
		$this->setFranszyza();
		$franszyza_wartosc = print_currency($this->getClaims_details()->getFranszyza_kwota());
		if ($this->checkFranszyza($ubezpieczenie['franszyza_rodzaj']) == 2 ){ //redukcyjna 
			$franszyza_info = 'Z warto¶ci ustalonego odszkodowania, zgodnie z zapisami ww. polisy zosta³a potr±cona franszyza redukcyjna w wysoko¶ci '.$franszyza_wartosc.' PLN. Franszyza redukcyjna jest to okre¶lona kwotowo warto¶æ obni¿aj±ca ³±czne odszkodowanie z tytu³u jednej szkody. ';
		}		
	}
	
	
	if ( $this->getType()==3 )  // poz
		$result = 'Podstaw± wyp³aty odszkodowania jest umowa ubezpieczenia odpowiedzialno¶ci cywilnej zawartej z: '.$kolo_nazwa.' '.$kolo_miasto.',  potwierdzona wystawieniem polisy '.$polisa.'. Warto¶æ szkody spowodowanej przez zwierzynê le¶n± zosta³a ustalona na podstawie protoko³u ostatecznego szacowania i wynosi '.$odszkodowanie.' PLN. '.$franszyza_info.' Zgodnie z ustaleniami kwota odszkodowania zostanie przekazana na konto.';
		if ($this->getClaims_details()->getRefundacja()){  
				$refundacja_kwota = $this->getClaims_details()->getRefundacja_kwota();	
 				$result .= "\n".'Do wysoko¶ci odszkodowania zosta³a doliczona kwota '.print_currency($refundacja_kwota).' PLN nale¿na ko³u za szacowanie szkód.';	
		}
	if ( $this->getType()==4 ) // odm
		$result = 'Podstaw± wyp³aty odszkodowania jest umowa ubezpieczenia odpowiedzialno¶ci cywilnej Ko³a £owieckiego '.$kolo_nazwa.' '.$kolo_miasto.' potwierdzona wystawieniem polisy '.$polisa.'. Zgodnie z zapisem w powy¿szej polisie jeste¶cie Pañstwo objêci sum± gwarancyjn± wysoko¶ci '.$suma_ubezp.' PLN (s³ownie: '.$suma_ubezp_slownie.'). W zwi±zku z faktem,
i¿ zdeklarowana przez Pañstwa suma gwarancyjna zosta³a ju¿ wykorzystana w ca³o¶ci, odszkodowanie za szkodê nr '.$szkoda_nr.' nie przys³uguje';	
			
	return $result;
}


	function checkFranszyza($franszyza_rodzaj){
//		if ( $franszyza_rodzaj ==2 && $this->getClaims_details()->getWyplata_zaakceptowana() < $this->getClaims_details()->getKwota_zaakceptowana()){
		if ( $franszyza_rodzaj ==2 && $this->getClaims_details()->getFranszyza() && $this->getClaims_details()->getFranszyza_kwota() > 0 ){
			return true;
		}else{
			return false;
		}	
	
	}

	static function updateDecision($id,$txt1,$txt2){
		$query = "UPDATE coris_allianz_decisions SET
			status=1,
			text1='".mysql_escape_string(stripslashes($txt1))."',
			text2='".mysql_escape_string(stripslashes($txt2))."'
		WHERE ID='$id'";
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
	 * @param field_type $franszyza
	 */
	public function setFranszyza() {
		$this->franszyza = true;
	}

}

?>