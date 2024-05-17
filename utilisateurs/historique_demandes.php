<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

function afficherHistorique($demandes)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Historique des demandes</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a class="active" href="./historique_demandes.php">Historique</a></li>
            <li><a href="./demande_extrait.php">Demande</a></li>
        </ul>
        <div style="overflow-x:auto;width:95%;margin:auto">
            <h1>Historique des demandes</h1>
            <?php if (empty($demandes)): ?>
                <p style="text-align:center">Aucune demande trouvée.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Numéro dans le registre</th>
                        <th>Année de déclaration</th>
                        <th>Commune</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($demandes as $demande): ?>
                        <tr>
                            <td><?php echo $demande['date']; ?></td>
                            <td><?php echo $demande['heure']; ?></td>
                            <td><?php echo $demande['numDansleregistre']; ?></td>
                            <td><?php echo $demande['anneeRegistre']; ?></td>
                            <td><?php echo $demande['nomCommune']; ?></td>
                            <td><?php echo $demande['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
            <div style="min-height: 50px;">

            </div>
            <?php
            include_once ('includes/footer.php'); ?>
        </div>
    </body>

    </html>
    <?php
}

function traiteHistorique($idCitoyen)
{
    global $conn;

    try {
        $query = "SELECT d.id, d.date, d.heure, d.status, e.numDansleregistre, e.anneeRegistre, ec.nomCommune
                  FROM demande d
                  INNER JOIN extrait e ON d.idExtrait = e.id
                  INNER JOIN centreec ec ON e.idCentreEc = ec.id
                  WHERE d.idCitoyen = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idCitoyen);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $demandes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $demandes[] = $row;
        }

        return $demandes;
    } catch (Exception $e) {
        return false;
    }
}

$idCitoyen = $_SESSION['id'];
$demandes = traiteHistorique($idCitoyen);
afficherHistorique($demandes);
?>