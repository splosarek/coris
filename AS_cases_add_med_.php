<?php include('include/include.php');
require_once('access.php');
include('include/include_mod.php');
include('lib/lib_cardif.php');
include('lib/lib_europa.php');
include('lib/lib_skok.php');
include('lib/lib_nhc.php');
include('lib/lib_vig.php');
include('lib/lib_ace.php');
include('lib/lib_case.php');

$lang = $_SESSION['GUI_language'];

$rezerwa_coris=89.00;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <title><?= AS_CASADD_TITLE ?></title>
        <link href="Styles/general.css" rel="stylesheet" type="text/css">
    <body bgcolor="#dfdfdf">
    <script language="javascript" src="Scripts/mootools-core.js"></script>
    <script language="JavaScript1.2" src="Scripts/js_cardif_announce.js"></script>
    <script language="JavaScript1.2" src="Scripts/js_europa_announce.js"></script>
    <script language="JavaScript1.2" src="Scripts/js_skok_announce.js"></script>
    <script language="JavaScript1.2" src="Scripts/js_nhc_announce.js"></script>
    <script language="JavaScript1.2" src="Scripts/js_allianz_announce.js"></script>
	<script language="JavaScript" src="Scripts/javascript.js"></script>
	<script language="JavaScript" src="CalendarPopup.js"></script>
	<script language="JavaScript">

	var 	lista_modulow = new Array('policy_agent','policy_agent2','policy_agent_cardif','policy_agent_europa','policy_agent_europa2','policy_agent_nhc','policy_agent_skok','policy_agent_allianz','policy_agent_vig_5','policy_agent_vig_7','policy_agent_vig_2306','policy_agent_vig_14500','policy_agent_ace');

	function hide_all(){
		for (i=0;i<lista_modulow.length;i++){
			document.getElementById(lista_modulow[i]).style.display='none';
		}
	}

	function change_form(){


		if (document.getElementById('contrahent_id').value == 7592) {
			document.getElementById('policy_agent').style.display='inline';
			document.getElementById('policy_agent2').style.display='inline';
			document.getElementById('policy_agent_cardif').style.display='none';
			document.getElementById('policy_agent_europa').style.display='none';
			document.getElementById('policy_agent_europa2').style.display='none';
			document.getElementById('policy_agent_nhc').style.display='none';
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='35000';
			document.getElementById('policy_agent_skok').style.display='none';
			document.getElementById('policy_agent_allianz').style.display='none';
			document.getElementById('policy_agent_vig_5').style.display='none';
			document.getElementById('policy_agent_vig_2306').style.display='none';
			document.getElementById('policy_agent_vig_14500').style.display='none';
		}else if (document.getElementById('contrahent_id').value == 11086) {
			hide_all();
			document.getElementById('policy_agent_cardif').style.display='inline';
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
		}else if (document.getElementById('contrahent_id').value == 11170) {
			hide_all();
			document.getElementById('policy_agent_nhc').style.display='inline';
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
		}else if (document.getElementById('contrahent_id').value == 11) { //europa 11
			hide_all();
			document.getElementById('policy_agent_europa').style.display='inline';
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
		}else if (document.getElementById('contrahent_id').value == 10) { //SKOK 11
			hide_all();
			document.getElementById('policy_agent_skok').style.display='inline';
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
		}else if (document.getElementById('contrahent_id').value == 2201) { //europa 2201
			hide_all();
			document.getElementById('policy_agent_europa2').style.display='inline';
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
		}else if (document.getElementById('contrahent_id').value == 5 ) { //VIG
			hide_all();
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
			document.getElementById('policy_agent_vig_5').style.display='inline';
		}else if (document.getElementById('contrahent_id').value == 7 ) { //VIG
			hide_all();
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
			document.getElementById('policy_agent_vig_7').style.display='inline';
		}else if (document.getElementById('contrahent_id').value == 2306 ) { //VIG
			hide_all();
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
			document.getElementById('policy_agent_vig_2306').style.display='inline';
		}else if (document.getElementById('contrahent_id').value == 14500 ) { //VIG
			hide_all();
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
			document.getElementById('policy_agent_vig_14500').style.display='inline';
		}else if (document.getElementById('contrahent_id').value == 14189 ) { //ACE
			hide_all();
			document.getElementById('policyCurrency').value='PLN';
			document.getElementById('policyAmount').value='0';
			document.getElementById('policy_agent_ace').style.display='inline';
		}else{
			hide_all();
		}
	}
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


                if (form_reg.paxName.value == "") {
                    alert("<?= AS_CASADD_MSG_BRIM ?>");
                    form_reg.paxName.focus();
                    return false;
                }
                if (form_reg.paxSurname.value == "") {
                    alert("<?= AS_CASADD_MSG_BRNAZW ?>");
                    form_reg.paxSurname.focus();
                    return false;
                }
                if (form_reg.paxSex.value == "") {
                    alert("<?= AS_CASADD_MSG_BRPLCI ?>");
                    form_reg.paxSex.focus();
                    return false;
                }
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
                    alert("Brak opisu zdarzenia");
                    form_reg.event.focus();
                    return false;
                } */
             /* if (form_reg.eventDate_d.value == "" || form_reg.eventDate_m.value == "" || form_reg.eventDate_y.value == "") {
                    alert("Brak peďż˝nej daty zdarzenia");
                    form_reg.eventDate_d.focus();
                    return false;
                } else { */
                    if (isNaN(form_reg.eventDate_d.value) || isNaN(form_reg.eventDate_m.value) || isNaN(form_reg.eventDate_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATZDARZ ?>");
                        form_reg.eventDate_d.focus();
                        return false;
                    }
              //  }
                if (form_reg.notificationDate_d.value == "" || form_reg.notificationDate_m.value == "" || form_reg.notificationDate_y.value == "") {
                    alert("<?= AS_CASADD_MSG_BRAKDATZGL ?>");
                    form_reg.notificationDate_d.focus();
                    return false;
                } else {
                    if (isNaN(form_reg.notificationDate_d.value) || isNaN(form_reg.notificationDate_m.value) || isNaN(form_reg.notificationDate_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATZGL ?>");
                        form_reg.notificationDate_d.focus();
                        return false;
                    }
                }
                if (form_reg.country.value == "") {
                    alert("<?= AS_CASADD_MSG_BRAKKRZD ?>");
                    form_reg.country.focus();
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
                if (form_reg.paxPhone.value != "") {
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
                if (form_reg.contrahent_id.value == 0) {
                    alert("<?= AS_CASADD_MSG_BRKLI ?>");
                    form_reg.contrahent_id.focus();
                    return false;
                }
                if ((form_reg.validity_from_d.value != "" || form_reg.validity_from_m.value != "" || form_reg.validity_from_y.value != "" || form_reg.validity_to_d.value != "" || form_reg.validity_to_m.value != "" || form_reg.validity_to_y.value != "") && (form_reg.validity_from_d.value == "" || form_reg.validity_from_m.value == "" || form_reg.validity_from_y.value == "" || form_reg.validity_to_d.value == "" || form_reg.validity_to_m.value == "" || form_reg.validity_to_y.value == "")) {
                    alert("<?= AS_CASADD_MSG_WYPWYCZDATWAZNPWYJ ?>");
                    form_reg.validity_from_d.focus();
                    return false;
                }
                if (form_reg.validity_from_d.value != "" || form_reg.validity_from_m.value != "" || form_reg.validity_from_y.value != "") {
                    if (isNaN(form_reg.validity_from_d.value) || isNaN(form_reg.validity_from_m.value) || isNaN(form_reg.validity_from_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATWAZNWYJ ?>");
                        form_reg.validity_from_d.focus();
                        return false;
                    }
				}
                if (form_reg.validity_to_d.value != "" || form_reg.validity_to_m.value != "" || form_reg.validity_to_y.value != "") {
                    if (isNaN(form_reg.validity_to_d.value) || isNaN(form_reg.validity_to_m.value) || isNaN(form_reg.validity_to_y.value)) {
                        alert("<?= AS_CASADD_MSG_DATWAZNWYJ ?>");
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
                    form_reg.policyAmount.value = form_reg.policyAmount.value.replace(/ /g, "");
					form_reg.policyAmount.value = form_reg.policyAmount.value.replace(/\./g, "");
					form_reg.policyAmount.value = form_reg.policyAmount.value.replace(/,/g, "");
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
                    alert("<?= AS_CASADD_MSG_BLFORMNREMAIL ?>");
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

                for (var i = 0; i < document.forms['form_reg'].length; i++) {
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

                if (document.getElementById('contrahent_id').value == 7592) {
                		if ( !(document.getElementById('ryzyko_gl').value > 0) ){
                                alert('Proszę wybrać ryzyko główne!');
                				document.getElementById('ryzyko_gl').focus();
                				return false

                		}

            		    if ( !(document.getElementById('id_ryzyko').value > 0) ){
                            alert('Proszę wybrać ryzyko cząstkowe!');
                            document.getElementById('id_ryzyko').focus();
                				return false

                		}

            		    if ( document.getElementById('rezerwa').value == '' ){
                                alert('Proszę podać wartość rezerwy!');
                                document.getElementById('rezerwa').focus();
                				return false
                		}

                	}

                	if (document.getElementById('contrahent_id').value == 11086) { //cardif
                		if (document.getElementById('typ_umowy').value == 0 ) {
								alert("Prosze wybraďż˝ typ umowy");
								document.getElementById('typ_umowy').focus();
								return false;
						}


						if (document.getElementById('wariant_ubezpieczenia').value > 0 ) {

						}else{
							alert("Prosze wybraďż˝ wariant umowy");
							document.getElementById('wariant_ubezpieczenia').focus();
							return false;
						}

						if (document.getElementById('id_swiadczenie').value == 0 ) {
									alert("Prosze wybraďż˝ ďż˝wiadczenie");
									document.getElementById('id_swiadczenie').focus();
									return false;
						}

	            		if ( document.getElementById('rezerwa_cardif').value == '' ){
	                				alert('Proszďż˝ podaďż˝ wartoďż˝ďż˝ rezerwy!');
	                				document.getElementById('rezerwa_cardif').focus();
	                				return false;
	                	}
                }


                 if (document.getElementById('contrahent_id').value == 11170) { //NHC
                		if (document.getElementById('nhc_policy_type').value == 0 ) {
								alert("Prosze wybraďż˝ typ umowy");
								document.getElementById('nhc_policy_type').focus();
								return false;
						}


					if (document.getElementById('nhc_main_cause').value > 0 ) {

					}else{
						alert("Prosze wybraďż˝ typ zdarzenia");
						document.getElementById('nhc_main_cause').focus();
						return false;
					}

					if (document.getElementById('nhc_id_swiadczenie').value == 0 ) {
								alert("Prosze wybraďż˝ rezerwe");
								document.getElementById('nhc_id_swiadczenie').focus();
								return false;
					}

            		if ( document.getElementById('nhc_rezerwa').value == '' ){
                				alert('Proszďż˝ podaďż˝ wartoďż˝ďż˝ rezerwy!');
                				document.getElementById('nhc_rezerwa').focus();
                				return false
                	}

            		if ( document.getElementById('nhc_rezerwacurrency_id').value == '' ){
                				alert('Proszďż˝ podaďż˝ walutďż˝ rezerwy!');
                				document.getElementById('nhc_rezerwacurrency_id').focus();
                				return false
                	}
                }

                 if (document.getElementById('contrahent_id').value == 5 ) { //VIG 5
              		if (document.getElementById('vig_5_program').value == 0 ) {
 								alert("Prosze wybraďż˝ program");
 								document.getElementById('vig_5_program').focus();
 								return false;
 						}
                 }
                 if (document.getElementById('contrahent_id').value == 7 ) { //VIG 7
              		if (document.getElementById('vig_7_program').value == 0 ) {
 							alert("Prosze wybraďż˝ program");
 							document.getElementById('vig_7_program').focus();
 							return false;
 					}
       		}

                 if (document.getElementById('contrahent_id').value == 2306 ) { //VIG 2306
              		if (document.getElementById('vig_2306_program').value == 0 ) {
 								alert("Prosze wybraďż˝ program");
 								document.getElementById('vig_2306_program').focus();
 								return false;
 						}
                 }
                 if (document.getElementById('contrahent_id').value == 14500 ) { //VIG 14500
              		if (document.getElementById('vig_14500_program').value == 0 ) {
 								alert("Prosze wybraďż˝ program");
 								document.getElementById('vig_14500_program').focus();
 								return false;
 						}
                 }
                 if (document.getElementById('contrahent_id').value == 14189 ) { //ACE 14189
              		if (document.getElementById('ace_program').value == 0 ) {
 								alert("Prosze wybraďż˝ program");
 								document.getElementById('ace_program').focus();
 								return false;
 						}
                 }

                 <?php if ($_SESSION['coris_branch']==1 ){?>
                if ($('country').value == 'DE' || $('country').value == 'AT'){
						if (confirm('Czy jesteďż˝ pewien, ďż˝e wybraďż˝eďż˝ prawidďż˝owy oddziaďż˝?')){
							return true;
						}else{
							return false;
						}

               }
                <?php }else{ ?>
		                if (confirm('Czy jesteďż˝ pewien, ďż˝e wybraďż˝eďż˝ prawidďż˝owy oddziaďż˝?')){
							return true;
						}else{
							return false;
						}
                <?php }?>
            }

            // do przechodzenia w polach daty
			// TODO: Poprawiďż˝ - aby nie byďż˝o "for"
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
				year  = y2k(today.getFullYear());

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
        <table cellpadding=4 cellspacing=0 border=0 width="100%">
            <tr style="border-left: #eeeeee 1px solid; border-right: #eeeeee 1px solid; border-bottom: #eeeeee 1px solid; border-top: #eeeeee 1px solid">
                <td align="center" bgcolor="#cccccc">
                    <b><?= CASEADD ?></b>
                </td>
            </tr>
            <tr>
                <td align="center">
<?php

if (isset($_GET['action']) && !(isset($_SESSION['coris_case_submit']) && $_SESSION['coris_case_submit'] == "$_POST[paxName]#$_POST[paxSurname]")) {

	$contrahent_id = getValue('contrahent_id');

    $case_type = 2; // SPRAWA MEDYCZNA

    if ($contrahent_id == 82 ) $case_type=10;
    mysql_query("BEGIN");
    	$query = "INSERT INTO coris_assistance_cases (number, year) SELECT MAX(number+1) AS number, year(NOW()) FROM coris_assistance_cases WHERE year = year(NOW())";

    if ($result = mysql_query($query)) {
    	$c_id = mysql_insert_id();

        $query = "SELECT case_id, number, year FROM coris_assistance_cases WHERE case_id = '$c_id' ";

        if ($result = mysql_query($query)) {
            if (!$row = mysql_fetch_array($result))
                die("Problem z pobraniem numeru sprawy");

                $zmiana_numeru = '';
             if ($row['number'] == 0){
				$zmiana_numeru = ',number=1';
				$row['number'] = 1;
			}

			$only_info=getValue('only_info') == 1 ? 1 : 0;
			$branch_id = getValue('branch_id');

            $query = "UPDATE coris_assistance_cases SET
            coris_branch_id = '$branch_id',
            client_id = '$_POST[contrahent_id]', type_id = $case_type, client_ref = '$_POST[contrahent_ref]',
            date = NOW(),only_info='$only_info',
            user_id = '$_SESSION[user_id]', operational=1,
            paxname = '".getValue('paxName')."', paxsurname = '".getValue('paxSurname')."',
            paxsex = '".getValue('paxSex')."', paxdob = '$_POST[paxDob_y]-$_POST[paxDob_m]-$_POST[paxDob_d]',
            policy_series='".getValue('policy_series')."',policy = '$_POST[policy]', event = '$_POST[event]',
             eventdate = '$_POST[eventDate_y]-$_POST[eventDate_m]-$_POST[eventDate_d]', country_id = '$_POST[country]', city = '$_POST[city]'
               $zmiana_numeru
            WHERE case_id = $row[case_id]";

            if ($result = mysql_query($query)) {

            	CaseInfo::updateFullNumber($c_id);

                $pax_place_of_stay = getValue('paxplaceofstay');

                $query = "INSERT INTO coris_assistance_cases_details
                                        (case_id, notificationdate,notificationTime ,
                                         informer, validityfromDep,
                                         validitytoDep,
                                         policypurchasedate,
                                         policypurchaselocation, policyamount,  policycurrency_id,
                                         circumstances, paxaddress, paxpost,
                                         paxcity, paxcountry, paxphone, paxmobile, pax_place_of_stay, benSurname,benName)
                               VALUES ('$row[case_id]', '$_POST[notificationDate_y]-$_POST[notificationDate_m]-$_POST[notificationDate_d]','".getValue('notificationTime')."',
                                       '$_POST[informer]', '$_POST[validity_from_y]-$_POST[validity_from_m]-$_POST[validity_from_d]',
                                       '$_POST[validity_to_y]-$_POST[validity_to_m]-$_POST[validity_to_d]',
                                        '$_POST[policyPurchaseDate_y]-$_POST[policyPurchaseDate_m]-$_POST[policyPurchaseDate_d]',
                                        '$_POST[policyPurchaseLocation]', '$_POST[policyAmount]', '$_POST[policyCurrency]',
                                        '".getValue('circumstances')."', '$_POST[paxAddress]', '$_POST[paxPost_1]-$_POST[paxPost_2]',
                                        '".mysql_real_escape_string($_POST[paxCity])."', '$_POST[paxCountry]', '$_POST[paxPhone]', '$_POST[paxMobile]', '$pax_place_of_stay','".mysql_real_escape_string($_POST['paxSurname'])."','".mysql_real_escape_string($_POST['paxName'])."')";

                if ($result = mysql_query($query)) {
                    mysql_query("COMMIT");

                    /*  if ($branch_id == 1){
                          $case_rezerwa = str_replace(',','.',getValue('case_rezerwa'));
                          $case_rezerwa_waluta = getValue('case_rezerwa_waluta');
                          CaseInfo::setGLobalReserveStart($c_id,$case_rezerwa,$case_rezerwa_waluta);


                      }*/

                    	//CaseInfo::setCaseState($c_id,1,0); // Status wtrakcie likwidacji

                    if (getValue('contrahent_id') == 7592){ //signal
                    	$ryzyko_gl=getValue('ryzyko_gl') > 0 ? getValue('ryzyko_gl') : 0;
						$biurop_id=getValue('biurop_id') > 0 ? getValue('biurop_id') : 0 ;

                    	$id_ryzyko=getValue('id_ryzyko') > 0 ? getValue('id_ryzyko') : 0 ;
						$rezerwa = str_replace(',','.',getValue('rezerwa'));
						$rezerwacurrency_id=getValue('rezerwacurrency_id');

                    	 $qi = "INSERT INTO coris_assistance_cases_announce SET case_id='$c_id', biurop_id='$biurop_id',ryzyko_gl='$ryzyko_gl' ";
                    	 $mr = mysql_query($qi);

                    	/* REZERWA */
                    	$qi2  = "INSERT INTO coris_assistance_cases_reserve  SET case_id ='$c_id',ID_ryzyko ='$id_ryzyko',rezerwa ='$rezerwa',suma='".str_replace(',','.',$_POST['policyAmount'])."',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1";
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ($mr && $mr2){
							$poz = mysql_insert_id();
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();
						}


							if ($poz>0){// insert history
								$qi3  = "INSERT INTO coris_assistance_cases_reserve_history  SET  ID_reserve  ='$poz',rezerwa ='$rezerwa',suma='".str_replace(',','.',$_POST['policyAmount'])."',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now()";
								$mr3 = mysql_query($qi3);
							}

						/* REZERWA CORIS */
						$qi2  = "INSERT INTO coris_assistance_cases_reserve  SET case_id ='$c_id',ID_ryzyko ='24',rezerwa ='$rezerwa_coris',suma='$rezerwa_coris',currency_id ='PLN', ID_user='".$_SESSION['user_id']."',date=now(), status=1";
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ($mr && $mr2){
							$poz = mysql_insert_id();
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();
						}

						if ($poz>0){// insert history
								$qi3  = "INSERT INTO coris_assistance_cases_reserve_history  SET  ID_reserve  ='$poz',rezerwa ='$rezerwa_coris',suma='$rezerwa_coris',currency_id ='PLN', ID_user='".$_SESSION['user_id']."',date=now()";
								$mr3 = mysql_query($qi3);
							}

						if ($poz>0){
							/* WYPLATA CORIS */
							$qi = "INSERT INTO coris_assistance_cases_coris_pay  SET case_id ='$c_id',ID_ryzyko ='24',number =1,amount ='$rezerwa_coris',currency_id ='PLN',ID_opis_rachunku =2,date=now(),status=1	";

							$mr2 = mysql_query($qi);
							if ( $mr2){

							}else{
								echo "<br>Update Error: ".$qi."\n<br> ".mysql_error();
							}
						}



                    }else  if (getValue('contrahent_id') == 11086){ //cardif

                    	$typ_umowy=getValue('typ_umowy') > 0 ? getValue('typ_umowy') : 0;
						$wariant_ubezpieczenia=getValue('wariant_ubezpieczenia') > 0 ? getValue('wariant_ubezpieczenia') : 0 ;

                    	$id_swiadczenie=getValue('id_swiadczenie') > 0 ? getValue('id_swiadczenie') : 0 ;

						$rezerwa = str_replace(',','.',getValue('rezerwa_cardif'));
						$rezerwacurrency_id=getValue('rezerwacurrency_id');

						$suma_ubezpieczenia = str_replace(',','.',getValue('suma_ubezpieczenia'));
						$sumacurrency_id=getValue('sumacurrency_id');

                    	 $qi = "INSERT INTO coris_cardif_announce  SET case_id='$c_id', ID_typ_umowy='$typ_umowy',ID_wariant_ubezpieczenia ='$wariant_ubezpieczenia' ";
                    	 $mr = mysql_query($qi);

                    	/* REZERWA */
                    	$qi2  = "INSERT INTO coris_cardif_cases_reserve  SET case_id ='$c_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$rezerwa',suma='".$suma_ubezpieczenia."',currency_id ='$sumacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1";
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ($mr && $mr2){
							$poz = mysql_insert_id();
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();
						}


							/*if ($poz>0){// insert history
								$qi3  = "INSERT INTO coris_assistance_cases_reserve_history  SET  ID_reserve  ='$poz',rezerwa ='$rezerwa',suma='".str_replace(',','.',$_POST['policyAmount'])."',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now()";
								$mr3 = mysql_query($qi3);
							}
							*/
                    }else  if (getValue('contrahent_id') == 11170){ //NHC


                    	$nhc_policy_type=getValue('nhc_policy_type') > 0 ? getValue('nhc_policy_type') : 0;
						$nhc_main_cause=getValue('nhc_main_cause') > 0 ? getValue('nhc_main_cause') : 0 ;

                    	$id_swiadczenie=getValue('nhc_id_swiadczenie') > 0 ? getValue('nhc_id_swiadczenie') : 0 ;

						$rezerwa = str_replace(',','.',getValue('nhc_rezerwa'));
						$rezerwacurrency_id=getValue('nhc_rezerwacurrency_id');
						$country=getValue('country');

						$qc = "SELECT country_code FROM coris_nhc_country  WHERE country_id ='$country' ";
						$mr = mysql_query($qc);
						$rr = mysql_fetch_array($mr);

						$country = $rr['country_code'];


                    	 $qi = "INSERT INTO coris_nhc_announce  SET case_id='$c_id', ID_policy_type ='$nhc_policy_type',ID_main_cause  ='$nhc_main_cause',country_inc='$country' ";
                    	 $mr = mysql_query($qi);

                    	/* REZERWA */
                    	$qi2  = "INSERT INTO coris_nhc_cases_reserve  SET case_id ='$c_id',ID_swiadczenie ='$id_swiadczenie',rezerwa ='$rezerwa',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now(), status=1";
						$mr2 = mysql_query($qi2);
						$poz=0;
						if ($mr && $mr2){
							$poz = mysql_insert_id();
						}else{
							echo  "<br>Update Error: ".$qi2."\n<br> ".mysql_error();
						}


							if ($poz>0){// insert history
								$qi3  = "INSERT INTO coris_nhc_reserve_history  SET  ID_reserve  ='$poz',reserve_new ='$rezerwa',currency_id ='$rezerwacurrency_id', ID_user='".$_SESSION['user_id']."',date=now()";
								$mr3 = mysql_query($qi3);
							}

                    }else  if (getValue('contrahent_id') == 2201){ //europa 2201

    					$case_id= $c_id;

                    	$typ_umowy=getValue('europa2_typ_umowy') > 0 ? getValue('europa2_typ_umowy') : 0;
						$wariant_ubezpieczenia=getValue('europa2_wariant_ubezpieczenia') > 0 ? getValue('europa2_wariant_ubezpieczenia') : 0 ;
						$opcje_ubezpieczenia = $_POST['europa2_opcje_ubezpieczenia'];


						$rezerwa = str_replace(',','.',getValue('europa2_rezerwa'));
						$rezerwacurrency_id=getValue('europa2_rezerwa_currency_id');

						$suma_ubezpieczenia = str_replace(',','.',getValue('europa2_suma_ubezpieczenia'));
						$sumacurrency_id=getValue('europa2_suma_ub_currency_id');
						$biuro_podrozy = 0;



							if (is_array($opcje_ubezpieczenia)){
								foreach ($opcje_ubezpieczenia As $poz){
									$query = "INSERT INTO coris_europa_announce_opcje  SET  case_id ='$case_id',ID_opcja='$poz' ";
									$mysql_result = mysql_query($query);
									//echo $query. " <br>".mysql_error();
								}
							}
						$var = " ID_typ_umowy='$typ_umowy', ID_wariant='$wariant_ubezpieczenia',ID_status='1',ID_biuro_podrozy='$biuro_podrozy',
						rezerwa_globalna= '$rezerwa',rezerwa_currency_id ='$rezerwacurrency_id',
						suma_ubezpieczenia='$suma_ubezpieczenia',suma_ubezpieczenia_currency_id='$sumacurrency_id' ";
		/////////////////////


						$var2 = "policyamount='$suma_ubezpieczenia',policycurrency_id ='$sumacurrency_id' ";


						$query = "INSERT INTO coris_europa_announce SET case_id='$case_id',forma_wyplaty=1, $var ";
						$query2 = "UPDATE coris_assistance_cases_details  SET $var2 WHERE case_id='$case_id' LIMIT 1 ";



						$mr = mysql_query($query);


								$mr2 = mysql_query($query2);
								$poz=0;
								if ($mr && $mr2){
								}else{
										echo  "<br>Update Error: $query <br>".$query2."\n<br> ".mysql_error();
								 }
						//history rezerwa_globalna
						$query = "INSERT INTO coris_europa_rezerwa_globalna_historia  SET case_id=$case_id,rezerwa_stara=0,rezerwa_nowa='$rezerwa',currency_id='$rezerwacurrency_id',ID_user='".$_SESSION['user_id']."',date=now();";
							$mr = mysql_query($query);

                 }else  if (getValue('contrahent_id') == 11){ //europa 11
    					$case_id= $c_id;

                    	$typ_umowy=getValue('europa11_typ_umowy') > 0 ? getValue('europa11_typ_umowy') : 0;
                    	$europa11_rodzaj_szkody=getValue('europa11_rodzaj_szkody') > 0 ? getValue('europa11_rodzaj_szkody') : 0;

						$biuro_podrozy=getValue('europa11_biuro_podrozy') > 0 ? getValue('europa11_biuro_podrozy') : 0 ;
						$wariant_ubezpieczenia=getValue('europa11_wariant_ubezpieczenia') > 0 ? getValue('europa11_wariant_ubezpieczenia') : 0 ;
						$opcje_ubezpieczenia = $_POST['europa11_opcje_ubezpieczenia'];


						$rezerwa = str_replace(',','.',getValue('europa11_rezerwa_europa'));
						$rezerwacurrency_id=getValue('europa11_rezerwacurrency_id');

						$suma_ubezpieczenia = str_replace(',','.',getValue('europa11_suma_ubezpieczenia'));
						$sumacurrency_id=getValue('europa11_sumacurrency_id');

						if (is_array($opcje_ubezpieczenia)){
								foreach ($opcje_ubezpieczenia As $poz){
									$query = "INSERT INTO coris_europa_announce_opcje  SET  case_id ='$case_id',ID_opcja='$poz' ";
									$mysql_result = mysql_query($query);
									//echo $query. " <br>".mysql_error();
								}
						}
					$var = " ID_typ_umowy='$typ_umowy', ID_wariant='$wariant_ubezpieczenia',ID_rodzaj='$europa11_rodzaj_szkody',ID_biuro_podrozy='$biuro_podrozy',
						rezerwa_globalna= '$rezerwa',rezerwa_currency_id ='$rezerwacurrency_id',
						suma_ubezpieczenia='$suma_ubezpieczenia',suma_ubezpieczenia_currency_id='$sumacurrency_id' ";
		/////////////////////


						$var2 = "policyamount='$suma_ubezpieczenia',policycurrency_id ='$sumacurrency_id' ";


						$query = "INSERT INTO coris_europa_announce SET case_id='$case_id',forma_wyplaty=1, $var ";
						$query2 = "UPDATE coris_assistance_cases_details  SET $var2 WHERE case_id='$case_id' LIMIT 1 ";



						$mr = mysql_query($query);


								$mr2 = mysql_query($query2);
								$poz=0;
								if ($mr && $mr2){
								}else{
										echo  "<br>Update Error: $query <br>".$query2."\n<br> ".mysql_error();
								 }
						//history rezerwa_globalna
						$query = "INSERT INTO coris_europa_rezerwa_globalna_historia  SET case_id=$case_id,rezerwa_stara=0,rezerwa_nowa='$rezerwa',currency_id='$rezerwacurrency_id',ID_user='".$_SESSION['user_id']."',date=now();";
							$mr = mysql_query($query);



							$europa_case = new EuropaCase($case_id);
							$europa_case->setStatus(5);
            }	else  if (getValue('contrahent_id') == 10){ //SKOK 10
    					$case_id= $c_id;

                    	$typ_umowy=getValue('skok10_typ_umowy') > 0 ? getValue('skok10_typ_umowy') : 0;
                    	$skok10_rodzaj_szkody=getValue('skok10_rodzaj_szkody') > 0 ? getValue('skok10_rodzaj_szkody') : 0;

						$biuro_podrozy=getValue('skok10_biuro_podrozy') > 0 ? getValue('skok10_biuro_podrozy') : 0 ;
						$wariant_ubezpieczenia=getValue('skok10_wariant_ubezpieczenia') > 0 ? getValue('skok10_wariant_ubezpieczenia') : 0 ;
						$opcje_ubezpieczenia = $_POST['skok10_opcje_ubezpieczenia'];


						$rezerwa = str_replace(',','.',getValue('skok10_rezerwa_skok'));
						$rezerwacurrency_id=getValue('skok10_rezerwacurrency_id');

						$suma_ubezpieczenia = str_replace(',','.',getValue('skok10_suma_ubezpieczenia'));
						$sumacurrency_id=getValue('skok10_sumacurrency_id');

						if (is_array($opcje_ubezpieczenia)){
								foreach ($opcje_ubezpieczenia As $poz){
									$query = "INSERT INTO coris_skok_announce_opcje  SET  case_id ='$case_id',ID_opcja='$poz' ";
									$mysql_result = mysql_query($query);
									//echo $query. " <br>".mysql_error();
								}
						}
					$var = " ID_typ_umowy='$typ_umowy', ID_wariant='$wariant_ubezpieczenia',ID_rodzaj='$skok10_rodzaj_szkody',ID_biuro_podrozy='$biuro_podrozy',
						rezerwa_globalna= '$rezerwa',rezerwa_currency_id ='$rezerwacurrency_id',
						suma_ubezpieczenia='$suma_ubezpieczenia',suma_ubezpieczenia_currency_id='$sumacurrency_id' ";
		/////////////////////


						$var2 = "policyamount='$suma_ubezpieczenia',policycurrency_id ='$sumacurrency_id' ";


						$query = "INSERT INTO coris_skok_announce SET case_id='$case_id', $var ";
						$query2 = "UPDATE coris_assistance_cases_details  SET $var2 WHERE case_id='$case_id' LIMIT 1 ";



						$mr = mysql_query($query);


								$mr2 = mysql_query($query2);
								$poz=0;
								if ($mr && $mr2){
								}else{
										echo  "<br>Update Error: $query <br>".$query2."\n<br> ".mysql_error();
								 }
						//history rezerwa_globalna
						$query = "INSERT INTO coris_skok_rezerwa_globalna_historia  SET case_id=$case_id,rezerwa_stara=0,rezerwa_nowa='$rezerwa',currency_id='$rezerwacurrency_id',ID_user='".$_SESSION['user_id']."',date=now();";
							$mr = mysql_query($query);



							$skok_case = new SKOKCase($case_id);
							$skok_case->setStatus(5);
            }	else  if (getValue('contrahent_id') == 5){ //VIG 5
    					$case_id= $c_id;

                    	$program=getValue('vig_5_program') > 0 ? getValue('vig_5_program') : 0;
					VIGCase::aktualizacja_programu($case_id, $program);


            }else  if (getValue('contrahent_id') == 7){ //VIG 7
    					$case_id= $c_id;

                    	$program=getValue('vig_7_program') > 0 ? getValue('vig_7_program') : 0;

                    	VIGCase::aktualizacja_programu($case_id, $program);


            }	else  if (getValue('contrahent_id') == 2306){ //VIG 2306
    					$case_id= $c_id;

                    	$program=getValue('vig_2306_program') > 0 ? getValue('vig_2306_program') : 0;
					VIGCase::aktualizacja_programu($case_id, $program);


            }	else  if (getValue('contrahent_id') == 14500){ //VIG 14500
    					$case_id= $c_id;

                    	$program=getValue('vig_14500_program') > 0 ? getValue('vig_14500_program') : 0;
						VIGCase::aktualizacja_programu($case_id, $program);

            }	else  if (getValue('contrahent_id') == 14189){ //ACE 14189
    					$case_id= $c_id;
                    	$program=getValue('ace_program') > 0 ? getValue('ace_program') : 0;
                    	ACECase::aktualizacja_programu($case_id, $program);
            }

            	CaseInfo::setCaseOperatingUser($c_id,Application::getCurrentUser());

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

					$client_id = $_POST['contrahent_id'];
					if ($client_id == 606 or
						$client_id == 607 or
						$client_id == 608 or
						$client_id == 609 or
						$client_id == 610 or
						$client_id == 611 or
						$client_id == 612 or
						$client_id == 613 or
						$client_id == 614 or
						$client_id == 615 or
						$client_id == 616 or
						$client_id == 617 or
						$client_id == 618 or
						$client_id == 619 or
						$client_id == 620 or
						$client_id == 621 or
						$client_id == 622 or
						$client_id == 623 or
						$client_id == 624 or
						$client_id == 625 or
						$client_id == 626 or
						$client_id == 627 or
						$client_id == 628 or
						$client_id == 630 or
						$client_id == 652) {
						echo "<font size=\"+1\">".AS_CASADD_PROSZEZAPISACNRSPR.": </font><font size=\"+1\" color=\"red\"><B>$row[number]/$row[year]/$case_type/$_POST[contrahent_id]</B></font>.<br>".AS_CASADD_ABYOTWSPRA.": <input type=\"button\" value=\"&raquo;\" onclick=\"open_case(".$row['case_id'].",'casewindow".$row['case_id']."');\" style=\"height: 18pt;\"><br>
<!-- Jeďż˝eli chcesz wydrukowaďż˝ zgďż˝oszenie, kliknij tutaj: <img src=\"graphics/ico_print.gif\" onmouseover=\"this.style.cursor='hand'\" onclick=\"window.open('AS_cases_add_med_form_notes_Warta.php?case_id=$row[case_id]','printwindow$row[case_id]','toolbar=yes,scrollbars=yes,location=no,status=yes,menubar=yes,resizable=no,width=750,height=450,screenX=100,screenY=100')\"> \/\/--> <br>
".AS_CASADD_MSG_JCHWPINOWSZK."<br>";

					} else {

						echo "<font size=\"+1\">".AS_CASADD_PROSZEZAPISACNRSPR.": </font><font size=\"+1\" color=\"red\"><B>$row[number]/$row[year]/$case_type/$_POST[contrahent_id]</B></font>.<br>".AS_CASADD_ABYOTWSPRA.": <input type=\"button\" value=\"&raquo;\" onclick=\"open_case(".$row['case_id'].",'casewindow".$row['case_id']."');\" style=\" height: 18pt;\"><br>
<!-- Jeďż˝eli chcesz wydrukowaďż˝ zgďż˝oszenie, kliknij tutaj: <img src=\"graphics/ico_print.gif\" onmouseover=\"this.style.cursor='hand'\" onclick=\"window.open('AS_cases_add_med_form_notes.php?case_id=$row[case_id]','printwindow$row[case_id]','toolbar=yes,scrollbars=yes,location=no,status=yes,menubar=yes,resizable=no,width=750,height=450,screenX=100,screenY=100')\"> \/\/--> <br>
".AS_CASADD_MSG_JCHWPINOWSZK."<br>";

					}


                } else {
                	$err = mysql_error();
                    mysql_query("ROLLBACK");
                    mail("krzysiek@evernet.com.pl",'Coris SSA SQL ERROR',"q: $query \n\n".$err);

                    die($err);
                }
            } else {
            	$err = mysql_error();
                mysql_query("ROLLBACK");
                die($err);
            }
        } else {
        	$err = mysql_error();
            mysql_query("ROLLBACK");
            die($err);
        }
    }else{
        echo 'Error 957;';
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
                    <input type="button" value="<?= AS_CASADD_SPRMED ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: #6699cc; width: 150px" disabled>
                    <input type="button" value="<?= AS_CASADD_SPRTECH ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: orange; width: 150px" onclick="document.location='AS_cases_add_tech.php'">
                  <?php   if (isset($_SESSION['coris_branch']) &&  $_SESSION['coris_branch'] == 1 ){ ?>
                    <input type="button" value="<?= AS_CASADD_SPRLS ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: #66cc66; width: 150px" onclick="document.location='AS_cases_add_ls.php'">
                  <?php } ?>
                </td>
            </tr>
        </table>
        <br>
        <center><a name="menu"><font color="#6699cc"><a href="#poszkodowany"><font color="#6699cc"><?= AS_CASADD_POSZK ?></font></a> | <a href="#szkoda"><font color="#6699cc"><?= AS_CASADD_SZK ?></font></a> | <a href="#polisa"><font color="#6699cc"><?= AS_CASADD_POL?></font></a> | <a href="#szczegoly"><font color="#6699cc"><?= AS_CASADD_SZCZ ?></font></a> | <a href="#kontakty"><font color="#6699cc"><?= AS_CASADD_KONT ?></font></a></font></a></center>
        <table><tr height="3"><td></td></tr></table>

            <div align="left"><font color="#6699cc"><?= AS_CASADD_SPRMED ?></font></div>
        <hr noshade size="1" color="#6699cc">

        <form action="AS_cases_add_med.php?action=1" method="post" name="form_reg" id="form_reg" onsubmit="return validate();">


        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9" border="0">
            <tr bgcolor="#eeeeee">
                <td width="110" align="center"><img src="img/1B.gif"></td>
		<td ><a href="#menu" tabindex="-1"><img src="img/KwadN.gif" border="0"></a>&nbsp;<a name="poszkodowany"><b><font color="#6699cc"><?= AS_CASADD_DANWYM ?></font></b></a></td>
            </tr>
            <tr>
                <td align="right"><b><small><?= AS_CASD_TYLKINF2 ?></small></b></td>
                <td> <input type="checkbox"  name="only_info"  id="only_info" value="1"></td>
            </tr>
            <tr>
                <td align="right"><b><small><?= SURNAME ?></small></b></td>
                <td> <input type="text" onChange="javascript:this.value=this.value.toUpperCase();" name="paxSurname" size="50" maxlength="50" class="required"></td>
            </tr>
            <tr>
                <td  align="right"><b><small><?= NAME ?></small></b></td>
                <td> <input type="text" onChange="javascript:this.value=this.value.toUpperCase();" name="paxName" size="25" maxlength="25" class="required"></td>
            </tr>
         <tr>
                <td  align="right"><b><small><?php echo GENDER; ?></small></b></td>
                <td> <?php
                 echo getPlec('paxSex','',0,'class="required"');
                ?></td>
            </tr>
            <tr>
                <td  align="right"><b><small><?= AS_CASADD_TOW ?></small></b></td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <input type="text" id="contrahent_id" name="contrahent_id" value="" size="5" onblur="contrahent_search_frame.location='GEN_contrahents_select_iframe.php?contrahent_id=' + this.value+'&branch_id='+$('branch_id').value;change_form();" onchange="change_form();" style="text-align: center;" class="required">
                                <input type="text" id="contrahent_name" name="contrahent_name" size="60" disabled> <input type="button" value="L" style="background: #cccccc; color: #6699cc; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 18pt;" onclick="window.open('GEN_contrahents_select_frameset.php?branch_id='+$('branch_id').value,'contrahentsearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=650,height=420,left='+ (screen.availWidth - 550) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 420) / 2);" title="<?= AS_CASADD_WYSZKLI ?>">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td  align="right"><b><small><?= COUNTRY  ?></small></b></td>
                <td align="left">
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
    $defaultCountrySymbol = '';
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

            <?php
            if (isset($_SESSION['coris_branch']) && 1 == $_SESSION['coris_branch'])
            {
                echo '<tr><td align="right"><b><small>' . BRANCH . '</small></b></td><td>' . print_user_coris_branch2('branch_id', 1) . '</td></tr>';
            }else{
                //echo '<tr><td><input type="hidden" name="branch_id" id="branch_id" value="2"></td><td></td></tr>';
                echo '<tr><td align="right"><b><small>' . BRANCH . '</small></b></td><td>' . print_user_coris_branch_de('branch_id', 1) . '</td></tr>';
            }
            ?>

<?php if(  $_SESSION['coris_branch'] == 1 ){ /* ?>
  <tr>
<td align="right"><div align="right"  > <small><b><?= AS_REZ_REZGLOB ?>:</b></samll></small></div>
				<td><input type="text" name="case_rezerwa" id="case_rezerwa" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency_pln('case_rezerwa_waluta','PLN',0,'class="required"'); ?>
</td>
</tr>
<?php */}?>
      	    <tr>
		<td colspan="2">
      	     <span id="policy_agent2">
          <table border="0" cellspacing=1 cellpadding=2  bgcolor="#d9d9d9" >
           <tr>
                <td width="100" align="right"><small>Ryzyko gďż˝ďż˝wne:</small></td>
                <td>
					<?php		echo  wysw_ryzyko_gl('ryzyko_gl',0,0,'class="required"'); ?>
                </td>
            </tr> <tr>
                <td width="100" align="right"><small>Rezerwa:</small></td>
                <td><small><b>Ryzyko czďż˝stkowe: </b></small> <?php echo wysw_ryzyko_czastkowe('id_ryzyko',0,0,0,'class="required"') ?>&nbsp;&nbsp;&nbsp;<small><b>Rezerwa:</b></samll>&nbsp;&nbsp;
				<input type="text" name="rezerwa" id="rezerwa" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency_pln('rezerwacurrency_id','PLN',0,'class="required"'); ?>


                </td>
            </tr>
          </table>
        </span>
        <span id="policy_agent_cardif">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Typ umowy:</b>	</td><td>
					<?php echo wysw_typy_umowy('typ_umowy',0,0,'onChange="getWariantUmowy(this.value,\'wariant_ubezpieczenia\');getSwiadczenia(this.value,\'id_swiadczenie\');" class="required"'); ?>
						</td></tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td>
					<?php echo  wysw_wariant_umowy('wariant_ubezpieczenia',0,0,0,' class="required"'); ?>
						</td>
					</tr>
			 <tr>
			 <td width="5%">&nbsp;</td>
                <td  align="right"><b>Ryzyko czďż˝stkowe: </b></td>
                <td align="right"> <?php echo wysw_swiadczenie('id_swiadczenie',0,0,0,'class="required" onChange="getSumaUbezp(this.value,\'typ_umowy\',\'suma_ubezpieczenia\');"'); ?>&nbsp;&nbsp;&nbsp;<small><b>Rezerwa:</b></samll>&nbsp;&nbsp;
				<input type="text" name="rezerwa_cardif" id="rezerwa_cardif" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency_pln('rezerwacurrency_id','PLN',0,'class="required"'); ?>

			<br>&nbsp;&nbsp;&nbsp;<small><b>Suma ubezpieczenia:</b></samll>&nbsp;&nbsp;
				<input type="text" name="suma_ubezpieczenia" id="suma_ubezpieczenia" value=""  style="text-align: right;" size="15" maxlength="20" class="required1">
				<?php  echo wysw_currency_pln('sumacurrency_id','PLN',0,'class="required"'); ?>


                </td>
            </tr>
          </table>
        </span>
        <span id="policy_agent_nhc">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b><?= AS_NHC_POLICY_TYPE ?>:</b>	</td><td>
					<?php
					echo NHCCase::wysw_policy_type('nhc_policy_type',0,0,'onChange="NHCgetMainCases(this.value,\'nhc_main_cause\');" class="required"');

				?>
						</td></tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b><?= AS_NHC_MAIN_CAUSES ?>:</b></td><td>
					<?php
					 echo NHCCase::wysw_main_causes('nhc_main_cause',0,0,0,' class="required"');;
					?>
						</td>
					</tr>
			 <tr>
			 <td width="5%">&nbsp;</td>
                <td  align="right"><b>Rezerwa: </b></td>
                <td align="right"> <?php echo NHCCase::wysw_rezerwy('nhc_id_swiadczenie',0,0,'class="required"'); ?>&nbsp;&nbsp;&nbsp;<small><b>Rezerwa:</b></small>&nbsp;&nbsp;
				<input type="text" name="nhc_rezerwa" id="rezerwa_nhc" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency('nhc_rezerwacurrency_id','PLN',0,'class="required"'); ?>


                </td>
            </tr>
          </table>
        </span>
        <span id="policy_agent_ace">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Program:</b>	</td><td>
					<?php
					echo ACECase::wysw_program('ace_program',0,0,'" class="required"');

				?>
					</td></tr>
          </table>
        </span>
        <span id="policy_agent_vig_5">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Program:</b>	</td><td>
					<?php
					echo VIGCase::wysw_program(5,'vig_5_program',0,0,'" class="required"');

				?>
					</td></tr>
          </table>
        </span>
        <span id="policy_agent_vig_7">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Program:</b>	</td><td>
					<?php
					echo VIGCase::wysw_program(7,'vig_7_program',0,0,'" class="required"');

				?>
					</td></tr>
          </table>
        </span>
        <span id="policy_agent_vig_2306">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Program:</b>	</td><td>
					<?php
					echo VIGCase::wysw_program(2306,'vig_2306_program',0,0,'" class="required"');

				?>
					</td></tr>
          </table>
        </span>
        <span id="policy_agent_vig_14500">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Program:</b>	</td><td>
					<?php
					echo VIGCase::wysw_program(14500,'vig_14500_program',0,0,'" class="required"');

				?>
					</td></tr>
          </table>
        </span>
        <span id="policy_agent_skok">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Typ umowy:</b>	</td><td>
					<?php
							echo SKOKCase::wysw_typy_umowy(10,'skok10_typ_umowy',0,0,' class="required" ');
					?>
						</td></tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Biuro podrďż˝y:</b></td><td>
					<?php
			//echo   skokCase::wysw_biura_podrozy('skok10_biuro_podrozy',0,0,0,' class="required" onChange="getWariantUmowyskok(this.value,document.getElementById(\'typ_umowy\').value,\'skok10_wariant_ubezpieczenia\');"');
			echo  SKOKCase::wysw_biura_podrozy_add_case('skok10_biuro_podrozy',0,0,0,'onChange="getWariantUmowySkokKod(this.value,document.getElementById(\'skok10_typ_umowy\').value,\'skok10_wariant_ubezpieczenia\',\'skok10_opcje_ubezpieczenia\');"');
					 ?>
						</td>
					</tr>
				<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td>
					<?php
					//echo  skokCase::wysw_wariant_umowy('skok10_wariant_ubezpieczenia',0,0,0,' class="required" ');
					echo	SKOKCase::wysw_wariant_umowy_kod('skok10_wariant_ubezpieczenia',0,0,0,'onChange="getWariantUmowySkokKodOpcje(this.value,\'skok10_opcje_ubezpieczenia\');skok_getSumaUbezp(this.value,\'skok10_suma_ubezpieczenia\',\'skok10_sumacurrency_id\')"');

					 ?>
						</td>
					</tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td>
					<?php
					//echo  skokCase::wysw_opcje_umowy('skok10_opcje_ubezpieczenia',0,0,0,' class="required" ');
					echo SKOKCase::wysw_opcje_umowy_kod('skok10_opcje_ubezpieczenia',0,0,0);
					 ?>
						</td>
					</tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Zakres ubezpieczenia:</b></td><td>
					<?php
					//echo  skokCase::wysw_opcje_umowy('skok10_opcje_ubezpieczenia',0,0,0,' class="required" ');
					echo SKOKCase::wysw_rodzaj_szkody(0,'skok10_rodzaj_szkody',0,0,0);;
					 ?>
						</td>
					</tr>

<!--					<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Sprawa zaleďż˝na:</b></td><td>
					<input type="text" id="sprawa_zal"  name="sprawa_zal" value=""  size="30" maxlength="30">
						</td>
					</tr>
-->



			 <td width="5%">&nbsp;</td>
                <td  align="right"><b>&nbsp; </b></td>
                <td ><div align="right"  style="margin-right:180px;"> <small><b>Rezerwa:</b></samll>&nbsp;&nbsp;
				<input type="text" name="skok10_rezerwa_skok" id="skok10_rezerwa_skok" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency_pln('skok10_rezerwacurrency_id','PLN',0,'class="required"'); ?>

			<br>&nbsp;&nbsp;<small><b>Suma ubezpieczenia:</b></samll>&nbsp;&nbsp;
				<input type="text" name="skok10_suma_ubezpieczenia" id="skok10_suma_ubezpieczenia" value=""  readonly style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency('skok10_sumacurrency_id','PLN',0,'class="required" onChange="alert();" '); ?>
							</div>

                </td>
            </tr>
          </table>
        </span>

        <span id="policy_agent_europa">
        <table cellpadding="5" cellspacing="0" border="0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Typ umowy:</b>	</td><td>
					<?php
					echo EuropaCase::wysw_typy_umowy(11,'europa11_typ_umowy',0,0,' class="required" ');

					?>
						</td></tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Biuro podrďż˝y:</b></td><td>
					<?php
			//echo   EuropaCase::wysw_biura_podrozy('europa11_biuro_podrozy',0,0,0,' class="required" onChange="getWariantUmowyEuropa(this.value,document.getElementById(\'typ_umowy\').value,\'europa11_wariant_ubezpieczenia\');"');
			echo  EuropaCase::wysw_biura_podrozy('europa11_biuro_podrozy',0,0,0,'onChange="getWariantUmowyEuropaKod(this.value,document.getElementById(\'europa11_typ_umowy\').value,\'europa11_wariant_ubezpieczenia\',\'europa11_opcje_ubezpieczenia\');"');
					 ?>
						</td>
					</tr>
	<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td>
					<?php
					//echo  EuropaCase::wysw_wariant_umowy('europa11_wariant_ubezpieczenia',0,0,0,' class="required" ');
					echo	EuropaCase::wysw_wariant_umowy_kod('europa11_wariant_ubezpieczenia',0,0,0,'onChange="getWariantUmowyEuropaKodOpcje(this.value,\'europa11_opcje_ubezpieczenia\');europa_getSumaUbezp(this.value,\'europa11_suma_ubezpieczenia\',\'europa11_sumacurrency_id\')"');


					 ?>
						</td>
					</tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td>
					<?php
					//echo  EuropaCase::wysw_opcje_umowy('europa11_opcje_ubezpieczenia',0,0,0,' class="required" ');
					echo EuropaCase::wysw_opcje_umowy_kod('europa11_opcje_ubezpieczenia',0,0,0);
					 ?>
						</td>
					</tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Zakres ubezpieczenia:</b></td><td>
					<?php
					//echo  EuropaCase::wysw_opcje_umowy('europa11_opcje_ubezpieczenia',0,0,0,' class="required" ');
					echo EuropaCase::wysw_rodzaj_szkody(0,'europa11_rodzaj_szkody',0,0,0);;
					 ?>
						</td>
					</tr>

<!--					<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Sprawa zaleďż˝na:</b></td><td>
					<input type="text" id="sprawa_zal"  name="sprawa_zal" value=""  size="30" maxlength="30">
						</td>
					</tr>
-->



			 <td width="5%">&nbsp;</td>
                <td  align="right"><b>&nbsp; </b></td>
                <td ><div align="right"  style="margin-right:180px;"> <small><b>Rezerwa:</b></samll>&nbsp;&nbsp;
				<input type="text" name="europa11_rezerwa_europa" id="europa11_rezerwa_europa" value=""  style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency_pln('europa11_rezerwacurrency_id','PLN',0,'class="required"'); ?>

			<br>&nbsp;&nbsp;<small><b>Suma ubezpieczenia:</b></samll>&nbsp;&nbsp;
				<input type="text" name="europa11_suma_ubezpieczenia" id="europa11_suma_ubezpieczenia" value=""  readonly style="text-align: right;" size="15" maxlength="20" class="required">
				<?php  echo wysw_currency('europa11_sumacurrency_id','PLN',0,'class="required" onChange="alert();" '); ?>
							</div>

                </td>
            </tr>
          </table>
        </span>
        <span id="policy_agent_europa2">
        <table cellpadding="5" cellspacing="0" border=0"  width="680" >

				<tr>	<td width="5%">&nbsp;</td>
					<td width="120" align="right"><b>Typ umowy:</b>	</td><td>
					<?php
					echo EuropaCase::wysw_typy_umowy(2201,'europa2_typ_umowy',0,0,' class="required" ');

					?>
						</td></tr>
	<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Wariant umowy:</b></td><td>
					<?php echo
						EuropaCase::wysw_wariant_umowy('europa2_wariant_ubezpieczenia',0,0,0,' class="required"  onChange="europa_getSumaUbezp(this.value,\'europa2_suma_ubezpieczenia\',\'europa2_suma_ub_currency_id\');"');
					 ?>
						</td>
					</tr>
			<tr >
					<td width="5%">&nbsp;</td>
					<td  align="right">
					<b>Opcje:</b></td><td>
					<?php echo
						EuropaCase::wysw_opcje_umowy('europa2_opcje_ubezpieczenia',0,0,0,' class="required" ');
					 ?>
						</td>
					</tr>
			 <tr>
			 <td width="5%">&nbsp;</td>
                <td  align="right"><b>&nbsp; </b></td>
                <td ><div align="right"  style="margin-right:180px;"> <small><b>Rezerwa:</b></samll>&nbsp;&nbsp;
				<input type="text" name="europa2_rezerwa" id="europa2_rezerwa" value=""  style="text-align: right;" size="15" maxlength="20" class="required" >
				<?php  echo wysw_currency_pln('europa2_rezerwa_currency_id','PLN',0,'class="required"'); ?>
<?php
	$suma_ubezpieczenia_tmp = EuropaCase::getSumaUbezpieczenia();
	if (is_array($suma_ubezpieczenia_tmp)){
			$suma_ubezpieczenia = $suma_ubezpieczenia_tmp['kwota'];
			$waluta_ubezpieczenia = $suma_ubezpieczenia_tmp['waluta'];
	}else{
		$suma_ubezpieczenia= '';
		$waluta_ubezpieczenia = 'PLN';
	}

?>
			<br>&nbsp;&nbsp;&nbsp;<small><b>Suma ubezpieczenia:</b></samll>&nbsp;&nbsp;
				<input type="text" name="europa2_suma_ubezpieczenia" id="europa2_suma_ubezpieczenia" readonly  style="text-align: right;" size="15" maxlength="20" class="required" value="<?php echo $suma_ubezpieczenia;  ?>">
				<?php  echo wysw_currency('europa2_suma_ub_currency_id',$waluta_ubezpieczenia,0,'class="required"'); ?>
							</div>

                </td>
            </tr>
          </table>
        </span>

          <div id="policy_agent_allianz" ><span style="margin-left:250px">Trwa ďż˝adowanie... </span></div>


        </td>
        </tr>
		<tr>
		<td colspan="2">
        	   <br><center><input type="submit" value="<?= AS_CASADD_ZAPSZK ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASADD_ZAPSZKMED ?>"></center><br>
		</td>
	    </tr>
        </table>
        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                <td width="100" align="center"><img src="img/2B.gif"></td>
		<td><a href="#menu" tabindex="-1"><img src="img/KwadN.gif" border="0"></a>&nbsp;<b><a name="szkoda"><font color="#6699cc"><?= AS_CASADD_SZK ?></font></a></b></td>
            </tr>
            <tr>
                <td width="100" align="right"><b><small><u><?= AS_CASADD_COSIESTALO ?></u></small></b></td>
                <td>
                    &nbsp;<input type="text" name="event" onchange="javascript:this.value=this.value.toUpperCase();" size="50" maxlength="50">
                </td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><b><small><?= AS_CASADD_DATZDARZ ?></small></b><br><small>(dd mm yyyy)</small></td>
                <td>
                    &nbsp;<input type="text" name="eventDate_d" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="eventDate_m" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="eventDate_y" size="4" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('eventDate')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>&nbsp;&nbsp;<b><small><?= AS_CASADD_DATZGLOSZ ?></small></b>&nbsp;&nbsp;<input type="text" name="notificationDate_d" size="1" value="<?php echo date("d") ?>" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="notificationDate_m" size="1" value="<?php echo date("m") ?>" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="notificationDate_y" size="4" value="<?php echo date("Y") ?>" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal('notificationDate')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small> <input type="text" name="notificationTime" size="8" value="<?php echo date("H:i:s") ?>" maxlength="8" style="text-align: center" > <small>(HH:mm:ss)</small>
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_OKOLO ?></small></td>
                <td>&nbsp;<textarea name="circumstances" cols="60" rows="5" style="font-family: Verdana; font-size: 8pt;"></textarea></td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= CITY ?></small></td>
                <td>&nbsp;<input type="text" name="city" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30">
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASD_ADRES_POBYTU ?></small></td>
                <td>&nbsp;<input type="text" name="paxplaceofstay" size="100" maxlength="100"></td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_KTOZGL ?></small></td>
                <td>&nbsp;<input type="text" name="informer" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30"></td>
            </tr>
        </table>

        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                <td width="100" align="center"><img src="img/3B.gif"></td>
		<td><a href="#menu" tabindex="-1"><img src="img/KwadN.gif" border="0"></a>&nbsp;<b><a name="polisa"><font color="#6699cc"><?= AS_CASADD_POL ?></font></a></b></td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASES_NRPOL ?></small></td>
                <td>&nbsp;Seria: <input type="text" name="policy_series" id="policy_series" onchange="javascript:this.value=this.value.toUpperCase();" size="20" maxlength="20">

   &nbsp;Nr: <input type="text" name="policy" id="policy" onchange="javascript:this.value=this.value.toUpperCase();" size="30" maxlength="30">
                <span id="policy_agent">&nbsp;&nbsp;<small><b>Agent:</b></small>&nbsp;&nbsp;<?php

		echo  wysw_biuro_podrozy('biurop_id',0,0);
		?></span>
                </td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><small><?= AS_TITLE_SUMA_DATA_WYJAZDU ?></small><br><small>(dd mm yyyy)</small></td>
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
                <td width="100" align="right"><small><?= AS_CASADD_NRSPRKL ?></small></td>
                <td>&nbsp;<input type="text" name="contrahent_ref" size="25" maxlength="50"></td>
            </tr>
        </table>

        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                <td width="100" align="center"><img src="img/4B.gif"></td>
		<td><a href="#menu" tabindex="-1"><img src="img/KwadN.gif" border="0"></a>&nbsp;<b><a name="szczegoly"><font color="#6699cc"><?= AS_CASADD_POSZKSZCZ ?></font></a></b></td>
            </tr>
            <tr valign="middle">
                <td width="100" align="right"><b><small><?= AS_CASADD_DATUR ?></small></b><br><small>(dd mm yyyy)</small></td>
                <td>
                    &nbsp;<input type="text" name="paxDob_d" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="paxDob_m" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;<input type="text" name="paxDob_y" size="4" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);"><a href="javascript:void(0)" onclick="newWindowCal('paxDob')" tabindex="-1" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a>
                </td>
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
                    &nbsp;<input type="text" name="paxPost_1" size="1" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;<input type="text" name="paxPost_2" size="2" maxlength="3" style="text-align: center" onkeydown="remove_formant(this,event);">&nbsp;&nbsp;<small>Miejscowoďż˝ďż˝</small>&nbsp;&nbsp;<input type="text" name="paxCity" onchange="javascript:this.value=this.value.toUpperCase();" size="25" maxlength="25">
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= COUNTRY ?></small></td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <?php if('' == $defaultCountrySymbol)$defaultCountrySymbol='PL'; ?>
                                <input type="text" name="paxCountry" id="paxCountry" size="3" maxlength="2" onblur="document.forms['form_reg'].elements['paxCountryList'].value = document.forms['form_reg'].elements['paxCountry'].value.toUpperCase(); document.forms['form_reg'].elements['paxCountry'].value = document.forms['form_reg'].elements['paxCountry'].value.toUpperCase()" style="text-align: center"  value="<?php echo $defaultCountrySymbol; ?>">
                            </td>
                            <td>
                                <?php echo Application :: countryList($defaultCountrySymbol, $lang, 'paxCountryList', 'style="font-size: 8pt;" "onChange="document.forms[\'form_reg\'].elements[\'paxCountry\'].value = document.forms[\'form_reg\'].elements[\'paxCountryList\'].value"')?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_TEL ?></small><br><small>(xx yyyyyyy)</small></td>
                <td>
                    &nbsp;<input type="text" name="paxPhone" size="7" maxlength="30" style="text-align: center" onkeydown="remove_formant(this,event);">
                </td>
            </tr>
            <tr>
                <td width="100" align="right"><small><?= AS_CASADD_TELKOM ?></small><br><small>(yyyyyyyyy)</small></td>
                <td>
                    &nbsp;<input type="text" name="paxMobile" size="10" maxlength="30" style="text-align: center">
                </td>
            </tr>
        </table>
        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9">
            <tr bgcolor="#eeeeee">
                <td width="100" align="center"><img src="img/5B.gif"></td>
		<td><a href="#menu" tabindex="-1"><img src="img/KwadN.gif" border="0"></a>&nbsp;<b><a name="kontakty"><font color="#6699cc"><?= AS_CASADD_DANKONT ?></font></a></b></td>
            </tr>
            <tr valign="middle">
                <td width="100"></td>
                <td>
                    <table cellpadding="1" cellspacing="1">
                        <tr>
                            <td></td>
                            <td><small><?= AS_CASADD_TYP ?></small></td>
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
                                <input type="text" name="contactDesc2" size="30" onchange="javascript:this.value=this.value.toUpperCase();" maxlength="80">
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
                                <input type="text" name="contactDesc3" size="30" onchange="javascript:this.value=this.value.toUpperCase();" maxlength="80">
                            </td>
                        </tr>
                    </table>
                    ďż˝<small><?= AS_CASADD_TYPY ?>: <img src="img/Tele.gif"> - <?= AS_CASADD_TEL ?> <img src="img/Fax.gif"> - <?= FAX ?> <img src="img/Email.gif"> - <?= EMAIL ?></small>
                </td>
            </tr>
        </table>
        <br>
        <center><input type="submit" value="<?= AS_CASADD_ZAPSZK ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASADD_ZAPSZKMED ?>"></center>
    </form>
        <iframe name="contrahent_search_frame" width="0" height="0" src=""></iframe>
        <br>
        <script>
        change_form();
        </script>
    </body>
</html>
<?php mysql_free_result($result); ?>
