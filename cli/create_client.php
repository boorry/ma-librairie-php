<?php
// cli/create_client.php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../src/Database.php";
require_once __DIR__ . "/../src/Schema.php";
require_once __DIR__ . "/../src/CrudGenerator.php";

// Vérification des arguments
if ($argc < 3) {
    echo "Usage: php cli/create_client.php <NomClient> <SchemaFile>\n";
    exit(1);
}

$clientName = $argv[1];   // Ex: testOperation
$schemaFile = $argv[2];   // Ex: examples/schema/schema_campagne.php

if (!file_exists($schemaFile)) {
    echo " Schéma introuvable : $schemaFile\n";
    exit(1);
}

$config = include __DIR__ . "/../config.php";

// Connexion DB
$db = new Database($config);
$db->createDatabase($clientName);
$db->useDatabase($clientName);

// Génération des tables
$schema = new Schema($db);
$schema->generate($schemaFile);

// Génération des DAO CRUD
$schemaArray = include $schemaFile;
$crud = new CrudGenerator($db, __DIR__ . "/fichierDAO/$clientName");
$crud->generateAll($schemaArray);

echo "Client '$clientName' créé avec succès.\n";
echo "DAO générés dans 'cli/fichierDAO/$clientName'\n";

//php cli/create_client.php testOperation examples/schema/schema_campagne.php
