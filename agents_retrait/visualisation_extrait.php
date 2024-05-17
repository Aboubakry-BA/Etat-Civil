<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/style2.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'extrait</title>
</head>
<script src="js/pdf.js"></script>
<script src="js/html2pdf.bundle.js"></script>

<?php
if (isset($_SESSION['extrait_details'])) {
    $extrait_details = $_SESSION['extrait_details'];
    ?>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a class="active" href="./visualisation_extrait.php">Extrait</a></li>
            <li><a href="./retrait_extrait.php">Retrait</a></li>
        </ul>
        <div style="min-height: 10px;"></div>
        <button style="float: right; margin-right: 5%;" id="button">Télécharger pdf</button>
        <div style="min-height: 50px;"></div>
        <div id="extrait">
            <div id="bloc_page2">
                <div class="un">
                    <div class="a">
                        <p>REGION: <?php echo strtoupper($extrait_details['region']); ?></p>
                        <p>DEPARTEMENT: <?php echo strtoupper($extrait_details['departement']); ?></p>
                        <p>COMMUNE: <?php echo strtoupper($extrait_details['commune']); ?></p>
                    </div>
                    <div>
                        <p>REPUBLIQUE DU SENEGAL</p>
                        <p>Un Peuple-Un But-Une foi</p>
                        <h3>ETAT CIVIL</h3>
                        <p>CENTRE PRINCIPALE (1)</p>
                        <p><?php echo strtoupper($extrait_details['commune']); ?> CENTRE PRINCIPALE</p>
                    </div>
                </div>
                <div class="deux">
                    <div class="b">
                        <h2>EXTRAIT DU REGISTRE DES ACTES DE NAISSANCE</h2>
                        <p>Pour l'année <?php echo $extrait_details['anneeRegistre']; ?></p>
                        <p>NUMERO: <?php echo $extrait_details['numDansLeRegistre']; ?> DANS LE REGISTRE</p>
                    </div>
                    <div class="c">
                        <h4>AN <?php echo $extrait_details['anneeRegistre']; ?></h4>
                        <h4><?php echo $extrait_details['numDansLeRegistre']; ?></h4>
                        <p>Numéro dans le registre en chiffres</p>
                    </div>
                </div>
                <div class="trois">
                    <div class="d">
                        <p>L'an <?php echo date('Y', strtotime($extrait_details['dateNaissance'])); ?>, le
                            <?php echo date('d', strtotime($extrait_details['dateNaissance'])); ?>
                            du mois numéro <?php echo date('m', strtotime($extrait_details['dateNaissance'])); ?>
                        </p>
                        <p>Est né à <?php echo strtoupper($extrait_details['lieuNaissance']); ?></p>
                        <h6>LIEU DE NAISSANCE</h6>
                        <p>Un enfant de sexe <?php echo strtoupper($extrait_details['sexe']); ?></p>
                        <h4><?php echo strtoupper($extrait_details['prenom']); ?></h4>
                        <h6>PRENOMS(S)</h6>
                        <p><strong>DE</strong> <?php echo strtoupper($extrait_details['prenomPere']); ?></p>
                        <h6>PRENOM(S) DU PERE</h6>
                        <p><strong>ET DE</strong> <?php echo strtoupper($extrait_details['prenomMere']); ?></p>
                        <h6>PRENOM(S) DE LA MERE</h6>
                        <p>Le pays de naissance pour les naissances à l'étranger:
                            <!-- <?php echo isset($extrait_details['paysNaissance']) ? strtoupper($extrait_details['paysNaissance']) : ''; ?> -->
                        </p>
                    </div>
                    <div class="e">
                        <p>à : <?php echo date('H', strtotime($extrait_details['heureNaissance'])); ?> HEURE(S)
                            <?php echo date('i', strtotime($extrait_details['heureNaissance'])); ?> MINUTE(S)
                        </p>
                        <h6>HEURE DE NAISSANCE</h6>
                        <h4><?php echo strtoupper($extrait_details['nomPere']); ?></h4>
                        <h6>NOM DE FAMILLE</h6>
                        <p><?php echo strtoupper($extrait_details['nomMere']); ?></p>
                        <h6>NOM DE FAMILLE DE LA MERE</h6>
                    </div>
                </div>
                <div class="quatre">
                    <div class="f">
                        <p>Jugement d'autorisation</p>
                        <p>d'inscription</p>
                        <p>(ex supplétif)</p>
                    </div>
                    <div class="g">

                    </div>
                    <div>

                    </div>
                </div>
                <div class="cinq">
                    <div class="h">
                        <h3>MENTIONS MARGINALES</h3>
                        <div>
                            <h5>EXTRAIT DELIVRE PAR LE CENTRE PRINCIPAL:<br>
                                <?php echo strtoupper($extrait_details['commune']); ?> CENTRE PRINCIPAL</h5>
                        </div>
                        <p>RESERVER</p>
                    </div>
                    <div class="i">
                        <div>
                            <h5>POUR EXTRAIT CERTIFIE CONFORME</h5>
                            <p>Fait à <?php echo strtoupper($extrait_details['commune']); ?> le <?php echo date("d/m/Y"); ?>
                            </p>
                            <p>L'officier de l'Etat civil soussigné</p>
                            <p>DIABY SY</p>
                        </div>
                        <p>CODE REGION <?php echo $extrait_details['codeRegion']; ?> - CODE CNI
                            <?php echo $extrait_details['codeCNI']; ?>,00 - TYPE: CPU
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="min-height: 50px;">

        </div>
        <?php
        include_once ('includes/footer.php'); ?>
    </body>

    </html>

    <?php
} else {
    ?>

    <body>
        <ul>
            <li style="float:left"><a>Etat Civil</a></li>
            <li><a href="./deconnexion.php" onclick="return confirm('Confirmer déconnexion')">
                    Se Deconnecter
                </a>
            </li>
            <li><a class="active" href="./visualisation_extrait.php">Extrait</a></li>
            <li><a href="./retrait_extrait.php">Retrait</a></li>
        </ul>
        <div style="min-height: 50px;">

        </div>
        <h3 style="color:red;text-align:center">
            Aucune donnée d'extrait n'a été trouvée.
        </h3>
        <div style="min-height: 50px;">

        </div>
        <?php
        include_once ('includes/footer.php'); ?>
    </body>
    <?php
}
?>