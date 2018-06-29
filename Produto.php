<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 28-06-2018
 * Time: 16:16
 */

class Produto
{
    public $nomeGama;
    public $familia;
    public $subFamilia;
    public $sku;
    public $descricao;
    public $preco;
    public $foraGama;
    public $peso;
    public $stock;
    public $nome;
    public $manufacter="no info";
    public $volume=0;
    public $comprimento=0;
    public $largura=0;
    public $altura=0;
    public $token;
    function getFamiliaSorefoz(){

    }

    function getSubFamiliaSorefoz(){

    }

    function getCategoriesFromSorefoz(){

    }

    function getCategoryId($gama){
        $categories = $this->getCategories();
        foreach ($categories['items'] as $category){
            if(strcmp($category['name'],$gama)==0){
                return $category['id'];
            }
        }
    }
    function addDataBase($token){
        $this->token = $token;
        $method = 'GET';
        $url = 'http://local.magento/index.php/rest/V1/products/'.$this->sku;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json", "Authorization: Bearer ".json_decode($token)
            ]
        ]);

        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($httpcode == 404){
            print_r("not found\n");
            $productData = [
                "product"=>[
                    "sku"=> $this->sku,
                    "name"=> $this->nome,
                    "price"=>$this->preco,
                    "status"=>$this->foraGama,
                    "type_id"=> "simple",
                    "attribute_set_id" => 4,
                    "weight"=>$this->peso,
                    "extension_attributes"=>["category_links"=>array(
                        0=>array(
                            "position"=>0,
                            "category_id"=>$this->getCategoryId($this->nomeGama),
                        ),
                        1=>array(
                            "position"=>0,
                            "category_id"=>$this->getCategoryId($this->familia),
                        ),
                        2=>array(
                            "position"=>0,
                            "category_id"=>$this->getCategoryId($this->subFamilia),
                        ))],
                    "custom_attributes"=>[
                        ["attribute_code" => "description", "value" => $this->nome],
                        ["attribute_code" => "meta_description", "value" => $this->descricao],
                        ["attribute_code" => "image", "value" => $this->sku."jpg"],
                        ["attribute_code" => "small_image", "value" => $this->sku."jpg"],
                        ["attribute_code" => "thumbnail", "value" => $this->sku."jpg"]
                    ]
                    ]];
            $productJSON = json_encode($productData);
            print_r($productJSON);
            $urlPost = 'http://local.magento/index.php/rest/V1/products';
            $curlPost = curl_init();

            curl_setopt_array($curlPost, [
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $productJSON,
                CURLOPT_URL => $urlPost,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json", "Authorization: Bearer ".json_decode($token)
                ]
            ]);
            $resultPost = curl_exec($curlPost);
            $httpcodePost = curl_getinfo($curlPost, CURLINFO_HTTP_CODE);
            if($httpcodePost == 200){
                print_r("Producto criado SUCESSO\n\n");
            }else{
                print_r("ERRO".$httpcodePost."\n".$resultPost);
            }
            curl_close($curlPost);
        }elseif ($httpcode == 200){
            print_r($resultPost."\n");
        }
        curl_close($curl);
    }
    function getCategories(){
        $urlPost = "http://local.magento/index.php/rest/V1/categories/list?searchCriteria[filterGroups][0]";
        $curlPost = curl_init();
        curl_setopt_array($curlPost, [
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $urlPost,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json", "Authorization: Bearer ".json_decode($this->token)
            ]
        ]);
        $categories = curl_exec($curlPost);
        $httpcodePost = curl_getinfo($curlPost, CURLINFO_HTTP_CODE);
        print_r("Codigo HTTP do pedido categorias:".$httpcodePost);
        if($httpcodePost == 200){
            return json_decode($categories,true);
        }else{
            print_r("ERRO ao receber categorias".$httpcodePost."\n");
            exit();
        }
        curl_close($curlPost);
    }
}