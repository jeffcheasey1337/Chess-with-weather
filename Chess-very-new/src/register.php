<?php
require_once("connection.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) &&
        !empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {

        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $salt = uniqid(mt_rand(), true);

        $combinedPassword = $password . $salt;
        $hashedPassword = password_hash($combinedPassword, PASSWORD_DEFAULT);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO Users (nickname, password_Hash, salt, UID, email, creation_time) VALUES (?, ?, ?, NULL, ?, CURRENT_TIMESTAMP)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $salt, $email);

        if ($stmt->execute()) {
            $userid = $stmt->insert_id;
            $stmtProfile = $conn->prepare("INSERT INTO Profiles (avatar, rating) VALUES ('default_avatar.png', 1000)");

            if ($stmtProfile->execute()) {
                $profileid = $stmtProfile->insert_id;

                $updateStmt = $conn->prepare("UPDATE Users SET profileid = ? WHERE ID = ?");
                $updateStmt->bind_param("ii", $profileid, $userid);
                if($updateStmt->execute()){
                    $_SESSION['userid'] = $userid;
                    $_SESSION['profileid'] = $profileid; 
                    header("Location: /pages/profile.php");
                    exit();
                }
                $updateStmt->close();
            } else {
            header("Location: /pages/registration.html");
            exit();
            }

            $stmtProfile->close(); 
    } else {
            echo "Ошибка при создании аккаунта: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Пожалуйста, заполните все поля формы.";
    }
}
?>
