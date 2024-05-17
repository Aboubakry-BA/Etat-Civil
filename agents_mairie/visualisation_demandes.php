<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once ("includes/phpmailer/PHPMailer.php");
require_once ("includes/phpmailer/Exception.php");
require_once ("includes/phpmailer/SMTP.php");

function afficherDemandesEnCours($demandes)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Demandes en cours</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a href="./declaration_naissance.php">Extrait</a></li>
            <li><a href="./historique_demandes.php">Historiques</a></li>
            <li><a class="active" href="./visualisation_demandes.php">Demandes</a></li>
        </ul>
        <div style="overflow-x:auto;width:95%;margin:auto">
            <h1>Visualisations des demandes</h1>
            <?php if (empty($demandes)): ?>
                <p style="text-align:center">Aucune demande en cours.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Auteur</th>
                        <th>Email</th>
                        <th>Numéro dans le registre</th>
                        <th>Année de déclaration</th>
                        <th>Commune</th>
                        <th style="text-align:center">Actions</th>
                    </tr>
                    <?php foreach ($demandes as $demande): ?>
                        <tr>
                            <td><?php echo $demande['date']; ?></td>
                            <td><?php echo $demande['heure']; ?></td>
                            <td><?php echo $demande['prenom'] . ' ' . $demande['nom']; ?></td>
                            <td><?php echo $demande['email']; ?></td>
                            <td><?php echo $demande['numDansleregistre']; ?></td>
                            <td><?php echo $demande['anneeRegistre']; ?></td>
                            <td><?php echo $demande['nomCommune']; ?></td>
                            <td>
                                <form method="post" style="display:flex">
                                    <input type="hidden" name="idDemande" value="<?php echo $demande['id']; ?>">
                                    <button type="submit" name="accepter" style="background:green"
                                        onclick="return confirm('Confirmer votre décision')">Accepter</button>
                                    &nbsp;<button type=" submit" name="refuser" style="background:red"
                                        onclick="return confirm('Confirmer votre décision')">Refuser</button>
                                </form>
                            </td>
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

function traiteDemandesEnCours()
{
    global $conn;

    try {
        $query = "SELECT d.id, d.date, d.heure, e.numDansleregistre, e.anneeRegistre, ec.nomCommune, u.prenom, u.nom, u.email
                  FROM demande d
                  INNER JOIN extrait e ON d.idExtrait = e.id
                  INNER JOIN centreec ec ON e.idCentreEc = ec.id
                  INNER JOIN utilisateur u ON d.idCitoyen = u.id
                  WHERE d.status = 'EN COURS'";
        $result = mysqli_query($conn, $query);

        $demandes = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $demandes[] = $row;
        }

        return $demandes;
    } catch (Exception $e) {
        return false;
    }
}

function mettreAJourStatutDemande($idDemande, $nouveauStatut)
{
    global $conn;

    try {
        $idAgentMairie = $_SESSION['id'];

        // Si la demande est validée
        if ($nouveauStatut === 'VALIDE') {
            $token = generateToken();

            // Mettre à jour la demande avec le nouvel état, l'id de l'agent de la mairie et le token
            $query = "UPDATE demande SET status = ?, idDelivreur = ?, token = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sisi", $nouveauStatut, $idAgentMairie, $token, $idDemande);
            mysqli_stmt_execute($stmt);

            // Récupérer l'email du citoyen pour l'envoi de l'e-mail de notification
            $queryCitoyen = "SELECT u.email FROM demande d INNER JOIN utilisateur u ON d.idCitoyen = u.id WHERE d.id = ?";
            $stmtCitoyen = mysqli_prepare($conn, $queryCitoyen);
            mysqli_stmt_bind_param($stmtCitoyen, "i", $idDemande);
            mysqli_stmt_execute($stmtCitoyen);
            $resultCitoyen = mysqli_stmt_get_result($stmtCitoyen);
            $rowCitoyen = mysqli_fetch_assoc($resultCitoyen);
            $emailCitoyen = $rowCitoyen['email'];

            // Envoyer un e-mail de notification au citoyen avec le token
            sendNotificationEmail($emailCitoyen, $token, true);
        } elseif ($nouveauStatut === 'REFUSE') {
            // Mettre à jour la demande avec le nouvel état et l'id de l'agent de la mairie
            $query = "UPDATE demande SET status = ?, idDelivreur = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sii", $nouveauStatut, $idAgentMairie, $idDemande);
            mysqli_stmt_execute($stmt);

            // Récupérer l'email du citoyen pour l'envoi de l'e-mail de notification
            $queryCitoyen = "SELECT u.email FROM demande d INNER JOIN utilisateur u ON d.idCitoyen = u.id WHERE d.id = ?";
            $stmtCitoyen = mysqli_prepare($conn, $queryCitoyen);
            mysqli_stmt_bind_param($stmtCitoyen, "i", $idDemande);
            mysqli_stmt_execute($stmtCitoyen);
            $resultCitoyen = mysqli_stmt_get_result($stmtCitoyen);
            $rowCitoyen = mysqli_fetch_assoc($resultCitoyen);
            $emailCitoyen = $rowCitoyen['email'];

            // Envoyer un e-mail de notification au citoyen
            sendNotificationEmail($emailCitoyen, null, false);
        }

        return true;
    } catch (Exception $e) {
        return false;
    }
}

function generateToken()
{
    return rand(100000, 999999);
}

function sendNotificationEmail($email, $token, $acceptee)
{
    $mail = new PHPMailer(true);
    try {
        // Paramètres du serveur SMTP
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->setLanguage('fr', 'includes/phpmailer/language');
        $mail->CharSet = "utf-8";

        // Expéditeur et destinataire
        $mail->setFrom('no-reply@etatcivil.sn', 'Etat Civil');
        $mail->addAddress($email);
        $mail->addReplyTo('reply@etatcivil.sn', 'Information');

        // Contenu de l'e-mail en fonction de l'acceptation ou du refus de la demande
        if ($acceptee) {
            // Contenu pour une demande acceptée
            $mail->isHTML(true);
            $mail->Subject = 'Votre demande a été acceptée';
            $mail->Body = "<p>Votre demande a été acceptée. Voici votre code de suivi : <strong>$token</strong></p>";
            $mail->AltBody = "Votre demande a été acceptée. Voici votre code de suivi : $token";
        } else {
            // Contenu pour une demande refusée
            $mail->isHTML(true);
            $mail->Subject = 'Votre demande a été refusée';
            $mail->Body = "<p>Votre demande a été refusée par l'agent de la mairie.</p>";
            $mail->AltBody = "Votre demande a été refusée par l'agent de la mairie.";
        }

        if (!$mail->Send()) {
            echo "Erreur d'envoi de l'e-mail: " . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        echo 'Impossible d\'envoyer le message. Erreur Mailer: ' . $mail->ErrorInfo;
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accepter'])) {
        $idDemande = $_POST['idDemande'];
        if (mettreAJourStatutDemande($idDemande, 'VALIDE')) {
            header("Refresh:0");
        }
    } elseif (isset($_POST['refuser'])) {
        $idDemande = $_POST['idDemande'];
        if (mettreAJourStatutDemande($idDemande, 'REFUSE')) {
            header("Refresh:0");
        }
    }
}

$demandes = traiteDemandesEnCours();
afficherDemandesEnCours($demandes);
?>