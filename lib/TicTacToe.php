<?php

class TicTacToe {

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
        $arg = isset($text[1])? $text[1] : null;
        switch ($command) {
            case Commands::$HELP:
                $result = $this->help();
                break;
            case Commands::$MOVE:
                $result = $this->move($arg);
                break;
            case Commands::$CHALLENGE:
                $result = $this->challenge($request['user_name'], $arg);
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

    private function challenge($challenger, $opponent) {
        $db = Database::getInstance();
        $game = $db->clearGameState();
        $game[Game::$CHALLENGER] = "@$challenger";
        $game[Game::$OPPONENT] = $opponent;
        $game[Game::$CHALLENGER_TIC] = Game::$X;
        $game[Game::$OPPONENT_TIC] = Game::$O;
        $game[Game::$WHOSTURN] = $challenger;
        $db->setGameState($game);
        $result = "
        ```
        @$challenger has challenged $opponent for Tic Tac Toe!
        @$challenger is {$game[Game::$CHALLENGER_TIC]}
        $opponent is {$game[Game::$OPPONENT_TIC]}
        
        Current game state:
        
        |   |   |   |
        |---+---+---|
        |   |   |   |
        |---+---+---|
        |   |   |   |
        
        @$challenger turn to play.
        ```
        ";

        return $result;
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