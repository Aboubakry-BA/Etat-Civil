<?php
session_start();
include_once ('configs/config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once ("includes/phpmailer/PHPMailer.php");
require_once ("includes/phpmailer/Exception.php");
require_once ("includes/phpmailer/SMTP.php");

function formulaire($email, $msg, $success_msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mot de Passe Oublié</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./connexion.php">Connexion</a></li>
        </ul>
        <div style="min-height: 50px;"></div>
        <form method="post" style="margin: auto;">
            <h3>Mot de Passe Oublié</h3>
            <p style="color: red; text-align: center">
                <?php echo $msg; ?>
            </p>
            <p style="color: green; text-align: center">
                <?php echo $success_msg; ?>
            </p>

            <div class="container">
                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Entrer votre email" name="email" value="<?php echo $email ?>" required>

                <button type="submit">Envoyer</button>
            </div>
        </form>
        <div style="min-height: 50px;"></div>
        <?php include_once ('includes/footer.php'); ?>
    </body>

    </html>
    <?php
}

function sendNewPasswordByEmail($email, $newPassword)
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
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body = "<p>Votre nouveau mot de passe est : <strong>$newPassword</strong></p>";
        $mail->AltBody = "Votre nouveau mot de passe est : $newPassword";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function traiteMotDePasseOublie($email)
{
    global $conn;

    try {
        $query = "SELECT * FROM utilisateur WHERE email=? AND actif=1 LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $newPassword = uniqid(rand(), true);
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateQuery = "UPDATE utilisateur SET motDePasse=? WHERE email=?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ss", $newPasswordHash, $email);
            if (mysqli_stmt_execute($updateStmt)) {
                if (sendNewPasswordByEmail($email, $newPassword)) {
                    $success_msg = "Un email avec votre nouveau mot de passe a été envoyé.";
                    formulaire($email, "", $success_msg);
                } else {
                    $msg = "Erreur lors de l'envoi de l'email.";
                    formulaire($email, $msg, "");
                }
            } else {
                $msg = "Une erreur s'est produite lors de la mise à jour du mot de passe.";
                formulaire($email, $msg, "");
            }
        } else {
            $msg = "Cet email n'est pas enregistré.";
            formulaire($email, $msg, "");
        }
    } catch (Exception $e) {
        $msg = "Une erreur est survenue : " . $e->getMessage();
        formulaire($email, $msg, "");
    }
}

if (!empty($_POST['email'])) {
    traiteMotDePasseOublie($_POST['email']);
} else {
    formulaire("", "", "");
}
?>