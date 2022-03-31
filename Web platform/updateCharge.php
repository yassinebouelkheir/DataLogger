<?php
    session_start();
    if(!isset($_SESSION["username"])) 
    {
        header("Location: login.php");
        exit();
    }
    
    if (isset($_GET['chargeid']) && isset($_GET['value'])) 
    {
        $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");
        $chargeid = stripslashes($_GET['chargeid']);
        $chargeid = mysqli_real_escape_string($mysqli, $chargeid);

        $chargevalue = stripslashes($_GET['value']);
        $chargevalue = mysqli_real_escape_string($mysqli, $chargevalue);

        $query = 'UPDATE `CHARGES` SET `VALUE` = '.$chargevalue.' WHERE `ID` = '.$chargeid;
        $mysqli->query($query) or die($mysqli->error);
	$mysqli->close();
    }
    header("Location: charges.php");
?>
