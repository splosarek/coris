<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coris_contrahents_contacts (contrahent_id, gender_id, name, surname, `position`, phone1, phone2, phone3, fax1, fax2, mobile1, mobile2, email, attention, `date`, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['contrahent_id'], "int"),
                       GetSQLValueString($_POST['gender_id'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['surname'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($_POST['phone1'], "text"),
                       GetSQLValueString($_POST['phone2'], "text"),
                       GetSQLValueString($_POST['phone3'], "text"),
                       GetSQLValueString($_POST['fax1'], "text"),
                       GetSQLValueString($_POST['fax2'], "text"),
                       GetSQLValueString($_POST['mobile1'], "text"),
                       GetSQLValueString($_POST['mobile2'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString(isset($_POST['attention']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString($_SESSION['user_id'], "int"));

//	$_SESSION['user_id'] = (isset($_POST['user_id'])) ? $_POST['user_id'] : 0;

  
  $Result1 = mysql_query($insertSQL) or die(mysql_error());

/*	
	$query_contact = "SELECT contact_id FROM coris_contrahents_contacts WHERE contact_id = LAST_INSERT_ID()";
	$contact = mysql_query($query_contact) or die(mysql_error());
	$row_contact = mysql_fetch_assoc($contact);
	$totalRows_contact = mysql_num_rows($contact);
*/
$contact_id = mysql_insert_id();
  $insertGoTo = "GEN_contrahents_details_contacts_details.php?contrahent_id=". $_POST['contrahent_id'] ."&contact_id=". $contact_id ."&offset=". $_POST['offset'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    //$insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$query_users = "SELECT u.user_id, u.username FROM coris_users u WHERE u.department_id = 7 AND  u.active = 1 ORDER BY username";
$users = mysql_query($query_users) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

$user_id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?PHP echo CONTACTADD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript" src="Scripts/validate.js"></script>
<body>
<br>

<form name="form1" method="POST" action="<?php echo $editFormAction; ?>" onSubmit="return validate('pl', 'name', 'r', 'surname', 'r')">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="4" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo CONTACTADD ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td width="111" align="right" nowrap><?php echo GENDER ?>&nbsp;</td>
    <td colspan="3">
      <select name="gender_id" id="gender_id">
	  	<option value="0"></option>
        <option value="2"><?= GEN_CN_KB_WOMAN ?></option>
        <option value="1"><?= GEN_CN_KB_MAN ?></option>
      </select>
    </td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><strong><?php echo NAME ?></strong>&nbsp;</td>
    <td colspan="3"><input name="name" type="text" class="required" id="name" size="15" maxlength="15">
    </td>
    </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><strong><?php echo SURNAME ?></strong>&nbsp;</td>
    <td colspan="3"><input name="surname" type="text" class="required" id="surname" size="30" maxlength="30">
    </td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo POSITION ?>&nbsp;&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="position" type="text" id="position" size="30" maxlength="30"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo PHONE ?></strong>&nbsp;</td>
    <td align="left" nowrap width="191"><input name="phone1" type="text" id="phone1" size="15" maxlength="15"></td>
    <td align="right" nowrap width="100"><strong><?php echo FAX ?></strong>&nbsp;</td>
    <td align="left" nowrap width="188"><input name="fax1" type="text" id="fax1" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone2" type="text" id="phone2" size="15" maxlength="15"></td>
    <td align="left" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="fax2" type="text" id="fax2" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone3" type="text" id="phone3" size="15" maxlength="15"></td>
    <td colspan="2" align="left" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><strong><?php echo MOBILE ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="mobile1" type="text" id="mobile1" size="15" maxlength="15"></td>
    <td colspan="2" rowspan="4" align="center" valign="middle" nowrap><table width="80%"  border="0" cellspacing="1" cellpadding="1" style="background: #f9f9f9; border: #eeeeee 1px solid;">
      <tr>
        <td width="16%">&nbsp;</td>
        <td width="76%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><input name="attention" type="checkbox" id="attention" value="checkbox" style="background: #f9f9f9">
        </td>
        <td align="left" bgcolor="yellow"><?php echo ATTENTION ?></td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></td>
    </tr>
  <tr valign="baseline">
    <td align="right" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="mobile2" type="text" id="mobile2" size="15" maxlength="15"></td>
    </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo EMAIL ?>&nbsp;</td>
    <td align="left" nowrap><input name="email" type="text" size="30" maxlength="30"></td>
    </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td colspan="3"><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
      <input name="offset" type="hidden" id="offset" value="<?PHP echo $_GET['offset'] ?>">
      <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&offset=<?PHP echo $_GET['offset'] ?>'">
        <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($users);
?>
