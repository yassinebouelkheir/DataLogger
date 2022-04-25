<!--
   Copyright (c) 2022 Data Logger

   This program is free software: you can redistribute it and/or modify it under the terms of the
   GNU General Public License as published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
   even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   General Public License for more details.

   You should have received a copy of the GNU General Public License along with this program.
   If not, see <http://www.gnu.org/licenses/>.
-->

<!--
   ScriptName    : settings.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 25/04/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
-->

<!DOCTYPE html>
<html lang="en">
    <?php
        session_start();
        if(!isset($_SESSION["username"])) 
        {
            header("Location: login.php");
            exit();
        }
        $mysqli = new mysqli("localhost", "root", "", "PFE");   

        $query = 'SELECT * FROM `CHARGES` WHERE 1 ORDER BY `ID` ASC';
        $result = $mysqli->query($query) or die($mysqli->error);
        $rows = array();
        while($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
        $mysqli->close();
        $activecharge = 0;

        for($i = 0; $i < 4; $i++)
        {
            if($rows[$i]['VALUE'] == 1) $activecharge++;
        }
    ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="author" content="BOUELKHEIR Yassine">
        <meta http-equiv="refresh" content="120">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Data logger - Paramètres</title>
        <link href="../assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
        <link href="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="../assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
        <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
        <link href="../assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="../assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
        <link href="dist/css/style.min.css" rel="stylesheet">
    </head>
    <body class="skin-blue fixed-layout">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger - Charges</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="index.html">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="index.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="index.php"><i class="ti-reload"></i></a> </li>
                        </ul>

                        <ul class="navbar-nav my-lg-0">
                            <li class="nav-item dropdown u-pro">
                                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user" class=""> <span class="hidden-md-down"><?php echo $_SESSION["username"]; ?> &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                                <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                    <a href="logout.php" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="left-sidebar">
                <div class="scroll-sidebar">
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <li class="user-pro">
                                <img src="../assets/images/logo.png" alt="user-img" height="110" width="210">
                            </li>
                            <li class="user-pro text-center">
                                <img src="../assets/images/Lastimi_Logo.png" alt="user-img" height="120" width="140" style="margin-right: 13px;">
                            </li>
                            <li class="nav-small-cap">--- Menu Principal</li>
                            <li> 
                                <a class="waves-effect waves-dark" href="index.php" aria-expanded="false"><i class="fas fa-bolt"></i>
                                <span class="hide-menu">Courant Faible</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="courantfort.php" aria-expanded="false"><i class="fas fa-bolt"></i>
                                <span class="hide-menu">Courant Fort</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="meteorologie.php" aria-expanded="false"><i class="fas fa-snowflake"></i>
                                <span class="hide-menu">Météorologie</span></a>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="charges.php" aria-expanded="false"><i class="fas fa-th"></i><span class="hide-menu"> Charges</span></a>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-download"></i><span class="hide-menu"> Exporter</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="exportData.php?interval=3600&location=1">Dernière heure</a></li>
                                    <li><a href="exportData.php?interval=86400&location=1">Aujourd'hui</a></li>
                                    <li><a href="exportData.php?interval=2592000&location=1">Le mois dernier</a></li>
                                    <li><a href="exportData.php?interval=15552000&location=1">Les 6 derniers mois</a></li>
                                    <li><a href="exportData.php?interval=31104000&location=1">Cette année</a></li>
                                    <li><a href="exportData.php?interval=0&location=1">Exporter tout</a></li>
                                </ul>
                            </li>
                            <li><a class="waves-effect waves-dark" href="userhistory.php" aria-expanded="false"><i class="fas fa-history"></i><span class="hide-menu"> History</span></a>
                            </li>
                            <li><a class="waves-effect waves-dark active" href="settings.php" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> Paramètres</span></a>
                            </li>
                            <li><a class="waves-effect waves-dark" href="logout.php" aria-expanded="false"><i class="fa fa-power-off"></i><span class="hide-menu"> Déconnexion</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">Data Logger v2.0</h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Paramètres</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Ajouter un nouveau utilisateur</h4>
                                    <form class="mt-4">
                                        <div class="form-group">
                                            <label for="InputNewUser">Nom d'utilisateur</label>
                                            <input type="text" class="form-control" id="InputNewUser" placeholder="Veuillez saisir un nom d'utilisateur">
                                        </div>
                                        <div class="form-group">
                                            <label for="InputNewPassowrd">Mot de passe</label>
                                            <input type="password" class="form-control" id="InputNewPassowrd" placeholder="Veuillez saisir un mot de passe" autocomplete="on">
                                        </div>
                                        <div class="form-group">
                                            <label>Type d'utilisateur</label>
                                            <select class="custom-select col-12" id="InputPermission">
                                                <option selected>Selectioner..</option>
                                                <option value="1">Utilisateur</option>
                                                <option value="2">Administrateur</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-info">Ajouter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Réinitialiser le mot de passe</h4>
                                    <form class="mt-4">
                                        <div class="form-group">
                                            <label>Nom d'utilisateur</label>
                                            <select class="custom-select col-12" id="InputPasswordUser">
                                                <option selected>Selectioner..</option>
                                                <option value="1">Utilisateur</option>
                                                <option value="2">Administrateur</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="InputInitPassowrd">Nouveau Mot de passe</label>
                                            <input type="password" class="form-control" id="InputInitPassowrd" placeholder="Veuillez saisir un mot de passe" autocomplete="on">
                                        </div>
                                        <button type="submit" class="btn btn-info">Réinitialiser</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Supprimer un utilisateur</h4>
                                    <form class="mt-4">
                                        <div class="form-group">
                                            <label>Nom d'utilisateur</label>
                                            <select class="custom-select col-12" id="InputDeleteUser">
                                                <option selected>Selectioner..</option>
                                                <option value="1">Utilisateur</option>
                                                <option value="2">Administrateur</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Modifier le temps d'enregistrement</h4>
                                    <h5 class="card-subtitle"> Veuillez entrer le temps d'enregistrement en minutes. </h5>
                                    <form class="form">
                                        <div class="form-group mt-5 row">
                                            <label for="updatetime1" class="col-2 col-form-label">Courant Faible</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="2" id="updatetime1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime2" class="col-2 col-form-label">Courant Fort</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="2" id="updatetime2">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime3" class="col-2 col-form-label">Température</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="2" id="updatetime3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime4" class="col-2 col-form-label">Luminosité</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="2" id="updatetime4">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime5" class="col-2 col-form-label">Humidité</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="2" id="updatetime5">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime6" class="col-2 col-form-label">Vitesse du vent</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="2" id="updatetime6">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Mise à jour</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Modifier les noms des charges</h4>
                                    <h5 class="card-subtitle"> Veuillez ne pas dépassez 24 lettres. </h5>
                                    <form class="form">
                                        <div class="form-group mt-5 row">
                                            <label for="chargename1" class="col-2 col-form-label">Relais PIN: IN1</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename2" class="col-2 col-form-label">Relais PIN: IN2</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename2">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename3" class="col-2 col-form-label">Relais PIN: IN3</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename4" class="col-2 col-form-label">Relais PIN: IN4</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename4">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename5" class="col-2 col-form-label">Relais PIN: IN5</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename5">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename6" class="col-2 col-form-label">Relais PIN: IN6</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename6">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename7" class="col-2 col-form-label">Relais PIN: IN7</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename7">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename8" class="col-2 col-form-label">Relais PIN: IN8</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="Charge X" id="chargename8">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">Mise à jour</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Exportation</h4>
                                    <h5 class="card-subtitle"> Changer le format de fichier généré par le plateforme. </h5>
                                    <form class="mt-4">
                                        <div class="form-group">
                                            <label>Format de fichier</label>
                                            <select class="custom-select col-12" id="InputPasswordUser">
                                                <option selected>Selectioner..</option>
                                                <option value="1">Excel</option>
                                                <option value="2">PDF</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success">Mise à jour</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Paramètres de niveau bas</h4>
                                    <h5 class="card-subtitle"> S'il vous plaît soyez prudent avant d'effectuer une commande ici. </h5>
                                    <form class="mt-4">
                                        <div class="form-group">
                                            <label>Format de fichier</label>
                                            <select class="custom-select col-12" id="InputPasswordUser">
                                                <option selected>Selectioner..</option>
                                                <option value="1">Redémarrez la plateforme</option>
                                                <option value="2">Exporter la base de données</option>
                                                <option value="3">Vider la base de données</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Exécuter</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                © 2022 Data Logger v2.0 by <a href="https://www.linkedin.com/in/yassine-bouelkheir/">BOUELKHEIR Yassine</a>
            </footer>
        </div>
        <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="dist/js/waves.js"></script>
        <script src="dist/js/sidebarmenu.js"></script>
        <script src="dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>
        <script src="../assets/node_modules/switchery/dist/switchery.min.js"></script>
    </body>
</html>
