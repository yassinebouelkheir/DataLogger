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

    $batterie = round((($staticrows[1]['VALUE']-12)*100)/13);
    if($batterie < 0) $batterie = 0;

    $temperature = round($staticrows[4]['VALUE']);
    $temperature1 = round($staticrows[5]['VALUE']);

    $data['battery'] = $batterie;
    $data['batterywidth'] = $batterie;

    $data['currentdc'] = number_format($staticrows[0]['VALUE'], 1);
    $data['cdcwidth'] = (($staticrows[0]['VALUE']*100)/30);

    $data['voltagedc'] = number_format($staticrows[1]['VALUE'], 1);
    $data['voltagedcwidth'] = ((($staticrows[1]['VALUE']-12)*100)/13);

    $data['currentac'] = number_format($staticrows[2]['VALUE'], 1);
    $data['cacwidth'] = $staticrows[2]['VALUE'];

    $data['temperature'] = number_format($temperature, 0);
    $data['tempwidth'] = (($temperature*100)/60);

    $data['temperature1'] = number_format($temperature1, 0);
    $data['temp1width'] = (($temperature1*100)/60);

    $data['brightness'] = number_format(round($staticrows[6]['VALUE']), 0);
    $data['brightneswidth'] = $staticrows[6]['VALUE'];

    $data['humidity'] = number_format(round($staticrows[7]['VALUE']), 0);
    $data['humidtywidth'] = $staticrows[7]['VALUE'];

    $data['windspeed'] = number_format(round($staticrows[8]['VALUE']), 0);
    $data['windspeedwidth'] = round((($staticrows[8]['VALUE'])*100)/40);
	echo json_encode($data);

?>