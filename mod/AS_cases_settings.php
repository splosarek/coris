<?php

$accesss_change_liquidation = array(275, 85, 26, 256, 39, 159, 76); //zmiana czy sprawa likwidacyjna

$accesss_change_archive0 = array(76, 39, 38, 261);

function module_update()
{
    global $pageName;
    $result = '';


    $change = isset($_POST['change']) ? $_POST['change'] : null;
    $case_id = getValue('case_id');


    $check_js = '';
    $message = '';


    if (isset($change['ch_settings_ustawienia']) && $case_id > 0) {
        $res = check_update($case_id, 'settings_ustawienia');
        if ($res[0]) {

            if (check_access_archive0() && !isset($_POST['archive0'])) {
                $qt = "SELECT archive,archive0 FROM coris_assistance_cases  WHERE case_id = $case_id LIMIT 1 ";
                $mr = mysql_query($qt);
                $rt = mysql_fetch_array($mr);

                if ($rt['archive'] == 0 && $rt['archive0'] == 1) {

                    mysql_query("UPDATE coris_assistance_cases SET archive0 = 0, archive0_date = null,archive0_user_id=0 WHERE case_id = $case_id LIMIT 1");
                }
            }

            if (isset($_POST['archive'])) {
                if ($_POST['archive'] == 1) {
                    $qt = "SELECT archive,archive0 FROM coris_assistance_cases  WHERE case_id = $case_id LIMIT 1 ";
                    $mr = mysql_query($qt);
                    $rt = mysql_fetch_array($mr);

                    if ($rt['archive'] == 0 && $rt['archive0'] == 0) {
                        mysql_query("UPDATE coris_assistance_cases SET archive0 = 1, archive0_date = NOW(),archive0_user_id='" . Application::getCurrentUser() . "' WHERE case_id = $case_id LIMIT 1");
                    }

                    $query = "UPDATE coris_assistance_cases SET archive = 1, archive_date = NOW(),archive_user_id='" . Application::getCurrentUser() . "' WHERE case_id = $case_id LIMIT 1";
                } else {
                    $query = "UPDATE coris_assistance_cases SET archive = 0, archive_date = NULL,archive_user_id=0 WHERE case_id = $case_id LIMIT 1";


                }
                if ($result = mysql_query($query)) {
                    //$updateOK = true;
                    //echo $query;
                } else {
                    $message .= "Update Error: " . $query . "\n<br> " . mysql_error();
                }
            }


            $type_id = getValue('type_id') > 0 ? getValue('type_id') : 0;
            $genre_id = getValue('genre_id') > 0 ? getValue('genre_id') : 0;

            $ambulatory = getValue('ambulatory') == 1 ? 1 : 0;
            $hospitalization = getValue('hospitalization') == 1 ? 1 : 0;
            $decease = getValue('decease') == 1 ? 1 : 0;
            $transport = getValue('transport') == 1 ? 1 : 0;
            $holowanie = getValue('holowanie') == 1 ? 1 : 0;
            $wynajem_samochodu = getValue('wynajem_samochodu') == 1 ? 1 : 0;
            $naprawa_na_miejscu = getValue('naprawa_na_miejscu') == 1 ? 1 : 0;
            $przewoz_osob = getValue('przewoz_osob') == 1 ? 1 : 0;
            $only_info = getValue('only_info') == 1 ? 1 : 0;
            $costless = getValue('costless') == 1 ? 1 : 0;
            $unhandled = getValue('unhandled') == 1 ? 1 : 0;
            $liquidation = getValue('liquidation') == 1 ? 1 : 0;
            $liquidation_old = getValue('liquidation_old') == 1 ? 1 : 0;

            $operational = getValue('operational') == 1 ? 1 : 0;
            $operational_old = getValue('operational_old') == 1 ? 1 : 0;

            $backoffice = getValue('backoffice') == 1 ? 1 : 0;
            $backoffice_old = getValue('backoffice_old') == 1 ? 1 : 0;

            $send_to_simple = getValue('send_to_simple') == 1 ? 1 : 0;
            $taxi = getValue('taxi') == 1 ? 1 : 0;


            $var = '';

            if ($operational_old != $operational) {
                $var .= ",operational='.$operational.',operational_date=now(),operational_user_id='" . Application::getCurrentUser() . "'";
            }


            if (check_access_liquidation()) {
                if ($liquidation_old == 0 && $liquidation == 1) {
                    $var .= ",liquidation=1,liquidation_date=now(),liquidation_user_id='" . Application::getCurrentUser() . "'";
                }
                if ($liquidation_old == 1 && $liquidation == 0) {
                    $var .= ",liquidation=0,liquidation_date=null,liquidation_user_id=0";
                }
            }


            if ($backoffice_old != $backoffice) {
                if ($backoffice == 1)
                    $var .= ",backoffice=1,backoffice_date=now(),backoffice_user_id='" . Application::getCurrentUser() . "'";
                else
                    $var .= ",backoffice=0,backoffice_date=null,backoffice_user_id='0'";
            }


            $query = "UPDATE coris_assistance_cases SET
			type_id='$type_id',genre_id='$genre_id',
			ambulatory='$ambulatory', hospitalization='$hospitalization',decease='$decease',transport='$transport',holowanie='$holowanie',
			wynajem_samochodu='$wynajem_samochodu',naprawa_na_miejscu='$naprawa_na_miejscu',przewoz_osob='$przewoz_osob',
			only_info='$only_info',costless='$costless',unhandled='$unhandled',send_to_simple='$send_to_simple', taxi=$taxi
			$var
			WHERE case_id ='$case_id' LIMIT 1";

            $mysql_result = mysql_query($query);
            if ($mysql_result) {
                //$message .= "Udpate OK";
                CaseInfo::updateFullNumber($case_id);
            } else {
                $message .= "Update Error: " . $query . "\n<br> " . mysql_error();
            }
        } else {//error update
            echo $res[1];

        }

    }


    if (isset($change['ch_settings_status']) && $case_id > 0) {
        $res = check_update($case_id, 'settings_status');
        if ($res[0]) {


            $status_client_notified = getValue('status_client_notified') == 1 ? 1 : 0;
            //$status_policy_confirmed = getValue('status_policy_confirmed')==1 ? 1 : 0 ;
            $status_documentation = getValue('status_documentation') == 1 ? 1 : 0;
            $status_decision = getValue('status_decision') == 1 ? 1 : 0;


            $var = '';

            $status_briefcase_found = getValue('status_briefcase_found') == 1 ? 1 : 0;
            $status_briefcase_found_old = getValue('status_briefcase_found_old') == 1 ? 1 : 0;
            if ($status_briefcase_found_old == 0 && $status_briefcase_found == 1) {
                $var .= ", status_briefcase_found=1,status_briefcase_date=now(),status_briefcase_user_id = '" . $_SESSION['user_id'] . "' ";
            }


            $status_policy_confirmed = getValue('status_policy_confirmed') == 1 ? 1 : 0;
            $status_policy_confirmed_old = getValue('status_policy_confirmed_old') == 1 ? 1 : 0;
            if ($status_policy_confirmed_old == 0 && $status_policy_confirmed == 1) {
                $var .= ", status_policy_confirmed=1,status_policy_confirmed_date=now(),status_policy_confirmed_user_id = '" . $_SESSION['user_id'] . "' ";
            }


            $status_send = getValue('status_send') == 1 ? 1 : 0;
            $status_send_old = getValue('status_send_old') == 1 ? 1 : 0;

            if ($status_send_old == 0 && $status_send == 1) {
                $var .= ", status_send=1,status_send_date=now(),status_send_user_id = '" . $_SESSION['user_id'] . "' ";
            }


            $liquidation_stop = getValue('status_liquidation_stop') == 1 ? 1 : 0;
            $liquidation_stop_old = getValue('status_liquidation_stop_old') == 1 ? 1 : 0;
            if ($liquidation_stop_old == 0 && $liquidation_stop == 1) {
                $var .= ",liquidation_stop=1,liquidation_stop_date=now(),liquidation_stop_user_id='" . Application::getCurrentUser() . "'";
            }
            if ($liquidation_stop_old == 1 && $liquidation_stop == 0) {
                $var .= ",liquidation_stop=0,liquidation_stop_date=null,liquidation_stop_user_id=0";
            }

            $backoffice_stop = getValue('status_backoffice_stop') == 1 ? 1 : 0;
            $backoffice_stop_old = getValue('status_backoffice_stop_old') == 1 ? 1 : 0;
            if ($backoffice_stop_old == 0 && $backoffice_stop == 1) {
                $var .= ",backoffice_stop=1,backoffice_stop_date=now(),backoffice_stop_user_id='" . Application::getCurrentUser() . "'";
            }
            if ($backoffice_stop_old == 1 && $backoffice_stop == 0) {
                $var .= ",backoffice_stop=0,backoffice_stop_date=null,backoffice_stop_user_id=0";
            }


            $status_assist_complete = getValue('status_assist_complete') == 1 ? 1 : 0;
            $status_assist_complete_old = getValue('status_assist_complete_old') == 1 ? 1 : 0;

            if ($status_assist_complete_old == 0 && $status_assist_complete == 1) {
                $var .= ",status_assist_complete=1,status_assist_complete_date=now(),status_assist_complete_user_id='" . Application::getCurrentUser() . "'";
            }

            if ($status_assist_complete_old == 1 && $status_assist_complete == 0) {
                $var .= ",status_assist_complete=0,status_assist_complete_date=null,status_assist_complete_user_id=0";
            }


            $query = "UPDATE coris_assistance_cases SET 
			status_client_notified='$status_client_notified',
			status_documentation='$status_documentation',
			status_decision	='$status_decision'
				
			$var	
			WHERE case_id ='$case_id' LIMIT 1";

            //echo $query;
            $mysql_result = mysql_query($query);
            if ($mysql_result) {
                //$message .= "Udpate OK<br>".$query;
            } else {
                $message .= "Update Error: " . $query . "\n<br> " . mysql_error();
            }
        } else {//error update
            echo $res[1];

        }

    }


    if (isset($change['ch_settings_status2']) && $case_id > 0) {
        $res = check_update($case_id, 'ch_settings_status2');
        if ($res[0]) {

            $attention2 = getValue('attention2') == 1 ? 1 : 0;
            $attention = getValue('attention') == 1 ? 1 : 0;
            $reclamation = getValue('reclamation') == 1 ? 1 : 0;
            $reclamation_old = getValue('reclamation_old') == 1 ? 1 : 0;

            $fraud = getValue('fraud') == 1 ? 1 : 0;
            $fraud_old = getValue('fraud_old') == 1 ? 1 : 0;

            $var = '';

            if ($fraud_old == 0 && $fraud == 1) {
                $var .= ",fraud=1,fraud_date=curdate(),fraud_user_id= '" . $_SESSION['user_id'] . "' ";
            }

            if ($fraud_old == 1 && $fraud == 0) {
                $var .= ",fraud=0,fraud_date=null,fraud_user_id=0";
            }

            $appeal = getValue('appeal') == 1 ? 1 : 0;
            $appeal_old = getValue('appeal_old') == 1 ? 1 : 0;


            if ($appeal_old == 0 && $appeal == 1) {
                $var .= ",appeal=1,appeal_date=now(),appeal_ID_user= '" . $_SESSION['user_id'] . "' ";
            }

            if ($appeal_old == 1 && $appeal == 0) {
                $var .= ",appeal=0,appeal_date=null,appeal_ID_user=0";
            }


            $taxi = getValue('taxi') == 1 ? 1 : 0;
            $taxi_old = getValue('taxi_old') == 1 ? 1 : 0;

            if ($taxi_old == 0 && $taxi == 1) {
                $var .= ",taxi=1";
            }

            if ($taxi_old == 1 && $taxi == 0) {
                $var .= ",taxi=0";
            }

            if ($reclamation_old != $reclamation) {
                $var .= ",reclamation='.$reclamation.',reclamation_date=now(),reclamation_user_id='" . Application::getCurrentUser() . "'";
            }


            $query = "UPDATE coris_assistance_cases SET 
			attention='$attention',
			attention2='$attention2'
			
			$var	
			WHERE case_id ='$case_id' LIMIT 1";

            $mysql_result = mysql_query($query);
            if ($mysql_result) {
                //$message .= "Udpate OK".$query;
            } else {
                $message .= "Update Error: " . $query . "\n<br> " . mysql_error();
            }
        } else {//error update
            echo $res[1];

        }

    }


    if (isset($change['ch_settings_decyzje']) && $case_id > 0) {
        $action = getValue('action');
        $lista = getValue('lista');

        if ($action == 'remove') {
            $decisions = explode(",", $lista);
            foreach ($decisions as $decision_id) {
                if ($decision_id > 0) {
                    $query = "UPDATE coris_assistance_cases_decisions SET active = 0 WHERE decision_id = '$decision_id'";
                    if (!$result = mysql_query($query))
                        die (mysql_error());
                }
            }
        }


    }


    echo $message;
}


