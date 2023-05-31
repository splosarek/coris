<?php

class VoyageDecision{
	
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
	var $text3;
	var $text4;
	var $text5;
	var $text6;
	var $amount;
	var $amount2;
	var $currency_id; 
	var $payment_amount;
	
	var $list_details = array ();
	
	var $franszyza= false;
	
	
	function __construct($id){				
		$this->ID = $id;		
		$this->load();
		
	}
	
	static public function createDecision($case_id,$EuropaClaimDetailsList){
		if ($case_id>0){
			
			
			$today = date('Y-m-d');			
			
			$query = "INSERT INTO coris_voyage_decisions 
			SET ID_case='$case_id',			
			ID_user = '".Application::getCurrentUser()."',
			ID_user2 = '0',			
			`date`='$today',
			`status`=0,text1='',text2='',
			amount='0.00',
			payment_amount='0.00',
			currency_id = 'EUR'			
			";
			$mysql_result = mysql_query($query);	
			if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }		
			
			$ced = mysql_insert_id();
			$suma = '0.0';
			$type = '';
			foreach ($EuropaClaimDetailsList As $cls){
				if ($cls->getWaluta() != 'EUR'){
					$kurs = Finance::getKurs($today,1,$cls->getWaluta());						
					$tabled_id = $kurs['table_id'] ;
					$rate = $kurs['rate'];
					$multiplier = $kurs['multiplier'];
				}else{
					$rate = 1;
					$multiplier = 1;
					$tabled_id = 1;
				}					
			
				if ( $cls->getStatus() == 3 ){
					   $kwota = $cls->getKwota_zaakceptowana();
				}else{
					   $kwota= 0;
				}
				
				
				$platnosc = Finance::ev_round( ( $kwota * $rate) / $multiplier ,2);
				$suma += $platnosc;
				
				$query = "INSERT INTO coris_voyage_decisions_details 
						SET ID_decisions='$ced',
						ID_claims_details = '".$cls->getID()."',						
						`type`='".$cls->getStatus()."',						
						amount='".$kwota."',
						payment_amount='".$platnosc."',
						currency_id = '".$cls->getWaluta()."',
						table_id = '$tabled_id',
						rate = '$rate',
						multiplier = '$multiplier'
				";
				$mysql_result = mysql_query($query);	
				if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }		
				
				if ($cls->getStatus()==3 && ($type == '' || $type== 4 ) ) { //akceptacja
					$type=3;
				}else if ($cls->getStatus()==4 && $type=='' ){ // odmowa
					$type=4;
				}
				
				//CaseInfo::setReserve($case_id,0,$platnosc,'PLN',$cls->getID());;
				$cls->createPayment($case_id,$platnosc);
			}
			
