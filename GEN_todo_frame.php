<?php include('include/include.php'); 
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <title><?= GEN_TODO_FRAMETITLE ?></title>
        <link href="Styles/general.css" rel="stylesheet" type="text/css">
	<body bgcolor="#eeeeee" onload="focus(); <?= (isset($_GET['offset'])) ? "document.body.scrollTop = '$_GET[offset]'" : "" ?>">
		<style>
			body {
				margin-top: 0.1cm;
				margin-bottom: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
			}
			td {
				font-size: 7pt;
			}
		</style>
		<script language="JavaScript1.2">
		<!--
			function SubmitEntry(s) {
				v = 'note' + s;
				var url = 'GEN_todo_frame.php?comment='+ s +'&value='+ document.getElementById(v).value +'&offset='+ document.body.scrollTop +'&statuses_id=<? echo $_GET['statuses_id']; ?>';
				document.location = url;
			}
		//-->
		</script>
<?

if (isset($_GET['action'])) {	
	$query = "INSERT INTO coris_todos (ref_id, value, user_id, date,ID_category,ID_label,ID_statuses   ) VALUES (0, '".$_POST['todo']."', '".$_SESSION['user_id']."', NOW(),'".$_POST['category_id']."','".$_POST['label_id']."',1)";
	if (mysql_query($query)) {		
			echo "<script>parent.form1.todo.value='';</script>";
	} else {
		die(mysql_error());
	}
}
if (isset($_GET['comment'])) {
	if (trim($_GET['value']) <> ''){
		$query = "INSERT INTO coris_todos (ref_id, value, user_id, date) VALUES ('".$_GET['comment']."', '".$_GET['value']."', '".$_SESSION['user_id']."', NOW())";
		mysql_query($query) or die(mysql_error());
			
	}
}

if (isset($_GET['remove'])) {
	$query = "UPDATE coris_todos SET active = 0 WHERE ID = '".$_GET['remove']."'";
	if (!mysql_query($query)) {
		die(mysql_error());
	}	
}
if (isset($_GET['completed'])) {
	if ($_GET['statuses_id']==1)
			$query = "UPDATE coris_todos SET completed = 0, ID_statuses=3 WHERE ID = '".$_GET['completed']."'";
	else if ($_GET['statuses_id']==3)
		$query = "UPDATE coris_todos SET completed = 1, ID_statuses=2 WHERE ID = '".$_GET['completed']."'";
	if (!mysql_query($query)) {
		die(mysql_error());
	}	
}
if (isset($_GET['incompleted'])) {
	
	if ($_GET['statuses_id']==3)	
		$query = "UPDATE coris_todos SET completed = 0, ID_statuses=1 WHERE ID = '".$_GET['incompleted']."'";
	else if ($_GET['statuses_id']==2)
		$query = "UPDATE coris_todos SET completed = 0, ID_statuses=3 WHERE ID = '".$_GET['incompleted']."'";
		
	if (!mysql_query($query)) {
		die(mysql_error());
	}	
}

$varunek = (isset($_GET['statuses_id'])) ? 'ID_statuses='.$_GET['statuses_id'] : 'ID_statuses=1';

