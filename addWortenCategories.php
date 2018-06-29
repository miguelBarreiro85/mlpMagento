<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/Categorie.php';
require_once __DIR__ . '/../src/Bootstrap.php';

$userData = array("username" => "admin", "password" => "magentorocks1");
$ch = curl_init("http://local.magento/index.php/rest/V1/integration/admin/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

$token = curl_exec($ch);
print_r($token);


$spreadsheetWorten = IOFactory::load("wortenCategorias.xlsx");
$wSheet= $spreadsheetWorten->getActiveSheet();
foreach ($wSheet->getColumnIterator() as $wCol) {
    $grandParent_id;
    $parent_id;
    foreach ($wCol->getCellIterator() as $wCell) {
        $category = new Categorie();
        if($wCell->getValue() == ''){
            continue;
        }
        switch ($wCell->getRow()){
            case 1:
                $category->name = $wCell->getValue();
                $category->is_active = true;
                $category->parent_id = 2;
                $grandParent_id = $category->registerMagento($token);
                break;
            case 2:
                $category->name = $wCell->getValue();
                $category->is_active = true;
                $category->parent_id = $grandParent_id;
                $parent_id = $category->registerMagento($token);
                break;
            default:
                $category->name = $wCell->getValue();
                $category->is_active = true;
                $category->parent_id = $parent_id;
                $category->id = $category->registerMagento($token);
                break;
        }
    }
}


