<?php


function  getPlec($name, $def, $tryb=0, $option=''){
    $translateTab = array('K' => GENDER_FEMALE, 'M' => GENDER_MALE);
	if ($tryb){
            $pozycjaName = $def;
            if(key_exists($def, $translateTab))
            {
               $pozycjaName = $translateTab[$def];
            }

			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 100px;" disabled>';
			$result .= '<option value="'. $def .'">' . $pozycjaName . '</option>';
			$result .= '</select>';		
			return $result;
	}
	$lista = array('', 'K', 'M');
	$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;" '.$option.'>';
		foreach ($lista As $pozycja)
        {
            $pozycjaName = $pozycja;
            if(key_exists($pozycja, $translateTab))
            {
               $pozycjaName = $translateTab[$pozycja];
            }
		    $result .= '<option value="'. $pozycja .'" '. (($pozycja == $def) ? "selected" : "") .'>' . $pozycjaName . '</option>';
        }
	  $result .= '</select>';
	return $result;															
}


function  wysw_biuro_podrozy($name,$def,$tryb=0){
	if ($tryb){
		if ($def > 0){
			$query = "SELECT ID,nazwa,miasto,um_gen_numer FROM coris_signal_biurap    WHERE ID='$def' ";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);		
			 $result = '<select name="'.$name.'" style="font-size: 8pt;width: 380px;" disabled>';
			//$result .=  substr($row2['nazwa'],0,70).', '.$row2['miasto'].', '.$row2['um_gen_numer'];		
			$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.substr($row2['nazwa'],0,65).', '.$row2['miasto'].', '.$row2['um_gen_numer'].'</option>';
			  $result .= '</select>';
			return $result;
		}else{
			 $result = '<select name="'.$name.'" style="font-size: 8pt;width: 380px;" disabled>';
			
			$result .= '<option value="0" > &nbsp; </option>';
			$result .= '</select>';
			return $result;			  			
		}
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" onChange="zmien_nr_polisy(this.value)">
					<option value=""></option>';
			$query = "SELECT ID,nazwa,miasto,um_gen_numer FROM coris_signal_biurap    WHERE status=1 ORDER BY nazwa,miasto";						
			$mysql_result = mysql_query($query);
			$js_polisa_tab = '';
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.substr($row2['nazwa'],0,65).', '.$row2['miasto'].', '.$row2['um_gen_numer'].'</option>';
						$js_polisa_tab[$row2['ID']] = $row2['um_gen_numer'];
			}
		  $result .= '</select>';
		  
		  $result .= '<script>
		  
		  function zmien_nr_polisy(biuro_id){
		  		var lista_polis = new Array('.count($js_polisa_tab).');';
		  		foreach ($js_polisa_tab As $key=>$val)
		  			$result .= 'lista_polis['.$key.']= \''.$val.'\'; ' ;
		  		$result .= '
		  		if (document.getElementById(\'policy\')){
		  			if(lista_polis[biuro_id])
		  				document.getElementById(\'policy\').value= lista_polis[biuro_id];
		  			else
		  				document.getElementById(\'policy\').value= \'\';	
		  		}		  
		  }
		  </script>';
	}	
	return $result;															
}


function  wysw_ryzyko_gl($name,$def,$tryb=0,$opcje=''){
		$result='';	
	if ($tryb){
			$query = "SELECT ID,numer,concat(nazwa, ' (', numer, ')') as nazwa,sort FROM coris_signal_ryzyka_glowne   WHERE ID='$def' ORDER BY sort";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			$result = '<select name="'.$name.'" style="font-size: 8pt;width: 240px;" disabled>';					
				$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			 $result .= '</select>';
			 return $result;		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 8pt;" '.$opcje.'>
					<option value=""></option>';
			$query = "SELECT ID,numer,concat(nazwa, ' (', numer, ')') as nazwa,sort FROM coris_signal_ryzyka_glowne     WHERE status=1 ORDER BY sort";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';		
	}
	return $result;															
}


function  wysw_ryzyko_czastkowe($name,$def,$tryb=0,$case_id,$opcje=''){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyka_czastkowe    WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" '.$opcje.'>
					<option value=""></option>';
					
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyka_czastkowe      WHERE status=1 AND ID NOT IN (SELECT    ID_ryzyko  FROM coris_assistance_cases_reserve  WHERE case_id ='$case_id') ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}


function  wysw_ryzyko_czastkowe2($name,$def,$tryb=0,$case_id,$ryzyko_glowne,$opcje=''){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyka_czastkowe    WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		
					
			$query = "SELECT coris_signal_ryzyka_czastkowe.ID,coris_signal_ryzyka_czastkowe.numer,coris_signal_ryzyka_czastkowe.nazwa 
					FROM coris_signal_ryzyka_czastkowe,coris_signal_ryzyka_czastkowe_vs_glowne       
					WHERE coris_signal_ryzyka_czastkowe.status=1 
					AND coris_signal_ryzyka_czastkowe.ID =coris_signal_ryzyka_czastkowe_vs_glowne.ID_ryzko_czastkowe 
					AND coris_signal_ryzyka_czastkowe_vs_glowne.ID_ryzyko_glowne ='$ryzyko_glowne'
					
					ORDER BY kolejnosc,nazwa";		
			
			//AND coris_signal_ryzyka_czastkowe.ID NOT IN (SELECT    ID_ryzyko  FROM coris_assistance_cases_reserve  WHERE case_id ='$case_id')
			//ECHO $query;
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" '.$opcje.'><option value=""></option>';	
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}

function  wysw_ryzyko_czastkowe3($name,$def,$tryb=0,$case_id,$ryzyko_glowne,$opcje=''){
		
		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyka_czastkowe    WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		
					
			$query = "SELECT coris_signal_ryzyka_czastkowe.ID,coris_signal_ryzyka_czastkowe.numer,coris_signal_ryzyka_czastkowe.nazwa,coris_signal_ryzyka_czastkowe_vs_glowne.kolejnosc 
					FROM coris_signal_ryzyka_czastkowe,coris_signal_ryzyka_czastkowe_vs_glowne       
					WHERE coris_signal_ryzyka_czastkowe.status=1 
					AND coris_signal_ryzyka_czastkowe.ID =coris_signal_ryzyka_czastkowe_vs_glowne.ID_ryzko_czastkowe 
					AND coris_signal_ryzyka_czastkowe_vs_glowne.ID_ryzyko_glowne ='$ryzyko_glowne'
					AND coris_signal_ryzyka_czastkowe.ID NOT IN (SELECT    ID_ryzyko  FROM coris_assistance_cases_reserve  WHERE case_id ='$case_id')
					UNION
					SELECT coris_signal_ryzyka_czastkowe.ID,coris_signal_ryzyka_czastkowe.numer,coris_signal_ryzyka_czastkowe.nazwa, 0 As kolejnosc 
					FROM coris_signal_ryzyka_czastkowe WHERE numer = '550' AND coris_signal_ryzyka_czastkowe.ID NOT IN (SELECT    ID_ryzyko  FROM coris_assistance_cases_reserve  WHERE case_id ='$case_id')
					ORDER BY kolejnosc,nazwa";		
			
			//AND coris_signal_ryzyka_czastkowe.ID NOT IN (SELECT    ID_ryzyko  FROM coris_assistance_cases_reserve  WHERE case_id ='$case_id')
			//ECHO $query;
			$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" '.$opcje.'><option value=""></option>';	
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}

function  wysw_ryzyko_operat($name,$def,$tryb=0,$case_id,$opcje=''){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyko_operat     WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" '.$opcje.'><option value=""></option>';
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyko_operat       WHERE status=1 ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}
function  wysw_ryzyko_operat2($name,$def,$tryb=0,$ryzyko_czastkowe,$opcje=''){

		$result='';
	
	if ($tryb){
			$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyko_operat     WHERE ID='$def' ORDER BY nazwa";						
			$mysql_result = mysql_query($query);
			$row2 = mysql_fetch_array($mysql_result);			
			return $row['nazwa'];		
	}else{
		$result = '<select name="'.$name.'" id="'.$name.'" style="font-size: 7pt;" '.$opcje.'><option value=""></option>';
			//$query = "SELECT ID,numer,nazwa FROM coris_signal_ryzyko_operat       WHERE status=1 ORDER BY nazwa";						
			$query = "SELECT  operat.ID, operat.numer, operat.nazwa 
		FROM coris_signal_ryzyko_operat operat,coris_signal_ryzyko_operat_vs_ryz_czastkowe    operat_vs
		WHERE status=1 AND operat_vs.ID_operat= operat.ID AND operat_vs.ID_ryzyko_czastkowe='$ryzyko_czastkowe'  ORDER BY operat_vs.kolejnosc, nazwa";
		
			$mysql_result = mysql_query($query);
			while ($row2 = mysql_fetch_array($mysql_result)) {
						$result .= '<option value="'. $row2['ID'] .'" '. (($row2['ID'] == $def) ? "selected" : "") .'>'.$row2['nazwa'].'</option>';
			}
		  $result .= '</select>';
	}
	return $result;															
}

function wysw_currency2($name, $def,$tryb=0,$opcje=''){	
	$result='';
	
	if ($tryb){
			if ($def=='')
				$def="PLN";
			$result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 8pt;width: 50px;" >';					
				$result .= '<option value="'. $def .'">'.$def.'</option>';
			 $result .= '</select>';
			
	}else{
		$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 AND insurance = 1 ORDER BY currency_id";
						$mysql_result = mysql_query($query) ;
						$result = "<select name=\"".$name."\"  id=\"".$name."\" $opcje><option></option>";
						while ($row2 = mysql_fetch_array($mysql_result)) {
								$result .=  ($def == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
						}					
				$result .= '</select>	';
	}	
		return $result;			
}

function wysw_currency($name, $def,$tryb=0,$opcje=''){	
	$result='';
	
	if ($tryb){
			$result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 8pt;width: 50px;" disabled>';					
				$result .= '<option value="'. $def .'">'.$def.'</option>';
			 $result .= '</select>';
			
	}else{
		$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 AND insurance = 1 ORDER BY currency_id";
						$mysql_result = mysql_query($query) ;
						$result = "<select name=\"".$name."\"  id=\"".$name."\" $opcje><option></option>";
						while ($row2 = mysql_fetch_array($mysql_result)) {
								$result .=  ($def == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
						}					
				$result .= '</select>	';
	}	
		return $result;			
}


function wysw_currency_eur($name, $def,$tryb=0,$opcje=''){
	$result='';

	if ($tryb){
		$result = '<select name="'.$name.' id="'.$name.'" style="font-size: 8pt;width: 50px;" disabled>';
		$result .= '<option value="'. $def .'">'.$def.'</option>';
		$result .= '</select>';

	}else{
		$query = "SELECT currency_id FROM coris_finances_currencies WHERE currency_id='EUR' ORDER BY currency_id";
		$mysql_result = mysql_query($query) ;
		$result = "<select name=\"".$name."\" id=\"".$name."\" $opcje>";
		while ($row2 = mysql_fetch_array($mysql_result)) {
			$result .=  ($def == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
		}
		$result .= '</select>	';
	}
	return $result;
}

function wysw_currency_pln($name, $def,$tryb=0,$opcje=''){	
	$result='';
	
	if ($tryb){
			$result = '<select name="'.$name.' id="'.$name.'" style="font-size: 8pt;width: 50px;" disabled>';					
				$result .= '<option value="'. $def .'">'.$def.'</option>';
			 $result .= '</select>';
			
	}else{
		$query = "SELECT currency_id FROM coris_finances_currencies WHERE currency_id='PLN' ORDER BY currency_id";
						$mysql_result = mysql_query($query) ;
						$result = "<select name=\"".$name."\" id=\"".$name."\" $opcje>";
						while ($row2 = mysql_fetch_array($mysql_result)) {
								$result .=  ($def == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
						}					
				$result .= '</select>	';
	}	
		return $result;			
}


function wysw_currency_all($name, $def,$tryb=0,$opcje=''){	
	$result='';
	
	if ($tryb){
			$result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 8pt;width: 50px;" disabled>';					
				$result .= '<option value="'. $def .'">'.$def.'</option>';
			 $result .= '</select>';
			
	}else{
		$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 ORDER BY currency_id";
						$mysql_result = mysql_query($query) ;
						$result = "<select name=\"".$name."\" id=\"".$name."\" $opcje><option></option>";
						while ($row2 = mysql_fetch_array($mysql_result)) {
								$result .=  ($def == $row2[0]) ? "<option value=\"$row2[0]\" selected>$row2[0]</option>" : "<option value=\"$row2[0]\">$row2[0]</option>";
						}					
				$result .= '</select>	';
	}	
		return $result;			
}


function row_agent($id){
	global $DBase;
	
	
	$query = "SELECT * FROM coris_signal_biurap   WHERE ID = '$id'";		
	
	$result = mysql_query($query);	
	if (!$result) {
		die ("Query Error: $query <br>".mysql_error());
	}

	if (mysql_num_rows($result) > 0 ){
			$row = mysql_fetch_array($result);
			return $row;
	}else{
		return null;
	}
}

function getNameproduct($id){
	$q= "SELECT numer,nazwa FROM coris_signal_ryzyka_glowne WHERE ID='$id'";
	$mr = mysql_query($q);
	$row= mysql_fetch_array($mr);
	return $row['numer'];
	
}
/*
function format_konto($txt){
  $txt = str_replace(' ','',$txt);
  $tab = array();
  
  for ($i=0;$i<strlen($txt);$i++){
    if ($i==0){
      $tab[] = substr($txt,0,2);
    }else{
      $tab[] = substr($txt,($i-1)*4+2,4);
    }
  }  
  return implode(' ',$tab);  
}
*/

function format_konto($txt){
	
  $txt = str_replace(' ','',$txt);
  $tab = array();
  $start=0;
  if ( !(substr($txt,0,2) > 0 ) ){
  	$start=2;  	
  	$tab[] = substr($txt,0,2);
  	$txt = substr($txt,3,strlen($txt)-2);
  }
  for ($i=0;$i<strlen($txt);$i++){
    if ($i==0)
      $tab[] = substr($txt,0,2);
    else{
      $tab[] = substr($txt,($i-1)*4+2,4);
    }
  }
  
  return implode(' ',$tab);
  
}
?>
