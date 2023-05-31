<?php include('include/include.php');
include('include/contrahent_monior.php');
$lang = $_SESSION['GUI_language'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$contrahent_id = getValue('contrahent_id');



// jesli zalogowany oddzial 1 czyli Coris Polska to pokazac wszystkich (czyli bez filtra)
// jesli zalogowany z innego oddzialu, to pokazac tylko kontrahentow danego oddzialu
// w innych przypadkach nic nie pokazywac
/*$userCorisBranchId = NULL;
$query_userCorisBranch = '';
if (isset($_SESSION['coris_branch']) && intval($_SESSION['coris_branch'])>0){
    if( $_SESSION['coris_branch'] == 1){
        $userCorisBranchId = 1;
    }else if( $_SESSION['coris_branch'] <> 1){
        $userCorisBranchId = intval($_SESSION['coris_branch']);
        $query_userCorisBranch = " AND (coris_contrahents.coris_branch_id ='$userCorisBranchId'
                                     OR coris_contrahents.coris_branch_id = 0 )
        ";
    }
}else{
    // $query_userCorisBranch = " AND coris_contrahents.coris_branch_id ='-1' ";
}

//$branchValidate = ", 'branch', 'j'";
//if(2 == $_SESSION['coris_branch'])
//{
//    $branchValidate = "";
//}
$branchValidate = "";
*/
if (isset($_GET['action']) && isset($_GET['contrahent_id'])){
  if ($_GET['action'] == 'delete'){
    if (isset($_GET['initials_id']) && $_GET['initials_id']>0){
        $query_delete = "DELETE FROM coris_contrahents_initials
                          WHERE ID='".$_GET['initials_id']."'
                            AND  contrahent_id='".$_GET['contrahent_id']."'
                            LIMIT 1";
        $mysql_result = mysql_query ($query_delete) or die($query_delete.'<br>'.mysql_error());
    }
    if (isset($_GET['account_id']) && $_GET['account_id']>0){
        $query_delete = "DELETE FROM coris_contrahents_accounts
                          WHERE account_id='".$_GET['account_id']."'
                            AND  contrahent_id='".$_GET['contrahent_id']."'
                          LIMIT 1";
        $mysql_result = mysql_query ($query_delete) or die($query_delete.'<br>'.mysql_error());
    }
  }
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("REPLACE coris_contrahents_notes (contrahent_id, note) VALUES (%s, %s)",
                       GetSQLValueString($_POST['contrahent_id'], "int"),
                       GetSQLValueString($_POST['note'], "text"));


  $Result1 = mysql_query($updateSQL) or die(mysql_error());

  $updateGoTo = "GEN_contrahents_details.php?contrahent_id=". $_POST['contrahent_id'] ."&offset=". $_POST['offset'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  exit();
}
if ($_SESSION['new_user']==0){
		if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
	  	$updateSQL = "UPDATE coris_contrahents SET
	              default_urgent=". GetSQLValueString(isset($_POST['default_urgent']) ? "true" : "", "defined","1","0").",
	              default_logistics=".GetSQLValueString(isset($_POST['default_logistics']) ? "true" : "", "defined","1","0").",
	              default_event_date_rate=".GetSQLValueString(isset($_POST['default_event_date_rate']) ? "true" : "", "defined","1","0").",
	              default_paymenttype_id='".$_POST['default_paymenttype_id']."',
	              default_payment_due='".$_POST['default_payment_due']."',
	               modification_date='".date('Y-m-d H:i:s')."',
	              KLJEZDOM='".$_POST['contrahents_language']."',
	              default_payment_due='".$_POST['default_payment_due']."',
	              default_currency_in='".$_POST['default_currency_in']."',
	              default_ignore_contrahent_nip='".$_POST['default_ignore_contrahent_nip']."'  ,
	              default_reduction = '".$_POST['default_reduction']."',
	              vat_enable  = '".( getValue('vat_enable')==1 ? 1 : 0 ) ."',
	              boook_760_4  = '".( getValue('boook_760_4')==1 ? 1 : 0 ) ."'
	              WHERE contrahent_id='".$_POST['contrahent_id']."' LIMIT 1";


	  $Result1 = mysql_query($updateSQL) or die($updateSQL.'<br>'.mysql_error());

	  $updateGoTo = "GEN_contrahents_details.php?contrahent_id=". $_POST['contrahent_id'] ."&offset=". $_POST['offset'];
	  if (isset($_SERVER['QUERY_STRING'])) {
	    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
	    //$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $updateGoTo));
	  exit();
	}
}



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

    $branchSelect = getValue('coris_branch');
    $branchUpdate = "";

    if(1 == $_SESSION['coris_branch'])
    {
        $branchUpdate = ", coris_branch_id='1' ";
    }

    if(is_array($branchSelect))
    {
        if(in_array(2, $branchSelect) )
        {
            // dla wszystkich
            $branchUpdate = ", coris_branch_id='0' ";
        }
    }

    $group = getValue('group') == 1 ? 1: 0 ;

    $sage = getValue('group') == 1 ? getValue('sage') :  '' ;
    $tu_pl = getValue('tu_pl') == 1 ? 1: 0 ;
    $updateSQL = sprintf("UPDATE coris_contrahents
                    SET contrahenttype_id=%s, gc_id=%s, simple_id=%s, name=%s, short_name=%s, address=%s, post=%s, province_id=%s,
                        city=%s, country_id=%s, phone1=%s, phone2=%s, phone3=%s, fax1=%s, fax2=%s,
                        mobile1=%s, mobile2=%s, email=%s,email_vindication=%s, www=%s, regon=%s, qualification_id=%s,
                        locked=%s, attention=%s, modification_date=%s, o_klnotuse='".$_POST['o_klnotuse']."',
                        contrahent_substitute='".$_POST['contrahent_substitute']."', `group`='$group',`sage`='$sage',tu_pl='$tu_pl'
                        $branchUpdate
            WHERE contrahent_id=%s",
                       GetSQLValueString($_POST['contrahenttype_id'], "int"),
                       GetSQLValueString($_POST['gc_id'], "int"),
                       GetSQLValueString($_POST['simple_id'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['short_name'], "text"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['post'], "text"),
                       GetSQLValueString($_POST['province_id'], "int"),
                       GetSQLValueString($_POST['city'], "text"),
                       GetSQLValueString($_POST['country_id'], "text"),
                       GetSQLValueString($_POST['phone1'], "text"),
                       GetSQLValueString($_POST['phone2'], "text"),
                       GetSQLValueString($_POST['phone3'], "text"),
                       GetSQLValueString($_POST['fax1'], "text"),
                       GetSQLValueString($_POST['fax2'], "text"),
                       GetSQLValueString($_POST['mobile1'], "text"),
                       GetSQLValueString($_POST['mobile2'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       "'".getValue('email_vindication') ."'",
                       GetSQLValueString($_POST['www'], "text"),

                       GetSQLValueString($_POST['regon'], "text"),
                       GetSQLValueString($_POST['qualification_id'], "int"),
                       GetSQLValueString(isset($_POST['locked']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['attention']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString($_POST['contrahent_id'], "int"));

    $Result1 = mysql_query($updateSQL) or die(mysql_error());

    $contrahent_id = getValue('contrahent_id');

    $nip = getValue('nip');
    $qt = "SELECT nip FROM coris_contrahents WHERE contrahent_id='$contrahent_id'";
    $mr = mysql_query($qt);
    $r = mysql_fetch_array($mr);


    if ( $nip != $r['nip']) {
        if (in_array(Application::getCurrentUser(), $_superUsers)) {
            $qu = "UPDATE coris_contrahents SET nip='$nip'  WHERE contrahent_id='$contrahent_id'";
            $mr = mysql_query($qu);

            zapiszLogZmian($contrahent_id,'','nip',$r['nip'],$nip);
        }else{
            dodajDoKolejkiZmian($contrahent_id,$nip);
            $insertGoTo = "GEN_contrahents_details.php?contrahent_id=$contrahent_id&".time()."#konta";

            echo "
            <div align='center'>Zmiana numeru NIP  zosta³a wys³ana do zatwierdzenia,<br> do momentu zatwierdzenia nie bêdzie widoczna w danych kontrahenta            
            <br><br><br><br> <a href=\"".$insertGoTo."\">Powrót do danych kontrahenta</a>
            </div>";
            exit();
        }

    }

    $updateGoTo = "GEN_contrahents_details.php?contrahent_id=". $_POST['contrahent_id'] ."&offset=". $_POST['offset'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        //$updateGoTo .= $_SERVER['QUERY_STRING'];
    }

    header("Location: $updateGoTo");
}
if (isset($_GET['link_id'])) {
    $query = "DELETE FROM coris_contrahents_links WHERE link_id = $_GET[link_id]";
  if (!$result = mysql_query($query)) {
        die(mysql_error());
  }
}
if (isset($_GET['contact_id'])) {
    $query = "UPDATE coris_contrahents_contacts SET active = 0 WHERE contact_id = $_GET[contact_id]";
  if (!$result = mysql_query($query)) {
        die(mysql_error());
  }
}

?>
<?php
$id_contrahent = "0";
if (isset($_GET['contrahent_id'])) {
  $id_contrahent = addslashes(stripslashes(trim($_GET['contrahent_id'])));
}

$query_contrahent = "
SELECT coris_contrahents.contrahent_id, coris_contrahents.contrahenttype_id, coris_contrahents.gc_id, coris_contrahents.simple_id,
       coris_contrahents.name,coris_contrahents.short_name, coris_contrahents.address, coris_contrahents.post,
       coris_contrahents.province_id, coris_contrahents.city, coris_contrahents.country_id, coris_contrahents.phone1,
       coris_contrahents.phone2, coris_contrahents.phone3, coris_contrahents.fax1, coris_contrahents.fax2, coris_contrahents.mobile1,
       coris_contrahents.mobile2, coris_contrahents.email, coris_contrahents.www, coris_contrahents.nip, coris_contrahents.regon,
       coris_contrahents.default_to_pln_ratetype_id, coris_contrahents.default_to_ext_ratetype_id, coris_contrahents.default_urgent,
       coris_contrahents.default_logistics, coris_contrahents.default_paymenttype_id,coris_contrahents.default_ignore_contrahent_nip,
       coris_contrahents.default_payment_due, coris_contrahents.default_event_date_rate, coris_contrahents.qualification_id,
       coris_contrahents.locked, coris_contrahents.attention, coris_contrahents.`date`, coris_contrahents.modification_date,
       coris_users.username,coris_contrahents.KLJEZDOM,coris_contrahents.default_currency_in,coris_contrahents.default_payment_due,
       coris_contrahents.o_klnotuse, coris_contrahents.contrahent_substitute,coris_contrahents.default_reduction,coris_contrahents.vat_enable,
       coris_contrahents.boook_760_4,coris_contrahents.email_vindication,
       coris_contrahents.coris_branch_id,
       coris_contrahents.group,coris_contrahents.sage, coris_contrahents.tu_pl,
       ( SELECT username FROM coris_users WHERE coris_contrahents.	modification_user_id = coris_users.user_id ) As usernamemod
  FROM coris_contrahents, coris_users
 WHERE coris_contrahents.user_id = coris_users.user_id
   AND coris_contrahents.contrahent_id = '$id_contrahent' ";

$query_contrahent .= $query_userCorisBranch;

$contrahent = mysql_query($query_contrahent) or die(mysql_error());
$row_contrahent = mysql_fetch_assoc($contrahent);
$totalRows_contrahent = mysql_num_rows($contrahent);

$totalRows_contrahent or die('180: No record or no permission.');

$branchSelect1 = '';
$branchSelect2 = '';
if(0 == $row_contrahent['coris_branch_id'])
{
    $branchSelect1 = 'checked="checked"';
    $branchSelect2 = 'checked="checked"';
}

if(1 == $row_contrahent['coris_branch_id'])
{
    $branchSelect1 = 'checked="checked"';
}

if(2 == $row_contrahent['coris_branch_id'])
{
    $branchSelect2 = 'checked="checked"';
}


$query_provinces = "SELECT coris_provinces.province_id, coris_provinces.`value` FROM coris_provinces WHERE coris_provinces.active = 1 ORDER BY coris_provinces.`value`";
$provinces = mysql_query($query_provinces) or die(mysql_error());
$row_provinces = mysql_fetch_assoc($provinces);
$totalRows_provinces = mysql_num_rows($provinces);


$query_contrahenttypes = "SELECT coris_contrahenttypes.contrahenttype_id, coris_contrahenttypes.`value`, simple_id FROM coris_contrahenttypes WHERE coris_contrahenttypes.active = 1 ORDER BY coris_contrahenttypes.`value`";
$contrahenttypes = mysql_query($query_contrahenttypes) or die(mysql_error());
$row_contrahenttypes = mysql_fetch_assoc($contrahenttypes);
$totalRows_contrahenttypes = mysql_num_rows($contrahenttypes);


$query_qualification = "SELECT coris_contrahents_qualifications.qualification_id, coris_contrahents_qualifications.`value`, coris_contrahents_qualifications.color FROM coris_contrahents_qualifications WHERE coris_contrahents_qualifications.active = 1 ORDER BY coris_contrahents_qualifications.qualification_id";
$qualification = mysql_query($query_qualification) or die(mysql_error());
$row_qualification = mysql_fetch_assoc($qualification);
$totalRows_qualification = mysql_num_rows($qualification);


$query_users = "SELECT u.user_id, u.username FROM coris_users u WHERE u.department_id = 7 AND  u.active = 1 ORDER BY username";
$users = mysql_query($query_users) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);


$query_paymenttypes = "SELECT coris_finances_paymenttypes.paymenttype_id, coris_finances_paymenttypes.`value` FROM coris_finances_paymenttypes ORDER BY coris_finances_paymenttypes.`value`";
$paymenttypes = mysql_query($query_paymenttypes) or die(mysql_error());
$row_paymenttypes = mysql_fetch_assoc($paymenttypes);
$totalRows_paymenttypes = mysql_num_rows($paymenttypes);


$query_links = "SELECT coris_contrahents_links.link_id, coris_contrahents_links.linked_contrahent_id, coris_contrahents.name, coris_contrahents_links_types.value AS type FROM coris_contrahents_links_types,coris_contrahents_links LEFT JOIN coris_contrahents ON coris_contrahents_links.linked_contrahent_id = coris_contrahents.contrahent_id WHERE coris_contrahents_links.type_id = coris_contrahents_links_types.type_id ORDER BY coris_contrahents_links.linked_contrahent_id";
$links = mysql_query($query_links) or die(mysql_error());
$row_links = mysql_fetch_assoc($links);
$totalRows_links = mysql_num_rows($links);

$id_note = "0";
if (isset($_GET['contrahent_id'])) {
  $id_note = (get_magic_quotes_gpc()) ? $_GET['contrahent_id'] : addslashes($_GET['contrahent_id']);
}

$query_note = sprintf("SELECT coris_contrahents_notes.note FROM coris_contrahents_notes WHERE coris_contrahents_notes.contrahent_id = %s", $id_note);
$note = mysql_query($query_note) or die(mysql_error());
$row_note = mysql_fetch_assoc($note);
$totalRows_note = mysql_num_rows($note);

$id_contacts = "0";
if (isset($_GET['contrahent_id'])) {
  $id_contacts = (get_magic_quotes_gpc()) ? $_GET['contrahent_id'] : addslashes($_GET['contrahent_id']);
}

$query_contacts = sprintf("SELECT coris_contrahents_contacts.contact_id, coris_contrahents_contacts.gender_id, CONCAT_WS(' ', coris_contrahents_contacts.name, coris_contrahents_contacts.surname) AS contact_name, coris_contrahents_contacts.`position`, CONCAT_WS(', ', coris_contrahents_contacts.phone1, coris_contrahents_contacts.phone2, coris_contrahents_contacts.phone3) AS phone, CONCAT_WS(', ', coris_contrahents_contacts.fax1, coris_contrahents_contacts.fax2) AS fax, CONCAT_WS(', ' , coris_contrahents_contacts.mobile1, coris_contrahents_contacts.mobile2) AS mobile, coris_contrahents_contacts.email, coris_contrahents_contacts.attention, coris_contrahents_contacts.user_id, DATE(coris_contrahents_contacts.`date`) AS `date` FROM coris_contrahents_contacts WHERE coris_contrahents_contacts.contrahent_id = %s AND coris_contrahents_contacts.active = 1", $id_contacts);
$contacts = mysql_query($query_contacts) or die(mysql_error());
$row_contacts = mysql_fetch_assoc($contacts);
$totalRows_contacts = mysql_num_rows($contacts);

$id_accounts = "0";
if (isset($_GET['contrahent_id'])) {
  $id_accounts = (get_magic_quotes_gpc()) ? $_GET['contrahent_id'] : addslashes($_GET['contrahent_id']);
}

$query_accounts = "SELECT coris_contrahents_accounts.account_id, coris_contrahents_accounts.account, coris_contrahents_accounts.name, coris_contrahents_accounts.country_id, coris_contrahents_accounts.note, DATE(coris_contrahents_accounts.`date`) AS `date`, `order` FROM coris_contrahents_accounts WHERE coris_contrahents_accounts.contrahent_id = '$id_accounts' AND coris_contrahents_accounts.active = 1 ORDER BY `order`,coris_contrahents_accounts.account_id";
$accounts = mysql_query($query_accounts) or die(mysql_error());
$totalRows_accounts = mysql_num_rows($accounts);



?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?PHP echo CONTRAHENTDETAILS ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
<body onload="<?php echo (isset($_GET['offset'])) ? "document.body.scrollTop = '$_GET[offset]'" : "" ?>;  <?php echo (isset($_GET['action']) && $_GET['action']=='view') ? "if ( window.opener.parent.frame) window.opener.parent.frame.location.reload();" : '' ; ?>"><br>
<script language="JavaScript" src="Scripts/validate.js"></script>

<!--
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="form1.offset.value=document.body.scrollTop; return validate('pl', 'qualification_id', 'l', 'simple_id', 'r', 'gc_id', 'n', 'name', 'r','short_name','r', 'address', 'r', 'city', 'r', 'post', 'r', 'country_id', 'l', 'regon', 'n', 'phone1', 'n', 'phone2', 'n', 'phone3', 'n', 'fax1', 'n', 'fax2', 'n', 'mobile1', 'n', 'mobile2', 'n');">
//-->

<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="form1.offset.value=document.body.scrollTop; return validate('pl', 'qualification_id', 'l', 'gc_id', 'n', 'name', 'r', 'city', 'r', 'country_id', 'l', 'regon', 'n' <?php echo $branchValidate ?>);">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" border="0">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="4" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo CONTRAHENTADD ?></strong></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><?php echo TYPE ?>&nbsp;</td>
    <td colspan="2" align="left" nowrap><select name="contrahenttype_id" class="required" id="contrahenttype_id" >
      <option value="0" <?php if (!(strcmp(0, $row_contrahent['contrahenttype_id']))) {echo "SELECTED";} ?>></option>
      <?php
do {
?>
      <option value="<?php echo $row_contrahenttypes['contrahenttype_id']?>"<?php if (!(strcmp($row_contrahenttypes['contrahenttype_id'], $row_contrahent['contrahenttype_id']))) {echo "SELECTED";} ?>><?php echo $row_contrahenttypes['value']?></option>
      <?php
} while ($row_contrahenttypes = mysql_fetch_assoc($contrahenttypes));
  $rows = mysql_num_rows($contrahenttypes);
  if($rows > 0) {
      mysql_data_seek($contrahenttypes, 0);
    $row_contrahenttypes = mysql_fetch_assoc($contrahenttypes);
  }
?>
    </select><input name="contrahenttype_id_old" type="hidden" value="<?php echo $row_contrahent['contrahenttype_id'] ?>">

  <span style="margin-left:10px;padding:6px;background-color:#FFFFFF;"><b>TU Polskie</b>  <input  class="required" <?php echo $row_contrahent["tu_pl"] == 1 ? 'checked'  : ''; ?>  type="checkbox" id="tu_pl" name="tu_pl" value="1"> </span>

    </td>
    <td width="188" align="center" nowrap bgcolor="#f9f9f9" style="border-left: #eeeeee 1px solid; border-top: #eeeeee 1px solid; border-bottom: #eeeeee 1px solid"><strong><?php echo ID ?></strong>&nbsp;
        <input name="contrahent_id" type="text" id="contrahent_id" style="background: yellow; text-align: center" value="<?php echo $row_contrahent['contrahent_id']; ?>" size="6" maxlength="4" readonly="yes">
    </td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo QUALIFICATION ?>&nbsp;</td>
    <td  align="left" nowrap><select name="qualification_id" id="qualification_id" class="required">
      <option value="" <?php if (!(strcmp("", $row_contrahent['qualification_id']))) {echo "SELECTED";} ?>></option>
      <?php
do {
?>
      <option value="<?php echo $row_qualification['qualification_id']?>"<?php if (!(strcmp($row_qualification['qualification_id'], $row_contrahent['qualification_id']))) {echo "SELECTED";} ?> style="color: <?php echo $row_qualification['color']?>"><?php echo $row_qualification['value']?></option>
      <?php
} while ($row_qualification = mysql_fetch_assoc($qualification));
  $rows = mysql_num_rows($qualification);
  if($rows > 0) {
      mysql_data_seek($qualification, 0);
    $row_qualification = mysql_fetch_assoc($qualification);
  }
?>
    </select></td>
<?php if(1 ==$_SESSION['coris_branch']): ?>
      <td align="right"><strong><?PHP echo BRANCH; ?></strong></td>
      <td>
          <label for="branch2" class="required"><input  class="required" <?php echo $branchSelect2; ?>  type="checkbox" id="branch2" name="coris_branch[]" value="2"><?php echo CORIS_BRANCH_NAME_2; ?></label>
      </td>
<?php else: ?>
      <td></td><td></td>
<?php endif; ?>

  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><strong>Grupa &nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><div align="left">
       <input  class="required" <?php echo $row_contrahent["group"] == 1 ? 'checked'  : ''; ?>  type="checkbox" id="group" name="group" value="1" onchange=" document.getElementById('sage').disabled = !this.checked; if (!this.checked) document.getElementById('sage').value='';">
    </div></td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><strong>Sage &nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><div align="left">
       <input  class="" <?php echo $row_contrahent["group"] == 0 ? 'disabled'  : ''; ?>  type="text" id="sage" name="sage" value="<?php  echo $row_contrahent["sage"] ?>">
    </div></td>
  </tr>

  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo SIMPLE ?><strong>&nbsp;</strong></td>
    <td width="191" align="left" nowrap><input name="simple_id" type="text" id="simple_id" value="<?php echo $row_contrahent['simple_id']; ?>" size="9" maxlength="8"></td>
    <td width="100" align="right" nowrap><?php echo GCID ?>&nbsp;</td>
    <td align="left" nowrap><input name="gc_id" type="text" id="gc_id" style="text-align: center; background: yellow" value="<?php echo $row_contrahent['gc_id']; ?>" size="4" maxlength="5">
&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo FULLNAME ?></strong></td>
    <td colspan="3" align="left" nowrap><input name="name" type="text" class="required" id="name" value="<?php echo htmlspecialchars($row_contrahent['name'],ENT_QUOTES ,'ISO-8859-1'); ?>" size="60" maxlength="255"></td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><strong>Krótka nazwa &nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><div align="left">
        <input name="short_name" type="text" class="required" id="short_name" value="<?php echo htmlspecialchars($row_contrahent['short_name'],ENT_QUOTES ,'ISO-8859-1'); ?>" size="40" maxlength="100">
    </div></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo ADDRESS ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="address" type="text" id="address" value="<?php echo $row_contrahent['address']; ?>" size="47" maxlength="50"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo CITY ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="city" type="text" class="required" id="city" value="<?php echo $row_contrahent['city']; ?>" size="30" maxlength="30">
&nbsp;<?php echo POST ?>&nbsp;
      <input name="post" type="text" id="post" value="<?php echo $row_contrahent['post']; ?>" size="7" maxlength="7"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo PROVINCE ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap><select name="province_id" id="province_id">
      <option value="0" <?php if (!(strcmp(0, $row_contrahent['province_id']))) {echo "SELECTED";} ?>></option>
      <?php
do {
?>
      <option value="<?php echo $row_provinces['province_id']?>"<?php if (!(strcmp($row_provinces['province_id'], $row_contrahent['province_id']))) {echo "SELECTED";} ?>><?php echo $row_provinces['value']?></option>
      <?php
} while ($row_provinces = mysql_fetch_assoc($provinces));
  $rows = mysql_num_rows($provinces);
  if($rows > 0) {
      mysql_data_seek($provinces, 0);
    $row_provinces = mysql_fetch_assoc($provinces);
  }
?>
    </select></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo COUNTRY ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap>

        <?php echo Application :: countryList($row_contrahent['country_id'], $lang , $idName = "country_id", 'class="required"');?>
    </td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo NIP ?></strong>&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="nip" type="text" id="nip" value="<?php echo $row_contrahent['nip']; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo REGON ?>&nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><input name="regon" type="text" id="regon" value="<?php echo $row_contrahent['regon']; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo PHONE ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="phone1" type="text" id="phone1" value="<?php echo $row_contrahent['phone1']; ?>" size="25" maxlength="25"></td>
    <td align="right" nowrap><strong><?php echo FAX ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="fax1" type="text" id="fax1" value="<?php echo $row_contrahent['fax1']; ?>" size="25" maxlength="25"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone2" type="text" id="phone2" value="<?php echo $row_contrahent['phone2']; ?>" size="25" maxlength="25"></td>
    <td align="left" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="fax2" type="text" id="fax2" value="<?php echo $row_contrahent['fax2']; ?>" size="25" maxlength="25"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone3" type="text" id="phone3" value="<?php echo $row_contrahent['phone3']; ?>" size="25" maxlength="25"></td>
    <td colspan="2" align="left" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><strong><?php echo MOBILE ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="mobile1" type="text" id="mobile1" value="<?php echo $row_contrahent['mobile1']; ?>" size="20" maxlength="20"></td>
    <td colspan="2" rowspan="6" align="center" valign="middle" nowrap><table width="85%"  border="0" cellspacing="2" cellpadding="1" style="background: #f9f9f9; border: #eeeeee 1px solid;">
        <tr>
          <td width="13%">&nbsp;</td>
          <td width="77%">&nbsp;</td>
          </tr>
      <!--   <tr>
          <td align="right"><input <?php if (!(strcmp($row_contrahent['locked'],1))) {echo "checked";} ?> name="locked" type="checkbox" id="locked" value="checkbox" style="background: #f9f9f9">
          </td>
          <td align="left"><?php echo LOCKED ?>&nbsp;<img src="Graphics/locked.gif" width="15" height="15" border="1"></td>
          </tr>
          -->
        <tr>
          <td align="right"><input <?php if (!(strcmp($row_contrahent['attention'],1))) {echo "checked";} ?> name="attention" type="checkbox" id="attention" value="checkbox" style="background: #f9f9f9">
          </td>
          <td align="left" bgcolor="yellow"><?php echo ATTENTION ?></td>
          </tr>
        <tr>
          <td align="right"><input <?php if ($row_contrahent['o_klnotuse']==1) {echo "checked";} ?> name="o_klnotuse" type="checkbox" id="o_klnotuse" value="1" style="background: #f9f9f9"></td>
      <td bgcolor="red">Nie u¿ywaj,
            Zamiast u¿yj
            <input name="contrahent_substitute" id="contrahent_substitute" type="text" size="5" maxlength="5" value="<?php echo $row_contrahent['contrahent_substitute']; ?>"></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
    </table></td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="mobile2" type="text" id="mobile2" value="<?php echo $row_contrahent['mobile2']; ?>" size="20" maxlength="20"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo EMAIL ?>&nbsp;</td>
    <td align="left" nowrap><input name="email" type="text" value="<?php echo $row_contrahent['email']; ?>" size="35" maxlength="200"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo WWW ?>&nbsp;</td>
    <td align="left" nowrap><input name="www" type="text" value="<?php echo $row_contrahent['www']; ?>" size="35" maxlength="40"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap>E-	mail windykacyjny&nbsp;</td>
    <td align="left" nowrap><input name="email_vindication" type="text" value="<?php echo $row_contrahent['email_vindication']; ?>" size="35" maxlength="40"></td>
  </tr>

  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td colspan="3"><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
      <input name="offset" type="hidden" id="offset">
        <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="window.close();">
        <input type="submit" class="submit" value="<?php echo SAVE ?>" onClick="return check_form_kontrahent();"></td>
  </tr>
</table>
<input type="hidden" name="MM_update" value="form1">
</form>
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" disabled>
        <tr valign="baseline" bgcolor="#FFFFFF">
        <td width="111" align="right" nowrap><?php echo ADDEDBY ?>&nbsp;</td>
        <td width="483"><?php echo $row_contrahent['username']; ?></td>

        </tr>
        <tr valign="baseline" bgcolor="#FFFFFF">
        <td width="111" align="right" nowrap><?php echo ADDEDDATE ?>&nbsp;</td>
        <td width="483"><?php echo $row_contrahent['date']; ?></td>
        </tr>
        <tr valign="baseline" bgcolor="#FFFFFF">
                <td align="right" nowrap><?php echo MODIFIEDBY ?>&nbsp;</td>
            <td width="483"><?php echo $row_contrahent['usernamemod']; ?></td>
  </tr>
        <tr valign="baseline" bgcolor="#FFFFFF">
        <td width="111" align="right" nowrap><?php echo MODIFIEDDATE ?>&nbsp;</td>
        <td width="483"><?php echo $row_contrahent['modification_date']; ?></td>
        </tr>
</table>
<form name="form3" method="POST" action="<?php echo $editFormAction; ?>" onSubmit="form3.offset.value=document.body.scrollTop; return validate('pl', 'note', 'r')">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo COMMENT ?></strong></td>
  </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;
    </td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="middle" nowrap>&nbsp;</td>
    <td width="483"><textarea name="note" cols="60" rows="6" id="note"><?php echo $row_note['note']; ?></textarea></td>
  </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>        <input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
      <input name="offset" type="hidden" id="offset">
      <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
<input type="hidden" name="MM_update" value="form3">
</form>
<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo CONTACTS ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCCCCC">
          <th width="20"></th>
          <th width="20"></th>
          <th width="90"><?php echo POSITION ?></th>
          <th width="343"><?php echo FULLNAME ?></th>
          <th width="343"><?php echo PHONE ?></th>
          <th width="343"><?php echo FAX ?></th>
          <th width="343"><?php echo MOBILE ?></th>
          <th width="343"><?php echo EMAIL ?></th>
        </tr>
        <?php if ($totalRows_contacts) do { ?>
        <tr bgcolor="<?PHP if ($row_contacts['attention']) { echo "yellow"; } ?>">
          <td nowrap><input type="button" title="Modyfikacja kontaktu" value="&gt;" style="width: 20px" onClick="document.location='GEN_contrahents_details_contacts_details.php?contrahent_id=<?php echo $_GET['contrahent_id']; ?>&contact_id=<?php echo $row_contacts['contact_id']; ?>&offset='+ document.body.scrollTop"></td>
          <td nowrap><input type="button" title="Usuñ kontakt" value="X" style="width: 20px" onclick="if (confirm('<?php echo CONTACTREMOVECONFIRM ?>')) document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&contact_id=<?PHP echo $row_contacts['contact_id'] ?>&offset='+ document.body.scrollTop"></td>
          <td align="left" nowrap><font color="#000099"><?php echo $row_contacts['position']; ?></font></td>
          <td align="left"><?php echo $row_contacts['contact_name']; ?></td>
          <td align="left"><em><?php echo $row_contacts['phone']; ?></em></td>
          <td align="left"><em><?php echo $row_contacts['fax']; ?></em></td>
          <td align="left"><em><?php echo $row_contacts['mobile']; ?></em></td>
          <td align="left"><?php echo $row_contacts['email']; ?></td>
        </tr>
        <?php } while ($row_contacts = mysql_fetch_assoc($contacts)); ?>
    </table></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><div align="center">
        <input name="Button" type="button" value="<?php echo CONTACTADD ?>" onClick="document.location='GEN_contrahents_details_contacts_add.php?contrahent_id=<?php echo $_GET['contrahent_id']; ?>&offset='+document.body.scrollTop">
    </div></td>
  </tr>
</table>
<a name="konta"></a><br>
<?php
if ($_SESSION['new_user']==0)
{
?>
<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo ACCOUNTS ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCCCCC">
          <th width="20">&nbsp;</th>
          <th width="20">&nbsp;</th>
          <th><?php echo ACCOUNT ?></th>
          <th><?php echo FULLNAME ?></th>
          <th width="40"><?php echo COUNTRY ?></th>
          <th width="90"><?php echo DATE ?></th>
          <th width="20"><?= GEN_CN_SORT ?></th>
        </tr>
        <?php
      $i = $totalRows_accounts;
    while ($row_accounts = mysql_fetch_array($accounts)) {
    ?>
        <tr >
          <td align="left"><input name="button2" title="<?= GEN_CN_MODKONT ?>" type="button" style="width: 20px" onClick="document.location='GEN_contrahents_details_accounts.php?contrahent_id=<?php echo $_GET['contrahent_id']; ?>&action=update&account_id=<?PHP echo $row_accounts['account_id'] ?>&offset='+ document.body.scrollTop" value="&gt;"></td>
          <td align="left"><input name="button3" title="<?= GEN_CN_DELKONT ?>" type="button" style="width: 20px" onClick="if (confirm('<?= GEN_CN_CONFDELKONT ?>')) document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&action=delete&account_id=<?PHP echo $row_accounts['account_id'] ?>&offset='+ document.body.scrollTop" value="X"></td>
          <td align="left"><strong><?php echo $row_accounts['account']; ?></strong></td>
          <td align="left"><?php echo $row_accounts['name']; ?></td>
          <td align="left"><?php echo $row_accounts['country_id']; ?></td>
          <td align="center"><?php echo $row_accounts['date']; ?></td>
          <td align="center"><?php echo $row_accounts['order']; ?></td>
        </tr>
        <tr>
          <td colspan="7" align="left"><small><em><font color="#3399FF"><?php echo $row_accounts['note']; ?></font></em></small></td>
        </tr>
        <?php } ?>
    </table></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><div align="center">
        <input name="Button" type="button" value="<?php echo ACCOUNTADD ?>" onClick="document.location='GEN_contrahents_details_accounts_add.php?contrahent_id=<?php echo $_GET['contrahent_id']; ?>&offset='+document.body.scrollTop">
    </div></td>
  </tr>
</table>

<form name="form2" method="POST" action="<?php echo $editFormAction; ?>" onSubmit="form2.offset.value=document.body.scrollTop; return validate('pl', 'default_payment_due', 'n')">
<table width="600" align="center" cellpadding="5" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo DEFAULTVALUES ?></strong></td>
  </tr>
  <tr valign="baseline">
    <td width="250" align="right" valign="baseline" nowrap><?php echo URGENT ?>&nbsp;
    </td>
    <td width="344" align="left" nowrap>
      <input <?php if (!(strcmp($row_contrahent['default_urgent'],1))) {echo "checked";} ?> name="default_urgent" type="checkbox" id="default_urgent" value="checkbox"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo LOGISTICS ?>&nbsp;</td>
    <td><input <?php if (!(strcmp($row_contrahent['default_logistics'],1))) {echo "checked";} ?> name="default_logistics" type="checkbox" id="default_logistics" value="checkbox"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_DOMJEZ ?>&nbsp; </td>
    <td><?php
  echo print_contrahents_language('contrahents_language',$row_contrahent['KLJEZDOM'],'');
?></td>
  </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap><hr></td>
    </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_DOMWALFAKTIN ?><br>
       </td>
    <td> <?php echo print_currency_all('default_currency_in',$row_contrahent['default_currency_in'],''); ?>
</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_DOMTERMPLFAKT ?></td>
    <td><input name="default_payment_due" type="text" id="due_date_fee" size="3" maxlength="3" value="<?php echo $row_contrahent['default_payment_due']; ?>">
Dni</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_DOMFORMPLFAKTPRZYCH ?> </td>
    <td><?php echo print_paymenttypes("default_paymenttype_id",$row_contrahent['default_paymenttype_id'],''); ?></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right" colspan="2"><hr></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"> <?= GEN_CN_IGNNIP ?> </td>
    <td><input type="checkbox" name="default_ignore_contrahent_nip" value="1" <? echo ($row_contrahent['default_ignore_contrahent_nip']==1) ? 'checked' : '';  ?>></td>
  </tr>
   <tr valign="baseline">
    <td nowrap align="right"> <?= GEN_CN_ZNIZKA ?> </td>
    <td><input type="checkbox" name="default_reduction" value="1" <? echo ($row_contrahent['default_reduction']==1) ? 'checked' : '';  ?>></td>
  </tr>
    <tr valign="baseline">
    <td nowrap align="right" colspan="2"><hr></td>
  </tr>
     <tr valign="baseline">
    <td nowrap align="right"> <?= GEN_CN_VAT ?> </td>
    <td><input type="checkbox" name="vat_enable" value="1" <? echo ($row_contrahent['vat_enable']==1) ? 'checked' : '';  ?>></td>
     </tr>
  </tr>
     <tr valign="baseline">
    <td nowrap align="right"> <?= GEN_CN_BOOK_760_4 ?> </td>
    <td><input type="checkbox" name="boook_760_4" value="1" <? echo ($row_contrahent['boook_760_4']==1) ? 'checked' : '';  ?>></td>
     </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
      <input name="offset" type="hidden" id="offset">
        <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
<input type="hidden" name="MM_update" value="form2">
<br>
</form><a name="umowy"></a>

<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong><?= GEN_CN_UMOWY ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCCCCC">
          <th width="20" rowspan="2"></th>
          <th width="20" rowspan="2">&nbsp;</th>
          <th width="20" rowspan="2">&nbsp;</th>
          <th width="200" colspan="2"><?= GEN_CN_DATOBOWIAZ ?> </th>
          <th width="150" rowspan="2"><?= GEN_CN_DATMOD ?> </th>
          <th width="150" rowspan="2"><?= USER ?></th>
        </tr>
        <tr bgcolor="#CCCCCC">
        	<td width="100">Od</td>
        	<td width="100">Do</td>
        </td>
        <?php
      $query = "SELECT * FROM coris_contrahents_initials WHERE contrahent_id='$id_contrahent' ORDER BY active_date DESC ";
          $resuly_initials = mysql_query($query);
          while ($row_initials = mysql_fetch_array($resuly_initials)) { ?>
        <tr bgcolor="#EEEEEE">
          <td nowrap><input name="button" type="button" title="<?= GEN_CN_ZMUM ?>" style="width: 20px" onClick="document.location='GEN_contrahents_details_initials.php?contrahent_id=<?php echo $id_contrahent ?>&initials_id=<?php echo $row_initials['ID']; ?>&action=update&offset='+ document.body.scrollTop" value="&gt;"></td>
          <td align="left" nowrap><input name="button32" title="<?= GEN_CN_USUM ?>" type="button" style="width: 20px" onclick="if (confirm('<?= GEN_CN_CONDELUM ?>')) document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&action=delete&initials_id=<?PHP echo $row_initials['ID']; ?>&offset='+ document.body.scrollTop" value="X"></td>
          <td align="center" nowrap><?php echo $row_initials['lump']==1 ? 'R' : ''; ?></td>
          <td align="left" nowrap><font color="#000099"><?php echo $row_initials['active_date']; ?></font></td>
          <td align="left" nowrap><font color="#000099"><?php echo $row_initials['end_date']; ?></font></td>
          <td align="left"><?php echo $row_initials['date']; ?></td>
          <td align="left"><em><?php echo getUserName($row_initials['user_id']); ?></em></td>
        </tr>
        <?php
          if (trim($row_initials['name']) <> ''){
          	echo '<tr bgcolor="#EEEEEE"><td colspan="3">&nbsp;</td><td colspan="4">'.$row_initials['name'].'</td></tr>';
          }

          } ?>
    </table></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><div align="center">
        <input name="Button2" type="button" value="<?= GEN_CN_DODUM ?>" onClick="document.location='GEN_contrahents_details_initials.php?action=add&contrahent_id=<?php echo $_GET['contrahent_id']; ?>&action=add&offset='+document.body.scrollTop">
    </div></td>
  </tr>
</table>
<?php

}

?>
<br>
<br>
<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo LINKS ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCCCCC">
          <th width="20"></th>
          <th width="40"><?php echo ID ?></th>
          <th><?php echo FULLNAME ?></th>
          <th width="130"><?php echo TYPE ?></th>
        </tr>
        <?php if ($totalRows_links) do { ?>
        <tr>
          <td nowrap><input type="button" value="X" style="width: 20px" onclick="if (confirm('<?php echo LINKREMOVECONFIRM ?>')) document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&link_id=<?PHP echo $row_links['link_id'] ?>&offset='+ document.body.scrollTop"></td>
          <td align="right" nowrap><font color="#000099"><?php echo $row_links['linked_contrahent_id']; ?></font></td>
          <td align="left"><?php echo $row_links['name']; ?></td>
          <td width="130" align="center"><?php echo $row_links['type']; ?></td>
        </tr>
        <?php } while ($row_links = mysql_fetch_assoc($links)); ?>
    </table></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><div align="center">
        <input name="Button" type="button" value="<?php echo LINKADD ?>" onClick="document.location='GEN_contrahents_details_links_add.php?contrahent_id=<?php echo $_GET['contrahent_id']; ?>&offset='+document.body.scrollTop">
    </div></td>
  </tr>
</table>


<br><br>
<script>

function EditAddress(id) {
		document.location='GEN_contrahents_details_work_adresses.php?contrahent_id=<?php echo $contrahent_id; ?>&action=edit&address_id='+id;
}

function EditContrahent(s) {
				window.open('AS_cases_details_expenses_position_details.php?expense_id='+ s,'PositionDetails','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=600,height=470,left='+ (screen.availWidth - 400) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 200) / 2);
}


function check_form_kontrahent(){
	if (document.getElementById('o_klnotuse').checked  ){
			if (document.getElementById('contrahent_substitute').value > 0){

			}else{
				alert('Prosze podaæ w polu "Nie u¿ywaj, Zamiast u¿yj" ID kontrahenta');
				return false;
			}
	}


	return true;


}
</script>
<a name="adresy"></a>
<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong>Adresy prowadzenia dzia³alno¶ci </strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCCCCC">
          <th width="20"></th>
          <th width="270">Info</th>
          <th width="50">Kod</th>
          <th width="120">Miasto</th>
          <th>Adres</th>

        </tr>
<?php


    $query = "SELECT *
    	FROM coris_contrahents_work_addresses
    WHERE contrahent_id  = '$contrahent_id'
    ORDER BY ID
     ";
    $mr = mysql_query($query);
    $licznik=0;
   while ($row = mysql_fetch_array($mr)){
       echo  '<tr bgcolor="'.($licznik%2 ? '#AAAAAA': '#BBBBBB').'"  '.($row['active']==0 ? 'style="text-decoration:line-through;"' : '').'>
          <td nowrap><input type="button" value=">" style="width: 20px" onclick="EditAddress('.$row['ID'].')"></td>
          <td align="center" >'.$row['info'].'</td>
          <td align="left" >'.$row['post'].'</td>
          <td align="center" nowrap>'.$row['city'].'</td>
          <td align="center" nowrap>'.$row['address'].'</td>
        </tr>';
       $licznik++;
   }
    echo '
    </table>
  ';

?>
</td>  </tr>

<tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><hr><div align="center">
        <input name="Button" type="button" value="Dodaj adres" onClick="document.location='GEN_contrahents_details_work_adresses.php?contrahent_id=<?php echo $_GET['contrahent_id']; ?>&amp;action=add'">
    </div></td>
  </tr>
</table>


<br>



<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong>Oceny</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCCCCC">
          <th width="20"></th>
          <th width="40">Ocena</th>
          <th>Opis</th>
          <th width="130">Data</th>
          <th width="80">U¿ytkownik</th>
        </tr>
<?php


    $query = "SELECT coris_contrahents_rank.*,coris_contrahents_rank_def.value
    	FROM coris_contrahents_rank,coris_contrahents_rank_def,coris_assistance_cases_expenses
    WHERE coris_assistance_cases_expenses.contrahent_id  = '$contrahent_id'
    AND   coris_assistance_cases_expenses.expense_id =  coris_contrahents_rank.ID_expences
    AND   coris_contrahents_rank_def.ID = coris_contrahents_rank.ID_rank


    ORDER BY coris_contrahents_rank.ID
     ";
    $mr = mysql_query($query);
    	//$res =  show_rank_form(1,$rr['ID_rank'],$rr['note'],$rr['date'].' '.getUserInitials($row['ID_user']));
   while ($row = mysql_fetch_array($mr)){
       echo  '<tr>
          <td nowrap><input type="button" value=">" style="width: 20px" onclick="EditContrahent('.$row['ID_expences'].')"></td>
          <td align="center" nowrap>'.$row['value'].'</td>
          <td align="left" >'.$row['note'].'</td>
          <td align="center" nowrap>'.$row['date'].'</td>
          <td align="center" nowrap>'.getUserInitials($row['ID_user']).'</td>

        </tr>';
   }
    echo '
    </table>
  ';

?>
</td>  </tr>
</table>


<br>
<br>

<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
    <tr valign="baseline" bgcolor="#CCCCCC">
        <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong>Historia zmian</strong></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
        <td align="right" nowrap></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
        <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#CCCCCC">
                    <th width="20"></th>
                    <th width="100">Parametr</th>
                    <th  width="180">Warto¶æ</th>
                    <th width="130">Data</th>
                    <th width="80">U¿ytkownik</th>
                </tr>
                <?php

                $query = "SELECT * FROM coris_contrahents_logs WHERE ID_contrahent='$contrahent_id' ORDER BY ID DESC" ;
                $mr = mysql_query($query);

                while ($row = mysql_fetch_array($mr)){
                    echo  '<tr>
                          <td nowrap>&nbsp;</td>
                          <td align="center" nowrap>'.$row['field'].'</td>
                          <td align="left" >'.$row['new_value'].'</td>
                          <td align="center" nowrap>'.$row['date'].'</td>
                          <td align="center" nowrap>'.getUserInitials($row['ID_user_accept']).'</td>
                    </tr>';
                }

                echo '
    </table>
  ';

                ?>
                </td>  </tr>
            </table>
<br>
<br>
            <a name="zmiany"></a>
<table width="600" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
    <tr valign="baseline" bgcolor="#CCCCCC">
        <td width="596" align="center" nowrap style="border: #000000 1px solid;"><strong>Zmiany do zatwierdzenia</strong></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
        <td align="right" nowrap></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
        <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#CCCCCC">
                    <th width="20"></th>
                    <th width="100">Parametr</th>
                    <th  width="180">Warto¶æ</th>
                    <th width="130">Data</th>
                    <th width="80">U¿ytkownik</th>
                </tr>
                <?php







                $query = "SELECT * FROM coris_contrahents_check WHERE ID_contrahent='$contrahent_id' ORDER BY ID";
                $mr = mysql_query($query);

                while ($row = mysql_fetch_array($mr)){
                    $action= "document.location='GEN_contrahents_queue.php?id=c_".$row['ID']."'";
                    echo  '<tr>
                          <td nowrap><input type="button" value=">" style="width: 20px" onclick="'.$action.'"></td>
                          <td align="center" nowrap>Nr NIP</td>
                          <td align="left" >'.$row['nip'].'</td>
                          <td align="center" nowrap>'.$row['date'].'</td>
                          <td align="center" nowrap>'.getUserInitials($row['ID_user']).'</td>
                    </tr>';
                }

                $query = "SELECT * FROM coris_contrahents_accounts_check WHERE contrahent_id='$contrahent_id' ORDER BY ID";
                $mr = mysql_query($query);

                while ($row = mysql_fetch_array($mr)){
                    $action= "document.location='GEN_contrahents_queue.php?id=a_".$row['ID']."'";
                    echo  '<tr>
                          <td nowrap><input type="button" value=">" style="width: 20px" onclick="'.$action.'"></td>
                          <td align="center" nowrap>'.($row['account_id'] > 0 ? 'Nr rachunku' : 'Nowe konto bankowe').'</td>
                          <td align="left" >'.$row['account'].'</td>
                          <td align="center" nowrap>'.$row['date'].'</td>
                          <td align="center" nowrap>'.getUserInitials($row['user_id']).'</td>
                    </tr>';
                }


                echo '
    </table>
  ';

                ?>
                </td>  </tr>
            </table>
<br><br><br>


</body>
</html>
<?php



?>