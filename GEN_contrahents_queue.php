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
<?php
$contrahent_id = getValue('contrahent_id');
$action = getValue('action');


if ($action=='update_contrahent' && $contrahent_id > 0){
    $record_id = getValue('record_id');
    $nip = getValue('new_nip');
    $org_nip = getValue('org_nip');


    $qu = "UPDATE coris_contrahents SET nip='$nip'  WHERE contrahent_id='$contrahent_id'";
    $mr = mysql_query($qu);
    zapiszLogZmian($contrahent_id,'','nip',$org_nip,$nip);

    mysql_query("DELETE  FROM coris_contrahents_check WHERE ID='$record_id' ");

    $insertGoTo = "GEN_contrahents_details.php?contrahent_id=$contrahent_id#konta";
    echo "  <div align='center'>Dane zostały zatwierdzone            
            <br><br><br><br> <a href=\"".$insertGoTo."\">Powrót do danych kontrahenta</a>";

    if (getValue('ref') == 'qlist')
        echo '<br><br><br><a href="GEN_contrahents_queue_list.php">Powrót to listy zatwierdzeń </a>';
    exit();

}else if ($action=='update_contrahent_account' && $contrahent_id > 0){
    $record_id = getValue('record_id');
    $account_id = getValue('account_id');

    $org_account = getValue('org_account');

    $new_account = getValue('new_account');
    $new_name = getValue('new_name');
    $new_address = getValue('new_address');
    $new_city = getValue('new_city');
    $new_post = getValue('new_post');
    $new_country_id = getValue('new_country_id');
    $new_account = getValue('new_account');
    $new_swift = getValue('new_swift');
    $new_note = getValue('new_note');

    if ($account_id==0){



        $query = "INSERT INTO  coris_contrahents_accounts  SET contrahent_id='$contrahent_id',account='$new_account',swift='$new_swift',name='$new_name',address='$new_address',post='$new_post',
	        city='$new_city',country_id='$new_country_id',note='$new_note',`order`=1, date=now(),user_id='".$_SESSION['user_id']."'";
        $mr = mysql_query($query);
       // echo $query;
        if ($mr){
            $account_id = mysql_insert_id();
            zapiszLogZmian($contrahent_id, $account_id, 'nr konta', "", $new_account);

        }else{
            echo "Error";
            exit();
        }


    }else {
        $qu = "UPDATE coris_contrahents_accounts SET account='$new_account'  WHERE account_id='$account_id'";
        $mr = mysql_query($qu);
        zapiszLogZmian($contrahent_id, $account_id, 'nr konta', $org_account, $new_account);
    }

    mysql_query("DELETE  FROM coris_contrahents_accounts_check WHERE ID='$record_id' ");

    $insertGoTo = "GEN_contrahents_details.php?contrahent_id=$contrahent_id#konta";
    echo "  <div align='center'>Dane zostały zatwierdzone            
            <br><br><br><br> <a href=\"".$insertGoTo."\">Powrót do danych kontrahenta</a>";
    if (getValue('ref') == 'qlist')
        echo '<br><br><br><a href="GEN_contrahents_queue_list.php">Powrót to listy zatwierdzeń </a>';
    exit();

}



$id = getValue('id');

$tmp = explode('_',$id);

if ($tmp[0] == 'c'){
    wysw_form_contrahent($tmp[1]);

}else if ($tmp[0] == 'a'){

    wysw_form_account($tmp[1]);
}



exit();

function get_contrahent_data($_id){
    $query = "SELECT * FROM coris_contrahents WHERE contrahent_id='$_id' ";
    $mr = mysql_query($query);
    $row = mysql_fetch_array($mr);

    return $row;
}

function get_account_data($_id){
    $query = "SELECT * FROM 	coris_contrahents_accounts  WHERE account_id='$_id' ";
    $mr = mysql_query($query);
    $row = mysql_fetch_array($mr);

    return $row;
}

