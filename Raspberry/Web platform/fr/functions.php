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
   ScriptName    : functions.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 08/05/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine 
-->

<!DOCTYPE html>
<html lang="en">
    <?php
        error_reporting(0);
        session_start();
        if(!isset($_SESSION["username"]) || !$_SESSION["P5"]) 
        {
            header("Location: login.php");
            exit();
        }
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600))
        { 
            session_regenerate_id(true);
            $_SESSION['LAST_ACTIVITY'] = time();
        }

        $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");
        $errormessage = "";
        
        if(!empty($_POST["InputParams"]) && 
            !empty($_POST["InputCondition"]) && 
            !empty($_POST["InputAction"]) && 
            !empty($_POST["InputValue"]) && 
            !empty($_POST["InputRelay"]))
        {
            $query = "INSERT INTO `FUNCTIONS` (`USERNAME`, `PARAM`, `CONDITIONS`, `VALUE`, `RELAY`, `FNCT`) VALUES ('".$_SESSION['username']."', ".$_POST["InputParams"].", ".$_POST["InputCondition"].", ".$mysqli->escape_string($_POST["InputValue"]).", ".$_POST["InputRelay"].", ".$_POST["InputAction"].")";
            $mysqli->query($query) or die($mysqli->error);

            $query = "INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `VALUE`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."', 1, 'création de la nouvelle fonction automatisée (ID : ".$mysqli->insert_id.") qui affecte le status de charge (ID: ".empty($_POST["InputRelay"]).")')";
            $mysqli->query($query) or die($mysqli->error); 
        }
        if(!empty($_POST["RemvFncID"]))
        {
            $query = "SELECT `ID` FROM FUNCTIONS WHERE `ID` = ".$mysqli->escape_string($_POST["RemvFncID"]);
            $result = $mysqli->query($query) or die($mysqli->error);
            if($result->num_rows != 0)
            {
                $query = "DELETE FROM FUNCTIONS WHERE `ID` = ".$mysqli->escape_string($_POST["RemvFncID"]);
                $mysqli->query($query) or die($mysqli->error);

                $query = "INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `VALUE`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."', 1, 'Suppréssion de la fonction automatisée (ID : ".$_POST["RemvFncID"].")')";
                $mysqli->query($query) or die($mysqli->error);
            }
            else $errormessage = "Identifiant de fonction non valide, veuillez réessayer";
            $result->free();
        }
        function htmlxssprotection($string)
        {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
        function getParam($param)
        {
            switch($param)
            {
                case 1: return 'C.Faible: Courant DC';
                case 2: return 'C.Faible: Courant DC';
                case 3: return 'C.Fort: Courant AC';
                case 4: return 'C.Fort: Tension AC';
                case 5: return 'Température Ambiante';
                case 6: return 'Température du Panneau';
                case 7: return 'Flux Lumineux';
                case 8: return 'Humidité Relative';
                case 9: return 'Vitesse du vent (Aval)';
                case 10: return 'Vitesse du vent (Amon)';
                case 11: return 'Turbine';
                case 12: return 'Éolienne: Courant DC';
                case 13: return 'Éolienne: Tension DC';
                case 14: return 'C.Faible: Batterie';
                case 15: return 'C.Faible: Puissance DC';
                case 16: return 'C.Fort: Puissance AC';
                case 17: return 'Éolienne: Puissance DC';
                case 18: return 'Irradiation';          
            }
        }
        function getCondition($cond)
        {
            switch($cond)
            {
                case 1: return '>';
                case 2: return '<';
                case 3: return '≥';
                case 4: return '≤';
                case 5: return '=';
                case 6: return '≠';
            }
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
        <title>Data logger - Fonctions Automatisée</title>
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - Fonctions Automatisée</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="functions.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="functions.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="functions.php"><i class="ti-reload"></i></a> </li>
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
                            <li><a class="waves-effect waves-dark" href="functions.php" aria-expanded="false"><i class="fas fa-subscript"></i><span class="hide-menu"> &nbsp;&nbsp;Fonctions</span></a></li>
                            <?php 
                                if($_SESSION["P2"] == 1 || $_SESSION["P3"] == 1 || $_SESSION["P4"] == 1) {
                                    echo'<li><a class="waves-effect waves-dark" href="settings.php" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> &nbsp;Paramètres</span></a></li>';
                                }
                                if($_SESSION["P5"] == 1) {
                                    echo'<li><a class="waves-effect waves-dark" href="history.php" aria-expanded="false"><i class="fas fa-history"></i><span class="hide-menu"> &nbsp;&nbsp;Historique</span></a></li>';
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
                                    <li class="breadcrumb-item active">Fonctions Automatisée</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Fonctions automatisées actives</h4>
                                    <div class="table-responsive m-t-20">
                                        <table id="myTable1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Créé par</th>
                                                    <th>Paramètre</th>
                                                    <th>Condition</th>
                                                    <th>Value</th>
                                                    <th>Charge</th>
                                                    <th>Action</th>
                                                    <th>Exécuté</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                $query = 'SELECT * FROM `FUNCTIONS` WHERE 1 ORDER BY `UNIXDATE` DESC';
                                                $result = $mysqli->query($query) or die($mysqli->error);
                                                $rows = array();
                                                while($row = $result->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>'.$row['ID'].'</td>';
                                                    echo '<td>'.$row['UNIXDATE'].'</td>';
                                                    echo '<td>'.$row['USERNAME'].'</td>';
                                                    echo '<td>'.getParam($row['PARAM']).'</td>';
                                                    echo '<td>'.getCondition($row['CONDITIONS']).'</td>';
                                                    echo '<td>'.htmlxssprotection($row['VALUE']).'</td>';
                                                    echo '<td>'.$row['RELAY'].'</td>';
                                                    echo '<td>'.(($row['FNCT']) ? ("ON") : ("OFF")).'</td>';
                                                    echo '<td>'.$row['EXEC'].' Fois</td>';
                                                    echo '</tr>';
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                if($_SESSION["P6"] == 1)
                {
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Ajouter une nouvelle fonction automatisées</h4>';
                                    echo '<h5 class="card-subtitle text-danger"> <strong>S\'il vous plaît soyez prudent avant deffectuer une commande ici.</strong> </h5>';
                                    echo '<form action="functions.php" method="POST" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label>Fonction paramètre</label>';
                                            echo '<select class="custom-select col-12" id="InputParams" name="InputParams">';
                                                echo '<option value="">Paramètres</option>';
                                                echo '<option value="14">C.Faible: Batterie</option>';
                                                echo '<option value="2">C.Faible: Tension DC</option>';
                                                echo '<option value="1">C.Faible: Courant DC</option>';
                                                echo '<option value="15">C.Faible: Puissance DC</option>';
                                                echo '<option value="4">C.Fort: Tension AC</option>';
                                                echo '<option value="3">C.Fort: Courant AC</option>';
                                                echo '<option value="16">C.Fort: Puissance AC</option>';
                                                echo '<option value="13">Éolienne: Tension DC</option>';
                                                echo '<option value="12">Éolienne: Courant DC</option>';
                                                echo '<option value="17">Éolienne: Puissance DC</option>';
                                                echo '<option value="5">Température Ambiante</option>';
                                                echo '<option value="6">Température du Panneau</option>';
                                                echo '<option value="7">Flux Lumineux</option>';
                                                echo '<option value="18">Irradiation</option>';
                                                echo '<option value="8">Humidité Relative</option>';
                                                echo '<option value="9">Vitesse du vent (Aval)</option>';
                                                echo '<option value="10">Vitesse du vent (Amon)</option>';
                                                echo '<option value="11">Turbine</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Condition opérateur</label>';
                                            echo '<select class="custom-select col-12" id="InputCondition" name="InputCondition">';
                                                echo '<option value="">Condition</option>';
                                                echo '<option value="1">></option>';
                                                echo '<option value="2"><</option>';
                                                echo '<option value="3">≥</option>';
                                                echo '<option value="4">≤</option>';
                                                echo '<option value="5">=</option>';
                                                echo '<option value="6">≠</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Valeur à comparer</label>';
                                            echo '<input type="number" class="form-control" id="InputValue" name="InputValue" value="" placeholder="Valeur">';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Relais</label>';
                                            echo '<select class="form-control" id="InputRelay" name="InputRelay">';
                                                echo '<option value="">Relais PIN</option>';
                                                echo '<option value="1">1</option>';
                                                echo '<option value="2">2</option>';
                                                echo '<option value="3">3</option>';
                                                echo '<option value="4">4</option>';
                                                echo '<option value="5">5</option>';
                                                echo '<option value="6">6</option>';
                                                echo '<option value="6">7</option>';
                                                echo '<option value="6">8</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Action à effectuer</label>';
                                            echo '<select class="custom-select col-12" id="InputAction" name="InputAction">';
                                                echo '<option value="">Action</option>';
                                                echo '<option value="1">ON</option>';
                                                echo '<option value="0">OFF</option>';
                                            echo '</select>';
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
                                    echo '<h4 class="card-title">Supprimer une fonction automatisée</h4>';
                                    echo '<h5 class="card-subtitle text-danger"> <strong>S\'il vous plaît soyez prudent avant deffectuer une commande ici.</strong> </h5>';
                                    echo '<h6 class="card-subtitle text-danger"> '.$errormessage.' </h6>';
                                    echo '<form action="functions.php" method="POST" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label for="RemvFncID">Fonction ID</label>';
                                            echo '<input type="number" class="form-control" id="RemvFncID" name="RemvFncID" placeholder="Fonction ID">';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-danger">Supprimer</button>';
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
        <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="../dist/js/sidebarmenu.js"></script>
        <script src="../dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="../assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
        <script>
        $(function () {
                $('#myTable1').DataTable( {
            "order": [[ 0, "desc" ]],
        	});
        });
    </script>
    </body>
</html>
