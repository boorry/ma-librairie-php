<?php

class Schema {
    private $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    public function generate($schemaFile){
        if(!file_exists($schemaFile)){
            throw new Exception("Fichier de schéma introuvable : $schemaFile");
        }

        // Charger le fichier PHP qui retourne un array
        $config = include $schemaFile;

        if (!isset($config['tables']) || !is_array($config['tables'])) {
            throw new Exception("Format de schéma invalide : 'tables' manquant");
        }

        foreach ($config['tables'] as $tableName => $columns) {
            $this->createTable($tableName, $columns);
        }
    }

    private function createTable($tableName, $columns){
        $cols = [];
        foreach ($columns as $colName => $colType) {
            $cols[] = "`$colName` $colType";
        }

        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (" . implode(", ", $cols) . ")";
        $this->db->exec($sql);
        echo "Table `$tableName` créée \n";
    }

    



    
}