<?php

class Database {

    private $gameState;

    private $_store;

    private static $_instance;

    /**
     * @return Database
     */
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Database constructor.
     */
    public function __construct() {
        $this->_store = dirname(__FILE__). '/gamestate.json';
    }

    /**
     * @return mixed
     */
    public function getGameState() {
        $store = file_get_contents($this->_store);
        $this->gameState = json_decode($store, true);
        return $this->gameState;
    }

    /**
     * @param mixed $gameState
     */
    public function setGameState($gameState) {
        $store = json_encode($gameState);
        file_put_contents($this->_store, $store);
        $this->gameState = $gameState;
    }

    /**
     * Clears game state
     * @return mixed
     */
    public function clearGameState() {
        $this->setGameState([]);
        return $this->getGameState();
    }
}