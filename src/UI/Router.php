<?php
require_once __DIR__ . "/Layout.php";

class Router {
    private $db;
    private const VALID_PAGES = [
        'home', 'operation_list', 'operation_add', 'operation_insert', 'operation_delete',
        'script_operation_list', 'script_operation_add', 'script_operation_insert', 'script_operation_delete',
        'statut_list', 'statut_add', 'statut_insert', 'statut_delete'
    ];

    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function handle() {
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) ?? 'home';
        if (!in_array($page, self::VALID_PAGES, true)) {
            $page = 'home';
        }

        Layout::header("Plateforme de gestion des opérations");

        switch ($page) {
            case 'operation_list':
                $this->operationList();
                break;
            case 'operation_add':
                $this->operationForm();
                break;
            case 'operation_insert':
                $this->operationInsert();
                break;
            case 'operation_delete':
                $this->operationDelete();
                break;
            case 'script_operation_list':
                $this->scriptOperationList();
                break;
            case 'script_operation_add':
                $this->scriptOperationForm();
                break;
            case 'script_operation_insert':
                $this->scriptOperationInsert();
                break;
            case 'script_operation_delete':
                $this->scriptOperationDelete();
                break;
            case 'statut_list':
                $this->statutList();
                break;
            case 'statut_add':
                $this->statutForm();
                break;
            case 'statut_insert':
                $this->statutInsert();
                break;
            case 'statut_delete':
                $this->statutDelete();
                break;
            default:
                echo "<h2>Bienvenue sur la plateforme de gestion des opérations !</h2>";
        }

