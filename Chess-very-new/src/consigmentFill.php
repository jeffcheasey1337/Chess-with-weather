<?php
$whitePlayerProfileID = $_POST['whitePlayerProfileID'];
$blackPlayerProfileID = $_POST['blackPlayerProfileID'];
$result = $_POST['result']; 
$time = $_POST['time']; 
$movesNumber = $_POST['movesNumber']; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO Сonsignments (white, black, result, time, moves_number) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iissi", $whitePlayerProfileID, $blackPlayerProfileID, $result, $time, $movesNumber);

if ($stmt->execute()) {
    echo "Результат партии успешно сохранен в базе данных";
} else {
    echo "Ошибка при сохранении результата партии: " . $stmt->error;
}

?>
