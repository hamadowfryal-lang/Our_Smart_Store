<?php

function fetchInventory() {
    $inventory = [];
    $filePath = "HW__Data_S25.csv";
    if (file_exists($filePath)) {
        $fileHandle = fopen($filePath, "r");
        fgetcsv($fileHandle); 
        while ($row = fgetcsv($fileHandle)) {
            if (empty($row[0])) continue; 
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

function nqat($productId, $u_id) { 
    $totalScore = 0;
    
    if (file_exists("behavior.csv")) {
        $h = fopen("behavior.csv", "r");
        while (($data = fgetcsv($h)) !== FALSE) {
            if (isset($data[3]) && $data[3] == $u_id && $data[0] == $productId) {
                if ($data[1] == 'purchased') $totalScore += 100;
                if ($data[1] == 'cart')      $totalScore += 50;  
                if ($data[1] == 'click')     $totalScore += 10; 
            }
        }
        fclose($h);
    }
    
    if (file_exists("ratings.csv")) {
        $h = fopen("ratings.csv", "r");
        while (($data = fgetcsv($h)) !== FALSE) {
            if (isset($data[3]) && $data[3] == $u_id && $data[0] == $productId) {
                $totalScore += ((int)$data[1] * 20);
            }
        }
        fclose($h);
    }
    return $totalScore;
}

function calculateSetFitness($recommendationSet, $u_id) {
    $score = 0;
    foreach ($recommendationSet as $item) {
        $score += nqat($item['id'], $u_id);
    }
    return $score;
}

function getGeneticRecommendations($inventory, $u_id) {
    if (empty($inventory)) return [];
    
    $currentPopulation = [];
    for ($i = 0; $i < 6; $i++) {
        shuffle($inventory);
        $currentPopulation[] = array_slice($inventory, 0, 7);
    }

    for ($generation = 0; $generation < 5; $generation++) {
        usort($currentPopulation, function($a, $b) use ($u_id) {
            return calculateSetFitness($b, $u_id) - calculateSetFitness($a, $u_id);
        });

        $bestSolutions = array_slice($currentPopulation, 0, 2);
        $nextGeneration = $bestSolutions;
        
        while (count($nextGeneration) < 6) {
            $child = array_merge(array_slice($bestSolutions[0], 0, 3), array_slice($bestSolutions[1], 3, 4));
            if (rand(0, 100) < 15) {
                $child[rand(0, 6)] = $inventory[array_rand($inventory)];
            }
            $nextGeneration[] = $child;
        }
        $currentPopulation = $nextGeneration;
    }

    usort($currentPopulation, function($a, $b) use ($u_id) {
        return calculateSetFitness($b, $u_id) - calculateSetFitness($a, $u_id);
    });

    return array_column($currentPopulation[0], 'id');
}

$products = fetchInventory(); 

function afdal_she($all_products, $u_id) {
    return getGeneticRecommendations($all_products, $u_id);
}

function login_or_reg($fn, $ln, $age, $city, $gen) {
    return manageUserSession($fn, $ln, $age, $city, $gen);
}
?>
