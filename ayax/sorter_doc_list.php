<?php


include_once('../include/include_ayax.php');

$def_cppage = 100;
$def_page = 0;


$form_action=getValue('form_action');
$form_action_value=getValue('form_action_value');


$id=getValue('id');
$branch_id=getValue('branch_id');
$data_od=getValue('data_od');
$search_txt= iconv('UTF-8','latin2',getValue('search_txt'));
$data_do=getValue('data_od');
$status=getValue('status') > 0 ? getValue('status')  : 1;
$profil=getValue('profil') > 0 ? getValue('profil')  : Application::$DEF_APPLICATION_ID;

$page=getValue('sd_page') != '' ? getValue('sd_page') : $def_page;
$count_per_page=getValue('sd_cppage') != '' ? getValue('sd_cppage') : $def_cppage;

$param = array('body' => $search_txt,'data_od' => $data_od, 'data_do' => $data_do , 'status' => $status,'profil' => $profil,'page'=> $page,'count_per_page' => $count_per_page,'branch_id' => $branch_id);


if ($form_action != '' && $form_action_value > 0 ){
		if ($form_action == 'docDelete'){
				 $docObject->deleteDocument($form_action_value)	;
		}else if ($form_action == 'docMove'){
				$tmp = explode('|',$form_action_value);
				$id = intval($tmp[0]);
				$dest = intval($tmp[1]);
				if ($id > 0 && $dest > 0 )
				 	$docObject->moveDocument($id,$dest)	;
		}
}

$lista_doc = $docObject->getNewDocuments($param);
$result = lista($lista_doc,$docObject);

echo iconv('latin2','UTF-8',$result);
exit();

