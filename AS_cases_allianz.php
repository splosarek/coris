<?php 
include('include/include.php'); 
include_once('lib/lib_allianz.php'); 
require_once('access.php'); 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>	
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

	<script language="JavaScript1.2">

	
	<!--

        var blnDOM = false, blnIE4 = false, blnNN4 = false; 

        if (document.layers) blnNN4 = true;
        else if (document.all) blnIE4 = true;
        else if (document.getElementById) blnDOM = true;

        function getKeycode(e)
        {
          if (blnNN4)
          {
            var NN4key = e.which
            if (NN4key == 13)
                ClearSubmitSearch();
          }
          if (blnDOM)
          {
            var blnkey = e.which
            if (blnkey == 13) {
                //re = /(\d+)/;
                //arr = re.exec(top.group.cols);
                //alert(arr[1]);
                ClearSubmitSearch();
            }
          }

          if (blnIE4)
          {
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
			document.getElementById('letter').value=l;
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
            var url = "AS_cases_frame_allianz.php?action=1&paxSurname="+ document.getElementById('paxSurname').value + "&caseId="+ document.getElementById('caseId').value +
            	"&year="+ document.getElementById('year').value +"&paxName="+ document.getElementById('paxName').value +"&policy="+ document.getElementById('policy').value +
            	
            	"&dateFrom_d="+ document.getElementById('dateFrom_d').value +
            	"&dateFrom_m="+ document.getElementById('dateFrom_m').value +"&dateFrom_y="+ document.getElementById('dateFrom_y').value +"&dateTo_d="+ document.getElementById('dateTo_d').value +
            	"&dateTo_m="+ document.getElementById('dateTo_m').value  +"&dateTo_y="+ document.getElementById('dateTo_y').value  +"&eventDateFrom_d="+ document.getElementById('eventDateFrom_d').value +
            	"&eventDateFrom_m="+ document.getElementById('eventDateFrom_m').value +"&eventDateFrom_y="+ document.getElementById('eventDateFrom_y').value +"&eventDateTo_d="+ document.getElementById('eventDateTo_d').value +
            	"&eventDateTo_m="+ document.getElementById('eventDateTo_m').value +"&eventDateTo_y="+ document.getElementById('eventDateTo_y').value +"&userId="+ document.getElementById('userId').value +
            	"&step="+ document.getElementById('step').value +"&letter="+ document.getElementById('letter').value +
            	"&sort="+ document.getElementById('sort').value  +'&client_id='+document.getElementById('client_id').value+'&city='+document.getElementById('city').value+ '&userRole='+document.getElementById('userRole').value+'&dok_cat='+document.getElementById('dok_cat').value
            	+ '&roszczenia_do_akceptacji='+$('roszczenia_do_akceptacji').checked+ '&roszczenia_do_poprawy='+$('roszczenia_do_poprawy').checked
            	+ '&decyzje_do_uzupelnienia='+$('decyzje_do_uzupelnienia').checked+ '&decyzje_do_drukowania='+$('decyzje_do_drukowania').checked
            	+ '&platnosci_do_wyslania='+$('platnosci_do_wyslania').checked+ '&platnosci_wyslane='+$('platnosci_wyslane').checked+ '&platnosci_oplacone_allianz='+$('platnosci_oplacone_allianz').checked
            	+ '&platnosci_do_wyplaty='+$('platnosci_do_wyplaty').checked + '&platnosci_wyplacone='+$('platnosci_wyplacone').checked+'&kolo_id='+$('kolo_id').value+'&decyzja_rodzaj='+$('decyzja_rodzaj').value
            	;
            
            assistcases_frame.location = url;
        }
			// TODO: Poprawić - aby nie było "for"
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

        function move_formant(s,e) {
            var form1 = document.getElementById('form1');
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
			var form1 = document.getElementById('form1');
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
	<body bgcolor="#dfdfdf" onload="document.getElementById('caseId').focus();ClearSubmitSearch();">
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
			function ResetCheckbox(nazwa){
					var lista = Array('roszczenia_do_poprawy','roszczenia_do_akceptacji','decyzje_do_uzupelnienia','decyzje_do_drukowania','platnosci_do_wyslania','platnosci_wyslane','platnosci_oplacone_allianz','platnosci_do_wyplaty','platnosci_wyplacone');
								    
				 	for (var i = 0; i < lista.length; i++){
					 	if (lista[i] != nazwa ){
						 	$(lista[i]).checked=false;
						}
				 	}		
			}
		</script>	
		<center>
		<form name="form1" id="form1">
            <table cellpadding="2" cellspacing="0" border="0" width="850">
                <tr valign="middle">
                    <td colspan="2" nowrap >
                      <a href="AS_allianz_kola.php"><b>Koło łowieckie :</b></a>
                      <?php 					 					 					
						echo AllianzCase::getKolaLowieckie('kolo_id',0,0,'onchange="ClearSubmitSearch();"',0);
						?>
                    </td>
                    <td width="70" align="right">
                    <select name="userRole" id="userRole"  onChange="ClearSubmitSearch()" style="font-size: 9px">
                    	<option value="1"><?= AS_CASES_RED ?></option>
                    	<option value="2" selected>Likwidator</option>
                    </select>
                    </td>
                    <td>
					<div align="left">
<?php
$query = "SELECT user_id, surname, name FROM coris_users WHERE name NOT LIKE '' AND active = 1 AND (department_id = 7 OR department_id = 4) ORDER BY surname";

if ($result = mysql_query($query)) {
    echo "<select name=\"userId\" id=\"userId\" onchange=\"ClearSubmitSearch()\" tabindex=\"11\" style=\"font-size: 9px;\">";
    echo "<option></option>";
    $def=0;
     if (!AllianzCase::isAdmin()){
     		$def=$_SESSION['user_id'];
     }
    while ($row = mysql_fetch_array($result)){
        //echo ($selected == $row[0]) ? "<option value=\"$row[0]\" selected>$row[1], $row[2]</option>" : "<option value=\"$row[0]\">$row[1], $row[2]</option>";
        echo "<option value=\"$row[0]\"  ".($row[0]==$def ? 'selected' : '' ).">$row[1], $row[2]</option>";
    }
        echo "</select>";
        mysql_free_result($result);
} else {
    die(mysql_error());
}
?>
                    </div></td>
                </tr>
</table>
<table>
                <tr>
                    <td width="70" align="right" bgcolor="#dfdfdf"><b><small><?= AS_CASES_NRSZKOD ?></small></b></td>
                    <td bgcolor="#dfdfdf">                      <div align="left">
                          <input tabindex="1" type="text" name="caseId" id="caseId" style="text-align: right" size="10">
                          <input tabindex="2" type="text" name="year" id="year" style="text-align: center" value="" size="4">
                      &nbsp;&nbsp;&nbsp;    <strong><?= AS_CASES_NRKLIENT ?></strong> 
                      <input tabindex="3" type="text" name="client_id" id="client_id" style="text-align: right" size="10" value="9" readonly class="disabled">
                    <td width="70" align="right" ><small><?= AS_CASES_MIAST ?></small>&nbsp;</td>
                    <td><div align="left"><input name="city" type="text" id="city" tabindex="16"></div></td>
                </tr>
                <tr>
                    <td width="70" align="right"><small><b><?= AS_CASES_NAZW ?></b></small></td>
                    <td><div align="left">
            			<input tabindex="3" type="text" name="paxSurname" id="paxSurname" size="20">
            			&nbsp;&nbsp; <small><?= AS_CASES_IMIE ?></small> <input tabindex="4" type="text" name="paxName" id="paxName" size="15">
          				&nbsp;&nbsp;
            			</div>
          			</td>
                  <td width="70" align="right"><small><?= AS_CASES_NRPOL ?></small></td>
                   <td><div align="left"><input tabindex="15" size="15" type="text" name="policy" id="policy"></div></td>
              </tr>        
             <tr>
                  <td width="70" align="right"><small><?= AS_CASES_ZDARZ ?></small></td>
                  <td>
                        <div align="left">
                            <input tabindex="5" type="text" name="eventDateFrom_d" id="eventDateFrom_d" size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center"> 
                            <input tabindex="6" type="text" name="eventDateFrom_m" id="eventDateFrom_m" size="1" maxlength="2" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);" style="text-align: center"> 
                            <input tabindex="7" type="text" name="eventDateFrom_y" id="eventDateFrom_y" size="4" maxlength="4" onkeydown="remove_formant(this,event);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('eventDateFrom')" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0"  ></a>&nbsp;-&nbsp;
                            <input tabindex="8" type="text" name="eventDateTo_d" id="eventDateTo_d" size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center"> 
                            <input tabindex="9" type="text" name="eventDateTo_m" id="eventDateTo_m" size="1" maxlength="2" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);" style="text-align: center"> 
                            <input tabindex="10" type="text" name="eventDateTo_y" id="eventDateTo_y" size="4" maxlength="4" onkeydown="remove_formant(this,event);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('eventDateTo')" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0" ></a>
                        </div></td>
                    <td width="70" align="right"><small><?= AS_CASES_OTW ?></small></td>
                    <td>
                        <div align="left">
                            <input tabindex="18" type="text" name="dateFrom_d" id="dateFrom_d" size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center"> 
                            <input tabindex="19" type="text" name="dateFrom_m" id="dateFrom_m" size="1" maxlength="2" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);" style="text-align: center"> 
                            <input tabindex="20" type="text" name="dateFrom_y" id="dateFrom_y" size="4" maxlength="4" onkeydown="remove_formant(this,event);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('dateFrom')" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0" ></a>&nbsp;-&nbsp;
                            <input tabindex="21" type="text" name="dateTo_d" id="dateTo_d" size="1" maxlength="2" onkeyup="move_formant(this,event);" style="text-align: center"> 
                            <input tabindex="22" type="text" name="dateTo_m" id="dateTo_m" size="1" maxlength="2" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);" style="text-align: center"> 
                            <input tabindex="23" type="text" name="dateTo_y" id="dateTo_y" size="4" maxlength="4" onkeydown="remove_formant(this,event);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('dateTo')" style="text-decoration: none"><img   src="img/kalendarz.gif" border="0" ></a>
                        </div></td>
                </tr>
            </table>
