<?php
return [
	'tables' => [
		'operation' => [
			'id' => 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'description' => 'VARCHAR(250) NOT NULL',
			'duree_conservation_appels_jours' => 'INT(11)'
		],
		'script_opration' => [
			'idopescr' => 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'fk_script' => 'INT(11) NOT NULL',
			'fk_operation' => 'INT(11) NOT NUL'
		],
		'statut' => [
			'id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'statut' => 'VARCHAR(50) NULL DEFAULT NULL',
			'type' => 'VARCHAR(10) NOT NULL',
			'famille' => 'VARCHAR(10) NULL DEFAULT NULL',
			'liste_inclusion' => 'VARCHAR(255) NULL DEFAULT NULL',
			'liste_exclusion' => 'VARCHAR(255) NULL DEFAULT NULL',
			'est_pause_tel' => 'TINYINT(1) NULL',
			'url_doli' => 'VARCHAR(255) NULL DEFAULT NULL',
			'url_externe' => 'VARCHAR(255) NULL DEFAULT NULL',
			'action_js' => 'MEDIUMTEXT NULL DEFAULT NULL',
			'action_php' => 'MEDIUMTEXT NULL DEFAULT NULL',
			'avec_reprise' => 'TINYINT(1) NOT NULL',
			'couleur' => 'VARCHAR(7) NULL DEFAULT NULL',
			'premission_libelle' => 'VARCHAR(255) NULL DEFAULT NULL'
		]
	]
];