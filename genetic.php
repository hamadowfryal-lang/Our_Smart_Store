<?php

function fetchInventory() {
    $inventory = [];
    $filePath = "HW__Data_S25.csv";
    if (file_exists($filePath)) {
        $fileHandle = fopen($filePath, "r");
        fgetcsv($fileHandle); 
        while ($row = fgetcsv($fileHandle)) {
            $inventory[] = [
                'id'    => $row[0],
                'name'  => $row[1],
                'price' => $row[2],
                'cat'   => $row[3] 
            ];
        }
        fclose($fileHandle);
    }
    return $inventory;
}

function manageUserSession($firstName, $lastName, $age, $location, $gender) {
    $filePath = "users.csv";
    if (!file_exists($filePath)) {
        $fileHandle = fopen($filePath, "w");
        fputcsv($fileHandle, ['user_id', 'age', 'location', 'gender', 'first_name', 'last_name']);
        fclose($fileHandle);
    }
    $existingUsers = array_map('str_getcsv', file($filePath));
    foreach ($existingUsers as $user) {
        if (isset($user[4]) && $user[4] == $firstName && $user[5] == $lastName) {
            return $user[0];
        }
    }
    $newId = count($existingUsers) + 100;
    $fileHandle = fopen($filePath, "a");
    fputcsv($fileHandle, [$newId, $age, $location, $gender, $firstName, $lastName]);
    fclose($fileHandle);
    return $newId;
}


function nqat($productId) { 
    $totalScore = 0;
    if (file_exists("behavior.csv")) {
        foreach (file("behavior.csv") as $entry) {
            $data = explode(",", trim($entry));
            if ($data[0] == $productId) {
                if ($data[1] == 'purchased') $totalScore += 100;
                if ($data[1] == 'cart')      $totalScore += 50;  
                if ($data[1] == 'click')     $totalScore += 10; 
            }
        }
    }
    if (file_exists("ratings.csv")) {
        foreach (file("ratings.csv") as $entry) {
            $data = explode(",", trim($entry));
            if ($data[0] == $productId) {
                $totalScore += ((int)$data[1] * 20);
            }
        }
    }
    return $totalScore;
}


function calculateSetFitness($recommendationSet) {
    $score = 0;
    foreach ($recommendationSet as $item) {
        $score += nqat($item['id']);
    }
    return $score;
}

function generateInitialPopulation($inventory, $popSize = 6) {
    $pop = [];
    for ($i = 0; $i < $popSize; $i++) {
        shuffle($inventory);
        $pop[] = array_slice($inventory, 0, 7);
    }
    return $pop;
}

function crossoverParents($parentOne, $parentTwo) {
    return array_merge(array_slice($parentOne, 0, 3), array_slice($parentTwo, 3, 4));
}

function getGeneticRecommendations($inventory) {
    if (empty($inventory)) return [];
    $currentPopulation = generateInitialPopulation($inventory);

    for ($generation = 0; $generation < 5; $generation++) {
        usort($currentPopulation, function($a, $b) {
            return calculateSetFitness($b) - calculateSetFitness($a);
        });
        $bestSolutions = array_slice($currentPopulation, 0, 2);
        $nextGeneration = $bestSolutions;
        while (count($nextGeneration) < 6) {
            $child = crossoverParents($bestSolutions[0], $bestSolutions[1]);
            if (rand(0, 100) < 15) {
                $child[rand(0, 6)] = $inventory[array_rand($inventory)];
            }
            $nextGeneration[] = $child;
        }
        $currentPopulation = $nextGeneration;
    }
    usort($currentPopulation, function($a, $b) {
        return calculateSetFitness($b) - calculateSetFitness($a);
    });
    return array_column($currentPopulation[0], 'id');
}

$products = fetchInventory(); 

function afdal_she($all_products) {
    return getGeneticRecommendations($all_products);
}

function login_or_reg($fn, $ln, $age, $city, $gen) {
    return manageUserSession($fn, $ln, $age, $city, $gen);
}
?>