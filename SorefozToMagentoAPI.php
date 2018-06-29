<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/../src/Bootstrap.php';
require_once __DIR__ . '/Produto.php';
$userData = array("username" => "admin", "password" => "magentorocks1");
$ch = curl_init("http://local.magento/index.php/rest/V1/integration/admin/token");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($userData))));

$token = curl_exec($ch);
print_r($token);



$spreadsheetAuferma = IOFactory::load("tot_jlcb.xlsx");
$aSheet=$spreadsheetAuferma->getActiveSheet();
foreach ($aSheet->getRowIterator() as $aRow){
    if ($aRow->getRowIndex() == 1){
        continue;
    }
    $produto = new Produto();
    foreach ($aRow->getCellIterator() as $aCell) {
        //Regex para partir a coluna da linha para depois ser inserido na folha magento
        $cord = $aCell->getCoordinate();
        preg_match_all('~[A-Z]+|\d+~', $cord, $split);
        switch ($aCell->getColumn()){
            //Referencia
            case 'S':
                if(strlen($aCell->getValue())==13){
                    $produto->sku = trim($aCell->getValue());
                }else{
                    break 2;
                }

                break;
            //Descricao
            case 'B':
               $produto->nome = trim($aCell->getValue());
                break;
            //Nome da marca
            case 'D':
                $produto->atributosAdicionais = 'Manufacter='.trim($aCell->getValue());
                break;
            case 'F':
                $produto->nomeGama = trim($aCell->getValue());
                break;
            case 'H':
                $produto->familia = trim($aCell->getValue());
                break;
            case 'J':
                $produto->subFamilia = trim($aCell->getValue());
                break;
            //Preço
            case 'M':
                $produto->preco = $aCell->getValue() * 1.05;
                $produto->preco += $preco + 7.5;
                break;
            //Gama | Fora de Gama
            case 'Q':
                switch ($aCell->getValue()){
                    case 'sim':
                        $produto->foraGama = 1;
                        break;
                    case 'não':
                        $produto->foraGama = 2;
                        break;
                }
                break;
            //PESO
            case 'T':
                $produto->peso = $aCell->getValue();
                break;
            //Volume
            case 'U':
                $produto->atributosAdicionais .= ',Volume='.$aCell->getValue();
                break;
            case 'V':
                $produto->atributosAdicionais .= ',Comprimento='.$aCell->getValue();
                break;
            case 'W':
                $produto->atributosAdicionais .= ',Largura='.$aCell->getValue();
                break;
            case 'X':
                $produto->atributosAdicionais .= ',Altura='.$aCell->getValue();
                break;
            //Link imagem
            case 'Y':
                break;
            //Caracteristicas
            case 'Z':
                $produto->descricao = $aCell->getValue();
                break;
            //Stock
            case 'AA':
                switch ($aCell->getValue()){
                    case 'sim':
                        $produto->stock = 1;
                        break;
                    case 'nao':
                        $produto->stock = 0;
                        break;
                }
                break;
        }
    }
    $produto->addDataBase($token);
}
