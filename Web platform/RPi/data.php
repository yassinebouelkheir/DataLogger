<?php
    $mysqli = new mysqli("localhost", "root", "", "PFE");
 
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    if($page == 60) $query = 'SELECT * FROM `SENSORS` WHERE `ID` = 58 ORDER BY `UNIXDATE` ASC LIMIT 10';
    else $query = 'SELECT * FROM `SENSORS` WHERE `ID` = '.$page.' ORDER BY `UNIXDATE` ASC LIMIT 10';
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

    if($page == 60)
    {
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
        $pjan = ((pow((($total[0]['VALUE']*1023)/100),2)/10)/(50));
        $pfeb = ((pow((($total[1]['VALUE']*1023)/100),2)/10)/(50));
        $pmar = ((pow((($total[2]['VALUE']*1023)/100),2)/10)/(50));
        $papr = ((pow((($total[3]['VALUE']*1023)/100),2)/10)/(50));
        $pmay = ((pow((($total[4]['VALUE']*1023)/100),2)/10)/(50));
        $pjun = ((pow((($total[5]['VALUE']*1023)/100),2)/10)/(50));
        $pjul = ((pow((($total[6]['VALUE']*1023)/100),2)/10)/(50));
        $paug = ((pow((($total[7]['VALUE']*1023)/100),2)/10)/(50));
        $psep = ((pow((($total[8]['VALUE']*1023)/100),2)/10)/(50));
        $poct = ((pow((($total[9]['VALUE']*1023)/100),2)/10)/(50));
    }
    else
    { 
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
    }
?>