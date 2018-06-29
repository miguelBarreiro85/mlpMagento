<?php
/**
 * Created by PhpStorm.
 * User: miguel
 * Date: 27-06-2018
 * Time: 17:50
 */
class Categorie
{

    public $id;
    public $parent_id;
    public $name;
    public $is_active;
    public $position;
    public $level;
    public $children;
    public $created_at;
    public $updated_at;
    public $path;
    public $available_sort_by = [];
    public $include_in_menu;
    public $extension_attributes = [];
    public $custom_attributes = ["attribute_code"=>"null","value"=>"null"];

    function registerMagento($token){
        $categoryData = array("category" => array("parent_id" => $this->parent_id, "name" => $this->name,
            "is_active" => true));
        print_r(json_encode($categoryData));
        $urlPost = "http://local.magento/index.php/rest/V1/categories";
        $curlPost = curl_init();
        curl_setopt_array($curlPost, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $urlPost,
            CURLOPT_POSTFIELDS => json_encode($categoryData),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json", "Authorization: Bearer ".json_decode($token),
                "Content-Lenght: " . strlen(json_encode($categoryData))
            ]
        ]);
        $resultPost = curl_exec($curlPost);
        $httpcodePost = curl_getinfo($curlPost, CURLINFO_HTTP_CODE);
        if($httpcodePost == 200){
            $respArray = json_decode($resultPost,true);
            print_r($respArray['id']);
            return $respArray['id'];
        }else{
            print_r("ERRO".$httpcodePost."\n".$resultPost);
            $id = $this->getCategoryId($token,$this->name);
            print_r("erro - id: ".$id."\n".$this->name);

            return $id;
        }
        curl_close($curlPost);
    }

    function getCategoryId($token,$name){
        $urlPost = "http://local.magento/index.php/rest/all/V1/categories";
        $curlPost = curl_init();
        curl_setopt_array($curlPost, [
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $urlPost,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json", "Authorization: Bearer ".json_decode($token)
            ]
        ]);
        $resultPost = curl_exec($curlPost);
        $root = json_decode($resultPost,true);
        $jsonArray=$root['children_data'];
        print_r($jsonArray);
        print_r("\nnumero de categorias: ".count($jsonArray[0]['children_data']));
        foreach ($jsonArray[0]['children_data'] as $category){
            print_r($category);
            if(strcmp($category['name'],$name)==0){
                return $category['id'];
            }

        }
    }
}