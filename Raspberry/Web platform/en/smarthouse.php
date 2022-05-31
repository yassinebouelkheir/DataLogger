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
   ScriptName    : smarthouse.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 30/05/2022
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

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 14 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query) or die($mysqli->error);
        $tempext = array();
        while($row = $result->fetch_assoc()) {
            $tempext[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 15 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $tempint = array();
        while($row = $result->fetch_assoc()) {
            $tempint[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 16 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $humidityint = array();
        while($row = $result->fetch_assoc()) {
            $humidityint[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 17 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $co2level = array();
        while($row = $result->fetch_assoc()) {
            $co2level[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 18 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $gauzeslevel = array();
        while($row = $result->fetch_assoc()) {
            $gauzeslevel[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 19 ORDER BY `UNIXDATE` DESC LIMIT 10';
        $result = $mysqli->query($query);
        $luminousflow = array();
        while($row = $result->fetch_assoc()) {
            $luminousflow[] = $row;
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
        <title>Data logger - Smart House</title>
        <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
        <link href="../dist/css/style.min.css" rel="stylesheet">
        <link href="../dist/css/pages/dashboard1.css" rel="stylesheet">
        <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.js' crossorigin='anonymous'></script>
    </head>
    <body class="skin-blue fixed-layout" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger v2.0 - Smart House</p>
            </div>
        </div>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="smarthouse.php">
                            <span>  
                    </div>
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"> <a class="nav-link d-block d-md-none waves-effect waves-dark" href="smarthouse.php"><i class="ti-reload"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link d-none d-lg-block d-md-block waves-effect waves-dark" href="smarthouse.php"><i class="ti-reload"></i></a> </li>
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
                                <a class="waves-effect waves-dark active" href="smarthouse.php" aria-expanded="false"><i class="fas fa-home"></i>
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
                                    <li class="breadcrumb-item active">Smart House</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="card-group">
                        <div class="card">
                            <div class="card-header bg-danger" id="lightsheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-lightbulb"></i></h3>
                                                <p class="text-danger" id="lighttitle">INTERIOR<br> LIGHTS</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="light">OFF</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger" id="doorheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-building"></i></h3>
                                                <p class="text-danger" id="doortitle">EXTERIOR<br> MAIN DOOR</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="door">CLOSED</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger" id="windowheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="far fa-window-maximize"></i></h3>
                                                    <p class="text-danger" id="windowtitle">EXTERIOR<br> WINDOW</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="window">CLOSED</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger" id="acheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-snowflake"></i></h3>
                                                    <p class="text-danger" id="actitle">AIR CONDITIONER</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="ac">OFF</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger" id="acfanheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-fan"></i></h3>
                                                    <p class="text-danger" id="acfantitle">AC FAN</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="acfan">OFF</h3>
                                            </div>
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
                                                <p class="text-danger" id="ldrfluxtitle">EXTERIOR<br> LUMINOUS FLOW</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="ldrflux">0 LUX</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" id="ldrfluxwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <h3><i class="fas fa-cloud"></i></h3>
                                                    <p class="text-danger" id="gauzesleveltitle">RESTROOM<br> GAUZES LEVEL</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="gauzeslevel">0.0 PPM</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="gauzeslevelwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger" id="extractorheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-wind"></i></h3>
                                                <p class="text-danger" id="extractortitle">RESTROOM<br> EXTRACTOR</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="extractor">OFF</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-danger" id="moveheader"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex no-block align-items-center">
                                            <div>
                                                <h3><i class="fas fa-male"></i></h3>
                                                    <p class="text-danger" id="movetitle">RESTROOM<br> MOVEMENT</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h3 class="counter text-danger" id="movement">NO</h3>
                                            </div>
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
                                                <h3><i class="fas fa-thermometer-three-quarters"></i></h3>
                                                <p class="text-danger" id="tempexttitle">EXTERIOR<br> TEMPERATURE</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="tempext">0 °C</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="tempextwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                <p class="text-danger" id="tempinttitle">INTERIOR<br> TEMPERATURE</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="tempint">0 °C</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="tempintwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                    <p class="text-danger" id="inthumiditytitle">INTERIOR<br> HUMIDITY</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="inthumidity">0.0 %RH</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="inthumiditywidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                                    <p class="text-danger" id="co2leveltitle">INTERIOR<br> CO2 LEVEL</p>
                                            </div>
                                            <div class="ml-auto">
                                                <h2 class="counter text-danger" id="co2level">0.0 PPM</h2>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-12">
                                        <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" id="co2levelwidth" style="width: 0%; height: 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                        <h5 class="card-title ">TEMPERATURES</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-danger"></i> Average Exterior Temp: <?php echo number_format(getaverage($tempext), 1); ?> °C</li>
                                                <li><i class="fa fa-circle text-warning"></i> Average Interior Temp: <?php echo number_format(getaverage($tempint), 1); ?> °C</li>
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
                                        <h5 class="card-title ">GAUZES LEVEL</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-purple"></i> Average Int.CO2 Level: <?php echo number_format(getaverage($co2level), 1); ?> PPM</li>
                                                <li><i class="fa fa-circle text-primary"></i> Average RR.Gauzes Level: <?php echo number_format(getaverage($gauzeslevel), 1); ?> PPM</li>
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
                                        <h5 class="card-title ">INTERIOR HUMIDITY</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-success"></i> Average Humidity: <?php echo number_format(getaverage($humidityint), 0); ?> %RH</li>
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
                                        <h5 class="card-title ">LUMINOUS FLOW</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Average Flow: <?php echo number_format(getaverage($luminousflow), 1); ?> LUX</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart3" style="height: 340px;"></div>
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
                            period: <?php echo "'".SHM($tempint[9]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[9]['VALUE']; ?>
                            , ext: <?php echo $tempext[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($tempint[8]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[8]['VALUE']; ?>
                            , ext: <?php echo $tempext[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($tempint[7]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[7]['VALUE']; ?>
                            , ext: <?php echo $tempext[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($tempint[6]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[6]['VALUE']; ?>
                            , ext: <?php echo $tempext[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($tempint[5]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[5]['VALUE']; ?>
                            , ext: <?php echo $tempext[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($tempint[4]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[4]['VALUE']; ?>
                            , ext: <?php echo $tempext[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($tempint[3]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[3]['VALUE']; ?>
                            , ext: <?php echo $tempext[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($tempint[2]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[2]['VALUE']; ?>
                            , ext: <?php echo $tempext[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($tempint[1]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[1]['VALUE']; ?>
                            , ext: <?php echo $tempext[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($tempint[0]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $tempint[0]['VALUE']; ?>
                            , ext: <?php echo $tempext[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['int', 'ext']
                    , labels: ['Int.Temp', 'Ext.Temp']
                    , parseTime: false
                    , ymax: 25
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#ab8ce4', '#ab8ce4']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#FDC02B', '#E26877']
                    , resize: true
                });
                Morris.Area({
                    element: 'morris-area-chart1'
                    , data: [{
                            period: <?php echo "'".SHM($co2level[9]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[9]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($co2level[8]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[8]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($co2level[7]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[7]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($co2level[6]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[6]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($co2level[5]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[5]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($co2level[4]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[4]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($co2level[3]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[3]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($co2level[2]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[2]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($co2level[1]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[1]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($co2level[0]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $co2level[0]['VALUE']; ?>
                            , ext: <?php echo $gauzeslevel[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['int', 'ext']
                    , labels: ['Int.CO2', 'RR.Gauzes']
                    , parseTime: false
                    , ymax: 25
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#ab8ce4', '#ab8ce4']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#8E76BD', '#F9957B']
                    , resize: true
                });
            
                Morris.Area({
                    element: 'morris-area-chart2'
                    , data: [{
                            period: <?php echo "'".SHM($humidityint[9]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityint[8]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityint[7]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityint[6]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityint[5]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityint[4]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($humidityint[3]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityint[2]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityint[1]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityint[0]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $humidityint[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['int']
                    , labels: ['Humidity']
                    , parseTime: false
                    , ymax: 25
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#ab8ce4']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#1FC293']
                    , resize: true
                });

                Morris.Area({
                    element: 'morris-area-chart3'
                    , data: [{
                            period: <?php echo "'".SHM($luminousflow[9]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[9]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($luminousflow[8]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[8]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($luminousflow[7]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[7]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($luminousflow[6]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[6]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($luminousflow[5]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[5]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($luminousflow[4]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[4]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($luminousflow[3]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[3]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($luminousflow[2]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[2]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($luminousflow[1]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[1]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($luminousflow[0]['UNIXDATE'])."'"; ?>
                            , int: <?php echo $luminousflow[0]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['int']
                    , labels: ['Luminous Flow']
                    , parseTime: false
                    , ymax: 25
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#ab8ce4']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#1CAAF0']
                    , resize: true
                });

                function refresh() {
                $.ajax({
                    url: './updateStaticValues.php',
                    type: 'post',
                    dataType: "json",
                    success: function (response) {
                        
                        if(response.lights == 1) {
                            document.getElementById('lightsheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('light').setAttribute("class", "counter text-success");
                            document.getElementById('lighttitle').setAttribute("class", "text-success");
                            document.getElementById('light').innerHTML = "ON";
                        }
                        else {
                            document.getElementById('lightsheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('light').setAttribute("class", "counter text-danger");
                            document.getElementById('lighttitle').setAttribute("class", "text-danger");
                            document.getElementById('light').innerHTML = "OFF";
                        }

                        if(response.exteriorDoor == 1) {
                            document.getElementById('doorheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('door').setAttribute("class", "counter text-success");
                            document.getElementById('doortitle').setAttribute("class", "text-success");
                            document.getElementById('door').innerHTML = "OPENED";
                        }
                        else {
                            document.getElementById('doorheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('door').setAttribute("class", "counter text-danger");
                            document.getElementById('doortitle').setAttribute("class", "text-danger");
                            document.getElementById('door').innerHTML = "CLOSED";
                        }

                        if(response.interiorWindow == 1) {
                            document.getElementById('windowheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('window').setAttribute("class", "counter text-success");
                            document.getElementById('windowtitle').setAttribute("class", "text-success");
                            document.getElementById('window').innerHTML = "OPENED";
                        }
                        else {
                            document.getElementById('windowheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('window').setAttribute("class", "counter text-danger");
                            document.getElementById('windowtitle').setAttribute("class", "text-danger");
                            document.getElementById('window').innerHTML = "CLOSED";
                        }

                        if(response.ac == 1) {
                            document.getElementById('acheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('ac').setAttribute("class", "counter text-success");
                            document.getElementById('actitle').setAttribute("class", "text-success");
                            document.getElementById('ac').innerHTML = "ON";
                        }
                        else {
                            document.getElementById('acheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('ac').setAttribute("class", "counter text-danger");
                            document.getElementById('actitle').setAttribute("class", "text-danger");
                            document.getElementById('ac').innerHTML = "OFF";
                        }

                        if(response.acfan == 1) {
                            document.getElementById('acfanheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('acfan').setAttribute("class", "counter text-success");
                            document.getElementById('acfantitle').setAttribute("class", "text-success");
                            document.getElementById('acfan').innerHTML = "ON";
                        }
                        else {
                            document.getElementById('acfanheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('acfan').setAttribute("class", "counter text-danger");
                            document.getElementById('acfantitle').setAttribute("class", "text-danger");
                            document.getElementById('acfan').innerHTML = "OFF";
                        }

                        if(response.extractor == 1) {
                            document.getElementById('extractorheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('extractor').setAttribute("class", "counter text-success");
                            document.getElementById('extractortitle').setAttribute("class", "text-success");
                            document.getElementById('extractor').innerHTML = "ON";
                        }
                        else {
                            document.getElementById('extractorheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('extractor').setAttribute("class", "counter text-danger");
                            document.getElementById('extractortitle').setAttribute("class", "text-danger");
                            document.getElementById('extractor').innerHTML = "OFF";
                        }

                        if(response.movement == 1) {
                            document.getElementById('moveheader').setAttribute("class", "card-header bg-success");
                            document.getElementById('movement').setAttribute("class", "counter text-success");
                            document.getElementById('movetitle').setAttribute("class", "text-success");
                            document.getElementById('movement').innerHTML = "YES";
                        }
                        else {
                            document.getElementById('moveheader').setAttribute("class", "card-header bg-danger");
                            document.getElementById('movement').setAttribute("class", "counter text-danger");
                            document.getElementById('movetitle').setAttribute("class", "text-danger");
                            document.getElementById('movement').innerHTML = "NO";
                        }

                        document.getElementById('tempext').innerHTML = response.tempint + " °C";
                        document.getElementById('tempextwidth').setAttribute("style", "width: " + response.tempintwidth + "%; height: 6px;");
                        if(response.tempext < 25) {
                            document.getElementById('tempextwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('tempext').setAttribute("class", "counter text-success");
                            document.getElementById('tempexttitle').setAttribute("class", "text-success");
                        }
                        else if(response.tempext < 37) {
                            document.getElementById('tempextwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('tempext').setAttribute("class", "counter text-primary");
                            document.getElementById('tempexttitle').setAttribute("class", "text-primary");
                        }
                        else if(response.tempext >= 37) {
                            document.getElementById('tempextwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('tempext').setAttribute("class", "counter text-danger");
                            document.getElementById('tempexttitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('tempint').innerHTML = response.tempext + " °C";
                        document.getElementById('tempintwidth').setAttribute("style", "width: " + response.tempextwidth + "%; height: 6px;");
                        if(response.tempint < 25) {
                            document.getElementById('tempintwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('tempint').setAttribute("class", "counter text-success");
                            document.getElementById('tempinttitle').setAttribute("class", "text-success");
                        }
                        else if(response.tempint < 37) {
                            document.getElementById('tempintwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('tempint').setAttribute("class", "counter text-primary");
                            document.getElementById('tempinttitle').setAttribute("class", "text-primary");
                        }
                        else if(response.tempint >= 37) {
                            document.getElementById('tempintwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('tempint').setAttribute("class", "counter text-danger");
                            document.getElementById('tempinttitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('inthumidity').innerHTML = response.humidityint + " %RH";
                        document.getElementById('inthumiditywidth').setAttribute("style", "width: " + response.humidityintwidth + "%; height: 6px;");
                        if(response.humidityint > 60) {
                            document.getElementById('inthumiditywidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('inthumidity').setAttribute("class", "counter text-success");
                            document.getElementById('inthumiditytitle').setAttribute("class", "text-success");
                        }
                        else if(response.humidityint >= 40) {
                            document.getElementById('inthumiditywidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('inthumidity').setAttribute("class", "counter text-primary");
                            document.getElementById('inthumiditytitle').setAttribute("class", "text-primary");
                        }
                        else if(response.humidityint < 40) {
                            document.getElementById('inthumiditywidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('inthumidity').setAttribute("class", "counter text-danger");
                            document.getElementById('inthumiditytitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('co2level').innerHTML = response.co2level + " PPM";
                        document.getElementById('co2levelwidth').setAttribute("style", "width: " + response.co2levelwidth + "%; height: 6px;");
                        if(response.co2level < 400) {
                            document.getElementById('co2levelwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('co2level').setAttribute("class", "counter text-success");
                            document.getElementById('co2leveltitle').setAttribute("class", "text-success");
                        }
                        else if(response.co2level < 700) {
                            document.getElementById('co2levelwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('co2level').setAttribute("class", "counter text-primary");
                            document.getElementById('co2leveltitle').setAttribute("class", "text-primary");
                        }
                        else if(response.co2level >= 700) {
                            document.getElementById('co2levelwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('co2level').setAttribute("class", "counter text-danger");
                            document.getElementById('co2leveltitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('gauzeslevel').innerHTML = response.gauzeslevel + " PPM";
                        document.getElementById('gauzeslevelwidth').setAttribute("style", "width: " + response.gauzeslevelwidth + "%; height: 6px;");
                        if(response.gauzeslevel < 400) {
                            document.getElementById('gauzeslevelwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('gauzeslevel').setAttribute("class", "counter text-success");
                            document.getElementById('gauzesleveltitle').setAttribute("class", "text-success");
                        }
                        else if(response.gauzeslevel < 700) {
                            document.getElementById('gauzeslevelwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('gauzeslevel').setAttribute("class", "counter text-primary");
                            document.getElementById('gauzesleveltitle').setAttribute("class", "text-primary");
                        }
                        else if(response.gauzeslevel >= 700) {
                            document.getElementById('gauzeslevelwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('gauzeslevel').setAttribute("class", "counter text-danger");
                            document.getElementById('gauzesleveltitle').setAttribute("class", "text-danger");
                        }

                        document.getElementById('ldrflux').innerHTML = response.luminousflow + " LUX";
                        document.getElementById('ldrfluxwidth').setAttribute("style", "width: " + response.luminousflowwidth + "%; height: 6px;");
                        if(response.luminousflowwidth > 60) {
                            document.getElementById('ldrfluxwidth').setAttribute("class", "progress-bar bg-success");
                            document.getElementById('ldrflux').setAttribute("class", "counter text-success");
                            document.getElementById('ldrfluxtitle').setAttribute("class", "text-success");
                        }
                        else if(response.luminousflowwidth >= 40) {
                            document.getElementById('ldrfluxwidth').setAttribute("class", "progress-bar bg-primary");
                            document.getElementById('ldrflux').setAttribute("class", "counter text-primary");
                            document.getElementById('ldrfluxtitle').setAttribute("class", "text-primary");
                        }
                        else if(response.luminousflowwidth < 40) {
                            document.getElementById('ldrfluxwidth').setAttribute("class", "progress-bar bg-danger");
                            document.getElementById('ldrflux').setAttribute("class", "counter text-danger");
                            document.getElementById('ldrfluxtitle').setAttribute("class", "text-danger");
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
