<?php

include("Connexion/fct_connexion.php");

$pdo = connexion();
session_start();
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
            <section class="row">
                <?php
                $date = date('Y-m-d');
                $select_horaire = ("SELECT * FROM Horaire 
                  WHERE date_horaire = '$date'");



              foreach ($pdo->query($select_horaire) as $row) {

                  $select_planifie = ("SELECT * FROM planifier 
                  WHERE Id_Horaire_P  = '$row[id_Horaire]'");


                  foreach ($pdo->query($select_planifie) as $row) {

                            $select_group = $pdo->query("SELECT * FROM groupe 
                  WHERE Id_Groupe  = '$row[Id_Groupe_P]'");
                      $select_horaire = $pdo->query("SELECT * FROM horaire  
                  WHERE Id_Horaire  = '$row[Id_Horaire_P]'");

                      ?>
                      <section class="col-md-2"> <!-- 8h ~ 10h -->
                          <?php

                          echo "ts";



                          ?>

                      </section>

                      <section class="col-md-2"> <!-- 10h ~ 12h -->

                      </section>

                      <section class="col-md-2"> <!-- 13h ~ 15h -->

                      </section>

                      <section class="col-md-2"> <!-- 15h ~ 17h -->

                      </section>

                      <section class="col-md-2"> <!-- 17h ~ 19h -->

                      </section>

                      <section class="col-md-2">

                      </section>
                      <?php

                  }
              }
                ?>
            </section>
            <?php


            ?>

              <section class="row">
                  <section class="col-md-4">
                      <h3>Scénarios</h3>
                        <p>Liste des scenarios</p>
                      <ul class="list-group">
                          <li class="list-group-item">Cras justo odio</li>
                          <li class="list-group-item">Dapibus ac facilisis in</li>
                          <li class="list-group-item">Ici leo risus</li>
                          <li class="list-group-item">Porta ac consectetur ac</li>
                          <li class="list-group-item">Vestibulum at eros</li>
                      </ul>
                  </section>
                  <section class="col-md-8">
                      <section class="row">
                          <section class="col-md-12">
                              <h3>Light</h3>

                          </section>

                          <section class="col-md-12">

                              <h3>Volet</h3>
                              <div class="row">

                                  <div class="">
                                      <img  id="" class="img-thumbnail" src="images/volet.png" alt="volet"/>
                                  </div>
                                  <div class="col-md-5">
                                      <h5>Volet n°1</h5>
                                      <button type="button" id="volet_ouv_btn" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ouverture_volet">Ouvrir Volet</button>
                                      <br>
                                      <br>
                                      <button type="button" id="volet_ferm_btn" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#fermeture_volet">Fermer volet</button>


                                  </div>
                                  <div class="col-md-5">
                                      <h5>Volet n°2</h5>
                                      <button type="button" id="volet_ouv_btn" class="btn btn-success btn-lg" data-toggle="modal" data-target="#ouverture_volet">Ouvrir Volet</button>
                                      <br>
                                      <br>
                                      <button type="button"  id="volet_ferm_btn" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#fermeture_volet">Fermer volet</button>


                                  </div>
                              </div>

                              <!-- Modal -->
                              <div class="modal fade" id="fermeture_volet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                          </div>
                                          <div class="modal-body">
                                              ...
                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <button type="button" class="btn btn-primary">Save changes</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <!-- Modal -->
                              <div class="modal fade" id="ouverture_volet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                          </div>
                                          <div class="modal-body">
                                              ...
                                          </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <button type="button" class="btn btn-primary">Save changes</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>


                          </section>

                          <section class="col-md-12">

                                  <h3>Température</h3>
                                      <div class="row">
                                          <div class="">
                                              <img   class="img-thumbnail" src="images/temperatur.png" alt="temp"/>
                                          </div>

                                      <div class="col-md-5">
                                          <h4>Température Interieur</h4>
                                          <h3>19°</h3>

                                      </div>
                                      <div class="col-md-5">
                                          <h4>Température extérieur</h4>
                                          <h3>5°</h3>

                                      </div>
                                  </div>
                          </section>

                      </section>


                  </section>
              </section>


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