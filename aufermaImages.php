<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/../src/Bootstrap.php';

$spreadsheetAuferma = IOFactory::load("ListagemAuferma.xlsx");
$spreadsheetMangeto = IOFactory::load("Magento_images_auferma.xlsx");

$files = scandir('/var/www/html/magento2/pub/media/import');
$mSheet= $spreadsheetMangeto->getActiveSheet();
$aSheet=$spreadsheetAuferma->getActiveSheet();
foreach ($aSheet->getRowIterator() as $aRow) {
    if ($aRow->getRowIndex() == 1) {
        continue;
    }
    $rowIndex = $aRow->getRowIndex();
    $cellVal = $aSheet->getCell('C'.$rowIndex,false)->getCalculatedValue();
    foreach ($files as $file){
        if(strcmp($file ,$cellVal.'.jpg') == 0){
            break;
        }
        if(end($files)==$file){
            continue 2;
        }

    }
    foreach ($aRow->getCellIterator() as $aCell) {
        //Regex para partir a coluna da linha para depois ser inserido na folha magento
        $cord = $aCell->getCoordinate();
        preg_match_all('~[A-Z]+|\d+~', $cord, $split);
        switch ($aCell->getColumn()) {
            case 'A':
                $cellValue = $aCell->getValue();
                $mSheet->setCellValue('A' . $split[0][1], $cellValue);
                break;
            case 'C':
                $cellValue = $aCell->getValue();
                $mSheet->setCellValue('B' . $split[0][1], $cellValue . '.jpg');
                $mSheet->setCellValue('C' . $split[0][1], $cellValue);
                $mSheet->setCellValue('D' . $split[0][1], $cellValue . '.jpg');
                $mSheet->setCellValue('E' . $split[0][1], $cellValue);
                $mSheet->setCellValue('F' . $split[0][1], $cellValue . '.jpg');
                $mSheet->setCellValue('G' . $split[0][1], $cellValue);
                break;
            default:
                break;
        }
    }
}
$i=0;
foreach ($mSheet->getRowIterator() as $mRow){
    print_r($i);
    foreach($mRow->getCellIterator() as $mCell){
        switch ($mCell->getColumn()){
            case 'A':
                if($mCell->getCalculatedValue()==''){
                    $mSheet->removeRow($mRow->getRowIndex());
                }
                break;
            default:
                break;
        }
    }
    $i++;
}
$writer = new Csv($spreadsheetMangeto);
$writer->save("FuckingBekoImages.csv");