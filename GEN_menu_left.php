<?php include('include/include.php'); 

$lang = $_SESSION['GUI_language'];

$list_niedostepnych_main = array(12);
$list_niedostepnych_el = array(56,57,58,59);



if (isset($_SESSION['coris_branch']) &&  $_SESSION['coris_branch'] == 1 ){
		$branch_var = '';
}else{				       	
		$branch_var = " AND (  branch_id='".$_SESSION['coris_branch']."' OR branch_id='0' )";
}


$query_topics = "SELECT topic_id, `value`,`value_eng`  FROM coris_menu_topics WHERE active = 1 $branch_var ORDER BY `order` ASC";
$topics = mysql_query($query_topics) or die(mysql_error());
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
  <script type="text/javascript">
    
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
      
function open_alerts(){
	window.open('AS_cases_alerts_list.php', 'AlertAll', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=630,height=550,left=0,top=0');	
	return false;
}      
    
  </script>
  
  
  <table width="100%" border="0" cellspacing="0" cellpadding="4">
    <tbody>
      <tr style="background-color: #cccccc;">
        <td colspan="2"><strong>Menu</strong></td>
      </tr>
            <?php 
      do {       	
				  if ($_SESSION['new_user'] == 1 && in_array($row_topics['topic_id'],$list_niedostepnych_main) ) {
				      			
				      			
				  }else{
				      ?>
				            <tr style="background-color: #d9d9d9; ">
				              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?php echo (( $lang=='en' && $row_topics['value_eng'] !='' ) ? $row_topics['value_eng'] : $row_topics['value'] ); ?></td>
				            </tr>          
				      <?php 
				      
				    
				      
				      $query_elements = "SELECT element_id, `value`,`value_eng`, resource,new_window,`option` FROM coris_menu_elements WHERE topic_id ='".$row_topics['topic_id']."' AND active = 1 $branch_var ORDER BY `order` ASC";
				      $elements = mysql_query($query_elements) or die(mysql_error() . '<br>'.$query_elements);
				      $row_elements = mysql_fetch_assoc($elements);
				      $totalRows_elements = mysql_num_rows($elements);  
					
				      $dostep_do_raportow_vig = array(26, 39, 155, 315, 4,76);
				      
				      if ($totalRows_elements) do { 
				      		if ($row_elements['element_id'] == 65 && !in_array( $_SESSION['user_id'],$dostep_do_raportow_vig) ) { //Raporty VIG
				      		
				      		}else if ($_SESSION['new_user'] == 1 && in_array($row_elements['element_id'],$list_niedostepnych_el) ) {
				      			
				      			
				      		}else{
				      			?>    
				            <tr>
				              <td>&#149;</td>
				              <td width="100%" nowrap><a href="<?php 
				              
				              if ( $row_elements['resource'] == 'AS_cases_add_med.php' && $_SESSION['user_id'] == 16 )
				              			echo 'AS_cases_add_tech.php';
				              else
				              			echo $row_elements['resource']; 
				              
				              ?>" target="<?php
				              if ($row_elements['new_window']==1)
				                echo "_blank";
				              else 
				                echo "main"; ?>" <?php echo $row_elements['option']; ?>  ><?php echo  (( $lang=='en' && $row_elements['value_eng'] !='' ) ? $row_elements['value_eng'] : $row_elements['value'] ); ?></a></td>
				            </tr>
				            <?php 
				      		}
				            
				        } while ($row_elements = mysql_fetch_assoc($elements));
				      ?>
				            <tr>
				              <td colspan="2" height="4"></td>      
				      <?php      
				  }
      } while ($row_topics = mysql_fetch_assoc($topics));      
      ?>      
      
<?php if (isset($_SESSION['coris_branch']) &&  $_SESSION['coris_branch'] == 1 ){ ?>      
<!--  
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;">Płatności Europa</td>
            </tr>          
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_europa_claims_pay.php" target="main" >Płatności roszczenia </a></td>
            </tr> 

<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;">Płatności Compensa</td>
            </tr>          
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_vig_claims_pay.php" target="main" >Płatności roszczenia </a></td>
            </tr> 

-->
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENU_PLATNOSCI_SIGNAL ?></td>
            </tr>          
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_signal_claims_pay_list.php" target="main" ><?= GEN_MENL_LISTPL ?></a></td>
            </tr> 
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"></td>
            </tr>          
  <tr>            
   <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_signal_claims_pay.php" target="main" ><?= GEN_MENL_PLWYK ?></a></td>
            </tr>        


<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENU_WYSYLKI_AKT ?></td>
            </tr>          
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap>
              <a href="FK_tu_assist_send.php" target="main" title="Lista wysłanych paczek"><?= MENU_WYSLANE ?></a>
              &nbsp;&nbsp;<a href="FK_tu_assist_send_list.php" target="main"  title="Nowa wysyłka"><?= MENU_NOWA_WYSYLKA ?></a></td>
            </tr> 
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap>Signal: <a href="FK_signal_assist_send.php" target="main" title="Signal Lista wysłanych paczek"><?= MENU_WYSLANE ?></a>              
              <a href="FK_signal_assist_send_list.php" target="main" title="Signal Nowa wysyłka"><?= MENU_NOWA_WYSYLKA 	 ?></a>
              
              
              
              </td>
            </tr> 
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"></td>
            </tr>          
  <tr>            
  
            
<?php
$finances_deport2=array(22,100,18,154,155,4,76,115,128,16);

 if (in_array($_SESSION['user_id'],$finances_deport2)){
 
 ?>
 <tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENU_WYSYLKI_FAKTUR ?></td>
            </tr>          
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_signal_inv_list.php" target="main" ><?= MENUDOCUMENTSLIST ?></a></td>
            </tr> 
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"></td>
            </tr>          
  <tr>            
   <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_signal_inv_list_send.php" target="main" ><?= MENU_WYSLANE_PACZKI ?></a></td>
            </tr>        
<?php

 }
?>
 <tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENUDOCUMENTS ?></td>
            </tr>          
  <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" onClick="ww = window.open('DOC_in_sorter.php', 'DocIn', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1100,height=750,left=0,top=0');"><?= GEN_MENU_DOKODEBR ?></a></td>
            </tr>
  
<!-- <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" onClick="window.open('FK_fax_in_sorter.php', 'FaxIn', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=950,height=750,left=0,top=0')"><?= GEN_MENU_DOKODEBR ?></a></td>
            </tr>  
      
                        <tr>--> 
        <!--       <td>&#149;</td>
              <td width="100%" nowrap><a href="FK_dokument_out_frameset.php" target="main" ><?= GEN_MENU_DOKWYSL ?></a></td>
            </tr> --> 
			<tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="DOC_out_raport.php" target="main" ><?= GEN_MENU_DOKWYSL ?></a></td>
            </tr> 

<tr style="background-color: #898989; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENUDOCUMENTS ?> DE</td>
            </tr>          
  <tr style="background-color: #b9b9b9; ">
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" onClick="ww = window.open('DOC_in_sorter.php?branch=2', 'DocIn', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1100,height=750,left=0,top=0');"><?= GEN_MENU_DOKODEBR ?></a></td>
            </tr>
	<tr style="background-color: #b9b9b9; ">
              <td>&#149;</td>
              <td width="100%" nowrap><a href="DOC_out_raport.php?branch=2" target="main" ><?= GEN_MENU_DOKWYSL ?></a></td>
            </tr>

          <tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid; background-color: darkgreen;">Rejestracje Barclaycard</td>
          </tr>
          <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" onClick="ww = window.open('DOC_in_sorter_backlaycard.php', 'DocIn', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1100,height=750,left=0,top=0');"><?= GEN_MENU_DOKODEBR ?></a></td>
          </tr>

<tr style="background-color: #d9d9d9; ">

              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENUTASKS ?></td>
            </tr>          
  <tr>            
   <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" OnClick="window.open('AS_cases_todos_list_all.php', 'TodoSAll', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=810,height=650,left=0,top=0')"><?= AS_CASTD_LISTZAD ?></a></td>
            </tr>        
<?php }else{ ?>
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENUDOCUMENTS ?></td>
            </tr>          
  <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" onClick="ww = window.open('DOC_in_sorter.php', 'DocIn', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=1100,height=750,left=0,top=0');"><?= GEN_MENU_DOKODEBR ?></a></td>
            </tr>
    <td>&#149;</td>
              <td width="100%" nowrap><a href="DOC_out_raport.php" target="main" ><?= GEN_MENU_DOKWYSL ?></a></td>
            </tr> 
<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;"><?= MENUTASKS ?></td>
            </tr>          
  <tr>            
   <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="javascript:;" OnClick="window.open('AS_cases_todos_list_all.php', 'TodoSAll', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=810,height=650,left=0,top=0')"><?= AS_CASTD_LISTZAD ?></a></td>
            </tr>                          
            
<?php } 
/*
 if (check_admin()){
 	?>
 			<tr style="background-color: #d9d9d9; ">
              <td colspan="2" nowrap style="border-bottom: #CCCCCC 1px solid;">Administracja</td>
            </tr>          
            <tr>
              <td>&#149;</td>
              <td width="100%" nowrap><a href="GEN_users.php" target="main" >Użytkownicy</a></td>             
            </tr> 
            <tr>
              <td>&#149;</td>
               <td width="100%" nowrap><a href="GEN_users_add.php" target="main" >Nowy użytkownik</a></td>             
            </tr> 
 	<?php 
 }

*/
?>
      </tbody>
</table>


</body>
</html>