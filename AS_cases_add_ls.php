<?php include('include/include.php'); 
require_once('access.php'); 
include('include/include_mod.php'); 
include('lib/lib_case.php');
include_once('lib/lib_allianz.php');


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <title><?= AS_CASADD_TITLE ?></title>
        <link href="Styles/general.css" rel="stylesheet" type="text/css">
    <body bgcolor="#dfdfdf">
    <script language="javascript" src="Scripts/mootools-core.js"></script>
    <script language="JavaScript1.2" src="Scripts/js_allianz_announce.js"></script>
	<script language="JavaScript" src="Scripts/javascript.js"></script>	
	<script language="JavaScript" src="CalendarPopup.js"></script>
	<script language="JavaScript">
	
	
	
	function change_form(){
		
				
		
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
				return true;					
                if ($('nr_protokolu').value == "") {
                    alert("Brak nr. protokołu");
                    $('nr_protokolu').focus();
                    return false;
                }

                if ($('kolo_id').value == 0) {
                    alert("Brak koła łowieckiego");
                    $('kolo_id').focus();
                    return false;
                }

                if ($('woj_id').value == 0) {
                    alert("Lokalizacja szkody: Brak wojewodztwa");
                    		$('woj_id').focus();
                    return false;
                }
               
                if ($('pow_id').value == 0) {
                    alert("Lokalizacja szkody: Brak powiatu");
                    		$('pow_id').focus();
                    return false;
                }
               
                if ($('gmina_id').value == 0) {
                    alert("Lokalizacja szkody: Brak gminy");
                    		$('woj_id').focus();
                    return false;
                }
               
                if ($('lok_miejscowosc').value == "") {
                    alert("Lokalizacja szkody: Brak nazwy miasta/wsi");
                    		$('lok_miejscowosc').focus();
                    return false;
                }
               
                if ($('nr_dzialki').value == "") {
                    alert("Lokalizacja szkody: Brak numeru działki");
                    		$('nr_dzialki').focus();
                    return false;
                }
               
                if ($('obwod_lowiecki').value == "") {
                    alert("Lokalizacja szkody: Brak numeru obwodu łowieckiego");
                    		$('obwod_lowiecki').focus();
                    return false;
                }
               
                if ($('poszk_nazwisko').value == "") {
                    alert("Poszkodowany: Brak nazwiska");
                    		$('poszk_nazwisko').focus();
                    return false;
                }
               
                if ($('poszk_imie').value == "") {
                    alert("Poszkodowany: Brak imienia");
                    		$('poszk_imie').focus();
                    return false;
                }
               
                if ($('poszk_adres').value == "") {
                    alert("Poszkodowany: Brak adresu");
                    		$('poszk_adres').focus();
                    return false;
                }
               
                if ($('poszk_kod').value == "") {
                    alert("Poszkodowany: Brak kodu pocztowego");
                    		$('poszk_kod').focus();
                    return false;
                }
               
                if ($('poszk_miejscowosc').value == "") {
                    alert("Poszkodowany: Brak miejscowości");
                    		$('poszk_miejscowosc').focus();
                    return false;
                }
               
                if ($('data_zgloszenia_do_kola').value == "") {
                    alert("Szkoda: Brak daty zgłoszenia szkody do KŁ");
                    		$('data_zgloszenia_do_kola').focus();
                    return false;
                }
               
                if ($('szacujacy_id').value == 0) {
                    alert("Szkoda: Brak szacującego szkodę");
                    		$('szacujacy_id').focus();
                    return false;
                }
               
                if ($('rodzaj_stan_upraw').value == 0) {
                    alert("Szkoda: Brak informacji o rodzaju i stanie upraw");
                    		$('rodzaj_stan_upraw').focus();
                    return false;
                }
               
                if ($('rodzaj_stan_upraw').value == 0) {
                    alert("Szkoda: Brak informacji o rodzaju i stanie upraw");
                    		$('rodzaj_stan_upraw').focus();
                    return false;
                }
               
                if ($('rws').value == 0) {
                    alert("Szkoda: Brak kwoty RWS");
                    		$('rws').focus();
                    return false;
                }
               
                if ($('kwota_roszczenia').value == 0) {
                    alert("Szkoda: Brak kwoty roszczenia");
                    		$('kwota_roszczenia').focus();
                    return false;
                }
               
				
    		return true;
                
              
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

if (isset($_GET['action']) )   {
	
    $case_type = 19; // SPRAWA likwidacyjna
    $genre_id = 1; // Allianz

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

			$eventdate=getValue('eventDate_y').'-'.getValue('eventDate_m').'-'.getValue('eventDate_d');
			$notificationdate=getValue('notificationDate_y').'-'.getValue('notificationDate_m').'-'.getValue('notificationDate_d');
			
            $query = "UPDATE coris_assistance_cases SET 
            client_id = '$_POST[contrahent_id]', type_id = $case_type,genre_id='$genre_id', client_ref = '".getValue('nr_sprawy_allianz')."', 
            date = NOW(), 
            user_id = '$_SESSION[user_id]', 
            paxname = '".getValue('poszk_imie')."', paxsurname = '".getValue('poszk_nazwisko')."',
            pax_email = '".getValue('poszk_email')."',
            paxsex = '', paxdob = '$_POST[paxDob_y]-$_POST[paxDob_m]-$_POST[paxDob_d]', policy_series='".getValue('policy_series')."',policy = '".getValue('kolo_nr_polisy')."', event = '$_POST[event]', eventdate = '".$eventdate."', country_id = '$_POST[country]', city = '".getValue('lok_miejscowosc')."',
			claim_handler_user_id='".$_SESSION[user_id]."', claim_handler_date=now(),liquidation=1            			            
            $zmiana_numeru              
            WHERE case_id = $row[case_id]";

            if ($result = mysql_query($query)) {
            	
            	
            	CaseInfo::updateFullNumber($c_id);
            	
            	$paxPhone = getValue('poszk_tel'); 
                $query = "INSERT INTO coris_assistance_cases_details (case_id, notificationdate, informer, validityfrom, validityto, policypurchasedate, policypurchaselocation, policyamount, policycurrency_id, circumstances, paxaddress, paxpost, paxcity, paxcountry, paxphone, paxmobile) VALUES ('$row[case_id]', '$notificationdate', '$_POST[informer]', '$_POST[validity_from_y]-$_POST[validity_from_m]-$_POST[validity_from_d]', '$_POST[validity_to_y]-$_POST[validity_to_m]-$_POST[validity_to_d]', '$_POST[policyPurchaseDate_y]-$_POST[policyPurchaseDate_m]-$_POST[policyPurchaseDate_d]', '$_POST[policyPurchaseLocation]', '$_POST[policyAmount]', '$_POST[policyCurrency]', '$_POST[circumstances]', '".getValue('poszk_adres')."', '".getValue('poszk_kod')."', '".getValue('poszk_miejscowosc')."', '$_POST[paxCountry]', '$paxPhone', '$_POST[paxMobile]')";

                if ($result = mysql_query($query)) {
                    mysql_query("COMMIT");
                    
                    $data_zgloszenia_do_kola = getValue('data_zgloszenia_do_kola_y').'-'.getValue('data_zgloszenia_do_kola_m').'-'.getValue('data_zgloszenia_do_kola_d');
                    $kolo_id = getValue('kolo_id');
                    if ($kolo_id == 'new' ){
                    	$kolo_id = AllianzCase::dodajKolo(getValue('kolo_nazwa'), getValue('kolo_adres'), getValue('kolo_kod'), getValue('kolo_miejscowosc'), getValue('kolo_zo'), getValue('kolo_konto'), getValue('kolo_suma_ubezpieczenia'), 0, 0);                    	
                    }
                    	$query = "INSERT coris_allianz_announce SET 
                    		case_id='$c_id',
                    		nr_protokolu = '".getValue('nr_protokolu')."',
                    		ID_kolo = '".$kolo_id."',
                    		kolo_nazwa = '".getValue('kolo_nazwa')."',
                    		kolo_adres = '".getValue('kolo_adres')."',
                    		kolo_kod = '".getValue('kolo_kod')."',
                    		kolo_miejscowosc = '".getValue('kolo_miejscowosc')."',
                    		kolo_zo = '".getValue('kolo_zo')."',
                    		kolo_konto = '".getValue('kolo_konto')."',
                    		
                    		
                    		szko_woj_id = '".getValue('woj_id')."',
                    		szk_pow_id = '".getValue('pow_id')."',
                    		szk_gmina_id = '".getValue('gmina_id')."',
							szk_lok_miejscowosc = '".getValue('lok_miejscowosc')."',
							szk_nr_dzialki = '".getValue('nr_dzialki')."',
							szk_obwod_lowiecki = '".getValue('obwod_lowiecki')."',
							powierzchnia_dzialki = '".getValue('powierzchnia_dzialki')."',
							
							data_zgloszenia_do_kola =  '$data_zgloszenia_do_kola',
							
							gatunek_zwierze_inne = '".getValue('gatunek_zwierzyny_inne')."',
							rodzaj_stan_upraw = '".getValue('rodzaj_stan_upraw')."',
							rws = '".str_replace(',','.',getValue('rws'))."',
							kwota_roszczenia = '".str_replace(',','.',getValue('kwota_roszczenia'))."'";
                    	                    	
                    		$mr = mysql_query($query);
										
							if (!$mr ){																						
										echo  "<br>Update Error: $query <br><br> ".mysql_error();				
							}		
								 
					$szacujacy_id = getValue('szacujacy_id');
					
					if (is_array($szacujacy_id)){
						foreach ($szacujacy_id  As $pozycja ){
							if ($pozycja == 'new'){
								$sz_id = AllianzCase::dodajSzacujacego($kolo_id, getValue('szacujacy_nazwa'), getValue('szacujacy_tel') );                    			
							}else{
								$sz_id = $pozycja;								
							}								
							if ($sz_id > 0 ){
									$szacujacy_dane = AllianzCase::infoSzacujacy($sz_id);									
									$qi = "INSERT INTO coris_allianz_announce_szacujacy SET
										 case_id='$c_id',ID_szacujacy='$sz_id',
										 imie_nazwisko= '".mysql_escape_string($szacujacy_dane['nazwa'])."',
											telefon = '".mysql_escape_string($szacujacy_dane['tel'])."'";																
			            			$mr = mysql_query($qi);											
									if (!$mr ){																						
											echo  "<br>INSRT Error: $qi <br><br> ".mysql_error();				
									 }	
								}								
						}								
                	}
                	
                	
						$gatunek_zwierzyny = $_POST['gatunek_zwierzyny'];

							if (is_array($gatunek_zwierzyny)){
								foreach ($gatunek_zwierzyny As $gatunek){
									$query = "INSERT coris_allianz_announce_gatunek 
								SET  case_id='$c_id',ID_gatunek='$gatunek'";
		            			$mr = mysql_query($query);
										
								if (!$mr ){																						
										echo  "<br>Update Error: $query <br><br> ".mysql_error();				
								 }		
								}		
									
							}

							
					$rws = str_replace(',','.',getValue('rws'));
					$kwota_roszczenia = str_replace(',','.',getValue('kwota_roszczenia'));

					$cl = new AllianzClaim(0,$c_id,1);
					$cl->setAnnounce_date($notificationdate);
					
					$cls = new AllianzClaimDetails(0, 0,1);
					$cls->setKwota_roszczenia($kwota_roszczenia);
					$cls->setKwota_rezerwa($kwota_roszczenia);
					$cls->setKwota_rws($rws);
					
					$cls->setRefundacja(1);
					$cls->setRefundacja_kwota(40);					
					$cls->setFranszyza(1);
					$cl->addClaimDetails($cls);					
					$cl->store();
                   
					$_SESSION['coris_case_submit'] = "$_POST[paxName]#$_POST[paxSurname]"; // zabezpieczenie przed postbackiem

                    // KONTAKTY
                   
                    					
											 
					echo "<font size=\"+1\">".AS_CASADD_PROSZEZAPISACNRSPR.": </font><font size=\"+1\" color=\"red\"><B>$row[number]/$row[year]/$case_type/$_POST[contrahent_id]</B></font>.<br>".AS_CASADD_ABYOTWSPRA.": <input type=\"button\" value=\"ďż˝\" onclick=\"open_case(".$row['case_id'].",'casewindow".$row['case_id']."');\" style=\"font-family: Webdings; height: 18pt;\"><br>
					<br>
					".AS_CASADD_MSG_JCHWPINOWSZK."<br>";
						  
					
					 

                } else {
                	$err = mysql_error();
                    mysql_query("ROLLBACK");
                    die($err .'<br>'.$query);
                }
            } else {
            	$err = mysql_error();
                mysql_query("ROLLBACK");
                die($err.'<br>'.$query);
            }
        } else {
        	$err = mysql_error();
            mysql_query("ROLLBACK");
            die($err.'<br>'.$query);
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
                    <input type="button" value="<?= AS_CASADD_SPRTECH ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: orange; width: 150px" onclick="document.location='AS_cases_add_tech.php'">
                    <input type="button" value="<?= AS_CASADD_SPRLS ?>" style="background: #eeeeee; border-left: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid; border-top: #000000 1px solid; color: #66cc66; width: 150px" disabled>
                </td>
            </tr>
        </table>
        <br>
        <center><a name="menu"><font color="#66cc66"><a href="#poszkodowany"><font color="#66cc66"><?= AS_CASADD_POSZK ?></font></a> | <a href="#szkoda"><font color="#66cc66"><?= AS_CASADD_SZK ?></font></a> | <a href="#polisa"><font color="#66cc66"><?= AS_CASADD_POL?></font></a> | <a href="#szczegoly"><font color="#66cc66"><?= AS_CASADD_SZCZ ?></font></a> | <a href="#kontakty"><font color="#66cc66"><?= AS_CASADD_KONT ?></font></a></font></a></center>
        <table><tr height="3"><td></td></tr></table>
        
            <div align="left"><font color="#66cc66"><?= AS_CASADD_SPRLS ?></font></div>
        <hr noshade size="1" color="#66cc66">
        <form action="AS_cases_add_ls.php?action=1" method="post" name="form_reg" id="form_reg" onsubmit="return validate();">
        <table cellspacing=1 cellpadding=2 width="100%" bgcolor="#d9d9d9" border="0">
            <tr bgcolor="#eeeeee">
                <td width="140" align="center"><img src="img/1B.gif"></td>
		<td width="100%"><a href="#menu" tabindex="-1"><img src="img/KwadN.gif" border="0"></a>&nbsp;<a name="poszkodowany"><b><font color="#66cc66"><?= AS_CASADD_DANWYM ?></font></b></a></td>
            </tr>                   
            <tr>
                <td  align="right"><b><small><?= AS_CASADD_TOW ?></small></b></td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <input type="text" id="contrahent_id" name="contrahent_id" value="9" size="5" onblur="contrahent_search_frame.location='GEN_contrahents_select_iframe.php?contrahent_id=' + this.value;change_form();"  style="text-align: center;" class="required"  readonly>
                                <input type="text" id="contrahent_name" name="contrahent_name" size="60" disabled> <input type="button" value="L" style="background: #cccccc; color: #66cc66; font-family: webdings; font-size: 12pt; height: 15pt; line-height: 8pt; width: 18pt;" onclick="window.open('GEN_contrahents_select_frameset.php','contrahentsearch','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=420,left='+ (screen.availWidth - 550) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 420) / 2);" title="<?= AS_CASADD_WYSZKLI ?>">
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
                                <input type="text" name="country" id="country" size="3" maxlength="2" class="required" style="text-align: center" onblur="aktualizuj_kraj(this.value);" value="PL" readonly>
                            </td>
                            <td>
                                <select tabindex=-1 name="countryList" id="countryList" onClick="return false;" onChange="document.forms['form_reg'].elements['country'].value = document.forms['form_reg'].elements['countryList'].value" style="font-size: 8pt;">
                                    
<?php
$result = mysql_query("SELECT country_id, name, prefix FROM coris_countries WHERE country_id='PL' ORDER BY name");
while ($row = mysql_fetch_array($result)) {
?>
                                    <option value="<?php echo $row['country_id'] ?>"><?php echo ($row['prefix'] != "") ? $row['name'] . " (+" . $row['prefix'] .")" : $row['name'] ?></option>
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
		<td colspan="2">
      	

               
          <div id="policy_agent_allianz" name="policy_agent_allianz" ><span style="margin-left:250px">Trwa ładowanie... </span></div>
          
        
        </td>
        </tr>
		<tr>          
		<td colspan="2">
        	   <br><center><input type="submit" value="<?= AS_CASADD_ZAPSZK ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= AS_CASADD_ZAPSZKMED ?>"></center><br>
		</td>
	    </tr>	
        </table>
      
        <br>
      
    </form>
        <iframe name="contrahent_search_frame" id="contrahent_search_frame" width="0" height="0" src=""></iframe>
        <br>
<script>
  window.addEvent('domready', function(){
	  			aktualizuj_kraj('PL');
				  $('contrahent_search_frame').src='GEN_contrahents_select_iframe.php?contrahent_id=' + $('contrahent_id').value;;
				  load_allianz_form('policy_agent_allianz');
				  
	       });	
						
        //-->
        </script>        
    </body>
</html>