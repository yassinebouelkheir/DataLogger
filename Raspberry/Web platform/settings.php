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
   Developers    : BOUELKHEIR Yassine 
-->

<!DOCTYPE html>
<html lang="en">
    <?php
        session_start();
        if(!isset($_SESSION["username"]) || !$_SESSION["P2"] || !$_SESSION["P3"] || !$_SESSION["P4"]) 
        {
            header("Location: login.php");
            exit();
        }
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            session_unset();
            session_destroy();
        }
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) session_regenerate_id(true);
        $_SESSION['LAST_ACTIVITY'] = time();

        $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");

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
            else if(!is_numeric($_POST["updatetime1"]) || 
            !is_numeric($_POST["updatetime2"]) || 
            !is_numeric($_POST["updatetime3"]) || 
            !is_numeric($_POST["updatetime4"]) || 
            !is_numeric($_POST["updatetime5"]) || 
            !is_numeric($_POST["updatetime6"]))
            {
                $errormessage4 =  "Le temps d'enregistrement doit être numérique.";
            }
            else
            {
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime1"]).' WHERE ID = 1';
                $mysqli->query($query) or die($mysqli->error); 
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime2"]).' WHERE ID = 2';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime3"]).' WHERE ID = 3';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime4"]).' WHERE ID = 4';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime5"]).' WHERE ID = 5';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime6"]).' WHERE ID = 6';
                $mysqli->query($query) or die($mysqli->error);
                $query = 'UPDATE `UPDATETIME` SET `TIME` = '.$mysqli->escape_string($_POST["updatetime7"]).' WHERE ID = 7';
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
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename1"])."' WHERE ID = 1";
            $mysqli->query($query) or die($mysqli->error); 
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename2"])."' WHERE ID = 2";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename3"])."' WHERE ID = 3";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename4"])."' WHERE ID = 4";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename5"])."' WHERE ID = 5";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename6"])."' WHERE ID = 6";
            $mysqli->query($query) or die($mysqli->error);
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename7"])."' WHERE ID = 7";
            $mysqli->query($query) or die($mysqli->error);     
            $query = "UPDATE `CHARGES` SET `NAME` = '".$mysqli->escape_string($_POST["chargename8"])."' WHERE ID = 8";
            $mysqli->query($query) or die($mysqli->error);      
        } 
        if(!empty($_POST['UserDelID']))
        {
            if($_POST['UserDelID'] != 1)
            {
                $query = "DELETE FROM `ACCOUNTS` WHERE `ID` = ".$_POST['UserDelID'];
                $mysqli->query($query) or die($mysqli->error);
            }
        }
        if(!empty($_POST['UserPwdChange']) && !empty($_POST['UserPwd']))
        {
            if(strlen($_POST['UserPwd']) < 4) $errormessage3 = "le mot de passe doit contenir au moins 4 caractères.";
            else if(strlen($_POST['UserPwd']) > 24) $errormessage3 = "le mot de passe doit contenir au maximum 24 caractères.";
            else if($_POST['UserPwdChange'] > 2 || $_POST['UserPwdChange'] < 1) $errormessage3 = "Une erreur s'est produite lors du traitement de votre demande, veuillez réessayer plus tard.";
            else 
            {
                $query = "UPDATE `ACCOUNTS` SET `PASSWORD` = '".$mysqli->escape_string(md5($_POST['UserPwd']))."' WHERE `ID` = ".$mysqli->escape_string($_POST['UserPwdChange']);
                $mysqli->query($query) or die($mysqli->error);  
            }
        }

        if(!empty($_POST['ExportationInterval']) && !empty($_POST['ExportationType']))
        {
            switch($_POST['ExportationInterval'])
            {
                case 1: {
                    header("Location: exportData.php?interval=3600&type=".$_POST['ExportationType']);
                    break;
                }
                case 2: {
                    header("Location: exportData.php?interval=86400&type=".$_POST['ExportationType']);
                    break;
                }
                case 3: {
                    header("Location: exportData.php?interval=604800&type=".$_POST['ExportationType']);
                    break;
                }
                case 4: {
                    header("Location: exportData.php?interval=2419200&type=".$_POST['ExportationType']);
                    break;
                }
                case 5: {
                    header("Location: exportData.php?interval=7257600&type=".$_POST['ExportationType']);
                    break;
                }
                case 6: {
                    header("Location: exportData.php?interval=29030400&type=".$_POST['ExportationType']);
                    break;
                }
                case 7: {
                    header("Location: exportData.php?interval=0&type=".$_POST['ExportationType']);
                    break;
                }
                default: {
                    $errormessage6 = "Une erreur s'est produite lors du traitement de votre demande, veuillez réessayer plus tard.";
                    break;
                }
            }
        }
        if(!empty($_POST['NewUserName']) && !empty($_POST['NewUserPwd']))
        {
            if(strlen($_POST['NewUserName']) < 5 || strlen($_POST['NewUserName']) > 24) $errormessage2 = "Le nom d'utilisateur est invalide, veuillez réessayer.";
            else if(strlen($_POST['NewUserPwd']) < 5 || strlen($_POST['NewUserPwd']) > 24) $errormessage2 = "Le mot de passe est invalide, veuillez réessayer.";
            else
            {
                $query = "INSERT INTO `ACCOUNTS`(`USERNAME`, `PASSWORD`, `P1`, `P2`, `P3`, `P4`, `P5`) VALUES ('".$mysqli->escape_string($_POST['NewUserName'])."', '".$mysqli->escape_string(md5($_POST['NewUserPwd']))."',
                ".(($_POST['customCheck1'] == 'on') ? (1) : (0)).",".(($_POST['customCheck2'] == 'on') ? (1) : (0)).",".(($_POST['customCheck3'] == 'on') ? (1) : (0)).",".(($_POST['customCheck4'] == 'on') ? (1) : (0)).",".(($_POST['customCheck5'] == 'on') ? (1) : (0)).")";
                $mysqli->query($query) or die($query);     
            }
        }

        if(!empty($_POST['InputLowLevel']))
        {
            switch($_POST['InputLowLevel'])
            {
                case 1:
                {
                    break;
                }
                case 2:
                {
                    $query = "DELETE FROM `ACCOUNTS` WHERE `ID` > 1";
                    $mysqli->query($query) or die($query);
                    break;
                }
                case 3:
                {
                    $query = "DELETE FROM `HISTORY` WHERE `TYPE` = 0";
                    $mysqli->query($query) or die($query);
                    break;
                }
                case 4:
                {
                    $query = "DELETE FROM `HISTORY` WHERE `TYPE` = 1";
                    $mysqli->query($query) or die($query);
                    break;
                }
                case 5:
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

                    $query = "UPDATE `SENSORS_STATIC` SET `VALUE` = 0 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);       

                    $query = "UPDATE `UPDATETIME` SET `TIME` = 2 WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);

                    $query = "DELETE FROM `SENSORS` WHERE 1";
                    $mysqli->query($query) or die($mysqli->error);
                    $query = "INSERT INTO `SENSORS` (`ID`, `VALUE`, `UNIXDATE`) VALUES
                    (1, 0, 1648582100),
                    (1, 0, 1648582220),
                    (1, 0, 1648582340),
                    (1, 0, 1648582460),
                    (1, 0, 1648582580),
                    (1, 0, 1648582700),
                    (1, 0, 1648582820),
                    (1, 0, 1648582940),
                    (1, 0, 1648583060),
                    (1, 0, 1648583180),
                    (2, 0, 1648582100),
                    (2, 0, 1648582220),
                    (2, 0, 1648582340),
                    (2, 0, 1648582460),
                    (2, 0, 1648582580),
                    (2, 0, 1648582700),
                    (2, 0, 1648582820),
                    (2, 0, 1648582940),
                    (2, 0, 1648583060),
                    (2, 0, 1648583180),
                    (3, 0, 1648582100),
                    (3, 0, 1648582220),
                    (3, 0, 1648582340),
                    (3, 0, 1648582460),
                    (3, 0, 1648582580),
                    (3, 0, 1648582700),
                    (3, 0, 1648582820),
                    (3, 0, 1648582940),
                    (3, 0, 1648583060),
                    (3, 0, 1648583180),
                    (4, 0, 1648582100),
                    (4, 0, 1648582220),
                    (4, 0, 1648582340),
                    (4, 0, 1648582460),
                    (4, 0, 1648582580),
                    (4, 0, 1648582700),
                    (4, 0, 1648582820),
                    (4, 0, 1648582940),
                    (4, 0, 1648583060),
                    (4, 0, 1648583180),
                    (5, 0, 1648582100),
                    (5, 0, 1648582220),
                    (5, 0, 1648582340),
                    (5, 0, 1648582460),
                    (5, 0, 1648582580),
                    (5, 0, 1648582700),
                    (5, 0, 1648582820),
                    (5, 0, 1648582940),
                    (5, 0, 1648583060),
                    (5, 0, 1648583180),
                    (6, 0, 1648582100),
                    (6, 0, 1648582220),
                    (6, 0, 1648582340),
                    (6, 0, 1648582460),
                    (6, 0, 1648582580),
                    (6, 0, 1648582700),
                    (6, 0, 1648582820),
                    (6, 0, 1648582940),
                    (6, 0, 1648583060),
                    (6, 0, 1648583180),
                    (7, 0, 1648582100),
                    (7, 0, 1648582220),
                    (7, 0, 1648582340),
                    (7, 0, 1648582460),
                    (7, 0, 1648582580),
                    (7, 0, 1648582700),
                    (7, 0, 1648582820),
                    (7, 0, 1648582940),
                    (7, 0, 1648583060),
                    (7, 0, 1648583180),
                    (8, 0, 1648582100),
                    (8, 0, 1648582220),
                    (8, 0, 1648582340),
                    (8, 0, 1648582460),
                    (8, 0, 1648582580),
                    (8, 0, 1648582700),
                    (8, 0, 1648582820),
                    (8, 0, 1648582940),
                    (8, 0, 1648583060),
                    (8, 0, 1648583180),
                    (9, 0, 1648582100),
                    (9, 0, 1648582220),
                    (9, 0, 1648582340),
                    (9, 0, 1648582460),
                    (9, 0, 1648582580),
                    (9, 0, 1648582700),
                    (9, 0, 1648582820),
                    (9, 0, 1648582940),
                    (9, 0, 1648583060),
                    (9, 0, 1648583180),
                    (10, 0, 1648582100),
                    (10, 0, 1648582220),
                    (10, 0, 1648582340),
                    (10, 0, 1648582460),
                    (10, 0, 1648582580),
                    (10, 0, 1648582700),
                    (10, 0, 1648582820),
                    (10, 0, 1648582940),
                    (10, 0, 1648583060),
                    (10, 0, 1648583180),
                    (11, 0, 1648582100),
                    (11, 0, 1648582220),
                    (11, 0, 1648582340),
                    (11, 0, 1648582460),
                    (11, 0, 1648582580),
                    (11, 0, 1648582700),
                    (11, 0, 1648582820),
                    (11, 0, 1648582940),
                    (11, 0, 1648583060),
                    (11, 0, 1648583180),
                    (12, 0, 1648582100),
                    (12, 0, 1648582220),
                    (12, 0, 1648582340),
                    (12, 0, 1648582460),
                    (12, 0, 1648582580),
                    (12, 0, 1648582700),
                    (12, 0, 1648582820),
                    (12, 0, 1648582940),
                    (12, 0, 1648583060),
                    (12, 0, 1648583180),
                    (13, 0, 1648582100),
                    (13, 0, 1648582220),
                    (13, 0, 1648582340),
                    (13, 0, 1648582460),
                    (13, 0, 1648582580),
                    (13, 0, 1648582700),
                    (13, 0, 1648582820),
                    (13, 0, 1648582940),
                    (13, 0, 1648583060),
                    (13, 0, 1648583180);";
                    $mysqli->query($query) or die($mysqli->error);         
                    break;
                }
                case 6:
                {
                    exec('sudo /sbin/reboot');                    
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
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
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
                                <a class="waves-effect waves-dark" href="index.php" aria-expanded="false"><i class="fas fa-charging-station"></i>
                                <span class="hide-menu">&nbsp;&nbsp;Courant Faible</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="courantfort.php" aria-expanded="false"><i class="fas fa-bolt"></i>
                                <span class="hide-menu">&nbsp;&nbsp;&nbsp;Courant Fort</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="eolienne.php" aria-expanded="false"><i class="fas fa-fan"></i>
                                <span class="hide-menu">&nbsp;&nbsp;Éolienne</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="meteorologie.php" aria-expanded="false"><i class="fas fa-snowflake"></i>
                                <span class="hide-menu">&nbsp;&nbsp;Météorologie</span></a>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="charges.php" aria-expanded="false"><i class="fas fa-th"></i><span class="hide-menu"> &nbsp;&nbsp;Charges</span></a>
                            </li>
                            <?php 
                                if($_SESSION["P2"] == 1 || $_SESSION["P3"] == 1 || $_SESSION["P4"] == 1) {
                                    echo'<li><a class="waves-effect waves-dark active" href="settings.php" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> &nbsp;Paramètres</span></a></li>';
                                }
                            ?>
                            <?php 
                                if($_SESSION["P5"] == 1) {
                                    echo'<li><a class="waves-effect waves-dark" href="history.php" aria-expanded="false"><i class="fas fa-history"></i><span class="hide-menu"> &nbsp;Historique</span></a></li>';
                                }
                            ?>
                            <li><a class="waves-effect waves-dark" href="logout.php" aria-expanded="false"><i class="fa fa-power-off"></i><span class="hide-menu"> &nbsp;&nbsp;Déconnexion</span></a>
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
            <?php
                if ($_SESSION["username"] == "admin")
                {
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Ajouter un nouvel utilisateur</h4>';
                                        echo '<h5 class="card-subtitle text-danger"> <strong>S\'il vous plaît soyez prudent et saisir un mot de passe dont vous souviendrez.</strong> </h5>';
                                            echo '<h5 class="card-subtitle text-danger"> '.$errormessage2.' </h5>';
                                                echo '<form action="settings.php" method="post" class="mt-4">';
                                                    echo '<div class="form-group">';
                                                        echo '<label for="UserPwd">Nom dutilisateur</label>';
                                                        echo '<input type="text" class="form-control" id="NewUserName" name="NewUserName" placeholder="Nom dutilisateur">';
                                                    echo '</div>';
                                                    echo '<div class="form-group">';
                                                        echo '<label for="UserPwd">Mot de passe</label>';
                                                        echo '<input type="password" class="form-control" id="NewUserPwd" name="NewUserPwd" placeholder="Mot de passe">';
                                                    echo '</div>';
                                                    echo '<label>Permissions</label>';
                                                    echo '<div class="form-group row pt-4">';
                                                        echo '<div class="col-sm-4">';
                                                            echo '<div class="custom-control custom-checkbox">';
                                                                echo '<input type="checkbox" class="custom-control-input" id="customCheck1" name="customCheck1">';
                                                                echo '<label class="custom-control-label" for="customCheck1">Modifier l\'état des charges</label>';
                                                            echo '</div>';
                                                            echo '<div class="custom-control custom-checkbox">';
                                                                echo '<input type="checkbox" class="custom-control-input" id="customCheck2" name="customCheck2">';
                                                                echo '<label class="custom-control-label" for="customCheck2">Modifier les noms des charges</label>';
                                                            echo '</div>';
                                                            echo '<div class="custom-control custom-checkbox">';
                                                                echo '<input type="checkbox" class="custom-control-input" id="customCheck3" name="customCheck3">';
                                                                echo '<label class="custom-control-label" for="customCheck3">Modifier l\'intervalle d\'enregistrement</label>';
                                                            echo '</div>';
                                                            echo '<div class="custom-control custom-checkbox">';
                                                                echo '<input type="checkbox" class="custom-control-input" id="customCheck4" name="customCheck4">';
                                                                echo '<label class="custom-control-label" for="customCheck4">Exporter les données</label>';
                                                            echo '</div>';
                                                            echo '<div class="custom-control custom-checkbox">';
                                                                echo '<input type="checkbox" class="custom-control-input" id="customCheck5" name="customCheck5">';
                                                                echo '<label class="custom-control-label" for="customCheck5">Voir l\'historique</label>';
                                                            echo '</div>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                echo '<button type="submit" class="btn btn-success">Ajouter</button>';
                                            echo '</form>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '<div class="row">';
                            echo '<div class="col-12">';
                                echo '<div class="card">';
                                    echo '<div class="card-body">';
                                        echo '<h4 class="card-title">Supprimer un utilisateur</h4>';
                                        echo '<h5 class="card-subtitle text-danger"> <strong>S\'il vous plaît soyez prudent, vous ne pouvez pas annuler les modifications après l\'exécution de la commande.</strong> </h5>';
                                        echo '<form action="settings.php" method="post" class="mt-4">';
                                            echo '<div class="form-group">';
                                                echo '<label>Utilisateur</label>';
                                                echo '<select class="custom-select col-12" id="UserDelID" name="UserDelID">';
                                                echo '<option value="0" selected>Selectioner..</option>';
                                                $query = 'SELECT ID,USERNAME FROM `ACCOUNTS` WHERE ID > 1 ORDER BY `ID` ASC';
                                                $result = $mysqli->query($query) or die($mysqli->error);
                                                $rows = array();
                                                while($row = $result->fetch_assoc()) {
                                                    echo '<option value="'.$row['ID'].'">'.$row['USERNAME'].'</option>';
                                                }
                                                echo '</select>';
                                            echo '</div>';
                                            echo '<button type="submit" class="btn btn-danger">Supprimer</button>';
                                        echo '</form>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Réinitialisation de mot de passe</h4>';
                                    echo '<h5 class="card-subtitle text-danger"> <strong>S\'il vous plaît soyez prudent et saisir un mot de passe dont vous souviendrez.</strong></h5>';
                                    echo '<h5 class="card-subtitle text-danger"> '.$errormessage3.' </h5>';
                                    echo '<form action="settings.php" method="post" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label>Utilisateur</label>';
                                            echo '<select class="custom-select col-12" id="UserPwdChange" name="UserPwdChange">';
                                            echo '<option value="0" selected>Selectioner..</option>';
                                                $query = 'SELECT ID,USERNAME FROM `ACCOUNTS` WHERE 1 ORDER BY `ID` ASC';
                                                $result = $mysqli->query($query) or die($mysqli->error);
                                                $rows = array();
                                                while($row = $result->fetch_assoc()) {
                                                    echo '<option value="'.$row['ID'].'">'.$row['USERNAME'].'</option>';
                                                }
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label for="UserPwd">Nouveau mot de passe</label>';
                                            echo '<input type="password" class="form-control" id="UserPwd" name="UserPwd" placeholder="Mot de passe">';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-danger">Réinitialiser</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                if($_SESSION["P1"] == 1)
                {
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                        echo '<h4 class="card-title">Modifier les noms des charges</h4>';
                                        echo '<h5 class="card-subtitle"> Veuillez ne pas dépassez 24 lettres. </h5>';
                                        echo '<h5 class="card-subtitle text-danger"> '.$errormessage5.'</h5>';
                                        echo '<form action="settings.php" method="post" class="form">';
                                        echo '<div class="form-group mt-5 row">';
                                            echo '<label for="chargename1" class="col-2 col-form-label">Relais PIN: IN1</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[0]['NAME'].'" id="chargename1" name="chargename1">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename2" class="col-2 col-form-label">Relais PIN: IN2</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[1]['NAME'].'" id="chargename2" name="chargename2">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename3" class="col-2 col-form-label">Relais PIN: IN3</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[2]['NAME'].'" id="chargename3" name="chargename3">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename4" class="col-2 col-form-label">Relais PIN: IN4</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[3]['NAME'].'" id="chargename4" name="chargename4">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename5" class="col-2 col-form-label">Relais PIN: IN5</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[4]['NAME'].'" id="chargename5" name="chargename5">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename6" class="col-2 col-form-label">Relais PIN: IN6</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[5]['NAME'].'" id="chargename6" name="chargename6">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename7" class="col-2 col-form-label">Relais PIN: IN7</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[6]['NAME'].'" id="chargename7" name="chargename7">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="chargename8" class="col-2 col-form-label">Relais PIN: IN8</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$chargesrows[7]['NAME'].'" id="chargename8" name="chargename8">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-success">Mise à jour</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                if($_SESSION["P3"] == 3)
                {
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Modifier l\'intervalle d\'enregistrement</h4>';
                                    echo '<h5 class="card-subtitle"> Veuillez entrer l\'intervalle d\'enregistrement en minutes. </h5>';
                                    echo '<h5 class="card-subtitle text-danger"> '.$errormessage4.'</h5>';
                                    echo '<form action="settings.php" method="post" class="form">';
                                        echo '<div class="form-group mt-5 row">';
                                            echo '<label for="updatetime1" class="col-2 col-form-label">Courant Faible</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$updatetimerows[0]['TIME'].'" id="updatetime1" name="updatetime1">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="updatetime2" class="col-2 col-form-label">Courant Fort</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$updatetimerows[1]['TIME'].'" id="updatetime2" name="updatetime2">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="updatetime7" class="col-2 col-form-label">Éolienne</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value='.$updatetimerows[6]['TIME'].'>" id="updatetime6" name="updatetime7">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="updatetime3" class="col-2 col-form-label">Température</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$updatetimerows[2]['TIME'].'" id="updatetime3" name="updatetime3">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="updatetime4" class="col-2 col-form-label">Énergie lumineuse</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$updatetimerows[3]['TIME'].'" id="updatetime4" name="updatetime4">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="updatetime5" class="col-2 col-form-label">Humidité</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$updatetimerows[4]['TIME'].'" id="updatetime5" name="updatetime5">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<div class="form-group row">';
                                            echo '<label for="updatetime6" class="col-2 col-form-label">Vitesse du vent</label>';
                                            echo '<div class="col-10">';
                                                echo '<input class="form-control" type="search" value="'.$updatetimerows[5]['TIME'].'" id="updatetime6" name="updatetime6">';
                                            echo '</div>';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-success">Mise à jour</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                if($_SESSION["P4"] == 1)
                {
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Exportation des données</h4>';
                                    echo '<h5 class="card-subtitle">Ici vous pouvez obtenir un fichier Excel ou PDF de toutes les données de la base de données</h5>';
                                    echo '<h5 class="card-subtitle text-danger"> '.$errormessage6.' </h5>';
                                    echo '<form action="settings.php" method="post" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label>Type des données</label>';
                                            echo '<select class="custom-select col-12" id="ExportationType" name="ExportationType">';
                                                echo '<option value="0" selected>Selectioner..</option>';
                                                echo '<option value="1">Courant Faible</option>';
                                                echo '<option value="2">Courant Fort</option>';
                                                echo '<option value="3">Éolienne</option>';
                                                echo '<option value="4">Météorologie</option>';
                                                echo '<option value="5">Vitesse du vent</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Interval d\'enregistrement</label>';
                                            echo '<select class="custom-select col-12" id="ExportationInterval" name="ExportationInterval">';
                                                echo '<option value="0" selected>Selectioner..</option>';
                                                echo '<option value="1">60 minutes</option>';
                                                echo '<option value="2">24 Heures</option>';
                                                echo '<option value="3">7 Jours</option>';
                                                echo '<option value="4">30 Jours</option>';
                                                echo '<option value="5">3 Mois</option>';
                                                echo '<option value="6">12 Mois</option>';
                                                echo '<option value="7">Tout les données</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-info">Exporter</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
                if($_SESSION["username"] == "admin")
                {
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Paramètres de niveau bas</h4>';
                                    echo '<h5 class="card-subtitle text-danger"> <strong>S\'il vous plaît soyez prudent avant deffectuer une commande ici.</strong> </h5>';
                                    echo '<form action="settings.php" method="post" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label>Format de fichier</label>';
                                            echo '<select class="custom-select col-12" id="InputLowLevel" name="InputLowLevel">';
                                                echo '<option value="0" selected>Selectioner..</option>';
                                                echo '<option value="1">Désactiver l\'accès depuis l\'application mobile</option>';
                                                echo '<option value="2">Supprimer tous les utilisateurs</option>';
                                                echo '<option value="3">Vider l\'historique de connexion</option>';
                                                echo '<option value="4">Vider l\'historique de commande</option>';
                                                echo '<option value="5">Vider la base de données</option>';
                                                echo '<option value="6">Redémarrez la plateforme</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-danger">Exécuter</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                } 
                ?>
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
