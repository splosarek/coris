<?php require_once('include/include.php');

if (getValue('logout') == 1) {

    $_SESSION['user_id'] = '';
    $_SESSION['MM_Username'] = '';
    $_SESSION['MM_UserGroup'] = '';
    $_SESSION['department_id'] = '';
    $_SESSION['new_user'] = '';
    $_SESSION['coris_branch'] = '';

    $_SESSION['height'] = '';
    $_SESSION['ip'] = '';
    $_SESSION['date_start'] = '';

    session_destroy();
    session_regenerate_id();

    header("Location: index.php");
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($accesscheck)) {
    $GLOBALS['PrevUrl'] = $accesscheck;
    $_SESSION['PrevUrl'] = $accesscheck;
    //session_register('PrevUrl');
}

$loginUsername = getValue('username');//$_POST['username'];
$password = getValue('password');//$_POST['password'];
$content = loginForm();
$error = '';
$change_password_form = getValue('change_password_form');

if ($change_password_form == 1) {
    $npassword = getValue('npassword');
    $npassword2 = getValue('npassword2');

    $res = UserObject::login_check($cn, $loginUsername, $password);
    if ($res) {
        //echo nl2br(print_r($res['data'],1));
        if ($npassword == $npassword2) {
            if (UserObject::check_password_restrict($cn, $res['data']['user_id'], $npassword)) {

                $pass_res = UserObject::userUpdatePassword($cn, $res['data']['user_id'], $npassword);
                if ($pass_res) {
                    $content = loginPassChangeOk();
                    $error = '';
                } else {
                    $txt = "B��d zmiany has�a, prosz� spr�bowa� ponownie";
                    $content = passwdChangeForm($txt);
                }
            } else {
                $txt = "To has�o by�o ju� uzywane prosz� ustawi� inne.";
                $content = passwdChangeForm($txt);
            }
        } else {
            $txt = "B��d zmiany has�a, prosz� spr�bowa� ponownie";
            $content = passwdChangeForm($txt);
        }
    } else {
        $error = "B��dne dane, prosz� spr�bowa� ponownie";
        $content = passwdChangeForm();
    }


} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && $loginUsername != '' && $password != '') {

    $MM_fldUserAuthorization = "group_id";
    $MM_redirectLoginSuccess = "GEN_frameset.php";
    $MM_redirectLoginFailed = "index.php?error=1";
    $MM_redirecttoReferrer = false;

    $res = UserObject::login_check($loginUsername, $password);


    if ($res) {
        if ($res['result'] == 1) {
            $row = $res['data'];

            $loginStrGroup = $row['group_id'];
            $loginIntId = $row['user_id'];
            $loginDepartmentId = $row['department_id'];
            $new_user = $row['new_user'];

            //declare two session variables and assign them
            $_SESSION['user_id'] = $loginIntId;
            $_SESSION['MM_Username'] = $loginUsername;
            $_SESSION['MM_UserGroup'] = $loginStrGroup;
            $_SESSION['department_id'] = $loginDepartmentId;
            $_SESSION['new_user'] = $new_user;
            $_SESSION['coris_branch'] = $row['coris_branch_id'];

            $_SESSION['height'] = $_POST['height'];
            $ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR);
            $_SESSION['ip'] = $ip;
            $_SESSION['date_start'] = date("Y-m-d G:i:s");

            $phone = '123';
            $_SESSION['ext'] = $phone;
            $_SESSION['session_id'] = UserObject::userWorkUpdate($loginIntId, $phone, $ip);

            $content = loginForm();

            if (isset($_SESSION['PrevUrl']) && false) {
                $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
            }
            header("Location: " . $MM_redirectLoginSuccess);
            exit();
        } else if ($res['result'] == 2) { //wymagana zmiana has�a
            $content = passwdChangeForm();
        } else {
            header("Location: " . $MM_redirectLoginFailed);
            exit();
        }
    } else {
        $error = "B��dne dane, prosz� spr�bowa� ponownie";
    }
}

if (isset($_GET['error']) && $_GET['error'] == 1) {
    $error = "B��dne dane, prosz� spr�bowa� ponownie";
}

