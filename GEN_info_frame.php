<?php include('include/include.php');

include('include/contrahent_monior.php');



         header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0

if (empty($_SESSION['session_id'])){
		//header("Location: index.php?session_error=1");
		echo "<script language='javascript'>
		<!--
			parent.document.location='index.php?session_error=1';
		//-->
		</script>";
		exit;
	}

html_start('Info','onload="setTimeout(\'document.location.reload();\',60000)" style="background: #ffffff"');

if ( $_SESSION['coris_branch'] == 1){
	$q = "SELECT * FROM info_status WHERE ID=1";
}else if ( $_SESSION['coris_branch'] == 2){
	$q = "SELECT * FROM info_status WHERE ID=2";
}else{

}

$coris_branch = $_SESSION['coris_branch'];
$user_id = $_SESSION['user_id'];

session_write_close();


//$q = "SELECT * FROM info_status WHERE ID=1";
$mr = mysql_query($q);
$rr = mysql_fetch_array($mr);

/*
 if ($totalRows_info > 0) {
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <form name="form1" onsubmit="return false;">
              <?php do { ?>
              <tr height="20">
                  <td width="6%" align="center" style="border-bottom: #cccccc 1px solid;"><?= ($row_info['user_id'] == $_SESSION['user_id']) ? "<input type=\"checkbox\" name=\"info\" value=\"$row_info[info_id]\">" : "&nbsp;" ?></td>
					  <td width="1%" bgcolor="#FFFF00" style="border-bottom: #cccccc 1px solid;">&nbsp;</td>
                  <td width="11%" align="center" nowrap style="border-bottom: #cccccc 1px solid;"><font color="#000099"><?php echo $row_info['date']; ?><br><small><?php echo $row_info['time']; ?></small></font></td>
                  <td width="68%" style="border-bottom: #cccccc 1px solid;"><small><font color="#6699cc"><?php echo preg_replace("/\n/", "<br>", $row_info['value']); ?></font></small></td>
                  <td width="14%" align="center" nowrap style="border-bottom: #cccccc 1px solid;" title="<?= "$row_info[name] $row_info[surname]" ?>"><font color="#999999"><?php echo $row_info['name']; ?>
                 </font></td>
              </tr>
              <?php } while ($row_info = mysql_fetch_assoc($info)); ?>
            </form>
			</table>
			<?php }
	*/
echo '<table width="1000" border="0" cellpadding="2" cellspacing="0">';

$dzis = date('Y-m-d');
$wczoraj = date('Y-m-d',mktime(0,0,0,date("m"),date("d") - 1,date("Y") ));