<div style="display:block;margin-left:13%;">            
            <table cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff" align="left">
                <tr >
<td align="center" width="30" bgcolor="#dfdfdf" nowrap >
<table cellpadding="3" cellspacing="1" border="0" bgcolor="#ffffff"><tr bgcolor="#aaaaaa"><td height="24" colspan="2" align="center"><b>Roszczenia</b></td></tr>
<tr bgcolor="#aaaaaa"><td align="center"><b>Do <br>akceptacji</b></td><td align="center"><b>Do <br>poprawy</b></td></tr>
<tr bgcolor="#aaaaaa"><td align="center"><input type="checkbox" name="roszczenia_do_akceptacji" id="roszczenia_do_akceptacji" onclick="ResetCheckbox('roszczenia_do_akceptacji');ClearSubmitSearch()" title="Roszczenia" style="background: #dfdfdf;" value="1"></td>
<td align="center"><input type="checkbox" name="roszczenia_do_poprawy" id="roszczenia_do_poprawy" onclick="ResetCheckbox('roszczenia_do_poprawy');ClearSubmitSearch()" title="Roszczenia do poprawy" style="background: #dfdfdf;" value="1"></td></tr>
 </table>
 </td>
 </tr>
 </table>
 <table cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff" align="left">
                <tr height="22">
