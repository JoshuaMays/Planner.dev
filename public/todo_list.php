<!DOCTYPE html>
<html>
<head>
	<title>Todo List</title>
	<link rel="stylesheet" href="/css/styles.css">
</head>
<body>
	<div id="container">
		<h1>$_GET</h1>
		<?php var_dump($_GET); ?>

		<h1>$_POST</h1>
		<?php var_dump($_POST); ?>

		<h1 id="todo">Todo List!</h1>

		<ul>
			<li>Make Sure Wearing Helmet.</li>
			<li>Buckle Up.</li>
			<li>Press Eject Button.</li>
			<li>Hold On To Yer Butts!</li>
		</ul>
		<div id="form">
			<h2>Add a Task to the List</h2>
			<form method="POST" action="/todo_list.php">
				<input type="text" name="add_item" id="add_item" placeholder="fill me out, yo.">
				
				<input type="submit" value="Put me in, Coach">
			</form>
		</div>
	</div>
</body>
</html>