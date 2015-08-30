<?php

    /**
     * DataManager
     * Mysqli Version
     *
     *
     * @package
     * @subpackage
     * @author     John Doe <jd@fbi.gov>
     */
class DataManager {

    /**
     * connect to the database
     *
     * note: alternatively put those in parameter list or as class variables
     *
     * @return connection resource
     */
    protected static function getConnection() {
        $con = new mysqli('localhost', 'root', '', 'slacklight');
        if (mysqli_connect_errno()) {
            die('Unable to connect to database.');
        }
        return $con;
    }

    /**
     * place query
     *
     * @return mixed
     */
    protected static function query($connection, $query) {
        $res = $connection->query($query);
        if (!$res) {
            die("Error in query \"" . $query . "\": " . $connection->error);
        }
        return $res;
    }

    /**
     * get the key of the last inserted item
     *
     * @return integer
     */
    protected static function lastInsertId($connection) {
        return mysqli_insert_id($connection);
    }

    /**
     * retrieve an object from the database result set
     *
     * @param object $cursor result set
     * @return object
     */
    protected static function fetchObject($cursor) {
        return $cursor->fetch_object();
    }

    /**
     * remove the result set
     *
     * @param object $cursor result set
     * @return null
     */
    protected static function close($cursor) {
        $cursor->close();
    }

    /**
     * close the database connection
     *
     * @param object $cursor resource of current database connection
     * @return null
     */
    protected static function closeConnection($connection) {
        $connection->close();
    }

    public static function writeLog($logMessage){
        $con = self::getConnection();
        self::query($con, 'BEGIN;');
        $msg = $con->real_escape_string($logMessage);
        self::query($con, "INSERT INTO logging (user_id,ip,action) VALUES ('".SessionController::getUser()."','".Util::getClientIp()."', '".$msg."')");
        self::query($con, 'COMMIT;');
        self::closeConnection($con);
    }
}