<td align="center" width="30" bgcolor="#dfdfdf" nowrap >
<table cellpadding="3" cellspacing="1" border="0" bgcolor="#ffffff"><tr bgcolor="#bbbbbb"><td height="24" colspan="2" align="center"><b>Decyzje</b> 
<select name="decyzja_rodzaj" id="decyzja_rodzaj" onChange="ClearSubmitSearch();" style="font-size:10px;">
	<option value="0">wszystkie</option>
	<option value="3">pozytywne</option>
	<option value="4">odmowne</option>
</select>
</td></tr>
<tr bgcolor="#bbbbbb"><td align="center"><b>Do <br>uzupełnia</b></td><td align="center"><b>Do <br>drukowania</b></td></tr>
<tr bgcolor="#bbbbbb"><td align="center"><input type="checkbox" name="decyzje_do_uzupelnienia" id="decyzje_do_uzupelnienia" onclick="ResetCheckbox('decyzje_do_uzupelnienia');ClearSubmitSearch()" title="Roszczenia" style="background: #dfdfdf;" value="1"></td>
<td align="center"><input type="checkbox" name="decyzje_do_drukowania" id="decyzje_do_drukowania" onclick="ResetCheckbox('decyzje_do_drukowania');ClearSubmitSearch()" title="Roszczenia do poprawy" style="background: #dfdfdf;" value="1"></td></tr>
 </table>
 </td>
 </tr>
 </table>
 
 
 <table cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff" align="left">
                <tr height="15">
<td align="center" width="30" bgcolor="#dfdfdf" nowrap >
<table cellpadding="3" cellspacing="1" border="0" bgcolor="#ffffff"><tr bgcolor="#cccccc"><td colspan="5" height="24" align="center"><b>Płatności</b></td></tr>
<tr bgcolor="#cccccc"><td align="center"><b>Do <br>wysłania</b></td>
<td align="center" nowrap><b>Wysłane <br>do Allianz</b></td>
<td align="center" nowrap><b>Opłacone <br>przez Allianz</b></td>
<td align="center"><b>Do <br>wypłaty</b></td>
<td align="center"><b>Wypłacone</b></td>
</tr>
<tr bgcolor="#cccccc">
<td align="center"><input type="checkbox" name="platnosci_do_wyslania" id="platnosci_do_wyslania" onclick="ResetCheckbox('platnosci_do_wyslania');ClearSubmitSearch()" title="Roszczenia" style="background: #dfdfdf;" value="1"></td>
<td align="center"><input type="checkbox" name="platnosci_wyslane" id="platnosci_wyslane" onclick="ResetCheckbox('platnosci_wyslane');ClearSubmitSearch()" title="Roszczenia do poprawy" style="background: #dfdfdf;" value="1"></td>
<td align="center"><input type="checkbox" name="platnosci_oplacone_allianz" id="platnosci_oplacone_allianz" onclick="ResetCheckbox('platnosci_oplacone_allianz');ClearSubmitSearch()" title="Roszczenia do poprawy" style="background: #dfdfdf;" value="1"></td>
<td align="center"><input type="checkbox" name="platnosci_do_wyplaty" id="platnosci_do_wyplaty" onclick="ResetCheckbox('platnosci_do_wyplaty');ClearSubmitSearch()" title="Roszczenia do poprawy" style="background: #dfdfdf;" value="1"></td>
<td align="center"><input type="checkbox" name="platnosci_wyplacone" id="platnosci_wyplacone" onclick="ResetCheckbox('platnosci_wyplacone');ClearSubmitSearch()" title="Roszczenia do poprawy" style="background: #dfdfdf;" value="1"></td>
</tr>
 </table>
 </td>
 </tr>
 </table>
 
 
 </div>
 <div style="clear:both;"></div>   
 <table cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff">
                <tr height="15"><td align="center" width="30" bgcolor="#dfdfdf" nowrap >&nbsp;</td>
