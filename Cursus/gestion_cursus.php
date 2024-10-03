<?php
session_start();
include("config.php");
$dbh=new PDO($dbstr);

// Récupérer la liste des cursus pour les formulaires
$query_cursus = "SELECT * FROM cr_cursus";
$stm_cursus = $dbh->prepare($query_cursus);
$stm_cursus->execute();

// Ajouter un cursus
if (isset($_POST['ajouter_cursus'])) {
    $nomcursus = $_POST['nomcursus'];
    $periodes = $_POST['periodes'];

    $query_ajouter = "INSERT INTO cr_cursus (nomcursus, periodes) VALUES (:nomcursus, :periodes)";
    $stm_ajouter = $dbh->prepare($query_ajouter);
    $stm_ajouter->bindParam(':nomcursus', $nomcursus);
    $stm_ajouter->bindParam(':periodes', $periodes);
    $stm_ajouter->execute();
}

// Retirer un cursus
if (isset($_POST['retirer_cursus'])) {
    $cid_retirer = $_POST['cursus_retirer'];

    $query_retirer = "DELETE FROM cr_cursus WHERE cid = :cid";
    $stm_retirer = $dbh->prepare($query_retirer);
    $stm_retirer->bindParam(':cid', $cid_retirer);
    $stm_retirer->execute();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion des cursus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Gestion des cursus</h1>

<h2>Ajouter un cursus</h2>
<form method="post">
    <label for="nomcursus">Nom du cursus :</label>
    <input type="text" id="nomcursus" name="nomcursus" required>
    <label for="periodes">Nombre de périodes :</label>
    <input type="number" id="periodes" name="periodes" placeholder="en année" required>
    <input type="submit" name="ajouter_cursus" value="Ajouter">
</form>

<h2>Retirer un cursus</h2>
<form method="post">
    <label for="cursus_retirer">Sélectionner un cursus à retirer :</label>
    <select name="cursus_retirer" required>
        <?php
        while ($res_cursus = $stm_cursus->fetch()) {
            echo "<option value='" . $res_cursus['cid'] . "'>" . $res_cursus['nomcursus'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="retirer_cursus" value="Retirer">
</form>

<h2>Demander l'édition des cours d'un cursus</h2>
<form method="post">
    <label for="cursus_editer">Sélectionner un cursus à éditer :</label>
    <select name="cursus_editer" required>
        <?php
        $stm_cursus->execute();
        while ($res_cursus = $stm_cursus->fetch()) {
            echo "<option value='" . $res_cursus['cid'] . "'>" . $res_cursus['nomcursus'] . "</option>";
        }
        ?>
    </select>
    <input type="submit" name="editer_cours" value="Éditer les cours">
</form>
<a href="index.php">Revenir à la page prédécente</a>
</body>
</html>