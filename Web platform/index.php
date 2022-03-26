<!DOCTYPE html>
<html lang="en">
    <?php
        $jsonurl = "https://api.openweathermap.org/data/2.5/weather?lat=34.0337&lon=6.7708&lang=fr&appid=36a1abfb8868c3cc0784a4953f738e70";
        $json = file_get_contents($jsonurl);

        $weather = json_decode($json);
        $kelvin = $weather->main->temp;
        $celcius = $kelvin - 273.15;
        $skystats = $weather->weather[0]->description;
        $skystats1 = mb_strtoupper($skystats);
        
        $batterie = 60;
        $tensiondc = 14.5;
        $courantdc = 15.2;
        $courantac = 53.8;
        $temperature = 51;
        $humidity = 59;
        $brightness = 29;
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
                            <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                            <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        </ul>

                        <ul class="navbar-nav my-lg-0">
                            <li class="nav-item dropdown u-pro">
                                <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="../assets/images/users/1.jpg" alt="user" class=""> <span class="hidden-md-down">Administrateur &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                                <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                    <a href="login.html" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
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
                                <img src="../assets/images/logo.png" alt="user-img" height="105" width="215">
                            </li>
                            <li class="nav-small-cap">--- MAIN MENU</li>
                            <li> <a class="waves-effect waves-dark active" href="javascript:void(0)" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Statistiques</span></a>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Charges <span class="badge badge-pill badge-cyan ml-auto">4</span></span></a>
                            </li>
                            <li> <a class="waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fa fa-power-off"></i><span class="hide-menu">Logout</span></a>
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
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active">Statistiques</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card bg-cyan text-white">
                                <div class="card-body ">
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
                                                echo '<b class="text-white">'.$skystats1.'</b>';
                                                echo '<p class="op-5">'.date('j F Y h:i').'</p>';
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
                                                        echo '<h2 class="counter text-danger">'.$batterie.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.$batterie.'%; height: 6px;" aria-valuenow="'.$batterie.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($batterie < 40) 
                                                    {
                                                        echo '<p class="text-primary">BATTERIE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$batterie.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.$batterie.'%; height: 6px;" aria-valuenow="'.$batterie.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">BATTERIE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$batterie.' %</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.$batterie.'%; height: 6px;" aria-valuenow="'.$batterie.'" aria-valuemin="0" aria-valuemax="100"></div>';
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
                                                    if($tensiondc < 15)
                                                    {
                                                        echo '<p class="text-danger">TENSION DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger">'.$tensiondc.' V</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.((($tensiondc-12)*100)/13).'%; height: 6px;" aria-valuenow="'.((($tensiondc-12)*100)/13).'" aria-valuemin="48" aria-valuemax="100"></div>';
                                                    }
                                                    else if($tensiondc < 20) 
                                                    {
                                                        echo '<p class="text-primary">TENSION DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$tensiondc.' V</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.((($tensiondc-12)*100)/13).'%; height: 6px;" aria-valuenow="'.((($tensiondc-12)*100)/13).'" aria-valuemin="48" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">TENSION DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$tensiondc.' V</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.((($tensiondc-12)*100)/13).'%; height: 6px;" aria-valuenow="'.((($tensiondc-12)*100)/13).'" aria-valuemin="48" aria-valuemax="100"></div>';
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
                                                    if($courantdc > 20)
                                                    {
                                                        echo '<p class="text-danger">COURANT DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger">'.$courantdc.' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.(($courantdc*100)/30).'%; height: 6px;" aria-valuenow="'.(($courantdc*100)/30).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($courantdc > 15) 
                                                    {
                                                        echo '<p class="text-primary">COURANT DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$courantdc.' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.(($courantdc*100)/30).'%; height: 6px;" aria-valuenow="'.(($courantdc*100)/30).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">COURANT DC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$courantdc.' A</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.(($courantdc*100)/30).'%; height: 6px;" aria-valuenow="'.(($courantdc*100)/30).'" aria-valuemin="0" aria-valuemax="100"></div>';
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
                                                    if($courantac > 90)
                                                    {
                                                        echo '<p class="text-danger">COURANT AC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger">'.$courantac.' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.($courantac).'%; height: 6px;" aria-valuenow="'.$courantac.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($courantac > 70) 
                                                    {
                                                        echo '<p class="text-primary">COURANT AC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$courantac.' A</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.($courantac).'%; height: 6px;" aria-valuenow="'.$courantac.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">COURANT AC</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$courantac.' A</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.($courantac).'%; height: 6px;" aria-valuenow="'.$courantac.'" aria-valuemin="0" aria-valuemax="100"></div>';
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
                                                        echo '<h2 class="counter text-danger">'.$temperature.' °C</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.(($temperature*100)/60).'%; height: 6px;" aria-valuenow="'.(($temperature*100)/60).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($temperature > 35) 
                                                    {
                                                        echo '<p class="text-primary">TEMPÉRATURE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$temperature.' °C</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.(($temperature*100)/60).'%; height: 6px;" aria-valuenow="'.(($temperature*100)/60).'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">TEMPÉRATURE</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$temperature.' °C</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.(($temperature*100)/60).'%; height: 6px;" aria-valuenow="'.(($temperature*100)/60).'" aria-valuemin="0" aria-valuemax="100"></div>';
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
                                                    if($humidity > 90)
                                                    {
                                                        echo '<p class="text-danger">HUMIDITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger">'.$humidity.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.($humidity).'%; height: 6px;" aria-valuenow="'.$humidity.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($humidity > 70) 
                                                    {
                                                        echo '<p class="text-primary">HUMIDITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$humidity.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.($humidity).'%; height: 6px;" aria-valuenow="'.$humidity.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">HUMIDITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$humidity.' %</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.($humidity).'%; height: 6px;" aria-valuenow="'.$humidity.'" aria-valuemin="0" aria-valuemax="100"></div>';
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
                                                    if($brightness < 15)
                                                    {
                                                        echo '<p class="text-danger">LUMINOSITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-danger">'.$brightness.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-danger" role="progressbar" style="width: '.($brightness).'%; height: 6px;" aria-valuenow="'.$brightness.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else if($brightness < 30) 
                                                    {
                                                        echo '<p class="text-primary">LUMINOSITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-primary">'.$brightness.' %</h2>';

                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-primary" role="progressbar" style="width: '.($brightness).'%; height: 6px;" aria-valuenow="'.$brightness.'" aria-valuemin="0" aria-valuemax="100"></div>';
                                                    }
                                                    else 
                                                    {
                                                        echo '<p class="text-success">LUMINOSITÉ</p>';
                                                        echo '</div>';
                                                        echo '<div class="ml-auto">';
                                                        echo '<h2 class="counter text-success">'.$brightness.' %</h2>';
                                                
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                        echo '<div class="col-12">';
                                                        echo '<div class="progress">';
                                                        echo '<div class="progress-bar bg-success" role="progressbar" style="width: '.($brightness).'%; height: 6px;" aria-valuenow="'.$brightness.'" aria-valuemin="0" aria-valuemax="100"></div>';
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
                                                <li><i class="fa fa-circle text-purple"></i> Tension moyenne</li>
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
                                                <li><i class="fa fa-circle text-primary"></i> Courant moyenne</li>
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
                                                <li><i class="fa fa-circle text-info"></i> Courant efficace</li>
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
                                                <li><i class="fa fa-circle text-danger"></i> Température</li>
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
                                                <li><i class="fa fa-circle text-cyan"></i> Humidité</li>
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
                                                <li><i class="fa fa-circle text-warning"></i> Luminosité</li>
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
        <script src="dist/js/dashboard1.js"></script>
        <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>
    </body>
</html>