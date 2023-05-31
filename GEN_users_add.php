<?php include('include/include.php'); 

if (!check_admin() ){
	die ('Access denied');
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
		
  $insertSQL = sprintf("INSERT INTO coris_users (color_id, username, name, surname, doctor, staff, initials, ext,
                                    department_id, group_id, new_user, Stats, coris_branch_id, ID_position)
                        VALUES (%s, %s, %s,  %s, %s, %s, %s, %s, %s,
                        %s,'" . (getValue('new_user')==1 ? 1 : 0) . "',
                        '" . (getValue('Stats')==1 ? 1 : 0). "',
                        '" . getValue('coris_branch_id') . "',
                        '" . getValue('id_position') . "')",

                       GetSQLValueString($_POST['color_id'], "int"),
                       GetSQLValueString($_POST['username'], "text"),
                     
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['doctor'], "int"),
                       GetSQLValueString($_POST['staff'], "int"),
                       GetSQLValueString($_POST['initials'], "text"),
                       GetSQLValueString($_POST['ext'], "int"),
                       GetSQLValueString($_POST['department_id'], "int"),
                       GetSQLValueString($_POST['group_id'], "int")
                       
                       );

  
  $Result1 = mysql_query($insertSQL) or die("$insertSQL<br>".mysql_error());

  $user_id = mysql_insert_id();
  
  UserObject::userUpdatePassword($user_id,getValue('password'),true);
  
  $insertGoTo = "GEN_users_add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  //exit();
}


$query_departments = "SELECT department_id, `value` FROM coris_users_departments ORDER BY `value` ASC";
$departments = mysql_query($query_departments) or die(mysql_error());
$row_departments = mysql_fetch_assoc($departments);
$totalRows_departments = mysql_num_rows($departments);


$query_groups = "SELECT group_id, `value` FROM coris_users_groups ORDER BY `value` ASC";
$groups = mysql_query($query_groups) or die(mysql_error());
$row_groups = mysql_fetch_assoc($groups);
$totalRows_groups = mysql_num_rows($groups);


$query_languages = "SELECT language_id, `value` FROM coris_users_languages ORDER BY `value` ASC";
$languages = mysql_query($query_languages) or die(mysql_error());
$row_languages = mysql_fetch_assoc($languages);
$totalRows_languages = mysql_num_rows($languages);


$query_colors = "SELECT color_id, name, code FROM coris_colors";
$colors = mysql_query($query_colors) or die(mysql_error());
$row_colors = mysql_fetch_assoc($colors);
$totalRows_colors = mysql_num_rows($colors);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_USER_ADDTITLE ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
<script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>	
</head>

<body>

<script>


	function form_user_check(){
	
					password = $('password').value;
					
					paswd=  /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{9,}$/;  
					if( !password.match(paswd) ){
						alert('Has³o nie spe³nia kryteriów bezpieczeñstwa');
						return false;	
					}  
					return true; 			

	}
</script>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>"  onSubmit="return form_user_check();">
	<table  border="0" cellspacing="1" cellpadding="1">
    	<tr>
    		<td width="377" class="popupTitle"><?= USER ?>&nbsp;</td>
   		</tr>
    	<tr>
    		<td valign="top"><table width="100%" align="center">
<tr valign="baseline">
                    	<td width="29%" align="right" nowrap><?= NAME ?></td>
                    	<td><input type="text" name="name" value="" size="32"></td>
           			</tr>
            		<tr valign="baseline">
                    	<td nowrap align="right"><?= SURNAME ?></td>
                    	<td><input type="text" name="surname" value="" size="32"></td>
           			</tr>
            		<tr valign="baseline">
            		<td nowrap align="right"><?= LOGIN ?></td>
            		<td><input type="text" name="username" value="" size="32"></td>
            		</tr>
            	<tr valign="baseline">
            		<td nowrap align="right"><?= PASSWORD ?></td>
            		<td><input type="password" name="password" id="password" value="" size="32"></td>
            		</tr>
            	<tr valign="baseline">
                	<td nowrap align="right"><?= COLOR ?></td>
                	<td><select name="color_id">
                			<option value="0"></option>
                			<?php
do {  
?>
                			<option style="background: <?php echo $row_colors['code']; ?>" value="<?php echo $row_colors['color_id']?>"><?php echo $row_colors['name']?></option>
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
            	<tr valign="baseline">
            		<td nowrap align="right"><?= GEN_USER_LEK ?></td>
            		<td><select name="doctor">
            				<option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>><?= YES ?></option>
            				<option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>><?= NO ?></option>
            				</select>
            			</td>
            		</tr>
            	<tr valign="baseline">
            		<td nowrap align="right"><?= GEN_USER_GRA ?></td>
            		<td><select name="staff">
            				<option value="1" <?php if (!(strcmp(1, ""))) {echo "SELECTED";} ?>><?= YES ?></option>
            				<option value="0" <?php if (!(strcmp(0, ""))) {echo "SELECTED";} ?>><?= NO ?></option>
            				</select>
            			</td>
            		</tr>
            	<tr valign="baseline">
            		<td nowrap align="right"><?= GEN_USER_INI ?></td>
            		<td><input type="text" name="initials" value="" size="32"></td>
            		</tr>
            	<tr valign="baseline">
            		<td nowrap align="right"><?= GEN_USER_WEW ?></td>
            		<td><input type="text" name="ext" value="" size="32"></td>
            		</tr>
            	<tr valign="baseline">
            		<td nowrap align="right"><?= GEN_USER_DZI ?></td>
            		<td width="71%"><select name="department_id">
            			<option value="0"></option>
            			<?php
do {  
?>
            			<option value="<?php echo $row_departments['department_id']?>"><?php echo $row_departments['value']?></option>
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
            		<tr valign="baseline">
            		<td nowrap align="right"><?= GEN_USER_GRU ?></td>
            		<td><select name="group_id">
            			<option value="0"></option>
            			<?php
do {  
?>
            			<option value="<?php echo $row_groups['group_id']?>"><?php echo $row_groups['value']?></option>
            			<?php
} while ($row_groups = mysql_fetch_assoc($groups));
  $rows = mysql_num_rows($groups);
  if($rows > 0) {
      mysql_data_seek($groups, 0);
	  $row_groups = mysql_fetch_assoc($groups);
  }
?>
            				                    </select>
            			</td></tr>
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
                    <tr>
                        <td nowrap align="right">Stanowisko</td>
                        <td><?php
                                echo print_user_position('id_position', 0);
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td nowrap align="right">Oddzia³</td>
                        <td><?php
                                echo print_user_coris_branch('coris_branch_id', 0);
                        ?>
                        </td>
                    </tr>

           	</table></td>
   		</tr>
    	<tr>
    		<td valign="top"><div align="center">
    				<input type="submit" class="submit" value="<?= BUTT_SAVE ?>">
    				</div></td>
   		</tr>
    	</table>
	<p>&nbsp;</p>
	<input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($departments);

mysql_free_result($groups);

mysql_free_result($languages);

mysql_free_result($colors);
?>
