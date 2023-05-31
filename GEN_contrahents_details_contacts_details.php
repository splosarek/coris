<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE coris_contrahents_contacts SET contrahent_id=%s, gender_id=%s, name=%s, surname=%s, `position`=%s, phone1=%s, phone2=%s, phone3=%s, fax1=%s, fax2=%s, mobile1=%s, mobile2=%s, email=%s, attention=%s, modification_date=%s WHERE contact_id=%s",
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
                       GetSQLValueString($_POST['contact_id'], "int"));

  
  $Result1 = mysql_query($updateSQL, $cn) or die(mysql_error());

  $insertSQL = "REPLACE coris_contrahents_contacts_notes (contact_id, note) VALUES (".GetSQLValueString($_POST['contact_id'], "int").", ".GetSQLValueString($_POST['note'], "text").")";  
  $Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());  
  
  $updateGoTo = "GEN_contrahents_details_contacts_details.php?contrahent_id=". $_POST['contrahent_id'] ."&contact_id=". $_POST['contact_id'] ."&offset=". $_POST['offset'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php
$id_contact = "0";
if (isset($_GET['contact_id'])) {
  $id_contact = (get_magic_quotes_gpc()) ? $_GET['contact_id'] : addslashes($_GET['contact_id']);
}

$query_contact = sprintf("SELECT coris_contrahents_contacts.contact_id, coris_contrahents_contacts.contrahent_id, coris_contrahents_contacts.gender_id, coris_contrahents_contacts.name, coris_contrahents_contacts.surname, coris_contrahents_contacts.`position`, coris_contrahents_contacts.phone1, coris_contrahents_contacts.phone2, coris_contrahents_contacts.phone3, coris_contrahents_contacts.fax1, coris_contrahents_contacts.fax2, coris_contrahents_contacts.mobile1, coris_contrahents_contacts.mobile2, coris_contrahents_contacts.email, coris_contrahents_contacts.attention, coris_contrahents_contacts.modification_date, coris_contrahents_contacts.date, coris_users.username FROM coris_contrahents_contacts, coris_users WHERE coris_contrahents_contacts.user_id = coris_users.user_id AND coris_contrahents_contacts.contact_id = %s", $id_contact);
$contact = mysql_query($query_contact, $cn) or die(mysql_error());
$row_contact = mysql_fetch_assoc($contact);
$totalRows_contact = mysql_num_rows($contact);

$id_note = "0";
if (isset($_GET['contact_id'])) {
  $id_note = (get_magic_quotes_gpc()) ? $_GET['contact_id'] : addslashes($_GET['contact_id']);
}

$query_note = sprintf("SELECT coris_contrahents_contacts_notes.note FROM coris_contrahents_contacts_notes WHERE coris_contrahents_contacts_notes.contact_id = %s", $id_note);
$note = mysql_query($query_note, $cn) or die(mysql_error());
$row_note = mysql_fetch_assoc($note);
$totalRows_note = mysql_num_rows($note);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1">
<input type="hidden" name="MM_update" value="form1">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="4" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo "Edycja kontaktu"; ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td width="111" align="right" nowrap><?php echo GENDER ?>&nbsp;</td>
    <td colspan="3"><select name="gender_id" id="gender_id">
      <option value="0" <?php if (!(strcmp(0, $row_contact['gender_id']))) {echo "SELECTED";} ?>></option>
      <option value="2" <?php if (!(strcmp(2, $row_contact['gender_id']))) {echo "SELECTED";} ?>><?= GEN_CN_KB_WOMAN ?></option>
        <option value="1" <?php if (!(strcmp(1, $row_contact['gender_id']))) {echo "SELECTED";} ?>><?= GEN_CN_KB_MAN ?></option>
      </select>
    </td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><strong><?php echo NAME ?></strong>&nbsp;</td>
    <td colspan="3"><input name="name" type="text" class="required" id="name" value="<?php echo $row_contact['name']; ?>" size="15" maxlength="15">
    </td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><strong><?php echo SURNAME ?></strong>&nbsp;</td>
    <td colspan="3"><input name="surname" type="text" class="required" id="surname" value="<?php echo $row_contact['surname']; ?>" size="30" maxlength="30">
    </td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo POSITION ?>&nbsp;&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="position" type="text" id="position" value="<?php echo $row_contact['position']; ?>" size="30" maxlength="30"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo PHONE ?></strong>&nbsp;</td>
    <td align="left" nowrap width="191"><input name="phone1" type="text" id="phone1" value="<?php echo $row_contact['phone1']; ?>" size="15" maxlength="15"></td>
    <td align="right" nowrap width="100"><strong><?php echo FAX ?></strong>&nbsp;</td>
    <td align="left" nowrap width="188"><input name="fax1" type="text" id="fax1" value="<?php echo $row_contact['fax1']; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone2" type="text" id="phone2" value="<?php echo $row_contact['phone2']; ?>" size="15" maxlength="15"></td>
    <td align="left" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="fax2" type="text" id="fax2" value="<?php echo $row_contact['fax2']; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone3" type="text" id="phone3" value="<?php echo $row_contact['phone3']; ?>" size="15" maxlength="15"></td>
    <td colspan="2" align="left" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><strong><?php echo MOBILE ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="mobile1" type="text" id="mobile1" value="<?php echo $row_contact['mobile1']; ?>" size="15" maxlength="15"></td>
    <td colspan="2" rowspan="4" align="center" valign="middle" nowrap><table width="80%"  border="0" cellspacing="1" cellpadding="1" style="background: #f9f9f9; border: #eeeeee 1px solid;">
        <tr>
          <td width="16%">&nbsp;</td>
          <td width="76%">&nbsp;</td>
          <td width="8%">&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><input <?php if (!(strcmp($row_contact['attention'],1))) {echo "checked";} ?> name="attention" type="checkbox" id="attention" value="checkbox" style="background: #f9f9f9">
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
    <td align="left" nowrap><input name="mobile2" type="text" id="mobile2" value="<?php echo $row_contact['mobile2']; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo EMAIL ?>&nbsp;</td>
    <td align="left" nowrap><input name="email" type="text" value="<?php echo $row_contact['email']; ?>" size="30" maxlength="30"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
 
</table>
<br>
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo COMMENT ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>&nbsp;
    </td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="middle" nowrap>&nbsp;</td>
    <td width="483"><textarea name="note" cols="60" rows="3" id="note"><?php echo $row_note['note']; ?></textarea></td>
  </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
</table><br>
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" disabled>
			  <tr valign="baseline" bgcolor="#FFFFFF">
				<td width="111" align="right" nowrap><?php echo ADDEDBY ?>&nbsp;</td>
				<td width="483"><?php echo $row_contact['username']; ?></td>
			  </tr>
			  <tr valign="baseline" bgcolor="#FFFFFF">
				<td width="111" align="right" nowrap><?php echo ADDEDDATE ?>&nbsp;</td>
				<td width="483"><?php echo $row_contact['date']; ?></td>
			  </tr>
			  <tr valign="baseline" bgcolor="#FFFFFF">
                <td align="right" nowrap><?php echo MODIFIEDBY ?>&nbsp;</td>
                <td>&nbsp;</td>
  </tr>
			  <tr valign="baseline" bgcolor="#FFFFFF">
				<td width="111" align="right" nowrap><?php echo MODIFIEDDATE ?>&nbsp;</td>
				<td width="483"><?php echo $row_contact['modification_date']; ?></td>
			  </tr>
</table>
<br>
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 0px solid;">  
 <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td colspan="3"><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
        <input name="contact_id" type="hidden" id="contact_id" value="<?PHP echo $_GET['contact_id'] ?>">
        <input name="offset" type="hidden" id="offset" value="<?PHP echo $_GET['offset'] ?>">
        <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&offset=<?PHP echo $_GET['offset'] ?>'">
        <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
</form>
<br>
</body>
</html>
<?php
mysql_free_result($contact);

mysql_free_result($note);
?>
