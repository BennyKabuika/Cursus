<?php
session_start();
include("config.php");
$dbh = new PDO($dbstr);

// Récupérer la liste des cursus pour afficher
$query_cursus = "SELECT * FROM cr_cursus";
$stm_cursus = $dbh->prepare($query_cursus);
$stm_cursus->execute();

// Gestion de l'ordre de classement
if (isset($_POST['order'])) {
    $_SESSION['order'] = $_POST['order'];
}

// Récupérer l'ordre de classement
$order = isset($_SESSION['order']) ? $_SESSION['order'] : 'nomcursus';
$query = "SELECT * FROM cr_cursus ORDER BY " . $order . " ASC";
$stm = $dbh->prepare($query);
$stm->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Affichage des cursus</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1 class="title">Liste des cursus</h1>
<form method="post" class="sort-form">
    <label for="order">Trier par :</label>
    <select name="order" id="order" class="sort-select">
        <option value="nomcursus" <?php echo ($order == 'nomcursus' ? 'selected' : ''); ?>>Nom du cursus</option>
        <option value="periodes" <?php echo ($order == 'periodes' ? 'selected' : ''); ?>>Nombre de périodes</option>
    </select>
    <input type="submit" value="Trier" class="sort-button">
</form>

<form method="post" class="cursus-form">
    <?php
    while ($res = $stm->fetch()) {
        echo "<div class='cursus-item'>";
        echo "<label for='cursus_" . $res['cid'] . "' class='cursus-label'>" . $res['nomcursus'] . " - Périodes (en annee) : " . $res['periodes'] . "</label>";
        echo "<input type='checkbox' id='cursus_" . $res['cid'] . "' name='cursus[]' value='" . $res['cid'] . "' class='cursus-checkbox'>";
        echo "</div>";
    }
    ?>
    <input type="submit" value="Afficher les cours" class="show-courses-button">
</form>

<?php
if (isset($_POST['cursus'])) {
    $selected_cursus = $_POST['cursus'];

    for ($i = 0; $i < count($selected_cursus); $i++) {
        $cid = $selected_cursus[$i];

        $query_cours = "SELECT * FROM cr_cours WHERE cid = :cid ORDER BY periode, nomcours";
        $stm_cours = $dbh->prepare($query_cours);
        $stm_cours->bindParam(':cid', $cid);
        $stm_cours->execute();

        echo "<div class='selected-cursus'>";
        echo "<h2 class='cursus-title'>Cours du cursus sélectionné :</h2>";
        echo "<ul class='cursus-list'>";
        while ($res_cours = $stm_cours->fetch()) {
            echo "<li class='cursus-course'>Période " . $res_cours['periode'] . " - " . $res_cours['nomcours'] . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}
?>
<a href="index.php" class="back-link">Revenir à la page prédécente</a>
</body>
</html>