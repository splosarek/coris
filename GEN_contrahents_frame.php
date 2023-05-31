<?php include('include/include.php');

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_contrahents = 100;
$pageNum_contrahents = 0;
if (isset($_GET['pageNum_contrahents'])) {
  $pageNum_contrahents = $_GET['pageNum_contrahents'];
}
$startRow_contrahents = $pageNum_contrahents * $maxRows_contrahents;


$query_contrahents = "SELECT coris_contrahents.contrahent_id, coris_contrahents.name, coris_contrahents.country_id,
                             CONCAT_WS(', ', coris_contrahents.phone1, coris_contrahents.phone2, coris_contrahents.phone3) AS phone,
                             CONCAT_WS(', ', coris_contrahents.fax1, coris_contrahents.fax2) AS fax,
                             CONCAT_WS(', ', coris_contrahents.mobile1, coris_contrahents.mobile2) AS mobile,
                             coris_contrahents.email, coris_contrahents.`locked`, coris_contrahents.attention,
                             coris_users.username, coris_colors.code AS color, DATE(coris_contrahents.`date`) AS date,
                             coris_contrahents_qualifications.`value` AS qualification,
                             coris_contrahents_qualifications.color AS qualification_color,
                             coris_contrahents_contacts.contact_id, coris_contrahents_contacts.position,
                             CONCAT_WS(' ', coris_contrahents_contacts.name, coris_contrahents_contacts.surname) AS contact_name,
                             CONCAT_WS(', ', coris_contrahents_contacts.phone1, coris_contrahents_contacts.phone2,
                             coris_contrahents_contacts.phone3) AS contact_phone, CONCAT_WS(', ', coris_contrahents_contacts.fax1,
                             coris_contrahents_contacts.fax2) AS contact_fax, CONCAT_WS(', ', coris_contrahents_contacts.mobile1,
                             coris_contrahents_contacts.mobile2) AS contact_mobile, coris_contrahents_contacts.email AS contact_email,
                             coris_contrahents_contacts.attention AS contact_attention,
                             cb.name AS coris_branch, city, post
                        FROM coris_users,
                             coris_contrahents_qualifications,
                             coris_contrahents
                   LEFT JOIN coris_colors ON  coris_colors.color_id = coris_contrahents.color_id
                   LEFT JOIN coris_provinces ON coris_contrahents.province_id = coris_provinces.province_id
                   LEFT JOIN coris_contrahents_contacts ON coris_contrahents.contrahent_id = coris_contrahents_contacts.contrahent_id
                             AND coris_contrahents_contacts.active = 1
                   LEFT JOIN coris_branch cb ON cb.ID=coris_contrahents.coris_branch_id
                       WHERE coris_contrahents.user_id = coris_users.user_id
                         AND coris_contrahents.qualification_id = coris_contrahents_qualifications.qualification_id
                         ";

if (isset($_GET['contrahenttype_id']) && $_GET['contrahenttype_id'] != 0) {
		$query_contrahents .= " AND coris_contrahents.contrahenttype_id = '$_GET[contrahenttype_id]' ";
}
if (isset($_GET['country_id']) && $_GET['country_id'] != '') {
		$query_contrahents .= " AND coris_contrahents.country_id = '$_GET[country_id]' ";
}
if (isset($_GET['province_id']) && $_GET['province_id'] != '') {
		$query_contrahents .= " AND coris_contrahents.province_id = '$_GET[province_id]' ";
}

$filterArray = (array)getValue('filter');

foreach($filterArray as $fieldName => $fieldValue)
{
    if($fieldValue != '')
    {
        if('coris_contrahents.coris_branch_id' == $fieldName)
        {
            $query_contrahents .= " AND (coris_contrahents.coris_branch_id ='$fieldValue'
                                                 OR coris_contrahents.coris_branch_id = 0 ) ";
        }
        else if (strstr($fieldName, "date")) {
            $query_contrahents .= " AND DATE(`$fieldName`) = '$fieldValue' ";
        } else if (strstr($fieldName, "phone")) {      // telefonow sa trzy pola
            $query_contrahents .= " AND (   coris_contrahents.phone1 LIKE '%$fieldValue%'
                                         OR coris_contrahents.phone2 LIKE '%$fieldValue%'
                                         OR coris_contrahents.phone3 LIKE '%$fieldValue%' ) ";

        } else if (strstr($fieldName, "gsm")) {        // gsm-ow sa trzy pola
            $query_contrahents .= " AND (   coris_contrahents.mobile1 LIKE '%$fieldValue%'
                                         OR coris_contrahents.mobile2 LIKE '%$fieldValue%' ) ";
        } else {
            $query_contrahents .= " AND $fieldName LIKE '%$fieldValue%' ";
        }
    }
}
/**
if (isset($_GET['fieldname']) && isset($_GET['fieldvalue']) && $_GET['fieldvalue'] != '') {
  if ($_GET['fieldname'] == 'contrahent_number') {
      $contrahent_number = split("/", $_GET['fieldvalue']);
      $query_contrahents .= " AND contrahent_number = $contrahent_number[0] AND contrahent_year = $contrahent_number[1] ";
  } else if (strstr($_GET['fieldname'], "date")) {
      $query_contrahents .= " AND DATE(`$_GET[fieldname]`) = '$_GET[fieldvalue]' ";
  } else {
      $query_contrahents .= " AND $_GET[fieldname] LIKE '%$_GET[fieldvalue]%' ";
  }
}
/**/

