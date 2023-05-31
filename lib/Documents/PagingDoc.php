<?php


/*
* Klasa realizuj?cy podzia³ strony
* $ilosc - ilosc pozycji na stronie
* $op - parametry do skryptu
* $query zapytanie (select count(*).... ) takie same warunki jak dla zapytania roboczego
* $tryb =0 jw =1 Q\query pelne zapytanie (np. z distinctrow)
*
*Sample
       $query = select * from where warunek='$warunek' order   ;
       $query_ = select count(*) from where warunek='$warunek' ;
       $op = "&amp;warunek=$warunek"  

          
      if ( isset($query_) ){
        include("include/podzial_str.inc.php");
     	$ilosc=10;
     	
     	$paging = new Paging($pageName);     	     
     	$paging->go($query_,$ilosc,$op);
     
     	if ($paging->getCountAll() > 0 )
     		$query .= " limit ".$paging->getActualPage().",$ilosc";
     	     
     	echo "<br><b>Znaleziono pozycji:</b> ".$paging->getCountAll();
     	echo $paging->getSummary();
     
      	echo lista_pozycji($pageName,$query);
          	
     	echo $paging->getSummary();
     	echo $paging->getPageSelector();     	
   	}else{    
    		echo lista_pozycji($pageName,$query); // tryb bez podzialu stron
   	}
   	
   	
   	$paging->getNewPageSelector();
   	zwraca tablice:
   	['prev']   	   	array(val => '', desc => '')
   	['pages'][]  - kolejne strony   array(val => '', desc => '')
   	['next']   array(val => '', desc => '')
   	['numberof'] array(val => '', desc => '')
*/

class PagingDoc{

	private $txt_pl = array('next' => 'Nastêpne','prev' => 'Poprzednie','page' => 'Strona' , 'of' => 'z');
	private $txt_eng = array('next' => 'Next','prev' => 'Previous','page' => 'Page' , 'of' => 'of');
	private $txt_statement = '';
	private $_url = '';
	private $_lang = '';
	private $_tryb = 0;
	private $_query = '';
	private $_ilosc = 0;
	private $_param = '';
	
	private $_count_all = '';
	private $_page_selector = '';
	private $_n_page_selector = '';
	private $_actual_page ='' ;
	private $_summary ='' ;
	private $_page ='' ;
	
	
	function __construct($lang='pl',$tryb=0){
		$this->_tryb = $tryb;
		$this->_lang = $lang;							
		
		if ($lang == 'en'){
			$this->txt_statement = $this->txt_eng;
		}else{
			$this->txt_statement = $this->txt_pl;
		}
	}
			
	function go($query,$ilosc,$page){
			$this->_query =$query;
			$this->_ilosc=$ilosc;
			$this->_page = $page;
	
			$this->podzial_na_strony();
	}	
	
	public function getCountAll(){		
		return $this->_count_all;
	}
	
	public function getPageSelector(){		
		return $this->_page_selector;
	}
	
	public function getNewPageSelector(){		
		return $this->_n_page_selector;
	}
	
	public function getActualPage(){		
		return $this->_actual_page;
	}

	public function getSummary(){		
		return $this->_summary ;
	}
	
	function podzial_na_strony(){		

		$storage = Application::getStorage();
		
		$query = $this->_query;
		$ilosc = $this->_ilosc;
		$op = $this->_param ;
		$tryb = $this->_tryb;
		
		$str = $this->_page;
		
		    $link = array();
		    $link_new = array();
		    $link_new_licznik=0; 

		    $mysql_result = $storage->query( $query);

		    if ($tryb==0){
		       $row =  $storage->fetch_array($mysql_result);
		       $ilosc_all = $row[0];  // ilsoc wszytkich pozycji       
		    }else{
		        $ilosc_all = $storage->num_rows($mysql_result);  // ilsoc wszytkich pozycji
		    }
		
		  //  
		
		    $ilosc_str = floor($ilosc_all  / $ilosc);
		    if ( ($ilosc_all % $ilosc) > 0  ) $ilosc_str++;
		
		    if (!( $str > 0 ) )   $str = 1;
		
		    if( $str > $ilosc_str ) $str=$ilosc_str;
		
		    if ($ilosc_str > 1) {
		      if  ($str > 1 ){
					 $link[]  = array( 'val' => ($str-1), 'desc' => '[&#139;&#139;'.$this->txt_statement['prev'].']');
					 $link_new['prev'] = array( 'val' => ($str-1), 'desc' => 'PREV' );
		      }
   		       $end=$ilosc_all/($ilosc*10);
		       if ( ($ilosc_all%($ilosc*10)) >0 ) $end++;
		
		       $dz = floor(($str-1)/10);
		       if ( (10+10*$dz) > $ilosc_str ){
		            $end=10-((10+10*$dz)-$ilosc_str);
		        }
		        else
		            $end=10;
		       if ( $end > 10 ) $end = 10;
		
		       for ($i = 1; $i <= $end; $i++) {
		           if (($i+($dz*10)) == $str) {		                
		                $link[] = array('val' => '' ,'desc' => ($i+($dz*10)));
		                $link_new['pages'][] = array('val' => '' ,'desc' => ($i+($dz*10)));;		                		                
		           }else {		      
		                $link[] =  array('val' => ($i+($dz*10)) ,'desc' => ($i+($dz*10)) );
		                $link_new['pages'][] =  array('val' => ($i+($dz*10)) ,'desc' => ($i+($dz*10)) );
		        }
		      }
		      if ( $str < $ilosc_str ){				          
		           $link[] = array('val' => ($str+1), 'desc' => '['.$this->txt_statement['next'].'&#155;&#155;]', 'val2' =>  $ilosc_str, 'desc2' => $this->txt_statement['of'].' '.$ilosc_str); 
		           $link_new['next']= array('val' => ($str+1), 'desc' => 'NEXT' );
		           $link_new['numberof']= array('val' => $ilosc_str, 'desc' => 'NUMBER_OF' );											            
		      }
		    }

		   $p_od = (($str-1)*$ilosc)+1;
		    if ( $ilosc_all == 0 ) $p_od=0;
		
		    if ( $ilosc > $ilosc_all)
		        $p_do = $p_od + $ilosc_all-1;
		    else
		        $p_do = $p_od + $ilosc-1;
		    if ( $p_do > $ilosc_all ) $p_do = $ilosc_all;
		
		    if ( $ilosc_all == 0 ){ $p_od=0; $p_do=0;}
				    
    
		    $this->_page_selector = $link;    
		    $this->_n_page_selector = $link_new;    
		    $this->_actual_page = $p_od-1;		   
		   	$this->_count_all = $ilosc_all;		   
		    $this->_summary = $this->txt_statement['page']." $str ".$this->txt_statement['of']." $ilosc_str";
		} 

}
 
?>
