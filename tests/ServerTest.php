<?php

class ServerTest {

    /**
     * @var Server
     */
    private $server;

    /**
     * @var array
     */
    private $request;

    /**
     * @var string
     */
    private $gameStateEmpty;

    /**
     * @var string
     */
    private $gameStateInProgress;

    /**
     * ServerTest constructor.
     */
    public function __construct() {
        $this->server = Server::getInstance();
        $this->request = array('user_name' => '@testUser', 'command' => '/ttt');
        $this->gameStateEmpty = '[]';
        $this->gameStateInProgress = '{"challenger":"@himanshu2","opponent":"@himanshu","challenger_tic":"X","opponent_tic":"O","whosturn":"@himanshu","count":1,"result":"","moves":{"1":"X","2":2,"3":3,"4":4,"5":5,"6":6,"7":7,"8":8,"9":9}}';
    }

    /**
     * Run tests
     */
    public function run() {
        // 1. test /ttt command works
        $this->testServerHandlesAbsenceOfCommand();

        // 2. test /ttt help works
        $this->testServerHandlesHelpCommand();

        // 3. test /ttt status works
        $this->testServerHandlesStatusCommandWhenGameIsNotInProgress();
        $this->testServerHandlesStatusCommandWhenGameInProgress();

        // 4. test /ttt end works
        $this->testServerHandlesEndCommandWhenGameInProgress();
        $this->testServerHandlesEndCommandWhenGameIsNotInProgress();

        // 5. test /ttt challenge works
        $this->testServerHandlesChallengeCommandWithoutOpponent();
        $this->testServerHandlesChallengeCommandWithOpponent();
        $this->testServerHandlesChallengeCommandWhenGameInProgress();

        // 6. test /ttt move works
        $this->testServerHandlesMoveCommandWhenGameNotInProgress();
        $this->testServerHandlesMoveCommandWhenNotYourTurn();
        $this->testServerHandlesMoveCommandWhenYourTurn();
        $this->testServerHandlesMoveCommandOnOccupiedPosition();
        $this->testServerHandlesMoveCommandWithoutPosition();
    }

