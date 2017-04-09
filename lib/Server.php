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
        $valid = Validator::validate($request);
        if ($valid === true) {
            $ticTacToe = TicTacToe::getInstance();
            $result = $ticTacToe->handle($request);
            $result = $this->respond($request);
        } else {
            $result = $this->error($valid);
        }

        return $result;
    }

    /**
     * @param $result
     * @return string
     */
    public function respond($result) {
        $cannedResult = "
        ```
        | X | O | O |
        |---+---+---|
        | O | X | X |
        |---+---+---|
        | X | O | X |
        ```";
        $result = array(
            "response_type" => "in_channel",
            "text" => $cannedResult
        );

        $this->setResponseHeaders();
        $this->setSuccessCode();
        return json_encode($result);
    }

    /**
     * @param $message
     * @return string
     */
    public function error($message) {
        $error = array(
            "response_type" => "in_channel",
            "text" => !empty($message) ? $message : "Sorry, that didn't work. Please try again."
        );

        $this->setResponseHeaders();
        $this->setErrorCode();
        return json_encode($error);
    }

    /**
     * Example headers from reddit api
     *
            accept-ranges:bytes
            access-control-allow-origin:*
            access-control-expose-headers:X-Reddit-Tracking, X-Moose
            cache-control:max-age=0, must-revalidate
            content-encoding:gzip
            content-length:6902
            content-type:application/json; charset=UTF-8
            date:Sun, 09 Apr 2017 02:38:48 GMT
            status:200
            strict-transport-security:max-age=15552000; includeSubDomains; preload
            vary:accept-encoding
            via:1.1 varnish
            x-cache:MISS
            x-cache-hits:0
            x-content-type-options:nosniff
            x-frame-options:SAMEORIGIN
            x-moose:majestic
            x-reddit-tracking:https://pixel.redditmedia.com/pixel/of_destiny.png?v=GXmqXGu%2FTPDTXoGKtiQ1XXpMolpvUHvm5h5lplAQcBOoKPEn8cEEHX991%2BKUAKCcafETsSl%2Fsx6mfA%2BgHw2RWPZAYVvfaMKm
            x-served-by:cache-sjc3141-SJC
            x-timer:S1491705528.290535,VS0,VE230
            x-ua-compatible:IE=edge
            x-xss-protection:1; mode=block

     */
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