<?php
    error_reporting(0);

    function map($x, $in_min, $in_max, $out_min, $out_max) {
        return ($x - $in_min) * ($out_max - $out_min) / ($in_max - $in_min) + $out_min;
    }
    $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE"); 
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

    $data['currentdc'] = number_format($staticrows[0]['VALUE'], 2);
    $data['cdcwidth'] = (($staticrows[0]['VALUE']*100)/30);

    $data['voltagedc'] = number_format($staticrows[1]['VALUE'], 1);
    $data['voltagedcwidth'] = ((($staticrows[1]['VALUE']-12)*100)/13);

    $data['currentac'] = number_format($staticrows[2]['VALUE'], 2);
    $data['cacwidth'] = $staticrows[2]['VALUE'];

    $data['voltageac'] = number_format($staticrows[3]['VALUE'], 1);
    $data['voltageacidth'] = (($staticrows[3]['VALUE']*100)/230);

    $data['temperature'] = number_format($temperature, 0);
    $data['tempwidth'] = (($temperature*100)/60);

    $data['temperature1'] = number_format($temperature1, 0);
    $data['temp1width'] = (($temperature1*100)/60);

    $data['brightness'] = number_format(round($staticrows[6]['VALUE']), 0);
    $data['brightneswidth'] = 1000-$staticrows[6]['VALUE'];

    $data['humidity'] = number_format(round(($staticrows[7]['VALUE']*100/1023)), 0);
    $data['humidtywidth'] = number_format(round(($staticrows[7]['VALUE']*100/1023)), 0);

    $data['windspeed'] = number_format(round($staticrows[8]['VALUE']), 0);
    $data['windspeedwidth'] = round((($staticrows[8]['VALUE'])*100)/40);

    $data['windspeedinv'] = number_format(round($staticrows[9]['VALUE']), 0);
    $data['windspeedinvwidth'] = round((($staticrows[9]['VALUE'])*100)/40);

    $data['turbine'] = number_format(round($staticrows[10]['VALUE']), 0);
    $data['turbinewidth'] = round(($staticrows[10]['VALUE']));

    $data['ecurrentdc'] = number_format($staticrows[11]['VALUE'], 2);
    $data['ecdcwidth'] = $staticrows[11]['VALUE'];

    $data['evoltagedc'] = number_format($staticrows[12]['VALUE'], 1);
    $data['evoltagedcwidth'] = ((($staticrows[12]['VALUE']-12)*100)/13);

    $data['tempext'] = number_format($staticrows[13]['VALUE'], 0);
    $data['tempextwidth'] = (($staticrows[13]['VALUE']*100)/60);

    $data['tempint'] = number_format($staticrows[14]['VALUE'], 0);
    $data['tempintwidth'] = (($staticrows[14]['VALUE']*100)/60);

    $data['humidityint'] = number_format($staticrows[15]['VALUE'], 0);
    $data['humidityintwidth'] = $staticrows[15]['VALUE']

    $data['co2level'] = number_format($staticrows[16]['VALUE'], 0);
    $data['co2levelwidth'] = map($staticrows[16]['VALUE'], 0, 2000, 0, 100);

    $data['gauzeslevel'] = number_format($staticrows[17]['VALUE'], 0);
    $data['gauzeslevelwidth'] = map($staticrows[17]['VALUE'], 0, 2000, 0, 100);

    $data['luminousflow'] = number_format(round(((2500/((512-$staticrows[18]['VALUE'])*0.0048828125)-500)/10)), 0);
    $data['luminousflowwidth'] = map($staticrows[18]['VALUE'], 0, 512, 0, 100);

    $data['lights'] = number_format(round($staticrows[19]['VALUE']), 0);
    $data['exteriorDoor'] = number_format(round($staticrows[20]['VALUE']), 0);
    $data['interiorWindow'] = number_format(round($staticrows[21]['VALUE']), 0);
    $data['ac'] = number_format(round($staticrows[22]['VALUE']), 0);
    $data['acfan'] = number_format(round($staticrows[23]['VALUE']), 0);
    $data['extractor'] = number_format(round($staticrows[24]['VALUE']), 0);
    $data['movement'] = number_format(round($staticrows[25]['VALUE']), 0);

	echo json_encode($data);
?>
