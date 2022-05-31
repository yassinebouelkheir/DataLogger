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

            $query = "INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `VALUE`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."', 1, 'User has created a new automated function (ID : ".$mysqli->insert_id.") which affects the status of relay (ID: ".empty($_POST["InputRelay"]).")')";
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

                $query = "INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `VALUE`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."', 1, 'User has removed the automated function (ID : ".$_POST["RemvFncID"].")')";
                $mysqli->query($query) or die($mysqli->error);
            }
            else $errormessage = "Identifiant entered is invalid, Please try again.";
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
                case 1: return 'L.Current: DC Current';
                case 2: return 'L.Current: DC Voltage';
                case 3: return 'H.Current: AC Current';
                case 4: return 'H.Current: AC Voltage';
                case 5: return 'Ambient Temperature';
                case 6: return 'Panel Temperature';
                case 7: return 'Luminous flow';
                case 8: return 'Relative humidity';
                case 9: return 'Wind speed (Upstream)';
                case 10: return 'Wind speed (Downstream)';
                case 11: return 'Wind Turbine speed';
                case 12: return 'Wind Turbine: DC Current';
                case 13: return 'Wind Turbine: DC Voltage';
                case 14: return 'L.Current: Battery';
                case 15: return 'L.Current: DC Power';
                case 16: return 'H.Current: AC Power';
                case 17: return 'Wind Turbine: DC Power';
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
        <title>Data logger - Automated Functions</title>
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - Automated Functions</p>
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
                                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/world.png" alt="user" class=""></img></a>
                                <div class="dropdown-menu dropdown-menu-right animated bounceIn">
                                    <?php  
                                        $curPageName = str_replace(".php", "", substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1));
                                        echo '<a href="../switchlang.php?page='.$curPageName.'&lang=0" class="dropdown-item"><i class="flag-icon flag-icon-fr"></i> French</a>';
                                        echo '<a href="../switchlang.php?page='.$curPageName.'&lang=1" class="dropdown-item"><i class="flag-icon flag-icon-us"></i> English</a>';
                                    ?>
                                </div>
                            </li>
                            <li class="nav-item dropdown u-pro">
                                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user" class=""> <span class="hidden-md-down"><?php echo $_SESSION["username"]; ?> &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                                <div class="dropdown-menu dropdown-menu-right animated bounceIn">
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
                            <li class="nav-small-cap">--- Dashboard</li>
                            <li> 
                                <a class="waves-effect waves-dark" href="index.php" aria-expanded="false"><i class="fas fa-charging-station"></i>
                                <span class="hide-menu">&nbsp;&nbsp;Low current</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="courantfort.php" aria-expanded="false"><i class="fas fa-bolt"></i>
                                <span class="hide-menu">&nbsp;&nbsp;&nbsp;High current</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="eolienne.php" aria-expanded="false"><i class="fas fa-fan"></i>
                                <span class="hide-menu">&nbsp;&nbsp;Wind Turbine</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="meteorologie.php" aria-expanded="false"><i class="fas fa-snowflake"></i>
                                <span class="hide-menu">&nbsp;&nbsp;&nbsp;Meteorology</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="smarthouse.php" aria-expanded="false"><i class="fas fa-home"></i>
                                <span class="hide-menu">&nbsp;&nbsp;Smart House</span></a>
                            </li>
                            <li> 
                                <a class="waves-effect waves-dark" href="livestream.php" aria-expanded="false"><i class="fas fa-camera"></i>
                                <span class="hide-menu">&nbsp;&nbsp;&nbsp;Live Stream</span></a>
                            </li>
                            <li class="nav-small-cap">--- Main settings</li>
                            <li> <a class="waves-effect waves-dark" href="charges.php" aria-expanded="false"><i class="fas fa-th"></i><span class="hide-menu"> &nbsp;&nbsp;Charges</span></a>
                            </li>
                            <li><a class="waves-effect waves-dark active" href="functions.php" aria-expanded="false"><i class="fas fa-subscript"></i><span class="hide-menu"> &nbsp;&nbsp;Functions</span></a></li>
                            <?php 
                                if($_SESSION["P2"] == 1 || $_SESSION["P3"] == 1 || $_SESSION["P4"] == 1) {
                                    echo'<li><a class="waves-effect waves-dark" href="settings.php" aria-expanded="false"><i class="fas fa-cogs"></i><span class="hide-menu"> &nbsp;Settings</span></a></li>';
                                }
                                if($_SESSION["P5"] == 1) {
                                    echo'<li><a class="waves-effect waves-dark" href="history.php" aria-expanded="false"><i class="fas fa-history"></i><span class="hide-menu"> &nbsp;&nbsp;History</span></a></li>';
                                }
                            ?>
                            <li><a class="waves-effect waves-dark" href="logout.php" aria-expanded="false"><i class="fa fa-power-off"></i><span class="hide-menu"> &nbsp;&nbsp;Logout</span></a>
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
                                    <li class="breadcrumb-item active">Automated Functions</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Active Automated Functions</h4>
                                    <div class="table-responsive m-t-20">
                                        <table id="myTable1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Date</th>
                                                    <th>Created by</th>
                                                    <th>Params</th>
                                                    <th>Condition</th>
                                                    <th>Value</th>
                                                    <th>Relay</th>
                                                    <th>Action</th>
                                                    <th>Executed</th>
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
                                                    echo '<td>'.$row['EXEC'].' times</td>';
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
                                    echo '<h4 class="card-title">Create a new Automated Function</h4>';
                                    echo '<h5 class="card-subtitle text-danger"> <strong>Please be careful and check twice before performing any changes here.</strong> </h5>';
                                    echo '<form action="functions.php" method="POST" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label>Function params</label>';
                                            echo '<select class="custom-select col-12" id="InputParams" name="InputParams">';
                                                echo '<option value="">Function params</option>';
                                                echo '<option value="14">L.Current: Battery</option>';
                                                echo '<option value="2">L.Current: DC Voltage</option>';
                                                echo '<option value="1">L.Current: DC Current</option>';
                                                echo '<option value="15">L.Current: DC Power</option>';
                                                echo '<option value="4">H.Current: AC Voltage</option>';
                                                echo '<option value="3">H.Current: AC Current</option>';
                                                echo '<option value="16">H.Current: AC Power</option>';
                                                echo '<option value="13">Wind Tubrine: DC Voltage</option>';
                                                echo '<option value="12">Wind Tubrine: DC Current</option>';
                                                echo '<option value="17">Wind Tubrine: DC Power</option>';
                                                echo '<option value="5">Ambient Temperature</option>';
                                                echo '<option value="6">Panel Temperature</option>';
                                                echo '<option value="7">Luminous flow</option>';
                                                echo '<option value="18">Irradiation</option>';
                                                echo '<option value="8">Relative humidity</option>';
                                                echo '<option value="9">Wind speed (Downstream)</option>';
                                                echo '<option value="10">Wind speed (Upstream)</option>';
                                                echo '<option value="11">Wind Turbine speed</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Condition</label>';
                                            echo '<select class="custom-select col-12" id="InputCondition" name="InputCondition">';
                                                echo '<option value="">Condition oper</option>';
                                                echo '<option value="1">></option>';
                                                echo '<option value="2"><</option>';
                                                echo '<option value="3">≥</option>';
                                                echo '<option value="4">≤</option>';
                                                echo '<option value="5">=</option>';
                                                echo '<option value="6">≠</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Value to compare</label>';
                                            echo '<input type="number" class="form-control" id="InputValue" name="InputValue" value="" placeholder="Value">';
                                        echo '</div>';
                                        echo '<div class="form-group">';
                                            echo '<label>Relay</label>';
                                            echo '<select class="form-control" id="InputRelay" name="InputRelay">';
                                                echo '<option value="">Relay PIN</option>';
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
                                            echo '<label>Action</label>';
                                            echo '<select class="custom-select col-12" id="InputAction" name="InputAction">';
                                                echo '<option value="">Action</option>';
                                                echo '<option value="1">ON</option>';
                                                echo '<option value="0">OFF</option>';
                                            echo '</select>';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-success">Create</button>';
                                    echo '</form>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                    echo '<div class="row">';
                        echo '<div class="col-12">';
                            echo '<div class="card">';
                                echo '<div class="card-body">';
                                    echo '<h4 class="card-title">Remove an Automated Function</h4>';
                                    echo '<h5 class="card-subtitle text-danger"> <strong>Please be careful and think twice before performing any changes here.</strong> </h5>';
                                    echo '<h6 class="card-subtitle text-danger"> '.$errormessage.' </h6>';
                                    echo '<form action="functions.php" method="POST" class="mt-4">';
                                        echo '<div class="form-group">';
                                            echo '<label for="RemvFncID">Function ID</label>';
                                            echo '<input type="number" class="form-control" id="RemvFncID" name="RemvFncID" placeholder="Function ID">';
                                        echo '</div>';
                                        echo '<button type="submit" class="btn btn-danger">Remove</button>';
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
