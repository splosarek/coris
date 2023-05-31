<?php include('include/include.php'); 

$query_users = "SELECT user_id, CONCAT_WS(' ', surname, name) AS fullname FROM coris_users ORDER BY surname";
$users = mysql_query($query_users, $cn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_MES_WIAD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="POST" action="GEN_messages_iframe2.php" target="iframe2" onSubmit="return validate('message', 'r', 'user_id[]', 'r');">
   <table width="100%"  border="0" cellspacing="2" cellpadding="2">
      <tr>
         <td colspan="2" class="popupTitle"><?= GEN_MES_WIAD ?>&nbsp;</td>
      </tr>
      <tr>
         <td width="78%"><iframe name="iframe1" width="500" height="266" src="GEN_messages_iframe1.php" scrolling="yes"></iframe></td>
         <td width="22%"><select name="user_id[]" size="20" multiple id="user_id[]">
            <?php
do {  
?>
            <option value="<?php echo $row_users['user_id']?>"><?php echo $row_users['fullname']?></option>
            <?php
} while ($row_users = mysql_fetch_assoc($users));
  $rows = mysql_num_rows($users);
  if($rows > 0) {
      mysql_data_seek($users, 0);
	  $row_users = mysql_fetch_assoc($users);
  }
?>
         </select></td>
      </tr>
      <tr>
         <td><input name="message" type="text" id="message" size="80"></td>
         <td>         <input name="Submit" type="submit" class="submit" value="<?= SEND ?>">
            <input name="urgent" type="checkbox" id="urgent" style="background: #dfdfdf" value="checkbox">
            <font style="background: yellow">Pilne</font> </td>
      </tr>
   </table>
   <input type="hidden" name="MM_insert" value="form1">
	<iframe name="iframe2" width="0" height="0" src="GEN_messages_iframe2.php">
	</iframe>	
<script language="JavaScript" type="text/JavaScript" src="Scripts/validate.js"></script>	
</form>
</body>
</html>
<?php
mysql_free_result($users);
?>
