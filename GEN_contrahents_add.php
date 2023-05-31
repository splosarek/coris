<?php include('include/include.php');

include('include/contrahent_monior.php');
$lang = $_SESSION['GUI_language'];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// nie jest wymagane
// $branchValidate = ", 'branch', 'j'";
//if(2 == $_SESSION['coris_branch'])
//{
//    $branchValidate = "";
//}
$branchValidate = "";

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  
  $query_contrahent = sprintf("SELECT simple_id FROM coris_contrahents WHERE simple_id LIKE %s",
  					GetSQLValueString($_POST['simple_id'], "text"));

	$contrahent = mysql_query($query_contrahent) or die(mysql_error());
	$row_contrahent = mysql_fetch_assoc($contrahent);
	$totalRows_contrahent = mysql_num_rows($contrahent);

	if (!$totalRows_contrahent) {

        $branchSelect = getValue('coris_branch');
        $branchUpdate = '1';
        if(is_array($branchSelect))
        {
            if(in_array(2, $branchSelect) )
            {
                // dla wszystkich
                $branchUpdate = '0';
            }
        }

        // tylko dla DE
        // zmiana - jesli DE to dla wszystkich - dla DE tez
        if(2 == $_SESSION['coris_branch'])
        {
            $branchUpdate = '0';
            $branchValidate = '';
        }

	  $insertSQL = sprintf("INSERT INTO coris_contrahents (contrahenttype_id, gc_id, simple_id, name, short_name, address, post, province_id,
	                                    city, country_id, phone1, phone2, phone3, fax1, fax2, mobile1, mobile2, email, www, regon,
	                                    qualification_id, `locked`, attention, user_id, date, coris_branch_id)
                           VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,  %s, %s, %s, %s, %s, %s, %s)",
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
						   GetSQLValueString($_POST['www'], "text"),
						   GetSQLValueString($_POST['regon'], "text"),
						   GetSQLValueString($_POST['qualification_id'], "int"),
						   GetSQLValueString(isset($_POST['locked']) ? "true" : "", "defined","1","0"),
						   GetSQLValueString(isset($_POST['attention']) ? "true" : "", "defined","1","0"),
						   GetSQLValueString($_SESSION['user_id'], "int"),
						   GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                           $branchUpdate
                            );



	  $Result1 = mysql_query($insertSQL) or die(mysql_error());


	  $contrahent_id = mysql_insert_id();


       $nip = getValue('nip');

        if (in_array(Application::getCurrentUser(), $_superUsers)) {
            $qu = "UPDATE coris_contrahents SET nip='$nip'  WHERE contrahent_id='$contrahent_id'";
            $mr = mysql_query($qu);

            zapiszLogZmian($contrahent_id,'','nip','',$nip);
        }else{
            dodajDoKolejkiZmian($contrahent_id,$nip);
            $insertGoTo = "GEN_contrahents_details.php?contrahent_id=$contrahent_id";

            echo "
            <div align='center'>Zmiana numeru NIP  zosta³a wys³ana do zatwierdzenia,<br> do momentu zatwierdzenia nie bêdzie widoczna w danych kontrahenta            
            <br><br><br><br> <a href=\"".$insertGoTo."\">Powrót do danych kontrahenta</a>
            </div>";
            exit();
        }




	  $insertGoTo = "GEN_contrahents_details.php?contrahent_id=". $contrahent_id;
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $insertGoTo));
	} else {
			echo "<script>alert('Nazwa Simple ju¿ wykorzystana. Proszê wpisaæ inn±.');</script>";
	}
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

$user_id = (isset($_SESSION['user_id'])) ? $_SESSION['user_id'] : 0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?PHP echo CONTRAHENTADD ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>

<body><br>
<script language="JavaScript" src="Scripts/validate.js"></script>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return validate('pl', 'contrahenttype_id', 'l', 'qualification_id', 'l', 'simple_id', 'r', 'gc_id', 'n', 'name', 'r', 'address', 'r', 'city', 'r', 'post', 'r', 'country_id', 'l', 'regon', 'n', 'phone1', 'n', 'phone2', 'n', 'phone3', 'n', 'fax1', 'n', 'fax2', 'n', 'mobile1', 'n', 'mobile2', 'n', 'email', 'e' <?php echo $branchValidate ?>);">
<table align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" border="0">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="4" align="center" nowrap style="border: #000000 1px solid;"><strong><?php echo CONTRAHENTADD ?></strong></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><?php echo TYPE ?>&nbsp;</td>
    <td colspan="2" align="left" nowrap><select name="contrahenttype_id" class="required" id="contrahenttype_id">
        <option value="0"></option>
        <?php
