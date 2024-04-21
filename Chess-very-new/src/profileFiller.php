<?php
session_start();
require_once("connection.php");

if(isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];

    
    $sql = "SELECT Users.nickname, Users.email, Profiles.avatar, Profiles.rating 
            FROM Users 
            INNER JOIN Profiles ON Users.profileID = Profiles.ID 
            WHERE Users.ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        
        $nickname = htmlspecialchars($row["nickname"]);
        $email = htmlspecialchars($row["email"]);
        $avatar = htmlspecialchars($row["avatar"]);
        $rating = htmlspecialchars($row["rating"]);

        echo "<div class='profile-info'> 
                <img src='/img/image/$avatar' alt='Avatar' /> 
                <h2>$nickname</h2> 
                <p>Email: $email</p> 
                <p>Rating: $rating</p> 
            </div>";
    } else {
        echo "Пользователь не найден";
    }

    $stmt->close();
} else {
    echo "Пользователь не авторизован";
}
?>
