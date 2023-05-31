<?php
include_once('../include/include_ayax.php');


$def_cppage = 100;
$def_page = 0;


 /*
   <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>Dokumenty wychodz±ce</strong></td>
  </tr>
 */

   	$result = search_list();
//$result = nl2br(print_r($_POST,1));

echo iconv('latin2','UTF-8',$result);
exit();   	


function search_list(){
		global $docObject;
		
$def_cppage = 100;
$def_page = 0;
			

$case_id = getValue('case_id');
$document_type = getValue('document_type');
$document_direction = getValue('document_direction');
$only_case = getValue('only_case');

$search_type = getValue('search_type');
$search_txt = getValue('search_txt');
$user = getValue('user');
$branch_id = getValue('branch_id'); 

$data_od = getValue('data_od');
$data_do = getValue('data_do');



if ($document_type=='') $document_type='all';


$page=getValue('sd_page') != '' ? getValue('sd_page') : $def_page;
$count_per_page=getValue('sd_cppage') != '' ? getValue('sd_cppage') : $def_cppage;

$param = array('data_od' => $data_od, 'data_do' => $data_do , 'user' => $user,'search_type' => $search_type,'search_txt'=> $search_txt,
'document_type' => $document_type,
'page'=> $page,'count_per_page' => $count_per_page,
'branch_id' => $branch_id );


//$lista_doc = $docObject->getOutDocuments($param);
$lista_doc = new OutInteractions();
$lista_doc->execute($param);

//html_start('Documenty out');


	$var = " ";
	$var_in = " ";
	$var_out = ' ';
	$tab_email_in = '';
	$tab_fax_in = '';
	$var_email_in = '';
	$var_fax_in = '';
	$var_email_out = '';
	$var_fax_out = '';
	$op = '';
	
	
	$nawigacja = $lista_doc->getPaging();

   	//$result .= '<tr><td colspan=7>'. $nawigacja .'</td></tr>';	
$result = $nawigacja.'<br><br>';	
$result .= '
<table width="99%" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">

  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr align="center" bgcolor="#CCCCCC">
		<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>
		<th width="20">&nbsp;</th>
		<th width="20">&nbsp;</th>
		<th width="100" nowrap>'.AS_DOK_MSG_DATA .'</th>
		<th align="center" width="150">'. AS_DOK_ODBIORCA .'</th>
		<th align="center" width="290">'. AS_DOK_TEMAT .'</th>
		<th align="center" width="150" nowrap>'. AS_DOK_SPRAWA .'</th>		
		<th width="100">'. USER .'</th>		
		<th width="70">'. AS_DOC_ILSTRZAL .'</th>
		<th width="40">'. AS_CASES_STATUS .'</th>
		
	</tr>';

	$lista = $lista_doc->getInteractions();
	
 foreach ($lista As $interaction ){	
	$st1 = '';
	$st2= '';
	
	$sender=$interaction->getInteractionContact();
	
	$case = new CorisCase($interaction->getCaseID());
	$document = $interaction->getDocument();
	$st = $document->get_status();

	$kolor_status = '#EEEEEE';	
		$status='';
			
		if ($st=='0'){
			$status= AS_DOK_WYSYLAN;
		}else{
      		$status=$st;
      
	      	if ($status=='done' )
	        	$kolor_status='#98FB98';
	      	else if ($status=='failed')
	        		$kolor_status='#FA9672';
	      	else if ($status=='rejected')
	        	$kolor_status='#F5DEB3';
	      	else 
	        	$kolor_status='#ADD8E6';
		}
		
		if ( $interaction->getType()== Interaction::$_TYPE_EMAIL ){ //email
			$status='done';
			$kolor_status='#98FB98';	
		}
		
		$bg_color = $kolor_status;

		$result .=  '<tr bgcolor="'.$bg_color.'">
	  		<td align="center"><input title="'.AS_DOC_PODGLFAX.'" type="button" value="&gt;" style="width: 20px" 
	  		onClick="open_interaction('.$interaction->getType().','.$interaction->getDirection().','.$interaction->getObjectId().','.$interaction->getCaseID().',\'sorter_out\')"></td>
	  		<td>';
		$result .= '<img src="img/'.$interaction->getIco().'">';
		$result .= '</td><td>';                
	  		$result .=  '</td><td align="center" nowrap><span class="style4">'.$interaction->getDate().'</span></td>
	  		<td align="center">'.$sender.'</td>
			<td align="left">'.$interaction->getInteractionSubject().'</td>
			<td align="center"><b>'.$case->getPaxsurname().' '.$case->getPaxname().'</b><br>'.$case->getCaseNumber().'</td>	  			  		
	  		<td align="right" >'.Application::getUserName($interaction->getUserId() ).'</td>			
	  		<td align="center" nowrap>'.$document->getSize().'</td>
	  			<td align="center" nowrap>'.$status.'</td>
	  	</tr>';
	}		
	$result .= '</table>
	</td>
  </tr>
</table>
<br>
';

	return $result ;
}