<?php

include './../curl-8.17.0/include/curl/curl.h';
// URL de l'API (à adapter selon ce que tu vois dans Swagger)
$apiUrl = "https://api.prix-carburants.2aaz.fr/stations";

// --- 1. Appel API ---

$ch = curl_init('https://api.prix-carburants.2aaz.fr/fuel/1/price/2024?responseFields=PriceTTC&key=SAE');
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);

if(curl_errno($ch)){
    echo "Erreur cURL : " . curl_error($ch);
    exit;
}

curl_close($ch);

// --- 2. Décodage JSON ---
$data = json_decode($response, true);

if (!$data) {
    echo "Erreur : réponse JSON invalide";
    exit;
}

// --- 3. Extraction des carburants et de leurs prix ---
echo "<h2>Liste des carburants et leurs prix</h2>";

foreach ($data as $station) {
    if (!isset($station['fuels'])) continue;

    echo "<h3>Station : " . htmlspecialchars($station['name'] ?? 'Sans nom') . "</h3>";
    
    foreach ($station['fuels'] as $fuel) {
        $fuelName = $fuel['name'] ?? "Carburant inconnu";
        $fuelPrice = $fuel['price'] ?? "N/A";
        $dateMaj = $fuel['updated'] ?? "Date inconnue";

        echo "- <b>$fuelName</b> : $fuelPrice € (maj : $dateMaj)<br>";
    }

    echo "<br>";
}
