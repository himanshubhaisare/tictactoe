<?php

class Server {

    private static $_instance = null;

    /**
     * @return null|Server
     */
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Handles the request
     * @param $request
     * @return array|bool|string
     */
    public function handle($request) {
        $validator = new Validator($request);
        if ($validator->validate()) {
            $ticTacToe = TicTacToe::getInstance();
            $result = $ticTacToe->handle($validator->getRequest());
            $result = $this->respond($result);
        } else {
            $errors = $validator->getErrors();
            $result = $this->error($errors);
        }

        return $result;
    }

    /**
     * @param $result
     * @return string
     */
    public function respond($result) {
        $result = array(
            "response_type" => "in_channel",
            "text" => $result
        );

        $this->setResponseHeaders();
        $this->setSuccessCode();
        return json_encode($result);
    }

    /**
     * @param $messages
     * @return string
     */
    public function error($messages) {
        $singleMessage = "";
        foreach ($messages as $message) {
            $singleMessage .= $message. "\n";
        }

        $error = array(
            "response_type" => "in_channel",
            "text" => !empty($singleMessage) ? "```$singleMessage```" : "```Sorry, that didn't work. Please try again.```"
        );

        $this->setResponseHeaders();
        $this->setErrorCode();
        return json_encode($error);
    }

    public function setResponseHeaders() {
        header("Content-Type: application/json; charset=UTF-8");
    }

    public function setSuccessCode() {
        header("HTTP/1.1 200 OK");
    }

    public function setErrorCode() {
        header("HTTP/1.1 400 Bad Request");
    }
}