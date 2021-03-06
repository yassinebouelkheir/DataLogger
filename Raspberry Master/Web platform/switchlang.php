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
   ScriptName    : switchlang.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 22/05/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine 
-->

<?php
    //error_reporting(0);
    session_start();
    if(!isset($_SESSION["username"])) 
    {
      header("Location: index.php");
      exit();
    }
    if(!empty($_GET["page"]))
    {
        switch($_GET["lang"])
        {
            case 0:
            {
                $_SESSION["language"] = 0;
                header("Location: /fr/".$_GET["page"].".php");
                exit(); 
            }
            case 1:
            {
                $_SESSION["language"] = 1;
                header("Location: /en/".$_GET["page"].".php");
                exit(); 
            }
        }
    }
?>
