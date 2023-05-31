<?

 if (file_exists($photo)){
  	$size = GetImageSize ("$photo");

  echo "<HTML>\n<HEADER>\n<TITLE>Foto</TITLE>\n";
  echo "<SCRIPT>\n";
  echo "setS();\n";
  echo "function setS(){\n";
  echo "window.resizeTo(";
  echo $size[0]+50 ;
  echo ",";
  echo $size[1]+90;
  echo ")\n}\n";
  echo "</SCRIPT>\n</HEADER>\n\n";
  echo "<body bgcolor=\"#FFFFFF\" text=\"#000000\">";
  echo "<div align=\"center\">\n";
//  echo "<table border=\"1\">\n";
//  echo "<tr><td>";
  echo "<a href=\"javascript:window.close()\"><img src=\"$photo\" ".$size[3]." BORDER=\"0\"></a>";
  echo "</div>\n</BODY>\n</HTML>";

 }
?>
