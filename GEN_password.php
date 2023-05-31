<? 
include('REMOVE-db.php');
session_start();

if (isset($_GET['action'])) {
	if ($_POST['old'] == "") {
		$error = 1;
	} else if ($_POST['new1'] == "") {
		$error = 2;
	} else if ($_POST['new1'] != $_POST['new2']) {
		$error = 3;
	} else {
        $query = "SELECT password FROM coris_users WHERE user_id = $_SESSION[user_id]";
		if ($result = mysql_query($query, $cn)) {
            if ($row = mysql_fetch_array($result)) {
                if ($row['password'] != $_POST['old']) {
                    $error = 4;
                    mysql_free_result($result);
                } else {
                    $query = "UPDATE coris_users SET password = '$_POST[new1]' WHERE user_id = $_SESSION[user_id]";

                    if ($result = mysql_query($query, $cn)) {

                        mysql_query("BEGIN", $handle);

                        $query = "SELECT date_start, date_end FROM coris_work WHERE user_id = $_SESSION[user_id] ORDER BY date_start DESC, date_end LIMIT 1";

                        if ($result = mysql_query($query, $cn)) {

                            if ($row = mysql_fetch_array($result)) {
                                $_SESSION['date_previous_start'] = $row['date_start'];
                                $_SESSION['date_previous_end'] = $row['date_end'];

                                if ($row['date_end'] == "0000-00-00 00:00:00") { // blednie zamkniete
                                    $result = mysql_db_query("coris","UPDATE coris_work SET date_end = NOW(), unfinished = 1 WHERE user_id = $_SESSION[user_id] AND date_start='$row[date_start]'");
                                }
                            }

                            $query = "INSERT INTO coris_work (user_id, ext, ip, date_start) VALUES ('$_SESSION[user_id]', '$_SESSION[ext]', '$_SESSION[ip]', '$_SESSION[date_start]')";

                            if ($result = mysql_query($query, $cn)) {
                                $query = "SELECT @@IDENTITY FROM coris_work";

                                if ($result = mysql_query($query, $cn)) {

                                    if ($row = mysql_fetch_array($result)) {
                                        $_SESSION['session_id'] = $row[0];
                                        mysql_query("COMMIT", $handle);
                                        header("Location: AS_main.php");
                                        exit;

                                    } else {
                                        die( GEN_PASS_SESSERROR );
                                    }

                                } else {
                                    mysql_query("ROLLBACK", $handle);
                                    die(mysql_error());
                                }
                            } else {
                                mysql_query("ROLLBACK", $handle);
                                die(mysql_error());
                            }
                        } else {
                            mysql_query("ROLLBACK", $handle);
                            die(mysql_error());
                        }

                        header("Location: index.php?error=3");
                        exit;
                    } else {
                        die(mysql_error());
                    }
                }
            }
        } else {
            die(mysql_error());
        }
	}
}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="Styles/general.css">
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-2">
	</head>
<body bgcolor="#dfdfdf">
	<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%">
		<form action="GEN_password.php?action=1" method="post" name="myform">
			<tr height="100%">
				<td colspan=2 align="center" valign="middle">
					<table width="300">
						<tr>
							<td colspan=2 align="center" style="background: #ffffff">
								<?= GEN_PASS_PROSZZMHASL ?>
							</td>
						</tr>
						<tr>
							<td colspan=2 align="left" style="background: #dfdfdf">
								<br>
								<font color="#6699cc">
								<ul>
									<li><?= GEN_PASS_WPSTARHASLO ?>
									<li><?= GEN_PASS_DUKRNOWHASLO ?>
									<li><?= GEN_PASS_ZATPRZYC ?>
								</ul>
								</font>
							</td>
						</tr>
						<tr>
							<td align="right">
								<?= GEN_PASS_STHASL ?>:
							</td>
							<td>
								<input type="password" name="old" value="<?= (isset($_POST['old'])) ? $_POST['old'] : "" ?>" size="16" maxlength="25">
							</td>
						</tr>
						<tr>
							<td align="right">
								<?= GEN_PASS_NHASL ?>:
							</td>
							<td>
								<input type="password" name="new1" value="<?= (isset($_POST['new1'])) ? $_POST['new1'] : "" ?>" size="16" maxlength="25">
							</td>
						</tr>
						<tr>
							<td align="right">
								(<?= GEN_PASS_PONOW ?>):
							</td>
							<td>
								<input type="password" name="new2" value="<?= (isset($_POST['new2'])) ? $_POST['new2'] : "" ?>" size="16" maxlength="25">
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan=2 align="center" style="background: #ffffff">
								<font color="#6699cc">
<?php
if (isset($error)) {
	if ($error == 1) {
		echo GEN_PASS_NIEWPSTHASL ;
	} else if ($error == 2) {
		echo GEN_PASS_HASLONIEMOZBYCP;
	} else if ($error == 3) {
		echo GEN_PASS_WPHASLSAROZN;
	} else if ($error == 4) {
		echo GEN_PASS_BLSTARHASLO ;
	}
}
?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan=2 align="center">
								<input type="submit" value="<?= SAVE ?>" style="width: 70px; color: #6699cc; background: #ffffff">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</form>
	</table>
</body>
</html>
