<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/../src/Bootstrap.php';

$spreadsheetSorefoz = IOFactory::load("tot_jlcb.xlsx");
$spreadsheetMangeto = IOFactory::load("Magento_images.xlsx");

$mSheet= $spreadsheetMangeto->getActiveSheet();
$sSheet=$spreadsheetSorefoz->getActiveSheet();
foreach ($sSheet->getRowIterator() as $sRow) {
    if ($sRow->getRowIndex() == 1) {
        continue;
    }
    $rowIndex = $sRow->getRowIndex();
    $imgLink = $sSheet->getCell('Y' . $rowIndex, false)->getCalculatedValue();
    $sku = $sSheet->getCell('S' . $rowIndex, false)->getCalculatedValue();

    $img = fopen();





    $ch = curl_init($imgLink);
    $fp = fopen( '/home/miguel/PhpstormProjects/Auferma2/samples/SorefozImages/'.$sku.'.jpeg', 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}