<?php
class Database {
    private $pdo;
    private $host;
    private $user;
    private $pass;

    public function __construct($config){
        $this->host = $config['db_host'];
        $this->user = $config['db_user'];
        $this->pass = $config['db_pass'];

        // Connexion initiale sans DB sélectionnée
        $this->pdo = new PDO("mysql:host={$this->host}", $this->user, $this->pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function createDatabase($dbName) {
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    public function useDatabase($dbName) {
        $this->pdo->exec("USE `$dbName`");
    }

    // Exécuter une requête SQL
    public function exec($sql) {
        $this->pdo->exec($sql);
    }

    // Retourner PDO (utile pour DAO plus tard)
    public function getConnection() {
        return $this->pdo;
    }



}