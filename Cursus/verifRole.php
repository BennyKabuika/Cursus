<?php
session_start();
include("config.php");
$dbh = new PDO($dbstr);

// $_SESSION['role'] contient le rôle de l'utilisateur
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    // si l'utilisateur est un administrateur
    include("gestion_cursus.php");
    include("gestion_cours.php");
}

// Affichage des cursus pour tous les utilisateurs
include("affichage_cursus.php");

// PS : Vous pouvez supprimer ce fichier si vous voulez tester les fonctionnalité. Car vous aviez dit qu'on ne va pas faire une session de login admin par exemple
