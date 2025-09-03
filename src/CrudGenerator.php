<?php

class CrudGenerator {
    private $db;
    private $outputDir;

    public function __construct(Database $db, $outputDir = __DIR__ . '/cli/fichierDAO/'){
        if (!$db instanceof Database) {
            throw new InvalidArgumentException("L'argument \$db doit être une instance de Database.");
        }

        $this->db = $db;
        $this->outputDir = rtrim($outputDir, '/');
        if (!is_dir($this->outputDir) && !mkdir($this->outputDir, 0755, true)) {
            throw new RuntimeException("Impossible de créer le répertoire : {$this->outputDir}");
        }

    }

    public function generateDAO($tableName, $columns){
        // Validation du nom de la table
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $tableName)) {
            throw new InvalidArgumentException("Nom de table invalide : {$tableName}");
        }

        $className = ucfirst($tableName) . "DAO";
        $filePath = "{$this->outputDir}/{$className}.php";

        // Exclure les colonnes AUTO_INCREMENT
        $insertColumns = array_filter($columns, function ($definition) {
            return stripos($definition, 'AUTO_INCREMENT') === false;
        });

        $colNames = array_keys($insertColumns);
        $colList = implode(", ", $colNames);
        $paramList = ":" . implode(", :", $colNames);

        $code = "<?php\n";
        $code .= "class $className {\n";
        $code .= "    private \$pdo;\n\n";
        $code .= "    public function __construct(\$pdo) { \$this->pdo = \$pdo; }\n\n";

        // findAll()
        $code .= "    public function findAll() {\n";
        $code .= "        try {\n";
        $code .= "            \$stmt = \$this->pdo->query('SELECT * FROM $tableName');\n";
        $code .= "            return \$stmt->fetchAll(PDO::FETCH_ASSOC);\n";
        $code .= "        } catch (PDOException \$e) {\n";
        $code .= "            throw new RuntimeException('Erreur lors de la récupération des données : ' . \$e->getMessage());\n";
        $code .= "        }\n";
        $code .= "    }\n\n";

        // findById()
        $code .= "    public function findById(\$id) {\n";
        $code .= "        try {\n";
        $code .= "            \$stmt = \$this->pdo->prepare('SELECT * FROM $tableName WHERE id = :id');\n";
        $code .= "            \$stmt->execute(['id' => \$id]);\n";
        $code .= "            return \$stmt->fetch(PDO::FETCH_ASSOC);\n";
        $code .= "        } catch (PDOException \$e) {\n";
        $code .= "            throw new RuntimeException('Erreur lors de la récupération par ID : ' . \$e->getMessage());\n";
        $code .= "        }\n";
        $code .= "    }\n\n";

        // insert()
        if (!empty($colNames)) {
            $code .= "    public function insert(\$data) {\n";
            $code .= "        try {\n";
            $code .= "            \$sql = 'INSERT INTO $tableName ($colList) VALUES ($paramList)';\n";
            $code .= "            \$stmt = \$this->pdo->prepare(\$sql);\n";
            $code .= "            return \$stmt->execute(\$data);\n";
            $code .= "        } catch (PDOException \$e) {\n";
            $code .= "            throw new RuntimeException('Erreur lors de l\'insertion : ' . \$e->getMessage());\n";
            $code .= "        }\n";
            $code .= "    }\n\n";
        }

        // update() - Exemple simple
        $updateSet = implode(", ", array_map(function ($col) {
            return "$col = :$col";
        }, $colNames));
        $code .= "    public function update(\$id, \$data) {\n";
        $code .= "        try {\n";
        $code .= "            \$sql = 'UPDATE $tableName SET $updateSet WHERE id = :id';\n";
        $code .= "            \$stmt = \$this->pdo->prepare(\$sql);\n";
        $code .= "            \$data['id'] = \$id;\n";
        $code .= "            return \$stmt->execute(\$data);\n";
        $code .= "        } catch (PDOException \$e) {\n";
        $code .= "            throw new RuntimeException('Erreur lors de la mise à jour : ' . \$e->getMessage());\n";
        $code .= "        }\n";
        $code .= "    }\n\n";

        // delete()
        $code .= "    public function delete(\$id) {\n";
        $code .= "        try {\n";
        $code .= "            \$stmt = \$this->pdo->prepare('DELETE FROM $tableName WHERE id = :id');\n";
        $code .= "            return \$stmt->execute(['id' => \$id]);\n";
        $code .= "        } catch (PDOException \$e) {\n";
        $code .= "            throw new RuntimeException('Erreur lors de la suppression : ' . \$e->getMessage());\n";
        $code .= "        }\n";
        $code .= "    }\n";

        $code .= "}\n";

        if (!file_put_contents($filePath, $code)) {
            throw new RuntimeException("Impossible d'écrire le fichier : $filePath");
        }

        echo "DAO `$className` généré \n";

    }

    public function generateAll($schema) {
        if (!isset($schema['tables']) || !is_array($schema['tables'])) {
            throw new InvalidArgumentException("Schema invalide : la clé 'tables' est manquante ou invalide.");
        }

        foreach ($schema['tables'] as $table => $columns) {
            $this->generateDAO($table, $columns);
        }
    }


}