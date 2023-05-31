<?

/*
Copyright At Evernet.com.pl

* Skryp realizuj±cy podzia³ strony
* $ilosc - ilosc pozycji na stronie
* $op - parametry do skryptu
* $query zapytanie (select count(*).... ) takie same warunki jak dla zapytania roboczego
* $tryb =0 jw =1 Q\query pelne zapytanie (np. z distinctrow)
*
*Sample
       $query = select * from where warunek='$warunek' order   ;
       $query_ = select count(*) from where warunek='$warunek' ;
       $op = "&warunek=$warunek"

   if ( isset($query_) ){
       include("include/podzial_str.inc.php");
     $ilosc=10;
     $wynik = podzial_na_strony($query_,$ilosc,$op);
     $query .= " limit $wynik[1],$ilosc";
     echo  $wynik[3]."<br>";  // wyswietla ilosc stron
      wyswietl_pozycje($query,$tryb);
     echo  '<p>'.$wynik[3]. $wynik[0].'</p>';  //wyswietla ilosc stron oraz przelacznik stron
   }else
          wyswietl_pozycje($query,$tryb);    // tryb bez podzialu stron

*/
function podzial_na_strony($query,$tryb,$ilosc,$op){
    global $str,$pageName;
    $link_s = "";
    $link_l = "";
    $link_p = "";
    $link = "";



    $mysql_result = mysql_query( $query);

    if ($tryb==0){
       $row = mysql_fetch_array($mysql_result);
       $ilosc_all = $row[0];  // ilsoc wszytkich pozycji
    }else{
        $ilosc_all = mysql_num_rows($mysql_result);  // ilsoc wszytkich pozycji
    }

    mysql_free_result($mysql_result);

    $ilosc_str = floor($ilosc_all  / $ilosc);
    if ( ($ilosc_all % $ilosc) > 0  ) $ilosc_str++;

    if (!( $str > 0 ) )   $str = 1;

    if( $str > $ilosc_str ) $str=$ilosc_str;

    if ($ilosc_str > 1) {
      if  ($str > 1 )
             $link_l = '<a href="'.$pageName.'?str='.($str-1).$op.'">[&#139;&#139;'.INC_POPRZ.']</a>&nbsp&nbsp';

       $end=$ilosc_all/($ilosc*10);
       if ( ($ilosc_all%($ilosc*10)) >0 ) $end++;

       $dz = floor(($str-1)/10);
       if ( (10+10*$dz) > $ilosc_str ){
            $end=10-((10+10*$dz)-$ilosc_str);
        }
        else
            $end=10;
       if ( $end > 10 ) $end = 10;

       for ($i = 1; $i <= $end; $i++) {
           if (($i+($dz*10)) == $str) {
                $link_s .= ($i+($dz*10)).'&nbsp';
           }else {
                $link_s .= '<a href="'.$pageName.'?str='.($i+($dz*10)).$op.'">'.($i+($dz*10)).'</a>&nbsp';
        }
      }
      if ( $str < $ilosc_str )
           $link_p = '&nbsp<a href="'.$pageName.'?str='.($str+1).$op.'">['.INC_NAST.'&#155;&#155;]</a>&nbsp '.INC_NASTZ.' <a href=\''.$pageName.'?str='.$ilosc_str.$op.'\'>'.$ilosc_str.'</a>';

     $link = "<br><DIV class=\"std1\">$link_l$link_s$link_p</div><br>";
    }
    // $p_od = ($th-1)*100+(($str-1)*$ilosc)+1;
   $p_od = (($str-1)*$ilosc)+1;
    if ( $ilosc_all == 0 ) $p_od=0;

    if ( $ilosc > $ilosc_all)
        $p_do = $p_od + $ilosc_all-1;
    else
        $p_do = $p_od + $ilosc-1;
    if ( $p_do > $ilosc_all ) $p_do = $ilosc_all;

    if ( $ilosc_all == 0 ){ $p_od=0; $p_do=0;}

    $link_ar[0] = $link;
    $link_ar[1] = $p_od-1;
    $link_ar[2] = $ilosc_all;
    $link_ar[3] = "<br><DIV class=\"std1\" align=\"left\">".INC_STR." $str ".INC_NASTZ." <b>$ilosc_str</b></div>";
    return $link_ar;
} 


 
?>
