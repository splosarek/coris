<?php
include('include/include.php');




$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coris_contrahents_links (contrahent_id, type_id, linked_contrahent_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['contrahent_id'], "int"),
                       GetSQLValueString($_POST['type_id'], "int"),
                       GetSQLValueString($_POST['linked_contrahent_id'], "int"));

  
  $Result1 = mysql_query($insertSQL) or die(mysql_error());

  $insertGoTo = "GEN_contrahents_details.php?contrahent_id=". $_POST['contrahent_id'] ."&offset=". $_POST['offset'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    //$insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<?php

$query_types = "SELECT coris_contrahents_links_types.type_id, coris_contrahents_links_types.`value` FROM coris_contrahents_links_types WHERE coris_contrahents_links_types.active = 1";
$types = mysql_query($query_types) or die(mysql_error());
$row_types = mysql_fetch_assoc($types);
$totalRows_types = mysql_num_rows($types);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="Scripts/js.js"></script>
<script language="JavaScript" src="Scripts/validate.js"></script>
</head>

<body>
<br>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>" onSubmit="return validate('pl', 'type_id', 'l', 'linked_contrahent_id', 'rn')">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo LINKADD ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td width="111" align="right" nowrap><?php echo TYPE ?>&nbsp;</td>
    <td width="483">
      <select name="type_id" class="required" id="type_id">
        <option value="0"></option>
        <?php
do {  
?>
        <option value="<?php echo $row_types['type_id']?>"><?php echo $row_types['value']?></option>
        <?php
} while ($row_types = mysql_fetch_assoc($types));
  $rows = mysql_num_rows($types);
  if($rows > 0) {
      mysql_data_seek($types, 0);
	  $row_types = mysql_fetch_assoc($types);
  }
?>
      </select>
    </td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?PHP echo CONTRAHENT ?>&nbsp;</td>
    <td><input name="linked_contrahent_id" type="text" class="required" id="linked_contrahent_id" style="text-align: center" onBlur="document.contrahents_select_iframe.location='GEN_contrahents_select_iframe.php?fieldname=linked_&contrahent_id='+ this.value" size="5">
        <input name="linked_contrahent_name" type="text" id="linked_contrahent_name" value="" size="31" readonly="yes" tabindex="-1">
        <input type="button" style="width: 20px" tabindex="-1" title="Wyszukaj klienta" onclick="MM_openBrWindow('GEN_contrahents_select_frameset.php?fieldname=linked_','','width=550,height=420')" value="&gt;"></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td height="22" align="right" nowrap>&nbsp;</td>
    <td><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
      <input name="offset" type="hidden" id="offset" value="<?PHP echo $_GET['offset'] ?>">
    <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&offset=<?PHP echo $_GET['offset'] ?>'">

      <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
<iframe name="contrahents_select_iframe" height="0" width="0" src=""></iframe>
</body>
</html>
<?php
mysql_free_result($types);
?>