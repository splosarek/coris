<?php


include_once('../include/include_ayax.php');


$code=getValue('code');


echo '<input type="button" value="Close" onClick="myICD10Window.close();">';
echo '<hr>';
echo '<div class="path" id="icd10_path"></div>';

echo '<div id="icd10_content"></div>';
echo '<script>';
echo '


function select_icd10(id,name){

        $(\'icd10\').value=id;
        $(\'icd10-desc\').innerHTML=name;
        myICD10Window.close();
}

function show_diag1(chapter,section,diag0){
        load_ayax(\'icd10_content\', \'ayax/icd10_diagnose.php?chapter=\'+chapter+\'&section=\'+section+\'&diag0=\'+diag0);
} 


function show_diag1(chapter,section,diag0){
        load_ayax(\'icd10_content\', \'ayax/icd10_diagnose.php?chapter=\'+chapter+\'&section=\'+section+\'&diag0=\'+diag0);
} 


function show_diag0(chapter,section){
        load_ayax(\'icd10_content\', \'ayax/icd10_diagnose.php?chapter=\'+chapter+\'&section=\'+section);
} 

function show_section(chapter){
        load_ayax(\'icd10_content\', \'ayax/icd10_chapter.php?chapter=\'+chapter);
}

function show_chapter(chapter){
        load_ayax(\'icd10_content\', \'ayax/icd10_chapter.php\');
}
';




if ($code == "") {
    echo "load_ayax('icd10_content', 'ayax/icd10_chapter.php')";
}else{
    echo "load_ayax('icd10_content', 'ayax/icd10_diagnose.php?diag1=".$code."')";
}




echo '</script>';


?>