<?php


function move_file($kat,$uploadFile,$newName){

      $dir = $kat;

      $fileNameOrg = $newName;
      $fileName_ = ''.$newName;
      $fileName = $dir.'/'.$newName;

       $i=0;
       while ( file_exists($fileName) ){ // spradzanie czy taki plik juz istnieje jesli tak to zmiana nazwy
         $poz = strpos($fileNameOrg,strrchr($fileNameOrg,'.'));
         $fileName_ = substr($fileNameOrg,0,$poz).'_'.$i.'.'.substr($fileNameOrg,$poz+1,strlen($fileNameOrg));
         $fileName = $dir.'/'.$fileName_;
         $i++;
       }

       move_uploaded_file($uploadFile, $fileName );
       return $fileName_;
}

function correct_file_name($name){
  $name=trim(strtolower($name));
	
  $zmiany = array(' ' => '', '/' => '', '\\' => '','-' => '', '(' => '', ')' => '', '+' => '');
  $trans_win_iso = array(chr(185) => chr(177),chr(165) => chr(161),chr(159) => chr(188),chr(143) => chr(172),chr(156) => chr(182),chr(140) => chr(166));
  $trans_iso_to_ang_lower = array(chr(177) => chr(97), chr(161) => chr(97 ),chr(175) => chr(122),chr(191)=>chr(122),chr(188)=>chr(122),chr(172)=>chr(122),chr(182)=>chr(115), chr(166)=>chr(115),chr(234)=>chr(101),chr(202)=>chr(101),chr(230)=>chr(99),chr(198)=>chr(99),chr(243)=>chr(111),chr(211)=>chr(111),chr(179)=>chr(108),chr(163)=>chr(108),chr(241)=>chr(110),chr(209)=>chr(110));
  
  $name =  strtr($name,$zmiany );    
  
  $name = strtr($name,$trans_win_iso);
  $name = strtr($name,$trans_iso_to_ang_lower);
  return $name;
}

function check_dir($lok,$dir1,$dir2){

    	
	
        if (!file_exists($lok.'/'.$dir1)){
          mkdir($lok.'/'.$dir1);
          mkdir($lok.'/'.$dir1.'/'.$dir2);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/m');
        }else if (!file_exists($lok.'/'.$dir1.'/'.$dir2)){
          mkdir($lok.'/'.$dir1.'/'.$dir2);
          mkdir($lok.'/'.$dir1.'/'.$dir2.'/m');
        }
        if (file_exists($lok.'/'.$dir1.'/'.$dir2.'/m')){
          return $dir1.'/'.$dir2;
        }else{
          return '';
        }      
}

 	function _mime_types($ext = '') {
  	
     $mimes = array(
       'hqx'   =>  'application/mac-binhex40',
       'cpt'   =>  'application/mac-compactpro',
       'doc'   =>  'application/msword',
       'bin'   =>  'application/macbinary',
       'dms'   =>  'application/octet-stream',
       'lha'   =>  'application/octet-stream',
       'lzh'   =>  'application/octet-stream',
       'exe'   =>  'application/octet-stream',
       'class' =>  'application/octet-stream',
       'psd'   =>  'application/octet-stream',
       'so'    =>  'application/octet-stream',
       'sea'   =>  'application/octet-stream',
       'dll'   =>  'application/octet-stream',
       'oda'   =>  'application/oda',
       'pdf'   =>  'application/pdf',
       'ai'    =>  'application/postscript',
       'eps'   =>  'application/postscript',
       'ps'    =>  'application/postscript',
       'smi'   =>  'application/smil',
       'smil'  =>  'application/smil',
       'mif'   =>  'application/vnd.mif',
       'xls'   =>  'application/vnd.ms-excel',
       'ppt'   =>  'application/vnd.ms-powerpoint',
       'wbxml' =>  'application/vnd.wap.wbxml',
       'wmlc'  =>  'application/vnd.wap.wmlc',
       'dcr'   =>  'application/x-director',
       'dir'   =>  'application/x-director',
       'dxr'   =>  'application/x-director',
       'dvi'   =>  'application/x-dvi',
       'gtar'  =>  'application/x-gtar',
       'php'   =>  'application/x-httpd-php',
       'php4'  =>  'application/x-httpd-php',
       'php3'  =>  'application/x-httpd-php',
       'phtml' =>  'application/x-httpd-php',
       'phps'  =>  'application/x-httpd-php-source',
       'js'    =>  'application/x-javascript',
       'swf'   =>  'application/x-shockwave-flash',
       'sit'   =>  'application/x-stuffit',
       'tar'   =>  'application/x-tar',
       'tgz'   =>  'application/x-tar',
       'xhtml' =>  'application/xhtml+xml',
       'xht'   =>  'application/xhtml+xml',
       'zip'   =>  'application/zip',
       'mid'   =>  'audio/midi',
       'midi'  =>  'audio/midi',
       'mpga'  =>  'audio/mpeg',
       'mp2'   =>  'audio/mpeg',
       'mp3'   =>  'audio/mpeg',
       'aif'   =>  'audio/x-aiff',
       'aiff'  =>  'audio/x-aiff',
       'aifc'  =>  'audio/x-aiff',
       'ram'   =>  'audio/x-pn-realaudio',
       'rm'    =>  'audio/x-pn-realaudio',
       'rpm'   =>  'audio/x-pn-realaudio-plugin',
       'ra'    =>  'audio/x-realaudio',
       'rv'    =>  'video/vnd.rn-realvideo',
       'wav'   =>  'audio/x-wav',
       'bmp'   =>  'image/bmp',
       'gif'   =>  'image/gif',
       'jpeg'  =>  'image/jpeg',
       'jpg'   =>  'image/jpeg',
       'jpe'   =>  'image/jpeg',
       'png'   =>  'image/png',
       'tiff'  =>  'image/tiff',
       'tif'   =>  'image/tiff',
       'css'   =>  'text/css',
       'html'  =>  'text/html',
       'htm'   =>  'text/html',
       'shtml' =>  'text/html',
       'txt'   =>  'text/plain',
       'text'  =>  'text/plain',
       'log'   =>  'text/plain',
       'rtx'   =>  'text/richtext',
       'rtf'   =>  'text/rtf',
       'xml'   =>  'text/xml',
       'xsl'   =>  'text/xml',
       'mpeg'  =>  'video/mpeg',
       'mpg'   =>  'video/mpeg',
       'mpe'   =>  'video/mpeg',
       'qt'    =>  'video/quicktime',
       'mov'   =>  'video/quicktime',
       'avi'   =>  'video/x-msvideo',
       'movie' =>  'video/x-sgi-movie',
       'doc'   =>  'application/msword',
       'word'  =>  'application/msword',
       'xl'    =>  'application/excel',
       'eml'   =>  'message/rfc822'
     );
     return (!isset($mimes[strtolower($ext)])) ?  'application/octet-stream' : $mimes[strtolower($ext)];
   }
?>

