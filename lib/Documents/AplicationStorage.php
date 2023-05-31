<?php

class AplicationStorage
{

    static $_dbConnection = NULL;

    static $_dbase = 'coris';
    static $_dbusername = 'script';
    static $_dbpassword = 'script';
    static $_dbhost = '127.0.0.1';

    public function __construct()
    {

        include(dirname(__FILE__) . '/AplicationStorage_config.php');

        self:: $_dbase = ASTORE_DBASE;
        self:: $_dbusername = ASTORE_DBUSERNAME;
        self:: $_dbpassword = ASTORE_DBPASSWORD;
        self:: $_dbhost = ASTORE_DBHOST;
        $this->connect();
    }


    function connect()
    {
        if (self::$_dbConnection == NULL) {
            self::$_dbConnection = mysqli_connect(self::$_dbhost, self::$_dbusername, self::$_dbpassword, self::$_dbase);
            if (self::$_dbConnection == NULL) {
                echo("Database connect Error : " . mysqli_connect_error());
                throw new Exception("Database connect Error : " . mysqli_connect_error());
            } else {
                $result = mysqli_set_charset(self::$_dbConnection, 'latin2');
            }
        }
    }

    function queryInsert($query)
    {
        $result = mysqli_query(self::$_dbConnection, $query);
        if ($result) {
            return mysqli_insert_id(self::$_dbConnection);
        } else {
            throw new Exception("Query Insert Error : $query\n<br>" . mysqli_error(self::$_dbConnection));
            return false;
        }
    }

    function queryUpdate($query)
    {
        $result = mysqli_query(self::$_dbConnection, $query);
        if ($result) {
            return true;
        } else {
            throw new Exception("Query Insert Error : $query\n<br>" . mysqli_error(self::$_dbConnection));
            return false;
        }
    }

    function query($query)
    {
        $result = mysqli_query(self::$_dbConnection, $query);
        if ($result) {
            return $result;
        } else {
            throw new Exception("Query Error : $query\n<br>" . mysqli_error(self::$_dbConnection));
            return false;
        }
    }


    function fetch_array($mr)
    {
        $result = mysqli_fetch_array($mr);

        return $result;
    }


    function num_rows($mr)
    {
        $result = mysqli_num_rows($mr);
        if ($result) {
            return $result;
        } else {
            throw new Exception("Fetch Array Error\n<br>" . mysqli_error(self::$_dbConnection));
            return false;
        }
    }

    function error()
    {
        return mysqli_error();
    }


    function getConnection()
    {
        return self::$_dbConnection;
    }

    function fetch_row($db_result)
    {
        return mysqli_fetch_array($db_result);
    }

}

?>