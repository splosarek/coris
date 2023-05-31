<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

ini_set('display_errors',false);

define('DB_HOST',  "localhost");
define('DB_USER', "script");
        define('DB_PASSWD',  "script");
define('DB_DBASE', "coris");
define('ADMIN_EMAIL', 'adres@domena');
define('ADRES_FROM', 'adres@domena');
define('EMAIL_FROM', 'assist@coris.com.pl');
$cn = null;



define('MAIL_SPOOL','c:/wamp64/www/coris/www/maile/');


define("M_USER", "test@poczta.evernet.com.pl");
define("M_SERVER", "poczta.evernet.com.pl");
define("M_PASS", "test");
define("M_TYPE", "IMAP");
define("M_FOLDER", "INBOX");

define("TMP_DIR", "../fax/tmp/");

define("BASE_URL","http://localhost");

/*
$cn = mysql_pconnect($hostname_cn, $username_cn, $password_cn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_query("SET NAMES 'latin2'", $cn);
mysql_select_db($database_cn, $cn);
*/

$cn = db_connect() or connect_error();
$smpl_vrbl = 'sample variable';



function db_connect() {
    $link= @mysql_connect(DB_HOST, DB_USER, DB_PASSWD);
    if ($link && mysql_select_db(DB_DBASE) ) {
        mysql_query( "SET NAMES 'latin2'");
        return($link) ;
    } else {
        return (FALSE);
    }
}


function connect_error(){
  echo '<h1>'.mysql_error().'</h1>';
  exit();
}

?>
