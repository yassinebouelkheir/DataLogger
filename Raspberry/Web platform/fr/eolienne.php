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
   ScriptName    : eolienne.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 01/05/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
-->

<!DOCTYPE html>
<html lang="en">
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

        $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");   

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 12 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query) or die($mysqli->error);
        $currentdcrows = array();
        while($row = $result->fetch_assoc()) {
            $currentdcrows[] = $row;
        }
        $result->free();


        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 13 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $voltagedcrows = array();
        while($row = $result->fetch_assoc()) {
            $voltagedcrows[] = $row;
        }
        $result->free();
        $mysqli->close();

        function getaverage($a)
        {
            $average = 0;
            for($i = 0; $i < 10; $i++)
            {
                $average += $a[$i]['VALUE'];
            }
            $average /= 10;
            return $average;
        }

        function SHM($seconds)
        {

            $days = floor($seconds/86400);
            $hrs = floor($seconds / 3600);
            $mins = intval(($seconds / 60) % 60); 
            $sec = intval($seconds % 60);

            if($days>0){
                $hrs = str_pad($hrs,2,'0',STR_PAD_LEFT);
                $hours = $hrs-($days*24);
                $return_days = $days." Days ";
                $hrs = str_pad($hours,2,'0',STR_PAD_LEFT);
            }else{
                $return_days="";
                $hrs = str_pad($hrs,2,'0',STR_PAD_LEFT);
            }

            $mins = str_pad($mins,2,'0',STR_PAD_LEFT);
            $sec = str_pad($sec,2,'0',STR_PAD_LEFT);

            return $hrs.":".$mins;
        }
    ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="description" content="">
        <meta name="author" content="BOUELKHEIR Yassine">
        <meta http-equiv="refresh" content="120">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Data logger - Éolienne</title>
        <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link href="../dist/css/pages/dashboard1.css" rel="stylesheet">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - Éolienne</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="eolienne.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="eolienne.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="eolienne.php"><i class="ti-reload"></i></a> </li>
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
                                <a class="waves-effect waves-dark active" href="eolienne.php" aria-expanded="false"><i class="fas fa-fan"></i>
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
                                    <li class="breadcrumb-item active">Éolienne</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card-group">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-bolt"></i></h3>
                                                <p class="text-danger" id="voltagedctitle">TENSION DC</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="voltagedc">0.0 V</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="tensiondcwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-exchange-alt"></i></h3>
                                                <p class="text-danger" id="currentdctitle">COURANT DC</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="currentdc">0.0 A</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="currentdcwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-fire"></i></h3>
                                                    <p class="text-danger" id="puissancedctitle">PUISSANCE DC</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="puissancedc">0.0 W</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="puissancedcwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">TENSION DC</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-purple"></i> Tension moyenne: <?php echo getaverage($voltagedcrows); ?> V</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">COURANT DC</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-primary"></i> Courant DC moyenne: <?php echo getaverage($currentdcrows); ?> A</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart1" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">PUISSANCE DC</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Puissance DC moyenne: <?php echo (getaverage($currentdcrows)*getaverage($voltagedcrows));?> W</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart7" style="height: 340px;"></div>
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
        <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="../dist/js/sidebarmenu.js"></script>
        <script src="../dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/raphael/raphael-min.js"></script>
        <script src="../assets/node_modules/morrisjs/morris.min.js"></script>
        <script type="text/javascript">
            $(function () {
                "use strict";
                Morris.Area({
                    element: 'morris-area-chart'
                    , data: [{
                            period: <?php echo "'".SHM($voltagedcrows[9]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[8]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[7]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[6]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[5]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[4]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($voltagedcrows[3]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($voltagedcrows[2]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($voltagedcrows[1]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($voltagedcrows[0]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['tensiondc']
                    , labels: ['Tension DC']
                    , parseTime: false
                    , ymax: 25
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#ab8ce4']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#ab8ce4']
                    , resize: true
                });

                Morris.Area({
                    element: 'morris-area-chart1'
                    , data: [{
                            period: <?php echo "'".SHM($currentdcrows[9]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[8]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[7]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[6]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[5]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[4]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentdcrows[3]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[2]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[1]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[0]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['currentdc']
                    , labels: ['Courant DC']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#fb9678']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#fb9678']
                    , resize: true
                });
                
                Morris.Area({
                    element: 'morris-area-chart7'
                    , data: [{
                            period: <?php echo "'".SHM($currentdcrows[9]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[9]['VALUE']*$voltagedcrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[8]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[8]['VALUE']*$voltagedcrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[7]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[7]['VALUE']*$voltagedcrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[6]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[6]['VALUE']*$voltagedcrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[5]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[5]['VALUE']*$voltagedcrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[4]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[4]['VALUE']*$voltagedcrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentdcrows[3]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[3]['VALUE']*$voltagedcrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[2]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[2]['VALUE']*$voltagedcrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[1]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[1]['VALUE']*$voltagedcrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[0]['UNIXDATE'])."'"; ?>
                            , puissancedc: <?php echo $currentdcrows[0]['VALUE']*$voltagedcrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['puissancedc']
                    , labels: ['PUISSANCE DC']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#0000ff']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#0000ff']
                    , resize: true
                });
            
                function refresh() {
                $.ajax({
                    url: './updateStaticValues.php',
                    type: 'post',
                    dataType: "json",
                    success: function (response) {
                        
                        document.getElementById('voltagedc').innerHTML = response.evoltagedc + " V";
                        document.getElementById('tensiondcwidth').setAttribute("style", "width: " + response.evoltagedcwidth + "%; height: 6px;");
                        if(response.evoltagedc < 14) {
                            document.getElementById('tensiondcwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('voltagedc').setAttribute("class", "counter text-danger");
                            document.getElementById('voltagedctitle').setAttribute("class", "text-danger");
                        }
                        else if(response.evoltagedc < 20) {
                            document.getElementById('tensiondcwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('voltagedc').setAttribute("class", "counter text-primary");
                            document.getElementById('voltagedctitle').setAttribute("class", "text-primary");
                        }
                        else if(response.evoltagedc >= 20){
                            document.getElementById('tensiondcwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('voltagedc').setAttribute("class", "counter text-success");
                            document.getElementById('voltagedctitle').setAttribute("class", "text-success");
                        }

                        document.getElementById('currentdc').innerHTML = response.ecurrentdc + " A";
                        document.getElementById('currentdcwidth').setAttribute("style", "width: " + response.ecdcwidth + "%; height: 6px;");
                        if(response.ecurrentdc < 15) {
                            document.getElementById('currentdcwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('currentdc').setAttribute("class", "counter text-success");
                            document.getElementById('currentdctitle').setAttribute("class", "text-success");
                        }
                        else if(response.ecurrentdc < 25) {
                            document.getElementById('currentdcwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('currentdc').setAttribute("class", "counter text-primary");
                            document.getElementById('currentdctitle').setAttribute("class", "text-primary");
                        }
                        else if(response.ecurrentdc >= 25) {
                            document.getElementById('currentdcwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('currentdc').setAttribute("class", "counter text-danger");
                            document.getElementById('currentdctitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('puissancedc').innerHTML = (response.evoltagedc*response.ecurrentdc).toFixed(1) + " W";
                        document.getElementById('puissancedcwidth').setAttribute("style", "width: " + ((response.evoltagedc*response.ecurrentdc)*100)/720 + "%; height: 6px;");
                        if((response.evoltagedc*response.ecurrentdc) > 600) {
                            document.getElementById('puissancedcwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('puissancedc').setAttribute("class", "counter text-danger");
                            document.getElementById('puissancedctitle').setAttribute("class", "text-danger");
                        }
                        else if((response.evoltagedc*response.ecurrentdc) > 300) {
                            document.getElementById('puissancedcwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('puissancedc').setAttribute("class", "counter text-primary");
                            document.getElementById('puissancedctitle').setAttribute("class", "text-primary");
                        }
                        else {
                            document.getElementById('puissancedcwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('puissancedc').setAttribute("class", "counter text-success");
                            document.getElementById('puissancedctitle').setAttribute("class", "text-success");
                        }
                    }});
                }
            setInterval(function(){
                refresh() 
            }, 600);
        });
        </script>
    </body>
</html>
