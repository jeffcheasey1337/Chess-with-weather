<?php
$apiKey = 'a8f09c4234de105a7ab90a1e6315f7ac'; // Замените 'YOUR_API_KEY' на ваш ключ API OpenWeatherMap
$city = $_GET['city'];
$url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

$response = file_get_contents($url);
$data = json_decode($response);

header('Content-Type: application/json');
echo json_encode($data);
?>
