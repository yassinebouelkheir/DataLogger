<!DOCTYPE html>
<html lang="en">
<?php
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
        
        $query = "SELECT * FROM `ACCOUNTS` WHERE username='$username' AND password='" . md5($password) . "'";
        $result = $mysqli->query($query) or die($mysqli->error);
        $rows = mysqli_num_rows($result);
	    $mysqli->close();
        if ($rows != 0) 
        {
            $BadInfo = 0;
            $_SESSION['username'] = $username;
            header("Location: index.php");
        } 
        else 
        {
            $BadInfo = 1;
        }
    }
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Data logger - Login</title>
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
</head>

<body class="skin-default card-no-border">
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