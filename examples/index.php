<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/UI/Router.php";

// Connexion DB
$config = include __DIR__ . "/../config.php";
$db = new Database($config);
$db->useDatabase("testOperation");

// Router
$router = new Router($db->getConnection());
$router->handle();
