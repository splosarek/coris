<?php


class Application{
	
	static $DB_STORAGE = NULL;
	static $DEF_APPLICATION_ID = 1;
	
	static function getCurrentUser(){
	//	return 76;
		return $_SESSION['user_id'];
	}
	
	
	static function getUser($user_id){
		return new UserObject($user_id);
	}
	
	
	static function addCaseHistory($case_id,$actiongroup_id){		
		$query = "INSERT INTO coris_assistance_cases_history (case_id, user_id, actiongroup_id, session_id, date) VALUES ('".$case_id."', '".$_SESSION['user_id']."','".$actiongroup_id."', '".$_SESSION['session_id']."', NOW())";
		if ($result = mysql_query($query)) {								
			return true;
		} else {		
			return false;
		}		
	}
	
	static function getStorage(){
		if (self::$DB_STORAGE == NULL){
			self::$DB_STORAGE = new	AplicationStorage();
		}
		return self::$DB_STORAGE;
	}
	
	static function getUserName($id){
			if ($id==0) return;
			$query = "SELECT name, surname FROM coris_users WHERE user_id ='$id'";
			$mysql_result = mysql_query($query);
			
			$row= mysql_fetch_array($mysql_result);
			return $row[0].' '.$row[1];			
	}
	
	static function getUserInitials($id){
		if ($id==0) return;
		$query = "SELECT initials FROM coris_users WHERE user_id ='$id'";
		$mysql_result = mysql_query($query);
		
		$row= mysql_fetch_array($mysql_result);
		return $row[0];
	}
	
	static function getContrahnetParam($id,$param){
		$query = "SELECT $param FROM coris_contrahents WHERE contrahent_id='$id'  LIMIT 1";
		$mysql_result = mysql_query($query) or die(mysql_error());
		$row=mysql_fetch_array($mysql_result);
		return $row[0];
	}
	
	function getActiveDestinationList(){ //slownik
		$result = array();
		
	/*	$query = "SELECT * FROM `".self::$_store_table_destination."` WHERE active=1 ORDER BY name";
		$db_result = $this->query($query);
   					while ($row =  $this->fetch_array($db_result)){  						
   						$result[$row['ID']] = $row['name'] ;   									   									
					}					
					return $result;
		*/
	}


    /**
     *
      Formatka: lista rozwijana krajow
     * $defaultCountrySymbol = '',
     * $lang = '',
     * $idName = "countryList",
     * $selectListOption = '',
     * $showPrefixes = false
     */
    static function countryList($defaultCountrySymbol = '', $lang = '', $idName = "countryList", $selectListOption = '', $showPrefixes = false)
    {
        $re ='';
        //$re .= '<select tabindex="-1" name="' . $idName . '" id="' . $idName . '" onClick="' . $onClick . '" onChange="' . $onChange . '" style="' . $style . '" class="' . $class . '" '. $listOption .'>';
        $re .= '<select tabindex="-1" name="' . $idName . '" id="' . $idName . '" style="width:170px;" '. $selectListOption .'>';
        $re .= '<option value=""></option>';

        if('pl' != $lang)
        {
            $sql = "SELECT country_id, `name_eng` AS `name`, prefix
                    FROM coris_countries
                    ORDER BY name_eng";
        }else
        {
            $sql = "SELECT country_id, `name`, prefix
                    FROM coris_countries
                    ORDER BY name";
        }

        $mysqlResult = mysql_query($sql);

        while ($row = mysql_fetch_assoc($mysqlResult))
        {
            $re .= '<option value="' . $row['country_id'] . '" ' . ($defaultCountrySymbol == $row['country_id'] ? 'selected="selected"':'') . '>';
            $re .= ($showPrefixes && $row['prefix'] != "") ? $row['name'] . " (+" . $row['prefix'] . ")" : $row['name'];
            $re .= '</option>';
        }
        $re .= '</select>';
        return $re;
    }


    static function getCountryName2($country_id, $lang='pl'){
    	$query = "SELECT * FROM coris_countries WHERE country_id='$country_id' ";
    	$mysql_result = mysql_query($query);
    	$row = mysql_fetch_array($mysql_result);
    	if ($lang=='pl')
    		return $row['name'];
    	else
    		return $row['name_eng'];
    }
    static function getCountryName($country_id, $lang='pl'){
    	$query = "SELECT * FROM coris_countries WHERE country_id='$country_id' ";
    	$mysql_result = mysql_query($query);
    	$row = mysql_fetch_array($mysql_result);
    	if ($lang=='pl')
    		return $row['name'];
    	else
    		return $row['name'].'-'.$row['name_eng'];
    }
    
   /* static function print_currency($val,$prec=2,$sep=''){
		if (is_numeric($val)){   		
			return number_format(self::ev_round($val,$prec), $prec, ',', $sep);		
		}else{
			$val = str_replace(',','.',$val);				
			return number_format(self::ev_round($val,$prec), $prec, ',',$sep);		
		}
    }

    
	static	function ev_round($liczba,$precyzja=0){
			$mnoznik = pow(10,$precyzja);
			if ($liczba>0){
				$wyn = $liczba*$mnoznik + 0.5;	
				return (self::ev_intval($liczba*$mnoznik + 0.5))/$mnoznik;	
			}else{
				return (self::ev_intval($liczba*$mnoznik - 0.5))/$mnoznik;	
			}
		}
		
		
	static	function ev_intval($liczba){
			$liczba_tmp = (String) $liczba;
			$poz = strpos($liczba_tmp,'.');
			if ($poz === false ){
				return $liczba;
			}else{
				return substr($liczba_tmp,0,$poz);
			}
		}
*/

	function getCaseInfo($case_id){
	$query = "SELECT coris_contrahents.contrahent_id ,coris_contrahents.name,coris_contrahents.o_klnagsim As shortName,coris_assistance_cases.paxname,coris_assistance_cases.paxname,coris_assistance_cases.paxsurname,coris_assistance_cases.type_id,coris_assistance_cases.number,coris_assistance_cases.year,coris_assistance_cases.client_ref, concat(number,'/',substring(year,3,2),'/',type_id,'/',client_id) As fullNumber  FROM coris_assistance_cases,coris_contrahents  WHERE coris_assistance_cases.case_id='$case_id' AND coris_contrahents.contrahent_id=coris_assistance_cases.client_id LIMIT 1" ;
	$mysql_result = mysql_query($query) OR die (mysql_error());
	
	if (mysql_num_rows($mysql_result)==0) return null;
	$row= mysql_fetch_array($mysql_result);
	return $row;
}
}




?>