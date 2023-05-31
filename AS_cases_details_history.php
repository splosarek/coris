<? include('include/include.php');
if (isset($_GET['case_id'])) {
    $query = "SELECT number, year, client_id, type_id, paxname, paxsurname, paxdob, watch, ambulatory, hospitalization, transport, decease, costless, unhandled, archive, reclamation, attention , attention2 FROM coris_assistance_cases WHERE case_id = $_GET[case_id]";

   	if ($_SESSION['new_user']==1){
			$query .= " AND `date` >= '2008-05-01 00:00:00' AND (client_id=7592 OR client_id=600 ) ";			
	}

    if ($result = mysql_query($query)){
    	if (mysql_num_rows($result)==0)	die('brak sprawy');
        $row = mysql_fetch_array($result);
    }else
        die(mysql_error());
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
<title><?php echo "$row[paxsurname], $row[paxname] [$row[number]/". substr($row['year'],2,2) ."/$row[type_id]/$row[client_id]] - ".AS_CASD_HIST  ?></title>
<link href="Styles/general.css" rel="stylesheet" type="text/css">
</head>
  <body>
    <script language="javascript">
    

    
    <!--
      function loadFax(noweokno) {
        if (noweokno != "") {
          window.open(noweokno,'','toolbar=0,scrollbars=yes,location=no,status=yes,menubar=no,resizable=no,width='+ (screen.availWidth - 6) + ',height='+ (screen.availHeight - 100) +',left=0,top=0');
        }
      }
    //-->
    </script>
    <style>
            body {
                margin-top: 0.1cm;
                margin-bottom: 0.1cm;
                margin-left: 0.1cm;
                margin-right: 0.1cm;
            }
    </style>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%">
      <tr>
        <td width="90%">
          <table cellpadding="2" cellspacing="0" border="0" width="100%">
            <tr height="30">
              <td width="60%"></td>
              <td bgcolor="#dfdfdf" align="right" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid;" rowspan="2" valign="top">
              <?PHP if (!(strcmp(1, $row['attention']))) {echo "<font style=\"background: red; color: yellow\">".ATTENTION2."</font>";} 
              if ( $row['attention2'] ==1 ) {echo "<font style=\"background: #6699cc; color: yellow\">".ATTENTION2."</font>";} 
              ?>
    <?
    echo "<b>$row[number]/". substr($row['year'],2) ."/$row[type_id]/$row[client_id]</b><br>";

    echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr height=\"3\"><td></td></tr></table>";
include('include/AS_cases_details_type_inc.php');
    ?>
              </td>
            </tr>
            <tr height="25">
              <td align="center" bgcolor="#eeeeee" style="border-top: #000000 1px solid; border-left: #000000 1px solid;"><?= $row['paxsurname'] ?>, <?= $row['paxname'] ?></td>
            </tr>
          </table>
          <table cellpadding="2" cellspacing="0" border="0" width="100%" height="89%" bgcolor="#cccccc" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-left: #000000 1px solid; border-bottom: #000000 1px solid;">
            <tr>
              <td valign="top">
                <!-- Właściwe okno -->
                <table cellpadding="2" cellspacing="2" border="0" width="100%">
                  <tr>
                    <td width=66% align="center" valign="middle" style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                                            <small><?= AS_CASD_WYBDOK ?></small><br>&nbsp;
                      <select name="new_document" style="font-family: Verdana; font-size: 8pt;" onchange='loadFax(this.value); location.reload();'>
                        <option value="">(Wybierz...)</option>
                        <option value="AS_forms_fax2.php?case_id=<?= $_GET['case_id'] ?>"><?= AS_CASD_NOWDOK ?></option>
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=14&doclang=uk'; ?>"><?= AS_CASD_PORSBRM1 ?></option>
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=15&doclang=uk'; ?>"><?= AS_CASD_PORSBRM2 ?></option>
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=13&doclang=pl'; ?>"><?= AS_CASD_HOLINTER ?></option>
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=12&doclang=pl'; ?>"><?= AS_CASD_HOLINNE ?></option>                        
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=3&doclang=pl'; ?>"><?= AS_CASD_WYNSAM ?></option>
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=4&doclang=pl'; ?>"><?= AS_CASD_REZNOCL ?></option>
                        <option value="AS_forms_fax2.php?case_id=<?  echo $_GET['case_id'].'&doc=5&doclang=pl'; ?>"><?= AS_CASD_AMBPL ?></option>                                                
                      </select>

                    </td>
                    <td width="33%" align="center" style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                      <small><?= AS_CASD_FILTR ?></small><br>&nbsp;
                      <select style="font-family: Verdana; font-size: 8pt;" name="filter_documents" onchange="zastosuj_filter()"    >
                        <option value="0"><?= AS_CASD_WYB ?></option>
                        <option value="all"><?= AS_CASD_WSZ ?></option>
                        <option value="3"><?= AS_CASD_ROZM ?></option>
                        <option value="4"><?= AS_CASD_NOT ?></option>
                        <option value="1"><?= FAX ?></option>
                        <option value="2"><?= EMAIL ?></option>
                      </select>
                    </td>
                  </tr>
                 
                  <tr>
<script>
function zastosuj_filter(){
  filter=document.all.filter_documents.value;
  if (filter=='0')
    return
  if (filter=='all')
    document.historyframe.location='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>';
  else  
    document.historyframe.location='AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>&type_id=' +filter;  
  
}

</script>                
                    <td align="center" colspan="2" style="background: #e0e0e0; border-left: #6699cc 1px solid; border-right: #6699cc 1px solid; border-top: #6699cc 1px solid; border-bottom: #6699cc 1px solid;">
                      <iframe id="historyframe" name="historyframe" width="100%" height="418" src="AS_cases_details_history_frame.php?case_id=<?= $_GET['case_id'] ?>" frameborder="0"></iframe>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
        <td width="10%">
          <table cellpadding="2" cellspacing="0" border="0" style="border-top: #000000 1px solid; border-right: #000000 1px solid;">
            <tr height="54">
              <td bgcolor="<?= ($row['type_id'] == 1) ? "orange" : "#6699cc" ?>">
              </td>
            </tr>
          </table>
                    <table cellpadding="2" cellspacing="0" border="0" height="89%" bgcolor="#ffffff" style="border-top: #000000 1px solid; border-right: #000000 1px solid; border-bottom: #000000 1px solid;">
                        <tr>
                            <td valign="top" align="center">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" title="<?= AS_CASD_TECZKA2 ?>" style="font-size: 32pt">Ě</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_TECZKA2 ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_variables.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt" title="<?= AS_CASD_USTAW ?>">'</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_USTAW ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_expenses.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 32pt" title="<?= AS_CASD_WYK ?>">@</font>&nbsp;<br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_WYK ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                    <td align="center"><font color="#6699cc" style="font-size: 42pt" face="Webdings" title="dokumenty">Ň</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_DOK ?></font></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_note.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_NOT ?>">¤</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_NOT ?></font></a></td>
                                    </tr>
                                    <tr height="50">
                                        <td align="center"><a href="AS_cases_details_todo.php?case_id=<?= $_GET['case_id'] ?>"><font color="#ced9e2" face="Webdings" onmouseover="this.color='#bec9d2'" onmouseout="this.color='#ced9e2'" style="font-size: 42pt;" title="<?= AS_CASD_ZAD ?>">ë</font><br><font color="#999999" style="font-size: 7pt;"><?= AS_CASD_ZAD ?></font></a></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
        </td>
      </tr>
    </table>
  </body>
</html>
<? } ?>
