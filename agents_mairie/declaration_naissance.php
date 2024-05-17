<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}

include_once ('configs/config.php');

function formulaire($region, $departement, $commune, $annee, $prenomEnfant, $nomEnfant, $prenomPere, $sexe, $prenomMere, $nomMere, $lieuNaissance, $paysNaissance, $numDansLeRegistre, $dateNaissance, $heureNaissance, $codeCNI, $codeRegion, $msg, $success_msg)
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ajouter un extrait</title>
    </head>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a href="./gestion_profil.php">Modifier Mot de Passe</a></li>
            <li><a class="active" href="./declaration_naissance.php">Extrait</a></li>
            <li><a href="./historique_demandes.php">Historiques</a></li>
            <li><a href="./visualisation_demandes.php">Demandes</a></li>
        </ul>
        <div style="overflow-x:auto;width:95%;margin:auto">
            <form method="post" id="regForm">

                <h1>Déclaration de naissance</h1>
                <p style="color: red; text-align: center">
                    <?php echo $msg; ?>
                </p>
                <p style="color: green; text-align: center">
                    <?php echo $success_msg; ?>
                </p>

                <!-- One "tab" for each step in the form: -->
                <div class="tab">Etape 1:
                    <p>
                        <select name="Region" id="Region" value="<?php echo $region; ?>">
                            <option value="DAKAR">DAKAR</option>
                            <option value="DIOURBEL">DIOURBEL</option>
                            <option value="THIES">THIES</option>
                            <option value="KOLACK">KAOLACK</option>
                            <option value="FATICK">FATICK</option>
                            <option value="ZIGUINCHOR">ZIGUINCHOR</option>
                            <option value="SAINTLOUIS">SAINT LOUIS</option>
                            <option value="MATAM">MATAM</option>
                            <option value="LOUGA">LOUGA</option>
                            <option value="KAFFRINE">KAFFRINE</option>
                            <option value="KEDOUGOU">KEDOUGOU</option>
                            <option value="TAMBACOUNDA">TAMBACOUNDA</option>
                            <option value="KOLDA">KOLDA</option>
                            <option value="SEDHIOU">SEDHIOU</option>
                        </select>
                    </p>
                    <p><input type="text" name="Departement" placeholder="Département..." oninput="this.className = ''"
                            required value="<?php echo $departement; ?>"> </p>
                    <p><input type="text" name="Commune" placeholder="Commune..." oninput="this.className = ''" required
                            value="<?php echo $commune; ?>"> </p>
                    <p><input type="number" name="Annee" placeholder="Année Déclaration..." min="1970" max="2021"
                            oninput="this.className = ''" required value="<?php echo $annee; ?>"> </p>
                </div>

                <div class="tab">Etape 2:
                    <p><input type="text" name="PrenomEnfant" placeholder="Prénom Enfant..." oninput="this.className = ''"
                            required value="<?php echo $prenomEnfant; ?>"> </p>
                    <p><input type="text" name="NomEnfant" placeholder="Nom Enfant..." oninput="this.className = ''"
                            required value="<?php echo $nomEnfant; ?>"> </p>
                    <p><input type="text" name="PrenomPere" placeholder="Prénom Père..." oninput="this.className = ''"
                            required value="<?php echo $prenomPere; ?>"> </p>
                    <p>
                        <select name="Sexe" id="sexe">
                            <option value="MASCULIN" <?php if ($sexe == "MASCULIN")
                                echo "selected"; ?>>MASCULIN</option>
                            <option value="FEMININ" <?php if ($sexe == "FEMININ")
                                echo "selected"; ?>>FEMININ</option>
                        </select>
                    </p>
                </div>

                <div class="tab">Etape 3:
                    <p><input type="text" name="PrenomMere" placeholder="Prénom Mère..." oninput="this.className = ''"
                            required value="<?php echo $prenomMere; ?>"> </p>
                    <p><input type="text" name="NomMere" placeholder="Nom Mère..." oninput="this.className = ''" required
                            value="<?php echo $nomMere; ?>"> </p>
                    <p><input type="text" name="LieuNaissance" placeholder="Lieu de naissance..."
                            oninput="this.className = ''" required value="<?php echo $lieuNaissance; ?>"> </p>
                    <p><input type="text" name="PaysNaissance" placeholder="Pays de naissance..."
                            oninput="this.className = ''" required value="<?php echo $paysNaissance; ?>"> </p>
                    <p><input type="number" name="NumDansLeRegistre" placeholder="Numéro dans le registre..." min="1"
                            max="2000" oninput="this.className = ''" required value="<?php echo $numDansLeRegistre; ?>">
                    </p>
                </div>

                <div class="tab">Last Etape:
                    <p>
                        <input type="date" name="DateNaissance" placeholder="Date de naissance..."
                            oninput="this.className = ''" min="1970-01-01" required value="<?php echo $dateNaissance; ?>">
                    </p>
                    <p><input type="time" name="HeureNaissance" placeholder="Heure de naissance..."
                            oninput="this.className = ''" required value="<?php echo $heureNaissance; ?>"> </p>
                    <p><input type="number" name="CodeCNI" placeholder="Code CNI..." min="1" max="999"
                            oninput="this.className = ''" required value="<?php echo $codeCNI; ?>"> </p>
                    <p><input type="number" name="CodeRegion" placeholder="Code Région..." min="1" max="14"
                            oninput="this.className = ''" required value="<?php echo $codeRegion; ?>"> </p>

                </div>

                <div style="overflow:auto;">
                    <div style="float:right; display:flex;">
                        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                        <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    </div>
                </div>

                <!-- Circles which indicates the steps of the form: -->
                <div style="text-align:center;margin-top:40px;">
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>
            </form>
            <div style="min-height: 50px;">

            </div>
            <?php
            include_once ('includes/footer.php'); ?>
            <script src="js/script.js"></script>
        </div>
    </body>

    </html>
    <?php
}

