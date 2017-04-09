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

    /**
     * @param $request
     * @return string|void
     */
    public function handle($request) {
        $text = explode(' ', $request['text']);
        $command = $text[0];
        $arg = $text[1];
        switch ($command) {
            case Commands::$HELP:
                $result = $this->help();
                break;
            case Commands::$MOVE:
                $result = $this->move($arg);
                break;
            case Commands::$CHALLENGE:
                $result = $this->challenge($arg);
                break;
            case Commands::$STATUS:
                $result = $this->status();
                break;
            case Commands::$END:
                $result = $this->end();
                break;
            default:
                break;
        }

        return $result;
    }

    private function help() {
        $result = "
        ```
        Tic Tac Toe commands manual:
        /ttt help : brings up the help menu
        /ttt move <position> : make your move on given position 1 to 9
        /ttt challenge @user : challenge @user for a tic tac toe game
        /ttt status : current game status
        /ttt end : ends current game
        ```";
        return $result;
    }

    private function move($position) {

    }

    private function challenge($user) {

    }

    private function status() {
        $currentState = "
        ```
        @user1 and @user2 wre playing.
        
        | X | O | O |
        |---+---+---|
        | O | X | X |
        |---+---+---|
        | X | O | X |
        
        @user2 won.
        
        ```";
        return $currentState;
    }

    private function end() {

    }
}