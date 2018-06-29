<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/../src/Bootstrap.php';

$spreadsheetAuferma = IOFactory::load("ListagemAuferma.xlsx");
$spreadsheetMangeto = IOFactory::load("catalog_product.xlsx");
$mSheet= $spreadsheetMangeto->getActiveSheet();
$aSheet=$spreadsheetAuferma->getActiveSheet();
foreach ($aSheet->getRowIterator() as $aRow){
    if ($aRow->getRowIndex() == 1){
        continue;
    }
    foreach ($aRow->getCellIterator() as $aCell) {
        //Regex para partir a coluna da linha para depois ser inserido na folha magento
        $cord = $aCell->getCoordinate();
        preg_match_all('~[A-Z]+|\d+~', $cord, $split);
        switch ($aCell->getColumn()){
            case 'A':
                $mSheet->setCellValue($cord,$aCell->getValue());
                break;
            case 'B':
                $mSheet->setCellValue('G'.$split[0][1],$aCell->getValue());
                break;
            case 'D':
                $mSheet->setCellValue('N'.$split[0][1],$aCell->getValue());
                break;
            case 'E':
                $mSheet->setCellValue('J'.$split[0][1],$aCell->getValue());
                break;
            case 'F':
                $mSheet->setCellValue('AM'.$split[0][1],'Manufacter='.$aCell->getValue());
                break;
            case 'G':
                switch ($aCell->getValue()){
                    case '115':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Encastre');
                        break;
                    case '120':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Congeladores,Categorias/Grandes Domésticos/Congeladores Horizontais');
                        break;
                    case '135':
                        $name = $aSheet->getCell('B'.$split[0][1],false)->getCalculatedValue();
                        print_r($name."\n");
                        if(preg_match('/Maq.Louça Integ/',$name)==1){
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Encastre,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Encastre/Máquinas de Lavar Louça');
                        }elseif (preg_match('/Maquina Lavar e Secar/',$name)===1) {
                            $mSheet->setCellValue('E' . $split[0][1], 'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Máquinas de Roupa,Categorias/Grandes Domésticos/Máquinas de Roupa/Máquinas de Lavar e Secar Roupa');
                        }elseif (preg_match('/Maquina Secar/',$name)==1){
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Máquinas de Roupa,Categorias/Grandes Domésticos/Máquinas de Roupa/Máquinas de Secar Roupa');
                        }elseif (preg_match('/Maquina Lavar/',$name)==1){
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Máquinas de Roupa,Categorias/Grandes Domésticos/Máquinas de Roupa/Máquinas de Lavar Roupa');
                        }elseif (preg_match('/Maq.Integ/',$name)==1) {
                            $mSheet->setCellValue('E' . $split[0][1], 'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Encastre,Categorias/Grandes Domésticos/Encastre/Máquinas de Lavar Roupa');
                        }elseif (preg_match('/Máquina Roupa/',$name)==1) {
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Máquinas de Roupa,Categorias/Grandes Domésticos/Máquinas de Roupa/Máquinas de Lavar Roupa');
                        }elseif (preg_match('/Maquina Louça/',$name)==1) {
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Máquinas de Roupa,Categorias/Grandes Domésticos/Máquinas de Lavar Louça');
                        }elseif (preg_match('/Maquina Louça/',$name)==1) {
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Máquinas de Roupa,Categorias/Grandes Domésticos/Máquinas de Lavar Louça');
                        }else{
                            $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos');
                        }
                        break;
                    case '110':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Fogões');
                        break;
                    case '130':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Frigoríficos');
                        break;
                    case '800':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Acessórios');
                        break;
                    case '925':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Leitores MP3, MP4 e Ipod');
                        break;
                    case '190':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos,Categorias/Pequenos Domésticos/Cuidado Pessoal');
                        break;
                    case '930':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Leitores MP3, MP4 e Ipod');
                        break;
                    case '230':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Rádios e Despertadores');
                        break;
                    case '215':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Aparelhagens Hi-Fi');
                        break;
                    case '235':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Rádios e Despertadores');
                        break;
                    case '295':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Acessórios');
                        break;
                    case '210':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Colunas Bluetooth');
                        break;
                    case '105':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Climatização,Categorias/Climatização/Ar Condicionado');
                        break;
                    case '250':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Tvs');
                        break;
                    case '190':
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                        break;
                    default:
                        $mSheet->setCellValue('E'.$split[0][1],'Categorias');
                }
            case 'H':
                $mSheet->setCellValue('H'.$split[0][1],$aCell->getValue());
                break;
        }
        //attribute_set_code
        $mSheet->setCellValue('C'.$split[0][1],'Default');
        //product_type
        $mSheet->setCellValue('D'.$split[0][1], 'simple');
        //product_websites
        $mSheet->setCellValue('F'.$split[0][1],'base');
        //product_online
        //$mSheet->setCellValue('F'.$split[0][1],1);
        //tax_class_name
        $mSheet->setCellValue('L'.$split[0][1],'Taxable Goods');
        //visibility
        $mSheet->setCellValue('M'.$split[0][1],'Catalog, Search');
        //quantity
        $mSheet->setCellValue('AN'.$split[0][1],'5');
        //website_id
        $mSheet->setCellValue('BH'.$split[0][1],'1');
    }
}
$writer = new Csv($spreadsheetMangeto);
$writer->save("FuckingBekoCSV.csv");
