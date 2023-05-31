<?php include('include/include.php'); 

if (!check_admin() ){
	die ('Access denied');
}


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

	$photo_id = 0;
  	if (isset($_FILES['file']['tmp_name']) && $_FILES['file']['size'] != 0) {

		$data = base64_encode(fread(fopen($_FILES['file']['tmp_name'], "r"), $_FILES['file']['size']));

		$insertSQL = sprintf("INSERT INTO coris_photos (value) VALUES (%s)",
				   GetSQLValueString($data, "text")); 
		
		$Result1 = mysql_query($insertSQL) or die(mysql_error());	    	

		
		$query_photo = "SELECT photo_id FROM coris_photos WHERE photo_id = @@IDENTITY";
		$photo = mysql_query($query_photo) or die(mysql_error());
		$row_photo = mysql_fetch_assoc($photo);
		$totalRows_photo = mysql_num_rows($photo);		
		
		if ($totalRows_photo) {
			$photo_id = $row_photo['photo_id'];
		}
		
		$updateSQL = sprintf("UPDATE coris_users SET color_id=%s, username=%s, name=%s, surname=%s, doctor=%s, staff=%s,
                                     initials=%s, ext=%s, department_id=%s, group_id=%s, photo_id=%s, active=%s,
                                     ID_expertness='" . getValue('id_expertness') . "',
                                     ID_position='" . getValue('id_position') . "',
                                     coris_branch_id='" . getValue('coris_branch_id') . "',
                                     new_user='" . (getValue('new_user')==1 ? 1 : 0) . "',
                                     Stats='" . (getValue('Stats')==1 ? 1 : 0) ."'
                                     WHERE user_id=%s",
						   GetSQLValueString($_POST['color_id'], "int"),
						   GetSQLValueString($_POST['username'], "text"),
						 
						   GetSQLValueString($_POST['name'], "text"),
						   GetSQLValueString($_POST['surname'], "text"),
						   GetSQLValueString($_POST['doctor'], "int"),
						   GetSQLValueString($_POST['staff'], "int"),
						   GetSQLValueString($_POST['initials'], "text"),
						   GetSQLValueString($_POST['ext'], "int"),
						   GetSQLValueString($_POST['department_id'], "int"),
						   GetSQLValueString($_POST['group_id'], "int"),
						   GetSQLValueString($photo_id, "text"),					   
						   GetSQLValueString($_POST['active'], "int"),
						   GetSQLValueString($_POST['user_id'], "int"));		
		
	} else {
  
		$updateSQL = sprintf("UPDATE coris_users SET color_id=%s, username=%s, name=%s, surname=%s, doctor=%s, staff=%s,
		                             initials=%s, ext=%s, department_id=%s, group_id=%s, active=%s,
		                             ID_expertness='" . getValue('id_expertness')."',
		                             ID_position='".getValue('id_position')."',
		                             coris_branch_id='" . getValue('coris_branch_id') . "',
		                             new_user='".(getValue('new_user') ==1 ? 1 : 0 )."',
		                             Stats='".(getValue('Stats') ==1 ? 1 : 0 )."'
		                             WHERE user_id=%s",
						   GetSQLValueString($_POST['color_id'], "int"),
						   GetSQLValueString($_POST['username'], "text"),						  
						   GetSQLValueString($_POST['name'], "text"),
						   GetSQLValueString($_POST['surname'], "text"),
						   GetSQLValueString($_POST['doctor'], "int"),
						   GetSQLValueString($_POST['staff'], "int"),
						   GetSQLValueString($_POST['initials'], "text"),
						   GetSQLValueString($_POST['ext'], "int"),
						   GetSQLValueString($_POST['department_id'], "int"),
						   GetSQLValueString($_POST['group_id'], "int"),
						   GetSQLValueString($_POST['active'], "int"),
						   GetSQLValueString($_POST['user_id'], "int"));
	}


  $Result1 = mysql_query($updateSQL) or die(mysql_error());

	if (isset($_POST['language_id'])) {
		$query = sprintf("DELETE FROM coris_users2languages WHERE user_id = %s", 
				GetSQLValueString($_POST['user_id'], "int"));

		if (!$result = mysql_query($query))
			die (mysql_error());
	
		 foreach ($_POST['language_id'] as $language_id) {
			  if ($language_id != "") {
				$query = sprintf("INSERT INTO coris_users2languages (user_id, language_id) VALUES (%s, %s)", 
                        GetSQLValueString($_POST['user_id'], "int"),
						GetSQLValueString($language_id, "int"));
	
				if (!$result = mysql_query($query))
					die (mysql_error());
			  }
		 }
	}  

	
	$change_password_form = getValue('change_password_form');
	$user_id = getValue('user_id');
	if ($change_password_form==1 && $user_id>0){
		$npassword = getValue('npassword');
  		$npassword2  = getValue('npassword2');		
  		$pass_res = UserObject::userUpdatePassword($cn,$user_id,$npassword,true);
	}

  $updateGoTo = "GEN_users_details.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  exit();
}

