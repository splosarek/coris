<?php require_once('include/include_ayax.php'); 


   

if ( $_SESSION['coris_branch'] == 2 ){	
	$branch_id = 2;
}else if ( $_SESSION['coris_branch'] == 3 ){	
	$branch_id = 3;
}else{	
	$branch_id = getValue('branch') > 0 ? getValue('branch') : $_SESSION['coris_branch'];		
}

html_start($title_page,'onLoad="focus();"');
?>

<script language="JavaScript" type="text/JavaScript">
<!--

documentID=0;
resizeTo(1050,760);



function select_case(case_id){

	if (documentID > 0 && confirm('<?php echo FK_FAX_CZYCHDOLDOKDOSPR;  ?>') ){	
			load_ayax('case_add_form','ayax/sorter_doc_add_to_case.php?case_id='+case_id+'&doc_id='+documentID<?php echo '+\'&branch_id='.$branch_id.'\''; ?>);
	}else{
			alert('Prosze wybraæ dokument');
	}
}

function move_email(id,dest){

		if ( confirm('Czy na pewno?') ){
			

			$('form_action').set('value','docMove');
			$('form_action_value').set('value',id+'|'+dest);
			$('dok_szukaj').click();

			$('form_action').set('value','');
			$('form_action_value').set('value','');			
			
			//load_document(0);
		}
		
	}
		

function delete_document(id){

	if ( confirm('<?php echo FK_EMAIL_POTWUSMAILA ; ?>') ){
		

		$('form_action').set('value','docDelete');
		$('form_action_value').set('value',id);
		$('dok_szukaj').click();

		$('form_action').set('value','');
		$('form_action_value').set('value','');
		
		load_document(0);
	}
	
}


function load_documents(){
	load_ayax('document_list','ayax/sorter_doc_list.php<?php echo '?branch_id='.$branch_id ?>');
}


function load_document(id){
	load_ayax('document_preview','ayax/doc_view.php?id='+id<?php echo '+\'&branch_id='.$branch_id.'\'' ?>);
}

function load_case(){
	load_ayax('case_list','ayax/sorter_case_list.php<?php echo '?branch_id='.$branch_id ?>');
}



window.addEvent('domready', function() {  
		load_documents();
		load_case();
});


function save_form(div_id,form_id,url){    	//,encoding: 'ISO-8859-2'
	var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
			onComplete: function(responseTree, responseElements, responseHTML) { 
				$(div_id).removeClass('ajax-loading')
				$(div_id).set('html', responseHTML); 
			} 		
			}).post($(form_id));
	$(div_id).empty().addClass('ajax-loading')
}




//-->
</script>

<table width="990"  border="1">
  <tr>
    <td valign="top" WIDTH="390"><div id="document_list" style="width:390px;height:350px;overflow:auto;"></div></td>
    <td width="520" rowspan="2"><div id="document_preview" style="width:610px;height:710px;overflow:auto;"></div><!-- <iframe HEIGHT=710 width=610 src="FK_fax_in_preview.php" name="frame_preview"></iframe>  --></td>
  </tr>
  <tr>
    <td><div id="case_list" style="width:390px;height:350px;overflow:auto;"></div><!--  <iframe HEIGHT=350 width=380 src="FK_fax_case.php" name="frame_case"></iframe>  --></td>
  </tr>
</table>

<br>
</body>
</html>