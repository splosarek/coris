<?php 
include('include/include.php'); 
 

$contrahent_id = getValue('contrahent_id');
$address_id = getValue('address_id');
$action = getValue('action');





?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Adres działalności</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="Scripts/validate.js"></script>
</head>

<body><br>


<?php

if ($action=='add_save' && $contrahent_id>0){
		zapisz_form_add($contrahent_id);
}else if ($action=='update_save' && $contrahent_id>0 && $address_id>0){
		zapisz_form_update($contrahent_id,$address_id);

}else if ($action=='delete_save' && $contrahent_id>0 && $address_id>0){
		delete_form_update($contrahent_id,$address_id);

}else if ( $action=='add' && $contrahent_id>0 ){
	$tab = array();
	
	
	wysw_form($tab,'add',$contrahent_id);
}else if( $action=='edit' && $contrahent_id>0  && $address_id>0){
	  $query = "SELECT * FROM coris_contrahents_work_addresses  WHERE ID='$address_id' AND contrahent_id='$contrahent_id'  ";
      $mysql_result = mysql_query($query);
      if (mysql_num_rows($mysql_result)>0){
      		$row=mysql_fetch_array($mysql_result);
      		wysw_form($row,'update',$contrahent_id);
      }	
}


function zapisz_form_add($contrahent_id){
	
	$info = getHttpValue('info');
	$address = getHttpValue('address');
	$post = getHttpValue('post');
	$city = getHttpValue('city');
	$note = getHttpValue('note');	
	$active = getValue('active') == 1 ? 1 : 0;	
	
	$query = "INSERT INTO coris_contrahents_work_addresses SET 
	contrahent_id='$contrahent_id', info = '$info',	address='$address', post='$post', 	city='$city',
	note='$note', active='$active',	user_id='".Application::getCurrentUser()."',date=now()	";

	$mysql_result = mysql_query($query) or die (mysql_error());
	$id = mysql_insert_id();

	 echo "<script> document.location='GEN_contrahents_details.php?contrahent_id=$contrahent_id#adresy'</script>";
	  exit;
	
}

function delete_form_update($contrahent_id,$address_id){

	
	
	$query = "DELETE FROM coris_contrahents_work_addresses 	
	WHERE ID='$address_id' AND contrahent_id='$contrahent_id' LIMIT 1";
	
	$mysql_result = mysql_query($query) or die (mysql_error());
	$id = mysql_insert_id();

	 echo "<script> document.location='GEN_contrahents_details.php?contrahent_id=$contrahent_id#adresy'</script>";
	  exit;
}

function zapisz_form_update($contrahent_id,$address_id){

	$info = getHttpValue('info');
	$address = getHttpValue('address');
	$post = getHttpValue('post');
	$city = getHttpValue('city');
	$note = getHttpValue('note');	
	$active = getValue('active') == 1 ? 1 : 0;
	
	
	
	
	$query = "UPDATE coris_contrahents_work_addresses SET 
	info = '$info',	address='$address', post='$post', 	city='$city',note='$note', active='$active',
	user_id='".Application::getCurrentUser()."',date=now()
	
	WHERE ID='$address_id' LIMIT 1";
	
	$mysql_result = mysql_query($query) or die (mysql_error());
	$id = mysql_insert_id();

	 echo "<script> document.location='GEN_contrahents_details.php?contrahent_id=$contrahent_id#adresy'</script>";
	  exit;
}

