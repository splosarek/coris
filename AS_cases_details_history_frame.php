<? require_once('include/include.php'); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<body>
<?

$exp_action = getValue('exp_action');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $exp_action=='export_del'){
		$exp_int_id = getValue('exp_int_id');

		if ($exp_int_id>0){
			$query = "UPDATE coris_assistance_cases_interactions SET signal_export=0 WHERE interaction_id='$exp_int_id' LIMIT 1";
			mysql_query($query);
			
		}
}


$reclamation=getValue('reclamation')==1 ? 1: 0 ;
$client_id=getValue('client_id');
$case_id=getValue('case_id');
$category_id=getValue('category_id');
$search=getValue('search');
$direction = 0;
$order = "date";
$query = "SELECT coris_assistance_cases_interactions.*, coris_users.name, coris_users.surname FROM coris_assistance_cases_interactions, coris_users ";
//if (isset($_GET['search'])) {
//	$query .= ", coris_assistance_cases_interactions_notes ";
//}
$var='';

	if ($_SESSION['new_user']==1){
		$var=' AND internal=0 ';
	}
//$query .= "WHERE case_id = $case_id AND coris_assistance_cases_interactions.reclamation='$reclamation' AND coris_assistance_cases_interactions.user_id = coris_users.user_id $var ";
$query .= "WHERE case_id = $case_id AND ".($reclamation==1 ? " coris_assistance_cases_interactions.reclamation='$reclamation' AND " : '')."  coris_assistance_cases_interactions.user_id = coris_users.user_id $var ";

if(isset($_GET['type_id']) && $_GET['type_id']>0)
	$query .= ' AND type_id=\''.$_GET['type_id'].'\'';

if ($search != '') {
	$query .= "AND (coris_users.surname LIKE '%$search%' OR coris_assistance_cases_interactions.interaction_name LIKE '%$search%' OR coris_assistance_cases_interactions.subject LIKE '%$search%' OR coris_assistance_cases_interactions.note LIKE '%$search%') ";
}

if ($category_id > 0 ) {
	$query .= "AND  coris_assistance_cases_interactions.documentcategory_id ='$category_id' ";
}


if (isset($_GET['order'])) {
    $order = $_GET['order'];
    $direction = $_GET['direction'];
    switch ($order) {
        case "type":
            $query .= "ORDER BY type_id";
            break;
        case "sender":
            $query .= "ORDER BY user_id";
            break;
        case "direction":
            $query .= "ORDER BY direction";
            break;
        case "recipient":
            $query .= "ORDER BY interaction_name";
            break;
        case "subject":
            $query .= "ORDER BY subject";
            break;
        case "date":
            $query .= "ORDER BY date";
            break;
    }
    $query .= ($direction) ? "" : " DESC";
} else {
    $query .= "ORDER BY date DESC";
}


