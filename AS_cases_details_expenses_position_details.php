<?php include('include/include.php'); 
include('include/include_mod.php');
$lang = $_SESSION['GUI_language'];

$decision_id = getValue('decision_id');

$expense_id  = getValue('expense_id');
$case_id = 0;
$qs = "SELECT case_id FROM coris_assistance_cases_expenses WHERE expense_id ='$expense_id' ";
$mr = mysql_query($qs);
if (mysql_num_rows($mr)>0){
	$r = mysql_fetch_array($mr);
	$case_id = $r['case_id'];
}

$client_id=0;
$case_cardif_info = array();
$row_case = null;
if ($case_id>0){
	$query  = "SELECT * FROM coris_assistance_cases WHERE case_id='$case_id'	";
	$mysql_result=mysql_query($query);
	if (mysql_num_rows($mysql_result)>0){
			$r=mysql_fetch_array($mysql_result);
			$row_case=$r;
			$client_id = $r['client_id'];
				
				if ($client_id==11086){
		
						include_once('lib/lib_cardif.php');
						include_once('include/include_mod.php');	
						$case_cardif_info = CardifCase::getCaseCardifInfo($case_id);	
				}
	}
	
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= AS_CASD_SZCZEGWYK ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="javascript" src="Scripts/mootools.js"></script>
<script language="javascript" src="Scripts/javascript.js"></script>
</head>
    <body bgcolor="#dfdfdf" onload="focus();">
		<script language="JavaScript1.2">
		<!--
			function checkboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

 function validate() {
                if (form1.contrahent_id.value == "") {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWYK ?>");
                    form1.contrahent_id.focus();
                    return false;
                }
                if (form1.activity_id.value == 0) {
                    alert('<?= AS_CASD_MSG_PROSZWYBRZLEC ?>');
                    form1.activity_id.focus();
                    return false;
                }
                if (form1.amount.value == "") {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWART ?>");
                    form1.amount.focus();
                    return false;
                }
                if (!form1.amount.value.match(/^\d*$/) && !form1.amount.value.match(/^\d*,\d\d$/) && !form1.amount.value.match(/^\d*\.\d\d$/)) {
                    alert("<?= AS_CASD_MSG_BLFORMWART ?>");
                    form1.amount.focus();
                    return false;
                }
                if (form1.currency_id.value == 0) {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWAL ?>");
                    form1.currency_id.focus();
                    return false;
                }
                   <?php
                		if ($client_id==11086){
                			?>
                			if (document.getElementById('id_swiadczenie').value == 0 ) {
								alert("Prosze wybraæ ¶wiadczenie");
								document.getElementById('id_swiadczenie').focus();
								return false;
							}
							
							suma = document.getElementById('suma_ubezpieczenia').value.replace(',','.');							
            				suma = suma *1.0;
							if ( suma > 0  ){
            					
            				}else{
                				alert('Proszê podaæ sumê ubezpieczenia!');
                				document.getElementById('suma_ubezpieczenia').focus();
                				return false;                				
                			} 
                			
                			amount = document.getElementById('amount').value.replace(',','.');			
                				amount = amount *1.0;			               			
                			if ( amount > suma){
                				alert('Koszt zlecenia wiêkszy ni¿ suma ubezpieczenia!');                				
                				return false;         
                			}

                			
                		<?php	      
                		      	                			
              		}              
                ?>
            }
            
function MM_openBrWindow(theURL,winName,features) { //v2.0
   window.open(theURL,winName,features);
}


function form_change_contrahent(contrahent_id, branch_id){
	  if (contrahent_id){
			ayax_action=1;
			var url = 'ayax/contrahent_select.php';
			var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8' ,
			onComplete: function(jsonObj) {										
				form_change_contrahent_step2(jsonObj);
			 ayax_action=0;
			}}).get({'contrahent_id': contrahent_id,
                         'branch_id': branch_id});
	  }else{


	  }
	}

	function form_change_contrahent_step2(jsonObj){
		if (jsonObj.substitute_error==1){
			alert('B³±d kontrahenta zastêpczego dla kontrahenta '+jsonObj.org_contrahent_id+' (niezdefiniowany lub nieistniej±cy), prosze poprawiæ i spróbowaæ ponownie');
			$('contrahent_id').value='';
			$('contrahent_name').value='';		
			$('contrahent_id').focus();		
		}else if (jsonObj.error==1){
				alert('<?php echo GEN_CONTR_BRAKWYST ; ?>');
				$('contrahent_id').value='';
				$('contrahent_name').value='';		
				$('contrahent_id').focus();		
			}else{
				if (jsonObj.notuse==1){
					if (confirm('Nie mo¿na u¿yæ tego kontrahenta, Czy zamiast chcesz u¿yæ kontrahenta: '+jsonObj.contrahent_id+' "'+jsonObj.name+'"')){
						$('contrahent_id').value=jsonObj.contrahent_id;
						$('old_contrahent_id').value=jsonObj.contrahent_id;
						$('contrahent_name').value=jsonObj.name;	
					}else{
						$('contrahent_id').value=$('old_contrahent_id').value;					
					}
				}else{
					$('contrahent_id').value=jsonObj.contrahent_id;
					$('old_contrahent_id').value=jsonObj.contrahent_id;
					$('contrahent_name').value=jsonObj.name;					
				}			
			}	
	}
        //-->
        </script>
