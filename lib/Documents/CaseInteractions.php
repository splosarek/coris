<?php



class CaseInteractions{
	
	protected static  $table_store_interaction = 'store_interaction';
	protected static  $table_store_document = 'store_document';
	
	
	protected $_case_id;
	protected $_param_query;
	protected $_order_query;
	protected $_interactions=array();
	protected $_storage;
	
	function __construct($case_id,$param=array(),$order=array()){
			$this->_case_id=$case_id;
			$this->_param_query=$param;
			$this->_order_query=$order;
		
			$this->_storage = Application::getStorage();
	}
	
	
	function prepareQuery(){
		
		$query = "SELECT ".self::$table_store_interaction.".*, coris_users.name, coris_users.surname 
						FROM ".self::$table_store_interaction.", coris_users ";
		
		$query .= " WHERE 	".self::$table_store_interaction.".ID_case = '".$this->_case_id."' AND ".self::$table_store_interaction.".ID_user = coris_users.user_id  ";

		foreach ($this->_param_query As $param=>$var){
			if ($param == 'search'){
				$query .= "AND (coris_users.surname LIKE '%".mysql_escape_string(stripslashes($var))."%' 
						OR ".self::$table_store_interaction.".interaction_name LIKE '%".mysql_escape_string(stripslashes($var))."%' 
						OR ".self::$table_store_interaction.".interaction_contact  LIKE '%".mysql_escape_string(stripslashes($var))."%' 
						OR ".self::$table_store_interaction.".subject LIKE '%".mysql_escape_string(stripslashes($var))."%' 
				) ";
			}else{
				$query .= ' AND '.$param."='".mysql_escape_string(stripslashes($var))."' ";
			}
		}



		    $order = $this->_order_query['order'];
		    $direction = $this->_order_query['direction'];
		    switch ($order) {
		        case "type":
		            $query .= "ORDER BY ID_document_type ";
		            break;
		        case "sender":
		            $query .= "ORDER BY coris_users.surname ";
		            break;
		        case "direction":
		            $query .= "ORDER BY direction ";
		            break;
		        case "recipient":
		            $query .= "ORDER BY interaction_name ";
		            break;
		        case "subject":
		            $query .= "ORDER BY subject ";
		            break;
		        case "date":
		            $query .= "ORDER BY date ";
		            break;
		    }
		    $query .= ($direction) ? $direction : " DESC";			
		  // echo $query;
		    return $query;
	}
	
	
	function execute(){
		
		$sql = $this->prepareQuery();
		$mysql_result = $this->_storage->query($sql);
		while ($row = $this->_storage->fetch_row($mysql_result)){
				$this->_interactions[] = new Interaction($this->_case_id,$row['ID']);								
		}		
	}
	
	function getInteractions(){
		return $this->_interactions;
	}
}

?>