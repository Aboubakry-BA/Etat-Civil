<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "extrait";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("La connexion à la base de données a échoué : " . mysqli_connect_error());
}
