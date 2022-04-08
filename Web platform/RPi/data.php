<?php
    $mysqli = new mysqli("localhost", "root", "", "PFE");
 
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $query = 'SELECT * FROM `SENSORS` WHERE `ID` = '.$page.' ORDER BY `UNIXDATE` ASC LIMIT 10';
    $result = $mysqli->query($query);
    $total = array();
    while($row = $result->fetch_assoc()) {
        $total[] = $row;
    }
    $result->free();
    $mysqli->close();

    function SHM($seconds)
    {

        $days = floor($seconds/86400);
        $hrs = floor($seconds / 3600);
        $mins = intval(($seconds / 60) % 60); 
        $sec = intval($seconds % 60);

        if($days>0){
            $hrs = str_pad($hrs,2,'0',STR_PAD_LEFT);
            $hours = $hrs-($days*24);
            $return_days = $days." Days ";
            $hrs = str_pad($hours,2,'0',STR_PAD_LEFT);
        }else{
            $return_days="";
            $hrs = str_pad($hrs,2,'0',STR_PAD_LEFT);
        }

        $mins = str_pad($mins,2,'0',STR_PAD_LEFT);
        $sec = str_pad($sec,2,'0',STR_PAD_LEFT);

        return $hrs.":".$mins;
    }

    $tjan = "'".SHM($total[0]['UNIXDATE'])."'";
    $tfeb = "'".SHM($total[1]['UNIXDATE'])."'";
    $tmar = "'".SHM($total[2]['UNIXDATE'])."'";
    $tapr = "'".SHM($total[3]['UNIXDATE'])."'";
    $tmay = "'".SHM($total[4]['UNIXDATE'])."'";
    $tjun = "'".SHM($total[5]['UNIXDATE'])."'";
    $tjul = "'".SHM($total[6]['UNIXDATE'])."'";
    $taug = "'".SHM($total[7]['UNIXDATE'])."'";
    $tsep = "'".SHM($total[8]['UNIXDATE'])."'";
    $toct = "'".SHM($total[9]['UNIXDATE'])."'";
    $pjan = $total[0]['VALUE'];
    $pfeb = $total[1]['VALUE'];
    $pmar = $total[2]['VALUE'];
    $papr = $total[3]['VALUE'];
    $pmay = $total[4]['VALUE'];
    $pjun = $total[5]['VALUE'];
    $pjul = $total[6]['VALUE'];
    $paug = $total[7]['VALUE'];
    $psep = $total[8]['VALUE'];
    $poct = $total[9]['VALUE'];
?>