if ($result = mysql_query($query)) {
?>	
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="right" style="border-bottom: #6699cc 1px solid;" height="30">
<?php if ($reclamation==0){
	?>	

					<form action="AS_cases_details_history_frame.php" method="get" name="form1">
					<font color="#6699cc"><?= AS_CASD_WYSZ ?></font> <input type="hidden" name="case_id" value="<?= $case_id ?>"><input type="text" name="search" style="border-top: #6699cc 1px solid; border-bottom:  #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; background: #eeeeee">&nbsp;
					<input type="submit" style="width: 0px">
					</form>
<?php }
?>					
				</td>
			</tr>
			<tr>
				<td align="center">
					<table cellpadding="0" cellspacing="1" border="0" width="745">
						<tr height="6" align="center">
							<td width="20"><font color="#6699cc"><?= TYPE ?></font></td>
							<td width="110"><font color="#6699cc">CORIS</font></td>
							<td width="20"></td>
							<td width="220"><font color="#6699cc"><?= AS_CASADD_KLIENT ?></font></td>
							<td width="205"><font color="#6699cc"><?= AS_CASD_TEMAT ?></font></td>
							<td width="110"><font color="#6699cc"><?= DATA ?></font></td>
						<?php echo ($client_id==7592 ?	'<td width="60"><font color="#6699cc">Export</font></td>' : '' ) ?>
						</tr>
						<tr height="6" style="cursor: hand" align="center">
							<td	bgcolor="<?= ($order == "type") ? "#6699cc" : "#999999" ?>" width="20" onmouseover="this.bgColor='#99ccff';" onmouseout="this.bgColor='<?= ($order == "type") ? "#6699cc" : "#999999" ?>';" onclick="window.location ='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&order=type&direction=<?= ($direction) ? "0" : "1"; ?><?= (isset($_GET['search'])) ? "&search=$_GET[search]" : "" ?>'"></td>
							<td bgcolor="<?= ($order == "sender") ? "#6699cc" : "#999999" ?>" width="100" onmouseover="this.bgColor='#99ccff';" onmouseout="this.bgColor='<?= ($order == "sender") ? "#6699cc" : "#999999" ?>';" onclick="window.location ='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&order=sender&direction=<?= ($direction) ? "0" : "1"; ?><?= (isset($_GET['search'])) ? "&search=$_GET[search]" : "" ?>'"></td>
							<td bgcolor="<?= ($order == "direction") ? "#6699cc" : "#999999" ?>" width="20" onmouseover="this.bgColor='#99ccff';" onmouseout="this.bgColor='<?= ($order== "direction") ? "#6699cc" : "#999999" ?>';" onclick="window.location ='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&order=direction&direction=<?= ($direction) ? "0" : "1"; ?><?= (isset($_GET['search'])) ? "&search=$_GET[search]" : "" ?>'"></td>
							<td bgcolor="<?= ($order == "recipient") ? "#6699cc" : "#999999" ?>" width="100" onmouseover="this.bgColor='#99ccff';" onmouseout="this.bgColor='<?= ($order == "recipient") ? "#6699cc" : "#999999" ?>';" onclick="window.location ='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&order=recipient&direction=<?= ($direction) ? "0" : "1"; ?><?= (isset($_GET['search'])) ? "&search=$_GET[search]" : "" ?>'"></td>
							<td bgcolor="<?= ($order == "subject") ? "#6699cc" : "#999999" ?>" width="135" onmouseover="this.bgColor='#99ccff';" onmouseout="this.bgColor='<?= ($order == "subject") ? "#6699cc" : "#999999" ?>';" onclick="window.location ='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&order=subject&direction=<?= ($direction) ? "0" : "1"; ?><?= (isset($_GET['search'])) ? "&search=$_GET[search]" : "" ?>'"></td>
							<td	bgcolor="<?= ($order == "date") ? "#6699cc" : "#999999" ?>" width="110" onmouseover="this.bgColor='#99ccff';" onmouseout="this.bgColor='<?= ($order == "date") ? "#6699cc" : "#999999" ?>';" onclick="window.location ='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&order=date&direction=<?= ($direction) ? "0" : "1"; ?><?= (isset($_GET['search'])) ? "&search=$_GET[search]" : "" ?>'"></td>
							<?php echo ($client_id==7592 ?	'<td	bgcolor="#999999" width="60" >&nbsp;</td>' : '' ) ?> 
						</tr>
						<tr height="5">
							<td colspan="5"></td>
						</td>
<?
    function StrTrim($string, $length) {
        return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
    }

    while ($row = mysql_fetch_array($result)) {
    		$st1='';
    		$st2='';
    		$msg_new='';
    	if ($row['new']==1){
    		$msg_new=AS_CASD_NOWDOK.'   ';
    		$st1='<b>';
    		$st2='</b>';
    	}
    	//<tr bgcolor="#e9e9e9" onmouseover="this.bgColor='#ced9e2';" onmouseout="this.bgColor='#e9e9e9';" style="cursor: hand" onclick="javascript:<?
?>
						
 <tr <?php if (($row['type_id'] == 5) ||($row['type_id'] == 4) || ($row['type_id'] == 3)) { echo "bgcolor=\"lightyellow\""; } else { echo "bgcolor=\"#e9e9e9\" onmouseover=\"this.bgColor='#ced9e2';\" onmouseout=\"this.bgColor='#e9e9e9';\"" ; } ?>  style="border-top: #ffffe0 1px solid; border-bottom: #ffffe0 1px solid; cursor: pointer;" onclick="javascript:<?						
        switch($row['documenttype_id']) {
            case 2: //email
                echo "doc1=window.open('assistcases-email.htm','doc1','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=900,height=520') ; if (false == doc1.closed) doc1.focus();";
                break;
            case 3: //call
          		
                //echo "parent.location='AS_cases_details_history_frame_note.php?interaction_id=$row[interaction_id]'";
                //echo "parent.location='AS_cases_details_note_edit.php?note_id=".$row['interaction_id']."'";
                echo "parent.location='AS_cases_details.php?case_id=".$case_id."&mod=doc&form_action=note_edit&id=".$row['interaction_id']."'";
                break;
            case 4: //note
             	echo "parent.location='AS_cases_details.php?case_id=".$case_id."&mod=doc&form_action=note_edit&id=".$row['interaction_id']."'";
                //echo "parent.location='AS_cases_details_note_edit.php?note_id=".$row['interaction_id']."'";
                break;
            case 7: { //fax // email
            	if ($row['type_id']==1){ //fax
		            	if ($row['direction']==1)
		                	echo "window.open('FK_fax_in_preview2.php?id=".$row['interaction_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=530,height=710')";
		                else 
		                	echo "window.open('FK_fax_out_preview2.php?id=".$row['ext_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710')";	
            	}else if ($row['type_id']==2){ //email
		            	if ($row['direction']==1)
		                	echo "window.open('FK_email_in_view2.php?id=".$row['interaction_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=600,height=710')";
		                else 
		                	echo "window.open('FK_email_out_view.php?id=".$row['ext_id']."','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=700,height=710')";	
            	
            	}
                break;
            }
                
        } 
        if ($row['documenttype_id']==0){
	        switch ($row['type_id']) {    	  
            	case 3: //call
     	            // echo "parent.location='AS_cases_details_note_edit.php?note_id=".$row['interaction_id']."'";
     	             echo "parent.location='AS_cases_details.php?case_id=".$case_id."&mod=doc&form_action=note_edit&id=".$row['interaction_id']."'";
                	break;
            	case 4: //note
                   //echo "parent.location='AS_cases_details_note_edit.php?note_id=".$row['interaction_id']."'";
                    echo "parent.location='AS_cases_details.php?case_id=".$case_id."&mod=doc&form_action=note_edit&id=".$row['interaction_id']."'";
                	break;
                	
                	case 5: //note
                   //echo "parent.location='AS_cases_details_note_edit.php?note_id=".$row['interaction_id']."'";
                    echo "parent.location='AS_cases_details.php?case_id=".$case_id."&mod=doc&form_action=note_edit&id=".$row['interaction_id']."'";
                	break;
	        }
        	
        }
        ?> " height="24">
							<td	width="20" align="center">
<?
        switch ($row['type_id']) {
            case 1: //fax
                //echo "<font size=\"+1\" face=\"webdings\" color=\"green\"></font>";
                echo '<img src="img/ico_fax.png">';
                break;
            case 2: //email
               // echo "<font size=\"3\" color=\"red\" face=\"wingdings\">*</font>";
                 echo '<img src="img/ico_email.png">';
                break;
            case 3: //call
                //echo "<font	size=\"+1\" color=\"green\" face=\"wingdings\">)</font>";
                echo '<img src="img/ico_call.png">';                
                break;
            case 4: //note
                //echo "<font	size=\"+1\" color=\"orange\" face=\"webdings\">¤</font>";
                echo '<img src="img/ico_note.png">';
                break;
            case 5: //note
                //echo "<font	size=\"+1\" color=\"orange\" face=\"webdings\">¤</font>";
                echo '<img src="img/ico_note.png">';
                break;
                
        }

?>
                            </td> <?
                            if ($row['type_id']==5)
								echo '<td	width="100"	align="center" title=""><font color="blue">'.$st1.'External note'.$st2.' </font></td>';
							else
								echo '<td	width="100"	align="center" title="'.  $row['surname'].', '.$row['name'] .'"><font color="blue">'. $st1.StrTrim($row['surname'].', '.$row['name'], 20).$st2 .'</font></td>';
?>

							<td	width="20" align="center">
<?
        switch ($row['direction']) {
            case 1: // from us
                //echo "<font size=\"0\" face=\"wingdings\" color=\"green\">ç</font>";
                echo '<img src="img/direct_in.png">';
                
                break;
            case 2: // to us
                //echo "<font size=\"0\" face=\"wingdings\" color=\"red\">č</font>";
                echo  '<img src="img/direct_out.png">';
                break;
        }
?>
                            </td>
							<td	align="center" title="<?= $row['interaction_name'].' '.$row['interaction_contact'] ?>"><font color="blue"><?
							if ($row['type_id']==2) { //email
									echo $st1.substr($row['interaction_contact'],0,15).$st2;
							}else{
								echo ($row['interaction_contact'] != "") ? $st1.$row['interaction_contact'] . "/" : "$st1" ?><?php echo StrTrim($row['interaction_name'], 15).$st2; 
								
							}
								?>
								
								</font></td>
							<td	 title="<?= $row['subject'].' '.$row['note'] ?>"><?php echo $st1. StrTrim($row['subject'], 22).$st2; ?></td>
							<td		align="right"><?php echo $st1;?>
<?
        if (substr($row['date'],0,10) == date("Y-m-d")) {
            echo "<font color=\"blue\">".AS_CASD_DZIS." ". substr($row['date'],11, 5) . "</font>";
        } else if (substr($row['date'],0,10) == date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")))) {
            echo "<font color=\"darkblue\">".AS_CASD_WCZ." ". substr($row['date'],11, 5) . "</font>";
        } else {
            echo substr($row['date'],0, 16);
        }
?>
							<?php echo $st2;?></td>														

							<?php 
								if ($client_id==7592){
									echo '<td align="center">';	
									if ($row['type_id']==1 || $row['type_id']==2)	{									
											echo '<input type="checkbox" value="1" '. ($row['signal_export'] ==1 ? 'checked' : 'disabled') .' title="Signal export" onClick="return odznacz_export('.$row['interaction_id'].')">';
									}else{
										echo '&nbsp;';
									} 							
									echo '</td>';
								}	
								?>
						</tr>
<? if ($row['type_id'] == 3 || $row['type_id'] == 4 || $row['type_id'] == 5 ) { ?>
						<tr>
							<td colspan="7" bgcolor="lightyellow" align="left"><small><?= nl2br($row['note']) ?></small></td>
						</tr>
<? } ?>
<?
    }
    mysql_free_result($result);
} else {
    die (mysql_error());
}
?>                       
					</table>
				</td>
			</tr>
		</table>
<form name="form_export" method="POST">
	<input type="hidden" name="exp_action" value="">
	<input type="hidden" name="exp_int_id" value="0">
</form>
<script>

function odznacz_export(id){

	if (confirm('Czy napewno chcesz wylaczyc dokument z exportu?')){
			if (id>0){
					document.getElementById('exp_action').value='export_del';
					document.getElementById('exp_int_id').value=id;
					document.getElementById('form_export').submit();			
				return false;									
			}
	}else{
			return false;
	}
	
}
</script>		
	</body>
</html>
