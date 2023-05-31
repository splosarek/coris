<?php

/**
 * Klasa Interaction
 *
 */
class Interaction{
	
	
	
	protected $_id;
	
	protected $_case_id;
	protected $_case;
	
	
	protected $_direction=0;
	
	protected $_type;  // Note, Call, Email, FAX
	static $_TYPE_NOTE=2;  
	static $_TYPE_CALL=3;  
	static $_TYPE_EMAIL=4;  
	static $_TYPE_FAX=5;  
	static $_TYPE_SMS=6;  
	
	
	static $DIRECTION_IN=2;  
	static $DIRECTION_OUT=1;  
	
	protected $_type_class_name = array('','','Note','Call','Email','Fax','SMS');
    // Note, Call, Email, FAX
    protected $_document_id = 0;
    protected $_template_id = 0;
    protected $_document = false;


	protected $_category;
	protected $_category_id=0;
	protected $_new;

	protected $_user_id;	
	protected $_date;
	
	protected $_last_user_id;	
	protected $_last_date;
	
	
	protected $_made_old;
	protected $_made_change=0;
	protected $_made;
	protected $_made_user_id;
	protected $_made_date;
	
	protected $_action_status;
	protected $_action_date;
	
	
	protected $_contact_id;
	protected $_contact_name;
	protected $_contact_contact;
	protected $_contact_subject;
	
	
	
	protected $_internal='';
	protected $_external='';
	protected $_reclamation='';
	
	protected $_destination='';
	
	protected static  $table_store_interaction = 'store_interaction';
	protected static  $table_store_case = 'coris_assistance_cases';
	protected static  $table_store_interaction_category = 'coris_fax_in_category';
	
	
	
	function __construct($case_id,$id=0,$type=0,$direction=0,$clone=0){		
			
		if ($id>0){ // istniejacy
			$this->_id = $id;
			$this->load();
			if ($clone==1){					
				//$this->setSourceObjectID($id);
				$this->getDocumentClone();
				$this->setObjectID(0);																										
			}			
		}else if ($case_id>0 && $type>0){ // nowy
			$this->_case_id=$case_id;
			$this->_type=$type;
			$this->_direction=$direction;			
			$this->_new=0;			
		}else{
			throw new Exception('Error Interactions construct parameter!!!');			
		}			
	}
					
	function getObjectId(){
		return $this->_id;
	}
	
	function setObjectId($id){
		return $this->_id = $id;
	}
	
	function addDocument($document){
			$this->_document = $document;	
			if ($document->getObjectId() > 0){
				$this->_document_id = $document->getObjectId();
			}
	}
	
	function getDocument(){
		global $docObject;
		if ($this->_document === false){
				if ($this->_document_id > 0  )	{					 						
						$this->_document = $docObject->getDocument($this->_document_id); 												
						return $this->_document;
				}						
		}else{
			return $this->_document;
		}
	}
	
	function getDocumentClone(){
		global $docObject;
		if ($this->_document === false){					
				if ($this->_document_id > 0){ 	
						$this->_document = $docObject->getCloneDocument($this->_document_id); ;
						$this->_document->store();
						$this->_document_id = $this->_document->getObjectID(); 
						return $this->_document;
				}
		}else{
			return $this->_document;
		}
	}
	
	function setCategoryId($cat_id){
			$this->_category_id = $cat_id;		
	}
	
	function getCategoryId(){
			return $this->_category_id ;		
	}
	
	function getCategoryName($lang='pl'){
		//$this->_category
		if ($this->getCategoryId() > 0 ){
			$storage = Application::getStorage();
			
			$query = "SELECT * FROM `".self::$table_store_interaction_category."` WHERE ID='".$this->getCategoryId()."'";
			$res = $storage->query($query);
			$row = $storage->fetch_row($res);			
			
			return ( ($lang=='en') ? $row['name_eng']  : $row['name']);
		}else{
			return '';	
		}		
	}
	
	
	function getTypeClassName(){	
		return 	$this->_type_class_name[$this->_type];	
	}
	
