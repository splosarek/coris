<?php

include('include/include.php');
include('lib/lib_case.php');
require_once('access.php');
include('include/include_mod.php');

$lang = $_SESSION['GUI_language'];
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <title>Zgłoszenie szkody</title>
        <link href="Styles/general.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="Scripts/mootools-core.js"></script>
	<script language="JavaScript" src="CalendarPopup.js"></script>
	<script language="JavaScript" src="Scripts/javascript.js"></script>

	</head>
    <body bgcolor="#dfdfdf">


	<script language="JavaScript">
	<!--
    	var cal = new CalendarPopup();
		cal.setMonthNames(<?= MONTHS_NAME ?>);
		cal.setDayHeaders(<?= DAY_NAME ?>);
		cal.setWeekStartDay(1);
		cal.setTodayText('<?= TODAY ?>');
	//-->
	</script>
        <script language="javascript">
        <!--
            function validate() {


	if (document.getElementById('contrahent_id').value == 113) {
				if (document.getElementById('marka_model').value == "") {
                    alert("<?= AS_CASADD_MSG_BRMARKAMODEL ?>");
                    document.getElementById('marka_model').focus();
                    return false;
                }

				if (document.getElementById('nr_rej').value == "") {
                    alert("<?= AS_CASADD_MSG_BRNRREJ ?>");
                    document.getElementById('nr_rej').focus();
                    return false;
                }

				if (document.getElementById('city').value == "" && document.getElementById('only_info').checked==false ) {
                    alert("<?= AS_CASADD_MSG_BRCITY ?>");
                    document.getElementById('city').focus();
                    return false;
                }


	}else{


             	if (document.getElementById('paxName').value == "") {
                    alert("<?= AS_CASADD_MSG_BRIM ?>");
                    document.getElementById('paxName').focus();
                    return false;
                }
                if (document.getElementById('paxSurname').value == "") {
                    alert("<?= AS_CASADD_MSG_BRNAZW ?>");
                    document.getElementById('paxSurname').focus();
                    return false;
                }

                if ( form_reg.contrahent_id.value == 0) {
                    alert("<?= AS_CASADD_MSG_BRKLI ?>");
                    form_reg.contrahent_id.focus();
                    return false;
                }

                if (document.getElementById('marka_model').value == "") {
                    alert("<?= AS_CASADD_MSG_BRMARKAMODEL ?>");
                    document.getElementById('marka_model').focus();
                    return false;
                }
				if (document.getElementById('nr_rej').value == "") {
                    alert("<?= AS_CASADD_MSG_BRNRREJ ?>");
                    document.getElementById('nr_rej').focus();
                    return false;
                }

                if (document.getElementById('city').value == "" && document.getElementById('only_info').checked==false ) {
                    alert("<?= AS_CASADD_MSG_BRCITY ?>");
                    document.getElementById('city').focus();
                    return false;
                }

               if (document.getElementById('paxPhone').value == "" && document.getElementById('only_info').checked==false ) {
                    alert("<?= AS_CASADD_MSG_NRTELPOSZK ?>");
                    document.getElementById('paxPhone').focus();
                    return false;
                }


               /*if (  $('branch_id').value==1 &&   !( 1.0*form_reg.case_rezerwa.value > 0.00)  ){
                   if ($('only_info').checked ){

                  }else{
                   	alert("<?= AS_CASADD_MSG_BRAKREZ ?>");
                   	form_reg.case_rezerwa.focus();
                   	return false;
                   }
               }*/

                if ((form_reg.paxDob_d.value != "" || form_reg.paxDob_m.value != "" || form_reg.paxDob_y.value != "") && (form_reg.paxDob_d.value == "" || form_reg.paxDob_m.value == "" || form_reg.paxDob_y.value == "")) {
                    alert("<?= AS_CASADD_MSG_WYWYCZ ?>");
                    form_reg.paxDob_d.focus();
                    return false;
                }
                if (form_reg.paxDob_d.value != "" && form_reg.paxDob_m.value != "" && form_reg.paxDob_y.value != "") {
                    if (isNaN(form_reg.paxDob_d.value) || isNaN(form_reg.paxDob_m.value) || isNaN(form_reg.paxDob_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATUR ?>");
                        form_reg.paxDob_d.focus();
                        return false;
                    }
                }
               /* if (form_reg.event.value == "") {
                    alert("<?= AS_CASADD_MSG_BRAKOPISU ?>");
                form_reg.event.focus();
                    return false;
                }
                */
                if (form_reg.eventDate_d.value == "" || form_reg.eventDate_m.value == "" || form_reg.eventDate_y.value == "") {
                    alert("<?= AS_CASADD_MSG_BRAKDATZDARZ ?>");
                    form_reg.eventDate_d.focus();
                    return false;
                } else {
                    if (isNaN(form_reg.eventDate_d.value) || isNaN(form_reg.eventDate_m.value) || isNaN(form_reg.eventDate_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATZDARZ ?>");
                        form_reg.eventDate_d.focus();
                        return false;
                    }
                }
                if (form_reg.notificationDate_d.value == "" || form_reg.notificationDate_m.value == "" || form_reg.notificationDate_y.value == "") {
                    alert("<?= AS_CASADD_MSG_BRAKDATZGL ?>");
                    form_reg.notificationDate_d.focus();
                    return false;
                } else {
                    if (isNaN(form_reg.notificationDate_d.value) || isNaN(form_reg.notificationDate_m.value) || isNaN(form_reg.notificationDate_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATZGL  ?>");
                        form_reg.notificationDate_d.focus();
                        return false;
                    }
                }
                if (form_reg.country.value == "") {
                    alert("<?= AS_CASADD_MSG_BRAKKRZD ?>");
                    form_reg.country.focus();
                    return false;
                }
                if ((form_reg.paxPost_1.value != "" || form_reg.paxPost_2.value != "") && (form_reg.paxPost_1.value == "" || form_reg.paxPost_2.value == "")) {
                    alert("<?= AS_CASADD_MSG_WYWYCZPOLKOD ?>");
                    form_reg.paxPost_1.focus();
                    return false;
                }
                if (form_reg.paxPost_1.value != "" && form_reg.paxPost_2.value != "") {
                    if (isNaN(form_reg.paxPost_1.value) || isNaN(form_reg.paxPost_2.value)) {
                        alert("<?= AS_CASADD_MSG_KODPOCZT ?>");
                        form_reg.paxPost_1.focus();
                        return false;
                    }
                }
                if (form_reg.paxPhone.value != "" ) {
                    if (isNaN(form_reg.paxPhone.value)) {
                        alert("<?= AS_CASADD_MSG_NRTELPOSZK ?>");
                        form_reg.paxPhone.focus();
                        return false;
                    }
                }
                if (form_reg.paxMobile.value != "") {
                    if (isNaN(form_reg.paxMobile.value)) {
                        alert("<?= AS_CASADD_MSG_NRTELKOMPOSZK ?>");
                        form_reg.paxMobile.focus();
                        return false;
                    }
                }

                if ((form_reg.validity_from_d.value != "" || form_reg.validity_from_m.value != "" || form_reg.validity_from_y.value != "" || form_reg.validity_to_d.value != "" || form_reg.validity_to_m.value != "" || form_reg.validity_to_y.value != "") && (form_reg.validity_from_d.value == "" || form_reg.validity_from_m.value == "" || form_reg.validity_from_y.value == "" || form_reg.validity_to_d.value == "" || form_reg.validity_to_m.value == "" || form_reg.validity_to_y.value == "")) {
                    alert("<?= AS_CASADD_MSG_WYPWYCZDATWAZNPOL ?>");
                    form_reg.validity_from_d.focus();
                    return false;
                }
                if (form_reg.validity_from_d.value != "" || form_reg.validity_from_m.value != "" || form_reg.validity_from_y.value != "") {
                    if (isNaN(form_reg.validity_from_d.value) || isNaN(form_reg.validity_from_m.value) || isNaN(form_reg.validity_from_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATWAZNPOL ?>");
                        form_reg.validity_from_d.focus();
                        return false;
                    }
				}
                if (form_reg.validity_to_d.value != "" || form_reg.validity_to_m.value != "" || form_reg.validity_to_y.value != "") {
                    if (isNaN(form_reg.validity_to_d.value) || isNaN(form_reg.validity_to_m.value) || isNaN(form_reg.validity_to_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATWAZNPOL ?>");
                        form_reg.validity_to_d.focus();
                        return false;
                    }
				}
                if ((form_reg.policyPurchaseDate_d.value != "" || form_reg.policyPurchaseDate_m.value != "" || form_reg.policyPurchaseDate_y.value != "") && (form_reg.policyPurchaseDate_d.value == "" || form_reg.policyPurchaseDate_m.value == "" || form_reg.policyPurchaseDate_y.value == "")) {
                    alert("<?= AS_CASADD_MSG_WYPWYCZDATZAKPOL ?>");
                    form_reg.policyPurchaseDate_d.focus();
                    return false;
                }
                if (form_reg.policyPurchaseDate_d.value != "" && form_reg.policyPurchaseDate_m.value != "" && form_reg.policyPurchaseDate_y.value != "") {
                    if (isNaN(form_reg.policyPurchaseDate_d.value) || isNaN(form_reg.policyPurchaseDate_m.value) || isNaN(form_reg.policyPurchaseDate_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATZAKPOL ?>");
                        form_reg.policyPurchaseDate_d.focus();
                        return false;
                    }
                }
                if (form_reg.policyAmount.value != "") {
                	form_reg.policyAmount.value.replace(/ /g, "");
                	form_reg.policyAmount.value.replace(/\./g, "");
                	form_reg.policyAmount.value.replace(/,/g, "");
                    if (isNaN(form_reg.policyAmount.value)) {
                        alert("<?= AS_CASADD_MSG_SUMUBEZP ?>");
                        form_reg.policyAmount.focus();
                        return false;
                    }
                }
                if (form_reg.policyCurrency.value != "" && form_reg.policyAmount.value == "") {
                    alert("<?= AS_CASADD_MSG_WPISZWIELKSUM ?>");
                    form_reg.policyAmount.focus();
                    return false;
                }
                if (form_reg.policyCurrency.value == "" && form_reg.policyAmount.value != "") {
                    alert("<?= AS_CASADD_MSG_WYBWAL ?>");
                    form_reg.policyAmount.focus();
                    return false;
                }

                // Kontakty 1
                if ((form_reg.contactNo1.value != "" || form_reg.contactDesc1.value != "" || form_reg.type1.value != 0 || form_reg.contacttype1.value != 0) && (form_reg.contactNo1.value == "" || form_reg.contactDesc1.value == "" || form_reg.type1.value == 0 || form_reg.contacttype1.value == 0)) {
                    alert("<?= AS_CASADD_MSG_PRWYPKONT ?>");
                    return false;
                }
                if (form_reg.type1.value == 1 && isNaN(parseFloat(form_reg.contactNo1.value))) {
                    alert("<?= AS_CASADD_MSG_BLFORMNRTEL ?>");
                    form_reg.contactNo1.focus();
                    return false;
                }
                if (form_reg.type1.value == 2 && isNaN(parseFloat(form_reg.contactNo1.value))) {
                    alert("<?= AS_CASADD_MSG_BLFORMNRFAX ?>");
                    form_reg.contactNo1.focus();
                    return false;
                }
                if (form_reg.type1.value == 3 && !form_reg.contactNo1.value.match(/.*@.*/)) {
                    alert("<?= AS_CASADD_MSG_BLFORMNREMAIL ?>");
                    form_reg.contactNo1.focus();
                    return false;
                }

                // Kontakty 2
                if ((form_reg.contactNo2.value != "" || form_reg.contactDesc2.value != "" || form_reg.type2.value != 0 || form_reg.contacttype2.value != 0) && (form_reg.contactNo2.value == "" || form_reg.contactDesc2.value == "" || form_reg.type2.value == 0 || form_reg.contacttype2.value == 0)) {
                    alert("<?= AS_CASADD_MSG_PRWYPKONT ?>");
                    return false;
                }
                if (form_reg.type2.value == 1 && isNaN(parseFloat(form_reg.contactNo2.value))) {
                    alert("<?= AS_CASADD_MSG_BLFORMNRTEL ?>");
                    form_reg.contactNo2.focus();
                    return false;
                }
                if (form_reg.type2.value == 2 && isNaN(parseFloat(form_reg.contactNo2.value))) {
                    alert("<?= AS_CASADD_MSG_BLFORMNRFAX ?>");
                    form_reg.contactNo2.focus();
                    return false;
                }
                if (form_reg.type2.value == 3 && !form_reg.contactNo2.value.match(/.*@.*/)) {
                    alert("B<?= AS_CASADD_MSG_BLFORMNREMAIL ?>");
                    form_reg.contactNo2.focus();
                    return false;
                }

                // Kontakty 3
                if ((form_reg.contactNo3.value != "" || form_reg.contactDesc3.value != "" || form_reg.type3.value != 0 || form_reg.contacttype3.value != 0) && (form_reg.contactNo3.value == "" || form_reg.contactDesc3.value == "" || form_reg.type3.value == 0 || form_reg.contacttype3.value == 0)) {
                    alert("<?= AS_CASADD_MSG_PRWYPKONT ?>");
                    return false;
                }
                if (form_reg.type3.value == 1 && isNaN(parseFloat(form_reg.contactNo3.value))) {
                    alert("<?= AS_CASADD_MSG_BLFORMNRTEL ?>");
                    form_reg.contactNo3.focus();
                    return false;
                }
                if (form_reg.type3.value == 2 && isNaN(parseFloat(form_reg.contactNo3.value))) {
                    alert("<?= AS_CASADD_MSG_BLFORMNRFAX ?>");
                    form_reg.contactNo3.focus();
                    return false;
                }
                if (form_reg.type3.value == 3 && !form_reg.contactNo3.value.match(/.*@.*/)) {
                    alert("<?= AS_CASADD_MSG_BLFORMNREMAIL ?>");
                    form_reg.contactNo3.focus();
                    return false;
                }

/*                for (var i = 0; i < document.forms['form_reg'].length; i++) {
                    if (document.forms['form_reg'].elements[i].name.match(/_d$/) && form_reg.elements[i].value != "" && (form_reg.elements[i].value <= 0 || form_reg.elements[i].value > 31)) {
                        alert("<?= AS_CASADD_MSG_BLWARTDN ?>");
                form_reg.elements[i].focus();
                        return false;
                    }
                }

                for (var i = 0; i < document.forms['form_reg'].length; i++) {
                    if (document.forms['form_reg'].elements[i].name.match(/_m$/) && form_reg.elements[i].value != "" && (form_reg.elements[i].value <= 0 || form_reg.elements[i].value > 12)) {
                        alert("<?= AS_CASADD_MSG_BLWARTMIES ?>");
                form_reg.elements[i].focus();
                        return false;
                    }
                }
*/

                <?php if ($_SESSION['coris_branch']==1 ){?>
                if ($('country').value == 'DE' || $('country').value == 'AT'){
						if (confirm('Czy jesteś pewien, że wybrałeś prawidłowy oddział?')){
							return true;
						}else{
							return false;
						}

               }
                <?php }else{ ?>
		                if (confirm('Czy jesteś pewien, że wybrałeś prawidłowy oddział?')){
							return true;
						}else{
							return false;
						}
                <?php }?>

	}
            }


        function zmien_info_only(stan){
            if (stan){
					document.getElementById('city').className ='';
					document.getElementById('paxPhone').className ='';
            }else{
            	document.getElementById('city').className ='required';
				document.getElementById('paxPhone').className ='required';

			}

       }

        function move_formant(s,e) {
            var form1 = document.getElementById('form_reg');
			//e = window.event;
			//var keyInfo = String.fromCharCode(e.keyCode);
        	if(window.event)
        		var keyInfo  = window.event.keyCode; // IE
        	else
        		var keyInfo  = e.charCode;

			if (keyInfo != 9 && keyInfo != 16 && keyInfo != 8) {
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

		function remove_formant(s,e) {
			var form1 = document.getElementById('form_reg');
			if(window.event)
        		var keyInfo  = window.event.keyCode; // IE
        	else
        		var keyInfo  = e.charCode;

			if (keyInfo == 8) {
				for (var i = 0; i < form1.length; i++) {
					if (s.name == form1.elements[i].name) {
						if ((form_reg.elements[i].value.length == 0)) {
							form1.elements[i-1].focus();
							var rng = form1.elements[i-1].createTextRange();
							rng.select();
							return false;
						}
					}
				}
			}
		}

			// Kalendarz
            function y2k(number)    { return (number < 1000) ? number + 1900 : number; }
			var today;
			var day;
			var month;
			var year
            function newWindowCal(name) {

				today = new Date();
				day   = today.getDate();
				month = today.getMonth();
				year  = y2k(today.getYear());

				var width = 260;
				var height = 200;
				var left = (screen.availWidth - width) / 2;
				var top = ((screen.availHeight - 0.2 * screen.availHeight) - height) / 2;
                mywindow = window.open('calendar.php?name='+ name,'','resizable=no,width='+ width +',height='+ height +',left='+ left +',top='+ top);
            }

        //-->
        </script>
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
            input {
                font-size: 8pt;
            }
        </style>
<script type="text/javascript">

function change_form(){


		if (document.getElementById('contrahent_id').value == 113) {
			document.getElementById('paxName').className = '';
			document.getElementById('paxSurname').className = '';
			document.getElementById('paxPhone').className = '';
		}else{
			document.getElementById('paxName').className = 'required';
			document.getElementById('paxSurname').className = 'required';
			document.getElementById('paxPhone').className = 'required';
		}
}
</script>

        <table cellpadding=4 cellspacing=0 width="100%">
            <tr style="border-left: #eeeeee 1px solid; border-right: #eeeeee 1px solid; border-bottom: #eeeeee 1px solid; border-top: #eeeeee 1px solid">
                <td align="center" bgcolor="#cccccc">
                    <b><?= CASEADD ?></b>
                </td>
            </tr>
            <tr>
                <td align="center">
<?php

//if (isset($_GET['action']) && !(isset($_SESSION['coris_case_submit']) && $_SESSION['coris_case_submit'] == "$_POST[paxName]#$_POST[paxSurname]")) {
if ( isset($_GET['action'])  ) {

    $case_type = 1; // SPRAWA TECHNICZNA


    if (getValue('contrahent_id') == 113) {//sprawa argos
    	$case_type=5;
    }


    $query = "INSERT INTO coris_assistance_cases (number, year) SELECT MAX(number+1) AS number, year(NOW()) FROM coris_assistance_cases WHERE year = year(NOW())";

    if ($result = mysql_query($query)) {
        $query = "SELECT case_id, number, year FROM coris_assistance_cases WHERE case_id = @@IDENTITY";

        if ($result = mysql_query($query)) {
            if (!$row = mysql_fetch_array($result))
                die("Problem z pobraniem numeru sprawy");


             $zmiana_numeru = '';
             if ($row['number'] == 0){
				$zmiana_numeru = ',number=1';
				$row['number'] = 1;
			}

			$case_id = $row['case_id'];
			$only_info=getValue('only_info') == 1 ? 1 : 0;
			$branch_id = getValue('branch_id');

            $query = "UPDATE coris_assistance_cases SET
             coris_branch_id = '$branch_id',operational=1,
            client_id = '$_POST[contrahent_id]', type_id = $case_type, client_ref = '$_POST[contrahent_ref]', date = NOW(), user_id = '$_SESSION[user_id]', paxname = '$_POST[paxName]', paxsurname = '$_POST[paxSurname]', paxdob = '$_POST[paxDob_y]-$_POST[paxDob_m]-$_POST[paxDob_d]', policy_series = '".getValue('policy_series')."', policy = '".getValue('policy')."',
             only_info='$only_info',event = '".getValue('event')."', eventdate = '".getValue('eventDate_y')."-".getValue('eventDate_m')."-".getValue('eventDate_d')."',
             country_id = '$_POST[country]', city = '$_POST[city]',adress1='".getValue('adress1')."',adress2='".getValue('adress2')."',marka_model='".getValue('marka_model')."' ,nr_rej='".getValue('nr_rej')."',vin='".getValue('vin')."' $zmiana_numeru WHERE case_id = $row[case_id]";

            if ($result = mysql_query($query)) {

            	CaseInfo::updateFullNumber($case_id);
            	CaseInfo::setCaseState($case_id,1,0); // Status wtrakcie likwidacji

                $query = "INSERT INTO coris_assistance_cases_details (case_id, notificationdate,notificationTime, informer, validityfrom, validityto, policypurchasedate, policypurchaselocation, policyamount, policycurrency_id, circumstances, paxaddress, paxpost, paxcity, paxcountry, paxphone, paxmobile)
                    VALUES ('$row[case_id]', '$_POST[notificationDate_y]-$_POST[notificationDate_m]-$_POST[notificationDate_d]','".getValue('notificationTime')."', '$_POST[informer]', '$_POST[validity_from_y]-$_POST[validity_from_m]-$_POST[validity_from_d]', '$_POST[validity_to_y]-$_POST[validity_to_m]-$_POST[validity_to_d]', '$_POST[policyPurchaseDate_y]-$_POST[policyPurchaseDate_m]-$_POST[policyPurchaseDate_d]', '$_POST[policyPurchaseLocation]', '$_POST[policyAmount]', '$_POST[policyCurrency]', '".getValue('circumstances')."', '$_POST[paxAddress]', '$_POST[paxPost_1]-$_POST[paxPost_2]', '$_POST[paxCity]', '$_POST[paxCountry]', '$_POST[paxPhone]', '$_POST[paxMobile]')";

                if ($result = mysql_query($query)) {
                    //mysql_query("COMMIT");

                	/*if ($branch_id == 1){
                    	$case_rezerwa = str_replace(',','.',getValue('case_rezerwa'));
                    	$case_rezerwa_waluta = getValue('case_rezerwa_waluta');
                    	CaseInfo::setGLobalReserveStart($case_id,$case_rezerwa,$case_rezerwa_waluta);

                    } */


					$_SESSION['coris_case_submit'] = "$_POST[paxName]#$_POST[paxSurname]"; // zabezpieczenie przed postbackiem

                    // KONTAKTY
                    for ($i = 1; $i <= 3; $i++) {
                        $type = "type". $i;
                        $contactNo = "contactNo". $i;
                        $contactDesc = "contactDesc". $i;
                        $contacttype = "contacttype". $i;
                        $query = "INSERT INTO coris_assistance_cases_contacts (case_id, type_id, contactno, contactdesc, contacttype_id, user_id, date) VALUES ($row[case_id], '$_POST[$type]', '$_POST[$contactNo]', '$_POST[$contactDesc]', '$_POST[$contacttype]', '$_SESSION[user_id]', NOW())";
                        if (!$result = mysql_query($query)) {
                            die(mysql_error());
                        }
                    }


                    CaseInfo::CaseCauseUpdate($case_id,intval(getValue('cause_id')),intval(getValue('cause_id_old')));
            	CaseInfo::setCaseOperatingUser($case_id,Application::getCurrentUser());

                                        echo "<font size=\"+1\">".AS_CASADD_PROSZEZAPISACNRSPR.": </font><font size=\"+1\" color=\"red\"><B>$row[number]/$row[year]/$case_type/$_POST[contrahent_id]</B></font>.<br>".AS_CASADD_ABYOTWSPRA.": <input type=\"button\" value=\"&raquo;\" onclick=\"open_case(".$row['case_id'].",'casewindow".$row['case_id']."');\" style=\"font-family: Webdings; height: 18pt;\"><br>".AS_CASADD_MSG_JCHWPINOWSZK.".<br>";


                } else {
                    //mysql_query("ROLLBACK");
                    echo $query;
                    mail("krzysiek@evernet.com.pl",'Coris SSA SQL ERROR',"q: $query \n\n".mysql_error());
                    die(mysql_error());
                }
            } else {
            	echo $query;
                //mysql_query("ROLLBACK");
                die(mysql_error());
            }
        } else {
        	echo $query;
            //mysql_query("ROLLBACK");
            die(mysql_error());
        }
    }
?>
                </td>
            </tr>
<?php
} else {
	unset($_SESSION['case_submit']); // usuwam dodanie sprawy (POSTBACK)
?>
            <tr>
                <td align="center">
                    <i><small><?= AS_CASADD_MSG_WYPPOLZGL ?></small></i>
                </td>
            </tr>
<?php
}
?>
            <tr bgcolor="#e0e0e0">
                <td align="center">
                    <input type="button" value="<?= AS_CASADD_SPRMED ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: #6699cc; width: 150px" onclick="document.location='AS_cases_add_med.php'">
                    <input type="button" value="<?= AS_CASADD_SPRTECH ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: orange; width: 150px" disabled>

                   <?php   if (isset($_SESSION['coris_branch']) &&  $_SESSION['coris_branch'] == 1 ){ ?>
                    <input type="button" value="<?= AS_CASADD_SPRLS ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: #66cc66; width: 150px" onclick="document.location='AS_cases_add_ls.php'">
                         <?php } ?>
                </td>
            </tr>
        </table>
        <br>
        <center><a name="menu"><font color="orange"><a href="#poszkodowany"><font color="orange"><?= AS_CASADD_POSZK ?></font></a> | <a href="#szkoda"><font color="orange"><?= AS_CASADD_SZK ?></font></a> | <a href="#polisa"><font color="orange"><?= AS_CASADD_POL?></font></a> | <a href="#szczegoly"><font color="orange"><?= AS_CASADD_SZCZ ?></font></a> | <a href="#kontakty"><font color="orange"><?= AS_CASADD_KONT ?></font></a></font></a></center>
        <table><tr height="3"><td></td></tr></table>
        <div align="left"><font color="orange"><?= AS_CASADD_SPRTECH ?></font></div>
        <hr noshade size="1" color="orange">
  <form action="AS_cases_add_tech.php?action=1" method="post" name="form_reg"  id="form_reg" onsubmit="return validate();">

  <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
<tr bgcolor="#eeeeee">
      <td colspan="2"><img src="img/1o.gif">&nbsp;&nbsp;<a href="#menu" tabindex="-1"><img src="img/KwadO.gif" border="0"></a>&nbsp;<b><a name="poszkodowany"><font color="orange"><?= AS_CASADD_DANWYM ?></font></a></b></td>

</tr>
            <tr>
                <td align="right"><b><small><?= AS_CASD_TYLKINF2 ?></small></b></td>
                <td> <input type="checkbox"  name="only_info"  id="only_info" value="1" onChange="zmien_info_only(this.checked)"></td>
            </tr>

<tr>
  <td width="100" align="right"><b><small><?= NAME ?></small></b></td>
     <td>&nbsp;<input class="required" type="text" name="paxName"  id="paxName" onChange="javascript:this.value=this.value.toUpperCase();" size="50" maxlength="50" ></td>
</tr>
<tr>
  <td width="100" align="right"><b><small><?= SURNAME ?></small></b></td>
  <td>&nbsp;<input class="required" type="text" name="paxSurname"  id="paxSurname" onChange="javascript:this.value=this.value.toUpperCase();" size="50" maxlength="50" ></td>
</tr>

 <tr>
      <td width="100" align="right"><b><small><?= AS_CASADD_TOW ?></small></b></td>
      <td>&nbsp;
      	<!--  	<input type="text" name="contrahent_id" id="contrahent_id" value="" size="5" onblur="contrahent_search_frame.location='GEN_contrahents_select_iframe.php?contrahent_id=' + this.value;change_form();" onchange="change_form();"  style="text-align: center;" class="required">
             <input type="text" name="contrahent_name" id="contrahent_name" size="30" disabled>
              <input type="button" value="L" style="background: #cccccc; color: orange; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 18pt;" onclick="window.open('GEN_contrahents_select_frameset.php','clientsearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=420,left='+ (screen.availWidth - 550) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 420) / 2);" title="<?= AS_CASADD_WYSZKLI ?>">
     -->
     <input type="text" id="contrahent_id" name="contrahent_id" value="" size="5" onblur="contrahent_search_frame.location='GEN_contrahents_select_iframe.php?contrahent_id=' + this.value+'&branch_id='+$('branch_id').value;change_form();" onchange="change_form();" style="text-align: center;" class="required">
     <input type="text" id="contrahent_name" name="contrahent_name" size="60" disabled> <input type="button" value="L" style="background: #cccccc; color: #6699cc; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 18pt;" onclick="window.open('GEN_contrahents_select_frameset.php?branch_id='+$('branch_id').value,'contrahentsearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=650,height=420,left='+ (screen.availWidth - 550) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 420) / 2);" title="<?= AS_CASADD_WYSZKLI ?>">

     </td>
</tr>
<tr>
    <td width="100" align="right"><b><small><?= AS_CASADD_MARKAMODEL ?></small></b></td>
	<td>&nbsp;<input class="required" type="text" name="marka_model" id="marka_model"  size="50" maxlength="50" ></td>
</tr>
 <tr>
   <td width="100" align="right"><b><small><?= AS_CASADD_NRREJ ?></small></b></td>
   <td>&nbsp;<input  class="required" type="text" name="nr_rej" id="nr_rej"  size="30" maxlength="50" ></td>
</tr>

<tr>
                <td width="100" align="right"><b><small><?= COUNTRY ?></small></b></td>
                <td>
                    <table>
                        <tr valign="middle">
                            <td>
                            <script>
function aktualizuj_kraj(kod_kraju){

		kod_kraju=kod_kraju.toUpperCase();

		 if("DE" == kod_kraju){
	            var branch = document.getElementById('branch_id');
	            for (var i=0; i<branch.length; i++){
	                if (branch.options[i].value == 2){
	                	<?php if ($_SESSION['coris_branch']==2 ){?>
	                    		branch.selectedIndex = i;
	                    <?php }?>
	                }
	            }
	        }

	        if("AT" == kod_kraju){
	            var branch = document.getElementById('branch_id');
	            for (var i=0; i<branch.length; i++){
	                if (branch.options[i].value == 3){
	                	<?php if ($_SESSION['coris_branch']==2 ){?>
	                    		branch.selectedIndex = i;
	                    <?php }?>
	                }
	            }
	        }
		ilosc= document.getElementById('countryList').length;
		zm=0;
		kr_status=0;
		for (i=0;i<ilosc;i++){
					if (document.getElementById('countryList').options[i].value == kod_kraju ){
							document.getElementById('countryList').selectedIndex = i;
							document.getElementById('country').value = document.getElementById('country').value.toUpperCase();
							kr_status=1;
					}
		}
		if (kr_status==0){
				document.getElementById('country').value = "";
				document.getElementById('countryList').selectedIndex = 0 ;
				alert("<?php echo AS_CASD_BRKROSKR ?> " + kod_kraju );
		}
}
</script>
<?php
    $defaultCountrySymbol = 'PL';
    $defaultCountry = '';
    if (isset($_SESSION['coris_branch']) && 2 == $_SESSION['coris_branch'])
    {
        $defaultCountrySymbol = 'DE';
        $defaultCountry = ' value="DE" ';
    }
?>

              <input type="text" name="country" id="country" size="3" maxlength="2" class="required" style="text-align: center" onblur="aktualizuj_kraj(this.value);" value="<?php echo $defaultCountrySymbol; ?>">
                            </td>
                            <td>
                                <?php echo Application :: countryList($defaultCountrySymbol, $lang, 'countryList', 'style="font-size: 8pt;" onChange="document.forms[\'form_reg\'].elements[\'country\'].value = document.forms[\'form_reg\'].elements[\'countryList\'].value"', true)?>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
<tr>
	<td width="100" align="right"><small><b><?= CITY ?></b></small></td>
	<td>&nbsp;<input class="required" type="text" name="city" id="city" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30"></td>
</tr>
<tr>
     <td width="100" align="right"><small><b><?=  AS_CASADD_TEL1 ?></b></small><br><small>(xx yyyyyyy)</small></td>
       <td>&nbsp;<input class="required" type="text" name="paxPhone" id="paxPhone" size="15" maxlength="30" style="text-align: center" onkeydown="remove_formant(this,event);">
	</td>
</tr>
      <?php
      if (isset($_SESSION['coris_branch']) && 1 == $_SESSION['coris_branch'])
      {
          echo '<tr><td align="right"><b><small>' . BRANCH . '</small></b></td><td>' . print_user_coris_branch2('branch_id', 1) . '</td></tr>';
      }else{
         // echo '<tr><td><input type="hidden" name="branch_id" id="branch_id" value="2"></td><td></td></tr>';
           echo '<tr><td align="right"><b><small>' . BRANCH . '</small></b></td><td>' . print_user_coris_branch_de('branch_id', 1) . '</td></tr>';
      }
      ?>

<?php if(  $_SESSION['coris_branch'] == 1 ){/* ?>
  <tr>
<td align="right"><div align="right"  > <small><b><?= AS_REZ_REZGLOB ?>:</b></samll></small></div>
				<td><input type="text" name="case_rezerwa" id="case_rezerwa" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency_pln('case_rezerwa_waluta','PLN',0,'class="required"'); ?>
</td>
</tr>
<?php */ }?>
		<tr>
		<td colspan="2">
        	   <br><center><input type="submit" value="<?= AS_CASADD_ZAPSZK ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASADD_ZAPSZKMED ?>"></center><br>
		</td>
	    </tr>


<tr bgcolor="#eeeeee">
                <td colspan="2"><img src="img/2o.gif">&nbsp;&nbsp;<a href="#menu" tabindex="-1"><img src="img/KwadO.gif" border="0"></a>&nbsp;<b><a name="poszkodowany"><font color="orange"><?= AS_CASADD_KLIENT ?></font></a></b></td>
</tr>
 <tr>
   <td width="100" align="right"><b><small>VIN</small></b></td>
   <td>&nbsp;<input type="text" name="vin"  size="30" maxlength="50" ></td>
</tr>
<tr>
<td width="100" align="right"><small><?= AS_CASADD_TEL2  ?></small><br>
        <small>(yyyyyyyyy)</small></td>
                <td>
                    &nbsp;
        <input type="text" name="paxMobile" size="15" maxlength="30" style="text-align: center">
                </td>
            </tr>
		 <tr>

      <td width="100" align="right"><b><small><?= AS_CASADD_ADRPOST ?></small></b></td>
                <td>&nbsp;

        <input type="text" name="adress1"  size="80" maxlength="200" >
      </td>
            </tr>
		 <tr>

      <td width="100" align="right"><b><small><?= AS_CASADD_ADRDOC ?></small></b></td>
                <td>&nbsp;

        <input type="text" name="adress2"  size="80" maxlength="200" >
      </td>
            </tr>
        </table>
        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                            <td colspan="2"><img src="img/3o.gif">&nbsp;&nbsp;<a href="#menu" tabindex="-1"><img src="img/KwadO.gif" border="0"></a>&nbsp;<b><a name="szkoda"><font color="orange"><?= AS_CASADD_SZKOD ?></font></a></b></td>
            </tr>
            <tr>
                <td width="100" align="right"><b><small><u><?= AS_CASADD_PRZYCZUST ?></u></small></b></td>
                <td>
                    &nbsp;<input type="text" name="event" onchange="javascript:this.value=this.value.toUpperCase();" size="50" maxlength="50" >
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><b><small><u><?= AS_CASADD_PRZYCZUST ?></u></small></b></td>
                <td>
                    &nbsp;<?php echo CaseInfo::getCaseCause('cause_id',0,0,1,''); ?>
                </td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><b><small><?= AS_CASADD_DATZDARZ ?></small></b><br><small>(dd mm yyyy)</small></td>
                <td>
                    &nbsp;<input type="text" name="eventDate_d" size="1" value="<?php echo date("d") ?>" maxlength="2"  style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="eventDate_m" size="1" value="<?php echo date("m") ?>" maxlength="2"  style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="eventDate_y" size="4" value="<?php echo date("Y") ?>" maxlength="4"  style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('eventDate')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>&nbsp;&nbsp;<b>

<?= AS_CASADD_DATZGLOSZ ?></small></b>&nbsp;&nbsp;<input type="text" name="notificationDate_d" size="1" value="<?php echo date("d") ?>" maxlength="2"  style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="notificationDate_m" size="1" value="<?php echo date("m") ?>" maxlength="2"  style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="notificationDate_y" size="4" value="<?php echo date("Y") ?>" maxlength="4"  style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('notificationDate')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small><input type="text" name="notificationTime" size="8" value="<?php echo date("H:i:s") ?>" maxlength="8" style="text-align: center" > <small>(HH:mm:ss)</small>
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_OPISPROBL ?></small></td>
                <td>&nbsp;<textarea name="circumstances" cols="60" rows="5" style="font-family: Verdana; font-size: 8pt;"></textarea></td>
            </tr>

            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_KTOZGL ?></small></td>
                <td>&nbsp;<input type="text" name="informer" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30"></td>
            </tr>
        </table>

  <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
    <tr bgcolor="#eeeeee">
           <td colspan="2"><img src="img/4o.gif">&nbsp;&nbsp;<a href="#menu" tabindex="-1"><img src="img/KwadO.gif" border="0"></a>&nbsp;<b><a name="polisa"><font color="orange"><?= AS_CASADD_POL ?></font></a></b></td>
    </tr>
<tr>
   <td width="100" align="right"><small><?= AS_CASES_NRPOL ?></small></td>
   <td>&nbsp;Seria: <input type="text" name="policy_series" id="policy_series" onchange="javascript:this.value=this.value.toUpperCase();" size="10" maxlength="10">

   &nbsp;Nr: <input type="text" name="policy" id="policy" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30"></td>
</tr>
  <tr valign="middle">
                <td width="100" align="right"><small><?= AS_CASADD_WAZN ?></small><br><small>(dd mm yyyy)</small></td>
                <td>
                    &nbsp;<small><?= AS_CASADD_WAZNOD ?></small>
                    <input type="text" name="validity_from_d" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="validity_from_m" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="validity_from_y" size="4" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('validity_from')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                    <small><?= AS_CASADD_WAZNDO ?></small>
                    <input type="text" name="validity_to_d" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="validity_to_m" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="validity_to_y" size="4" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('validity_to')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_MIEJSCZAK ?></small></td>
                <td>
                    &nbsp;<input type="text" name="policyPurchaseLocation" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="100">&nbsp;&nbsp;<small><?= AS_CASADD_DATZAK ?></small>&nbsp;&nbsp;<input type="text" name="policyPurchaseDate_d" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="policyPurchaseDate_m" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="policyPurchaseDate_y" size="4" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('policyPurchaseDate')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_SUMUBEZP ?></small></td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <input type="text" name="policyAmount" id="policyAmount" size="10" maxlength="10" style="text-align: right">
                            </td>
                            <td>
                                <select name="policyCurrency" id="policyCurrency" style="font-size: 8pt;">
                                    <option value=""></option>
<?php
$result = mysql_query("SELECT currency_id FROM coris_finances_currencies WHERE insurance = 1 AND active = 1 ORDER BY currency_id");
while ($row = mysql_fetch_array($result)) {
?>
                                    <option value="<?php echo $row['currency_id'] ?>"><?php echo $row['currency_id'] ?></option>
<?php
}
?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
</tr>
<tr>
      <td width="100" align="right"><small><?= AS_CASADD_NRSPRKL?></small></td>
      <td>&nbsp;
        <input type="text" name="contrahent_ref" size="25" maxlength="50">
      </td>
    </tr>
  </table>
        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                <td colspan="2"><img src="img/5o.gif">&nbsp;&nbsp;<a href="#menu" tabindex="-1"><img src="img/KwadO.gif" border="0"></a>&nbsp;<b><a name="szczegoly"><font color="orange"><?= AS_CASADD_POSZKSZCZ ?></font></a></b></td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><small><?= AS_CASADD_ULICA ?></small></td>
                <td>
                    &nbsp;<input type="text" name="paxAddress" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="50">
                </td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><small><?= AS_CASADD_KOD ?></small></td>
                <td>
                    &nbsp;<input type="text" name="paxPost_1" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="paxPost_2" size="2" maxlength="3" style="text-align: center" onkeydown="remove_formant(this,event);">&nbsp;&nbsp;<small><?= CITY ?></small>&nbsp;&nbsp;<input type="text" name="paxCity" onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= COUNTRY ?></small></td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <input type="text" name="paxCountry"  id="paxCountry" size="3" maxlength="2" onblur="document.forms['form_reg'].elements['paxCountryList'].value = document.forms['form_reg'].elements['paxCountry'].value.toUpperCase(); document.forms['form_reg'].elements['paxCountry'].value = document.forms['form_reg'].elements['paxCountry'].value.toUpperCase()" style="text-align: center" value="<?php echo $defaultCountrySymbol; ?>">
                            </td>
                            <td>
                                <?php echo Application :: countryList($defaultCountrySymbol, $lang, 'paxCountryList', 'style="font-size: 8pt;" onChange="document.forms[\'form_reg\'].elements[\'paxCountry\'].value = document.forms[\'form_reg\'].elements[\'paxCountryList\'].value"')?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                <td colspan="2">
                <img src="img/6o.gif">&nbsp;&nbsp;<a href="#menu" tabindex="-1"><img src="img/KwadO.gif" border="0"></a>&nbsp;<b><a name="kontakty"><font color="orange"><?= AS_CASADD_DANKONT ?></font></a></b></td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"></td>
                <td>
                <small><?= AS_CASADD_TYPY ?>: <img src="img/Tele.gif"> - <?= AS_CASADD_TEL ?> <img src="img/Fax.gif"> - <?= FAX ?> <img src="img/Email.gif"> - <?= EMAIL ?></small>
                    <table cellpadding="1" cellspacing="1">
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="center"><small><?= AS_CASADD_NRADR ?></small></td>
                            <td align="center"><small><?= AS_CASADD_RODZ ?></small></td>
                            <td align="center"><small><?= AS_CASADD_OPIS ?></small></td>
                        </tr>
                        <tr>
                            <td align="center"><small>1.</small></td>
                            <td>
                                <select name="type1" style="">
                                    <option value="0"></option>
                                     <option value="1">Tel.</option>
                                    <option value="2">Fax</option>
                                    <option value="3">Email</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contactNo1" size="20" maxlength="40" style="text-align: center;">
                            </td>
                            <td>
                                <select name="contacttype1" style="font-size: 8pt; text-align: center">
                                    <option value="0"></option>
                                        <option value="1"><?= AS_CASADD_RODZZNAJ ?></option>
                                    <option value="2"><?= AS_CASADD_LEKSZP ?></option>
                                    <option value="3"><?= AS_CASADD_PILOTBP ?></option>
                                    <option value="4"><?= AS_CASADD_WLADZ ?></option>
                                    <option value="5"><?= AS_CASADD_INNE ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contactDesc1" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="80">
                            </td>
                        </tr>
                        <tr>
                            <td align="center"><small>2.</small></td>
                            <td>
                                <select name="type2" style="">
                                    <option value="0"></option>
                                    <option value="1">Tel.</option>
                                    <option value="2">Fax</option>
                                    <option value="3">Email</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contactNo2" size="20" maxlength="40" style="text-align: center;">
                            </td>
                            <td>
                                <select name="contacttype2" style="font-size: 8pt; text-align: center">
                                    <option value="0"></option>
                                    <option value="1"><?= AS_CASADD_RODZZNAJ ?></option>
                                    <option value="2"><?= AS_CASADD_LEKSZP ?></option>
                                    <option value="3"><?= AS_CASADD_PILOTBP ?></option>
                                    <option value="4"><?= AS_CASADD_WLADZ ?></option>
                                    <option value="5"><?= AS_CASADD_INNE ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contactDesc2" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="80">
                            </td>
                        </tr>
                        <tr>
                            <td align="center"><small>3.</small></td>
                            <td>
                                <select name="type3" style="">
                                    <option value="0"></option>
 									<option value="1">Tel.</option>
                                    <option value="2">Fax</option>
                                    <option value="3">Email</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contactNo3" size="20" maxlength="40" style="text-align: center;">
                            </td>
                            <td>
                                <select name="contacttype3" style="font-size: 8pt; text-align: center">
                                    <option value="0"></option>
                                    <option value="1"><?= AS_CASADD_RODZZNAJ ?></option>
                                    <option value="2"><?= AS_CASADD_LEKSZP ?></option>
                                    <option value="3"><?= AS_CASADD_PILOTBP ?></option>
                                    <option value="4"><?= AS_CASADD_WLADZ ?></option>
                                    <option value="5"><?= AS_CASADD_INNE ?></option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="contactDesc3" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="80">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><b><small> <!-- Data urodzenia //--> </small></b><br><small> <!-- (dd mm yyyy) //--> </small></td>
                <td>
                    &nbsp;<input type="text" name="paxDob_d" size="1" maxlength="2" style="text-align: center; visibility: hidden;" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="paxDob_m" size="1" maxlength="2" style="text-align: center; visibility: hidden;" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="paxDob_y" size="4" maxlength="4" style="text-align: center; visibility: hidden;" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('paxDob')" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 16pt; visibility: hidden;">1</font></a>
                </td>
            </tr>
        </table>
        <br>
        <center><input type="submit" value="<?= AS_CASADD_ZAPSZK ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASADD_ZAPSZKTECH ?>"></center>
        </form>
        <iframe name="contrahent_search_frame" width="0" height="0" src=""></iframe>
        <br>

    </body>
</html>
<?php mysql_free_result($result); ?>
