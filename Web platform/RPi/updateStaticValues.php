<?php
    if(isset($_GET["page"]))
    {
      $page = $_GET["page"];
      $page += $val;
      if($page < 54) $page = 62;
      if($page > 62) $page = 54;
    }
    else
    {
      $page = 54;
    }

    $mysqli = new mysqli("localhost", "root", "", "PFE");
    if($page == 60) $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 58 LIMIT 1';
    else if($page == 61) $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 55 LIMIT 1';
    else if($page == 62) $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 56 LIMIT 1';
    else $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = '.$page.' LIMIT 1';

    $result = $mysqli->query($query);
    $rows = array();
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->free();

    if($page == 60) $rows[0]['VALUE'] = number_format(((pow((($rows[0]['VALUE']*1023)/100),2)/10)/(50)), 1);
    if($page == 61) $rows[0]['VALUE'] = number_format(($rows[0]['VALUE']*220), 1);
    if($page == 62){
      $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 54 LIMIT 1';
      $result = $mysqli->query($query);
      $rows1 = array();
      while($row = $result->fetch_assoc()) {
        $rows1[] = $row;
      }
      $result->free();
      $rows[0]['VALUE'] = ($rows[0]['VALUE']*$rows1[0]['VALUE']);
    }
    $rows[0]['VALUE'];
    switch($page)
    {
      case 54:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' A';
        break;
      }
      case 55:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' A';
        break;
      }
      case 56:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' V';
        break;
      }
      case 57:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' °C';
        break;
      }
      case 58:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' %';
        break;
      }
      case 59:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' %';
        break;
      }
      case 60:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' W/m²';
        break;
      }
      case 61:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' W';
        break;
      }
      case 62:{
        $rows[0]['VALUE'] = $rows[0]['VALUE'].' W';
        break;
      }
    }
    $data['value'] = $rows[0]['VALUE'];
    $mysqli->close();
    echo json_encode($data);
?>