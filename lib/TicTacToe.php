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
                $resuult = $this->help();
                break;
            case Commands::$MOVE:
                $resuult = $this->move();
                break;
            case Commands::$CHALLENGE:
                $resuult = $this->challenge();
                break;
            case Commands::$STATUS:
                $resuult = $this->status();
                break;
            case Commands::$END:
                $resuult = $this->end();
                break;
            default:
                break;
        }

        return $resuult;
    }

    private function help() {
        $result = "```\n
    Tic Tac Toe commands manual:\n\n
    /ttt help : brings up the help menu\n
    /ttt move <position> : make your move on given position 1 to 9\n
    /ttt challenge @user : challenge @user for a tic tac toe game\n
    /ttt status : current game status\n
    /ttt end : ends current game```";
        return $result;
    }

    private function move() {

    }

    private function challenge() {

    }
    private function status() {

    }
    private function end() {

    }
}