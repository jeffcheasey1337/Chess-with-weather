<?php
require_once('engine/chess_game.php');
session_start();
$human_color =$_POST['human_color']; 
$userID = $_SESSION['userid'];
$blackID = null;
$whiteID = null;
if(isset($_SESSION['userid'])) {
    if( json_encode($human_color) == COLOR_BLACK){
        $blackID = $userID;
    }
    else{
        $whiteID = $userID;
    }
}
$game = new ChessGame();
$game->createNewGame($human_color, $whiteID, $blackID);
$data = $game->getClientJsonGameState();
header('Content-Type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE);