<?php
require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/Schema.php";

$config = include __DIR__ . "/../config.php";

$db = new Database($config);

$nomBase = "testOperation";

$db->createDatabase($nomBase);

$db->useDatabase($nomBase);

$fichier = __DIR__ . "/schema/schema_campagne.php";

$schema = new Schema($db);

$schema->generate($fichier);
