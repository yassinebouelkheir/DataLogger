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
    
    require('classes/PHPExcel.php');

    function getName($pin)
    {
        if($pin == 54) return 'Courant DC';
        if($pin == 55) return 'Courant AC'; 
        if($pin == 56) return 'Tension DC'; 
        if($pin == 57) return 'Température'; 
        if($pin == 58) return 'Luminosité'; 
        if($pin == 59) return 'Humidité'; 
    } 

    if (isset($_GET['interval'])) 
    {
        $mysqli = new mysqli("localhost", "root", "", "PFE");
        $interval = stripslashes($_GET['interval']);
        $interval = mysqli_real_escape_string($mysqli, $interval);

        $objPHPExcel = PHPExcel_IOFactory::load('assets/exemple.xlsx');
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setCellValue('H8', strftime('%d/%m/%Y').' '.strftime('%H:%M'));

        if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` ASC';
        else $query = 'SELECT * FROM `SENSORS` WHERE 1 ORDER BY `UNIXDATE` ASC';
        $result = $mysqli->query($query) or die($mysqli->error);
        $rows = array();
        $k = 0;
        $i = 0;

        while($row = $result->fetch_assoc()) 
        {
            $objPHPExcel->getActiveSheet()->mergeCells('B'.(11+$i).':C'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('D'.(11+$i).':E'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('F'.(11+$i).':G'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('H'.(11+$i).':I'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('J'.(11+$i).':K'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('L'.(11+$i).':M'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('N'.(11+$i).':O'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->getRowDimension(''.(11+$i).'')->setRowHeight(21);

            $objPHPExcel->getActiveSheet()->getStyle('B'.(11+$i).':O'.(11+$i).'')->applyFromArray(array('borders' => array (
                  'allborders' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                    'color' => array('rgb' => '000000'),
                  )
                )
              )
            );
            $objPHPExcel->getActiveSheet()->getStyle('B'.(11+$i).':O'.(11+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.(11+$i).':O'.(11+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            if($row['ID'] == 56) $objPHPExcel->getActiveSheet()->setCellValue('D'.(11+$i).'', ''.$row['VALUE'].'');
            if($row['ID'] == 54) $objPHPExcel->getActiveSheet()->setCellValue('F'.(11+$i).'', ''.$row['VALUE'].'');
            if($row['ID'] == 55) $objPHPExcel->getActiveSheet()->setCellValue('H'.(11+$i).'', ''.$row['VALUE'].'');
            if($row['ID'] == 57) $objPHPExcel->getActiveSheet()->setCellValue('J'.(11+$i).'', ''.$row['VALUE'].'');
            if($row['ID'] == 58) $objPHPExcel->getActiveSheet()->setCellValue('L'.(11+$i).'', ''.$row['VALUE'].'');
            if($row['ID'] == 59) $objPHPExcel->getActiveSheet()->setCellValue('N'.(11+$i).'', ''.$row['VALUE'].'');

            if($k == 0) $objPHPExcel->getActiveSheet()->setCellValue('B'.(11+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');

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
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="DataLogger_'.strftime('%d-%m-%Y').'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }
?>
