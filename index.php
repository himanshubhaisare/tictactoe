<?php

require_once 'config.php';

$server = Server::getInstance();
$response = $server->handle($_POST);
echo $response;
exit();