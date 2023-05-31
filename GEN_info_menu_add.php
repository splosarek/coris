<?php include('include/include.php'); 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO coris_infos (`value`, user_id, date) VALUES (%s, %s, NOW())",
                       GetSQLValueString($_POST['info'], "text"),
                       GetSQLValueString($_SESSION['user_id'], "int"));

  
  $Result1 = mysql_query($insertSQL, $cn) or die(mysql_error());

  $insertGoTo = "GEN_info_menu_add.php?complete=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?= GEN_FR_DODINFOR ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
<body>
        <script language="javascript">
        <!--
            function validate() {
                if (form1.info.value == "") {
                    alert("<?= GEN_FR_WPTEKSTINF ?>");
                    form1.info.focus();
                    return false;
                }
                return true;
            }
        //-->
        </script>
<?php
    if (isset($_GET['complete']))
        echo "<script>opener.top.info.location.reload(); window.close();</script>";
?>
        <table cellpadding=4 cellspacing=0 width="100%">
            <form name="form1" action="<?php echo $editFormAction; ?>" method="POST" onsubmit="return validate();">
            <tr style="border-left: #eeeeee 1px solid; border-right: #eeeeee 1px solid; border-bottom: #eeeeee 1px solid; border-top: #eeeeee 1px solid">
                <td align="center" bgcolor="#cccccc">
                    <b><?= GEN_FR_DODINFOR ?></b>
                </td>
            </tr>
            <tr valign="top">
                <td align="center">
                    <textarea name="info" cols="48" rows="4" style="font-family: Verdana"></textarea>
                </td>
            </tr>
            <tr>
                <td bgcolor="#dfdfdf" style="border-top: #cccccc 1px solid;" align="center">
                    <input type="submit" value="<?= SAVE ?>" style="color: green; font-family: Verdana; font-size: 7pt; line-height: 5pt; height: 12pt; width: 100px; background: yellow" title="<?= GEN_FR_ZAPINF ?>">
                </td>
            </tr>
            <input type="hidden" name="MM_insert" value="form1">
           </form>
        </table>
        <script>form1.info.focus();</script>
</body>
</html>