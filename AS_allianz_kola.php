<?php 
require_once('include/include.php'); 
include_once('include/strona.php'); 
include_once('include/send_list.inc.php');
include_once('lib/lib_allianz.php');
html_start();

$kolo_id = intval(getValue('kolo_id'));
$action = getValue('action');

echo '
<script>
function edit(id){
	document.getElementById(\'kolo_id\').value=id;
	document.getElementById(\'form_kolo_lista\').submit();
}
</script>

';

echo '<form method="post" id="form_kolo_lista"><input type="hidden" name="kolo_id" id="kolo_id" value="0"></form>';
echo '<form method="post"><div align="center"><b>Allianz Ko³a £owieckie</b></div></form>';

$new_note = getValue('new_note');

if ($action == 'add_form' ){
	echo add_form();
}else if ($action == 'save_record' && $kolo_id>0){
	echo save_kolo($kolo_id);
	if ($new_note != ''){
		save_note($kolo_id,$new_note);
		echo editKolo($kolo_id);
	}else{
		showList();
	}
}else if ($action == 'add_record' ){
	echo add_kolo($kolo_id);
	showList();
}else if ($kolo_id>0){
	echo editKolo($kolo_id);
}else{	
	showList();	
}

function save_kolo($kolo_id){
		
		$kolo_nazwa = getValue('kolo_nazwa');
		$kolo_adres = getValue('kolo_adres');
		$kolo_kod = getValue('kolo_kod');
		$kolo_miejscowosc = getValue('kolo_miejscowosc');
		$kolo_zo = getValue('kolo_zo');
		$kolo_konto = getValue('kolo_konto');
		$adres_do_korespondencji = getValue('adres_do_korespondencji');
		
		
		$kolo_polisa = getValue('kolo_nr_polisy');
		$kolo_suma_ubezpieczenia = str_replace(',','.',getValue('kolo_suma_ubezpieczenia'));
		$franszyza = getValue('franszyza');
		$franszyza_kwota = str_replace(',','.',getValue('franszyza_kwota'));
		$active = getValue('active')==1 ? 1 : 0;
		
		
			$query = "UPDATE coris_allianz_kola  SET 
				nazwa  ='".$kolo_nazwa."',
				adres  ='".$kolo_adres."',  
				kod    ='".$kolo_kod."',
				miejscowosc    ='".$kolo_miejscowosc."',
				ZO    ='".$kolo_zo."',
				konto_bankowe ='".$kolo_konto."',
				adres_do_korespondencji='".$adres_do_korespondencji."',
				active   ='".$active."'										
				WHERE ID='$kolo_id' LIMIT 1";
				$mr = mysql_query($query);
				if (!$mr ){																						
					echo  "<br>Insert Error: $query <br><br> ".mysql_error();				
			 	}	
			$query = "UPDATE coris_allianz_ubezpieczenia   SET 
				kolo_nr_polisy='".$kolo_polisa."',
				suma_ubezpieczenia='".$kolo_suma_ubezpieczenia."',
				franszyza_rodzaj  ='".$franszyza."',
				franszyza_kwota  	='".$franszyza_kwota."'							
				WHERE ID_kolo='$kolo_id' LIMIT 1";
				$mr = mysql_query($query);
				if (!$mr ){																						
					echo  "<br>Insert Error: $query <br><br> ".mysql_error();				
			 	}

			 	
		return '<div align="center"><b>Dane ko³a zosta³y zaktualizowane</b></div>';		 	
}

function save_note($kolo_id,$new_note){
	
		$query = "INSERT INTO coris_allianz_kola_notatki   SET 
				ID_kolo='".$kolo_id."', text='".mysql_escape_string($new_note)."',  ID_user='".Application::getCurrentUser()."'
				, date=now(),  active=1"; 
		$mr = mysql_query($query);		
		if ($mr){
				echo '<div align="center"><b>Notatka zosta³a dodana</b></div>';
				
		}else{
				echo '<div align="center"><b>B³±d dodawania notatki.</b></div>';
				echo  "<br>Insert Error: $query <br><br> ".mysql_error();
		}		 				
}

function add_kolo($kolo_id){
		$kolo_nazwa = getValue('kolo_nazwa');
		$kolo_adres = getValue('kolo_adres');
		$kolo_kod = getValue('kolo_kod');
		$kolo_miejscowosc = getValue('kolo_miejscowosc');
		$kolo_zo = getValue('kolo_zo');
		$kolo_konto = getValue('kolo_konto');
		$adres_do_korespondencji = getValue('adres_do_korespondencji');
		
		$kolo_polisa = getValue('kolo_nr_polisy');
		$kolo_suma_ubezpieczenia = str_replace(',','.',getValue('kolo_suma_ubezpieczenia'));
		$franszyza = getValue('franszyza');
		$franszyza_kwota = str_replace(',','.',getValue('franszyza_kwota'));
		$active = getValue('active')==1 ? 1 : 0;
		
		
			$query = "INSERT INTO coris_allianz_kola  SET 
				nazwa  ='".$kolo_nazwa."',
				adres  ='".$kolo_adres."',  
				kod    ='".$kolo_kod."',
				miejscowosc    ='".$kolo_miejscowosc."',
				ZO    ='".$kolo_zo."',
				konto_bankowe ='".$kolo_konto."',
				adres_do_korespondencji='".$adres_do_korespondencji."',
				active   ='".$active."',					
				manual=1";					
				
				$mr = mysql_query($query);
				if (!$mr ){																						
					echo  "<br>Insert Error: $query <br><br> ".mysql_error();	exit();			
			 	}	
			 	$kolo_id = mysql_insert_id();
			 if ($kolo_id>0){	
				$query = "INSERT INTO coris_allianz_ubezpieczenia   SET
					ID_kolo='$kolo_id',ID_umowa=1, 
					kolo_nr_polisy='".$kolo_polisa."',
					suma_ubezpieczenia='".$kolo_suma_ubezpieczenia."',
					franszyza_rodzaj  ='".$franszyza."',
					franszyza_kwota  	='".$franszyza_kwota."'";							
					
					$mr = mysql_query($query);
					if (!$mr ){																						
						echo  "<br>Insert Error: $query <br><br> ".mysql_error();		exit();		
				 	}
				 	
				 	return '<div align="center"><b>Dane ko³a zosta³y dodane</b></div>';
			 }
		return '<div align="center"><b>B³±d dodawania ko³a</b></div>';		 	
}



function add_form(){
	$result = '<form method="POST">
		
		<input type="hidden" name="action" value="add_record">
		<div align="center">';
  		$result .= '<b>Dodawanie nowego Ko³a</b> - <a href="As_allianz_kola.php"><b>&lt;&lt; Powrót do listy</b></a><br><br> <table cellpadding="5" cellspacing="1">';					
					$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><textarea type="text" cols="50" rows="2" name="kolo_nazwa" id="kolo_nazwa" class="required1"></textarea></td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="required1" value=""></td></tr>';
					$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="kolo_kod" id="kolo_kod" class="required1" value="" > <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="required1" value=""></td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="required1" value="'.$row['ZO'].'" ></td></tr>';
					$result .= '<tr bgcolor="#CCCCCC" ><td>Adres do korespondencji: </td><td><textarea cols="50"  rows="3" name="adres_do_korespondencji" id="adres_do_korespondencji" class="required1" >'.$row['adres_do_korespondencji'].'</textarea></td></tr>';					
					$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="required1" style="text-align:right;" value="'.print_currency(0.00).'" > PLN, Franszyza 
					<select name="franszyza">
						<option value="0" ></option>
						<option value="1" >Itegralna</option>
						<option value="2" >Redukcyjna</option>
					</select>
					<input type="text" size="7" name="franszyza_kwota" id="franszyza_kwota" class="required1" style="text-align:right;" value="'.print_currency(0.00).'" > PLN </td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>Nr polisy: </td><td><input type="text" size="40" name="kolo_nr_polisy" id="kolo_nr_polisy" class="required1"  value="" > </td></tr>';							
					$result .= '<tr bgcolor="#AAAAAA" ><td>Nr konta bankowego:</td><td><input type="text" size="50" name="kolo_konto" id="kolo_konto" class="required1" value=""></td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>Aktywno¶æ:</td><td><input type="checkbox" size="50" name="active" id="active" class="required1" value="1" checked></td></tr>';
			$result .= '</table><br><br>';
				$result .= '<input type="submit" value="Dodaj">';
		$result .= '</div></form>';	
		
		$result .= '<form method="POST"><div align="center">';			
		$result .= '<input type="submit" value="Anuluj">';
		$result .= '</div></form>';	
		return $result;				
}
function editKolo($kolo_id){
	
	
	 $query = "SELECT coris_allianz_kola.* ,coris_allianz_ubezpieczenia.kolo_nr_polisy,
  coris_allianz_ubezpieczenia.suma_ubezpieczenia,
  coris_allianz_ubezpieczenia.franszyza_rodzaj,
  coris_allianz_ubezpieczenia.franszyza_kwota
   FROM coris_allianz_kola LEFT JOIN coris_allianz_ubezpieczenia ON  coris_allianz_kola.ID=coris_allianz_ubezpieczenia.ID_kolo AND coris_allianz_ubezpieczenia.ID_umowa=1
   WHERE  coris_allianz_kola.ID='$kolo_id'";   	  	
  	$mysql_result = mysql_query($query);
  	if (mysql_num_rows($mysql_result) == 0) return 'BRAK KO£A ID:'.$kolo_id;
  	$row = mysql_fetch_array($mysql_result);
  	
  		$result = '
  		<script>
  		
  		function xopen_history(id){			 
			window.open(\'AS_allianz_kola_history.php?id=\' + id, \'HistoriaKola\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=800,height=700,left=\'+ (screen.availWidth - 750) / 2 +\',top=\'+ ((screen.availHeight - screen.availHeight * 0.05) - 750) / 2);
		}
  		</script>
  		';
  		
  		$status_sumy_ubezpieczenia = AllianzCase::getKoloDostepnaSumaUbezpieczenia($row['ID']);

  		$style = 'style="background-color:#00FF00"';
  		
  		if ($status_sumy_ubezpieczenia['status'] == 'warning' )
  			$style = 'style="background-color:#FFFF00"';
		
  		if ($status_sumy_ubezpieczenia['status'] == 'error' )
  			$style = 'style="background-color:#FF0000"';
  			
  			
		$result .= '<form method="POST">
		<input type="hidden" name="kolo_id" id="kolo_id" value="'.$kolo_id.'">
		<input type="hidden" name="action" value="save_record">
		<div align="center">';
		$result .= '<b>Edycja Ko³a</b> - <a href="AS_allianz_kola.php"><b>&lt;&lt; Powrót do listy</b></a><br><br> <table cellpadding="5" cellspacing="1">';
					$result .= '<tr bgcolor="#BBBBBB" ><td>ID: </td><td>'.$row['ID'].'</td></tr>';
					$result .= '<tr bgcolor="#AAAAAA" ><td>Nazwa: </td><td><textarea type="text" cols="50" rows="2" name="kolo_nazwa" id="kolo_nazwa" class="required1">'.$row['nazwa'].'</textarea></td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>Adres: </td><td><input type="text" size="50" name="kolo_adres" id="kolo_adres" class="required1" value="'.$row['adres'].'"></td></tr>';
					$result .= '<tr bgcolor="#AAAAAA" ><td>Kod Miejscowo¶æ:</td><td><input type="text" size="6" name="kolo_kod" id="kolo_kod" class="required1" value="'.$row['kod'].'" > <input type="text" size="35" name="kolo_miejscowosc" id="kolo_miejscowosc" class="required1" value="'.$row['miejscowosc'].'"></td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>ZO: </td><td><input type="text" size="50" name="kolo_zo" id="kolo_zo" class="required1" value="'.$row['ZO'].'" ></td></tr>';
					$result .= '<tr bgcolor="#CCCCCC" ><td>Adres do korespondencji: </td><td><textarea cols="50"  rows="3" name="adres_do_korespondencji" id="adres_do_korespondencji" class="required1" >'.$row['adres_do_korespondencji'].'</textarea></td></tr>';
					$result .= '<tr bgcolor="#AAAAAA" ><td>Suma ubezpieczenia: </td><td><input type="text" size="10" name="kolo_suma_ubezpieczenia" id="kolo_suma_ubezpieczenia" class="required1" style="text-align:right;" value="'.print_currency($row['suma_ubezpieczenia']).'" > PLN, Franszyza 
					<select name="franszyza">
						<option value="0" ></option>
						<option value="1" '.($row['franszyza_rodzaj']==1? 'selected' : '').'>Itegralna</option>
						<option value="2" '.($row['franszyza_rodzaj']==2? 'selected' : '').'>Redukcyjna</option>
					</select>
					<input type="text" size="7" name="franszyza_kwota" id="franszyza_kwota" class="required1" style="text-align:right;" value="'.print_currency($row['franszyza_kwota']).'" > PLN </td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>Nr polisy: </td><td><input type="text" size="40" name="kolo_nr_polisy" id="kolo_nr_polisy" class="required1"  value="'.$row['kolo_nr_polisy'].'" > </td></tr>';							
					$result .= '<tr bgcolor="#AAAAAA" ><td>Nr konta bankowego:</td><td><input type="text" size="50" name="kolo_konto" id="kolo_konto" class="required1" value="'.$row['konto_bankowe'].'"></td></tr>';
					$result .= '<tr bgcolor="#BBBBBB" ><td>Aktywno¶æ:</td><td><input type="checkbox" size="50" name="active" id="active" class="required1" value="1" '.($row['active']==1 ? 'checked' : '').'></td></tr>';
					$result .= '<tr '.$style.'><td align="center" '.$style.'><b>Dostêpna suma ubezpieczenia</b></td><td > '.print_currency($status_sumy_ubezpieczenia['dostepna_suma_ubezpeczenia']).'  PLN</td></tr>';
					$result .= '<tr bgcolor="#CCCCCC"><td>&nbsp;</td><td> <a href="javascript:;" onClick="xopen_history('.$row['ID'].');"><b>Historia wyp³at/spraw</b></a></td></tr>';
					$result .= '<tr bgcolor="#CCCCCC"><td>Notatki <br><br> <a href="#new_note_form" onClick="$(\'new_note\').style.display= \'block\';">Nowa notatka</a></td><td> '.wysw_notatki($row['ID']).'</td></tr>';
			$result .= '</table><br><br>';						
				$result .= '<input type="submit" value="Zapisz">';
		$result .= '</div></form>';	
		
		$result .= '<form method="POST"><div align="center">';			
		$result .= '<input type="submit" value="Anuluj">';
		$result .= '</div></form>';	
		return $result;				
}

function wysw_notatki($id){
	$query = "SELECT * FROM coris_allianz_kola_notatki  WHERE ID_kolo='$id' AND active=1 ORDER BY ID desc ";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result) > 0 ){
		$result = '<div style="background-color:#CCCCCC;width:400px;height:300px;overflow:auto;border:#000000 1px solid;padding:5px;">';		
		while ($row = mysql_fetch_array($mysql_result)){
			$txt = $row['text'];
			$date = $row['date'];
			$user = Application::getUserName($row['ID_user']);
			
			$result .= '<div style="width:385px;border-bottom:#888888 3px solid;margin-bottom:15px;">';
			$result .= '<div style="background-color:#DDDDDD;border-bottom:#BBBBBB 1px solid"><i>'.$date.' '.$user.'</i></div>';
			$result .= '<div style="padding:10px;background-color:#EEEEEE;"> '.nl2br($txt).'</div>';
			$result .= '</div>';
		}
		
		$result .= '</div>';
	}
	
	$result .= '<a name="new_note_form"></a><div id="new_note" style="background-color:#52DCF2;width:400px;border:#000000 1px solid;padding:5px;display:none;" >';
		$result .= 'Nowa notatka:<br><textarea style="width:380px;height:100px;" name="new_note"> </textarea>';
	$result .= '</div>';
	return $result ;
	
}

function showList(){

echo '<form method="post" id="form_kolo_lista"><input type="hidden" name="action"  value="add_form">
<input type="submit" value="Dodaj Nowe">
</form>';
?>
<table width="95%" align="center">
<tr><td>
  <?php 
  
  $id = getValue('id');
  
  $query = "SELECT coris_allianz_kola.* ,coris_allianz_ubezpieczenia.kolo_nr_polisy,
  coris_allianz_ubezpieczenia.suma_ubezpieczenia,
  coris_allianz_ubezpieczenia.franszyza_rodzaj,
  coris_allianz_ubezpieczenia.franszyza_kwota
   FROM coris_allianz_kola LEFT JOIN coris_allianz_ubezpieczenia ON  coris_allianz_kola.ID=coris_allianz_ubezpieczenia.ID_kolo AND coris_allianz_ubezpieczenia.ID_umowa=1 
  ORDER BY ID ";   	  	
  $mysql_result = mysql_query($query);
  

  $i = 0;
 ?>
<table border="0" cellpadding="1" cellspacing="1"  align="left">
<tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="80">ID</th>
    <th width="150">Nazwa</th>
    <th >Adres</th>
    <th >Kod</th>
    <th >Miejscowo¶æ</th>
    <th >ZO</th>
    <th >Konto Bankowe</th>
    <th >Nr polisy</th>
    <th >Suma ubezpieczenia</th>
    <th >Franszyza</th>
    <th >Dostêpna suma ubezpieczenia</th>
    <th >Aktywno¶æ</th>
  </tr>
 <?php
  while ($row = mysql_fetch_array($mysql_result)){ 
  		$status_sumy_ubezpieczenia = AllianzCase::getKoloDostepnaSumaUbezpieczenia($row['ID']);

  		$style = '';
  		
  		if ($status_sumy_ubezpieczenia['status'] == 'warning' )
  			$style = 'style="background-color:#FFFF00"';
		
  		if ($status_sumy_ubezpieczenia['status'] == 'error' )
  			$style = 'style="background-color:#FF0000"';
  		?>
  <tr <?php echo ($row['active']==0 ? 'style="text-decoration:line-through;"' : ''); echo $style ;?> bgcolor="<?PHP  echo ($i++ % 2) ? "#FFFFFF" : "#EEEEEE" ?>">
    <td nowrap><input type="button" value="&gt;" style="width: 20px" onClick="edit(<?php echo $row['ID']; ?>);"></td>        
    <td nowrap align="center"><span class="style4"><?php echo $row['ID'] ?></span> </td>
    <td align="center" nowrap><?php echo $row['nazwa']; ?></td>
    <td align="center" nowrap><?php echo $row['adres']; ?></td>
    <td align="center" nowrap><?php echo $row['kod']; ?></td>
    <td align="center" nowrap><?php echo $row['miejscowosc']; ?></td>
    <td align="center" nowrap><?php echo $row['ZO']; ?></td>
    <td align="center" nowrap><?php echo $row['konto_bankowe']; ?></td>
    <td align="center" nowrap><?php echo $row['kolo_nr_polisy']; ?></td>
    <td align="center" nowrap><?php echo print_currency($row['suma_ubezpieczenia'],2,' '); ?></td>
    <td align="center" nowrap><?php echo ($row['franszyza_rodzaj']==1 ? 'Integralna' :'' ).($row['franszyza_rodzaj']==2 ? 'Redukcyjna' :'' ); ?> <?php echo print_currency($row['franszyza_kwota']); ?></td>
    <td align="center" nowrap> <?php echo print_currency($status_sumy_ubezpieczenia['dostepna_suma_ubezpeczenia'],2,' ');  ?>  PLN</td>
    <td align="center" nowrap><?php echo ($row['active'] == '1' ? 'TAK' : 'NIE'); ?></td>                    
  </tr>
  <?php } 

?>
</table>
<?php
 
?>
</td></tr>
</table>
<?php

}

html_stop2();

?>