function wysw_form($row,$tryb,$contrahent_id){ 

echo '<table width="500" align="center" cellpadding="1" cellspacing="2" border="0" style="border: #cccccc 1px solid;">
  <tr valign="baseline" bgcolor="#CCCCCC">
    <td colspan="2" align="center" nowrap style="border: #000000 1px solid;"><strong>Adres działalności</strong></td>
  </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
    <td colspan="2" align="right" nowrap>';
  	
	if ($tryb == 'update'){		
		echo '<form name="form1" method="POST" action="GEN_contrahents_details_work_adresses.php" style="margin:0;">
				<input type="hidden" name="contrahent_id" value="'.$contrahent_id.'">';		
		echo '<input type="hidden" name="action" value="delete_save">';
		echo '<input type="hidden" name="address_id" value="'.$row['ID'].'">';		
		echo '<input type="submit" class="submit" style="background-color:red;font-weight:bold;" value="Usuń" onCLick="return confirm(\'Czy napewno?\');">';
		echo '</form>';
	}else{
		echo '&nbsp;';
	}	
echo '</td>
  </tr>
  </table>';
	
	?>
<form name="form1" method="POST"  action="GEN_contrahents_details_work_adresses.php">
<input type="hidden" name="contrahent_id" value="<?php echo $contrahent_id; ?>">
<?php
	if ($tryb == 'add')
		echo '<input type="hidden" name="action" value="add_save">';
	else if ($tryb == 'update'){
		echo '<input type="hidden" name="action" value="update_save">';
		echo '<input type="hidden" name="address_id" value="'.$row['ID'].'">';		
	}

?>
<table width="500" align="center" cellpadding="1" cellspacing="2" border="0" style="border: #cccccc 1px solid;">
  
   <tr bgcolor="#AAAAAA" >
    <td align="right" nowrap><b>Info</b>&nbsp;</td>
    <td><textarea cols="60" rows="2" name="info"><?php echo $row['info'];?></textarea></td>    
   </tr>
   <tr bgcolor="#BBBBBB" valign="baseline">
    <td nowrap align="right"><b>Ulica</b>&nbsp;&nbsp;</td>
    <td><input name="address" type="text" id="address" size="30" maxlength="50" value="<?php echo $row['address']; ?>"></td>
  </tr>
   
   
   <tr bgcolor="#AAAAAA" valign="baseline">
    <td nowrap align="right"><b>Kod Miasto</b>&nbsp;&nbsp;</td>
    <td><input name="post" type="text" id="post" size="7" maxlength="6" value="<?php echo $row['post']; ?>"> <input name="city" type="text" id="city" size="30" maxlength="50" value="<?php echo $row['city']; ?>"></td>
  </tr>
   
  <tr bgcolor="#CCCCCC" valign="baseline">
    <td colspan="2" align="center" bgcolor="#CCCCCC" nowrap><strong>&nbsp;</strong>&nbsp;</td>  
  </tr>
   <tr  bgcolor="#AAAAAA" >
    <td align="right" nowrap><b>Notatka</b>&nbsp;</td>
    <td><textarea cols="60" rows="3" name="note"><?php echo $row['note'];?></textarea></td>    
   </tr>
  
   <tr  bgcolor="#BBBBBB" >
    <td align="right" nowrap><b>Aktywność</b>&nbsp;</td>
    <td><input type="checkbox" name="active" value="1" <?php echo ($row['active']==1? 'checked' : '' );?>></td>    
   </tr>
  
  
  
  <tr valign="baseline">
    <td colspan="2" align="right" nowrap><hr></td>
    </tr>
  <tr valign="baseline">
    <td nowrap colspan="2" align="center" bgcolor="#CCCCCC"><strong>Dane wprowadził / ostatni zmodyfikował </strong>&nbsp;</td>    
  </tr>
	<tr valign="baseline">
    	<td colspan="2" align="center" nowrap><?php 
    	if ($row['user_id'] > 0 ){
    		$user= new UserObject($row['user_id']);
    		echo $user->getName().' '.$user->getSurname(). ', '.$row['date'];    		    		
    	} 
    	?></td>
    </tr>
     <tr valign="baseline">
    <td colspan="2" align="right" nowrap><hr></td>
    </tr>
  <tr valign="baseline">
    <td height="22" align="right" nowrap>&nbsp;</td>
    <td><input name="contrahent_id" type="hidden" id="contrahent_id" value="<?PHP echo $_GET['contrahent_id'] ?>">
        <input name="offset" type="hidden" id="offset" value="<?PHP echo $_GET['offset'] ?>">
        <input name="Button" type="button" class="cancel" value="<?php echo BACK ?>" onClick="document.location='GEN_contrahents_details.php?contrahent_id=<?PHP echo $_GET['contrahent_id'] ?>&offset=<?PHP echo $_GET['offset'] ?>'">
        <input type="submit" class="submit" value="<?php echo SAVE ?>"></td>
  </tr>
</table>
</form>
<?php }



?>

</body>
</html>