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
   ScriptName    : livestream.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 03/06/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine 
-->
<?php
    error_reporting(0);
    session_start();
    if(!isset($_SESSION["username"])) 
    {
        header("Location: login.php");
        exit();
    }
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600))
    { 
        session_regenerate_id(true);
        $_SESSION['LAST_ACTIVITY'] = time();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="author" content="BOUELKHEIR Yassine">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Data logger - Live Stream</title>
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link href="../dist/css/pages/pricing-page.css" rel="stylesheet">
        <script src='../dist/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - Live Stream</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="livestream.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="livestream.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="livestream.php"><i class="ti-reload"></i></a> </li>
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
                                <a class="waves-effect waves-dark active" href="livestream.php" aria-expanded="false"><i class="fas fa-camera"></i>
                                <span class="hide-menu">&nbsp;&nbsp;&nbsp;Live Stream</span></a>
                            </li>
                            <li class="nav-small-cap">--- Main settings</li>
                            <li> <a class="waves-effect waves-dark" href="charges.php" aria-expanded="false"><i class="fas fa-th"></i><span class="hide-menu"> &nbsp;&nbsp;Charges</span></a>
                            </li>
                            <li><a class="waves-effect waves-dark" href="functions.php" aria-expanded="false"><i class="fas fa-subscript"></i><span class="hide-menu"> &nbsp;&nbsp;Functions</span></a></li>
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
                                    <li class="breadcrumb-item active">Live Stream</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                              <div class="card-header">
                                Live stream from inside the smart house
                              </div>
                              <div class="card-body"> 
                                <p align="center"><iframe style="border:none;" src="http://192.168.0.200:5000/" title="livestream" height="500" width="660"></iframe></p>
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
        <script src="../assets/node_modules/popper/popper.min.js"></script>
        <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="../dist/js/sidebarmenu.js"></script>
        <script src="../dist/js/custom.min.js"></script>
    </body>
</html>
