<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/../src/Bootstrap.php';

$spreadsheetSorefoz = IOFactory::load("tot_jlcb.xlsx");
$spreadsheetMangeto = IOFactory::load("Magento_images.xlsx");

$mSheet= $spreadsheetMangeto->getActiveSheet();
$sSheet=$spreadsheetSorefoz->getActiveSheet();
$sorefozRowIndex=2;
foreach ($sSheet->getRowIterator() as $sRow) {
    if ($sRow->getRowIndex() == 1) {
        continue;
    }
    $rowIndex = $sRow->getRowIndex();
    $imgLink = $sSheet->getCell('Y'.$rowIndex,false)->getCalculatedValue();
    $sku = $sSheet->getCell('S'.$rowIndex,false)->getCalculatedValue();

    if(preg_match('/^http/',$imgLink) && strlen($sku)==13){
        if($sorefozRowIndex == 1002){
            break;
        }else {
            $mSheet->setCellValue('A' . $sorefozRowIndex, $sku);
            $mSheet->setCellValue('B' . $sorefozRowIndex, $sku . '.jpeg');
            $mSheet->setCellValue('C' . $sorefozRowIndex, $sku);
            $mSheet->setCellValue('D' . $sorefozRowIndex, $sku . '.jpeg');
            $mSheet->setCellValue('E' . $sorefozRowIndex, $sku);
            $mSheet->setCellValue('F' . $sorefozRowIndex, $sku . '.jpeg');
            $mSheet->setCellValue('G' . $sorefozRowIndex, $sku);
            $sorefozRowIndex++;
        }
    }else{
        print_r("vazia\n");
    }
}
$writer = new Csv($spreadsheetMangeto);
$writer->save('SorefozImages.csv');