<?php
if (isset($_GET['delete'])) {
	
	$query = "UPDATE coris_assistance_cases_expenses SET active = 0 WHERE expense_id = '$expense_id'";
    if ($result = mysql_query($query)) {
    			if ($decision_id>0 && ($client_id==11086 ||$client_id==2201 ||$client_id==11 ) ){
    					include_once('lib/RegisterActionAfterDecision.php');
    					RegisterActionAfterDecision::registerUserAction($case_id, $decision_id, 'DELETE',$expense_id);    				
    			}
    	
    	if ($_GET['tryb']=='cardif'){    		
    		$q = "DELETE  FROM coris_cardif_cases_reserve WHERE case_id='$case_id' AND ID_expenses='$expense_id'"; 
    		$mr = mysql_query($q);
    	}else if ($_GET['tryb']=='europa'){    		
    		$q = "DELETE  FROM coris_europa_rezerwy  WHERE case_id='$case_id' AND ID_expenses='$expense_id'"; 
    		$mr = mysql_query($q);
    	}
    	
    	if ($_GET['tryb']=='cardif'){    		
			 echo "<script>opener.document.getElementById('form_rezerwy').submit();  window.close();</script>";  
    		exit; 		
    	}else if ($_GET['tryb']=='europa'){    		
			 echo "<script>opener.document.getElementById('form_rezerwy').submit();  window.close();</script>";  
    		exit; 		
    	}else{
        	echo "<script>opener.document.location.reload(); window.close();</script>";
        	exit;
    	}
	} else {
        die (mysql_error());
	}

}


