<?php
$MM_referrer = substr(strrchr($_SERVER['PHP_SELF'], '/') , 1);

//$MM_referrer;
//exit();


$query_departments = sprintf("SELECT coris_pages2departments.department_id FROM coris_pages2departments, coris_pages WHERE coris_pages2departments.page_id = coris_pages.page_id AND coris_pages.resource LIKE '%s'", $MM_referrer);
$departments = mysql_query($query_departments, $cn) or die(mysql_error());
$row_departments = mysql_fetch_assoc($departments);
$totalRows_departments = mysql_num_rows($departments);

$i = 0;
$arrDepartments = '';
do {			
	$arrDepartments .= $row_departments['department_id'];
	if (++$i < $totalRows_departments)
		$arrDepartments .= ", ";
} while ($row_departments = mysql_fetch_assoc($departments));


$query_groups = sprintf("SELECT coris_pages2groups.group_id FROM coris_pages2groups, coris_pages WHERE coris_pages2groups.page_id = coris_pages.page_id AND coris_pages.resource LIKE '%s'", $MM_referrer);
$groups = mysql_query($query_groups, $cn) or die(mysql_error());
$row_groups = mysql_fetch_assoc($groups);
$totalRows_groups = mysql_num_rows($groups);

$i = 0;
$arrGroups = '';
do {			
	$arrGroups .= $row_groups['group_id'];
	if (++$i < $totalRows_groups)
		$arrGroups .= ", ";
} while ($row_groups = mysql_fetch_assoc($groups));

$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($UserName, $strGroups, $strDepartments, $UserGroup, $UserDepartment) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrDepartments = Explode(",", $strDepartments); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserDepartment, $arrDepartments)) { 
	    if (in_array($UserGroup, $arrGroups)) { 
			$isValid = true; 
	  	}
    } 
//    if (($strUsers == "") && false) { 
//      $isValid = true; 
//    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "GEN_deny.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized($_SESSION['MM_Username'], $arrGroups, $arrDepartments, $_SESSION['MM_UserGroup'], $_SESSION['department_id'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

mysql_free_result($groups);
mysql_free_result($departments);
?>