	function getIco(){	
		return 	'ico_'.strtolower($this->_type_class_name[$this->_type]).'.png';	
	}
	
	
	function  setCaseId($case_id){
		$this->_case_id=$case_id;
	}
	
	function  getCaseID(){
		return $this->_case_id;				
	}
	
	
	function  setMade($val){				
		$this->_made=$val;
		
		if ($this->_made_old != $this->_made )
			$this->_made_change=1;
		
	}		
	
	function  getNew(){
		return $this->_new;				
	}
	
	function  setNew($val){
		$this->_new = $val;				
	}
	
	function  getMade(){
		return $this->_made;				
	}
	
	function  getMadeOld(){
		return $this->_made_old;				
	}
	
	function  getMadeUserId(){
		return $this->_made_user_id;				
	}
	
	function  getMadeDate(){
		return $this->_made_date;				
	}
		
	function getDirection(){
		return ($this->_direction > 0 ? $this->_direction : 0 );
	}
	
	
	function setInteractionName($txt){
		 $this->_contact_name = $txt;
	}
	
	function setInteractionContact($txt){
		$this->_contact_contact = $txt;
	}
	
	function setInteractionSubject($txt){
		$this->_contact_subject = $txt;
	}

	
	function getInteractionName(){
		return $this->_contact_name;
	}
	
	function getInteractionContact(){
		return $this->_contact_contact;
	}
	
	function getInteractionSubject(){
		return $this->_contact_subject;
	}
	
	
	function getType(){
		return $this->_type;
	}
	
	
	function getDate(){
		return $this->_date;
	}
	
	
	function getUserId(){
		return $this->_user_id;
	}
	
		function getLastDate(){
		return $this->_last_date;
	}
	
	
	function getLast_UserId(){
		return $this->_last_user_id;
	}
	
	function getActionStatus(){
		return $this->_action_status;
	}

	function getActionDate(){
		return $this->_action_date;
	}
	