do {  
?>
        <option value="<?php echo $row_contrahenttypes['contrahenttype_id']?>"><?php echo $row_contrahenttypes['value']?> (<?php echo $row_contrahenttypes['simple_id']?>)</option>
        <?php
} while ($row_contrahenttypes = mysql_fetch_assoc($contrahenttypes));
  $rows = mysql_num_rows($contrahenttypes);
  if($rows > 0) {
      mysql_data_seek($contrahenttypes, 0);
	  $row_contrahenttypes = mysql_fetch_assoc($contrahenttypes);
  }
?>
    </select></td>
    <td width="188" align="center" nowrap bgcolor="#f9f9f9" style="border-left: #eeeeee 1px solid; border-top: #eeeeee 1px solid; border-bottom: #eeeeee 1px solid"><strong><?php echo ID ?></strong>&nbsp;
      <input name="contrahent_id" type="text" id="contrahent_id" style="background: yellow; text-align: center" size="6" maxlength="4" readonly="yes">
</td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo QUALIFICATION ?>&nbsp;</td>
    <td align="left" nowrap><select name="qualification_id" id="qualification_id" class="required">
		<option value=""></option>	
      <?php
do {  
?>
      <option value="<?php echo $row_qualification['qualification_id']?>" style="color: <?php echo $row_qualification['color']?>"><?php echo $row_qualification['value']?></option>
      <?php
} while ($row_qualification = mysql_fetch_assoc($qualification));
  $rows = mysql_num_rows($qualification);
  if($rows > 0) {
      mysql_data_seek($qualification, 0);
	  $row_qualification = mysql_fetch_assoc($qualification);
  }
?>
    </select></td>
<?php if(1 == $_SESSION['coris_branch']): ?>
        <td align="right"><strong><?PHP echo BRANCH; ?></strong></td>
        <td>
            <label class="required"  for="branch2"><input   type="checkbox" id="branch2" name="coris_branch[]" value="2"><?php echo CORIS_BRANCH_NAME_2; ?></label>
        </td>
<?php else: ?>
        <td></td><td></td>
<?php endif; ?>

    </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo SIMPLE ?><strong>&nbsp;</strong></td>
    <td width="191" align="left" nowrap><input name="simple_id" type="text" class="required" id="simple_id" value="<?php echo (isset($_POST['simple_id'])) ? $_POST['simple_id'] : ""; ?>" size="9" maxlength="8"></td>
    <td width="100" align="right" nowrap><?php echo GCID ?>&nbsp;</td>
    <td align="left" nowrap><input name="gc_id" type="text" id="gc_id" value="<?php echo (isset($_POST['gc_id'])) ? $_POST['gc_id'] : ""; ?>" size="4" maxlength="5" style="text-align: center; background: yellow">
      &nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><strong><?php echo FULLNAME ?>&nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><div align="left">
      <input name="name" type="text" class="required" id="name" value="<?php echo (isset($_POST['name'])) ? $_POST['name'] : ""; ?>" size="30" maxlength="255">
</div></td>
    </tr>
  <tr valign="baseline">
    <td width="111" align="right" valign="baseline" nowrap><strong>Krótka nazwa &nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><div align="left">
        <input name="short_name" type="text" class="required" id="short_name" value="<?php echo (isset($_POST['short_name'])) ? $_POST['short_name'] : ""; ?>" size="40" maxlength="100">
    </div></td>
  </tr>    
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;    </td>
    </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo ADDRESS ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="address" type="text" class="required" id="address" value="<?php echo (isset($_POST['address'])) ? $_POST['address'] : ""; ?>" size="47" maxlength="50"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo CITY ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="city" type="text" class="required" id="city" value="<?php echo (isset($_POST['city'])) ? $_POST['city'] : ""; ?>" size="30" maxlength="30">      &nbsp;<?php echo POST ?>&nbsp;      <input name="post" type="text" class="required" id="post" value="<?php echo (isset($_POST['post'])) ? $_POST['post'] : ""; ?>" size="7" maxlength="7"></td>
    </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><?php echo PROVINCE ?>&nbsp;</td>
    <td colspan="3" align="left" nowrap><select name="province_id" id="province_id">
      <option value="0"></option>
      <?php
