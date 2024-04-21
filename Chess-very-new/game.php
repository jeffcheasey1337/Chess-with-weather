<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Программируем шахматы</title>
		<link rel="stylesheet" href="/chess.css" type="text/css" media="all" />
		<script type="text/javascript" src="/chess.js"></script>
	</head>
	<body>
		<div class="container">
		<div class="control">
			Начать новую игру:
			<a href="#" class="new_game white">за белых</a>
			<a href="#" class="new_game black">за чёрных</a>
			<a href="#" class="new_game gray">вдвоем за доской</a>
		</div>
		<div class="game_status">
			<?php 
			session_start();
			setcookie("userid", $_SESSION['userid'], time() + 3600, "/"); ?>
			Статус игры: <span class="status">Игра не начата</span>
		</div>
		<div class="board"></div>
		</div>
	</body>
</html>