	function load(){
		$storage = Application::getStorage();
		
		$query = "SELECT * FROM ".self::$table_store_interaction." WHERE ID='".$this->_id."' ";
		$res = $storage->query($query);
		$row = $storage->fetch_row($res);
		
			$this->_case_id = $row['ID_case'];			
			$this->_new = $row['new'];
			
			
			$this->_action_date = $row['action_date'];
			$this->_action_status = $row['action_status'];
									
			$this->_made = $row['made'];						
			$this->_made_old = $row['made'];						
			$this->_made_date = $row['made_date'];
			$this->_made_user_id = $row['made_ID_user'];
			
			$this->_date = $row['date'];
			$this->_user_id = $row['ID_user'];
			
			$this->_last_date = $row['last_date'];
			$this->_last_user_id = $row['last_ID_user'];
			
			
			$this->_type = $row['ID_document_type'];
			$this->_direction = $row['direction'];
			
			$this->_document_id = $row['ID_document'];
			$this->_category_id = $row['ID_category'];	
			
			
			$this->_contact_name = $row['interaction_name'];
			$this->_contact_contact = $row['interaction_contact'] ;
			$this->_contact_subject = $row['subject'];	
			
			$this->_reclamation = $row['reclamation'];	
			$this->_internal = $row['internal'];	
			$this->_external = $row['external'];	
			
			$this->_destination = $row['target_destination'];	
			$this->_template_id = $row['template_id'];

	}
	
	
	function store(){	
			$storage = Application::getStorage();
		 if ($this->_id > 0 ){ // update
		 			 	
		 	$query = "UPDATE ".self::$table_store_interaction." SET 
		 				ID_case='".$this->_case_id."', 
		 				ID_document_type='".$this->_type."', 
		 				ID_document='".$this->_document_id."', 
		 				direction='".$this->_direction."', 
		 				ID_category='".$this->_category_id."', 
		 				new='".$this->_new."', 
		 				internal='".$this->_internal."',
		 				external='".$this->_external."',
		 				reclamation='".$this->_reclamation."',		 				
		 				last_date=now(), 
		 				last_ID_user='".Application::getCurrentUser()."'";
		 				
		 				if($this->_made_change){
		 					$query .= ", made=1,made_date=now(),made_ID_user='".Application::getCurrentUser()."'";
		 				}
		 				
		 				$query .= " WHERE ID='".$this->_id."' LIMIT 1";	
//		 		$res = mysql_query($query);	 	
		 		$res = $storage->query($query);	 			 		
		 		if ($res){
		 		
		 			
		 		}else{
		 			throw new Exception("Interaction query ERROR: ".$query."\n\n");
		 		}
		 }else{	
		 		if ($this->_document && $this->_document_id==0 ){
		 					$this->_document->store();
		 					$this->_document_id = $this->_document->getObjectId();
		 		}
		 		
		 		$query = "INSERT INTO ".self::$table_store_interaction." SET 
		 				ID_case='".$this->_case_id."', 
		 				ID_document_type='".$this->_type."', 
		 				ID_document='".$this->_document_id."', 
		 				direction='".$this->_direction."', 
		 				ID_category='".$this->_category_id."', 
		 				internal='".$this->_internal."',
		 				external='".$this->_external."',
		 				reclamation='".$this->_reclamation."',
		 				new='".$this->_new."', 
		 				date=now(), 
		 				action_status='".$this->_action_status."', 
		 				action_date=now(), 
		 				ID_user='".Application::getCurrentUser()."',
		 				interaction_name='".mysql_escape_string(stripslashes($this->_contact_name))."',
		 				interaction_contact='".mysql_escape_string(stripslashes($this->_contact_contact))."',
		 				subject='".mysql_escape_string(stripslashes($this->_contact_subject))."',
		 				target_destination='".$this->_destination."',
                        template_id='".$this->_template_id."'
		 				";		 		
				$res = $storage->queryInsert($query);	 					 			
		 		//$res = mysql_query($query);	 	
		 		if ($res){
		 			
		 			$this->_id=$res;
		 		}else{
		 			throw new Exception("Interaction query ERROR: ".$query."\n\n");
		 		}
		 }	
	}
	
	
	static function getBindedCaseToDocument($doc_id){
			$storage = Application::getStorage();
			$query = "SELECT case_id, number, year 
						FROM ".self::$table_store_case." cac,".self::$table_store_interaction." ci
				WHERE cac.case_id=ci.ID_case 
							AND ci.ID_document='$doc_id'  				
				ORDER BY year DESC, number";
					$result = array();
					
					//echo $query;
   					$db_result =  $storage->query($query);	 
   					while ($row = $storage->fetch_row($db_result)){  						
   							$result[] = array('case_id' =>$row['case_id'],'number'=>$row['number'],'year' => $row['year']);			   										
					}
					
					return $result;
	}
	
	static function unbindCase($doc_id,$case_id){
			global $docObject;
			
			$storage = Application::getStorage();
			$query = "DELETE FROM ".self::$table_store_interaction." 
				WHERE ID_case='$case_id'  AND ID_document='$doc_id' LIMIT 1";															
   				$db_result =  $storage->queryUpdate($query);	 

   					
   				$lista = self::getBindedCaseToDocument($doc_id);
   				
   				
   				if (count($lista) == 0){
   					$docObject->setStatusDocument($doc_id,Document::$STATUS_NEW);
   				}   					
				return ;
	}
	
