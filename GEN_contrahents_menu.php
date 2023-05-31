<?php include('include/include.php'); 
$lang = $_SESSION['GUI_language'];

$query_contrahenttypes = "SELECT coris_contrahenttypes.contrahenttype_id, coris_contrahenttypes.`value`,  coris_contrahenttypes.`value_eng`, coris_contrahenttypes.simple_id FROM coris_contrahenttypes WHERE coris_contrahenttypes.active = 1 ORDER BY coris_contrahenttypes.`value`";
$contrahenttypes = mysql_query($query_contrahenttypes, $cn) or die(mysql_error());
$row_contrahenttypes = mysql_fetch_assoc($contrahenttypes);
$totalRows_contrahenttypes = mysql_num_rows($contrahenttypes);


$query_provinces = "SELECT coris_provinces.province_id, coris_provinces.`value` FROM coris_provinces WHERE coris_provinces.active = 1 ORDER BY coris_provinces.`value`";
$provinces = mysql_query($query_provinces, $cn) or die(mysql_error());
$row_provinces = mysql_fetch_assoc($provinces);
$totalRows_provinces = mysql_num_rows($provinces);

$filterList = Array(
'coris_contrahents.city' => array('name' => GEN_COUN_MIA, 'size'=>'12'),
'coris_contrahents.post' => array('name' => POST, 'size'=>'8'),
'coris_contrahents.contrahent_id' => array('name' => GEN_COUN_NRK, 'size'=>'6'),
'coris_contrahents.name' => array('name' => FULLNAME, 'size'=>'16'),
'phone' => array('name' => PHONE, 'size'=>'10'),
'gsm' => array('name' => MOBILE, 'size'=>'10'),
'coris_contrahents.email' => array('name' => EMAIL, 'size'=>'12'),
'br',
'coris_contrahents.date' => array('name' => DATE, 'size'=>'10'),
'coris_users.username' => array('name' => USER, 'size'=>'10'),
);


$filterRe = '';
foreach($filterList as $field => $fieldSettings)
{
    if('br' == $field)
    {
        $filterRe .= '<br/>';
    }else
    {
        $filterRe .= $fieldSettings['name'] .
        ': <input name="filter[' .$field. ']" type="text" id="filter[' .$field. ']" size="' . $fieldSettings['size'] . '" maxlength="30"> ';
    }
}



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<script language="JavaScript" src="Scripts/validate.js"></script>
<body>
<form action="GEN_contrahents_frame.php" method="get" name="form1" style="margin-bottom: 0px;"  target="frame">
	<table width="100%" height="100%" align="center" border="0">
		<tr valign="middle">
		  <td height="50%" nowrap>&nbsp;
            <input name="Button" type="button" onClick="MM_openBrWindow('GEN_contrahents_add.php','','scrollbars=yes,resizable=yes,width=650,height=620, left=175,top=40')" value="<?PHP echo CONTRAHENTADD ?>">
          </td>

		  <td height="50%" align="center" nowrap><?php echo TYPE ?>&nbsp;		    
		    <select name="contrahenttype_id" id="contrahenttype_id" onChange="form1.submit()">
		    <option value="0"></option>
		    <?php
do {  
?>
		    <option value="<?php echo $row_contrahenttypes['contrahenttype_id']?>"><?php echo ( ($lang=='en' && $row_contrahenttypes['value_eng'] != '' ) ? $row_contrahenttypes['value_eng'] : $row_contrahenttypes['value'] ); ?></option>
		    <?php
} while ($row_contrahenttypes = mysql_fetch_assoc($contrahenttypes));
  $rows = mysql_num_rows($contrahenttypes);
  if($rows > 0) {
      mysql_data_seek($contrahenttypes, 0);
	  $row_contrahenttypes = mysql_fetch_assoc($contrahenttypes);
  }
?>
	      </select>
&nbsp;<?php echo COUNTRY ?>
&nbsp;
<?php echo Application :: countryList('', $lang, 'country_id', 'onChange="form1.submit()"'); ?>

	      &nbsp;<?php echo PROVINCE ?>&nbsp;
	      <select name="province_id" id="province_id" onChange="form1.submit()">
	        <option value=""></option>
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
          </select>

<?php
    if (isset($_SESSION['coris_branch']) && 1 == $_SESSION['coris_branch'])
    {
        echo BRANCH . ': ' . print_user_coris_branch('filter[coris_contrahents.coris_branch_id]', 0, 'onChange="form1.submit()"');
    }
?>


        </td>
    </tr>
    <tr valign="middle">
	    <td colspan="2" align="center" >&nbsp;
            <?php echo $filterRe; ?>
        </td>
    </tr>
    <tr>
        <td colspan="2"  align="center">
            <input type="submit" name="Submit" value="<?PHP echo SEARCH ?>">
            <input type="reset" name="Reset" value="<?PHP echo CANCEL ?>" onClick="parent.frames['frame'].location='GEN_contrahents_frame.php'">
        </td>
    </tr>
	</table>
</form>
</body>
</html>
<?php
mysql_free_result($contrahenttypes);

mysql_free_result($provinces);
?>