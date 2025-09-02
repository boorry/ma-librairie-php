<?php
require_once __DIR__ . "/../src/Database.php";

$config = include __DIR__ . "/../config.php";

$db = new Database($config);

$nomBase = "testOperation";

$db->createDatabase($nomBase);

$db->useDatabase($nomBase);

// Créer une table simple
$sql = "CREATE TABLE $nomBase.`operation` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`description` VARCHAR(250) NOT NULL DEFAULT '0',
	`duree_conservation_appels_jours` INT(11) NULL DEFAULT '730',
	PRIMARY KEY (`id`)
)";

$db->exec($sql);

echo "Voir la création de la base! ";
