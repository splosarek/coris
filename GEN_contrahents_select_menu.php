<?php require_once('include/include.php'); 

$query_countries = "SELECT country_id, name FROM coris_countries WHERE active = 1 ORDER BY name ASC";
$countries = mysql_query($query_countries) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
$totalRows_countries = mysql_num_rows($countries);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="document.getElementById('name').focus()">

<form name="form1" method="get" action="GEN_contrahents_select_frame.php" target="contrahents_select_frame">
	<input type="hidden" name="fax" value="<?php echo $_GET['fax'];?>">
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="popupTitle"><?= GEN_COUN_WYK ?></td>
		</tr>
	</table>
	<table width="100%"  border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td class="frame"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><div align="right"><strong><?= GEN_COUN_NUM ?></strong>&nbsp;</div></td>
						<td><input name="contrahent_id" type="text" id="contrahent_id" size="3" style="background: yellow"></td>
						<td><div align="right"><?= FULLNAME ?></div></td>
						<td ><input id="name" name="name" type="text" id="name" size="40" maxlength="100"></td>
                        <?php
                        	$branch_id = getValue('branch_id');
                        	if ($branch_id > 0 ){                        		
                        			echo '<input type="hidden" name="branch_id" id="branch_id" value="'.$branch_id.'">';	
                        	}else{
	                            if (isset($_SESSION['coris_branch']) && 1 == $_SESSION['coris_branch'])
	                            {
	                               echo '<td><div align="right">' . BRANCH . '</div></td>
	                                     <td> ';
	                                echo print_user_coris_branch('coris_contrahents.coris_branch_id', 1, 'onChange="form1.submit()"');
	                            }else
	                            {
	                                echo '<td></td><td>';
	                            }
                        	}
                        ?>
                        </td>
					</tr>
					<tr>
						<td><div align="right"><?= POST ?>&nbsp;</div></td>
						<td><input name="post" type="text" id="post" size="6" maxlength="6"></td>
						<td><div align="right"><?= GEN_COUN_MIA ?></div></td>
						<td colspan="3" nowrap><input name="city" type="text" id="city" size="20" maxlength="50">
						&nbsp;<?= COUNTRY ?>&nbsp;&nbsp;
                            <?php echo Application :: countryList('', $_SESSION['GUI_language'], 'country_id'); ?>
						</td>
					</tr>
			</table></td>
		</tr>
	</table>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><div align="center">
					<input type="submit" value="<?= SEARCH ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" >
			</div></td>
		</tr>
	</table>
</form>
</body>
</html>
<?php
mysql_free_result($countries);
?>