$userCorisBranchId = 0;
// jesli zalogowany oddzial 1 czyli Coris Polska to pokazac wszystkich (czyli bez filtra)
// jesli zalogowany z innego oddzialu, to pokazac tylko kontrahentow danego oddzialu
// w innych przypadkach nic nie pokazywac
/*if (isset($_SESSION['coris_branch']) && intval($_SESSION['coris_branch'])>0){
    if( $_SESSION['coris_branch'] == 1){
        $userCorisBranchId = 1;
    }else if( $_SESSION['coris_branch'] <> 1){
        $userCorisBranchId = intval($_SESSION['coris_branch']);
        $query_contrahents .= " AND (coris_contrahents.coris_branch_id ='$userCorisBranchId'
                                     OR coris_contrahents.coris_branch_id = 0 )
                              ";
    }
}else{
    $query_contrahents .= " AND coris_contrahents.coris_branch_id ='-1' ";
}

*/

$query_contrahents .= " AND coris_contrahents.active = 1
                        ORDER BY coris_contrahents.contrahent_id";
$query_limit_contrahents = sprintf("%s LIMIT %d, %d", $query_contrahents, $startRow_contrahents, $maxRows_contrahents);
//echo $query_contrahents;

$contrahents = mysql_query($query_limit_contrahents, $cn) or die(mysql_error());
$row_contrahents = mysql_fetch_assoc($contrahents);

if (isset($_GET['totalRows_contrahents'])) {
  $totalRows_contrahents = $_GET['totalRows_contrahents'];
} else {
  $all_contrahents = mysql_query($query_contrahents);
  $totalRows_contrahents = mysql_num_rows($all_contrahents);
}
$totalPages_contrahents = ceil($totalRows_contrahents/$maxRows_contrahents)-1;

$queryString_contrahents = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_contrahents") == false && 
        stristr($param, "totalRows_contrahents") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_contrahents = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_contrahents = sprintf("&totalRows_contrahents=%d%s", $totalRows_contrahents, $queryString_contrahents);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Untitled Document</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body>