	static function bindDocumentToCase($case_id,$doc_id,$category_id,$reclamation,$destination=1){
			global $docObject;
			
			$storage = Application::getStorage();

			$document = $docObject->getNewDocument($doc_id);
			
			$contact_name = '';
			$contact_contact = '';
			$contact_subject = '';
			
			if ($document instanceof  Email){
				$contact_name = $document->get_from();
				$contact_contact = $document->get_from_email();
				$contact_subject = $document->getName();
			}else if ($document instanceof  Fax){
				$contact_name = '';
				$contact_contact = '';
				$contact_subject = $document->getName();						
			}else if ($document instanceof  SMS){
				$contact_name = '';
				$contact_contact = $document->getPhone();
				$contact_subject = $document->getName();	
			}else if ($document instanceof  Note){
				$contact_name = '';
				$contact_contact = '';
				$contact_subject = $document->getName();
			}
			
			
				$query = "INSERT INTO ".self::$table_store_interaction." SET 
		 				ID_case='".$case_id."', 
		 				ID_document_type='".$document->get_document_type()."', 
		 				ID_document='".$doc_id."', 
		 				direction='".$document->get_direction()."', 
		 				ID_category='".$category_id."', 
		 				new='1', 
		 				date=now(), 
		 				ID_user='".Application::getCurrentUser()."',
		 				interaction_name='".mysql_escape_string(stripslashes($contact_name))."',
		 				interaction_contact='".mysql_escape_string(stripslashes($contact_contact))."',
		 				subject='".mysql_escape_string(stripslashes($contact_subject))."',		 				
		 				target_destination='".$destination."',
		 				reclamation = '$reclamation'		 				
		 		";	
				//echo $query;
				//$res = mysql_query($query);	 	
				$res =  $storage->queryInsert($query);	
		 		if ($res){
		 			//$id = mysql_insert_id();
		 			$id = $res;
		 			
		 			$docObject->setStatusDocument($doc_id,Document::$STATUS_ASIGN);
		 			
			 		if ($reclamation==1){
			 			 CorisCase::set_case_reclamation($case_id);
			 		}
		 			return $id;
		 		}else{
		 			throw new Exception("Interaction query ERROR: ".$query."\n\n".mysql_error());
		 		}
		 		
		 		
	}
	
	function send($save_only=0,$target=1,$bcc=''){
		if ($this->getDirection()== self::$DIRECTION_OUT){
				$docObject  = $this->getDocument() ;  	
			    if ($docObject instanceof Email){			    				
			    				return $docObject->send($save_only,$target,$bcc);
			    }else if ($docObject instanceof Fax){	
								return $docObject->send($save_only,$target);
			    }				        
			    return false; 		
		}
		return false;
	}
	
	
	static function updateCategoryId($interaction_id,$category_id){
				$storage = Application::getStorage();
				
				$query = "UPDATE  ".self::$table_store_interaction." SET ID_category='$category_id' WHERE ID='".$interaction_id."'";
				$res = $storage->queryUpdate($query);		
	}

	static function updateMade($interaction_id){
				$storage = Application::getStorage();
				
				$query = "UPDATE  ".self::$table_store_interaction." SET new=0,made=1,made_date=now(),made_ID_user=".Application::getCurrentUser()." WHERE ID='".$interaction_id."'";
				$res = $storage->queryUpdate($query);		
	}
	
	static function updateActionStatus($interaction_id,$status,$date){
				$storage = Application::getStorage();
				
				$query = "UPDATE  ".self::$table_store_interaction." SET action_status='$status', action_date='$date' WHERE ID='".$interaction_id."'";
				$res = $storage->queryUpdate($query);		
				
				$this->_action_date = $date;
				$this->_action_status = $status;
	}
	
	static function updateReclamation($interaction_id,$flaga){
				$storage = Application::getStorage();
				
				$query = "UPDATE  ".self::$table_store_interaction." SET reclamation='.$flaga.' WHERE ID='".$interaction_id."'";
				$res = $storage->queryUpdate($query);		
				
				if ($flaga==1){
					$query = "SELECT ID_case FROM ".self::$table_store_interaction." WHERE ID='".$interaction_id."' ";
					$res = $storage->query($query);
					$row = $storage->fetch_row($res); 				
		
					CorisCase::set_case_reclamation($row['ID_case']);
				}
	}
	
