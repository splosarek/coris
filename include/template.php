<?php

class Template {
	
	var $template_content='';
	var $zmiany = array();
	
	function  load_template($file){
		  if (!file_exists($file)) return null;
		  $fcontents = file ($file);
		  $this->template_content = implode('',$fcontents);
		  
	}
	
	
	function set($name,$var){
		$this->zmiany[$name] = $var;		
	}
	
	
	function realize(){
		if ($this->template_content != ''){
			 return strtr($this->template_content,$this->zmiany );
		}
		
	}
}

?>