<?php

    $mysqli = new mysqli("localhost", "root", "", "PFE"); 
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
    $data['voltagedcwidth'] = ((($staticrows[2]['VALUE']-12)*100)/13);

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