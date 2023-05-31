<?php include('include/include.php'); 

html_start('','');
?>

	<script language="JavaScript1.2">
	<!--
		function sortBy(x, y) {
			var pform = parent.document.getElementById('form1');
			parent.document.getElementById('step').value = 0;
					parent.document.getElementById('letter').value = "";
			if (x != y) {
				if (parent.document.getElementById('sort').value == x)
					parent.document.getElementById('sort').value = y;
				else
					parent.document.getElementById('sort').value = x;
				parent.SubmitSearch();
			} else {
				if (parent.document.getElementById('sort').value != x) {
					parent.document.getElementById('sort').value = x;
					parent.SubmitSearch();
				}
			}
		}

    	function openWindow(winId, id) {
    		var url = "AS_cases_details_old.php?case_id=" + id;
			var childwin = window.open(url, '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=830,height=830,left='+ (screen.availWidth - 750) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
			childwin.opener = parent;
		}
	//-->
	</script>  
    	<center>
        
<?php

$open_param = '';
$kolo_id = intval(getValue('kolo_id'));

$roszczenia_do_akceptacji=getValue('roszczenia_do_akceptacji');
$roszczenia_do_poprawy=getValue('roszczenia_do_poprawy');

$decyzje_do_uzupelnienia=getValue('decyzje_do_uzupelnienia');
$decyzje_do_drukowania=getValue('decyzje_do_drukowania');

$platnosci_do_wyslania=getValue('platnosci_do_wyslania');
$platnosci_wyslane=getValue('platnosci_wyslane');
$platnosci_oplacone_allianz=getValue('platnosci_oplacone_allianz');
$platnosci_do_wyplaty=getValue('platnosci_do_wyplaty');
$platnosci_wyplacone=getValue('platnosci_wyplacone');
$decyzja_rodzaj=getValue('decyzja_rodzaj');


{
	$year = ($_GET['year'] != "") ? $_GET['year'] : "";
	$paxDob = ($_GET['paxDob_y'] != "") ? "${_GET['paxDob_y']}-${_GET['paxDob_m']}-${_GET['paxDob_d']}" : "";
	$dateFrom = ($_GET['dateFrom_y'] != "") ? "${_GET['dateFrom_y']}-${_GET['dateFrom_m']}-${_GET['dateFrom_d']}" : "";
	$dateTo = ($_GET['dateTo_y'] != "") ? "${_GET['dateTo_y']}-${_GET['dateTo_m']}-${_GET['dateTo_d']}" : "";
	$eventDateFrom = ($_GET['eventDateFrom_y'] != "") ? "${_GET['eventDateFrom_y']}-${_GET['eventDateFrom_m']}-${_GET['eventDateFrom_d']}" : "";
	$eventDateTo = ($_GET['eventDateTo_y'] != "") ? "${_GET['eventDateTo_y']}-${_GET['eventDateTo_m']}-${_GET['eventDateTo_d']}" : "";

  		if ($_GET['dok_cat'] > 0 ){
  			if ($_GET['letter'] != "")
				$query = "SELECT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(coris_assistance_cases.date) AS date, watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation, status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete, status_settled, attention, attention2,marka_model,nr_rej,status_briefcase_found,liquidation 
					FROM coris_assistance_cases,coris_assistance_cases_interactions,coris_allianz_announce   
						WHERE coris_assistance_cases.case_id = coris_allianz_announce.case_id AND coris_assistance_cases.case_id=coris_assistance_cases_interactions.case_id AND coris_assistance_cases_interactions.documentcategory_id='".$_GET['dok_cat']."' AND ";//USE INDEX(paxsurname)
			else 
				$query = "SELECT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(coris_assistance_cases.date) AS date, watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation, status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete, status_settled, attention,attention2,marka_model,nr_rej,status_briefcase_found,liquidation 
					FROM coris_assistance_cases,coris_assistance_cases_interactions,coris_allianz_announce WHERE coris_assistance_cases.case_id = coris_allianz_announce.case_id AND coris_assistance_cases.case_id=coris_assistance_cases_interactions.case_id AND  coris_assistance_cases_interactions.documentcategory_id='".$_GET['dok_cat']."' AND ";
					
			$query2 = "SELECT count(*) FROM coris_assistance_cases,coris_assistance_cases_interactions,coris_allianz_announce WHERE coris_assistance_cases.case_id = coris_allianz_announce.case_id AND coris_assistance_cases.case_id=coris_assistance_cases_interactions.case_id AND  coris_assistance_cases_interactions.documentcategory_id='".$_GET['dok_cat']."' AND ";
  			
  		}else{
			if ($_GET['letter'] != "")
				$query = "SELECT coris_assistance_cases.case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date, watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation, status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete, status_settled, attention, attention2,marka_model,nr_rej,status_briefcase_found,liquidation 
				FROM coris_assistance_cases,coris_allianz_announce,coris_allianz_announce   WHERE coris_assistance_cases.case_id = coris_allianz_announce.case_id AND ";//USE INDEX(paxsurname)
			else 
				$query = "SELECT coris_assistance_cases.case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date, watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation, status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete, status_settled, attention,attention2,marka_model,nr_rej,status_briefcase_found,liquidation 
				FROM coris_assistance_cases,coris_allianz_announce  WHERE coris_assistance_cases.case_id = coris_allianz_announce.case_id AND ";
					
			$query2 = "SELECT count(*) FROM coris_assistance_cases,coris_allianz_announce WHERE coris_assistance_cases.case_id = coris_allianz_announce.case_id AND ";
  		}
  		
	
	
	
	if ($kolo_id>0 ) {
		$query .= " coris_allianz_announce.ID_kolo = '$kolo_id' AND";
		$query2 .= " coris_allianz_announce.ID_kolo = '$kolo_id' AND";
	}
	if ($_GET['paxName'] != '') {
		$query .= " paxname LIKE '%$_GET[paxName]%' AND";
		$query2 .= " paxname LIKE '%$_GET[paxName]%' AND";
	}
	if ($_GET['paxSurname'] != '') {
		$query .= " paxsurname LIKE '%$_GET[paxSurname]%' AND";
		$query2 .= " paxsurname LIKE '%$_GET[paxSurname]%' AND";
	}
	if ($_GET['policy'] != '') {
		$query .= " policy LIKE '%".getValue('policy')."%' AND";
		$query2 .= " policy LIKE '%".getValue('policy')."%' AND";
	}
	
	if ($roszczenia_do_akceptacji == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_claims.ID_case FROM coris_allianz_claims,coris_allianz_claims_details WHERE coris_allianz_claims.ID = coris_allianz_claims_details.ID_claims AND coris_allianz_claims_details.status2=1) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_claims.ID_case FROM coris_allianz_claims,coris_allianz_claims_details WHERE coris_allianz_claims.ID = coris_allianz_claims_details.ID_claims AND coris_allianz_claims_details.status2=1) AND ";
		$open_param .= 'mod=allianz_claims';
	}
	
	
	if ($roszczenia_do_poprawy == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_claims.ID_case FROM coris_allianz_claims,coris_allianz_claims_details WHERE coris_allianz_claims.ID = coris_allianz_claims_details.ID_claims AND coris_allianz_claims_details.status2=2) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_claims.ID_case FROM coris_allianz_claims,coris_allianz_claims_details WHERE coris_allianz_claims.ID = coris_allianz_claims_details.ID_claims AND coris_allianz_claims_details.status2=2) AND ";
		$open_param .= 'mod=allianz_claims';
	}
	
	
	if ($decyzja_rodzaj == 3 || $decyzja_rodzaj == 4) {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_claims.ID_case FROM coris_allianz_claims,coris_allianz_claims_details WHERE coris_allianz_claims.ID = coris_allianz_claims_details.ID_claims AND coris_allianz_claims_details.status='".$decyzja_rodzaj."') AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_claims.ID_case FROM coris_allianz_claims,coris_allianz_claims_details WHERE coris_allianz_claims.ID = coris_allianz_claims_details.ID_claims AND coris_allianz_claims_details.status='".$decyzja_rodzaj."') AND ";
		$open_param .= 'mod=allianz_claims';
	}
	
	
	if ($decyzje_do_uzupelnienia == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_decisions.ID_case FROM coris_allianz_decisions  WHERE coris_allianz_decisions .status=0) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_decisions.ID_case FROM coris_allianz_decisions  WHERE coris_allianz_decisions .status=0) AND ";
		
		
		$open_param .= 'mod=allianz_claims';
	}
	
	if ($decyzje_do_drukowania == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_decisions.ID_case FROM coris_allianz_decisions  WHERE coris_allianz_decisions .status=1) AND ";		
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_decisions.ID_case FROM coris_allianz_decisions  WHERE coris_allianz_decisions .status=1) AND ";		
		$open_param .= 'mod=allianz_claims';
	}
	
	
	if ($platnosci_do_wyslania == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=0) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=0) AND ";		
		$open_param .= 'mod=allianz_claims';
	}

	if ($platnosci_wyslane == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=1) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=1) AND ";		
		$open_param .= 'mod=allianz_claims';
	}
	
	
	if ($platnosci_oplacone_allianz == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=2) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=2) AND ";		
		$open_param .= 'mod=allianz_claims';
	}
	
	
	
	if ($platnosci_do_wyplaty == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=3) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=3) AND ";		
		$open_param .= 'mod=allianz_claims';
	}
	
	
	if ($platnosci_wyplacone == 'true') {
		$query .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=4) AND ";
		$query2 .= " coris_assistance_cases.case_id IN (SELECT coris_allianz_payment.ID_case FROM coris_allianz_payment  WHERE  coris_allianz_payment.status=4) AND ";		
		$open_param .= 'mod=allianz_claims';
	}
	
	
    if ($_GET['userId'] != "") {
    		if ($_GET['userRole']==1){ // redaktor
		        	$query .= " coris_assistance_cases.user_id = $_GET[userId] AND";
		        	$query2 .= " coris_assistance_cases.user_id = $_GET[userId] AND";
    		}else if ($_GET['userRole']==2){ // likwidator
    			   	$query .= " coris_assistance_cases.claim_handler_user_id   = $_GET[userId] AND";
		        	$query2 .= " coris_assistance_cases.claim_handler_user_id   = $_GET[userId] AND";
    		}
    }
	if ($year != "") {
		$query .= " year = '$year' AND";
		$query2 .= " year = '$year' AND";
	}
	if ($_GET['caseId'] != "") {
		$query .= " number = '".$_GET[caseId]."' AND";
		$query2 .= " number = '".$_GET[caseId]."' AND";
	}
   if ($_GET['client_id'] != "") {
		$query .= " client_id = '".$_GET[client_id]."' AND";
		$query2 .= " client_id = '".$_GET[client_id]."' AND";
	}
	
	if ($_GET['archive'] == "true") {
		$query .= " archive = 1 AND";
		$query2 .= " archive = 1 AND";
	}
	if (trim($_GET['city']) != '' ) {
		$query .= " city LIKE '%".trim($_GET['city'])."%' AND";
		$query2 .= " city LIKE '%".trim($_GET['city'])."%' AND";
	}
	
	
	
	if ($dateFrom != "" || $dateTo != "") {
		if ($dateFrom != "" && $dateTo == "") {
			$dateTo = date("Y-m-d");
		} else if ($dateFrom == "" && $dateTo != "") {
			$dateFrom = "0000-00-00";
		}
		$query .= " coris_assistance_cases.date BETWEEN '$dateFrom 00:00:00' AND '$dateTo 23:59:59' AND";
		$query2 .= " coris_assistance_cases.date BETWEEN '$dateFrom 00:00:00' AND '$dateTo 23:59:59' AND";
	}
	if ($eventDateFrom != "" || $eventDateTo != "") {
		if ($eventDateFrom != "" && $eventDateTo == "") {
			$eventDateTo = date("Y-m-d");
		} else if ($eventDateFrom == "" && $eventDateTo != "") {
			$eventDateFrom = "0000-00-00";
		}
		$query .= " eventdate BETWEEN '$eventDateFrom' AND '$eventDateTo' AND";
		$query2 .= " eventdate BETWEEN '$eventDateFrom' AND '$eventDateTo' AND";
	}
	
	if ($_GET['client_ref'] != '' ) {
		$query .= " coris_assistance_cases.client_ref LIKE '%".getValue('client_ref')."%' AND";
		$query2 .= " coris_assistance_cases.client_ref LIKE '%".getValue('client_ref')."%' AND";
	}

	
	
    $multiplier = 10;
	$amount = ($_GET['amount'] != 0) ? $_GET['amount'] : $multiplier;
	$step = ($_GET['step'] == "") ? 0 : $_GET['step'];
	$from = ($step != 0) ? ($step * $amount) : 0;

	

	
	if ( isset($_GET['new_alerts'])  ){		
			$query .= " active = 1 ORDER BY docdate LIMIT $from, $amount";
			$query2 .= " active = 1";		
	}if ( isset($_GET['new_documents'])  && $_GET['new_documents']=='true' && isset($_GET['new_documents_sort'])  && $_GET['new_documents_sort']=='true'){
		if ($_GET['dok_cat'] > 0 ){
			$query .= " active = 1 ORDER BY docdate LIMIT $from, $amount";
			$query2 .= " active = 1";			
		}else{
			$query .= " active = 1 ORDER BY docdate LIMIT $from, $amount";
			$query2 .= " active = 1";
		}
	}else if ($_GET['letter'] != "") {
		$query .= " paxsurname LIKE '%$_GET[letter]%' AND active = 1 ORDER BY paxsurname, year, number LIMIT $from, $amount";
		$query2 .= " paxsurname LIKE '%$_GET[letter]%' AND active = 1";
	} else if ($_GET['sort'] != "") {
		$query .= " active = 1 ORDER BY ";
		switch ($_GET['sort']) {
			case 1:
				$query .= " archive DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 2:
				$query .= " archive, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 3:
				$query .= " watch DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 4:
				$query .= " watch, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 5:
				$query .= " transport DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 6:
				$query .= " transport, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 7:
				$query .= " decease DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 8:
				$query .= " decease, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 9:
				$query .= " ambulatory DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 10:
				$query .= " hospitalization DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 11:
				$query .= " year DESC, number DESC LIMIT $from, $amount";
				break;
			case 12:
				$query .= " year, number LIMIT $from, $amount";
				break;
//			case 13:
//				$query .= "coris_assistance_cases.year desc, number desc limit $from, $amount";
//				break;
//			case 14:
//				$query .= "coris_assistance_cases.year desc, number desc limit $from, $amount";
//				break;
			case 15:
				$query .= " paxsurname, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 16:
				$query .= " paxsurname DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 17:
				$query .= " paxname, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 18:
				$query .= " paxname DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 19:
				$query .= " coris_assistance_cases.date DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 20:
				$query .= " coris_assistance_cases.date, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 21:
				$query .= " eventdate DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 22:
				$query .= " eventdate, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 23:
				$query .= " country_id, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 24:
				$query .= " country_id DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 25:
				$query .= " costless DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 26:
				$query .= " unhandled DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 27:
				$query .= " coris_assistance_cases.reclamation DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 28:
				$query .= " status_briefcase_found DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			case 29:
				$query .= " liquidation DESC, year DESC, number DESC LIMIT $from, $amount";
				break;
			}
	} else {
		$query .= " active = 1 ORDER BY year DESC, number DESC LIMIT $from, $amount";
		$query2 .= " active = 1";
	}
}

