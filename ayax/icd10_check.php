<?php


include_once('../include/include_ayax.php');


$code=getValue('code');
if ($code != "") {
    echo show_diag1_2($code);
}


function show_diag1_2($diag1){

    $query = "SELECT * FROM 	icd10_diag1 WHERE ID='$diag1' ";
    $mr = mysql_query($query);

    if ($row=mysql_fetch_array($mr)){
         return '<script>$(\'icd10\').value=\''.$row['ID'].'\'; $(\'icd10-desc\').innerHTML=\''.$row['name'].'\'; </script>';
    }else{
         return '<script>alert("Bledny kod"); $(\'icd10\').value=\'\'; $(\'icd10-desc\').innerHTML=\'\'; </script>';
    }


}

?>