<td align="center" width="30" bgcolor="#dfdfdf" nowrap >&nbsp;</td>
<td align="center" width="40" bgcolor="#dfdfdf" nowrap >&nbsp;</td>
<td align="center" width="40" bgcolor="#dfdfdf" nowrap >&nbsp;</td>
<td align="center" width="200" bgcolor="#dfdfdf" nowrap title="Kategoria nowego dokumentu">Dokumenty&nbsp;
<select name="dok_cat" id="dok_cat" onChange="ClearSubmitSearch()" title="Kategoria nowego dokumentu" alt="Kategoria nowego dokumentu" style="font-size: 9px" onchange="ClearSubmitSearch()" >
<?php
$q= "SELECT * FROM coris_fax_in_category ";
$mr = mysql_query($q);
echo '<option value="0"> Wszystkie </option>';
while ($row=mysql_fetch_array($mr)){
	echo '<option value="'.$row['ID'].'">'.$row['name'].'</option>';
}
?>
</select></td>
                 <td align="left" width="465" bgcolor="#dfdfdf"> &nbsp;</td>

                    <td align="center" width="30" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="form1.reset(); SubmitSearch();" title="<?= AS_CASES_WYCZUSTWYSZ ?>">&nbsp;<font color="#6699cc" style="font-size: 12pt;" face="Wingdings">x</font>&nbsp;</td>
                    <td align="center" width="50" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="clear_step_end(); document.getElementById('amount').value = 100; SubmitSearch();" title="<?= AS_CASES_WYSW100 ?>"><font color="#6699cc"><small>100</small></font></td>
                    <td align="center" width="50" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="clear_step_end(); document.getElementById('amount').value = 500; SubmitSearch();" title="<?= AS_CASES_WYSW500 ?>"><font color="#6699cc"><small>500</small></font></td>
                    <td align="center" width="50" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: pointer;" onclick="clear_step_end(); document.getElementById('amount').value  = 1000; SubmitSearch();" title="<?= AS_CASES_WYSW1000 ?>"><font color="#6699cc"><small>1000</small></font></td>
                    <td align="center" width="30" bgcolor="#bbbbbb" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#bbbbbb'" style="cursor: pointer;" onclick="javascript:previous();"><font color="#6699cc" >&lt;&lt;</font></td>
                    <td align="center" width="30" bgcolor="#bbbbbb" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#bbbbbb'" style="cursor: pointer;" onclick="javascript:next();"><font color="#6699cc" >&gt;&gt;</font></td>
                </tr>
            </table>
		<iframe application="yes" width="100%" HIDEFOCUS height="270" frameborder="1" name="assistcases_frame" id="assistcases_frame" src="">
			Your browser does not support frames / Twoja przeglądarka nie obsługuje ramek.
		</iframe>
		<div align="right"><input size="8" type="hidden" name="sort" id="sort"><font color="#6699cc"><small><?= AS_CASES_LIT ?></small>&nbsp;<input size="2" type="text" name="letter" id="letter" style="border:none; text-align: center" disabled>&nbsp;<input size="8" type="hidden" name="amount" id="amount"><input size="8" type="hidden" name="step" id="step" value="0"><input size="8" type="hidden" name="end" id="end" value="0"><font color="#6699cc"><small><?= AS_CASES_WYSW ?></small>&nbsp;</font><input type="text" style="color: #6699cc; border: none; text-align: center" size="8" name="count" id="count" disabled><font color="#6699cc"> <small><?= AS_CASES_WYSWZ ?></small> </font><input type="text" style="color: #6699cc; border: none; text-align: center" size="8" name="total" id="total" disabled></font></div>
		</form>
       </center>

	</body>
</html>
