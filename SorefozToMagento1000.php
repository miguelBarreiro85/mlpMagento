<?php

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
require_once __DIR__ . '/../src/Bootstrap.php';

$spreadsheetAuferma = IOFactory::load("tot_jlcb.xlsx");
$spreadsheetMangeto = IOFactory::load("catalog_product.xlsx");
$mSheet= $spreadsheetMangeto->getActiveSheet();
$aSheet=$spreadsheetAuferma->getActiveSheet();
foreach ($aSheet->getRowIterator() as $aRow){
    if ($aRow->getRowIndex() == 1){
        continue;
    }
    $nomeGama;
    $familia;
    $subFamilia;
    $atributosAdicionais;
    foreach ($aRow->getCellIterator() as $aCell) {
        //Regex para partir a coluna da linha para depois ser inserido na folha magento
        $cord = $aCell->getCoordinate();
        preg_match_all('~[A-Z]+|\d+~', $cord, $split);
        $atributosAdicionais;
        switch ($aCell->getColumn()){
            //Referencia
            case 'S':
                if(strlen($aCell->getValue())==13){
                    $mSheet->setCellValue('A'.$split[0][1],$aCell->getValue());
                }else{
                    print_r($value."\n");
                    $value = $aSheet->getCell('A'.$split[0][1]);
                    $mSheet->setCellValue('A'.$split[0][1],$value);
                }

                break;
            //Descricao
            case 'B':
                $mSheet->setCellValue('G'.$split[0][1],$aCell->getValue());
                break;
            //Nome da marca
            case 'D':
                $atributosAdicionais = 'Manufacter='.$aCell->getValue();
                break;
            case 'F':
                $nomeGama = $aCell->getValue();
                break;
            case 'H':
                $familia = $aCell->getValue();
                break;
            case 'J':
                $subFamilia = $aCell->getValue();
                break;
            //Preço
            case 'M':
                $preco = $aCell->getValue() * 1.05;
                $preco += $preco + 7.5;
                $mSheet->setCellValue('N'.$split[0][1],$preco );
                break;
            //Gama | Fora de Gama
            case 'Q':
                switch ($aCell->getValue()){
                    case 'sim':
                        $mSheet->setCellValue('K'.$split[0][1],'1');
                        break;
                    case 'nao':
                        $mSheet->setCellValue('K'.$split[0][1],'2');
                        break;
                }
                break;
            //PESO
            case 'T':
                $mSheet->setCellValue('J'.$split[0][1], $aCell->getValue());
                break;
            //Volume
            case 'U':
                $atributosAdicionais .= ',Volume='.$aCell->getValue();
                break;
            /* case 'G':
                 switch ($aCell->getValue()){
                     //ACESSÓRIOS E BATERIAS -> Tem calculadoras WTF!!! | Oculos 3d ???
                     case '900':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Encastre');
                         break;
                     //Televisao
                     case '200':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Congeladores,Categorias/Grandes Domésticos/Congeladores Horizontais');
                         break;
                     //TElevisao c/ DVD
                     case '230':
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
                     //DVD/BR/TDT
                     case '240':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Fogões');
                         break;
                     //ACESSORIOS INFORMATICA
                     case '590':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Frigoríficos');
                         break;
                     //PC / Tablets / CALCULADORAS
                     case '510':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Grandes Domésticos,Categorias/Grandes Domésticos/Acessórios');
                         break;
                     //memorias DISCOS RIGIDOS
                     case '550':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Leitores MP3, MP4 e Ipod');
                         break;
                     //Acessorios imagem e som | Suportes
                     case '290':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos,Categorias/Pequenos Domésticos/Cuidado Pessoal');
                         break;
                     //Cameras fotograficas
                     case '250':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Leitores MP3, MP4 e Ipod');
                         break;
                     //Home-cinema
                     case '280':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Rádios e Despertadores');
                         break;
                     //Equipamentos Audio | aUS CULTADORES | SOUD BAR | COLUNAS | aPAREGLHAGEM | rECETOR av
                     case '260':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Aparelhagens Hi-Fi');
                         break;
                     //Consumiveis
                     case '580':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Rádios e Despertadores');
                         break;
                     //Monitores PC
                     case '530':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Acessórios');
                         break;
                     //Audio Portatil | mp3 | MP4
                     case '270':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Som,Categorias/Imagem e Som/Som/Colunas Bluetooth');
                         break;
                     //Impressoras
                     case '1020':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Climatização,Categorias/Climatização/Ar Condicionado');
                         break;
                     //SOFTWARE
                     case '570':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Imagem e Som,Categorias/Imagem e Som/Tvs');
                         break;
                     //TELEMOVEIS CARTOES
                     case '410':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //mATERIAL PROMOCIONAL
                     case '1030':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //tELEFONES fIXOS
                     case '420':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //ASSEIO PESSOAL
                     case '330':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //car AUDIO / ALTIFALANTES
                     case '720':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //CAR AUDIO / AUTO RADIOS
                     case '700':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //car AUDIO / SISTEMA NAVEGAÇÃO
                     case '730':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //CAR AUDIO / AMPLIFICADORES
                     case '750':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //INFORMATICA | gps
                     case '560':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     // maq SECAR
                     case '10':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //fOGOES
                     case '30':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //cONGELADORES
                     case '60':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //fRIGO / COMBINADOS
                     case '50':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //mAQUINA LAVAR ROUPA
                     case '1':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     //MAQUINA LAVAR LOIÇA
                     case '20':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     case '50':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     case '50':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     case '50':
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/Pequenos Domésticos');
                         break;
                     default:
                         $mSheet->setCellValue('E'.$split[0][1],'Categorias');
                 }
            */
            //Comprimento
            case 'V':
                $atributosAdicionais .= ',Comprimento='.$aCell->getValue();
                break;
            case 'W':
                $atributosAdicionais .= ',Largura='.$aCell->getValue();
                break;
            case 'X':
                $atributosAdicionais .= ',Altura='.$aCell->getValue();
                break;
            //Link imagem
            case 'Y':
                break;
            //Caracteristicas
            case 'Z':
                $mSheet->setCellValue('H'.$split[0][1], $aCell->getValue());
                break;
            //Stock
            case 'AA':
                switch ($aCell->getValue()){
                    case 'sim':
                        $mSheet->setCellValue('AX'.$split[0][1],'1');
                        break;
                    case 'nao':
                        $mSheet->setCellValue('AX'.$split[0][1],'0');
                        break;
                }
                break;
        }
        //Categorias
        $subFamilia=trim($subFamilia);
        $mSheet->setCellValue('E'.$split[0][1],'Categorias,Categorias/'.$nomeGama.',Categorias/'.$nomeGama.'/'.$familia.',Categorias/'.$nomeGama.'/'.$familia.'/'.$subFamilia);
        //Atributos adicionais
        $mSheet->setCellValue('AM'.$split[0][1],$atributosAdicionais);
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
        //$mSheet->setCellValue('AN'.$split[0][1],'5');
        //website_id
        $mSheet->setCellValue('BH'.$split[0][1],'1');
        //manage stock
        //$mSheet->setCellValue('BA'.$split[0][1],'0');
    }
    //if($aRow->getRowIndex() % 1000 == 0){
    if($aRow->getRowIndex() == 1000){
        print_r("Escrever ficheiro");
        $writer = new Csv($spreadsheetMangeto);
        $writer->save("Sorefoz.csv");
    }

}
$writer = new Csv($spreadsheetMangeto);
$writer->save("Sorefoz.csv");
