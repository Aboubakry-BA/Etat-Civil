<?php
include_once ('configs/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once ("includes/phpmailer/PHPMailer.php");
require_once ("includes/phpmailer/Exception.php");
require_once ("includes/phpmailer/SMTP.php");

function formulaire($prenom, $nom, $email, $motDePasse, $telephone, $msg, $success_msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./connexion.php">Connexion</a></li>
            <li><a class="active" href="./inscription.php">Inscription</a></li>
        </ul>
        <div style="min-height: 50px;">

        </div>
        <form method="post" style="margin: auto;">
            <h3>Inscription</h3>
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

                <label for="motDePasse"><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer votre mot de passe" name="motDePasse"
                    value="<?php echo $motDePasse ?>" id="showpsw" required>

                <label for="telephone"><b>Téléphone</b></label>
                <input type="text" placeholder="Entrer votre téléphone" name="telephone" value="<?php echo $telephone ?>"
                    pattern="\d{9}" title="Le numéro de téléphone doit comporter exactement 9 chiffres" required>

                <button type="submit">S'inscrire</button>
                <label>
                    <input type="checkbox" name="showPsw" onclick="toggle()"> <strong>Show Password</strong>
                </label>
            </div>

            <div class="container" style="background-color:#f1f1f1">
                <button type="reset" class="cancelbtn">Annuler</button>
                <span class="psw">Mot de passe <a href="./mot_passe_oublie.php">oublié</a></span>
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

function traite($prenom, $nom, $email, $motDePasse, $telephone)
{
    global $conn;

    try {
        $sql = mysqli_query($conn, "SELECT * FROM utilisateur WHERE email='$email'");
        if (mysqli_num_rows($sql) == 0) {
            $type = 'CITOYEN';
            $date = date('Y-m-d');
            $heure = date('H:i:s');
            $actif = 0;
            $token = password_hash(uniqid(rand(), true), PASSWORD_DEFAULT);
            $motDePasse_hash = password_hash($motDePasse, PASSWORD_DEFAULT);

            $mailSent = sendValidationEmail($email, $token);

            if ($mailSent) {
                $query = "INSERT INTO utilisateur (prenom, nom, email, motDePasse, telephone, type, date, heure, actif, token) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ssssssssss", $prenom, $nom, $email, $motDePasse_hash, $telephone, $type, $date, $heure, $actif, $token);
                if (mysqli_stmt_execute($stmt)) {
                    $success_msg = "Un email de validation a été envoyé à votre adresse. Veuillez vérifier votre boîte de réception.";
                    formulaire($prenom, $nom, $email, $motDePasse, $telephone, "", $success_msg);
                } else {
                    throw new Exception("Une erreur s'est produite lors de l'inscription. Veuillez réessayer plus tard.");
                }
            } else {
                throw new Exception("Une erreur s'est produite lors de l'envoi de l'e-mail de validation.");
            }
        } else {
            throw new Exception("L'email est déjà utilisé.");
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        formulaire($prenom, $nom, $email, $motDePasse, $telephone, $msg, "");
    }
}

function sendValidationEmail($email, $token)
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
        $mail->Subject = 'Validation de votre adresse e-mail';
        $mail->Body = "<p>Merci de vous être inscrit. Veuillez cliquer sur le lien suivant pour valider votre adresse e-mail :</p>";
        $mail->Body .= "<p><a href='http://localhost/etat_civil/utilisateurs/validation.php?email=" . urlencode($email) . "&token=" . $token . "'>Cliquez ici pour valider votre adresse e-mail</a></p>";
        $mail->AltBody = "Merci de vous être inscrit. Veuillez cliquer sur le lien suivant pour valider votre adresse e-mail: http://localhost/projets/Projet_extrait/utilisateurs/validation.php?email=" . urlencode($email) . "&token=" . $token;

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
    $motDePasse = strip_tags($_POST['motDePasse']);
    $telephone = strip_tags($_POST['telephone']);

    // Vérification des champs obligatoires
    if (!empty($prenom) && !empty($nom) && !empty($email) && !empty($motDePasse) && !empty($telephone)) {
        traite($prenom, $nom, $email, $motDePasse, $telephone);
    } else {
        formulaire($prenom, $nom, $email, $motDePasse, $telephone, "Tous les champs sont requis", "");
    }
} else {
    formulaire("", "", "", "", "", "", "");
}
?>