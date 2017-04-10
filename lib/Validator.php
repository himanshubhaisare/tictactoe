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
        if ($command === 'help') {
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
     * @return bool
     */
    private function isMyTurn($command, $user) {
        $result = true;
        if (!in_array($command, array(Commands::$HELP, Commands::$STATUS))) {
            $db = Database::getInstance();
            $gameState = $db->getGameState();
            if (!empty($gameState)) {
                if ($gameState['whosturn'] !== $user) {
                    $this->errors[] = "@$user, this is not your turn. Please wait for your turn.";
                }
            }
        }

        return $result;
    }

    private function isNoOnePlaying() {
        return true;
    }

    private function isGameInProgress() {
        return !$this->isNoOnePlaying();
    }

    /**
     * @param $command
     * @param $arg
     * @return bool
     */
    private function isValidCommand($command, $arg) {
        $result = true;
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

        return $result;
    }
}