function module_main()
{
    global $case_id;
    $result = '';

    $query = "SELECT ac.number, ac.year, ac.client_id, ac.type_id, ac.genre_id, ac.paxname, ac.paxsurname, ac.watch, ac.ambulatory, ac.hospitalization, 
		                 ac.transport, ac.decease, ac.costless,ac.only_info, ac.costless, ac.unhandled, ac.archive,ac.archive_date,ac.archive_user_id, ac.archive0,ac.archive0_date,ac.archive0_user_id, ac.reclamation, ac.reclamation_user_id, ac.reclamation_date,ac.fraud,ac.fraud_date,ac.fraud_user_id, ac.status_client_notified,
                         ac.status_policy_confirmed,ac.status_policy_confirmed_date,ac.status_policy_confirmed_user_id, ac.status_documentation, ac.status_decision, ac.status_assist_complete, ac.status_assist_complete_date, ac.status_assist_complete_user_id, ac.status_account_complete,
                         ac.status_settled, ac.attention, ac.attention2, acr.reclamation_text, ac.attention, ac.attention2,
                         ac.liquidation ,ac.liquidation_date ,ac.liquidation_user_id ,ac.liquidation_stop ,ac.liquidation_stop_date ,ac.liquidation_stop_user_id ,
                         ac.backoffice ,ac.backoffice_date ,ac.backoffice_user_id ,ac.backoffice_stop ,ac.backoffice_stop_date ,ac.backoffice_stop_user_id ,
                         ac.operational,ac.operational_user_id,ac.operational_date,
                         ac.holowanie,ac.wynajem_samochodu,
                         ac.naprawa_na_miejscu,ac.przewoz_osob,
                         ac.status_briefcase_found,ac.status_briefcase_date,ac.status_briefcase_user_id,
                         ac.status_send,ac.status_send_date,ac.status_send_user_id,ac.send_to_simple,
                         cb.name AS coris_branch, cb.ID AS coris_branch_id,
                         ac.ID_case_state,ac.ID_case_state2, appeal, appeal_date, appeal_ID_user, taxi
                    FROM coris_assistance_cases ac
               LEFT JOIN coris_assistance_cases_reclamations acr ON ac.case_id = acr.case_id
               LEFT JOIN coris_branch cb ON cb.ID=ac.coris_branch_id
                   WHERE ac.case_id = '" . $case_id . "'";

    $mysql_result = mysql_query($query);
    if (!$mysql_result) {
        echo "Error query" . $query . "<br>" . mysql_error();
        exit();
    }
    $row_case_settings = mysql_fetch_array($mysql_result);


    $result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
    $result .= ustawienia($row_case_settings);
    $result .= '</div>';


    $result .= '<div style="clear:both;"></div>';
    $result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
    $result .= status($row_case_settings);
    $result .= '</div>';
    $result .= '<div style="clear:both;"></div>';
    $result .= '<div style="width: 1010px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;;margin: 5px;">';
    $result .= status2($row_case_settings);
    $result .= '</div>';


    $result .= '<div style="width: 360px;float:left;padding-right:10px;border: #6699cc 1px solid;height:auto;background-color: #DFDFDF;margin:5px">';
    $result .= decyzje($row_case_settings);
    $result .= '</div><div style="width:570px;float:right;padding-right:10px;border: #6699cc 1px solid;height:auto; background-color: #DFDFDF;margin: 5px">';
    $result .= wykonawcy($row_case_settings);
    $result .= '</div>';


    $result .= '<div style="clear:both;"></div>';
    return $result;
}


