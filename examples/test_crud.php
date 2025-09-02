<?php
require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/Schema.php";
require_once __DIR__ . "/../src/CrudGenerator.php";

// Charger config
$config = include __DIR__ . "/../config.php";

// Initialiser Database
$db = new Database($config)
;
$nomBase = "testOperation";
$db->createDatabase($nomBase);
$db->useDatabase($nomBase);

// Générer les tables
$schemaFile = __DIR__ . "/schema/schema_campagne.php";
$schemaArray = include $schemaFile;
$schema = new Schema($db);
$schema->generate($schemaFile);

// Générer les DAO CRUD
$crud = new CrudGenerator($db);
$crud->generateAll($schemaArray);
