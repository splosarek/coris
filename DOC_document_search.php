<?php 
require_once('include/include_ayax.php'); 

html_start(AS_DOC_WYSZUK,'');

$def_cppage = 10;
$def_page = 0;
?>

<script language="JavaScript" type="text/JavaScript">
<!--


function save_form(div_id,form_id,url){    	//,encoding: 'ISO-8859-2'	
	var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
			onComplete: function(responseTree, responseElements, responseHTML) {				
				$(div_id).removeClass('ajax-loading')
				$(div_id).set('html', responseHTML); 
			} 		
			}).post($(form_id));
	$(div_id).empty().addClass('ajax-loading')
}
</script>

<?php

$status = (getValue('status')>0) ? getValue('status') : 0;

$status1_sel = '';
$status2_sel = '';
$status3_sel = '';
$status4_sel = '';
$status5_sel = '';
$zm = 'status'.$status.'_sel';
$$zm = 'selected';

$case_id = getValue('case_id');
$document_type = getValue('document_type');
$document_direction = getValue('document_direction');
$only_case = getValue('only_case');

$search_type = getValue('search_type');
$search_txt = getValue('search_txt');


if ($case_id>0){	
	$case = new CorisCase($case_id);
	$branch = $case->getBranchId();
}


$only_case_checked = '';
if ($document_type=='' || $only_case==1){
	$only_case_checked	= 'checked';
	$only_case=1;
}

if ($document_type=='') $document_type='all';
if ($document_direction=='') $document_direction='all';


$def_cppage = 10;
$def_page = 0;


?>




<form name="form_search" id="form_search" method="post" action="">
<input type="hidden" name="case_id" value="<?php echo $case_id; ?>">
<input type="hidden" name="sd_cppage" id="sd_cppage" value="<?php echo $def_cppage; ?>">
<input type="hidden" name="sd_page" id="sd_page" value="<?php echo $def_page; ?>">
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch; ?>">

<table WIDTH=750 cellpadding="1" cellspacing="0" border="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong><?= AS_DOC_DOK ?></strong> <?php 
    	if ($case_id>0){
    		echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="only_case" value="1" '.$only_case_checked.' style="background-color:#CCCCCC;" > '.AS_DOC_TYLKSPR.': ';
    		
    		echo $case->getCaseNumber();
    	}?></td>

  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><table width="100%" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align=right><?= AS_DOC_DATOD ?>:&nbsp; </td>
        <td colspan="2" align=left><input name="data_od" type="text" size="11" maxlength="10" value="<?php echo getValue('data_od'); ?>">
          &nbsp;&nbsp;&nbsp;<?= AS_DOC_DATDO ?>: 
          <input name="data_do" type="text" size="11" maxlength="10" value="<?php echo getValue('data_do'); ?>">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Szukaj po: <select name="search_type" >          
          <option value=0 ></option>
          <option value=1 <?php echo ($search_type==1) ? 'selected': ''?>><?= AS_DOC_NADODB ?></option>
          <option value=2 <?php echo ($search_type==2) ? 'selected': ''?>><?= CASENO ?></option>          
          <option value=3 <?php echo ($search_type==3) ? 'selected': ''?>><?= AS_CASD_TEMAT ?></option>          
</select> <input name="search_txt" type="text" size="11" maxlength="200" value="<?php echo getValue('search_txt'); ?>">
          </td>
        </tr>
      <tr>
        <td align=right><?= AS_DOC_TYPDOK ?></td>
        <td align=left><select name="document_type" >          
          <option value="all" <?php echo ($document_type== 'all' || $document_type== '') ? 'selected' : '' ;?>><?= AS_DOC_ALL ?></option>
          <option value="email" <?php echo ($document_type== 'email') ? 'selected' : '' ;?>><?= AS_DOC_TYLKEMAIL ?></option>
          <option value="fax" <?php echo($document_type== 'fax') ? 'selected' : '' ;?>><?=AS_DOC_TYLKFAX  ?></option>
          <option value="sms" <?php echo($document_type== 'sms') ? 'selected' : '' ;?>><?= SMS  ?></option>
</select>  &nbsp;  
<select name="document_direction" >          
          <option value=all <?php echo ($document_direction== 'all' || $document_direction== '') ? 'selected' : '' ;?>><?= AS_DOC_ALL ?></option>
          <option value="out" <?php echo ($document_direction== 'out' ) ? 'selected' : ''  ;?>><?= AS_DOC_WYCH ?></option>
          <option value="in" <?php echo ($document_direction== 'in' ) ? 'selected' : ''  ; ;?>><?= AS_DOC_PRZYCH ?></option>
</select>                   
        </td>
        <td align=right><input name="Szukaj" type="submit" id="Szukaj" value="<?= SEARCH ?>"></td>
      </tr>
    </table></td>
  </tr>
</table>
	</td>
  </tr>
</table>


<script>
$('form_search').addEvent('submit', function(e){  	 
			
			if ($type(e) == 'event') 
				e.stop();								
			save_form('search_result_list','form_search','ayax/doc_doc_list.php');   														
	}); 


function goResultPage(page){
	document.getElementById('sd_page').value=page;			
	form_name = $('form_search');
	form_name.fireEvent('submit',form_name);
}

window.addEvent('domready', function() {  					
	form_name = $('form_search');
	form_name.fireEvent('submit',form_name);			
});	  
</script>
		
<div id="search_result_list" style="width:750px;height:auto;overflow:auto;"> </div>

</form>
</body>
</html>