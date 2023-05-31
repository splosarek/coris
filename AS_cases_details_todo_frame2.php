<?php include('include/include.php'); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<html>
	<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
    </script>
<body leftmargin="10" rightmargin="0">
		<style>
			body {
				background: #dfdfdf;
				font-family: Verdana;
				font-size: 8pt;
				margin-bottom: 0.1cm;
				margin-left: 0.1cm;
				margin-right: 0.1cm;
			}
			td {
				font-size: 8pt;
			}
            tr.complete {
                color: #999999;
            }
		</style>
		<script language="javascript">
		<!--
			function clearToDo(s) {
				var url = "AS_cases_details_todo_frame2.php?checked=1&id="+s.value+"<?= (isset($_GET['case_id'])) ? "&case_id=$_GET[case_id]" : "" ?>";
				location.href = url;
			}
			function checkToDo(s) {
				var url = "AS_cases_details_todo_frame2.php?checked=0&id="+s.value+"<?= (isset($_GET['case_id'])) ? "&case_id=$_GET[case_id]" : "" ?>";
				location.href = url;
			}			
			setTimeout("document.location = 'AS_cases_details_todo_frame2.php<?= (isset($_GET['case_id'])) ? "?case_id=$_GET[case_id]" : "" ?>'", 60000);
		//-->
		</script>
        <center>
		<table width="100%" cellpadding="2" cellspacing="0" border="0">
            <tr>
              <th width="3%" align="center" nowrap style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">&nbsp;</th>
                <td width="6%" align="center" style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">
                    <img src="img/Ptaszek.gif" border="0">
                </td>
                <td colspan="2" width="40%" align="center" style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">
                    <font color="#6699cc"><small><?= AS_CASD_ZAD2 ?></small></font>
                </td>
                <td colspan="2" width="27%" align="center" style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">
                    <font color="#6699cc"><small><?= DUE2 ?></small></font>
                </td>
                <td colspan="2" width="27%" align="center" style="border-right: #dfdfdf 1px solid; border-bottom: #6699cc 1px solid;">
                    <font color="#6699cc"><small><?= AS_CASD_ZAKON ?></small></font>
                </td>
            </tr>
<?

if (isset($_GET['id'])) {
	if ($_GET['checked'] == 1) {
    	$query = "UPDATE coris_assistance_cases_todos SET complete = 1, date_complete = NOW(), user_id_complete = '$_SESSION[user_id]' where todo_id = $_GET[id]";
	} else {
	    $query = "UPDATE coris_assistance_cases_todos SET complete = 0, date_complete = NULL, user_id_complete = 0 where todo_id = $_GET[id]";	
	}
	if (!$result = mysql_query($query, $cn))
		die(mysql_error());
}
if (isset($_GET['case_id'])) {
    $query  = "SELECT acd.todo_id, acd.important, acd.value, DATE_FORMAT(acd.date_due, '%Y-%m-%d') AS date_due, DATE_FORMAT(acd.date_due, '%H:%i') AS time_due, DATE_FORMAT(acd.date_complete, '%Y-%m-%d') AS date_complete, DATE_FORMAT(acd.date_complete, '%H:%i') AS time_complete, acd.complete, u.name, u.surname, u2.name AS name_complete, u2.surname AS surname_complete FROM coris_users u, coris_assistance_cases_todos acd LEFT JOIN coris_users u2 ON (u2.user_id = acd.user_id_complete) where u.user_id = acd.user_id AND case_id = '$_GET[case_id]' AND acd.active = 1 ORDER BY complete, acd.date_due, important DESC";

    if ($result = mysql_query($query, $cn)) {
        while ($row = mysql_fetch_array($result)) {
?>
			<tr <?= ($row['complete'] == 1) ? "style=\"text-decoration: line-through; color: #bbbbbb\"" : "" ?> <?= ($row['important'] && !$row['complete']) ? "bgcolor=\"orange\"" : "" ?>>
			  <td align="center" valign="middle" style="border-right: #6699cc 1px solid;"><input type="button" style="width: 20px" onClick="MM_openBrWindow('AS_cases_details_todo_details.php?todo_id=<?php echo $row['todo_id']; ?>','','width=320,height=230,left=350,top=200')" value="&gt;"></td>
				<td width="6%" align="center" style="border-right: #6699cc 1px solid;">
					<input type="checkbox" name="todo" value="<?= $row['todo_id'] ?>" onclick="if (this.checked) clearToDo(this); else checkToDo(this);" <?= ($row['complete'] == 1) ? "checked" : "" ?> style="background: #dfdfdf;">
				</td>
				<td width="30%" align="left" <?= ($row['complete'] == 1) ? "style=\"text-decoration: line-through; color: #bbbbbb\"" : "" ?>>
					<?= $row['value'] ?>
				</td>
                <td width="10%" align="center" style="border-right: #6699cc 1px solid; text-decoration: none">                
                    <img src="img/KwadracikRed.gif"  title="<?= $row['name'] . " " . $row['surname'] ?>">&nbsp;
                   <?php if ($row['complete']){?> 
                   		 <img src="img/KwadracikGreen.gif"  title="<?= $row['name_complete'] . " " . $row['surname_complete'] ?>">&nbsp;
                    <?php }else{ ?>
                    	<img src="img/KwadracikGray.gif"  title="">&nbsp;
                    <?php }?>
                    
                   
                   
                   
                </td>
				<td width="17%" align="center" style="border-right: #6699cc 1px solid;<?= ($row['complete'] == 1) ? "text-decoration: line-through; color: #bbbbbb" : "" ?>"  >
					<?= $row['date_due'] ?>
				</td>
				<td width="10%" align="center" style="border-right: #6699cc 1px solid;<?= ($row['complete'] == 1) ? "text-decoration: line-through; color: #bbbbbb" : "" ?>" >
					<?= $row['time_due'] ?>
				</td>
				<td width="17%" align="center" style="border-right: #6699cc 1px solid;<?= ($row['complete'] == 1) ? "text-decoration: line-through; color: #bbbbbb" : "" ?>" >
					<?php echo ($row['complete']) ? $row['date_complete'] : "&nbsp;" ?>
				</td>
				<td width="10%" align="center"  <?= ($row['complete'] == 1) ? "style=\"text-decoration: line-through; color: #bbbbbb\"" : "" ?>>
					<?php echo ($row['complete']) ? $row['time_complete'] : "&nbsp;" ?>
				</td>
			</tr>
<?
        }
    } else {
        die(mysql_error());
    }
  
}
?>
		</table>
        </center>
	</body>
</html>
<?php
	mysql_free_result($result);
?>