//echo '<hr>'.$query;
//echo '<hr>'.$query2;

$result = mysql_query($query);// or die(mysql_error());

if (!$result) echo "Error query: <br>".$query.'<br><br>'.mysql_error();

$num_rows = mysql_num_rows($result);
if (!$num_rows) {
?>
        <table cellpadding="0" width="100%" height="100%" cellspacing="1" bgcolor="#dddddd" border="0">
            <tr>
                <td align="center"><font color="#6699cc"><?= AS_CASES_MSG_BRREK ?></font></td>  
            </tr>
        </table>
<?php
} else {
?>
        <table cellpadding="0"  cellspacing="1" bgcolor="#dddddd" border="0" style="border-bottom: #6699cc 1px solid;">
            <tr>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(29,29);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(28,28);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(1,1);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(3,3);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(5,5);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(7,7);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(9,9);">&nbsp;</td>
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(10,10);">&nbsp;</td>
				<!-- NOWE -->
				<!-- tu bêdzie bez kosztów //-->
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(25,25);">&nbsp;</td>
				<!-- tu bêdzie bez rycza³tu //-->
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(26,26);">&nbsp;</td>
				<!-- tu bêdzie reklamacja //-->
                <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(27,27);">&nbsp;</td>
                <td width="140" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(11,12);"><font color="#ffffff"><small><?= AS_CASES_NR ?></small></font></td>
                <td width="100" bgcolor="#6699cc" align="center"><font color="#ffffff"><small><?= AS_CASES_STATUS ?></small></font></td>
            	<td width="160" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(15,16);"><font color="#ffffff"><small><?= AS_CASES_NAZWMARKAMOD ?></small></font></td>
                <td width="120" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(17,18);"><font color="#ffffff"><small><?= AS_CASES_IMNRREJ ?></small></font></td>
                <td width="80" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(21,22);"><font color="#ffffff" title="Data zdarzenia"><small><?= AS_CASES_ZDARZ ?></small></font></td>
                <td width="80" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(19,20);"><font color="#ffffff" title="Data otwarcia"><small><?= AS_CASES_OTW ?></small></font></td>
                <td width="40" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(23,24);"><font color="#ffffff"><small><?= COUNTRY ?></small></font></td>
            </tr>
<?php
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
?>
			<tr height="24" 
			<?php
					if ( isset($_GET['new_documents'])  && $_GET['new_documents']=='true')
						echo 'title="'.$row['docdate'].'"';
				?>
			bgcolor="<?php 
			if ($row['type_id']==1 || $row['type_id']==5)
				echo ($i % 2) ? "#FFFF00" : "#FFFF99" ;
			else
				echo ($i % 2) ? "#e9e9e9" : "#dddddd" ;
				
			?>" onmouseover="this.bgColor='#ced9e2';" onmouseout="this.bgColor='<?php
			 if ($row['type_id']==1 || $row['type_id']==5)
				echo ($i % 2) ? "#FFFF00" : "#FFFF99" ;
			else
				echo ($i % 2) ? "#e9e9e9" : "#dddddd" ;
				
			 
			 ?>';" style="<?php 
			 
			 	echo ($row['attention']==1) ? "color: red;" : "" ;
			 	echo ($row['attention2']==1) ? "color: #6699cc" : "" ;
			 //	echo ($row['attention2']==1) ? "color: black;" : "" ;
			 
			 ?>; cursor: pointer;" onclick="open_case('<?php echo $row['case_id'].'&'.$open_param; ?>','casewindow<?php echo $row['case_id'] ?>');">
                <td align="center"><?php echo ($row['liquidation']) ? "<font style=\"font-size: 12pt;\" color=\"#c0c0c0\" >L</font>" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['status_briefcase_found']) ? "<font style=\"font-size: 12pt;\" color=\"#c0c0c0\" >".($row['type_id']==1 ? 'F' : 'T')."</font>" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['archive']) ? "<img src=\"img/archiwum.gif\" border=\"0\" >" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['watch']) ? "<font style=\"font-size: 12pt;\" color=\"#c0c0c0\" face=\"webdings\">N</font>" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['transport']) ? "<img  src=\"img/transport.gif\" border=\"0\" >" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['decease']) ? "<img src=\"img/zgon.gif\" border=\"0\" >" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['ambulatory']) ? "<font style=\"font-size: 10pt;\" color=\"#c0c0c0\"><b>A</b></font>" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['hospitalization']) ? "<font style=\"font-size: 10pt;\" color=\"#c0c0c0\"><b>H</b></font>" : "&nbsp;" ?></td>
				<!-- NOWE //-->
                <td align="center"><?php echo ($row['costless']) ? "<img  src=\"img/bez-kosztow.gif\" border=\"0\" >" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['unhandled']) ? "<img  src=\"img/bez-ryczaltu.gif\" border=\"0\" >" : "&nbsp;" ?></td>
                <td align="center"><?php echo ($row['reclamation']) ? "<font color=\"#c0c0c0\" style=\"font-size: 10pt;\">R</font>" : "&nbsp;" ?></td>
                <td align="left"><font color="#6699cc"><small><?php echo $row['number'] ?>/<?php echo $row['year'] ?>/<?php echo $row['type_id'] ?>/<?php echo $row['client_id'] ?></small></font></td> 
				<td align="center"> 
					<table cellpadding="1" cellspacing="1" border="0" width="80">
						<tr height="15" align="center">
							<td bgcolor="<?php echo ($row['status_client_notified']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_ZGLOSZSZK ?>" style="border-left: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_policy_confirmed']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_POTWWAZNPOL ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_documentation']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DOK ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_decision']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DEC ?>" style="border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_assist_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZASSZAK ?>" style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_send']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_WYSLAC ?>" style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_account_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZRACHZAK ?>" style="border: #999999 1px solid">&nbsp;</td>
							<td bgcolor="<?php echo ($row['status_settled']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_SPRROZL ?>" style="border: #999999 1px solid">&nbsp;</td>
						</tr>
					</table>
				</td>
                <td align="left"><small><?php 
                 if ($row['type_id']==1 || $row['type_id']==5)
                	echo (strlen($row['marka_model']) < 16) ? $row['marka_model'] : substr($row['marka_model'], 0, 15) . "..."; 
                 else
                 	echo (strlen($row['paxsurname']) < 16) ? $row['paxsurname'] : substr($row['paxsurname'], 0, 15) . "..."; 
                
                ?></small></td>
                <td align="left"><small><?php 
                 if ($row['type_id']==1 || $row['type_id']==5)
                 	echo (strlen($row['nr_rej']) < 11) ? $row['nr_rej'] : substr($row['nr_rej'], 0, 10) . "..."; 
                 else
                	echo (strlen($row['paxname']) < 11) ? $row['paxname'] : substr($row['paxname'], 0, 10) . "..."; 
                	?></small></td>
                <td align="center"><small><?php echo $row['eventdate'] ?></small></td>
                <td align="center" nowrap><small><?php echo $row['date'] ?></small></td>
                <td align="center"><small><?php echo $row['country_id'] ?></small></td>
            </tr>
<?php
        $i++;
    }
?>
        </table>
<?php
}

$num_rows = mysql_num_rows($result);
$num_rows =  ($num_rows>0) ? $num_rows : 0; 
if (isset($amount)) {
	if ($num_rows < $amount) {
		echo "<script language=\"JavaScript\">if (parent.document.getElementById('end').value == 0) parent.document.getElementById('end').value = 1;</script>";
	}
}
echo "<script language=\"JavaScript\">parent.document.getElementById('count').value = $num_rows;</script>";
if ($result = mysql_query($query2)) {
    if ($row = mysql_fetch_array($result)) {
        echo "<script language=\"JavaScript\">parent.document.getElementById('total').value = $row[0];</script>";
    }
}
?>
        </center>  
<?php
html_stop2();
?>