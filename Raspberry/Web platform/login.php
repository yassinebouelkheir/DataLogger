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
   ScriptName    : login.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 18/03/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine 
-->

<!DOCTYPE html>
<html lang="en">
    <?php
        error_reporting(0);
        session_start();
        if(isset($_SESSION["username"])) 
        {
            header("Location: index.php");
            exit();
        }

        $BadInfo = 0;
        if (isset($_POST['username'])) 
        {
            $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");
            $username = stripslashes($_REQUEST['username']);
            $username = mysqli_real_escape_string($mysqli, $username);

            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($mysqli, $password);
            
            $query = "SELECT * FROM `ACCOUNTS` WHERE username='$username' AND password='" . md5($password) . "' LIMIT 1";
            $result = $mysqli->query($query) or die($mysqli->error);
            $rows = mysqli_num_rows($result);
            if ($rows != 0) 
            {
                while($row = $result->fetch_assoc()) {
                    $_SESSION["P1"] = $row['P1'];
                    $_SESSION["P2"] = $row['P2'];
                    $_SESSION["P3"] = $row['P3'];
                    $_SESSION["P4"] = $row['P4'];
                    $_SESSION["P5"] = $row['P5'];
                }
                $BadInfo = 0;
                $_SESSION['username'] = $username;
                $_SESSION['LAST_ACTIVITY'] = time();
                
                $LocationArray = json_decode(file_get_contents('http://ip-get-geolocation.com/api/json/'.$_SERVER['REMOTE_ADDR']), true);
                $result->close();

                if(!empty($LocationArray['isp'])) $query = "INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `ISP`, `VALUE`) VALUES ('".$_SESSION['username']."',
            '".$_SERVER['REMOTE_ADDR']."', 0, '".$LocationArray['isp']."', '".$LocationArray['city'].", ".$LocationArray['country']."')";
                 else $query = "INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `ISP`, `VALUE`) VALUES ('".$_SESSION['username']."', '".$_SERVER['REMOTE_ADDR']."', 0, 'Local', 'Local')";

                $mysqli->query($query) or die($mysqli->error);
                header("Location: index.php");
            } 
            else 
            {
                $BadInfo = 1;
            }
            $mysqli->close();
        }
    ?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <title>Data logger - Login</title>
        <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
        <link href="dist/css/style.min.css" rel="stylesheet">
    </head>
    <body class="skin-default card-no-border" oncontextmenu="return false">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Data logger</p>
            </div>
        </div>
        <section id="wrapper">
            <div class="login-register" style="background-image:url(../assets/images/login.png);">
                <br>
                <div class="login-box card"><br>
                    <img src="../assets/images/logo.png" alt="user-img" height="130" width="285" style="margin-left: 55px;"></img>
                    <div class="card-body">
                        <form class="form-horizontal form-material" id="loginform" method="post">
                            <br>
                            <h3 class="text-center m-b-20">Connectez-vous</h3>
                            <br>
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" required="" id="username" name="username" placeholder=" Nom d'utilisateur"> </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input class="form-control" type="password" required="" id="password" name="password" placeholder=" Mot de passe"> </div>
                            </div>
                            <br>
                            <?php 
                                if($BadInfo == 1) echo '<h6 class="text-center m-b-20 text-danger">Votre informations de connexion sont incorrect.</h6>';
                            ?>
                            <br>
                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20">
                                    <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit">Connexion</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <script src="../assets/node_modules/popper/popper.min.js"></script>
        <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(function() {
                $(".preloader").fadeOut();
            });
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });
            $('#to-recover').on("click", function() {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });
        </script>
    </body>
</html>
