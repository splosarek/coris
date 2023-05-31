<?php include('include/include.php');

html_start('', '');
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
            var childwin = window.open(url, '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=830,height=830,left=' + (screen.availWidth - 750) / 2 + ',top=' + ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
            childwin.opener = parent;
        }

        //-->
    </script>
    <center>

        <?php


        // ustawiam tutaj, bo dotyczy to wszystkich zapytan, a poniewaz maja rozna konstrukcje to wrzucam do kazdego zapytania jako zmienna.
        // jak pl to pokazac to co wybrane
        // jak inne to tylko dla danego oddzia�u
        $coris_branch_id = getValue('coris_branch_id');

        if ($_SESSION['coris_branch'] == 1) {
            $branchFilter = '';

            if ($coris_branch_id != '') {
                $branchFilter = " AND coris_branch_id = '$coris_branch_id' ";
            }
        } else if ($_SESSION['coris_branch'] == 2) {
//	echo " JEST ";
            if ($coris_branch_id == 2 || $coris_branch_id == 3) {
                $branchFilter = " AND coris_branch_id = '$coris_branch_id' ";
            } else {
                $branchFilter = " AND (coris_branch_id = '2' OR coris_branch_id = '3' ) ";
            }

        } else {
            $coris_branch_id = intval($_SESSION['coris_branch']);
            $branchFilter = " AND coris_branch_id = '$coris_branch_id' ";
        }

        if (isset($_GET['new_alerts'])) {

            $query = "SELECT DISTINCT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id, eventdate,
                                      paxname, paxsurname, country_id, status_send, DATE(coris_assistance_cases.date) AS date, watch,
                                      archive, transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice,
                                      status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete,
                                      status_account_complete, status_settled, attention,attention2,marka_model,nr_rej,
                                      (SELECT MIN(date)
                                         FROM coris_assistance_cases_alerts
                                        WHERE coris_assistance_cases.case_id=coris_assistance_cases_alerts.case_id
                                          AND coris_assistance_cases_alerts.new=1
                                      ) As docdate,
                                      status_briefcase_found, liquidation, cb.name AS coris_branch, coris_branch_id
    			                 FROM coris_assistance_cases_alerts, coris_assistance_cases
                            LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
                                WHERE coris_assistance_cases.case_id=coris_assistance_cases_alerts.case_id
                                  AND coris_assistance_cases_alerts.new=1
                                  $branchFilter
                                  ";//USE INDEX(paxsurname)

            $query2 = "SELECT count(DISTINCT coris_assistance_cases.case_id)
                 FROM coris_assistance_cases, coris_assistance_cases_alerts
                WHERE coris_assistance_cases.case_id=coris_assistance_cases_alerts.case_id
                  AND coris_assistance_cases_alerts.new=1
                  $branchFilter
                  ";
        } else if (isset($_GET['new_ext_note'])) {

            $query = "SELECT DISTINCT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id, eventdate, paxname,
                                          paxsurname, country_id, DATE(coris_assistance_cases.date) AS date, watch, archive, transport, decease,
                                          ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice,
                                          status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete,
                                          status_account_complete, status_settled, attention,attention2,marka_model,nr_rej,
                                          (SELECT MIN(date)
                                             FROM store_interaction
                                            WHERE coris_assistance_cases.case_id=store_interaction.ID_case
                                              AND store_interaction.new=1
                                          ) As docdate,
                                          status_briefcase_found, liquidation, status_send, cb.name AS coris_branch, coris_branch_id
                                     FROM store_interaction, coris_assistance_cases
                                LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
                                    WHERE coris_assistance_cases.case_id=store_interaction.ID_case
                                      AND store_interaction.new = 1
                                      AND store_interaction.external = 1
                                      AND store_interaction.ID_document_type=2
                                      $branchFilter
                                      ";//USE INDEX(paxsurname)
            $query2 = "SELECT count(DISTINCT coris_assistance_cases.case_id)
    		             FROM coris_assistance_cases, store_interaction
    		            WHERE coris_assistance_cases.case_id=store_interaction.ID_case
    		              AND store_interaction.new=1
    		              AND store_interaction.external=1
    		              AND store_interaction.ID_document_type=2
    		              $branchFilter
    		              ";

        } else if (isset($_GET['claims_stat'])) {
            $query = "SELECT case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date, watch, archive,
    	                 transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice,
                         status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete,
                         status_send, status_account_complete, status_settled, attention, attention2, marka_model, nr_rej, status_briefcase_found,
                         liquidation, cb.name AS coris_branch, coris_branch_id
                    FROM coris_assistance_cases
               LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
                   WHERE active = 1
    	             AND case_id IN (SELECT distinct ID_case FROM coris_assistance_cases_claims_pay cp
    	                              WHERE status IN (" . $_GET['claims_stat'] . ") )
    	              $branchFilter
    	        ORDER BY year DESC, number ";
            $query2 = "SELECT count(*)
    	             FROM coris_assistance_cases
    	            WHERE active = 1
    	              AND case_id IN (SELECT distinct ID_case
    	                                FROM coris_assistance_cases_claims_pay cp
    	                               WHERE status IN (" . $_GET['claims_stat'] . ")  )
                       $branchFilter ";
        } else if (!isset($_GET['action'])) {
            $var1 = '';
            $var2 = '';

            /*	if ($_SESSION['new_user']==1){
                        $var1 = " AND coris_assistance_cases.`date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) ";
                        $var2 = "  WHERE coris_assistance_cases.`date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) ";	
                }
                */

            $query = "SELECT case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date, watch,
    	                 archive, transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice,
    	                 status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete,
    	                 status_send, status_account_complete, status_settled, attention,attention2,
    	                 marka_model,nr_rej,status_briefcase_found, liquidation, cb.name AS coris_branch, coris_branch_id
                   FROM coris_assistance_cases
              LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
                  WHERE active = 1
                    AND archive = 0
                    $var1
                    $branchFilter
               ORDER BY year DESC, number DESC LIMIT 10";

            $query2 = "SELECT count(*)
    	             FROM coris_assistance_cases
    	                $var2
    	                $branchFilter ";


        } else {
            $year = ($_GET['year'] != "") ? $_GET['year'] : "";
            $paxDob = ($_GET['paxDob_y'] != "") ? "${_GET['paxDob_y']}-${_GET['paxDob_m']}-${_GET['paxDob_d']}" : "";
            $dateFrom = ($_GET['dateFrom_y'] != "") ? "${_GET['dateFrom_y']}-${_GET['dateFrom_m']}-${_GET['dateFrom_d']}" : "";
            $dateTo = ($_GET['dateTo_y'] != "") ? "${_GET['dateTo_y']}-${_GET['dateTo_m']}-${_GET['dateTo_d']}" : "";
            $eventDateFrom = ($_GET['eventDateFrom_y'] != "") ? "${_GET['eventDateFrom_y']}-${_GET['eventDateFrom_m']}-${_GET['eventDateFrom_d']}" : "";
            $eventDateTo = ($_GET['eventDateTo_y'] != "") ? "${_GET['eventDateTo_y']}-${_GET['eventDateTo_m']}-${_GET['eventDateTo_d']}" : "";

            $liquidationStartFrom = ($_GET['liquidationStartDateFrom_y'] != "") ? "${_GET['liquidationStartDateFrom_y']}-${_GET['liquidationStartDateFrom_m']}-${_GET['liquidationStartDateFrom_d']}" : "";
            $liquidationStartTo = ($_GET['liquidationStartDateTo_y'] != "") ? "${_GET['liquidationStartDateTo_y']}-${_GET['liquidationStartDateTo_m']}-${_GET['liquidationStartDateTo_d']}" : "";


            $liquidationStopFrom = ($_GET['liquidationStopDateFrom_y'] != "") ? "${_GET['liquidationStopDateFrom_y']}-${_GET['liquidationStopDateFrom_m']}-${_GET['liquidationStopDateFrom_d']}" : "";
            $liquidationStopTo = ($_GET['liquidationStopDateTo_y'] != "") ? "${_GET['liquidationStopDateTo_y']}-${_GET['liquidationStopDateTo_m']}-${_GET['liquidationStopDateTo_d']}" : "";


            if (isset($_GET['new_documents']) && $_GET['new_documents'] == 'true') {

                if ($_GET['dok_cat'] > 0) {
                    $query = "SELECT DISTINCT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id,
  			                          eventdate, paxname, paxsurname, country_id, DATE(coris_assistance_cases.date) AS date,
  			                          watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled,
  			                          coris_assistance_cases.reclamation,coris_assistance_cases.backoffice, status_client_notified, status_policy_confirmed,
  			                          status_documentation, status_decision, status_assist_complete, status_account_complete,
  			                          status_settled, attention, attention2, marka_model, nr_rej,
  			                          (SELECT MIN(date)
  			                             FROM store_interaction
  			                            WHERE coris_assistance_cases.case_id=store_interaction.ID_case
  			                              AND store_interaction.new=1
  			                          )  As docdate,
  			                          status_briefcase_found, liquidation, status_send, cb.name AS coris_branch, coris_branch_id
    		                     FROM store_interaction, coris_assistance_cases
    		                LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
    		                    WHERE coris_assistance_cases.case_id=store_interaction.ID_case
    		                      AND store_interaction.new=1
    		                      AND store_interaction.ID_category='" . $_GET['dok_cat'] . "'
    		                       $branchFilter
    		                      AND ";//USE INDEX(paxsurname)
                    $query2 = "SELECT count(DISTINCT coris_assistance_cases.case_id)
    		             FROM coris_assistance_cases,store_interaction
    		            WHERE coris_assistance_cases.case_id=store_interaction.ID_Case
    		              AND store_interaction.new=1
    		              AND store_interaction.ID_category='" . $_GET['dok_cat'] . "'
    		               $branchFilter
    		              AND ";
                } else {
                    $query = "SELECT DISTINCT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id,
                                      eventdate, paxname, paxsurname, country_id, DATE(coris_assistance_cases.date) AS date, watch, archive,
                                      transport, decease, ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice,
                                      status_client_notified, status_policy_confirmed, status_documentation, status_decision, status_assist_complete,
                                      status_account_complete, status_settled, attention, attention2, marka_model, nr_rej,
                                        (SELECT MIN(date)
                                           FROM store_interaction
                                          WHERE coris_assistance_cases.case_id=store_interaction.ID_case
                                            AND store_interaction.new=1) As docdate,
                                      status_briefcase_found, liquidation, status_send, cb.name AS coris_branch, coris_branch_id
    		                   FROM store_interaction, coris_assistance_cases
    		              LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
    		                  WHERE coris_assistance_cases.case_id=store_interaction.ID_case
    		                    AND store_interaction.new=1
    		                     $branchFilter
    		                    AND ";//USE INDEX(paxsurname)
                    $query2 = "SELECT count(DISTINCT coris_assistance_cases.case_id)
    		             FROM coris_assistance_cases,store_interaction
    		            WHERE coris_assistance_cases.case_id=store_interaction.ID_case
    		              AND store_interaction.new=1
    		                $branchFilter
    		              AND";
                }
            } else {

                if ($_GET['dok_cat'] > 0) {
                    if ($_GET['letter'] != "") {
                        $query = "SELECT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id, eventdate, paxname,
				                 paxsurname, country_id, DATE(coris_assistance_cases.date) AS date, watch, archive, transport, decease, ambulatory,
				                 hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice, status_client_notified,
				                 status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete,
				                 status_settled, attention, attention2,marka_model,nr_rej,status_briefcase_found,liquidation,
				                 status_send, cb.name AS coris_branch, coris_branch_id
					        FROM store_interaction, coris_assistance_cases
					   LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
					       WHERE coris_assistance_cases.case_id=store_interaction.ID_case
					         AND store_interaction.ID_category='" . $_GET['dok_cat'] . "'
					         $branchFilter
					         AND ";//USE INDEX(paxsurname)
                    } else {
                        $query = "SELECT coris_assistance_cases.case_id, number, year, coris_assistance_cases.type_id, client_id, eventdate,
				                 paxname, paxsurname, country_id, DATE(coris_assistance_cases.date) AS date, watch, archive, transport, decease,
				                 ambulatory, hospitalization, costless, unhandled, coris_assistance_cases.reclamation,coris_assistance_cases.backoffice, status_client_notified,
				                 status_policy_confirmed, status_documentation, status_decision, status_assist_complete, status_account_complete,
				                 status_settled, attention,attention2,marka_model,nr_rej,status_briefcase_found,liquidation,
				                 status_send, cb.name AS coris_branch, coris_branch_id
					        FROM store_interaction, coris_assistance_cases
					   LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
					       WHERE coris_assistance_cases.case_id=store_interaction.ID_case
					         $branchFilter
					         AND store_interaction.ID_category='" . $_GET['dok_cat'] . "' AND ";

                        $query2 = "SELECT count(*)
			             FROM coris_assistance_cases, store_interaction
			            WHERE coris_assistance_cases.case_id=store_interaction.ID_case
			              AND store_interaction.ID_category='" . $_GET['dok_cat'] . "'
			               $branchFilter
			              AND ";
                    }
                } else {
                    if ($_GET['letter'] != "") {
                        $query = "SELECT case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date,
				                 watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled,
				                 coris_assistance_cases.reclamation,coris_assistance_cases.backoffice, status_client_notified, status_policy_confirmed, status_documentation,
				                 status_decision, status_assist_complete, status_account_complete, status_settled, attention, attention2,
				                 marka_model, nr_rej, status_briefcase_found, liquidation, status_send, cb.name AS coris_branch, coris_branch_id
				            FROM coris_assistance_cases
				       LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
				           WHERE 1=1
				             $branchFilter ";//USE INDEX(paxsurname)
                    } else {
                        $query = "SELECT case_id, number, year, type_id, client_id, eventdate, paxname, paxsurname, country_id, DATE(date) AS date,
				                 watch, archive, transport, decease, ambulatory, hospitalization, costless, unhandled,
				                 coris_assistance_cases.reclamation,coris_assistance_cases.backoffice, status_client_notified, status_policy_confirmed, status_documentation,
				                 status_decision, status_assist_complete, status_account_complete, status_settled, attention, attention2,
				                 marka_model, nr_rej, status_briefcase_found, liquidation, status_send, cb.name AS coris_branch, coris_branch_id
                            FROM coris_assistance_cases
                       LEFT JOIN coris_branch cb ON cb.ID=coris_assistance_cases.coris_branch_id
                            WHERE 1=1
                              $branchFilter
                              AND ";
                    }
                    $query2 = "SELECT count(*)
			             FROM coris_assistance_cases
			             WHERE 1=1
			               $branchFilter
			               AND ";
                }
            }


            if ($_GET['beneficjent'] == 'true') {
                $paxName = getValue('paxName');
                $paxSurname = getValue('paxSurname');

                if ($paxName != '' || $paxSurname != '') {

                    $var = '';
                    if ($paxName != '') {
                        $var .= "  AND ( benName LIKE '%" . $paxName . "%' ) ";
                    }

                    if ($paxSurname != '') {
                        $var .= "  AND ( benSurname LIKE '%" . $paxSurname . "%' ) ";
                    }

                    $query .= " case_id IN (  SELECT case_id FROM coris_assistance_cases_details WHERE 1=1 $var ) AND";
                    $query2 .= " case_id IN (  SELECT case_id FROM coris_assistance_cases_details WHERE 1=1 $var ) AND";
                }
            } else {
                if ($_GET['paxName'] != '') {
                    $query .= " paxname LIKE '%$_GET[paxName]%' AND";
                    $query2 .= " paxname LIKE '%$_GET[paxName]%' AND";
                }
                if ($_GET['paxSurname'] != '') {
                    $query .= " paxsurname LIKE '%$_GET[paxSurname]%' AND";
                    $query2 .= " paxsurname LIKE '%$_GET[paxSurname]%' AND";
                }
            }
            if ($_GET['policy'] != '') {
                $query .= " policy LIKE '%" . getValue('policy') . "%' AND";
                $query2 .= " policy LIKE '%" . getValue('policy') . "%' AND";
            }
            if ($_GET['policy_series'] != '') {
                $query .= " policy_series LIKE '%" . getValue('policy_series') . "%' AND";
                $query2 .= " policy_series LIKE '%" . getValue('policy_series') . "%' AND";
            }
            if ($_GET['country'] != '') {
                $query .= " country_id = '$_GET[country]' AND";
                $query2 .= " country_id = '$_GET[country]' AND";
            }
            if ($_GET['userId'] != "") {
                if ($_GET['userRole'] == 1) { // redaktor
                    $query .= " coris_assistance_cases.user_id = $_GET[userId] AND";
                    $query2 .= " coris_assistance_cases.user_id = $_GET[userId] AND";
                } else if ($_GET['userRole'] == 2) { // likwidator
                    $query .= " coris_assistance_cases.claim_handler_user_id   = $_GET[userId] AND";
                    $query2 .= " coris_assistance_cases.claim_handler_user_id   = $_GET[userId] AND";
                } else if ($_GET['userRole'] == 3) { // osblugujacy
                    $query .= " coris_assistance_cases.operating_user_id   = $_GET[userId] AND";
                    $query2 .= " coris_assistance_cases.operating_user_id   = $_GET[userId] AND";
                }
            }
            if ($year != "") {
                $query .= " year = '$year' AND";
                $query2 .= " year = '$year' AND";
            }
            if ($_GET['caseId'] != "") {
                $query .= " number = '" . $_GET[caseId] . "' AND";
                $query2 .= " number = '" . $_GET[caseId] . "' AND";
            }
            if ($_GET['client_id'] != "") {
                $query .= " client_id = '" . $_GET[client_id] . "' AND";
                $query2 .= " client_id = '" . $_GET[client_id] . "' AND";
            }
            if ($_GET['attention'] == "true") {
                $query .= " attention = 1 AND";
                $query2 .= " attention= 1 AND";
            }

            if ($_GET['attention2'] == "true") {
                $query .= " attention2 = 1 AND";
                $query2 .= " attention2= 1 AND";
            }

            if ($_GET['case_status'] == 1) {
                $query .= " archive = 1 AND";
                $query2 .= " archive = 1 AND";
            } else if ($_GET['case_status'] == 0) {  // JEZELI NIE ZAZNACZONE ARCHIWUM, POKAZ SPRAWY BIEZACE
                $query .= " archive = 0 AND";
                $query2 .= " archive = 0 AND";
            } else {

            }

            $case_status_completed_operationally = getValue('case_status_completed_operationally');

            if ($case_status_completed_operationally == 1) { //zako�czone operacyjnie
                $query .= " status_assist_complete=1 AND";
                $query2 .= " status_assist_complete=1 AND";
            }

            if ($case_status_completed_operationally == 2) { //nie zako�czone operacyjnie
                $query .= " status_assist_complete=0 AND";
                $query2 .= " status_assist_complete=0 AND";
            }

            $case_status_liqidation = getValue('case_status_liqidation');
            if ($case_status_liqidation == 1) { //wszystkie likwidacyjne
                $query .= " liquidation=1 AND";
                $query2 .= " liquidation=1 AND";
            }
            if ($case_status_liqidation == 2) { //likwidacja niezako�czona
                $query .= " liquidation=1 AND liquidation_stop=0 AND ";
                $query2 .= " liquidation=1 AND liquidation_stop=0 AND ";
            }
            if ($case_status_liqidation == 3) { //likwidacja zako�czona
                $query .= " liquidation=1 AND liquidation_stop=1 AND ";
                $query2 .= " liquidation=1 AND liquidation_stop=1 AND ";
            }

            if ($case_status_liqidation == 4) { //wszystkie back-office
                $query .= " backoffice=1 AND";
                $query2 .= " backoffice=1 AND";
            }
            if ($case_status_liqidation == 5) { //back-office niezako�czona
                $query .= " backoffice=1 AND backoffice_stop=0 AND ";
                $query2 .= " backoffice=1 AND backoffice_stop=0 AND ";
            }
            if ($case_status_liqidation == 6) { //back-office zako�czona
                $query .= " backoffice=1 AND backoffice_stop=1 AND ";
                $query2 .= " backoffice=1 AND backoffice_stop=1 AND ";
            }

            if (isset($_GET['watch']) && $_GET['watch'] == "true") {
                $query .= " watch = 1 AND";
                $query2 .= " watch = 1 AND";
            }
            if (isset($_GET['transport']) && $_GET['transport'] == "true") {
                $query .= " transport = 1 AND";
                $query2 .= " transport = 1 AND";
            }
            if (isset($_GET['decease']) && $_GET['decease'] == "true") {
                $query .= " decease = 1 AND";
                $query2 .= " decease = 1 AND";
            }
            if (isset($_GET['ambulatory']) && $_GET['ambulatory'] == "true") {
                $query .= " ambulatory = 1 AND";
                $query2 .= " ambulatory = 1 AND";
            }
            if (isset($_GET['hospitalization']) && $_GET['hospitalization'] == "true") {
                $query .= " hospitalization = 1 AND";
                $query2 .= " hospitalization = 1 AND";
            }
            // NOWE
            if (isset($_GET['costless']) && $_GET['costless'] == "true") {
                $query .= " costless = 1 AND";
                $query2 .= " costless = 1 AND";
            }
            if (isset($_GET['unhandled']) && $_GET['unhandled'] == "true") {
                $query .= " unhandled = 1 AND";
                $query2 .= " unhandled = 1 AND";
            }
            if (isset($_GET['reclamation']) && $_GET['reclamation'] == "true") {
                $query .= " coris_assistance_cases.reclamation = 1 AND";
                $query2 .= " coris_assistance_cases.reclamation = 1 AND";
            }
            if (isset($_GET['fraud']) && $_GET['fraud'] == "true") {
                $query .= " coris_assistance_cases.fraud= 1 AND";
                $query2 .= " coris_assistance_cases.fraud = 1 AND";
            }
            if (isset($_GET['appeal']) && $_GET['appeal'] == "true") {
                $query .= " coris_assistance_cases.appeal= 1 AND";
                $query2 .= " coris_assistance_cases.appeal = 1 AND";
            }
            if (isset($_GET['taxi']) && $_GET['taxi'] == "true") {
                $query .= " coris_assistance_cases.taxi = 1 AND";
                $query2 .= " coris_assistance_cases.taxi = 1 AND";
            }

            if (isset($_GET['backoffice']) && $_GET['backoffice'] == "true") {
                $query .= " coris_assistance_cases.backoffice= 1 AND";
                $query2 .= " coris_assistance_cases.backoffice = 1 AND";
            }

            if (isset($_GET['city']) && trim($_GET['city']) != '') {
                $query .= " city LIKE '%" . trim($_GET['city']) . "%' AND";
                $query2 .= " city LIKE '%" . trim($_GET['city']) . "%' AND";
            }

            if (isset($_GET['holowanie']) && $_GET['holowanie'] == "true") {
                $query .= " holowanie = 1 AND";
                $query2 .= " holowanie = 1 AND";
            }

            if (getValue('liquidation') == "true") {
                $query .= " liquidation = 1 AND";
                $query2 .= " liquidation = 1 AND";
            }

            if ($_GET['wynajem_samochodu'] == "true") {
                $query .= " wynajem_samochodu = 1 AND";
                $query2 .= " wynajem_samochodu = 1 AND";
            }
            if ($_GET['naprawa_na_miejscu'] == "true") {
                $query .= " naprawa_na_miejscu = 1 AND";
                $query2 .= " naprawa_na_miejscu = 1 AND";
            }

            if ($_GET['case_type'] > 0) {
                $query .= " coris_assistance_cases.type_id = '" . $_GET['case_type'] . "' AND";
                $query2 .= " coris_assistance_cases.type_id = '" . $_GET['case_type'] . "' AND";
            }

            if ($_GET['marka_model'] != '') {
                $query .= " marka_model LIKE '%" . $_GET['marka_model'] . "%' AND";
                $query2 .= " marka_model LIKE '%" . $_GET['marka_model'] . "%' AND";
            }
            if ($_GET['nr_rej'] != '') {
                $query .= " nr_rej LIKE '%" . $_GET['nr_rej'] . "%' AND";
                $query2 .= " nr_rej LIKE '%" . $_GET['nr_rej'] . "%' AND";
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
            if ($paxDob != "") {
                $query .= " paxdob LIKE '$paxDob' AND";
                $query2 .= " paxdob LIKE '$paxDob' AND";
            }

            if ($_GET['client_ref'] != '') {
                $query .= " coris_assistance_cases.client_ref LIKE '%" . getValue('client_ref') . "%' AND";
                $query2 .= " coris_assistance_cases.client_ref LIKE '%" . getValue('client_ref') . "%' AND";
            }


            $multiplier = 10;
            $amount = ($_GET['amount'] != 0) ? $_GET['amount'] : $multiplier;
            $step = ($_GET['step'] == "") ? 0 : $_GET['step'];
            $from = ($step != 0) ? ($step * $amount) : 0;


            /*if ($_SESSION['new_user']==1){
                $query .= " coris_assistance_cases.`date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) AND ";
                $query2 .= " coris_assistance_cases.`date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) AND ";
                
            }*/
//signalowe 
            if ($_GET['signal_ready_export'] == 'true') {
                $query .= " client_id=7592 AND coris_assistance_cases.case_id IN (SELECT coris_assistance_cases_announce.case_id FROM coris_assistance_cases_announce  WHERE status=1 AND signal_status=0 ) AND";
                $query2 .= " client_id=7592 AND coris_assistance_cases.case_id IN (SELECT coris_assistance_cases_announce.case_id FROM coris_assistance_cases_announce  WHERE status=1 AND signal_status=0 )  AND";
            }

            if ($_GET['signal_export'] == 'true') {
                $query .= " client_id=7592 AND coris_assistance_cases.case_id IN (SELECT coris_assistance_cases_announce.case_id FROM coris_assistance_cases_announce  WHERE  signal_status=1  ) AND";
                $query2 .= " client_id=7592 AND coris_assistance_cases.case_id IN (SELECT coris_assistance_cases_announce.case_id FROM coris_assistance_cases_announce  WHERE signal_status=1 ) AND";
            }

            if ($_GET['signal_nexport'] == 'true') {
                $query .= " client_id=7592 AND coris_assistance_cases.`date`>='2008-05-01' AND coris_assistance_cases.case_id NOT IN (SELECT coris_assistance_cases_announce.case_id FROM coris_assistance_cases_announce  WHERE  signal_status=1  )  AND";
                $query2 .= " client_id=7592 AND coris_assistance_cases.`date`>='2008-05-01' AND coris_assistance_cases.case_id NOT IN (SELECT coris_assistance_cases_announce.case_id FROM coris_assistance_cases_announce  WHERE  signal_status=1  ) AND";
            }


            if ($liquidationStartFrom != "" || $liquidationStartTo != "") {
                if ($liquidationStartFrom != "" && $liquidationStartTo == "") {
                    $liquidationStartTo = date("Y-m-d");
                } else if ($liquidationStartFrom == "" && $liquidationStartTo != "") {
                    $liquidationStartFrom = "0000-00-00";
                }
                $query .= " liquidation=1 AND liquidation_date BETWEEN '$liquidationStartFrom' AND '$liquidationStartTo' AND";
                $query2 .= " liquidation=1 AND liquidation_date BETWEEN '$liquidationStartFrom' AND '$liquidationStartTo' AND";
            }

            if ($liquidationStopFrom != "" || $liquidationStopTo != "") {
                if ($liquidationStopFrom != "" && $liquidationStopTo == "") {
                    $liquidationStopTo = date("Y-m-d");
                } else if ($liquidationStopFrom == "" && $liquidationStopTo != "") {
                    $liquidationStopFrom = "0000-00-00";
                }
                $query .= " liquidation_stop=1 AND liquidation_stop_date BETWEEN '$liquidationStopFrom' AND '$liquidationStopTo' AND";
                $query2 .= " liquidation_stop=1 AND liquidation_stop_date BETWEEN '$liquidationStopFrom' AND '$liquidationStopTo' AND";
            }


            if (isset($_GET['new_alerts'])) {
                $query .= " active = 1 ORDER BY docdate LIMIT $from, $amount";
                $query2 .= " active = 1";
            }
            if (isset($_GET['new_documents']) && $_GET['new_documents'] == 'true' && isset($_GET['new_documents_sort']) && $_GET['new_documents_sort'] == 'true') {
                if ($_GET['dok_cat'] > 0) {
                    $query .= " active = 1 ORDER BY docdate LIMIT $from, $amount";
                    $query2 .= " active = 1";
                } else {
                    $query .= " active = 1 ORDER BY docdate LIMIT $from, $amount";
                    $query2 .= " active = 1";
                }
            } else if ($_GET['letter'] != "") {
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
                    case 30:
                        $query .= " backoffice DESC, year DESC, number DESC LIMIT $from, $amount";
                        break;
                }
            } else {
                $query .= " active = 1 ORDER BY year DESC, number DESC LIMIT $from, $amount";
                $query2 .= " active = 1";
            }
        }

        //echo '<hr>'.$query;
        //echo '<hr>'.$query2;
        //echo '<pre style="display:block;margin:5px;padding:15px;border: solid 2px brown;text-align:left;"><a style="color: red" href="netbeans://,' . __FILE__ . ',' . __LINE__ . '">&DoubleRightArrow;go</a>&nbsp;<b>' . __FILE__ . ':<br><i>' . __LINE__ . '</i>&nbsp;&nbsp;' . __FUNCTION__ . '</b> <br>';
        //print_r($query);
        //echo '</pre>';

        $result = mysql_query($query);// or die(mysql_error());

        if (!$result) echo "Error query: <br>" . $query . '<br><br>' . mysql_error();

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
            <table cellpadding="0" cellspacing="1" bgcolor="#dddddd" border="0" style="border-bottom: #6699cc 1px solid;">
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
                    <!-- tu b�dzie bez koszt�w //-->
                    <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(25,25);">&nbsp;</td>
                    <!-- tu b�dzie bez rycza�tu //-->
                    <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(26,26);">&nbsp;</td>
                    <!-- tu b�dzie reklamacja //-->
                    <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(27,27);">&nbsp;</td>
                    <td width="29" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" style="cursor: pointer;" onclick="sortBy(30,30);">&nbsp;</td>
                    <td width="140" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(11,12);"><font
                                color="#ffffff"><small><?= AS_CASES_NR ?></small></font></td>
                    <td width="100" bgcolor="#6699cc" align="center"><font color="#ffffff"><small><?= AS_CASES_STATUS ?></small></font></td>
                    <td width="160" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(15,16);"><font
                                color="#ffffff"><small><?= AS_CASES_NAZWMARKAMOD ?></small></font></td>
                    <td width="120" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(17,18);"><font
                                color="#ffffff"><small><?= AS_CASES_IMNRREJ ?></small></font></td>
                    <td width="80" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(21,22);"><font
                                color="#ffffff" title="Data zdarzenia"><small><?= AS_CASES_ZDARZ ?></small></font></td>
                    <td width="80" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(19,20);"><font
                                color="#ffffff" title="Data otwarcia"><small><?= AS_CASES_OTW ?></small></font></td>
                    <td width="40" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(23,24);"><font
                                color="#ffffff"><small><?= COUNTRY ?></small></font></td>
                    <td width="40" bgcolor="#6699cc" onmouseover="bgColor='#bbbbbb'" onmouseout="bgColor='#6699cc'" align="center" style="cursor: pointer;" onclick="sortBy(23,24);"><font
                                color="#ffffff"><small><?= BRANCH ?></small></font></td>
                </tr>
                <?php
                $i = 0;
                while ($row = mysql_fetch_array($result)) {
                    ?>
                    <tr height="24"
                        <?php
                        if (isset($_GET['new_documents']) && $_GET['new_documents'] == 'true')
                            echo 'title="' . $row['docdate'] . '"';
                        ?>
                        bgcolor="<?php
                        if ($row['type_id'] == 1 || $row['type_id'] == 5)
                            echo ($i % 2) ? "#FFFF00" : "#FFFF99";
                        else
                            echo ($i % 2) ? "#e9e9e9" : "#dddddd";

                        ?>" onmouseover="this.bgColor='#ced9e2';" onmouseout="this.bgColor='<?php
                    if ($row['type_id'] == 1 || $row['type_id'] == 5)
                        echo ($i % 2) ? "#FFFF00" : "#FFFF99";
                    else
                        echo ($i % 2) ? "#e9e9e9" : "#dddddd";


                    ?>';" style="<?php

                    echo ($row['attention'] == 1) ? "color: red;" : "";
                    echo ($row['attention2'] == 1) ? "color: #6699cc" : "";
                    //	echo ($row['attention2']==1) ? "color: black;" : "" ;

                    ?>; cursor: pointer;" onclick="open_case( '<?php echo $row['case_id'] ?>','casewindow<?php echo $row['case_id'] ?>');">
                        <td align="center"><?php echo ($row['liquidation']) ? "<font style=\"font-size: 12pt;\" color=\"#c0c0c0\" >L</font>" : "&nbsp;" ?></td>
                        <td align="center"><?php echo ($row['status_briefcase_found']) ? "<font style=\"font-size: 12pt;\" color=\"#c0c0c0\" >" . ($row['type_id'] == 1 ? 'F' : 'T') . "</font>" : "&nbsp;" ?></td>
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
                        <td align="center"><?php echo ($row['backoffice']) ? "<font color=\"#c0c0c0\" style=\"font-size: 10pt;\">B</font>" : "&nbsp;" ?></td>
                        <td align="left"><font color="#6699cc"><small><?php echo $row['number'] ?>/<?php echo substr($row['year'], 2, 2); ?>/<?php echo $row['type_id'] ?>
                                    /<?php echo $row['client_id'] ?></small></font></td>
                        <td align="center">
                            <table cellpadding="1" cellspacing="1" border="0" width="80">
                                <tr height="15" align="center">
                                    <td bgcolor="<?php echo ($row['status_client_notified']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_ZGLOSZSZK ?>"
                                        style="border-left: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_policy_confirmed']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_POTWWAZNPOL ?>"
                                        style="border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_documentation']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DOK ?>"
                                        style="border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_decision']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DEC ?>"
                                        style="border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_assist_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZASSZAK ?>"
                                        style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_send']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_WYSLAC ?>"
                                        style="border-right: #999999 1px solid; border-top: #999999 1px solid; border-bottom: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_account_complete']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_DZRACHZAK ?>"
                                        style="border: #999999 1px solid">&nbsp;
                                    </td>
                                    <td bgcolor="<?php echo ($row['status_settled']) ? "lightgreen" : "#cccccc" ?>" width="5" title="<?= AS_CASES_SPRROZL ?>" style="border: #999999 1px solid">&nbsp;
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td align="left"><small><?php
                                if ($row['type_id'] == 1 || $row['type_id'] == 5)
                                    echo (strlen($row['marka_model']) < 16) ? $row['marka_model'] : substr($row['marka_model'], 0, 15) . "...";
                                else
                                    echo (strlen($row['paxsurname']) < 16) ? $row['paxsurname'] : substr($row['paxsurname'], 0, 15) . "...";

                                ?></small></td>
                        <td align="left"><small><?php
                                if ($row['type_id'] == 1 || $row['type_id'] == 5)
                                    echo (strlen($row['nr_rej']) < 11) ? $row['nr_rej'] : substr($row['nr_rej'], 0, 10) . "...";
                                else
                                    echo (strlen($row['paxname']) < 11) ? $row['paxname'] : substr($row['paxname'], 0, 10) . "...";
                                ?></small></td>
                        <td align="center"><small><?php echo $row['eventdate'] ?></small></td>
                        <td align="center" nowrap><small><?php echo $row['date'] ?></small></td>
                        <td align="center"><small><?php echo $row['country_id'] ?></small></td>
                        <td align="center" valign="middle"><?php


                            if ($row['coris_branch_id'] == 2) {
                                echo '<img src="img/flaga_de.png" style="padding=3px;" width="30">';
                            } else if ($row['coris_branch_id'] == 3) {
                                echo '<img src="img/flaga_at.png" style="padding=3px;" width="30">';
                            } else {
                                echo '<small>' . $row['coris_branch'] . '</small>';
                            }
                            ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </table>
            <?php
        }

        $num_rows = mysql_num_rows($result);
        $num_rows = ($num_rows > 0) ? $num_rows : 0;
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