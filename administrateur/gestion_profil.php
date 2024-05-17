<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

function formulaire($ancienMotDePasse, $nouveauMotDePasse, $msg, $success_msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modifier Mot de Passe</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">Se Deconnecter</a></li>
            <li><a class="active" href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a href="./gestion_agents.php">Ajouter Agent</a></li>
        </ul>
        <div style="min-height: 50px;"></div>
        <form method="post" style="margin: auto;">
            <h3>Modifier votre mot de passe</h3>
            <p style="color: red; text-align: center">
                <?php echo $msg; ?>
            </p>
            <p style="color: green; text-align: center">
                <?php echo $success_msg; ?>
            </p>

            <div class="container">
                <label for="ancienMotDePasse"><b>Ancien Mot de passe</b></label>
                <input type="password" placeholder="Entrer votre ancien mot de passe" name="ancienMotDePasse"
                    value="<?php echo $ancienMotDePasse ?>" id="showpsw" required>

                <label for="nouveauMotDePasse"><b>Nouveau Mot de passe</b></label>
                <input type="password" placeholder="Entrer votre nouveau mot de passe" name="nouveauMotDePasse"
                    value="<?php echo $nouveauMotDePasse ?>" id="showpsw2" required>
                <label>
                    <input type="checkbox" name="showPsw" onclick="toggle()"> <strong>Show Passwords</strong>
                </label>

                <button type="submit">Modifier</button>
            </div>
        </form>
        <div style="min-height: 50px;"></div>
        <?php
        include_once ('includes/footer.php'); ?>
        <script src="js/script.js"></script>
    </body>

    </html>
    <?php
}

function traiteModification($ancienMotDePasse, $nouveauMotDePasse)
{
    global $conn;
    $email = $_SESSION["login"];

    try {
        $sql = mysqli_query($conn, "SELECT * FROM utilisateur WHERE email='$email'");
        if (mysqli_num_rows($sql) > 0) {
            $row = mysqli_fetch_assoc($sql);
            if (password_verify($ancienMotDePasse, $row['motDePasse'])) {
                $nouveauMotDePasse_hash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);
                $query = "UPDATE utilisateur SET motDePasse=? WHERE email=?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ss", $nouveauMotDePasse_hash, $email);
                if (mysqli_stmt_execute($stmt)) {
                    $success_msg = "Votre mot de passe a été mis à jour avec succès.";
                    formulaire($ancienMotDePasse, $nouveauMotDePasse, "", $success_msg);
                } else {
                    $msg = "Une erreur s'est produite lors de la mise à jour du mot de passe. Veuillez réessayer plus tard.";
                    formulaire($ancienMotDePasse, $nouveauMotDePasse, $msg, "");
                }
            } else {
                $msg = "L'ancien mot de passe est incorrect.";
                formulaire($ancienMotDePasse, $nouveauMotDePasse, $msg, "");
            }
        } else {
            $msg = "L'email n'existe pas.";
            formulaire($ancienMotDePasse, $nouveauMotDePasse, $msg, "");
        }
    } catch (Exception $e) {
        $msg = $e->getMessage();
        formulaire($ancienMotDePasse, $nouveauMotDePasse, $msg, "");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des entrées utilisateur pour éviter les failles XSS
    $ancienMotDePasse = strip_tags($_POST['ancienMotDePasse']);
    $nouveauMotDePasse = strip_tags($_POST['nouveauMotDePasse']);

    // Vérification des champs obligatoires
    if (!empty($ancienMotDePasse) && !empty($nouveauMotDePasse)) {
        traiteModification($ancienMotDePasse, $nouveauMotDePasse);
    } else {
        formulaire($ancienMotDePasse, $nouveauMotDePasse, "Tous les champs sont requis", "");
    }
} else {
    formulaire("", "", "", "");
}
?>