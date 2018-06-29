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



$categorias = json_decode(file_get_contents('SorefozCatArray.txt'),true);

$gamas = array_values($categorias);
foreach ($gamas as $gama){
    $categoryG = new Categorie();
    $categoryG->name = $gama;
    $categoryG->parent_id = 2;
    $categoryG->is_active = true;
    $gamaId = $categoryG->registerMagento($token);
    $familias = array_values($categorias[$gama]);
    foreach ($familias as $familia){
        //A sorefoz tem a gama ACESSORIOS E BATERIAS igual ao nome da familia
        if(strcmp($gama,$familia)!==0 || strcmp($familia,'ACESSÓRIOS E BATERIAS')==0){
            $categoryF = new Categorie();
            $categoryF->name = $familia;
            $categoryF->parent_id = $gamaId;
            $categoryF->is_active = true;
            $familiaId = $categoryF->registerMagento($token);
            $subFamilias = array_values($categorias[$gama][$familia]);
            foreach ($subFamilias as $subFamilia) {
                if (strcmp($familia, $subFamilia) !== 0) {
                    $categorySf = new Categorie();
                    $categorySf->name = $subFamilia;
                    $categorySf->parent_id = $familiaId;
                    $categorySf->is_active = true;
                    $subFamiliaId = $categorySf->registerMagento($token);
                }
            }
        }
    }
}