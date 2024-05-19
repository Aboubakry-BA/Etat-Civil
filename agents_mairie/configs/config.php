<?php

// Fonction pour charger les variables d'environnement depuis un fichier .env
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception("Le fichier .env n'existe pas");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
        }
    }
}

// Charger le fichier .env
loadEnv(__DIR__ . '/.env');

// Récupérer les variables d'environnement
$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Se connecter à la base de données
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}
