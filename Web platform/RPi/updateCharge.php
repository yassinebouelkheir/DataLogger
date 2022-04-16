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
   ScriptName    : updateChange.php
   Author        : BOUELKHEIR Yassine
   Version       : 1.0
   Created       : 18/03/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
-->

<?php
    session_start();
    if(!isset($_SESSION["username"])) 
    {
        header("Location: login.php");
        exit();
    }
    
    if (isset($_GET['chargeid']) && isset($_GET['value'])) 
    {
        $mysqli = new mysqli("localhost", "root", "", "PFE");
        $chargeid = stripslashes($_GET['chargeid']);
        $chargeid = mysqli_real_escape_string($mysqli, $chargeid);

        $chargevalue = stripslashes($_GET['value']);
        $chargevalue = mysqli_real_escape_string($mysqli, $chargevalue);

        $query = 'UPDATE `CHARGES` SET `VALUE` = '.$chargevalue.' WHERE `ID` = '.$chargeid;
        $mysqli->query($query) or die($mysqli->error);
	$mysqli->close();
    }
    header("Location: index.php");
?>
