<?php 
include('include/include.php');
include_once('lib/lib_case.php');

$lang = $_SESSION['GUI_language'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
    <body bgcolor="#dfdfdf">
<?php
if (isset($_GET['expense_id'])) {
    $positions = split(",", $_GET['expense_id']);
    foreach ($positions as $expense_id) {
        if ($expense_id != "") {
			$query = "UPDATE coris_assistance_cases_expenses SET active = 0 WHERE expense_id = $expense_id";
            if (!$result = mysql_query($query))
               die (mysql_error());
        }
    }
}
?>
        <script language="JavaScript">
        <!--
            function update() {
                document.location='AS_cases_details_expenses_frame.php?case_id=<?php echo $_GET['case_id'] ?>&type1='+ document.form1.type1.value +'&type2='+ document.form1.type2.value +'&date_sum='+ document.form1.date_sum1.value +'&date_guarantee='+ document.form1.date_guarantee1.value;
            }

			function EditContrahent(s) {
				window.open('AS_cases_details_expenses_position_details.php?expense_id='+ s+'&branch_id=<?php echo getValue('branch_id');?>','PositionDetails','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=620,height=550,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);
			}
            
        //-->
        </script>
<script language="JavaScript" src="CalendarPopup.js"></script>
    <script language="JavaScript">
		<!--
		var cal = new CalendarPopup();		
		cal.setMonthNames(<?= MONTHS_NAME ?>); 
		cal.setDayHeaders(<?= DAY_NAME ?>); 
		cal.setWeekStartDay(1); 
		cal.setTodayText('<?= TODAY ?>');
		//-->
	</script>		
		<style>
			body {
				margin-top: 0.1cm;
				margin-bottom: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
			}
			td {
				font-size: 7pt;
			}
		</style>
        <form name="form1"> 		
        <table  border="0" cellpadding="2" cellspacing="1">
            <tr align="center" bgcolor="#6699cc" style="color: #ffffff">
                <td width="15"></td>
                <td width="25"></td>
                <td width="35"><small><?= ID ?></small></td>
                <td width="140"><small><?= AS_CASD_WYKA ?></small></td>
                <td width="130"><small><?= AS_CASD_ZLEC ?></small></td>
                <td width="35"><small><?= AS_CASES_DEC ?></small></td>
                <td width="10"><small><?= AS_CASD_GWAR  ?></small></td>
                <td width="10"><small><?= INVOICE  ?></small></td>
                <td width="10"><small><?= FK_PAYDET_PLATN ?></small></td>
                <td width="65"><small><?= AS_CASD_WART ?></small></td>
                <td width="20"><small><?= AS_CASD_WAL ?></small></td>
            </tr>
<?php

//
//$query = "SELECT WHERE SELECT MAX(fct.table_id) FROM coris_finances_currencies_tables fct WHERE fct.ratetype_id = 2 AND fct.active = 1";
$rates = array();

$date_sum1 = isset($_GET['date_sum']) ? "'".$_GET['date_sum']."'": "NOW()";
$type = isset($_GET['type1']) ? $_GET['type1'] : 1;
$case_id = (isset($_GET['case_id'])) ? $_GET['case_id'] : 0;



$subc='';  
  if ($type==1)
$subc = "SELECT table_id
FROM coris_finances_currencies_tables WHERE 
ratetype_id='$type' AND ( type_id = 'A'  OR type_id ='B') 
AND publication_date < $date_sum1
ORDER BY publication_date DESC LIMIT 2";
else 
$subc = "SELECT table_id
FROM coris_finances_currencies_tables WHERE 
ratetype_id='$type' AND type_id = 'C'
AND publication_date < $date_sum1
ORDER BY publication_date DESC LIMIT 1";


$mysql_result = mysql_query($subc);
$res = array();
while ($row = mysql_fetch_array($mysql_result)){
	$res[] = $row[0];
}

$query_cur = "SELECT coris_finances_currencies_tables_rates.currency_id,multiplier,rate 
FROM coris_finances_currencies_tables_rates 
WHERE  coris_finances_currencies_tables_rates.table_id IN 
( ".implode(',',$res)." )
 ";

//echo $query_cur;

$mysql_result = mysql_query($query_cur);
while ($row=mysql_fetch_array($mysql_result)){
	$tmp = array( $row['currency_id'] => array ($row['multiplier'],$row['rate']) );	
	$rates = array_merge($rates,$tmp);
}



$query = "SELECT ace.expense_id, ace.contrahent_id, ace.amount, ace.currency_id, ace.date, ace.final, ace.earlier_payment,ace.guarantee,
	 ace.activity_note, u.username, acpa.value,acpa.value_eng,
 c.name AS company, ace.currency_id,cfctr.multiplier,cfctr.rate  ,ace.coris_amount,ace.client_amount 
FROM coris_users u, coris_finances_activities acpa, coris_contrahents c, coris_assistance_cases_expenses ace
LEFT JOIN coris_finances_currencies_tables_rates  cfctr ON cfctr.table_id = ace.table_id AND cfctr.currency_id = ace.currency_id
WHERE ace.case_id = '$case_id' AND ace.active = 1 AND ace.user_id = u.user_id AND ace.activity_id = acpa.activity_id AND ace.contrahent_id = c.contrahent_id";
//echo $query;
// AND fct.publication_date
if ($result = mysql_query($query)) {
    $i = 1;
    $sum = 0;
    $suma_pln = 0.0;
    $suma_coris_pln = 0.0;
    $suma_klient_pln = 0.0;
    
    while ($row = mysql_fetch_array($result)) {
        if ($row['currency_id'] == "PLN") {
        		$kwota_pln = $row['amount'];
        		$coris_pln = $row['coris_amount'];
        		$klient_pln = $row['client_amount'];
        		
        }else{
        		$kwota_pln = ev_round(($row['amount']*$row['rate']/$row['multiplier']),2);        	
        		$coris_pln = ev_round(($row['coris_amount']*$row['rate']/$row['multiplier']),2);        	
        		$klient_pln = ev_round(($row['client_amount']*$row['rate']/$row['multiplier']),2);        	
        }
		$suma_pln += $kwota_pln;
		$suma_coris_pln += $coris_pln;
		$suma_klient_pln += $klient_pln;
?>

			<tr <?php 
					if ($row['final']==1){ 
						echo 'bgcolor="#FF8C00"';
					}else if ($row['earlier_payment']){ 
						echo "bgcolor=\"lightgreen\""; 
					}else if ($i % 2){ 
						echo "bgcolor=\"#d0d0d0\"";  
					}else{
						echo "";
					} 
					
					$val = ( ($lang=='en' && $row['value_eng'] != '' ) ? $row['value_eng'] : $row['value'] );
				?> title="<?php echo AS_CASD_NRPOZ.": $row[expense_id]\n".AS_CASD_TYPUSL.": $val\n".AS_CASD_NRUSL.": $row[contrahent_id]\n".AS_CASADD_KLIENT.": $row[company]\n".AS_CASD_DATWPR.": $row[date]\n".AS_CASD_WPROW.": $row[username]\n".AS_CASD_WAL.": $row[currency_id]"; if ($row['currency_id'] != "PLN") { echo "\n".AS_CASD_KURS.": " .($row['multiplier']).$row['currency_id'].' = '. ($row['rate']) . " PLN"; } ?>" 
					onmouseover="this.bgColor='#ced9e2'" 
					onmouseout="this.bgColor='<?php 
						if ($row['final'] ==1 ) 
							echo '#FF8C00';
						else if ($row['earlier_payment']) 
							echo "lightgreen"; 
						else if ($i % 2) 
							echo "#d0d0d0";  
						else  
							echo ""; ?>'" style="cursor: hand">
                <td align="right" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><font color="#6699cc"><?php echo $i ?></font></td>
                <td align="center" onclick="javascript:void(0);"><input type="checkbox" name="expense_id[]" value="<?php echo $row['expense_id'] ?>"></td>
                <td align="center" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><?php echo $row['contrahent_id'] ?></td>
                <td onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><?php echo (strlen($row['company']) < 20) ? $row['company'] : substr($row['company'], 0, 20) . "..."; ?></td>
                <td onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><small><?php echo (strlen($val) < 25) ? $val : substr($val, 0, 25) . "..."; ?></small></td>
                
                
                <td onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><small><?php echo sprawdz_decyzje($row['expense_id']); ?></small></td>
                <td align="center" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><small><?php echo ($row['guarantee'] ? YES : NO ); ?></small></td>
                <td align="center" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><small><?php echo sprawdz_fakture($row['expense_id']); ?></small></td>
                <td align="center" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><small><?php  echo sprawdz_platnosc($row['expense_id']); ?></small></td>
         
                <td align="right" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><font color="#999999"><?php echo strrev(preg_replace("/(\d{3})/", "\$1;psbn&", strrev(str_replace(".", ",", $row['amount'])))) ?></font></td>
                <td align="left" onclick="EditContrahent('<?php echo $row['expense_id'] ?>')"><?php echo $row['currency_id'] ?> <?php 
                	if ($row['currency_id'] == "PLN") ; 
                	else if (!isset($rates[$row['currency_id']][1])) 
                		echo "<font color=\"green\" title=\"".AS_CASD_MSG_MRAKKURS."\" style=\"cursor: default\"><b>!</b></font>"; 
                		else if ($rates[$row['currency_id']][1] == "0.0000") echo "<font color=\"red\" title=\"".AS_CASD_MSG_KURS0."\" style=\"cursor: default\"><b>!</b></font>";  ?></td>
            </tr>
			<tr>
				<td colspan="11" bgcolor="lightyellow">
					<?php echo $row['activity_note'] ?>
				</td>
			</tr>
<?php
        if (isset($rates[$row['currency_id']][1])) { // sumowanie pozycji z uwzględnieniem kursu danej waluty / przelicznika
            $sum = ($sum + (($row['amount'] * $rates[$row['currency_id']][0]) * $rates[$row['currency_id']][1]));
        } else if ($row['currency_id'] == "PLN") {
            $sum = ($sum+$row['amount']);
        }

      
    
         
        
        $i++;
    }
    echo '<input type="hidden" name="count_expense" id="count_expense" value="'.$i.'">';
    $coris_case = CaseInfo::getFullCaseInfo($case_id);
    
    
    if (mysql_num_rows($result) && $coris_case['coris_branch_id']==1	 ) {
?>
<tr>

                <td colspan="9" align="right" style="border-top: #6699cc 1px solid;">
                    <small><b><?= FK_ININDET_CIEZKL ?></b></small>&nbsp;&nbsp;&nbsp;                  
                </td>
                <td align="right" style="border-top: #6699cc 1px solid;"><?php echo print_currency($suma_klient_pln) ?></td>
                <td style="border-top: #6699cc 1px solid;">PLN</td>
            </tr>
<tr>

                <td colspan="9" align="right" style="border-top: #6699cc 1px solid;">
                    <small><b><?= FK_ININDET_CIEZCORIS ?></b></small>&nbsp;&nbsp;&nbsp;                  
                </td>
                <td align="right" style="border-top: #6699cc 1px solid;"><?php echo print_currency($suma_coris_pln) ?></td>
                <td style="border-top: #6699cc 1px solid;">PLN</td>
            </tr>
            
            <tr>
                <td colspan="9	" align="right" style="border-top: #6699cc 1px solid;">
                    <small><b><?= SUM ?></b></small>&nbsp;&nbsp;&nbsp;                  
                </td>
                <td align="right" style="border-top: #6699cc 1px solid;"><?php echo print_currency($suma_pln) ?></td>
                <td style="border-top: #6699cc 1px solid;">PLN</td>
            </tr>
<?php
/*

            <tr>
                <td colspan="5" align="right" style="border-top: #6699cc 1px solid;">
                    <small><b><?= SUM ?></b></small>&nbsp;&nbsp;
                    <font style="font-size: 7pt;"><?= AS_CASD_KURS?>:</font>&nbsp;
                    <select name="type1" style="background: #dddddd; font-size: 7pt;" onchange="update();">
                        <option value="1" <?php echo (isset($_GET['type1']) && $_GET['type1'] == 1) ? "selected" : ""; ?>><?= AS_CASD_KRS ?></option>
                        <option value="2" <?php echo (isset($_GET['type1']) && $_GET['type1'] == 2) ? "selected" : ""; ?>><?= AS_CASD_KKUP ?></option>
                        <option value="3" <?php echo (isset($_GET['type1']) && $_GET['type1'] == 3) ? "selected" : ""; ?>><?= AS_CASD_SPRZ ?></option>
                    </select>&nbsp;
                    <font style="font-size: 7pt;"><?= DATEFROM ?>:</font>
                    <a href="javascript:void(0)" onclick="cal.select(document.form1.date_sum1,'anchor1','yyyy-MM-dd'); update();" tabindex="-1" style="text-decoration: none" name="anchor1" id="anchor1"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
                    <input type="text" name="date_sum1" value="<?php echo (isset($_GET['date_sum'])) ? $_GET['date_sum'] : date("Y-m-d") ?>" style="background: #dddddd; font-size: 7pt; text-align: center" size="12" onchange="update()">
                </td>
                <td align="right" style="border-top: #6699cc 1px solid;"><?php echo strrev(preg_replace("/(\d{3})/", "\$1;psbn&", strrev(str_replace(".", ",", round($sum, 2))))) ?></td>
                <td style="border-top: #6699cc 1px solid;">PLN</td>
            </tr>
*/
$amount = 0;
$currency_id = "";
$multiplier = 0;
$rate = 1;
$guarantee = 0;

$query = "SELECT policyamount, policycurrency_id FROM coris_assistance_cases_details WHERE case_id = ".$_GET['case_id'];

if ($result = mysql_query($query)) {

    if ($row = mysql_fetch_array($result)) {
        $currency_id = $row['policycurrency_id'];
        $amount = $row['policyamount'];


$date_guarantee = isset($_GET['date_guarantee']) ? "'".$_GET['date_guarantee']."'": "NOW()";
$type2 = isset($_GET['type2']) ? $_GET['type2'] : 1;



$query_cur = "SELECT coris_finances_currencies_tables_rates.currency_id,multiplier,rate  
		FROM coris_finances_currencies_tables_rates,coris_finances_currencies_tables  
		WHERE coris_finances_currencies_tables.table_id = coris_finances_currencies_tables_rates.table_id 
			AND coris_finances_currencies_tables.ratetype_id='$type2' 
			AND coris_finances_currencies_tables.publication_date < $date_guarantee   
			AND coris_finances_currencies_tables_rates.currency_id='$currency_id' 
			
			ORDER BY coris_finances_currencies_tables.publication_date DESC LIMIT 1";

//echo $query_cur;
	if ($mysql_result = mysql_query($query_cur) ){
		if ( $amount>0){
		$row=mysql_fetch_array($mysql_result);
//		$tmp = array( $row['currency_id'] => array ($row['multiplier'],$row['rate']) );	

                        $rate = $row['rate'];
                        $multiplier = $row['multiplier'];

                        $guarantee = round(((($amount*$rate)/$multiplier)),2);
		}
	}else     
            die (mysql_error());
        
   }else     
            die (mysql_error());
    

} else {
    die (mysql_error());
}
    
/*
            <tr>
                <td colspan="5" align="right">
                    <small><b><font color="green" title="ubezpieczenie">UBEZP.</font></b></small>&nbsp;&nbsp;
                    <font style="font-size: 7pt;"><?= AS_CASD_KURS ?>:</font>&nbsp;
                    <select name="type2" style="background: #dddddd; font-size: 7pt;" onchange="update()">
                        <option value="1" <?php echo (isset($_GET['type2']) && $_GET['type2'] == 1) ? "selected" : ""; ?>><?= AS_CASD_KRS ?></option>
                        <option value="2" <?php echo (isset($_GET['type2']) && $_GET['type2'] == 2) ? "selected" : ""; ?>><?= AS_CASD_KKUP ?></option>
                        <option value="3" <?php echo (isset($_GET['type2']) && $_GET['type2'] == 3) ? "selected" : ""; ?>><?= AS_CASD_SPRZ ?></option>
                    </select>&nbsp;
                    <font style="font-size: 7pt;"><? DATEFROM ?>:</font>
                    <a href="javascript:void(0)" onclick="cal.select(document.form1.date_guarantee1,'anchor2','yyyy-MM-dd'); update();" tabindex="-1" style="text-decoration: none" name="anchor2" id="anchor2"><font face="Webdings" style="color: #000000; font-size: 12pt;">1</font></a>
                    <input type="text" name="date_guarantee1" value="<?php echo (isset($_GET['date_guarantee'])) ? $_GET['date_guarantee'] : date("Y-m-d") ?>" style="background: #dddddd; font-size: 7pt; text-align: center" size="12" onchange="update();">
                </td>
                <td align="right"><font color="green"><?php echo ($guarantee != 0) ? strrev(preg_replace("/(\d{3})/", "\$1;psbn&", strrev(str_replace(".", ",", $guarantee)))) : "-" ?></font></td>
                <td>PLN</td>
                <td>&nbsp;</td>
            </tr>
            <tr height="20">
                <td colspan="5"></td>
                <td align="right" style="border-top: #6699cc 2px solid;"><font color="<?php echo (($guarantee- $sum) < 0) ? "red" : "navy" ?>"><?php echo ($guarantee != 0) ? strrev(preg_replace("/(\d{3})/", "\$1;psbn&", strrev(str_replace(".", ",", round(($guarantee- $sum), 2))))) : strrev(preg_replace("/(\d{3})/", "\$1;psbn&", strrev(str_replace(".", ",", round((0-$sum),2))))); ?></font></td>
                <td style="border-top: #6699cc 2px solid;">PLN</td>
            </tr>*/
?>            
<?php

    }
} else {
    die (mysql_error());
}


	function  sprawdz_fakture($expense_id)  {		
		$query= "SELECT invoice_in_id  FROM coris_finances_invoices_in WHERE expense_id='$expense_id' ";
		$mysql_result = mysql_query($query);
		$ilosc= mysql_num_rows($mysql_result);
		if ($ilosc == 0){
			return NO;
		}else{ 			
			return YES. ($ilosc>1  ? '('.$ilosc.')' : '');
		}			
	}   
	
	function  sprawdz_platnosc($expense_id)  {		
		$query= "SELECT payment_confirmed FROM coris_finances_invoices_in WHERE expense_id='$expense_id' ";
	//	echo $query;
		$mysql_result = mysql_query($query);
		$ilosc= mysql_num_rows($mysql_result);
		if ($ilosc == 0){
				return '';
		}
		$res = true;
		while ( $row=mysql_fetch_array($mysql_result) ){
			if ($row['payment_confirmed']==0)
				$res = false;
		} 
		
		
		if ($res){
			return YES;
		}else{ 			
			return NO;
		}							
	}   
	
	
	function sprawdz_decyzje($expense_id){
	
	$query = "SELECT coris_assistance_cases_decisions_types.value FROM coris_assistance_cases_decisions,coris_assistance_cases_decisions_types  
	
		WHERE coris_assistance_cases_decisions.ID_expenses='$expense_id' AND coris_assistance_cases_decisions.active=1 
		AND coris_assistance_cases_decisions.type_id = coris_assistance_cases_decisions_types.type_id
		ORDER BY decision_id desc ";
	$mr = mysql_query($query);
	//echo $query;
	if (mysql_num_rows($mr) == 0){
			return false;			
	}else{
			$rr = mysql_fetch_array($mr);
			return $rr['value'];		
	}		

	}
?>
        </table>
       </form>		
        <br>
        <center><small><i><font color="#6699cc"><?= AS_CASD_TXTWARTSUM ?></font></i></small></center>
    </body>
</html>
