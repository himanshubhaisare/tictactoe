<?php

class Validator {

    private $request;

    private $errors;

    public function __construct($request) {
        $this->request = $request;
        $this->errors = array();
    }

    /**
     * @return mixed
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function validate() {
        $user = $this->request['user_name'];
        $text = explode(' ', $this->request['text']);
        $command = ($text[0] == '') ? 'help' : $text[0];
        $arg = isset($text[1])? $text[1] : null;

        $this->isValidCommand($command, $arg);
        $this->isMyTurn($command, $user);
        $this->isGameInProgress($command);
        $this->validateMove($command, $user, $arg);
        if (in_array($command, array(Commands::$HELP, Commands::$STATUS))) {
            $this->request['text'] = $command;
            $this->errors = array();
        }

        if (empty($this->errors)) {
            return true;
        }
    }

    /**
     * @param $command
     * @param $user
     */
    private function isMyTurn($command, $user) {
        if (in_array($command, array(Commands::$MOVE, Commands::$CHALLENGE))) {
            $db = Database::getInstance();
            $game = $db->getGameState();
            if (!empty($gameState)) {
                if ($game[Game::$WHOSTURN] !== $user) {
                    $this->errors[] = "@$user, this is not your turn. Please wait for your turn.";
                }
            }
        }
    }

    /**
     * @param $command
     */
    private function isGameInProgress($command) {
        $db = Database::getInstance();
        $game = $db->getGameState();
        $gameInProgress = !empty($game);
        if ($gameInProgress && $command === Commands::$CHALLENGE) {
            $this->errors[] = "Another game is in progress. Please wait for it to end or `/ttt end` to force end.";
        }
    }

    /**
     * @param $command
     * @param $arg
     */
    private function isValidCommand($command, $arg) {
        if (!in_array($command, array(Commands::$CHALLENGE, Commands::$END, Commands::$HELP, Commands::$MOVE, Commands::$STATUS))) {
            $this->errors[] = "$command is not a valid command. `/ttt help` for manual.";
        }
        if (in_array($command, array(Commands::$MOVE, Commands::$CHALLENGE))) {
            if (empty($arg)) {
                switch ($command) {
                    case Commands::$MOVE:
                        $this->errors[] = "$command command also needs position 1 to 9. e.g. `/ttt move 5` or`/ttt help` for manual.";
                        break;
                    case Commands::$CHALLENGE:
                        $this->errors[] = "$command command also needs user to be challenged. e.g. `/ttt challenge @user` or `/ttt help` for manual.";
                        break;
                    default:
                        break;
                }
            }
        }
    }

    /**
     * @param $command
     * @param $player
     * @param $position
     */
    private function validateMove($command, $player, $position) {
        if ($command === Commands::$MOVE) {
            //1. cannot move <1 and >9
            if (empty($position) || $position < 1 || $position > 9) {
                $this->errors[] = "$command <position> where position is between 1 to 9. e.g. `/ttt move 5`";
            } else {
                //2. cannot move where position is already occupied
                $db = Database::getInstance();
                $game = $db->getGameState();
                if ($game[Game::$MOVES][$position] == Game::$X || $game[Game::$MOVES][$position] == Game::$O) {
                    $this->errors[] = "tile $position is already taken. Please play again.";
                    $game[Game::$WHOSTURN] = "@$player";
                    $db->setGameState($game);
                }
            }
        }
    }

    /**
     * @param $command
     * @param $challenger
     * @param $opponent
     */
    private function challengingSelf($command, $challenger, $opponent) {
        $opponent = str_replace('@', '', $opponent);
        if (($command === Commands::$CHALLENGE) && ($challenger === $opponent)) {
            $this->errors[] = "Cannot challenge yourself $challenger.";
        }
    }
}