<table border="0">
  <tr>
    <td align="center"><input name="" type="button" <?php if (!$pageNum_contrahents > 0) { echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value="<<|" onclick="document.location='<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, 0, $queryString_contrahents); ?>'">
    </td>
    <td align="center"><input name="" type="button" <?php if (!$pageNum_contrahents > 0) { echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value="<<" onclick="document.location='<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, max(0, $pageNum_contrahents - 1), $queryString_contrahents); ?>'">
    </td>
    <td align="center"><input name="" type="button" <?php if ($pageNum_contrahents >= $totalPages_contrahents) {  echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value=">>" onclick="document.location='<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, min($totalPages_contrahents, $pageNum_contrahents + 1), $queryString_contrahents); ?>'">
    </td>
    <td align="center"><input name="" type="button" <?php if ($pageNum_contrahents >= $totalPages_contrahents) { echo "disabled=\"true\" style=\"background: #cccccc\""; } ?> value="|>>" onclick="document.location='<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, $totalPages_contrahents, $queryString_contrahents); ?>'">
    </td>
    <td align="center">
      <input type="button" name="Button" value="<?php echo REFRESH ?>" onClick="document.location='GEN_contrahents_frame.php'">
    </td>
  </tr>
</table>
<table border="0" cellpadding="1" cellspacing="1" width="1500" style="table-layout:fixed" align="center">
  <tr bgcolor="#CCCCCC">
    <th width="20">&nbsp; </th>
    <th width="30">&nbsp;</th>
    <th width="5">&nbsp;</th>
    <th width="40"><?php echo ID ?></th>
    <th width="100"><?php echo QUALIFICATION ?></th>
    <th width="400"><?php echo FULLNAME ?></th>
    <th width="40"><?php echo COUNTRY ?></th>
    <th width="120"><?php echo CITY ?></th>
    <th width="50"><?php echo POST ?></th>

    <th width="160"><?php echo PHONE ?></th>
    <th width="160"><?php echo FAX ?></th>
    <th width="160"><?php echo MOBILE ?></th>
    <th width="160"><?php echo EMAIL ?></th>

    <?php
        if(1 == $userCorisBranchId)
        {
            echo '<th width="120">' . BRANCH . '</th>';
        }
    ?>

  </tr>
  <?php 
  $old_contrahent_id = 0;
  $bgcolor = '';  
  if ($totalRows_contrahents) do { 
  	if ($old_contrahent_id != $row_contrahents['contrahent_id']) {  
		if ($bgcolor == "#FFFFFF") {
			$bgcolor = "#EEEEEE";
		} else {
			$bgcolor = "#FFFFFF";
		}  
  ?>
  <tr height="17" bgcolor="<?PHP if ($row_contrahents['attention']) { echo "yellow"; } else { echo $bgcolor; } ?>">
    <td nowrap><input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_contrahents_details.php?contrahent_id=<?php echo $row_contrahents['contrahent_id'] ?>','','scrollbars=yes,resizable=yes,width=650,height=620, left=175,top=40')"></td>
    <td align="center" nowrap><?php if (!(strcmp($row_contrahents['locked'],1))) {echo "<img src=\"graphics/locked.gif\" width=\"15\" height=\"15\" border=\"1\">";} ?></td>
    <td align="right" nowrap bgcolor="<?php echo $row_contrahents['color']; ?>">&nbsp;</td>
    <td align="right" nowrap><font color="#000099"><?php echo $row_contrahents['contrahent_id']; ?></font></td>
    <td align="center" nowrap><font color="<?php echo $row_contrahents['qualification_color']; ?>"><?php echo $row_contrahents['qualification']; ?></font></td>
    <td ><strong><?php echo $row_contrahents['name']; ?></strong></td>
    <td align="center" ><font color="#339900"><strong><?php echo $row_contrahents['country_id']; ?></strong></font></td>
    <td title="<?php echo $row_contrahents['city']; ?>" ><em><?php echo $row_contrahents['city']; ?></em></td>
    <td ><em><?php echo $row_contrahents['post']; ?></em></td>
    <td title="<?php echo $row_contrahents['phone']; ?>" ><em><?php echo $row_contrahents['phone']; ?></em></td>
    <td title="<?php echo $row_contrahents['fax']; ?>" ><em><?php echo $row_contrahents['fax']; ?></em></td>
    <td title="<?php echo $row_contrahents['mobile']; ?>" ><em><?php echo $row_contrahents['mobile']; ?></em></td>
    <td title="<?php echo $row_contrahents['email']; ?>" ><?php echo $row_contrahents['email']; ?></td>
    <?php
        if(1 == $userCorisBranchId)
        {
          echo '<td nowrap>' . ($row_contrahents['coris_branch']==''?'PL/DE':$row_contrahents['coris_branch']) . '</td>';
        }
    ?>

  </tr>
  <?PHP } 
  if ($old_contrahent_id == $row_contrahents['contrahent_id'] || $row_contrahents['contact_id']) { ?>
  <tr height="17" bgcolor="<?PHP if ($row_contrahents['contact_attention']) { echo "yellow"; } else { echo "#FFFFCC"; } ?>">
    <td colspan="4" align="right" nowrap><!--<input type="button" value="&gt;" style="width: 20px" onClick="MM_openBrWindow('GEN_contrahents_details_contacts_details.php?contrahent_id=<?php echo $row_contrahents['contrahent_id'] ?>&contact_id=<?php echo $row_contrahents['contact_id'] ?>','','scrollbars=yes,resizable=yes,width=650,height=620')">--></td>
    <td nowrap colspan="4"><strong><?php echo $row_contrahents['contact_name']; ?></strong> (<?php echo $row_contrahents['position']; ?>)</td>
    <td nowrap><em><?php echo $row_contrahents['contact_phone']; ?></em></td>
    <td nowrap><em><?php echo $row_contrahents['contact_fax']; ?></em></td>
    <td nowrap><em><?php echo $row_contrahents['contact_mobile']; ?></em></td>
    <td nowrap><?php echo $row_contrahents['contact_email']; ?></td>
    <td align="center" nowrap></td>
    <td nowrap></td>
  </tr>    
  <?php 
  	}
	$old_contrahent_id = $row_contrahents['contrahent_id'];	
  } while ($row_contrahents = mysql_fetch_assoc($contrahents)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($contrahents);
?>
