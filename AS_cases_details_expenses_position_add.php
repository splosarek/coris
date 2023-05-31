<?php 
//include('include/include.php');
require_once('include/include_ayax.php'); 
include('include/include_mod.php');
include_once('lib/lib_case.php');

$lang = $_SESSION['GUI_language'];

$case_id = getValue('case_id');
$client_id=0;
$case_cardif_info = array();
if ($case_id>0){
	$query  = "SELECT * FROM coris_assistance_cases WHERE case_id='$case_id'	";
	$mysql_result=mysql_query($query);
	if (mysql_num_rows($mysql_result)>0){
			$row=mysql_fetch_array($mysql_result);
			$client_id = $row['client_id'];				
			if ($client_id==11086){		
				include_once('lib/lib_cardif.php');
				include_once('include/include_mod.php');	
				$case_cardif_info = CardifCase::getCaseCardifInfo($case_id);	
			}
	}	
}

$branch = CaseInfo::getCaseBarnch($case_id);
$checkRezerwy = CaseInfo::checkGLobalReserve($case_id);
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= AS_CASD_DODWYK ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="javascript" src="Scripts/mootools.js"></script>
<script language="javascript" src="Scripts/javascript.js"></script>
</head>
    <body bgcolor="#dfdfdf">
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
                if (document.getElementById('contrahent_id').value == "") {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWYK ?>");
                    document.getElementById('contrahent_id').focus();
                    return false;
                }
                if (document.getElementById('activity_id').value == 0) {
                    alert('<?= AS_CASD_MSG_PROSZWYBRZLEC ?>');
                    document.getElementById('activity_id').focus();
                    return false;
                }

                if ($('amount_applied').value == "") {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWART ?>");
                    $('amount_applied').focus();
                    return false;
                }


                if (document.getElementById('amount').value == "" ) {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWART ?>");
                    document.getElementById('amount').focus();
                    return false;
                }
                if (!document.getElementById('amount').value.match(/^\d*$/) && !document.getElementById('amount').value.match(/^\d*,\d\d$/) && !document.getElementById('amount').value.match(/^\d*\.\d\d$/)) {
                    alert("<?= AS_CASD_MSG_BLFORMWART ?>");
                    document.getElementById('amount').focus();
                    return false;
                }
                if ($('amount_applied').value != "" && $('amount').value != ""  && ( 1.0 * $('amount_applied').value.replace(',','.')) > (1.0 * $('amount').value.replace(',','.')) ) {
                	if ( $('saving_id').value == 0 ){
                     	alert('<?= AS_CASD_UZOPISOSZCZEDN ?>');				                     	
                    	$('saving_id').focus();
                    	return false;
					}
                }

                if ($('amount_applied').value != "" && $('amount').value != ""  ) {
            		amount = ($('amount').value.replace(',','.'))*1.0;			
            		amount_applied = ($('amount_applied').value.replace(',','.'))*1.0;			

					if (amount == 0.00){
						alert('<?= AS_WYK_PRKKWOT_WZ ?>'); //Proszę podać kwotę do zagwarantowania większą niż 0   
						document.getElementById('amount').focus();             				
						return false;
					}	
					if ( amount > amount_applied){
						alert('<?= AS_WYK_KWZAGWKWZ ?>'); //Kwota do zagwarantowania wieksza niż kwota żądana!                				
						return false;         
					}else{
    						
					}
 
					if ( amount == amount_applied){  // jesli kwota wnioskowana jest = kwocie przewiduwanej to kasujemy oszczednosci        					
						$('saving_id').value = '';    						 
    				}
            }
            
                if (document.getElementById('currency_id').value == 0) {
                    alert("<?= AS_CASD_MSG_PROSZWYBRWAL ?>");
                    document.getElementById('currency_id').focus();
                    return false;
                }


                if ($('client_charge_id').value > 0 && ($('client_amount').value == '0,00' || $('client_amount').value == ''  || $('client_amount').value == '0') ){
					alert('Proszę podać kwotę "Obciążenie klienta"');
					return false;
                }

                if ($('coris_charge_id').value > 0 && (  $('coris_amount').value == '0,00' || $('coris_amount').value == ''  || $('coris_amount').value == '0') ){
					alert('Proszę podać kwotę "W ciężar APA"');
					return false;
                }
                
                if ( $('client_amount').value != '0,00' && $('client_charge_id').value == 0   ){
					alert('Proszę wybrać "Obciążenie klienta"');
					return false;
                }
                
                if ( $('coris_amount').value != '0,00' && $('coris_charge_id').value == 0   ){
					alert('Proszę wybrać "W ciężar APA"');
					return false;
                }
                
                <?php
                		if ($client_id==11086){
                			?>
                			if (document.getElementById('id_swiadczenie').value == 0 ) {
								alert("Prosze wybrać świadczenie");
								document.getElementById('id_swiadczenie').focus();
								return false;
							}
							
							suma = document.getElementById('suma_ubezpieczenia').value.replace(',','.');							
            				suma = suma *1.0;
							if ( suma > 0  ){
            					
            				}else{
                				alert('Proszę podać sumę ubezpieczenia!');
                				document.getElementById('suma_ubezpieczenia').focus();
                				return false;                				
                			} 
                			
                			amount = document.getElementById('amount').value.replace(',','.');			
                				amount = amount *1.0;			               			
                			if ( amount > suma){
                				alert('Koszt zlecenia większy niż suma ubezpieczenia!');                				
                				return false;         
                			}

                			
                		<?php	      
                		      	                			
              		}              
                ?>

            }

