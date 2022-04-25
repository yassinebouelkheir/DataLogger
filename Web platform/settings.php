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
        if(!isset($_SESSION["username"]) || $_SESSION["username"] != "admin") 
        {
            header("Location: login.php");
            exit();
        }

        $mysqli = new mysqli("localhost", "root", "", "PFE");

        $errormessage1 = "";
        $errormessage2 = "";
        $errormessage3 = "";
        $errormessage4 = "";
        $errormessage5 = "";
        $errormessage6 = "";

        if(!empty($_POST["updatetime1"]) && 
            !empty($_POST["updatetime2"]) && 
            !empty($_POST["updatetime3"]) && 
            !empty($_POST["updatetime4"]) && 
            !empty($_POST["updatetime5"]) && 
            !empty($_POST["updatetime6"]))
        {
            if(($_POST["updatetime1"] < 1) || 
            ($_POST["updatetime2"] < 1) || 
            ($_POST["updatetime3"] < 1) || 
            ($_POST["updatetime4"] < 1) || 
            ($_POST["updatetime5"] < 1) || 
            ($_POST["updatetime6"] < 1))
            {
                $errormessage4 =  "Le temps d'enregistrement ne peut pas être inférieur à 1 minute."; 
            }
            else
            {
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$_POST["updatetime1"].' WHERE ID = 1';
                $mysqli->query($query) or die($mysqli->error); 
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$_POST["updatetime2"].' WHERE ID = 2';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$_POST["updatetime3"].' WHERE ID = 3';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$_POST["updatetime4"].' WHERE ID = 4';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$_POST["updatetime5"].' WHERE ID = 5';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$_POST["updatetime6"].' WHERE ID = 6';
                $mysqli->query($query) or die($mysqli->error);
            }     
        } 

        if(!empty($_POST["chargename1"]) && 
            !empty($_POST["chargename2"]) && 
            !empty($_POST["chargename3"]) && 
            !empty($_POST["chargename4"]) && 
            !empty($_POST["chargename5"]) && 
            !empty($_POST["chargename6"]) && 
            !empty($_POST["chargename7"]) && 
            !empty($_POST["chargename8"]))
        {
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename1"]."' WHERE ID = 1";
            $mysqli->query($query) or die($mysqli->error); 
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename2"]."' WHERE ID = 2";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename3"]."' WHERE ID = 3";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename4"]."' WHERE ID = 4";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename5"]."' WHERE ID = 5";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename6"]."' WHERE ID = 6";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename7"]."' WHERE ID = 7";
            $mysqli->query($query) or die($mysqli->error);     
            $query = "UPDATE `CHARGES` SET `NAME` = '".$_POST["chargename8"]."' WHERE ID = 8";
            $mysqli->query($query) or die($mysqli->error);      
        } 

        if(!empty($_POST['ExportationFormat']))
        {
            switch($_POST['ExportationFormat'])
            {
                case 1:
                {
                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 1 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);                     
                    break;
                }
                case 2:
                {
                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 2 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);                     
                    break;
                }
                default: 
                {
                    $errormessage6 = "Votre choix n'est pas valide, veuillez réessayer.";
                    break;
                }
            }
        }

        if(!empty($_POST['ExportationFormat']))
        {
            switch($_POST['ExportationFormat'])
            {
                case 1:
                {
                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 1 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);                     
                    break;
                }
                case 2:
                {
                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 2 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);                     
                    break;
                }
                default: 
                {
                    $errormessage6 = "Votre choix n'est pas valide, veuillez réessayer.";
                    break;
                }
            }
        }

        if(!empty($_POST['InputLowLevel']))
        {
            switch($_POST['InputLowLevel'])
            {
                case 1:
                {
                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 0 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);                     
                    break;
                }
                case 2:
                {
                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 1 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);                     
                    break;
                }
                case 3:
                {
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 1' WHERE ID = 1";
                    $mysqli->query($query) or die($mysqli->error); 
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 2' WHERE ID = 2";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 3' WHERE ID = 3";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 4' WHERE ID = 4";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 5' WHERE ID = 5";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 6' WHERE ID = 6";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 7' WHERE ID = 7";
                    $mysqli->query($query) or die($mysqli->error);     
                    $query = "UPDATE `CHARGES` SET `NAME` = 'Charge 8' WHERE ID = 8";
                    $mysqli->query($query) or die($mysqli->error);       

                    $query = "UPDATE `EXPORTATIONTYPE` SET `TYPE` = 1 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);   

                    $query = "UPDATE `SENSORS_STATIC` SET `VALUE` = 0 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);       

                    $query = "UPDATE `UPDATETIME` SET `TIME` = 2 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);

                    $query = "DELETE FROM `SENSORS` WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "INSERT INTO `SENSORS` (`ID`, `VALUE`, `UNIXDATE`) VALUES
                        (1, 0, 1648582167),
                        (1, 0, 1648582167),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (1, 0, 1648582168),
                        (2, 0, 1648582168),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582169),
                        (2, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582170),
                        (3, 0, 1648582171),
                        (3, 0, 1648582171),
                        (3, 0, 1648582171),
                        (4, 0, 1648582171),
                        (4, 0, 1648582171),
                        (4, 0, 1648582171),
                        (4, 0, 1648582171),
                        (4, 0, 1648582171),
                        (4, 0, 1648582171),
                        (4, 0, 1648582172),
                        (4, 0, 1648582172),
                        (4, 0, 1648582172),
                        (4, 0, 1648582172),
                        (5, 0, 1648582172),
                        (5, 0, 1648582172),
                        (5, 0, 1648582172),
                        (5, 0, 1648582173),
                        (5, 0, 1648582173),
                        (5, 0, 1648582173),
                        (5, 0, 1648582173),
                        (5, 0, 1648582173),
                        (5, 0, 1648582173),
                        (5, 0, 1648582173),
                        (6, 0, 1648582173),
                        (6, 0, 1648582173),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (6, 0, 1648582174),
                        (7, 0, 1648582173),
                        (7, 0, 1648582173),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (7, 0, 1648582174),
                        (8, 0, 1648582173),
                        (8, 0, 1648582173),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (8, 0, 1648582174),
                        (9, 0, 1648582173),
                        (9, 0, 1648582173),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174),
                        (9, 0, 1648582174);";
                    $mysqli->query($query) or die($mysqli->error);         
                    break;
                }
                case 4:
                {
                    // Restart                    
                    break;
                }
                default: 
                {
                    $errormessage6 = "Votre choix n'est pas valide, veuillez réessayer.";
                    break;
                }
            }
        }

        $query = 'SELECT * FROM `CHARGES` WHERE 1 ORDER BY `ID` ASC';
        $result = $mysqli->query($query) or die($mysqli->error);
        $chargesrows = array();
        while($row = $result->fetch_assoc()) {
            $chargesrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `UPDATETIME` WHERE 1 ORDER BY `ID` ASC';
        $result = $mysqli->query($query) or die($mysqli->error);
        $updatetimerows = array();
        while($row = $result->fetch_assoc()) {
            $updatetimerows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `EXPORTATIONTYPE` WHERE 1 LIMIT 1';
        $result = $mysqli->query($query) or die($mysqli->error);
        $exporttyperows = array();
        while($row = $result->fetch_assoc()) {
            $exporttyperows[] = $row;
        }
        $result->free();

        $mysqli->close();
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
                <p class="loader__label">Data logger v2.0 - Paramètres</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="settings.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="settings.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="settings.php"><i class="ti-reload"></i></a> </li>
                        </ul>

                        <ul class="navbar-nav my-lg-0">
                            <li class="nav-item dropdown u-pro">
                                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user" class=""> <span class="hidden-md-down"><?php echo $_SESSION["username"]; ?> &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                                <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                    <a href="logout.php" class="dropdown-item"><i class="fa fa-power-off"></i> Déconnexion</a>
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
                            <?php 
                                if($_SESSION["username"] == "admin") {
                                    echo'<li><a class="waves-effect waves-dark active" href="settings.php" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> Paramètres</span></a></li>';
                                }
                            ?>
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
                                    <h4 class="card-title">Modifier le temps d'enregistrement</h4>
                                    <h5 class="card-subtitle"> Veuillez entrer le temps d'enregistrement en minutes. </h5>
                                    <h5 class="card-subtitle text-danger"> <?php echo $errormessage4; ?> </h5>
                                    <form action="settings.php" method="post" class="form">
                                        <div class="form-group mt-5 row">
                                            <label for="updatetime1" class="col-2 col-form-label">Courant Faible</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $updatetimerows[0]['TIME'] ?>" id="updatetime1" name="updatetime1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime2" class="col-2 col-form-label">Courant Fort</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $updatetimerows[1]['TIME'] ?>" id="updatetime2" name="updatetime2">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime3" class="col-2 col-form-label">Température</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $updatetimerows[2]['TIME'] ?>" id="updatetime3" name="updatetime3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime4" class="col-2 col-form-label">Luminosité</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $updatetimerows[3]['TIME'] ?>" id="updatetime4" name="updatetime4">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime5" class="col-2 col-form-label">Humidité</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $updatetimerows[4]['TIME'] ?>" id="updatetime5" name="updatetime5">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="updatetime6" class="col-2 col-form-label">Vitesse du vent</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $updatetimerows[5]['TIME'] ?>" id="updatetime6" name="updatetime6">
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
                                    <h5 class="card-subtitle text-danger"> <?php echo $errormessage5; ?> </h5>
                                    <form action="settings.php" method="post" class="form">
                                        <div class="form-group mt-5 row">
                                            <label for="chargename1" class="col-2 col-form-label">Relais PIN: IN1</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[0]['NAME'] ?>" id="chargename1" name="chargename1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename2" class="col-2 col-form-label">Relais PIN: IN2</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[1]['NAME'] ?>" id="chargename2" name="chargename2">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename3" class="col-2 col-form-label">Relais PIN: IN3</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[2]['NAME'] ?>" id="chargename3" name="chargename3">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename4" class="col-2 col-form-label">Relais PIN: IN4</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[3]['NAME'] ?>" id="chargename4" name="chargename4">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename5" class="col-2 col-form-label">Relais PIN: IN5</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[4]['NAME'] ?>" id="chargename5" name="chargename5">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename6" class="col-2 col-form-label">Relais PIN: IN6</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[5]['NAME'] ?>" id="chargename6" name="chargename6">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename7" class="col-2 col-form-label">Relais PIN: IN7</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[6]['NAME'] ?>" id="chargename7" name="chargename7">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="chargename8" class="col-2 col-form-label">Relais PIN: IN8</label>
                                            <div class="col-10">
                                                <input class="form-control" type="search" value="<?php echo $chargesrows[7]['NAME'] ?>" id="chargename8" name="chargename8">
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
                                    <h5 class="card-subtitle text-info"> <strong>Format d'exportation actuelle: <strong><?php if($exporttyperows[0]['TYPE'] == 1) echo 'Excel'; else if($exporttyperows[0]['TYPE'] == 2) echo 'PDF'; else echo 'Exportation des données est Désactivé'; ?></strong></strong> </h5>
                                    <h5 class="card-subtitle text-danger"> <?php echo $errormessage6; ?> </h5>
                                    <form action="settings.php" method="post" class="mt-4">
                                        <div class="form-group">
                                            <label>Format de fichier</label>
                                            <select class="custom-select col-12" id="ExportationFormat" name="ExportationFormat">
                                                <option value="0" selected>Selectioner..</option>
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
                                    <h5 class="card-subtitle text-danger"> <strong>S'il vous plaît soyez prudent avant d'effectuer une commande ici.</strong> </h5>
                                    <form action="settings.php" method="post" class="mt-4">
                                        <div class="form-group">
                                            <label>Format de fichier</label>
                                            <select class="custom-select col-12" id="InputLowLevel" name="InputLowLevel">
                                                <option value="0" selected>Selectioner..</option>
                                                <option value="1">Désactiver l'exportation des données</option>
                                                <option value="2">Activer l'exportation des données</option>
                                                <option value="3">Vider la base de données</option>
                                                <option value="4">Redémarrez la plateforme</option>
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
        <script src="dist/js/sidebarmenu.js"></script>
        <script src="dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/switchery/dist/switchery.min.js"></script>
    </body>
</html>
