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
   Developers    : BOUELKHEIR Yassine 
-->

<?php
    error_reporting(0);
    session_start();
    if(!isset($_SESSION["username"]) || !$_SESSION["P4"]) 
    {
        header("Location: login.php");
        exit();
    }
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600))
    { 
        session_regenerate_id(true);
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    require('../classes/PHPExcel.php');
    require_once '../classes/PHPExcel/IOFactory.php';

    if (isset($_GET['interval'])) 
    {
        $mysqli = new mysqli("localhost", "adminpi", "adminpi", "PFE");

        $interval = stripslashes($_GET['interval']);
        $interval = mysqli_real_escape_string($mysqli, $interval);

        $type = stripslashes($_GET['type']);

        switch($_GET['lang'])
        {
            case 1: 
            {
                if($type < 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/en/assets/exemple_'.$type.'.xlsx');
                else if($type == 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/en/exemple_1.xlsx');
                else if($type > 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/en/exemple_'.($type-1).'.xlsx');
                break;
            }
            case 2:
            {
                if($type < 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/fr/assets/exemple_'.$type.'.xlsx');
                else if($type == 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/fr/exemple_1.xlsx');
                else if($type > 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/fr/exemple_'.($type-1).'.xlsx');
                break;
            }
            case 3: 
            {
                if($type < 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/es/assets/exemple_'.$type.'.xlsx');
                else if($type == 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/es/exemple_1.xlsx');
                else if($type > 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/es/exemple_'.($type-1).'.xlsx');
                break;
            }
            case 4: 
            {
                if($type < 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/de/assets/exemple_'.$type.'.xlsx');
                else if($type == 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/de/exemple_1.xlsx');
                else if($type > 3) $objPHPExcel = PHPExcel_IOFactory::load('../exportation/de/exemple_'.($type-1).'.xlsx');
                break;
            }
            default: 
            {
                header("Location: login.php");
                exit();
                break;
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('B9', strftime('%d/%m/%Y').' '.strftime('%H:%M'));

        switch($type)
        {
            case 1:
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F9', 'Courant Faible');
                if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE (ID=1 OR ID=2) AND UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` DESC, `ID` ASC';
                else $query = 'SELECT * FROM `SENSORS` WHERE ID=1 OR ID=2 ORDER BY `UNIXDATE` DESC, `ID` ASC';

                $result = $mysqli->query($query) or die($mysqli->error);
                $rows = array();

                $k = 0;
                $i = 0;
                $volt = 0.0;
                $ampere = 0.0;
                while($row = $result->fetch_assoc()) 
                {
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.(12+$i).':C'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('D'.(12+$i).':E'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('F'.(12+$i).':G'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.(12+$i).':I'.(12+$i).'');

                    if($k == 0) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.(12+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.(12+$i).'', number_format($row['VALUE'], 1).' A');
                        $ampere = $row['VALUE'];
                    }
                    else if($k == 1) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.(12+$i).'', number_format($row['VALUE'], 1).' V');
                        $volt = $row['VALUE'];
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->applyFromArray(array('borders' => array (
                          'allborders' => array (
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                            'color' => array('rgb' => '000000'),
                          )
                        )
                      )
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $k += 1;
                    if($k == 2) 
                    { 
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.(12+$i).'', number_format(($volt*$ampere), 1).' W');
                        $k = 0; 
                        $i += 1; 
                    }
                }

                for($z = 1; $z < ($i+13); $z++) $objPHPExcel->getActiveSheet()->getRowDimension(''.($z).'')->setRowHeight(21);
                
                $result->free();
                $mysqli->close();
                break;
            }
            case 2:
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F9', 'Courant Fort');
                if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE (ID=3 OR ID=4) AND UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` DESC, `ID` ASC';
                else $query = 'SELECT * FROM `SENSORS` WHERE ID=3 OR ID=4 ORDER BY `UNIXDATE` DESC, `ID` ASC';

                $result = $mysqli->query($query) or die($mysqli->error);
                $rows = array();

                $k = 0;
                $i = 0;
                $volt = 0.0;
                $ampere = 0.0;
                while($row = $result->fetch_assoc()) 
                {
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.(12+$i).':C'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('D'.(12+$i).':E'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('F'.(12+$i).':G'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.(12+$i).':I'.(12+$i).'');

                    if($k == 0) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.(12+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.(12+$i).'', number_format($row['VALUE'], 1).' A');
                        $ampere = $row['VALUE'];
                    }
                    else if($k == 1) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.(12+$i).'', number_format($row['VALUE'], 1).' V');
                        $volt = $row['VALUE'];
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->applyFromArray(array('borders' => array (
                          'allborders' => array (
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                            'color' => array('rgb' => '000000'),
                          )
                        )
                      )
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $k += 1;
                    if($k == 2) 
                    { 
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.(12+$i).'', number_format(($volt*$ampere), 1).' W');
                        $k = 0; 
                        $i += 1; 
                    }
                }

                for($z = 1; $z < ($i+13); $z++) $objPHPExcel->getActiveSheet()->getRowDimension(''.($z).'')->setRowHeight(21);

                $result->free();
                $mysqli->close();
                break;
            }
            case 3:
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F9', 'Éolienne');
                if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE (ID=3 OR ID=4) AND UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` DESC, `ID` ASC';
                else $query = 'SELECT * FROM `SENSORS` WHERE ID=3 OR ID=4 ORDER BY `UNIXDATE` DESC, `ID` ASC';

                $result = $mysqli->query($query) or die($mysqli->error);
                $rows = array();

                $k = 0;
                $i = 0;
                $volt = 0.0;
                $ampere = 0.0;
                while($row = $result->fetch_assoc()) 
                {
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.(12+$i).':C'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('D'.(12+$i).':E'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('F'.(12+$i).':G'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.(12+$i).':I'.(12+$i).'');

                    if($k == 0) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.(12+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.(12+$i).'', number_format($row['VALUE'], 1).' A');
                        $ampere = $row['VALUE'];
                    }
                    else if($k == 1) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.(12+$i).'', number_format($row['VALUE'], 1).' V');
                        $volt = $row['VALUE'];
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->applyFromArray(array('borders' => array (
                          'allborders' => array (
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                            'color' => array('rgb' => '000000'),
                          )
                        )
                      )
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $k += 1;
                    if($k == 2) 
                    { 
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.(12+$i).'', number_format(($volt*$amper), 1).' W');
                        $k = 0; 
                        $i += 1; 
                    }
                }

                for($z = 1; $z < ($i+13); $z++) $objPHPExcel->getActiveSheet()->getRowDimension(''.($z).'')->setRowHeight(21);

                $result->free();
                $mysqli->close();
                break;
            }
            case 4:
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F9', 'Météorologie');
                if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE (ID > 4 AND ID < 9) AND UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` DESC, `ID` ASC';
                else $query = 'SELECT * FROM `SENSORS` WHERE ID > 4 AND ID < 9 ORDER BY `UNIXDATE` DESC, `ID` ASC';

                $result = $mysqli->query($query) or die($mysqli->error);
                $rows = array();

                $k = 0;
                $i = 0;
                $tempamb = 0;
                while($row = $result->fetch_assoc()) 
                {
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.(13+$i).':B'.(13+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('F'.(13+$i).':G'.(13+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.(13+$i).':I'.(13+$i).'');

                    if($k == 0) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.(13+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');
                         $objPHPExcel->getActiveSheet()->setCellValue('D'.(13+$i).'', number_format($row['VALUE'], 1).' °C');
                    }
                    else if($k == 1) $objPHPExcel->getActiveSheet()->setCellValue('E'.(13+$i).'', number_format($row['VALUE'], 1).' °C');
                    else if($k == 2) {
                        $data = number_format(((2500/($row['VALUE']*0.0048828125)-500)/10), 1);
                        if($data < 0) $data = 0;
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.(13+$i).'', $data.' LUX');

                        $data = number_format(((pow((1000-$row['VALUE']),2)/10)/(50)), 1);
                        if($data < 0) $data = 0;
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.(13+$i).'', $data.' W/m²');
                    }
                    else if($k == 3) {
                        $data = number_format(round($row['VALUE']*100/1023.0), 0);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.(13+$i).'', $data.' %');
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(13+$i).':I'.(13+$i).'')->applyFromArray(array('borders' => array (
                          'allborders' => array (
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                            'color' => array('rgb' => '000000'),
                          )
                        )
                      )
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(13+$i).':I'.(13+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.(13+$i).':I'.(13+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $k += 1;
                    if($k == 4) 
                    { 
                        $k = 0; 
                        $i += 1; 
                    }
                }

                for($z = 1; $z < ($i+14); $z++) $objPHPExcel->getActiveSheet()->getRowDimension(''.($z).'')->setRowHeight(21);
                
                $result->free();
                $mysqli->close();
                break;
            }
            case 5:
            {
                $objPHPExcel->getActiveSheet()->setCellValue('F9', 'Vitesse du vent');
                if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE (ID > 8 AND ID < 12) AND UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` DESC, `ID` ASC';
                else $query = 'SELECT * FROM `SENSORS` WHERE ID > 8 AND ID < 12 ORDER BY `UNIXDATE` DESC, `ID` ASC';

                $result = $mysqli->query($query) or die($mysqli->error);
                $rows = array();

                $k = 0;
                $i = 0;
                while($row = $result->fetch_assoc()) 
                {
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.(12+$i).':C'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('D'.(12+$i).':E'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('F'.(12+$i).':G'.(12+$i).'');
                    $objPHPExcel->getActiveSheet()->mergeCells('H'.(12+$i).':I'.(12+$i).'');

                    if($k == 0) 
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.(12+$i).'', ''.gmdate("d/m/Y H:i", $row['UNIXDATE']).'');
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.(12+$i).'', number_format($row['VALUE'], 1).' KM/H');
                    }
                    else if($k == 1) $objPHPExcel->getActiveSheet()->setCellValue('F'.(12+$i).'', number_format($row['VALUE'], 1).' KM/H');
                    else if($k == 2) $objPHPExcel->getActiveSheet()->setCellValue('H'.(12+$i).'', number_format($row['VALUE'], 1).' TR/MIN');

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->applyFromArray(array('borders' => array (
                          'allborders' => array (
                            'style' => PHPExcel_Style_Border::BORDER_THICK,
                            'color' => array('rgb' => '000000'),
                          )
                        )
                      )
                    );

                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.(12+$i).':I'.(12+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $k += 1;
                    if($k == 3) 
                    { 
                        $k = 0; 
                        $i += 1; 
                    }
                }

                for($z = 1; $z < ($i+13); $z++) $objPHPExcel->getActiveSheet()->getRowDimension(''.($z).'')->setRowHeight(21);
                
                $result->free();
                $mysqli->close();
                break;
            }
        }

        $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="DataLogger_'.strftime('%d-%m-%Y').'.xlsx"');
        header('Cache-Control: max-age=0');
    
        $objPHPExcel->getActiveSheet()->setShowGridlines(false);
        $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        ob_end_clean();
        $writer->save('php://output');
        exit();
    }
?>
