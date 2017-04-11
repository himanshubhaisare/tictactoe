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
                $result = $this->move($request['user_name'], $arg);
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

    /**
     * @return string
     */
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

    /**
     * @param $player
     * @param $position
     * @return string
     */
    private function move($player, $position) {
        $db = Database::getInstance();
        $game = $db->getGameState();
        if (!empty($game)) {
            if ($game[Game::$CHALLENGER] == "@$player") {
                $tic = $game[Game::$CHALLENGER_TIC];
                $game[Game::$WHOSTURN] = $game[Game::$OPPONENT];
            } else {
                $tic = $game[Game::$OPPONENT_TIC];
                $game[Game::$WHOSTURN] = $game[Game::$CHALLENGER];
            }
            $game[Game::$MOVES][$position] = $tic;
            $game[Game::$COUNT] += 1;

            $winner = $this->findWinner($game);
            if ($winner != null) {
                // game over!
                $db->clearGameState();
                $game[Game::$RESULT] = "$winner won! GAME OVER!";
                $result = "
                ```
                {$game[Game::$CHALLENGER]} is playing Tic Tac Toe with {$game[Game::$OPPONENT]}.
                {$game[Game::$CHALLENGER]} is {$game[Game::$CHALLENGER_TIC]}
                {$game[Game::$OPPONENT]} is {$game[Game::$OPPONENT_TIC]}
                
                $player played $tic at $position.
                
                Current game state:
                
                | {$game[Game::$MOVES][1]} | {$game[Game::$MOVES][2]} | {$game[Game::$MOVES][3]} |
                |---+---+---|
                | {$game[Game::$MOVES][4]} | {$game[Game::$MOVES][5]} | {$game[Game::$MOVES][6]} |
                |---+---+---|
                | {$game[Game::$MOVES][7]} | {$game[Game::$MOVES][8]} | {$game[Game::$MOVES][9]} |
                
                {$game[Game::$RESULT]}
                ```
                ";
            } else {
                $db->setGameState($game);
                $result = "
                ```
                {$game[Game::$CHALLENGER]} is playing Tic Tac Toe with {$game[Game::$OPPONENT]}.
                {$game[Game::$CHALLENGER]} is {$game[Game::$CHALLENGER_TIC]}
                {$game[Game::$OPPONENT]} is {$game[Game::$OPPONENT_TIC]}
                
                $player played $tic at $position.
                
                Current game state:
                
                | {$game[Game::$MOVES][1]} | {$game[Game::$MOVES][2]} | {$game[Game::$MOVES][3]} |
                |---+---+---|
                | {$game[Game::$MOVES][4]} | {$game[Game::$MOVES][5]} | {$game[Game::$MOVES][6]} |
                |---+---+---|
                | {$game[Game::$MOVES][7]} | {$game[Game::$MOVES][8]} | {$game[Game::$MOVES][9]} |
                
                {$game[Game::$WHOSTURN]} turn to play.
                ```
                ";
            }

        } else {
            $result = "```No one is playing.```";
        }

        return $result;
    }

    /**
     * @param $challenger
     * @param $opponent
     * @return string
     */
    private function challenge($challenger, $opponent) {
        $db = Database::getInstance();
        $game = $db->clearGameState();
        $game[Game::$CHALLENGER] = "@$challenger";
        $game[Game::$OPPONENT] = $opponent;
        $game[Game::$CHALLENGER_TIC] = Game::$X;
        $game[Game::$OPPONENT_TIC] = Game::$O;
        $game[Game::$WHOSTURN] = "@$challenger";
        $game[Game::$COUNT] = 0;
        $game[Game::$RESULT] = "";

        for ($i = 1; $i < 10; $i++) {
            $game[Game::$MOVES][$i] = $i;
        }
        $db->setGameState($game);
        $result = "
        ```
        @$challenger has challenged $opponent for Tic Tac Toe!
        @$challenger is {$game[Game::$CHALLENGER_TIC]}
        $opponent is {$game[Game::$OPPONENT_TIC]}
        
        Current game state:
        
        | {$game[Game::$MOVES][1]} | {$game[Game::$MOVES][2]} | {$game[Game::$MOVES][3]} |
        |---+---+---|
        | {$game[Game::$MOVES][4]} | {$game[Game::$MOVES][5]} | {$game[Game::$MOVES][6]} |
        |---+---+---|
        | {$game[Game::$MOVES][7]} | {$game[Game::$MOVES][8]} | {$game[Game::$MOVES][9]} |
        
        @$challenger turn to play.
        ```
        ";

        return $result;
    }

    /**
     * @return string
     */
    private function status() {
        $db = Database::getInstance();
        $game = $db->getGameState();
        if (!empty($game)) {
            $result = "
            ```
            {$game[Game::$CHALLENGER]} is playing Tic Tac Toe with {$game[Game::$OPPONENT]}.
            {$game[Game::$CHALLENGER]} is {$game[Game::$CHALLENGER_TIC]}
            {$game[Game::$OPPONENT]} is {$game[Game::$OPPONENT_TIC]}
            
            Current game state:
            
            | {$game[Game::$MOVES][1]} | {$game[Game::$MOVES][2]} | {$game[Game::$MOVES][3]} |
            |---+---+---|
            | {$game[Game::$MOVES][4]} | {$game[Game::$MOVES][5]} | {$game[Game::$MOVES][6]} |
            |---+---+---|
            | {$game[Game::$MOVES][7]} | {$game[Game::$MOVES][8]} | {$game[Game::$MOVES][9]} |
            
            {$game[Game::$WHOSTURN]} turn to play.
            ```
            ";
        } else{
            $result = "```No one is playing.```";
        }

        return $result;
    }

    /**
     * @return string
     */
    private function end() {
        $db = Database::getInstance();
        $game = $db->getGameState();
        $db->clearGameState();
        if (!empty($game)) {
            $result = "
            ```
            {$game[Game::$CHALLENGER]} was playing Tic Tac Toe with {$game[Game::$OPPONENT]}.
            {$game[Game::$CHALLENGER]} was {$game[Game::$CHALLENGER_TIC]}
            {$game[Game::$OPPONENT]} was {$game[Game::$OPPONENT_TIC]}
            
            Last game state:
            
            | {$game[Game::$MOVES][1]} | {$game[Game::$MOVES][2]} | {$game[Game::$MOVES][3]} |
            |---+---+---|
            | {$game[Game::$MOVES][4]} | {$game[Game::$MOVES][5]} | {$game[Game::$MOVES][6]} |
            |---+---+---|
            | {$game[Game::$MOVES][7]} | {$game[Game::$MOVES][8]} | {$game[Game::$MOVES][9]} |
            
            {$game[Game::$RESULT]}
            
            Game ended.
            ```
            ";
        } else {
            $result = "```No one is playing.```";
        }

        return $result;
    }

    /**
     * Decide winner
     *
     * @param $game
     * @return null
     */
    private function findWinner($game) {
        $winner = null;
        $winningCombinations = array(
            array(1, 2, 3),
            array(4, 5, 6),
            array(7, 8, 9),
            array(1, 4, 7),
            array(2, 5, 8),
            array(3, 6, 9),
            array(1, 5, 9),
            array(3, 5, 7)
        );

        // minimum 5 moves played, possible winner in game.
        if ($game[Game::$COUNT] > 4) {
            foreach ($winningCombinations as $combination) {
                $threePlaceMove = "";
                foreach ($combination as $move) {
                    $threePlaceMove .= $game[Game::$MOVES][$move];
                }
                if ($threePlaceMove === $game[Game::$CHALLENGER_TIC] . $game[Game::$CHALLENGER_TIC] . $game[Game::$CHALLENGER_TIC]) {
                    $winner = $game[Game::$CHALLENGER];
                    break;
                }
                else if ($threePlaceMove === $game[Game::$OPPONENT_TIC] . $game[Game::$OPPONENT_TIC] . $game[Game::$OPPONENT_TIC]) {
                    $winner = $game[Game::$OPPONENT];
                    break;
                }
            }
        }

        return $winner;
    }
}