<?php
include_once ('configs/config.php');

function formulaire($email, $msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Validation OTP</title>
    </head>

    <body>
        <nav class="navbar">
            <a class="active" href="#">Validation OTP</a>
        </nav>
        <form method="post" style="margin: auto;">
            <h3>Validation du code OTP</h3>
            <p style="color: green; text-align: center">
                Veuillez renseignez votre code OTP reçu par mail !
            </p>
            <p style="color: red; text-align: center">
                <?php echo $msg; ?>
            </p>

            <div class="container">
                <label for="otp"><b>Code OTP</b></label>
                <input type="text" placeholder="Entrer le code OTP" name="otp" required>

                <input type="hidden" name="email" value="<?php echo $email; ?>">

                <button type="submit">Valider</button>
            </div>
        </form>
        <?php
        include_once ('includes/footer.php'); ?>
        <script src="js/script.js"></script>
    </body>

    </html>
    <?php
}

function traite($email, $otp)
{
    global $conn;

    try {
        $query = "SELECT * FROM utilisateur WHERE email=? AND otpCode=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            session_start();

            $_SESSION['id'] = $user_data['id'];
            $_SESSION['login'] = $user_data['email'];

            header("Location: demande_extrait.php");
            exit;
        } else {
            $msg = "Code OTP invalide.";
            formulaire($email, $msg);
        }
    } catch (Exception $e) {
        // Gérer l'erreur de la requête SQL
        $msg = "Une erreur est survenue lors de la validation du code OTP.";
        formulaire($email, $msg);
    }
}

if (!empty($_POST['otp']) && !empty($_POST['email'])) {
    traite($_POST['email'], $_POST['otp']);
} else {
    if (!empty($_GET['email'])) {
        formulaire($_GET['email'], "");
    } else {
        header("Location: connexion.php");
        exit;
    }
}
?>