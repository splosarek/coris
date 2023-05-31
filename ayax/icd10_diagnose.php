<?php


include_once('../include/include_ayax.php');


$chapter=getValue('chapter');
$section=getValue('section');
$diag0=getValue('diag0');
$diag1=getValue('diag1');


if ($diag1 != "") {
    echo show_diag1_2($diag1);

}else if ($diag0 == "") {
    echo show_diag0($chapter,$section,"");
    echo show_path($chapter,$section,"");
}else{
    echo show_diag1($chapter,$section,$diag0);
    echo show_path($chapter,$section,$diag0);
}

function show_diag0($chapter,$section,$diag0){
    $result = '<ul>';
    $query = "SELECT * FROM 	icd10_diag0  WHERE ID_section='$section' ORDER BY ID";
    $mr = mysql_query($query);

    while ($row=mysql_fetch_array($mr)){
        $result .= '<li><a href="javascript:;" onClick="show_diag1(\''.$chapter.'\',\''.$section.'\',\''.$row['ID'].'\')">'.$row['name'].' ('.$row['ID'].')</a><br/><br/></li>';
    }
    $result .= '</ul>';
    return $result;
}

function show_diag1($chapter,$section,$diag0){
    $result = '<ul>';
    $query = "SELECT * FROM 	icd10_diag1 WHERE ID_section='$section' AND ID_diag0='$diag0' ORDER BY ID";
    $mr = mysql_query($query);

    while ($row=mysql_fetch_array($mr)){
        $result .= '<li><a href="javascript:;" onClick="select_icd10(\''.$row['ID'].'\',\''.addslashes($row['name']).'\')">'.$row['name'].' ('.$row['ID'].')</a>';
        if ($row['includes'] != ''){
            $result .= '<br><b>Zawiera:</b> '.$row['includes'];
        }
        if ($row['excludes1'] != ''){
            $result .= '<br><b>Nie zawiera:</b> '.$row['excludes1'];
        }
        if ($row['excludes2'] != ''){
            $result .= '<br><b>Nie zawiera:</b> '.$row['excludes2'];
        }

        $result .= '<br/><br/></li>';
    }
    $result .= '</ul>';
    return $result;
}

function show_diag1_2($diag1){

    $query = "SELECT * FROM 	icd10_diag1 WHERE ID='$diag1' ";
    $mr = mysql_query($query);

    if ($row=mysql_fetch_array($mr)){
        $result = '<ul>';
        $result .= '<li><a href="javascript:;" onClick="select_icd10(\''.$row['ID'].'\',\''.addslashes($row['name']).'\')">'.$row['name'].' ('.$row['ID'].')</a>';
        if ($row['includes'] != ''){
            $result .= '<br><b>Zawiera:</b> '.$row['includes'];
        }
        if ($row['excludes1'] != ''){
            $result .= '<br><b>Nie zawiera:</b> '.$row['excludes1'];
        }
        if ($row['excludes2'] != ''){
            $result .= '<br><b>Nie zawiera:</b> '.$row['excludes2'];
        }

        $result .= '</li>';
        $result .= '</ul>';


        $qt = "SELECT * FROM icd10_section WHERE ID='".$row['ID_section']."'";
        $mr = mysql_query($qt);
        $rs = mysql_fetch_array($mr);
        $result .= show_path2($rs['ID_chapter'],$row['ID_section'],$row['ID_diag0']);
        return $result;
    }else{
        return '<div align="center"><b>Bledny kod: '.$diag1.'</b><br><br><a href="javascript:;" onClick="show_chapter()">Lista kodow ICD-10</a></div><br>';


    }


}


function get_diag0($diag){

    $query = "SELECT * FROM 	icd10_diag0 WHERE  ID='$diag'";
    $mr = mysql_query($query);

    if ($row=mysql_fetch_array($mr)){
        return $row;
    }
}
function get_section($section){

    $query = "SELECT * FROM 	icd10_section WHERE  ID='$section'";
    $mr = mysql_query($query);

    if ($row=mysql_fetch_array($mr)){
        return $row;
    }
}
function get_chapter($chapter){

    $query = "SELECT * FROM 	icd10_chapter WHERE  ID='$chapter'";

//    echo "$query";
    $mr = mysql_query($query);

    if ($row=mysql_fetch_array($mr)){
        return $row;
    }
}


function show_path2($chapter,$section,$diag0){
    $result = '';



    $html = '<a href="javascript:;" onClick="show_chapter()">ICD-10 codes</a>';


        $data = get_chapter($chapter);
        $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick=\'show_section("'.$data['ID'].'")\'>'.$data['name'].' </a>';

        $data = get_section($section);
        $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick=\'show_diag0("'.$chapter.'","'.$section.'")\'>'.$data['name'].' ('.$data['ID'].')</a>';

        $data = get_diag0($diag0);
        $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick=\'show_diag1("'.$chapter.'","'.$section.'","'.$diag0.'")\'>'.$data['name'].' ('.$data['ID'].')</a>';



    $result .=  '<script>  $(\'icd10_path\').innerHTML = \''.str_replace("'","\\'",$html).'\' </script> ';

    return $result;



}

function show_path($chapter,$section,$diag0){
    $result = '';



    $html = '<a href="javascript:;" onClick="show_chapter()">ICD-10 codes</a>';


        $data = get_chapter($chapter);
        $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick=\'show_section("'.$data['ID'].'")\'>'.$data['name'].'</a>';

        $data = get_section($section);
        $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick=\'show_diag0("'.$chapter.'","'.$section.'")\'>'.$data['name'].' ('.$data['ID'].')</a>';


        if ($diag0!= "") {
            $data = get_diag0($diag0);
            $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick=\'show_diag1("'.$chapter.'","'.$section.'","'.$diag0.'")\'>' . $data['name'] . ' ('.$data['ID'].')</a>';
        }



    $result .=  '<script>  $(\'icd10_path\').innerHTML = \''.str_replace("'","\\'",$html).'\' </script> ';

    return $result;



}



?>