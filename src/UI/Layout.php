<?php
// src/UI/Layout.php

class Layout {
    public static function header($title = "Librairie PHP") {
        echo <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>$title</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        header { background: #007bff; color: white; padding: 10px; }
        nav { background: #f1f1f1; padding: 10px; }
        nav a { margin-right: 10px; text-decoration: none; color: #333; }
        main { padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
<header><h1>$title</h1></header>
<nav>
    <a href="?page=operation_list">Operation</a>
    <a href="?page=script_list">Script</a>
</nav>
<main>
HTML;
    }

    public static function footer() {
        echo <<<HTML
</main>
<footer style="background:#f1f1f1; padding:10px; text-align:center;">
    &copy; 2025 Konecta Librairie
</footer>
</body>
</html>
HTML;
    }
}
