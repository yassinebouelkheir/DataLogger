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
   ScriptName    : updateStaticValues.php
   Author        : BOUELKHEIR Yassine
   Version       : 1.0
   Created       : 18/03/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
-->

<?php

    $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE"); 
    $query = 'SELECT * FROM `SENSORS_STATIC` WHERE 1 ORDER BY `ID` ASC';
    $result = $mysqli->query($query);
    $staticrows = array();
    while($row = $result->fetch_assoc()) {
        $staticrows[] = $row;
    }
    $result->free();
    $mysqli->close();

    $batterie = round((($staticrows[2]['VALUE']-12)*100)/13);
    if($batterie < 0) $batterie = 0;
    $temperature = round($staticrows[3]['VALUE']);

    $data['battery'] = $batterie;
    $data['batterywidth'] = $batterie;

    $data['voltagedc'] = $staticrows[2]['VALUE'];
    $data['voltagedcwidth'] = (($staticrows[2]['VALUE']-12)*100);

    $data['currentac'] = number_format($staticrows[1]['VALUE'], 1);
    $data['cacwidth'] = $staticrows[1]['VALUE'];

    $data['currentdc'] = number_format($staticrows[0]['VALUE'], 1);
    $data['cdcwidth'] = (($staticrows[0]['VALUE']*100)/30);

    $data['temperature'] = $temperature;
    $data['temwidth'] = (($temperature*100)/60);

    $data['brightness'] = round($staticrows[4]['VALUE']);
    $data['brightneswidth'] = $staticrows[4]['VALUE'];

    $data['humidity'] = round($staticrows[5]['VALUE']);
    $data['humidtywidth'] = $staticrows[5]['VALUE'];
	echo json_encode($data);

?>