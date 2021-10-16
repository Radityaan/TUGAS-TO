<?php

class Parameters
{
    const FILE_NAME = 'products.txt';
    const COLUMNS = ['item', 'price'];
    const POPULATION_SIZE = 10;
    const BUDGET = 280000;
    const STOPPING_VALUE = 10000;
    const CROSOVER_RATE=0.8;
}

class Catalogue
{
    function createProductColumn($listOfRawProduct){
        foreach (array_keys($listOfRawProduct) as $listOfRawProductKey){
            $listOfRawProduct[Parameters::COLUMNS[$listOfRawProductKey]] = $listOfRawProduct[$listOfRawProductKey];
            unset($listOfRawProduct[$listOfRawProductKey]);
        }
        return $listOfRawProduct; 
    }
    function product(){
        $collectionOfListProduct = [];

        $raw_data = file(Parameters::FILE_NAME);
        foreach ($raw_data as $listOfRawProduct) {
            $collectionOfListProduct[] = $this->createProductColumn(explode(",", $listOfRawProduct));
        }
        return $collectionOfListProduct;
    }

}

class PopulationGenerator
{
    function createIndividu($parameters){
        $catalogue = new Catalogue;
        $lengthOfGen = $catalogue->product($parameters)['gen_length'];
        for($i=0; $i <= $lengthOfGen-1; $i++){
            $ret[]=rand(0,1);
        }
        return $ret;
    }

    function createPopulation($parameters){
        for ($i = 0; $i<= $parameters['population_size']; $i++){
            $ret[] = $this->createIndividu($parameters);
        }

        foreach($ret as $key => $val){
            print_r($val);
            echo '<br>';
        }
    
    }
}

class Crossover
{
    public $population;

    function __construct($populations)
    {
        $this->populations=$populations;
    }

    function randomZeroToOne()
    {
        return(float)rand()/(float)getrandmax();
    }

    function generateCrossover()
    {
        for ($i=0; $i<=Parameters::POPULATION_SIZE-1; $i++){
            $randomZeroToOne=$this->randomZeroToOne();
            if($randomZeroToOne < Parameters::CROSOVER_RATE){
                $parent[$i]=$randomZeroToOne;
            }
        }
       foreach(array_keys($parents)as $key){
           foreach (array_keys($parents)as $subkey){
               if ($key !== $subkey){
                   $ret[]=[$key,$subkey];
               }
           }
           array_shift($parents);
       }
       return $ret;
    }

    function offspring($parent1, $parent2, $cutPointIndex, $offspring)
    {
        $lengthOfGen=new Individu;
        if($offspring===1){
            for($i=0;i<=lengthOfGen->countNumberOfGen()-1; $i++){
                if($i<=$cutPointIndex){
                    $ret[]=$parent1[$i];
                }
                if($i>$cutPointIndex){
                    $ret=$parent2[$i];
                }
            }
        }
        if($offspring===2){
            for($i=0;i<=lengthOfGen->countNumberOfGen()-1; $i++){
                if($i<=$cutPointIndex){
                    $ret[]=$parent2[$i];
                }
                if($i>$cutPointIndex){
                    $ret=$parent1[$i];
                }
            }
        }

        return $ret;

    }

    function cutPointRandom()
    {
        $lengthOfGen=new Individu;
        return rand(0, $lengthOfGen->countNumberOfGen()-1);
    }

    function crossover()
    {
        $cutPointIndex=$this->cutPointRandom();
        //echo $cutPointIndex;
        foreach($this->generateCrossover() as $listOfCrossover){
            $parent1=$this->populations[$listOfCrossover[0]];
            $parent2=$this->populations[$listOfCrossover[1]];
            // echo '<p></p>'
            // echo 'Parents : <br>';
            // foreach($parent1 as $gen){
            //     echo $gen;
            // }
            // echo '><';
            // foreach($parent2 as $gen){
            //     echo $gen;
            // }
            // echo '<br>';
            // echo 'offspring<br>';
            $offspring1=$this->offspring($parent1, $parent2, $cutPointIndex, 1);
            $offspring2=$this->offspring($parent1, $parent2, $cutPointIndex, 2);
            // foreach($offspring1 as $gen){
            //     echo $gen;
            // }
            // echo '><';
            // foreach($offspring2 as $gen){
            //     echo $gen;
            // }
            // echo '<br>';
            $offsprings[] = $offspring1;
            $offsprings[] = $offspring2;
        }
        return $offsprings;
    }
}


class Randomizer
{
    static function getRandomIndexOfGen(){
        return rand(0, (new Individu)->countNumberOfGen - 1);
    }

    static function getRandomIndexOfIndividu(){
        return rand(0, Parameters::POPULATION_SIZE - 1);
    }
} 

class Mutation
{
    function __construct($population)
    {
        $this->population = $population;
    }

    function calculateMutationRate()
    {
        return 1 / (new Individu())->countNumberOfGen();
    }

    function calculateNumOfMutation()
    {
        return round($this->calculateMutationRate() * Parameters::POPULATION_SIZE);
    }

    function isMutation()
    {
        if ($this->calculateNumOfMutation() > 0){
            return TRUE;
        }
    }

    function generateMutation($valueOfGen)
    {
        if ($valueOfGen === 0){
            return -1;
        } else {
            return 0;
        }
    }

    function mutation()
    {
        if($this->isMutation()){
            for ($i=0; $i <= $this->calculateNumOfMutation()-1; $i++) {
                $indexOfIndividu = Randomizer::getRandomIndexOfIndividu();
                $indexOfGen = Randomizer::getRandomIndexOfGen();
                $selectedIndividu = $this->population[$indexOfIndividu];

                echo 'Before mutation: ';
                print_r($selectedIndividu);
                echo '<br>';
                $valueOfGen = $selectedIndividu[$indexOfGen];
                $mutatedGen = $this->generateMutation($valueOfGen);
                $selectedIndividu[$indexOfGen] = $mutatedGen;
                echo 'After mutation: ';
                print_r($selectedIndividu);
                $ret[] = $selectedIndividu;
            } 
            return $ret;
        }
    }
}

$initalPopulation = new Population;
$population = $initalPopulation->creatteRandomPopulation();

//$fitness = new Fitness;
//$fitness->fitnessPopulation(InitialPopulation);

$crossover=new Crossover($population);
$crossoverOffsprings = $crossover->crossover();

echo 'Crossover offsprings: <br>';
print_r($crossoverOffsprings);
echo '<p></p>';
//(new Mutation())->mutation();
$mutation = new Mutation($population);
if ($mutation->mutation()){
    $mutationOffsprings = $mutation->mutation();
    echo 'Mutation offsprings<br>';
    print_r($mutationOffsprings);
    echo '<p></p>';
    foreach ($mutationOffsprings AS $mutationOffsprings){
        $crossoverOffsprings[] = $mutationOffsprings;
    }
}
echo 'Mutation offsprings <br>';
print_r($crossoverOffsprings);
//$individu = new Individu;
//print_r($individu->createRandomIndividu());