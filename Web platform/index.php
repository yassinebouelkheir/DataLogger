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
   ScriptName    : index.php
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

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 54 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query) or die($mysqli->error);
        $currentdcrows = array();
        while($row = $result->fetch_assoc()) {
            $currentdcrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 55 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query);
        $currentacrows = array();
        while($row = $result->fetch_assoc()) {
            $currentacrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 56 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query);
        $voltagedcrows = array();
        while($row = $result->fetch_assoc()) {
            $voltagedcrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 57 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query);
        $temprows = array();
        while($row = $result->fetch_assoc()) {
            $temprows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 58 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query);
        $brightnessrows = array();
        while($row = $result->fetch_assoc()) {
            $brightnessrows[] = $row;
        }
        $result->free();

        $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 59 ORDER BY `UNIXDATE` ASC LIMIT 10';
        $result = $mysqli->query($query);
        $humidityrows = array();
        while($row = $result->fetch_assoc()) {
            $humidityrows[] = $row;
        }
        $result->free();
        

        $query = 'SELECT * FROM `SENSORS_STATIC` WHERE 1 ORDER BY `ID` ASC';
        $result = $mysqli->query($query);
        $staticrows = array();
        while($row = $result->fetch_assoc()) {
            $staticrows[] = $row;
        }
        $result->free();
        $mysqli->close();

        $activesensors = 0;
        if($staticrows[0]['VALUE'] > 1) $activesensors++;
        if($staticrows[1]['VALUE'] > 1) $activesensors++;
        if($staticrows[2]['VALUE'] > 1) $activesensors++;
        if($staticrows[3]['VALUE'] > 1) $activesensors++;
        if($staticrows[4]['VALUE'] > 1) $activesensors++;
        if($staticrows[5]['VALUE'] > 1) $activesensors++;

        $jsonurl = "https://api.openweathermap.org/data/2.5/weather?lat=34.0337&lon=6.7708&lang=fr&appid=36a1abfb8868c3cc0784a4953f738e70";
        $json = file_get_contents($jsonurl);

        $batterie = round((($staticrows[2]['VALUE']-12)*100)/13);
        if($batterie < 0) $batterie = 0;
        $temperature = round($staticrows[3]['VALUE']);

        $weather = json_decode($json);
        $kelvin = $weather->main->temp;
        $celcius = round($kelvin - 277.15);
        $skystats = $weather->weather[0]->description;
        $skystats = mb_strtoupper($skystats);

        setlocale (LC_TIME, 'fr_FR.utf8','fra'); 

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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Data logger - PFE 2021/2022</title>
        <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
        <link href="../assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
        <link href="dist/css/style.min.css" rel="stylesheet">
        <link href="dist/css/pages/dashboard1.css" rel="stylesheet">
    </head>
    <body class="skin-blue fixed-layout">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger - PFE 2021/2022</p>
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
                            <li> <a class="waves-effect waves-dark active" href="javascript:void(0)" aria-expanded="false"><i class="icon-speedometer"></i>
                                <?php 
                                    if($activesensors != 6) echo '<span class="hide-menu">Statistiques <span class="badge badge-pill badge-danger ml-auto"> '.$activesensors.' / 6</span></span></a>';
                                    else echo '<span class="hide-menu">Statistiques <span class="badge badge-pill badge-cyan ml-auto"> '.$activesensors.' / 6</span></span></a>';
                                ?>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="charges.php" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Charges</span></a>
                            </li>
                            <li> <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="icon-cloud-download"></i><span class="hide-menu">Exporter</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="exportData.php?interval=3600&location=1">Dernière heure</a></li>
                                    <li><a href="exportData.php?interval=86400&location=1">Aujourd'hui</a></li>
                                    <li><a href="exportData.php?interval=2592000&location=1">Le mois dernier</a></li>
                                    <li><a href="exportData.php?interval=15552000&location=1">Les 6 derniers mois</a></li>
                                    <li><a href="exportData.php?interval=31104000&location=1">Cette année</a></li>
                                    <li><a href="exportData.php?interval=0&location=1">Exporter tout</a></li>
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
                                    <li class="breadcrumb-item active">Statistiques</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card bg-cyan text-white">
                                <div class="card-body">
                                    <div class="row weather">
                                        <div class="col-6 m-t-40">
                                            <h3>&nbsp;</h3>
                                            <?php
                                                echo '<div class="display-4">'.$celcius.'<sup>°C</sup></div>';
                                            ?>
                                            <p class="text-white">SALÉ, MAROC</p>
                                        </div>
                                        <div class="col-6 text-right">
                                            <h1 class="m-b-"><i class="wi wi-day-cloudy-high"></i></h1>
                                            <?php
                                                echo '<b class="text-white">'.$skystats.'</b>';
                                                echo '<br>'.strftime('%H:%M').'</b><p class="op-5">'.mb_strtoupper(strftime('%A %d %B %Y')).'</p>';
                                            ?>
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
                                                <h3><i class="fas fa-battery-three-quarters"></i></h3>
                                                <?php
                                                    if($batterie < 20)
                                                    {
                                                        echo '<p class="text-danger">BATTERIE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="batterie">'.$batterie.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="batteriewidth" style="width: '.$batterie.'%; height: 6px;" aria-valuenow="'.$batterie.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($batterie < 40) 
                                                    {
                                                        echo '<p class="text-primary">BATTERIE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="batterie">'.$batterie.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="batteriewidth" style="width: '.$batterie.'%; height: 6px;" aria-valuenow="'.$batterie.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">BATTERIE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="batterie">'.$batterie.' %</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="batteriewidth" style="width: '.$batterie.'%; height: 6px;" aria-valuenow="'.$batterie.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                                <h3><i class="fas fa-bolt"></i></h3>
                                                <?php
                                                    if($staticrows[5]['VALUE'] < 15)
                                                    {
                                                        echo '<p class="text-danger">TENSION DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="voltagedc">'.number_format($staticrows[2]['VALUE'], 1).' V</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="tensiondcwidth" style="width: '.number_format(((($staticrows[2]['VALUE']-12)*100)/13), 1).'%; height: 6px;" aria-valuenow="'.((($staticrows[2]['VALUE']-12)*100)/13).'" aria-valuemin="48" aria-valuemax="100"></div>';
                                                    }
                                                    else if($staticrows[2]['VALUE'] < 20) 
                                                    {
                                                        echo '<p class="text-primary">TENSION DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="voltagedc">'.number_format($staticrows[2]['VALUE'], 1).' V</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="tensiondcwidth" style="width: '.number_format(((($staticrows[2]['VALUE']-12)*100)/13), 1).'%; height: 6px;" aria-valuenow="'.((($staticrows[2]['VALUE']-12)*100)/13).'" aria-valuemin="48" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">TENSION DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="voltagedc">'.number_format($staticrows[2]['VALUE'], 1).' V</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="tensiondcwidth" style="width: '.number_format(((($staticrows[2]['VALUE']-12)*100)/13), 1).'%; height: 6px;" aria-valuenow="'.((($staticrows[2]['VALUE']-12)*100)/13).'" aria-valuemin="48" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                                <?php
                                                    if($staticrows[0]['VALUE'] > 20 || $staticrows[0]['VALUE'] < 0)
                                                    {
                                                        echo '<p class="text-danger">COURANT DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="currentdc">'.number_format($staticrows[0]['VALUE'], 1).' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="currentdcwidth" style="width: '.number_format((($staticrows[0]['VALUE']*100)/30), 1).'%; height: 6px;" aria-valuenow="'.(($staticrows[0]['VALUE']*100)/30).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($staticrows[0]['VALUE'] > 15) 
                                                    {
                                                        echo '<p class="text-primary">COURANT DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="currentdc">'.number_format($staticrows[0]['VALUE'], 1).' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="currentdcwidth" style="width: '.number_format((($staticrows[0]['VALUE']*100)/30), 1).'%; height: 6px;" aria-valuenow="'.(($staticrows[0]['VALUE']*100)/30).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">COURANT DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="currentdc">'.number_format($staticrows[0]['VALUE'], 1).' A</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="currentdcwidth" style="width: '.number_format((($staticrows[0]['VALUE']*100)/30), 1).'%; height: 6px;" aria-valuenow="'.(($staticrows[0]['VALUE']*100)/30).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                                <?php
                                                    if($staticrows[1]['VALUE'] > 90 || $staticrows[1]['VALUE'] < 0)
                                                    {
                                                        echo '<p class="text-danger">COURANT AC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="currentac">'.number_format($staticrows[1]['VALUE'], 1).' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="currentacwidth" style="width: '.number_format(($staticrows[1]['VALUE']), 1).'%; height: 6px;" aria-valuenow="'.$staticrows[1]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($staticrows[1]['VALUE'] > 70) 
                                                    {
                                                        echo '<p class="text-primary">COURANT AC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="currentac">'.number_format($staticrows[1]['VALUE'], 1).' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="currentacwidth" style="width: '.number_format(($staticrows[1]['VALUE']), 1).'%; height: 6px;" aria-valuenow="'.$staticrows[1]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">COURANT AC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="currentac">'.number_format($staticrows[1]['VALUE'], 1).' A</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="currentacwidth" style="width: '.number_format(($staticrows[1]['VALUE']), 1).'%; height: 6px;" aria-valuenow="'.$staticrows[1]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                                <?php
                                                    if($temperature > 50)
                                                    {
                                                        echo '<p class="text-danger">TEMPÉRATURE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="temperature">'.$temperature.' °C</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="tempwidth" style="width: '.(($temperature*100)/60).'%; height: 6px;" aria-valuenow="'.(($temperature*100)/60).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($temperature > 35) 
                                                    {
                                                        echo '<p class="text-primary">TEMPÉRATURE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="temperature">'.$temperature.' °C</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="tempwidth" style="width: '.(($temperature*100)/60).'%; height: 6px;" aria-valuenow="'.(($temperature*100)/60).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">TEMPÉRATURE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="temperature">'.$temperature.' °C</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="tempwidth" style="width: '.(($temperature*100)/60).'%; height: 6px;" aria-valuenow="'.(($temperature*100)/60).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                                <h3><i class="wi wi-raindrop"></i></h3>
                                                <?php
                                                    if($staticrows[5]['VALUE'] > 90)
                                                    {
                                                        echo '<p class="text-danger">HUMIDITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="humidity">'.round($staticrows[5]['VALUE']).' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="humiditywidth" style="width: '.round($staticrows[5]['VALUE']).'%; height: 6px;" aria-valuenow="'.$staticrows[5]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($staticrows[5]['VALUE'] > 70) 
                                                    {
                                                        echo '<p class="text-primary">HUMIDITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="humidity">'.round($staticrows[5]['VALUE']).' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="humiditywidth" style="width: '.round($staticrows[5]['VALUE']).'%; height: 6px;" aria-valuenow="'.$staticrows[5]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">HUMIDITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="humidity">'.round($staticrows[5]['VALUE']).' %</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="humiditywidth" style="width: '.round($staticrows[5]['VALUE']).'%; height: 6px;" aria-valuenow="'.$staticrows[5]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                                <h3><i class="wi wi-day-sunny"></i></h3>
                                                <?php
                                                    if($staticrows[4]['VALUE'] < 15)
                                                    {
                                                        echo '<p class="text-danger">LUMINOSITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger" id="brightness">'.round($staticrows[4]['VALUE']).' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" id="brightnesswidth" style="width: '.round($staticrows[4]['VALUE']).'%; height: 6px;" aria-valuenow="'.$staticrows[4]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($staticrows[4]['VALUE'] < 30) 
                                                    {
                                                        echo '<p class="text-primary">LUMINOSITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary" id="brightness">'.round($staticrows[4]['VALUE']).' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" id="brightnesswidth" style="width: '.round($staticrows[4]['VALUE']).'%; height: 6px;" aria-valuenow="'.$staticrows[4]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">LUMINOSITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success" id="brightness">'.round($staticrows[4]['VALUE']).' %</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" id="brightnesswidth" style="width: '.round($staticrows[4]['VALUE']).'%; height: 6px;" aria-valuenow="'.$staticrows[4]['VALUE'].'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                            ?>
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
                                        <h5 class="card-title ">COURANT AC</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-info"></i> Courant AC moyenne: <?php echo getaverage($currentacrows); ?> A</li>
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
                                        <h5 class="card-title ">TEMPÉRATURE</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-danger"></i> Température moyenne: <?php echo getaverage($temprows); ?> °C</li>
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
                                        <h5 class="card-title ">HUMIDITÉ</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-cyan"></i> Humidité moyenne: <?php echo getaverage($humidityrows); ?> %</li>
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
                                        <h5 class="card-title ">LUMINOSITÉ</h5>
                                        <div class="ml-auto">
                                            <ul class="list-inline font-12">
                                                <li><i class="fa fa-circle text-warning"></i> Luminosité moyenne: <?php echo getaverage($brightnessrows); ?> %</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="morris-area-chart5" style="height: 340px;"></div>
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
        <script type="text/javascript">
            $(function () {
                "use strict";
                Morris.Area({
                    element: 'morris-area-chart'
                    , data: [{
                            period: <?php echo "'".SHM($voltagedcrows[0]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[0]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[1]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[1]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[2]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[2]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[3]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[3]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[4]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[4]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($voltagedcrows[5]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[5]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($voltagedcrows[6]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[6]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($voltagedcrows[7]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[7]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($voltagedcrows[8]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[8]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($voltagedcrows[9]['UNIXDATE'])."'"; ?>
                            , tensiondc: <?php echo $voltagedcrows[9]['VALUE']; ?>
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
                            period: <?php echo "'".SHM($currentdcrows[0]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[0]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[1]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[1]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[2]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[2]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[3]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[3]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[4]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[4]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentdcrows[5]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[5]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentdcrows[6]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[6]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[7]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[7]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[8]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[8]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentdcrows[9]['UNIXDATE'])."'"; ?>
                            , currentdc: <?php echo $currentdcrows[9]['VALUE']; ?>
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
                    element: 'morris-area-chart2'
                    , data: [{
                            period: <?php echo "'".SHM($currentacrows[0]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[0]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[1]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[1]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[2]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[2]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[3]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[3]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[4]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[4]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($currentacrows[5]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[5]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($currentacrows[6]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[6]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[7]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[7]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[8]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[8]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($currentacrows[9]['UNIXDATE'])."'"; ?>
                            , currentac: <?php echo $currentacrows[9]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['currentac']
                    , labels: ['Courant AC']
                    , parseTime: false
                    , pointSize: 3
                    , fillOpacity: 0
                    , pointStrokeColors: ['#03a9f3']
                    , behaveLikeLine: true
                    , gridLineColor: '#e0e0e0'
                    , lineWidth: 3
                    , hideHover: 'auto'
                    , lineColors: ['#03a9f3']
                    , resize: true
                });

                Morris.Area({
                    element: 'morris-area-chart3'
                    , data: [{
                            period: <?php echo "'".SHM($temprows[0]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[0]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[1]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[1]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[2]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[2]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[3]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[3]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[4]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[4]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($temprows[5]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[5]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($temprows[6]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[6]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temprows[7]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[7]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temprows[8]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[8]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($temprows[9]['UNIXDATE'])."'"; ?>
                            , temp: <?php echo $temprows[9]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['temp']
                    , labels: ['Température']
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
                    element: 'morris-area-chart4'
                    , data: [{
                            period: <?php echo "'".SHM($humidityrows[0]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[0]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[1]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[1]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[2]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[2]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[3]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[3]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[4]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[4]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($humidityrows[5]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[5]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($humidityrows[6]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[6]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityrows[7]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[7]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityrows[8]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[8]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($humidityrows[9]['UNIXDATE'])."'"; ?>
                            , humidity: <?php echo $humidityrows[9]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['humidity']
                    , labels: ['Humidité']
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
                            period: <?php echo "'".SHM($brightnessrows[0]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[0]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[1]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[1]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[2]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[2]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[3]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[3]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[4]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[4]['VALUE']; ?>
                    }, {
                            period: <?php echo "'".SHM($brightnessrows[5]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[5]['VALUE']; ?>
                    }
                        , {
                            period: <?php echo "'".SHM($brightnessrows[6]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[6]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[7]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[7]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[8]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[8]['VALUE']; ?>
                    }
                    ,{
                            period: <?php echo "'".SHM($brightnessrows[9]['UNIXDATE'])."'"; ?>
                            , brightness: <?php echo $brightnessrows[9]['VALUE']; ?>
                    }]
                    , xkey: 'period'
                    , ykeys: ['brightness']
                    , labels: ['Luminosité']
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
            }); 
            function refresh() {
            $.ajax({
                url: './updateStaticValues.php',
                type: 'post',
                dataType: "json",
                success: function (response) {
                    document.getElementById('batterie').innerHTML = response.battery + " %";
                    document.getElementById('batteriewidth').setAttribute("style", "width: " + response.batterywidth + "%; height: 6px;");
                    if(response.batterie < 20) document.getElementById('batteriewidth').setAttribute("class", "progress-bar bg-danger");
                    else if(response.batterie < 50) document.getElementById('batteriewidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.batterie >= 50) document.getElementById('batteriewidth').setAttribute("class", "progress-bar bg-success");

                    document.getElementById('voltagedc').innerHTML = response.voltagedc + " V";
                    document.getElementById('tensiondcwidth').setAttribute("style", "width: " + response.voltagedcwidth + "%; height: 6px;");
                    if(response.voltagedc < 14) document.getElementById('tensiondcwidth').setAttribute("class", "progress-bar bg-danger");
                    else if(response.voltagedc < 20) document.getElementById('tensiondcwidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.voltagedc >= 20) document.getElementById('tensiondcwidth').setAttribute("class", "progress-bar bg-success");

                    document.getElementById('currentac').innerHTML = response.currentac + " A";
                    document.getElementById('currentacwidth').setAttribute("style", "width: " + response.cacwidth + "%; height: 6px;");
                    if(response.currentac < 50) document.getElementById('currentacwidth').setAttribute("class", "progress-bar bg-success");
                    else if(response.currentac < 80) document.getElementById('currentacwidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.currentac >= 80) document.getElementById('currentacwidth').setAttribute("class", "progress-bar bg-danger");

                    document.getElementById('currentdc').innerHTML = response.currentdc + " A";
                    document.getElementById('currentdcwidth').setAttribute("style", "width: " + response.cdcwidth + "%; height: 6px;");
                    if(response.currentdc < 15) document.getElementById('currentdcwidth').setAttribute("class", "progress-bar bg-success");
                    else if(response.currentdc < 25) document.getElementById('currentdcwidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.currentdc >= 25) document.getElementById('currentdcwidth').setAttribute("class", "progress-bar bg-danger");

                    document.getElementById('temperature').innerHTML = response.temperature + " °C";
                    document.getElementById('tempwidth').setAttribute("style", "width: " + response.temwidth + "%; height: 6px;");
                    if(response.temperature < 25) document.getElementById('tempwidth').setAttribute("class", "progress-bar bg-success");
                    else if(response.temperature < 37) document.getElementById('tempwidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.temperature >= 37) document.getElementById('tempwidth').setAttribute("class", "progress-bar bg-danger");

                    document.getElementById('humidity').innerHTML = response.humidity + " %";
                    document.getElementById('humiditywidth').setAttribute("style", "width: " + response.humidtywidth + "%; height: 6px;");
                    if(response.humiditywidth < 25) document.getElementById('humiditywidth').setAttribute("class", "progress-bar bg-success");
                    else if(response.humiditywidth < 50) document.getElementById('humiditywidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.humiditywidth >= 50) document.getElementById('humiditywidth').setAttribute("class", "progress-bar bg-danger");

                    document.getElementById('brightness').innerHTML = response.brightness + " %";
                    document.getElementById('brightnesswidth').setAttribute("style", "width: " + response.brightneswidth + "%; height: 6px;");
                    if(response.brightneswidth < 25) document.getElementById('brightnesswidth').setAttribute("class", "progress-bar bg-danger");
                    else if(response.brightneswidth < 50) document.getElementById('brightnesswidth').setAttribute("class", "progress-bar bg-primary");
                    else if(response.brightneswidth >= 50) document.getElementById('brightnesswidth').setAttribute("class", "progress-bar bg-success");
                }
             });
            }
            setInterval(function(){
                refresh() 
            }, 400);
        </script>
    </body>
</html>
