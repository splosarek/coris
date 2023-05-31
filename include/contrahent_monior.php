<?php
$_superUsers = array(76,100,352,261);


function zapiszLogZmian($contrahent_id,$account_id,$field,$old_value,$new_value,$id_user=0){

    $qi = "INSERT INTO coris_contrahents_logs SET
          ID_contrahent='$contrahent_id',
          ID_account = '$account_id',
          field = '$field',
          old_value = '".mysql_escape_string($old_value)."',
          new_value = '".mysql_escape_string($new_value)."',
          ID_user = '$id_user',
          ID_user_accept = '".Application::getCurrentUser()."',
          `date` = now()";

    $res = mysql_query($qi);
    if (!$res){
        echo "QE: $qi <br>".mysql_error();
        exit();
    }
}



function dodajDoKolejkiZmian($contrahent_id,$nip){

    $qi = "INSERT INTO coris_contrahents_check SET
          ID_contrahent='$contrahent_id',
          nip='$nip',
          ID_user = '".Application::getCurrentUser()."',
          `date` = now()";
    $res = mysql_query($qi);
    if (!$res){
        echo "QE: $qi <br>".mysql_error();
        exit();
    }
}

function dodajDoKolejkiZmian2($contrahent_id,$account_id,$account,$swift='',$name='',$address='',$post='',$city='',$country_id='',$note='',$order=''){
    $qi = "INSERT INTO  coris_contrahents_accounts_check  SET account_id='$account_id',contrahent_id='$contrahent_id', account='$account',swift='$swift',name='$name',address='$address',post='$post',
	city='$city',country_id='$country_id',note='$note',`order`='$order',
	date=now(),user_id='".$_SESSION['user_id']."'";

    $res = mysql_query($qi);
    if (!$res){
        echo "QE: $qi <br>".mysql_error();
        exit();
    }
}