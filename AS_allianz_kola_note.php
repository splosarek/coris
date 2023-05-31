<?php 
require_once('include/include.php'); 
include_once('include/strona.php'); 
include_once('include/send_list.inc.php');

html_start();

$kolo_id = intval(getValue('id'));



if ($kolo_id>0){
	echo viewKolo($kolo_id);
}
		
function viewKolo($kolo_id){
	
		$result = '';
		
	 $query = "SELECT coris_allianz_kola.* ,coris_allianz_ubezpieczenia.kolo_nr_polisy,
  coris_allianz_ubezpieczenia.suma_ubezpieczenia,
  coris_allianz_ubezpieczenia.franszyza_rodzaj,
  coris_allianz_ubezpieczenia.franszyza_kwota
   FROM coris_allianz_kola LEFT JOIN coris_allianz_ubezpieczenia ON  coris_allianz_kola.ID=coris_allianz_ubezpieczenia.ID_kolo AND coris_allianz_ubezpieczenia.ID_umowa=1
   WHERE  coris_allianz_kola.ID='$kolo_id'";   	  	
  	$mysql_result = mysql_query($query);
  	if (mysql_num_rows($mysql_result) == 0) return 'BRAK KO£A ID:'.$kolo_id;
  	$row = mysql_fetch_array($mysql_result);
  	
  		 $result .= '<div align="center"><b>Notatki <br> '.$row['nazwa'].' ('.$kolo_id.')</b></div> ';

 
		$result .= wysw_notatki($kolo_id);

		return $result;
}



function wysw_notatki($id){
	$query = "SELECT * FROM coris_allianz_kola_notatki  WHERE ID_kolo='$id' AND active=1 ORDER BY ID desc ";
	$mysql_result = mysql_query($query);
	if (mysql_num_rows($mysql_result) > 0 ){
		$result = '<br><br><div style="background-color:#CCCCCC;width:600px;height:auto;overflow:auto;border:#000000 1px solid;padding:5px;">';		
		while ($row = mysql_fetch_array($mysql_result)){
			$txt = $row['text'];
			$date = $row['date'];
			$user = Application::getUserName($row['ID_user']);
			
			$result .= '<div style="width:95%;border-bottom:#888888 3px solid;margin-bottom:15px;">';
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

html_stop2();

?>