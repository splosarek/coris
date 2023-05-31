<?php include('include/include.php'); 
require_once('access.php'); 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
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
			var s = form1;
			if (s.step.value != 0) {
				s.end.value = 0;
				s.step.value = parseInt(s.step.value) - 1;
				SubmitSearch();
			}
		}
		function next() {
			var s = form1;
			if (s.end.value != 1) {
				s.step.value = parseInt(s.step.value) + 1;
				SubmitSearch();
			}
		}
		function lettersearch(l) {
			var s = form1;
			clear_step_end();
			s.letter.value=l;
            SubmitSearch();
		}
		function clear_step_end() {
			var s = form1;
			s.step.value = 0;
			s.end.value = 0;
		}
		function clear_all() {
			var s = form1;
			s.letter.value = '';
			s.step.value = 0;
			s.end.value = 0;
		}
        function ClearSubmitSearch() {
            clear_step_end();
            SubmitSearch();
        }
        function SubmitSearch() {
            var url = "AS_cases_frame.php?action=1&paxSurname="+ form1.paxSurname.value + "&caseId="+ form1.caseId.value +"&year="+ form1.year.value +"&paxName="+ form1.paxName.value +"&policy="+ form1.policy.value +"&policy_series="+ form1.policy_series.value +"&paxDob_d="+ form1.paxDob_d.value +"&paxDob_m="+ form1.paxDob_m.value +"&paxDob_y="+ form1.paxDob_y.value +"&country="+ form1.country.value +"&dateFrom_d="+ form1.dateFrom_d.value +"&dateFrom_m="+ form1.dateFrom_m.value +"&dateFrom_y="+ form1.dateFrom_y.value +"&dateTo_d="+ form1.dateTo_d.value +"&dateTo_m="+ form1.dateTo_m.value  +"&dateTo_y="+ form1.dateTo_y.value  +"&eventDateFrom_d="+ form1.eventDateFrom_d.value +"&eventDateFrom_m="+ form1.eventDateFrom_m.value +"&eventDateFrom_y="+ form1.eventDateFrom_y.value +"&eventDateTo_d="+ form1.eventDateTo_d.value +"&eventDateTo_m="+ form1.eventDateTo_m.value +"&eventDateTo_y="+ form1.eventDateTo_y.value +"&userId="+ form1.userId.value +"&amount="+ form1.amount.value +"&step="+ form1.step.value +"&letter="+ form1.letter.value +"&sort="+ form1.sort.value +"&archive="+ form1.archive.checked +"&watch="+ form1.watch.checked +"&transport="+ form1.transport.checked +"&decease="+ form1.decease.checked +"&ambulatory="+ form1.ambulatory.checked +"&hospitalization="+ form1.hospitalization.checked +"&costless="+ form1.costless.checked +"&unhandled="+ form1.unhandled.checked +"&reclamation="+ form1.reclamation.checked +"&new_documents=" + form1.new_documents.checked+"&new_documents_sort=" + form1.new_documents_sort.checked+'&client_id='+form1.client_id.value+'&attention='+form1.attention.checked+'&attention2='+form1.attention2.checked+'&city='+form1.city.value+'&wynajem_samochodu='+form1.wynajem_samochodu.checked+'&holowanie='+form1.holowanie.checked+'&marka_model='+form1.marka_model.value+'&nr_rej='+form1.nr_rej.value+'&signal_ready_export='+form1.signal_ready_export.checked+'&signal_export='+form1.signal_export.checked+'&signal_nexport='+form1.signal_nexport.checked+'&userRole='+form1.userRole.value+'&dok_cat='+form1.dok_cat.value+'&case_type='+document.getElementById('case_type').value+'&client_ref='+document.getElementById('client_ref').value;
            assistcases_frame.location = url;
        }
			// TODO: Poprawić - aby nie było "for"
            function move(s) {
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
	<body bgcolor="#dfdfdf" onload="form1.caseId.focus();">
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
				
				at= document.getElementById('attention');
				at2=  document.getElementById('attention2');
				
				if (s=='attention') {					
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
		<form name="form1">
            <table cellpadding="2" cellspacing="0" border="0">
                <tr valign="middle">
                    <td colspan="2" nowrap>
                        <input type="checkbox" name="archive" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" face="webdings" title="<?= AS_CASES_ARCH ?>" style="cursor: help; font-size: 14pt;">Ě</font>
                        <input type="checkbox" name="attention" id="attention" title="<?= AS_TITLE_RPT ?>" onclick="zaznacz_uwaga('attention');ClearSubmitSearch()" style="background: #dfdfdf; border: #FF0000 1px solid;" value="1">
                        <font color="#bbbbbb" face="webdings" title="<?= AS_TITLE_RPT ?>" style="cursor: help; font-size: 14pt;">i</font>
                        <input type="checkbox" name="attention2" id="attention2" title="<?= AS_TITLE_UWAGA ?>" onclick="zaznacz_uwaga('attention2');ClearSubmitSearch()" style="background: #dfdfdf; border: #6699cc 1px solid;" value="1">
                        <font color="#bbbbbb" face="webdings" title="<?= AS_TITLE_UWAGA ?>" style="cursor: help; font-size: 14pt;">i</font>                         
                         <input type="checkbox" name="holowanie" title="<?= AS_CASES_HOL ?>" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1">
                        <font color="#bbbbbb" face="webdings" title="<?= AS_CASES_HOL ?>" style="cursor: help; font-size: 14pt;">&#112;
                         <input type="checkbox" name="wynajem_samochodu" title="<?= AS_CASES_WYNSAM ?>" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" face="webdings" title="<?= AS_CASES_WYNSAM ?>" style="cursor: help; font-size: 14pt;">&#142;</font></font>
                        
                        &nbsp;&nbsp;
                        <input type="checkbox" name="watch" onclick="ClearSubmitSearch()" style="background: #dfdfdf; display: none;" value="1"><font color="#bbbbbb" face="webdings" title="<?= AS_CASES_NOWEWIAD ?>" style="cursor: help; font-size: 16pt; display: none;">N</font>&nbsp;
                        <input type="checkbox" name="transport" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" face="wingdings" title="<?= AS_CASES_TRANSP ?>" style="cursor: help; font-size: 14pt;"><b>Q</b></font>&nbsp;
                        <input type="checkbox" name="decease" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" face="wingdings" title="<?= AS_CASES_ZGON ?>" style="cursor: help; font-size: 14pt;">U</font>&nbsp;
                        <input type="checkbox" name="ambulatory" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" title="<?= AS_CASES_AMB ?>" style="cursor: help; font-size: 11pt;"><b>A</b></font>&nbsp;
                        <input type="checkbox" name="hospitalization" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" title="<?= AS_CASES_HOSP ?>" style="cursor: help; font-size: 11pt;"><b>H</b></font>&nbsp;
						<!-- NOWE -->
						<input type="checkbox" name="costless" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" title="<?= AS_CASES_BEZKOSZT ?>" style="cursor: help; font-size: 11pt; text-decoration: line-through"><b>$</b></font>&nbsp;
						<input type="checkbox" name="unhandled" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" title="<?= AS_CASES_BEZRYCZHON ?>" style="cursor: help; font-size: 14pt" face="Webdings">y</font>&nbsp;
						<input type="checkbox" name="reclamation" onclick="ClearSubmitSearch()" style="background: #dfdfdf;" value="1"><font color="#bbbbbb" title="<?= AS_CASES_REKL ?>" style="cursor: help; font-size: 11pt;"><b>R</b></font>
                    </td>
                    <td width="70" align="right">
                    <select name="userRole"  onChange="ClearSubmitSearch()">
                    	<option value="1"><?= AS_CASES_RED ?></option>
                    	<option value="2">Likwidator</option>
                    </select>
                    </td>
                    <td>
					<div align="left">
<?php
$query = "SELECT user_id, surname, name FROM coris_users WHERE name NOT LIKE '' AND active = 1 AND (department_id = 7 OR department_id = 4) ORDER BY surname";

if ($result = mysql_query($query)) {
    echo "<select name=\"userId\" onchange=\"ClearSubmitSearch()\" tabindex=\"11\" style=\"font-size: 8pt;\">";
    echo "<option></option>";
    while ($row = mysql_fetch_array($result))
        //echo ($selected == $row[0]) ? "<option value=\"$row[0]\" selected>$row[1], $row[2]</option>" : "<option value=\"$row[0]\">$row[1], $row[2]</option>";
        echo "<option value=\"$row[0]\">$row[1], $row[2]</option>";
        echo "</select>";
        mysql_free_result($result);
} else {
    die(mysql_error());
}
?>
                    </div></td>
                </tr>
                <tr>
                    <td width="70" align="right" bgcolor="#dfdfdf"><b><small><?= AS_CASES_NRSZKOD ?></small></b></td>
                    <td bgcolor="#dfdfdf">                      <div align="left">
                          <input tabindex="1" type="text" name="caseId" style="text-align: right" size="10">
                          <input tabindex="2" type="text" name="year" style="text-align: center" value="" size="4">
                      &nbsp;&nbsp;&nbsp;    <strong><?= AS_CASES_NRKLIENT ?></strong> 
                      <input tabindex="3" type="text" name="client_id" style="text-align: right" size="10">
                    &nbsp;&nbsp;&nbsp; <?= AS_CASES_TYPE ?>: <select name="case_type"  id="case_type"  onchange="ClearSubmitSearch();"><option value="0"> wszystkie </option><?php
                    
                    $query = "SELECT * FROM coris_assistance_cases_types ";
                    $mysql_result = mysql_query($query);
                    while ($row=mysql_fetch_array($mysql_result)){
                    		echo '<option value="'.$row['type_id'].'">'.$row['value'].'</option>';
                    }
                    
                    ?></select></div></td>
                    <td width="70" align="right" title="<?= AS_CASES_CLREF_L ?>">
            <small><?= AS_CASES_DAT ?></small>&nbsp;
                    </td>
                    <td>
                        <div align="left">
            <input tabindex="12" type="text" name="paxDob_d" size="1" maxlength="2" onkeyup="move(this);" style="text-align: center"> 
                            <input tabindex="13" type="text" name="paxDob_m" size="1" maxlength="2" onkeyup="move(this);" onkeydown="remove(this);" style="text-align: center"> 
                            <input tabindex="14" type="text" name="paxDob_y" size="4" maxlength="4" onkeydown="remove(this);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('paxDob')" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 16pt;">1</font></a>
                          
                        </div></td>
                </tr>
                <tr>
                    <td width="70" align="right">
                        <small><b><?= AS_CASES_NAZW ?></b></small>
                    </td>
                    <td>
                        <div align="left">
            <input tabindex="3" type="text" name="paxSurname" size="20">
            &nbsp;&nbsp; <small><?= AS_CASES_IMIE ?></small> <input tabindex="4" type="text" name="paxName" size="15">
          &nbsp;&nbsp;
            </div>
          </td>
                    <td width="70" align="right"><small><?= AS_CASES_NRPOL ?></small></td>
                    <td>
                        <div align="left">
                         Seria: <input tabindex="15" size="10" maxlength="10" type="text" name="policy_series">
                         &nbsp;Nr: <input tabindex="15" type="text" name="policy">
                        </div></td>
                </tr>
                <tr>
                    
        <td width="70" align="right"><?= AS_CASES_MARKMOD ?> </td>
                    <td>
                        <div align="left">
                          <input tabindex="4" type="text" name="marka_model" size="15">
            <?= AS_CASES_NRREJ ?> 
            <input tabindex="4" type="text" name="nr_rej" size="10">
                        <small><?= AS_CASES_CLREF ?></small>
                            <input type="text" name="client_ref" id="client_ref" size="15" maxlength="30" onchange="ClearSubmitSearch();"> 
          </div></td>
                    <td width="70" align="right">
                        <small><?= COUNTRY ?></small>
                    </td>
                    <td>                      <div align="left">
                            <input tabindex="16" type="text" name="country" size="3" style="text-align: center">
                      &nbsp;&nbsp;<?= AS_CASES_MIAST ?>&nbsp;
                      <input name="city" type="text" id="city" tabindex="15">
                    </div></td>
                </tr>
                <tr>
                    <td width="70" align="right">
                        <small><?= AS_CASES_ZDARZ ?></small>
                    </td>
                    <td>
                        <div align="left">
                            <input tabindex="5" type="text" name="eventDateFrom_d" size="1" maxlength="2" onkeyup="move(this);" style="text-align: center"> 
                            <input tabindex="6" type="text" name="eventDateFrom_m" size="1" maxlength="2" onkeyup="move(this);" onkeydown="remove(this);" style="text-align: center"> 
                            <input tabindex="7" type="text" name="eventDateFrom_y" size="4" maxlength="4" onkeydown="remove(this);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('eventDateFrom')" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 16pt;">1</font></a>&nbsp;-&nbsp;
                            <input tabindex="8" type="text" name="eventDateTo_d" size="1" maxlength="2" onkeyup="move(this);" style="text-align: center"> 
                            <input tabindex="9" type="text" name="eventDateTo_m" size="1" maxlength="2" onkeyup="move(this);" onkeydown="remove(this);" style="text-align: center"> 
                            <input tabindex="10" type="text" name="eventDateTo_y" size="4" maxlength="4" onkeydown="remove(this);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('eventDateTo')" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 16pt;">1</font></a>
                        </div></td>
                    <td width="70" align="right">
                        <small><?= AS_CASES_OTW ?></small>
                    </td>
                    <td>
                        <div align="left">
                            <input tabindex="18" type="text" name="dateFrom_d" size="1" maxlength="2" onkeyup="move(this);" style="text-align: center"> 
                            <input tabindex="19" type="text" name="dateFrom_m" size="1" maxlength="2" onkeyup="move(this);" onkeydown="remove(this);" style="text-align: center"> 
                            <input tabindex="20" type="text" name="dateFrom_y" size="4" maxlength="4" onkeydown="remove(this);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('dateFrom')" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 16pt;">1</font></a>&nbsp;-&nbsp;
                            <input tabindex="21" type="text" name="dateTo_d" size="1" maxlength="2" onkeyup="move(this);" style="text-align: center"> 
                            <input tabindex="22" type="text" name="dateTo_m" size="1" maxlength="2" onkeyup="move(this);" onkeydown="remove(this);" style="text-align: center"> 
                            <input tabindex="23" type="text" name="dateTo_y" size="4" maxlength="4" onkeydown="remove(this);" style="text-align: center">
                            <a href="javascript:void(0)" onclick="newWindowCal('dateTo')" style="text-decoration: none"><font face="Webdings" style="color: #000000; font-size: 16pt;">1</font></a>
                        </div></td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="1" border="0" bgcolor="#ffffff">
                <tr height="15">
<td align="center" width="30" bgcolor="#dfdfdf" nowrap ><input type="checkbox" name="signal_ready_export" onclick="ClearSubmitSearch()" title="<?= AS_CASES_SIGNAL1 ?>" style="background: #dfdfdf;" value="1"><font color="#6699cc"  title="<?= AS_CASES_SIGNAL1 ?>">G</font></td>
<td align="center" width="30" bgcolor="#dfdfdf" nowrap ><input type="checkbox" name="signal_export" onclick="ClearSubmitSearch()" title="<?= AS_CASES_SIGNAL2 ?>" style="background: #dfdfdf;" value="1"><font color="#6699cc"  title="<?= AS_CASES_SIGNAL2 ?>">E</font></td>
<td align="center" width="30" bgcolor="#dfdfdf" nowrap ><input type="checkbox" name="signal_nexport" onclick="ClearSubmitSearch()" title="<?= AS_CASES_SIGNAL3 ?>" style="background: #dfdfdf;" value="1"><font color="#6699cc"  title="<?= AS_CASES_SIGNAL3 ?>">N</font></td>
<td align="center" width="40" bgcolor="#dfdfdf" nowrap ><input type="checkbox" name="new_documents" onclick="ClearSubmitSearch()" title="<?= AS_CASES_NOWDOKWSPR ?>" style="background: #dfdfdf;" value="1"><font color="#6699cc" style="font-size: 12pt;" face="Wingdings" title="<?= AS_CASES_NOWDOKWSPR ?>">1</font></td>
<td align="center" width="40" bgcolor="#dfdfdf" nowrap ><input type="checkbox" name="new_documents_sort" onclick="ClearSubmitSearch()" title="<?= AS_CASES_NOWDOKWSPR ?>" style="background: #dfdfdf;" value="1"><font color="#6699cc" style="font-size: 12pt;" face="Wingdings" title="<?= AS_CASES_NOWDOKWSPRSORT ?>">â</font></td>
<td align="center" width="150" bgcolor="#dfdfdf" nowrap title="Kategoria nowego dokumentu" >
<select name="dok_cat" onChange="ClearSubmitSearch()" title="Kategoria nowego dokumentu" alt="Kategoria nowego dokumentu" style="font-size: 9px" onchange="ClearSubmitSearch()" >
<?php
$q= "SELECT * FROM coris_fax_in_category ";
$mr = mysql_query($q);
echo '<option value="0"> Wszystkie </option>';
while ($row=mysql_fetch_array($mr)){
	echo '<option value="'.$row['ID'].'">'.$row['name'].'</option>';
}
?>
</select></td>

                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('a');"><font color="#6699cc">a</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('b');"><font color="#6699cc">b</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('c');"><font color="#6699cc">c</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('ć');"><font color="#6699cc">ć</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('d');"><font color="#6699cc">d</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('e');"><font color="#6699cc">e</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('f');"><font color="#6699cc">f</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('g');"><font color="#6699cc">g</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('h');"><font color="#6699cc">h</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('i');"><font color="#6699cc">i</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('j');"><font color="#6699cc">j</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('k');"><font color="#6699cc">k</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('l');"><font color="#6699cc">l</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('ł');"><font color="#6699cc">ł</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('m');"><font color="#6699cc">m</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('n');"><font color="#6699cc">n</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('o');"><font color="#6699cc">o</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('p');"><font color="#6699cc">p</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('q');"><font color="#6699cc">q</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('r');"><font color="#6699cc">r</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('s');"><font color="#6699cc">s</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('ś');"><font color="#6699cc">ś</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('t');"><font color="#6699cc">t</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('u');"><font color="#6699cc">u</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('v');"><font color="#6699cc">v</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('w');"><font color="#6699cc">w</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('x');"><font color="#6699cc">x</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('y');"><font color="#6699cc">y</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('z');"><font color="#6699cc">z</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('ź');"><font color="#6699cc">ź</font></td>
                    <td align="center" width="15" bgcolor="#dfdfdf" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#dfdfdf'" style="cursor: hand;" onclick="lettersearch('ż');"><font color="#6699cc">ż</font></td>
                    <td align="center" width="30" bgcolor="#ffffff" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#ffffff'" style="cursor: hand;" onclick="form1.reset(); SubmitSearch();" title="<?= AS_CASES_WYCZUSTWYSZ ?>">&nbsp;<font color="#6699cc" style="font-size: 12pt;" face="Wingdings">x</font>&nbsp;</td>
                    <td align="center" width="50" bgcolor="#ffffff" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#ffffff'" style="cursor: hand;" onclick="clear_step_end(); form1.amount.value = 100; SubmitSearch();" title="<?= AS_CASES_WYSW100 ?>"><font color="#6699cc"><small>100</small></font></td>
                    <td align="center" width="50" bgcolor="#ffffff" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#ffffff'" style="cursor: hand;" onclick="clear_step_end(); form1.amount.value = 500; SubmitSearch();" title="<?= AS_CASES_WYSW500 ?>"><font color="#6699cc"><small>500</small></font></td>
                    <td align="center" width="50" bgcolor="#ffffff" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#ffffff'" style="cursor: hand;" onclick="clear_step_end(); form1.amount.value  = 1000; SubmitSearch();" title="<?= AS_CASES_WYSW1000 ?>"><font color="#6699cc"><small>1000</small></font></td>
                    <!-- <td align="center" width="60" bgcolor="#ffffff" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#ffffff'" style="cursor: hand;" onclick="window.open('assistcases-view-all.php','all','toolbar=no,location=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=800,height=600')" title="UWAGAWyświetl wszystkie spraw na raz"><font color="#6699cc"><small>wszystkie</small></font></a></td>-->
                    <td align="center" width="30" bgcolor="#bbbbbb" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#bbbbbb'" style="cursor: hand;" onclick="javascript:previous();"><font color="#6699cc" face="Webdings">7</font></td>
                    <td align="center" width="30" bgcolor="#bbbbbb" onmouseover="bgColor='#eeeeee'" onmouseout="bgColor='#bbbbbb'" style="cursor: hand;" onclick="javascript:next();"><font color="#6699cc" face="Webdings">8</font></td>
                </tr>
            </table>
		<iframe application="yes" width="100%" HIDEFOCUS height="250" frameborder="1" name="assistcases_frame" id="assistcases_frame" src="AS_cases_frame.php">
			Your browser does not support frames / Twoja przeglądarka nie obsługuje ramek.
		</iframe>
		<div align="right"><input size="8" type="hidden" name="sort"><font color="#6699cc"><small><?= AS_CASES_LIT ?></small>&nbsp;<input size="2" type="text" name="letter" style="border:none; text-align: center" disabled>&nbsp;<input size="8" type="hidden" name="amount"><input size="8" type="hidden" name="step" value="0"><input size="8" type="hidden" name="end" value="0"><font color="#6699cc"><small><?= AS_CASES_WYSW ?></small>&nbsp;</font><input type="text" style="color: #6699cc; border: none; text-align: center" size="8" name="count" disabled><font color="#6699cc"> <small><?= AS_CASES_WYSWZ ?></small> </font><input type="text" style="color: #6699cc; border: none; text-align: center" size="8" name="total" disabled></font></div>
		</form>
       </center>
<?php
if (isset($_GET['new_documents']) && $_GET['new_documents']=='1'){
	echo '<script>document.form1.new_documents.click();</script>';

}
if (isset($_GET['claims_stat']) ){
	echo '<script>	
	 var url = "AS_cases_frame.php?claims_stat='.$_GET['claims_stat'].'";
     assistcases_frame.location = url;
	</script>';
}
?>

	</body>
</html>
