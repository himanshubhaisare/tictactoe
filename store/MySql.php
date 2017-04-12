<?php

class MySql {

    const USER = 'root';
    const PASS = 'root';
    const HOST = 'localhost';
    const PORT = 3306;
    const DB = ' tictactoe';
    const TIME_LIMIT = 0; // No time limit
    const MEMORY_LIMIT = -1; // No memory limit

    protected static $_AFFECTED_ROWS = 0; // Counter for rows affected
    protected $_mysqli = NULL;

    /**
     * No time limit, No memory limit
     */
    public function init() {
        set_time_limit(self::TIME_LIMIT);
        ini_set('memory_limit', self::MEMORY_LIMIT);
    }

    /**
     * Destruct
     */
    public function __destruct() {
        $this->getMysqli()->close();
    }

    /**
     * Gets mysqli handler
     *
     * @param bool $forceReload
     * @return mysqli
     */
    public function getMysqli($forceReload = FALSE) {
        if ( $forceReload || is_null($this->_mysqli) ) {
            $this->initMysqli();
        }

        //this will reconnect if the connection was closed
        if ( !$this->_mysqli->ping() ) {
            $this->initMysqli();
        }

        return $this->_mysqli;
    }

    /**
     * Establish connection to mysql db
     */
    protected function initMysqli() {
        $this->_mysqli = new mysqli(self::HOST, self::USER, self::PASS, self::DB);
    }

    /**
     * Sets mysql handler
     *
     * @param mysqli $mysqli
     */
    public function setMysqli(mysqli $mysqli) {
        $this->_mysqli = $mysqli;
    }

    /**
     * @param $query
     * @return bool
     */
    public function executeQuery($query) {
        $status = FALSE;
        $mysqli = $this->getMysqli();
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
     * @return bool|mysqli_result
     */
    public function executeQueryAndGetResult($query) {
        $mysqli = $this->getMysqli();
        $query = str_replace(array("\n", "\r"), '', $query);
        $result = $mysqli->query($query);

        return $result;
    }

}