$colname_users = "1";
if (isset($_GET['user_id'])) {
  $colname_users = getValue('user_id');
}

$query_users = "SELECT * FROM coris_users WHERE user_id = '$colname_users' ";
$users = mysql_query($query_users) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);


$query_colors = "SELECT color_id, name, code FROM coris_colors";
$colors = mysql_query($query_colors) or die(mysql_error());
$row_colors = mysql_fetch_assoc($colors);
$totalRows_colors = mysql_num_rows($colors);

$id_languages = "1";
if (isset($_GET['user_id'])) {
  $id_languages = (get_magic_quotes_gpc()) ? $_GET['user_id'] : addslashes($_GET['user_id']);
}

$query_languages = sprintf("SELECT coris_users_languages.language_id, coris_users_languages.`value`, coris_users2languages.language_id AS language_id_sel FROM coris_users_languages LEFT JOIN coris_users2languages ON coris_users_languages.language_id = coris_users2languages.language_id AND coris_users2languages.user_id = %s ORDER BY coris_users_languages.`value` ASC", $id_languages);
$languages = mysql_query($query_languages) or die(mysql_error());
$row_languages = mysql_fetch_assoc($languages);
$totalRows_languages = mysql_num_rows($languages);


$query_groups = "SELECT group_id, `value` FROM coris_users_groups ORDER BY `value` ASC";
$groups = mysql_query($query_groups) or die(mysql_error());
$row_groups = mysql_fetch_assoc($groups);
$totalRows_groups = mysql_num_rows($groups);


$query_departments = "SELECT department_id, `value` FROM coris_users_departments ORDER BY `value` ASC";
$departments = mysql_query($query_departments) or die(mysql_error());
$row_departments = mysql_fetch_assoc($departments);
$totalRows_departments = mysql_num_rows($departments);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>U�ytkownik</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>	 
</head>

<body>

<script>
	function change_passwd_form_show(){
			$('passwd_form').show();
			$('change_password_form').value=1;
			
	}

	function form_user_check(){
		if ($('change_password_form').value==1){

					npassword = $('npassword').value;
					npassword2 = $('npassword2').value;
					
					if (npassword != npassword2){
							alert('Has�a s� r�ne');
							return false;
					}
					paswd=  /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,}$/;  
					if( !npassword.match(paswd) ){
						alert('Has�o nie spe�nia kryteri�w bezpiecze�stwa');
						return false;	
					}  

					return true; 			
					
		}else{
			return true;
		}
	}
</script>
<form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="return form_user_check();">
	<table width="100%" height="370"  border="0" cellpadding="1" cellspacing="1">
    	<tr>
    		<td colspan="2" class="popupTitle"><?= USER ?>&nbsp;</td>
   		</tr>
    	<tr>
    		<td width="385" valign="top">
				<table width="100%" align="center">
