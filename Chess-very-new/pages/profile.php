<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link
			rel="stylesheet"
			href="/pages/pages.css"
			type="text/css"
			media="all"
		/>
		<title>Profile</title>
	</head>
	<body>
		<div class="container">
		<?php 
			include 'E:/ospanel/domains/Chess-very-new/src/profileFiller.php'; 
		?>
			<h3>Таблица игр</h3>
			<table>
				<thead>
					<tr>
						<th>Цвет</th>
						<th>Результат</th>
						<th>Кол-во ходов</th>
						<th>Время начала</th>
						<th>Противник</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'E:/ospanel/domains/Chess-very-new/src/tableFiller.php';
					?>
				</tbody>
			</table>
			<div>
				<a href="/game.php"> Играть </a>
			</div>
		</div>
		
	</body>
</html>
