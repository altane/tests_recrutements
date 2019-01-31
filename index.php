<?php

define('ROOT', dirname(__DIR__ . '/..'));
require ROOT . '/app/App.php';
\App\App::load();

$router = new \App\Router();
$router->add("", ["controller" => "Contact", "action" => "index"]);

$router->add("api/{action}", ["namespace" => "api"]);
$router->add("api/{action}/{id:\d+}");

$router->add("{controller}",["action" => "index"]);
$router->add("{controller}/{action}");
$router->add("{controller}/{action}/{id:\d+}");

$router->dispatch($_SERVER["QUERY_STRING"]);

/*
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'contact.index';
}

$page = explode('.', $page);
$controller = '\App\Controllers\\' . ucfirst($page[0]) . 'Controller';
$action = $page[1];
$controller = new $controller();
$controller->$action();
*/
