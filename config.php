<?php
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

require_once DOCROOT . 'lib/Commands.php';
require_once DOCROOT . 'lib/Server.php';
require_once DOCROOT . 'lib/Validator.php';
require_once DOCROOT . 'lib/TicTacToe.php';
require_once DOCROOT . 'store/Database.php';