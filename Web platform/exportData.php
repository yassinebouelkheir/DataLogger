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
   ScriptName    : exportData.php
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
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
    
    require('classes/PHPExcel.php');

    function getName($pin)
    {
        if($pin == 1) return 'Courant DC';
        if($pin == 2) return 'Tension DC';
        if($pin == 3) return 'Courant AC';  
        if($pin == 4) return 'Tension AC'; 
        if($pin == 5) return 'Température Ambiante';
        if($pin == 6) return 'Température du panneau'; 
        if($pin == 7) return 'Luminosité'; 
        if($pin == 8) return 'Humidité'; 
        if($pin == 9) return 'Vitesse du vent'; 
    } 

    if (isset($_GET['interval'])) 
    {
        $mysqli = new mysqli("localhost", "root", "", "PFE");

        $query = 'SELECT * FROM `EXPORTATIONTYPE` WHERE 1 LIMIT 1';
        $result = $mysqli->query($query) or die($mysqli->error);
        $exporttyperows = array();
        while($row = $result->fetch_assoc()) {
            $exporttyperows[] = $row;
        }
        $result->free();
        if($exporttyperows[0]['TYPE'] == 0)
        {
          echo '<html>
                  <head>
                    <meta http-equiv="refresh" content="2; url=index.php"/>
                  </head>
                  <body>
                    <h1>Exportation des données est désactivé pour le moment.</h1>
                    <h2>Merci de contacter votre administrateur pour tout information.</h2>
                  </body>
                </html>';
          die();
        }

        $interval = stripslashes($_GET['interval']);
        $interval = mysqli_real_escape_string($mysqli, $interval);

        $objPHPExcel = PHPExcel_IOFactory::load('assets/exemple.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setCellValue('I10', strftime('%d/%m/%Y').' '.strftime('%H:%M'));

        if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` ASC';
        else $query = 'SELECT * FROM `SENSORS` WHERE 1 ORDER BY `UNIXDATE` ASC';
        $result = $mysqli->query($query) or die($mysqli->error);
        $rows = array();
        $k = 0;
        $i = 0;
        $lastvalueVoltage = -1;
        $lastvalueCurrent = -1;
        $lastvalueCurrentAC = -1;
        while($row = $result->fetch_assoc()) 
        {
            $objPHPExcel->getActiveSheet()->mergeCells('B'.(13+$i).':C'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('D'.(13+$i).':E'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('F'.(13+$i).':G'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('H'.(13+$i).':I'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('J'.(13+$i).':K'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('L'.(13+$i).':M'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('N'.(13+$i).':O'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('P'.(13+$i).':Q'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('R'.(13+$i).':S'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('T'.(13+$i).':U'.(13+$i).'');
            $objPHPExcel->getActiveSheet()->getRowDimension(''.(13+$i).'')->setRowHeight(21);

            $objPHPExcel->getActiveSheet()->getStyle('B'.(13+$i).':U'.(13+$i).'')->applyFromArray(array('borders' => array (
                  'allborders' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                    'color' => array('rgb' => '000000'),
                  )
                )
              )
            );
            $objPHPExcel->getActiveSheet()->getStyle('B'.(13+$i).':U'.(13+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.(13+$i).':U'.(13+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            if($row['ID'] == 56) { $objPHPExcel->getActiveSheet()->setCellValue('D'.(13+$i).'', ''.$row['VALUE'].' V'); $lastvalueVoltage = $row['VALUE']; }
            if($row['ID'] == 54) { $objPHPExcel->getActiveSheet()->setCellValue('F'.(13+$i).'', ''.$row['VALUE'].' A'); $lastvalueCurrent = $row['VALUE']; }
            if($row['ID'] == 55) { $objPHPExcel->getActiveSheet()->setCellValue('J'.(13+$i).'', ''.$row['VALUE'].' A'); $lastvalueCurrentAC = $row['VALUE']; }
            if($row['ID'] == 57) $objPHPExcel->getActiveSheet()->setCellValue('N'.(13+$i).'', ''.$row['VALUE'].' °C');
            if($row['ID'] == 58) { 
              $objPHPExcel->getActiveSheet()->setCellValue('P'.(13+$i).'', ''.$row['VALUE'].' %');
              $objPHPExcel->getActiveSheet()->setCellValue('R'.(13+$i).'', ''.number_format(((pow((($row['VALUE']*1023)/100),2)/10)/(50)), 2).' W/m²');
            }
            if($row['ID'] == 59) $objPHPExcel->getActiveSheet()->setCellValue('T'.(13+$i).'', ''.$row['VALUE'].' %');

            if($lastvalueVoltage != -1 && $lastvalueCurrent != -1) {
              $objPHPExcel->getActiveSheet()->setCellValue('H'.(13+$i).'', ''.number_format(($lastvalueVoltage*$lastvalueCurrent), 2) .' W');
              $lastvalueVoltage = -1;
              $lastvalueCurrent = -1;
            }

            if($lastvalueCurrentAC != -1) {
              $objPHPExcel->getActiveSheet()->setCellValue('L'.(13+$i).'', ''.number_format(($lastvalueCurrentAC*220), 2).' W');
              $lastvalueCurrentAC = -1;
            }

            if($k == 0) $objPHPExcel->getActiveSheet()->setCellValue('B'.(13+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');

            $k += 1;
            if($k == 6) 
            {
              $k = 0;
              $i += 1;
            }
        }
        
        $result->free();
        $mysqli->close();

        for($j = 0; $j < 300; $j++)
        {
            $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':BZ'.$j.'')->applyFromArray(
            array(
                'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => 'FFFFFF')
                ),
            ));
        }

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(1.9);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.6);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.6);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1.9);

        if($exporttyperows[0]['TYPE'] == 1)
        {
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="DataLogger_'.strftime('%d-%m-%Y').'.xlsx"');
          header('Cache-Control: max-age=0');
          $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
          $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
          ob_end_clean();
          $writer->save('php://output');
        }
        else if($exporttyperows[0]['TYPE'] == 2)
        {
          echo "PDF Test";
        }
        exit();
    }
?>
