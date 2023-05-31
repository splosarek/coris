<?php
include('include/include.php');
include('include/contrahent_monior.php');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {



    if (in_array(Application::getCurrentUser(), $_superUsers)) {


        $insertSQL = sprintf("INSERT INTO coris_contrahents_accounts (contrahent_id, account,swift, name, address, post, city, country_id, note, date, user_id,`order`) 
						VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'".$_POST['order']."')",
            GetSQLValueString($_POST['contrahent_id'], "int"),
            GetSQLValueString($_POST['account'], "text"),
            GetSQLValueString($_POST['swift'], "text"),
            GetSQLValueString($_POST['name'], "text"),
            GetSQLValueString($_POST['address'], "text"),
            GetSQLValueString($_POST['post'], "text"),
            GetSQLValueString($_POST['city'], "text"),
            GetSQLValueString($_POST['country_id'], "text"),
            GetSQLValueString($_POST['note'], "text"),
            GetSQLValueString(date('Y-m-d H:i:s'), "date"),
            GetSQLValueString($_SESSION['user_id'], "int"));

        $Result1 = mysql_query($insertSQL) or die("1".$insertSQL.'<br>'.mysql_error());

        $insertGoTo = "GEN_contrahents_details.php?contrahent_id=". $_POST['contrahent_id'] ."&offset=". $_POST['offset'];
        if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        }

        $contrahent_id = getValue('contrahent_id');
        $account_id = mysql_insert_id();

        zapiszLogZmian($contrahent_id,$account_id,'konto',"", getValue('account') );

        header(sprintf("Location: %s", $insertGoTo));
    }else{

        $insertGoTo = "GEN_contrahents_details.php?contrahent_id=". $_POST['contrahent_id'] ."&offset=". $_POST['offset'];

        $contrahent_id = getValue('contrahent_id');
        $account = getValue('account');
        $swift = getValue('swift');
        $name = getValue('name');
        $address = getValue('address');
        $post = getValue('post');
        $city = getValue('city');
        $country_id = getValue('country_id');
        $note  = getValue('note');
        $order = getValue('order');

        dodajDoKolejkiZmian2($contrahent_id,0,$account,$swift,$name,$address,$post,$city,$country_id,$note,$order);

        echo "<div align='center'>Dane zosta³y wys³ane do zatwierdzenia,<br> do momentu zatwierdzenia nie bêd± widoczne w danych kontrahenta

            <br><br><br><br> <a href=\"".$insertGoTo."\">Powrót do danych kontrahenta</a>
            </div>";




    }


  exit();
}


$query_countries = "SELECT coris_countries.country_id, coris_countries.name, coris_countries.`prefix` FROM coris_countries WHERE coris_countries.active = 1 ORDER BY coris_countries.name";
$countries = mysql_query($query_countries) or die(mysql_error());
$row_countries = mysql_fetch_assoc($countries);
$totalRows_countries = mysql_num_rows($countries);


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
<title><?PHP echo ACCOUNTADD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="Scripts/validate.js"></script>
</head>

<body><br>
<form name="form1" method="POST" action="<?php echo $editFormAction; ?>" onSubmit="return validate('pl', 'name', 'r', 'country_id', 'l',  'ra', 'user_id', 'l')">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo ACCOUNTADD ?></strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" nowrap><?= GEN_CN_KB_NAZWBANK ?>&nbsp;</td>
    <td width="483"><input name="name" type="text" class="required" id="name" size="50" maxlength="50"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo ADDRESS ?>&nbsp;</td>
    <td><input name="address" type="text" id="address" size="50" maxlength="50"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo CITY ?>&nbsp;</td>
    <td><input name="city" type="text" id="city" size="30" maxlength="30">
      &nbsp;<?php echo POST ?>&nbsp;
      <input name="post" type="text" id="post" size="6" maxlength="6"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?php echo COUNTRY ?>&nbsp;</td>
    <td><select name="country_id" class="required" id="country_id">
      <option value=""></option>
      <?php
do {  
?>
      <option value="<?php echo $row_countries['country_id']?>"><?php echo $row_countries['name']?> (+<?php echo $row_countries['prefix']?>)</option>
      <?php
} while ($row_countries = mysql_fetch_assoc($countries));
  $rows = mysql_num_rows($countries);
  if($rows > 0) {
      mysql_data_seek($countries, 0);
	  $row_countries = mysql_fetch_assoc($countries);
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
    <td><input name="account" type="text" class="required" id="account" size="50" maxlength="50">
    </td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">SWIFT&nbsp;</td>
    <td><input name="swift" type="text" id="swift" size="20" maxlength="20"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_KB_UWAGI ?>&nbsp;</td>
    <td><textarea name="note" cols="75" rows="4" id="note"></textarea></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right"><?= GEN_CN_KB_KOL ?>: </td>
    <td><input name="order" type="text" id="order" size="6" maxlength="6"></td>
  </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td>&nbsp;</td>
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
</body>
</html>
<?php
mysql_free_result($countries);

mysql_free_result($users);
?>
