<?php

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/UI/Router.php";
require_once __DIR__ . '/../src/UI/Layout.php';

// Connexion DB
$config = include __DIR__ . "/../config.php";

// Choisir le client depuis URL
$client = $_GET['client'] ?? "testOperation";

$db = new Database($config);
$db->useDatabase($client);

$router = new Router($db->getConnection());
$router->handle();