if (isset($_GET['action'])) {

	$earlier_payment = (isset($_POST['earlier_payment'])) ? 1 : 0;
	$amount = str_replace(',','.',getValue('amount'));
   // $query = "UPDATE coris_assistance_cases_expenses SET contrahent_id = '$_POST[contrahent_id]', ref_number='".$_POST['ref_number']."',activity_id = '$_POST[activity_id]', amount = '". str_replace(",", ".", $_POST['amount']) . "', currency_id = '$_POST[currency_id]', earlier_payment = '$earlier_payment', note = '".$_POST['note']."', user_id = '$_SESSION[user_id]', date = NOW() WHERE expense_id = '$_GET[expense_id]'";
    $query = "UPDATE coris_assistance_cases_expenses SET contrahent_id = '".$_POST['contrahent_id']."', ref_number='".$_POST['ref_number']."',activity_id = '".$_POST['activity_id']."',activity_date='".$_POST['activity_date']."', 
         amount = '". str_replace(",", ".", $_POST['amount']) . "', currency_id = '$_POST[currency_id]', earlier_payment = '$earlier_payment', note = '".$_POST['note']."',
		 client_charge_id = '".$_POST['client_charge_id']."', client_amount = '". str_replace(",", ".", $_POST['client_amount']) . "',
		 coris_charge_id = '".$_POST['coris_charge_id']."', coris_amount = '". str_replace(",", ".", $_POST['coris_amount']) . "',
		 cicp_charge_id = '".$_POST['cicp_charge_id']."', cicp_amount = '". str_replace(",", ".", $_POST['cicp_amount']) . "',
		 final = '".(isset($_POST['final']) ? 1 :  0)."', table_id = '".getValue('currency_table_id')."',
		  	number_of_units = '".getValue('number_of_units')."'
		 WHERE expense_id = '".$expense_id."' LIMIT 1";
   // 
    if ($result = mysql_query($query)) {
    	
    	
    	if ($client_id==11086){
						$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	
    					$suma_ubezpieczenia = str_replace(',','.',getValue('suma_ubezpieczenia'));
						$sumacurrency_id=getValue('sumacurrency_id');																		    		
    			
    			$q = "SELECT * FROM coris_cardif_cases_reserve WHERE case_id='$case_id' AND ID_expenses='$expense_id' ";
    			$mr = mysql_query($q);
    			if (mysql_num_rows($mr)==0){ // brak
                    	/* REZERWA */ 
                    	$qi2  = "INSERT INTO coris_cardif_cases_reserve  SET   ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',suma='".$suma_ubezpieczenia."',currency_id ='$sumacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1 ";		                    

						$mr2 = mysql_query($qi2);
						
						if ( $mr2){								
						
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();				
						}	    				
    				
    			}else{																				                    	
                    	/* REZERWA */ 
                    	$qi2  = "UPDATE coris_cardif_cases_reserve  SET case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',suma='".$suma_ubezpieczenia."',currency_id ='$sumacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1
                    	WHERE ID_expenses='$expense_id' LIMIT 1 ";		
                    	//echo $qi2;
						$mr2 = mysql_query($qi2);
						
						if ( $mr2){								
						
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();				
						}	
    			}

    			if ($decision_id>0){
    					include_once('lib/RegisterActionAfterDecision.php');
    					RegisterActionAfterDecision::registerUserAction($case_id, $decision_id, 'UPDATE',$expense_id);    				
    			}
    	}else if ($client_id==11 || $client_id== 2201){    			
    					$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	
    					
						$currency_id=getValue('currency_id');																		
						                    	 
						$query = "SELECT * FROM coris_europa_rezerwy WHERE ID_expenses='$expense_id' ";
						$mysql_result = mysql_query($query);
						$poz=0;
						
						if (mysql_num_rows($mysql_result) == 0){                    	
                    		$qi2  = "INSERT INTO coris_europa_rezerwy   SET  ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',currency_id ='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";	
                    		$mr2 = mysql_query($qi2);
							$poz=0;
							if ( $mr2){								
								$poz = mysql_insert_id();	
							}
						}else{
							$row = mysql_fetch_array($mysql_result);
							$poz = $row['ID'];
									
							$qi2  = "UPDATE  coris_europa_rezerwy   SET  case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',currency_id ='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() WHERE ID_expenses='$expense_id' ";									
							$mr2 = mysql_query($qi2);						
						}
						
						
						if ( $mr2){								
							
						}else{
							echo  "<br>INSERT  Error: ".$qi2."\n<br> ".mysql_error();				
						}		    				    		    			
						
						$qi = "INSERT INTO coris_europa_rezerwa_historia SET ID_rezerwa='$poz',rezerwa_stara='0',rezerwa_nowa='$amount',currency_id='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";
						$mr = mysql_query($qi);
						
    			if ($decision_id>0){
    					include_once('lib/RegisterActionAfterDecision.php');
    					RegisterActionAfterDecision::registerUserAction($case_id, $decision_id, 'UPDATE',$expense_id);    				
    			}		
    	
    	} else if ($client_id==10 ){    			
    					$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	
    					
						$currency_id=getValue('currency_id');																		
						                    	 
						$query = "SELECT * FROM coris_skok_rezerwy WHERE ID_expenses='$expense_id' ";
						$mysql_result = mysql_query($query);
						$poz=0;
						
						if (mysql_num_rows($mysql_result) == 0){                    	
                    		$qi2  = "INSERT INTO coris_skok_rezerwy   SET  ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',currency_id ='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";	
                    		$mr2 = mysql_query($qi2);
							$poz=0;
							if ( $mr2){								
								$poz = mysql_insert_id();	
							}
						}else{
							$row = mysql_fetch_array($mysql_result);
							$poz = $row['ID'];
									
							$qi2  = "UPDATE  coris_skok_rezerwy   SET  case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',currency_id ='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() WHERE ID_expenses='$expense_id' ";									
							$mr2 = mysql_query($qi2);						
						}
						
						
						if ( $mr2){								
							
						}else{
							echo  "<br>INSERT  Error: ".$qi2."\n<br> ".mysql_error();				
						}		    				    		    			
						
						$qi = "INSERT INTO coris_skok_rezerwa_historia SET ID_rezerwa='$poz',rezerwa_stara='0',rezerwa_nowa='$amount',currency_id='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";
						$mr = mysql_query($qi);    	
    	}    			  	    	
    	    	
    	$rank_edit = getValue('rank_edit') ==1 ? 1 : 0 ;
    	$rank_value = getValue('rank_value');
    	if ($rank_edit && $rank_value>0 ){
    		
			
			$rank_description = getValue('rank_description');
			
			$query = "INSERT INTO coris_contrahents_rank SET
			ID_expences = '$expense_id',
			ID_rank = '$rank_value',			
			note = '$rank_description',
			ID_user = '".$_SESSION['user_id']."',
			date = now()
			";
			$mr = mysql_query($query);
			if (!$mr){
				echo "\n<br>QE: ".$query." \n<br>".mysql_error();
			}
			
    	}
    	
     	if ($_GET['tryb']=='cardif'){    		
			 echo "<script>opener.document.getElementById('form_rezerwy').submit();  window.close();</script>";  
    		exit; 		
    	}else 	if ($_GET['tryb']=='europa'){    		
			 echo "<script>opener.document.getElementById('form_rezerwy').submit();  window.close();</script>";  
    		exit; 		
    	}else{
        	echo "<script>if (opener) opener.document.location.reload(); window.close();</script>";
        	exit;
    	}
    	
    	
    	
    
		
    } else {
        die (mysql_error());
    }
}



