<?php include('include/include.php');
include('include/contrahent_monior.php');


if (!in_array(Application::getCurrentUser(), $_superUsers)) {
    echo "BRAK dostępu";
    exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="Scripts/validate.js"></script>
</head>

<body><br>

<script>window.focus()</script>
<?php


echo '<table width="95%" align="center" cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;">
    <tr valign="baseline" bgcolor="#CCCCCC">
        <td  align="center" nowrap style="border: #000000 1px solid;"><strong>Zmiany do zatwierdzenia</strong></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
        <td align="right" nowrap></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
        <td align="center" nowrap><table width="100%" border="0" cellpadding="1" cellspacing="1">
                <tr bgcolor="#CCCCCC">
                    <th width="20"></th>
                    <th width="200">Kontrahent</th>
                    <th width="100">Parametr</th>
                    <th  width="180">Wartość</th>
                    <th width="130">Data</th>
                    <th width="80">Użytkownik</th>
                </tr>';








                $query = "SELECT coris_contrahents_check.*,coris_contrahents.name as contrahent FROM coris_contrahents_check,coris_contrahents WHERE coris_contrahents.contrahent_id =  coris_contrahents_check.ID_contrahent ORDER BY coris_contrahents_check.ID";
                $mr = mysql_query($query);

                while ($row = mysql_fetch_array($mr)){
                    $action= "document.location='GEN_contrahents_queue.php?id=c_".$row['ID']."&ref=qlist'";
                    echo  '<tr>
                          <td nowrap><input type="button" value=">" style="width: 20px" onclick="'.$action.'"></td>
                          <td align="left" >'.$row['contrahent'].' ('.$row['ID_contrahent'].')</td>
                          <td align="center" nowrap>Nr NIP</td>                          
                          <td align="left" >'.$row['nip'].'</td>
                          <td align="center" nowrap>'.$row['date'].'</td>
                          <td align="center" nowrap>'.getUserInitials($row['ID_user']).'</td>
                    </tr>';
                }

                $query = "SELECT coris_contrahents_accounts_check.*,coris_contrahents.name as contrahent FROM coris_contrahents_accounts_check,coris_contrahents WHERE coris_contrahents.contrahent_id = coris_contrahents_accounts_check.contrahent_id ORDER BY coris_contrahents_accounts_check.ID";
                $mr = mysql_query($query);

                while ($row = mysql_fetch_array($mr)){
                    $action= "document.location='GEN_contrahents_queue.php?id=a_".$row['ID']."&ref=qlist'";
                    echo  '<tr>
                          <td nowrap><input type="button" value=">" style="width: 20px" onclick="'.$action.'"></td>
                          <td align="left" >'.$row['contrahent'].' ('.$row['contrahent_id'].')</td>
                          <td align="center" nowrap>'.($row['account_id'] > 0 ? 'Nr rachunku' : 'Nowe konto bankowe').'</td>
                          <td align="left" >'.$row['account'].'</td>
                          <td align="center" nowrap>'.$row['date'].'</td>
                          <td align="center" nowrap>'.getUserInitials($row['user_id']).'</td>
                    </tr>';
                }


                echo '
    </table>
  ';





echo '
</body>
</html>';