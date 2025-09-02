<?php
// src/UI/Router.php
require_once __DIR__ . "/Layout.php";

class Router {
    private $db;

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function handle() {
        $page = $_GET['page'] ?? 'home';

        Layout::header();

        switch ($page) {
            case 'operation_list':
                $this->operationList();
                break;
            case 'script_list':
                $this->scriptList();
                break;
            default:
                echo "<h2>Bienvenue sur la plateforme !</h2>";
        }

        Layout::footer();
    }

    private function operationList() {
        try {
            $stmt = $this->db->query("SELECT id, description, duree_conservation_appels_jours FROM operation");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Liste des opérations</h2>";
            if (empty($rows)) {
                echo "<p>Aucun enregistrement trouvé.</p>";
                return;
            }

            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($rows[0]) as $col) {
                echo "<th scope='col'>" . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "</tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }

    private function scriptList(){
        try {
            $stmt = $this->db->query("SELECT idopescr, fk_script, fk_operation FROM script_operation");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Liste des scripts</h2>";
            if (empty($rows)) {
                echo "<p>Aucun enregistrement trouvé.</p>";
                return;
            }

            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($rows[0]) as $col) {
                echo "<th scope='col'>" . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "</tr>";
            foreach ($rows as $row){ echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        } 
    }
}
