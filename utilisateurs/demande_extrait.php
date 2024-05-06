<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

function formulaire($annee, $numRegistre, $commune, $msg, $success_msg)
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
            <li><a href="./historique_demandes.php">Historique</a></li>
            <li><a class="active" href="./demande_extrait.php">Demande</a></li>
        </ul>
        <div class="entete">
            <p>
                Bienvenue cher visiteur. Cette application web (Sama Extrait) est spécialement
                conçue pour vous permettre de visualiser et même imprimer votre extrait
                de naissance. Ainsi, vous n'avez plus besoin de vous déplacer jusqu'à votre
                centre d'Etat Civil pour le récupérer. Par contre en ce qui concerne la déclaration
                d'un nouvel enfant, il faut se rendre nécessairement au centre d'Etat civil correspondant.
            </p>
        </div>
        <div id="bloc_page">
            <p></p>
            <form method="post">
                <div class="container">
                    <p style="color: red; text-align: center">
                        <?php echo $msg; ?>
                    </p>
                    <p style="color: green; text-align: center">
                        <?php echo $success_msg; ?>
                    </p>

                    <label for="Annee"><b>Année déclaration</b></label>
                    <input type="number" placeholder="Entrer l'année de déclaration" name="Annee" min="1920" max="2025"
                        value="<?php echo $annee; ?>" required>

                    <label for="NumRegistre"><b>Numéro dans le registre</b></label>
                    <input type="number" placeholder="Entrer le numéro dans le registre" name="NumRegistre" min="1"
                        value="<?php echo $numRegistre; ?>" required>

                    <label for="Commune"><b>Commune</b></label>
                    <input type="text" placeholder="Entrer le nom de la commune" name="Commune"
                        value="<?php echo $commune; ?>" required>

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

function traite($annee, $numRegistre, $commune)
{
    global $conn;
    $idCitoyen = $_SESSION['id'];

    try {
        $queryDemandeEnCours = "SELECT id FROM demande WHERE idCitoyen = ? AND status = 'EN COURS'";
        $stmtDemandeEnCours = mysqli_prepare($conn, $queryDemandeEnCours);
        mysqli_stmt_bind_param($stmtDemandeEnCours, "i", $idCitoyen);
        mysqli_stmt_execute($stmtDemandeEnCours);
        $resultDemandeEnCours = mysqli_stmt_get_result($stmtDemandeEnCours);

        if (mysqli_num_rows($resultDemandeEnCours) > 0) {
            $success_msg = "Vous avez déjà une demande en cours.";
            formulaire($annee, $numRegistre, $commune, "", $success_msg);
        } else {
            $query = "SELECT e.id AS idExtrait, ec.id AS idCentreEc
                      FROM extrait e
                      INNER JOIN centreec ec ON e.idCentreEc = ec.id
                      WHERE e.numDansleregistre = ? AND e.anneeRegistre = ? AND ec.nomCommune = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "iis", $numRegistre, $annee, $commune);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $idExtrait = $row['idExtrait'];
                // $idCentreEc = $row['idCentreEc'];

                $status = 'EN COURS';
                $date = date('Y-m-d');
                $heure = date('H:i:s');

                $queryInsert = "INSERT INTO demande (idExtrait, idCitoyen, date, heure, status) 
                                VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $queryInsert);
                mysqli_stmt_bind_param($stmtInsert, "iisss", $idExtrait, $idCitoyen, $date, $heure, $status);
                if (mysqli_stmt_execute($stmtInsert)) {
                    $success_msg = "Votre demande a été enregistrée avec succès !";
                    formulaire($annee, $numRegistre, $commune, "", $success_msg);
                } else {
                    $msg = "Une erreur s'est produite lors de l'insertion de la demande.";
                    formulaire($annee, $numRegistre, $commune, $msg, "");
                }
            } else {
                $msg = "L'extrait correspondant n'a pas été trouvé dans la base de données.";
                formulaire($annee, $numRegistre, $commune, $msg, "");
            }
        }
    } catch (Exception $e) {
        // $msg = $e->getMessage();
        $msg = "Une erreur est survenue lors du traitement de la demande.";
        formulaire($annee, $numRegistre, $commune, $msg, "");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['Annee']) && !empty($_POST['NumRegistre']) && !empty($_POST['Commune'])) {
        traite($_POST['Annee'], $_POST['NumRegistre'], $_POST['Commune']);
    } else {
        formulaire($_POST['Annee'], $_POST['NumRegistre'], $_POST['Commune'], "Tous les champs sont requis");
    }
} else {
    formulaire("", "", "", "", "");
}
?>