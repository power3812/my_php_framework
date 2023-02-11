<?php

require_once('config/init.php');


$admin_map = [
    '/' => [
        'controller' => Controller_Admin_Bbs::class,
        'method' => 'index',
    ],
    '/delete' => [
        'controller' => Controller_Admin_Bbs::class,
        'method' => 'delete',
    ],
    '/delete-image' => [
        'controller' => Controller_Admin_Bbs::class,
        'method' => 'deleteImage',
    ],
    '/recovery' => [
        'controller' => Controller_Admin_Bbs::class,
        'method' => 'recovery',
    ],
    '/login' => [
        'controller' => Controller_Admin_Login::class,
        'method' => 'login',
    ],
    '/logout' => [
        'controller' => Controller_Admin_Login::class,
        'method' => 'logout',
    ],
];

$map = [
    '/' => [
        'controller' => Controller_User_Bbs::class,
        'method' => 'index',
    ],
    '/post' => [
        'controller' => Controller_User_Bbs::class,
        'method' => 'post',
    ],
    '/edit' => [
        'controller' => Controller_User_Bbs::class,
        'method' => 'edit',
    ],
    '/delete' => [
        'controller' => Controller_User_Bbs::class,
        'method' => 'delete',
    ],
    '/login' => [
        'controller' => Controller_User_Login::class,
        'method' => 'login',
    ],
    '/logout' => [
        'controller' => Controller_User_Login::class,
        'method' => 'logout',
    ],
    '/register' => [
        'controller' => Controller_User_Register::class,
        'method' => 'register',
    ],
    '/register-post' => [
        'controller' => Controller_User_Register::class,
        'method' => 'registerPost',
    ],
    '/register-finish' => [
        'controller' => Controller_User_Register::class,
        'method' => 'registerFinish',
    ],
    '/activate' => [
        'controller' => Controller_User_Register::class,
        'method' => 'activate',
    ],
];

$namespace = '';

$router = new Router();
$router->push($admin_map, $namespace, '/admin');
$router->push($map, $namespace);
$router->run();