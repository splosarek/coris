<?php include('include/include.php'); 

$colname_contrahent = "";
if (isset($_GET['contrahent_id']) && $_GET['contrahent_id']<>'') {
  $colname_contrahent = addslashes(stripslashes($_GET['contrahent_id']));
}else{
  exit;
}




$userCorisBranchId = 0;
$query_userBranch = '';
// jesli zalogowany oddzial 1 czyli Coris Polska to pokazac wszystkich (czyli bez filtra)
// jesli zalogowany z innego oddzialu, to pokazac tylko kontrahentow danego oddzialu
// w innych przypadkach nic nie pokazywac
/*if (isset($_SESSION['coris_branch']) && intval($_SESSION['coris_branch'])>0){
    if( $_SESSION['coris_branch'] == 1){
        $userCorisBranchId = 1;
    }else if( $_SESSION['coris_branch'] <> 1){
        $userCorisBranchId = intval($_SESSION['coris_branch']);
        $query_userBranch = " AND (coris_contrahents.coris_branch_id ='$userCorisBranchId'
                                     OR coris_contrahents.coris_branch_id = 0 )
                              ";
    }
}else{
    $query_userBranch = " AND coris_contrahents.coris_branch_id ='-1' ";
}
$branch_id = getValue('branch_id');
if ($branch_id > 0){
		  $query_userBranch = " AND (coris_contrahents.coris_branch_id ='$branch_id' OR coris_contrahents.coris_branch_id ='0')";	
}
*/
$branch_id = getValue('branch_id');

if ($branch_id==2 || $branch_id==3){
		$query_userBranch = " AND (coris_contrahents.tu_pl = '0')";	
}

$query_contrahent = "SELECT name FROM coris_contrahents WHERE contrahent_id = '$colname_contrahent' ";
$query_contrahent .= $query_userBranch;

$contrahent = mysql_query($query_contrahent, $cn) or die(mysql_error());
$totalRows_contrahent = mysql_num_rows($contrahent);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title></title>
</head>

<body>
<script language="JavaScript">
<!--
<?php
  if ($totalRows_contrahent>0){
    $row = mysql_fetch_array($contrahent);
    echo '
    if (parent.document.form1)    
    		parent.document.form1.contrahent_name.value= \''. $row['name'] .'\';
      
    if (parent.document.form_reg)    
    		parent.document.form_reg.contrahent_name.value= \''. $row['name'] .'\';';
  
    
  }else{
    echo 'alert(\''.GEN_CONTR_BRAKWYST.'\');';
         
    echo '
     if (parent.document.form1){    	
     	parent.document.form1.contrahent_name.value= \'\';
    	parent.document.form1.contrahent_id.value= \'\';
    	parent.document.form1.contrahent_id.focus();
    }
    if (parent.document.form_reg){    	
     	parent.document.form_reg.contrahent_name.value= \'\';
    	parent.document.form_reg.contrahent_id.value= \'\';
    	parent.document.form_reg.contrahent_id.focus();
    }
      	
    	';
  }
?>
//-->
</script>
</body>
</html>
<?php
mysql_free_result($contrahent);
?>
