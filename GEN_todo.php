<?php include('include/include.php'); 
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
        <title><?= GEN_TODO_TITLE ?></title>
        <link href="Styles/general.css" rel="stylesheet" type="text/css">
	<body bgcolor="#dfdfdf" onload="focus()">
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
		<table cellpadding="2" cellspacing="2" width="100%">
		<form name="form1" action="GEN_todo_frame.php?action=1" target="todos" method="post" onsubmit="return validate(this);">
			<tr>
				<td align="right" style="background: #eeeeee; border: #6699cc 1px solid;"><small><font color="#6699cc"><?= BUTT_TODO ?></font></small></td>
			</tr><tr>
				<td align="left">
<table border="0">
  <tr>
    <td><?= GEN_TODO_DOT ?>: <?php echo lista_kategorii('category_id'); ?> </td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?= GEN_TODO_PRI ?>: <?php echo lista_prirytet('label_id'); ?></td>
  </tr>
</table></td>
			<tr>
				<td align="left">
					<textarea name="todo" cols="85" rows="5" style="font-family: Verdana; font-size: 8pt;" wrap="virtual"></textarea>
				</td>
			</tr>
			<tr>
				<td align="center">
					<input type="submit" value="<?= BUTT_ADD ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= GEN_TODO_DOZ ?>">
				</td>
			</tr>
<tr>
				<td ><?= GEN_TODO_WYS ?>: <input name="radiobutton" type="radio" value="1" style="background: #dfdfdf" onClick="ustaw_filtr('statuses_id=1')" checked ><?= GEN_TODO_OTW ?> |
			<input name="radiobutton" type="radio" value="3" style="background: #dfdfdf" onClick="ustaw_filtr('statuses_id=3')"><?= GEN_TODO_WTR ?>|
<input name="radiobutton" type="radio" value="2" style="background: #dfdfdf" onClick="ustaw_filtr('statuses_id=2')"><?= GEN_TODO_ZAM ?> </td>
			</tr>
			<tr>
				<td>
					<iframe name="todos" src="GEN_todo_frame.php?statuses_id=1" width="100%" height="350"></iframe>
				</td>
			</tr>
		</form>
		</table>
		<script>form1.todo.focus();
		function ustaw_filtr(filtr){
				document.todos.location= 'GEN_todo_frame.php?' + filtr
		}
	</script>
	</body>
</html>
<?php
	function lista_kategorii($name){
		$query = "SELECT * FROM coris_todos_categories ";
		$mysql_result = mysql_query($query);

		$result = '<select name="'.$name.'">';
		while ($row=mysql_fetch_array($mysql_result)){
			$result .= '<option value="'.$row['ID'].'">'.$row['value'].'</option>';
		}
	    
    $result .= '</select>';
       
    return $result;
	}
	

	function lista_prirytet($name){
		$query = "SELECT * FROM coris_todos_labels  ORDER  BY ID desc ";
		$mysql_result = mysql_query($query);

		$result = '<select name="'.$name.'">';
		while ($row=mysql_fetch_array($mysql_result)){
			$result .= '<option value="'.$row['ID'].'">'.$row['value'].'</option>';
		}
	    
    $result .= '</select>';
       
    return $result;
	}	
 	
?>