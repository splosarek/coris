<?php


include_once('../include/include_ayax.php');


$chapter=getValue('chapter');


if ($chapter == "") {
    echo show_chapter();

    echo show_path();
}else{
    echo show_section($chapter);
    echo show_path($chapter);
}

function show_chapter($chapter){
    $result = '<ul>';
    $query = "SELECT * FROM 	icd10_chapter ORDER BY ID";
    $mr = mysql_query($query);

    while ($row=mysql_fetch_array($mr)){
        $result .= '<li><a href="javascript:;" onClick="show_section('.$row['ID'].')">'.$row['name'].'</a><br/><br/></li>';
    }
    $result .= '</ul>';
    return $result;
}

function show_section($chapter){
    $result = '<ul>';
    $query = "SELECT * FROM 	icd10_section WHERE ID_chapter='$chapter' ORDER BY ID";
    $mr = mysql_query($query);

    while ($row=mysql_fetch_array($mr)){
        $result .= '<li><a href="javascript:;" onClick="show_diag0(\''.$chapter.'\',\''.$row['ID'].'\')">'.$row['name'].' ('.$row['ID'].')</a><br/><br/></li>';
    }
    $result .= '</ul>';
    return $result;
}


function get_chapter($chapter){

    $query = "SELECT * FROM 	icd10_chapter WHERE  ID='$chapter'";
    $mr = mysql_query($query);

    if ($row=mysql_fetch_array($mr)){
        return $row;
    }


}


function show_path($chapter){
    $result = '';



    $html = '<a href="javascript:;" onClick="show_chapter()">ICD-10 codes</a>';


    if ($chapter != ''){
        $data = get_chapter($chapter);
        $html .= '&nbsp;&nbsp;&gt;&nbsp;&nbsp;<a href="javascript:;" onClick="show_section("'.$data['ID'].'")">'.$data['name'].'</a>';

    }

    $result .=  '<script>  $(\'icd10_path\').innerHTML = \''.$html.'\' </script> ';

    return $result;



}


?>