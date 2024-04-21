<?php
require_once("connection.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    -- Создание таблицы Profiles
    CREATE TABLE IF NOT EXISTS Profiles (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        avatar VARCHAR(255),
        rating FLOAT DEFAULT 0
    );
    -- Создание таблицы Users
    CREATE TABLE IF NOT EXISTS Users (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        nickname VARCHAR(50) NOT NULL,
        password_Hash VARCHAR(255) NOT NULL,
        salt VARCHAR(50) NOT NULL,
        UID VARCHAR(50),
        email VARCHAR(100) UNIQUE NOT NULL,
        creation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        profileID INT,
        FOREIGN KEY (profileID) REFERENCES Profiles(ID)
    );


    -- Создание таблицы Consignments
    CREATE TABLE IF NOT EXISTS Consignments (
        ID INT AUTO_INCREMENT PRIMARY KEY,
        white INT,
        black INT,
        result VARCHAR(50),
        time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        moves_number INT,
        FOREIGN KEY (white) REFERENCES Profiles(ID),
        FOREIGN KEY (black) REFERENCES Profiles(ID)
    );
";

if ($conn->multi_query($sql) === TRUE) {
    echo "Таблицы успешно созданы";
} else {
    echo "Ошибка при создании таблиц: " . $conn->error;
}

$conn->close();
?>
