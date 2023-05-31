<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

ini_set('display_errors',0);

define('DB_HOST',  "localhost");
define('DB_USER', "root");
define('DB_PASSWD',  "root");
define('DB_DBASE', "coris");
define('ADMIN_EMAIL', 'adres@domena');
define('ADRES_FROM', 'adres@domena');
$cn = null;

/*
$cn = mysql_pconnect($hostname_cn, $username_cn, $password_cn) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES 'latin2'", $cn);
mysql_select_db($database_cn, $cn);
*/

$cn=db_connect() or connect_error();



function db_connect(){
	 
   $link= @mysql_connect(DB_HOST, DB_USER, DB_PASSWD);
   if ( $link && mysql_select_db(DB_DBASE) ){
   	 mysql_query("SET NAMES 'latin2'", $link);
     return($link) ;
   }else  
     return (FALSE);
   
}


function connect_error(){
	echo '<h1>'.mysql_error().'</h1>';
	exit();
}

?>