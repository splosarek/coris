<?php


require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');
include_once('../lib/lib_allianz.php');




$result  =  form_register();
	
echo iconv('latin2','UTF-8',$result);
exit();


function form_register(){

	$result = '
	  <table cellpadding="5" cellspacing="1" border="0"   >
					<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Nr protoko³u:</b>	</td><td><input type="text" size="50" name="nr_protokolu" id="nr_protokolu" class="required1" ></td>
				</tr>
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Ko³o ³owieckie :</b>	</td><td>';					 					 					
						$result .= AllianzCase::getKolaLowieckie('kolo_id',0,0,'onChange="getKoloLowieckie(this.value);"');
						

						$result .= '<hr><table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><input type="text" size="50" name="kolo_nazwa" id="kolo_nazwa" class="required1" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="required1" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="kolo_kod" id="kolo_kod" class="required1" readonly> <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="required1" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="required1" readonly></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="required1" style="text-align:right;" value="" readonly> PLN, <span id="franszyza_info"></span></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr polisy: </td><td><input type="text" size="40" name="kolo_nr_polisy" id="kolo_nr_polisy" class="required1"  value="" > </td></tr>';
							$result .= '<tr bgcolor="#AAAAAA" ><td>Nr konta bankowego:</td><td><input type="text" size="50" name="kolo_konto" id="kolo_konto" class="required1" ></td></tr>';
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				
				<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Lokalizacja szkody :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#BBBBBB"><td>Województwo: </td><td>'.AllianzCase::getWojewodztwa('woj_id',0,0,'onChange="getPowiaty(this.value);"').'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powiat: </td><td>'.AllianzCase::getPowiatyRegister().'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Gmina: </td><td>'.AllianzCase::getGminyRegister().'</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Wie¶/miasto: </td><td><input type="text" size="50" name="lok_miejscowosc" id="lok_miejscowosc" class="required1" ></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Nr dzia³ki: </td><td>							
							<textarea cols="60" rows="2"  class="required1" name="nr_dzialki" id="nr_dzialki"></textarea>
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Powierzchnia dzia³ki: </td><td><input type="text" size="10" name="powierzchnia_dzialki" id="powierzchnia_dzialki" class="required1" style="text-align:right;"> ha</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Obwód ³owiecki nr: </td><td><input type="text" size="50" name="obwod_lowiecki" id="obwod_lowiecki" class="required1" ></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Poszkodowany :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>Nazwisko: </td><td><input type="text" size="50" name="poszk_nazwisko" id="poszk_nazwisko" class="required1" onChange="this.value=this.value.toUpperCase();"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Imiê: </td><td><input type="text" size="50" name="poszk_imie" id="poszk_imie" class="required1" onChange="this.value=this.value.toUpperCase();"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Adres: </td><td><input type="text" size="50" name="poszk_adres" id="poszk_adres" class="required1" onChange="this.value=this.value.toUpperCase();"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="poszk_kod" id="poszk_kod" class="required1" > <input type="text" size="35" name="poszk_miejscowosc" id="poszk_miejscowosc" class="required1" onChange="this.value=this.value.toUpperCase();"></td></tr>';
							$result .= '<tr bgcolor="#AAAAAA"><td>Tel: </td><td><input type="text" size="15" name="poszk_tel" id="poszk_tel" class="required1" ></td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Email: </td><td><input type="text" size="40" name="poszk_email" id="poszk_email" class="required1" ></td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
			<tr bgcolor="#AAAAAA">	
					<td width="150" align="right"><b>Szkoda :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
						$result .= '<tr bgcolor="#BBBBBB"><td>Nr sprawy Allianz: </td><td><input type="text" size="30" name="nr_sprawy_allianz" id="nr_sprawy_allianz" class="required1" ></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zdarzenia: </td><td>
											<input type="text" name="eventDate_d" id="eventDate_d" size="1" value="" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;
											<input type="text" name="eventDate_m" id="eventDate_m" size="1" value="" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;
											<input type="text" name="eventDate_y" id="eventDate_y" size="4" value="" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);">
											<a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal(\'eventDate\')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small></td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data zg³oszenia szkody<br>do ko³a ³owieckiego: </td><td>
											<input type="text" name="data_zgloszenia_do_kola_d" id="data_zgloszenia_do_kola_d" size="1" value="" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;
											<input type="text" name="data_zgloszenia_do_kola_m" id="data_zgloszenia_do_kola_m" size="1" value="" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;
											<input type="text" name="data_zgloszenia_do_kola_y" id="data_zgloszenia_do_kola_y" size="4" value="" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);">
											<a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal(\'data_zgloszenia_do_kola\')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small>
											
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Data wp³yniêcia<br> do CORIS: </td><td>
							<input type="text" name="notificationDate_d" id="notificationDate_d" size="1" value="'.date("d") .'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);">&nbsp;
											<input type="text" name="notificationDate_m" id="notificationDate_m" size="1" value="'. date("m") .'" maxlength="2" style="text-align: center" onkeyup="move_formant(this,event);" onkeydown="remove_formant(this,event);">&nbsp;
											<input type="text" name="notificationDate_y" id="notificationDate_y" size="4" value="'. date("Y") .'" maxlength="4" style="text-align: center" onkeydown="remove_formant(this,event);">
											<a href="javascript:void(0)" tabindex="-1" onclick="newWindowCal(\'notificationDate\')" style="text-decoration: none"><img src="img/kalendarz.gif" border="0"></a> <small>(dd mm yyyy)</small>
							</td></tr>';
							
							$result .= '<tr bgcolor="#BBBBBB"><td>Szacuj±cy szkodê: </td><td>'.AllianzCase::getSzacujacyRegister().'Nowy: Imiê Nazwisko: <input type="text" size="25" name="szacujacy_nazwa" id="szacujacy_nazwa" class="required1" readonly> Tel:<input type="text" size="12" name="szacujacy_tel" id="szacujacy_tel" class="required1" ></td></tr>';
														
							$result .= '<tr bgcolor="#BBBBBB"><td>Gatunek zwierzyny, <br>który wyrz±dzi³ szkodê: </td><td>
								<select multiple="multiple" class="required1" size="6" name="gatunek_zwierzyny[]" onClick="sprawdz_zwierzyne()">
									<option value="1">Dzik</option>
									<option value="2">£o¶</option>
									<option value="3">Jeleñ</option>
									<option value="4">Daniel</option>
									<option value="5">Sarna</option>
									<option value="6">Inne</option>
								</select> Inne: <input type="text" size="35" name="gatunek_zwierzyny_inne" id="gatunek_zwierzyny_inne" class="required1" >
							</td></tr>';
							$result .= '<tr bgcolor="#BBBBBB"><td>Rodzaj, stan i jako¶æ upraw: </td><td><textarea cols="80" rows="5"  class="required1" name="rodzaj_stan_upraw" id="rodzaj_stan_upraw"></textarea></td></tr>';
							$result .= '</table>';
					$result .= '</td></tr>
			<tr >	
		<tr >	
				<tr bgcolor="#BBBBBB">	
					<td width="150" align="right"><b>Roszczenie :</b>	</td><td>';					 					 											
						$result .= '<table cellpadding="5" cellspacing="1">';
							$result .= '<tr bgcolor="#AAAAAA"><td>RWS: </td><td><input type="text" size="10" name="rws" id="rws" class="required1" > PLN </td></tr>';							
							$result .= '<tr bgcolor="#AAAAAA"><td>Kwota roszczenia: </td><td><input type="text" size="10" name="kwota_roszczenia" id="kwota_roszczenia" class="required1" > PLN</td></tr>';							
						$result .= '</table>';
					$result .= '</td></tr>
			<tr >		
		</table>							
	';
	
	
	return $result;
}
?>