<?php
// Funktion zum Abrufen der Wetterdaten
function getWeatherData($city) {
    $apiKey = '6c1db3aabafab352c05cb6e3674f8ab4'; // OpenWeatherMap API-Schlüssel
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

    $response = file_get_contents($apiUrl);
    return $response;
}

// Funktion zum Abrufen des Stadtbildes
function getCityImage($city) {
    $unsplashApiKey = 'NunEEgZRZSR3cb4LXJI4vO4_u73MrapIi9C99D784Hg'; // Unsplash API-Schlüssel
    $unsplashApiUrl = "https://api.unsplash.com/search/photos?query={$city}&client_id={$unsplashApiKey}";

    $response = file_get_contents($unsplashApiUrl);
    return $response;
}

// Validierung der Eingabe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';

    if (empty($city)) {
        echo json_encode(['error' => 'Bitte geben Sie eine Stadt ein.']);
        exit;
    }

    // Abrufen der Wetterdaten
    $weatherData = getWeatherData($city);
    if (!$weatherData) {
        echo json_encode(['error' => 'Fehler beim Abrufen der Wetterdaten.']);
        exit;
    }

    // Abrufen des Stadtbildes
    $cityImageData = getCityImage($city);
    if (!$cityImageData) {
        echo json_encode(['error' => 'Fehler beim Abrufen des Stadtbildes.']);
        exit;
    }

    // Zusammenstellen der Antwort
    $response = [
        'weather' => json_decode($weatherData, true),
        'image' => json_decode($cityImageData, true)
    ];

    echo json_encode($response);
    exit;
} else {
    echo json_encode(['error' => 'Ungültige Anfrage.']);
    exit;
}
