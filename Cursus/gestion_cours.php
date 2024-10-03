<?php
session_start();
include("config.php");
$dbh=new PDO($dbstr);

// Récupérer la liste des cursus
$query_cursus = "SELECT cid, nomcursus FROM cr_cursus";
$stm_cursus = $dbh->prepare($query_cursus);
$stm_cursus->execute();

// Récuperer la listes des cours
$query_cours = "SELECT id, nomcours FROM cr_cours";
$stm_cours = $dbh->prepare($query_cours);
$stm_cours->execute();

// Ajouter un nouveau cours pour un cursus choisi
if (isset($_POST['ajouter_cours'])) {
    $cid = $_POST['cid'];
    $nomcours = $_POST['nomcours'];
    $heures = $_POST['heures'];
    $periode = $_POST['periode'];

    $query = "INSERT INTO cr_cours (cid, nomcours, heures, periode) VALUES (:cid, :nomcours, :heures, :periode)";
    $stm = $dbh->prepare($query);
    $stm->bindParam(':cid', $cid);
    $stm->bindParam(':nomcours', $nomcours);
    $stm->bindParam(':heures', $heures);
    $stm->bindParam(':periode', $periode);
    $stm->execute();
}

// Modifier un cours d'un cursus choisi
if (isset($_POST['modifier_cours'])) {
    $id = $_POST['id'];
    $nomcours = $_POST['nomcours'];
    $heures = $_POST['heures'];
    $periode = $_POST['periode'];

    $query = "UPDATE cr_cours SET nomcours = :nomcours, heures = :heures, periode = :periode WHERE id = :id";
    $stm = $dbh->prepare($query);
    $stm->bindParam(':id', $id);
    $stm->bindParam(':nomcours', $nomcours);
    $stm->bindParam(':heures', $heures);
    $stm->bindParam(':periode', $periode);
    $stm->execute();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion des cours</title>
</head>
<body>
<h1>Gestion des cours</h1>
<h2>Ajouter un nouveau cours pour un cursus choisi</h2>
<form method="post">
    <label for="cid">Sélectionner un cursus :</label>
    <select name="cid" required>
        <?php
        while ($res_cursus = $stm_cursus->fetch()) {
            echo "<option value='" . $res_cursus['cid'] . "'>" . $res_cursus['nomcursus'] . "</option>";
        }
        ?>
    </select>
    <label for="nomcours">Nom du cours :</label>
    <input type="text" name="nomcours" required>
    <label for="heures">Nombre d'heures :</label>
    <input type="number" name="heures" required>
    <label for="periode">Numéro de la période :</label>
    <input type="text" name="periode" required>
    <input type="submit" name="ajouter_cours" value="Ajouter">
</form>
<h2>Modifier un cours</h2>
<form method="post">
    <label for="id">Sélectionner un cours à modifier :</label>
    <select name="id" required>
        <?php

            while ($res_cours = $stm_cours->fetch()) {
                echo "<option value='" . $res_cours['id'] . "'>" . $res_cours['nomcours'] .  "</option>";
            }

        ?>
    </select>
    <label for="nomcours">Nouveau nom du cours :</label>
    <input type="text" name="nomcours" required>
    <label for="heures">Nombre d'heures :</label>
    <input type="number" name="heures" required>
    <label for="periode">Numéro de la période :</label>
    <input type="text" name="periode" required>
    <input type="submit" name="modifier_cours" value="Modifier">
</form>
<a href="index.php">Revenir à la page prédécente</a>
</body>
</html>
