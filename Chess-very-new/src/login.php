<?php
require_once("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginNickname = $_POST["username"];
    $loginPassword = $_POST["password"];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT ID, nickname, password_Hash, salt, profileid FROM Users WHERE nickname = ?");
    $stmt->bind_param("s", $loginNickname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password_Hash'];
        $salt = $row['salt'];
        $profileid = $row['profileid'];
        $userid = $row['ID'];

        $combinedPassword = $loginPassword . $salt;
        if (password_verify($combinedPassword, $hashedPassword)) {
            session_start();
            $_SESSION['userid'] = $userid;
            $_SESSION['profileid'] = $profileid; 
            
            echo($_SESSION['profileid']);
            header("Location: /pages/profile.php");
            exit();
        } else {
            echo "Неверное имя пользователя или пароль.";
        }
    } else {
        echo "Неверное имя пользователя или пароль.";
    }

    $stmt->close();
}
?>
