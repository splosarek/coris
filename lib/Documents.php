<?php 

define('STORE_APPL_IDENT',1);  //identyfikator aplikacji w storage - zgodnie z tabela `store_destination`
define('STORE_APPL_USER_ID',Application::getCurrentUser());  //identyfikator uzytkownika

define('DEF_FAX_SENDER_NR', 'ssa');  //domyslny nr faksu nadawcy
define('DEF_EMAIL_SENDER', 'de@coris.com.pl');  // domyslny adres emaila nadawcy


if ( $_SESSION['GUI_language']=='en'){
	define('DEF_DOCUMENT_LANG', 'en');
}else{ 
	define('DEF_DOCUMENT_LANG', 'pl');
} 
//include_once('Application_config.php');
include_once('Documents/AplicationStorage.php');
include_once('Documents/OutInteractions.php');
include_once('Documents/SearchInteractions.php');
include_once('Documents/Interaction.php');
include_once('Documents/CaseInteractions.php');
require_once('Documents/Document_out_util.php');
require_once('Documents/PagingDoc.php');

include_once('c:/wamp64/www/dokumenty/lib/Documents/Documents.php');
?>