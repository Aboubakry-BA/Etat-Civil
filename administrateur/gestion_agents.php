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

function formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, $msg, $success_msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajout Agent</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">Se Deconnecter</a></li>
            <li><a href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a class="active" href="./gestion_agents.php">Ajouter Agent</a></li>
        </ul>
        <div style="min-height: 50px;">

        </div>
        <form method="post" style="margin: auto;">
            <h3>Ajout d'un nouvel agent</h3>
            <p style="color: red; text-align: center">
                <?php echo $msg; ?>
            </p>
            <p style="color: green; text-align: center">
                <?php echo $success_msg; ?>
            </p>

            <div class="container">
                <label for="prenom"><b>Prénom</b></label>
                <input type="text" placeholder="Entrer votre prénom" name="prenom" value="<?php echo $prenom ?>" required>

                <label for="nom"><b>Nom</b></label>
                <input type="text" placeholder="Entrer votre nom" name="nom" value="<?php echo $nom ?>" required>

                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Entrer votre email" name="email" value="<?php echo $email ?>" required>

                <label for="type"><b>Type</b></label>
                <select name="type" value="<?php echo $type; ?>" required>
                    <option value="ADMIN">Administrateur</option>
                    <option value="AGENTMAIRIE">Agent Mairie</option>
                    <option value="AGENTRETRAIT">Agent Centre Retrait</option>
                </select>

                <label for="telephone"><b>Téléphone</b></label>
                <input type="text" placeholder="Entrer votre téléphone" name="telephone" value="<?php echo $telephone ?>"
                    pattern="\d{9}" title="Le numéro de téléphone doit comporter exactement 9 chiffres" required>

                <label for="numCNI"><b>Numéro CNI</b></label>
                <input type="text" placeholder="Entrer votre numéro CNI" name="numCNI" value="<?php echo $numCNI ?>"
                    pattern="\d{13}" title="Le numéro de CNI doit comporter exactement 13 chiffres" required>

                <button type="submit">S'inscrire</button>
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
        <div style="min-height: 50px;">

        </div>
        <?php
        include_once ('includes/footer.php'); ?>
        <script src="js/script.js"></script>
    </body>

    </html>
    <?php
}

function traite($prenom, $nom, $email, $type, $telephone, $numCNI)
{
    global $conn;
    $idAdmin = $_SESSION["id"];
    $motDePasse = uniqid(rand(), true);

    try {
        $sql = mysqli_query($conn, "SELECT * FROM utilisateur WHERE email='$email'");
        if (mysqli_num_rows($sql) == 0) {
            $date = date('Y-m-d');
            $heure = date('H:i:s');
            $actif = 1;
            $token = md5(uniqid(rand(), true));
            $motDePasse_hash = password_hash($motDePasse, PASSWORD_DEFAULT);

            $mailSent = sendValidationEmail($email, $motDePasse);

            if ($mailSent) {
                $query = "INSERT INTO utilisateur (prenom, nom, email, motDePasse, telephone, numCNI, type, date, heure, actif, token, idAdmin) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "sssssssssssi", $prenom, $nom, $email, $motDePasse_hash, $telephone, $numCNI, $type, $date, $heure, $actif, $token, $idAdmin);
                if (mysqli_stmt_execute($stmt)) {
                    $success_msg = "Agent ajouté avec succés et un mail lui a été envoyé.";
                    formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, "", $success_msg);
                } else {
                    $msg = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer plus tard.";
                    formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, $msg, "");
                }
            } else {
                $msg = "Une erreur s'est produite lors de l'envoi de l'e-mail de validation.";
                formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, $msg, "");
            }
        } else {
            $msg = "L'email est déjà utilisé.";
            formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, $msg, "");
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, $msg, "");
    }
}

function sendValidationEmail($email, $motDePasse)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'localhost';
        // $mail->Host = 'smtp.office365.com';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'baprojects123@outlook.com';
        // $mail->Password = 'azertyuiopqsdfghjklmwxcvbn';
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 1025;
        // $mail->Port = 587;
        $mail->setLanguage('fr', 'includes/phpmailer/language');

        //Charset
        $mail->CharSet = "utf-8";

        //Recipients
        $mail->setFrom('no-reply@etatcivil.sn', 'Etat Civil');
        $mail->addAddress($email);
        $mail->addReplyTo('reply@etatcivil.sn', 'Information');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Vos identifiants de connexion';
        $mail->Body = "<p>Login : <strong>$email</strong></p><p>Mot de passe : <strong>$motDePasse</strong></p>";

        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des entrées utilisateur pour éviter les failles XSS
    $prenom = strip_tags($_POST['prenom']);
    $nom = strip_tags($_POST['nom']);
    $email = strip_tags($_POST['email']);
    $type = strip_tags($_POST['type']);
    $telephone = strip_tags($_POST['telephone']);
    $numCNI = strip_tags($_POST['numCNI']);

    // Vérification des champs obligatoires
    if (!empty($prenom) && !empty($nom) && !empty($email) && !empty($type) && !empty($telephone) && !empty($numCNI)) {
        traite($prenom, $nom, $email, $type, $telephone, $numCNI);
    } else {
        formulaire($prenom, $nom, $email, $type, $telephone, $numCNI, "Tous les champs sont requis", "");
    }
} else {
    formulaire('', '', '', '', '', '', '', '');
}
?>