<?php
session_start();
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cn = "localhost";
$database_cn = "coris";
$username_cn = "script";
$password_cn = "";
$cn = mysql_pconnect($hostname_cn, $username_cn, $password_cn) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES 'latin2'", $cn);
mysql_select_db($database_cn);


?>
