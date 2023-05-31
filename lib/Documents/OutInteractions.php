<?php



class OutInteractions{

	protected static  $table_store_interaction = 'store_interaction';
	//protected static  $table_store_document = 'store_document';


	protected $_param_query;
	protected $_order_query;
	protected $_limit_query;
	protected $_paging;
	protected $_interactions=array();


	function __construct($param=array(),$order=array(),$limit=array()){
			$this->_param_query=$param;
			$this->_order_query=$order;
			$this->_limit_query=$limit;
	}


	function prepareQuery($param){


					$var = array();
					$var[] = " si.direction = '".Interaction::$DIRECTION_OUT."' ";
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
										$var[] = "   si.ID_case IN ($ca_id) ";
									}
								}else if ($val == 3 ){ // temat
										$var[] = "  si.subject  LIKE '%".$param['search_txt']."%'";
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
							}else if ($key == 'data_od'){
									$var[] = '  si.date >= \''.mysql_escape_string($val).' 00:00:00\''	;
							}else if ($key == 'data_do'){
									$var[] = '  si.date <= \''.mysql_escape_string($val).' 23:59:59\''	;
							}else if ($key == 'user'){
									if ( intval($val) > 0 )
										$var[] = '  si.ID_user = \''.intval($val).'\''	;
							}else if ($key == 'count_per_page'){
									$limit_per_page = intval($val)	;
							}else if ($key == 'page'){
									$page = intval($val)	;
							}else if ($key == 'branch_id'){
                                  if ($val == 1)
                                      $var[] = "   cac.coris_branch_id  ='1' ";
                                  if ($val == 2)
                                      $var[] = "   (cac.coris_branch_id  ='2' || cac.coris_branch_id  ='3') ";
									//$var[] = '  cac.coris_branch_id = \''.intval($val).'\''	;
							}
						}
			}

				$query = "SELECT si.ID, si.ID_case
				FROM ".self::$table_store_interaction." si, coris_assistance_cases cac ";
		$query .= " WHERE 	si.ID_case = cac.case_id AND ".implode(' AND ',$var)." ORDER BY si.date desc ";

		$query_ = "SELECT count(si.ID)
				FROM ".self::$table_store_interaction." si, coris_assistance_cases cac ";
		$query_ .= " WHERE 	si.ID_case = cac.case_id AND	".implode(' AND ',$var);


					$paging = new PagingDoc($_SESSION['GUI_language']);
					$paging->go($query_,$limit_per_page,$page);


					if ($paging->getCountAll() > 0 )
     				$query .= " limit ".$paging->getActualPage().",$limit_per_page";



    				$countAll = $paging->getCountAll();
					$summary = $paging->getSummary();
					$pageSelector = $paging->getPageSelector();



     		$this->_paging = NUMBER_OF_ITEMS.' '.$countAll;
			$this->_paging  .= ' &nbsp;&nbsp;&nbsp;'.$summary;
			$this->_paging  .= '<br>&nbsp;&nbsp;&nbsp;';
			foreach ($pageSelector as $poz) {
				if ($poz['val'] != '')
					$this->_paging  .= '<a href="javascript:;" onClick="goResultPage('.$poz['val'].')">'.$poz['desc'].'</a>&nbsp;';
				else
					$this->_paging  .= $poz['desc'].'&nbsp;';
}
		    return $query;
	}


	function execute($param){

		$sql = $this->prepareQuery($param);
		$mysql_result = mysql_query($sql);
		if (!$mysql_result) {echo "QE: ".$sql."<br>".mysql_error();}
		while ($row = mysql_fetch_array($mysql_result)){
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