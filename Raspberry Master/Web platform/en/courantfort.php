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
   ScriptName    : courantfort.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 25/04/2022
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

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 3 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query) or die($mysqli->error);
        $currentacrows = array();
        while($row = $result->fetch_assoc()) {
            $currentacrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 4 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query);
        $voltageacrows = array();
        while($row = $result->fetch_assoc()) {
            $voltageacrows[] = $row;
        }
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
        <title>Data logger - High current</title>
        <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link href="../dist/css/pages/dashboard1.css" rel="stylesheet">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - High current</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="courantfort.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="courantfort.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="courantfort.php"><i class="ti-reload"></i></a> </li>
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
                                <a class="waves-effect waves-dark active" href="courantfort.php" aria-expanded="false"><i class="fas fa-bolt"></i>
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
                                    <li class="breadcrumb-item active">High current</li>
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
                                                <p class="text-danger" id="voltageactitle">AC VOLTAGE</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="voltageac">0.0 V</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="voltageacwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <p class="text-danger" id="currentactitle">AC CURRENT</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="currentac">0.0 A</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="currentacwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                    <p class="text-danger" id="puissanceactitle">AC POWER</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="puissanceac">0.0 W</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="puissanceacwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                        <h5 class="card-title ">AC VOLTAGE</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-purple"></i> Average Voltage: <?php echo number_format(getaverage($voltageacrows), 2); ?> V</li>
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
                                        <h5 class="card-title ">AC CURRENT</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-primary"></i> Average Current: <?php echo number_format(getaverage($currentacrows), 2); ?> A</li>
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
                                        <h5 class="card-title ">AC POWER</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Average Power: <?php echo number_format(getaverage($currentacrows)*getaverage($voltageacrows), 2);?> W</li>
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
                            period: <?php echo "'".SHM($currentacrows[9]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[8]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[7]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[6]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[5]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[4]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentacrows[3]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[2]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[1]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[0]['UNIXDATE'])."'"; ?>
                            , voltageac: <?php echo $voltageacrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['voltageac']
                    , labels: ['AC Voltage']
                    , parseTime: false
                    , ymax: 440
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
                            period: <?php echo "'".SHM($currentacrows[9]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[8]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[7]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[6]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[5]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[4]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentacrows[3]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[2]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[1]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[0]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['currentac']
                    , labels: ['AC Current']
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
                            period: <?php echo "'".SHM($currentacrows[9]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[9]['VALUE']*$voltageacrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[8]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[8]['VALUE']*$voltageacrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[7]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[7]['VALUE']*$voltageacrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[6]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[6]['VALUE']*$voltageacrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[5]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[5]['VALUE']*$voltageacrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[4]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[4]['VALUE']*$voltageacrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentacrows[3]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[3]['VALUE']*$voltageacrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[2]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[2]['VALUE']*$voltageacrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[1]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[1]['VALUE']*$voltageacrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[0]['UNIXDATE'])."'"; ?>
                            , puissanceac: <?php echo $currentacrows[0]['VALUE']*$voltageacrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['puissanceac']
                    , labels: ['AC Power']
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

                        document.getElementById('voltageac').innerHTML = "220 V";
                        document.getElementById('voltageacwidth').setAttribute("style", "width: 100%; height: 6px;");
                        /*if(response.voltageac < 14) {
                            document.getElementById('voltageacwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('voltageac').setAttribute("class", "counter text-danger");
                            document.getElementById('voltageactitle').setAttribute("class", "text-danger");
                        }
                        else if(response.voltageac < 20) {
                            document.getElementById('voltageacwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('voltageac').setAttribute("class", "counter text-primary");
                            document.getElementById('voltageactitle').setAttribute("class", "text-primary");
                        }
                        else {*/
                            document.getElementById('voltageacwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('voltageac').setAttribute("class", "counter text-success");
                            document.getElementById('voltageactitle').setAttribute("class", "text-success");
                        //}

                        document.getElementById('currentac').innerHTML = response.currentac + " A";
                        document.getElementById('currentacwidth').setAttribute("style", "width: " + response.cacwidth*100/4 + "%; height: 6px;");
                        if(response.currentac < 1) {
                            document.getElementById('currentacwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('currentac').setAttribute("class", "counter text-success");
                            document.getElementById('currentactitle').setAttribute("class", "text-success");
                        }
                        else if(response.currentac < 2.75) {
                            document.getElementById('currentacwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('currentac').setAttribute("class", "counter text-primary");
                            document.getElementById('currentactitle').setAttribute("class", "text-primary");
                        }
                        else {
                            document.getElementById('currentacwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('currentac').setAttribute("class", "counter text-danger");
                            document.getElementById('currentactitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('puissanceac').innerHTML = (220*response.currentac).toFixed(1) + " W";
                        document.getElementById('puissanceacwidth').setAttribute("style", "width: " + ((220*response.currentac)*100)/1000 + "%; height: 6px;");
                        if((220*response.currentac) > 600) {
                            document.getElementById('puissanceacwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('puissanceac').setAttribute("class", "counter text-danger");
                            document.getElementById('puissanceactitle').setAttribute("class", "text-danger");
                        }
                        else if((220*response.currentac) < 600 && (220*response.currentac) > 200) {
                            document.getElementById('puissanceacwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('puissanceac').setAttribute("class", "counter text-primary");
                            document.getElementById('puissanceactitle').setAttribute("class", "text-primary");
                        }
                        else if((220*response.currentac) <= 200) {
                            document.getElementById('puissanceacwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('puissanceac').setAttribute("class", "counter text-success");
                            document.getElementById('puissanceactitle').setAttribute("class", "text-success");
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
