<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

function traiteHistoriqueRetraits($idRetireur)
{
    global $conn;

    try {
        $query = "SELECT d.id, d.dateRetrait, d.heureRetrait, d.status, e.numDansleregistre, e.anneeRegistre, ec.nomCommune, u.prenom, u.nom, u.email
                  FROM demande d
                  INNER JOIN extrait e ON d.idExtrait = e.id
                  INNER JOIN centreec ec ON e.idCentreEc = ec.id
                  INNER JOIN utilisateur u ON d.idCitoyen = u.id
                  WHERE d.idRetireur = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $idRetireur);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $historique = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $historique[] = $row;
        }

        return $historique;
    } catch (Exception $e) {
        return false;
    }
}

function afficherHistoriqueRetraits($historique)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Historique des retraits</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a class="active" href="./historique_retraits.php">Historique</a></li>
            <li><a href="./retrait_extrait.php">Retrait</a></li>
        </ul>
        <div style="overflow-x:auto;width:95%;margin:auto">
            <h1>Historique des retraits</h1>
            <?php if (empty($historique)): ?>
                <p style="text-align:center">Aucun retrait trouvé.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Date Retrait</th>
                        <th>Heure Retrait</th>
                        <th>Auteur</th>
                        <th>Email</th>
                        <th>Numéro dans le registre</th>
                        <th>Année de déclaration</th>
                        <th>Commune</th>
                    </tr>
                    <?php foreach ($historique as $demande): ?>
                        <tr>
                            <td><?php echo $demande['dateRetrait']; ?></td>
                            <td><?php echo $demande['heureRetrait']; ?></td>
                            <td><?php echo $demande['prenom'] . ' ' . $demande['nom']; ?></td>
                            <td><?php echo $demande['email']; ?></td>
                            <td><?php echo $demande['numDansleregistre']; ?></td>
                            <td><?php echo $demande['anneeRegistre']; ?></td>
                            <td><?php echo $demande['nomCommune']; ?></td>
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

$idRetireur = $_SESSION['id'];
$historique = traiteHistoriqueRetraits($idRetireur);
afficherHistoriqueRetraits($historique);
?>