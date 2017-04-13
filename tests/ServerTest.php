<?php

class ServerTest {

    /**
     * @var Server
     */
    private $server;

    /**
     * Initiate test objects
     */
    public function init() {
        $this->server = Server::getInstance();
    }

    /**
     * Run tests
     */
    public function run() {

        $this->init();

        // 1. test /ttt command works
        $this->testServerHandlesAbsenceOfCommand();

        // 2. test /ttt help works
        $this->testServerHandlesHelpCommand();

        // 3. test /ttt status works
        $this->testServerHandlesStatusCommand();

        // 4. test /ttt end works
        $this->testServerHandlesEndCommand();

        // 5. test /ttt challenge works
        $this->testServerHandlesChallengeCommand();

        // 6. test /ttt move works
        $this->testServerHandlesMoveCommand();
    }

    private function testServerHandlesAbsenceOfCommand() {
        $request = array('user_name' => '@testUser', 'command' => '/ttt', 'text' => '');
        $response = $this->server->handle($request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'Tic Tac Toe commands manual:') !== false) {
            echo "server shows help when user inputs no command : PASS";
        } else {
            echo "server shows help when user inputs no command : FAIL";
        }
    }

    private function testServerHandlesHelpCommand() {
    }

    private function testServerHandlesStatusCommand() {
    }

    private function testServerHandlesEndCommand() {
    }

    private function testServerHandlesChallengeCommand() {
    }

    private function testServerHandlesMoveCommand() {
    }
}