$ilosc=$rr['fax_in_1'];

	$ilosc2=$rr['fax_in_2'];
	$ilosc3=$rr['fax_in_3'];
	$ilosc3a=$rr['fax_in_3a'];
	$ilosc4=$rr['fax_in_4'];
	$ilosc4a=$rr['fax_in_4a'];

	echo '<tr><td><b>'.FAXSES.'</b>: '.INFO_FR_RECSENDTOD.' ('.$ilosc3.'/'.$ilosc4.'), '.AS_CASD_WCZ.' ('.$ilosc3a.'/'.$ilosc4a.')&nbsp;';
	if ($ilosc>0 || $ilosc2>0){

		if ($ilosc>0){
			echo '<a href="javascript:;" OnClick="window.open(\'DOC_in_sorter.php\', \'DOCIn\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=950,height=750,left=0,top=0\')">'.GEN_FR_NOWE.' ('.$ilosc.'), '.GEN_INFOLAST.' '.$rr['fax_in_1_date'].'</a>&nbsp;&nbsp;&nbsp;';
		}

		if ($ilosc2>0){
			echo '<a href="javascript:;" OnClick="window.open(\'DOC_in_sorter.php\', \'DOCIn\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=950,height=750,left=0,top=0\')">Nie przydzielone ('.$ilosc2.')</a>';
		}
	}

	$ilosc=$rr['email_in_1'];
	$ilosc2=$rr['email_in_2'];

	$ilosc3=$rr['email_in_3'];
	$ilosc3a=$rr['email_in_3a'];
	$ilosc4=$rr['email_in_4'];
	$ilosc4a=$rr['email_in_4a'];

	echo '<tr><td><b>Emaile</b>: '.INFO_FR_RECSENDTOD.' ('.$ilosc3.'/'.$ilosc4.'), '.AS_CASD_WCZ.' ('.$ilosc3a.'/'.$ilosc4a.')&nbsp;&nbsp;';
	if ($ilosc>0 || $ilosc2>0){

		if ($ilosc>0){
			$query = "SELECT date FROM coris_email_in  WHERE new=1 AND assistance=1 ORDER BY date desc LIMIT 1";
			$mysql_result = mysql_query($query);
			$row = mysql_fetch_array($mysql_result);
			echo '<a href="javascript:;" OnClick="window.open(\'DOC_in_sorter.php\', \'DOCIn\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=950,height=750,left=0,top=0\')">'.GEN_FR_NOWE.' ('.$ilosc.'),  '.GEN_INFOLAST.' '.$row['email_in_1_date'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
		}

		if ($ilosc2>0){
			echo '<a href="javascript:;" OnClick="window.open(\'DOC_in_sorter.php\', \'FaxIn\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=950,height=750,left=0,top=0\')">Nie przydzielone ('.$ilosc2.')</a>&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		echo '</td><td></td></tr>';

	}

$ilosc1=$rr['dok_1'];


	$ilosc2=$rr['dok_2'];
	if ($ilosc1>0){
		//echo '<tr><td><a href="AS_cases.php?new_documents=1" target="main">Spraw z nowymi dokumentami / nowych dokumentów: ('.$ilosc1.'/'.$ilosc2.')</a></td><td></td></tr>';
	}

	$ilosc3=$rr['dok_3'];


	$ilosc4=$rr['dok_4'];


$ilosc7=$rr['dok_7'];
$ilosc8=$rr['dok_8'];

	echo '<tr><td><b>'.GEN_INFO_CASEDOC.'</b> : '.GEN_INFO_ATTTOD.' (<b>'.$ilosc3.'</b>/'.$ilosc4.'),  '.AS_CASD_WCZ.'  (<b>'.$ilosc7.'</b>/'.$ilosc8.')';

//	echo '<tr><td><b> Sprawy</b> / dokumenty:&nbsp;';
/*
	if ($ilosc3>0){
		echo '<a href="AS_cases.php?new_documents=1" target="main"> do obrobienia (<b>'.$ilosc1.'</b>/'.$ilosc2.')</a>';

	}
*/
	echo '</td><td>';


if ( in_array(Application::getCurrentUser(), $_superUsers)) {

    $query = "SELECT count(*) FROM coris_contrahents_check ";
    $mr = mysql_query($query);
    $row = mysql_fetch_array($mr);
    $ilosc_kolejka1 = $row[0];

    $query2 = "SELECT count(*) FROM coris_contrahents_account_check ";
    $mr = mysql_query($query2);
    $row = mysql_fetch_array($mr);
    $ilosc_kolejka2 = $row[0];

    if ($ilosc_kolejka1 > 0 || $ilosc_kolejka2 > 0) {
        echo 'Dane kontrahentów do zatwierdzenia <a href="javascript:;" OnClick="window.open(\'GEN_contrahents_queue_list.php\', \'Contrahents data\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=720,height=700,left=0,top=0\')">(' . ($ilosc_kolejka1 + $ilosc_kolejka2) . ')</a> ';

    }
}
	echo '</td></tr>';


$ilosc3 = $rr['dok_5']; // $dok_5
$ilosc4 = $rr['dok_6']; // $dok_6
$id_todos = $user_id>0 ? $user_id : "1";

//$query_todos = "SELECT act.todo_id, act.case_id, act.important, act.value, CONCAT_WS('/', ac.number, ac.`year`, ac.type_id, ac.client_id) AS case_no, act2u.user_id FROM coris_assistance_cases_todos act, coris_assistance_cases ac, coris_assistance_cases_todos2users act2u WHERE act.todo_id = act2u.todo_id AND act2u.user_id = '$id_todos' AND act.case_id = ac.case_id AND act.date_due <= NOW() AND act.complete = 0 AND act.active = 1";
$query_todos_past = "SELECT act.todo_id FROM coris_assistance_cases_todos act,  coris_assistance_cases_todos2users act2u
		WHERE act.todo_id = act2u.todo_id AND act2u.user_id = '$id_todos' AND act.date_due <= NOW() AND act.complete = 0 AND act.active = 1";

$query_todos_fure = "SELECT act.todo_id FROM coris_assistance_cases_todos act,  coris_assistance_cases_todos2users act2u WHERE act.todo_id = act2u.todo_id AND act2u.user_id = '$id_todos' AND act.date_due > NOW() AND act.complete = 0 AND act.active = 1";

$todos_past = mysql_query($query_todos_past) or die(mysql_error());
$todos_future = mysql_query($query_todos_fure) or die(mysql_error());

$ilosc_past = mysql_num_rows($todos_past);
$ilosc_future =  mysql_num_rows($todos_future);

$query = "SELECT count(*) FROM coris_assistance_cases_alerts,  coris_assistance_cases ac
				WHERE coris_assistance_cases_alerts.case_id = ac.case_id  AND ac.coris_branch_id = '".$coris_branch."'
			AND coris_assistance_cases_alerts.new='1' ";
$mr = mysql_query($query);
$rr = mysql_fetch_array($mr);
$ilosc_nowych_alertow = $rr[0];

$query = "SELECT count(*) FROM store_interaction,coris_assistance_cases  WHERE
			store_interaction.new='1' AND store_interaction.ID_document_type=2
			AND store_interaction.external=1
			AND coris_assistance_cases.case_id = store_interaction.ID_case
			AND coris_assistance_cases.coris_branch_id='". $coris_branch ."'
			";
$mr = mysql_query($query);
$rr = mysql_fetch_array($mr);
$ilosc_nowych_notatek_zewnetrznych = $rr[0];

echo '<tr><td>';
	if ($ilosc_nowych_alertow > 0 )
		echo "<b>Alerty:</b> nowe <a href=\"javascript:;\" onClick=\"open_alerts();\">($ilosc_nowych_alertow)</a>&nbsp;&nbsp;&nbsp;&nbsp;";

	if ($ilosc_nowych_notatek_zewnetrznych > 0 )
		echo "<b>Notatki zewnêtrzne:</b> nowe <a href=\"AS_cases.php?new_ext_note=1\" target=\"main\">($ilosc_nowych_notatek_zewnetrznych)</a>&nbsp;&nbsp;&nbsp;";

///
$query_todos_past = "SELECT act.todo_id FROM coris_assistance_cases_todos act, coris_assistance_cases cac
			WHERE cac.case_id = act.case_id AND (cac.coris_branch_id=2 || cac.coris_branch_id=3 )
			AND act.date_due <= NOW() AND act.complete = 0 AND act.active = 1";
$query_todos_fure = "SELECT act.todo_id FROM coris_assistance_cases_todos act, coris_assistance_cases cac
			WHERE cac.case_id = act.case_id AND (cac.coris_branch_id=2 || cac.coris_branch_id=3)
		AND act.date_due > NOW() AND act.complete = 0 AND act.active = 1";

$todos_past = mysql_query($query_todos_past) or die(mysql_error());
$todos_future = mysql_query($query_todos_fure) or die(mysql_error());

$de_ilosc_past = mysql_num_rows($todos_past);
$de_ilosc_future =  mysql_num_rows($todos_future);
	if ($de_ilosc_past>0 || $de_ilosc_future>0)	{
		echo '&nbsp&nbsp&nbsp<b>'.TASKS.' '.CORIS_BRANCH_NAME_2.':</b> '.AS_CASTD_PRZ.' <a href="javascript:;" OnClick="window.open(\'AS_cases_todos_list.php?tryb=future&branch_id=2\', \'TodoS\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=720,height=500,left=0,top=0\')">('.$de_ilosc_future.')</a> '.GEN_FR_ZAL.' <a href="javascript:;" OnClick="window.open(\'AS_cases_todos_list.php?tryb=past&branch_id=2\', \'TodoS\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=720,height=500,left=0,top=0\')">('.$de_ilosc_past.')</a>';
	}

echo '</td><td align="left">';
if ($ilosc_past>0 || $ilosc_future>0)
	echo 	GEN_FR_TWPRZYPWSPR.': '.AS_CASTD_PRZ.' <a href="javascript:;" OnClick="window.open(\'AS_cases_todos_list.php?tryb=future\', \'TodoS\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=720,height=500,left=0,top=0\')">('.$ilosc_future.')</a> '.GEN_FR_ZAL.' <a href="javascript:;" OnClick="window.open(\'AS_cases_todos_list.php?tryb=past\', \'TodoS\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=720,height=500,left=0,top=0\')">('.$ilosc_past.')</a>';




echo "</td>";
echo "</tr>";
echo '</table>';
?>
<script type="text/javascript">
function open_alerts(){
	window.open('AS_cases_alerts_list.php', 'AlertAll', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=630,height=550,left=0,top=0');
	return false;
}
</script>
</body>
</html>