if (isset($_GET['session_error']) && $_GET['session_error'] == 1) {
    $error = "B��d sesji, prosze ponownie zalogowa� si�.<br>
						Je�li b��d powtarza si� cz�sto prosz� o kontakt z dzia�em IT";
}
?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html, charset=iso-8859-2" name="viewport">
        <title>APRIL Polska - logowanie</title>
        <style type="text/css">
            <!--
            body {
                background-color: #FFFFFF;
            }

            -->
        </style>
        <link href="Styles/general.css" rel="stylesheet" type="text/css">
        <script src="https://kit.fontawesome.com/032bf0d93e.js" crossorigin="anonymous"></script>
        <style type="text/css">
            <!--
            .style5 {
                color: #999999
            }

            -->
        </style>

        <script type="text/javascript" language="javascript" src="Scripts/mootools-core.js"></script>
        <script type="text/javascript" language="javascript" src="Scripts/mootools-more.js"></script>
    </head>

    <body onload="myform.height.value = screen.height; myform.username.focus();">
    <script type="text/javascript">
        <!--
        if (self != top) {
            if (document.images) top.location.replace(document.location.href);
            else top.location.href = document.location.href;
        }

        function laguage_change(val) {
            document.getElementById('ch_language').value = val;
            document.getElementById('form_language').submit();
        }

        //-->
    </script>

    <form method="post" action="<?php echo $loginFormAction; ?>" name="form_language" id="form_language">
        <input type="hidden" name="ch_language" id="ch_language">
    </form>
    <form action="<?php echo $loginFormAction; ?>" method="POST" name="myform" id="myform">
        <table width="600" height="400" border=0 align="center" cellpadding=0 cellspacing=0 bgcolor="#EEEEEE" style="border: #999999 1px solid;">
            <tr valign="bottom">
                <td align="center" colspan=2 valign="top" style="color:red;"><br><b><?php
                        echo $error;
                        ?></b></td>
            </tr>

            <tr height="100" valign="bottom">
                <td width="75%"></td>
                <td align="center" valign="top">
                    <div align="right">
                        <table cellpadding="5">
                            <tr>
                                <td><a href="javascript:laguage_change('pl')"><img src="img/lang_pl.gif" border="0"></a></td>
                                <td><a href="javascript:laguage_change('en')"><img src="img/lang_eng.gif" border="0"></a></td>
                            </tr>
                        </table>
                    </div>

                    <font color="#6699CC" style="font-size: 14px"><b>APRIL Polska</b></font><br>
                    <font color="#6699CC">Assistance </font>
                </td>
            </tr>
            <tr height="95%">
                <td colspan=2 align="center" valign="middle">

                    <?php echo $content; ?>
                </td>
            </tr>
            <tr height="95%">
                <td colspan=2 align="center" valign="middle">&nbsp;</td>
            </tr>
            <tr height="95%">
                <td align="center" valign="middle">
                    <div align="left"><font color="#6699CC">&nbsp;
                            <?php

                            echo($_SESSION['GUI_language'] == 'pl' ? 'polski' : '');
                            echo($_SESSION['GUI_language'] == 'en' ? 'english' : '');

                            //"<a href='index.php?lang=pl'>Polski</a>"
                            ?>
                        </font></div>
                </td>
                <td align="center" valign="middle">
                    <div align="right"><span class="style5">
	            <?= VERSION ?>
  2.1.3&nbsp; </span></div>
                </td>
            </tr>
        </table>
    </form>

    </body>
    </html>
<?php

function loginForm()
{

    $result = '	<table width="300"  cellpadding="5" border="0">
						<tr>
							<td colspan=2 align="center" style="background: #6699CC">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC">' . LOGIN . ':</font>
							</td>
							<td width="150">
								<input type="text" name="username" size="16" maxlength="25" style="background: #dfdfdf">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC">' . PASSWORD . ':</font>
							</td>
							<td>
								<input type="password" name="password" size="16" maxlength="25" style="background: #dfdfdf">
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
                                <input type="hidden" name="height" value="">
								<input type="submit" value="' . BNLOGIN . '" style="width: 70px; background: #000000; color: #ffffff"></td>
						</tr>
					</table>
					



					';

    return $result;

}

function passwdChangeForm($txt = PASSWORD_EXPIRED_CHANGE)
{

    $result = '
			<input type="hidden" name="change_password_form" value="1">
	
			<span style="color:red;font-size:14px;font-weight:bold;">' . $txt . '</span>	<table width="300"  cellpadding="5" border="0">
						<tr>
							<td colspan=2 align="center" style="background: #6699CC">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC">' . LOGIN . ':</font>
							</td>
							<td width="150">
								<input type="text" name="username" size="16" maxlength="25" style="background: #dfdfdf">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC">' . PASSWORDOLD . ':</font>
							</td>
							<td>
								<input type="password" name="password" size="16" maxlength="25" style="background: #dfdfdf">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC">' . NEWPASSWORD . ':</font>
							</td>
							<td>
								<input type="password" name="npassword" id="npassword" size="16" maxlength="25" style="background: #dfdfdf">
							</td>
						</tr>
						<tr>
							<td align="right">
								<font color="#6699CC">' . NEWPASSWORD2 . ':</font>
							</td>
							<td>
								<input type="password" name="npassword2"  id="npassword2" size="16" maxlength="25" style="background: #dfdfdf">
							</td>
						</tr>
						<tr>
							<td align="center" colspan="2">
								Has�o powinno zawiera� du�e i ma�e litery, cyfry oraz znaki specjalne. <br>D�ugo�� has�a minimum 8 znak�w
							</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
                                <input type="hidden" name="height" value="">
								<input type="button"  onClick="check_form_passwd();" value="' . CHANGE . '" style="width: 70px; background: #000000; color: #ffffff"></td>
						</tr>
					</table>
	<script>
		function check_form_passwd(){
			
			npassword = $(\'npassword\').value;
			npassword2 = $(\'npassword2\').value;
			
			if (npassword != npassword2){
					alert(\'Has�a s� r�ne\');
					return false;
			}
			paswd=  /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,}$/;  
			if( !npassword.match(paswd) ){
				alert(\'Has�o nie spe�nia kryteri�w bezpiecze�stwa\');
				return false;	
			}   			
			$(\'myform\').submit();
		}
	</script>					

';

    return $result;
}

function loginPassChangeOk()
{

    $result = '	<table width="300"  cellpadding="5" border="0">
						<tr>
							<td colspan=2 align="center" style="background: #6699CC">
							</td>
						</tr>
						<tr>
							
							
							
							<td colspan="2">
								Twoje has�o zosta�o zmienione.
								<br>Kliknij OK aby zalogowa� si� w systemie z u�yciem nowego has�a
							</td>
						</tr>						
						<tr>
							<td colspan=2 align="center">                                
								<input type="button" onClick="document.location=\'index.php?' . time() . '\'" value="OK" style="width: 70px; background: #000000; color: #ffffff"></td>
						</tr>
					</table>
					';
    return $result;
}

?>