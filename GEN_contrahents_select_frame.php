<?php include('include/include.php');

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_contrahents = 100;
$pageNum_contrahents = 0;
if (isset($_GET['pageNum_contrahents'])) {
  $pageNum_contrahents = $_GET['pageNum_contrahents'];
}
$startRow_contrahents = $pageNum_contrahents * $maxRows_contrahents;

$varunki = '';

if (isset($_GET['contrahent_id']) && trim($_GET['contrahent_id']) > 0) {
  $ccontrahent_contrahents = addslashes(stripslashes(trim($_GET['contrahent_id'])));
  $varunki = " AND coris_contrahents.contrahent_id = '$ccontrahent_contrahents' ";
}

if (isset($_GET['name']) && trim($_GET['name']) <> '') {
  $cname_contrahents = addslashes(stripslashes(trim( $_GET['name'] )));
  $varunki .= " AND coris_contrahents.name LIKE '%$cname_contrahents%' ";
}

if (isset($_GET['post'])  && trim($_GET['post']) <> '') {
  $cpost_contrahents = addslashes(stripslashes(trim( $_GET['post'])));
  $varunki .= " AND coris_contrahents.post LIKE '%$cpost_contrahents%' ";
}

if (isset($_GET['city'])  && trim($_GET['city']) <> '') {
  $ccity_contrahents = addslashes(stripslashes(trim(  $_GET['city'] )));
  $varunki .= " AND coris_contrahents.city LIKE '%$ccity_contrahents%' ";
}


if (isset($_GET['country_id']) && trim($_GET['country_id'])<> '') {
  $ccountry_contrahents = addslashes(stripslashes(trim( $_GET['country_id'] )));
  $varunki .= " AND coris_contrahents.country_id ='$ccountry_contrahents' ";
}

$corisBranchId = 0;
// jesli oddzial 1 czyli Coris Polska to pokazac wszystkich (czyli bez filtra)
// jesli inne oddzialy, to pokazac tylko kontrahentow danego oddzialu
// w innych przypadkach nic nie pokazywac
/*if (isset($_SESSION['coris_branch']) && intval($_SESSION['coris_branch'])>0){
    if( $_SESSION['coris_branch'] == 1) {
        $corisBranchId = 1;
    }

    if( $_SESSION['coris_branch'] <> 1) {
        $corisBranchId = intval($_SESSION['coris_branch']);
        $varunki .= " AND (coris_branch_id ='$corisBranchId'
                       OR coris_contrahents.coris_branch_id = 0 )" ;
    }
}else{
    $varunki .= " AND coris_branch_id ='-1' ";
}

$corisBranchId = getValue('coris_contrahents_coris_branch_id');

if($corisBranchId <> '' && $corisBranchId <> 1)
{
    $varunki .= " AND (coris_contrahents.coris_branch_id ='$corisBranchId'
                                         OR coris_contrahents.coris_branch_id = 0 ) ";
}

$branch_id = getValue('branch_id');
if ($branch_id > 0){
		  $varunki  .= " AND (coris_contrahents.coris_branch_id ='$branch_id' OR coris_contrahents.coris_branch_id ='0')";
}
*/
//$query_contrahents = sprintf("SELECT coris_contrahents.contrahent_id, coris_contrahents.name, coris_contrahents.name_long, coris_contrahents.country_id, coris_contrahents.city, coris_contrahents.phone1, coris_contrahents.fax1, coris_contrahents.address, coris_contrahents.post, coris_contrahents.nip FROM coris_contrahents WHERE coris_contrahents.name LIKE '%s%%' AND coris_contrahents.post LIKE '%s%%' AND coris_contrahents.city LIKE '%s%%' AND coris_contrahents.country_id LIKE '%s%%' AND coris_contrahents.contrahent_id LIKE '%s%%' AND coris_contrahents.active = 1 ORDER BY coris_contrahents.contrahent_id", $cname_contrahents,$cpost_contrahents,$ccity_contrahents,$ccountry_contrahents,$ccontrahent_contrahents);
$query_contrahents = "SELECT coris_contrahents.contrahent_id, coris_contrahents.name, coris_contrahents.country_id,
                             coris_contrahents.city, coris_contrahents.phone1, coris_contrahents.fax1, coris_contrahents.address,
                             coris_contrahents.post, coris_contrahents.nip, coris_contrahents.email
                        FROM coris_contrahents
                       WHERE coris_contrahents.active = 1
                             $varunki
                    ORDER BY coris_contrahents.contrahent_id";

$query_limit_contrahents = sprintf("%s LIMIT %d, %d", $query_contrahents, $startRow_contrahents, $maxRows_contrahents);
$contrahents = mysql_query($query_limit_contrahents) or die(mysql_error());
//$row_contrahents = mysql_fetch_assoc($contrahents);
//	echo $query_contrahents;

$all_contrahents = '';
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
<script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>

</head>