if ($client_id==11086){	
	echo '<script language="JavaScript1.2" src="Scripts/js_cardif_announce.js"></script>';
}else if ($client_id==2201 || $client_id==11  ) {	
	echo '<script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>';

}else if ( $client_id==10  ) {	// SKOK
	echo '<script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>';

}


//$query = "SELECT cfp.case_id, cfp.contrahent_id, cfp.activity_id, cfp.amount, cfp.currency_id, cfp.earlier_payment, cfp.note, ac.type_id FROM coris_assistance_cases_expenses cfp, coris_assistance_cases ac WHERE cfp.expense_id = '$_GET[expense_id]' AND cfp.case_id = ac.case_id";
$query = "SELECT cfp.*,cfp.case_id, cfp.contrahent_id, cfp.activity_id, cfp.amount, cfp.currency_id, cfp.earlier_payment, cfp.note, ac.type_id FROM coris_assistance_cases_expenses cfp, coris_assistance_cases ac WHERE cfp.expense_id = '$_GET[expense_id]' AND cfp.case_id = ac.case_id";
$row = "";
//echo $query;
if ($result = mysql_query($query)) {
	if ($row = mysql_fetch_array($result)) {
?>
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="popupTitle"><?= AS_CASD_SZCZEGWYK ?>&nbsp;</td>
		</tr>
	</table>	
        <table cellpadding=4 cellspacing=0 width="100%">
        <form action="AS_cases_details_expenses_position_details.php?action=1&expense_id=<?php echo $_GET['expense_id'] ?>&decision_id=<?php echo $decision_id; ?>&tryb=<?php echo isset($_GET['tryb'])?$_GET['tryb']:''; ?>" method="post" name="form1" onsubmit="return validate();">
            <tr>
                <td align="center">
                    <table width="100%">
                        <tr>
                            <td align="right"><small><?= AS_CASD_WYKA ?></small></td>
                          <td align="left">
								<input type="text" name="contrahent_id" id="contrahent_id" value="<?php echo $row['contrahent_id'] ?>" size="5"  onBlur="form_change_contrahent(this.value, <?php echo getValue('branch_id')?getValue('branch_id'):0;?>);"  style="text-align: center;">
								<input type="hidden" name="old_contrahent_id" id="old_contrahent_id" value="<?php echo $row['contrahent_id'] ?>" >
								
                                <input type="text" name="contrahent_name" id="contrahent_name" size="30" style="background: #eeeeee" disabled> <input type="button" tabindex="-1" value="L" style="background: #cccccc; color: #999999; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 18pt;" onclick="window.open('GEN_contrahents_select_frameset.php?branch_id=<?php echo getValue('branch_id');?>','ContrahentSearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=420,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 220) / 2);" title="<?= AS_CASD_WYSZWYK ?>">
                                <input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_contrahents_details.php?contrahent_id='+document.getElementById('contrahent_id').value,'','scrollbars=yes,resizable=yes,width=650,height=620, left=175,top=40')" title="<?= AS_CASD_SZCZEGWYK ?>">
&nbsp;&nbsp;
<?php

if ($row['guarantee']){
	$q = "SELECT * FROM coris_assistance_cases_expenses_guarantee  WHERE  ID_expense='$expense_id'  ORDER BY ID DESC LIMIT 1";
	
	$mr = mysql_query($q);
	$title='';
	if (mysql_num_rows($mr)>0){
		$r = mysql_fetch_array($mr);
		$title = 'Wys³ane: '.$r['date'].', '.($r['ID_document_type']==1 ? 'fax'  : '').($r['ID_document_type']==2 ? 'email'  : '').','."\n".' user: '.getUserName($r['ID_user']);		
	}

	echo '<input type="checkbox" title="'.$title.'" checked disabled>';	
}
	

?>&nbsp;<input name="but" type="button" id="but" style="width:110px" value="<?= AS_CASD_WYSLGWAR ?>" onClick="gwarancja();"> </td>
                        </tr>
                        <tr>
                          <td align="right"><small><?= AS_CASD_NRWYK ?></small></td>
                          <td align="left"><input name="ref_number" type="text" id="ref_number" style="text-align: right" value="<?php echo $row['ref_number'];?>" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                            <td align="right"><small><?= AS_CASD_ZLEC ?></small></td>
                          <td align="left" nowrap>
                                <select name="activity_id" style="font-size: 8pt;">
                                    <option value="0"></option>
<?php

//AS_cases_details_expenses_popup_position_new_popup_client.php

$query = "SELECT afa.activity_id, afa.value , afa.value_eng FROM coris_finances_activities afa, coris_finances_activities2types afa2t WHERE afa.active = 1 AND afa.activity_id = afa2t.activity_id AND afa2t.type_id = $row[type_id] ORDER BY afa.sort";
if ($result = mysql_query($query)) {
    while ($row2 = mysql_fetch_array($result)) {
    	$val = ( ($lang=='en' && $row2['value_eng'] != '' ) ? $row2['value_eng'] : $row2['value'] );
    	
		if ($row2['activity_id'] == $row['activity_id']) {
			echo "<option value=\"".$row2['activity_id']."\" selected>". StrTrim($val, 35) ."</option>";
		} else {
			echo "<option value=\"".$row2['activity_id']."\">". StrTrim($val, 35) ."</option>";
		}
    }
} else {
    die (mysql_error());
}
?>
                                </select> 
                              <?= AS_CASD_DATZLEC ?> 
                              <input type="text" name="activity_date" value="<?php echo $row['activity_date']; ?>" style="font-size: 7pt; text-align: center" size="12" >
                              </td>
                        </tr>
    					<tr >
                          <td align="right"><small><?= AS_CASD_ILOSCJEDN ?></small></td>
                          <td align="left">                                                       	                            	
                            	<input name="number_of_units" type="text" id="number_of_units" style="text-align: right;" size="10" maxlength="10" value="<?php echo $row['number_of_units']; ?>">
                           </td>
                        </tr>                                                    
                        <tr>
                          <td align="right"><small><?= AS_CASD_KOSZTZLEC ?></small></td>
                          <td align="left">
                            <input type="text" name="amount" id="amount" size="10" maxlength="10" style="text-align: right" value="<?php echo print_currency($row['amount']); ?>">
    <?= AS_CASD_ROZL ?>
    <input name="final" type="checkbox" id="final" style="background: #dddddd;" value="1" <?php echo $row['final']==1 ? 'checked' : '' ?>>
    <small>&nbsp;<?= AS_CASD_WAL ?></small>
    <select name="currency_id" id="currency_id" style="font-size: 8pt;" onChange="zmien_waluta();getCaseCurrency(<?php echo $case_id; ?>,'currency_id','currency_info','currency_table_id');">
      <option value="0"></option>
      <?php
$query = "SELECT currency_id FROM coris_finances_currencies WHERE active = 1 ORDER BY currency_id";
if ($result = mysql_query($query)) {
    while ($row2 = mysql_fetch_array($result)) {
		if ($row2['currency_id'] == $row['currency_id']) {
?>
      <option value="<?php echo $row2['currency_id'] ?>" selected><?php echo $row2['currency_id'] ?></option>
      <?php
		} else {
?>
      <option value="<?php echo $row2['currency_id'] ?>"><?php echo $row2['currency_id'] ?></option>
      <?php

		}
    }
} else {
    die (mysql_error());
}
?>
    </select>&nbsp;<span id="currency_info">Kurs: </span><input type="hidden" name="currency_table_id" id="currency_table_id" value="<?php echo $row['table_id'];;?>">
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" align="right">&nbsp;                          </td>
                        </tr>
<?php
	if ($client_id==11086){						
		
				$cardif_reserve = 0;
				$id_swiadczenie = 0;
				$suma_ubezpieczenia = '';
				$amount = '';
				
    			$q = "SELECT * FROM coris_cardif_cases_reserve WHERE case_id='$case_id' AND ID_expenses='$expense_id'";
    			$mr = mysql_query($q);
    			if (mysql_num_rows($mr)>0){
    				$r = mysql_fetch_array($mr);
    				$cardif_reserve = $r['ID'];
    				$id_swiadczenie = $r['ID_swiadczenie'];
    				$suma_ubezpieczenia = $r['suma'];
    				$amount = $r['rezerwa'];    				
    			}
		echo '
		<tr> 		
                <td  align="right"><small><b>¦wiadczenie: </b></small></td>
                <td align="left">'. wysw_swiadczenie('id_swiadczenie',$id_swiadczenie,$case_cardif_info['ID_typ_umowy'],0,'class="required" onChange="getSumaUbezp(this.value,\'wariant_ubezpieczenia\',\'suma_ubezpieczenia\');"') .'&nbsp;&nbsp;&nbsp;				
				&nbsp;&nbsp;&nbsp;<small><b>Suma ubezp.:</b></samll>&nbsp;&nbsp; 
				<input type="text" name="suma_ubezpieczenia" id="suma_ubezpieczenia" value="'.$suma_ubezpieczenia.'"  style="text-align: right;" size="8" maxlength="20" class="required">				
			 '. wysw_currency_pln('sumacurrency_id','PLN',0,'class="required"') .'										
                <input type="hidden" name="typ_umowy" id="typ_umowy" value="'.$case_cardif_info['ID_typ_umowy'].'">
                <input type="hidden" name="wariant_ubezpieczenia" id="wariant_ubezpieczenia" value="'.$case_cardif_info['ID_wariant_ubezpieczenia'].'">
                <input type="hidden" name="cardif_reserve" id="cardif_reserve" value="'.$cardif_reserve.'">
             
                
                </td></tr>
               <tr><td align="right" colspan="2">&nbsp;</td></tr>  
                ';			
	}else if ($client_id==2201){
		
		include('lib/lib_europa.php');
		$europa_case = new EuropaCase($case_id);
		
		echo $europa_case->listaSwiadczenZlecenieEdit($expense_id);
		
	}else if ($client_id==11){
		
		include('lib/lib_europa.php');
		$europa_case = new EuropaCase($case_id);
		
		echo $europa_case->listaSwiadczenZlecenieEdit($expense_id);
		
	}else if ($client_id==10){
		
		include('lib/lib_skok.php');
		$skok_case = new SKOKCase($case_id);
		
		echo $skok_case->listaSwiadczenZlecenieEdit($expense_id);
		
	}
?>                           
                        <tr>
                          <td align="right"><strong><small><?= AS_CASD_OBCKLIEN ?> </small></strong></td>
                          <td align="left"> <?php echo activity_lista('client_charge_id',$row['client_charge_id'],$row['type_id'],1) ?>  <input name="client_amount" type="text" id="client_amount" style="text-align: right" value="<?php echo str_replace(".", ",", $row['client_amount']) ?>" size="10" maxlength="10">
                          &nbsp;&nbsp;<input name="currency_klient" type="text" id="currency_klient" style="background: #eeeeee" size="3" maxlength="3" readonly;  text-align: center></td>
                        </tr>
<?php if ($row['cicp_amount'] != 0.0 ) {?>                        <tr>
                          <td align="right"><strong><small><?= AS_CASD_OBCCICP ?></small></strong></td>
                          <td align="left">
                         <?php echo activity_lista('cicp_charge_id',$row['cicp_charge_id'],$row['type_id'],2) ?>	<input name="cicp_amount" type="text" id="cicp_amount" style="text-align: right" value="<?php echo str_replace(".", ",", $row['cicp_amount']) ?>" size="10" maxlength="10">
                         &nbsp;&nbsp;<input name="currency_cicp" type="text" id="currency_cicp" style="background: #eeeeee" size="3" maxlength="3" readonly;  text-align: center></td>
                        </tr>
<?php } ?>                        
                        
                        <tr>
                            <td align="right"><strong><small><?= AS_CASD_OBCWCOR ?></small></strong></td>
                            <td align="left">
							<?php echo activity_lista('coris_charge_id',$row['coris_charge_id'],$row['type_id'],3) ?>	<input name="coris_amount" type="text" id="coris_amount" style="text-align: right" value="<?php echo str_replace(".", ",", $row['coris_amount']) ?>" size="10" maxlength="10">
							&nbsp;&nbsp;<input name="currency_coris" type="text" id="currency_coris" style="background: #eeeeee" size="3" maxlength="3" readonly;  text-align: center>							
							<small>&nbsp;</small></td>
                        </tr>
                        <tr>
                          <td align="right"><small><?=  NOTE ?></small></td>
                          <td align="left">
                            <textarea name="note" rows="3" cols="50" style="font-family: Verdana; font-size: 8pt" onKeyPress="return (this.value.length < 255);" onPaste="return ((form1.note.value.length + window.clipboardData.getData('Text').length) < 255 );" wrap="virtual"><?php echo $row['note'] ?></textarea>
                          </td>
                        </tr>
     <tr>
                            <td align="right"><small><?= AS_CASD_WPROW2 ?>&nbsp;</small></td>
                            <td align="left">
                              <input name="user" type="text" style="background: #eeeeee";  font-size: 7pt" value="<?php echo getUserInitials($row['user_id']) ?>" size="5" maxlength="5" readonly> 
                              Dnia: 
                              <input type="text" name="date" value="<?php echo $row['date']; ?>" style="background: #eeeeee";  font-size: 7pt; text-align: center" size="12" readonly ></td>
                        </tr>
                        <tr>
              <td colspan="2" align="center">
<?php
    $earlier_payment = (isset($row['earlier_payment'])) ? 1 : 0;
?>
                <input type="checkbox" name="earlier_payment" style="background: #dfdfdf" <?php echo ($row['earlier_payment']) ? "checked" : "" ?>>&nbsp;<small><font color="red" style="cursor: default" onclick="checkboxSelect(form1.earlier_payment);"><u><?= AS_CASD_PROWCZOPL ?></u></font></small>
              </td>
                        </tr>
                        <tr height="2">
                            <td colspan="2" style="border-top: #cccccc 1px solid;" align="left">
                            <b style="color:red;">Opisz w jakim stopniu dostawca wywi±za³ siê z powierzonego zadania:</b><br><br>
                            <?php
                                                        
                       $res = '';

                            $query = "SELECT * FROM coris_contrahents_rank  WHERE ID_expences='$expense_id' ";
                            $mr = mysql_query($query);
                            if (mysql_num_rows($mr) == 0 ){
                            	$res = show_rank_form(0,0,'','');
                            }else{                            
                            	$rr = mysql_fetch_array($mr);
                            	$res =  show_rank_form(1,$rr['ID_rank'],$rr['note'],$rr['date'].' '.getUserInitials($rr['ID_user']));
                            }
	
							echo $res;
                            ?>
                            </td>
                        </tr>
                        <tr height="28">
                            <td colspan="2" style="border-top: #cccccc 1px solid;" align="center">
            <center><input type="button" value="<?= AS_CASD_DEL ?>" style="color: red; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASD_DELPOS ?>" onclick="javascript: if (confirm('Czy na pewno chcesz usun±æ pozycjê?')) window.location='AS_cases_details_expenses_position_details.php?delete=1&expense_id=<?php echo $_GET['expense_id'] ?>&decision_id=<?php echo $decision_id; ?>&tryb=<?php echo isset($_GET['tryb'])?$_GET['tryb']:''; ?>';">&nbsp;&nbsp;
            <input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASD_ZAPPOZ ?>" onclick="return check_koszty();">
              &nbsp;
              <input name="Button" type="button" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= CLOSE ?>" value="<?= CLOSE ?>" Onclick="window.close()">
            </center>
                            </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </form>
        </table>
    <iframe name="contrahent_search_frame" width="0" height="0" src="GEN_contrahents_select_iframe.php?contrahent_id=<?php echo $row['contrahent_id'] ?>"></iframe>

<?php
  } else {
    exit;
  }
} else {
  die (mysql_error());
}
?>
<script>
function gwarancja(){
	
	<?php
	
	
	if ($row_case['client_id'] ==7592 && $row_case['eventdate'] >= '2008-08-01' && $row_case['country_id']=='EG'){
	?>
			window.open('DOC_new_document.php?case_id=<? echo $row['case_id'];?>&doc=16&contrahent_id='+form1.contrahent_id.value+'&amount='+form1.amount.value+'&currency_id='+form1.currency_id.value+'&ref_number='+form1.ref_number.value+'&expense_id=<?php echo $expense_id; ?>','','toolbar=0,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width='+ (screen.availWidth - 6) + ',height='+ (screen.availHeight - 100) +',left=0,top=0');
	<?php }else{

		if (  getValue('branch_id')==2 || getValue('branch_id')==3 ) {
			$template_id= 61; // 20
		}else{

			$template_id= 60;;//11
		}

	?>

			window.open('DOC_new_document.php?case_id=<? echo $row['case_id'];?>&doc=<?php echo $template_id ?>&contrahent_id='+document.getElementById('contrahent_id').value+'&amount='+document.getElementById('amount').value+'&currency_id='+document.getElementById('currency_id').value+'&ref_number='+document.getElementById('ref_number').value+'&expense_id=<?php echo $expense_id; ?>','','toolbar=0,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width='+ (screen.availWidth - 6) + ',height='+ (screen.availHeight - 100) +',left=0,top=0');
	<?php } ?>
}

