<?php

class TicTacToe
{

    private static $_instance;

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function play($request) {
        return true;
    }
}