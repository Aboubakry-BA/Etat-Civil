<?php
include_once ('configs/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once ("includes/phpmailer/PHPMailer.php");
require_once ("includes/phpmailer/Exception.php");
require_once ("includes/phpmailer/SMTP.php");

function formulaire($email, $motDePasse, $msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a class="active" href="./connexion.php">Connexion</a></li>
            <!-- <li><a href="./inscription.php">Inscription</a></li> -->
        </ul>
        <div style="min-height: 50px;">

        </div>
        <form method="post" style="margin: auto;">
            <h3>Connexion</h3>
            <?php if (!empty($msg)) { ?>
                <p style="color: red; text-align: center">
                    <?php echo $msg; ?>
                </p>
            <?php } elseif (isset($_GET['success'])) { ?>
                <p style="color: green; text-align: center">
                    <?php echo $_GET['success']; ?>
                </p>
            <?php } ?>

            <div class="container">
                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Entrer votre email" name="email" value="<?php echo $email ?>" required>

                <label for="motDePasse"><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrer votre mot de passe" name="motDePasse"
                    value="<?php echo $motDePasse ?>" id="showpsw" required>

                <button type="submit">Se connecter</button>
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

function traite($email, $motDePasse)
{
    global $conn;

    try {
        $query = "SELECT * FROM utilisateur WHERE email=? AND actif=1 AND type='AGENTMAIRIE' LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($motDePasse, $user['motDePasse'])) {
                $otpCode = generateOTP();
                $mailSent = sendOTPByEmail($email, $otpCode);
                if ($mailSent) {
                    storeOTPInDatabase($email, $otpCode);
                    header("Location: validation_otp.php?email=" . urlencode($email));
                    exit;
                } else {
                    $msg = "Erreur lors de l'envoi du code OTP par e-mail.";
                    formulaire($email, $motDePasse, $msg);
                }
            } else {
                $msg = "Email ou mot de passe incorrect.";
                formulaire($email, $motDePasse, $msg);
            }
        } else {
            $msg = "Email ou mot de passe incorrect.";
            formulaire($email, $motDePasse, $msg);
        }
    } catch (Exception $e) {
        // Gérer l'erreur de la requête SQL ou de l'envoi d'e-mail
        $msg = "Une erreur est survenue lors du traitement de votre demande.";
        formulaire($email, $motDePasse, $msg);
    }
}

function generateOTP()
{
    return rand(100000, 999999);
}

function sendOTPByEmail($email, $otpCode)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->setLanguage('fr', 'includes/phpmailer/language');
        $mail->CharSet = "utf-8";

        // Sender and recipient
        $mail->setFrom('no-reply@etatcivil.sn', 'Etat Civil');
        $mail->addAddress($email);
        $mail->addReplyTo('reply@etatcivil.sn', 'Information');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Réception de votre code OTP';
        $mail->Body = "<p>Merci de vous être inscrit. Votre code OTP est : <strong>$otpCode</strong></p>";
        $mail->AltBody = "Merci de vous être inscrit. Votre code OTP est : $otpCode";

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

function storeOTPInDatabase($email, $otpCode)
{
    global $conn;
    $query = "UPDATE utilisateur SET otpCode=? WHERE email=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $otpCode, $email);
    mysqli_stmt_execute($stmt);
}

if (!empty($_POST['email']) && !empty($_POST['motDePasse'])) {
    traite($_POST['email'], $_POST['motDePasse']);
} else {
    formulaire("", "", "");
}
?>