    private function testServerHandlesAbsenceOfCommand() {
        $this->request['text'] = '';
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'Tic Tac Toe commands manual:') !== false) {
            echo "server shows help when user inputs no command : PASS\n";
        } else {
            echo "server shows help when user inputs no command : FAIL\n";
        }
    }

    private function testServerHandlesHelpCommand() {
        $this->request['text'] = 'help';
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'Tic Tac Toe commands manual:') !== false) {
            echo "server shows help when user inputs 'help' command : PASS\n";
        } else {
            echo "server shows help when user inputs 'help' command : FAIL\n";
        }
    }

    private function testServerHandlesStatusCommandWhenGameIsNotInProgress() {
        $this->request['text'] = 'status';
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'No one is playing.') !== false) {
            echo "server says 'No one is playing' when user inputs 'status' command and no game in progress : PASS\n";
        } else {
            echo "server says 'No one is playing' when user inputs 'status' command and no game in progress : FAIL\n";
        }
    }

    private function testServerHandlesStatusCommandWhenGameInProgress() {
        $this->request['text'] = 'status';
        Database::getInstance()->setGameState(json_decode($this->gameStateInProgress, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'Current game state:') !== false) {
            echo "server shows game status when user inputs 'status' command when game is in progress : PASS\n";
        } else {
            echo "server shows game status when user inputs 'status' command when game is in progress : FAIL\n";
        }

        // clear gamestate
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesEndCommandWhenGameInProgress() {
        $this->request['text'] = 'end';
        Database::getInstance()->setGameState(json_decode($this->gameStateInProgress, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'Game ended.') !== false) {
            echo "server says 'game ended' when user inputs 'end' command and game is in progress : PASS\n";
        } else {
            echo "server says 'game ended' when user inputs 'end' command and game is in progress : FAIL\n";
        }

        // clear gamestate
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesEndCommandWhenGameIsNotInProgress() {
        $this->request['text'] = 'end';
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'No one is playing.') !== false) {
            echo "server says 'No one is playing' when user inputs 'end' command and no game is in progress: PASS\n";
        } else {
            echo "server says 'No one is playing' when user inputs 'end' command and no game is in progress: FAIL\n";
        }
    }

    private function testServerHandlesChallengeCommandWithoutOpponent() {
        $this->request['text'] = 'challenge';
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], 'challenge command also needs user to be challenged.') !== false) {
            echo "server shows error text 'command also needs user to be challenged.' when user inputs 'challenge' command without opponent: PASS\n";
        } else {
            echo "server shows error text 'command also needs user to be challenged.' when user inputs 'challenge' command without opponent: FAIL\n";
        }

        // clear
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesChallengeCommandWithOpponent() {
        $this->request['text'] = 'challenge @himanshu';
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "{$this->request['user_name']} has challenged @himanshu for Tic Tac Toe!") !== false) {
            echo "server starts the game when user inputs 'challenge' command with opponent: PASS\n";
        } else {
            echo "server starts the game when user inputs 'challenge' command with opponent: FAIL\n";
        }

        // clear
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesChallengeCommandWhenGameInProgress() {
        $this->request['text'] = 'challenge @himanshu';
        Database::getInstance()->setGameState(json_decode($this->gameStateInProgress, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "this is not your turn.") !== false) {
            echo "server says 'this is not your turn' when user inputs 'challenge' command while another game is in progress: PASS\n";
        } else {
            echo "server says 'this is not your turn' when user inputs 'challenge' command while another game is in progress: FAIL\n";
        }

        // clear
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesMoveCommandWhenNotYourTurn() {
        $this->request['text'] = 'move 1';
        Database::getInstance()->setGameState(json_decode($this->gameStateInProgress, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "this is not your turn.") !== false) {
            echo "server says 'this is not your turn' when user inputs 'move' command while another game is in progress: PASS\n";
        } else {
            echo "server says 'this is not your turn' when user inputs 'move' command while another game is in progress: FAIL\n";
        }

        // clear
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesMoveCommandWhenYourTurn() {
        $gameState = json_decode($this->gameStateInProgress, true);
        $this->request['text'] = 'move 5';
        $this->request['user_name'] = str_replace('@', '', $gameState[Game::$WHOSTURN]);
        Database::getInstance()->setGameState($gameState);
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "Current game state:") !== false) {
            echo "server shows current game state when user inputs 'move' command on their turn: PASS\n";
        } else {
            echo "server shows current game state when user inputs 'move' command on their turn: FAIL\n";
        }

        // clear
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesMoveCommandWhenGameNotInProgress() {
        $this->request['text'] = 'move 1';
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "No one is playing.") !== false) {
            echo "server says 'No one is playing.' when user inputs 'move' command and no game is in progress: PASS\n";
        } else {
            echo "server says 'No one is playing.' when user inputs 'move' command and no game is in progress: FAIL\n";
        }
    }

    private function testServerHandlesMoveCommandOnOccupiedPosition() {
        $gameState = json_decode($this->gameStateInProgress, true);
        $this->request['text'] = 'move 1';
        $this->request['user_name'] = $gameState[Game::$WHOSTURN];
        Database::getInstance()->setGameState($gameState);
        $this->server->handle($this->request);
        $this->request['text'] = 'move 1';
        $this->request['user_name'] = $gameState[Game::$WHOSTURN];
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "tile 1 is already taken.") !== false) {
            echo "server says 'tile is already taken' when user on occupied position: PASS\n";
        } else {
            echo "server says 'tile is already taken' when user on occupied position: FAIL\n";
        }

        // clear
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
    }

    private function testServerHandlesMoveCommandWithoutPosition() {
        $this->request['text'] = 'move';
        Database::getInstance()->setGameState(json_decode($this->gameStateEmpty, true));
        $response = $this->server->handle($this->request);
        $response = json_decode($response, true);
        if (strpos($response['text'], "move command also needs position 1 to 9") !== false) {
            echo "server says 'move command also needs position 1 to 9' when user inputs 'move' command without position: PASS\n";
        } else {
            echo "server says 'move command also needs position 1 to 9' when user inputs 'move' command without position: FAIL\n";
        }
    }
}