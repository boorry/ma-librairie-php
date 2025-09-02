<?php
return [
    'tables' => [
        'operation' => [
            'id' => 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'description' => 'VARCHAR(250) NOT NULL',
            'duree_conservation_appels_jours' => 'INT(11)'
        ],
        'script_operation' => [ 
            'idopescr' => 'INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'fk_script' => 'INT(11) NOT NULL',
            'fk_operation' => 'INT(11) NOT NULL'
        ],
        'statut' => [
            'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'statut' => 'VARCHAR(50)',
            'type' => 'VARCHAR(10) NOT NULL',
            'famille' => 'VARCHAR(10)',
            'liste_inclusion' => 'VARCHAR(255)',
            'liste_exclusion' => 'VARCHAR(255)',
            'est_pause_tel' => 'TINYINT(1) DEFAULT 0', 
            'url_doli' => 'VARCHAR(255)', 
            'url_externe' => 'VARCHAR(255)', 
            'action_js' => 'MEDIUMTEXT', 
            'action_php' => 'MEDIUMTEXT', 
            'avec_reprise' => 'TINYINT(1) NOT NULL DEFAULT 0', 
            'couleur' => 'VARCHAR(7)', 
            'permission_libelle' => 'VARCHAR(255)' 
        ]
    ]
];