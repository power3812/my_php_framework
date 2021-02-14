<?php

require_once('../config/init.php');

$controller = new Controller_Admin_Bbs();
$controller->setParams(array_merge($_GET, $_POST));
$controller->setFiles($_FILES);
$controller->setEnvs($_SERVER);
$controller->execute('deleteImage');
