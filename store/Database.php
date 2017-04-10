<?php

class Database {

    private $gameState;

    private static $_store = DOCROOT . 'store/gamestate.json';

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
     * @return mixed
     */
    public function getGameState() {
        $store = file_get_contents(self::$_store);
        $this->gameState = json_decode($store, true);
        return $this->gameState;
    }

    /**
     * @param mixed $gameState
     */
    public function setGameState($gameState) {
        $store = json_encode($gameState);
        file_put_contents(self::$_store, $store);
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