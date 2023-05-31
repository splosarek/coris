<?php include('include/include.php'); 

$colname_image = "1";
if (isset($_GET['photo_id'])) {
  $colname_image = (get_magic_quotes_gpc()) ? $_GET['photo_id'] : addslashes($_GET['photo_id']);
}

$query_image = sprintf("SELECT `value` FROM coris_photos WHERE photo_id = %s", $colname_image);
$image = mysql_query($query_image, $cn) or die(mysql_error());
$row_image = mysql_fetch_assoc($image);
$totalRows_image = mysql_num_rows($image);

header("Content-type: image/jpeg"); 
echo base64_decode($row_image['value']);
mysql_free_result($image);
?>
