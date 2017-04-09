<?php

require_once 'Commands.php';

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

    public function validate() {
        $user = $this->request['user_name'];
        $text = explode(' ', $this->request['text']);
        $command = $text[0];
        $arg = $text[1];
        $this->isValidCommand($command, $arg);
        $this->isMyTurn($command, $user);
        if (empty($this->errors)) {
            return true;
        }
    }

    private function isMyTurn($command, $user) {
        if(false) {
            $this->errors[] = "@$user, this is not your turn. Please wait for your turn";
        }
        return true;
    }

    private function isNoOnePlaying() {
        return true;
    }

    private function isGameInProgress() {
        return !$this->isNoOnePlaying();
    }

    private function isValidCommand($command, $arg) {
        $result = true;
        if (!in_array($command, array(Commands::$CHALLENGE, Commands::$END, Commands::$HELP, Commands::$MOVE, Commands::$STATUS))) {
            $this->errors[] = "$command is not a valid command. ```/ttt help``` for manual.";
        }
        if (in_array($command, array(Commands::$MOVE, Commands::$CHALLENGE))) {
            if (empty($arg)) {
                switch ($command) {
                    case Commands::$MOVE:
                        $this->errors[] = "$command command also needs position 1 to 9. ```/ttt help``` for manual.";
                        break;
                    case Commands::$CHALLENGE:
                        $this->errors[] = "$command command also needs user to be challenged. ```/ttt help``` for manual.";
                        break;
                    default:
                        break;
                }
            }
        }

        return $result;
    }
}