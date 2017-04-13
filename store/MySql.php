<?php

class MySql {

    const USER = 'root';
    const PASS = 'root';
    const HOST = 'localhost';
    const PORT = 3306;
    const DB = 'tictactoe';

    private static $_AFFECTED_ROWS = 0; // Counter for rows affected
    private static $_instance;
    private static $_connection;

    /**
     * @return MySql
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Initializes the connection
     */
    public function __construct() {
        $this->initConnection();
    }

    /**
     * Destructs the connection
     */
    public function __destruct() {
        $this->getConnection()->close();
    }

    /**
     * Gets the Mysqli handler
     *
     * @param bool $forceReload
     * @return mysqli
     */
    protected function getConnection($forceReload = FALSE) {
        if ( $forceReload || is_null(self::$_connection) || !self::$_connection->ping() ) {
            // If force-reload or connection hasn't been initialized yet or if connection was initialized but got lost, then (re-)initialize
            $this->initConnection();
        }

        return self::$_connection;
    }

    /**
     * Initializes the connection
     */
    protected function initConnection() {
        self::$_connection = new mysqli(self::HOST, self::USER, self::PASS, self::DB);
    }

    /**
     * @param $query
     * @return bool
     */
    public function executeQuery($query) {
        $status = FALSE;
        $mysqli = $this->getConnection();
        $query = str_replace(array("\n", "\r"), '', $query);
        $result = $mysqli->query($query);
        if(!$result) {
            error_log("Error: ".$mysqli->error." \n");
        } else {
            if($result->num_rows > 0 ) {
                $status = TRUE;
            }
            if(strpos($query, 'INSERT') !== FALSE || strpos($query, 'UPDATE') !== FALSE) {
                $status = TRUE;
            }
            self::$_AFFECTED_ROWS += $result->num_rows;
        }

        return $status;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function executeQueryAndGetResult($query) {
        $mysqli = $this->getConnection();
        $query = str_replace(array("\n", "\r"), '', $query);
        $result = $mysqli->query($query);
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);

        return $resultArray;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function persistUser($user) {
        $query = "INSERT INTO user ('name') VALUES ('$user')";
        $this->executeQuery($query);
        $user = $this->getUser($user);
        return $user;
    }

    /**
     * @param $userName
     * @return mixed
     */
    public function getUser($userName) {
        $query = "SELECT * FROM user where name = '$userName';";
        $user = $this->executeQueryAndGetResult($query);
        return $user;
    }
}