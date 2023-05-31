<?php

class UserObject
{

    protected $_user_id;
    protected $_name;
    protected $_surname;
    protected $_position_pl;
    protected $_position_en;
    protected $_coris_branch_id;
    protected $_coris_branch_name;

    static $SALT = 'BG^%Fvjqwe732 1!8923.//.;32e!)2*BC24!2';
    static $PASSWD_EXP = 2592000; // 30 * 24 * 60 * 60 /

    function __construct($id)
    {
        $this->_user_id = $id;
        $this->loadData();
    }

    private function loadData()
    {
        if ($this->_user_id > 0) {
            $query = "SELECT cu.*, cup.name_pl as position_pl, cup.name_en as position_en,
			                 cb.ID as coris_branch_id, cb.name as coris_branch_name
			          FROM coris_users cu
			          JOIN coris_branch cb ON cb.ID=cu.coris_branch_id
			          LEFT JOIN coris_users_position cup ON cup.ID=cu.ID_position
			          WHERE user_id='" . $this->_user_id . "'";
            $mysql_result = mysql_query($query);
            if (mysql_num_rows($mysql_result) == 0) return;
            $row = mysql_fetch_array($mysql_result);
            $this->_name = $row['name'];
            $this->_surname = $row['surname'];
            $this->_position_pl = $row['position_pl'];
            $this->_position_en = $row['position_en'];
            $this->_coris_branch_id = $row['coris_branch_id'];
            $this->_coris_branch_name = $row['coris_branch_name'];
        }
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getSurname()
    {
        return $this->_surname;
    }


    public function getStempel()
    {
        if (file_exists('img/podpisy/' . $this->_user_id . '.jpg')) {
            return '<img height=100 src="img/podpisy/' . $this->_user_id . '.jpg" >';
        } else if (file_exists('img/podpisy/' . $this->_user_id . '.png')) {
            return '<img height=100 src="img/podpisy/' . $this->_user_id . '.png" >';
        } else {
            return '';
        }
    }


    public function getPosition($language = 'pl')
    {
        if ('en' == $language || 'eng' == $language || 'uk' == $language) {
            return $this->_position_en;
        } else {
            return $this->_position_pl;
        }
    }

    public function getCorisBranchID()
    {
        return $this->$this->_coris_branch_id;
    }

    public function getCorisBranchName()
    {
        return $this->$this->_coris_branch_name;
    }


    static function login_check($login, $pass)
    {

        $login = mysql_escape_string(stripslashes($login));
        $n_haslo = sha1($pass . self::$SALT);

        $query = "SELECT * FROM coris_users WHERE username='" . $login . "' AND npassword='" . $n_haslo . "' AND active=1 LIMIT 1";
        $mysql_result = mysql_query($query);
        if ((mysql_num_rows($mysql_result) == 1) && ($login <> "") && ($pass <> "")) {
            $row = mysql_fetch_array($mysql_result);

            $res = 1;
            if ($row['npassword_expired'] > 0 && $row['npassword_expired'] < time())
                $res = 2;

            return array('result' => $res, 'data' => $row);
        } else {
            sleep(1);
            return false;
        }
    }

    static function getPhoneNum($ip)
    {
        $query = "SELECT phone FROM coris_ip2phone WHERE ip LIKE '$ip'";
        $result = mysql_query($query);
        if ($row = mysql_fetch_array($result)) {
            return $row['phone'];
        }
    }

    static function userWorkUpdate($user_id, $ext, $ip)
    {

        $query = "SELECT date_start, date_end FROM coris_work WHERE user_id = '$user_id' ORDER BY date_start DESC, date_end LIMIT 1";
        if ($result = mysql_query($query)) {
            if ($row = mysql_fetch_array($result)) {
                $_SESSION['date_previous_start'] = $row['date_start'];
                $_SESSION['date_previous_end'] = $row['date_end'];
                if ($row['date_end'] == "0000-00-00 00:00:00") { // blednie zamkniete
                    $result = mysql_query("UPDATE coris_work SET date_end = NOW(), unfinished = 1 WHERE user_id = '$user_id' AND date_start='$row[date_start]'") or die(mysql_error());;
                }
            }

            $query = "INSERT INTO coris_work (user_id, ext, ip, date_start) VALUES ('$user_id', '$ext', '$ip', Now())";
            if ($result = mysql_query($query)) {
                $session_id = mysql_insert_id();
                return $session_id;
            }


        }

    }

    static function check_password_restrict($user_id, $npassword)
    {
        $n_haslo = sha1($npassword . self::$SALT);
        $query = "SELECT * FROM coris_users_password_history WHERE ID IN (SELECT ID FROM coris_users_password_history WHERE  ID_user='$user_id' ORDER BY ID desc) AND password='$n_haslo' ";
        //echo $query;
        $mysql_result = mysql_query($query);
        if (mysql_num_rows($mysql_result) > 0)
            return false;

        return true;
    }

    static function userUpdatePassword($user_id, $npassword, $admin = false, $time_exp = 1)
    {
        if ($user_id > 0) {
            $n_haslo = sha1($npassword . self::$SALT);
            $password_exp = time() + self::$PASSWD_EXP;
            $var = '';
            if ($admin) {
                $var = ' ,first_login=1 ';

                if ($time_exp == 0)
                    $password_exp = 0;
            }


            $query = "UPDATE coris_users SET npassword='$n_haslo', npassword_expired='$password_exp' $var WHERE user_id = '$user_id' LIMIT 1";
            //echo $query;
            if ($result = mysql_query($query)) {
                $query = "INSERT INTO coris_users_password_history SET ID_user='$user_id', password='$n_haslo' ";

                $result = mysql_query($query);
            }

            return true;
        }

    }

    static function passwdHash($pass)
    {
        return sha1($pass . self::$SALT);
    }
}

?>