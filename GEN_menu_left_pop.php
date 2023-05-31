<?php include('include/include.php'); 

$query_topics = "SELECT topic_id, `value` FROM coris_menu_topics WHERE active = 1 ORDER BY `order` ASC";
$topics = mysql_query($query_topics, $cn) or die(mysql_error());
$row_topics = mysql_fetch_assoc($topics);
$totalRows_topics = mysql_num_rows($topics);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title>Untitled Document</title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
<body>
  <script language="JavaScript">
    <!--
      function searchwindow() {
        var year;
        if (myform.year.value == "" && myform.number.value == "") {
          alert("Wpisz numer sprawy do wyszukania.");
          return false;
        }
        if (myform.year.value.length == '2') {
          year = "20"+ myform.year.value;
        } else if (myform.year.value.length == '1') {
          year = "200"+ myform.year.value;
        }
        var url = "AS_cases_details.php?number="+ myform.number.value +"&year="+ year;
                window.open(url,'searchwin','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=620,height=600');
      }
    //-->
  </script>
  
  
  <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tbody>
      <tr style="background-color: #cccccc;">
        <td colspan="2"><strong><?= GEN_MENU_MENU ?></strong></td>
      </tr>
            <?php 
      do { 
      ?>
            <tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?php echo $row_topics['value']; ?></td>
            </tr>          
      <?php 
      
      $query_elements = sprintf("SELECT element_id, `value`, resource,new_window FROM coris_menu_elements WHERE topic_id = %s AND active = 1 ORDER BY `order` ASC", $row_topics['topic_id']);
      $elements = mysql_query($query_elements, $cn) or die(mysql_error());
      $row_elements = mysql_fetch_assoc($elements);
      $totalRows_elements = mysql_num_rows($elements);          
      if ($totalRows_elements) do { ?>    
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="<?php echo $row_elements['resource']; ?>" target="<?php
              if ($row_elements['new_window']==1)
                echo "_blank";
              else 
                echo "main"; ?>"><?php echo $row_elements['value']; ?></a></td>
            </tr>
            <?php 
        } while ($row_elements = mysql_fetch_assoc($elements));
      ?>
            <tr>
              <td colspan="2" height="4"></td>      
      <?php      
      } while ($row_topics = mysql_fetch_assoc($topics));      
      ?>      

 <tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;">Dokumenty</td>
            </tr>          
  <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" onClick="window.open('FK_fax_in_sorter.php', 'FaxIn', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=950,height=750,left=0,top=0')"><?= GEN_MENU_DOKODEBR ?></a></td>
            </tr>  
                        <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_dokument_out_frameset.php" target="main" ><?= GEN_MENU_DOKWYSL ?></a></td>
            </tr> 
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= AS_CASD_ZAD2 ?></td>
            </tr>          
  <tr>            
   <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" OnClick="window.open('AS_cases_todos_list_all.php', 'TodoSAll', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=810,height=650,left=0,top=0')"><?= AS_CASTD_LISTZAD ?></a></td>
            </tr>        

             
      </tbody>
</table>
    <table cellpadding=0 cellspacing=1 border=0 width="100%">
      <tr>
        <td align="center"><input type="button" value="<?= GEN_TODO_TITLE ?>" onclick="window.open('GEN_todo.php', 'ToDO', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=650,height=550,left='+ (screen.availWidth - 550) / 2 +',top='+ ((screen.availHeight - screen.availHeight * 0.05) - 400) / 2);" ></td>
      </tr>
    </table>
 
        <!--
        <table>
            <tr>
                <td align="center"><font color="#6699cc"><small>Ostatnie logowanie:<br><?= (isset($_SESSION['date_previous_end']) && $_SESSION['date_previous_end'] != "0000-00-00 00:00:00") ? $_SESSION['date_previous_start'] :  GEN_MENU_USERNIEPRWYL ?></small></font></td>
            </tr>
        </table>
        //-->
</body>
</html>
<?php
mysql_free_result($topics);
mysql_free_result($elements);
?>