function check_koszty(){
	a1 =  1.0 * document.form1.client_amount.value.replace(',','.');
	a2 = 1.0 * document.form1.coris_amount.value.replace(',','.');
	if (document.form1.cicp_amount ) 
			a3 = 1.0 * document.form1.cicp_amount.value.replace(',','.');
	else
			a3 = 0;
	
	amount = 1.00 * document.form1.amount.value.replace(',','.');

	sum = 1.0 * (a1 + a2 + a3);
	
	licznik=0
	if (a1>0.00 || a1>0 )
		licznik++;
	if (a2>0.00 || a2>0 )
		licznik++;
	if (a3>0.00 || a3>0 )
		licznik++;
	

if (sum != amount || licznik>2 ) {alert('<?= AS_CASD_BLPODZKOSZT ?> '+ licznik ); return false;}
		
	return true;
}
zmien_waluta();
function zmien_waluta(){
	document.form1.currency_klient.value = document.form1.currency_id.value;
    if (document.form1.currency_cicp) document.form1.currency_cicp.value = document.form1.currency_id.value;
    document.form1.currency_coris.value = document.form1.currency_id.value;
}


<?php
if ($row['table_id'] > 0 )
	echo ' getCaseCurrency('.$case_id .',\'currency_id\',\'currency_info\',\'currency_table_id\');';