function traite($region, $departement, $commune, $annee, $prenomEnfant, $nomEnfant, $prenomPere, $sexe, $prenomMere, $nomMere, $lieuNaissance, $paysNaissance, $numDansLeRegistre, $dateNaissance, $heureNaissance, $codeCNI, $codeRegion)
{
    global $conn;

    try {
        $idAgent = $_SESSION['id'];

        // Vérifier si les données du centre existent dans la table centreec
        $query = "SELECT id FROM centreec WHERE nomCommune = ? AND codeCNI = ? AND departement = ? AND region = ? AND codeRegion = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $commune, $codeCNI, $departement, $region, $codeRegion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Le centre existe, récupérer son ID
            $row = $result->fetch_assoc();
            $idCentreEc = $row['id'];
        } else {
            // Le centre n'existe pas, l'ajouter et récupérer son ID
            $query = "INSERT INTO centreec (nomCommune, codeCNI, departement, region, codeRegion) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssss', $commune, $codeCNI, $departement, $region, $codeRegion);
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'ajout du centre.");
            }
            $idCentreEc = $conn->insert_id;
        }

        // Insérer le père dans la table pere
        $query = "INSERT INTO pere (prenom, nom) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $prenomPere, $nomEnfant);
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'ajout du père.");
        }
        $idPere = $conn->insert_id;

        // Insérer la mère dans la table mere
        $query = "INSERT INTO mere (prenom, nom) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $prenomMere, $nomMere);
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'ajout de la mère.");
        }
        $idMere = $conn->insert_id;

        // Insérer l'extrait dans la table extrait
        $dateDeLivrance = date('Y-m-d');
        $heureDeLivrance = date('H:i:s');
        $query = "INSERT INTO extrait (numDansLeRegistre, dateDeLivrance, paysNaissance, prenom, sexe, dateNaissance, lieuNaissance, heureNaissance, anneeRegistre, idPere, idMere, idCentreEc, idAgent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssssssiiii', $numDansLeRegistre, $dateDeLivrance, $paysNaissance, $prenomEnfant, $sexe, $dateNaissance, $lieuNaissance, $heureNaissance, $annee, $idPere, $idMere, $idCentreEc, $idAgent);
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'ajout de l'extrait.");
        }

        $success_msg = "L'extrait a été ajouté avec succès !";
        formulaire($region, $departement, $commune, $annee, $prenomEnfant, $nomEnfant, $prenomPere, $sexe, $prenomMere, $nomMere, $lieuNaissance, $paysNaissance, $numDansLeRegistre, $dateNaissance, $heureNaissance, $codeCNI, $codeRegion, "", $success_msg);
    } catch (Exception $e) {
        $msg = "Une erreur est survenue lors du traitement de la demande : " . $e->getMessage();
        formulaire($region, $departement, $commune, $annee, $prenomEnfant, $nomEnfant, $prenomPere, $sexe, $prenomMere, $nomMere, $lieuNaissance, $paysNaissance, $numDansLeRegistre, $dateNaissance, $heureNaissance, $codeCNI, $codeRegion, $msg, "");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des entrées utilisateur pour éviter les failles XSS
    $annee = strip_tags($_POST['Annee']);
    $region = strip_tags($_POST['Region']);
    $departement = strip_tags($_POST['Departement']);
    $numDansLeRegistre = strip_tags($_POST['NumDansLeRegistre']);
    $commune = strip_tags($_POST['Commune']);
    $prenomEnfant = strip_tags($_POST['PrenomEnfant']);
    $nomEnfant = strip_tags($_POST['NomEnfant']);
    $prenomPere = strip_tags($_POST['PrenomPere']);
    $sexe = strip_tags($_POST['Sexe']);
    $prenomMere = strip_tags($_POST['PrenomMere']);
    $nomMere = strip_tags($_POST['NomMere']);
    $lieuNaissance = strip_tags($_POST['LieuNaissance']);
    $paysNaissance = strip_tags($_POST['PaysNaissance']);
    $dateNaissance = strip_tags($_POST['DateNaissance']);
    $heureNaissance = strip_tags($_POST['HeureNaissance']);
    $codeCNI = strip_tags($_POST['CodeCNI']);
    $codeRegion = strip_tags($_POST['CodeRegion']);

    // Vérification des champs obligatoires
    if (!empty($annee) && !empty($region) && !empty($departement) && !empty($numDansLeRegistre) && !empty($commune) && !empty($prenomEnfant) && !empty($nomEnfant) && !empty($prenomPere) && !empty($sexe) && !empty($prenomMere) && !empty($nomMere) && !empty($lieuNaissance) && !empty($paysNaissance) && !empty($dateNaissance) && !empty($heureNaissance) && !empty($codeCNI) && !empty($codeRegion)) {
        traite(
            $region,
            $departement,
            $commune,
            $annee,
            $prenomEnfant,
            $nomEnfant,
            $prenomPere,
            $sexe,
            $prenomMere,
            $nomMere,
            $lieuNaissance,
            $paysNaissance,
            $numDansLeRegistre,
            $dateNaissance,
            $heureNaissance,
            $codeCNI,
            $codeRegion
        );
    } else {
        formulaire(
            $region,
            $departement,
            $commune,
            $annee,
            $prenomEnfant,
            $nomEnfant,
            $prenomPere,
            $sexe,
            $prenomMere,
            $nomMere,
            $lieuNaissance,
            $paysNaissance,
            $numDansLeRegistre,
            $dateNaissance,
            $heureNaissance,
            $codeCNI,
            $codeRegion,
            "Tous les champs sont requis",
            ""
        );
    }
} else {
    formulaire('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
}
?>