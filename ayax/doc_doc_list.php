<?php


include_once('../include/include_ayax.php');

$def_cppage = 10;
$def_page = 0;

$param_array = array();
$order_array =array();

$form_action=getValue('form_action');
$form_action_value=getValue('form_action_value');


$id=getValue('id');
$case_id=getValue('case_id');
$branch_id=getValue('branch_id');
$case_only=getValue('only_case') == 1 ? 1 : 0 ;

$data_od=getValue('data_od');
$data_do=getValue('data_od');

$direction=getValue('document_direction');

$search_type=getValue('search_type'); // 1 - nadawca / odbiorca , 2 - nr sprawy, 3- temat
$search_txt=getValue('search_txt');


if ($search_type > 0 ){
	$param_array['search_type'] = $search_type;
	$param_array['search_txt'] = $search_txt;

}

$status=getValue('status') > 0 ? getValue('status')  : 1;
$profil=getValue('profil') > 0 ? getValue('profil')  : Application::$DEF_APPLICATION_ID;

$document_direction = getValue('document_direction');
if ($document_direction=='') $document_direction='all';


$document_type = getValue('document_type');
if ($document_type=='') $document_type='all';

$page=getValue('sd_page') != '' ? getValue('sd_page') : $def_page;
$count_per_page=getValue('sd_cppage') != '' ? getValue('sd_cppage') : $def_cppage;


$param_array['page'] = $page;
$param_array['count_per_page'] = $count_per_page;

if ($_SESSION['new_user']==1){
		$param_array['internal'] = '0';
}

if ($branch_id > 0){
		$param_array['branch_id'] = $branch_id;
}

if ($data_od != '') {
	$param_array['data_od'] = $data_od;
}
if ($data_do != '') {
	$param_array['data_do'] = $data_do;
}

if ($case_only){
	$param_array['case_id'] = $case_id;
}

$param_array['direction'] = $direction;
$param_array['document_type'] = $document_type;


$case_interactions = new SearchInteractions();
$case_interactions->execute($param_array);

$result = lista($case_interactions,$docObject);

echo iconv('latin2','UTF-8',$result);
exit();

function lista($lista_doc,$docObject){

	$pageSelector =  $lista_doc->getPaging();;
	$result='';

$result .= '<div id="doc_list_result"><br>';
$result .= $pageSelector;

$result .= '<br><br><table  WIDTH="750" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>
		<th width="20">&nbsp;</th>
		<th width="20">&nbsp;</th>
		<th width="100" nowrap>'. AS_DOC_DAT .'</th>
		<th align="center" width="150">'. AS_DOC_NADODB .'</th>
		<th align="center" width="390">'. AS_CASD_TEMAT .'</th>
		<th width="70">'. AS_DOC_ILSTRZAL .'</th>

	</tr>';

	$lista = $lista_doc->getInteractions();
	$licz=0;
  foreach ($lista  As $pozycja ){
  	$interaction = $pozycja;
  	$bg_color = ($licz%2) ? '#FFFFFF' : '#DDDDDD';

	$licz++;
  	$document = $pozycja->getDocument();
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
	else
		$sender = $document->getName();

	$result .= '<tr bgcolor="'.$bg_color.'">
	  		<td align="center"><a name="faxin'.$document->getObjectID().'"></a>
	  		<input title="'.AS_DOC_PODGLFAX.'" type="button" value="&gt;" style="width: 20px"
	  		onClick="open_interaction('.$interaction->getType().','.$interaction->getDirection().',\''.$interaction->getObjectId().'\','.$interaction->getCaseID().',\'export\')"></td>';

		 $result .= '<td>';
		if  ($pozycja->getDirection() == Interaction::$DIRECTION_IN) {
                $result .= '<img src="img/direct_in.png">';
        }

        if  ($pozycja->getDirection() == Interaction::$DIRECTION_OUT) {
                $result .= '<img src="img/direct_out.png">';
        }
         $result .= '</td>';
	 $result .= '<td>';
		$result .= '<img src="img/'.$document->getIco().'">';
	$result  .= '</td>
	  		<td align="center" nowrap><span class="style4">'.$st1.$document->getCreateDate().$st2.'</span></td>';
					$result .= '<td align="center" title="Subject:'.htmlspecialchars(trim($document->getName()),ENT_QUOTES ,'ISO-8859-1').'
					Nadawca:'.$sender.'">'.$st1;
						$result .= htmlspecialchars(substr($sender,0,20));
					$result .= $st2.'</td>';


			if ($document instanceof Fax){
				$ilosc = $document->get_page_number();
			}else{
				$ilosc = count($document->getAttchments()->get_list());
			}
	  		$result .= '<td align="left" nowrap>'.$st1.htmlspecialchars(substr($document->getName(),0,40),ENT_QUOTES ,'ISO-8859-1').$st2.'</td>';
	  		$result .= '<td align="center" nowrap>'.$st1.$ilosc.$st2.'</td>
	  	</tr>';
  }
  	$result .= '</table>
  	</div>
	</td>
  </tr>
</table>';

  return $result;
}


?>