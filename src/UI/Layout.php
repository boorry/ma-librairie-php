<?php

class Layout {
    public static function header($title = "Librairie PHP") {
        echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>$title</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f9; }
        header { background: #007bff; color: white; padding: 15px; text-align: center; }
        nav { background: #e9ecef; padding: 10px; border-bottom: 1px solid #ddd; }
        nav a { margin: 0 15px; text-decoration: none; color: #333; font-weight: bold; }
        nav a:hover { color: #007bff; }
        main { padding: 20px; max-width: 1200px; margin: 0 auto; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: white; }
        td a { color: #dc3545; text-decoration: none; }
        td a:hover { text-decoration: underline; }
        form { max-width: 600px; margin: 20px 0; }
        form label { display: block; margin-bottom: 5px; font-weight: bold; }
        form input, form select, form textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; }
        form button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        form button:hover { background: #0056b3; }
        form a { margin-left: 10px; color: #333; text-decoration: none; }
        form a:hover { color: #007bff; }
        p.error { color: #dc3545; font-weight: bold; }
        p.success { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
<header><h1>$title</h1></header>
<nav>
    <a href="?page=home">Accueil</a>
    <a href="?page=operation_list">Opérations</a>
    <a href="?page=script_operation_list">Associations Script-Opération</a>
    <a href="?page=statut_list">Statuts</a>
</nav>
<main>
HTML;
    }

    public static function footer() {
        echo <<<HTML
</main>
<footer style="background: #e9ecef; padding: 15px; text-align: center; border-top: 1px solid #ddd;">
    &copy; <?php echo date('Y'); ?> Konecta
</footer>
</body>
</html>
HTML;
    }
}