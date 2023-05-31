<?php include('include/include.php');
include('include/contrahent_monior.php');


$contrahent_id = getHttpValue('contrahent_id',0);
$account_id = getHttpValue('account_id',0);
$action = getHttpValue('action','');

$user_id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 0;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
<title><?= GEN_CN_KB_TITLE  ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="Scripts/validate.js"></script>
</head>

<body><br>
<?php

if ($action=='update_save' && $contrahent_id>0 && $account_id>0){
		zapisz_form_update($contrahent_id,$account_id);

}else if( $action=='update' && $contrahent_id>0  && $account_id>0){
	  $query = "SELECT * FROM coris_contrahents_accounts  WHERE account_id='$account_id' AND contrahent_id='$contrahent_id'  ";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)>0){
      		$row=mysql_fetch_array($mysql_result);
      		wysw_form($row,'update',$contrahent_id);
      }	
}

function zapisz_form_update($contrahent_id,$account_id){
	global $_superUsers;


	$account = getHttpValue('account');
	$swift = getHttpValue('swift');	
	$name = getHttpValue('name');
	$address = getHttpValue('address');	
	$post = getHttpValue('post');
	$city = getHttpValue('city');	
	$country_id = getHttpValue('country_id');
	
	$note = getHttpValue('note');
	$order = getHttpValue('order');



    $query = "UPDATE coris_contrahents_accounts  SET swift='$swift',name='$name',address='$address',post='$post',
	city='$city',country_id='$country_id',note='$note',`order`='$order',
	date=now(),user_id='".$_SESSION['user_id']."'
	WHERE account_id='$account_id' LIMIT 1";

    $mysql_result = mysql_query($query) or die ($query.'<br>'.mysql_error());


    $qt = "SELECT account FROM coris_contrahents_accounts WHERE account_id='$account_id'";
    $mr = mysql_query($qt);
    $r = mysql_fetch_array($mr);


    if ( $account != $r['account']) {
        if (in_array(Application::getCurrentUser(), $_superUsers)) {
            $qu = "UPDATE coris_contrahents_accounts  SET account='$account' WHERE account_id='$account_id' ";
            $mr = mysql_query($qu);

             zapiszLogZmian($contrahent_id,'','konto',$r['account'],$account);
        }else{
            dodajDoKolejkiZmian2($contrahent_id,$account_id,$account);

            $insertGoTo = "GEN_contrahents_details.php?contrahent_id=$contrahent_id#konta";

            echo "
            <div align='center'>Zmiana numeru konta  została wysłane do zatwierdzenia,<br> do momentu zatwierdzenia nie będzie to widoczne w danych kontrahenta            
            <br><br><br><br> <a href=\"".$insertGoTo."\">Powrót do danych kontrahenta</a>
            </div>";
            exit();
        }

    }






	 echo "<script> document.location='GEN_contrahents_details.php?contrahent_id=$contrahent_id#konta'</script>";
	  exit;
}

function wysw_form($row,$tryb,$contrahent_id){ 
	global $editFormAction;
	
$query_countries = "SELECT coris_countries.country_id, coris_countries.name, coris_countries.`prefix` FROM coris_countries WHERE coris_countries.active = 1 ORDER BY coris_countries.name";
$countries = mysql_query($query_countries) or die(mysql_error());
//	/onSubmit="return validate('pl', 'name', 'r', 'country_id', 'l', 'account', 'ra', 'user_id', 'l')">
	?>
<form name="form1" method="POST" action="GEN_contrahents_details_accounts.php"  onSubmit="return validate('pl', 'name', 'r', 'country_id', 'l',  'ra', 'user_id', 'l')">
<input type="hidden" name="contrahent_id" value="<?php echo $contrahent_id;?>">
<?php
	if ($tryb == 'add')
		echo '<input type="hidden" name="action" value="add_save">';
	else if ($tryb == 'update'){
		echo '<input type="hidden" name="action" value="update_save">';
		echo '<input type="hidden" name="account_id" value="'.$row['account_id'].'">';		
	}

?>


<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo ACCOUNTADD ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" nowrap><?= GEN_CN_KB_NAZWBANK ?>&nbsp;</td>
    <td width="483"><input name="name" type="text" class="required" id="name" size="50" maxlength="50" value="<?php echo $row['name']; ?>"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo ADDRESS ?>&nbsp;</td>
    <td><input name="address" type="text" id="address" size="50" maxlength="50" value="<?php echo $row['address']; ?>"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo CITY ?>&nbsp;</td>
    <td><input name="city" type="text" id="city" size="30" maxlength="30" value="<?php echo $row['city']; ?>" >
      &nbsp;<?php echo POST ?>&nbsp;
      <input name="post" type="text" id="post" size="6" maxlength="6" value="<?php echo $row['post']; ?>"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo COUNTRY ?>&nbsp;</td>
    <td><select name="country_id" class="required" id="country_id">
      <option value=""></option>
      <?php
while ($row_countries = mysql_fetch_array($countries)) {  
	 $sel = $row_countries['country_id'] == $row['country_id'] ? 'selected' : '';
     echo '<option value="'.$row_countries['country_id'].'" '.$sel.'>'.$row_countries['name'].' (+'.$row_countries['prefix'].')</option>';

} 

?>
    </select></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td align="right" nowrap><strong><?php echo ACCOUNT ?></strong>&nbsp;</td>
    <td><input name="account" type="text" class="required" id="account" size="50" maxlength="50" value="<?php echo $row['account']; ?>">
    </td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">SWIFT&nbsp;</td>
    <td><input name="swift" type="text" id="swift" size="20" maxlength="20" value="<?php echo $row['swift']; ?>"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_KB_UWAGI ?>&nbsp;</td>
    <td><textarea name="note" cols="75" rows="4" id="note"><?php echo $row['note']; ?></textarea></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_KB_KOL ?>: </td>
    <td><input name="order" type="text" id="order" size="6" maxlength="6" value="<?php echo $row['order']; ?>"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td height="22" align="right" nowrap>&nbsp;</td>
    <td>
        <input name="offset" type="hidden" id="offset" value="<?PHP echo $_GET['offset']; ?>">
        <input name="Button" type="button" class="cancel" value="<?php echo BACK; ?>" onClick="document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id']; ?>&offset=<?PHP echo $_GET['offset']; ?>'">
        <input type="submit" class="submit" value="<?php echo SAVE; ?>"></td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
<?php }
?>
</body>
</html>
