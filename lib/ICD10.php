<?php


class ICD10 {


    static function getCodeName($code){
        if ($code=="") return "";
        $query = "SELECT * FROM 	icd10_diag1 WHERE ID='$code'";
        $mr = mysql_query($query);
        if ($row = mysql_fetch_array($mr)){

            return $row['name'];
        }

    }

}


?>