$query = "SELECT t.ID, t.ref_id, t.value, t.user_id,DATE_FORMAT(t.date,'%Y-%m-%d') As date, u.name,u.surname,t.ID_statuses,t.ID_label,ts.value As status,tl.value As label, tc.value As  category   FROM coris_todos t, coris_users u, coris_todos_statuses ts, coris_todos_labels tl, coris_todos_categories tc   WHERE u.user_id = t.user_id AND t.active = 1 AND ref_id=0 AND tl.ID=t.ID_label AND ts.ID=t.ID_statuses AND tc.ID=t.ID_category AND $varunek ORDER BY t.ID_label ASC, t.ID_statuses, t.ID DESC";
$result = mysql_query($query) or die(mysql_error());
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">";
	$i = 0;
	while ($row = mysql_fetch_array($result)) {	
			$i++;
			$j = 0;
?>
<tr bgcolor="<?= (($i % 2) || $row['ID_statuses']==2) ? "#e0e0e0" : "#eaeaea" ?>" height="23">	
	<td width="5%" align="right"><font color="#6699cc"><?= $i ?></font></td>
	<td width="5%" align="center"><input type="checkbox" <?= ($row['ID_statuses']==2) ? "checked" : "" ?> name="todo_<?= $row['ID'] ?>" <?= ($_SESSION['department_id'] == 9) ? "onclick=\"if (this.checked) document.location='GEN_todo_frame.php?completed=". $row['ID'] ."&offset='+ document.body.scrollTop+'&statuses_id=".$_GET['statuses_id']."'; else document.location='GEN_todo_frame.php?incompleted=". $row['ID'] ."&offset='+ document.body.scrollTop + '&statuses_id=".$_GET['statuses_id']."';\"" : "disabled" ?>></td>
	<td width="4%" align="center"><?= ($_SESSION['user_id'] == $row['user_id'] || $_SESSION['department_id'] == 9 ) ? "<input title=\"Usuń zgłoszenie\" type=\"button\" style=\"font-weight: bold; height: 14pt; line-height: 6pt; width: 15px;\" value=\"x\" onclick=\"if (confirm('Czy na pewno chcesz usunąć wpis?')) document.location='GEN_todo_frame.php?remove=". $row['ID'] ."'\" >" : "&nbsp;" ?></td>
	<td width="6%" align="center">
		<? if (($row['ID_statuses']==1 || $row['ID_statuses']==3)  ) { ?>
			<input title="<?= BUTT_ADDCOMM ?>"  type="button" style="color: #6699cc; font-family: Webdings; font-size: 12pt; font-weight: bold; height: 14pt; line-height: 6pt; width: 23px;" value=")" onclick="if (comment<?= $row['ID'] ?>.style.display == 'none') { comment<?= $row['ID'] ?>.style.display = ''; note<?= $row['ID'] ?>.focus(); } else { comment<?= $row['ID'] ?>.style.display = 'none'; }">
		<? } 
	echo '</td>
	<td width="70%">';
		echo  ($row['ID_statuses']==2) ? "<font style=\"color: #bbbbbb; text-decoration: line-through;\">" : "" ;
			if ($row['ID_label']==1) 
				echo '<font color=red><b>'.$row['label'].'</b></font><br> ' ;
			echo '<b>'.$row['category'].'</b><br> ' ;
			echo '<b>'.$row['name'].' '.$row['surname'].':</b> '. $row['value'] ;
		echo ($row['ID_statuses']==2) ? "</font>" : "" ;
		echo '</td><td nowrap>'.$row['date'].'</td>'; ?>
</tr>
<tr valign="middle" bgcolor="<?= ($row['ID_statuses']==2) ? "#cccccc" : "lightyellow" ?>" id="comment<?= $row['ID'] ?>" style="display: none">
	<td width="20%" colspan="4" align="center"></td>
	<td><i><textarea id="note<?= $row['ID'] ?>" rows="3" cols="72" style="font-family: Verdana; font-size: 7pt;"></textarea></i>
	<input title="<?= BUTT_APROV ?>" type="button" style="background: yellow; color: green; font-family: Webdings; height: 14pt; line-height: 6pt; width: 16px;" value="a" onclick="SubmitEntry(<?= $row['ID'] ?>);"></td>
</tr>
<?
 	lista_komentarzy($row['ID'],$j,$row);
		 
	}
	mysql_free_result($result);
	echo "</table>";

	


function lista_komentarzy($id,$j,$r){
$query = "SELECT t.ID, t.ref_id, t.value, t.user_id, DATE_FORMAT(t.date,'%Y-%m-%d') As date,u.name, u.surname FROM coris_todos t, coris_users u WHERE ref_id='$id' AND u.user_id = t.user_id AND t.active = 1   ORDER BY t.completed, t.ID";
//echo $query;
$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {
		echo '<tr bgcolor="';
				echo ($r['ID_statuses']==2) ? "#cccccc" : "lightyellow";
				echo '"><td width="20%" colspan="4" align="center">&nbsp;</td>
						<td width="80%"><i>';
				echo  (($j % 2) || $r['ID_statuses']==2) ? "<font color=\"#888888\">" : "<font color=\"#555555\">" ;
				echo  $row['name'].' '.$row['surname'].': '.  $row['value'] ;
				echo  ($j % 2) ? "</font>" : "" .'</i></td><td nowrap>'.$row['date'].'</td>
		</tr>';
	
	}
}

?>
	</body>
</html>