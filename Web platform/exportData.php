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

        $objPHPExcel->getActiveSheet()->setCellValue('E8', strftime('%d/%m/%Y').' '.strftime('%H:%M'));

        if($interval != 0) $query = 'SELECT * FROM `SENSORS` WHERE UNIXDATE > '.(time()-$interval).' ORDER BY `UNIXDATE` ASC';
        else $query = 'SELECT * FROM `SENSORS` WHERE 1 ORDER BY `UNIXDATE` ASC';
        $result = $mysqli->query($query) or die($mysqli->error);
        $rows = array();

        $i = 0;
        while($row = $result->fetch_assoc()) 
        {
            $objPHPExcel->getActiveSheet()->mergeCells('B'.(11+$i).':D'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('E'.(11+$i).':G'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->mergeCells('H'.(11+$i).':J'.(11+$i).'');
            $objPHPExcel->getActiveSheet()->getRowDimension(''.(11+$i).'')->setRowHeight(21);

            $objPHPExcel->getActiveSheet()->getStyle('B'.(11+$i).':J'.(11+$i).'')->applyFromArray(array('borders' => array (
                  'allborders' => array (
                    'style' => PHPExcel_Style_Border::BORDER_THICK,
                    'color' => array('rgb' => '000000'),        // BLACK
                  )
                )
              )
            );

            $objPHPExcel->getActiveSheet()->getStyle('B'.(11+$i).':H'.(11+$i).'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B'.(11+$i).':H'.(11+$i).'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue('B'.(11+$i).'', ''.getName($row['ID']).'');
            $objPHPExcel->getActiveSheet()->setCellValue('E'.(11+$i).'', ''.$row['VALUE'].'');
            $objPHPExcel->getActiveSheet()->setCellValue('H'.(11+$i).'', ''.gmdate("j/m/Y H:i:s", $row['UNIXDATE']).'');
            $i += 1;
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
