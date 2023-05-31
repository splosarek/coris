<?php
    		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
          header ("Cache-Control: no-store "); // HTTP/1.1
          header ("Pragma: no-cache"); // HTTP/1.0

session_start();

require_once('../include/cn.php');
include_once('../include/strona.php'); 
include_once('../include/main.php');



$contrahent_id = getValue('contrahent_id');
$result = array();

if ($contrahent_id>0){	
		
		$contrahent_details = getContrahentDetails($contrahent_id);
		if (is_array($contrahent_details) && $contrahent_details['contrahent_id']>0){
			if ($contrahent_details['o_klnotuse']==1 ){
				$result['notuse']=1;
				$result['org_contrahent_id']=$contrahent_details['contrahent_id'];
				if ($contrahent_details['contrahent_substitute']>0){
						$contrahent_details = getContrahent($contrahent_details['contrahent_substitute']); 		
						if ($contrahent_details['contrahent_id']>0){
									//OK substitut											
							$result['contrahent_id'] = $contrahent_details['contrahent_id'];
							$result['name'] = iconv('latin2','UTF-8',$contrahent_details['name']);	
						}else{
							$result['substitute_error']=1;
						}				
				}else{
					$result['substitute_error']=1;
				}				
			}else{
				//OK
				$result['contrahent_id'] = $contrahent_details['contrahent_id'];
				$result['name'] = iconv('latin2','UTF-8',$contrahent_details['name']);
			}
		}else{
			$result['error']=1;
		}
		
}else{
	$result['error']=1;
}

function getContrahent($contrahent_id){
		$contrahent_substitute_details = getContrahentDetails($contrahent_id);
		if ($contrahent_substitute_details['o_klnotuse']==1 ){
				return getContrahent($contrahent_substitute_details['contrahent_substitute']);
		}else{
			return $contrahent_substitute_details;
		}
}


function  getContrahentDetails($contrahent_id){

    $query_userBranch = '';
    // jesli zalogowany oddzial 1 czyli Coris Polska to pokazac wszystkich (czyli bez filtra)
    // jesli zalogowany z innego oddzialu, to pokazac tylko kontrahentow danego oddzialu
    // w innych przypadkach nic nie pokazywac
   /* if (isset($_SESSION['coris_branch']) && intval($_SESSION['coris_branch'])>0){
        if( $_SESSION['coris_branch'] == 1){
            $query_userBranch = '';
        }else if( $_SESSION['coris_branch'] <> 1){
            $userCorisBranchId = intval($_SESSION['coris_branch']);
            $query_userBranch = " AND (coris_contrahents.coris_branch_id ='$userCorisBranchId'
                                         OR coris_contrahents.coris_branch_id = 0 )
                                  ";
        }
    }else{
        $query_userBranch = " AND coris_contrahents.coris_branch_id ='-1' ";
    }

    $branch_id = getValue('branch_id');
    if ($branch_id > 0){
    		  $query_userBranch = " AND (coris_contrahents.coris_branch_id ='$branch_id'
    		                               OR coris_contrahents.coris_branch_id ='0')";
    }
*/
	$query_contrahent = "SELECT * FROM  coris_contrahents WHERE contrahent_id='$contrahent_id' ";
	$query_contrahent .= $query_userBranch;

    $contrahent_result = mysql_query($query_contrahent) or die(mysql_error());
	$row_contrahent = mysql_fetch_array($contrahent_result);
	return $row_contrahent;
}



echo json_encode($result);

?>