<?php



$host="localhost";
$user="root";
$password="";
$dbname="batico";

    $options = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',);
    $pdo = new PDO('mysql:host='.$host.';dbname='.$dbname.';', $user, $password,$options);





    //compteur d'ajout

    $nb_cour_ajouter = 0;

	//Définition de variables de type tableau qui contiendront toutes les informations utiles 
    $dateTableau = Array();
    $salleTableau = Array();
    $NomCoursTableau = Array();
    $HeureFinTableau = Array();
    $NomGroupeTableau = Array();
	
	//Renseignement de l'URL pointant vers le fichier .ics relatif aux enseignements planifiés au sein d'une salle de cours
	$calendrier = file_get_contents("http://ade6-usmb-ro.grenet.fr/jsp/custom/modules/plannings/direct_cal.jsp?resources=2603&projectId=4&calType=ical&login=iCalExport&password=73rosav&lastDate=2030-08-14");


	//Définition des expressions ICS relatives aux détails des cours planifiés
	$regExpDate = "'DTSTART:(.*)'"; //Date de début (Année-Jour-Mois-Heure-Minutes-secondes)
	$regExpSalle = "'LOCATION:(.*)'"; //Salle concernée
	$regExpNomCours = "'SUMMARY:(.*)'"; //Déscription de l'enseignement
	$regExpHeureFin = "'DTEND:(.*)'"; //Date de fin (Année-Jour-Mois-Heure-Minutes-secondes)
	$regExpNomGroupe = "'DESCRIPTION:(.*)'"; //Nom du groupe concerné par l'enseignement
	
	//Récupération de tous les détails précedemment renseignés (Dates de début et de fin, salle concernée, description de l'enseignement, nom du groupe concerné)
	$n = preg_match_all($regExpDate, $calendrier, $dateTableau, PREG_PATTERN_ORDER);
	$n1 = preg_match_all($regExpSalle, $calendrier, $salleTableau, PREG_PATTERN_ORDER);
	$n2 = preg_match_all($regExpNomCours, $calendrier, $NomCoursTableau, PREG_PATTERN_ORDER);
	$n3 = preg_match_all($regExpHeureFin, $calendrier, $HeureFinTableau, PREG_PATTERN_ORDER);
    $n4 = preg_match_all($regExpNomGroupe, $calendrier, $NomGroupeTableau, PREG_PATTERN_ORDER);



	//Permet de vider la base de données à chaque nouvelle exécution du programme afin d'éviter les duplications
	$sql_trunc1 = "TRUNCATE TABLE Salle";
	$sql_trunc2 = "TRUNCATE TABLE Groupe";
	$sql_trunc3 = "TRUNCATE TABLE Horaire";
	$sql_trunc4 = "TRUNCATE Table Planifier";


    $res = $pdo->prepare($sql_trunc1);
    $res->execute();

    $res1 = $pdo->prepare($sql_trunc2);
    $res1->execute();

    $res2 = $pdo->prepare($sql_trunc3);
    $res2->execute();

    $res3 = $pdo->prepare($sql_trunc4);
    $res3->execute();

	
	// Récupération des données
	for ($j=0 ; $j < $n ; ++$j) {
        $annee = substr($dateTableau[0][$j], 8, 4);
        $mois = substr($dateTableau[0][$j], 12, 2);
        $jour = substr($dateTableau[0][$j], 14, 2);
        $heure = substr($dateTableau[0][$j], 17, 2);
        $min = substr($dateTableau[0][$j], 19, 2);


        // Mise en forme des données
        $date = $annee . "-" . $mois . "-" . $jour; //Format AAAA-MM-JJ
        $dateformat = strtotime('$date'); //Conversion du type String au time Time
        $horaire = ($heure + 1) . ":" . $min . ":00"; // + 1 car horaires UTC (internationales) et ajout de 0 secondes pour correspondre au format attendu sous PHPmyAdmin
        //!!!!!Possible décalage compte tenu du changement d'heure hiver/été (vérifier dans la BDD et éventuellement rajouter +2 au lieu de +1)

        $heureFin = substr($HeureFinTableau[0][$j], 15, 2);
        $minFin = substr($HeureFinTableau[0][$j], 17, 2);
        $horaireFin = ($heureFin + 1) . ":" . $minFin . ":00"; // + 1 car horaires UTC (internationales) et ajout de 0 secondes pour correspondre au format attendu sous PHPmyAdmin
        //!!!!!Possible décalage compte tenu du changement d'heure hiver/été (vérifier dans la BDD et éventuellement rajouter +2 au lieu de +1)

        $salle = substr($salleTableau[0][$j], 9);
        $salle_trim = rtrim($salle); //Supprime les caractères spéciaux de fin de ligne, retour à la ligne, etc

        $NomCours = substr($NomCoursTableau[0][$j], 8);

        $NomGroupe = substr($NomGroupeTableau[0][$j], 14);
        $NomGroupe = substr($NomGroupe, 0, strpos($NomGroupe, '\\'));


        //Définition des différentes requêtes SQL
        $req_horaire = $pdo->prepare("INSERT INTO Horaire(date_Horaire, heure_debut_Horaire, heure_fin_Horaire)
         VALUES (:date, :horaire, :horaire_fin)");

        $req_horaire->bindParam(':date', $date);
        $req_horaire->bindParam(':horaire', $horaire);
        $req_horaire->bindParam(':horaire_fin', $horaireFin);



        $groupe = $pdo->prepare("INSERT INTO Groupe(nom_Groupe)
         VALUES (:name)");
        $groupe->bindParam(':name', $NomGroupe);


        $salle = $pdo->prepare("INSERT INTO Salle(nom_Salle)
         VALUES (:salle)");
        $salle->bindParam(':salle', $salle_trim);



        $critere_salle = $pdo->prepare("SELECT Id_Salle FROM Salle WHERE nom_Salle = '".$salle_trim."'");

        if ($critere_salle->execute(array(
            'nom_Salle' => $salle_trim
        ))) {
            $count = $critere_salle->rowCount();
            if ($count == 1) {
                //Salle déjà existante dans la base, on ne fait rien

            } else {

                 $salle->execute();

            }
        }

        $critere_horaire = $pdo->prepare("SELECT id_Horaire FROM Horaire WHERE date_horaire = '$date' AND heure_debut_Horaire = '$horaire' AND heure_fin_Horaire = '$horaireFin'");
            if ($critere_horaire->execute(array(
                'date_horaire' => $horaire,
                'heure_debut_horaire' =>$horaire,
                'heure_fin_Horaire' => $horaireFin
            ))) {
                $count = $critere_horaire->rowCount();
                if ($count == 1) {
                //Horaire déjà existant dans la base, on ne fait rien

            } else {
                $req_horaire->execute();

            }
        }


        $criteregroupe = $pdo->prepare("SELECT Id_Groupe FROM Groupe WHERE nom_groupe = '$NomGroupe'");
        if ($criteregroupe->execute(array(
            'nom_groupe' => $NomGroupe
        ))) {
            $count = $criteregroupe->rowCount();
            if ($count == 1) {
                //Horaire déjà existant dans la base, on ne fait rien

            } else {
                $groupe->execute();

            }
        }


		//Jointure de toutes les informations relatives à un enseignement(Salle, Groupe, Horaires) dans une nouvelle table (Planifier) de la base
        //Définition des différentes requêtes SQL
        $req_planifie = $pdo->prepare("
        INSERT INTO Planifier(Id_Salle_P, Id_Groupe_P, Id_Horaire_P) VALUES 
        ((SELECT Id_Salle FROM Salle WHERE nom_Salle LIKE '$salle_trim'), 
        (SELECT Id_Groupe FROM Groupe WHERE nom_groupe = '$NomGroupe'), 
        (SELECT id_Horaire FROM Horaire WHERE date_horaire = '$date' AND heure_debut_Horaire = '$horaire' AND heure_fin_Horaire = '$horaireFin'))");



        $req_planifie->execute();

        $nb_cour_ajouter++;
	}
	?>
<html lang="en">
<head>
    <link rel="icon" href="images/Logo_FINAL_2D_BAtICo.png">


    <meta name = "viewport" content = "width = device-width, initial-scale = 1">
    <link rel = "stylesheet"     href = "css/style.css">

    <link rel = "stylesheet"
          href = "https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">
    <script src = "https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js">
    </script>
    <link rel = "stylesheet"
          href = "https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Batico</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/bootstrap.css" >

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- JQUERY -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <meta charset="utf-8">
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        [class*="col"] { margin-bottom: 20px; }
        img { width: 100%; }
    </style>

</head>



<body>
<header role="presentation">

    <nav class="navbar navbar-expand-lg navbar-light bg-dark navbar-center navbar-toggleable-md">
        <div class="container">

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h3 class="text-left">BATICO</h3>
            <a class="navbar-brand" href="home.php"> <img class="small" src="images/Logo_FINAL_2D_BAtICo.png"> </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active mr-auto">
                        <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li  class="nav-item">
                        <a class="nav-link" href="plan_salle.html">Plan de la salle</a></li>
                    <li  class="nav-item" >
                        <a class="nav-link" href="APP_Acquistion_donnees_ADE.php">Mise à jour emploi du temps</a> </li>
                </ul>

            </div>
        </div>
    </nav>
</header>





<main role="main" class="container">
    <div class="container">
        <div class="col-md-6">
            <?php if($nb_cour_ajouter > 0)
                {
                   ?>


            <?php

                }
                ?>
        </div>
        <div class="col-md-6">
            Vous avez planifié <?php echo $nb_cour_ajouter;?> cours
        </div>

    </div>

</main>

<footer class=" bg-dark">
    <div class="container">
        <div class="row">

            <div class=" col-2 image_footer">

                <img class="rounded" src="images/logo_polytech.jpg">
            </div>
            <div class="col-8   image_footer">
                <div class="text-center">
                    <h6 class="text-uppercase font-weight-bold">
                        <strong>Contact</strong>
                    </h6>
                    <hr class="deep-purple accent-2 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                    <p>
                        <i class="fa fa-home mr-3"></i> New York, NY 10012, US</p>
                    <p>
                        <i class="fa fa-envelope mr-3"></i> info@example.com</p>
                    <p>
                        <i class="fa fa-phone mr-3"></i> + 01 234 567 88</p>
                    <p>
                        <i class="fa fa-print mr-3"></i> + 01 234 567 89</p>
                </div>
            </div>
            <div class="col-2 image_footer">
                <img class="rounded" src="images/logo_usmb.jpg">
            </div>

        </div>

    </div>


    <div class="footer-copyright py-3 text-center">
        © 2018 BATICO:

    </div>
    <!--/.Copyright-->

    </div>
</footer>





</body>
<script>
    $(document).ready(function () {
        $('#iframe1').on('load', function () {
            $('#loader1').hide();
        });
    });
</script>
</html>
	
	
	
	
	
?>