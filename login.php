<?php

require_once('config/init.php');

$controller = new Controller_User_Login();
$controller->setParams(array_merge($_GET, $_POST));
$controller->setFiles($_FILES);
$controller->setEnvs($_SERVER);
$controller->execute('login');
