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
   ScriptName    : charges.php
   Author        : BOUELKHEIR Yassine
   Version       : 1.0
   Created       : 18/03/2022
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
        $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");   

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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Data logger - Charges</title>
        <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
        <link href="../assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
        <link href="dist/css/style.min.css" rel="stylesheet">
        <link href="dist/css/pages/pricing-page.css" rel="stylesheet">
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
                                <img src="../assets/images/logo.png" alt="user-img" height="110" width="215">
                            </li>
                            <li class="nav-small-cap">--- MAIN MENU</li>
                            <li> <a class="waves-effect waves-dark" href="index.php" aria-expanded="false"><i class="icon-speedometer"></i>
                                <span class="hide-menu">Statistiques </span></a>
                            </li>
                            <li> <a class="waves-effect waves-dark active" href="charges.php" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Charges<span class="badge badge-pill badge-cyan ml-auto"> <?php echo $activecharge; ?> / 4</span></span></a>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-cloud-download"></i><span class="hide-menu">Exporter</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="exportData.php?interval=3600&location=0">Dernière heure</a></li>
                                    <li><a href="exportData.php?interval=86400&location=0">Aujourd'hui</a></li>
                                    <li><a href="exportData.php?interval=2592000&location=0">Le mois dernier</a></li>
                                    <li><a href="exportData.php?interval=15552000&location=0">Les 6 derniers mois</a></li>
                                    <li><a href="exportData.php?interval=31104000&location=0">Cette année</a></li>
                                    <li><a href="exportData.php?interval=0&location=0">Exporter tout</a></li>
                                </ul>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="logout.php" aria-expanded="false"><i class="fa fa-power-off"></i><span class="hide-menu">Logout</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">Data Logger v1.0</h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active">Charges</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row pricing-plan">
                                        <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                            <div class="pricing-box">
                                                <div class="pricing-body">
                                                    <div class="pricing-header">
                                                        <h4 class="text-center">Relais PIN: IN1</h4>
                                                        <h2 class="text-center">Charge 1</h2>
                                                        <p class="uppercase text-success">Disponible</p>
                                                    </div>
                                                    <div class="price-table-content">
                                                        <?php 
                                                            if($rows[0]['VALUE'] == 0)
                                                            {
                                                                echo '<div class="price-row">État: Éteint <i class="fas fa-minus-circle"></i></div><div class="price-row">';
                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-success waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=22&value=1'\">Allumer</button>");
                                                            }
                                                            else
                                                            {
                                                                echo '<div class="price-row">État: Allumé <i class="fas fa-check-circle"></i></div><div class="price-row">';
                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-danger waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=22&value=0'\">Éteindre</button>");                                                               
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                            <div class="pricing-box">
                                                <div class="pricing-body">
                                                    <div class="pricing-header">
                                                        <h4 class="text-center">Relais PIN: IN2</h4>
                                                        <h2 class="text-center">Charge 2</h2>
                                                        <p class="uppercase text-success">Disponible</p>
                                                    </div>
                                                    <div class="price-table-content">
                                                        <?php 
                                                            if($rows[1]['VALUE'] == 0)
                                                            {
                                                                echo '<div class="price-row">État: Éteint <i class="fas fa-minus-circle"></i></div><div class="price-row">';
                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-success waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=23&value=1'\">Allumer</button>");
                                                            }
                                                            else
                                                            {
                                                                echo '<div class="price-row">État: Allumé <i class="fas fa-check-circle"></i></div><div class="price-row">';
                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-danger waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=23&value=0'\">Éteindre</button>");                                                            
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                            <div class="pricing-box">
                                                <div class="pricing-body">
                                                    <div class="pricing-header">
                                                        <h4 class="text-center">Relais PIN: IN3</h4>
                                                        <h2 class="text-center">Charge 3</h2>
                                                        <p class="uppercase text-success">Disponible</p>
                                                    </div>
                                                    <div class="price-table-content">
                                                        <?php 
                                                            if($rows[2]['VALUE'] == 0)
                                                            {
                                                                echo '<div class="price-row">État: Éteint <i class="fas fa-minus-circle"></i></div><div class="price-row">';

                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-success waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=24&value=1'\">Allumer</button>");
                                                            }
                                                            else
                                                            {
                                                                echo '<div class="price-row">État: Allumé <i class="fas fa-check-circle"></i></div><div class="price-row">';

                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-danger waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=24&value=0'\">Éteindre</button>");                                                           
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                            <div class="pricing-box">
                                                <div class="pricing-body">
                                                    <div class="pricing-header">
                                                        <h4 class="text-center">Relais PIN: IN4</h4>
                                                        <h2 class="text-center">Charge 4</h2>
                                                        <p class="uppercase text-success">Disponible</p>
                                                    </div>
                                                    <div class="price-table-content">
                                                        <?php 
                                                            if($rows[3]['VALUE'] == 0)
                                                            {
                                                                echo '<div class="price-row">État: Éteint <i class="fas fa-minus-circle"></i></div><div class="price-row">';
                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-success waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=25&value=1'\">Allumer</button>");
                                                            }
                                                            else
                                                            {
                                                                echo '<div class="price-row">État: Allumé <i class="fas fa-check-circle"></i></div><div class="price-row">';
                                                                if($_SESSION["username"] == "admin") echo( "<button class=\"btn btn-danger waves-effect waves-light m-t-20\" onclick= \"location.href='updateCharge.php?chargeid=25&value=0'\">Éteindre</button>");                                              
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                © 2022 Data Logger by BOUELKHEIR Yassine
            </footer>
        </div>
        <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <script src="../assets/node_modules/popper/popper.min.js"></script>
        <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="dist/js/waves.js"></script>
        <script src="dist/js/sidebarmenu.js"></script>
        <script src="dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/raphael/raphael-min.js"></script>
        <script src="../assets/node_modules/morrisjs/morris.min.js"></script>
        <script src="../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>
        <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script> 
    </body>
</html>