function lista($list,$docObject){
	global $def_cppage,$def_page,$branch_id;

	$lista_doc = $list['result'];
	$countAll = $list['countAll'];
	$summary = $list['summary'];
	$pageSelector = $list['pageSelector'];


	$search=getValue('search') ;
	$result='';

if ($search != 1 ){
	$result = '
	<script>
		function goResultPage(page){
			document.getElementById(\'sd_page\').value=page;
			form_name = $(\'form_doc_list\');
			form_name.fireEvent(\'submit\',form_name);
		}
	</script>

	<form name="form_doc_list" id="form_doc_list" method="post">

	<input type="hidden" name="search" value="1">
	<input type="hidden" name="sd_cppage" id="sd_cppage" value="'.$def_cppage.'">
	<input type="hidden" name="sd_page" id="sd_page" value="'.$def_page.'">
	<input type="hidden" name="form_action" id="form_action" value="">
	<input type="hidden" name="form_action_value"  id="form_action_value" value="">
	<input type="hidden" name="branch_id"  id="branch_id" value="'.$branch_id.'">
	<table WIDTH=370 cellpadding="0" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>'.  FK_FAX_FAXEMAILPRZYCH .'</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"  style="font-size:10px"><table width="100%" border="0" cellpadding="2" cellspacing="2">
      <tr>
        <td align="right" style="font-size:10px">'. AS_DOC_DATOD .':&nbsp; </td>
        <td colspan="2" align=left style="font-size:10px"><input name="data_od" type="text" style="font-size:10px" size="11" maxlength="10" value="'. getValue('data_od') .'">
          &nbsp;&nbsp;&nbsp;'. AS_DOC_DATDO .':
          <input name="data_do" type="text" size="11" maxlength="10"  style="font-size:10px" value="'. getValue('data_do') .'"></td>
        </tr>
      <tr>
        <td align=right style="font-size:10px">'.INBOX.': </td><td align=left style="font-size:10px">
        	<select name="profil" style="font-size:10px;width:80px;">';
		 $list =  $docObject->getActiveDestinationList($branch_id);
		foreach ($list As $key => $val)
            	$result .= '<option value="'.$key.'" '.($key == Application::$DEF_APPLICATION_ID ? 'selected' : '').'>'.$val.'</option>';

        $result .= '</select>&nbsp;
        '. AS_DOK_MSG_STATUS .':&nbsp;
        <select name="status" style="font-size:9px;width:80px;">
          <!-- <option value=0>Nowe</option> -->
          <option value=1 >'. AS_DOC_NIEPRZ .'</option>
          <option value=2 >'. AS_DOC_PRZYDZ .'</option>
          <option value=3 >'. AS_DOC_NASSIST .'</option>
          <option value=4 >'. AS_DOC_ALL .'</option>
           <option value=5 >'.AS_DOC_SKAS .'</option>
        </select></td>
        <td align=right style="font-size:10px"><input name="Szukaj" style="font-size:10px" type="button" id="dok_szukaj" value="'. SEARCH .'" onClick="goResultPage(0)"></td>
      </tr>
     <!-- <tr><td colspan="3"> <input style="font-size:10px" type="text" name="search_txt" id="search_txt" value="'.getValue('search_txt').'"></td></tr> -->
    </table></td>
  </tr>
</table></form>';
}



$result .= '<div id="doc_list_result">';
$result .= NUMBER_OF_ITEMS.': '.$countAll;
$result .= ' &nbsp;&nbsp;&nbsp;'.$summary;


$result .= '<br>&nbsp;&nbsp;&nbsp;';
foreach ($pageSelector as $poz) {
	if ($poz['val'] != '')
		$result .= '<a href="javascript:;" onClick="goResultPage('.$poz['val'].')">'.$poz['desc'].'</a>&nbsp;';
	else
		$result .= $poz['desc'].'&nbsp;';
}


$result .= '<table  WIDTH="355" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>
		<th width="20">&nbsp;</th>
		<th width="100" nowrap>'. DATE .'</th>
		<th align="center" width="150">'. FK_FAX_NUMER .'</th>
		<th width="70">'. AS_DOK_MSG_ILSTR  .'</th>
	</tr>';


  foreach ($lista_doc As $document ){
	$st1 = '';
	$st2= '';
	if ($document->get_new()){
		$st1 = '<b>';
		$st2= '</b>';
	}

	if ($document instanceof Email)
		$sender = $document->get_from_email();
	else if ($document instanceof Fax)
		$sender = $document->get_number();
	else if ($document instanceof SMS)
		$sender = $document->getPhone();
	else
		$sender = $document->getName();

	$result .= '<tr>
	  		<td align="center"><a name="faxin'.$document->getObjectID().'"></a><input title="'.AS_DOC_PODGLFAX.'" type="button" value="&gt;" style="width: 20px" onClick="load_document('.$document->getObjectID().')"></td>
	  		<td>';
		$result .= '<img src="img/'.$document->getIco().'">';

		$target_style = "";

       if ($document->get_destination() == 9 ) {
           $target_style = 'style="color:blue;"';
       }else if ($document->get_destination() == 12 ) {
           $target_style = 'style="color:red;"';
       }else if ($document->get_destination() == 10 ) {
           $target_style = 'style="color:brown;"';
       }

	$result  .= '</td>
	  		<td align="center" nowrap><span class="style4" '.$target_style.'>'.$st1.$document->getCreateDate().$st2.'</span></td>';
					$result .= '<td align="center" title="Subject:'.$document->getName().'
					Nadawca:'.$sender.'"  '.$target_style.'>'.$st1;
						$result .= htmlspecialchars(substr($sender,0,20),ENT_QUOTES ,'ISO-8859-1');
					$result .= $st2.'</td>';
			if ($document instanceof Fax){
				$ilosc = $document->get_page_number();
			}else{
				$ilosc = count($document->getAttchments()->get_list());
			}
			//$ilosc = $document->getSize();
	  		$result .= '<td align="center" nowrap>'.$st1.$ilosc.$st2.'</td>
	  	</tr>';
  }
  	$result .= '</table>
  	</div>
	</td>
  </tr>
</table>
<script>
 function refresh_form_doc_list(){
	save_form(\'doc_list_result\',\'form_doc_list\',\'ayax/sorter_doc_list.php\');
}
</script>
';

  	if ($search != 1 ){
		$result .= '
		<script>
				$(\'form_doc_list\').addEvent(\'submit\', function(e){
							if ($type(e) == \'event\')
								e.stop();
							save_form(\'doc_list_result\',\'form_doc_list\',\'ayax/sorter_doc_list.php\');
				});
		</script>
		';
  	}
  return $result;
}


?>