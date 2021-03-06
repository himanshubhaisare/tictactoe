<?php
define('DOCROOT', realpath(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR);

require_once DOCROOT . 'constants/Commands.php';
require_once DOCROOT . 'constants/Game.php';
require_once DOCROOT . 'lib/Server.php';
require_once DOCROOT . 'lib/Validator.php';
require_once DOCROOT . 'lib/TicTacToe.php';
require_once DOCROOT . 'store/Database.php';
require_once DOCROOT . 'store/MySql.php';
require_once DOCROOT . 'tests/ServerTest.php';