function wysw_form_account($data_id){
    $query = "SELECT * FROM coris_contrahents_accounts_check WHERE ID='$data_id' ";
    $mr = mysql_query($query);

    if (mysql_num_rows($mr) == 0 ) return;
    $row = mysql_fetch_array($mr);

    $contrahentID = $row['contrahent_id'];
    $account_id = $row['account_id'];

    $contrahent_data = get_contrahent_data($contrahentID);

    $org_data = get_account_data($account_id);

    echo '
<form name="form1" method="POST" onSubmit="">
<input type="hidden" name="contrahent_id" value="' . $contrahentID . '">
<input type="hidden" name="account_id" value="' . $account_id . '">
		<input type="hidden" name="action" value="update_contrahent_account">
		<input type="hidden" name="record_id" value="' . $data_id . '">
		<input type="hidden" name="ref" value="' . getValue('ref') . '">
		';

    echo '<table align="center"  cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" width="95%">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="3" align="center" nowrap style="border: #000000 1px solid;"><strong>Konto bankowe kontrahenta do zatwierdzenia</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="3" align="right" nowrap>&nbsp;</td>
  </tr> 
   <tr valign="baseline" bgcolor="">
    <td colspan="3" align="center" nowrap><br>Kontrahent: <b>'.$contrahent_data['name'].'</b>('.$contrahentID.')<br><br></td>
  </tr>
   <tr valign="baseline" bgcolor="">
    <td colspan="3" align="center" nowrap><b>'.($account_id == 0 ? "Nowe konto" : "Aktualizacja istniejącego").'</b><br><br></td>
  </tr>
  <tr valign="baseline">
    <td width="10%" align="right" nowrap><b>Parametr</b></td>
    <td width="45%" align="center" nowrap><b>Stara wartość</b></td>
    <td width="45%" align="center" nowrap><b>Nowa wartość</b></td>    
  </tr>';

    if ($account_id == 0){

        echo '
        <tr valign="baseline">
            <td  align="right" nowrap>Nazwa banku</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_name" type="text" id="new_name" size="40" maxlength="50" value="' . $row['name'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Adres</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_address" type="text" id="new_address" size="40" maxlength="50" value="' . $row['address'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Miejscowość</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_city" type="text" id="new_city" size="40" maxlength="50" value="' . $row['city'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Kod</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_post" type="text" id="new_post" size="40" maxlength="50" value="' . $row['post'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Kraj</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_country_id" type="text" id="new_country_id" size="40" maxlength="2" value="' . $row['country_id'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Nr konta</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_account" type="text" id="new_account" size="40" maxlength="50" value="' . $row['account'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Swift</td>
            <td  align="center"> </td>
            <td  align="center"><input name="new_swift" type="text" id="new_swift" size="40" maxlength="50" value="' . $row['swift'] . '"></td>
          </tr>
          <tr valign="baseline">
            <td  align="right">Uwagi</td>
            <td  align="center"> </td>
            <td  align="center"><textarea name="new_note" type="text" id="new_note" cols="37" rows="2">' . $row['note'] . '</textarea></td>
          </tr>
          
          ';
    }else {
        echo '<tr valign="baseline">
            <td  align="center">Nr konta</td>
            <td  align="center"> <input name="org_account" type="text" id="org_account" size="40" maxlength="50" value="' . $org_data['account'] . '" readonly></td>
            <td  align="center"><input name="new_account" type="text" id="new_account" size="40" maxlength="50" value="' . $row['account'] . '"></td>
          </tr>';
    }

  
  echo '<tr valign="baseline">
    <td colspan="3" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
  
    <td colspan="3" align="center">       
        
        
        <input type="submit" class="submit" value="Zatwierdź" style="width: 100px">
        <br>
        <br>';

        if (getValue('ref') == 'qlist')
            echo '<input name="Button" type="button" class="cancel" value="Powrót" onClick="document.location=\'GEN_contrahents_queue_list.php\'">';
        else
            echo '<input name="Button" type="button" class="cancel" value="Powrót" onClick="document.location=\'GEN_contrahents_details.php?contrahent_id=' . $contrahentID . '\'">';

        echo '</td>
        
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>';

}
function wysw_form_contrahent($data_id)
{


    $query = "SELECT * FROM coris_contrahents_check WHERE ID='$data_id' ";
    $mr = mysql_query($query);

    if (mysql_num_rows($mr) == 0) return;

    $row = mysql_fetch_array($mr);
    $contrahentID = $row['ID_contrahent'];


    $org_data = get_contrahent_data($contrahentID);

    echo '
<form name="form1" method="POST" onSubmit="">
<input type="hidden" name="contrahent_id" value="' . $contrahentID . '">
		<input type="hidden" name="action" value="update_contrahent">
		<input type="hidden" name="record_id" value="' . $data_id . '">
		<input type="hidden" name="ref" value="' . getValue('ref') . '">';

    echo '<table align="center"  cellpadding="1" cellspacing="0" style="border: #cccccc 1px solid;" width="80%">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="3" align="center" nowrap style="border: #000000 1px solid;"><strong>Dane kontrahenta do zatwierdzenia</strong></td>
  </tr>
  <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="3" align="right" nowrap>&nbsp;</td>
  </tr> 
   <tr valign="baseline" bgcolor="">
    <td colspan="3" align="center" nowrap><br>Kontrahent: <b>'.$org_data['name'].'</b>('.$contrahentID.')<br><br></td>
  </tr>
  <tr valign="baseline">
    <td width="20%" align="center" nowrap><b>Parametr</b></td>
    <td width="40%" align="center" nowrap><b>Stara wartość</b></td>
    <td width="40%" align="center" nowrap><b>Nowa wartość</b></td>    
  </tr>
  <tr valign="baseline">
    <td  align="center">Nr NIP</td>
    <td  align="center"> <input name="org_nip" type="text" id="org_nip" style="width: 200px" size="12" maxlength="20" value="' . $org_data['nip'] . '" readonly></td>
    <td  align="center"><input name="new_nip" type="text" id="new_nip" style="width: 200px" size="12" maxlength="20" value="' . $row['nip'] . '"></td>
  </tr>
  
  <tr valign="baseline">
    <td colspan="3" align="right" nowrap>&nbsp;</td>
  </tr>
  <tr valign="baseline">
  
    <td colspan="3" align="center">       
        
        
        <input type="submit" class="submit" value="Zatwierdź" style="width: 100px">
        <br>
        <br>';
    if (getValue('ref') == 'qlist')
        echo '<input name="Button" type="button" class="cancel" value="Powrót" onClick="document.location=\'GEN_contrahents_queue_list.php\'">';
    else
        echo '<input name="Button" type="button" class="cancel" value="Powrót" onClick="document.location=\'GEN_contrahents_details.php?contrahent_id=' . $contrahentID . '\'">';

    echo '</td>
        
  </tr>
</table>
<input type="hidden" name="MM_insert" value="form1">
</form>';
}

echo '
</body>
</html>';