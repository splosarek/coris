<?php

//ini_set('include_path',get_include_path().';d:/work/projekty/coris/www/coris//lib/');
//include('Documents/Documents.php');


function module_update(){			
	global  $pageName;
	$result ='';

	
	$change = isset($_POST['change']) ? $_POST['change'] : null;
	$case_id = getValue('case_id');
	
	
	$check_js = '';
	$message = '';

	echo $message;	
}


function module_main(){
	global $case_id;
	$result = '';
	


	$result .=  '<div style="width: 1323px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';	
		$result .=  dokumenty('',0);	
	$result .=  '</div>';	
	

			$result .=  '<div style="clear:both;"></div>';
	return $result;	
}


function dokumenty($row,$tryb){
	global $case_id;
	$result = '';
	
	$form_action=getValue('form_action');
	$id=getValue('id');

	
		$result .= '  <script language="javascript">
			    <!--
			      function loadDocument(opcja) {
			        if (opcja != "") {			        		
			          			window.open(opcja,\'\',\'toolbar=0,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width=\'+ (screen.availWidth - 6) + \',height=\'+ (screen.availHeight - 100) +\',left=0,top=0\');
		       		}		
			      }
			      
			      
			    function sort_order(s,sd){
						$(\'sort_direction\').value = sd
						$(\'sort\').value = s;
				}
				  
			    function zastosuj_filter(){
			    		typ=\'\';
			    		cat=\'\';
			    		txt=\'\';
			    		s=\'\';
			    		sd=\'\';			    		
			    		
						if ($(\'filter_documents\')) typ = $(\'filter_documents\').value; 
						if  ($(\'category_id\')) cat = $(\'category_id\').value
						if ($(\'search_txt\')) txt = $(\'search_txt\').value
						if ($(\'order\')) s = $(\'order\').value
						if ($(\'sort_direction\')) sd = $(\'sort_direction\').value
						
						init_documents('.$case_id.',\'reclamation=1&type=\'+typ+\'&category=\'+cat+\'&txt_search=\'+txt+\'&order=\'+s+\'&sort_direction=\'+sd)
				}
				
				function clickOrder(zm){
					if ($(\'order\').value==zm) {	
						if ($(\'sort_direction\').value == \'\'){
								$(\'sort_direction\').value=\'DESC\';
						}else{
								$(\'sort_direction\').value= $(\'sort_direction\').value==\'ASC\' ? \'DESC\' : \'ASC\';
						}
					}else{
						$(\'order\').value=zm;
						$(\'sort_direction\').value=\'DESC\';
					}									
					zastosuj_filter();				
				}				
			    //-->
			    </script>';
	$result .= '	<table cellpadding="2" cellspacing="2" border="0" width="100%">
			                  <tr>            
			                    <td align="center" colspan="2" style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
			                    <div id="historyframe"  name="historyframe" style="width:100%;height:700px;overflow:auto;" > </div>';			                  
			

			    
$result .= '
			              </td>
			            </tr>
			          </table>'; 
	$result .= '<script>
		
		
			window.addEvent(\'domready\', function() {  
					init_documents(\''.$case_id.'&reclamation=1\',\'\');
			});
	</script>';
//	}
	return $result ;
}



    
?>