?>


window.addEvent('domready', function(){
	$('contrahent_id').addEvent('zmien', function(value){		
		form_change_contrahent(value);
	})
})
</script>
    </body>
</html>
		
<?php

function StrTrim($string, $length) {
    return (strlen($string) < $length) ? $string : substr($string, 0, $length) . "...";
}	

function activity_lista($name,$default,$type_id,$group_id){
	$lang = $_SESSION['GUI_language'];
	
   $result = '<select name="'.$name.'" style="font-size: 8pt;">
                                 ';

$query = "SELECT fc.charge_id, fc.value, fc.value_eng FROM coris_finances_charges  fc WHERE fc.active = 1 AND fc.type_id = '$type_id' AND group_id='$group_id' ORDER BY charge_id";
if ($mysql_result = mysql_query($query)) {
    while ($row2 = mysql_fetch_array($mysql_result)) {
     	 $val = ( ($lang=='en' && $row2['value_eng'] != '' ) ? $row2['value_eng'] : $row2['value'] );
       
		if ($default == $row2['charge_id']) {			
			$result .=  "<option value=\"".$row2['charge_id']."\" selected>". StrTrim( $val, 35) ."</option>";
		} else {
			$result .=  "<option value=\"".$row2['charge_id']."\">". StrTrim( $val, 35) ."</option>";
		}
    }
} else {
    die (mysql_error());
}
	$result .= '</select>'; 
	return $result;
}
		