<body>
<script language="JavaScript" type="text/JavaScript">
function contrahent_select(id, name,fax,email) {

	<?php
		if ($_GET['fax']==1){
			echo '
			//top.opener.form1.contrahent_id.value=id;
			//top.opener.form1.contrahent_name.value=name;
			//top.opener.form1.faxto.value=fax;
			top.opener.document.getElementById(\'contrahent_id\').value=id;
			top.opener.document.getElementById(\'contrahent_name\').value=name;
			top.opener.document.getElementById(\'faxto\').value=fax;




			formant = top.opener.document.getElementById(\'email_to\');
			if (formant){
				if (formant.value==\'\'){
					formant.value = email;
				}else
					formant.value = formant.value + \';\'+email
			}


			top.opener.focus();
			top.window.close();
			';
		}else{


			echo '
			top.opener.document.getElementById(\'contrahent_id\').value=id;
			if ($){
					top.opener.document.getElementById(\'contrahent_id\').fireEvent(\'zmien\', id, 10);
			}else{
			 		//top.opener.document.getElementById(\'contrahent_name\').value=name;
					top.opener.document.getElementById(\'contrahent_id\').focus();
					top.opener.document.getElementById(\'contrahent_id\').blur();
					top.opener.focus();

			}
			top.window.close();
			';

		}
		?>
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
   window.open(theURL,winName,features);
}


</script>

<table border="0" width="50%" align="center">
	<tr>
		<td width="23%" align="center"><?php if ($pageNum_contrahents > 0) { // Show if not first page ?>
    			<a href="<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, 0, $queryString_contrahents); ?>">&lt;&lt;|</a>
    			<?php } // Show if not first page ?>
		</td>
		<td width="31%" align="center"><?php if ($pageNum_contrahents > 0) { // Show if not first page ?>
    			<a href="<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, max(0, $pageNum_contrahents - 1), $queryString_contrahents); ?>">&lt;&lt;</a>
    			<?php } // Show if not first page ?>
		</td>
		<td width="23%" align="center"><?php if ($pageNum_contrahents < $totalPages_contrahents) { // Show if not last page ?>
    			<a href="<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, min($totalPages_contrahents, $pageNum_contrahents + 1), $queryString_contrahents); ?>">&gt;&gt;</a>
    			<?php } // Show if not last page ?>
		</td>
		<td width="23%" align="center"><?php if ($pageNum_contrahents < $totalPages_contrahents) { // Show if not last page ?>
    			<a href="<?php printf("%s?pageNum_contrahents=%d%s", $currentPage, $totalPages_contrahents, $queryString_contrahents); ?>">|&gt;&gt;</a>
    			<?php } // Show if not last page ?>
		</td>
	</tr>
</table>
<table style="table-layout:fixed" border="0" cellpadding="1" cellspacing="1" width="800">
	<tr bgcolor="#DFDFFF">
		<th nowrap width="25">&nbsp;</th>
		<th nowrap width="20">&nbsp;</th>
		<td nowrap width="30"><?= ID ?></td>
	<!--	<td nowrap>Nazwa skr.</td> //-->
		<td nowrap width="350"><?= FULLNAME ?></td>
		<td nowrap width="30"><?= COUNTRY ?></td>
		<td nowrap><?= GEN_COUN_MIA ?></td>
		<td nowrap><?= PHONE ?></td>
		<td nowrap><?= FAX ?></td>
	</tr>
	<?php while ($row_contrahents = mysql_fetch_assoc($contrahents)) { ?>
	<tr bgcolor="#FFFFCA">
		<td nowrap><input type="button" value="+" style="line-height: 4pt; height: 12pt; width: 20px" onclick="contrahent_select('<?php echo $row_contrahents['contrahent_id'] ?>', '<?php echo addslashes(htmlspecialchars($row_contrahents['name'],ENT_QUOTES ,'ISO-8859-1')) ?>', '<?php echo addslashes(htmlspecialchars($row_contrahents['fax1'],ENT_QUOTES ,'ISO-8859-1')) ?>', '<?php echo addslashes(htmlspecialchars($row_contrahents['email'],ENT_QUOTES ,'ISO-8859-1')) ?>')"></td>
    		<td nowrap><input type="button" value="&gt;" style="width: 20px" onclick="MM_openBrWindow('GEN_contrahents_details.php?contrahent_id=<?php echo $row_contrahents['contrahent_id'] ?>','','scrollbars=yes,resizable=yes,width=650,height=620, left=175,top=40')"></td>
		<td nowrap><div align="right" style="font-weight: bold"><?php echo $row_contrahents['contrahent_id']; ?></div></td>
	<!--	<td nowrap><?php // echo $row_contrahents['simple_id']; ?></td> //-->
		<td nowrap><?php echo $row_contrahents['name']; ?></td>
		<td nowrap><div align="center"><?php echo $row_contrahents['country_id']; ?></div></td>
		<td nowrap><?php echo $row_contrahents['city']; ?></td>
		<td nowrap><?php echo $row_contrahents['phone1']; ?></td>
		<td nowrap><?php echo $row_contrahents['fax1']; ?></td>
	</tr>
	<?php }  ?>
</table>
</body>
</html>
<?php
mysql_free_result($contrahents);
?>