<tr>
                    	<td nowrap align="right"><?= NAME ?></td>
                    	<td><input type="text" name="name" value="<?php echo $row_users['name']; ?>" size="32"></td>
           			</tr>
            		<tr>
                    	<td nowrap align="right"><?= SURNAME ?></td>
                    	<td><input type="text" name="surname" value="<?php echo $row_users['surname']; ?>" size="32"></td>
           			</tr>
            		<tr>
            		<td nowrap align="right"><?= GEN_USER_LOG ?></td>
            		<td><input type="text" name="username" value="<?php echo $row_users['username']; ?>" size="32"></td>
            		</tr>
            	<tr>
            		<td colspan="2"><div ><a href="javascript:;" onClick="change_passwd_form_show()">zmie� has�o</a></div>
            		<input type="hidden" name="change_password_form"  id="change_password_form" value="0">
            		<div id="passwd_form" style="display:none;">
            			<table width="100%" bgcolor="#EEEEEE">
            			<tr>
							<td align="right">
								<font color="#6699CC"><?= NEWPASSWORD ?>:</font>
							</td>
							<td>
								<input type="password" name="npassword" id="npassword" size="16" maxlength="25" style="background: #FFFFFF">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC"><?= NEWPASSWORD2 ?>:</font>
							</td>
							<td>
								<input type="password" name="npassword2"  id="npassword2" size="16" maxlength="25" style="background: #FFFFFF">
							</td>
						</tr>
						<tr>
							<td align="center" colspan="2">
								Has�o powinno zawiera� du�e i ma�e litery, cyfry oraz znaki specjalne. D�ugo�� has�a minimum 8 znak�w
							</td>
						</tr>
            			</table>
            			<br>
            		</div>
            		
            		</td>
            		</tr>
            	<tr>
                	<td nowrap align="right"><?= GEN_USER_KOL ?></td>
                	<td><select name="color_id">
                		<option value="0" <?php if (!(strcmp(0, $row_users['color_id']))) {echo "SELECTED";} ?>></option>
                		<?php
do {  
?>
                		<option style="background: <?php echo $row_colors['code']; ?>" value="<?php echo $row_colors['color_id']?>"<?php if (!(strcmp($row_colors['color_id'], $row_users['color_id']))) {echo "SELECTED";} ?>><?php echo $row_colors['name']?></option>
                			<?php
} while ($row_colors = mysql_fetch_assoc($colors));
  $rows = mysql_num_rows($colors);
  if($rows > 0) {
      mysql_data_seek($colors, 0);
	  $row_colors = mysql_fetch_assoc($colors);
  }
?>
                			</select>
                		</td>                
            	<tr>
            		<td nowrap align="right"><?= GEN_USER_LEK ?></td>
            		<td><select name="doctor">
            				<option value="1" <?php if (!(strcmp(1, $row_users['doctor']))) {echo "SELECTED";} ?>><?= YES ?></option>
            				<option value="0" <?php if (!(strcmp(0, $row_users['doctor']))) {echo "SELECTED";} ?>><?= NO ?></option>
            				</select>
            			</td>
            		</tr>
            	<tr>
            		<td nowrap align="right"><?= GEN_USER_GRA ?></td>
            		<td><select name="staff">
            				<option value="1" <?php if (!(strcmp(1, $row_users['staff']))) {echo "SELECTED";} ?>><?= YES ?></option>
            				<option value="0" <?php if (!(strcmp(0, $row_users['staff']))) {echo "SELECTED";} ?>><?= NO ?></option>
            				</select>
            			</td>
            		</tr>
            	<tr>
            		<td nowrap align="right"><?=GEN_USER_INI ?></td>
            		<td><input type="text" name="initials" value="<?php echo $row_users['initials']; ?>" size="32"></td>
            		</tr>
            	<tr>
            		<td nowrap align="right"><?= GEN_USER_WEW ?></td>
            		<td><input type="text" name="ext" value="<?php echo $row_users['ext']; ?>" size="32"></td>
            		</tr>
            	<tr>
            		<td nowrap align="right"><?= GEN_USER_DZI ?></td>
            		<td><select name="department_id">
            				<option value="0" <?php if (!(strcmp(0, $row_users['department_id']))) {echo "SELECTED";} ?>></option>
            				<?php
do {  
?>
            				<option value="<?php echo $row_departments['department_id']?>"<?php if (!(strcmp($row_departments['department_id'], $row_users['department_id']))) {echo "SELECTED";} ?>><?php echo $row_departments['value']?></option>
            				<?php
} while ($row_departments = mysql_fetch_assoc($departments));
  $rows = mysql_num_rows($departments);
  if($rows > 0) {
      mysql_data_seek($departments, 0);
	  $row_departments = mysql_fetch_assoc($departments);
  }
?>
            				</select>
            			</td>
            		<tr>
            		<td nowrap align="right"><?= GEN_USER_GRU ?></td>
            		<td><select name="group_id">
            				<option value="0" <?php if (!(strcmp(0, $row_users['group_id']))) {echo "SELECTED";} ?>></option>
            				<?php
