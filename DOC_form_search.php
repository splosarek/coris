<?php 
//require_once('include/include.php'); 

require_once('include/include_ayax.php'); 

$status = (getValue('status')>0) ? getValue('status') : 0;



$case_id = getValue('case_id');


html_start('Formularze');
//

$result = '
<script>
function wstaw_form(typ,pozycja,link){
		doc = opener;
		if (opener){			
				// if ( doc.dodaj_document_vol2(\'form\',id))
				 if ( doc.addToList(typ,pozycja,link) )
				 	alert("'.AS_DOK_MSG_ZALDOD.'");
		}else
				alert("'.AS_DOK_MSG_BLOKNOZAMK.'");				
	}

	</script>

<form name="form1" method="post" action="DOC_form_search.php?case_id=<?php echo $case_id; ?>">
<table WIDTH=580 cellpadding="1" cellspacing="0" border="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td align="center" nowrap style="border: #000000 1px solid;"><strong>Formularze</strong> </td>
  </tr>  
</table>
<table  WIDTH="580" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap>
	<table border="0" cellpadding="1" cellspacing="1" width="100%">
	<tr align="center" bgcolor="#CCCCCC">
		<th width="20">&nbsp;</th>			
		<th align="center" width="450">Nazwa</th>				
		<th align="center" width="100">Podgl±d</th>				
	</tr>
';

if ($case_id>0){	
	$case = new CorisCase($case_id);
	$branch = $case->getBranchId();
}


	$type = 'form';
	if ($branch==3){
		$query = "SELECT * FROM coris_forms  WHERE status=1 AND branch_id = '2' ORDER BY `sort` ";
	}else{	
		$query = "SELECT * FROM coris_forms  WHERE status=1 AND branch_id = '$branch' ORDER BY `sort` ";
	}             	
          
  	$mysql_result = mysql_query($query) or die($query. '<br>'. mysql_error());
	$licz = 0;
	while ($row = mysql_fetch_assoc($mysql_result)){
	$bg_color = ($licz%2) ? '#FFFFFF' : '#DDDDDD'; 
	$licz++;
		$result .=  '<tr bgcolor="'.$bg_color.'">
	  		<td align="center"><input title="Dodaj formularz" type="button" value="&gt;" style="width: 20px" onClick="wstaw_form(\''.$type.'\',\''.$row['name'].'-'.$row['file'].'\',\'DOC_get_content.php?id='.$row['file'].'&source=form&action=view\');"></td>';	  		    
	  		$result .= '
	  		<td align="center">'.$row['name'].'</td>
			<td align="center"><a href="DOC_get_content.php?id='.$row['file'].'&source=form&action=view" target="_blank">podgl±d</a></td>	  		
	  	</tr>';	
  } 
$result .= '</table>
	</td>
  </tr>
</table>

</form>';


echo $result;
html_stop2();
?>