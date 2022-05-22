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
   ScriptName    : meterologie.php
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

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 5 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $temprows = array();
        while($row = $result->fetch_assoc()) {
            $temprows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 6 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $temp1rows = array();
        while($row = $result->fetch_assoc()) {
            $temp1rows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 7 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $brightnessrows = array();
        while($row = $result->fetch_assoc()) {
            $brightnessrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 8 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $humidityrows = array();
        while($row = $result->fetch_assoc()) {
            $humidityrows[] = $row;
        }
        for($i = 0; $i < 10; $i++) $humidityrows[$i]['VALUE'] = ($humidityrows[$i]['VALUE']*100)/1023;
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 9 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $windspeedrows = array();
        while($row = $result->fetch_assoc()) {
            $windspeedrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 10 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $windspeedinvrows = array();
        while($row = $result->fetch_assoc()) {
            $windspeedinvrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 11 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $turbinerows = array();
        while($row = $result->fetch_assoc()) {
            $turbinerows[] = $row;
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
        <title>Data logger - Meteorology</title>
        <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link href="../dist/css/pages/dashboard1.css" rel="stylesheet">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - Meteorology</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="meteorologie.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="meteorologie.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="meteorologie.php"><i class="ti-reload"></i></a> </li>
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
                                <a class="waves-effect waves-dark active" href="meteorologie.php" aria-expanded="false"><i class="fas fa-snowflake"></i>
                                <span class="hide-menu">&nbsp;&nbsp;&nbsp;Meteorology</span></a>
                            </li>
                            <li><a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-home"></i><span class="hide-menu">&nbsp;&nbsp;&nbsp;Smart House</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="Smarthouse/index.php">Main space</a></li>
                                    <li><a href="Smarthouse/aircon.php">Air conditioner</a></li>
                                    <li><a href="Smarthouse/bathroom.php">Bathroom</a></li>
                                </ul>
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
                                    <li class="breadcrumb-item active">Meteorology</li>
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
                                                <h3><i class="fas fa-thermometer-three-quarters"></i></h3>
                                                <p class="text-danger" id="tempambiant">AMBIENT TEMPERATURE</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="tempambiantvalue">0.0 °C</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="tempambiantwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <h3><i class="fas fa-thermometer-three-quarters"></i></h3>
                                                <p class="text-danger" id="temppanneau">PANEL TEMPERATURE</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="temppanneauvalue">0.0 °C</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="temppanneauwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
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
                                                <h3><i class="fas fa-sun"></i></h3>
                                                    <p class="text-danger" id="brightness">LUMINOUS FLOW</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="brightnessvalue">0.0 LUX</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="brightnesswidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <h3><i class="fas fa-atom"></i></h3>
                                                    <p class="text-danger" id="irradiation">IRRADIATION</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="irradiationvalue">0.0 W/m²</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="irradiationwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <h3><i class="fas fa-tint"></i></h3>
                                                    <p class="text-danger" id="humidity">RELATIVE HUMIDITY</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="humidityvalue">0.0 %RH</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="humiditywidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
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
                                                    <h3><i class="fas fa-tachometer-alt"></i></h3>
                                                        <p class="text-danger" id="windspeed">WIND SPEED (DOWNSTREAM)</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-danger" id="windspeedvalue">0.0 KM/H</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="windspeedwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                    <h3><i class="fas fa-tachometer-alt"></i></h3>
                                                        <p class="text-danger" id="windspeedinv">WIND SPEED (UPSTREAM)</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-danger" id="windspeedinvvalue">0.0 KM/H</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="windspeedinvwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                    <h3><i class="fas fa-rocket"></i></h3>
                                                        <p class="text-danger" id="turbine">TURBINE SPEED</p>
                                                </div>
                                                <div class="ml-auto">
                                                    <h2 class="counter text-danger" id="turbinevalue">0.0 TR/MIN</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="turbinewidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                        <h5 class="card-title ">AMBIENT TEMPERATURE</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-danger"></i> Average Temperature: <?php echo number_format(getaverage($temprows), 0); ?> °C</li>
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
                                        <h5 class="card-title ">PANEL TEMPERATURE</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-danger"></i> Average Temperature: <?php echo number_format(getaverage($temprows), 0); ?> °C</li>
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
                                        <h5 class="card-title ">LUMINOUS FLOW</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Average Luminous flow: <?php echo number_format((((2500/(getaverage($brightnessrows)*0.0048828125)-500)/10)), 0);?> LUX</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart2" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title">IRRADIATION</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Average Irradiation: <?php echo number_format(((pow((1000-getaverage($brightnessrows)),2)/10)/(50)), 0);?> W/m²</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart3" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">RELATIVE HUMIDITY</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Average %RH: <?php echo number_format(getaverage($humidityrows), 0);?> %RH</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart4" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">WIND SPEED (DOWNSTREAM)</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-primary"></i> Average Speed: <?php echo number_format(getaverage($windspeedrows), 0);?> KM/H</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart5" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">WIND SPEED (UPSTREAM)</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-purple"></i> Average Speed: <?php echo number_format(getaverage($windspeedinvrows), 0);?> KM/H</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart6" style="height: 340px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex m-b-40 align-items-center no-block">
                                        <h5 class="card-title ">TURBINE SPEED</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Average Speed: <?php echo number_format(getaverage($turbinerows), 0);?> TR/MIN</li>
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
                            period: <?php echo "'".SHM($temprows[9]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[8]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[7]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[6]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[5]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[4]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($temprows[3]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temprows[2]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temprows[1]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temprows[0]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['temp']
                    , labels: ['Temperature']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#e46a76']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#e46a76']
                    , resize: true
                });
                Morris.Area({
                    element: 'morris-area-chart1'
                    , data: [{
                            period: <?php echo "'".SHM($temp1rows[9]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temp1rows[8]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temp1rows[7]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temp1rows[6]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temp1rows[5]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temp1rows[4]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($temp1rows[3]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temp1rows[2]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temp1rows[1]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temp1rows[0]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temp1rows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['temp']
                    , labels: ['Temperature']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#e46a76']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#e46a76']
                    , resize: true
                });

                Morris.Area({
                    element: 'morris-area-chart2'
                    , data: [{
                            period: <?php echo "'".SHM($brightnessrows[9]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[9]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[8]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[8]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[7]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[7]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[6]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[6]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[5]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[5]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[4]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[4]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }
                        , {
                            period: <?php echo "'".SHM($brightnessrows[3]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[3]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[2]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[2]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[1]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[1]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[0]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo number_format((2500/($brightnessrows[0]['VALUE']*0.0048828125)-500)/10, 1, '.', ''); ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['brightness']
                    , labels: ['Luminous Flow']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#fec107']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#fec107']
                    , resize: true
                });

                Morris.Area({
                    element: 'morris-area-chart3'
                    , data: [{
                            period: <?php echo "'".SHM($brightnessrows[9]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[9]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[8]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[8]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[7]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[7]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[6]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[6]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[5]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[5]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[4]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[4]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }
                        , {
                            period: <?php echo "'".SHM($brightnessrows[3]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[3]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[2]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[2]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[1]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[1]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[0]['UNIXDATE'])."'"; ?>
                            , Irradiation: <?php echo number_format((pow((1000-$brightnessrows[0]['VALUE']),2)/10)/(50), 1, '.', ''); ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['Irradiation']
                    , labels: ['Irradiation']
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

                Morris.Area({
                    element: 'morris-area-chart4'
                    , data: [{
                            period: <?php echo "'".SHM($humidityrows[9]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[9]['VALUE'], 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[8]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[8]['VALUE'], 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[7]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[7]['VALUE'], 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[6]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[6]['VALUE'], 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[5]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[5]['VALUE'], 1, '.', ''); ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[4]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[4]['VALUE'], 1, '.', ''); ?>
                    }
                        , {
                            period: <?php echo "'".SHM($humidityrows[3]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[3]['VALUE'], 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityrows[2]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[2]['VALUE'], 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityrows[1]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[1]['VALUE'], 1, '.', ''); ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityrows[0]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo number_format($humidityrows[0]['VALUE'], 1, '.', ''); ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['humidity']
                    , labels: ['%RH']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#00bfc7']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#00bfc7']
                    , resize: true
                });

                Morris.Area({
                    element: 'morris-area-chart5'
                    , data: [{
                            period: <?php echo "'".SHM($windspeedrows[9]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedrows[8]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedrows[7]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedrows[6]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedrows[5]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedrows[4]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($windspeedrows[3]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($windspeedrows[2]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($windspeedrows[1]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($windspeedrows[0]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['Vitesse']
                    , labels: ['Wind Speed (DOWNSTREAM)']
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
                    element: 'morris-area-chart6'
                    , data: [{
                            period: <?php echo "'".SHM($windspeedinvrows[9]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedinvrows[8]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedinvrows[7]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedinvrows[6]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedinvrows[5]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($windspeedinvrows[4]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($windspeedinvrows[3]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($windspeedinvrows[2]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($windspeedinvrows[1]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($windspeedrows[0]['UNIXDATE'])."'"; ?>
                            , Vitesse: <?php echo $windspeedinvrows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['Vitesse']
                    , labels: ['Wind Speed (UPSTREAM)']
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
                    element: 'morris-area-chart7'
                    , data: [{
                            period: <?php echo "'".SHM($turbinerows[9]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($turbinerows[8]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($turbinerows[7]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($turbinerows[6]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($turbinerows[5]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($turbinerows[4]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($turbinerows[3]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($turbinerows[2]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($turbinerows[1]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($turbinerows[0]['UNIXDATE'])."'"; ?>
                            , vitesseamon: <?php echo $turbinerows[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['vitesseamon']
                    , labels: ['Turbine speed']
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
                            document.getElementById('temppanneauvalue').innerHTML = response.temperature1 + " °C";
                            document.getElementById('temppanneauwidth').setAttribute("style", "width: " + response.temp1width + "%; height: 6px;");
                            if(response.temperature < 25) {
                                document.getElementById('temppanneauwidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('temppanneauvalue').setAttribute("class", "counter text-success");
                                document.getElementById('temppanneau').setAttribute("class", "text-success");
                            }
                            else if(response.temperature < 37) {
                                document.getElementById('temppanneauwidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('temppanneauvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('temppanneau').setAttribute("class", "text-primary");
                            }
                            else if(response.temperature >= 37) {
                                document.getElementById('temppanneauwidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('temppanneauvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('temppanneau').setAttribute("class", "text-danger");
                            }

                            document.getElementById('tempambiantvalue').innerHTML = response.temperature + " °C";
                            document.getElementById('tempambiantwidth').setAttribute("style", "width: " + response.tempwidth + "%; height: 6px;");
                            if(response.temperature1 < 25) {
                                document.getElementById('tempambiantwidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('tempambiantvalue').setAttribute("class", "counter text-success");
                                document.getElementById('tempambiant').setAttribute("class", "text-success");
                            }
                            else if(response.temperature1 < 37) {
                                document.getElementById('tempambiantwidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('tempambiantvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('tempambiant').setAttribute("class", "text-primary");
                            }
                            else if(response.temperature1 >= 37) {
                                document.getElementById('tempambiantwidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('tempambiantvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('tempambiant').setAttribute("class", "text-danger");
                            }

                            document.getElementById('humidityvalue').innerHTML = response.humidity + " %RH";
                            document.getElementById('humiditywidth').setAttribute("style", "width: " + response.humidtywidth + "%; height: 6px;");
                            if(response.humidity < 50) {
                                document.getElementById('humiditywidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('humidityvalue').setAttribute("class", "counter text-success");
                                document.getElementById('humidity').setAttribute("class", "text-success");
                            }
                            else if(response.humidity < 70) {
                                document.getElementById('humiditywidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('humidityvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('humidity').setAttribute("class", "text-primary");
                            }
                            else if(response.humidity >= 70) {
                                document.getElementById('humiditywidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('humidityvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('humidity').setAttribute("class", "text-danger");
                            }

                            if(response.brightness > 0) document.getElementById('brightnessvalue').innerHTML = ((2500/(response.brightness*0.0048828125)-500)/10).toFixed(0)  + " LUX";
                            else document.getElementById('brightnessvalue').innerHTML = "0 LUX";
                            document.getElementById('brightnesswidth').setAttribute("style", "width: " + (((2500/(response.brightness*0.0048828125)-500)/10).toFixed(0)) + "%; height: 6px;");
                            if((((2500/(response.brightness*0.0048828125)-500)/10).toFixed(0)) < 25) {
                                document.getElementById('brightnesswidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('brightnessvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('brightness').setAttribute("class", "text-danger");
                            }
                            else if((((2500/(response.brightness*0.0048828125)-500)/10).toFixed(0)) < 76) {
                                document.getElementById('brightnesswidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('brightnessvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('brightness').setAttribute("class", "text-primary");
                            }
                            else if((((2500/(response.brightness*0.0048828125)-500)/10).toFixed(0)) >= 76) {
                                document.getElementById('brightnesswidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('brightnessvalue').setAttribute("class", "counter text-success");
                                document.getElementById('brightness').setAttribute("class", "text-success");
                            }

                            document.getElementById('irradiationvalue').innerHTML = ((Math.pow((1000-response.brightness),2)/10)/(50)).toFixed(0) + " W/m²";
                            document.getElementById('irradiationwidth').setAttribute("style", "width: " + (((Math.pow((1000-response.brightness),2)/10)/(50)).toFixed(0)/10) + "%; height: 6px;");
                            if(((Math.pow((1000-response.brightness),2)/10)/(50)).toFixed(0) < 400) {
                                document.getElementById('irradiationwidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('irradiationvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('irradiation').setAttribute("class", "text-danger");
                            }
                            else if(((Math.pow((1000-response.brightness),2)/10)/(50)).toFixed(0)  < 700) {
                                document.getElementById('irradiationwidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('irradiationvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('irradiation').setAttribute("class", "text-primary");
                            }
                            else if(((Math.pow((1000-response.brightness),2)/10)/(50)).toFixed(0)  >= 700) {
                                document.getElementById('irradiationwidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('irradiationvalue').setAttribute("class", "counter text-success");
                                document.getElementById('irradiation').setAttribute("class", "text-success");
                            }

                            document.getElementById('windspeedvalue').innerHTML = (response.windspeed*1).toFixed(0) + " KM/H";
                            document.getElementById('windspeedwidth').setAttribute("style", "width: " + response.windspeedwidth + "%; height: 6px;");
                            if(response.windspeed < 10) {
                                document.getElementById('windspeedwidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('windspeedvalue').setAttribute("class", "counter text-success");
                                document.getElementById('windspeed').setAttribute("class", "text-success");
                            }
                            else if(response.windspeed < 20) {
                                document.getElementById('windspeedwidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('windspeedvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('windspeed').setAttribute("class", "text-primary");
                            }
                            else if(response.windspeed >= 20) {
                                document.getElementById('windspeedwidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('windspeedvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('windspeed').setAttribute("class", "text-danger");
                            }

                            document.getElementById('windspeedinvvalue').innerHTML = (response.windspeedinv*1).toFixed(0) + " KM/H";
                            document.getElementById('windspeedinvwidth').setAttribute("style", "width: " + response.windspeedinvwidth + "%; height: 6px;");
                            if(response.windspeedwidth < 10) {
                                document.getElementById('windspeedinvwidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('windspeedinvvalue').setAttribute("class", "counter text-success");
                                document.getElementById('windspeedinv').setAttribute("class", "text-success");
                            }
                            else if(response.windspeedwidth < 20) {
                                document.getElementById('windspeedinvwidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('windspeedinvvalue').setAttribute("class", "counter text-primary");
                                document.getElementById('windspeedinv').setAttribute("class", "text-primary");
                            }
                            else if(response.windspeedwidth >= 20) {
                                document.getElementById('windspeedinvwidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('windspeedinvvalue').setAttribute("class", "counter text-danger");
                                document.getElementById('windspeedinv').setAttribute("class", "text-danger");
                            }

                            document.getElementById('turbinevalue').innerHTML = (response.turbine*1).toFixed(0) + " TR/MIN";
                            document.getElementById('turbinewidth').setAttribute("style", "width: " + response.turbinewidth + "%; height: 6px;");
                            if(response.windspeedwidth < 25) {
                                document.getElementById('turbinewidth').setAttribute("class", "progress-bar bg-success");
                                document.getElementById('turbinevalue').setAttribute("class", "counter text-success");
                                document.getElementById('turbine').setAttribute("class", "text-success");
                            }
                            else if(response.windspeedwidth < 40) {
                                document.getElementById('turbinewidth').setAttribute("class", "progress-bar bg-primary");
                                document.getElementById('turbinevalue').setAttribute("class", "counter text-primary");
                                document.getElementById('turbine').setAttribute("class", "text-primary");
                            }
                            else if(response.windspeedwidth >= 40) {
                                document.getElementById('turbinewidth').setAttribute("class", "progress-bar bg-danger");
                                document.getElementById('turbinevalue').setAttribute("class", "counter text-danger");
                                document.getElementById('turbine').setAttribute("class", "text-danger");
                            }
                            
                        }
                    });
                }
            setInterval(function(){
                refresh() 
            }, 600);
        });
        </script>
    </body>
</html>