do {  
?>
      <option value="<?php echo $row_provinces['province_id']?>"><?php echo $row_provinces['value']?></option>
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
        <?php
        $defaultCountry = '';
        if (isset($_SESSION['coris_branch']) && 2 == $_SESSION['coris_branch'])
        {
            $defaultCountry = 'DE';
        }
        echo Application :: countryList($defaultCountry, $lang , $idName = "country_id", 'class="required"', true);
        ?>
    </td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo NIP ?></strong>&nbsp;</td>
    <td colspan="3" align="left" nowrap><input name="nip" type="text" id="nip" value="<?php echo (isset($_POST['nip'])) ? $_POST['nip'] : ""; ?>" size="15" maxlength="15"></td>
    </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo REGON ?>&nbsp;</strong></td>
    <td colspan="3" align="left" nowrap><input name="regon" type="text" id="regon" value="<?php echo (isset($_POST['regon'])) ? $_POST['regon'] : ""; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" valign="baseline" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap><strong><?php echo PHONE ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="phone1" type="text" id="phone1" value="<?php echo (isset($_POST['phone1'])) ? $_POST['phone1'] : ""; ?>" size="15" maxlength="15"></td>
    <td align="right" nowrap><strong><?php echo FAX ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="fax1" type="text" id="fax1" value="<?php echo (isset($_POST['fax1'])) ? $_POST['fax1'] : ""; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone2" type="text" id="phone2" value="<?php echo (isset($_POST['phone2'])) ? $_POST['phone2'] : ""; ?>" size="15" maxlength="15"></td>
    <td align="left" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="fax2" type="text" id="fax2" value="<?php echo (isset($_POST['fax2'])) ? $_POST['fax2'] : ""; ?>" size="15" maxlength="15"></td>
  </tr>
  <tr valign="baseline">
    <td align="right" valign="baseline" nowrap>&nbsp;</td>
    <td align="left" nowrap><input name="phone3" type="text" id="phone3" value="<?php echo (isset($_POST['phone3'])) ? $_POST['phone3'] : ""; ?>" size="15" maxlength="15"></td>
    <td colspan="2" align="left" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
    <td align="right" nowrap><strong><?php echo MOBILE ?></strong>&nbsp;</td>
    <td align="left" nowrap><input name="mobile1" type="text" id="mobile1" value="<?php echo (isset($_POST['mobile1'])) ? $_POST['mobile1'] : ""; ?>" size="15" maxlength="15"></td>
    <td colspan="2" rowspan="6" align="center" valign="middle" nowrap><table width="80%"  border="0" cellspacing="1" cellpadding="1" style="background: #f9f9f9; border: #eeeeee 1px solid;">
      <tr>
        <td width="16%">&nbsp;</td>
        <td width="76%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><input name="locked" type="checkbox" id="locked" value="checkbox" style="background: #f9f9f9">          </td>
        <td align="left"><?php echo LOCKED ?>&nbsp;<img src="Graphics/locked.gif" width="15" height="15" border="1"></td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><input name="attention" type="checkbox" id="attention" value="checkbox" style="background: #f9f9f9">          </td>
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
    <td align="left" nowrap><input name="mobile2" type="text" id="mobile2" value="<?php echo (isset($_POST['mobile2'])) ? $_POST['mobile2'] : ""; ?>" size="15" maxlength="15"></td>
    </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo EMAIL ?>&nbsp;</td>
    <td align="left" nowrap><input name="email" id="email" type="text" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ""; ?>" size="30" maxlength="30"></td>
    </tr>
  <tr valign="baseline">
    <td align="right" nowrap><?php echo WWW ?>&nbsp;</td>
    <td align="left" nowrap><input name="www" type="text" value="<?php echo (isset($_POST['www'])) ? $_POST['www'] : ""; ?>" size="30" maxlength="30"></td>
    </tr>
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td colspan="4" align="right" nowrap>&nbsp;</td>
    </tr>
  <tr valign="baseline">
    <td nowrap align="right">&nbsp;</td>
    <td colspan="3"><input type="hidden" name="case_id" value="<?PHP echo (isset($_GET['case_id'])) ? $_POST['case_id'] : ""; ?>">
        <input name="offset" type="hidden" id="offset" value="<?PHP echo (isset($_GET['offset'])) ? $_POST['offset'] : ""; ?>">
        <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="window.close();">
        <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>
</body>
</html>
<?php
mysql_free_result($provinces);

mysql_free_result($contrahenttypes);

mysql_free_result($qualification);

mysql_free_result($users);
?>
