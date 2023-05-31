<?php 

include('include/include_ayax.php');
 	

$attach_id = getValue('id');
$action = getValue('action');
$attach = getValue('attach');
	$doc = null;
if ($attach_id>0){
	
	if ($attach != '' ){		
		$doc1 = new Document($attach_id);
	
		if ($doc1->get_document_type() == 5 ){
			if ( $doc1->get_direction() == Document::$DIRECT_IN ){
				//getAttachmentName($name)
				foreach ( $doc1->getAttchments()->get_list() As $position ){
						if ($position->getName() == $attach ){
							$doc = new Document($position->getObjectID());
						}		
				}				
			}else{ // doc out
				if ($attach == 'LOG'){
						$doc = new Fax( $attach_id );


	  					header("Pragma: public");
      					header("Expires: 0");
      					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      					header("Cache-Control: public");
      					
      					 @header("Content-type: text/plain");
      	 				@header("Content-Disposition: inline; filename=\"FaxLog".$doc1->getObjectID().".txt\"");
												
					  	@header("Content-Length: ". strlen($doc->get_long_log()));;
      					echo $doc->get_long_log();;      
						
      					exit();
					
					
				}
			} 
			
		}
		
						
	}else{
	  $doc = new Document($attach_id);
	}
	
	if ($doc == null) die("ERROR DOC: ".$attach_id.', attach:'.$attach);
	
	  header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: public");
    
   
   
      
      if ($action=='download'){
      	 @header("Content-type: ".$doc->getContentType());
      	 @header("Content-Description: File Transfer");
      	 @header("Content-Transfer-Encoding: chunked");
      	 @header("Content-Disposition: attachment; filename=\"".$doc->getName()."\"");
      	 
      }else	{
   		 @header("Content-type: ".$doc->getContentType());
      	 @header("Content-Disposition: inline; filename=\"".$doc->getName()."\"");
      	 
      }
      //exit;
    //  @header("Content-Transfer-Encoding: binary");
      @header("Content-Length: ". strlen($doc->getBody()));;
      echo $doc->getBody();      	
}else{
	echo "Error";
}
   
?>