do {  
?>
            				<option value="<?php echo $row_groups['group_id']?>"<?php if (!(strcmp($row_groups['group_id'], $row_users['group_id']))) {echo "SELECTED";} ?>><?php echo $row_groups['value']?></option>
            				<?php
} while ($row_groups = mysql_fetch_assoc($groups));
  $rows = mysql_num_rows($groups);
  if($rows > 0) {
      mysql_data_seek($groups, 0);
	  $row_groups = mysql_fetch_assoc($groups);
  }
?>
            				</select>
            			</td>
            		<tr>
            		<td nowrap align="right"><?= GEN_USER_AKT ?></td>
            		<td><select name="active">
            				<option value="1" <?php if (!(strcmp(1, $row_users['active']))) {echo "SELECTED";} ?>><?= YES ?></option>
            				<option value="0" <?php if (!(strcmp(0, $row_users['active']))) {echo "SELECTED";} ?>><?= NO ?></option>
            				</select>
            			</td>
            		</tr>
            		
                    <tr>
                        <td nowrap align="right">Zaawansowanie</td>
                        <td><?php
                                echo print_user_expertnes('id_expertness',$row_users['ID_expertness']);
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap align="right">Stanowisko</td>
                        <td><?php
                                echo print_user_position('id_position', $row_users['ID_position']);
                        ?>
                        </td>
                    </tr>

                    <tr>
                        <td nowrap align="right">Oddzia�</td>
                        <td><?php
                                echo print_user_coris_branch('coris_branch_id', $row_users['coris_branch_id']);
                        ?>
                        </td>
                    </tr>

          	<tr>
            			<td nowrap align="right">Nowy pracownik</td>
            		<td><?php
            				echo '<input type="checkbox" value="1" name="new_user" '.($row_users['new_user']==1 ? 'checked' : '').'>';            		
            		?>            		
            			</td>
            		</tr>
 	<tr>	
            			<td nowrap align="right">Statystyki</td>
            		<td><?php
            				echo '<input type="checkbox" value="1" name="Stats" '.($row_users['Stats']==1 ? 'checked' : '').'>';            		
            		?>            		
            			</td>
            		</tr>

            		
            		
            	</table>
			</td>
    		<td width="379" valign="top">
				<table width="95%">
					<tr>
						<td width="100"><p align="right"><?= GEN_USER_JEZ ?>&nbsp;</p>
						</td>
						<td width="94"><select name="language_id[]" size="6" multiple>
							<?php
			do {  
			?>
							<option value="<?php echo $row_languages['language_id']?>" <?php if (!(strcmp($row_languages['language_id'], $row_languages['language_id_sel']))) {echo "SELECTED";} ?>><?php echo $row_languages['value']?></option>
							<?php
			} while ($row_languages = mysql_fetch_assoc($languages));
			  $rows = mysql_num_rows($languages);
			  if($rows > 0) {
				  mysql_data_seek($languages, 0);
				  $row_languages = mysql_fetch_assoc($languages);
			  }
			?>
						</select></td>
						<td width="41">&nbsp;</td>
						<td width="102" nowrap style="background: #efefef; border: #6699cc 1px solid;"><div align="center"></div>   	    	<div align="center"><?php if ($row_users['photo_id'])  { ?><img src="GEN_image.php?photo_id=<?php echo $row_users['photo_id']; ?>"><?php } ?><br>
								
						</div></td>
					</tr>
					<tr>
						<td><div align="right"><?= GEN_USER_ZDJ ?>&nbsp;</div></td>
						<td colspan="3"><input type="file" name="file"></td>
					</tr>
					<tr>
						<td colspan="4" class="popupTitle"><?= GEN_USER_STA ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4"><iframe width="100%" height="70"></iframe></td>
					</tr>
					<tr>
						<td colspan="4" class="popupTitle"><?= GEN_USER_ZAL ?>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4"><iframe width="100%" height="70"></iframe></td>					
					</tr>
				</table>
			</td>
   		</tr>
    	<tr>
    		<td colspan="2" valign="top">
				<div align="center"><input type="submit" class="submit" value="<?= BUTT_SAVE ?>"></div>
			</td>
   		</tr>
   	</table>
	<p>&nbsp;</p>
	<input type="hidden" name="MM_update" value="form1">
	<input type="hidden" name="user_id" value="<?php echo $row_users['user_id']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($users);
mysql_free_result($colors);
mysql_free_result($languages);
mysql_free_result($groups);
mysql_free_result($departments);



?>