function wykonawcy_details($row2, $tryb)
{
    global $case_id;
    $result = '';

    if ($tryb) {

        $result .= ' <script language="JavaScript">
        <!--
            function RemovePositions() {
                var values = "";
                fr_contrahents = document.getElementById(\'contrahents\');

                obj = fr_contrahents.contentWindow.document.getElementsByName(\'expense_id[]\');

                ilosc =  obj.length;
	
             	for (var i = 0; i < ilosc ; i++) {
                        if (obj[i].checked){
                            values += obj[i].value + ",";
                        }
                 }
                
                if (values == "") {
                    alert("' . AS_CASD_MSG_BRZAPOZDOUSWYK . ' ");
                    return;
                } else {
                    if (!confirm("' . AS_CASD_MSG_CONFUSPOZ . ' "))
                        return;
                   
                    //contrahents.document.location = "AS_cases_details_expenses_frame.php?case_id=' . $case_id . ' &expense_id="+ values;
                    fr_contrahents.src = "AS_cases_details_expenses_frame.php?case_id=' . $case_id . '&expense_id="+ values;
                }
            }
            
            function MM_openBrWindow(theURL,winName,features) { //v2.0
				  window.open(theURL,winName,features);
			}

        //-->
        </script>';
        $result .= ' <table cellpadding="2" cellspacing="2" border="0" width="100%">
         <tr>
         <td style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
			<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td width=50%>
            	<input type="button" value="+" style="font-weight: bold; width: 35px" title="' . AS_CASD_MSG_DODWYK . '" onclick="window.open(\'AS_cases_details_expenses_position_add.php?case_id=' . $case_id . '&branch_id=' . $row2['coris_branch_id'] . '&type_id=' . $row2['type_id'] . ' \',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=500,left=\'+ (screen.availWidth - 400) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);">
                <input type="button" value="-" style="font-weight: bold; width: 35px" title="' . AS_CASD_MSG_USUNWYK . ' " onclick="RemovePositions();">
                </td><td width=50% align="right">';
        if ($_SESSION['new_user'] == 0) {
            //$result .= '	<input type="button" value="'.  AS_CASD_MSG_BUTFIN .' " style="font-weight: bold; width: 135px" title="'.  AS_CASD_MSG_PRZEDOFIN .' " onclick="MM_openBrWindow(\'../finances/FK_cases_details.php?case_id='. $case_id .' \',\'\',\'scrollbars=yes,resizable=yes,top=50,left=170,width=650,height=570\')"> ';
        }
        $result .= '
    		</td></tr>
        </table>
      </td>
    </tr>
    <tr>
		<td style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
   <iframe name="contrahents" id="contrahents"  src="AS_cases_details_expenses_frame.php?case_id=' . $case_id . '&branch_id=' . $row2['coris_branch_id'] . ' " width="100%" height="420"></iframe>
																						
                                                                                </td>
                                                                        </tr>
                                                                </table>';
    } else {
        $result = '<table cellpadding="2" cellspacing="2" border="0" width="100%">
              <tr>
              <td style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;"></td>
    		</tr>
    		<tr>
            <td style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
   				<iframe name="contrahents" id="contrahents" src="AS_cases_details_expenses_frame.php?case_id=' . $case_id . '&branch_id=' . $row2['coris_branch_id'] . ' " width="100%" height="420"></iframe>
			</td>
            </tr>
      </table>';
    }

    return $result;
}


function decyzje($row)
{
    $result = '';
    global $global_link, $change, $case_id;

    $result .= '<form method="POST" action="" style="padding:0px;margin:0px" name="form_decyzje_action" id="form_decyzje_action">';
    $result .= '<input type="hidden" id="form_decyzje_action_input" name="form_decyzje_action_input" value="1">';
    $result .= '<input type="hidden" id="form_decyzje_action_dec_id" name="decision_id" value="0">';
    $result .= '</form>';

    $result .= '<form method="POST" style="padding:0px;margin:0px" name="form_decyzje" id="form_decyzje">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>' . AS_CASD_DEC . '</b></font></small>&nbsp;
				</td>
				<td align="right">';

    if (isset($change['settings_decyzje'])) {
        $result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_decyzje] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="' . $global_link . '"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				
				<input type="hidden" name="action" id="action" value="">
				<input type="hidden" name="lista"  id="lista" value="">
				</div>';
    } else {
        $result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[settings_decyzje] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

    }

    $result .= '</td>	
			</tr>
			</table>';
    $query = "SELECT acd.decision_id, acd.type_id, acd.amount, acd.currency_id, acd.decision_date, acd.note, acd.date, acdt.value, u.name, u.surname,u.initials  FROM coris_assistance_cases_decisions acd, coris_users u, coris_assistance_cases_decisions_types acdt WHERE case_id = '$case_id' AND acd.user_id = u.user_id AND acd.type_id = acdt.type_id AND acd.active = 1 ORDER BY decision_date DESC";

    $mysql_result = mysql_query($query);
    $result .= '
		
		 <script language="javascript">
        <!--
		function NewDecision() {
			var decision = window.open(\'AS_cases_details_insurance_decisions_add.php?case_id=' . $case_id . '\',\'\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=240,left=\'+ (screen.availWidth - 500) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 220) / 2); 
			//Niepotrzebne, bo nie dzia3a
			//decision.opener = parent;
		}
			function checkboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

            function validate() {
                return true;
            }

			// TODO: Poprawia - aby nie by3o "for"
            function move(s) {
				e = window.event;
				var keyInfo = String.fromCharCode(e.keyCode);

				if (e[\'keyCode\'] != 9 && e[\'keyCode\'] != 16 && e[\'keyCode\'] != 8) {
					for (var i = 0; i < form1.length; i++) {
						if (s.name == form1.elements[i].name) {
							if ((form1.elements[i].value.length == 2)) {
								form1.elements[i+1].focus();
								return false;
							}
						}
					}
				}
            }

			function remove(s) {
				e = window.event;
				var keyInfo = String.fromCharCode(e.keyCode);

				if (e[\'keyCode\'] == 8) {
					for (var i = 0; i < form1.length; i++) {
						if (s.name == form1.elements[i].name) {
							if ((form1.elements[i].value.length == 0)) {
								form1.elements[i-1].focus();
								var rng = form1.elements[i-1].createTextRange();
								rng.select();
								return false;
							}
						}
					}
				}
			}
		
            function removeDecisions() {
                var values = "";
				//checkedForm = decisions_frame.form1;
				checkedForm = document.getElementById(\'form_decyzje\');
				
				lista = document.getElementsByName(\'decisioncheck\');				
                if (lista.length== 1 && lista[0].checked ) {
                    values = lista[0].value;
                } else if (checkedForm.elements.length > 1) {
                    for (var i = 0; i < lista.length; i++)
                        if (lista[i].checked)
                            values += lista[i].value + ",";
                }
                if (values == "") {
                    alert("' . AS_CASD_BRZAZDECDOUS . '");
                    return;
                } else {
                    if (!confirm("' . AS_CASD_CZYCHCUSDEC . '"))
                        return;
                        
                    document.getElementById(\'action\').value = \'remove\';
                    document.getElementById(\'lista\').value = values ;
                     document.getElementById(\'form_decyzje\').submit();  
					//var url = "AS_cases_details_insurance_decisions.php?action=remove&case_id=' . $case_id . '&decision_id="+ values;
                    //decisions_frame.location = url;
                }
            }
        //-->
        </script>
		';
    if (isset($change['settings_decyzje'])) {


        $result .= '<table cellpadding="2" cellspacing="1" border="0" >
                                    <tr height="20" bgcolor="#eeeeee">
                                        <td  align="center" style="border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid;">
                                            <input style="width: 20px;" type="button" value="+" onclick="NewDecision();" title="' . AS_CASD_NOWDEC . '"> 
											<input style="width: 20px;" type="button" value="-" onclick="removeDecisions()" title="' . AS_CASD_USUNDEC . '"> 
                                        </td>
                                        
                                    </tr></table>';

        $result .= '<table width="100%" cellpadding="2" cellspacing="1" border="0">';


        $i = 1;
        while ($row2 = mysql_fetch_array($mysql_result)) {


            $result .= '<tr bgcolor="' . (($i % 2) ? "#eeeeee" : "#e0e0e0") . '" style="cursor: default" title="' . "Data: " . $row2['date'] . "\nWprowadzaj?cy: " . $row2['name'] . "  " . $row2['surname'] . '">
				<td width="5%" align="center"><input style="background: lightyellow" type="checkbox" name="decisioncheck" value="' . $row2['decision_id'] . '"></td>
				<td width="24%" align="center"><font color="#6699cc">' . $row2['value'] . ' </font></td>
				<td width="20%" align="right"><font color="navy">' . str_replace(".", ",", $row2['amount']) . '</font></td>
				<td width="6%">' . $row2['currency_id'] . '</td>
				<td width="30%" align="center"><small>' . $row2['decision_date'] . '</small></td>
				<td width="15%" align="center"><font color="#6699cc">' . $row2['initials'] . '</font></td>
			</tr>
			<tr>
				<td bgcolor="lightyellow" colspan="6"><font color="#999999"><i><small>' . $row2['note'] . '</small></i></td>
			</tr>';
            $i++;
        }

        $result .= '</table>';
    } else {
        $result .= '<table width="100%" cellpadding="2" cellspacing="1" border="0">';


        $i = 1;
        while ($row2 = mysql_fetch_array($mysql_result)) {
            $result .= '<tr bgcolor="' . (($i % 2) ? "#eeeeee" : "#e0e0e0") . '" style="cursor: default" title="' . "Data: " . $row2['date'] . "\nWprowadzaj?cy: " . $row2['name'] . "  " . $row2['surname'] . '">
				
				<td width="24%" align="center"><font color="#6699cc">' . $row2['value'] . ' </font></td>
				<td width="20%" align="right"><font color="navy">' . str_replace(".", ",", $row2['amount']) . '</font></td>
				<td width="6%">' . $row2['currency_id'] . '</td>
				<td width="30%" align="center"><small>' . $row2['decision_date'] . '</small></td>
				<td width="15%" align="center"><font color="#6699cc">' . $row2['initials'] . '</font></td>
			</tr>
			<tr>
				<td bgcolor="lightyellow" colspan="6"><font color="#999999"><i><small>' . $row2['note'] . '</small></i></td>
			</tr>';
            $i++;
        }

        $result .= '</table>';


    }

    $result .= '</form>';
    return $result;
}

function wykonawcy($row)
{
    $result = '';
    global $global_link, $change;
    $result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>' . AS_CASD_ZLEC2 . '</b></font></small>&nbsp;
				</td>
				<td align="right">';

    if (isset($change['settings_wykonawcy'])) {
        $result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_wykonawcy] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="' . $global_link . '"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
    } else {
        $result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[settings_wykonawcy] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

    }

    $result .= '</td>	
			</tr>
			</table>';
    if (isset($change['settings_wykonawcy'])) {
        $result .= wykonawcy_details($row, 1);

    } else {
        $result .= wykonawcy_details($row, 0);

    }

    $result .= '</form>';
    return $result;

}

function status2($row)
{
    $result = '';
    global $global_link, $change;
    $result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>' . AS_CASES_FLAGI . '</b></font></small>&nbsp;
				</td>
				<td align="right">';

    if (isset($change['settings_status2'])) {
        $result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="' . $global_link . '"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
    } else {
        $result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[settings_status2] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

    }

    $result .= '</td>	
			</tr>
			</table>';
    if (isset($change['settings_status2'])) {
        $result .= '<script language="JavaScript1.2">
			<!--
			function checkboxSelect(s) {
				if (s.checked) {
					s.checked = false;
				} else {
					s.checked = true;
				}
			}

			function zaznacz_uwaga2(s) {
				//alert(\'zaznacz_uwaga2 \' + s);
				at= document.getElementById(\'attention\');
				at2=  document.getElementById(\'attention2\');
				
				if (s==\'attention\') {
					//at.checked = true;

					if (at.checked)
							at.checked = false;
					else
						  at.checked = true;
					at2.checked = false;										
				} else {
					//at2.checked = true;
					if (at2.checked)
							at2.checked = false;
					else
						at2.checked = true;
					at.checked = false;
				}
			}

			function zaznacz_uwaga(s) {
				//alert(\'zaznacz_uwaga \' + s);
				at= document.getElementById(\'attention\');
				at2=  document.getElementById(\'attention2\');
				
				if (s==\'attention\') {					
					if (!at.checked)
							at.checked = false;
					else		
						  at.checked = true;
					at2.checked = false;										
				} else {
					if (!at2.checked)
							at2.checked = false;
					else		
						at2.checked = true;
					
					at.checked = false;
				}
			}
			
			
			//-->
			</script>
			';
        $result .= '
				<table cellpadding="1" cellspacing="0" border="1" align="center" width=95%>
			 		<tr>
						<td width="16%" align="center" ' . (($row['attention2']) ? "bgcolor=\"#6699cc\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox" name="attention2" id="attention2" value="1" style="background: #6699cc" onclick="zaznacz_uwaga(\'attention2\');"  ' . (($row['attention2']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="zaznacz_uwaga2(\'attention2\');" title="' . AS_CASD_PROSZZAZNSZCZEGSPR2 . '"><b>' . AS_CASD_UWAGNATASPR2 . '</b></font>
						</td>
						<td width="16%" align="center" ' . (($row['attention']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox" value="1" name="attention"  id="attention" style="background: red;" onclick="zaznacz_uwaga(\'attention\');" ' . (($row['attention']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="zaznacz_uwaga2(\'attention\');" title="' . AS_CASD_PROSZZAZNSZCZEGSPR . '"><b>' . AS_CASD_UWAGNATASPR . '</b></font>
						</td>	
						<td width="16%" align="center" ' . (($row['reclamation']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"   value="1" name="reclamation"  id="reclamation" style="background: red;" ' . (($row['reclamation']) ? "checked" : "") . '  title="' . ($row['reclamation'] == 1 ? 'Data: ' . $row['reclamation_date'] . "\n" . "U?ytkownik: " . getUserName($row['reclamation_user_id']) : '') . '">&nbsp;<font onclick="FormcheckboxSelect(\'reclamation\');" style="cursor: default" title="' . AS_TITLE_REKLAM1 . '"><b>' . AS_TITLE_REKLAM1 . '</b></font>
							<input type="hidden"  name="reclamation_old"  id="reclamation_old" value="' . $row['reclamation'] . '">
						 </td>																
						<td width="16%" align="center" >
							<input type="checkbox"   value="1" name="fraud"  id="fraud" style="background: red;" ' . (($row['fraud']) ? "checked" : "") . '    title="' . ($row['fraud'] == 1 ? 'Data: ' . $row['fraud_date'] . "\n" . "U?ytkownik: " . getUserName($row['fraud_user_id']) : '') . '">&nbsp;<font onclick="FormcheckboxSelect(\'fraud\');"  style="cursor: default"  title="' . ($row['fraud'] == 1 ? 'Data: ' . $row['fraud_date'] . "\n" . "U?ytkownik: " . getUserName($row['fraud_user_id']) : '') . '"><b>' . AS_TITLE_FRAUD . '</b></font>
							<input type="hidden"  name="fraud_old"  id="fraud_old" value="' . $row['fraud'] . '">
						 </td>
						 <td width="16%" align="center" ' . (($row['appeal']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"   value="1" name="appeal"  id="appeal" style="background: red;" ' . (($row['appeal']) ? "checked" : "") . '    title="' . ($row['appeal'] == 1 ? 'Data: ' . $row['appeal_date'] . "\n" . "U?ytkownik: " . getUserName($row['appeal_ID_user']) : '') . '">&nbsp;<font onclick="FormcheckboxSelect(\'appeal\');"  style="cursor: default"  title="' . ($row['appeal'] == 1 ? 'Data: ' . $row['appeal_date'] . "\n" . "U?ytkownik: " . getUserName($row['appeal_ID_user']) : '') . '"><b>Odwo³anie</b></font>
							<input type="hidden"  name="appeal_old"  id="appeal_old" value="' . $row['appeal'] . '">
						 </td>																
						 <td width="16%" align="center" ' . (($row['taxi']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"   value="1" name="taxi"  id="taxi" style="background: red;" ' . (($row['taxi']) ? "checked" : "") . '    title="' . ($row['taxi'] == 1 ? 'Data: ' . $row['taxi_date'] . "\n" . "U?ytkownik: " . getUserName($row['taxi_ID_user']) : '') . '">&nbsp;<font onclick="FormcheckboxSelect(\'taxi\');"  style="cursor: default"  title="' . ($row['taxi'] == 1 ? 'Data: ' . $row['taxi_date'] . "\n" . "U?ytkownik: " . getUserName($row['taxi_ID_user']) : '') . '"><b>Taxi</b></font>
						 </td>																
					</tr>
				</table><br>';
    } else {
        $result .= '
				<table cellpadding="1" cellspacing="0" border="1" align="center" width=95%>
					<tr>
						<td width="16%" align="center" ' . (($row['attention2']) ? "bgcolor=\"#6699cc\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox" disabled name="attention2" id="attention2" value="1" style="background: #6699cc"   ' . (($row['attention2']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"title="' . AS_CASD_PROSZZAZNSZCZEGSPR2 . '"><b>' . AS_CASD_UWAGNATASPR2 . '</b></font>
						</td>
						<td width="16%" align="center" ' . (($row['attention']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"  disabled value="1" name="attention"  id="attention" style="background: red;" ' . (($row['attention']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  title="' . AS_CASD_PROSZZAZNSZCZEGSPR . '"><b>' . AS_CASD_UWAGNATASPR . '</b></font>
						</td>
						<td width="16%" align="center" ' . (($row['reclamation']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"  disabled value="1" name="reclamation"  id="reclamation" style="background: red;" ' . (($row['reclamation']) ? "checked" : "") . ' title="' . ($row['reclamation'] == 1 ? 'Data: ' . $row['reclamation_date'] . "\n" . "U?ytkownik: " . getUserName($row['reclamation_user_id']) : '') . '">&nbsp;<font style="cursor: default"  title="' . AS_TITLE_REKLAM1 . '"><b>' . AS_TITLE_REKLAM1 . '</b></font>
						</td>				
						<td width="16%" align="center" >
							<input type="checkbox"  disabled value="1" name="fraud"  id="fraud" style="background: red;" ' . (($row['fraud']) ? "checked" : "") . '  title="' . ($row['fraud'] == 1 ? 'Data: ' . $row['fraud_date'] . "\n" . "U?ytkownik: " . getUserName($row['fraud_user_id']) : '') . '">&nbsp;<font style="cursor: default"   title="' . ($row['fraud'] == 1 ? 'Data: ' . $row['fraud_date'] . "\n" . "U?ytkownik: " . getUserName($row['fraud_user_id']) : '') . '"><b>' . AS_TITLE_FRAUD . '</b></font>				
						</td>				
						<td width="16%" align="center" ' . (($row['appeal']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"  disabled value="1" name="appeal"  id="reclamation" style="background: red;" ' . (($row['appeal']) ? "checked" : "") . ' title="' . ($row['appeal'] == 1 ? 'Data: ' . $row['appeal_date'] . "\n" . "U?ytkownik: " . getUserName($row['appeal_ID_user']) : '') . '">&nbsp;<font style="cursor: default"  title="Odwo³anie"><b>Odwo³anie</b></font>
						</td>				
						<td width="16%" align="center" ' . (($row['taxi']) ? "bgcolor=\"red\" style=\"color: yellow\"" : "style=\"color: red\"") . '>
							<input type="checkbox"  disabled value="1" name="taxi"  id="taxi" style="background: red;" ' . (($row['taxi']) ? "checked" : "") . ' title="' . ($row['taxi'] == 1 ? 'Data: ' . $row['taxi_date'] . "\n" . "U?ytkownik: " . getUserName($row['taxi_ID_user']) : '') . '">&nbsp;<font style="cursor: default"  title="Taxi"><b>Taxi</b></font>
						</td>				
					</tr>
				</table><br>';
    }


    $result .= '</form>';
    return $result;

}

function status($row)
{
    $result = '';
    global $global_link, $change;
    $result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>' . AS_CASES_STATUS . '</b></font></small>&nbsp;
				</td>
				<td align="right">';

    if (isset($change['settings_status'])) {
        $result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_status] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="' . $global_link . '"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
    } else {
        $result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[settings_status] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

    }

    $result .= '</td>	
			</tr>
			</table>';
    if (isset($change['settings_status'])) {

        $result .= '
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=95%>
					  <tr>
		<td width="11%"  title="' . AS_CASD_ZAZN . '" nowrap>
							<input type="checkbox" id="status_client_notified" name="status_client_notified" value="1" style="background: #dfdfdf" ' . (($row['status_client_notified']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  onclick="FormcheckboxSelect(\'status_client_notified\');">' . AS_CASD_ZGL . '</font>
				</td>	
				
		<td width="14%"  nowrap title="' . ($row['status_briefcase_found'] == 1 ? 'Data: ' . $row['status_briefcase_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_briefcase_user_id']) : '') . '">
					<input type="checkbox" id="status_briefcase_found" value="1" name="status_briefcase_found" style="background: #dfdfdf" ' . (($row['status_briefcase_found']) ? "checked" : "") . ' ' . (($row['status_briefcase_found']) ? "disabled" : "") . '>&nbsp;<font style="cursor: default"  onclick="FormcheckboxSelect(\'status_briefcase_found\');">' . ($row['type_id'] == 1 ? DOC_FOTO : AS_CASD_TECZKA_ZAL) . '</font>
					<input type="hidden" name="status_briefcase_found_old" value="' . $row['status_briefcase_found'] . '">
				</td>	
		<td width="11%"  nowrap title="' . AS_CASD_ZAZNZOSPOTWWAZN . '">
					<input type="checkbox" id="status_policy_confirmed" value="1" name="status_policy_confirmed" style="background: #dfdfdf" ' . (($row['status_policy_confirmed']) ? "checked disabled" : "") . ' title="' . ($row['status_policy_confirmed'] == 1 ? 'Data: ' . $row['status_policy_confirmed_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_policy_confirmed_user_id']) : '') . '">&nbsp;<font style="cursor: default"  onclick="FormcheckboxSelect(\'status_policy_confirmed\');">' . AS_CASD_POTWPOL . '</font>
					<input type="hidden" name="status_policy_confirmed_old" value="' . $row['status_policy_confirmed'] . '">
				</td>	
				
		<td width="13%" nowrap  title="' . AS_CASD_ZAZJUZYSKWSZDOK . '">
					<input type="checkbox" id="status_documentation" name="status_documentation" value="1" style="background: #dfdfdf" ' . (($row['status_documentation']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  onclick="FormcheckboxSelect(\'status_documentation\');">' . AS_CASES_DOK . '</font>

				</td>	
				
		<td width="9%" nowrap  title="' . AS_CASD_ZAZJESDECYTU . '">
					<input type="checkbox" id="status_decision" name="status_decision" value="1" onclick="if (' . $row['status_decision'] . ' && this.checked) alert(\'' . AS_CASD_PRWPISDECWTECZ . '\');" style="background: #dfdfdf" ' . (($row['status_decision']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  onclick="if (' . $row['status_decision'] . ' && !form1.status_decision.checked) alert(\'' . AS_CASD_PRWPISDECWTECZ . '\'); FormcheckboxSelect(\'status_decision\');">' . AS_CASES_DEC . '
				</td>	


		<td  nowrap nowrap title="BACK-OFFICE zako?czona" width="120">
					<div style="float:left;width:20px;"><input type="checkbox"   id="status_backoffice_stop" name="status_backoffice_stop" value="1" onClick="if (' . ($row['backoffice'] == 0) . ' && this.checked ) { return false;}" style="background: #dfdfdf" ' . (($row['backoffice_stop']) ? "checked" : "") . ' title="' . ($row['backoffice_stop'] == 1 ? 'Data: ' . $row['backoffice_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_stop_user_id']) : '') . '">
					<input type="hidden"   id="status_backoffice_stop_old" name="status_backoffice_stop_old" value="' . $row['backoffice_stop'] . '"></div>
					<div style="float:left;width:90px;text-align:center;">&nbsp;<font onclick="' . ($row['backoffice'] == 1 ? 'FormcheckboxSelect(\'status_backoffice_stop\');' : '') . '" style="cursor: default"  title="' . ($row['backoffice_stop'] == 1 ? 'Data: ' . $row['backoffice_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_stop_user_id']) : '') . '">B-OFF zako?czona</font></div>
		</td>

      <td  nowrap nowrap title="' . AS_CASD_ZAZLIQUIDSTOP . '" width="120">
					<div style="float:left;width:20px;"><input type="checkbox"   id="status_liquidation_stop" name="status_liquidation_stop" value="1" onClick="if (' . ($row['liquidation'] == 0) . ' && this.checked ) { return false;}" style="background: #dfdfdf" ' . (($row['liquidation_stop']) ? "checked" : "") . ' title="' . ($row['liquidation_stop'] == 1 ? 'Data: ' . $row['liquidation_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_stop_user_id']) : '') . '">
					<input type="hidden"   id="status_liquidation_stop_old" name="status_liquidation_stop_old" value="' . $row['liquidation_stop'] . '">
					</div>
					<div style="float:left;width:90px;text-align:center;">&nbsp;<font onclick="' . ($row['liquidation'] == 1 ? 'FormcheckboxSelect(\'status_liquidation_stop\');' : '') . '"  style="cursor: default"  title="' . ($row['liquidation_stop'] == 1 ? 'Data: ' . $row['liquidation_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_stop_user_id']) : '') . '">' . AS_CASD_ZAZLIQUIDSTOP . '</font></div>
		</td>

		<td nowrap title="' . AS_CASD_ZAZNZAKDZASST . '" width="120">
					<div style="float:left;width:20px;"><input type="checkbox" id="status_assist_complete" value="1" name="status_assist_complete" style="background: #dfdfdf" ' . (($row['status_assist_complete']) ? "checked" : "") . ' title="' . ($row['status_assist_complete'] == 1 ? 'Data: ' . $row['status_assist_complete_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_assist_complete_user_id']) : '') . '">
					<input type="hidden"   id="status_assist_complete_old" name="status_assist_complete_old" value="' . $row['status_assist_complete'] . '">
					</div>
					<div style="float:left;width:90px;text-align:center;">&nbsp;<font style="cursor: default"   onclick="FormcheckboxSelect(\'status_assist_complete\');" color="green"><b>' . AS_CASD_ZAKONCZ . '</b></font></div>
					
				</td>	
				<td nowrap>
					<input type="checkbox" id="status_send" name="status_send"  value="1" style="background: #dfdfdf" ' . (($row['status_send']) ? "checked" : "") . ' title="' . ($row['status_send'] == 1 ? 'Data: ' . $row['status_send_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_send_user_id']) : '') . '" ' . (($row['status_send'] == 1) ? "checked" : "") . ' ' . (($row['status_send'] == 1) ? "disabled" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'status_send\');">' . AS_CASES_WYSLAC . '</font>
					<input type="hidden" name="status_send_old" value="' . $row['status_send'] . '">
				</td>
				
			</tr>
		</table><br>';
    } else {
        $result .= '
	<table cellpadding="1" cellspacing="0" border="1" align="center" width=95%>
					  <tr>
		<td width="11%"  title="' . AS_CASD_ZAZN . '" nowrap    >
							<input type="checkbox"  disabled  disabled id="status_client_notified" name="status_client_notified" value="1" style="background: #dfdfdf" ' . (($row['status_client_notified']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  >' . AS_CASD_ZGL . '</font>
				</td>	
		<td width="14%"  nowrap title="' . ($row['status_briefcase_found'] == 1 ? 'Data: ' . $row['status_briefcase_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_briefcase_user_id']) : '') . '">
					<input type="checkbox" id="status_briefcase_found" value="1" name="status_briefcase_found" style="background: #dfdfdf" ' . (($row['status_briefcase_found']) ? "checked" : "") . ' disabled>&nbsp;<font style="cursor: default"  >' . ($row['type_id'] == 1 ? DOC_FOTO : AS_CASD_TECZKA_ZAL) . '</font>
				</td>	
						
		<td width="11%"  title="' . AS_CASD_ZAZNZOSPOTWWAZN . '" nowrap>
					<input type="checkbox"  disabled id="status_policy_confirmed" value="1" name="status_policy_confirmed" style="background: #dfdfdf" ' . (($row['status_policy_confirmed']) ? "checked" : "") . ' title="' . ($row['status_policy_confirmed'] == 1 ? 'Data: ' . $row['status_policy_confirmed_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_policy_confirmed_user_id']) : '') . '" >&nbsp;<font style="cursor: default"  >' . AS_CASD_POTWPOL . '</font>
		</td>	
				
		<td width="13%"  title="' . AS_CASD_ZAZJUZYSKWSZDOK . '" nowrap>
					<input type="checkbox"  disabled id="status_documentation" name="status_documentation" value="1" style="background: #dfdfdf" ' . (($row['status_documentation']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  >' . AS_CASES_DOK . '</font>

				</td>	
				
		<td width="9%"  title="' . AS_CASD_ZAZJESDECYTU . '" nowrap>
					<input type="checkbox"  disabled id="status_decision" name="status_decision" value="1" onclick="if (' . $row['status_decision'] . ' && this.checked) alert(\'' . AS_CASD_PRWPISDECWTECZ . '\');" style="background: #dfdfdf" ' . (($row['status_decision']) ? "checked" : "") . '>&nbsp;<font style="cursor: default"  >' . AS_CASES_DEC . '
				</td>	
				
		<td  nowrap title="BACK-OFFICE zako?czona" width="120">
						<div style="float:left;width:20px;"><input type="checkbox"  disabled id="status_backoffice_stop" name="status_backoffice_stop" value="1" onClick="if (' . ($row['backoffice'] == 0) . ' && this.checked ) { return false;}" style="background: #dfdfdf" ' . (($row['backoffice_stop']) ? "checked" : "") . ' title="' . ($row['backoffice_stop'] == 1 ? 'Data: ' . $row['backoffice_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_stop_user_id']) : '') . '">
						</div>
						<div style="float:left;width:90px;text-align:center;">&nbsp;<font style="cursor: default"  title="' . ($row['backoffice_stop'] == 1 ? 'Data: ' . $row['backoffice_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_stop_user_id']) : '') . '">B-OFF zako?czona</font></div>

		</td>	
		<td  nowrap title="' . AS_CASD_ZAZLIQUIDSTOP . '" width="120">
						<div style="float:left;width:20px;">		<input type="checkbox"  disabled id="status_liquidation_stop" name="status_liquidation_stop" value="1" onClick="if (' . ($row['liquidation'] == 0) . ' && this.checked ) { return false;}" style="background: #dfdfdf" ' . (($row['liquidation_stop']) ? "checked" : "") . ' title="' . ($row['liquidation_stop'] == 1 ? 'Data: ' . $row['liquidation_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_stop_user_id']) : '') . '">
						</div>
						<div style="float:left;width:90px;text-align:center;">&nbsp;<font style="cursor: default"  title="' . ($row['liquidation_stop'] == 1 ? 'Data: ' . $row['liquidation_stop_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_stop_user_id']) : '') . '">' . AS_CASD_ZAZLIQUIDSTOP . '</font></div>
		</td>

		<td nowrap title="' . AS_CASD_ZAZNZAKDZASST . '" width="120">
						<div style="float:left;width:20px;"><input type="checkbox"  disabled id="status_assist_complete" value="1" name="status_assist_complete" style="background: #dfdfdf" ' . (($row['status_assist_complete']) ? "checked" : "") . ' title="' . ($row['status_assist_complete'] == 1 ? 'Data: ' . $row['status_assist_complete_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_assist_complete_user_id']) : '') . '">
						</div>
						<div style="float:left;width:90px;text-align:center;">&nbsp;<font style="cursor: default"    color="green"><b>' . AS_CASD_ZAKONCZ . '</b></font></div>

				</td>	
				<td nowrap>
				 <input type="checkbox" disabled id="status_send" name="status_send"  value="1" style="background: #dfdfdf" ' . (($row['status_send']) ? "checked" : "") . ' title="' . ($row['status_send'] == 1 ? 'Data: ' . $row['status_send_date'] . "\n" . "U?ytkownik: " . getUserName($row['status_send_user_id']) : '') . '">&nbsp;<font style="cursor: default" >' . AS_CASES_WYSLAC . '</font>
				</td>
			</tr>
		</table><br>';
    }

    $result .= '</form>';
    return $result;

}


function ustawienia($row)
{
    $lang = $_SESSION['GUI_language'];

    $result = '';
    global $global_link, $change;
    $result .= '<form method="POST" style="padding:0px;margin:0px">
	
	<table cellpadding="1" cellspacing="1" border="0" width="100%" >
			<tr>
				<td align="left" valign="top"><small><font color="#6699cc"><b>' . AS_CASD_USTAW3 . '</b></font></small>&nbsp;
				</td>
				<td align="right">';

    if (isset($change['settings_ustawienia'])) {
        $result .= '<div style="float:rigth;padding:2px">								
				<input type=hidden name=change[ch_settings_ustawienia] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/act.gif" title="Zatwierd?" border="0" style="background-color:transparent;">							&nbsp;
				<a href="' . $global_link . '"><img src="img/noact.gif" title="Rezygnacja" border="0" style="background-color:transparent;"></a>
				</div>';
    } else {
        $result .= '<div style="float:rigth;padding:3px">								
				<input type=hidden name=change[settings_ustawienia] value=1>
				<input type="hidden" name="edit_form" value="1">
				<input type="image" src="img/edit.gif" title="Edycja" border="0" style="background-color:transparent;"></div>';

    }

    $result .= '</td>	
			</tr>
			</table>';
    if (isset($change['settings_ustawienia'])) {
        $result .= '<table cellpadding="0" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
		<tr>
<td width="10%">&nbsp;</td>
		<td width="40%"><b>' . AS_CASES_TYPE . ':</b>	' . wysw_typ_sprawy('type_id', $row['type_id'], 0, '') . '</td>
		<td width="10%">&nbsp;</td>
		<td width="40%">
		<b>' . AS_CASES_GENRE . ':</b>	<select name="genre_id" style="font-size: 8pt;">
			<option value=""></option>';
        $query = "SELECT genre_id, value, value_eng FROM coris_assistance_cases_genres WHERE type_id = '" . $row['type_id'] . "' ORDER BY `sort`,genre_id";

        $mysql_result = mysql_query($query);
        while ($row2 = mysql_fetch_array($mysql_result)) {
            $result .= '<option value="' . $row2['genre_id'] . '" ' . (($row2['genre_id'] == $row['genre_id']) ? "selected" : "") . '>' . (($lang == 'en' && $row2['value_eng'] != '') ? $row2['value_eng'] : $row2['value']) . '</option>';
        }
        $result .= '</select>
						</td>
					</tr>
			</table>		
				
<br>
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=95%>
			  <tr>
				
				<td width="25%" >
					<input type="checkbox" id="ambulatory" name="ambulatory" value="1" style="background: #dfdfdf" ' . (($row['ambulatory']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'ambulatory\');">' . AS_CASD_AMB . '</font>
				</td>	
				<td width="25%">
					<input type="checkbox" id="hospitalization" name="hospitalization"  value="1" style="background: #dfdfdf" ' . (($row['hospitalization']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'hospitalization\');">' . AS_CASES_HOSP . '</font>
				</td>
				<td width="25%">
				<input type="checkbox" id="decease" name="decease"  value="1" style="background: #dfdfdf" ' . (($row['decease']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'decease\');">' . AS_CASES_ZGON . '</font>
				</td>	
				<td width="25%">
					<input type="checkbox" id="transport" name="transport"  value="1" style="background: #dfdfdf" ' . (($row['transport']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'transport\');">' . AS_CASES_TRANSP . '</font>
				</td>		
			
			   </tr>
			<tr><td>
				<input type="checkbox" id="holowanie"  name="holowanie"  value="1" style="background: #dfdfdf" ' . (($row['holowanie']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'holowanie\');">' . TOWING . '</font>
			</td>	
			<td>
<input type="checkbox" id="wynajem_samochodu" name="wynajem_samochodu"  value="1" style="background: #dfdfdf" ' . (($row['wynajem_samochodu']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'wynajem_samochodu\');">' . AS_CASES_WYNSAM . '</font>
			</td>  
			<td><input type="checkbox" id="naprawa_na_miejscu" name="naprawa_na_miejscu"  value="1" style="background: #dfdfdf" ' . (($row['naprawa_na_miejscu']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'naprawa_na_miejscu\');">' . AS_CASES_NAPRNAMIEJSC . '</font></td>
			<td><input type="checkbox" id="przewoz_osob" name="przewoz_osob"  value="1" style="background: #dfdfdf" ' . (($row['przewoz_osob']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'przewoz_osob\');">' . AS_CASES_PRZEWOSB . '</font></td>

			</tr>
			<tr>
				<td>
				<input type="checkbox" id="only_info" name="only_info"  value="1" style="background: #dfdfdf" ' . (($row['only_info']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'only_info\');">' . AS_CASD_TYLKINF . '</font>
				</td><td>
				<input type="checkbox" id="costless" name="costless" value="1"  style="background: #dfdfdf" ' . (($row['costless']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'costless\');">' . AS_CASES_BEZKOSZT . '</font>
				</td><td nowrap>
					<input type="checkbox" id="unhandled" name="unhandled"  value="1" style="background: #dfdfdf" ' . (($row['unhandled']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'unhandled\');">' . AS_CASES_BEZRYCZHON2 . '</font>
				</td><td>
					<input type="checkbox" id="operational" name="operational"  value="1" style="background: #dfdfdf" ' . (($row['operational']) ? "checked" : "") . '   title="' . ($row['operational_user_id'] > 0 ? 'Data: ' . $row['operational_date'] . "\n" . "U?ytkownik: " . getUserName($row['operational_user_id']) : '') . '">&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'operational\');" title="' . ($row['operational_user_id'] > 0 ? 'Data: ' . $row['operational_date'] . "\n" . "U?ytkownik: " . getUserName($row['operational_user_id']) : '') . '">OPERACYJNA</font>
					<input type="hidden" id="operational_old" name="operational_old"  value="' . $row['operational'] . '" >
					
				&nbsp;					
				&nbsp;					
				
									
					<input type="checkbox" id="liquidation" name="liquidation"  value="1" style="background: #dfdfdf" ' . (($row['liquidation']) ? "checked" : "") . '   title="' . ($row['liquidation'] == 1 ? 'Data: ' . $row['liquidation_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_user_id']) : '') . '" ' . (check_access_liquidation() ? '' : 'disabled') . '>&nbsp;<font style="cursor: default" onclick="' . (check_access_liquidation() ? 'FormcheckboxSelect(\'liquidation\');' : '') . '" title="' . ($row['liquidation'] == 1 ? 'Data: ' . $row['liquidation_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_user_id']) : '') . '"  >' . AS_CASES_LIKWIDAC . '</font>
					<input type="hidden" id="liquidation_old" name="liquidation_old"  value="' . $row['liquidation'] . '" >
				</td>
			</tr>
			<tr>
					<td colspan="2">';
        if ($row['archive'] == 1) {
            $result .= '<input type="checkbox" id="archive" name="archive"  value="0" style="background: #dfdfdf"><font style="cursor: default" onclick="FormcheckboxSelect(\'archive\');"> <b> ' . AS_CASE_OPENAGAIN . '</b></font>';
        } else {
            $result .= '<input type="checkbox" id="archive" name="archive"  value="1" style="background: #dfdfdf"><font style="cursor: default" onclick="FormcheckboxSelect(\'archive\');"> <b> ' . AS_CASE_SENDARCHIV . '</b></font>';
        }

        if ($row['archive0'] == 1) {
            $result .= '<br><input type="checkbox" id="archive0" name="archive0"  value="1" style="background: #dfdfdf"  ' . (($row['archive0']) ? "checked" : "") . '  ' . (check_access_archive0() && $row['archive'] == 0 ? '' : 'disabled') . '>
                            <input type="hidden" id="archive0_old" name="archive0_old"  value="' . $row['archive0'] . '" >';
            $result .= '<b>Archiwum 1: ' . $row['archive0_date'] . ', ' . Application::getUserName($row['archive0_user_id']) . '</b>';

        }
        $result .= '</td>
					
					
					<td><input type="checkbox" id="send_to_simple" name="send_to_simple"  value="1" style="background: #dfdfdf" ' . (($row['send_to_simple'] == 1) ? "checked" : "") . '><font style="cursor: default" onclick="FormcheckboxSelect(\'send_to_simple\');"> ' . AS_CASD_SENDTOSIMPLE . '</font></td>
					<td>
					<input type="checkbox" id="backoffice" name="backoffice"  value="1" style="background: #dfdfdf" ' . (($row['backoffice'] == 1) ? "checked" : "") . '   title="' . ($row['backoffice'] == 1 ? 'Data: ' . $row['backoffice_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_user_id']) : '') . '" ' . '>&nbsp;<font style="cursor: default" onclick="FormcheckboxSelect(\'backoffice\');" title="' . ($row['backoffice'] == 1 ? 'Data: ' . $row['backoffice_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_user_id']) : '') . '"  >BACK-OFFICE</font>
					<input type="hidden" id="backoffice_old" name="backoffice_old"  value="' . $row['backoffice'] . '" ></td>
				</tr>	
		</table><br>';
    } else {
        $result .= '<table cellpadding="0" cellspacing="0" border="0" bgcolor="#e0e0e0" width="100%" >
		<tr>
<td width="10%">&nbsp;</td>
														<td width="40%">
														<b>' . AS_CASES_TYPE . ':</b> ' . wysw_typ_sprawy('type_id', $row['type_id'], 1, '') . '
											</td>
														<td width="10%">&nbsp;</td>
														<td width="40%">
														<b>' . AS_CASES_GENRE . ':</b>	<select disabled>';
        $query = "SELECT genre_id, value, value_eng FROM coris_assistance_cases_genres WHERE type_id = " . $row['type_id'] . " AND genre_id=" . $row['genre_id'];
        $mysql_result = mysql_query($query);
        $row2 = mysql_fetch_array($mysql_result);
        $result .= '<option>' . (($lang == 'en' && $row2['value_eng'] != '') ? $row2['value_eng'] : $row2['value']) . '</option>';
        $result .= '
						</select></td>
					</tr>
			</table>		
				
<br>
		<table cellpadding="1" cellspacing="0" border="1" align="center" width=95%>
			  <tr>
				
				<td width="25%" >
					<input type="checkbox"  disabled  disabled id="ambulatory" name="ambulatory" value="1" style="background: #dfdfdf" ' . (($row['ambulatory']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASD_AMB . '</font>
				</td>	
				<td width="25%">
					<input type="checkbox"  disabled id="hospitalization" name="hospitalization"  value="1" style="background: #dfdfdf" ' . (($row['hospitalization']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_HOSP . '</font>
				</td>
				<td width="25%">
				<input type="checkbox"  disabled id="decease" name="decease"  value="1" style="background: #dfdfdf" ' . (($row['decease']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_ZGON . '</font>
				</td>	
				<td width="25%">
					<input type="checkbox"  disabled id="transport" name="transport"  value="1" style="background: #dfdfdf" ' . (($row['transport']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_TRANSP . '</font>
				</td>		
		
			   </tr>
			<tr><td>
				<input type="checkbox"  disabled id="holowanie"  name="holowanie"  value="1" style="background: #dfdfdf" ' . (($row['holowanie']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . TOWING . '</font>
			</td>	
			<td>
<input type="checkbox"  disabled id="wynajem_samochodu" name="wynajem_samochodu"  value="1" style="background: #dfdfdf" ' . (($row['wynajem_samochodu']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_WYNSAM . '</font>
			</td>  
			<td><input disabled type="checkbox" id="wynajem_samochodu" name="naprawa_na_miejscu"  value="1" style="background: #dfdfdf" ' . (($row['naprawa_na_miejscu']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_NAPRNAMIEJSC . '</font></td>
			<td><input disabled  type="checkbox" id="przewoz_osob" name="przewoz_osob"  value="1" style="background: #dfdfdf" ' . (($row['przewoz_osob']) ? "checked" : "") . '>&nbsp;<font style="cursor: default">' . AS_CASES_PRZEWOSB . '</font></td>

			</tr>
			<tr>
				<td>
				<input type="checkbox"  disabled id="only_info" name="only_info"  value="1" style="background: #dfdfdf" ' . (($row['only_info']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASD_TYLKINF . '</font>
				</td><td>
				<input type="checkbox"  disabled id="costless" name="costless" value="1"  style="background: #dfdfdf" ' . (($row['costless']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_BEZKOSZT . '</font>
				</td><td nowrap>
					<input type="checkbox"  disabled id="unhandled" name="unhandled"  value="1" style="background: #dfdfdf" ' . (($row['unhandled']) ? "checked" : "") . '>&nbsp;<font style="cursor: default" >' . AS_CASES_BEZRYCZHON2 . '</font>
				</td><td>
						<input disabled type="checkbox" id="operational" name="operational"  value="1" style="background: #dfdfdf" ' . (($row['operational']) ? "checked" : "") . '   title="' . ($row['operational_user_id'] > 0 ? 'Data: ' . $row['operational_date'] . "\n" . "U?ytkownik: " . getUserName($row['operational_user_id']) : '') . '">&nbsp;<font style="cursor: default" title="' . ($row['operational_user_id'] > 0 ? 'Data: ' . $row['operational_date'] . "\n" . "U?ytkownik: " . getUserName($row['operational_user_id']) : '') . '">OPERACYJNA</font>
					<input type="hidden" id="operational_old" name="operational_old"  value="' . $row['operational'] . '" >
					
				&nbsp;					
				&nbsp;					
			
				
				 <input type="checkbox" disabled id="liquidation" name="liquidation"  value="1" style="background: #dfdfdf" ' . (($row['liquidation']) ? "checked" : "") . ' title="' . ($row['liquidation'] == 1 ? 'Data: ' . $row['liquidation_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_user_id']) : '') . '">&nbsp;<font style="cursor: default" title="' . ($row['liquidation'] == 1 ? 'Data: ' . $row['liquidation_date'] . "\n" . "U?ytkownik: " . getUserName($row['liquidation_user_id']) : '') . '">' . AS_CASES_LIKWIDAC . '</font>
				 <input type="hidden" id="liquidation_old" name="liquidation_old"  value="' . $row['liquidation'] . '" >
				</td>
				<tr>
					<td colspan="2">';
        if ($row['archive'] == 1) {
            $result .= '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Archiwum - Zamkni?cie: ' . $row['archive_date'] . ', ' . Application::getUserName($row['archive_user_id']) . '</b>';
        } else {
            $result .= '<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . AS_CASE_CASEOPEN . '</b>';
        }

        if ($row['archive0'] == 1) {
            $result .= '<br>';
            $result .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Archiwum 1: ' . $row['archive0_date'] . ', ' . Application::getUserName($row['archive0_user_id']) . '</b>';

        }
        $result .= '</td>
					
				
					<td><input type="checkbox" id="send_to_simple" name="send_to_simple"  value="1" style="background: #dfdfdf" ' . (($row['send_to_simple'] == 1) ? "checked" : "") . ' disabled>' . AS_CASD_SENDTOSIMPLE . '</td>
					<td><input type="checkbox" disabled id="backoffice" name="backoffice"  value="1" style="background: #dfdfdf" ' . (($row['backoffice'] == 1) ? "checked" : "") . ' title="' . ($row['backoffice'] == 1 ? 'Data: ' . $row['backoffice_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_user_id']) : '') . '">&nbsp;<font style="cursor: default" title="' . ($row['backoffice'] == 1 ? 'Data: ' . $row['backoffice_date'] . "\n" . "U?ytkownik: " . getUserName($row['backoffice_user_id']) : '') . '">BACK-OFFICE</font>
				 <input type="hidden" id="backoffice_old" name="backoffice_old"  value="' . $row['backoffice'] . '" >
				 </td>
				</tr>	
			</tr>
		</table><br>';
    }

    $result .= '</form>';
    return $result;

}

function wysw_typ_sprawy($name, $def, $tryb = 0, $option = '')
{
    $lang = $_SESSION['GUI_language'];

    $result = '';
    if ($tryb) {
        $query = "SELECT type_id, value, value_eng FROM coris_assistance_cases_types WHERE type_id ='$def'";
        $mysql_result = mysql_query($query);
        $row2 = mysql_fetch_array($mysql_result);
        $result .= '<select name="' . $name . '" style="font-size: 8pt;width: 150px;" disabled>';
        $result .= '<option value="' . $row2['type_id'] . '" ' . (($row2['type_id'] == $def) ? "selected" : "") . '>' . (($lang == 'en' && $row2['value_eng'] != '') ? $row2['value_eng'] : $row2['value']) . '</option>';
        $result .= '</select>';
        return $result;
    } else {
        $result .= '<select name="' . $name . '" style="font-size: 8pt;" ' . $option . '>
					<option value=""></option>';

        $query = "SELECT type_id, value, value_eng FROM coris_assistance_cases_types ORDER BY type_id";

        $mysql_result = mysql_query($query);
        while ($row2 = mysql_fetch_array($mysql_result)) {
            $result .= '<option value="' . $row2['type_id'] . '" ' . (($row2['type_id'] == $def) ? "selected" : "") . '>' . (($lang == 'en' && $row2['value_eng'] != '') ? $row2['value_eng'] : $row2['value']) . '</option>';
        }
        $result .= '</select>';
    }
    return $result;
}

function check_access_liquidation()
{
    global $accesss_change_liquidation;

    return in_array(Application::getCurrentUser(), $accesss_change_liquidation) ? 1 : 0;

}

function check_access_archive0()
{
    global $accesss_change_archive0;

    return in_array(Application::getCurrentUser(), $accesss_change_archive0) ? 1 : 0;

}

?>
