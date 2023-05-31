<?php



class SearchInteractions{
	
	protected static  $table_store_interaction = 'store_interaction';
	protected static  $table_store_document = 'store_document';
	
		
	protected $_param_query;
	protected $_order_query;
	protected $_limit_query;
	protected $_paging;
	protected $_interactions=array();
	protected $_storage;
	
	
	function __construct($param=array(),$order=array(),$limit=array()){			
			$this->_param_query=$param;
			$this->_order_query=$order;					
			$this->_limit_query=$limit;
			$this->_storage = Application::getStorage();					
	}
	
	
	function prepareQuery($param){
		

					$var = array();
					//$var[] = self::$table_store_interaction.".direction = '".Interaction::$DIRECTION_OUT."' ";		
					$var[] = 'si.ID_case = cac.case_id ';					
					$limit_per_page = 20;
					$page = 0;
		
		foreach ($param As $key => $val ){
						if (	$val != ''	){
						  if ($key == 'search_type'){
								if ($val == 1 ){ //nadawca
									$var[] = "  interaction_contact  LIKE '%".$param['search_txt']."%'";										
								}else if ($val == 2 ){ // nr sprawy		
									if ($param['search_txt'] != ''){							
										$ca_id = CorisCase::search_case($param['search_txt']);
										$var[] = "   ID_case IN ($ca_id) ";						
									}					
								}else if ($val == 3 ){ // temat
										$var[] = "  subject  LIKE '%".$param['search_txt']."%'";																
								}								
							}else if ($key == 'document_type'){
								if ($val == 'all'){ //										
										$var[] = "( ID_document_type = ".Interaction::$_TYPE_EMAIL ." OR ID_document_type = ".Interaction::$_TYPE_FAX .' OR ID_document_type = '.Interaction::$_TYPE_SMS .' )';										
								}else if ($val == 'email' ){ // 
									$var[] = "  ID_document_type = ".Interaction::$_TYPE_EMAIL;									
								}else if ($val == 'fax' ){ // 
									$var[] = "  ID_document_type = ".Interaction::$_TYPE_FAX;																
								}else if ($val == 'sms' ){ // 
									$var[] = "  ID_document_type = ".Interaction::$_TYPE_SMS;																
								}								
							}else if ($key == 'direction'){
								if ($val == 'all'){ //										
										//$var[] = "(  )';										
								}else if ($val == 'in' ){ // 
									$var[] = "  direction = '".Interaction::$DIRECTION_IN."'" ;									
								}else if ($val == 'out' ){ // 
									$var[] = "  direction = '".Interaction::$DIRECTION_OUT."'";																
								}								
							}else if ($key == 'case_id'){
									$var[] = '  ID_case = \''.intval($val).'\''	;
							}else if ($key == 'data_od'){
									$var[] = '  	si.date >= \''.mysql_escape_string($val).' 00:00:00\''	;
							}else if ($key == 'data_do'){
									$var[] = '  	si.date <= \''.mysql_escape_string($val).' 23:59:59\''	;							
							}else if ($key == 'user'){
									if ( intval($val) > 0 )									
										$var[] = '  ID_user = \''.intval($val).'\''	;
							}else if ($key == 'count_per_page'){
									$limit_per_page = intval($val)	;
							}else if ($key == 'page'){
									$page = intval($val)	;
							}else if ($key == 'branch_id'){
									$var[] = '  cac.coris_branch_id = \''.intval($val).'\'';
										;
							}
						}
			}

			/*	$query = "SELECT ".self::$table_store_interaction.".ID, ".self::$table_store_interaction.".ID_case 
				FROM ".self::$table_store_interaction." ";		
		$query .= " WHERE 	".implode(' AND ',$var)." ORDER BY date desc ";
		
		$query_ = "SELECT count(".self::$table_store_interaction.".ID)
				FROM ".self::$table_store_interaction." ";		
		$query_ .= " WHERE 	".implode(' AND ',$var);*/
				$query = "SELECT si.ID, si.ID_case 
				FROM ".self::$table_store_interaction." si, coris_assistance_cases cac ";		
		$query .= " WHERE 	  ".implode(' AND ',$var)." ORDER BY si.date desc ";
		
		$query_ = "SELECT count(si.ID)
				FROM ".self::$table_store_interaction." si, coris_assistance_cases cac ";		
		$query_ .= " WHERE 		".implode(' AND ',$var);  
		
		  
					$paging = new PagingDoc();
					$paging->go($query_,$limit_per_page,$page);

					if ($paging->getCountAll() > 0 )
     						$query .= " limit ".$paging->getActualPage().",$limit_per_page";
     	     
     				$countAll = $paging->getCountAll();
					$summary = $paging->getSummary();
					$pageSelector = $paging->getPageSelector();
				
     		$this->_paging = 'Ilo¶æ pozycji: '.$countAll;
			$this->_paging  .= ' &nbsp;&nbsp;&nbsp;'.$summary;
			$this->_paging  .= '<br>&nbsp;&nbsp;&nbsp;';
			foreach ($pageSelector as $poz) {
				if ($poz['val'] != '')
					$this->_paging  .= '<a href="javascript:;" onClick="goResultPage('.$poz['val'].')">'.$poz['desc'].'</a>&nbsp;';
				else	
					$this->_paging  .= $poz['desc'].'&nbsp;';
			}
			//echo $query_;
		    return $query;
	}
	
	
	function execute($param){
		
		$sql = $this->prepareQuery($param);
		//echo $sql;
		$mysql_result = $this->_storage->query($sql);
		while ($row = $this->_storage->fetch_row($mysql_result)){			
				$this->_interactions[] = new Interaction($row['ID_case'],$row['ID']);								
		}		
	}
	
	function getInteractions(){
		return $this->_interactions;
	}

	function getPaging(){
		return $this->_paging;
	}
}

?>