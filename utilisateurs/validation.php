<?php
include_once ('configs/config.php');

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    $sql = mysqli_query($conn, "SELECT * FROM utilisateur WHERE email='$email' AND token='$token'");
    if ($sql) {
        if (mysqli_num_rows($sql) > 0) {
            $user = mysqli_fetch_assoc($sql);
            if ($user['actif'] == 0) {
                $update_query = mysqli_query($conn, "UPDATE utilisateur SET actif=1 WHERE email='$email'");
                if ($update_query) {
                    $msg = "Votre adresse e-mail a été validée avec succès.";
                    header("Location: connexion.php?success=" . urlencode($msg));
                    exit;
                } else {
                    $msg = "Une erreur s'est produite lors de la mise à jour de votre compte. Veuillez réessayer plus tard.";
                }
            } else {
                $msg = "Votre compte est déjà actif.";
            }
        } else {
            $msg = "Lien de validation invalide.";
        }
    } else {
        $msg = "Une erreur s'est produite lors de la récupération de votre compte. Veuillez réessayer plus tard.";
    }
} else {
    $msg = "Paramètres manquants.";
}

echo $msg;