        Layout::footer();
    }

    // ---------- OPERATION ----------
    private function operationList() {
        try {
            $stmt = $this->db->query("SELECT id, description, duree_conservation_appels_jours FROM operation");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Liste des opérations</h2>";
            echo '<a href="?page=operation_add"> Ajouter une opération</a>';
            if (empty($rows)) {
                echo "<p>Aucun enregistrement trouvé.</p>";
                return;
            }

            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($rows[0]) as $col) {
                echo "<th scope='col'>" . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "<th scope='col'>Actions</th></tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                }
                echo "<td><a href='?page=operation_delete&id=" . (int)$row['id'] . "'>Supprimer</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }

    private function operationForm() {
        echo "<h2>Ajouter une opération</h2>";
        echo <<<HTML
<form method="post" action="?page=operation_insert">
    <label>Description: <input type="text" name="description" required maxlength="250"></label><br><br>
    <label>Durée de conservation (jours): <input type="number" name="duree_conservation_appels_jours" min="0"></label><br><br>
    <button type="submit">Enregistrer</button>
    <a href="?page=operation_list">Annuler</a>
</form>
HTML;
    }

    private function operationInsert() {
        try {
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $duree = filter_input(INPUT_POST, 'duree_conservation_appels_jours', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) ?: null;

            if (empty($description)) {
                throw new InvalidArgumentException("La description est requise.");
            }

            $stmt = $this->db->prepare("INSERT INTO operation (description, duree_conservation_appels_jours) VALUES (:description, :duree)");
            $stmt->execute([
                'description' => $description,
                'duree' => $duree
            ]);
            echo "<p>Opération ajoutée !</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo "<a href='?page=operation_list'>Retour</a>";
    }

    private function operationDelete() {
        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if (!$id) {
                throw new InvalidArgumentException("ID invalide.");
            }

            $stmt = $this->db->prepare("DELETE FROM operation WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "<p>Opération supprimée !</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo "<a href='?page=operation_list'>⬅️Retour</a> ";
    }

    // ---------- SCRIPT_OPERATION ----------
    private function scriptOperationList() {
        try {
            $stmt = $this->db->query("SELECT so.idopescr, so.fk_script, so.fk_operation, s.nom AS script_nom, o.description AS operation_description
                                      FROM script_operation so
                                      LEFT JOIN script s ON so.fk_script = s.id
                                      LEFT JOIN operation o ON so.fk_operation = o.id");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Liste des associations script-opération</h2>";
            echo '<a href="?page=script_operation_add">Ajouter une association</a>';
            if (empty($rows)) {
                echo "<p>Aucun enregistrement trouvé.</p>";
                return;
            }

            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($rows[0]) as $col) {
                echo "<th scope='col'>" . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "<th scope='col'>Actions</th></tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                }
                echo "<td><a href='?page=script_operation_delete&id=" . (int)$row['idopescr'] . "'>Supprimer</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }

    private function scriptOperationForm() {
        try {
            $scripts = $this->db->query("SELECT id, nom FROM script")->fetchAll(PDO::FETCH_ASSOC);
            $operations = $this->db->query("SELECT id, description FROM operation")->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Ajouter une association script-opération</h2>";
            echo '<form method="post" action="?page=script_operation_insert">';
            echo '<label>Script: <select name="fk_script" required>';
            echo '<option value="">Sélectionner un script</option>';
            foreach ($scripts as $script) {
                echo "<option value='" . (int)$script['id'] . "'>" . htmlspecialchars($script['nom'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            echo '</select></label><br><br>';
            echo '<label>Opération: <select name="fk_operation" required>';
            echo '<option value="">Sélectionner une opération</option>';
            foreach ($operations as $operation) {
                echo "<option value='" . (int)$operation['id'] . "'>" . htmlspecialchars($operation['description'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            echo '</select></label><br><br>';
            echo '<button type="submit">Enregistrer</button>';
            echo '<a href="?page=script_operation_list">Annuler</a>';
            echo '</form>';
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }

    private function scriptOperationInsert() {
        try {
            $fk_script = filter_input(INPUT_POST, 'fk_script', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            $fk_operation = filter_input(INPUT_POST, 'fk_operation', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

            if (!$fk_script || !$fk_operation) {
                throw new InvalidArgumentException("Script et opération requis.");
            }

            $stmt = $this->db->prepare("INSERT INTO script_operation (fk_script, fk_operation) VALUES (:fk_script, :fk_operation)");
            $stmt->execute([
                'fk_script' => $fk_script,
                'fk_operation' => $fk_operation
            ]);
            echo "<p>Association ajoutée !</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo "<a href='?page=script_operation_list'>Retour</a>";
    }

    private function scriptOperationDelete() {
        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if (!$id) {
                throw new InvalidArgumentException("ID invalide.");
            }

            $stmt = $this->db->prepare("DELETE FROM script_operation WHERE idopescr = :id");
            $stmt->execute(['id' => $id]);
            echo "<p>Association supprimée !</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo "<a href='?page=script_operation_list'>Retour</a>";
    }

    // ---------- STATUT ----------
    private function statutList() {
        try {
            $stmt = $this->db->query("SELECT id, statut, type, famille, liste_inclusion, liste_exclusion, est_pause_tel, url_doli, url_externe, couleur, permission_libelle FROM statut");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Liste des statuts</h2>";
            echo '<a href="?page=statut_add">Ajouter un statut</a>';
            if (empty($rows)) {
                echo "<p>Aucun enregistrement trouvé.</p>";
                return;
            }

            echo "<table border='1'>";
            echo "<tr>";
            foreach (array_keys($rows[0]) as $col) {
                echo "<th scope='col'>" . htmlspecialchars($col, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "<th scope='col'>Actions</th></tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                }
                echo "<td><a href='?page=statut_delete&id=" . (int)$row['id'] . "'>Supprimer</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }

    private function statutForm() {
        echo "<h2>Ajouter un statut</h2>";
        echo <<<HTML
<form method="post" action="?page=statut_insert">
    <label>Statut: <input type="text" name="statut" maxlength="50"></label><br><br>
    <label>Type: <input type="text" name="type" required maxlength="10"></label><br><br>
    <label>Famille: <input type="text" name="famille" maxlength="10"></label><br><br>
    <label>Liste inclusion: <input type="text" name="liste_inclusion" maxlength="255"></label><br><br>
    <label>Liste exclusion: <input type="text" name="liste_exclusion" maxlength="255"></label><br><br>
    <label>Pause téléphonique: <input type="checkbox" name="est_pause_tel" value="1"></label><br><br>
    <label>URL Dolibarr: <input type="url" name="url_doli" maxlength="255"></label><br><br>
    <label>URL externe: <input type="url" name="url_externe" maxlength="255"></label><br><br>
    <label>Action JS: <textarea name="action_js"></textarea></label><br><br>
    <label>Action PHP: <textarea name="action_php"></textarea></label><br><br>
    <label>Avec reprise: <input type="checkbox" name="avec_reprise" value="1"></label><br><br>
    <label>Couleur: <input type="color" name="couleur" value="#000000"></label><br><br>
    <label>Permission: <input type="text" name="permission_libelle" maxlength="255"></label><br><br>
    <button type="submit">Enregistrer</button>
    <a href="?page=statut_list">Annuler</a>
</form>
HTML;
    }

    private function statutInsert() {
        try {
            $statut = filter_input(INPUT_POST, 'statut', FILTER_SANITIZE_STRING) ?: null;
            $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
            $famille = filter_input(INPUT_POST, 'famille', FILTER_SANITIZE_STRING) ?: null;
            $liste_inclusion = filter_input(INPUT_POST, 'liste_inclusion', FILTER_SANITIZE_STRING) ?: null;
            $liste_exclusion = filter_input(INPUT_POST, 'liste_exclusion', FILTER_SANITIZE_STRING) ?: null;
            $est_pause_tel = filter_input(INPUT_POST, 'est_pause_tel', FILTER_VALIDATE_INT) ?: 0;
            $url_doli = filter_input(INPUT_POST, 'url_doli', FILTER_VALIDATE_URL) ?: null;
            $url_externe = filter_input(INPUT_POST, 'url_externe', FILTER_VALIDATE_URL) ?: null;
            $action_js = filter_input(INPUT_POST, 'action_js', FILTER_SANITIZE_STRING) ?: null;
            $action_php = filter_input(INPUT_POST, 'action_php', FILTER_SANITIZE_STRING) ?: null;
            $avec_reprise = filter_input(INPUT_POST, 'avec_reprise', FILTER_VALIDATE_INT) ?: 0;
            $couleur = filter_input(INPUT_POST, 'couleur', FILTER_SANITIZE_STRING) ?: null;
            $permission_libelle = filter_input(INPUT_POST, 'permission_libelle', FILTER_SANITIZE_STRING) ?: null;

            if (empty($type)) {
                throw new InvalidArgumentException("Le type est requis.");
            }

            $stmt = $this->db->prepare("INSERT INTO statut (statut, type, famille, liste_inclusion, liste_exclusion, est_pause_tel, url_doli, url_externe, action_js, action_php, avec_reprise, couleur, permission_libelle) 
                                       VALUES (:statut, :type, :famille, :liste_inclusion, :liste_exclusion, :est_pause_tel, :url_doli, :url_externe, :action_js, :action_php, :avec_reprise, :couleur, :permission_libelle)");
            $stmt->execute([
                'statut' => $statut,
                'type' => $type,
                'famille' => $famille,
                'liste_inclusion' => $liste_inclusion,
                'liste_exclusion' => $liste_exclusion,
                'est_pause_tel' => $est_pause_tel,
                'url_doli' => $url_doli,
                'url_externe' => $url_externe,
                'action_js' => $action_js,
                'action_php' => $action_php,
                'avec_reprise' => $avec_reprise,
                'couleur' => $couleur,
                'permission_libelle' => $permission_libelle
            ]);
            echo "<p>Statut ajouté !</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo "<a href='?page=statut_list'>Retour</a>";
    }

    private function statutDelete() {
        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if (!$id) {
                throw new InvalidArgumentException("ID invalide.");
            }

            $stmt = $this->db->prepare("DELETE FROM statut WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "<p>Statut supprimé !</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo "<a href='?page=statut_list'>Retour</a>";
    }
}