function show_rank_form($tryb,$rank_value,$rank_description){
	$lang = $_SESSION['GUI_language'];
	
				$query = "SELECT * FROM coris_contrahents_rank_def  ORDER BY value ";

                            $mr = mysql_query($query);
                           // $res =  'Zapisz ocenê: <input type="checkbox" name="rank_save" value="1">';
                           $res = '';
                            $res .=  'Ocena: <input type="hidden" name="rank_edit" value="'.($tryb==1 ? 0 : 1).'">';
                            $res .=  '<select name="rank_value" '.($tryb==1 ? 'disabled' : '').'>';
                            $res .=  '<option value="0" >-- Oceñ --</option>';
                            while ($row = mysql_fetch_array($mr)){                            	 
                            	$desc = ( ($lang=='en' && $row['description_eng'] != '' ) ? $row['description_eng'] : $row['description'] );       
                            	$res .=  '<option value="'.$row['ID'].'" '.($row['ID']==$rank_value ? 'selected' : '').'>'.$desc.' ('.$row['value'].')</option>';
                            }
	
    					$res .= "</select>";                        
                            
    					$res .=  '<br>Uwagi: <textarea cols="80" rows="3" name="rank_description" '.($tryb==1 ? 'readonly' : '').'>'.$rank_description.'</textarea>';

    			return $res;
}
?>