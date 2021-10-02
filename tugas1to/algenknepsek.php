<?php

class Catalogue
{
    function createProductColumn($column, $listOfRawProduct){
        foreach (array_keys($listOfRawProduct) as $listOfRawProductKey){
            $listOfRawProduct[$columns[$listOfRawProductKey]] = $listOfRawProduct[$listOfRawProductKey];
            unset($listOfRawProduct[$listOfRawProductKey]);
        }

    }
    function product($parameters){
        $collectionOfListProduct = [];

        $raw_data = file($parameters['file_name']);
        foreach ($raw_data as $listOfRawProduct) {
            $collectionOfListProduct[] = $this->createProductColumn($parameters['columns'], explode(",", $listOfRawProduct));
        }

        foreach ($collectionOfListProduct as $listOfRawProduct){
            print_r($listOfRawProduct);
            echo '<br>';
        }
        return [
            'product' =>$collectionOfListProduct,
            'gen_length' => count($collectionOfListProduct),
        ];
    }

}

class PopulationGenerator
{
    function createIndividu($parameters){
        $catalogue = new Catalogue;
        $lengthOfGen = $catalogue->product($parameters)['gen_length'];
        for ($i = 0; $ <= $catalogue->product()['gen_length']-1; $i++){
            $ret[] = rand(0,1);
        }
        return $ret;
    }

    function createPopulation($parameters){
        for ($i = 0; $ <= $parameters['population_size']; $i++){
            $ret[] = $this->createIndividu($parameters);
        }
        foreach ($ret as $key => $val){
            print_r($val);
            echo '<br>';
        }
        print_r($ret);
    }
}

$parameters= [
    'file_name' => 'products.txt',
    'columns' => ['item', 'harga'],
    'population_size' => 10

];

$katalog = new Catalogue;
$katalog->product($parameters);

$initalPopulation = new PopulationGenerator;
$initalPopultaion->createPopulation($parameters);