<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

function formulaire($email, $token, $msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Demande d'extrait</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a href="./historique_retraits.php">Historique</a></li>
            <li><a class="active" href="./retrait_extrait.php">Retrait</a></li>
        </ul>
        <div class="entete">
            <p>
                Cette page a pour but de faciliter la délivrance des extraits de naissance aux personnes venant les
                récupérer. Elle est conçue pour permettre la validation en demandant, notamment, la présentation d'une pièce
                d'identité. Les informations affichées sont relatives à l'extrait de naissance. Son format est prêt pour
                l'impression, même si l'expression peut être améliorée, l'idée est là.
            </p>
        </div>
        <div id="bloc_page">
            <p></p>
            <form method="post">
                <div class="container">
                    <p style="color: red; text-align: center">
                        <?php echo $msg; ?>
                    </p>

                    <label for="Email"><b>Email</b></label>
                    <input type="email" placeholder="Entrer l'email du retireur" name="Email" value="<?php echo $email; ?>"
                        required>

                    <label for="Token"><b>Token</b></label>
                    <input type="text" placeholder="Entrer le token du retireur" name="Token" value="<?php echo $token; ?>"
                        required>

                    <button type="submit">Valider</button>
                </div>

                <div class="container" style="background-color:#f1f1f1">
                    <button type="reset" class="cancelbtn">Annuler</button>
                    <span class="psw">
                        <a onclick="return confirm('Confirmer déconnexion')" href="deconnexion.php">Se
                            Déconnecter
                        </a>
                    </span>
                </div>
            </form>
        </div>
        <div style="min-height: 50px;">

        </div>
        <?php
        include_once ('includes/footer.php'); ?>
    </body>

    </html>
    <?php
}

function traite($email, $token)
{
    global $conn;
    $idAgentRetrait = $_SESSION['id'];

    try {
        // Récupérer l'ID de l'utilisateur
        $queryUserId = "SELECT id FROM utilisateur WHERE email = ?";
        $stmtUserId = mysqli_prepare($conn, $queryUserId);
        mysqli_stmt_bind_param($stmtUserId, "s", $email);
        mysqli_stmt_execute($stmtUserId);
        $resultUserId = mysqli_stmt_get_result($stmtUserId);

        if ($rowUserId = mysqli_fetch_assoc($resultUserId)) {
            $userId = $rowUserId['id'];

            // Vérifier si l'utilisateur et le token existent dans la table demande
            $queryCheckToken = "SELECT id, idExtrait FROM demande 
            WHERE idCitoyen = ? AND token = ? AND status = 'VALIDE'";
            $stmtCheckToken = mysqli_prepare($conn, $queryCheckToken);
            mysqli_stmt_bind_param($stmtCheckToken, "is", $userId, $token);
            mysqli_stmt_execute($stmtCheckToken);
            $resultCheckToken = mysqli_stmt_get_result($stmtCheckToken);

            if ($rowCheckToken = mysqli_fetch_assoc($resultCheckToken)) {
                $idDemande = $rowCheckToken['id'];
                $idExtrait = $rowCheckToken['idExtrait'];

                // Sélectionner les informations de l'extrait associé à la demande
                $querySelectExtrait = "SELECT e.*, p.prenom AS prenomPere, p.nom AS nomPere, m.prenom AS prenomMere, m.nom AS nomMere, c.nomCommune AS commune, c.departement, c.region, c.codeRegion, c.codeCNI
                                       FROM extrait e
                                       INNER JOIN centreec c ON e.idCentreEc = c.id
                                       INNER JOIN pere p ON e.idPere = p.id
                                       INNER JOIN mere m ON e.idMere = m.id
                                       WHERE e.id = ?";
                $stmtSelectExtrait = mysqli_prepare($conn, $querySelectExtrait);
                mysqli_stmt_bind_param($stmtSelectExtrait, "i", $idExtrait);
                mysqli_stmt_execute($stmtSelectExtrait);
                $resultSelectExtrait = mysqli_stmt_get_result($stmtSelectExtrait);

                if ($rowExtrait = mysqli_fetch_assoc($resultSelectExtrait)) {
                    $dateRetrait = date('Y-m-d');
                    $heureRetrait = date('H:i:s');
                    $queryUpdateDemande = "UPDATE demande SET idRetireur = ?, token = NULL, dateRetrait = ?, heureRetrait= ? WHERE id = ?";
                    $stmtUpdateDemande = mysqli_prepare($conn, $queryUpdateDemande);
                    mysqli_stmt_bind_param($stmtUpdateDemande, "issi", $idAgentRetrait, $dateRetrait, $heureRetrait, $idDemande);
                    $updateSuccess = mysqli_stmt_execute($stmtUpdateDemande);
                    if ($updateSuccess) {
                        $_SESSION['extrait_details'] = $rowExtrait;
                        header("location: visualisation_extrait.php");
                        exit;
                    } else {
                        $msg = "Erreur lors de la mise à jour de la demande.";
                        formulaire($email, $token, $msg, "");
                    }
                } else {
                    $msg = "L'extrait associé à la demande n'a pas été trouvé.";
                    formulaire($email, $token, $msg, "");
                }
            } else {
                $msg = "Email et/ou token invalide(s) !";
                unset($_SESSION['extrait_details']);
                formulaire($email, $token, $msg, "");
            }
        } else {
            $msg = "Email et/ou token invalide(s) !";
            unset($_SESSION['extrait_details']);
            formulaire($email, $token, $msg, "");
        }
    } catch (Exception $e) {
        $msg = "Une erreur est survenue lors du traitement de la demande : " . $e->getMessage();
        formulaire($email, $token, $msg, "");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Email']) && !empty($_POST['Token'])) {
        traite($_POST['Email'], $_POST['Token']);
    } else {
        formulaire($_POST['Email'], $_POST['Token'], "Tous les champs sont requis", "");
    }
} else {
    formulaire("", "", "");
}
?>