function expense_selected(case_id,contrahent_id,amount,currency_id,activity_id) { 
		opener.document.form1.expense_id.value= case_id; 
		opener.document.form1.contrahent_id.value= contrahent_id; 
		opener.document.form1.contrahent_id.focus();
		opener.document.form1.contrahent_id.blur();
	    opener.document.form1.amount.value= amount; 

		ilosc= opener.document.form1.currency_id.length;
		zm=0;
		for (i=0;i<ilosc;i++){
			if (opener.document.form1.currency_id.options[i].value == currency_id){
					opener.document.form1.currency_id.selectedIndex = i;
			}
		}

		ilosc= opener.document.form1.activity_id.length;
		zm=0;
		for (i=0;i<ilosc;i++){
			if (opener.document.form1.activity_id.options[i].value == activity_id){
					opener.document.form1.activity_id.selectedIndex = i;
			}
		}
		window.close();
}           



function form_change_contrahent(contrahent_id, branch_id){
  if (contrahent_id){
		ayax_action=1;
		var url = 'ayax/contrahent_select.php';
		var jsonRequest = new Request.JSON({url: url, encoding: 'UTF-8',
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
			alert('Błąd kontrahenta zastępczego dla kontrahenta '+jsonObj.org_contrahent_id+' (niezdefiniowany lub nieistniejący), prosze poprawić i spróbować ponownie');
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
				if (confirm('Nie można użyć tego kontrahenta, Czy zamiast chcesz użyć kontrahenta: '+jsonObj.contrahent_id+' "'+jsonObj.name+'"')){
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

$decision_id=getValue('decision_id');
if (isset($_GET['action'])) {

	$earlier_payment = (isset($_POST['earlier_payment'])) ? 1 : 0;
	
	$amount = str_replace(',','.',getValue('amount'));
	
	$amount_applied = str_replace(',','.',getValue('amount_applied'));
	$desc_of_saving = getValue('desc_of_saving');
	
	

    $query = "INSERT INTO coris_assistance_cases_expenses (case_id, contrahent_id,ref_number, activity_id,activity_date, amount, currency_id, earlier_payment, note, user_id, date,client_charge_id,coris_charge_id,cicp_charge_id,client_amount,cicp_amount,coris_amount,final,table_id,amount_applied , desc_of_saving,ID_saving,number_of_units) ";
	$query .= "	VALUES ('".$_GET['case_id']."', '".$_POST['contrahent_id']."','".$_POST['ref_number']."', '".$_POST['activity_id']."','".$_POST['activity_date']."', '". $amount. "', '".$_POST['currency_id']."', '$earlier_payment', '".$_POST['note']."', '".$_SESSION['user_id']."', NOW(),'".$_POST['client_charge_id']."','".$_POST['coris_charge_id']."','".$_POST['cicp_charge_id']."','". str_replace(",", ".", $_POST['client_amount']) . "', '". str_replace(",", ".", $_POST['cicp_amount']) . "','".str_replace(",", ".",$_POST['coris_amount'])."','". (isset($_POST['final']) ? 1 :  0) . "','".getValue('currency_table_id')."','$amount_applied','$desc_of_saving','".getValue('saving_id')."','".getValue('number_of_units')."')";
    if ($result = mysql_query($query)) {
    	$expense_id=mysql_insert_id();
    	
    			if ($decision_id>0 && ($client_id==11086 ||$client_id==2201 ||$client_id==11 ) ){
    					include_once('lib/RegisterActionAfterDecision.php');
    					RegisterActionAfterDecision::registerUserAction($case_id, $decision_id, 'INSERT',$expense_id);    				
    			}
    			
    			$client_amount = str_replace(',','.',getValue('client_amount'));
    			$waluta = 'PLN';
    			
				$currency_id = getValue('currency_id');
				if ($currency_id == 'PLN' ){
					$rezerwa=$client_amount;
				}else{
					$table_id = getValue('currency_table_id');
					
					$tmpKurs = Finance::getKurs('', '', $currency_id,$table_id);
					$rate =$tmpKurs['rate'] ;
					$multiplier =$tmpKurs['multiplier'] ;
														
					$rezerwa = Finance::ev_round(($client_amount*$rate/$multiplier),2);					 
				}    			
    			
				CaseInfo::setReserve($case_id,$expense_id,$rezerwa,$waluta);
				if ($branch == 1 && $checkRezerwy ){    				
    				if (  getValue('case_rezerwa_globalna_zmiana') == 1 ){		
    					$case_rezerwa_globalna = str_replace(',','.',getValue('case_rezerwa_globalna'));
		    			CaseInfo::setGLobalReserve($case_id, $case_rezerwa_globalna , 'PLN',$expense_id);
    				}
				}
    	if ($client_id==11086){
    			$cardif_reserve = getValue('cardif_reserve');
    			$q = "SELECT * FROM coris_cardif_cases_reserve WHERE case_id='$case_id' AND ID_expenses=0 ";
    			$mr = mysql_query($q);
    			if (mysql_num_rows($mr)==0){
    					$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	

    					$suma_ubezpieczenia = str_replace(',','.',getValue('suma_ubezpieczenia'));
						$sumacurrency_id=getValue('sumacurrency_id');																		
                    	 
                    	/* REZERWA */ 
                    	$qi2  = "INSERT INTO coris_cardif_cases_reserve  SET  ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',suma='".$suma_ubezpieczenia."',currency_id ='$sumacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1";		
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ($mr && $mr2){								
							$poz = mysql_insert_id();
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();				
						}		    				
    			}else{
    				$r = mysql_fetch_array($mr);
    				if ($cardif_reserve>0){
    				
    				
    				}else{
    					$cardif_reserve=$r['ID'];
    					
    				}  
						$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	

    					$suma_ubezpieczenia = str_replace(',','.',getValue('suma_ubezpieczenia'));
						$sumacurrency_id=getValue('sumacurrency_id');																		
                    	 
                    	/* REZERWA */ 
                    	$qi2  = "UPDATE coris_cardif_cases_reserve  SET ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',suma='".$suma_ubezpieczenia."',currency_id ='$sumacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1
                    	WHERE ID='$cardif_reserve' LIMIT 1 ";		
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ($mr && $mr2){								
							$poz = mysql_insert_id();
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();				
						}		    				    				
    				
    			}
    			
    			  			    	
    	}
    	
    	if ($client_id==11 || $client_id== 2201){    			
    					$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	
    					
						$currency_id=getValue('currency_id');																		
                    	 
                    	
                    	$qi2  = "INSERT INTO coris_europa_rezerwy   SET  ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',currency_id ='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";		
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ( $mr2){								
							$poz = mysql_insert_id();
						}else{
							echo  "<br>INSERT  Error: ".$qi2."\n<br> ".mysql_error();				
						}		    				
    		
    			
						
						$qi = "INSERT INTO coris_europa_rezerwa_historia SET ID_rezerwa='$poz',rezerwa_stara='0',rezerwa_nowa='$amount',currency_id='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";
						$mr = mysql_query($qi);
						
						
    	
    	}
    	
    	if ($client_id==10 ){ //SKOK   			 
    					$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;						                    																	
    					
						$currency_id=getValue('currency_id');																		
                    	 
                    	
                    	$qi2  = "INSERT INTO coris_skok_rezerwy   SET  ID_expenses='$expense_id',case_id ='$case_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$amount',currency_id ='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";		
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ( $mr2){								
							$poz = mysql_insert_id();
						}else{
							echo  "<br>INSERT  Error: ".$qi2."\n<br> ".mysql_error();				
						}		    				
    		
    			
						
						$qi = "INSERT INTO coris_skok_rezerwa_historia SET ID_rezerwa='$poz',rezerwa_stara='0',rezerwa_nowa='$amount',currency_id='$currency_id', ID_user='".$_SESSION['user_id']."',date=now() ";
						$mr = mysql_query($qi);
						
						
    	
    	}
    	if ($_GET['tryb']=='finance'){
    		
    		//echo "<script>opener.document.form1.expense_id.value='$id'; window.close();</script>";
    		echo "<script>expense_selected('$expense_id','".$_POST['contrahent_id']."','".$_POST['amount']."','".$_POST['currency_id']."','".$_POST['activity_id']."')</script>";
			
    		exit;
    	}else if ($_GET['tryb']=='cardif'){    		
			 echo "<script>opener.document.getElementById('form_rezerwy').submit();  window.close();</script>";  
    		exit; 		
    	}else if ($_GET['tryb']=='europa'){    		
			 echo "<script>opener.document.getElementById('form_rezerwy').submit(); window.close();</script>";  
    		exit; 		    	    		
    	}else if ($_GET['tryb']=='rezerwy'){    		
			echo "<script>
					if (opener){
			        	if ( opener.document.getElementById('rezerwa_globalna_lock') ){
	        					opener.document.getElementById('rezerwa_globalna_lock').value=1;
	        					opener.document.getElementById('form_rezerwy').submit();
	        					
	        			}else{
	        					opener.document.location.reload(); 
	        			}		
	        		}
        			window.close();
			</script>";
    		exit; 		
    	}else{    	    			
			echo "<script>opener.contrahents.document.location.reload(); window.close();</script>";
			exit;
    	}
    } else {
        die (mysql_error());
    }

}


if ($client_id==11086){	

	echo '
    <script language="JavaScript1.2" src="Scripts/js_cardif_announce.js"></script>';
}else if ($client_id==2201 || $client_id==11  ) {	
	echo '
    <script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>';

}else if ($client_id==10   ) {	
	echo '
    <script language="JavaScript1.2" src="Scripts/js_skok_announce.js"></script>';

}


?>
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="popupTitle"><?= AS_CASD_DODWYK ?>&nbsp;</td>
		</tr>
	</table>	
        <table cellpadding=4 cellspacing=0 width="100%">
        <form action="AS_cases_details_expenses_position_add.php?action=1&case_id=<?php echo $_GET['case_id']; ?>&decision_id=<?php echo $decision_id; ?>&tryb=<?php echo isset($_GET['tryb'])?$_GET['tryb']:''; ?>" method="post" name="form1" onsubmit="return validate();">
            <tr>
                <td align="center">
                    <table width="100%">
                        <tr>
                            <td align="right"><small><?= AS_CASD_WYKA ?></small></td>
                            <td align="left">
                                <input type="text" name="contrahent_id" id="contrahent_id" value="" size="5" onBlur="form_change_contrahent(this.value, <?php echo getValue('branch_id')?getValue('branch_id'):0;?>);"  style="text-align: center;">
                                <input type="hidden" name="old_contrahent_id" id="old_contrahent_id" value="" >
<!--
                                <input type="text" name="contrahent_name" size="30" style="background: #eeeeee" disabled> <input type="button" value="L" style="background: #cccccc; color: #999999; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 18pt;" onclick="window.open('AS_cases_details_expenses_popup_position_new_popup_client.php','ContrahentSearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=400,height=220,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 220) / 2);" title="Wyszukaj wykonawcę">
//-->
                                <input type="text" name="contrahent_name" id="contrahent_name" size="30" style="background: #eeeeee" disabled>
                                <input type="button" value="L" style="background: #cccccc; color: #999999; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 22pt;" onclick="window.open('GEN_contrahents_select_frameset.php?branch_id=<?php echo getValue('branch_id');?>','ContrahentSearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=850,height=520,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 220) / 2);" title="<?= AS_CASD_WYSZWYK ?>">
                          </td>
                        </tr>
                        <tr>
                          <td align="right"><small><?= AS_CASD_NRWYK ?></small></td>
                          <td align="left"><input name="ref_number" type="text" id="ref_number" style="text-align: right" size="30" maxlength="30"></td>
                        </tr>
                        <tr>
                            <td align="right"><small><?= AS_CASD_ZLEC ?></small></td>
                          <td align="left" nowrap >
                                <select name="activity_id" id="activity_id" style="font-size: 8pt;">
                                    <option value="0"></option>
<?php

$query = "SELECT afa.activity_id, afa.value, afa.value_eng FROM coris_finances_activities afa, coris_finances_activities2types afa2t WHERE afa.active = 1 AND afa.activity_id = afa2t.activity_id AND afa2t.type_id = '$_GET[type_id]' ORDER BY afa.sort";
if ($result = mysql_query($query)) {
    while ($row = mysql_fetch_array($result)) {
        //echo "<option value=\"".$row['activity_id']."\">(".$row['activity_id'].") - ". StrTrim($row['value'], 35) ."</option>";
        $val = ( ($lang=='en' && $row['value_eng'] != '' ) ? $row['value_eng'] : $row['value'] );
        
        echo "<option value=\"".$row['activity_id']."\">". StrTrim($val, 35) ."</option>";
    }
} else {
    die (mysql_error());
}
?>
                                </select>                              <small><?= AS_CASD_DATZLEC ?> </small>
                                 <input type="text" name="activity_date" id="activity_date" value="<?php echo date('Y-m-d') ?>" style="font-size: 7pt; text-align: center" size="12" >
                          </td>
                        </tr>
    <tr bgcolor="#AAAAAA">
                          <td align="right"><small><?= AS_CASD_KOSZTZLECWNIOSK ?></small></td>
                          <td align="left">                           
                            	<input type="text" name="amount_applied" id="amount_applied" size="10" maxlength="10" style="text-align: right" value="0,00">                            
                            	<input name="amount_applied_currency" type="text" id="amount_applied_currency" style="background: #eeeeee; text-align: center;" size="3" maxlength="3" readonly value="<?php echo ($client_id==11086 ? 'PLN' : '' ) ?>">
                            	
                            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <small><?= AS_CASD_ILOSCJEDN ?>:</small> <input name="number_of_units" type="text" id="number_of_units" style="text-align: right;" size="10" maxlength="10" value="1">
                           </td>
                           </tr> 
                                                   
                        <tr  bgcolor="#AAAAAA">
                          <td align="right"><small><?= AS_CASD_KOSZTZLEC ?></small></td>
                          <td align="left" nowrap>
                            <input type="text" id="amount" name="amount" size="10" maxlength="10" style="text-align: right" value="0,00" class="required">
    <?= AS_CASD_ROZL ?>
    <input name="final" type="checkbox" id="final" style="background: #dddddd;" value="1" >
    <small>&nbsp;<?= AS_CASD_WAL ?></small>
<?php     
	if ($client_id==11086){
				echo wysw_currency_pln('currency_id','PLN',0,'');				
	}else{
        $currencyOnChange = "zmien_waluta();getCaseCurrency(" . $case_id . ",'currency_id','currency_info','currency_table_id','currency_rate_id');";
        $currencyLabel = "Kurs:";
        $branch_id = intval( getValue('branch_id') ); // popr
        if($branch_id == 2 || $branch_id == 3){
            $row['currency_id']='EUR';
            $currencyOnChange = "";
            $currencyLabel = "";
        }


        ?>
    <select name="currency_id" id="currency_id" style="font-size: 8pt;" onChange=<?php echo $currencyOnChange; ?>>
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
    </select>&nbsp;<span id="currency_info"><?php $currencyLabel ?></span><input type="hidden" name="currency_table_id" id="currency_table_id" value="0">
    <input type="hidden" name="currency_rate_id" id="currency_rate_id" value="">
    <?php 
	}?>
                          </td>
                        </tr>
                        
 <tr bgcolor="#AAAAAA">
                          <td align="right"><small><?= AS_CASD_OPISOSZCZEDN ?></small></td>
                          <td align="left">                           
                            <!--   <textarea name="desc_of_saving" id="desc_of_saving" rows="2" cols="50" style="font-family: Verdana; font-size: 8pt;height:28px;" ><?php echo $row['desc_of_saving']; ?></textarea> -->
                             <?php echo CaseInfo::wysw_oszczednosci($row['ID_saving'],'saving_id'); ?>
                           </td>  
                                                 <tr  bgcolor="#AAAAAA"> 		                
                <td align="right">
<?php 		
		//$result .= wysw_swiadczenie('id_swiadczenie',$id_swiadczenie,$case_cardif_info['ID_typ_umowy'],0,'class="required" onChange="getSumaUbezp(this.value,\'typ_umowy\',\'suma_ubezpieczenia\');"') ;
		
		echo   '&nbsp;</td><td align="right" style="padding-right:120px;"> 
				<!--<small><b>Suma ubezp. do wykorzystania:</b></small> <input type="text" name="case_suma_ubezpieczenia" id="case_suma_ubezpieczenia" value="'.$suma_ubezpieczenia.'"  style="text-align: right;" size="8" maxlength="20" class="required" disabled>'. wysw_currency('sumacurrency_id','PLN',0,'class="required"  disabled') .'										
				<br>
				-->';
if ($branch == 1 && $checkRezerwy ){				
		echo '<small><b>'.AS_REZ_REZGLOB.': </b></small>
					<input type="hidden" name="case_rezerwa_globalna_zmiana" id="case_rezerwa_globalna_zmiana" value="0">
					<input type="text" name="case_rezerwa_globalna" id="case_rezerwa_globalna" value=""  style="text-align: right;" size="8" maxlength="20" class="disabled" readonly >'. wysw_currency('case_rezerwa_currency_id','PLN',0,'class="required" disabled') .'
			<br>	<small><b>'.AS_REZ_REZDOWYK.': </b></small>
					<input type="text" name="case_rezerwa_do_wyk" id="case_rezerwa_do_wyk" value=""  style="text-align: right;" size="8" maxlength="20" class="disabled" readonly>'. wysw_currency('case_rezerwa_currency_id','PLN',0,'class="required" disabled') .'               
                
                <script>case_getSuma_do_wyk('.$case_id.',0,\'case_rezerwa_globalna\',\'case_rezerwa_do_wyk\')</script>                
               ';
                                           
	}
      ?>                 </td></tr>
                        <tr>
                          <td align="right">&nbsp;</td>
                          <td align="left">&nbsp; </td>
                        </tr>
                        
<?php
	if ($client_id==11086){
		

		
				$cardif_reserve = 0;
				$id_swiadczenie = 0;
				$suma_ubezpieczenia = '';
				$amount = '';
				
    			$q = "SELECT * FROM coris_cardif_cases_reserve WHERE case_id='$case_id' AND ID_expenses=0 ";
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
                <td  align="right"><small><b>Świadczenie: </b></small></td>
                <td align="left">'. wysw_swiadczenie('id_swiadczenie',$id_swiadczenie,$case_cardif_info['ID_typ_umowy'],0,'class="required" onChange="getSumaUbezp(this.value,\'wariant_ubezpieczenia\',\'suma_ubezpieczenia\');"') .'&nbsp;&nbsp;&nbsp;				
				&nbsp;&nbsp;&nbsp;<small><b>Suma ubezp.:</b></samll>&nbsp;&nbsp; 
				<input type="text" name="suma_ubezpieczenia" id="suma_ubezpieczenia" value="'.$suma_ubezpieczenia.'"  style="text-align: right;" size="8" maxlength="20" class="required">				
			 '. wysw_currency_pln('sumacurrency_id','PLN',0,'class="required"') .'										
                <input type="hidden" name="typ_umowy" id="typ_umowy" value="'.$case_cardif_info['ID_typ_umowy'].'">
                <input type="hidden" name="wariant_ubezpieczenia" id="wariant_ubezpieczenia" value="'.$case_cardif_info['ID_wariant_ubezpieczenia'].'">
                <input type="hidden" name="cardif_reserve" id="cardif_reserve" value="'.$cardif_reserve.'">
                <script>document.getElementById(\'amount\').value=\''.$amount.'\';</script>
                
                </td></tr>
               <tr><td align="right" colspan="2">&nbsp;</td></tr>  
                ';			
	}else if ($client_id==2201){
		
		include('lib/lib_europa.php');
		$europa_case = new EuropaCase($case_id);
		
		echo $europa_case->listaSwiadczenZlecenie();
		
	}else if ($client_id==11){
		
		include('lib/lib_europa.php');
		$europa_case = new EuropaCase($case_id);
		
		echo $europa_case->listaSwiadczenZlecenie();
		
	}else if ($client_id==10){ // skok
		
		include('lib/lib_skok.php');
		$skok_case = new SKOKCase($case_id);
		
		echo $skok_case->listaSwiadczenZlecenie();
		
	}
?>           <tr>
                          <td align="right"><strong><small><?= AS_CASD_OBCKLIEN ?> </small></strong></td>
                          <td align="left"> <?php echo activity_lista('client_charge_id',0,$_GET['type_id'],1) ?>
                              <input name="client_amount" type="text" id="client_amount" style="text-align: right" value="0,00" size="10" maxlength="10" onChange="sprawdz_rezerwe(<?php echo $case_id; ?>,0,'case_rezerwa_globalna','case_rezerwa_do_wyk',this)">
                          &nbsp;&nbsp;
                              <input name="currency_klient" type="text" id="currency_klient" style="background: #eeeeee; text-align: center;" size="3" maxlength="3" readonly value="<?php echo ($client_id==11086 ? 'PLN' : '' ) ?>"></td>
                        </tr>
                        <tr>
                          <td align="right"><strong><small>W ciężar APA</small></strong></td>
                          <td align="left"> <?php echo activity_lista('coris_charge_id',0,$_GET['type_id'],3) ?>
                              <input name="coris_amount" type="text" id="coris_amount" style="text-align: right" value="0,00" size="10" maxlength="10">
                          &nbsp;&nbsp;
                              <input name="currency_coris" type="text" id="currency_coris" style="background: #eeeeee;text-align: center;" size="3" maxlength="3" readonly  value="<?php echo ($client_id==11086 ? 'PLN' : '' ) ?>" >
                              <small>&nbsp;</small></td>
                        </tr>

                        <tr>
                            <td align="right"><small><?= NOTE ?></small></td>
                            <td align="left">
								<textarea name="note" cols="50" rows="3" wrap="virtual" id="note" style="font-family: Verdana; font-size: 8pt" onKeyPress="return (this.value.length < 255);" onPaste="return ((form1.description.value.length + window.clipboardData.getData('Text').length) < 255 );"></textarea>
                          </td>
                        </tr>
                        <tr>
						<td colspan="2" align="center"><input type="checkbox" name="earlier_payment" style="background: #dfdfdf">&nbsp;<small><font color="red" style="cursor: default" onclick="checkboxSelect(form1.earlier_payment);"><u><?= AS_CASD_PROWCZOPL ?></u></font></small></td>
                        </tr>
                        <tr height="2">
                            <td colspan="2"></td>
                        </tr>
                        <tr height="28">
                            <td colspan="2" style="border-top: #cccccc 1px solid;" align="center">                              
                                <center><input type="submit" value="<?= SAVE ?>" class="przycisk_save_ok" title="<?= AS_CASD_ZAPPOZ ?>" id="przycisk_save"  onclick="return check_koszty();"></center>
                            </td>
                        </tr>
                  </table>
                </td>
            </tr>
			<script>document.getElementById('contrahent_id').focus();</script>
        </form>
        </table>
        <iframe name="contrahent_search_frame" width="0" height="0" src=""></iframe>
<script>

function check_koszty(){
	a1 =  1.0 * $('client_amount').value.replace(',','.');
	a2 = 1.0 * $('coris_amount').value.replace(',','.');	

	if (a1 > 0 && $('client_charge_id').value == 0 ){
			alert('<?= AS_WYK_KOMWYBOBCK ?>'); //Proszę wybrać "Obciążenie klienta"
			$('client_charge_id').focus();
			return false;
	}
	
	if (a2 > 0 && $('coris_charge_id').value == 0 ){
			alert('<?= AS_WYK_KOMWYBWCCOR ?>'); //Proszę wybrać "W ciężar Coris"
			$('coris_charge_id').focus();
			return false;
	}
	
	a3 = 0;
	
	amount = 1.00 * $('amount').value.replace(',','.');

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

function zmien_waluta(){
	$('currency_klient').value = $('currency_id').value;
    //if (document.form1.currency_cicp )
      //  	document.form1.currency_cicp.value = document.form1.currency_id.value;
    $('currency_coris').value = $('currency_id').value;
    $('amount_applied_currency').value = $('currency_id').value;
}


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
   $result = '<select name="'.$name.'"  id="'.$name.'" style="font-size: 8pt;">
                                 ';

$query = "SELECT fc.charge_id, fc.value FROM coris_finances_charges  fc WHERE fc.active = 1 AND fc.type_id = '$type_id' AND group_id='$group_id' ORDER BY charge_id";
if ($mysql_result = mysql_query($query)) {
    while ($row2 = mysql_fetch_array($mysql_result)) {
    	$val = ( ($lang=='en' && $row2['value_eng'] != '' ) ? $row2['value_eng'] : $row2['value'] );
		if ($default == $row2['charge_id']) {
			$result .=  "<option value=\"".$row2['charge_id']."\" selected>". StrTrim($val, 35) ."</option>";
		} else {
			$result .=  "<option value=\"".$row2['charge_id']."\">". StrTrim($val, 35) ."</option>";
		}
    }
} else {
    die (mysql_error());
}
	$result .= '</select>'; 
	return $result;
}
			
?>