			$query = "UPDATE coris_voyage_decisions SET payment_amount='$suma',type='$type' WHERE ID='$ced' LIMIT 1";
			$mysql_result = mysql_query($query);	
			if (!$mysql_result){echo "<br>QE: $query <br>".mysql_error(); }		
		}
	}
	
	private function load(){
			$query = "SELECT * FROM coris_voyage_decisions  WHERE ID = '$this->ID'";
			$mysql_result = mysql_query($query);
			if (mysql_num_rows($mysql_result) >0){
				$row = mysql_fetch_array($mysql_result);
					
				$this->setID_claims_details($row['ID_claims_details']);
				$this->claims_details = new VoyageClaimDetails($this->getID_claims_details());
				
				$this->setID_case($row['ID_case']);
				$this->setType($row['type']);
				$this->setID_user($row['ID_user']);
				$this->setAccept_ID_user($row['ID_user2']);
				$this->setDate($row['date']);
				$this->setStatus($row['status']);
				$this->setText1($row['text1']);
				$this->setText2($row['text2']);
				$this->setText3($row['text3']);
				$this->setText4($row['text4']);
				$this->setText5($row['text5']);
				$this->setText6($row['text6']);
				
				$this->setAmount($row['amount']);
				$this->setAmount2($row['amount2']);
				$this->setCurrency_id($row['currency_id']);
				$this->setPayment_amount($row['payment_amount']);
				
				
				$query = "SELECT * FROM coris_voyage_decisions_details  WHERE ID_decisions = '$this->ID' ORDER BY ID";
				$mysql_result = mysql_query($query);
				if (mysql_num_rows($mysql_result) >0){
					while($row = mysql_fetch_array($mysql_result)){
						$this->list_details[] = $row;
					}
				}
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
	
		$row_case_announce = VoyageCase::getCaseAnnounce($this->ID_case);
		$row_case= CaseInfo::getFullCaseInfo($this->ID_case);
		
		if ($nr==1)
			return $this->getTxt1($row_case,$row_case_announce);
			
		if ($nr==2)	
			return $this->getTxt2($row_case,$row_case_announce);
		if ($nr=='sumy_ubezpieczenia')	
			return $this->getSumyUbezp($row_case,$row_case_announce);
		if ($nr=='adresat')	
			return $this->getAdresat($row_case,$row_case_announce);
		if ($nr=='beneficjent')	
			return $this->getAdresat($row_case,$row_case_announce);
}	


function getAdresat($row_case,$row_case_announce){

    $result = $row_case['paxsex']=='M' ? 'Herr ' : 'Frau ';
	$result .= $row_case['paxname'].' '.$row_case['paxsurname']."\n";
	$result .= ''.$row_case['paxaddress']."\n";
	$result .= str_replace('-','',$row_case['paxpost']).' '.$row_case['paxcity']."\n";
		
	return $result;
}

function getTxt1($row_case,$row_case_announce){
	 	
	$odszkodowanie = Finance::print_currency($this->getPayment_amount());	
	$slownie = Finance::slownie($odszkodowanie);		
	
	if ( $this->getType()==3 ){  // poz

        $result = ($row_case['paxsex']=='M' ? 'Sehr geehrter Herr ' : 'Sehr geehrte Frau ').' '.$row_case['paxsurname'].',';
		$result .= "\n\n".'wir haben die Leistungsprüfung abgeschlossen und folgende Ansprüche anerkannt. <br>  Folgender Betrag ist fällig geworden: <!--KWOTA-->';
	//	$result = '';
	}	
	
	if ( $this->getType()==4 ) { // odm
        $result = ($row_case['paxsex']=='M' ? 'Sehr geehrter Herr ' : 'Sehr geehrte Frau ').' '.$row_case['paxsurname'].',';
        $result .= "\n\n".'nach Auswertung der Unterlagen und auf Grundlage der uns vorliegenden Informationen können wir Ihren Forderungsanspruch leider nicht geltend machen.';
    }
	
	return $result;
} 

function getTxt2($row_case,$row_case_announce){
	if ( $this->getType()==3 ){  // poz
		$result = 'Der oben genannte Betrag setzt sich wie folgt zusammen: ';
		$result  .= "\n\n<!--ZESTAWIENIE-->";
		
		$result .= "\n\n".' Als Grundlage für die Berechnung der Auszahlung wird der Tageswechselkurs der Europäischen Zentralbank angewendet, vom Tag an dem die Schadenregulierung stattfand';

        $result .= "\n\n\n\n".'Diesen Betrag werden wir in den nächsten Tagen auf das uns benannte Konto überweisen. ';
		
	}
		

	if ( $this->getType()==4 ) { // odm
        $result = 'Für diesen Fall besteht gemäß den Allgemeinen Versicherungsbedingungen (Seite XX) kein Versicherungsschutz. ';
        $result .= "\n\n" . 'Für Rückfragen stehen wir Ihnen jederzeit gern zur Verfügung.';

    }
	return $result;
}


function getSumyUbezp($row_case,$row_case_announce){

	if ( $this->getType()==3 )  // poz
/*		$result = '
Leczenie amb. (wypadek)                    ... PLN
Leczenie szpitalne (wypadek)               ... PLN
Leczenie amb. (choroba)                     ... PLN
Leczenie szpitalne (choroba)	           ....PLN
Koszty ratownictwa			... PLN
Koszty transportu			... PLN
Pomoc w podró¿y		           ... PLN
Baga¿ podró¿ny			           ... PLN
Opó¼nienie dostarczenia baga¿u           ... PLN
OC					... PLN';
*/
		$result = '
<table>
<tr><td>Leczenie amb. (wypadek)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie szpitalne (wypadek)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie amb. (choroba)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie szpitalne (choroba)</td><td align="right">... PLN</td></tr>
<tr><td>Koszty ratownictwa</td><td align="right">... PLN</td></tr>
<tr><td>Koszty transportu</td><td align="right">... PLN</td></tr>
<tr><td>Pomoc w podró¿y</td><td align="right">... PLN</td></tr>
<tr><td>Baga¿ podró¿ny</td><td align="right">... PLN</td></tr>
<tr><td>Opó¼nienie dostarczenia baga¿u</td><td align="right">... PLN</td></tr>
<tr><td>OC</td><td align="right">... PLN</td></tr>
<tr><td>Stomatologia</td><td align="right">... PLN</td></tr>
</table>
';

		if ( $this->getType()==4 ) // odm
				$result = '
<table>
<tr><td>Leczenie amb. (wypadek)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie szpitalne (wypadek)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie amb. (choroba)</td><td align="right">... PLN</td></tr>
<tr><td>Leczenie szpitalne (choroba)</td><td align="right">... PLN</td></tr>
<tr><td>Koszty ratownictwa</td><td align="right">... PLN</td></tr>
<tr><td>Koszty transportu</td><td align="right">... PLN</td></tr>
<tr><td>Pomoc w podró¿y</td><td align="right">... PLN</td></tr>
<tr><td>Baga¿ podró¿ny</td><td align="right">... PLN</td></tr>
<tr><td>Opó¼nienie dostarczenia baga¿u</td><td align="right">... PLN</td></tr>
<tr><td>OC</td><td align="right">... PLN</td></tr>
<tr><td>Stomatologia</td><td align="right">... PLN</td></tr>
</table>
';			
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

	static function updateDecision($decisions_id, $tekst1, $tekst2, $tekst3, $tekst4, $tekst5, $data_decyzji){
		
		$query = "SELECT * FROM coris_voyage_decisions_details WHERE ID_decisions='$decisions_id'";
		$mysql_result = mysql_query($query);	
		$suma = '0.0';
		while ($row = mysql_fetch_array($mysql_result)){
			
			if ($row['currency_id'] != 'EUR' && $row['amount'] > 0.0){
					$kurs = Finance::getKurs($data_decyzji,1,$row['currency_id']);						
					$tabled_id = $kurs['table_id'] ;
					$rate = $kurs['rate'];
					$multiplier = $kurs['multiplier'];
					
					$platnosc = Finance::ev_round( ( $row['amount'] * $rate) / $multiplier ,2);										
					$qu = "UPDATE coris_voyage_decisions_details 						
						SET payment_amount='".$platnosc."',						
						table_id = '$tabled_id',
						rate = '$rate',
						multiplier = '$multiplier'
						WHERE ID='".$row['ID']."'";
					$mr = mysql_query($qu);
			}else{											
					$platnosc =  $row['amount'] ;					
			}
			
			$suma += $platnosc;
		}		
		
		$query = "UPDATE coris_voyage_decisions SET
			status=1,
			text1='".mysql_escape_string(stripslashes($tekst1))."',
			text2='".mysql_escape_string(stripslashes($tekst2))."',
			text3='".mysql_escape_string(stripslashes($tekst3))."',
			text4='".mysql_escape_string(stripslashes($tekst4))."',
			text5='".mysql_escape_string(stripslashes($tekst5))."',
			date='".$data_decyzji."',
			payment_amount='$suma'
		WHERE ID='$decisions_id'";		
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
	/**
	 * @return the $currency_id
	 */
	public function getCurrency_id() {
		return $this->currency_id;
	}

	/**
	 * @param field_type $currency_id
	 */
	public function setCurrency_id($currency_id) {
		$this->currency_id = $currency_id;
	}
	/**
	 * @return the $list_details
	 */
	public function getList_details() {
		return $this->list_details;
	}
	/**
	 * @return the $text3
	 */
	public function getText3() {
		return $this->text3;
	}

	/**
	 * @return the $text4
	 */
	public function getText4() {
		return $this->text4;
	}

	/**
	 * @return the $text5
	 */
	public function getText5() {
		return $this->text5;
	}

	/**
	 * @return the $text6
	 */
	public function getText6() {
		return $this->text6;
	}

	/**
	 * @param field_type $text3
	 */
	public function setText3($text3) {
		$this->text3 = $text3;
	}

	/**
	 * @param field_type $text4
	 */
	public function setText4($text4) {
		$this->text4 = $text4;
	}

	/**
	 * @param field_type $text5
	 */
	public function setText5($text5) {
		$this->text5 = $text5;
	}

	/**
	 * @param field_type $text6
	 */
	public function setText6($text6) {
		$this->text6 = $text6;
	}




}

?>