		static function checkBindDocumentToCase($case_id,$doc_id){
			$storage = Application::getStorage();
			$query = "SELECT * FROM ".self::$table_store_interaction." 
				WHERE ID_case='$case_id'  AND ID_document='$doc_id' ";															
   				//$db_result =  $storage->query($query);	 
   				$db_result =  mysql_query($query);	 

//   			if ($storage->num_rows($db_result) > 0	){
   			if (mysql_num_rows($db_result) > 0	){
   				return true;
   			}else{		 					
				return false;
   			}
		
	}
	
	
	function toStore(){
		return array(
			'_id' => $this->_id,
			'_case_id' => $this->_case_id,
			
			'_made_change' => $this->_made_change,			
			'_new' => $this->_new,
			
			'_type' => $this->_type,
			'_direction' => $this->_direction,
			
			'_document_id' => $this->_document_id,
			'_category_id' => $this->_category_id,
			
			'_contact_name' => $this->_contact_name,
			'_contact_contact' => $this->_contact_contact,
			'_contact_subject' => $this->_contact_subject,	
			'_destination' => $this->_destination,
			'_template_id' => $this->_template_id
		);
		
		
	}
	/**
	 * @return the $_internal
	 */
	public function getInternal() {
		return $this->_internal;
	}
	/**
	 * @return the $_external
	 */
	public function getExternal() {
		return $this->_external;
	}

	/**
	 * @return the $_reclamation
	 */
	public function getReclamation() {
		return $this->_reclamation;
	}

	/**
	 * @param field_type $_internal
	 */
	public function setInternal($_internal) {
		$this->_internal = $_internal;
	}
	
	/**
	 * @param field_type $_external
	 */
	public function setExternal($_external=1) {
		$this->_external = $_external;
	}

	/**
	 * @param field_type $_reclamation
	 */
	public function setReclamation($_reclamation) {
		$this->_reclamation = $_reclamation;
	}

	static function getContact($case_id,$contact_typ){
			$storage = Application::getStorage();
			$result = array();
			$query = "SELECT DISTINCT interaction_contact,interaction_name FROM ".self::$table_store_interaction." WHERE ID_case='".$case_id."' AND ID_document_type='".$contact_typ."' ";
			$res = $storage->query($query);
			while ($row = $storage->fetch_row($res)){
				$email_tmp = self::emailAdressExplode($row['interaction_contact']);
				$email_name = $row['interaction_name'];
				foreach ($email_tmp As $email){
					$result[$email] = $email_name ;
				}
			}
			
			return $result;
	}
	
	static function getEmailContact($case_id){
			return self::getContact($case_id,self::$_TYPE_EMAIL);		
	}
	
	static function getFaxContact($case_id){
			return self::getContact($case_id,self::$_TYPE_FAX);		
	}
	
	static function emailAdressExplode($email){	
			$email = strtolower($email);
			
			$emails = str_replace(';',',',$email);
			$tmp = explode(',',$emails);
			$ilosc = count($tmp);
			$res = array();
			
			if ($ilosc==1){
				if (self::is_email($email))
					$res[] =  $email;		
			}else{				
				for($i=0;$i<$ilosc;$i++){
						if (self::is_email($tmp[$i])){
							$res[] = $tmp[$i];					
						}			
				}		
			}	
			return $res;
	}
	
	static function is_email($string){
	    $string = trim($string);
	    $ret = ereg(
	                '^([A-Za-z0-9_]|\\-|\\.)+'.
	                '@'.
	                '(([A_Za-z0-9_]|\\-)+\\.)+'.
	                '[A-Za-z]{2,10}$',
	                $string);
	    return($ret);
	}
	/**
	 * @return the $_destination
	 */
	public function get_destination() {
		return $this->_destination;
	}

	/**
	 * @param field_type $_destination
	 */
	public function set_destination($_destination) {
		$this->_destination = $_destination;
	}

	/**
	 * @return the $_destination
	 */
	public function get_template_id() {
		return $this->_template_id;
	}

	/**
	 * @param field_type $_destination
	 */
	public function set_template_id($_template_id) {
		$this->_template_id = $_template_id;
	}
	
}

?>