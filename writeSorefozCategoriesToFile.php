<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/Categorie.php';
require_once __DIR__ . '/../src/Bootstrap.php';

$spreadsheetSorefozCat = IOFactory::load("sorefozCat.xlsx");
$aSheet=$spreadsheetSorefozCat->getActiveSheet();
$categorias = array();
$gama = '';
$familia = '';
foreach ($aSheet->getRowIterator() as $aRow) {
    if ($aRow->getRowIndex() == 1) {
        continue;
    }
    foreach ($aRow->getCellIterator() as $cell)
        switch ($cell->getColumn()) {
            case 'A':
                $gama = trim($cell->getValue());
                if (!in_array($gama, $categorias)) {
                    $categorias[] = $gama;
                }
                break;
            case 'B':
                $familia = trim($cell->getValue());
                if (!in_array($familia, $categorias[$gama])) {
                    $categorias[$gama][] =  $familia;
                }
                break;
            case 'C':
                $subFamilia = trim($cell->getValue());
                if (!in_array($subFamilia, $categorias[$gama][$familia])) {
                    $categorias[$gama][$familia][] = $subFamilia;
                }
                break;
        }
}

file_put_contents('SorefozCatArray.txt',json_encode($categorias));


