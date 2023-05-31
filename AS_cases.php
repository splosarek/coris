<?php include('include/include.php');
require_once('access.php');

$lang = $_SESSION['GUI_language'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
    <title></title>
    <script language="javascript" src="Scripts/mootools.js"></script>
    <link href="Styles/general.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/032bf0d93e.js" crossorigin="anonymous"></script>
</head>

<script language="JavaScript1.2">


    <!--

    var blnDOM = false, blnIE4 = false, blnNN4 = false;

    if (document.layers) blnNN4 = true;
    else if (document.all) blnIE4 = true;
    else if (document.getElementById) blnDOM = true;

    function getKeycode(e) {
        if (blnNN4) {
            var NN4key = e.which
            if (NN4key == 13)
                ClearSubmitSearch();
        }
        if (blnDOM) {
            var blnkey = e.which
            if (blnkey == 13) {
                //re = /(\d+)/;
                //arr = re.exec(top.group.cols);
                //alert(arr[1]);
                ClearSubmitSearch();
            }
        }

        if (blnIE4) {
            var IE4key = event.keyCode
            if (IE4key == 13) {
                //re = /(\d+)/;
                //arr = re.exec(top.group.cols);
                //alert(arr[1]);
                ClearSubmitSearch();
            }
        }

    }

    document.onkeydown = getKeycode
    if (blnNN4) document.captureEvents(Event.KEYDOWN)

    function previous() {
        //	var s = form1;
        if (document.getElementById('step').value != 0) {
            document.getElementById('end').value = 0;
            document.getElementById('step').value = parseInt(document.getElementById('step').value) - 1;
            SubmitSearch();
        }
    }

    function next() {
        //	var s = form1;
        if (document.getElementById('end').value != 1) {
            document.getElementById('step').value = parseInt(document.getElementById('step').value) + 1;
            SubmitSearch();
        }
    }

    function lettersearch(l) {
        //	var s = form1;
        clear_step_end();
        document.getElementById('letter').value = l;
        SubmitSearch();
    }

    function clear_step_end() {
        //var s = form1;
        document.getElementById('step').value = 0;
        document.getElementById('end').value = 0;
    }

    function clear_all() {
        //var s = form1;
        document.getElementById('letter').value = '';
        document.getElementById('step').value = 0;
        document.getElementById('end').value = 0;
    }

    function ClearSubmitSearch() {
        clear_step_end();
        SubmitSearch();
    }

    function SubmitSearch() {

        var corisBranchId = '';
        if (document.getElementById('coris_branch_id')) {
            corisBranchId = document.getElementById('coris_branch_id').value;
        }

        var url = "AS_cases_frame.php?action=1&paxSurname=" + document.getElementById('paxSurname').value + "&caseId=" + document.getElementById('caseId').value +
            "&year=" + document.getElementById('year').value + "&paxName=" + document.getElementById('paxName').value + "&policy=" + document.getElementById('policy').value +
            "&policy_series=" + document.getElementById('policy_series').value + "&paxDob_d=" + document.getElementById('paxDob_d').value + "&paxDob_m=" + document.getElementById('paxDob_m').value +
            "&paxDob_y=" + document.getElementById('paxDob_y').value + "&country=" + document.getElementById('country').value + "&dateFrom_d=" + document.getElementById('dateFrom_d').value +
            "&dateFrom_m=" + document.getElementById('dateFrom_m').value + "&dateFrom_y=" + document.getElementById('dateFrom_y').value + "&dateTo_d=" + document.getElementById('dateTo_d').value +
            "&dateTo_m=" + document.getElementById('dateTo_m').value + "&dateTo_y=" + document.getElementById('dateTo_y').value + "&eventDateFrom_d=" + document.getElementById('eventDateFrom_d').value +
            "&eventDateFrom_m=" + document.getElementById('eventDateFrom_m').value + "&eventDateFrom_y=" + document.getElementById('eventDateFrom_y').value + "&eventDateTo_d=" + document.getElementById('eventDateTo_d').value +
            "&eventDateTo_m=" + document.getElementById('eventDateTo_m').value + "&eventDateTo_y=" + document.getElementById('eventDateTo_y').value + "&userId=" + document.getElementById('userId').value +
            "&amount=" + document.getElementById('amount').value + "&step=" + document.getElementById('step').value + "&letter=" + document.getElementById('letter').value +
            "&sort=" + document.getElementById('sort').value + "&case_status=" + document.getElementById('case_status').value + "&transport=" + document.getElementById('transport').checked +
            "&decease=" + document.getElementById('decease').checked + "&ambulatory=" + document.getElementById('ambulatory').checked + "&hospitalization=" + document.getElementById('hospitalization').checked +
            "&costless=" + document.getElementById('costless').checked + "&unhandled=" + document.getElementById('unhandled').checked + "&reclamation=" + document.getElementById('reclamation').checked + "&fraud=" + document.getElementById('fraud').checked +
            "&new_documents=" + document.getElementById('new_documents').checked + "&new_documents_sort=" + document.getElementById('new_documents_sort').checked + '&client_id=' + document.getElementById('client_id').value +
            '&attention=' + document.getElementById('attention').checked + '&attention2=' + document.getElementById('attention2').checked + '&city=' + document.getElementById('city').value +
            '&wynajem_samochodu=' + document.getElementById('wynajem_samochodu').checked + '&holowanie=' + document.getElementById('holowanie').checked + '&marka_model=' + document.getElementById('marka_model').value +
            '&nr_rej=' + document.getElementById('nr_rej').value + '&signal_ready_export=' + document.getElementById('signal_ready_export').checked + '&signal_export=' + document.getElementById('signal_export').checked +
            '&signal_nexport=' + document.getElementById('signal_nexport').checked + '&userRole=' + document.getElementById('userRole').value + '&dok_cat=' + document.getElementById('dok_cat').value +
            '&case_type=' + document.getElementById('case_type').value + '&client_ref=' + document.getElementById('client_ref').value + '&naprawa_na_miejscu=' + document.getElementById('naprawa_na_miejscu').checked +
            '&liquidation=' + document.getElementById('liquidation').checked + '&coris_branch_id=' + corisBranchId + '&beneficjent=' + $('beneficjent').checked +
            "&liquidationStopDateFrom_d=" + $('liquidationStopDateFrom_d').value + "&liquidationStopDateFrom_m=" + $('liquidationStopDateFrom_m').value + "&liquidationStopDateFrom_y=" + $('liquidationStopDateFrom_y').value +
            "&liquidationStopDateTo_d=" + $('liquidationStopDateTo_d').value + "&liquidationStopDateTo_m=" + $('liquidationStopDateTo_m').value + "&liquidationStopDateTo_y=" + $('liquidationStopDateTo_y').value +
            "&liquidationStartDateFrom_d=" + $('liquidationStartDateFrom_d').value + "&liquidationStartDateFrom_m=" + $('liquidationStartDateFrom_m').value + "&liquidationStartDateFrom_y=" + $('liquidationStartDateFrom_y').value +
            "&liquidationStartDateTo_d=" + $('liquidationStartDateTo_d').value + "&liquidationStartDateTo_m=" + $('liquidationStartDateTo_m').value + "&liquidationStartDateTo_y=" + $('liquidationStartDateTo_y').value +
            "&case_status_completed_operationally=" + document.getElementById('case_status_completed_operationally').value + "&case_status_liqidation=" + document.getElementById('case_status_liqidation').value + "&backoffice=" +
            document.getElementById('backoffice').checked + "&appeal=" + document.getElementById('appeal').checked + "&taxi=" + document.getElementById('taxi').checked
        ;

        assistcases_frame.location = url;
    }

    // TODO: Poprawi? - aby nie by?o "for"
    /*            function move(s) {
                    e = window.event;
                    var keyInfo = String.fromCharCode(e.keyCode);

                    if (e['keyCode'] != 9 && e['keyCode'] != 16 && e['keyCode'] != 8) {
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

                    if (e['keyCode'] == 8) {
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
    */

    function move_formant(s, e) {
        var form1 = document.getElementById('form1');
        //e = window.event;
        //var keyInfo = String.fromCharCode(e.keyCode);
        if (window.event)
            var keyInfo = window.event.keyCode; // IE
        else
            var keyInfo = e.charCode;

        if (keyInfo != 9 && keyInfo != 16 && keyInfo != 8) {
            for (var i = 0; i < form1.length; i++) {
                if (s.name == form1.elements[i].name) {
                    if ((form1.elements[i].value.length == 2)) {
                        form1.elements[i + 1].focus();
                        return false;
                    }
                }
            }
        }
    }

    function remove_formant(s, e) {
        var form1 = document.getElementById('form1');
        if (window.event)
            var keyInfo = window.event.keyCode; // IE
        else
            var keyInfo = e.charCode;

        if (keyInfo == 8) {
            for (var i = 0; i < form1.length; i++) {
                if (s.name == form1.elements[i].name) {
                    if ((form_reg.elements[i].value.length == 0)) {
                        form1.elements[i - 1].focus();
                        var rng = form1.elements[i - 1].createTextRange();
                        rng.select();
                        return false;
                    }
                }
            }
        }
    }

    // Kalendarz
    function y2k(number) {
        return (number < 1000) ? number + 1900 : number;
    }

    var today;
    var day;
    var month;
    var year

    function newWindowCal(name) {

        today = new Date();
        day = today.getDate();
        month = today.getMonth();
        year = y2k(today.getYear());

        var width = 260;
        var height = 200;
        var left = (screen.availWidth - width) / 2;
        var top = ((screen.availHeight - 0.2 * screen.availHeight) - height) / 2;
        mywindow = window.open('calendar.php?name=' + name, '', 'resizable=no,width=' + width + ',height=' + height + ',left=' + left + ',top=' + top);
    }

    //-->
</script>
<body bgcolor="#dfdfdf" onload="document.getElementById('caseId').focus();">
<style>
    body {
        margin-top: 0.1cm;
        margin-bottom: 0.1cm;
        margin-left: 0.1cm;
        margin-right: 0.1cm;
        scrollbar-3dlight-color: #cccccc;
        scrollbar-arrow-color: #dddddd;
        scrollbar-base-color: #6699cc;
        scrollbar-dark-shadow-color: #dddddd;
        scrollbar-face-color: #6699cc;
        scrollbar-highlight-color: #eeeeee;
        scrollbar-shadow-color: #dddddd;
    }

    td {
        font-size: 7pt;
    }
</style>
<script type="text/javascript">
    function zaznacz_uwaga(s) {

        at = document.getElementById('attention');
        at2 = document.getElementById('attention2');

        if (s == 'attention') {
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
</script>
<center>
    <form name="form1" id="form1">
        <table cellpadding="2" cellspacing="0" border="0">
            <tr valign="middle">
                <td colspan="2" nowrap>
                    <?php echo($lang == 'en' ? 'Cases' : 'Sprawy'); ?>:
                    <select name="case_status" id="case_status" style="font-size: 9px" onChange="ClearSubmitSearch()">
                        <option value="2" selected><?php echo($lang == 'en' ? 'all' : 'wszystkie'); ?></option>
                        <option value="0"><?php echo($lang == 'en' ? 'open' : 'otwarte'); ?></option>
                        <option value="1"><?php echo($lang == 'en' ? 'archive' : 'archiwum'); ?></option>

                    </select>


                    <select name="case_status_completed_operationally" id="case_status_completed_operationally"
                            style="font-size: 9px" onChange="ClearSubmitSearch()">
                        <option value="0" selected><?php echo($lang == 'en' ? 'all' : 'wszystkie'); ?></option>
                        <option value="1"><?php echo($lang == 'en' ? 'completed operationally' : 'zakończone operacyjnie'); ?></option>
                        <option value="2"><?php echo($lang == 'en' ? 'not completed operationally' : 'nie zakończone operacyjnie'); ?></option>
                    </select>

                    <select name="case_status_liqidation" id="case_status_liqidation" style="font-size: 9px"
                            onChange="ClearSubmitSearch()">
                        <option value="0" selected><?php echo($lang == 'en' ? 'all' : 'wszystkie'); ?></option>
                        <option value="1"><?php echo($lang == 'en' ? 'all liquidation' : 'wszystkie likwidacyjne'); ?></option>
                        <option value="2"><?php echo($lang == 'en' ? 'not complete liquidation' : 'likwidacja niezakończona'); ?></option>
                        <option value="3"><?php echo($lang == 'en' ? 'complete liquidation' : 'likwidacja zakończona'); ?></option>

                        <option value="0"> -----</option>
                        <option value="4"><?php echo($lang == 'en' ? 'all back-office' : 'wszystkie back-office'); ?></option>
                        <option value="5"><?php echo($lang == 'en' ? 'not complete back-office' : 'back-office niezakończona'); ?></option>
                        <option value="6"><?php echo($lang == 'en' ? 'complete back-office' : 'back-office zakończona'); ?></option>
                    </select>


                    <!-- <input type="checkbox" name="archive" id="archive" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1" title="<?= AS_CASES_ARCH ?>" ><img  title="<?= AS_CASES_ARCH ?>" src="img/archiwum.gif" border="0"  style="cursor: help;"> -->

                    <input type="checkbox" name="liquidation" id="liquidation" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1" title="<?= AS_TITLE_TCLAIMS ?>"><img
                            title="<?= AS_TITLE_TCLAIMS ?>" src="img/likwidacja.gif" border="0" style="cursor: help;">

                    <span style="padding:6px 1px 1px 1px;background: #dfdfdf; border: #FF0000 1px solid;"><input
                                type="checkbox" name="attention" id="attention" title="<?= AS_TITLE_RPT ?>"
                                onclick="zaznacz_uwaga('attention');ClearSubmitSearch()" style="background: #dfdfdf; "
                                value="1"></span>
                    <img title="<?= AS_TITLE_RPT ?>" src="img/rpt-bierzace-uwaga.gif" border="0" style="cursor: help;">

                    <span style="padding:6px 1px 1px 1px;background: #dfdfdf; border: #6699cc 1px solid;"><input
                                type="checkbox" name="attention2" id="attention2" title="<?= AS_TITLE_UWAGA ?>"
                                onclick="zaznacz_uwaga('attention2');ClearSubmitSearch()" style="background: #dfdfdf; "
                                value="1"></span>
                    <img title="<?= AS_TITLE_UWAGA ?>" src="img/rpt-bierzace-uwaga.gif" border="0"
                         style="cursor: help;">
                    <input type="checkbox" name="holowanie" id="holowanie" title="<?= AS_CASES_HOL ?>"
                           onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_HOL ?>" src="img/holowanie.gif" border="0" style="cursor: help;">
                    <input type="checkbox" name="wynajem_samochodu" id="wynajem_samochodu"
                           title="<?= AS_CASES_WYNSAM ?>" onclick="ClearSubmitSearch()" style="background: #dfdfdf;"
                           value="1">
                    <img title="<?= AS_CASES_WYNSAM ?>" src="img/wynajem-samochodu.gif" border="0"
                         style="cursor: help;">

                    <input type="checkbox" name="naprawa_na_miejscu" id="naprawa_na_miejscu"
                           onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"
                           title="<?= AS_CASES_NAPRNAMIEJSC ?>">
                    <img title="<?= AS_CASES_NAPRNAMIEJSC ?>" src="img/naprawa-na-miejscu.gif" border="0"
                         style="cursor: help;">

                    <input type="checkbox" name="transport" id="transport" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_TRANSP ?>" src="img/transport.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <input type="checkbox" name="decease" id="decease" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_ZGON ?>" src="img/zgon.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <input type="checkbox" name="ambulatory" id="ambulatory" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_AMB ?>" src="img/ambulatoryjna.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <input type="checkbox" name="hospitalization" id="hospitalization" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_HOSP ?>" src="img/hospitalizacja.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <!-- NOWE -->
                    <input type="checkbox" name="costless" id="costless" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_BEZKOSZT ?>" src="img/bez-kosztow.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <input type="checkbox" name="unhandled" id="unhandled" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_BEZRYCZHON ?>" src="img/bez-ryczaltu.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <input type="checkbox" name="reclamation" id="reclamation" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_CASES_REKL ?>" src="img/reklamacja.gif" border="0" style="cursor: help;">

                    &nbsp;
                    <input type="checkbox" name="fraud" id="fraud" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="<?= AS_TITLE_FRAUD ?>" src="img/fraud.gif" border="0" style="cursor: help;">
                    &nbsp;
                    <input type="checkbox" name="backoffice" id="backoffice" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="Back-Officce" src="img/backoffice.gif" border="0" style="cursor: help;">

                    <input type="checkbox" name="appeal" id="appeal" onclick="ClearSubmitSearch()"
                           style="background: #dfdfdf;" value="1">
                    <img title="Odwołanie" src="img/appeal.gif" border="0" style="cursor: help;">

                    <input type="checkbox" name="taxi" id="taxi" onclick="ClearSubmitSearch()" title="Taxi"
                           style="background: #dfdfdf;" value="1">
                    <i title="Taxi" class="fas fa-taxi fa-lg" style="cursor: help"></i>
                    <!--                        <img  title="TAXI" src="img/appeal.gif" border="0" style="cursor: help;">-->

                </td>
                <td width="70" align="right">
                    <select name="userRole" id="userRole" onChange="ClearSubmitSearch()" style="font-size: 9px">
                        <option value="3"><?= AS_CASES_OPER ?></option>
                        <option value="1"><?= AS_CASES_RED ?></option>
                        <option value="2"><?= AS_CASES_CLHANDL ?></option>
                    </select>
                </td>
                <td>
                    <div align="left">
                        <?php
                        $query = "SELECT user_id, surname, name FROM coris_users WHERE name NOT LIKE '' AND active = 1 AND (department_id = 7 OR department_id = 4) ORDER BY surname";

                        if ($result = mysql_query($query)) {
                            echo "<select name=\"userId\" id=\"userId\" onchange=\"ClearSubmitSearch()\" tabindex=\"11\" style=\"font-size: 9px;\">";
                            echo "<option></option>";
                            while ($row = mysql_fetch_array($result))
                                //echo ($selected == $row[0]) ? "<option value=\"$row[0]\" selected>$row[1], $row[2]</option>" : "<option value=\"$row[0]\">$row[1], $row[2]</option>";
                                echo "<option value=\"$row[0]\">$row[1], $row[2]</option>";
                            echo "</select>";
                            mysql_free_result($result);
                        } else {
                            die(mysql_error());
                        }

                        if ($_SESSION['coris_branch'] == 1) {
                            echo print_user_coris_branchCase('coris_branch_id', 0, 'onChange="ClearSubmitSearch()"');
                        } else if ($_SESSION['coris_branch'] == 2) {
                            echo print_user_coris_branch_de('coris_branch_id', 0, 'onChange="ClearSubmitSearch()"');
                        } else {

                        }
                        ?>

                    </div>


                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="70" align="right" bgcolor="#dfdfdf"><b><small><?= AS_CASES_NRSZKOD ?></small></b></td>
                <td bgcolor="#dfdfdf">
                    <div align="left">
                        <input tabindex="1" type="text" name="caseId" id="caseId" style="text-align: right" size="10">
                        <input tabindex="2" type="text" name="year" id="year" style="text-align: center" value=""
                               size="4">
                        &nbsp;&nbsp;&nbsp; <small><strong><?= AS_CASES_NRKLIENT ?></strong></small>
                        <input tabindex="3" type="text" name="client_id" id="client_id" style="text-align: right"
                               size="10">
                        <td width="70" align="right" title="<?= AS_CASES_CLREF_L ?>">
                            <small><?= AS_CASES_DAT ?></small>&nbsp;
                        </td>
                        <td>
                            <div align="left">
                                <input tabindex="12" type="text" name="paxDob_d" id="paxDob_d" size="1" maxlength="2"
                                       onkeyup="move_formant(this,event);" style="text-align: center">
                                <input tabindex="13" type="text" name="paxDob_m" id="paxDob_m" size="1" maxlength="2"
                                       onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);"
                                       style="text-align: center">
                                <input tabindex="14" type="text" name="paxDob_y" id="paxDob_y" size="4" maxlength="4"
                                       onkeydown="remove_formant(this,event);" style="text-align: center">
                                <a href="javascript:void(0)" onclick="newWindowCal('paxDob')"
                                   style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>

                            </div>
                        </td>
            </tr>
            <tr>
                <td width="70" align="right">
                    <small><b><?= AS_CASES_NAZW ?></b></small>
                </td>
                <td>
                    <div align="left">
                        <input tabindex="3" type="text" name="paxSurname" id="paxSurname" size="20">
                        &nbsp;&nbsp; <small><?= AS_CASES_IMIE ?></small> <input tabindex="4" type="text" name="paxName"
                                                                                id="paxName" size="15">
                        &nbsp;&nbsp;<input type="checkbox" name="beneficjent" id="beneficjent"
                                           onclick="ClearSubmitSearch()"> <small><?= AS_BENEFICIARY ?></small>
                    </div>
                </td>
                <td width="70" align="right"><small><?= AS_CASES_NRPOL ?></small></td>
                <td>
                    <div align="left">
                        <small><?= AS_CASES_POLSER ?>: </small><input tabindex="15" size="10" maxlength="10" type="text"
                                                                      name="policy_series" id="policy_series">
                        &nbsp;<small><?= AS_CASES_POLNO ?>: </small><input tabindex="15" size="15" type="text"
                                                                           name="policy" id="policy">
                    </div>
                </td>
            </tr>
            <tr>

                <td width="70" align="right"><small><?= AS_CASES_MARKMOD ?></small></td>
                <td>
                    <div align="left">
                        <input tabindex="4" type="text" name="marka_model" id="marka_model"
                               size="15"><small><?= AS_CASES_NRREJ ?></small>
                        <input tabindex="4" type="text" name="nr_rej" id="nr_rej"
                               size="10"><small><?= AS_CASES_CLREF ?></small>
                        <input type="text" name="client_ref" id="client_ref" size="15" maxlength="30"
                               onchange="ClearSubmitSearch();">
                    </div>
                </td>
                <td width="70" align="right">
                    <small><?= COUNTRY ?></small>
                </td>
                <td>
                    <div align="left">
                        <input tabindex="15" type="text" name="country" id="country" size="3"
                               style="text-align: center">
                        &nbsp;&nbsp;<small><?= AS_CASES_MIAST ?></small>&nbsp;
                        <input name="city" type="text" id="city" tabindex="16">
                    </div>
                </td>
            </tr>
            <tr>
                <td width="70" align="right">
                    <small><?= AS_CASES_ZDARZ ?></small>
                </td>
                <td>
                    <div align="left">
                        <input tabindex="5" type="text" name="eventDateFrom_d" id="eventDateFrom_d" size="1"
                               maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="6" type="text" name="eventDateFrom_m" id="eventDateFrom_m" size="1"
                               maxlength="2" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <input tabindex="7" type="text" name="eventDateFrom_y" id="eventDateFrom_y" size="4"
                               maxlength="4" onkeydown="remove_formant(this,event);" style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('eventDateFrom')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>&nbsp;-&nbsp;
                        <input tabindex="8" type="text" name="eventDateTo_d" id="eventDateTo_d" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="9" type="text" name="eventDateTo_m" id="eventDateTo_m" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <input tabindex="10" type="text" name="eventDateTo_y" id="eventDateTo_y" size="4" maxlength="4"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('eventDateTo')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                    </div>
                </td>
                <td width="70" align="right">
                    <small><?= AS_CASES_OTW ?></small>
                </td>
                <td>
                    <div align="left">
                        <input tabindex="18" type="text" name="dateFrom_d" id="dateFrom_d" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="19" type="text" name="dateFrom_m" id="dateFrom_m" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <input tabindex="20" type="text" name="dateFrom_y" id="dateFrom_y" size="4" maxlength="4"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('dateFrom')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>&nbsp;-&nbsp;
                        <input tabindex="21" type="text" name="dateTo_d" id="dateTo_d" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="22" type="text" name="dateTo_m" id="dateTo_m" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <input tabindex="23" type="text" name="dateTo_y" id="dateTo_y" size="4" maxlength="4"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('dateTo')" style="text-decoration: none"><img
                                    src="img/kalendarz.gif" border="0"></a>
                    </div>
                </td>
            </tr>

            <tr>
                <td align="right" nowrap>
                    <small><?= AS_CASES_LIKWSTART ?></small>
                </td>
                <td>
                    <div align="left">
                        <input tabindex="24" type="text" name="liquidationStartDateFrom_d"
                               id="liquidationStartDateFrom_d" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="25" type="text" name="liquidationStartDateFrom_m"
                               id="liquidationStartDateFrom_m" size="1" maxlength="2"
                               onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <input tabindex="26" type="text" name="liquidationStartDateFrom_y"
                               id="liquidationStartDateFrom_y" size="4" maxlength="4"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('liquidationStartDateFrom')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>&nbsp;-&nbsp;
                        <input tabindex="27" type="text" name="liquidationStartDateTo_d" id="liquidationStartDateTo_d"
                               size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="28" type="text" name="liquidationStartDateTo_m" id="liquidationStartDateTo_m"
                               size="1" maxlength="2" onkeyup="move_formant(this,event);"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <input tabindex="29" type="text" name="liquidationStartDateTo_y" id="liquidationStartDateTo_y"
                               size="4" maxlength="4" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('liquidationStartDateTo')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                    </div>
                </td>
                <td width="90" align="right">
                    <small><?= AS_CASES_LIKWSTOP ?></small>
                </td>
                <td>
                    <div align="left">
                        <input tabindex="30" type="text" name="liquidationStopDateFrom_d" id="liquidationStopDateFrom_d"
                               size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="31" type="text" name="liquidationStopDateFrom_m" id="liquidationStopDateFrom_m"
                               size="1" maxlength="2" onkeyup="move_formant(this,event);"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <input tabindex="32" type="text" name="liquidationStopDateFrom_y" id="liquidationStopDateFrom_y"
                               size="4" maxlength="4" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('liquidationStopDateFrom')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>&nbsp;-&nbsp;
                        <input tabindex="33" type="text" name="liquidationStopDateTo_d" id="liquidationStopDateTo_d"
                               size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center">
                        <input tabindex="34" type="text" name="liquidationStopDateTo_m" id="liquidationStopDateTo_m"
                               size="1" maxlength="2" onkeyup="move_formant(this,event);"
                               onkeydown="remove_formant(this,event);" style="text-align: center">
                        <input tabindex="35" type="text" name="liquidationStopDateTo_y" id="liquidationStopDateTo_y"
                               size="4" maxlength="4" onkeydown="remove_formant(this,event);"
                               style="text-align: center">
                        <a href="javascript:void(0)" onclick="newWindowCal('liquidationStopDateTo')"
                           style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                    </div>
                </td>
            </tr>

        </table>
        <table cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff">
            <tr height="15">
                <td align="center" width="30" bgcolor="#dfdfdf" nowrap>
                    <input type="checkbox" name="signal_ready_export" id="signal_ready_export" onclick="ClearSubmitSearch()" title="<?= AS_CASES_SIGNAL1 ?>" style="background: #dfdfdf;" value="1"><img
                            src="img/signal-gotowe-do-eksportu.gif" border="0" title="<?= AS_CASES_SIGNAL1 ?>"></td>
                <td align="center" width="30" bgcolor="#dfdfdf" nowrap><input type="checkbox" name="signal_export"
                                                                              id="signal_export"
                                                                              onclick="ClearSubmitSearch()"
                                                                              title="<?= AS_CASES_SIGNAL2 ?>"
                                                                              style="background: #dfdfdf;"
                                                                              value="1"><img
                            src="img/signal-wyeksportowane.gif" border="0" title="<?= AS_CASES_SIGNAL2 ?>"></td>
                <td align="center" width="30" bgcolor="#dfdfdf" nowrap><input type="checkbox" name="signal_nexport"
                                                                              id="signal_nexport"
                                                                              onclick="ClearSubmitSearch()"
                                                                              title="<?= AS_CASES_SIGNAL3 ?>"
                                                                              style="background: #dfdfdf;"
                                                                              value="1"><img
                            src="img/signal-niewyeksportowane.gif" border="0" title="<?= AS_CASES_SIGNAL3 ?>"></td>
                <td align="center" width="40" bgcolor="#dfdfdf" nowrap><input type="checkbox" name="new_documents"
                                                                              id="new_documents"
                                                                              onclick="ClearSubmitSearch()"
                                                                              title="<?= AS_CASES_NOWDOKWSPR ?>"
                                                                              style="background: #dfdfdf;"
                                                                              value="1"><img
                            src="img/nowe-dokumenty-w-sprawie.gif" border="0" title="<?= AS_CASES_NOWDOKWSPR ?>"></td>
                <td align="center" width="40" bgcolor="#dfdfdf" nowrap><input type="checkbox" name="new_documents_sort"
                                                                              id="new_documents_sort"
                                                                              onclick="ClearSubmitSearch()"
                                                                              title="<?= AS_CASES_NOWDOKWSPR ?>"
                                                                              style="background: #dfdfdf;"
                                                                              value="1"><img
                            src="img/sortuj-po-dacie.gif" border="0" title="<?= AS_CASES_NOWDOKWSPR ?>"></td>
                <td align="center" width="200" bgcolor="#dfdfdf" nowrap
                    title="Kategoria nowego dokumentu"><?= DOCUMENTS ?>&nbsp;
                    <select name="dok_cat" id="dok_cat" onChange="ClearSubmitSearch()"
                            title="Kategoria nowego dokumentu" alt="Kategoria nowego dokumentu" style="font-size: 9px"
                            onchange="ClearSubmitSearch()">
                        <?php
                        $q = "SELECT * FROM coris_fax_in_category ";
                        $mr = mysql_query($q);
                        echo '<option value="0"> ' . AS_DOC_ALL . ' </option>';
                        while ($row = mysql_fetch_array($mr)) {
                            echo '<option value="' . $row['ID'] . '">' . (($lang == 'en' && $row['name_eng'] != '') ? $row['name_eng'] : $row['name']) . '</option>';

                        }
                        ?>
                    </select></td>
                <td align="left" width="465" bgcolor="#dfdfdf"><font color="#000000"/>

                    &nbsp; <?= AS_CASES_TYPE ?>: <select name="case_type" id="case_type" onchange="ClearSubmitSearch();"
                                                         title="Typ sprawy" alt="Typ sprawy" style="font-size: 9px">
                        <option value="0"> <?= AS_DOC_ALL ?> </option><?php

                        $query = "SELECT * FROM coris_assistance_cases_types ";
                        $mysql_result = mysql_query($query);
                        while ($row = mysql_fetch_array($mysql_result)) {
                            echo '<option value="' . $row['type_id'] . '">' . (($lang == 'en' && $row['value_eng'] != '') ? $row['value_eng'] : $row['value']) . '</option>';
                        }

                        ?></select></div></td>
                &nbsp;</font></td>


                <!--
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('a');"><font color="#6699cc">a</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('b');"><font color="#6699cc">b</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('c');"><font color="#6699cc">c</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('?');"><font color="#6699cc">?</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('d');"><font color="#6699cc">d</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('e');"><font color="#6699cc">e</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('f');"><font color="#6699cc">f</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('g');"><font color="#6699cc">g</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('h');"><font color="#6699cc">h</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('i');"><font color="#6699cc">i</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('j');"><font color="#6699cc">j</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('k');"><font color="#6699cc">k</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('l');"><font color="#6699cc">l</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('?');"><font color="#6699cc">?</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('m');"><font color="#6699cc">m</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('n');"><font color="#6699cc">n</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('o');"><font color="#6699cc">o</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('p');"><font color="#6699cc">p</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('q');"><font color="#6699cc">q</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('r');"><font color="#6699cc">r</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('s');"><font color="#6699cc">s</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('?');"><font color="#6699cc">?</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('t');"><font color="#6699cc">t</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('u');"><font color="#6699cc">u</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('v');"><font color="#6699cc">v</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('w');"><font color="#6699cc">w</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('x');"><font color="#6699cc">x</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('y');"><font color="#6699cc">y</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('z');"><font color="#6699cc">z</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('?');"><font color="#6699cc">?</font></td>
                                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="lettersearch('?');"><font color="#6699cc">?</font></td>
                -->

                <td align="center" width="30" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'"
                    onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="form1.reset(); SubmitSearch();"
                    title="<?= AS_CASES_WYCZUSTWYSZ ?>">&nbsp;<font color="#6699cc" style="font-size: 12pt;"
                                                                    face="Wingdings">x</font>&nbsp;
                </td>
                <td align="center" width="50" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'"
                    onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;"
                    onclick="clear_step_end(); document.getElementById('amount').value = 100; SubmitSearch();"
                    title="<?= AS_CASES_WYSW100 ?>"><font color="#6699cc"><small>100</small></font></td>
                <td align="center" width="50" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'"
                    onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;"
                    onclick="clear_step_end(); document.getElementById('amount').value = 500; SubmitSearch();"
                    title="<?= AS_CASES_WYSW500 ?>"><font color="#6699cc"><small>500</small></font></td>
                <td align="center" width="50" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'"
                    onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;"
                    onclick="clear_step_end(); document.getElementById('amount').value  = 1000; SubmitSearch();"
                    title="<?= AS_CASES_WYSW1000 ?>"><font color="#6699cc"><small>1000</small></font></td>
                <!-- <td align="center" width="60" bgcolor="#ffffff" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#ffffff'" style="cursor: pointer;" onclick="window.open('assistcases-view-all.php','all','toolbar=no,location=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=800,height=600')" title="UWAGAWy?wietl wszystkie spraw na raz"><font color="#6699cc"><small>wszystkie</small></font></a></td>-->
                <td align="center" width="30" bgcolor="#bbbbbb" onmouseover="bgColor='#eeeeee'"
                    onmouseout="bgColor='#bbbbbb'" style="cursor: pointer;" onclick="javascript:previous();"><font
                            color="#6699cc">&lt;&lt;</font></td>
                <td align="center" width="30" bgcolor="#bbbbbb" onmouseover="bgColor='#eeeeee'"
                    onmouseout="bgColor='#bbbbbb'" style="cursor: pointer;" onclick="javascript:next();"><font
                            color="#6699cc">&gt;&gt;</font></td>
            </tr>
        </table>
        <iframe application="yes" width="100%" HIDEFOCUS height="470" frameborder="1" name="assistcases_frame"
                id="assistcases_frame" src="AS_cases_frame.php">
            Your browser does not support frames / Twoja przegl?darka nie obs?uguje ramek.
        </iframe>
        <div align="right"><input size="8" type="hidden" name="sort" id="sort"><font
                    color="#6699cc"><small><?= AS_CASES_LIT ?></small>&nbsp;<input size="2" type="text" name="letter"
                                                                                   id="letter"
                                                                                   style="border:none; text-align: center"
                                                                                   disabled>&nbsp;<input size="8"
                                                                                                         type="hidden"
                                                                                                         name="amount"
                                                                                                         id="amount"><input
                        size="8" type="hidden" name="step" id="step" value="0"><input size="8" type="hidden" name="end"
                                                                                      id="end" value="0"><font
                        color="#6699cc"><small><?= AS_CASES_WYSW ?></small>&nbsp;</font><input type="text"
                                                                                               style="color: #6699cc; border: none; text-align: center"
                                                                                               size="8" name="count"
                                                                                               id="count" disabled><font
                        color="#6699cc"> <small><?= AS_CASES_WYSWZ ?></small> </font><input type="text"
                                                                                            style="color: #6699cc; border: none; text-align: center"
                                                                                            size="8" name="total"
                                                                                            id="total" disabled></font>
        </div>
    </form>
</center>
<?php
if (isset($_GET['new_documents']) && $_GET['new_documents'] == '1') {
    echo '<script>document.form1.new_documents.click();</script>';

}
if (isset($_GET['claims_stat'])) {
    echo '<script>	
	 var url = "AS_cases_frame.php?claims_stat=' . $_GET['claims_stat'] . '";
     assistcases_frame.location = url;
	</script>';
}
if (isset($_GET['new_alerts'])) {
    echo '<script>	
	 var url = "AS_cases_frame.php?new_alerts=' . $_GET['new_alerts'] . '";
     assistcases_frame.location = url;
	</script>';
}
if (isset($_GET['new_ext_note'])) {
    echo '<script>	
	 var url = "AS_cases_frame.php?new_ext_note=' . $_GET['new_ext_note'] . '";
     assistcases_frame.location = url;
	</script>';
}
?>

</body>
</html>
