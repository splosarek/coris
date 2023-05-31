<?php require_once('include/include_ayax.php'); 

html_start('Dokumenty wys³ane','');

$def_cppage = 100;
$def_page = 0;

if ( $_SESSION['coris_branch'] == 2 ){	
	$branch_id = 2;
}else{	
	$branch_id = getValue('branch') > 0 ? getValue('branch') : $_SESSION['coris_branch'];		
}
?>

<script language="JavaScript" type="text/JavaScript">
<!--


function load_documents(){
	load_ayax('search_result','ayax/doc_out_list.php<?php echo '?branch_id='.$branch_id ?>');
}


function save_form(div_id,form_id,url){    	//,encoding: 'ISO-8859-2'
	var myHTMLRequest = new Request.HTML({evalResponse: true,evalScripts: true,url: ''+url, 
			onComplete: function(responseTree, responseElements, responseHTML) { 
				$(div_id).removeClass('ajax-loading')
				$(div_id).set('html', responseHTML); 
			} 		
			}).post($(form_id));
	$(div_id).empty().addClass('ajax-loading')
}


f_user=<?php echo Application::getCurrentUser(); ?>;
f_day=1;

function zmiana_daty(tryb){
	data_d = '<?php echo date("Y-m-d"); ?>';
	data_w = '<?php echo date("Y-m-d", mktime(0,0,0,date('m'),date('d')-1,date('Y'))); ?>';
	
	data_od = document.getElementById('data_od');
	data_do = document.getElementById('data_do');
	ch_dzisiaj = document.getElementById('ch_dzisiaj');
	ch_wczoraj = document.getElementById('ch_wczoraj');
	
	
	if (tryb == 0 ){
		data_od.value=data_d;
		data_do.value=data_d;	
		ch_wczoraj.checked=false;
	}
	
	if (tryb == -1 ){
		data_od.value=data_w;
		data_do.value=data_w;		
		ch_dzisiaj.checked=false;
	}				
}

function reset_dzien(){
	ch_dzisiaj = document.getElementById('ch_dzisiaj');
	ch_wczoraj = document.getElementById('ch_wczoraj');
	ch_wczoraj.checked=false;	
	ch_dzisiaj.checked=false;		
}

 	
	//-->
	</script>	


<form name="form_doc_out_search" id="form_doc_out_search" method="POST" style="margin:0px;padding:0px;">
<input type="hidden" name="sd_cppage" id="sd_cppage" value="<?php echo $def_cppage; ?>">
<input type="hidden" name="sd_page" id="sd_page" value="<?php echo $def_page; ?>">
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">
<table WIDTH=770 cellpadding="1" cellspacing="0" border="1" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong><?= AS_DOK_DOKWYSL ?></strong>
    </td></tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td align=right valign="top" nowrap><b><?= AS_DOC_DATOD ?></b>:&nbsp; <br>
              <input type="checkbox"  style="background:#cccccc " name="ch_dzisiaj" id="ch_dzisiaj" value="checkbox" checked onclick="zmiana_daty(0)">
              <?= TODAY ?> 
              <input type="checkbox"  style="background:#cccccc " name="ch_wczoraj" id="ch_wczoraj" value="checkbox"  onclick="zmiana_daty(-1)">
              <?= YESTERDAY ?></td>
            <td colspan="2" align=left valign="top" nowrap> 
              <input name="data_od" id="data_od" type="text" size="11" maxlength="10" value="<?php echo date("Y-m-d"); ?>" onfocus="reset_dzien()">
              &nbsp;&nbsp;&nbsp;<b><?= AS_DOC_DATDO ?></b>: 
              <input name="data_do" id="data_do" type="text" size="11" maxlength="10" value="<?php echo date("Y-m-d"); ?>"  onfocus="reset_dzien()">
              <br>
            </td>
            <td align=right colspan="2" valign="top"><b><?= AS_DOK_SZUKPO ?></b>: 
              <select name="search_type" >
                <option value=0 ></option>
                <option value=1 ><?= AS_DOC_NADODB ?></option>
                <option value=2 ><?= CASENO ?></option>
                <option value=3 ><?= AS_DOK_TEMAT ?></option>
              </select>
              <input name="search_txt" type="text" size="19" maxlength="200" value="">
            </td>
          </tr>
          <tr> 
            <td align=right valign="top"><b><?= AS_DOC_TYPDOK ?>: </b></td>
            <td align=left valign="top"> 
              <select name="document_type" >
                <option value="all" ><?= AS_DOC_ALL ?></option>
                <option value="email" ><?= AS_DOC_TYLKEMAIL ?></option>
                <option value="fax" ><?= AS_DOC_TYLKFAX ?></option>
                <option value="sms" ><?= SMS ?></option>
              </select>
            </td>
            <td align=left colspan="2"> 
              <table border="0" align="center" cellpadding="2" cellspacing="0">
                <tr valign="middle"> 
                  <td colspan="2" nowrap><b><?= AS_DOK_POKDOK ?></b>: </td>
                  <td nowrap><b> 
                    <input name="user" type="radio" style="background:#cccccc " value="<?php echo Application::getCurrentUser(); ?>" checked>
                    </b><?= AS_CASTD_UZYTKOW ?><b>&nbsp;&nbsp;&nbsp; 
                    <input name="user" type="radio" value="0" style="background:#cccccc " >
                    </b><?= AS_DOC_ALL ?><b> </b></td>
                </tr>
              </table>
            </td>
            <td align=right valign="bottom"> 
              <input name="Szukaj" type="submit" id="Szukaj" value="<?= SEARCH ?>">
            </td>
          </tr>
        </table>
      </td>
  </tr>

</table>
  </form>
<script>
$('form_doc_out_search').addEvent('submit', function(e){  	 
			
			if ($type(e) == 'event') 
				e.stop();								
			save_form('search_result_list','form_doc_out_search','ayax/doc_out_list.php?branch_id=<?= $branch_id ?>');   														
	}); 


function goResultPage(page){
	document.getElementById('sd_page').value=page;			
	form_name = $('form_doc_out_search');
	form_name.fireEvent('submit',form_name);
}

</script>
		
<div id="search_result_list" style="width:1250px;height:700px;overflow:auto;"> </div>


	
<?php
html_stop2();
?>
